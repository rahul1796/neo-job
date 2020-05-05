ALTER TABLE neo.neo_batches
  ADD COLUMN is_active BOOLEAN DEFAULT TRUE;
  
  -------------------------------------------------
  ALTER TABLE neo.candidates
  ADD COLUMN is_active BOOLEAN DEFAULT TRUE;  
  
  ----------------------------------------------
  
  CREATE VIEW users.vw_batches_list (
    id,
    batch_code,
    batch_type,
    customer_name,
    center_name,
    course_name,
    course_code,
    buisness_unit,
    trainer_email,
    batch_created_date,
    created_by,
    is_active,
    batch_start_date,
    batch_end_date,
    qp_name,
    batch_size)
AS
 WITH bc AS (
SELECT cb.batch_code,
            count(DISTINCT cb.candidate_id)::integer AS batch_size
FROM neo.candidate_qp_details cb
GROUP BY cb.batch_code
        )
    SELECT b.id,
    btrim(b.batch_code) AS batch_code,
    b.batch_type,
    b.customer_name,
    b.center_name,
    b.course_name,
    b.course_code,
    b.business_unit AS buisness_unit,
    b.trainer_email,
    b.batch_created_date,
    b.created_by,
    b.is_active,
    b.batch_start_date,
    b.batch_end_date,
        CASE COALESCE(b.qp_name, ''::text)
            WHEN ''::text THEN '-NA-'::text
            ELSE format('%s (%s)'::text, b.qp_name, b.qp_code)
        END AS qp_name,
    COALESCE(bc.batch_size, 0) AS batch_size
    FROM neo.neo_batches b
     LEFT JOIN bc ON upper(btrim(bc.batch_code)) = upper(btrim(b.batch_code))
    ORDER BY (btrim(b.batch_code));

ALTER VIEW users.vw_batches_list
  OWNER TO postgres;
  
  ----------------------------------------------------
  
  CREATE OR REPLACE FUNCTION users.fn_get_candidate_data (
  i_qualification_pack_id integer = 0,
  i_education_id integer = 0,
  i_search_column_index integer = 0,
  i_search_text text = ''::text,
  i_limit integer = 25,
  i_offset integer = 0
)
RETURNS TABLE (
  id integer,
  candidate_name text,
  mobile_number text,
  email text,
  gender_id integer,
  gender_name text,
  dob text,
  total_experience text,
  state_id integer,
  state_name text,
  district_id integer,
  district_name text,
  expected_relocate_salary text,
  education_ids integer [],
  education text,
  source_application text,
  qualification_pack_ids integer [],
  qualification_packs text,
  created_date text,
  mt_type text,
  aadhaar_number text,
  source_name text,
  course_name text,
  batch_code text,
  center_name text,
  location text,
  company_name text,
  skill_name text,
  candidate_enrollment_id text,
  igs_customer_name text,
  igs_contract_id text,
  is_active boolean
) AS
$body$
DECLARE

 

BEGIN
    i_search_text = TRIM(i_search_text);

 

    RETURN QUERY
        WITH EDU AS
        (
            SELECT    CED.candidate_id,
                      array_agg(CED.education_id) AS education_ids,
                      STRING_AGG(E.name,',') AS education
            FROM       neo.candidate_education_details AS CED
            LEFT JOIN neo_master.educations AS E ON E.id=CED.education_id
            GROUP BY  CED.candidate_id
        ),
        QP AS
        (
            SELECT      CQD.candidate_id,
                         array_agg(CQD.qualification_pack_id) AS qualification_pack_ids,
                        STRING_AGG(CQD.course_name,',') AS course_name,
                         STRING_AGG(CQD.center_name,',') AS center_name,
                        STRING_AGG(CQD.batch_code,',') AS batch_code,
                         STRING_AGG((CASE COALESCE(QP.name,'') WHEN '' THEN '-NA-' ELSE FORMAT('%s (%s)',QP.name,QP.code) END),',') AS qualification_packs
            FROM          neo.candidate_qp_details AS CQD
            LEFT JOIN     neo_master.qualification_packs AS QP ON QP.id=CQD.qualification_pack_id
            GROUP BY     CQD.candidate_id
        ),
        EXP AS 
        (
            SELECT      CED.candidate_id,
                        STRING_AGG(CED.company_name,',') AS company_name,
                        STRING_AGG(CED.location,',') AS location,
                         sum(CED."to"::integer - CED."from"::integer)::TEXT AS total_experience
            FROM          neo.candidate_employment_details AS CED
            GROUP BY     CED.candidate_id
        ),
        CSD AS
        (
                SELECT      SD.candidate_id,
                            STRING_AGG(SD.skill_name,',') AS skill_name
                FROM     neo.candidate_skill_details AS SD
                GROUP BY    SD.candidate_id
        )
        SELECT         c.id,
                    c.candidate_name,
                    c.mobile AS mobile_number,
                    c.email,
                    c.gender_id,
                    g.name AS gender_name,
                    c.date_of_birth AS dob,
                    EXP.total_experience,
                    st.id AS state_id,
                    st.name AS state_name,
                    dt.id AS district_id,
                    dt.name AS district_name, 
                    c.expected_salary_percentage::TEXT AS expected_relocate_salary,
                    EDU.education_ids,
                    EDU.education,                   
                    c.source_name AS source_application,  
                    QP.qualification_pack_ids,
                    QP.qualification_packs,
                    to_char(c.created_at, 'dd-Mon-yyyy'::text) AS created_date,
                    C.mt_type,
                    CASE 
                        WHEN COALESCE(NULLIF(c.aadhaar_number,''),'')<>'' THEN OVERLAY(c.aadhaar_number PLACING 'XXXXXXXX' FROM 1 FOR 8)
                        ELSE 'NA'
                    END,                   
                    cs.name AS source_name,
                    QP.course_name,
                    QP.batch_code,
                    QP.center_name,
                    EXP.location,
                    EXP.company_name,
                    CSD.skill_name,
                    C.candidate_enrollment_id,
                    CB.customer_name AS igs_customer_name,
                    CB.contract_id AS igs_contract_id,
                    c.is_active              
        FROM        neo.candidates c
        LEFT JOIN   neo_master.genders g ON g.id=c.gender_id
        LEFT JOIN   EDU ON EDU.candidate_id=C.id
        LEFT JOIN   QP ON QP.candidate_id=C.id
        LEFT JOIN   EXP ON EXP.candidate_id=C.id
        LEFT JOIN   neo.neo_batches AS CB ON CB.batch_code=C.batch_code
        LEFT JOIN   neo_master.districts dt ON dt.id = c.district_id
        LEFT JOIN   neo_master.states st ON st.id = dt.state_id
        LEFT JOIN   neo_master.candidate_sources cs ON cs.id=c.source_id
        LEFT JOIN   CSD ON csd.candidate_id=c.id
        WHERE       (COALESCE(i_qualification_pack_id,0) < 1 OR i_qualification_pack_id=ANY(QP.qualification_pack_ids))
        AND         (COALESCE(i_education_id,0) < 1 OR i_education_id=ANY(EDU.education_ids))       
        AND            (
                        (i_search_column_index < 1)
                        OR 
                        (i_search_text = '')
                        OR
                        (
                            i_search_text <> '' 
                            AND 
                            (
                                (i_search_column_index = 1 AND C.id::TEXT ~* i_search_text)
                                OR
                                (i_search_column_index = 2 AND C.candidate_name ~* i_search_text)
                                 OR
                                (i_search_column_index = 3 AND C.email ~* i_search_text)
                                 OR
                                (i_search_column_index = 4 AND C.mobile ~* i_search_text)
                                 OR
                                (i_search_column_index = 5 AND QP.batch_code ~* i_search_text)
                                 OR
                                (i_search_column_index = 6 AND QP.center_name ~* i_search_text)
                                OR
                                (i_search_column_index = 7 AND c.candidate_enrollment_id ~* i_search_text)
                                OR
                                (i_search_column_index = 8 AND c.aadhaar_number ~* i_search_text)
                                OR
                                (i_search_column_index = 9 AND cs.name ~* i_search_text)
                                OR
                                (i_search_column_index = 10 AND EXP.company_name ~* i_search_text)
                                OR
                                (i_search_column_index = 11 AND EXP.location ~* i_search_text)
                                OR
                                (i_search_column_index = 12 AND CSD.skill_name ~* i_search_text)
                                OR
                                (i_search_column_index = 13 AND QP.course_name ~* i_search_text)
                            )
                        )
                    )
        ORDER BY      1 DESC
        LIMIT       i_limit
        OFFSET      i_offset;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION users.fn_get_candidate_data (i_qualification_pack_id integer, i_education_id integer, i_search_column_index integer, i_search_text text, i_limit integer, i_offset integer)
  OWNER TO postgres;
  
  ---------------------------------------------------
  
  CREATE VIEW neo_job.vw_job_list (
    id,
    job_title,
    job_code,
    job_description,
    no_of_position,
    customer_id,
    customer_name,
    customer_manager,
    qualification_pack_id,
    qualification_pack_name,
    applicable_consulting_fee,
    business_vertical_id,
    business_vertical_name,
    practice,
    office_location,
    key_skills,
    functional_area_id,
    functional_area_name,
    industry_id,
    industry_name,
    primary_skills,
    reference_id,
    education_id,
    education_name,
    job_open_type_id,
    job_open_type_name,
    job_location,
    age_from,
    age_to,
    job_priority_level_id,
    job_priority_level_name,
    experience_from,
    experience_to,
    relevent_experience_from,
    relevant_experience_to,
    offered_ctc_from,
    offered_ctc_to,
    shifts_available,
    preferred_nationality,
    gender_id,
    gender_name,
    remarks,
    comments,
    target_employers,
    no_poach_companies,
    job_status_id,
    job_status_name,
    job_expiry_text,
    job_expiry_date,
    created_user_id,
    created_user_name,
    created_at,
    applied_candidate_count,
    recommended_candidate_count,
    joined_candidates,
    recruiter_ids,
    placement_officer_ids,
    assigned_user_ids,
    placement_officer_names,
    recruiter_names,
    no_of_vacancies)
AS
 WITH cj AS (
SELECT cjb.job_id,
            count(cjb.candidate_id) AS applied_candidate_count
FROM neo_job.candidates_jobs cjb
             LEFT JOIN neo.candidates c ON c.id = cjb.candidate_id
             LEFT JOIN neo.neo_batches nb ON nb.batch_code = c.batch_code
WHERE (cjb.candidate_status_id <> ALL (ARRAY[15, 17])) AND
    COALESCE(c.is_active, true) AND COALESCE(nb.is_active, true)
GROUP BY cjb.job_id
        ), rc AS (
    SELECT jb.id AS job_id,
            count(DISTINCT cqp.candidate_id) AS recommended_candidate_count
    FROM neo_job.jobs jb
             LEFT JOIN neo.candidate_qp_details cqp ON
                 cqp.qualification_pack_id = jb.qualification_pack_id
             JOIN neo.candidates can ON can.id = cqp.candidate_id
             JOIN neo.neo_batches nb ON nb.batch_code = can.batch_code
    WHERE jb.job_status_id = 2 AND COALESCE(can.is_active, true) AND
        COALESCE(nb.is_active, true) AND NOT (EXISTS (
        SELECT canj.id
        FROM neo_job.candidates_jobs canj
        WHERE canj.job_id = jb.id AND canj.candidate_id = cqp.candidate_id
        LIMIT 1
        ))
    GROUP BY jb.id
    ), jc AS (
    SELECT cp.job_id,
            count(cp.candidate_id) AS joined_candidates
    FROM neo_job.candidate_placement cp
             JOIN neo_job.candidates_jobs cnj ON cnj.candidate_id =
                 cp.candidate_id AND cnj.job_id = cp.job_id
             JOIN neo.candidates cn ON cn.id = cp.candidate_id
             LEFT JOIN neo.neo_batches nb ON nb.batch_code = cn.batch_code
    WHERE (cnj.candidate_status_id = ANY (ARRAY[15, 17])) AND
        COALESCE(cn.is_active, true) AND COALESCE(nb.is_active, true)
    GROUP BY cp.job_id
    )
    SELECT j.id,
    j.job_title,
    j.neo_job_code AS job_code,
    j.job_description,
    j.no_of_position,
    j.customer_id,
    COALESCE(cust.company_name, j.customer_name) AS customer_name,
    j.customer_manager,
    j.qualification_pack_id,
        CASE COALESCE(qp.name, ''::text)
            WHEN ''::text THEN '-NA-'::text
            ELSE format('%s (%s)'::text, qp.name, qp.code)
        END AS qualification_pack_name,
    j.applicable_consulting_fee,
    j.business_vertical_id,
    bv.name AS business_vertical_name,
    j.practice,
    j.office_location,
    j.key_skills,
    j.functional_area_id,
    fa.name AS functional_area_name,
    j.industry_id,
    ind.name AS industry_name,
    j.primary_skills,
    j.reference_id,
    j.education_id,
    edu.name AS education_name,
    j.job_open_type_id,
    jot.name AS job_open_type_name,
    j.job_location,
    j.age_from,
    j.age_to,
    j.job_priority_level_id,
    jpl.name AS job_priority_level_name,
    j.experience_from,
    j.experience_to,
    j.relevent_experience_from,
    j.relevant_experience_to,
    j.offered_ctc_from,
    j.offered_ctc_to,
    j.shifts_available,
    j.preferred_nationality,
    j.gender_id,
    g.name AS gender_name,
    j.remarks,
    j.comments,
    j.target_employers,
    j.no_poach_companies,
    j.job_status_id,
    js.name AS job_status_name,
        CASE
            WHEN j.job_expiry_date < now()::date THEN
                format('Expired on <font color="red">%s</font>'::text, to_char(j.job_expiry_date::timestamp with time zone, 'dd-Mon-yyyy'::text))
            ELSE format('Expires on <font color="red">%s</font>'::text,
                COALESCE(to_char(j.job_expiry_date::timestamp with time zone, 'dd-Mon-yyyy'::text), 'N/A'::text))
        END AS job_expiry_text,
    to_char(j.job_expiry_date::timestamp with time zone, 'dd-Mon-yyyy'::text)
        AS job_expiry_date,
    j.created_by AS created_user_id,
        CASE acc.id
            WHEN 18 THEN bde.name
            WHEN 19 THEN rse.name
            ELSE ''::text
        END AS created_user_name,
    to_char(j.created_at, 'dd-Mon-yyyy HH12:MI:SS AM'::text) AS created_at,
    COALESCE(cj.applied_candidate_count, 0::bigint) AS applied_candidate_count,
    COALESCE(rc.recommended_candidate_count, 0::bigint) AS recommended_candidate_count,
    COALESCE(jc.joined_candidates, 0::bigint) AS joined_candidates,
    (
        SELECT array_agg(ju.user_id) AS array_agg
        FROM neo_job.jobs_users ju
        WHERE ju.job_id = j.id AND ju.user_type = 'Recruiter'::text
        ) AS recruiter_ids,
    (
        SELECT array_agg(ju.user_id) AS array_agg
        FROM neo_job.jobs_users ju
        WHERE ju.job_id = j.id AND ju.user_type = 'Placement Officer'::text
        ) AS placement_officer_ids,
    (
        SELECT array_agg(ju.user_id) AS array_agg
        FROM neo_job.jobs_users ju
        WHERE ju.job_id = j.id
        ) AS assigned_user_ids,
    (
        SELECT string_agg(u.name, ', '::text) AS string_agg
        FROM neo_job.jobs_users ju
             JOIN neo_user.users u ON u.id = ju.user_id
        WHERE ju.job_id = j.id AND ju.user_type = 'Placement Officer'::text
        ) AS placement_officer_names,
    (
        SELECT string_agg(u.name, ', '::text) AS string_agg
        FROM neo_job.jobs_users ju
             JOIN neo_user.users u ON u.id = ju.user_id
        WHERE ju.job_id = j.id AND ju.user_type = 'Recruiter'::text
        ) AS recruiter_names,
        CASE
            WHEN jc.joined_candidates > j.no_of_position::bigint THEN 0::bigint
            ELSE j.no_of_position::bigint - COALESCE(jc.joined_candidates, 0::bigint)
        END AS no_of_vacancies
    FROM neo_job.jobs j
     LEFT JOIN neo_customer.companies cust ON cust.id = j.customer_id
     LEFT JOIN neo_master.business_verticals bv ON bv.id = j.business_vertical_id
     LEFT JOIN neo_master.functional_areas fa ON fa.id = j.functional_area_id
     LEFT JOIN neo_master.industries ind ON ind.id = j.industry_id
     LEFT JOIN neo_master.educations edu ON edu.id = j.education_id
     LEFT JOIN neo_master.job_open_types jot ON jot.id = j.job_open_type_id
     LEFT JOIN neo_master.job_priority_levels jpl ON jpl.id = j.job_priority_level_id
     LEFT JOIN neo_master.genders g ON g.id = j.gender_id
     LEFT JOIN neo_master.job_statuses js ON js.id = j.job_status_id
     LEFT JOIN neo_master.qualification_packs qp ON qp.id = j.qualification_pack_id
     LEFT JOIN users.accounts acc ON acc.id = j.created_by
     LEFT JOIN users.bd_executive bde ON bde.user_id = acc.id
     LEFT JOIN users.rs_executive rse ON rse.user_id = acc.id
     LEFT JOIN cj ON cj.job_id = j.id
     LEFT JOIN rc ON rc.job_id = j.id
     LEFT JOIN jc ON jc.job_id = j.id;

ALTER VIEW neo_job.vw_job_list
  OWNER TO postgres;
  
  ------------------------------------------------
  
  CREATE OR REPLACE FUNCTION neo_job.fn_get_job_list_data (
  i_user_id integer,
  i_qualification_pack_id integer,
  i_job_status_id integer = 2
)
RETURNS TABLE (
  id integer,
  job_title text,
  job_code text,
  job_description text,
  no_of_position text,
  customer_id integer,
  customer_name text,
  customer_manager text,
  qualification_pack_id integer,
  qualification_pack_name text,
  applicable_consulting_fee integer,
  business_vertical_id integer,
  business_vertical_name text,
  practice text,
  office_location text,
  key_skills text,
  functional_area_id integer,
  functional_area_name text,
  industry_id integer,
  industry_name text,
  primary_skills text,
  reference_id integer,
  education_id integer,
  education_name text,
  job_open_type_id integer,
  job_open_type_name text,
  job_location text,
  age_from integer,
  age_to integer,
  job_priority_level_id integer,
  job_priority_level_name text,
  experience_from integer,
  experience_to integer,
  relevent_experience_from integer,
  relevant_experience_to integer,
  offered_ctc_from integer,
  offered_ctc_to integer,
  shifts_available text,
  preferred_nationality text,
  gender_id integer,
  gender_name text,
  remarks text,
  comments text,
  target_employers text,
  no_poach_companies text,
  job_status_id integer,
  job_status_name text,
  job_expiry_text text,
  job_expiry_date text,
  created_user_id integer,
  created_user_name text,
  created_at text,
  applied_candidate_count bigint,
  recommended_candidate_count bigint,
  joined_candidates bigint,
  recruiter_ids integer [],
  placement_officer_ids integer [],
  assigned_user_ids integer [],
  placement_officer_names text,
  recruiter_names text,
  no_of_vacancies bigint
) AS
$body$
DECLARE
	t_hierarchy_user_id_array INT[] = ARRAY[]::INT[];
	
  
BEGIN
	i_job_status_id := COALESCE(i_job_status_id,2);
    i_qualification_pack_id := COALESCE(i_qualification_pack_id,0);
    i_user_id := COALESCE(i_user_id,-1);

	SELECT 	ARRAY_AGG(user_id) 
    INTO	t_hierarchy_user_id_array
    FROM 	neo_user.fn_get_recursive_team_data(i_user_id);
    
    t_hierarchy_user_id_array := COALESCE(t_hierarchy_user_id_array, ARRAY[i_user_id]);
    
  	RETURN QUERY
    	WITH RES AS
        (
            WITH cj AS 
            (
                SELECT 		CJB.job_id,
                            COUNT(DISTINCT CJB.candidate_id) AS applied_candidate_count
                FROM 		neo_job.candidates_jobs CJB
                LEFT JOIN 	neo.candidates c ON c.id = CJB.candidate_id
                LEFT JOIN 	neo.neo_batches nb ON nb.batch_code = c.batch_code
                WHERE 		CJB.candidate_status_id NOT IN (15,17) 
                AND 		COALESCE(c.is_active, true)
                AND			COALESCE(nb.is_active, true)
                GROUP BY 	CJB.job_id
            ), 
            rc AS 
            (
                SELECT 		JB.id AS job_id,
                            COUNT(DISTINCT cqp.candidate_id) AS recommended_candidate_count
                FROM 		neo_job.jobs AS JB
                LEFT JOIN	neo.candidate_qp_details AS CQP ON CQP.qualification_pack_id=JB.qualification_pack_id
                LEFT JOIN 	neo.candidates AS CAN ON CAN.id = CQP.candidate_id
                LEFT JOIN 	neo.neo_batches AS NB ON NB.batch_code = CAN.batch_code
                WHERE		JB.job_status_id = 2
                AND			COALESCE(CAN.is_active, true)
                AND			COALESCE(NB.is_active, true)
                AND			NOT EXISTS (SELECT CANJ.id 
                                        FROM neo_job.candidates_jobs AS CANJ 
                                        WHERE CANJ.job_id=JB.id 
                                        AND CANJ.candidate_id=CQP.candidate_id
                                        LIMIT 1)	
                GROUP BY	JB.id
            ), 
            jc AS 
            (
                SELECT 		CP.job_id,
                            COUNT(DISTINCT CP.candidate_id) AS joined_candidates
                FROM 		neo_job.candidate_placement AS CP
                INNER JOIN 	neo_job.candidates_jobs AS CNJ ON CNJ.candidate_id = CP.candidate_id AND CNJ.job_id = CP.job_id
                LEFT JOIN 	neo.candidates AS CN ON CN.id = CP.candidate_id
                LEFT JOIN 	neo.neo_batches AS NB ON NB.batch_code = CN.batch_code
                WHERE 		CNJ.candidate_status_id IN (15, 17) 
                AND 		COALESCE(CN.is_active, true)
                AND        	COALESCE(NB.is_active, true)
                GROUP BY 	CP.job_id
            )
            SELECT 		j.id,
                        j.job_title,
                        j.neo_job_code AS job_code,
                        j.job_description,
                        j.no_of_position,
                        j.customer_id,
                        COALESCE(cust.company_name, j.customer_name) AS customer_name,
                        j.customer_manager,
                        j.qualification_pack_id,
                        CASE COALESCE(qp.name, ''::text)
                            WHEN ''::text THEN '-NA-'::text
                            ELSE format('%s (%s)'::text, qp.name, qp.code)
                        END AS qualification_pack_name,
                        j.applicable_consulting_fee,
                        j.business_vertical_id,
                        bv.name AS business_vertical_name,
                        j.practice,
                        j.office_location,
                        j.key_skills,
                        j.functional_area_id,
                        fa.name AS functional_area_name,
                        j.industry_id,
                        ind.name AS industry_name,
                        j.primary_skills,
                        j.reference_id,
                        j.education_id,
                        edu.name AS education_name,
                        j.job_open_type_id,
                        jot.name AS job_open_type_name,
                        j.job_location,
                        j.age_from,
                        j.age_to,
                        j.job_priority_level_id,
                        jpl.name AS job_priority_level_name,
                        j.experience_from,
                        j.experience_to,
                        j.relevent_experience_from,
                        j.relevant_experience_to,
                        j.offered_ctc_from,
                        j.offered_ctc_to,
                        j.shifts_available,
                        j.preferred_nationality,
                        j.gender_id,
                        g.name AS gender_name,
                        j.remarks,
                        j.comments,
                        j.target_employers,
                        j.no_poach_companies,
                        j.job_status_id,
                        js.name AS job_status_name,
                        CASE
                            WHEN j.job_expiry_date < now()::date THEN
                                format('Expired on <font color="red">%s</font>'::text, to_char(j.job_expiry_date::timestamp with time zone, 'dd-Mon-yyyy'::text))
                            ELSE format('Expires on <font color="red">%s</font>'::text,
                                COALESCE(to_char(j.job_expiry_date::timestamp with time zone, 'dd-Mon-yyyy'::text), 'N/A'::text))
                        END AS job_expiry_text,
                        to_char(j.job_expiry_date::timestamp with time zone, 'dd-Mon-yyyy'::text) AS job_expiry_date,
                        j.created_by AS created_user_id,
                        '' AS created_user_name,
                        to_char(j.created_at, 'dd-Mon-yyyy HH12:MI:SS AM'::text) AS created_at,
                        COALESCE(cj.applied_candidate_count, 0::bigint) AS applied_candidate_count,
                        COALESCE(rc.recommended_candidate_count, 0::bigint) AS recommended_candidate_count,
                        COALESCE(jc.joined_candidates, 0::bigint) AS joined_candidates,
                        (
                            SELECT array_agg(ju.user_id) AS array_agg
                            FROM neo_job.jobs_users ju
                            WHERE ju.job_id = j.id AND ju.user_type = 'Recruiter'::text
                        ) AS recruiter_ids,
                        (
                            SELECT array_agg(ju.user_id) AS array_agg
                            FROM neo_job.jobs_users ju
                            WHERE ju.job_id = j.id AND ju.user_type = 'Placement Officer'::text
                        ) AS placement_officer_ids,
                        (
                            SELECT array_agg(ju.user_id) AS array_agg
                            FROM neo_job.jobs_users ju
                            WHERE ju.job_id = j.id
                        ) AS assigned_user_ids,
                        (
                            SELECT string_agg(u.name, ', '::text) AS string_agg
                            FROM neo_job.jobs_users ju
                                 JOIN neo_user.users u ON u.id = ju.user_id
                            WHERE ju.job_id = j.id AND ju.user_type = 'Placement Officer'::text
                        ) AS placement_officer_names,
                        (
                            SELECT string_agg(u.name, ', '::text) AS string_agg
                            FROM neo_job.jobs_users ju
                                 JOIN neo_user.users u ON u.id = ju.user_id
                            WHERE ju.job_id = j.id AND ju.user_type = 'Recruiter'::text                
                        ) AS recruiter_names,
                        CASE
                            WHEN jc.joined_candidates > j.no_of_position::bigint THEN 0::bigint
                            ELSE j.no_of_position::bigint - COALESCE(jc.joined_candidates, 0::bigint)
                        END AS no_of_vacancies
            FROM 		neo_job.jobs j
            LEFT JOIN 	neo_customer.companies cust ON cust.id = j.customer_id
            LEFT JOIN 	neo_master.business_verticals bv ON bv.id = j.business_vertical_id
            LEFT JOIN 	neo_master.functional_areas fa ON fa.id = j.functional_area_id
            LEFT JOIN 	neo_master.industries ind ON ind.id = j.industry_id
            LEFT JOIN 	neo_master.educations edu ON edu.id = j.education_id
            LEFT JOIN 	neo_master.job_open_types jot ON jot.id = j.job_open_type_id
            LEFT JOIN 	neo_master.job_priority_levels jpl ON jpl.id = j.job_priority_level_id
            LEFT JOIN 	neo_master.genders g ON g.id = j.gender_id
            LEFT JOIN	neo_master.job_statuses js ON js.id = j.job_status_id
            LEFT JOIN 	neo_master.qualification_packs qp ON qp.id = j.qualification_pack_id
            LEFT JOIN 	cj ON cj.job_id = j.id
            LEFT JOIN 	rc ON rc.job_id = j.id
            LEFT JOIN 	jc ON jc.job_id = j.id
            WHERE		(i_qualification_pack_id<1 OR j.qualification_pack_id=i_qualification_pack_id)
            AND			(COALESCE(i_job_status_id,-1)<0 OR J.job_status_id=i_job_status_id)             
        )
        SELECT 		RES.*
        FROM 		RES
        WHERE		(
        				(i_user_id IN (11,14) AND i_user_id=ANY(RES.assigned_user_ids))
        				OR
                    	(i_user_id NOT IN (11,14) AND (RES.assigned_user_ids && t_hierarchy_user_id_array))
        			);
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION neo_job.fn_get_job_list_data (i_user_id integer, i_qualification_pack_id integer, i_job_status_id integer)
  OWNER TO postgres;
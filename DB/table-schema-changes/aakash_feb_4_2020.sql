CREATE OR REPLACE FUNCTION reports.fn_get_lead_detail_report_data (
  i_start_date text,
  i_end_date text,
  i_user_id integer
)
RETURNS TABLE (
  customer_name text,
  opportunity_code text,
  lead_status text,
  contract_id text,
  created_by text,
  created_on text,
  modified_on text,
  modified_by text,
  industry_name text,
  handler_name text,
  location_name text,
  lead_source text,
  hr_name text,
  hr_email text,
  hr_phone text
) AS
$body$
DECLARE
	d_hierarchy_id_array INT[] = ARRAY[]::INT[];

BEGIN
	SELECT 	ARRAY_AGG(user_id)
    INTO	d_hierarchy_id_array
    FROM	neo_user.fn_get_recursive_team_data(i_user_id);

  	RETURN QUERY
    	SELECT 		C.company_name,
        			OPP.opportunity_code,
                    LS.name AS lead_status,
                    CASE WHEN OPP.is_contract THEN OPP.contract_id ELSE 'NA' END AS contract_id,
                    UC.name AS created_by,
                    TO_CHAR(OPP.created_at,'dd-Mon-yyyy HH12:MI:SS AM') AS created_on,
                    TO_CHAR(OPP.updated_at,'dd-Mon-yyyy HH12:MI:SS AM') AS modified_on,
                    UU.name AS modified_by,
                    I.name AS industry_name,
                    UC.name AS handler_name,
                    FORMAT('%s%s%s, %s',TRIM(CB.address),(CASE WHEN COALESCE(NULLIF(TRIM(CB.address),''),'')<>'' THEN ', ' ELSE '' END), DIST.name,ST.name) AS location_name,
                    LSOR.name AS lead_source,
                    C.hr_name AS hr_name,
                    C.hr_email AS hr_email,
                  	C.hr_phone AS hr_phone
        FROM 		neo_customer.opportunities AS OPP
        LEFT JOIN 	neo_customer.companies AS C ON C.id = OPP.company_id
        LEFT JOIN 	neo_master.lead_statuses AS LS ON LS.id = OPP.lead_status_id
        LEFT JOIN 	neo_user.users AS UC ON UC.id = OPP.created_by
        LEFT JOIN 	neo_user.users AS UU ON UU.id = OPP.updated_by
        LEFT JOIN 	neo_master.industries AS I ON I.id= OPP.industry_id
        LEFT JOIN 	neo_master.lead_sources AS LSOR ON LSOR.id=C.lead_source_id
        LEFT JOIN 	neo_customer.customer_branches AS CB ON CB.opportunity_id = OPP.id
		LEFT JOIN	neo_master.country AS CON ON CON.id=CB.country_id
        LEFT JOIN	neo_master.districts AS DIST ON DIST.id=CB.district_id
        LEFT JOIN	neo_master.states AS ST ON ST.id=DIST.state_id
        WHERE 		OPP.created_by=ANY(d_hierarchy_id_array)
        AND 		(OPP.created_at::DATE BETWEEN i_start_date::DATE AND i_end_date::DATE)
        ORDER BY	1,2;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION reports.fn_get_lead_detail_report_data (i_start_date text, i_end_date text, i_user_id integer)
  OWNER TO postgres;
------------------------------------------------------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION reports.fn_get_client_opportunity_tracker_report_data (
  i_user_id integer
)
RETURNS TABLE (
  sr_no bigint,
  customer_name text,
  opportunity_code text,
  contract_id text,
  created_user_name text,
  created_user_role text,
  active_status text,
  business_vertical_name text,
  office_location text,
  job_count bigint,
  requirement_count bigint,
  interested_count bigint,
  profile_submitted_count bigint,
  pending_feedback_from_employer_count bigint,
  profile_accepted_count bigint,
  profile_rejected_count bigint,
  interview_scheduled_count bigint,
  interview_attended_count bigint,
  interview_not_attended_count bigint,
  selected_count bigint,
  rejected_count bigint,
  offer_in_pipeline_count bigint,
  offerred_count bigint,
  offer_accepted_count bigint,
  offer_rejected_count bigint,
  joined_count bigint,
  not_joined_count bigint
) AS
$body$
DECLARE
	d_hierarchy_id_array INT[] = ARRAY[]::INT[];

BEGIN
	SELECT 	ARRAY_AGG(user_id)
    INTO	d_hierarchy_id_array
    FROM	neo_user.fn_get_recursive_team_data(i_user_id);

   	RETURN QUERY
        WITH RES AS
        (
            SELECT    	J.customer_id,
            			J.opportunity_id,
                        CJ.candidate_status_id,
                        COUNT(CJ.id) AS candidate_count
            FROM    	neo_job.candidates_jobs AS CJ
            LEFT JOIN   neo_job.jobs AS J ON J.id=CJ.job_id
            GROUP BY   	J.customer_id,
            			J.opportunity_id,
                        CJ.candidate_status_id
        )
        SELECT    	ROW_NUMBER() OVER(ORDER BY CUST.company_name,OPP.opportunity_code) AS sr_no,
                    CUST.company_name,
                    OPP.opportunity_code,
                    OPP.contract_id,
                    U.name AS created_user_name,
                    UR.name AS created_user_role,
                    (CASE OPP.active WHEN TRUE THEN 'Active' ELSE 'Inactive' END) AS active_status,
                    COALESCE(BV.name,'NA') AS business_vertical_name,
                    FORMAT('%s%s%s, %s',TRIM(CB.address),(CASE WHEN COALESCE(NULLIF(TRIM(CB.address),''),'')<>'' THEN ', ' ELSE '' END), DIST.name,ST.name) AS office_location,
                    (SELECT COUNT(J.id) FROM neo_job.jobs AS J WHERE J.customer_id=CUST.id) AS job_count,
                    COALESCE((SELECT SUM(J.no_of_position::INTEGER) FROM neo_job.jobs AS J WHERE J.customer_id=CUST.id),0) AS requirement_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=1),0) AS interested_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=2),0) AS profile_submitted_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=3),0) AS pending_feedback_from_employer_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=4),0) AS profile_accepted_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=5),0) AS profile_rejected_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=6),0) AS interview_scheduled_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=7),0) AS interview_attended_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=8),0) AS interview_not_attended_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=9),0) AS selected_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=10),0) AS rejected_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=11),0) AS offer_in_pipeline_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=12),0) AS offerred_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=13),0) AS offer_accepted_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=14),0) AS offer_rejected_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=15),0) AS joined_count,
                    COALESCE((SELECT RES.candidate_count FROM RES WHERE RES.customer_id=CUST.id AND RES.opportunity_id=OPP.id AND RES.candidate_status_id=16),0) AS not_joined_count
        FROM     	neo_customer.opportunities AS OPP
        LEFT JOIN  	neo_customer.companies AS CUST ON CUST.id=opp.company_id
        LEFT JOIN	neo_master.business_verticals AS BV ON BV.id=OPP.business_vertical_id
        LEFT JOIN	neo_customer.customer_branches AS CB ON CB.opportunity_id=OPP.id
        LEFT JOIN 	neo_user.users AS U ON U.id=OPP.created_by
        LEFT JOIN 	neo_user.user_roles AS UR ON UR.id=U.user_role_id
        LEFT JOIN	neo_master.country AS CON ON CON.id=CB.country_id
        LEFT JOIN	neo_master.districts AS DIST ON DIST.id=CB.district_id
        LEFT JOIN	neo_master.states AS ST ON ST.id=DIST.state_id
        WHERE    	OPP.is_contract
        AND			OPP.created_by=ANY(d_hierarchy_id_array)
        ORDER BY  	1;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION reports.fn_get_client_opportunity_tracker_report_data (i_user_id integer)
  OWNER TO postgres;
------------------------------------------------------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION reports.fn_get_placement_detail_report_data (
  i_job_created_start_date text,
  i_job_created_end_date text,
  i_candidate_status_id integer,
  i_gender_id integer,
  i_center_name text,
  i_customer_id integer,
  i_batch_start_date_from text,
  i_batch_start_date_to text,
  i_batch_end_date_from text,
  i_batch_end_date_to text,
  i_qualification_pack_id integer,
  i_business_vertical_id integer,
  i_state_id integer,
  i_district_id integer,
  i_pin_code text,
  i_employment_type text,
  i_job_location text,
  i_job_title text,
  i_user_id integer
)
RETURNS TABLE (
  region_name text,
  batch_code text,
  batch_start_date text,
  batch_end_date text,
  center_name text,
  batch_customer_name text,
  batch_contract_id text,
  candidate_name text,
  enrollment_no text,
  date_of_birth text,
  candidate_current_status text,
  current_status_changed_on text,
  contact_no text,
  interview_date text,
  date_of_join text,
  customer_name text,
  opportunity_code text,
  contract_id text,
  job_location text,
  job_title text,
  job_created_date TEXT,
  job_qualification_pack text,
  business_vertical text,
  job_created_by text,
  job_created_by_user_role text,
  employment_type text,
  salary text,
  state_name text,
  district_name text,
  city_name text,
  pin_code text,
  gender text,
  offer_letter_uploaded_date text,
  offer_letter_file text,
  certification_status text
) AS
$body$
DECLARE
    d_hierarchy_id_array INT[] = ARRAY[]::INT[];

BEGIN
    SELECT  ARRAY_AGG(user_id)
    INTO    d_hierarchy_id_array
    FROM    neo_user.fn_get_recursive_team_data(i_user_id);

    i_candidate_status_id := COALESCE(i_candidate_status_id,0);
    i_gender_id := COALESCE(i_gender_id,0);
    i_center_name := COALESCE(i_center_name,'');
    i_customer_id := COALESCE(i_customer_id,0);
    i_qualification_pack_id := COALESCE(i_qualification_pack_id,0);
    i_business_vertical_id := COALESCE(i_business_vertical_id,0);

    i_state_id := COALESCE(i_state_id,0);
    i_district_id := COALESCE(i_district_id,0);

    i_pin_code := TRIM(COALESCE(i_pin_code,''));
    i_employment_type := TRIM(COALESCE(i_employment_type,''));
    i_job_location := TRIM(COALESCE(i_job_location,''));
    i_job_title := TRIM(COALESCE(i_job_title,''));

    i_batch_start_date_from := TRIM(COALESCE(i_batch_start_date_from,''));
    i_batch_start_date_to := TRIM(COALESCE(i_batch_start_date_to,''));

    i_batch_end_date_from := TRIM(COALESCE(i_batch_end_date_from,''));
    i_batch_end_date_to := TRIM(COALESCE(i_batch_end_date_to,''));

    i_job_created_start_date := TRIM(COALESCE(i_job_created_start_date,''));
    i_job_created_end_date := TRIM(COALESCE(i_job_created_end_date,''));

    RETURN QUERY
        WITH JU AS
        (
            SELECT      JB.id AS job_id,
                        COALESCE((SELECT ARRAY_AGG(JU.user_id)
                                  FROM   neo_job.jobs_users AS JU
                                  WHERE  JU.job_id=JB.id),ARRAY[]::INT[])||JB.created_by AS assigned_user_ids
            FROM        neo_job.jobs AS JB
            GROUP BY    JB.id
        ),
        RG AS
        (
        SELECT AC.center_name,
                    AC.region_name,
                    ROW_NUMBER() OVER(PARTITION BY AC.center_name ORDER BY AC.center_name) AS counter
            FROM igs_upload.all_centers AS AC
            WHERE COALESCE(TRIM(AC.region_name))<>''
        )
        SELECT      COALESCE(REG.name,COALESCE(RG.region_name,'NA')) AS region_name,
                    COALESCE(NB.batch_code,'NA') AS batch_code,
                    COALESCE(NB.batch_start_date,'NA') AS batch_start_date,
                    COALESCE(NB.batch_end_date,'NA') AS batch_end_date,
                    COALESCE(NB.center_name,'NA') AS center_name,
                    COALESCE(NB.customer_name,'NA') AS batch_customer_name,
                    COALESCE(NB.contract_id,'NA') AS batch_contract_id,
                    CAN.candidate_name,
                    CAN.candidate_enrollment_id AS enrollment_no,
                    CAN.date_of_birth,
                    CS.name AS candidate_current_status,
                    (SELECT TO_CHAR(MAX(JL.created_at),'dd-Mon-yyyy HH12:MI:SS AM')
                    FROM neo_job.candidates_jobs_logs AS JL
                    WHERE JL.candidate_id=CAN.id
                    AND JL.job_id=J.id
                    AND JL.candidate_status_id=CJL.candidate_status_id) AS current_status_changed_on,
                    CAN.mobile AS contact_no,
                    (SELECT     STRING_AGG(TO_CHAR(INT.schedule_date,'dd-Mon-yyyy'),',')
                        FROM    neo_job.candidates_jobs_logs AS INT
                        WHERE   INT.candidate_id=CAN.id
                        AND     INT.job_id=J.id
                        AND     INT.candidate_status_id=6) AS interview_date,
                    (SELECT TO_CHAR(CP.date_of_join,'dd-Mon-yyyy') FROM neo_job.candidate_placement AS CP WHERE CP.job_id=J.id AND CP.candidate_id=CAN.id) AS date_of_join,
                    CUST.company_name AS customer_name,
                    OPP.opportunity_code,
                    OPP.contract_id,
                    J.office_location AS job_location,
                    J.job_title AS job_title,
                    J.created_at::TEXT AS job_created_date,
                    QP.name AS job_qualification_pack,
                    BV.name AS business_vertical,
                    U.name AS job_created_by,
                    UR.name AS job_created_by_user_role,
                    ET.name AS employment_type,
                    COALESCE(NCP.ctc,NCP.offered_ctc,CONCAT(J.offered_ctc_from, '-', J.offered_ctc_to)) AS salary,
                    STA.name AS state_name,
                    DIS.name AS district_name,
                    J.city AS city_name,
                    J.pincode AS pin_code,
                    GEN.name AS gender,
                    TO_CHAR(NCP.offer_letter_uploaded_on,'dd-Mon-yyyy') AS offer_letter_uploaded_date,
					NCP.offer_letter_file AS offer_letter_file,
                    CCD.certification_status
        FROM        neo_job.jobs AS J
        LEFT JOIN   JU ON JU.job_id=J.id
        LEFT JOIN	neo_customer.opportunities AS OPP ON OPP.id=J.opportunity_id
        LEFT JOIN  	neo_customer.companies AS CUST ON CUST.id=J.customer_id
        LEFT JOIN   neo_master.qualification_packs AS QP ON QP.id = J.qualification_pack_id
        LEFT JOIN   neo_master.business_verticals AS BV ON BV.id = OPP.business_vertical_id
        LEFT JOIN   neo_user.users AS U ON U.id = J.created_by
        LEFT JOIN   neo_user.user_roles AS UR ON UR.id = U.user_role_id
        LEFT JOIN   neo_master.states AS STA ON STA.id = J.state_id
        LEFT JOIN   neo_master.districts AS DIS ON DIS.id = J.district_id
        LEFT JOIN   neo_job.candidates_jobs AS CJL ON CJL.job_id = J.id
        LEFT JOIN   neo.candidates AS CAN ON CAN.id = CJL.candidate_id
        LEFT JOIN   neo_master.candidate_statuses AS CS ON CS.id = CJL.candidate_status_id
        LEFT JOIN   neo.candidate_qp_details AS CQD ON CQD.candidate_id = CJL.candidate_id AND CQD.qualification_pack_id=J.qualification_pack_id
        LEFT JOIN   neo.neo_batches AS NB ON NB.batch_code = CAN.batch_code
        LEFT JOIN   neo_master.genders AS GEN ON GEN.id = CAN.gender_id
        LEFT JOIN   neo_user.centers AS CEN ON LOWER(NB.center_name) = LOWER(CEN.center_name)
        LEFT JOIN   neo_master.region AS REG ON REG.id = CEN.region_id
        LEFT JOIN 	RG ON RG.center_name=NB.center_name AND RG.counter=1
        LEFT JOIN   neo_job.candidate_placement AS NCP ON NCP.candidate_id = CAN.id AND NCP.job_id = J.id
        LEFT JOIN 	neo_master.employment_type AS ET ON ET.id=NCP.employment_type_id
        LEFT JOIN 	neo.candidate_certification_details AS CCD ON ccd.enrollment_id=CAN.candidate_enrollment_id
        WHERE       (COALESCE(CAN.id,0) > 0)
        AND         (JU.assigned_user_ids && d_hierarchy_id_array)
        AND         (i_job_created_start_date = '' OR (J.created_at::DATE BETWEEN i_job_created_start_date::DATE AND i_job_created_end_date::DATE))
        AND         (i_candidate_status_id < 1 OR CJL.candidate_status_id=i_candidate_status_id)
        AND         (i_gender_id < 1 OR can.gender_id=i_gender_id)
        AND         (i_center_name = '' OR COALESCE(NB.center_name,'NA') ~* i_center_name)
        AND         (i_customer_id < 1 OR J.customer_id=i_customer_id)
        AND         (i_qualification_pack_id < 1 OR J.qualification_pack_id=i_qualification_pack_id)
        AND         (i_business_vertical_id < 1 OR OPP.business_vertical_id=i_business_vertical_id)
        AND         (i_state_id < 1 OR J.state_id=i_state_id)
        AND         (i_district_id < 1 OR J.district_id=i_district_id)
        AND         (i_pin_code='' OR J.pincode ~* i_pin_code)
        AND         (i_employment_type='' OR ET.name ~* i_employment_type)
        AND         (i_job_location='' OR J.job_location ~* i_job_location)
        AND         (i_job_title='' OR J.job_title ~* i_job_title)
        AND         (i_batch_start_date_from = '' OR i_batch_start_date_to = '' OR (NB.batch_start_date::DATE BETWEEN i_batch_start_date_from::DATE AND i_batch_start_date_to::DATE))
        AND         (i_batch_end_date_from = '' OR i_batch_end_date_to = '' OR (NB.batch_end_date::DATE BETWEEN i_batch_end_date_from::DATE AND i_batch_end_date_to::DATE))
        ORDER BY    region_name,
                    batch_code,
                    candidate_name;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION reports.fn_get_placement_detail_report_data (i_job_created_start_date text, i_job_created_end_date text, i_candidate_status_id integer, i_gender_id integer, i_center_name text, i_customer_id integer, i_batch_start_date_from text, i_batch_start_date_to text, i_batch_end_date_from text, i_batch_end_date_to text, i_qualification_pack_id integer, i_business_vertical_id integer, i_state_id integer, i_district_id integer, i_pin_code text, i_employment_type text, i_job_location text, i_job_title text, i_user_id integer)
  OWNER TO postgres;
------------------------------------------------------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION reports.fn_get_job_detailed_report_data (
  i_customer_id integer,
  i_industry_id integer,
  i_qualification_pack_id integer,
  i_job_created_user_id integer,
  i_job_title text,
  i_business_vertical_id integer,
  i_job_location text,
  i_education_id integer,
  i_created_date_from text,
  i_created_date_to text,
  i_pin_code text = ''::text,
  i_user_id integer = '-1'::integer
)
RETURNS TABLE (
  industry text,
  job_qp text,
  job_posted_by text,
  user_role text,
  job_title text,
  business_vertical text,
  job_location text,
  pin_code text,
  no_of_vacancies bigint,
  min_salary integer,
  max_salary integer,
  education text,
  min_age integer,
  max_age integer,
  min_experience integer,
  max_experience integer,
  customer_name text,
  opportunity_code text,
  contract_id text,
  key_skills text
) AS
$body$
DECLARE
	d_hierarchy_id_array INT[] = ARRAY[]::INT[];

BEGIN
	SELECT 	ARRAY_AGG(user_id)
    INTO	d_hierarchy_id_array
    FROM	neo_user.fn_get_recursive_team_data(i_user_id);

	i_customer_id := COALESCE(i_customer_id,0);
    i_industry_id := COALESCE(i_industry_id,0);
    i_qualification_pack_id := COALESCE(i_qualification_pack_id,0);
    i_job_created_user_id := COALESCE(i_job_created_user_id,0);
    i_job_title := TRIM(COALESCE(i_job_title,''));
    i_business_vertical_id := COALESCE(i_business_vertical_id,0);
    i_job_location := TRIM(COALESCE(i_job_location,''));
    i_education_id := COALESCE(i_education_id,0);
    i_created_date_from := TRIM(COALESCE(i_created_date_from,''));
    i_created_date_to := TRIM(COALESCE(i_created_date_to,''));
    i_pin_code := TRIM(COALESCE(i_pin_code,''));

	RETURN QUERY
    	WITH JDR AS
        (
        	SELECT   	COALESCE(IND.name,'NA') as industry,
           				COALESCE(QP.name,'NA') AS job_qp,
                       	U.name AS job_posted_by,
                       	UR.name AS user_role,
                       	J.job_title,
                       	BV.name AS business_vertical,
                        TRIM(COALESCE(J.office_location,'')) AS job_location,
                        J.pincode AS pin_code,
                        (J.no_of_position::BIGINT - COALESCE((SELECT 	 COUNT(DISTINCT CJ.candidate_id) as joined_candidate
                            									FROM 	 neo_job.candidate_placement AS CJ
                            									WHERE 	 CJ.job_id = J.id
                                                                AND		 CJ.date_of_join IS NOT NULL
                                                                GROUP BY CJ.job_id), 0)) AS no_vacancies,
                       	J.offered_ctc_from AS min_salary,
                       	J.offered_ctc_to AS max_salary,
                       	EDU.name AS education,
                       	J.age_from AS min_age,
                       	J.age_to AS max_age,
                       	J.experience_from AS min_experience,
                       	J.experience_to AS max_experience,
                       	COALESCE(C.company_name,'') AS customer_name,
                        OPP.opportunity_code,
                        OPP.contract_id,
                       	J.key_skills,
                        COALESCE((SELECT ARRAY_AGG(JU.user_id)
                                  FROM 	 neo_job.jobs_users AS JU
                                  WHERE	 JU.job_id=J.id),ARRAY[]::INT[])||J.created_by AS assigned_user_ids
            FROM 		neo_job.jobs AS J
            LEFT JOIN	neo_customer.opportunities AS OPP ON OPP.id=J.opportunity_id
            LEFT JOIN 	neo_customer.companies AS C ON C.id = J.customer_id
            LEFT JOIN 	neo_master.business_verticals AS BV ON BV.id = OPP.business_vertical_id
            LEFT JOIN 	neo_master.qualification_packs AS QP ON QP.id = J.qualification_pack_id
            LEFT JOIN 	neo_master.industries AS IND ON IND.id = J.industry_id
            LEFT JOIN 	neo_master.educations AS EDU ON EDU.id = J.education_id
            LEFT JOIN 	neo_user.users AS U ON U.id = J.created_by
            LEFT JOIN 	neo_user.user_roles AS UR ON UR.id = U.user_role_id
            LEFT JOIN	neo_master.districts AS D ON D.id=J.district_id
            LEFT JOIN	neo_master.states AS S ON S.id=J.state_id
            WHERE 		(i_customer_id < 1 OR J.customer_id=i_customer_id)
            AND			(i_industry_id < 1 OR J.industry_id=i_industry_id)
            AND			(i_qualification_pack_id < 1 OR J.qualification_pack_id=i_qualification_pack_id)
            AND			(i_job_created_user_id < 1 OR J.created_by=i_job_created_user_id)
            AND			(i_job_title='' OR J.job_title ~* i_job_title)
            AND			(i_business_vertical_id < 1 OR OPP.business_vertical_id=i_business_vertical_id)
            AND			(i_education_id < 1 OR J.education_id=i_education_id)
            AND			(i_created_date_from = '' OR (J.created_at::DATE BETWEEN i_created_date_from::DATE AND i_created_date_to::DATE))
            AND			(i_pin_code='' OR J.pincode ~* i_pin_code)
        )
        SELECT  	JDR.industry,
         			JDR.job_qp,
         			JDR.job_posted_by,
                    JDR.user_role,
                    JDR.job_title,
                    JDR.business_vertical,
                    JDR.job_location,
                    JDR.pin_code,
           			(CASE WHEN JDR.no_vacancies < 0 THEN 0 ELSE JDR.no_vacancies END) AS no_of_vacancies,
                 	JDR.min_salary,
                 	JDR.max_salary,
                 	JDR.education,
                 	JDR.min_age,
                 	JDR.max_age,
                 	JDR.min_experience,
                 	JDR.max_experience,
                 	JDR.customer_name,
                    JDR.opportunity_code,
                    JDR.contract_id,
                 	JDR.key_skills
        FROM 		JDR
        WHERE		(i_job_location='' OR JDR.job_location ~* i_job_location)
        AND			(JDR.assigned_user_ids && d_hierarchy_id_array)
        ORDER BY	1,2,3,5;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION reports.fn_get_job_detailed_report_data (i_customer_id integer, i_industry_id integer, i_qualification_pack_id integer, i_job_created_user_id integer, i_job_title text, i_business_vertical_id integer, i_job_location text, i_education_id integer, i_created_date_from text, i_created_date_to text, i_pin_code text, i_user_id integer)
  OWNER TO postgres;
------------------------------------------------------------------------------------------------------------------------------
CREATE OR REPLACE FUNCTION reports.fn_get_user_usage_report_data (
  i_start_date text,
  i_end_date text,
  i_user_id integer
)
RETURNS TABLE (
  sr_no bigint,
  user_name text,
  user_email text,
  user_role_name text,
  log_date text,
  login_count bigint,
  lead_created_count bigint,
  lead_status_changed_count bigint,
  lead_converted_to_customer_count bigint,
  jobs_posted_count bigint,
  candidate_created_count bigint,
  jobs_applied_count bigint,
  jobs_closed_count bigint,
  candidate_status_changed_count bigint
) AS
$body$
DECLARE
	d_hierarchy_id_array INT[] = ARRAY[]::INT[];

BEGIN
	SELECT 	ARRAY_AGG(user_id)
    INTO	d_hierarchy_id_array
    FROM	neo_user.fn_get_recursive_team_data(i_user_id);

	RETURN QUERY
     	WITH
        	RES as
            (
                SELECT   	U.id AS user_id,
                            U.name as user_name,
                            U.email as user_email,
                            UR.name  as user_role_name,
                            TO_CHAR(UL.login, 'yyyy-MM-dd') as log_date_sort,
                            TO_CHAR(UL.login, 'dd-Mon-yyyy') as log_date,
                            COUNT(UL.id) AS login_count
                FROM   		neo_user.users as U
                INNER JOIN  neo_user.user_roles as UR ON UR.id = U.user_role_id AND UR.id NOT IN (1,2)
                INNER JOIN  neo_user.user_logs as UL ON UL.user_id = U.id
                WHERE   	(UL.login::DATE BETWEEN i_start_date::DATE AND i_end_date::DATE)
                AND			U.id=ANY(d_hierarchy_id_array)
                GROUP BY 	U.id,
                            U.name,
                            U.email,
                            UR.name,
                            TO_CHAR(UL.login, 'yyyy-MM-dd'),
                            TO_CHAR(UL.login, 'dd-Mon-yyyy')
                ORDER BY 	log_date_sort
            ),
            CAN as
            (
                SELECT   	C.created_by,
                            C.created_at::DATE AS created_at,
                            COUNT(C.id) AS candidate_count
                FROM   		neo.candidates AS C
                WHERE   	(C.created_at::DATE BETWEEN i_start_date::DATE AND i_end_date::DATE)
                AND			(C.created_by=ANY(d_hierarchy_id_array))
                GROUP BY 	C.created_by,
                            C.created_at::DATE
            )
        	SELECT	ROW_NUMBER() OVER() AS sr_no,
                    RES.user_name,
                    RES.user_email,
                    RES.user_role_name,
                    RES.log_date,
                    RES.login_count,
                    (SELECT COUNT(C.id) FROM neo_customer.opportunities as C WHERE C.created_by=RES.user_id AND C.created_at::DATE=RES.log_date::DATE) AS lead_created_count,
                    (SELECT COUNT(LL.id) FROM neo_customer.lead_logs AS LL WHERE LL.created_by=RES.user_id AND LL.created_at::DATE=RES.log_date::DATE) AS lead_status_changed_count,
                    (SELECT COUNT(LL.id) FROM neo_customer.lead_logs AS LL WHERE LL.lead_status_id=22 AND LL.created_by=RES.user_id AND LL.created_at::DATE=RES.log_date::DATE) AS lead_converted_to_customer_count,
                    (SELECT COUNT(J.id) FROM neo_job.jobs AS J WHERE J.created_by=RES.user_id AND J.created_at::DATE=RES.log_date::DATE) AS jobs_posted_count,
                    COALESCE((SELECT CAN.candidate_count FROM CAN WHERE CAN.created_by=RES.user_id AND CAN.created_at=RES.log_date::DATE),0) AS candidate_created_count,
                    (SELECT COUNT(CJL.id) FROM neo_job.candidates_jobs_logs AS CJL WHERE CJL.created_by=RES.user_id AND CJL.created_at::DATE=RES.log_date::DATE AND CJL.candidate_status_id=1) AS jobs_applied_count,
                    (SELECT COUNT(JSL.id) FROM neo_job.jobs_statuses_logs AS JSL WHERE JSL.created_by=RES.user_id AND JSL.created_at::DATE=RES.log_date::DATE AND JSL.job_status_id=3) AS jobs_closed_count,
                    (SELECT COUNT(CJL.id) FROM neo_job.candidates_jobs_logs AS CJL WHERE CJL.created_by=RES.user_id AND CJL.created_at::DATE=RES.log_date::DATE) AS candidate_status_changed_count
        	FROM   	RES;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION reports.fn_get_user_usage_report_data (i_start_date text, i_end_date text, i_user_id integer)
  OWNER TO postgres;
------------------------------------------------------------------------------------------------------------------------------

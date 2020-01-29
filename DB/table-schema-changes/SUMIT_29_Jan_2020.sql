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
SELECT candidates_jobs.job_id,
            count(candidates_jobs.candidate_id) AS applied_candidate_count
FROM neo_job.candidates_jobs
WHERE candidates_jobs.candidate_status_id <> ALL (ARRAY[15, 17])
GROUP BY candidates_jobs.job_id
        ), rc AS (
    SELECT j_1.id AS job_id,
            count(DISTINCT cqp.candidate_id) AS recommended_candidate_count
    FROM neo_job.jobs j_1
             LEFT JOIN neo.candidate_qp_details cqp ON
                 cqp.qualification_pack_id = j_1.qualification_pack_id AND NOT (cqp.candidate_id IN (
        SELECT candidates_jobs.candidate_id
        FROM neo_job.candidates_jobs
        WHERE candidates_jobs.job_id = j_1.id
        ))
    WHERE j_1.job_status_id = 2
    GROUP BY j_1.id
    ), jc AS (
    SELECT cp.job_id,
            count(cp.candidate_id) AS joined_candidates
    FROM neo_job.candidate_placement cp
             LEFT JOIN neo_job.candidates_jobs cj_1 ON cj_1.candidate_id =
                 cp.candidate_id AND cj_1.job_id = cp.job_id
    WHERE cj_1.candidate_status_id = ANY (ARRAY[15, 17])
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
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
  job_location text,
  job_title text,
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
        SELECT      COALESCE(REG.name,COALESCE(RG.center_name,'NA')) AS region_name,
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
                    C.customer_name AS customer_name,
                    J.office_location AS job_location,
                    J.job_title AS job_title,
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
                    CCD.certification_status
        FROM        neo_job.jobs AS J
        LEFT JOIN   JU ON JU.job_id=J.id
        LEFT JOIN   neo_customer.customers AS C ON J.customer_id = C.id
        LEFT JOIN   neo_master.qualification_packs AS QP ON QP.id = J.qualification_pack_id
        LEFT JOIN   neo_master.business_verticals AS BV ON BV.id = C.business_vertical_id
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
        LEFT JOIN RG ON RG.center_name=NB.center_name AND RG.counter=1
        LEFT JOIN   neo_job.candidate_placement AS NCP ON NCP.candidate_id = CAN.id AND NCP.job_id = J.id
        LEFT JOIN neo_master.employment_type AS ET ON ET.id=NCP.employment_type_id
        LEFT JOIN neo.candidate_certification_details AS CCD ON ccd.enrollment_id=CAN.candidate_enrollment_id
        WHERE       (COALESCE(CAN.id,0) > 0)
        AND         (JU.assigned_user_ids && d_hierarchy_id_array)
        AND         (i_job_created_start_date = '' OR (J.created_at::DATE BETWEEN i_job_created_start_date::DATE AND i_job_created_end_date::DATE))
        AND         (i_candidate_status_id < 1 OR CJL.candidate_status_id=i_candidate_status_id)
        AND         (i_gender_id < 1 OR can.gender_id=i_gender_id)
        AND         (i_center_name = '' OR COALESCE(NB.center_name,'NA') ~* i_center_name)
        AND         (i_customer_id < 1 OR J.customer_id=i_customer_id)
        AND         (i_qualification_pack_id < 1 OR J.qualification_pack_id=i_qualification_pack_id)
        AND         (i_business_vertical_id < 1 OR C.business_vertical_id=i_business_vertical_id)
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

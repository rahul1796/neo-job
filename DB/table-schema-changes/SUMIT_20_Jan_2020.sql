CREATE VIEW neo.vw_self_employed_candidate_list (
    region_name,
    batch_code,
    batch_start_date,
    batch_end_date,
    center_name,
    batch_customer_name,
    batch_contract_id,
    file_name,
    document_uploaded_on,
    qualification_pack,
    candidate_id,
    candidate_name,
    enrollment_no,
    date_of_birth,
    enrollment_id,
    gender,
    state,
    district,
    certification_status,
    employment_type,
    employment_start_date,
    self_employment_start_date,
    projected_earnings_per_month,
    candidate_source,
    category,
    skilling_type)
AS
 WITH rg AS (
SELECT ac.center_name,
            ac.region_name,
            row_number() OVER (PARTITION BY ac.center_name
ORDER BY ac.center_name) AS counter
FROM igs_upload.all_centers ac
WHERE COALESCE(btrim(ac.region_name)) <> ''::text
        )
    SELECT DISTINCT COALESCE(NULLIF(btrim(reg.name), ''::text),
        COALESCE(rg.region_name, 'NA'::text)) AS region_name,
    COALESCE(nb.batch_code, 'NA'::text) AS batch_code,
    COALESCE(nb.batch_start_date, 'NA'::text) AS batch_start_date,
    COALESCE(nb.batch_end_date, 'NA'::text) AS batch_end_date,
    COALESCE(nb.center_name, 'NA'::text) AS center_name,
    COALESCE(nb.customer_name, 'NA'::text) AS batch_customer_name,
    COALESCE(nb.contract_id, 'NA'::text) AS batch_contract_id,
    COALESCE(cmp.file_name, 'NA'::text) AS file_name,
    cmp.created_at AS document_uploaded_on,
    format('%s (%s)'::text, nb.qp_name, nb.qp_code) AS qualification_pack,
    can.id AS candidate_id,
    can.candidate_name,
    can.candidate_enrollment_id AS enrollment_no,
    can.date_of_birth,
    cmp.enrollment_id,
    gen.name AS gender,
    sta.name AS state,
    dis.name AS district,
    ccd.certification_status,
    cmp.employment_type,
    cmp.employment_start_date,
    to_char(cmp.employment_start_date::timestamp with time zone,
        'dd-Mon-yyyy'::text) AS self_employment_start_date,
    cmp.ctc AS projected_earnings_per_month,
    can.source_name AS candidate_source,
        CASE
            WHEN upper(can.source_name) = 'IGS'::text THEN 'MTS'::text
            ELSE 'MTO'::text
        END AS category,
        CASE
            WHEN cmp.skilling_type_id = 1 THEN 'Post Skilling'::text
            ELSE 'Pre-Skilling'::text
        END AS skilling_type
    FROM neo.candidate_employment_details cmp
     LEFT JOIN neo.candidates can ON can.candidate_enrollment_id = cmp.enrollment_id
     LEFT JOIN neo.neo_batches nb ON nb.batch_code = can.batch_code
     LEFT JOIN neo_master.genders gen ON gen.id = can.gender_id
     LEFT JOIN neo_user.centers cen ON lower(nb.center_name) = lower(cen.center_name)
     LEFT JOIN neo_master.region reg ON reg.id = cen.region_id
     LEFT JOIN neo.candidate_certification_details ccd ON ccd.enrollment_id =
         can.candidate_enrollment_id
     LEFT JOIN rg ON rg.center_name = nb.center_name AND rg.counter = 1
     LEFT JOIN neo_master.districts dis ON dis.id = can.district_id
     LEFT JOIN neo_master.states sta ON sta.id = dis.state_id
    WHERE cmp.employment_type ~* 'Self'::text AND
        COALESCE(btrim(can.candidate_enrollment_id), ''::text) <> ''::text
    ORDER BY cmp.employment_start_date;

ALTER VIEW neo.vw_self_employed_candidate_list
  OWNER TO postgres;
	
	
	-------------------------------------------------
	
	
	CREATE OR REPLACE FUNCTION neo.get_selfemployed_fn (
  i_from_date text,
  i_to_date text,
  i_center_id integer,
  i_batch_code text,
  i_qualification_pack integer,
  i_enrollment_no text
)
RETURNS TABLE (
  region_name text,
  batch_code text,
  batch_start_date text,
  batch_end_date text,
  center_name text,
  batch_customer_name text,
  batch_contract_id text,
  file_name text,
  document_uploaded_on timestamp,
  qualification_pack text,
  candidate_id integer,
  candidate_name text,
  enrollment_no text,
  date_of_birth text,
  enrollment_id text,
  gender text,
  state text,
  district text,
  certification_status text,
  employment_type text,
  employment_start_date date,
  self_employment_start_date text,
  projected_earnings_per_month text,
  candidate_source text,
  category text,
  skilling_type text
) AS
$body$
BEGIN
	i_from_date := COALESCE(i_from_date,'');
    i_to_date := COALESCE(i_to_date,'');
    i_center_id := COALESCE(i_center_id,0);
    i_batch_code := COALESCE(i_batch_code,'');
    i_qualification_pack := COALESCE(i_qualification_pack,0);
    i_enrollment_no := COALESCE(i_enrollment_no,'');
  RETURN QUERY
 WITH rg AS (
SELECT ac.center_name,
            ac.region_name,
            row_number() OVER (PARTITION BY ac.center_name
ORDER BY ac.center_name) AS counter
FROM igs_upload.all_centers ac
WHERE COALESCE(btrim(ac.region_name)) <> ''::text
        )
    SELECT DISTINCT COALESCE(NULLIF(btrim(reg.name), ''::text),
        COALESCE(rg.region_name, 'NA'::text)) AS region_name,
    COALESCE(nb.batch_code, 'NA'::text) AS batch_code,
    COALESCE(nb.batch_start_date, 'NA'::text) AS batch_start_date,
    COALESCE(nb.batch_end_date, 'NA'::text) AS batch_end_date,
    COALESCE(nb.center_name, 'NA'::text) AS center_name,
    COALESCE(nb.customer_name, 'NA'::text) AS batch_customer_name,
    COALESCE(nb.contract_id, 'NA'::text) AS batch_contract_id,
    COALESCE(cmp.file_name, 'NA'::text) AS file_name,
    cmp.created_at AS document_uploaded_on,
    format('%s (%s)'::text, nb.qp_name, nb.qp_code) AS qualification_pack,
    can.id AS candidate_id,
    can.candidate_name,
    can.candidate_enrollment_id AS enrollment_no,
    can.date_of_birth,
    cmp.enrollment_id,
    gen.name AS gender,
    sta.name AS state,
    dis.name AS district,
    ccd.certification_status,
    cmp.employment_type,
    cmp.employment_start_date,
    to_char(cmp.employment_start_date,
        'dd-Mon-yyyy'::text) AS self_employment_start_date,
    cmp.ctc AS projected_earnings_per_month,
    can.source_name AS candidate_source,
        CASE
            WHEN upper(can.source_name) = 'IGS'::text THEN 'MTS'::text
            ELSE 'MTO'::text
        END AS category,
        CASE
            WHEN cmp.skilling_type_id = 1 THEN 'Post Skilling'::text
            ELSE 'Pre-Skilling'::text
        END AS skilling_type
    FROM neo.candidate_employment_details cmp
     LEFT JOIN neo.candidates can ON can.candidate_enrollment_id = cmp.enrollment_id
     LEFT JOIN neo.neo_batches nb ON nb.batch_code = can.batch_code
     LEFT JOIN neo_master.genders gen ON gen.id = can.gender_id
     LEFT JOIN neo_user.centers cen ON lower(nb.center_name) = lower(cen.center_name)
     LEFT JOIN neo_master.region reg ON reg.id = cen.region_id
     LEFT JOIN neo.candidate_certification_details ccd ON ccd.enrollment_id =
         can.candidate_enrollment_id
     LEFT JOIN rg ON rg.center_name = nb.center_name AND rg.counter = 1
     LEFT JOIN neo_master.districts dis ON dis.id = can.district_id
     LEFT JOIN neo_master.states sta ON sta.id = dis.state_id
    WHERE cmp.employment_type ~* 'Self'::text AND
    COALESCE(btrim(can.candidate_enrollment_id), ''::text) <> ''::text
    AND (i_from_date = '' OR (cmp.employment_start_date::TEXT BETWEEN i_from_date::TEXT AND i_to_date::TEXT))
    ORDER BY employment_start_date ;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION neo.get_selfemployed_fn (i_from_date text, i_to_date text, i_center_id integer, i_batch_code text, i_qualification_pack integer, i_enrollment_no text)
  OWNER TO postgres;
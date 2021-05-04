CREATE OR REPLACE FUNCTION reports.fn_get_selfemployed_detail_report_data (
  i_self_employed_start_date text,
  i_self_employed_end_date text,
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
  file_name text,
  document_uploaded_on text,
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
  created_at timestamp,
  employment_start_date date,
  self_employment_start_date text,
  projected_earnings_per_month text,
  candidate_source text,
  category text,
  skilling_type text
) AS
$body$
DECLARE
    d_hierarchy_id_array INT[] = ARRAY[]::INT[];

BEGIN
    SELECT  ARRAY_AGG(user_id)
    INTO    d_hierarchy_id_array
    FROM    neo_user.fn_get_recursive_team_data(i_user_id);

    i_self_employed_start_date := TRIM(COALESCE(i_self_employed_start_date,''));
    i_self_employed_end_date := TRIM(COALESCE(i_self_employed_end_date,''));
    
    RETURN QUERY 
		WITH rg AS 
        (
			SELECT 		ac.center_name,
            			ac.region_name,
            			row_number() OVER (PARTITION BY ac.center_name
			ORDER BY 	ac.center_name) AS counter
			FROM 		igs_upload.all_centers ac
			WHERE 		COALESCE(btrim(ac.region_name)) <> ''::text
        )
    	SELECT 		DISTINCT 
                    COALESCE(NULLIF(btrim(reg.name), ''::text),
                    COALESCE(rg.region_name, 'NA'::text)) AS region_name,
                    COALESCE(nb.batch_code, 'NA'::text) AS batch_code,
                    COALESCE(nb.batch_start_date, 'NA'::text) AS batch_start_date,
                    COALESCE(nb.batch_end_date, 'NA'::text) AS batch_end_date,
                    COALESCE(nb.center_name, 'NA'::text) AS center_name,
                    COALESCE(nb.customer_name, 'NA'::text) AS batch_customer_name,
                    COALESCE(nb.contract_id, 'NA'::text) AS batch_contract_id,
                    COALESCE(cmp.file_name, 'NA'::text) AS file_name,
                    to_char(cmp.created_at,'dd-Mon-yyyy') AS document_uploaded_on,
                    format('%s (%s)'::text, nb.qp_name, nb.qp_code) AS qualification_pack,
                    can.id AS candidate_id,
                    can.candidate_name,
                    CAN.candidate_enrollment_id AS enrollment_no,
                    can.date_of_birth,
                    cmp.enrollment_id,
                    gen.name AS gender,
                    sta.name AS state,
                    dis.name AS district,
                    ccd.certification_status,
                    cmp.employment_type,
                    cmp.created_at,
                    cmp.employment_start_date,
                    to_char(cmp.employment_start_date, 'dd-Mon-yyyy'::text) AS self_employment_start_date,
                    COALESCE(cmp.ctc, 'NA'::text) AS projected_earnings_per_month,
                    can.source_name AS candidate_source,
                    CASE
                        WHEN upper(can.source_name) = 'IGS'::text THEN 'MTS'::text
                        ELSE 'MTO'::text
                    END AS category,
                    CASE
                        WHEN cmp.skilling_type_id = 1 THEN 'Post Skilling'::text
                        ELSE 'Pre-Skilling'::text
                    END AS skilling_type
    	FROM 		neo.candidate_employment_details cmp
       	LEFT JOIN 	neo.candidates can ON can.id = cmp.candidate_id
       	LEFT JOIN 	neo.neo_batches nb ON nb.batch_code = can.batch_code
       	LEFT JOIN 	neo_master.genders gen ON gen.id = can.gender_id
       	LEFT JOIN 	neo_user.centers cen ON lower(nb.center_name) = lower(cen.center_name)
       	LEFT JOIN 	neo_master.region reg ON reg.id = cen.region_id
       	LEFT JOIN 	neo.candidate_certification_details ccd ON ccd.enrollment_id = can.candidate_enrollment_id
       	LEFT JOIN 	rg ON rg.center_name = nb.center_name AND rg.counter = 1
       	LEFT JOIN 	neo_master.districts dis ON dis.id = can.district_id
       	LEFT JOIN 	neo_master.states sta ON sta.id = can.state_id
    	WHERE 		cmp.employment_type ~* 'Self'::text 
        AND			COALESCE(btrim(can.candidate_enrollment_id), ''::text) <> ''::text
    	AND 		(i_self_employed_start_date = '' OR (cmp.employment_start_date BETWEEN i_self_employed_start_date::DATE AND i_self_employed_end_date::DATE))
    	ORDER BY 	cmp.created_at;
END;
$body$
LANGUAGE 'plpgsql'
VOLATILE
CALLED ON NULL INPUT
SECURITY INVOKER
PARALLEL UNSAFE
COST 100 ROWS 1000;

ALTER FUNCTION reports.fn_get_selfemployed_detail_report_data (i_self_employed_start_date text, i_self_employed_end_date text, i_user_id integer)
  OWNER TO postgres;
--------------- SQL ---------------

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
  potential_number text,
  potential_order_value_per_month text,
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
    	WITH POVCTE AS (
          SELECT 	LG.id AS log_id,
              LG.customer_id,
              LG.potential_number,
              LG.potential_order_value_per_month,
              ROW_NUMBER() OVER(PARTITION BY LG.customer_id ORDER BY LG.id DESC) AS counter
          FROM 	neo_customer.lead_logs AS LG
          WHERE LG.lead_status_id=8
        )

    	SELECT 		C.company_name,
        			OPP.opportunity_code,
                    LS.name AS lead_status,
                    CASE WHEN OPP.is_contract THEN OPP.contract_id ELSE 'NA' END AS contract_id,
                    COALESCE(POVCTE.potential_number::TEXT, 'NA'),
                    COALESCE(POVCTE.potential_order_value_per_month::TEXT, 'NA'),
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
        LEFT JOIN 	POVCTE ON POVCTE.customer_id = OPP.id
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

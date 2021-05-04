CREATE VIEW neo_customer.vw_company_spoc_details (
    company_id,
    spoc_name,
    spoc_email,
    spoc_phone,
    spoc_designation,
    is_main_branch,
    is_contract)
AS
SELECT cb.customer_id AS company_id,
    initcap(COALESCE(btrim(x.t ->> 'spoc_name'::text), ''::text)) AS spoc_name,
    COALESCE(btrim(x.t ->> 'spoc_email'::text), ''::text) AS spoc_email,
    COALESCE(btrim(x.t ->> 'spoc_phone'::text), ''::text) AS spoc_phone,
    initcap(COALESCE(btrim(x.t ->> 'spoc_designation'::text), ''::text)) AS
        spoc_designation,
    cb.is_main_branch,
    o.is_contract
FROM neo_customer.customer_branches cb
     LEFT JOIN neo_customer.companies c ON c.id = cb.customer_id
     LEFT JOIN neo_customer.opportunities o ON o.company_id = c.id
     CROSS JOIN LATERAL json_array_elements(cb.spoc_detail::json) x(t)
UNION
SELECT cust.id AS company_id,
    initcap(cust.hr_name) AS spoc_name,
    cust.hr_email AS spoc_email,
    cust.hr_phone AS spoc_phone,
    'HR'::text AS spoc_designation,
    cbu.is_main_branch,
    oc.is_contract
FROM neo_customer.companies cust
     LEFT JOIN neo_customer.customer_branches cbu ON cbu.customer_id = cust.id
     LEFT JOIN neo_customer.opportunities oc ON oc.company_id = cust.id
WHERE COALESCE(btrim(cust.hr_name), ''::text) <> ''::text
ORDER BY 1, 2;

ALTER VIEW neo_customer.vw_company_spoc_details
  OWNER TO postgres;
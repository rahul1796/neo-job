CREATE VIEW neo_customer.vw_company_list (
    id,
    company_name,
    opportunity_count,
    company_description,
    industry_id,
    industry,
    functional_area_id,
    functional_area,
    spoc_name,
    spoc_email,
    spoc_phone,
    state_id,
    state,
    district_id,
    district,
    lead_source_id,
    lead_source,
    remarks)
AS
SELECT c.id,
    c.company_name,
    (
    SELECT count(*) AS count
    FROM neo_customer.opportunities o
    WHERE o.company_id = c.id
    ) AS opportunity_count,
    c.company_description,
    c.industry_id,
    i.name AS industry,
    c.functional_area_id,
    f.name AS functional_area,
    b.spoc_name ||
        CASE
            WHEN COALESCE(btrim(c.hr_name), ''::text) <> ''::text THEN
                ','::text || btrim(c.hr_name)
            ELSE ''::text
        END AS spoc_name,
    b.spoc_email ||
        CASE
            WHEN COALESCE(btrim(c.hr_email), ''::text) <> ''::text THEN
                ','::text || btrim(c.hr_email)
            ELSE ''::text
        END AS spoc_email,
    b.spoc_phone ||
        CASE
            WHEN COALESCE(btrim(c.hr_phone), ''::text) <> ''::text THEN
                ','::text || btrim(c.hr_phone)
            ELSE ''::text
        END AS spoc_phone,
    cb.state_id,
    s.name AS state,
    cb.district_id,
    d.name AS district,
    c.lead_source_id,
    ls.name AS lead_source,
    c.remarks
FROM neo_customer.companies c
     LEFT JOIN neo_master.industries i ON i.id = c.industry_id
     LEFT JOIN neo_master.functional_areas f ON f.id = c.functional_area_id
     LEFT JOIN neo_customer.customer_branches cb ON cb.customer_id = c.id
     LEFT JOIN neo_master.states s ON s.id = cb.state_id
     LEFT JOIN neo_master.districts d ON d.id = cb.district_id
     LEFT JOIN neo_master.lead_sources ls ON ls.id = c.lead_source_id
     LEFT JOIN (
    SELECT cb_1.customer_id,
            string_agg(x.t ->> 'spoc_name'::text, ','::text) AS spoc_name,
            string_agg(x.t ->> 'spoc_email'::text, ','::text) AS spoc_email,
            string_agg(x.t ->> 'spoc_phone'::text, ','::text) AS spoc_phone
    FROM neo_customer.customer_branches cb_1
             CROSS JOIN LATERAL json_array_elements(cb_1.spoc_detail::json) x(t)
    GROUP BY cb_1.customer_id
    ) b ON b.customer_id = c.id
WHERE cb.is_main_branch = true
ORDER BY b.customer_id;

ALTER VIEW neo_customer.vw_company_list
  OWNER TO postgres;
  
  ------------------------------------
  
  
 CREATE OR REPLACE VIEW neo_customer.vw_oppurtunity (
    id,
    company_id,
    company_name,
    managed_by,
    lead_status_id,
    lead_status_name,
    business_vertical_id,
    business_vertical_name,
    functional_area_id,
    functional_area_name,
    industry_id,
    industry_name,
    labournet_entity_id,
    labournet_entity_name,
    opportunity_code,
    contract_id)
AS
SELECT o.id,
    o.company_id,
    c.company_name,
    o.managed_by,
    o.lead_status_id,
    ls.name AS lead_status_name,
    o.business_vertical_id,
    bv.name AS business_vertical_name,
    o.functional_area_id,
    f.name AS functional_area_name,
    o.industry_id,
    i.name AS industry_name,
    o.labournet_entity_id,
    le.name AS labournet_entity_name,
    o.opportunity_code,
    o.contract_id
FROM neo_customer.opportunities o
     LEFT JOIN neo_customer.companies c ON c.id = o.company_id
     LEFT JOIN neo_master.lead_statuses ls ON ls.id = o.lead_status_id
     LEFT JOIN neo_master.business_verticals bv ON bv.id = o.business_vertical_id
     LEFT JOIN neo_master.functional_areas f ON f.id = o.functional_area_id
     LEFT JOIN neo_master.industries i ON i.id = o.industry_id
     LEFT JOIN neo_master.labournet_entities le ON le.id = o.labournet_entity_id;
CREATE VIEW users.vw_address_book_contact_list (
    company_id,
    company_name,
    hr_name,
    designation,
    hr_phone,
    hr_email,
    industry_name,
    source)
AS
SELECT c.id AS company_id,
    c.company_name,
    c.hr_name,
    'HR'::text AS designation,
    c.hr_phone,
    c.hr_email,
    i.name AS industry_name,
    ls.name AS source
FROM neo_customer.companies c
     LEFT JOIN neo_master.industries i ON i.id = c.industry_id
     LEFT JOIN neo_master.lead_sources ls ON ls.id = c.lead_source_id;

ALTER VIEW users.vw_address_book_contact_list
  OWNER TO postgres;
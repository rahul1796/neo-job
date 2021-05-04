<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends MY_Model
{
    public function getCandidateCustomerQuery() {
      return $this->db->select('C.batch_code AS "batch_code",
                                C.candidate_name AS "candidate_name",
                                C.candidate_enrollment_id AS "enrollment_id",
                                J.job_title AS "job_title",
                                C.candidate_number AS "candidate_number",
                                COALESCE(C.date_of_birth::TEXT, \'NA\') AS "dob",
                                COALESCE(C.mobile, \'NA\') AS "MOBILE",
                                COALESCE(ET.name, neo_job.candidate_placement.employment_type, \'NA\') AS "employment_type",
                                CO.company_name AS "employer_name",
                                COALESCE(neo_job.candidate_placement.date_of_join::TEXT, \'NA\') AS "date_of_join",
                                COALESCE(neo_job.candidate_placement.offer_letter_date_of_join::TEXT, \'NA\') AS "offer_letter_date_of_join",
                                COALESCE(neo_job.candidate_placement.offer_letter_uploaded_on::TEXT, \'NA\') AS "offer_letter_uploaded_on",
                                COALESCE(neo_job.candidate_placement.ctc::TEXT, \'NA\') AS "annual_ctc",
                                (CASE
                                WHEN neo_job.candidate_placement.resigned_date IS NOT NULL THEN
                                \'RESIGNED\'
                                WHEN neo_job.candidate_placement.date_of_join IS NOT NULL THEN
                                \'JOINED\'
                                ELSE
                                \'OFFERED\'
                                END) AS "status"', FALSE)
              ->from('neo_job.candidate_placement')
              ->join('neo.candidates as C','C.id = neo_job.candidate_placement.candidate_id','INNER', FALSE)
              ->join('neo_master.employment_type as ET','ET.id = neo_job.candidate_placement.employment_type_id','LEFT', FALSE)
              ->join('neo_job.jobs as J','J.id = neo_job.candidate_placement.job_id','LEFT', FALSE)
              ->join('neo_customer.companies as CO','CO.id = J.customer_id','LEFT', FALSE);
    }

    public function getCandidateCustomer() {
        $data['total_records']=$this->getCandidateCustomerQuery()->count_all_results();
        $data['data'] = $this->getCandidateCustomerQuery()->get()->result();
        return $data;
    }
}

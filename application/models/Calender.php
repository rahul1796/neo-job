<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calender extends MY_Model
{
    protected $tableName = 'neo.events';
  // public function getDates() {
  //   return $this->db->select("string_agg(DISTINCT EXTRACT(DAY FROM neo_job.candidates_jobs_logs.schedule_date)::TEXT, ',') AS event_day")
  //   ->where('neo_job.candidates_jobs_logs.schedule_date IS NOT NULL')
  //   ->from('neo_job.candidates_jobs_logs')->get()->row();
  // }

  public function getCandidateDates($year, $month) {
    return $this->db->select("DISTINCT EXTRACT(DAY FROM neo_job.candidates_jobs_logs.schedule_date) AS event_day")
    ->where('neo_job.candidates_jobs_logs.schedule_date IS NOT NULL')
    ->where('EXTRACT(YEAR FROM neo_job.candidates_jobs_logs.schedule_date)  =', $year)
    ->where('EXTRACT(MONTH FROM neo_job.candidates_jobs_logs.schedule_date)  =', $month)
    ->from('neo_job.candidates_jobs_logs')->get_compiled_select();
  }

  public function getLeadDates($year, $month) {
    return $this->db->select("DISTINCT EXTRACT(DAY FROM neo_customer.lead_logs.schedule_date) AS event_day")
    ->where('neo_customer.lead_logs.schedule_date IS NOT NULL')
    ->where('EXTRACT(YEAR FROM neo_customer.lead_logs.schedule_date)  =', $year)
    ->where('EXTRACT(MONTH FROM neo_customer.lead_logs.schedule_date)  =', $month)
    ->from('neo_customer.lead_logs')->get_compiled_select();
  }

  public function getEventDates($year, $month) {
    return $this->db->select("DISTINCT EXTRACT(DAY FROM neo.events.event_start) AS event_day")
    ->where('neo.events.event_start IS NOT NULL')
    ->where('EXTRACT(YEAR FROM neo.events.event_start)  =', $year)
    ->where('EXTRACT(MONTH FROM neo.events.event_start)  =', $month)
    ->from('neo.events')->get_compiled_select();
  }

  public function getDates($year, $month) {
    $query = $this->db->query($this->getCandidateDates($year, $month).' UNION '. $this->getLeadDates($year, $month).' UNION '.$this->getEventDates($year, $month));
    return $query->result();
  }

  public function getEventsSchedules($date) {
    return $this->db->where("TO_CHAR(event_start,'dd-Mon-yyyy')", $date)->get($this->tableName)->result();
  }

  public function getCandidatesSchedules($date) {
    return $this->db->select("cjl.schedule_date as schedule_date, c.candidate_name as candidate_name,
    c.mobile as candidate_mobile, c.email as candidate_email,
    cus.customer_name as customer_name, cus.hr_email as hr_email, cus.hr_phone as hr_phone,
    cus.location as location, cus.address as address")
    ->from('neo_job.candidates_jobs_logs AS cjl')
    ->join('neo.candidates as c', 'cjl.candidate_id=c.id', 'LEFT')
    ->join('neo_job.jobs as j', 'cjl.job_id=j.id', 'LEFT')
    ->join('neo_customer.customers as cus', 'j.customer_id = cus.id', 'LEFT')
    ->where("TO_CHAR(cjl.schedule_date,'dd-Mon-yyyy')", $date)
    ->get()->result();
  }

  public function getLeadsSchedules($date) {
    return $this->db->select('logs.schedule_date as schedule_date, logs.name as meeting_person, logs.phone as contact_phone,
      logs.address as contact_address, logs.city as contact_city, logs.remarks as contact_remark, l.name as status,
       c.lead_managed_by as managed_by')
    ->from('neo_customer.lead_logs as logs')
    ->join('neo_customer.customers as c', 'logs.customer_id = c.id')
    ->join('neo_master.lead_statuses as l', 'logs.lead_status_id = l.id')
    ->where("TO_CHAR(logs.schedule_date,'dd-Mon-yyyy')", $date)
    ->get()->result();
  }

}

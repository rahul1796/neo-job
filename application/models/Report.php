<?php
class Report extends MY_Model {

  protected $table = 'neo.report_logs';
  private $user_id;

  public function __construct() {
    parent::__construct();
    $this->load->dbutil();
  }

  public function getRecPlaceUsers() {
    return $this->db->where_in('user_role_id', [11,14])->where('is_active', TRUE)->get('neo_user.users')->result();
  }

  public function getPlacementOfficers() {
    return $this->db->where('user_role_id', 14)->where('is_active', TRUE)->where_in('id', $this->session->userdata('user_hierarchy'))->get('neo_user.users')->result();
  }

  public function getUserLogInReport($data) {

    $select_column = "user_name AS \"USER NAME\", user_email AS \"USER EMAIL\", user_role AS \"USER ROLE\",
                  reporting_manager_name AS \"REPORTING MANAGER NAME\", reporting_manager_email AS \"REPOTING MANAGER EMAIL\",
                  reporting_manager_role AS \"REPOTING MANAGER ROLE\", session_id AS \"SESSION ID\",
                  login_datetime AS \"LOGIN DATE TIME\",
                  logout_datetime AS \"LOGOUT DATE TIME\",
                  browser_name AS \"BROWSER NAME\", ip_address AS \"IP ADDRESS\",
                  platform AS \"PLATFORM\"";

    $query = $this->db->query("SELECT {$select_column} FROM reports.fn_get_user_activity_report_data(?,?,?)",
              [
                $this->getDateWithMonthName($data['start_date']),
                $this->getDateWithMonthName($data['end_date']),
                $this->adminCloneforReportViewer(),
              ]);
    return $this->dbutil->csv_from_result($query);
  }

  public function getUsabilityReport($data) {

    $select_column = "sr_no as \"SR NO\", user_name as \"USER NAME\", user_email as \"USER EMAIL\",
                      user_role_name as \"USER ROLE\", log_date as \"DATE\", login_count as \"#LOGGED IN\",
                      lead_created_count as \"#LEAD CREATED\", lead_status_changed_count as \"#LEAD STATUS CHANGED\",
                      lead_converted_to_customer_count as \"#LEAD CONVERTED TO CUSTOMER\",
                      jobs_posted_count as \"#JOBS POSTED\", candidate_created_count as \"#CANDIDATE ADDED\",
                      jobs_applied_count as \"#JOBS APPLIED\", jobs_closed_count as \"#JOBS CLOSED\",
                      candidate_status_changed_count as \"#CANDIDATE STATUS CHANGED\"";

    $query = $this->db->query("SELECT {$select_column} FROM reports.fn_get_user_usage_report_data(?, ?, ?)",
                              [
                                $this->getDateWithMonthName($data['start_date']),
                                $this->getDateWithMonthName($data['end_date']),
                                $this->adminCloneforReportViewer(),
                              ]);

    return $this->dbutil->csv_from_result($query);
  }

  public function getClientTrackerReport() {
    $select_column = "sr_no as \"SR NO\", customer_name AS \"CUSTOMER NAME\",
                      created_user_name AS \"CUSTOMER CREATED BY\",
                      created_user_role AS \"USER ROLE\", active_status AS \"ACTIVE STATUS\",
                      business_vertical_name AS \"BUSSINESS VERTICAL NAME\",
                      business_practice_name AS \"BUSINESS PRACTICE NAME\", office_location AS \"OFFICE LOCATION\",
                      job_count AS \"JOBS COUNT\", requirement_count AS \"REQUIREMENT\",
                      interested_count AS \"INTERSTED\",
                      profile_submitted_count AS \"PROFILE SUBMITTED\",
                      pending_feedback_from_employer_count AS \"PENDING FEEDBACK\", profile_accepted_count AS \"PROFILE ACCEPTED\",
                      profile_rejected_count AS \"PROFILE REJECTED\", interview_scheduled_count \"INTERVIEW SCHEDULE\",
                      interview_attended_count AS \"INTERVIEW ATTENDED\",
                      interview_not_attended_count AS \"INTERVIEW NOT ATTENDED\",
                      selected_count AS \"SELECTED\", rejected_count AS \"REJECTED\",
                      offer_in_pipeline_count AS \"OFFER IN PIPELINE\", offerred_count AS \"OFFERED\",
                      offer_accepted_count AS \"OFFER ACCEPTED\", offer_rejected_count AS \"OFFER REJECTED\",
                      joined_count AS \"JOINED COUNT\", not_joined_count AS \"NOT JOINED COUNT\"";

    $query = $this->db->query("SELECT {$select_column} FROM reports.fn_get_client_tracker_report_data(?)",
                              [
                                $this->adminCloneforReportViewer(),
                              ]);

    return $this->dbutil->csv_from_result($query);
  }

  public function getLeadDetailsReport($data) {
    $select_column = "customer_name AS \"CUSTOMER NAME\",
		lead_status AS \"LEAD STATUS\",
		created_by AS \"CREATED BY\",
		created_on AS \"CREATED ON\",
		modified_on AS \"MODIFIED ON\",
		modified_by AS \"MODIFIED BY\",
		industry_name AS \"INDUSTRY\",
		handler_name AS \"HANDLER\",
		location_name AS \"LOCATION\",
		lead_source AS \"LEAD SOURCE\",
		hr_name AS \"HR NAME\",
		hr_email AS \"HR EMAIL\",
		hr_phone AS \"HR PHONE\"";

    $query = $this->db->query("SELECT {$select_column} FROM reports.fn_get_lead_detail_report_data(?,?,?)",
              [
                $this->getDateWithMonthName($data['start_date']),
                $this->getDateWithMonthName($data['end_date']),
                $this->adminCloneforReportViewer(),
              ]);
    return $this->dbutil->csv_from_result($query);

  }


    public function getPlacementDetailReport($data) {

      $select_column = "region_name AS \"REGION\", batch_code AS \"BATCH CODE\", batch_start_date AS \"BATCH START DATE\",
                        batch_end_date AS \"BATCH END DATE\", center_name AS \"CENTER NAME\", batch_customer_name AS \"IGS CUSTOMER NAME\",
                        batch_contract_id AS \"IGS CONTRACT ID\", candidate_name AS \"CANDIDATE NAME\", enrollment_no AS \"ENROLLMENT NO#\", date_of_birth AS \"DATE OF BIRTH\",
                        candidate_current_status AS \"CANDIDATE STATUS\", contact_no AS \"CONTACT NO\", interview_date AS \"INTERVIEWED DATE\",
                        date_of_join AS \"DATE OF JOINING\", customer_name AS \"CUSTOMER NAME\", job_location AS \"JOB LOCATION\",
                        job_title AS \"JOB TITLE\",
                        job_qualification_pack AS \"JOB QP\", business_vertical AS \"BUSINESS VERTICAL\",
                        job_created_by AS \"JOB CREATED BY\", job_created_by_user_role AS \"USER ROLE\",
                        employment_type AS \"EMPLOYMENT TYPE\", salary AS \"SALARY (INR)\", state_name AS \"STATE\",
                        district_name AS \"CITY\", pin_code AS \"PINCODE\", gender AS \"GENDER\",offer_letter_uploaded_date AS \"OFFER LATER UPLOADED DATE\", certification_status AS \"CERTIFICATION STATUS\"";

      $query = $this->db->query("SELECT {$select_column} FROM reports.fn_get_placement_detail_report_data(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                                [
                                  $this->getDateWithMonthName($data['start_date']),
                                  $this->getDateWithMonthName($data['end_date']),
                                  $data['candidate_status_id'],
                                  $data['gender_id'],
                                  $data['center_name'],
                                  $data['customer_id'],
                                  $data['batch_start_from_date'],
                                  $data['batch_start_to_date'],
                                  $data['batch_end_from_date'],
                                  $data['batch_end_to_date'],
                                  $data['qp_id'],
                                  $data['bv_id'],
                                  $data['state_id'],
                                  $data['district_id'],
                                  $data['pincode'],
                                  $data['employment_type'],
                                  $data['job_location'],
                                  $data['job_title'],
                                  $this->adminCloneforReportViewer(),
                                ]);

      return $this->dbutil->csv_from_result($query);
    }

    public function getJobDetailedReport($data) {

      $select_column = "industry AS \"INDUSTRY\" , job_qp AS \"JOB QP\", job_posted_by AS \"JOB POSTED BY\",
                        user_role AS \"USER ROLE\", job_title AS \"JOB TITLE\", business_vertical AS \"BUSINESS VERTICAL\",
                        job_location AS \"JOB LOCATION\", pin_code AS \"PINCODE\", no_of_vacancies AS \"#NO VACANCIES\", min_salary AS \"MIN SALARY PA\",
                        max_salary AS \"MAX SALARY PA\", education AS \"QUALIFICATION\",
                        min_age AS \"MIN AGE\", max_age AS \"MAX AGE\", min_experience AS \"MIN EXP\",
                        max_experience AS \"MAX EXP\", customer_name AS \"CUSTOMER\", key_skills AS \"KEY SKILLS\" ";

      $query = $this->db->query("SELECT {$select_column} FROM reports.fn_get_job_detailed_report_data(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)",
                                [
                                  $data['customer_id'],
                                  $data['industry_id'],
                                  $data['qp_id'],
                                  $data['user_id'],
                                  $data['job_title'],
                                  $data['bv_id'],
                                  $data['job_location'],
                                  $data['education_id'],
                                  $this->getDateWithMonthName($data['start_date']),
                                  $this->getDateWithMonthName($data['end_date']),
                                  $data['pincode'],
                                  $this->adminCloneforReportViewer()
                                ]);

      return $this->dbutil->csv_from_result($query);
    }

  private function getDateWithMonthName($val) {
    $date=date_create($val);
    return "'".date_format($date,'d-M-Y')."'";
    //return "'".date('d-M-Y')."'";
  }
  private function getDate() {

    return "'".date('d-m-Y')."'";
  }

  private function adminCloneforReportViewer() {
    if($this->session->userdata('usr_authdet')['user_group_id']==15) {
      return 1;
    }
    return $this->session->userdata('usr_authdet')['id'];
  }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

  private $reports = ['getUserLogInReport' => 'User Login Activity Report',
                      'getUsabilityReport' => 'User Usability Report',
                      'getLeadDetailsReport' => 'Lead Details Report',
                      'getClientTrackerReport'=>'Client Tracker Report',
                      'getPlacementDetailReport' => 'Placement Detail Report',
                      'getJobDetailedReport' => 'Job Detailed Report'];

  public function __construct() {
    parent::__construct();

    if($this->input->get('slug')=='getUserLogInReport' || $this->input->get('slug')=='getUsabilityReport') {
        $this->authorize(admin_only_reports());
    } else {
        if($this->input->get('slug')=='getPlacementDetailReport') {
          $this->authorize(reports_falcon_user());
        }
        else {
          $this->authorize(reports_falcon_user());;
        }
    }
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model('Report', 'report');
    $this->load->helper('download');
  }

  public function index() {

    $data['report_list']= $this->reports;
    $data['report'] = $this->input->get('slug');

    $this->load->view('layouts/header');
    if($data['report']=='getPlacementDetailReport'){
        $this->load->view('reports/placement_detail_report', $this->placementReportSearchOptions($data));
    }else if($data['report']=='getJobDetailedReport'){
        $this->load->view('reports/job_detail_report', $this->jobDetailReportSearchOptions($data));
    } else {
        $this->load->view('reports/index', $data);
    }

    $this->load->view('layouts/footer');
  }

  public function getUserLogInReport() {
    $data = $this->report->getUserLogInReport($this->searchFilters());
    $this->downloadRequest('UserLoginActivityReport', $data);
  }

  public function getUsabilityReport() {
    $data = $this->report->getUsabilityReport($this->searchFilters());
    $this->downloadRequest('UserUsabilityReport', $data);
  }

  public function getLeadDetailsReport(){
    $data = $this->report->getLeadDetailsReport($this->searchFilters());
    $this->downloadRequest('LeadDetailsReport', $data);
  }

  public function getClientTrackerReport() {
    $data = $this->report->getClientTrackerReport();
    $this->downloadRequest('ClientTrackerReport', $data);
  }

  public function getPlacementDetailReport() {
    $data = $this->report->getPlacementDetailReport($this->searchFiltersPlacementReport());
    $this->downloadRequest('PlacementDetailReport', $data);
  }

  public function getJobDetailedReport() {
    $data = $this->report->getJobDetailedReport($this->searchFiltersJobDetailedReport());
    $this->downloadRequest('JobDetailedReport', $data);
  }

  private function downloadRequest($file_name, $data) {
    $name = $file_name.'-'.date('d-M-Y').'.csv';
    force_download($name, $data);
    exit;
  }

  private function searchFilters() {
    $data['start_date'] = $this->input->get('start_date');
    $data['end_date'] = $this->input->get('end_date');
    return $data;
  }

  private function searchFiltersPlacementReport() {
    return $this->input->get();
  }

  private function searchFiltersJobDetailedReport() {
    return $this->input->get();
  }

  private function placementReportSearchOptions($data) {
    $data['customer_options'] = $this->report->allCustomers();
    $data['gender_options'] = $this->report->getGenders();
    $data['candidate_statuses_options'] = $this->report->getCandidateStatuses();
    $data['employment_type_options'] = $this->report->getEmploymentTypes();
    $data['center_options'] = $this->report->getCenters();
    $data['business_vertical_options'] = $this->report->getBusinessVerticals();
    $data['qp_options'] = $this->report->getQualificationPacks();
    $data['state_options'] = $this->report->getStates(99);
    return $data;
  }

  public function jobDetailReportSearchOptions($data) {
    $data['customer_options'] = $this->report->allCustomers();
    $data['qp_options'] = $this->report->getQualificationPacks();
    $data['industry_options'] = $this->report->getIndustries();
    $data['business_vertical_options'] = $this->report->getBusinessVerticals();
    $data['user_options'] = $this->report->getPlacementOfficers();
    $data['education_options'] = $this->report->getEducations();
    return $data;
  }
}

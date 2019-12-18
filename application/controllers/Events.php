<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Events extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model('Calender', 'calender');
  }

  public function index() {
    $data = array();
    $data = $this->fetchCalender($data);
    $prefs['template'] = $this->load->view('calenders/template', $data, true);
    $prefs['show_next_prev'] = TRUE;
    $prefs['day_type'] = 'short';

    $this->load->library('calendar', $prefs);

    $this->loadviews($data);
	}

  public function store() {
      $data['title'] = $this->input->post('title');
      $data['description'] = $this->input->post('description');
      $data['event_start'] = $this->input->post('start_date');
      $data['event_end'] = $this->input->post('end_date');

      $response['data']=$data;
      $response['status'] = false;
      $response['message'] = 'Error Creating Event, Please Try Again.';

      if ($this->calender->save($data)) {
        $response['status'] = true;
        $response['message'] = 'Event Created Successfully';
      }
      echo json_encode($response);
      exit;
  }

  public function getCalender($year, $month, $dates) {
    $data = array();
    foreach ($dates as $date) {
      $data[$date->event_day] = base_url('events/index/').$year."/".$month."/".$date->event_day;
    }
    return $data;
  }

  public function fetchCalender($data) {
    $year = date('Y');
    $month = date('m');

    if($this->uri->segment(3)!='' && strlen($this->uri->segment(3))==4 && $this->uri->segment(3)>1900 && $this->uri->segment(3)<=$year){
      $year = $this->uri->segment(3);
    }
    if($this->uri->segment(4)!='' && ($this->uri->segment(4)>0 && $this->uri->segment(4) < 13)){
      $month = $this->uri->segment(4);
    }
    // $month = $this->uri->segment(4) ?? date('m');
    // $year = $this->uri->segment(3) ?? date('Y');
    $data['month'] = $month;
    $data['year'] = $year;
    $dates = $this->calender->getDates($year, ltrim($month));
    $data['my_dates'] = $this->getCalender($year, $month, $dates);

    if($this->uri->segment(5)!='' && $this->uri->segment(5)>0 && $this->uri->segment(5)<=cal_days_in_month(CAL_GREGORIAN,$month,$year)) {
      $day = $this->uri->segment(5);
      $buildDate = $this->buildDate($year, $month, $day);
      // echo var_dump( $buildDate);
      // exit();
      $data['current_date'] = $buildDate;
      $data['data']['candidate_schedule'] = $this->calender->getCandidatesSchedules($buildDate);
      $data['data']['lead_schedule'] = $this->calender->getLeadsSchedules($buildDate);
      $data['data']['event_schedule'] = $this->calender->getEventsSchedules($buildDate);
    }

    return $data;
  }

  public function loadViews($data) {
    $this->load->view('layouts/header');
		$this->load->view('calenders/index', $data);
    $this->load->view('layouts/footer');
  }

  public function buildDate($year, $month, $day) {
    $date = date_create("$month/$day/$year");
    return date_format($date,"d-M-Y");
  }

}

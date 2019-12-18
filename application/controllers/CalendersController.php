<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CalendersController extends MY_Controller {

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

    $this->load->library('calendar', $prefs);

    $this->loadviews($data);
	}

  public function getCalender($year, $month, $dates) {
    $data = array();
    foreach ($dates as $date) {
      $data[$date->event_day] = base_url('CalendersController/index/').$year."/".$month."/".$date->event_day;
    }
    return $data;
  }

  public function fetchCalender($data) {
    $month = $this->uri->segment(4);
    $year = $this->uri->segment(3);
    $dates = $this->calender->getDates($year, ltrim($month));
    $data['my_dates'] = $this->getCalender($year, $month, $dates);

    if($this->uri->segment(5)!='') {
      $day = $this->uri->segment(5);
      $buildDate = $this->buildDate($year, $month, $day);
      // echo var_dump( $buildDate);
      // exit();
      $data['data']['candidate_schedule'] = $this->calender->getCandidatesSchedules($buildDate);
      $data['data']['lead_schedule'] = $this->calender->getLeadsSchedules($buildDate);
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

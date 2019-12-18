<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmployersController extends MY_Controller {

  public function __construct() {
    parent::__construct();
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model("Employer_model", "employer");
    $this->load->model('Sale', 'sale');
    //$this->user_logged_in = $this->session->userdata['usr_authdet'];
  }

  /*public function index() {
   $data['employers'] = $this->sale->allEmployers();
    $this->loadFormViews('index', $data);
	}*/

  public function index()
  {
    $user              = $this->pramaan->_check_module_task_auth(true);
    $data['page']      = 'index';
    $data['title']     = 'Customers';
    $this->load->view('index', $data);
  }

  


  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('employer/'.$action, $data);
    $this->load->view('layouts/footer');
  }

  private function configPagination($total_rows, $url) {
  $this->load->library('pagination');

  $config['base_url'] = $url;
  $config['total_rows'] = $total_rows;
  $config['uri_segment'] = 4;
  $config['per_page'] = 20;
  $config['reuse_query_string'] = true;
  $config['attributes'] = array('class' => 'paginate_button btn btn-md btn-primary', 'style'=>'margin:5px;');
  $config['full_tag_open'] = '<div class="table_paginate">';
  $config['full_tag_close'] = '</div>';
  $config['cur_tag_open'] = '<a class="paginate_button btn btn-md btn-warning current">';
  $config['cur_tag_close'] = '</a>';

  $this->pagination->initialize($config);
  }

  // public function candidate_ids($id) {
  //   echo var_dump($this->candidate->jobCandidateIDs($id));
  // }
  public function getSearchParams($id) {
    $search_data=[];
    foreach($this->search_fields as $fields) {
      $search_data[$fields] =  $this->input->get($fields) ?? '';
    }
    return $search_data;
  }


  private function candidateData($id=0) {
    $fields = array();
    $data = array();

    if ($id==0) {
      foreach($this->candidateFields as $candidateField) {
        $fields[$candidateField] = '';
      }
    } else {
      $fields = $this->candidate->find($id);
    }

    foreach($this->candidateFields as $candidateField) {
      $data[$candidateField] = $this->input->post($candidateField) ?? $fields[$candidateField];
    }
    return $data;
  }
}

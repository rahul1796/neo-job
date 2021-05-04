<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CandidatesController extends MY_Controller {

  protected $candidateFields= [ 'center_name', 'center_type', 'first_name', 'last_name', 'candidate_registration_id',
                              'candidate_enrollment_id', 'email', 'mobile_number', 'country_id', 'state', 'district_id',
                              'marital_status', 'gender_code', 'education_id', 'qualification_pack_id', 'main_state_id', 'state_name',
                              'main_district_id', 'district_name'];

  protected $exclude_fields=['main_state_id'=>'', 'state_name'=>'', 'main_district_id'=>'', 'district_name'=>''];

  protected $redirectUrl = 'partner/candidates';

  protected $gender = ['Male', 'Female', 'Other'];
  protected $marrige = ['Married', 'Unmarried'];

  protected $search_fields = ['search_name', 'search_phone', 'search_email', 'search_education'];

  private $msg= '';

  private $user_logged_in;

  public function __construct() {
    parent::__construct();
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model('Candidate', 'candidate');
    //$this->user_logged_in = $this->session->userdata['usr_authdet'];
  }

  public function index() {
    $this->load->view('layouts/header');
		$this->load->view('candidates/index');
    $this->load->view('layouts/footer');
	}

  public function create() {
    $data = $this->setData();
    $this->loadFormViews('create', $data);
  }

  public function store() {
    $data = $this->setData();
    if($this->validateRequest()){
      if($this->candidate->save(array_diff_key($data['data']['fields'], $this->exclude_fields))) {
        $this->msg = 'Candidate created successfully';
      } else {
        $this->msg = 'Error creating candidate, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $this->loadFormViews('create', $data);
    }
  }

  public function edit($id) {
    $data = $this->setData($id);
    $data['id'] = $id;
    $this->loadFormViews('edit', $data);
  }

  public function update($id) {

    $data = $this->setData($id);
    $data['id'] = $id;

    if($this->validateRequest()){
      // echo var_dump(array_diff($data['data']['fields'], $this->exclude_fields));
      // exit;
      if ($this->candidate->update($id, array_diff_key($data['data']['fields'], $this->exclude_fields))) {
        $this->msg = 'Candidate updated successfully';
      } else {
        $this->msg = 'Error updating candidate, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $this->loadFormViews('edit', $data);
    }
  }

  public function delete() {

  }

  public function candidateJobStatus() {
    $data['status_id']= $this->input->post('status_id');
    $data['candidate_id']= $this->input->post('candidate_id');
    $data['job_id']= $this->input->post('job_id');
    $data['updated_by'] = $this->session->userdata('usr_authdet')['id'];

    $remark = $this->input->post('remarks');
    $status = false;
    if($this->input->post('flag')=='insert') {
      $status = $this->candidate->addCandidateJob($data, $remark);
    } elseif ($this->input->post('flag')=='update') {
      $status = $this->candidate->updateCandidateJobStatus($data, $remark);
    }
    $response['status'] = $status;
    $response['msg'] = 'request reached successfully';
    echo json_encode($response);
    exit;
  }

  public function appliedCandidates($id, $page=0) {

    $search_data = $this->getSearchParams($id);
    //echo $this->candidate->jobCandidates($id, $page, $search_data);
    // echo $this->candidate->jobCandidateQuery($id, $page);
    // exit;
    $data['data']['search_data'] = $search_data;
    $url = base_url('CandidatesController/appliedCandidates/'.$id);
    $data['data']['education_options'] = $this->candidate->getEducation();
    $data['page'] = 'Applied Candidates';
    $data['appliedCandidates'] = $this->candidate->jobCandidates($id, $page, $search_data);
    $data['job_id']=$id;
    $data['job_status'] = $this->candidate->getStatus();
    $this->configPagination($this->candidate->jobCandidatesCount($id, $search_data)['total_row'], $url);
    $this->load->view('layouts/header');
		$this->load->view('candidates/applied_candidates', $data);
    $this->load->view('layouts/footer');
  }

  public function suggestedCandidates($id, $page=0) {

    $search_data = $this->getSearchParams($id);
        //echo $this->candidate->jobCandidates($id, $page, $search_data);
        // echo $this->candidate->candidatesByJobPreferenceCount($id, $search_data);
        //  exit;
    $data['data']['search_data'] = $search_data;
    $url = base_url('CandidatesController/suggestedCandidates/'.$id);
    $data['data']['education_options'] = $this->candidate->getEducation();
    $data['page'] = 'Suggested Candidates';
    $data['suggestedCandidates'] = $this->candidate->candidatesByJobPreference($id, $page, $search_data);
    $data['job_id']=$id;
    $data['job_status'] = $this->candidate->getStatus();
    $this->configPagination($this->candidate->candidatesByJobPreferenceCount($id, $search_data),$url);
    $this->load->view('layouts/header');
		$this->load->view('candidates/suggested_candidates', $data);
    $this->load->view('layouts/footer');
  }

  private function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->candidateData($id);
    $data['data']['education_options'] = $this->candidate->getEducation();
    $data['data']['countries_options'] = $this->candidate->getCountries();
    $data['data']['qualification_pack_options'] = $this->candidate->getQualificationPack();
    $data['data']['marrige_options'] = $this->marrige;
    $data['data']['gender_options'] = $this->gender;
    return $data;
  }

  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('center_name', 'Center Name', 'required');
    $this->form_validation->set_rules('center_type', 'Center Type', 'required');
    $this->form_validation->set_rules('first_name', 'First Name', 'required');
    $this->form_validation->set_rules('last_name', 'Last Name', 'required');
    $this->form_validation->set_rules('candidate_registration_id', 'Candidate Registration ID', 'required');
    $this->form_validation->set_rules('candidate_enrollment_id', 'Candidate Enrollment ID', 'required|numeric');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('mobile_number', 'Mobile Number', 'required|numeric|exact_length[10]');
    $this->form_validation->set_rules('country_id', 'Country', 'required|numeric');
    $this->form_validation->set_rules('state', 'State', 'required|numeric');
    $this->form_validation->set_rules('district_id', 'District', 'required|numeric');
    $this->form_validation->set_rules('marital_status', 'Marital Status', 'required');
    $this->form_validation->set_rules('gender_code', 'Gender', 'required');
    $this->form_validation->set_rules('qualification_pack_id', 'Qualification Pack', 'required|numeric');
    $this->form_validation->set_rules('education_id', 'Education', 'required|numeric');

    return $this->form_validation->run();
  }

  // public function getCountries() {
  //   echo json_encode($this->candidate->getCountries());
  //   exit;
  // }

  public function getStates($country_id) {
    echo json_encode($this->candidate->getStates($country_id));
    exit;
  }

  public function getDistricts($state_id) {
    echo json_encode($this->candidate->getDistricts($state_id));
    exit;
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('candidates/'.$action, $data);
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

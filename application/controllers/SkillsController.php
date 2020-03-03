<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SkillsController extends MY_Controller {

protected $skillFields= [ 'skill_name', 'skill_description', 'version', 'last_used_year', 'last_used_month',
                          'experience_year', 'experience_month'];

                          protected $redirectUrl = 'skillscontroller/';

 public function __construct() {
   parent::__construct();
   $this->load->model("Pramaan_model", "pramaan");
   $this->load->model('Skill', 'skill');
   //$this->user_logged_in = $this->session->userdata['usr_authdet'];
 }

  // public function index() {
  //   $data = $this->setData();
  //
  //   $this->load->model('Job', 'job');
  //   $data['jobs'] = $this->job->searchJob();
  //   $data['job_list'] = $this->load->view('jobs/job', $data, TRUE);
  //   $this->load->view('layouts/header');
  //   $this->load->view('jobs/index', $data);
  //   $this->load->view('layouts/footer');
  // }


    public function create($candidate_id) {
      $data = $this->setData($candidate_id);
      $this->loadFormViews('create', $data);
    }

    public function store($candidate_id) {
      $data = $this->setData($candidate_id);
      $status_code = 0;
      $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
      if($this->validateRequest()){
        if($this->skill->save($data['data']['fields'])) {
          $this->msg = 'Skill Added Successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error Adding Skill, please try again after sometime';
          $status_code = 0;
        }
        $this->session->set_flashdata('status', $this->msg);
        $this->session->set_flashdata('status_code', $status_code);
        redirect($this->redirectUrl.'/create/'.$data['data']['fields']['candidate_id'], 'refresh');

      } else {
      $this->loadFormViews('create', $data);

      }
    }

    public function edit($candidate_id, $id) {
      $data = $this->setData($candidate_id, $id);
      $data['id'] = $id;
      $data['data']['candidate_id'] = $candidate_id;
      $this->loadFormViews('edit', $data);
    }

    public function update($candidate_id, $id) {

      $data = $this->setData($candidate_id, $id);
      $status_code = 0;
      $data['id'] = $id;

      if($this->validateRequest()){
        // echo var_dump(array_diff($data['data']['fields'], $this->exclude_fields));
        // exit;
        if ($this->skill->update($id, $data['data']['fields'])) {
          $this->msg = 'Skill updated successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error updating Skill, please try again after sometime';
          $status_code = 0;
        }
        $this->session->set_flashdata('status', $this->msg);
        $this->session->set_flashdata('status_code', $status_code);
        //echo $this->redirectUrl.'create/'.$data['data']['fields']['candidate_id'];
        redirect($this->redirectUrl.'create/'.$data['data']['fields']['candidate_id'], 'refresh');
      } else {
      $this->loadFormViews('edit', $data);
      }
    }

    public function delete($candidate_id, $id) {
      $status_code = 0;
      if($this->skill->deleteCandidateAssociation($candidate_id, $id)) {
        $this->msg = 'Skill deleted successfully';
        $status_code = 1;
      } else {
          $this->msg = 'Error deleting skill';
          $status_code = 0;
      }
      $this->load->library('user_agent');
      $refer='';
      if ($this->agent->referrer()!='')
      {
          $refer =  $this->agent->referrer();
      }
      if(strpos($refer, 'create'))
      {
        $this->session->set_flashdata('status', $this->msg);
        $this->session->set_flashdata('status_code', $status_code);
        redirect($this->redirectUrl.'create/'.$candidate_id, 'refresh');
      } else {
        $this->session->set_flashdata('status', $this->msg);
        $this->session->set_flashdata('status_code', $status_code);
        redirect('candidatescontroller/show/'.$candidate_id.'?type=skill', 'refresh');
      }
    }


  private function validateRequest($data=null) {
    $current_year = date('Y');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('skill_name', 'Skill Name', 'required|max_length[30]');
    $this->form_validation->set_rules('skill_description', 'Skill Description', 'required|max_length[100]');
    $this->form_validation->set_rules('version', 'version', 'numeric');
    $this->form_validation->set_rules('last_used_year', 'Last Year Used', 'required|is_natural_no_zero|greater_than_equal_to[1995]|'."less_than_equal_to[{$current_year}]");
    $this->form_validation->set_rules('last_used_month', 'Last Month Used', 'required|is_natural_no_zero|less_than_equal_to[12]');
    $this->form_validation->set_rules('experience_year', 'Experience Year', 'required|is_natural_no_zero|less_than_equal_to[20]');
    $this->form_validation->set_rules('experience_month', 'Experience Month', 'required|is_natural_no_zero|less_than_equal_to[11]');
    $this->form_validation->set_rules('candidate_id', 'Candidate', 'required|numeric');
    return $this->form_validation->run();
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
    $this->load->view('skills/'.$action, $data);
    $this->load->view('layouts/footer');
  }

  private function candidateData($fields_array, $id=0) {
    $fields = array();
    $data = array();

    if ($id==0) {
      foreach($fields_array as $field_name) {
        $fields[$field_name] = '';
      }
    } else {
      $fields = $this->skill->find($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }


  private function setData($candidate_id, $id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->candidateData($this->skillFields, $id);
    $data['data']['fields']['candidate_id'] = $candidate_id;
    $data['data']['skills'] = $this->skill->allByCandidate($candidate_id);
    //$data['data']['functional_area_options'] = $this->job->getFunctionalAreas();
    //$data['data']['education_options'] = $this->job->getEducations();
    return $data;
  }

}

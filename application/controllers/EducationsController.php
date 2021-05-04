<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EducationsController extends MY_Controller {

  protected $education_fields = [ 'education_id', 'specialization', 'institution', 'location',
                                                          'from_year', 'to_year', 'year_of_passing', 'learning_type_id'];

protected $redirectUrl = 'educationscontroller/';

 public function __construct() {
   parent::__construct();
   $this->load->model("Pramaan_model", "pramaan");
   $this->load->model('Education', 'education');
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
        if($this->education->save($data['data']['fields'])) {
          $this->msg = 'Education Added Successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error Adding Education, please try again after sometime';
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
        if ($this->education->update($id, $data['data']['fields'])) {
          $this->msg = 'Education updated successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error updating Education, please try again after sometime';
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
      if($this->education->deleteCandidateAssociation($candidate_id, $id)) {
        $this->msg = 'Education details deleted successfully';
        $status_code = 1;
      } else {
          $this->msg = 'Error deleting details';
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
        redirect('candidatescontroller/show/'.$candidate_id, 'refresh');
      }
    }


  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('specialization', 'Specialization', 'required');
    $this->form_validation->set_rules('institution', 'Institution', 'required');
    $this->form_validation->set_rules('location', 'Location', 'required');
    $this->form_validation->set_rules('from_year', 'From Year', 'required|is_natural_no_zero|min_length[4]');
    $this->form_validation->set_rules('to_year', 'To Year', 'required|is_natural_no_zero|min_length[4]|callback_compare_number[from_year]');
    $this->form_validation->set_rules('year_of_passing', 'Year of Passing', 'required|is_natural_no_zero|matches[to_year]|min_length[4]');
    $this->form_validation->set_rules('learning_type_id', 'Learning Type', 'required|is_natural_no_zero');
    $this->form_validation->set_rules('candidate_id', 'Candidate', 'required|is_natural_no_zero');
    $this->form_validation->set_rules('education_id', 'Education', 'required|is_natural_no_zero');
    return $this->form_validation->run();
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
    $this->load->view('educations/'.$action, $data);
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
      $fields = $this->education->find($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }


  private function setData($candidate_id, $id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->candidateData($this->education_fields, $id);
    $data['data']['fields']['candidate_id'] = $candidate_id;
    $data['data']['educations'] = $this->education->allByCandidate($candidate_id);
    //$data['data']['functional_area_options'] = $this->job->getFunctionalAreas();
    $data['data']['education_options'] = $this->education->getEducations();
    $data['data']['learning_type_options'] = $this->education->getLearningTypes();
    return $data;
  }

}

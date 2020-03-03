<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmploymentsController extends MY_Controller {


  protected $employment_fields = [  'company_name', 'from', 'to', 'designation', 'location', 'ctc', 'gross_salary', 'currency',
                                    'address', 'job_profile', 'office_landline', 'employee_code', 'reason_for_leaving',
                                    'city', 'country_id', 'employment_type', 'skilling_type_id', 'employment_start_date',
                                    'current_employer', 'notice_period', 'joining_location', 'reporting_location'];

                                    // protected $employment_fields = [  'company_name', 'from', 'to', 'designation', 'country_id',
                                    //                                   'state_id', 'district_id', 'city', 'location', 'ctc', 'gross_salary', 'currency',
                                    //                                   'address', 'job_profile', 'office_landline', 'employee_code', 'reason_for_leaving',
                                    //                                   'current_employer', 'notice_period', 'joining_location', 'reporting_location'];
protected $exclude_fields=['main_state_id'=>'', 'state_name'=>'', 'main_district_id'=>'', 'district_name'=>'', 'action'=>''];

protected $redirectUrl = 'employmentscontroller/';

protected $bool = [0=> 'No', 1 => 'Yes'];

protected $employment_type = ['Self Employed', 'Employed'];

protected $skilling_type = [0=>'Pre Skilling', 1 => 'Post Skilling'];


 public function __construct() {
   parent::__construct();
   $this->load->model("Pramaan_model", "pramaan");
   $this->load->model('Employment', 'employment');
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
      $data['data']['fields']['action'] = 'create';
      $this->loadFormViews('create', $data);
    }

    public function store($candidate_id) {
      $data = $this->setData($candidate_id);
      $status_code = 0;
      $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
      $data['data']['fields']['action'] = 'create';
      if($this->validateRequest()){
        $data = $this->setCompanyName($data);
        $file_name = $this->addFileInfo([])['file_name'];
        if($file_name!='')
        {
            $data['data']['fields']['file_name'] = $file_name;
        }
        if($this->employment->save(array_diff_key($data['data']['fields'], $this->exclude_fields))) {
          $this->msg = 'Employment Added Successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error Adding Employment, please try again after sometime';
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
      $data['data']['fields']['action'] = 'edit';
      $data['data']['candidate_id'] = $candidate_id;
      $this->loadFormViews('edit', $data);
    }

    public function update($candidate_id, $id) {

      $data = $this->setData($candidate_id, $id);
      $status_code = 0;
      $data['id'] = $id;
      $data['data']['fields']['action'] = 'edit';
      if($this->validateRequest()){
        // echo var_dump(array_diff($data['data']['fields'], $this->exclude_fields));
        // exit;
        $data = $this->setCompanyName($data);

        $file_name = $this->addFileInfo([])['file_name'];
        if($file_name!='')
        {
            $data['data']['fields']['file_name'] = $file_name;
        }

        if ($this->employment->update($id, array_diff_key($data['data']['fields'], $this->exclude_fields))) {
          $this->msg = 'Employment updated successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error updating Employment, please try again after sometime';
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
      if($this->employment->deleteCandidateAssociation($candidate_id, $id)) {
        $this->msg = 'Employment details deleted successfully';
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
        redirect('candidatescontroller/show/'.$candidate_id.'?type=employment', 'refresh');
      }
    }


  private function validateRequest($data=null) {

    $current_year = date('Y');
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    if($this->input->post('employment_type')=='Self Employment' || $this->input->post('employment_type')=='Self Employed') {
        $this->form_validation->set_rules('company_name', 'Company Name', '');
        $this->form_validation->set_rules('ctc', 'Projected Annual Earning', 'required|is_natural|greater_than_equal_to[100]|less_than_equal_to[3000000]');
        if($this->input->post('action')=='create')
        {
            //$this->form_validation->set_rules('file_name', 'File', 'callback_check_file[file_name]');
        }
        $this->form_validation->set_rules('file_name', 'File', 'callback_check_file[file_name]');
        $this->form_validation->set_rules('employment_start_date', 'Employment Start Date', 'required');
    } else {
      $this->form_validation->set_rules('from', 'From Year', 'is_natural_no_zero|greater_than_equal_to[1995]|'."less_than_equal_to[{$current_year}]");
      $this->form_validation->set_rules('to', 'To Year', 'is_natural_no_zero|greater_than_equal_to[1995]|'."less_than_equal_to[{$current_year}]".'|callback_compare_number[from]');
      $this->form_validation->set_rules('company_name', 'Company Name', 'required');
      $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural');
      $this->form_validation->set_rules('city', 'City', 'required');
      $this->form_validation->set_rules('ctc', 'CTC', 'required|is_natural|greater_than_equal_to[100]|less_than_equal_to[3000000]');
      $this->form_validation->set_rules('office_landline', 'Office Landline', 'is_natural|max_length[10]');
      $this->form_validation->set_rules('employee_code', 'Employee Code', 'max_length[10]');
      $this->form_validation->set_rules('reason_for_leaving', 'Reason for Leaving', '');
      $this->form_validation->set_rules('current_employer', 'Current Employer', 'required');
      $this->form_validation->set_rules('notice_period', 'Notice Period', 'is_natural|max_length[2]');
      $this->form_validation->set_rules('address', 'Address', 'required');
      $this->form_validation->set_rules('joining_location', 'Joining Location', '');
      $this->form_validation->set_rules('reporting_location', 'Reporting Location', '');
      $this->form_validation->set_rules('state_id', 'State', 'is_natural');
      $this->form_validation->set_rules('district_id', 'District', 'is_natural');
      $this->form_validation->set_rules('designation', 'Job Role', 'required');
      // $this->form_validation->set_rules('gross_salary', 'Gross Salary', 'required|is_natural|greater_than_equal_to[100]|less_than_equal_to[3000000]');
      //$this->form_validation->set_rules('currency', 'Currency', '');
      //$this->form_validation->set_rules('job_profile', 'Job Profile', 'required');
    }

    $this->form_validation->set_rules('location', 'Location', 'required');
    $this->form_validation->set_rules('employment_type', 'Employment Type', 'required');
    $this->form_validation->set_rules('skilling_type_id', 'Skilling Type', 'required');

    $this->form_validation->set_rules('candidate_id', 'Candidate', 'required|is_natural|max_length[10]');
    return $this->form_validation->run();
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
    $this->load->view('employments/'.$action, $data);
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
      $fields = $this->employment->find($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }


  public function uploadDocument() {

    $id = $this->input->post('employment_id');

    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('file_name', 'File', 'callback_check_attachment[file_name]');
    if($this->form_validation->run()) {
      $request=[];
      $request = $this->addFileInfo($request);
      if($this->employment->update($id,$request)) {
        $data['message'] = 'File Uploaded Successfully';
        $data['errors'] = [];
      }

    } else {
      $data['message'] = 'Validation Errors';
      $data['errors'] = $this->form_validation->error_array();
    }
    $data['status'] = true;
    echo json_encode($data);
    exit;
  }

  public function addFileInfo($data) {
    $file_data = $this->uploadFile('file_name');
    if($file_data['status']==true) {
        $data['file_name'] = $file_data['upload_data']['file_name'];
    } else {
      $data['file_name']='';
    }
    return $data;
  }

  public function check_file($value, $input) {
    // echo var_dump($_FILES);
    // exit;
    //return empty($_FILES[$input]) ?   $this->form_validation->set_message('check_attachment', 'Select a File') : ;
    if($this->input->post('action')=='create'){
      if(empty($_FILES[$input]['name'])) {
        $this->form_validation->set_message('check_file', 'Select a File');
        return false;
      }
    }
    if((!empty($_FILES[$input]['name'])) && (!in_array($_FILES[$input]['type'], $this->allowedFileTypes))) {
      $this->form_validation->set_message('check_file', 'File Type is not allowed');
      return false;
    }
    if((!empty($_FILES[$input]['name'])) && ($_FILES[$input]['size']>=(3*1024*1024))) {
      $this->form_validation->set_message('check_file', 'File size too large. Max size 3 MB');
      return false;
    }
    return true;
  }

  public function getStates($country_id) {
    echo json_encode($this->employment->getStates($country_id));
    exit;
  }

  public function getDistricts($state_id) {
    echo json_encode($this->employment->getDistricts($state_id));
    exit;
  }

  public function setCompanyName($data) {
    if ($this->input->post('employment_type')=='Self Employment') {
      $data['data']['fields']['company_name'] = ' ';
    }
    return $data;
  }

  private function setData($candidate_id, $id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->candidateData($this->employment_fields, $id);
    $data['data']['fields']['candidate_id'] = $candidate_id;
    $data['data']['current_employer_options'] = $this->bool;
    $data['data']['countries_options'] = $this->employment->getCountries();
    $data['data']['employment_type_options'] = $this->employment->getEmploymentTypesActive();
    $data['data']['employments'] = $this->employment->allByCandidate($candidate_id);
    //$data['data']['functional_area_options'] = $this->job->getFunctionalAreas();
    //$data['data']['education_options'] = $this->job->getEducations();
    return $data;
  }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class JobsController extends MY_Controller {

  protected $jobFields= [ 'job_title', 'job_description', 'no_of_position', 'customer_id', 'client_manager_name', 'client_manager_email','client_manager_phone',
                          'applicable_consulting_fee', 'business_vertical_id', 'practice', 'office_location',
                          /*'key_skills',*/ 'domain_skills', 'soft_skills','type_of_workplace', 'functional_area_id', 'industry_id', 'primary_skills', 'reference_id',
                           'education_id', 'job_open_type_id', 'job_location', 'age_from', 'age_to', 'job_priority_level_id',
                           'experience_from', 'experience_to', 'relevent_experience_from', 'relevant_experience_to',
                           'offered_ctc_from', 'offered_ctc_to', 'shifts_available', 'preferred_nationality', 'gender_id',
                           'remarks', 'comments', 'target_employers', 'no_poach_companies', 'job_status_id',
                            'district_id', 'country_id', 'state_id','pincode', 'city',
                           'job_expiry_date', 'qualification_pack_id'];

   protected $JobStatusColors = array (
                               1 => 'info',
                               2 => 'success',
                               3 => 'danger',
                               4 => 'warning',
                               5 => 'default'
                           );

  protected $redirectUrl = 'partner/pramaan_jobs';

  public function __construct() {
   parent::__construct();
   $this->load->model("Pramaan_model", "pramaan");
   $this->load->model('Job', 'job');
   $this->load->model('Sale', 'sale');
    $this->load->model('Candidate', 'candidate');
   //$this->user_logged_in = $this->session->userdata['usr_authdet'];
  }

  public function index()
  {
     // $data = $this->setData();

     //$this->load->model('Job', 'job');

     $data['qualification_pack_options'] = $this->job->getQualificationPacks();
     $data['education_options'] = $this->job->getEducations();
     $data['joined_candidates'] = $this->job->getJoinedCandidateCount($id);
     $data['jobs'] = $this->job->searchJob(1);
     $data['job_list'] = $this->load->view('jobs/job', $data, TRUE);
     $this->load->view('layouts/header');
     $this->load->view('jobs/index', $data);
     $this->load->view('layouts/footer');
   }

   public function getSpocDetails($id) {
    echo json_encode($this->sale->getSpocsByCustomerID($id));
    exit;
  }


    public function create($id=0) {
      $this->authorize(job_add_roles());
      $data = $this->setData($id);
      $this->loadFormViews('create', $data);
    }

    public function jobStatus($data) {
      if($data['data']['fields']['business_vertical_id']==3){
          $data['data']['fields']['job_status_id'] = 2;
      } else {
        $data['data']['fields']['job_status_id'] = 1;
      }
      return $data;
    }

    public function store() {
      $this->authorize(job_add_roles());
      $data = $this->setData();
      $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
      $data['data']['fields']['updated_by'] =   $data['data']['fields']['created_by'];
      if($this->validateRequest()){
        $data = $this->jobStatus($data);
        $response = $this->job->save($data['data']['fields']);
        if($response['status']) {
          $this->msg = 'Job created successfully. (Job Code: <strong>'.$response['job_code'].'</strong>)';
        } else {
          $this->msg = 'Error creating Job, please try again after sometime';
        }
        $this->session->set_flashdata('status', $this->msg);
        redirect($this->redirectUrl, 'refresh');

      } else {
      $this->loadFormViews('create', $data);

      }
    }

    public function edit($id) {
      $this->authorize(job_edit_roles());
      $data = $this->setData($id);
      $data['id'] = $id;
      $this->loadFormViews('edit', $data);
    }

    public function update($id) {
      $this->authorize(job_edit_roles());
        $data = $this->setData($id);
        $data['id'] = $id;

        if($this->validateRequest()){
          $data['data']['fields']['updated_by'] = $this->session->userdata('usr_authdet')['id'];
          $data['data']['fields']['updated_at'] = date('Y-m-d H:i:s');
          $data = $this->jobStatus($data);
          $response = $this->job->update($id, $data['data']['fields']);
          if ($response['status']) {
            $this->msg = 'Job updated successfully. (Job Code: <strong>'.$response['job_code'].'</strong>)';
          } else {
            $this->msg = 'Error updating Job, please try again after sometime';
          }
          $this->session->set_flashdata('status', $this->msg);
          redirect($this->redirectUrl, 'refresh');
        } else {
        $this->loadFormViews('edit', $data);
        }
    }

    public function delete() {

    }


  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('job_title', 'Job Title', 'required');
    $this->form_validation->set_rules('job_description', 'Job Description', 'required');
    $this->form_validation->set_rules('no_of_position', 'No of Position', 'required|is_natural');
    $this->form_validation->set_rules('customer_id', 'Client', 'required|is_natural');
    $this->form_validation->set_rules('job_expiry_date', 'Job Expiry Date', 'required');
    $this->form_validation->set_rules('offered_ctc_from', 'Minimum CTC per Month', 'required|is_natural|greater_than_equal_to[10]');
    $this->form_validation->set_rules('offered_ctc_to', 'Maximum CTC per Month', 'required|is_natural|less_than_equal_to[50000000]|callback_compare_number[offered_ctc_from]');
    //$this->form_validation->set_rules('key_skills', 'Key Skills', 'required');
    $this->form_validation->set_rules('domain_skills', 'Domain Skills', 'required');
    $this->form_validation->set_rules('soft_skills', 'Soft Skills', 'required');
    $this->form_validation->set_rules('type_of_workplace', 'Type of Workplace', 'required');
    $this->form_validation->set_rules('education_id', 'Education', 'required|is_natural');
    $this->form_validation->set_rules('qualification_pack_id', 'Qualification Pack', 'required|is_natural');
    //$this->form_validation->set_rules('job_location', 'Job Location', 'required');
    $this->form_validation->set_rules('client_manager_name', 'Client Manager', 'required');
    $this->form_validation->set_rules('applicable_consulting_fee', 'Fees', 'is_natural');
    $this->form_validation->set_rules('business_vertical_id', 'Business Vertical', 'required|is_natural');
//    $this->form_validation->set_rules('practice', 'Practice', '');
    $this->form_validation->set_rules('office_location', 'Office Location', 'required');
    $this->form_validation->set_rules('functional_area_id', 'Functional Area', 'is_natural');
    $this->form_validation->set_rules('industry_id', 'Industry', 'required|is_natural');
    $this->form_validation->set_rules('primary_skills', 'Primary Skills', '');
    $this->form_validation->set_rules('reference_id', 'Job Profile ID', 'is_natural');
//    $this->form_validation->set_rules('job_open_type_id', 'Job Open Type', 'is_natural');
    $this->form_validation->set_rules('age_from', 'Min Age', 'required|is_natural|greater_than_equal_to[18]');
    $this->form_validation->set_rules('age_to', 'Max Age', 'required|is_natural|less_than_equal_to[65]|callback_compare_number[age_from]');
    $this->form_validation->set_rules('job_priority_level_id', 'Job Priority Level', 'is_natural');
    $this->form_validation->set_rules('experience_from', 'Min Experience', 'is_natural');
    $this->form_validation->set_rules('experience_to', 'Max Experience', 'is_natural|callback_compare_number[experience_from]');
    $this->form_validation->set_rules('relevent_experience_from', 'Min Relevent Experience', 'required|is_natural');
    $this->form_validation->set_rules('relevant_experience_to', 'Max Relevent Experience', 'required|is_natural|callback_compare_number[relevent_experience_from]');
    $this->form_validation->set_rules('shifts_available', 'Shifts Avaiable', '');
    $this->form_validation->set_rules('preferred_nationality', 'Preferred Nationality', '');
    $this->form_validation->set_rules('gender_id', 'Gender', 'required|is_natural');
    $this->form_validation->set_rules('job_status_id', 'Job Status', 'is_natural');
    $this->form_validation->set_rules('city', 'Town/Village', 'max_length[30]');
    $this->form_validation->set_rules('pincode', 'Pincode', 'required|is_natural_no_zero|exact_length[6]', array('is_natural_no_zero'=>'Enter Valid Pincode'));
    $this->form_validation->set_rules('district_id', 'District/City', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    //$this->form_validation->set_rules('location_id', 'Job Location', 'required|is_natural_no_zero');
    $this->form_validation->set_rules('remarks', 'Remarks', '');
    $this->form_validation->set_rules('comments', 'Comments', '');
    $this->form_validation->set_rules('target_employers', 'Target Employers', '');
    $this->form_validation->set_rules('no_poach_companies', 'No Poach Companies', 'is_natural');
    $this->form_validation->set_rules('recruiters[]', 'Recruiter', 'required');
    $this->form_validation->set_rules('placement_officers[]', 'Placement Officer', 'required');

    return $this->form_validation->run();
  }

  public function updateJobStatus() {
    $response_data['status'] = false;
    $data['job_status_id'] = $this->input->post('job_status_id');
    $data['created_by'] = $this->session->userdata('usr_authdet')['id'];
    if($this->job->updateJobStatus($this->input->post('job_id'), $data)) {
      $response_data['message'] = 'Status updated successfully';
      $response_data['status'] = true;
      $response_data['color'] = $this->JobStatusColors[$data['job_status_id']];
    }
    echo json_encode($response_data);
    exit;
  }

  public function AssignedUser() {
    $response_data['status'] = false;
    $data['assigned_user_id'] = $this->input->post('assigned_user_id');
    $data['created_by'] = $this->session->userdata('usr_authdet')['id'];
    if($this->job->assignuser($this->input->post('job_id'), $data)) {
      $response_data['message'] = 'User Assigned successfully';
      $response_data['status'] = true;
      $response_data['color'] = $this->JobStatusColors[$data['assigned_user_id']];
    }
    echo json_encode($response_data);
    exit;
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('jobs/'.$action, $data);
    $this->load->view('layouts/footer');
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

  private function candidateData($fields_array, $id=0) {
    $fields = array();
    $data = array();

    if ($id==0) {
      foreach($fields_array as $field_name) {
        $fields[$field_name] = '';
      }
      $data['recruiters'] = $this->input->post('recruiters') ?? [];
      $data['placement_officers'] = $this->input->post('placement_officers') ?? [];
    } else {
      $fields = $this->job->find($id);
      $data['recruiters'] = $this->input->post('recruiters') ?? $this->job->getAssociatedRecruiters($id);
      $data['placement_officers'] = $this->input->post('placement_officers') ?? $this->job->getAssociatedPO($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }


  private function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->candidateData($this->jobFields, $id);
    $data['data']['gender_options'] = $this->job->getGenders();
    $data['data']['industry_options'] = $this->job->getIndustries();
    $data['data']['customer_options'] = $this->sale->allCustomers();
    $data['data']['job_status_options'] = $this->job->getJobStatuses();
    $data['data']['qualification_pack_options'] = $this->job->getQualificationPacks();
    $data['data']['functional_area_options'] = $this->job->getFunctionalAreas();
    $data['data']['education_options'] = $this->job->getEducations();
    //$data['data']['location_options'] = $this->job->getLocations();
    $data['data']['business_vertical_options'] = $this->job->getBusinessVerticals();
    $data['data']['job_priority_level_options'] = $this->job->getJobPriorityLevels();
    $data['data']['job_open_type_options'] = $this->job->getJobOpenTypes();
    $data['data']['countries_options'] = $this->candidate->getCountries();
    $data['data']['recruiters_options'] = $this->job->getRecruiters();
    $data['data']['placement_officer_options'] = $this->job->getPlacementOfficers();
    $data['joined_candidates'] = $this->job->getJoinedCandidateCount($id);
    return $data;
  }

  // private function setData() {
  //   $this->load->model('Master', 'master');
  //   $data['education_options'] = $this->master->getEducation();
  //   $data['qualification_pack_options'] = $this->master->getQualificationPack();
  //   return $data;
  // }

  public function getJobs() {
    $page = $this->input->post('page');
    $request_data = $this->input->post() ?? [];
    $data['status'] = true;
    $data['message'] = 'Request reached successfully';
    $data['data'] = $this->job->searchJob($page, $request_data);
    //$respose_data['count'] = 3;
    //$respose_data = $this->load->view('jobs/job', $data, TRUE);
    echo json_encode($data);
    exit;
  }

  public function applyJob() {
    $this->load->library('form_validation');
    $response['status'] = false;
    $response['errors'] = array();
    $response['data'] = $this->input->post();
    $reponse['message'] = 'Some Error Occurred. Try again later';
    if($this->applyJobValidation()) {
      if($this->job->saveJobApplication($response['data'])){
        $response['status'] = true;
        $reponse['message'] = 'Job Application Successful';
      }
    } else {
      $response['errors'] = $this->form_validation->error_array();
    }
    echo json_encode($response);
  }

  public function applyJobValidation() {
    $this->form_validation->set_rules('job_id', 'Job', 'required|is_natural_no_zero');
    $this->form_validation->set_rules('name', 'Name', 'required');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('phone', 'Phone', 'required|is_natural|exact_length[10]');
    return $this->form_validation->run();
  }

  public function getStates($country_id) {
    echo json_encode($this->candidate->getStates($country_id));
    exit;
  }

  public function getDistricts($state_id) {
    echo json_encode($this->candidate->getDistricts($state_id));
    exit;
  }

  public function getjob($job_id) {
    $this->dd($this->job->createJobCode($job_id));
  }

  public function getPlacementDetails($candidate_id, $job_id) {
    $response['status'] = true;
    $response['message'] = 'Request Reached Successfully';
    $response['data'] = $this->job->getPlacementDetails($candidate_id, $job_id);
    echo json_encode($response);
    exit;
  }

  public function getJobHandlers($job_id) {
    $response['status'] = true;
    $response['message'] = 'Request Reached Successfully';
    $response['data'] = $this->job->getJobHandlers($job_id);
    echo json_encode($response);
    exit;
  }

}

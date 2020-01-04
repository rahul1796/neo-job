<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CandidatesController extends MY_Controller {

  protected $candidateFields= [ 'candidate_name', 'aadhaar_number', 'gender_id', 'prefered_job_location',
                                'expected_salary_percentage', 'date_of_birth', 'age', 'nationality',
                                'work_authorization_id', 'industry_id', 'source_id', 'remarks', 'email',
                                'mobile', 'landline', 'address', 'city','pincode', 'state_id', 'country_id',
                                'current_location', 'linkedin_url', 'facebook_url', 'twitter_url',
                                'marital_status_id', 'caste_category_id', 'religion_id', 'mt_type',
                               'main_state_id', 'state_name', 'main_district_id', 'district_name','district_id'];
                              // 'created_by', district_id,

  protected $exclude_fields=['main_state_id'=>'', 'state_name'=>'', 'main_district_id'=>'', 'district_name'=>''];

  protected $redirectUrl = 'partner/candidates';

  protected $search_fields = ['search_name', 'search_phone', 'search_email', 'search_education',
                              'search_candidate_type', 'search_employment_type', 'search_gender',
                              'search_course_name', 'search_center_name', 'search_batch_code',
                              'search_qualification_pack'];

  protected $candidate_types = ['MTO', 'MTS'];

  protected $employment_types = ['Self Employed', 'Employed'];

  private $msg= '';

  private $user_logged_in;

  public function __construct() {
    parent::__construct();
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model('Candidate', 'candidate');
    $this->load->model('Job', 'job');
    $this->load->model('QualificationPack', 'qp');
    $this->load->model('Skill', 'skill');
    $this->load->model('Employment', 'employment');
    $this->load->model('Education', 'education');
    $this->load->model('Document', 'document');
    //$this->user_logged_in = $this->session->userdata['usr_authdet'];
  }

  public function index() {
    $this->load->view('layouts/header');
    $this->load->view('candidates/index');
    $this->load->view('layouts/footer');
	}

  public function create() {
    $this->authorize(candidate_add_roles());
    $data = $this->setData();
    $data['data']['action']='create';
    $data['data']['id']=0;
    $this->loadFormViews('create', $data);
  }

  public function store() {
    $this->authorize(candidate_add_roles());
    $data = $this->setData();
    $data['data']['action']='create';
    $data['data']['id']=0;
    $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
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
    $this->authorize(candidate_update_roles());
    $this->validateIDs($this->candidate->find($id));
    $data = $this->setData($id);
    $data['id'] = $id;
    $data['data']['id']=$id;
    $data['data']['action']='edit';
    $this->loadFormViews('edit', $data);
  }

  public function update($id) {
    $this->authorize(candidate_update_roles());
    $this->validateIDs($this->candidate->find($id));
    $data = $this->setData($id);
    $data['id'] = $id;
    $data['data']['action']='edit';
    $data['data']['id']=$id;
    //$data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
    if($this->validateRequest()){
      // echo var_dump(array_diff($data['data']['fields'], $this->exclude_fields));
      // exit;
      $data['data']['fields']['modified_by'] = $this->session->userdata('usr_authdet')['id'];
      $data['data']['fields']['modified_at'] = date('Y-m-d H:i:s');
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


  public function show($id) {
    $this->authorize(candidate_view_profile_roles());
    $this->validateIDs($this->candidate->find($id));
    $data['candidate_details'] = $this->candidate->find($id);
    $data['data']['skills'] = $this->skill->allByCandidate($id);
    $data['data']['qps'] = $this->qp->allByCandidate($id);
    $data['data']['educations'] = $this->education->allByCandidate($id);
    $data['data']['employments'] = $this->employment->allByCandidate($id);
    $data['data']['documents'] = $this->document->allByCandidate($id);
    $data['data']['fields']['candidate_id'] = $id;
    $this->loadFormViews('show', $data);
  }

  public function delete() {

  }

  public function candidateJobStatus() {
    $data['candidate_status_id']= $this->input->post('candidate_status_id');
    $data['candidate_id']= $this->input->post('candidate_id');
    $data['job_id']= $this->input->post('job_id');
    if($this->input->post('schedule_date')!=''){
      $data['schedule_date']= $this->input->post('schedule_date');
    }
    $data['created_by'] = $this->session->userdata('usr_authdet')['id'];

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


  private function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->candidateData($this->candidateFields, $id);
    $data['data']['countries_options'] = $this->candidate->getCountries();
    $data['data']['marrige_options'] = $this->candidate->getMaritalStatuses();
    $data['data']['religion_options'] = $this->candidate->getReligions();
    $data['data']['gender_options'] = $this->candidate->getGenders();
    $data['data']['work_authorization_options'] = $this->candidate->getWorkAuthorizations();
    $data['data']['caste_category_options'] = $this->candidate->getCasteCategories();
    $data['data']['industry_options'] = $this->candidate->getIndustries();
    $data['data']['candidate_source_options'] = $this->candidate->getCandidateSources();
    $data['data']['candidate_type']=$this->candidate_types;
    return $data;
  }

  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('candidate_name', 'Candidate Name', 'required|regex_match[/^[a-zA-Z ]*$/]',['regex_match'=>'Enter valid name']);
    $this->form_validation->set_rules('candidate_number', 'Candidate Number','');
    $this->form_validation->set_rules('aadhaar_number', 'Aadhaar Number','required|is_natural_no_zero|callback_check_duplicate_aadhar[neo.candidates]|exact_length[12]', array('exact_length' => 'The Aadhaar Number field must be exactly 12 Digit in length.'));
    $this->form_validation->set_rules('gender_id', 'Gender', 'required|is_natural_no_zero');
    $this->form_validation->set_rules('prefered_job_location', 'Prefred Job Location', '');
    $this->form_validation->set_rules('expected_salary_percentage', 'Expected Salary Percentage', 'required|is_natural_no_zero|less_than_equal_to[100]');
    $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'required');
    $this->form_validation->set_rules('age', 'Age', 'required|is_natural_no_zero|greater_than_equal_to[18]', array('greater_than_equal_to'=>'Candidate must be at least 18 years old.'));
    $this->form_validation->set_rules('nationality', 'Nationality', 'required');
    $this->form_validation->set_rules('work_authorization_id', 'Work Authorization', 'is_natural_no_zero');
    $this->form_validation->set_rules('industry_id', 'Industry', 'required|is_natural');
    $this->form_validation->set_rules('source_id', 'Source', 'required|is_natural');
    $this->form_validation->set_rules('remarks', 'Remark', '');
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
    $this->form_validation->set_rules('mobile', 'Contact Number', 'required|is_natural_no_zero|exact_length[10]', array('exact_length' => 'The Mobile Number field must be exactly 10 Digit in length.'));
    $this->form_validation->set_rules('landline', 'Landline Number', 'is_natural');
    $this->form_validation->set_rules('address', 'Address', 'required');
    $this->form_validation->set_rules('city', 'Town/Village', 'max_length[30]');
    $this->form_validation->set_rules('pincode', 'Pincode', 'required|is_natural_no_zero|exact_length[6]', array('is_natural_no_zero'=>'Enter Valid Pincode'));
    $this->form_validation->set_rules('district_id', 'District/City', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('marital_status_id', 'Marital Status', 'required|is_natural');
    $this->form_validation->set_rules('caste_category_id', 'Caste Category', 'is_natural');
    $this->form_validation->set_rules('religion_id', 'Religion', 'is_natural');

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
    } else {
      $fields = $this->candidate->find($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }

  private function configPagination($total_rows, $url) {
    $this->load->library('pagination');

    $config['use_page_numbers'] = TRUE;
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

  public function appliedCandidates($id, $page=0) {
      $this->authorize(job_board_applied_roles());
      $this->validateIDs($this->job->find($id));
      $data['is_filled']=$this->candidate->getJobVacancyDetail($id);
  if($page<0) {
    $page = 0;
  }

  $search_data = $this->getSearchParams($id);
  //echo $this->candidate->jobCandidates($id, $page, $search_data);
  // echo $this->candidate->jobCandidateQuery($id, $page);
  // exit;
  $data['data']['candidate_type']=$this->candidate_types;
  $data['data']['search_data'] = $search_data;

  $data['data']['job'] = $this->job->findEmployer($id);
  $url = base_url('CandidatesController/appliedCandidates/'.$id);
  $data['data']['education_options'] = $this->candidate->getEducations();
  $data['data']['employment_type']=$this->candidate->getEmploymentTypes();
  $data['data']['gender_options']=$this->candidate->getGenders();
  $data['data']['qp_options']=$this->candidate->getQualificationPacks();
  $data['page'] = 'Applied Candidates';
  $data['data']['employer_type_list'] = $this->candidate->getEmploymentTypesActiveArray();
  $data['appliedCandidates'] = $this->candidate->jobCandidates($id, $page, $search_data);
  $data['job_id']=$id;
  $data['job_status'] = $this->candidate->getCandidateStatuses();
  // $data['candidates_count'] = $this->candidate->jobCandidatesCount($id, $search_data)['total_row'];

  $data['candidates_count'] = $this->candidate->jobCandidatesCount($id, $search_data);
  $this->configPagination($data['candidates_count'], $url);
  $this->load->view('layouts/header');
  $this->load->view('candidates/applied_candidates', $data);
  $this->load->view('layouts/footer');
}

public function suggestedCandidates($id, $page=0) {
  $this->authorize(job_board_recommanded_roles());
  $this->validateIDs($this->job->find($id));
  $data['is_filled']=$this->candidate->getJobVacancyDetail($id);
  if($page<0) {
    $page = 0;
  }
  if($this->job->jobDetailCheck($id)) {
    $this->msg = 'Kindly Update the Job with QualificationPack Detail and Education Detail to see Recommanded Candidates';
    $this->session->set_flashdata('status', $this->msg);
    redirect("JobsController/edit/$id", 'refresh');
  } else {

    $search_data = $this->getSearchParams($id);

        //echo $this->candidate->jobCandidates($id, $page, $search_data);
        // echo $this->candidate->candidatesByJobPreferenceCount($id, $search_data);
        //  exit;
    $data['candidate_type']=$this->candidate_types;
    $data['employment_type']=$this->candidate->getEmploymentTypes();
    $data['search_data'] = $search_data;
    $url = base_url('CandidatesController/suggestedCandidates/'.$id);
    $data['job'] = $this->job->findEmployer($id);
    $data['education_options'] = $this->candidate->getEducations();
    $data['gender_options']=$this->candidate->getGenders();
    $data['qp_options']=$this->candidate->getQualificationPacks();
    $data['page'] = 'Recommended Candidates';
     $data['employer_type_list'] = $this->candidate->getEmploymentTypesActiveArray();
    $data['suggestedCandidates'] = $this->candidate->candidatesByJobPreference($id, $page, $search_data);
    $data['job_id']=$id;
    $data['job_status'] = $this->candidate->getCandidateStatuses();
    $data['candidates_count'] = $this->candidate->candidatesByJobPreferenceCount($id, $search_data);

    $data['data']=$data;
    $this->configPagination($data['candidates_count'], $url);
    $this->load->view('layouts/header');
    $this->load->view('candidates/suggested_candidates', $data);
    $this->load->view('layouts/footer');
  }
}


public function mtoCandidates($id, $page=0) {
  $this->authorize(job_board_all_candidates_roles());
  $this->validateIDs($this->job->find($id));
  $data['is_filled']=$this->candidate->getJobVacancyDetail($id);
  if($page<0) {
    $page = 0;
  }
  if($this->job->jobDetailCheck($id)) {
    $this->msg = 'Kindly Update the Job with QualificationPack Detail and Education Detail to see Recommanded Candidates';
    $this->session->set_flashdata('status', $this->msg);
    redirect("JobsController/edit/$id", 'refresh');
  } else {

    $search_data = $this->getSearchParams($id);
    $data['candidate_type']=$this->candidate_types;
    $data['employment_type']=$this->candidate->getEmploymentTypesActive();
    $data['search_data'] = $search_data;
    $url = base_url('CandidatesController/mtocandidates/'.$id);
    $data['job'] = $this->job->findEmployer($id);
    $data['education_options'] = $this->candidate->getEducations();
    $data['gender_options']=$this->candidate->getGenders();
    $data['qp_options']=$this->candidate->getQualificationPacks();
    $data['page'] = 'MTO Candidates';
    $data['suggestedCandidates'] = $this->candidate->candidatesByMTO($id, $page, $search_data);
    $data['job_id']=$id;
    $data['job_status'] = $this->candidate->getCandidateStatuses();
    $data['candidates_count'] = $this->candidate->candidatesMTOCount($id, $search_data);

    $data['data']=$data;
    $this->configPagination($data['candidates_count'], $url);
    $this->load->view('layouts/header');
    $this->load->view('candidates/mto_candidates', $data);
    $this->load->view('layouts/footer');
  }
}


    public function getJoiningDetails() {
        $job_id=$this->input->post('job_id');
        $candidate_id=$this->input->post('candidate_id');
         $response['data'] = $this->job->findPlacementDetails($job_id,$candidate_id);
       if(!empty($response['data'])) {
           $response['status'] = TRUE;
            $response['msg'] = 'request reached successfully';
            echo json_encode($response);
            exit;
       }
        $response['status'] = FALSE;
            $response['msg'] = 'error';
            echo json_encode($response);
            exit;
    }


public function allCandidates($id, $page=0) {
  $this->authorize(job_board_all_candidates_roles());
  $this->validateIDs($this->job->find($id));
  $data['is_filled']=$this->candidate->getJobVacancyDetail($id);
  if($page<0) {
    $page = 0;
  }
  if($this->job->jobDetailCheck($id)) {
    $this->msg = 'Kindly Update the Job with QualificationPack Detail and Education Detail to see Recommanded Candidates';
    $this->session->set_flashdata('status', $this->msg);
    redirect("JobsController/edit/$id", 'refresh');
  } else {

    $search_data = $this->getSearchParams($id);
    $data['candidate_type']=$this->candidate_types;
    $data['employment_type']=$this->candidate->getEmploymentTypes();
    $data['gender_options']=$this->candidate->getGenders();
    $data['qp_options']=$this->candidate->getQualificationPacks();
    $data['search_data'] = $search_data;
    $url = base_url('CandidatesController/allcandidates/'.$id);
    $data['job'] = $this->job->findEmployer($id);
    $data['education_options'] = $this->candidate->getEducations();
    $data['page'] = 'All Candidates';
    $data['suggestedCandidates'] = $this->candidate->candidatesAll($id, $page, $search_data);
    $data['job_id']=$id;
    $data['job_status'] = $this->candidate->getCandidateStatuses();
    $data['candidates_count'] = $this->candidate->candidatesAllCount($id, $search_data);

    $data['data']=$data;
    $this->configPagination($data['candidates_count'], $url);
    $this->load->view('layouts/header');
    $this->load->view('candidates/all_candidates', $data);
    $this->load->view('layouts/footer');
  }

}

}

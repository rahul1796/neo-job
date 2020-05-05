<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class QualificationPacksController extends MY_Controller {

  protected $qp_fields = [ 'qualification_pack_id', 'batch_code', 'center_name', 'center_location',
                          'course_name', 'funding_source', 'certification_date'];

  protected $batch_fields = ['Project Name *'=>'project_name', 'CenterName *' => 'center_name',
                              'SubCenter ' => 'sub_center', 'Program Name *' => 'program_name',
                              'ClientName' => 'client_name', 'BatchCapacity' => 'batch_capacity',
                              'BatchCommittedStartDate(MM/DD/YYYY)' => 'bc_start_date',
                              'BatchCommittedEndDate(MM/DD/YYYY)' => 'bc_end_date',
                              'ActualStartDate(MM/DD/YYYY)' => 'actual_start_date',
                              'ActualEndDate(MM/DD/YYYY)' => 'actual_end_date',
                              'SBU' => 'sbu', 'MT_BatchCode' => 'batch_code',
                              'TrainerEmailId' => 'trainer_email_id'];


      protected $igs_candidate_fields = ['Centername'=>'center_name',
                            'Center Type' => 'center_type',
                            'FirstName' => 'candidate_name',
                            'Candidate Registration Id' => 'candidate_number',
                            'Candidate Enrollment Id' => 'candidate_enrollment_id',
                            'Mobile Number' => 'mobile',
                            'Email ID' => 'email',
                            'Address' => 'address',
                            'Country' => 'country',
                            'State' => 'state',
                            'District' => 'district',
                            'PinCode' => 'pincode',
                            'Gender' => 'gender_name',
                            'DateOfBirth' => 'date_of_birth',
                            'Document Type' => 'document_type',
                            'Document Number' => 'document_number',
                            'Employment Type' => 'employment_type',
                            'Candidate Created Date' => 'created_at',
                            'Father Name' => 'father_name',
                            'Family Contact Number' => 'family_contact_number',
                            'Language Known' => 'language_known',
                            'First Education' => 'education_name',
                            'First Education Year Of Passing' => 'year_of_passing',
                            'Religion' => 'religion',
                            'Age' => 'age',
                            'Marital Status' => 'marital_status',
                            'BatchCode' => 'batch_code',
                            'Technical Education' => 'technical_education',
                            'Preferred Location' => 'prefered_job_location',
                            'Willing to Travel' => 'willing_to_travel',
                            'Willing to Work in NightShifts' => 'willing_to_work_at_night',
                            'Computer Knowledge' => 'computer_knowledge',
                            'Enrollment Date' => 'enrollment_date',
                            'Caste Catagory' => 'caste_category'];


  protected $msg = '';

  protected $redirectUrl = 'qualificationpackscontroller/';

  protected $allowedFileTypes= ['csv' => 'application/vnd.ms-excel'];

 public function __construct() {
   parent::__construct();
   $this->load->model("Pramaan_model", "pramaan");
   $this->load->model('QualificationPack', 'qp');
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
        if($this->qp->save($data['data']['fields'])) {
          $this->msg = 'Qualification Pack Added Successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error Adding Qualification Pack, please try again after sometime';
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
        if ($this->qp->update($id, $data['data']['fields'])) {
          $this->msg = 'Qualification Pack updated successfully';
          $status_code = 1;
        } else {
          $this->msg = 'Error updating Qualification Pack, please try again after sometime';
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
      if($this->qp->deleteCandidateAssociation($candidate_id, $id)) {
        $this->msg = 'Qualification details deleted successfully';
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
        redirect('candidatescontroller/show/'.$candidate_id.'?type=qp', 'refresh');
      }
    }

    public function csv_form() {
      $this->authorize(batch_view_roles());
      $this->loadFormViews('csv_form');
    }

    public function uploadCSV() {
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
      $this->form_validation->set_rules('file_name', 'File', 'callback_check_attachment[file_name]');
      if($this->form_validation->run()) {
        $rows   = array_map('str_getcsv', file($_FILES['file_name']['tmp_name']));
        $header = array_shift($rows);
        $csv = [];
        //echo var_dump(array_filter($header));
        if(count(array_intersect(array_filter($header), array_keys($this->batch_fields)))==count( $this->batch_fields)){
          foreach($rows as $row) {
            if(!empty(array_filter($row))) {
                $new_row = [];
                foreach($header as $key=>$column) {
                    if(!empty($column) && array_key_exists($column, $this->batch_fields)) {
                        $new_row[$this->batch_fields[$column]] = $row[$key];
                    }
                }
                array_push($csv, $new_row);
            }
          }
          if(count($csv)>0) {
            $up_data = $this->qp->uploadBatchCSV($csv);
            $data['data'] = $up_data;
            if(count($up_data)>0) {
              $this->msg = 'Batch Processed Successfully';
            } else {
              $this->msg = 'Error Processing Batch Data';
            }
          } else {
            $this->msg = 'No data in file for upload';
          }
          // $this->session->set_flashdata('status', $this->msg);
          // redirect($this->redirectUrl.'csv_form/', 'refresh');
          $data['errors'] = [];
        } else {
          $data['errors'] = array('file_name'=>'Different CSV Uploaded');
        }

      } else {
        $this->msg = 'Validation Errors';
        $data['errors'] = $this->form_validation->error_array();
      }
      $data['status'] = true;
      $data['message'] = $this->msg;
      //$data['data'] = $this->job->searchJob($page, $request_data);
      //$respose_data['count'] = 3;
      //$respose_data = $this->load->view('jobs/job', $data, TRUE);
      echo json_encode($data);
      exit;
    }



    public function uploadCandidate() {
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
      $this->form_validation->set_rules('file_name', 'File', 'callback_check_attachment[file_name]');
      if($this->form_validation->run()) {
        $rows   = array_map('str_getcsv', file($_FILES['file_name']['tmp_name']));
        $header = array_shift($rows);
        $csv = [];
      //  echo var_dump($header);
        // echo var_dump(array_intersect($header, array_keys($this->igs_candidate_fields)));
        // exit;
        if(count(array_intersect($header, array_keys($this->igs_candidate_fields)))==count( $this->igs_candidate_fields)){

          foreach($rows as $row) {
            if(!empty(array_filter($row))) {
                $new_row = [];
                foreach($header as $key=>$column) {
                    if(!empty($column) && array_key_exists($column, $this->igs_candidate_fields)) {
                        $new_row[$this->igs_candidate_fields[$column]] = $row[$key];
                    }
                }
                array_push($csv, $new_row);
            }
          }
          if(count($csv)>0) {
            $up_data = $this->qp->uploadCandidates($csv);
            $data['data'] = $up_data;
            if(count($up_data)>0) {
              $this->msg = 'Candidate data Processed Successfully';
            } else {
              $this->msg = 'Error Processing Candidate Batch Data';
            }
          } else {
            $this->msg = 'No data in file for upload';
          }

          $data['errors'] = [];

        } else {
          $data['errors'] = array('file_name'=>'Different CSV Uploaded');
        }

      } else {
        $this->msg = 'Validation Errors';
        $data['errors'] = $this->form_validation->error_array();
      }
      $data['status'] = true;
      $data['message'] = $this->msg;
      // $data['data'] = $this->job->searchJob($page, $request_data);
      //$respose_data['count'] = 3;
      //$respose_data = $this->load->view('jobs/job', $data, TRUE);
      echo json_encode($data);
      exit;
    }


  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('center_name', 'Center Name', 'required');
    $this->form_validation->set_rules('center_location', 'Center Location', 'required');
    $this->form_validation->set_rules('batch_code', 'Batch Code', '');
    $this->form_validation->set_rules('qualification_pack_id', 'Qualification Pack', 'required|is_natural');
    $this->form_validation->set_rules('course_name', 'Course Name', 'required');
    $this->form_validation->set_rules('funding_source', 'Funding Source', '');
    $this->form_validation->set_rules('certification_date', 'Certification Date', 'required');
    $this->form_validation->set_rules('candidate_id', 'Candidate', 'required|is_natural');

    return $this->form_validation->run();
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
    $this->load->view('qualification_packs/'.$action, $data);
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
      $fields = $this->qp->find($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }


  private function setData($candidate_id, $id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->candidateData($this->qp_fields, $id);
    $data['data']['fields']['candidate_id'] = $candidate_id;
    $data['data']['qps'] = $this->qp->allByCandidate($candidate_id);
    //$data['data']['functional_area_options'] = $this->job->getFunctionalAreas();
    $data['data']['qualification_pack_options'] = $this->qp->getQualificationPacks();
    return $data;
  }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DocumentsController extends MY_Controller {

  protected $qp_fields = [ 'document_type_id', 'candidate_id', 'file_name'];

  protected $redirectUrl = 'DocumentsController/';

  protected $document_ids = [6, 7];

 public function __construct() {
   parent::__construct();
   $this->load->model("Pramaan_model", "pramaan");
   $this->load->model('Document', 'document');

  $this->load->library('form_validation');
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

          if(!$this->document->checkDuplicate($candidate_id, $this->input->post('document_type_id'))
            && !in_array($this->input->post('document_type_id'),$this->document_ids)) {
              $this->msg = 'Duplicate Document';
              $this->session->set_flashdata('status', $this->msg);
              redirect($this->redirectUrl.'/create/'.$data['data']['fields']['candidate_id'], 'refresh');
          }


          $data = $this->addFileInfo($data);
          // echo var_dump($data['data']['fields']);
          // exit;
          if($this->document->save($data['data']['fields'])) {
            $this->msg = 'Document uploaded successfully';
            $status_code = 1;
          } else {
            $this->msg = 'Error uploading document, please try again after sometime';
            $status_code = 0;
          }
          $this->session->set_flashdata('status', $this->msg);
          $this->session->set_flashdata('status_code', $status_code);
          redirect($this->redirectUrl.'/create/'.$data['data']['fields']['candidate_id'], 'refresh');

        } else {
        $this->loadFormViews('create', $data);

        }
    }

    public function delete($candidate_id, $id) {
      $status_code = 0;
      if($this->document->deleteCandidateAssociation($candidate_id, $id)) {
        $this->msg = 'Document deleted successfully';
        $status_code = 1;
      } else {
          $this->msg = 'Error deleting document';
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
        redirect('candidatescontroller/show/'.$candidate_id.'?type=doc', 'refresh');
      }
    }


  private function validateRequest($data=null) {
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('document_type_id', 'Document Type', 'required|is_natural');
    $this->form_validation->set_rules('candidate_id', 'Candidate', 'required|is_natural');
    $this->form_validation->set_rules('file_name', 'File', 'callback_check_attachment[file_name]');
    //return $this->uploadFile($this->input->post('file_name'));
    return $this->form_validation->run();
  }

  public function addFileInfo($data) {
    $file_data = $this->uploadFile('file_name');
    if($file_data['status']==true) {
        $data['data']['fields']['file_name'] = $file_data['upload_data']['file_name'];
    }
    return $data;
  }

  public function check_attachment($value, $input) {

    //return empty($_FILES[$input]) ?   $this->form_validation->set_message('check_attachment', 'Select a File') : ;
    if(empty($_FILES[$input]['name'])) {
      $this->form_validation->set_message('check_attachment', 'Select a File');
      return false;
    }
    if(!in_array($_FILES[$input]['type'], $this->allowedFileTypes)) {
      $this->form_validation->set_message('check_attachment', 'File Type is not allowed');
      return false;
    }
    return true;
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
    $this->load->view('documents/'.$action, $data);
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
    $data['data']['documents'] = $this->document->allByCandidate($candidate_id);
    //$data['data']['functional_area_options'] = $this->job->getFunctionalAreas();
    $data['data']['document_options'] = $this->document->getDocumentTypes();
    return $data;
  }

}

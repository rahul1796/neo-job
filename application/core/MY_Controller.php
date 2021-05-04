<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    protected $allowedFileTypes= ['doc' =>'application/msword',
                                  'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                                  'png' => 'image/png',
                                  'pdf' => 'application/pdf',
                                  'jpg' => 'image/jpeg',
                                  'jpeg' => 'image/jpeg',
                                  'ppt' => 'application/vnd.ms-powerpoint',
                                  'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation'];

    public function __construct() {
        parent::__construct();

        $this->load->helper('role_helper');
        if(!isset($this->session->userdata['usr_authdet']) && $this->uri->segment(1) != 'UsersController'
            && $this->uri->segment(2) != 'authenticateEmail' && $this->uri->segment(1) != 'jobs'
            && $this->uri->segment(2) != 'getJobs' && $this->uri->segment(2) != 'applyJob') {

          redirect(base_url(), 'refresh');
        }
        if(isset($this->session->userdata['usr_authdet']) && $this->uri->segment(1) == 'jobs') {
          redirect(base_url(), 'refresh');
        }
    }

    protected function emailConfig() {
      $config['protocol'] = 'smtp';
      $config['smtp_host'] = 'smtp.office365.com';
      $config['smtp_user'] = 'neo.helpdesk@labournet.in';
      $config['smtp_pass'] = 'Labnet@123';
      $config['smtp_port'] = '587';
      $config['mailtype'] = 'text';
      $config['priority'] =1;
      $config['newline'] = "\r\n";
      $config['smtp_crypto'] = 'tls';
      return $config;
    }

    public function check_attachment($value, $input) {
      // echo var_dump($_FILES);
      // exit;
      //return empty($_FILES[$input]) ?   $this->form_validation->set_message('check_attachment', 'Select a File') : ;
      if(empty($_FILES[$input]['name'])) {
        $this->form_validation->set_message('check_attachment', 'Select a File');
        return false;
      }
      if(!in_array($_FILES[$input]['type'], $this->allowedFileTypes)) {
        $this->form_validation->set_message('check_attachment', 'File Type is not allowed');
        return false;
      }
      if($_FILES[$input]['size']>=(3*1024*1024)) {
        $this->form_validation->set_message('check_attachment', 'File size too large. Max size 3 MB');
        return false;
      }
      return true;
    }

    public function compare_number($value, $min_value) {
      $error_message = array ('offered_ctc_from' => 'Must be greater than min CTC ',
                              'relevent_experience_from' => 'Must be greater than Relevent Experience From',
                              'experience_from' => 'Must be greater than Experience From',
                              'from_year' => 'Must be greater than Year/Date From',
                              'from' => 'Must be greater than Year/Date From',
                              'age_from' => 'Must be greater than min Age');
      if($value < $this->input->post($min_value)) {
        $this->form_validation->set_message('compare_number', $error_message[$min_value]);
        return false;
      }
      return true;
    }

    public function check_duplicate_email($value, $table) {
      if($_POST['action']!='edit') {
        $count = $this->db->where('LOWER(email)', strtolower($value))->limit(1)->get($table)->num_rows();
        if($count >0) {
          $this->form_validation->set_message('check_duplicate_email', 'Duplicate Found');
          return false;
        }
      }
      return true;
    }

    public function check_duplicate_aadhar($value, $table) {
        if($_POST['id']!=0){
          $this->db->where('id !=', $_POST['id']);
        }
        $count = $this->db->where('aadhaar_number', $value)->limit(1)->get($table)->num_rows();
        if($count >0) {
          $this->form_validation->set_message('check_duplicate_aadhar', 'Duplicate Found');
          return false;
        }

      return true;
    }

    public function check_duplicate_employee_id($value, $table) {
      if($_POST['action']=='create' && $value!='') {
          $count = $this->db->where('employee_id', $value)->limit(1)->get($table)->num_rows();
          if($count >0) {
            $this->form_validation->set_message('check_duplicate_employee_id', 'Duplicate Found');
            return false;
          }
      } else if($_POST['action']=='edit' && $value!='') {
        $count = $this->db->where('employee_id', $value)->where('email!=', $_POST['email'])->get($table)->num_rows();
        if($count ==1) {
          $this->form_validation->set_message('check_duplicate_employee_id', 'Duplicate Found');
          return false;
        }
      }
      return true;
    }

    public function check_duplicate_fields($value) {
      if(isset($_POST['spoc_detail'])) {
        $count = 0;
        foreach($_POST['spoc_detail'] as $post_data){
          if((trim($post_data['spoc_phone'])!='' && $post_data['spoc_phone'] == $value) || (trim($post_data['spoc_email'])!='' && $post_data['spoc_email']==$value)){
              $count++;
          }
        }
        if($count>1) {
          $this->form_validation->set_message('check_duplicate_fields', 'Duplicate Found');
          return false;
        }
      }
      return true;
    }

    public function uploadFile($name) {
      $data['status'] = false;
      $data['message'] = 'No file Attached';
      $data['errors'] = array();
      $data['upload_data'] = array();

      if(!empty($_FILES[$name])) {

          $config['upload_path']          = 'documents/';
          $config['allowed_types']        = 'pdf|jpg|jpeg|png|docx|doc|ppt|pptx|xlsx|xls';
          $config['max_size']             = 4096;
          $config['encrypt_name']         = false;

          $this->load->library('upload', $config);

          if ( ! $this->upload->do_upload($name))
          {
                  $data['message'] = 'Error Uplaoding File';
                  $data['errors'] = $this->upload->display_errors();
          }
          else
          {
                  $data['status'] = true;
                  $data['message'] = 'File uploaded successfully';
                  $data['upload_data'] = $this->upload->data();
          }
          return $data;
        }
        return $data;
      }

      public function authorize($data){
        $user_group_id = $this->session->userdata('usr_authdet')['user_group_id'];
        if(!in_array($user_group_id, $data)){
          $this->session->set_flashdata('status', 'You are not authorised to access that page');
          redirect('/pramaan/dashboard', 'refresh');
        }
      }

      public function validateIDs($data) {
        if(empty($data)) {
          $this->session->set_flashdata('status', "Can't process Request, Try again later");
          redirect('/pramaan/dashboard', 'refresh');
        }
      }

      public function dd($data){
        echo var_dump($data);
        exit;
      }
}

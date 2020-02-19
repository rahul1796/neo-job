<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CentersController extends MY_Controller {

  protected $dbFields= [ 'center_id', 'center_name', 'center_type', 'status', 'address', 'location',
                            'city', 'pincode', 'district_id', 'state_id', 'country_id', 'region_id'];

  protected $center_fields = ['CenterID'=>'center_id', 'CenterName' => 'center_name',
                              'CenterType' => 'center_type', 'Status' => 'status',
                              'Address' => 'address', 'City' => 'city',
                              'District' => 'district', 'State' => 'state',
                              'PINCode' => 'pincode'];

  protected $allowedFileTypes= ['csv' => 'application/vnd.ms-excel'];

  protected $redirectUrl = 'centerscontroller/index';

  protected $msg = '';

  public function __construct() {
    parent::__construct();
    $this->load->model("Pramaan_model", "pramaan");
      $this->load->model('Center', 'center');
      //$this->load->library('session');
    //$this->load->helper('custom_functions_helper');
  }

  public function index() {
    $this->authorize(add_edit_view_center_roles());
    $this->load->view('layouts/header');
		$this->load->view('centers/index');
    $this->load->view('layouts/footer');
	}

  public function create() {
    $this->authorize(add_edit_view_center_roles());
    $data = $this->setData();
    $this->loadFormViews('create', $data);
  }

  public function store() {
    // echo var_dump($_POST);
    // exit;
    $this->authorize(add_edit_view_center_roles());
    $status_code = 0;
    $data = $this->setData();
    $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
    $data['data']['fields']['updated_by'] = $data['data']['fields']['created_by'];
    if($this->validateRequest()){
      //$center_managers = $this->input->post('center_managers');
      if($this->center->save($data['data']['fields'])) {
        $this->msg = 'Center created successfully';
        $status_code = 1;
      } else {
        $this->msg = 'Error creating Center, please try again after sometime';
        $status_code = 0;
      }
      $this->session->set_flashdata('status', $this->msg);
      $this->session->set_flashdata('status_code', $status_code);
       $status_code = 0;
      redirect($this->redirectUrl, 'refresh');

    } else {
    $this->loadFormViews('create', $data);

    }
  }

  public function edit($id) {
    $this->authorize(add_edit_view_center_roles());
    $data = $this->setData($id);
    $data['id'] = $id;
    $this->loadFormViews('edit', $data);
  }

  public function update($id) {
    $this->authorize(add_edit_view_center_roles());
    $status_code = 0;
    $data = $this->setData($id);
    $data['id'] = $id;
    if($this->validateRequest()){
      $data['data']['fields']['updated_by'] = $this->session->userdata('usr_authdet')['id'];
      $data['data']['fields']['updated_at'] = date('Y-m-d H:i:s');
      if ($this->center->update($id, $data['data']['fields'])) {
        $this->msg = 'Center updated successfully';
        $status_code = 1;
      } else {
        $this->msg = 'Error updating center, please try again after sometime';
        $status_code = 0;
      }
      $this->session->set_flashdata('status', $this->msg);
      $this->session->set_flashdata('status_code', $status_code);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $this->loadFormViews('edit', $data);
    }
  }


  public function show($id) {

  }

  public function delete() {

  }

  private function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->centerData($this->dbFields, $id);
    $data['data']['countries_options'] = $this->center->getCountries();
    $data['data']['center_types_options'] = $this->center->getCenterTypes();
    $data['data']['center_managers_options'] = $this->center->usersByCenterManagerRole();
    $data['data']['regions_options'] = $this->center->getRegions();
    //$data['data']['user_roles_options'] = $this->user->getUserRoles();
    return $data;
  }

  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('center_id', 'Center ID', 'required|is_natural_no_zero|max_length[6]');
    $this->form_validation->set_rules('center_name', 'Center Name', 'required');
    $this->form_validation->set_rules('status', 'Status', '');
    $this->form_validation->set_rules('center_type', 'Center Type', 'required');
    $this->form_validation->set_rules('address', 'Address', 'required|max_length[150]');
    $this->form_validation->set_rules('location', 'Location', 'required|max_length[50]');
    $this->form_validation->set_rules('center_managers[]', 'Center Managers', '');
    $this->form_validation->set_rules('city', 'City/Town/Village', 'required|max_length[30]');
    $this->form_validation->set_rules('pincode', 'Pincode', 'required|is_natural_no_zero|exact_length[6]', array('is_natural_no_zero'=>'Enter Valid Pincode'));
    $this->form_validation->set_rules('district_id', 'District', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('region_id', 'Region', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select Region'));

    return $this->form_validation->run();
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('centers/'.$action, $data);
    $this->load->view('layouts/footer');
  }


  private function centerData($fields_array, $id=0) {
    $fields = array();
    $data = array();

    if ($id==0) {
      foreach($fields_array as $field_name) {
        $fields[$field_name] = '';
      }
    $data['center_managers'] = $this->input->post('center_managers') ?? [];
    } else {
      $fields = $this->center->find($id);
      $data['center_managers'] = $this->input->post('center_managers') ?? $this->center->getAssociatedUsers($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
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
      if(count(array_intersect(array_filter($header), array_keys($this->center_fields)))==count( $this->center_fields)){
        foreach($rows as $row) {
          if(!empty(array_filter($row))) {
              $new_row = [];
              foreach($header as $key=>$column) {
                  if(!empty($column) && array_key_exists($column, $this->center_fields)) {
                      $new_row[$this->center_fields[$column]] = $row[$key];
                  }
              }
              array_push($csv, $new_row);
          }
        }
        if(count($csv)>0) {
          $up_data = $this->center->uploadCenterCSV($csv);
          $data['data'] = $up_data;
          if(count($up_data)>0) {
            $this->msg = 'Center Processed Successfully';
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

}

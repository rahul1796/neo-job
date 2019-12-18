<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends MY_Controller {

  protected $locationFields= [ 'location', 'pincode', 'city', 'district_id', 'state_id', 'country_id'];
                              // 'created_by', district_id,

  private $msg= '';
  protected $redirectUrl='master/index';

  private $user_logged_in;

  public function __construct() {
    parent::__construct();
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model("Master_model", "master");
    //$this->user_logged_in = $this->session->userdata['usr_authdet'];
  }

  public function index() {
    $data['locations'] = $this->master->getLocations();
    $this->load->view('layouts/header');
		$this->load->view('masters/index', $data);
    $this->load->view('layouts/footer');
	}

  public function create() {
    $data = $this->setData();
    $this->loadFormViews('create', $data);
  }

  public function store() {
    $data = $this->setData();
    $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
    $data['data']['fields']['modified_by'] = $this->session->userdata('usr_authdet')['id'];
    if($this->validateRequest()){
      if($this->master->save($data['data']['fields'])) {
        $this->msg = 'Location created successfully';
      } else {
        $this->msg = 'Error creating location, please try again after sometime';
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
    //$data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
    if($this->validateRequest()){
      // echo var_dump(array_diff($data['data']['fields'], $this->exclude_fields));
      // exit;
      $data['data']['fields']['modified_by'] = $this->session->userdata('usr_authdet')['id'];
      $data['data']['fields']['modified_at'] = date('Y-m-d H:i:s');
      if ($this->master->update($id, $data['data']['fields'])) {
        $this->msg = 'Location updated successfully';
      } else {
        $this->msg = 'Error updating Location, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $this->loadFormViews('edit', $data);
    }
  }

  public function delete() {

  }

  private function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->masterData($this->locationFields, $id);
    $data['data']['countries_options'] = $this->master->getCountries();
    return $data;
  }

  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('location', 'Location', 'required|max_length[50]');
    $this->form_validation->set_rules('city', 'City/Town/Village', 'required|max_length[30]');
    $this->form_validation->set_rules('pincode', 'Pincode', 'required|is_natural_no_zero|exact_length[6]', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('district_id', 'District', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    return $this->form_validation->run();
  }

  // public function getCountries() {
  //   echo json_encode($this->master->getCountries());
  //   exit;
  // }

  public function getStates($country_id) {
    echo json_encode($this->master->getStates($country_id));
    exit;
  }

  public function getDistricts($state_id) {
    echo json_encode($this->master->getDistricts($state_id));
    exit;
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('masters/'.$action, $data);
    $this->load->view('layouts/footer');
  }


  private function masterData($fields_array, $id=0) {
    $fields = array();
    $data = array();

    if ($id==0) {
      foreach($fields_array as $field_name) {
        $fields[$field_name] = '';
      }
    } else {
      $fields = $this->master->find($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }

}

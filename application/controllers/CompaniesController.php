<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CompaniesController extends MY_Controller {

  protected $redirectUrl = 'companiescontroller/index';

  public function __construct() {
    parent::__construct();
    $this->load->helper('inflector');
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model('Company', 'company');
    $this->load->model('Candidate', 'candidate');
  }

  public function index() {
    $this->loadFormViews('index');
  }

  public function create() {
    $data = $this->setData();
    $data['data']['action'] = 'create';
    $this->loadFormViews('create', $data);
  }

  public function store() {
    if($this->validateRequest()){
      $data = $this->input->post();
      if($this->company->save($data)) {
        $this->msg = 'Company created successfully';
      } else {
        $this->msg = 'Error creating company, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $data['data']['action'] = 'create';
    $data = $this->setData();
    $this->loadFormViews('create', $data);
    }
  }

  public function edit($company_id) {
    $data = $this->setData($company_id);
    $data['id'] = $company_id;
    $this->loadFormViews('edit', $data);
  }

  public function update($company_id) {
    if($this->validateRequest()){
      $data = $this->input->post();
      if ($this->company->update($company_id, $data)) {
        $this->msg = 'Lead updated successfully';
      } else {
        $this->msg = 'Error Lead candidate, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
      $data = $this->setData($company_id);
      $data['id'] = $company_id;
      $this->loadFormViews('edit', $data);
    }
  }

  public function show($company_id) {

  }

  public function destroy($company_id) {

  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('companies/'.$action, $data);
    $this->load->view('layouts/footer');
  }

  public function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->saleData($this->company->fillable, $id);
    $data['data']['location_fields'] = $this->locationData($this->location->fillable, $id);
    $data['data']['lead_status_options'] = $this->company->getLeadStatuses();
    $data['data']['lead_source_options'] = $this->company->getLeadSources();
    $data['data']['industry_options'] = $this->company->getIndustries();
    $data['data']['business_practice_options'] = $this->company->getBusinessPractices();
    $data['data']['business_vertical_options'] = $this->company->getBusinessVerticals();
    $data['data']['functional_area_options'] = $this->company->getFunctionalAreas();
    $data['data']['countries_options'] = $this->company->getCountries();
    $data['data']['customer_type_options'] = $this->company->getLeadType();
    return $data;
  }


    private function saleData($fields_array, $id=0) {
      $fields = array();
      $data = array();

      if ($id==0) {
        foreach($fields_array as $field_name) {
          $fields[$field_name] = '';
        }

      } else {
        $fields = $this->company->find($id);
      }

      foreach($fields_array as $field_name) {
        $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
      }
      return $data;
    }

    private function locationData($fields_array, $id=0) {
      $fields = array();
      $data = array();

      if ($id==0) {
        foreach($fields_array as $field_name) {
          $fields[$field_name] = '';
        }
        $fields['spoc_detail'] = [];
      } else {
        $fields = $this->company->findLocation(['customer_id'=>$id, 'is_main_branch' => TRUE]);
        $fields['spoc_detail'] = json_decode($fields['spoc_detail'], true);
      }

      foreach($fields_array as $field_name) {
        $data[$field_name] = $this->input->post($field_name) ?? ($fields[$field_name] ?? '');
      }
    //  echo var_dump($data);
      return $data;
    }


    private function validateRequest($data=null) {
      $this->load->library('form_validation');

      $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');

      $this->form_validation->set_rules('lead_type_id', 'Lead Type', 'required');
      $this->form_validation->set_rules('lead_source_id', 'Lead Source', 'required|is_natural');
      $this->form_validation->set_rules('company_name', 'Company Name', 'required|min_length[3]');
      $this->form_validation->set_rules('company_description', 'Company Description', 'required');
      $this->form_validation->set_rules('landline', 'Landline', 'is_natural|max_length[12]');
      $this->form_validation->set_rules('address', 'Address', 'required');
      $this->form_validation->set_rules('hr_name', 'HR Name', '');
      $this->form_validation->set_rules('hr_email', 'HR Email', 'valid_email');
      $this->form_validation->set_rules('hr_phone', 'HR Phone', 'is_natural|exact_length[10]');
      $this->form_validation->set_rules('hr_designation', 'HR Designation', 'max_length[30]');
      $this->form_validation->set_rules('skype_id', 'Skype ID', '');
      $this->form_validation->set_rules('annual_revenue', 'Annual Revenue', 'is_natural|max_length[12]');
      $this->form_validation->set_rules('fax_number', 'Fax', 'is_natural|max_length[10]');
      $this->form_validation->set_rules('functional_area_id', 'Functional Area', 'required|is_natural');
      $this->form_validation->set_rules('industry_id', 'Industry', 'required|is_natural');
      $this->form_validation->set_rules('tagert_employers', 'Target Employer', '');
      $this->form_validation->set_rules('website', 'website', 'valid_url');
      $this->form_validation->set_rules('remarks', 'remarks', '');

      //Branch Location Validation
      $this->form_validation->set_rules('city', 'Town/Village', 'max_length[30]');
      $this->form_validation->set_rules('pincode', 'Pincode', 'required|is_natural_no_zero|exact_length[6]', array('is_natural_no_zero'=>'Enter Valid Pincode'));
      $this->form_validation->set_rules('district_id', 'District/City', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
      $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
      $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));

      //Multiple Spoc Validation
      foreach($this->input->post('spoc_detail') as $i=>$spoc) {
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_name]", 'Spoc Name', 'required');
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_email]", 'Spoc Email', 'required|valid_email|callback_check_duplicate_fields');
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_phone]", 'Spoc Phone', 'required|is_natural_no_zero|exact_length[10]|callback_check_duplicate_fields');
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_designation]", 'Spoc Designation', 'required|max_length[30]');
      }
      return $this->form_validation->run();
    }

    ///////////////////////SUMIT//////////////////////////

    public function getCompanyList()
  {
    $requestData= $_REQUEST;
    $resp_data=$this->company->getCompanyList($requestData);
    echo json_encode($resp_data);
  }

}

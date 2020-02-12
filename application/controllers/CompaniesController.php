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
    $this->load->model('Sale', 'sale');
    $this->load->helper('download');
   // $this->load->library('CSVReader');
    //$this->load->library('M_pdf','mpdf');
  }

  public function index() {
    $this->authorize(company_view_roles());
    $data['company_name_options'] = $this->sale->getCompanyNames();
    $data['lead_source_options'] = $this->sale->getLeadSources();
    $data['state_options'] = $this->sale->getStates();
    $data['spoc_name_list_options'] = $this->sale->getCompanySpocName();
    $data['spoc_email_list_options'] = $this->sale->getCompanySpocEmail();
    $data['spoc_phone_list_options'] = $this->sale->getCompanySpocPhone();
    $data['industries_list_options'] = $this->sale->getIndustries();
    $data['functional_area_list_options'] = $this->sale->getFunctionalAreas();
    $this->loadFormViews('index', $data);

  }

  public function create() {
    $this->authorize(company_add_roles());
    $data = $this->setData();
    $data['data']['action'] = 'create';
    $this->loadFormViews('create', $data);
  }

  public function store() {
    $this->authorize(company_add_roles());
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
    $this->authorize(company_update_roles());
    $data = $this->setData($company_id);
    $data['id'] = $company_id;
    $this->loadFormViews('edit', $data);
  }

  public function update($company_id) {
    $this->authorize(company_update_roles());
    if($this->validateRequest()){
      $data = $this->input->post();
      if ($this->company->update($company_id, $data)) {
        $this->msg = 'Company updated successfully';
      } else {
        $this->msg = 'Error Company updating, please try again after sometime';
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
    $resp_data=$this->company->getCompanyData($requestData);
    echo json_encode($resp_data);
  }

  public function getOpportunityDetail($company_id=0)
	{
		//$this->pramaan->_check_module_task_auth(false);
		$opportunity_results=$this->company->getOpportunityData($company_id);
		echo json_encode($opportunity_results);
  }

  public function getContractDetail($company_id=0)
	{
		//$this->pramaan->_check_module_task_auth(false);
		$contract_results=$this->company->getContractData($company_id);
		echo json_encode($contract_results);
  }

  public function exportDataCsv($company_id=0) {
    $opportunity_results=$this->company->getOpportunityList($company_id);
    $this->downloadRequest('Opportunity', $opportunity_results);
  }

  public function exportContractDataCsv($company_id=0) {
    $opportunity_results=$this->company->getContractList($company_id);
    $this->downloadRequest('Opportunity', $opportunity_results);
  }

  private function downloadRequest($file_name, $data) {
    $name = $file_name.'-'.date('d-M-Y').'.csv';
    force_download($name, $data);
    exit;
  }
  

}

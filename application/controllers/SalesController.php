<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SalesController extends MY_Controller {


  protected $customerFields = ['lead_name', 'lead_source_id', 'lead_managed_by', 'lead_phone', 'lead_email',
                              'customer_name','lead_type_id', 'customer_description', 'landline',
                              'spoc_name', 'spoc_email', 'spoc_phone', 'hr_name', 'hr_email', 'hr_phone', 'hr_designation',
                              'skype_id', 'no_of_employees', 'annual_revenue', 'business_value',
                              'fax_number', 'functional_area_id', 'lead_status_id','industry_id',
                              'no_of_users', 'business_vertical_id', 'business_practice_id', 'tagert_employers',
                              'no_poach_companies', 'website', 'remarks',
                              'main_state_id', 'state_name', 'main_district_id'];

  protected $locationFields = ['address', 'location','city', 'pincode', 'district_id', 'state_id', 'country_id',  'spoc_detail'];

  protected $fee_type= ['0'=>'Percentage', '1'=>'Flat'];

  protected $commercials_fields = ['onboarding_fee', 'sourcing_fee', 'monthly_service_fee', 'reimbursement_fee', 'absorption_fee'];

  protected $commercial_sub_fields = ['title', 'value', 'customer_id', 'fee_type', 'option_remarks', 'remarks', 'created_by'];

  protected $exclude_fields=['main_state_id'=>'', 'state_name'=>'', 'main_district_id'=>'', 'district_name'=>'', 'spoc'=>''];

  protected $redirectUrl = 'leads/index/1';

  protected $search_fields = ['search_name', 'search_phone', 'search_email', 'search_education'];

  private $msg= '';

  private $user_logged_in;


  public function __construct() {
    parent::__construct();
    $this->load->helper('inflector');
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model('Sale', 'sale');
    $this->load->model('Candidate', 'candidate');
    //$this->user_logged_in = $this->session->userdata['usr_authdet'];
  }

  public function index($page=0) {
    $this->authorize(lead_view_roles());
    $pagination_url = base_url('leads/index');
    $this->configPagination(count($this->sale->allLeads()), $pagination_url);
    $data['leads'] = $this->sale->Leads($page);
    $data['lead_status_options'] = $this->sale->getLeadStatuses();
    $data['data']['placement_officer_options'] = $this->sale->getPlacementOfficers();
    $data['business_vertical_options'] = $this->sale->getBusinessVerticals();
    $data['lead_source_options'] = $this->sale->getLeadSources();
    $data['lead_managed_by_options'] = $this->sale->getLeadManagedby();
    $data['state_options'] = $this->sale->getStates();
    $data['customer_name_options'] = $this->sale->getLeadCustomerNames();
    $data['spoc_name_list_options'] = $this->sale->getSpocName();
    $data['spoc_email_list_options'] = $this->sale->getSpocEmail();
    $data['spoc_phone_list_options'] = $this->sale->getSpocPhone();
    $this->load->view('layouts/header');
    $this->load->view('sales/index', $data);
    $this->load->view('layouts/footer');
	}

  public function create() {
    $this->authorize(lead_add_roles());
    $data = $this->setData();
    $data['data']['action'] = 'create';
    $this->loadFormViews('create', $data);
  }

  public function store() {
    // echo var_dump($_POST);
    // exit;
    $this->authorize(lead_add_roles());
    $data = $this->setData();
    $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
    $data['data']['fields']['updated_by'] = $this->session->userdata('usr_authdet')['id'];
    if($this->validateRequest()){
      if($this->sale->save_with_location(array_diff_key($data['data']['fields'], $this->exclude_fields),$data['data']['location_fields'] )) {
        $this->msg = 'Lead created successfully';
      } else {
        $this->msg = 'Error creating Lead, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $data['data']['action'] = 'create';
    $this->loadFormViews('create', $data);
    }
  }

  public function edit($id) {
    $this->authorize(lead_update_roles());
    $data = $this->setData($id);
    $data['id'] = $id;
    $this->loadFormViews('edit', $data);
  }

  public function update($id) {
    // echo var_dump($_POST);
    // exit;
    $this->authorize(lead_update_roles());
    $data = $this->setData($id);
    $data['id'] = $id;
    $data['data']['fields']['updated_by'] = $this->session->userdata('usr_authdet')['id'];
    $data['data']['fields']['updated_at'] = date('Y-m-d H:i:s');
    if($this->validateRequest()){
      // echo var_dump(array_diff($data['data']['fields'], $this->exclude_fields));
      // exit;
      if ($this->sale->update_with_location($id, array_diff_key($data['data']['fields'], $this->exclude_fields), $data['data']['location_fields'])) {
        $this->msg = 'Lead updated successfully';
      } else {
        $this->msg = 'Error Lead candidate, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $this->loadFormViews('edit', $data);
    }
  }

  public function delete() {

  }


  public function commercials_documents($id) {
    $data = $this->set_commercial_document_data($id);
    //$data['commercials_base'] = $this->commercials_fields;
    $this->loadFormViews('commercials_documents',$data);
  }

  private function set_commercial_document_data($id) {
    $data['id'] = $id;
    $data['legal_verified'] = false;
    $data['fee_types_option'] = $this->fee_type;
    $data['remark_options'] = $this->sale->getCommercialRemarkTypes();
    $data['commercials'] = $this->commericalData($this->commercial_sub_fields, $id);
    $data['documents'] = $this->sale->findDocument($id);
    if (count($this->sale->getCommercials($id))==5 && count($data['documents'])==1) {
      $data['legal_verified'] = true;
    }
    return $data;
  }

  public function verify_documents_commercial($id) {
    $status = $this->input->get('status');
    if ($this->sale->verfied_customer($id, $status)) {
      if($status=='accept') {
          $this->msg = 'Documents & Commercials Approved. Lead Converted to Customer';
      } else {
        $this->msg = 'Documents & Commercials Rejected. Status updated for Lead';
      }
    } else {
      $this->msg = 'Error verifiying documents';
    }
    $this->session->set_flashdata('status', $this->msg);
    redirect($this->redirectUrl, 'refresh');
  }

  public function commericals_store($id) {
    //echo var_dump($this->input->post());
  //  $data = $this->input->post('commercial');
    //
    $data = $this->set_commercial_document_data($id);
    if($this->validateCommercial()){
      $data_document['customer_id'] = $id;
      $data_document['created_by'] = $this->session->userdata('usr_authdet')['id'];
      $data_document = $this->addFileInfo($data_document);
      if ($this->sale->saveCommercials($id, $data['commercials'], $data_document)) {
        $this->msg = 'Commercial data updated successfully';
      } else {
        $this->msg = 'Error saving commercial, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect(base_url().'leads/commercials_documents/'.$id, 'refresh');
    }
    $this->loadFormViews('commercials_documents',$data);
  }

    public function validateCommercial() {
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
      if($this->input->post('action')=='create'|| ($this->input->post('action')=='edit' && isset($_FILES['file_name']))) {
          $this->form_validation->set_rules('file_name', 'File', 'callback_check_attachment[file_name]');
      }
      for($i=0; $i<=4; $i++) {
        //protected $commercial_sub_fields = ['title', 'value', 'customer_id', 'fee_type', 'created_by'];
          $this->form_validation->set_rules("commercial[{$i}][{$this->commercial_sub_fields[0]}]", 'Title', 'required');
          if($this->input->post("commercial[{$i}][{$this->commercial_sub_fields[3]}]")==0) {
              $this->form_validation->set_rules("commercial[{$i}][{$this->commercial_sub_fields[1]}]", 'Value', 'required|is_natural_no_zero|less_than_equal_to[100]');
          } else {
            $this->form_validation->set_rules("commercial[{$i}][{$this->commercial_sub_fields[1]}]", 'Value', 'required|is_natural_no_zero|less_than_equal_to[999999]');
          }
          $this->form_validation->set_rules("commercial[{$i}][{$this->commercial_sub_fields[2]}]", 'Customer', 'required|is_natural_no_zero');
          $this->form_validation->set_rules("commercial[{$i}][{$this->commercial_sub_fields[3]}]", 'Fee Type', 'required');
          if($this->input->post("commercial[{$i}][{$this->commercial_sub_fields[3]}]")=='0') {
              $this->form_validation->set_rules("commercial[{$i}][{$this->commercial_sub_fields[4]}]", 'Remark', 'required');
          } else {
            $this->form_validation->set_rules("commercial[{$i}][{$this->commercial_sub_fields[5]}]", 'Remark', '');
          }
      }
      return $this->form_validation->run();
    }

    private function validateDocumentRequest() {
      $this->load->library('form_validation');
      $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
      $this->form_validation->set_rules('file_name', 'File', 'callback_check_attachment[file_name]');
      //return $this->uploadFile($this->input->post('file_name'));
      return $this->form_validation->run();
    }

  public function documents_store($id) {
    $data_store['customer_id'] = $id;
    $data_store['created_by'] = $this->session->userdata('usr_authdet')['id'];

    if($this->validateDocumentRequest()) {
      $data_store = $this->addFileInfo($data_store);
      if ($this->sale->saveDocument($id, $data_store)) {
        $this->msg = 'Document updated successfully';
      } else {
        $this->msg = 'Error uploading document, please try again after sometime';
      }
      //$this->sale->getCommercials($id);
      $this->session->set_flashdata('status', $this->msg);
      //redirect($this->redirectUrl, 'refresh');
      redirect(base_url().'leads/commercials_documents/'.$id, 'refresh');
    }
    else {
      $data = $this->set_commercial_document_data($id);
      $this->loadFormViews('commercials_documents', $data);
    }

  }

  public function document_delete($customer_id, $document_id) {
    if($this->sale->deleteCustomerDocument($customer_id, $document_id)) {
      $this->msg = 'Document deleted successfully';
    } else {
        $this->msg = 'Error deleting document';
    }
    $this->session->set_flashdata('status', $this->msg);
    redirect(base_url().'leads/commercials_documents/'.$customer_id, 'refresh');
  }

  public function addFileInfo($data) {
    $file_data = $this->uploadFile('file_name');
    if($file_data['status']==true) {
        $data['file_name'] = $file_data['upload_data']['file_name'];
    }
    return $data;
  }

  public function leadStatusUpdate() {
    $data['lead_status_id']= $this->input->post('lead_status_id');
    $data['customer_id']= $this->input->post('customer_id');
    $data['created_by'] = $this->session->userdata('usr_authdet')['id'];
    $data['is_paid'] = $this->input->post('is_paid') ?? -1;

    if($data['lead_status_id']!=8){
      $data['remarks'] = $this->input->post('remark');
      if($this->input->post('schedule_date') != '') {
        $data['schedule_date'] = $this->input->post('schedule_date');
        $data['name'] = $this->input->post('name');
        $data['phone'] = $this->input->post('phone');
        $data['city'] = $this->input->post('city');
        $data['address'] = $this->input->post('address');
      }
    } else {
      $data['remarks'] = $this->input->post('remarks');
      $data['schedule_date'] = $this->input->post('schedule_date');
      $data['potential_order_value_per_month'] = $this->input->post('potential_order_value_per_month');
      $data['potential_number'] = $this->input->post('potential_number');
      $data = $this->addFileInfo($data);
    }
    $status = $this->sale->updateLeadStatus($data);
    if ($status) {
      $this->msg = 'Lead Status updated successfully';
    } else {
      $this->msg = 'Error updating lead Status';
    }
    $this->session->set_flashdata('status', $this->msg);
    $response['status'] = $status;
    $response['msg'] = 'request reached successfully';
    echo json_encode($response);
    exit;
  }

  public function getSpocsByCustomerID($id) {
    $data = $this->sale->getSpocsByCustomerID($id);
    if (count($data)>0) {
      $status = true;
      $this->msg = 'Spocs Found';
    } else {
      $status = false;
      $this->msg = 'Not able to find any Additional Spoc';
    }
    //$this->session->set_flashdata('status', $this->msg);
    $response['status'] = $status;
    $response['msg'] = $this->msg;
    $response['data'] = $data;
    echo json_encode($response);
    exit;
  }

  public function getCommercialsByCustomerID($id) {
    $data = $this->sale->getCommercialsByCustomerID($id);
    if (count($data)>0) {
      $status = true;
      $this->msg = 'Commercials Found';
    } else {
      $status = false;
      $this->msg = 'Not able to find any Commercials';
    }
    //$this->session->set_flashdata('status', $this->msg);
    $response['status'] = $status;
    $response['msg'] = $this->msg;
    $response['data'] = $data;
    echo json_encode($response);
    exit;
  }

  private function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->saleData($this->customerFields, $id);
    $data['data']['location_fields'] = $this->locationData($this->locationFields, $id);
    $data['data']['lead_status_options'] = $this->sale->getLeadStatuses();
    $data['data']['lead_source_options'] = $this->sale->getLeadSources();
    //['data']['country_options'] = $this->sale->getCountries();
    $data['data']['industry_options'] = $this->sale->getIndustries();
    $data['data']['business_practice_options'] = $this->sale->getBusinessPractices();
    $data['data']['location_options'] = $this->sale->getLocations();
    $data['data']['business_vertical_options'] = $this->sale->getBusinessVerticals();
    $data['data']['functional_area_options'] = $this->sale->getFunctionalAreas();
    $data['data']['countries_options'] = $this->candidate->getCountries();
    $data['data']['customer_type_options'] = $this->candidate->getLeadType();
    //$data['data']['placement_officer_options'] = $this->sale->getPlacementOfficers();
    //$data['data']['spoc_options'] = $this->sale->getSpocs();
    return $data;
  }

  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
  //  $this->form_validation->set_rules('lead_name', 'Company Name', 'required');
    $this->form_validation->set_rules('lead_type_id', 'Lead Type', 'required');
    $this->form_validation->set_rules('lead_managed_by', 'Lead Managed by', 'required');
     $this->form_validation->set_rules('lead_source_id', 'Lead Source', 'required|is_natural');
    // $this->form_validation->set_rules('lead_email', 'Email', 'valid_email');
    // $this->form_validation->set_rules('lead_phone', 'Mobile Number', 'is_natural|exact_length[10]');
     $this->form_validation->set_rules('customer_name', 'Company Name', 'required|min_length[3]');
    $this->form_validation->set_rules('customer_description', 'Customer Description', 'required');
    $this->form_validation->set_rules('landline', 'Landline', 'is_natural|max_length[12]');
    //$this->form_validation->set_rules('location_id', 'Location', 'required|is_natural_no_zero');
    $this->form_validation->set_rules('address', 'Address', 'required');

    $this->form_validation->set_rules('hr_email', 'HR Email', 'valid_email');
    $this->form_validation->set_rules('hr_phone', 'HR Phone', 'is_natural|exact_length[10]');
    $this->form_validation->set_rules('hr_designation', 'HR Designation', 'max_length[30]');
    $this->form_validation->set_rules('skype_id', 'Skype ID', '');
    $this->form_validation->set_rules('no_of_employees', 'Number of Employees', 'is_natural');
    $this->form_validation->set_rules('annual_revenue', 'Annual Revenue', 'is_natural|max_length[12]');
    //$this->form_validation->set_rules('business_value', 'Business Value', 'required|is_natural|max_length[12]');
    $this->form_validation->set_rules('fax_number', 'Fax', 'is_natural|max_length[10]');
    $this->form_validation->set_rules('functional_area_id', 'Functional Area', 'required|is_natural');
    //$this->form_validation->set_rules('lead_status_id', 'Lead Status', 'is_natural');
    $this->form_validation->set_rules('industry_id', 'Industry', 'required|is_natural');
    $this->form_validation->set_rules('no_of_users', 'Number of Users', 'is_natural|less_than_equal_to[500000]');
    $this->form_validation->set_rules('business_vertical_id', 'Business Vertical', 'required|is_natural');
    $this->form_validation->set_rules('business_practice_id', 'Business Practice', 'is_natural');
    $this->form_validation->set_rules('tagert_employers', 'Target Employer', '');
    //$this->form_validation->set_rules('no_poach_companies', 'No Poach Companies', 'is_natural');
    $this->form_validation->set_rules('website', 'website', 'valid_url');
     $this->form_validation->set_rules('city', 'Town/Village', 'max_length[30]');
    $this->form_validation->set_rules('pincode', 'Pincode', 'required|is_natural_no_zero|exact_length[6]', array('is_natural_no_zero'=>'Enter Valid Pincode'));
    $this->form_validation->set_rules('district_id', 'District/City', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
    $this->form_validation->set_rules('remarks', 'remarks', '');
  //  $this->form_validation->set_rules('placement_officers[]', 'Placement Officer', 'required');

    foreach($this->input->post('spoc_detail') as $i=>$spoc) {
      $this->form_validation->set_rules("spoc_detail[{$i}][spoc_name]", 'Spoc Name', 'required');
      $this->form_validation->set_rules("spoc_detail[{$i}][spoc_email]", 'Spoc Email', 'required|valid_email|callback_check_duplicate_fields');
      $this->form_validation->set_rules("spoc_detail[{$i}][spoc_phone]", 'Spoc Phone', 'required|is_natural_no_zero|exact_length[10]|callback_check_duplicate_fields');
      $this->form_validation->set_rules("spoc_detail[{$i}][spoc_designation]", 'Spoc Designation', 'required|max_length[30]');
    }
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

  public function getLeadHistory($lead_id){
    echo json_encode($this->sale->getLeadHistory($lead_id));
    exit;
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('sales/'.$action, $data);
    $this->load->view('layouts/footer');
  }

  private function configPagination($total_rows, $url) {
  $this->load->library('pagination');

  $config['base_url'] = $url;
  $config['total_rows'] = $total_rows;
  $config['uri_segment'] = 3;
  $config['use_page_numbers'] = TRUE;
  $config['per_page'] = 20;
  $config['first_url'] = base_url($this->redirectUrl);
  $config['reuse_query_string'] = true;
  $config['attributes'] = array('class' => 'paginate_button btn btn-md btn-primary', 'style'=>'margin:5px;');
  $config['full_tag_open'] = '<div class="table_paginate">';
  $config['full_tag_close'] = '</div>';
  $config['cur_tag_open'] = '<a class="paginate_button btn btn-md btn-warning current">';
  $config['cur_tag_close'] = '</a>';

  $this->pagination->initialize($config);
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


  private function saleData($fields_array, $id=0) {
    $fields = array();
    $data = array();

    if ($id==0) {
      foreach($fields_array as $field_name) {
        $fields[$field_name] = '';
      }
      //$data['placement_officers'] = $this->input->post('placement_officers') ?? [];
    } else {
      $fields = $this->sale->find($id);
     //$fields['spoc'] = $this->sale->getSpocs($id);
     //$data['placement_officers'] = $this->input->post('placement_officers') ?? $this->sale->getAssociatedPO($id);
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
      $fields = $this->sale->findLocation($id);
      $fields['spoc_detail'] = json_decode($fields['spoc_detail'], true);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? ($fields[$field_name] ?? '');
    }
  //  echo var_dump($data);
    return $data;
  }

  private function commericalData($fields_array, $id=0) {
    $data= array();
    if (count($this->sale->getCommercials($id))==0 && (empty($this->input->post()) || empty($this->input->post('commercial')))) {
      for($i=0; $i<count($this->commercials_fields); $i++) {
        foreach($fields_array as $field_name) {
          if($field_name=='title') {
            $data[$i][$field_name] = $this->commercials_fields[$i];
          } else {
          $data[$i][$field_name] = '';
          }
        }
      }
    } else {
      $data = $this->input->post('commercial') ?? $this->sale->getCommercials($id);
    }
    return $data;
  }

  public function get_lead_data()
  {
    $requestData= $_REQUEST;
    $resp_data=$this->sale->get_lead_data($requestData);
    echo json_encode($resp_data);
  }

  public function getAssignedUserToLead() {
    $data = $this->sale->getAssociatedPO($this->input->post('customer_id'));
    if (count($data)>0) {
      $status = true;
      $this->msg = 'Commercials Found';
    } else {
      $status = false;
      $this->msg = 'Not able to find any Commercials';
    }
    //$this->session->set_flashdata('status', $this->msg);
    $response['status'] = $status;
    $response['msg'] = $this->msg;
    $response['data'] = $data;
    echo json_encode($response);
    exit;
  }

  public function changeLeadAssignee() {
    $created_by= $this->session->userdata('usr_authdet')['id'];
    $customer_id = $this->input->post('customer_id');
    $user_id = $this->input->post('placement_officer');
    if ($this->sale->replaceCustomerUsers($customer_id, $created_by, 'Placement Officer', $user_id)) {
      $status = true;
      $this->msg = 'Assignment Successful';
    } else {
      $status = false;
      $this->msg = 'Not able to Assign User, Kindly refresh the page and try after sometime';
    }
    //$this->session->set_flashdata('status', $this->msg);
    $response['status'] = $status;
    $response['msg'] = $this->msg;
    echo json_encode($response);
    exit;
  }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class OpportunitiesController extends MY_Controller {

  protected $redirectUrl = 'opportunitiescontroller/index';

  public function __construct() {
    parent::__construct();
    $this->load->helper('inflector');
    $this->load->model("Pramaan_model", "pramaan");
    $this->load->model('Opportunity', 'opportunity');
    $this->load->model('Company', 'company');
    $this->load->model('Sale', 'sale');
    // $this->load->model('Candidate', 'candidate');
  }

  public function index() {
    $data['lead_status_options'] = $this->opportunity->getLeadStatuses();
    $data['company_name_options'] = $this->sale->getCompanyNames();
    $data['industries_list_options'] = $this->sale->getIndustries();
    $data['business_vertical_options'] = $this->sale->getBusinessVerticals();
    $data['opportunity_code_options'] = $this->sale->getOpportunityCode();
    $data['contract_id_options'] = $this->sale->getContractId();
    $data['ln_entity_options'] = $this->sale->getLnEntity();
    $this->loadFormViews('index', $data);
  }

  public function create($customer_id=0) {
    $data = $this->setData();
    $data['data']['action'] = 'create';
    $data['data']['fields']['customer_id'] = $customer_id;
    $data['data']['company'] = $this->company->find($customer_id);
    $this->loadFormViews('create', $data);
  }

  public function store($customer_id) {
    if($this->validateRequest()){
      $data = $this->input->post();
      if($this->opportunity->save($data)) {
        $this->msg = 'opportunity created successfully';
      } else {
        $this->msg = 'Error creating opportunity, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
    $data['data']['action'] = 'create';
    $data = $this->setData();
    $data['data']['fields']['customer_id'] = $customer_id;
    $data['data']['company'] = $this->company->find($customer_id);
    $this->loadFormViews('create', $data);
    }
  }

  public function edit($opportunity_id) {
    $data = $this->setData($opportunity_id);
    $data['id'] = $opportunity_id;
    $data['data']['company'] = $this->company->find($data['data']['fields']['company_id']);
    $this->loadFormViews('edit', $data);
  }

  public function update($opportunity_id) {
    if($this->validateRequest()){
      $data = $this->input->post();
      if ($this->opportunity->update($opportunity_id, $data)) {
        $this->msg = 'Opportunity updated successfully';
      } else {
        $this->msg = 'Error updating Opportunity, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
      $data = $this->setData($opportunity_id);
      $data['id'] = $opportunity_id;
      $data['data']['company'] = $this->company->find($data['data']['fields']['company_id']);
      $this->loadFormViews('edit', $data);
    }
  }

  public function show($opportunity_id) {

  }

  public function destroy($opportunity_id) {

  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('opportunities/'.$action, $data);
    $this->load->view('layouts/footer');
  }

  public function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->saleData($this->opportunity->fillable, $id);
    $data['data']['location_fields'] = $this->locationData($this->location->fillable, $id);
    $data['data']['lead_status_options'] = $this->opportunity->getLeadStatuses();
    $data['data']['lead_source_options'] = $this->opportunity->getLeadSources();
    $data['data']['industry_options'] = $this->opportunity->getIndustries();
    $data['data']['business_practice_options'] = $this->opportunity->getBusinessPractices();
    $data['data']['business_vertical_options'] = $this->opportunity->getBusinessVerticals();
    $data['data']['functional_area_options'] = $this->opportunity->getFunctionalAreas();
    $data['data']['countries_options'] = $this->opportunity->getCountries();
    $data['data']['labournet_entity_options'] = $this->opportunity->getLabournetEntities();
    $data['data']['customer_type_options'] = $this->opportunity->getLeadType();
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
        $fields = $this->opportunity->find($id);
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
        $fields = $this->opportunity->findLocation(['opportunity_id' => $id]);
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

      $this->form_validation->set_rules('company_id', 'Lead Type', 'required|is_natural_no_zero');
      $this->form_validation->set_rules('managed_by', 'Managed By', '');
      $this->form_validation->set_rules('lead_status_id', 'Lead Status', 'required|is_natural_no_zero');
      $this->form_validation->set_rules('business_vertical_id', 'Product', 'required|is_natural_no_zero');
      $this->form_validation->set_rules('functional_area_id', 'Functional Area', 'required|is_natural');
      $this->form_validation->set_rules('industry_id', 'Industry', 'required|is_natural');
      $this->form_validation->set_rules('labournet_entity_id', 'Labournet Entity', '');

      //Branch Location Validation
      $this->form_validation->set_rules('address', 'Address', 'required');
      $this->form_validation->set_rules('city', 'Town/Village', 'max_length[30]');
      $this->form_validation->set_rules('pincode', 'Pincode', 'required|is_natural_no_zero|exact_length[6]', array('is_natural_no_zero'=>'Enter Valid Pincode'));
      $this->form_validation->set_rules('district_id', 'District/City', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
      $this->form_validation->set_rules('state_id', 'State', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));
      $this->form_validation->set_rules('country_id', 'Country', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select value from dropdown'));

      //Multiple Spoc Validation
      foreach($this->input->post('spoc_detail') as $i=>$spoc) {
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_name]", 'Spoc Name', '');
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_email]", 'Spoc Email', 'valid_email|callback_check_duplicate_fields');
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_phone]", 'Spoc Phone', 'is_natural_no_zero|exact_length[10]|callback_check_duplicate_fields');
        $this->form_validation->set_rules("spoc_detail[{$i}][spoc_designation]", 'Spoc Designation', 'max_length[30]');
      }
      return $this->form_validation->run();
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
        $status = $this->opportunity->updateLeadStatus($data);
        if ($status) {
          $this->msg = 'Opportunity Status updated successfully';
        } else {
          $this->msg = 'Error updating Opportunity Status';
        }
        $this->session->set_flashdata('status', $this->msg);
        $response['status'] = $status;
        $response['msg'] = 'request reached successfully';
        echo json_encode($response);
        exit;
      }

      public function addFileInfo($data) {
        $file_data = $this->uploadFile('file_name');
        if($file_data['status']==true) {
            $data['file_name'] = $file_data['upload_data']['file_name'];
        }
        return $data;
      }

      public function getLeadHistory($lead_id){
        echo json_encode($this->opportunity->getLeadHistory($lead_id));
        exit;
      }

      public function getSpocsByCustomerID($id) {
        $data = $this->opportunity->getSpocsByCustomerID($id);
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

      //sumit's code
      public function getOpporunityList()
      {
        $requestData= $_REQUEST;
        $resp_data=$this->opportunity->getOpportunityData($requestData);
        echo json_encode($resp_data);
      }

      public function check_commercial_document($customer_id)
      {
        //echo $customer_id;
          $result = array(
            "file_name" => ""
          );
          $query = "WITH RES AS
          (
              SELECT 	LL.file_name,
                      ROW_NUMBER() OVER(PARTITION BY LL.customer_id,LL.lead_status_id ORDER BY id DESC) AS counter
              FROM 	neo_customer.lead_logs AS LL
              WHERE	LL.customer_id= $customer_id
              AND		LL.lead_status_id=8
          )
          SELECT 	COALESCE(RES.file_name,'') AS file_name
          FROM	RES
          WHERE	RES.counter=1 ";

          $response = $this->db->query($query);
          if ($response->num_rows() > 0)
          {
              //echo json_encode($response->row());
              $result["file_name"] = $response->row()->file_name;
          }
          else
          {
              $result["file_name"] = "";
          }

          echo json_encode($result);
      }

}

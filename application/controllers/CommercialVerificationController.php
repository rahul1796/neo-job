<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CommercialVerificationController extends MY_Controller {


    protected $fee_type= ['0'=>'Percentage', '1'=>'Flat'];

    protected $commercials_fields = ['onboarding_fee', 'sourcing_fee', 'monthly_service_fee', 'reimbursement_fee', 'absorption_fee'];

    protected $commercial_sub_fields = ['title', 'value', 'customer_id', 'fee_type', 'option_remarks', 'remarks', 'created_by'];


    public function __construct() {
      parent::__construct();
      $this->load->helper('inflector');
      $this->load->model("Pramaan_model", "pramaan");
      $this->load->model('Commercial', 'commercial');
      $this->load->model('Opportunity', 'opportunity');
    }

    public function commericalsStore($id) {
      //echo var_dump($this->input->post());
    //  $data = $this->input->post('commercial');
      //

      $customer = $this->opportunity->find($id);
      // $this->commercial_redirect($customer);
      $data = $this->set_commercial_document_data($id);
      $data['customer'] = $customer;

      if($this->validateCommercial()){
        $data_document['customer_id'] = $id;
        $data_document['created_by'] = $this->session->userdata('usr_authdet')['id'];
        $data_document = $this->addFileInfo($data_document);
        if ($this->commercial->saveCommercials($id, $data['commercials'], $data_document)) {
          $this->msg = 'Commercial data updated successfully';
        } else {
          $this->msg = 'Error saving commercial, please try again after sometime';
        }
        $this->session->set_flashdata('status', $this->msg);
        redirect(base_url().'CommercialVerificationController/commercials_documents/'.$id, 'refresh');
      }
      $this->loadFormViews('commercials_documents',$data);
    }


    public function commercials_documents($id) {
      $customer = $this->opportunity->find($id);
      // $this->commercial_redirect($customer);
      $data = $this->set_commercial_document_data($id);
      $data['customer'] = $customer;
      $this->loadFormViews('commercials_documents',$data);
    }

    private function set_commercial_document_data($id) {
      $data['id'] = $id;
      $data['legal_verified'] = false;
      $data['fee_types_option'] = $this->fee_type;
      $data['remark_options'] = $this->commercial->getCommercialRemarkTypes();
      $data['commercials'] = $this->commericalData($this->commercial_sub_fields, $id);
      $data['commercial_options'] = $this->commercial->getCommercialStatuses();
      $data['documents'] = $this->commercial->findDocument($id);
      if (count($this->commercial->getCommercials($id))==5 && count($data['documents'])==1) {
        $data['legal_verified'] = true;
      }
      return $data;
    }

    public function commercial_redirect($customer) {
      if($customer['is_customer'] == true) {
        $this->session->set_flashdata('status', 'You are not authorised to access that page');
        redirect('/pramaan/dashboard', 'refresh');
      } else if (!(in_array($customer['lead_status_id'], [20,21,16]))) {
        $this->session->set_flashdata('status', 'You are not authorised to access that page');
        redirect('/pramaan/dashboard', 'refresh');
      } else if (!(in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_commercial_view_roles()))) {
        $this->session->set_flashdata('status', 'You are not authorised to access that page');
        redirect('/pramaan/dashboard', 'refresh');
      }
    }

    public function verify_documents_commercial() {
      $customer_id = $this->input->post('customer_id');
      $request['status'] = $this->input->post('status');
      $request['remarks'] = $this->input->post('remarks');
      $data['status'] = false;
      if ($this->commercial->verfied_customer($customer_id, $request)) {
        $data['status'] = true;
        if($request['status']=='accept') {
            $this->msg = 'Documents & Commercials Approved. Lead Converted to Customer';
            $data['message'] = $this->msg ;
        } else {
          $this->msg = 'Documents & Commercials Rejected. Resubmission of commercial required';
          $data['message'] = $this->msg ;
        }
      } else {
        $this->msg = 'Error verifiying documents';
        $data['message'] = $this->msg ;
      }
      $this->session->set_flashdata('status', $this->msg);
      echo json_encode($data);
      exit;
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
        if ($this->commercial->saveDocument($id, $data_store)) {
          $this->msg = 'Document updated successfully';
        } else {
          $this->msg = 'Error uploading document, please try again after sometime';
        }
        //$this->sale->getCommercials($id);
        $this->session->set_flashdata('status', $this->msg);
        //redirect($this->redirectUrl, 'refresh');
        redirect(base_url().'CommercialVerificationController/commercials_documents/'.$id, 'refresh');
      }
      else {
        $data = $this->set_commercial_document_data($id);
        $this->loadFormViews('commercials_documents', $data);
      }

    }

    public function document_delete($customer_id, $document_id) {
      if($this->commercial->deleteCustomerDocument($customer_id, $document_id)) {
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

    public function getCommercialsByCustomerID($id) {
      $data = $this->commercial->getCommercialsByCustomerID($id);
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

    private function commericalData($fields_array, $id=0) {
      $data= array();
      if (count($this->commercial->getCommercials($id))==0 && (empty($this->input->post()) || empty($this->input->post('commercial')))) {
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
        $data = $this->input->post('commercial') ?? $this->commercial->getCommercials($id);
      }
      return $data;
    }

    private function loadFormViews($action, $data=null) {
      $this->load->view('layouts/header');
   		$this->load->view('commercials/'.$action, $data);
      $this->load->view('layouts/footer');
    }



}

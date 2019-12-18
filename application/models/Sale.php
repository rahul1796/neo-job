<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sale extends MY_Model
{
  protected $tableName = 'neo_customer.customers';
  private $jobTable = 'job_process.jobs';
  private $limit = 20;
  private $exclude_fields = ['placement_officers'=>''];

  public function find($id) {
    return $this->db->select('neo_customer.customers.*, neo_master.lead_sources.name as source_name, neo_master.lead_statuses.name as status_name,
     neo_master.states.id as main_state_id, neo_master.states.name as state_name,
     neo_master.districts.id as main_district_id, neo_master.districts.name as district_name')->from($this->tableName)
    ->join('neo_master.districts', 'neo_customer.customers.district_id=neo_master.districts.id', 'LEFT')
    ->join('neo_master.states', 'neo_master.districts.state_id=neo_master.states.id', 'LEFT')
    ->join('neo_master.lead_statuses', 'neo_customer.customers.lead_status_id=neo_master.lead_statuses.id', 'LEFT')
    ->join('neo_master.lead_sources', 'neo_customer.customers.lead_source_id=neo_master.lead_sources.id', 'LEFT')
    //->where('neo_customer.customers.is_customer', false)
    ->where('neo_customer.customers.id', $id)->get()->row_array();
  }

  public function findLocation($id) {
    return $this->db->where('customer_id', $id)->where('is_main_branch', TRUE)->get('neo_customer.customer_branches')->row_array();
  }

  public function allLeads() {
    return $this->db->where('is_customer', FALSE)->get($this->tableName)->result();
  }

  public function allEmployers() {
    return $this->db->where('is_customer', true)->get($this->tableName)->result();
  }

  public function allCustomers() {
    return $this->db->where('is_customer', true)->get('neo_customer.customers')->result();
  }


  public function getSpocsByCustomerID($id) {
    $spocs = $this->db->select('spoc_detail')->where('customer_id', $id)->get('neo_customer.customer_branches')->row_array();
    $hr = $this->db->select('hr_name as spoc_name, hr_email as spoc_email, hr_phone as spoc_phone, hr_designation as spoc_designation')
          ->where('id', $id)->get('neo_customer.customers')->row();
    $spoc_array = json_decode($spocs['spoc_detail']);
    array_push($spoc_array, $hr);
    return $spoc_array;
  }

  public function getCommercialsByCustomerID($id) {
    return $this->db->where('customer_id', $id)->get('neo_customer.customer_commercials')->result();
  }

  public function getAssociatedPO($id){
    return array_column($this->db->where('lead_id', $id)->where('user_type', 'Placement Officer')->get('neo_customer.leads_users')->result_array(), 'user_id');
  }

  public function Leads($page) {
    $offset = ($page==0) ? 0 : ((($page-1) * $this->limit));
    return $this->db->select('neo_customer.customers.*, neo_master.lead_sources.name as source_name, neo_master.lead_statuses.name as status_name,
     neo_master.states.id as main_state_id, neo_master.states.name as state_name,
     neo_master.districts.id as main_district_id, neo_master.districts.name as district_name')->from($this->tableName)
    ->join('neo_master.districts', 'neo_customer.customers.district_id=neo_master.districts.id', 'LEFT')
    ->join('neo_master.states', 'neo_master.districts.state_id=neo_master.states.id', 'LEFT')
    ->join('neo_master.lead_statuses', 'neo_customer.customers.lead_status_id=neo_master.lead_statuses.id', 'LEFT')
    ->join('neo_master.lead_sources', 'neo_customer.customers.lead_source_id=neo_master.lead_sources.id', 'LEFT')
    ->where('is_customer', false)->get()->result();
    //->limit($this->limit, $offset)
  }

  public function getCommercials($id) {
    return $this->db->where('customer_id', $id)->get('neo_customer.customer_commercials')->result_array();
  }

  public function saveCommercials($id, $data, $data_document) {
    $customer_data['has_commercial'] = TRUE;
    $this->db->trans_start();
    if($this->db->where('customer_id', $id)->get('neo_customer.customer_commercials')->num_rows()>0){
      $this->db->reset_query();
      $this->db->where('customer_id', $id)->delete('neo_customer.customer_commercials');
    }
    $this->db->reset_query();
    $this->db->insert_batch('neo_customer.customer_commercials', $data);
    $this->db->reset_query();
    $this->db->update('neo_customer.customers', $customer_data);

    if(!empty($data_document['file_name'])){
        $this->saveDocument($id, $data_document);
    }

    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function saveDocument($id, $data) {
    $customer_data['has_documents'] = TRUE;
    $this->db->reset_query();
    $this->db->insert('neo_customer.customer_documents', $data);
    $this->db->reset_query();
    $this->db->update('neo_customer.customers', $customer_data);
  }

  // public function saveDocument($id, $data) {
  //   $customer_data['has_documents'] = TRUE;
  //   $this->db->trans_start();
  //   $this->db->insert('neo_customer.customer_documents', $data);
  //   $this->db->reset_query();
  //   $this->db->update('neo_customer.customers', $customer_data);
  //   $this->db->trans_complete();
  //   return $this->db->trans_status();
  // }

  public function verfied_customer($id) {
    $data['legally_verified'] = TRUE;
    $data['is_customer'] = TRUE;
    $this->db->trans_start();
    $this->db->where('id', $id);
    $this->db->update($this->tableName, $data);
    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function deleteCustomerDocument($customer_id, $id) {
    $query = $this->db->where('customer_id', $customer_id)->where('id', $id)->get('neo_customer.customer_documents')->num_rows();
    if($query==1) {
      $this->db->delete('neo_customer.customer_documents', ['customer_id'=>$customer_id, 'id' => $id]);
      if($this->db->affected_rows() == 1 ) {
        return true;
      }
    }
    return false;
  }

  public function findDocument($id) {
    return $this->db->where('customer_id', $id)->get('neo_customer.customer_documents')->result();
  }

  public function save_with_location($data, $location_data) {
    $this->db->trans_start();
    $this->db->insert($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));
    $customer_id = $this->db->insert_id();
    $location_data['customer_id'] = $customer_id;
    $location_data['created_by'] = $data['created_by'];
    $location_data['is_main_branch'] = TRUE;

    if(count($location_data['spoc_detail'])>0) {
        $location_data['spoc_detail'] = json_encode(array_values($location_data['spoc_detail']));
    } else {
      $location_data['spoc_detail'] ='';
    }

    $this->db->reset_query();
    $this->db->insert('neo_customer.customer_branches', $location_data);

    // $this->db->reset_query();
    // $this->replaceCustomerUsers($customer_id, $data['created_by'], 'Placement Officer', $data['placement_officers']);
    $logs_data['customer_id'] = $customer_id;
    $logs_data['created_by'] = $data['created_by'];
    $logs_data['lead_status_id'] = 1;
    $this->db->reset_query();
    $this->db->insert('neo_customer.lead_logs', $logs_data);

    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function update_with_location($id, $data, $location_data) {
    $this->db->trans_start();
    $this->db->where('id', $id);
    $this->db->update($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));
    $location_data['customer_id'] = $id;
    $this->db->reset_query();

    if(count($location_data['spoc_detail'])>0) {
        $location_data['spoc_detail'] = json_encode(array_values($location_data['spoc_detail']));
    } else {
      $location_data['spoc_detail'] ='';
    }

    if($this->db->where('customer_id', $id)->where('is_main_branch', TRUE)->get('neo_customer.customer_branches')->num_rows() == 1) {
      $this->db->reset_query();
      $this->db->where('is_main_branch', TRUE);
      $this->db->where('customer_id', $id);
      $this->db->update('neo_customer.customer_branches', $location_data);
    } else {
      $this->db->reset_query();
      $location_data['is_main_branch'] = TRUE;
      $this->db->insert('neo_customer.customer_branches', $location_data);
    }

    // $this->db->reset_query();
    // $this->replaceCustomerUsers($id, $data['updated_by'],'Placement Officer', $data['placement_officers']);

    $this->db->trans_complete();
    return $this->db->trans_status();
  }

  public function replaceCustomerUsers($customer_id, $created_by, $type, $data) {
    $this->db->trans_start();
    $this->db->reset_query();
    $this->db->delete('neo_customer.leads_users', ['lead_id'=>$customer_id, 'user_type'=>$type]);
    // foreach($data as $id) {
    //   $row = array();
      $row['user_id'] = $data;
      $row['lead_id'] = $customer_id;
      $row['user_type'] = $type;
      $row['created_by'] = $created_by;
      $this->db->reset_query();
      $this->db->insert('neo_customer.leads_users', $row);
    // }
    $this->db->trans_complete();
    return $this->db->trans_status();
  }



  public function createLeadLog($data, $remark='') {
    $data['remarks'] = $remark;
    return $this->db->insert('neo_customer.lead_logs', $data);
  }

  public function updateLeadStatus($data) {
    $this->db->where('id', $data['customer_id']);
    $maindata['lead_status_id'] = $data['lead_status_id'];
    if($data['is_paid']!=-1){
      $maindata['is_paid'] = $data['is_paid'];
      if($data['lead_status_id']==16 && $maindata['is_paid']==0) {
         $maindata['is_customer'] = true;
      }
    }
    unset($data['is_paid']);
    $this->db->update($this->tableName, $maindata);
    if($this->db->affected_rows() == 1 ) {
      $this->createLeadLog($data, $data['remarks']);
      return true;
    }
  }

  // public function getCountries() {
  //   return $this->db->select('*')->where('active_status', 1)->get('neo_master.country')->result();
  // }

  // public function getStates($country_id) {
  //   return $this->db->select('neo_master.state.*')->from('neo_master.state')
  //   ->join('neo_master.regions', 'neo_master.state.region_id=neo_master.regions.id', 'LEFT')
  //   ->join('neo_master.country', 'neo_master.regions.country_id=neo_master.country.id', 'LEFT')
  //   ->where('neo_master.country.id', $country_id)->get()->result();
  // }

  public function getDistricts($state_id) {
    return $this->db->select('neo_master.district.*')->from('neo_master.district')
    ->join('neo_master.state', 'neo_master.district.state_id=neo_master.state.id', 'LEFT')
    ->where('neo_master.state.id', $state_id)->get()->result();
  }

  public function addCandidateJob($data, $remark) {
    $this->db->insert('job_process.candidate_jobs', $data);
    if($this->db->affected_rows() == 1 ) {
      $this->addJobLog($data, $remark);
      return true;
    }
    return false;
  }

  public function updateCandidateJobStatus($data, $remark) {
    $this->db->where('candidate_id', $data['candidate_id']);
    $this->db->where('job_id', $data['job_id']);
    $this->db->update('job_process.candidate_jobs', $data);
    if($this->db->affected_rows() == 1 ) {
      $this->addJobLog($data, $remark);
      return true;
    }
    return false;
  }

  public function addJobLog($data, $remark='') {
    $data['remarks'] = $remark;
    $this->db->insert('job_process.candidate_jobs_log', $data);
  }

  public function getLeadHistory($lead_id) {
    return $this->db->select('logs.* , status.name as status_name')->from('neo_customer.lead_logs as logs')
    ->join('neo_master.lead_statuses as status', 'logs.lead_status_id = status.id', 'LEFT')
    ->order_by('logs.created_at', 'DESC')
    ->where('logs.customer_id', $lead_id)->get()->result();
  }

  public function getSpocs($customer_id) {
    return $this->db->where('customer_id', $customer_id)->get('neo_customer.customer_spoc_details')->result();
  }

  function get_lead_edit_data($lead_id)
  {
    $query = "SELECT 	C.id,
                        C.lead_name,
                        C.lead_source_id,
                        C.lead_managed_by,
                        C.lead_phone,
                        C.lead_email,
                        C.lead_status_id,
                        C.business_probability,
                        C.customer_name,
                        C.customer_description,
                        C.landline,
                        C.location,
                        C.address,
                        COALESCE(C.district_id,0) AS district_id,
                        C.spoc_email,
                        C.spoc_phone,
                        C.hr_email,
                        C.hr_phone,
                        C.fax_number,
                        C.skype_id,
                        C.industry_id,
                        C.functional_area_id,
                        C.no_of_employees,
                        C.annual_revenue,
                        C.business_value,
                        C.no_of_users,
                        C.business_vertical_id,
                        C.business_practice_id,
                        C.tagert_employers,
                        C.no_poach_companies,
                        C.website,
                        C.remarks,
                        C.active,
                        C.is_customer,
                        C.country_id,
                        COALESCE(D.state_id,0) AS state_id,
                        C.spoc_name,
                        C.hr_name,
                        C.customer_type,
                        D.name AS district_name,
                        S.name AS state_name
                FROM 	neo_customer.customers AS C
                LEFT JOIN neo_master.districts AS D ON D.id=C.district_id
                LEFT JOIN neo_master.states AS S ON S.id=D.state_id
                WHERE 	C.id=$lead_id";
    $response = $this->db->query($query);
    if ($response->num_rows())
      return $response->result_array()[0];
    else
      return array();
  }
}

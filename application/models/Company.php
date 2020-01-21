<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Company extends MY_Model
{
    protected $tableName = 'neo_customer.companies';

    private $current_user_id, $current_timestamp;

    public $fillable = ['company_name', 'lead_type_id', 'lead_source_id', 'company_description',
                        'industry_id', 'functional_area_id', 'hr_name', 'hr_email', 'hr_phone', 'hr_designation',
                        'landline', 'fax_number', 'skype_id', 'annual_revenue', 'website','target_employers', 'remarks',
                        'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct() {
      parent::__construct();
      $this->current_user_id = $this->session->userdata('usr_authdet')['id'];
      $this->current_timestamp = date('Y-m-d H:i:s');
      $this->load->model('Location', 'location');
    }

    public function find($company_id) {
      return $this->db->where('id', $company_id)->get($this->tableName)->row_array();
    }

    public function save($data) {
      $data = $this->makeTimeStamp($data, 'save');
      $this->db->trans_start();
      $this->db->insert($this->tableName, $this->getFillable($data));
      $this->createOrUpdateLocation($this->db->insert_id(), $data, 'save');
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    public function update($company_id, $data) {
      $data = $this->makeTimeStamp($data);

      $this->db->trans_start();
      $this->db->where('id', $company_id)->update($this->tableName, $this->getFillable($data));
      $this->createOrUpdateLocation($company_id, $data);
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    private function createOrUpdateLocation($customer_id, $data, $action='update') {
      $data['customer_id'] = $customer_id;
      $data['is_main_branch'] = TRUE;
      $this->db->reset_query();
      if($action=='save') {
          $this->location->save($data);
      } else {
         $this->location->updateRelation($data, ['customer_id'=>$data['customer_id'], 'is_main_branch'=>TRUE]);
      }
    }

    public function findLocation($data) {
      return $this->location->findFirst($data);
    }

    public function makeTimeStamp($data, $action='update') {
      $data['updated_at'] = $this->current_timestamp;
      $data['updated_by'] =$this->current_user_id;
      if($action=='save') {
        $data['created_at'] = $this->current_timestamp;
        $data['created_by'] =$this->current_user_id;
      }
      return $data;
    }
}

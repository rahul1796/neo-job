<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Opportunity extends MY_Model
{
    protected $tableName = 'neo_customer.opportunities';

    private $current_user_id, $current_timestamp;

    public $fillable = ['company_id', 'managed_by', 'lead_status_id', 'business_vertical_id',
                        'functional_area_id', 'industry_id', 'labournet_entity_id', 'opportunity_code', 'contract_id',
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
      $data['opportunity_id'] = $this->db->insert_id();
      $this->createOrUpdateLocation($data['opportunity_id'], $data, 'save');
      $data = $this->generateOpportunityCodes($data);
      $this->db->where('id', $data['opportunity_id'])->update($this->tableName, $this->getFillable($data));
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    public function update($opportunity_id, $data) {
      $data = $this->makeTimeStamp($data);

      $this->db->trans_start();
      $this->db->where('id', $opportunity_id)->update($this->tableName, $this->getFillable($data));
      $this->createOrUpdateLocation($opportunity_id, $data);
      $this->db->trans_complete();

      return $this->db->trans_status();
    }

    private function createOrUpdateLocation($opportunity_id, $data, $action='update') {
      $data['customer_id'] = $data['company_id'];
      $data['opportunity_id'] = $opportunity_id;
      $data['is_main_branch'] = FALSE;
      $this->db->reset_query();
      if($action=='save') {
          $this->location->save($data);
      } else {
         $this->location->updateRelation($data, ['customer_id'=>$data['customer_id'], 'opportunity_id'=>$data['opportunity_id']]);
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

    public function makeCodes($data) {

      return $data;
    }

    //generates contract code as well
    private function generateOpportunityCodes($data) {
      $result = $this->db->select('COM.company_name, BV.code', false)->from($this->tableName)
              ->join('neo_customer.companies AS COM', "COM.id={$this->tableName}.company_id", 'LEFT', FALSE)
              ->join('neo_master.business_verticals AS BV', "BV.id={$this->tableName}.business_vertical_id", 'LEFT', FALSE)
              ->where("{$this->tableName}.id", $data['opportunity_id'])->get()->row_array();
      $data['opportunity_code'] = 'OPP-'.strtoupper(substr($result['company_name'], 0, (strlen($result['company_name'])>3 ? 4 : 3))).'-'.$result['code'].'-'.$data['opportunity_id'];
      $data['contract_id'] = 'CON-'.strtoupper(substr($result['company_name'], 0, (strlen($result['company_name'])>3 ? 4 : 3))).'-'.$data['opportunity_id'];;
      return $data;
    }
}

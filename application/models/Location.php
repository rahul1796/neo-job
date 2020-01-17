<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Location extends MY_Model
{
    protected $tableName = 'neo_customer.customer_branches';

    private $current_user_id, $current_timestamp;

    public $fillable = ['customer_id', 'is_main_branch', 'address', 'city', 'district_id', 'state_id', 'opportunity_id',
                                  'country_id', 'pincode', 'spoc_detail', 'created_at', 'created_by', 'updated_at', 'updated_by'];

    public function __construct() {
      parent::__construct();
      $this->current_user_id = $this->session->userdata('usr_authdet')['id'];
      $this->current_timestamp = date('Y-m-d H:i:s');
    }

    public function find($location_id) {
      return $this->db->where('id', $location_id)->get($this->tableName)->row_array();
    }

    public function save($data) {
      $data = $this->convertToJSON($data);
      $this->db->insert($this->tableName, $this->getFillable($data));
    }

    public function updateRelation($data, $conditions=[]) {
      $data = $this->convertToJSON($data);
      if(!empty($conditions)) {
        foreach($conditions as $key => $value) {
          $this->db->where($key, $value);
        }
        $this->db->update($this->tableName, $this->getFillable($data));
      }
    }

    public function convertToJSON($data) {
      if(count($data['spoc_detail'])>0) {
          $data['spoc_detail'] = json_encode(array_values($data['spoc_detail']));
      } else {
          $data['spoc_detail'] = '';
      }
      return $data;
    }


}

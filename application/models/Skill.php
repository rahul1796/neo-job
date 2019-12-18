<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Skill extends MY_Model
{
  protected $tableName = 'neo.candidate_skill_details';

  // public function find($id) {
  //   return $this->db->get($this->tableName)->row_array();
  // }
  //
  // public function all() {
  //   return $this->db->get($this->tableName)->result();
  // }
  //
  // public function save($data) {
  //   $this->db->insert($this->tableName, $data);
  //   if($this->db->affected_rows() == 1 ) {
  //     return true;
  //   }
  //   return false;
  // }
  //
  // public function update($id, $data) {
  //   $this->db->where('id', $id);
  //   $this->db->update($this->tableName, $data);
  //   if($this->db->affected_rows() == 1 ) {
  //     // $remarkdata['updated_by'] = $data['modified_by'];
  //     // $remarkdata['lead_status_id'] = $data['lead_status_id'];
  //     // $remarkdata['employer_id'] = $id;
  //     // $this->createLeadLog($remarkdata);
  //     return true;
  //   }
  //   return false;
  // }

}

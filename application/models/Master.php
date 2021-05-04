<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master extends CI_Model
{

  public function getEducation() {
    $query = $this->db->select('*')->order_by('sort_order', 'ASC')->get('master.candidate_education');
    return $query->result();
  }

  public function getQualificationPack() {
    $query = $this->db->get('master.qualification_pack');
    return $query->result();
  }

  public function getDepartments() {
    $query = $this->db->get('master.departments');
    return $query->result();
  }

  public function getSectors() {
    $query = $this->db->get('master.sector');
    return $query->result();
  }

  public function getStates($country_id) {
    return $this->db->select('master.state.*')->from('master.state')
    ->join('master.regions', 'master.state.region_id=master.regions.id', 'LEFT')
    ->join('master.country', 'master.regions.country_id=master.country.id', 'LEFT')
    ->where('master.country.id', $country_id)->get()->result();
  }

  public function getDistricts($state_id) {
    return $this->db->select('master.district.*')->from('master.district')
    ->join('master.state', 'master.district.state_id=master.state.id', 'LEFT')
    ->where('master.state.id', $state_id)->get()->result();
  }

}

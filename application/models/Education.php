<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Education extends MY_Model
{
  protected $tableName = 'neo.candidate_education_details';

  public function allByCandidate($candidate_id) {
    return $this->db->select('neo.candidate_education_details.*, neo_master.educations.name as edu_name, neo_master.learning_types.name as learning_type_name')->from($this->tableName)
    ->join('neo_master.educations', 'neo.candidate_education_details.education_id=neo_master.educations.id', 'LEFT')
    ->join('neo_master.learning_types', 'neo.candidate_education_details.learning_type_id=neo_master.learning_types.id', 'LEFT')
    ->where('neo.candidate_education_details.candidate_id', $candidate_id)->get()->result();
  }
}

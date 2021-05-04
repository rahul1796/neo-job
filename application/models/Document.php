<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Document extends MY_Model
{
  protected $tableName = 'neo.candidate_document_details';


  public function allByCandidate($candidate_id) {
    return $this->db->select('neo.candidate_document_details.*, neo_master.document_types.name as document_name')->from($this->tableName)
    ->join('neo_master.document_types', 'neo.candidate_document_details.document_type_id=neo_master.document_types.id', 'LEFT')
    ->where('neo.candidate_document_details.candidate_id', $candidate_id)->get()->result();
  }

  public function checkDuplicate($candidate_id, $type_id) {
    $query = $this->db->where('candidate_id', $candidate_id)->where('document_type_id', $type_id)->get($this->tableName)->result();
    if(count($query)==0){
      return true;
    }
    return false;
  }

}

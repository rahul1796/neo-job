<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employment extends MY_Model
{
  protected $tableName = 'neo.candidate_employment_details';

  public function deleteCandidateAssociation($candidate_id, $id) {
    $employment_data = $this->db->where('candidate_id', $candidate_id)->where('id', $id)->get($this->tableName)->result_array();
    if(count($employment_data)==1) {
      $placement_id = $employment_data[0]['placement_id'] ?? 0;
      $job_id = 0;
      $this->db->reset_query();

      $this->db->trans_start();

      if($placement_id>0) {
        $placement_data = $this->db->where('candidate_id', $candidate_id)->where('id', $placement_id)->get('neo_job.candidate_placement')->row_array();
        $job_id = $placement_data['job_id'] ?? 0;
        $this->db->reset_query();
        $this->db->insert('neo_job.candidate_placement_deleted', $this->swapIDs($placement_data, 'candidate_placement_id'));
        $this->db->reset_query();
        $this->db->delete('neo_job.candidate_placement', ['candidate_id'=>$candidate_id, 'id'=>$placement_id]);
      }

      if($job_id>0) {
        $this->db->reset_query();
        $this->db->delete('neo_job.candidates_jobs', ['candidate_id'=>$candidate_id, 'job_id'=>$job_id]);
        $this->db->reset_query();
        $this->db->insert('neo_job.candidates_jobs_logs',
                          ['candidate_id'=>$candidate_id, 'job_id'=>$job_id,
                          'remarks'=>'Record Deleted', 'created_by'=>1]);

      }

      $this->db->reset_query();
      $this->db->insert('neo.candidate_employment_details_deleted', $this->swapIDs($employment_data[0], 'candidate_employment_details_id'));
      $this->db->reset_query();
      $this->db->delete($this->tableName, ['candidate_id'=>$candidate_id, 'id' => $id]);

      $this->db->trans_complete();
      return $this->db->trans_status();
    }
    return false;
  }

  private function swapIDs($data, $field_name) {
    $data[$field_name] = $data['id'];
    unset($data['id']);
    return $data;
  }
}

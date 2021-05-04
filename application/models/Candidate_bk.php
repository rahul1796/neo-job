<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Candidate extends CI_Model
{
  private $tableName = 'users.labournet_candidates';
  private $jobTable = 'job_process.jobs';
  private $limit = 20;

  public function find($id) {
    return $this->db->select('users.labournet_candidates.*, master.state.id as main_state_id, master.state.name as state_name, master.district.id as main_district_id, master.district.name as district_name')->from($this->tableName)
    ->join('master.district', 'users.labournet_candidates.district_id=master.district.id', 'LEFT')
    ->join('master.state', 'master.district.state_id=master.state.id', 'LEFT')
    ->where('users.labournet_candidates.id', $id)->get()->row_array();
  }

  public function save($data) {
    $this->db->insert($this->tableName, $data);
    if($this->db->affected_rows() == 1 ) {
      return true;
    }
    return false;
  }

  public function update($id, $data) {
    $this->db->where('id', $id);
    $this->db->update($this->tableName, $data);
    if($this->db->affected_rows() == 1 ) {
      return true;
    }
    return false;
  }

  public function candidatesByJobPreferenceQuery($id, $search_data) {
    $job = $this->db->select('job_process.jobs.min_qualification_id, job_process.jobs.qualification_pack_id, master.education.*')
          ->from($this->jobTable)
          ->join('master.education', 'job_process.jobs.min_qualification_id=master.education.id')
          ->where('job_process.jobs.id', $id)->get()->row();
    $education = $this->db->select('id')->where('weightage >=', $job->weightage)->get('master.education')->result_array();
    $education_ids = array_column($education, 'id');
  //  return $education_ids;
    $candidate_ids_exclude = $this->jobCandidateIDs($id);
    $query = $this->db->select('*')
    ->where('qualification_pack_id', $job->qualification_pack_id)
    ->where_in('education_id', $education_ids);
    if(count($candidate_ids_exclude)>0){
      $query = $query->where_not_in('id', $candidate_ids_exclude);
    }
    $query = $query->like('users.labournet_candidates.mobile_number', $search_data['search_phone'])
    ->like('users.labournet_candidates.email', $search_data['search_email'])
    ->group_start()
    ->like('users.labournet_candidates.last_name', $search_data['search_name'])
    ->or_like('users.labournet_candidates.first_name', $search_data['search_name'])
    ->group_end();
    if($search_data['search_education']!=''){
    $query = $query->where('users.labournet_candidates.education_id', $search_data['search_education']);
    }
    return $query;
  }

  public function candidatesByJobPreferenceCount($id, $search_data) {
    return count($this->candidatesByJobPreferenceQuery($id, $search_data)->get($this->tableName)->result());
  }

  public function candidatesByJobPreference($id, $page, $search_data) {
    $offset = ($page==0) ? 0 : ((($page-1) * $this->limit));
    return $this->candidatesByJobPreferenceQuery($id, $search_data)->limit($this->limit, $offset)->get($this->tableName)->result();
  }

  public function jobCandidatesCount($id, $search_data) {
    $query = $this->db->select('count(users.labournet_candidates.*) as total_row')->from('job_process.candidate_jobs')
      ->join('users.labournet_candidates', 'job_process.candidate_jobs.candidate_id=users.labournet_candidates.id')
      ->join('master.status', 'job_process.candidate_jobs.status_id = master.status.id')->group_by('job_process.candidate_jobs.job_id')
      ->where('job_process.candidate_jobs.job_id', $id)
      ->like('users.labournet_candidates.mobile_number', $search_data['search_phone'])
      ->like('users.labournet_candidates.email', $search_data['search_email'])
      ->group_start()
      ->like('users.labournet_candidates.last_name', $search_data['search_name'])
      ->or_like('users.labournet_candidates.first_name', $search_data['search_name'])
      ->group_end();
      if($search_data['search_education']!=''){
      $query = $query->where('users.labournet_candidates.education_id', $search_data['search_education']);
      }
      return $query->get()->row_array();
  }

  public function jobCandidates($id, $page, $search_data) {
      $offset = ($page==0) ? 0 : ((($page-1) * $this->limit));
      return $this->jobCandidateQuery($id, $search_data)->limit($this->limit, $offset)->get()->result();
  }

  public function jobCandidateQuery($id, $search_data) {
    $query = $this->db->select('users.labournet_candidates.*, job_process.candidate_jobs.status_id, master.status.name ')->from('job_process.candidate_jobs')
      ->join('users.labournet_candidates', 'job_process.candidate_jobs.candidate_id=users.labournet_candidates.id')
      ->join('master.status', 'job_process.candidate_jobs.status_id = master.status.id')
      ->where('job_process.candidate_jobs.job_id', $id)
      ->like('users.labournet_candidates.mobile_number', $search_data['search_phone'])
      ->like('users.labournet_candidates.email', $search_data['search_email'])
      ->group_start()
      ->like('users.labournet_candidates.last_name', $search_data['search_name'])
      ->or_like('users.labournet_candidates.first_name', $search_data['search_name'])
      ->group_end();
      if($search_data['search_education']!=''){
      $query = $query->where('users.labournet_candidates.education_id', $search_data['search_education']);
      }
      return $query;
  }

  public function jobCandidateIDs($id) {
    $candidate_ids = $this->db->select('candidate_id')->where('job_id', $id)->get('job_process.candidate_jobs')->result_array();
    return array_column($candidate_ids, 'candidate_id');
  }

  public function getEducation() {
    $query = $this->db->select('*')->order_by('sortorder', 'ASC')->get('master.education');
    return $query->result();
  }

  public function getQualificationPack() {
    $query = $this->db->get('master.qualification_pack');
    return $query->result();
  }

  public function getStatus() {
    $query = $this->db->get('master.status');
    return $query->result();
  }

  public function getCountries() {
    return $this->db->select('*')->where('active_status', 1)->get('master.country')->result();
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
}

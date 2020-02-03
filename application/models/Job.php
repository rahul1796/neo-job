<?php

class Job extends MY_Model
{
  protected $tableName = 'neo_job.jobs';
  protected $tableName2 = 'neo_job.candidate_placement';
  private $limit = 25;

  private $exclude_fields = ['recruiters'=>'', 'placement_officers'=>''];


    public function all() {
      $query = $this->db->get($this->tableName);
      return $query->result();
    }

    public function findEmployer($id) {
      return $this->db->select('neo_job.jobs.*, neo_customer.companies.company_name,neo_master.districts.name as district_name,neo_master.qualification_packs.name as qualification_pack_name, neo_master.qualification_packs.code AS qualification_code')
            ->from($this->tableName)
            ->join('neo_customer.companies', 'neo_job.jobs.customer_id=neo_customer.companies.id', 'LEFT')
            ->join('neo_master.districts', 'neo_job.jobs.district_id=neo_master.districts.id', 'LEFT')
            ->join('neo_master.qualification_packs', 'neo_job.jobs.qualification_pack_id=neo_master.qualification_packs.id', 'LEFT')
            ->where('neo_job.jobs.id', $id)->get()->row();
    }

    public function findPlacementDetails($job_id,$candidate_id) {
      return $this->db->select('neo_job.candidate_placement.*')
            ->from($this->tableName2)
            ->where('neo_job.candidate_placement.job_id', $job_id)
             ->where('neo_job.candidate_placement.candidate_id', $candidate_id)->get()->row();
    }


    public function getRecruiters() {
      return $this->db->where('user_role_id', 11)->get('neo_user.users')->result();
    }

    public function getPlacementOfficers() {
      return $this->db->where('user_role_id', 14)->get('neo_user.users')->result();
    }

    public function save($data) {
      $this->db->trans_start();

      $this->db->insert($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));
      $job_id = $this->db->insert_id();
      $this->db->reset_query();
      $job_code = $this->createJobCode($job_id);
      $this->db->reset_query();
      $this->db->where('id', $job_id)->update($this->tableName, ['neo_job_code' => $job_code]);
      $this->db->reset_query();
      $this->replaceJobUsers($job_id, $data['created_by'], 'Recruiter', $data['recruiters']);
      $this->replaceJobUsers($job_id, $data['created_by'], 'Placement Officer', $data['placement_officers']);
      $this->db->trans_complete();

      return ['status'=> $this->db->trans_status(), 'job_code'=>$job_code];
    }


    public function update($id, $data) {
      $this->db->trans_start();

      $this->db->where('id', $id);
      $this->db->update($this->tableName, array_filter(array_diff_key($data, $this->exclude_fields), array($this, 'nonZeroFilter')));
      $this->db->reset_query();
      $job_code = $this->db->select('neo_job_code')->where('id', $id)->get($this->tableName)->row_array()['neo_job_code'];

      if(empty($job_code)){
        $this->db->reset_query();
        $job_code = $this->createJobCode($id);
        $this->db->reset_query();
        $this->db->where('id', $id)->update($this->tableName, ['neo_job_code' => $job_code]);
      }
      // $this->db->reset_query();
      // $job_code = $this->createJobCode($id);
      // $this->db->reset_query();
      // $this->db->where('id', $id)->update($this->tableName, ['neo_job_code' => $job_code]);

      $this->db->reset_query();
      $this->replaceJobUsers($id, $data['updated_by'],'Recruiter', $data['recruiters']);
      $this->replaceJobUsers($id, $data['updated_by'],'Placement Officer', $data['placement_officers']);
      $this->db->trans_complete();
      return ['status'=> $this->db->trans_status(), 'job_code'=>$job_code];
    }

    public function replaceJobUsers($job_id, $created_by, $type, $data) {
      $this->db->reset_query();
      $this->db->delete('neo_job.jobs_users', ['job_id'=>$job_id, 'user_type'=>$type]);
      foreach($data as $id) {
        $row = array();
        $row['user_id'] = $id;
        $row['job_id'] = $job_id;
        $row['user_type'] = $type;
        $row['created_by'] = $created_by;
        $this->db->reset_query();
        $this->db->insert('neo_job.jobs_users', $row);
      }
    }

    public function getAssociatedPO($id){
      return array_column($this->db->where('job_id', $id)->where('user_type', 'Placement Officer')->get('neo_job.jobs_users')->result_array(), 'user_id');
    }

    public function getAssociatedRecruiters($id){
      return array_column($this->db->where('job_id', $id)->where('user_type', 'Recruiter')->get('neo_job.jobs_users')->result_array(), 'user_id');
    }

    public function jobDetailCheck($id) {
      $query = $this->db->where('id', $id)
            //  ->group_start()
              ->where('qualification_pack_id', null)
              //->or_where('education_id', null)
              //->group_end()
              // ->where('qualification_pack_id!=', )
              // ->where('education_id!=', '')
              ->get($this->tableName)
              ->result();
      if(count($query)>0) {
        return TRUE;
      }
      return FALSE;
    }


  public function searchJob($page, $data=[]) {
    $offset = ($page==0) ? 0 : ((($page-1) * $this->limit));
    $query = $this->db->select('neo_job.jobs.* , neo_master.qualification_packs.name as qp_name, neo_master.educations.name as edu_name')->from('neo_job.jobs')
            ->join('neo_master.qualification_packs', 'neo_job.jobs.qualification_pack_id = neo_master.qualification_packs.id','LEFT')
            ->join('neo_master.educations', 'neo_job.jobs.education_id = neo_master.educations.id', 'LEFT');
    if(isset($data['qp_ids']) && count($data['qp_ids'])>0) {
      $query = $query->where_in('neo_job.jobs.qualification_pack_id', $data['qp_ids']);
    }
    if(isset($data['education_ids']) && count($data['education_ids'])>0) {
      $query = $query->where_in('neo_job.jobs.education_id', $data['education_ids']);
    }
    if(isset($data['job_location']) && $data['job_location'] !='') {
      $query = $query->like('LOWER(neo_job.jobs.job_location)', strtolower($data['job_location']));
    }
    $query = $query->where('neo_job.jobs.job_status_id', 2)->limit($this->limit, $offset);
    return $query->get()->result();
  }

  public function updateJobStatus($id, $data)
  {
    $request_data['job_status_id'] =  $data['job_status_id'];
    $this->db->where('id', $id);
    $this->db->update($this->tableName, $request_data);
    if($this->db->affected_rows() > 0) {
      $data['job_id'] = $id;
      $this->createJobStatusLog($data);
      return true;
    }
    return false;
  }


  public function assignuser($id, $data)
  {
    $request_data['assigned_user_id'] =  $data['assigned_user_id'];
    $this->db->where('id', $id);
    return $this->db->update($this->tableName, $request_data);
  }

  public function createJobStatusLog($data){
    $this->db->insert('neo_job.jobs_statuses_logs', $data);
    if($this->db->affected_rows() > 0) {
      return true;
    }
    return false;
  }


  public function saveJobApplication($data) {
    $this->db->insert('neo_job.job_applications', $data);
    if($this->db->affected_rows() == 1 ) {
      return true;
    }
    return false;
  }

  public function createJobCode($job_id){
    $result = $this->db->select('neo_job.jobs.id as job_id, neo_customer.companies.company_name as customer_name,
                                neo_master.business_verticals.code as code')
                        ->from('neo_job.jobs')
                        ->join('neo_customer.companies', 'neo_customer.companies.id = neo_job.jobs.customer_id', 'LEFT')
                        ->join('neo_master.business_verticals', 'neo_master.business_verticals.id = neo_job.jobs.business_vertical_id', 'LEFT')
                        ->where('neo_job.jobs.id', $job_id)->get()->row_array();

    return 'JB-'.strtoupper(substr($result['customer_name'], 0, (strlen($result['customer_name'])>3 ? 4 : 3))).'-'.$result['code'].'-'.$job_id;
  }

  public function getPlacementDetails($candidate_id, $job_id) {
    $result = $this->db->select('neo_job.candidates_jobs.candidate_id as candidate_id,
                                neo_job.candidates_jobs.candidate_status_id as candidate_status_id,
                                neo_job.candidates_jobs.job_id as job_id,
                                neo_job.jobs.job_title as job_title,
                                neo_job.jobs.business_vertical_id as bv_id,
                                neo_master.business_verticals.name as bv_name,
                                neo_customer.customers.customer_name as customer_name,
                                neo_job.candidate_placement.date_of_join as joining_date,
                                (SELECT COUNT(JU.user_id) as handler_count from neo_job.jobs_users AS JU where JU.job_id = neo_job.candidates_jobs.job_id GROUP BY JU.job_id ) AS handler_count', FALSE)
                                ->from('neo_job.candidates_jobs')
                        ->join('neo_job.jobs', 'neo_job.jobs.id = neo_job.candidates_jobs.job_id','LEFT')
                        ->join('neo_customer.customers', 'neo_customer.customers.id = neo_job.jobs.customer_id','LEFT')
                        ->join('neo_job.candidate_placement', 'neo_job.candidate_placement.job_id = neo_job.candidates_jobs.job_id AND neo_job.candidate_placement.candidate_id = neo_job.candidates_jobs.candidate_id','LEFT')
                        ->join('neo_master.business_verticals', 'neo_master.business_verticals.id = neo_job.jobs.business_vertical_id','LEFT')
                        ->where('neo_job.candidates_jobs.candidate_id', $candidate_id)
                        ->where('neo_job.candidates_jobs.candidate_status_id', 15)
                        ->where('neo_job.candidates_jobs.job_id !=', $job_id)
                        ->get()->result_array();
    return $result;
  }

  public function getJobHandlers($job_id) {
    $result = $this->db->select('neo_user.users.name, neo_user.users.email, neo_job.jobs_users.user_type')
                       ->from('neo_job.jobs_users')
                       ->join('neo_user.users', 'neo_user.users.id = neo_job.jobs_users.user_id','LEFT')
                       ->where('neo_job.jobs_users.job_id', $job_id)
                       ->get()->result();
    return $result;
  }

}

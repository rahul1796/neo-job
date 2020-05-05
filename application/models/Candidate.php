<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Candidate extends MY_Model {

    protected $tableName = 'neo.candidates';
    private $jobTable = 'neo_job.jobs';
    private $limit = 20;

    public function find($id) {
        return $this->db->select('neo.candidates.*, neo_master.states.id as main_state_id, neo_master.states.name as state_name,
          neo_master.districts.id as main_district_id, neo_master.districts.name as district_name')->from($this->tableName)
                        ->join('neo_master.districts', 'neo.candidates.district_id=neo_master.districts.id', 'LEFT')
                        ->join('neo_master.states', 'neo_master.districts.state_id=neo_master.states.id', 'LEFT')
                        ->where('neo.candidates.id', $id)->get()->row_array();
    }

    public function candidatesByJobPreferenceQuery($id, $search_data) {
        $job = $this->db->select(' neo_job.jobs.qualification_pack_id')
                        ->from($this->jobTable)
                        ->where('neo_job.jobs.id', $id)->get()->row();
        $candidate_ids_exclude = $this->jobCandidateIDs($id);
        $query = $this->db->select('neo.candidates.*, neo_master.genders.name as gender_code, CE.employment_type as emp_type,
                                CE.designation as emp_designation,  QP.qp_name as qp_name, QP.batch_code as batch_code,
                                QP.center_name as center_name, QP.course_name as course_name, E.education_name as edu_name,
                                QP.qualification_pack_id, E.education_id, E.weightage, neo_master.religions.name as religion,
                                neo_master.caste_categories.name as caste_cat,
                                neo_master.marital_statuses.name as marital_status', false)->from($this->tableName);

        $query = $this->candidateAssociationQuery($query);

        $query->where('QP.qualification_pack_id', $job->qualification_pack_id, false)
              ->where('neo.neo_batches.is_active', TRUE);

        $query = $this->searchBuilder($query, $search_data);

        if (count($candidate_ids_exclude) > 0) {
            $query = $query->where_not_in('neo.candidates.id', $candidate_ids_exclude);
        }
        return $query;
    }

    public function candidatesByJobPreferenceCount($id, $search_data) {
        return $this->candidatesByJobPreferenceQuery($id, $search_data)->count_all_results();
    }

    public function candidatesByJobPreference($id, $page, $search_data) {
        $offset = ($page == 0) ? 0 : ((($page - 1) * $this->limit));
        return $this->candidatesByJobPreferenceQuery($id, $search_data)->limit($this->limit, $offset)->get()->result();
    }

    //MTO Candidates only
    public function candidatesMTOQuery($id, $search_data) {

        $candidate_ids_exclude = $this->jobCandidateIDs($id);
        $query = $this->db->select('neo.candidates.*, neo_master.genders.name as gender_code, CE.employment_type as emp_type,
                                CE.designation as emp_designation,  QP.qp_name as qp_name, QP.batch_code as batch_code,
                                QP.center_name as center_name, QP.course_name as course_name, E.education_name as edu_name,
                                QP.qualification_pack_id, E.education_id, E.weightage,
                                neo_master.genders.name as gender_code, CE.employment_type as emp_type,
                                neo_master.religions.name as religion, neo_master.caste_categories.name as caste_cat,
                                neo_master.marital_statuses.name as marital_status', false)
                ->from($this->tableName);
        $query = $this->candidateAssociationQuery($query);
        $query = $query->where('neo.candidates.mt_type', 'MTO');

        $query = $this->searchBuilder($query, $search_data);

        if (count($candidate_ids_exclude) > 0) {
            $query = $query->where_not_in('neo.candidates.id', $candidate_ids_exclude);
        }
        return $query;
    }

    public function candidatesMTOCount($id, $search_data) {
        return $this->candidatesMTOQuery($id, $search_data)->count_all_results();
    }

    public function candidatesByMTO($id, $page, $search_data) {
        $offset = ($page == 0) ? 0 : ((($page - 1) * $this->limit));
        return $this->candidatesMTOQuery($id, $search_data)->limit($this->limit, $offset)->get()->result();
    }

    public function candidatesAllQuery($id, $search_data) {

        $job = $this->db->select(' neo_job.jobs.qualification_pack_id')
                        ->from($this->jobTable)
                        ->where('neo_job.jobs.id', $id)->get()->row();

        $candidate_ids_exclude = $this->jobCandidateIDs($id);
        $query = $this->db->select('neo.candidates.*, neo_master.genders.name as gender_code, CE.employment_type as emp_type,
                                  CE.designation as emp_designation,  QP.qp_name as qp_name, QP.batch_code as batch_code,
                                  QP.center_name as center_name, QP.course_name as course_name, E.education_name as edu_name,
                                  QP.qualification_pack_id, E.education_id, E.weightage, neo_master.religions.name as religion, neo_master.caste_categories.name as caste_cat,
                                  neo_master.marital_statuses.name as marital_status', FALSE)
                ->from($this->tableName);

        $query = $this->candidateAssociationQuery($query);

        $query = $query->where('QP.qualification_pack_id !=', $job->qualification_pack_id, false);

        $query = $this->searchBuilder($query, $search_data);

        if (count($candidate_ids_exclude) > 0) {
            $query = $query->where_not_in('neo.candidates.id', $candidate_ids_exclude);
        }
        return $query;
    }

    public function candidatesAllCount($id, $search_data) {
        return $this->candidatesAllQuery($id, $search_data)->count_all_results();
    }

    public function candidatesAll($id, $page, $search_data) {
        $offset = ($page == 0) ? 0 : ((($page - 1) * $this->limit));
        return $this->candidatesAllQuery($id, $search_data)->limit($this->limit, $offset)->get()->result();
    }

    //candidates by jobs

    public function jobCandidateQuery($id, $search_data) {
        $query = $this->db->select('neo.candidates.*, neo_job.candidates_jobs.candidate_status_id, CE.employment_type as emp_type,
                                CE.designation as emp_designation,  QP.qp_name as qp_name, QP.batch_code as batch_code,
                                QP.center_name as center_name, QP.course_name as course_name, E.education_name as edu_name,
                                QP.qualification_pack_id, E.education_id, E.weightage, neo_master.candidate_statuses.name,
                                neo_master.genders.name as gender_code, CE.employment_type as emp_type,
                                neo_master.religions.name as religion, neo_master.caste_categories.name as caste_cat,
                                neo_master.marital_statuses.name as marital_status', false)
                ->from('neo_job.candidates_jobs')
                ->join('neo.candidates', 'neo_job.candidates_jobs.candidate_id=neo.candidates.id', 'INNER')
                ->join('neo_master.candidate_statuses', 'neo_job.candidates_jobs.candidate_status_id = neo_master.candidate_statuses.id', 'LEFT');
        $query = $this->candidateAssociationQuery($query);
        $query = $query->where('neo_job.candidates_jobs.job_id', $id)->where_not_in('neo_job.candidates_jobs.candidate_status_id', [15,17]);

        $query = $this->searchBuilder($query, $search_data);
        return $query;
    }

    public function jobCandidatesCount($id, $search_data) {
        return $this->jobCandidateQuery($id, $search_data)->count_all_results();
    }

    public function jobCandidates($id, $page, $search_data) {
        $offset = ($page == 0) ? 0 : ((($page - 1) * $this->limit));
        return $this->jobCandidateQuery($id, $search_data)->limit($this->limit, $offset)
                        ->order_by('created_at', 'DESC')->get()->result();
    }

    public function jobCandidateIDs($id) {
        $candidate_ids = $this->db->select('candidate_id')->where('job_id', $id)->get('neo_job.candidates_jobs')->result_array();
        return array_column($candidate_ids, 'candidate_id');
    }

    public function addCandidateJob($data, $remark) {
        $this->db->insert('neo_job.candidates_jobs', $data);
        if ($this->db->affected_rows() == 1) {
            $this->addJobLog($data, $remark);
            return true;
        }
        return false;
    }

    public function updateCandidateJobStatus($data, $remark) {
        if (isset($data['schedule_date'])) {
            $schedule_date = $data['schedule_date'];
            unset($data['schedule_date']);
        }
        $this->db->where('candidate_id', $data['candidate_id']);
        $this->db->where('job_id', $data['job_id']);
        $this->db->update('neo_job.candidates_jobs', $data);
        if ($this->db->affected_rows() == 1) {
            if (isset($schedule_date) && $schedule_date != '') {
                $data['schedule_date'] = $schedule_date;
            }
            $this->addJobLog($data, $remark);
            return true;
        }
        return false;
    }

    public function addJobLog($data, $remark = '') {
        $data['remarks'] = $remark;
        $this->db->insert('neo_job.candidates_jobs_logs', $data);
    }

    public function candidateAssociationQuery($query) {
        $query = $query->join('(SELECT  CED.candidate_id, CED.education_id, EDU.name AS education_name, EDU.weightage,
              ROW_NUMBER() OVER(PARTITION BY CED.candidate_id ORDER BY CED.candidate_id,CED.id DESC) AS counter
              FROM  neo.candidate_education_details AS CED LEFT JOIN neo_master.educations AS EDU
              ON EDU.id=CED.education_id) AS E', 'E.candidate_id=neo.candidates.id AND E.counter=1', 'LEFT', FALSE)
                ->join('(SELECT E.candidate_id, E.designation, E.employment_type,
              ROW_NUMBER() OVER(PARTITION BY E.candidate_id ORDER BY E."from" DESC)
              AS counter FROM 	neo.candidate_employment_details AS E) AS CE', 'CE.candidate_id=neo.candidates.id AND CE.counter=1', 'LEFT', false)
                ->join('(SELECT  CQP.candidate_id, CQP.batch_code, CQP.center_name, CQP.course_name, CQP.qualification_pack_id, CASE Q.id WHEN NULL THEN \'NA\' ELSE FORMAT(\'%s (%s)\',Q.name,Q.code)
              END AS qp_name, ROW_NUMBER() OVER(PARTITION BY CQP.candidate_id ORDER BY CQP.candidate_id,CQP.id DESC) AS counter
              FROM  neo.candidate_qp_details AS CQP LEFT JOIN neo_master.qualification_packs AS Q ON Q.id=CQP.qualification_pack_id) AS QP'
                        , 'QP.candidate_id=neo.candidates.id AND QP.counter=1', 'LEFT', FALSE)
                ->join('neo_master.genders', 'neo.candidates.gender_id = neo_master.genders.id', 'LEFT')
                ->join('neo_master.marital_statuses', 'neo.candidates.marital_status_id = neo_master.marital_statuses.id', 'LEFT')
                ->join('neo_master.religions', 'neo.candidates.religion_id = neo_master.religions.id', 'LEFT')
                ->join('neo_master.caste_categories', 'neo.candidates.caste_category_id = neo_master.caste_categories.id', 'LEFT')
                // ->join('neo.neo_batches', 'neo.neo_batches.ln_batch_code = neo.candidates.batch_code', 'LEFT');
                ->join('neo.neo_batches', 'neo.neo_batches.batch_code=neo.candidates.batch_code', 'LEFT');
        return $query;
    }

    public function searchBuilder($query, $search_data) {
        if (!empty($search_data['search_candidate_type'])) {
            $query = $query->where('neo.candidates.mt_type', $search_data['search_candidate_type']);
        }
        if (!empty($search_data['search_employment_type'])) {
            $query = $query->where('CE.employment_type', "'" . $search_data['search_employment_type'] . "'", FALSE);
        }
        if (!empty($search_data['search_education'])) {
            $query = $query->where_in('E.education_id', $search_data['search_education'], FALSE);
        }
        if (!empty($search_data['search_phone'])) {
            $query = $query->like('neo.candidates.mobile', $search_data['search_phone']);
        }
        if (!empty($search_data['search_email'])) {
            $query = $query->like('LOWER(neo.candidates.email)', strtolower($search_data['search_email']));
        }
        if (!empty($search_data['search_name'])) {
            $query = $query->like('LOWER(neo.candidates.candidate_name)', strtolower($search_data['search_name']));
        }
        if (!empty($search_data['search_gender'])) {
            $query = $query->where('neo.candidates.gender_id', $search_data['search_gender']);
        }
        if (!empty($search_data['search_qualification_pack'])) {
            $query = $query->where('QP.qualification_pack_id', $search_data['search_qualification_pack'], FALSE);
        }
        if (!empty($search_data['search_course_name'])) {
            $query = $query->like('LOWER(QP.course_name)', strtolower($search_data['search_course_name']), FALSE);
        }
        if (!empty($search_data['search_center_name'])) {
            $query = $query->like('LOWER(QP.center_name)', strtolower($search_data['search_center_name']), FALSE);
        }
        if (!empty($search_data['search_batch_code'])) {
            $query = $query->like('LOWER(QP.batch_code)', strtolower($search_data['search_batch_code']), FALSE);
        }
        $query = $query->where('neo.candidates.is_active', TRUE);
        return $query;
    }

}

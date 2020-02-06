<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Model
{

  private $assignedJobs = [];
  //candidate job statuses
  public function hierarchy() {
      return $this->session->userdata['user_hierarchy'];
  }

  public function assignedJobs() {
    $job_ids =  $this->db->select('job_id')->where_in('user_id', $this->hierarchy())
    ->get('neo_job.jobs_users')->result_array();
    $this->assignedJobs = array_column($job_ids, 'job_id');
    return $this->assignedJobs;
  }

  public function assignedLeads() {
    $lead_ids = $this->db->select('lead_id')->where_in('user_id', $this->hierarchy())
    ->get('neo_customer.leads_users')->result_array();
    return array_column($lead_ids, 'lead_id');
  }

  public function getCandidatesCount() {
    return $this->IND_money_format($this->db->select('*')->from('neo.candidates')->count_all_results());
  }

  public function getCompanyCount() {
    return $this->db->where_in('created_by', $this->hierarchy())->from('neo_customer.companies')->count_all_results();
  }

  public function getOpportunityCount() {
    return $this->db->where_in('created_by', $this->hierarchy())->from('neo_customer.opportunities')->count_all_results();
  }

  public function getContractCount() {
    return $this->db->where('is_contract', true)->where_in('created_by', $this->hierarchy())->from('neo_customer.opportunities')->count_all_results();
  }

  public function getEmployerCount() {
    //return $this->IND_money_format($this->db->where('is_customer', true)->from('neo_customer.customers')->count_all_results());
    return $this->db->where('is_customer', true)->where_in('created_by', $this->hierarchy())->from('neo_customer.customers')->count_all_results();
  }

  public function getLeadCount() {
    $lead_ids = $this->assignedLeads();

    $this->db->where('is_customer', false);

    $this->db->group_start();
    $this->db->where_in('created_by', $this->hierarchy());
    if(count($lead_ids)>0)
    {
      $this->db->or_where_in('id', $lead_ids);
    }
    $this->db->group_end();
    return $this->db->from('neo_customer.customers')->count_all_results();
  }

  public function getJobsCount() {
    $job_ids = $this->assignedJobs();

    $this->db->where_in('created_by', $this->hierarchy());

    if(count($job_ids)>0)
    {
      $this->db->or_where_in('id', $job_ids);
    }
    return $this->db->from('neo_job.jobs')->count_all_results();
  }

  /******** SUMIT *************/

    public function getJobOpeningsCount() {
       //return $this->IND_money_format($this->db->select_sum('no_of_position::INTEGER')->where_in('created_by', $this->hierarchy())->where_in('job_status_id', [2])->from('neo_job.jobs')->get()->row('no_of_position'));
       $HierarchyIdList = join(",",$this->hierarchy());

        $query = "WITH P AS
                (
                    SELECT 	CP.job_id,
                                COUNT(DISTINCT CP.candidate_id) AS joined_count
                    FROM 	neo_job.candidate_placement AS CP
                    WHERE 	CP.date_of_join IS NOT NULL
                    GROUP BY 	CP.job_id
                )
                SELECT      COALESCE(SUM(no_of_position::INTEGER),0) AS job_openings,
                            COALESCE(SUM(P.joined_count),0) AS joined_count,
                            (CASE
                                WHEN COALESCE(SUM(P.joined_count),0)>COALESCE(SUM(no_of_position::INTEGER),0) THEN 0
                                ELSE COALESCE(SUM(no_of_position::INTEGER),0)-COALESCE(SUM(P.joined_count),0)
                            END) AS total_openings
                FROM        neo_job.jobs AS J
                LEFT JOIN   P ON P.job_id=J.id
                WHERE       J.job_status_id=2
                AND         J.created_by IN (".$HierarchyIdList.") ";
            $result = $this->db->query($query)->row()->total_openings;
            return $result;
    }

 /******* SUMIT END *************/
  public function getInterestedCandidatesCount() {
    return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [1])->from('neo_job.candidates_jobs')->count_all_results());
  }

  public function getPendingCandidatesCount() {
    return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [3])->from('neo_job.candidates_jobs')->count_all_results());
  }

  public function getProfileCandidatesCount() {
    return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [2,4])->from('neo_job.candidates_jobs')->count_all_results());
  }

  public function getInterviewCandidatesCount() {
    return $this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [6,7])->from('neo_job.candidates_jobs')->count_all_results();
  }

  public function getSelectedCandidatesCount() {
    return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [9])->from('neo_job.candidates_jobs')->count_all_results());
  }

  public function getOfferedCandidatesCount() {
    return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [12])->from('neo_job.candidates_jobs')->count_all_results());
  }

    public function getJoinedCandidatesCount() {
        //return $this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [15])->from('neo_job.candidates_jobs')->count_all_results();
        $HierarchyIdList = join(",",$this->hierarchy());

        $query = "WITH P AS
                (
                    SELECT 	CP.job_id,
                                COUNT(DISTINCT CP.candidate_id) AS joined_count
                    FROM 	neo_job.candidate_placement AS CP
                    WHERE 	CP.date_of_join IS NOT NULL
                    GROUP BY 	CP.job_id
                )
                SELECT      COALESCE(SUM(P.joined_count),0) AS joined_count
                FROM        neo_job.jobs AS J
                LEFT JOIN   P ON P.job_id=J.id
                WHERE       J.job_status_id=2
                AND         J.created_by IN (".$HierarchyIdList.") ";
        $result = $this->db->query($query)->row()->joined_count;
        return $result;
    }

    public function getNotjoinedCandidates() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('candidate_status_id', [16])->from('neo_job.candidates_jobs')->count_all_results());
    }

    //jobs status counts

    public function getDraftedJobsCount() {
      return $this->IND_money_format($this->db->where_in('job_status_id', [1])->group_start()->where_in('created_by', $this->hierarchy())->or_where_in('id', $this->assignedJobs)->group_end()->from('neo_job.jobs')->count_all_results());
    }

    public function getOpenJobsCount() {
      return $this->IND_money_format($this->db->where_in('job_status_id', [2])->group_start()->where_in('created_by', $this->hierarchy())->or_where_in('id', $this->assignedJobs)->group_end()->from('neo_job.jobs')->count_all_results());
    }

    public function getClosedJobsCount() {
      return $this->IND_money_format($this->db->where_in('job_status_id', [3])->group_start()->where_in('created_by', $this->hierarchy())->or_where_in('id', $this->assignedJobs)->group_end()->from('neo_job.jobs')->count_all_results());
    }

    public function getOnHoldJobsCount() {
      return $this->IND_money_format($this->db->where_in('job_status_id', [4])->group_start()->where_in('created_by', $this->hierarchy())->or_where_in('id', $this->assignedJobs)->group_end()->from('neo_job.jobs')->count_all_results());
    }

    //customer status counts


    public function getLeadIdentifiedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [1])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getInitialMeetingScheduleCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [2])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getInitialMeetingCompletedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [3])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getOpLostAtEntryLevelCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [4])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getFollowUpMeetingScheduleCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [5])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getFollowUpMeetingCompletedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [6])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getOpLostAtFollowUpLevelCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [7])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getProposalSharedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [8])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getProposalUnderReviewCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [9])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getProposalUnderRFECount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [10])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getNegotiationCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [11])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getProposalAcceptedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [12])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getOpLostAtProposalLevelCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [13])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getOpportunityLostCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [18])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getLegalApprovedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [20])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getLegalRejectedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [21])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getContractCompletedCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [22])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getLeadConvertToClientCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [16])->from('neo_customer.opportunities')->count_all_results());
    }

    public function getOnHoldCount() {
      return $this->IND_money_format($this->db->where_in('created_by', $this->hierarchy())->where_in('lead_status_id', [17])->from('neo_customer.opportunities')->count_all_results());
    }

  private function IND_money_format($number)
  {
    $decimal = (string)($number - floor($number));
    $money = floor($number);
    $length = strlen($money);
    $delimiter = '';
    $money = strrev($money);

    for($i=0;$i<$length;$i++){
      if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
        $delimiter .=',';
      }
      $delimiter .=$money[$i];
    }

    $result = strrev($delimiter);
    $decimal = preg_replace("/0\./i", ".", $decimal);
    $decimal = substr($decimal, 0, 3);

    if( $decimal != '0'){
      $result = $result.$decimal;
    }

    return $result;
  }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model :: Employer Model
 * @author Sangamesh.p@pramaan.in
 * prefix(get/do for fetching and add/update respectivly)
**/
class Employer_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->load->helper('role_helper');
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to  add job
	*/
	function do_add_job($data,$data_location)
	{
		$this->db->insert('job_process.jobs', $data);
		$created_on=date('Y-m-d');
	  	if($this->db->affected_rows())
	  	{
	  		$job_id=$this->db->insert_id();
	  		foreach ($data_location as $value)
	  		{
	  			$value['job_id']=$job_id;
	  			$value['created_on']=$created_on;
	  			$this->db->insert('job_process.job_detail',$value);
	  		}

	  		return $this->db->insert_id();
	  	}
	  	else
	  	{
	  		return false;
	  	}

	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to  edit job
	*/
	function do_update_job($job_id,$data,$data_location)
	{
		$this->db->update('job_process.jobs', $data,array('id'=>$job_id));

		$created_on=date('Y-m-d');
	  	if($this->db->affected_rows())
	  	{

	  		$this->db->query('update job_process.job_detail set job_status=? where job_id=?',array('f',$job_id));
	  		foreach ($data_location as $value)
	  		{
	  			$value['job_id']=$job_id;
	  			$value['created_on']=$created_on;
	  			$this->db->insert('job_process.job_detail',$value);
	  		}

	  		return $this->db->insert_id();
	  	}
	  	else
	  	{
	  		return false;
	  	}

	}
	/**
	 * @author Sangamesh <sangamesh@pramaan.in>
	 * function to get job list /*AND VW.id=509
	*/
    function get_job_list($job_status_id=2,$qp_id=0,$education_id=0,$pg=0,$limit=25,$user_id = 0)
	{

		$cond = '';
                $active_user_role_id = $this->session->userdata('usr_authdet')['user_group_id'];
                $TeamMemberIdList = implode(",",$this->session->userdata('user_hierarchy'));
                //$user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];



		if ($job_status_id > 0) $cond .= " AND VW.job_status_id=$job_status_id ";
		if ($qp_id > 0) $cond .= " AND VW.qualification_pack_id=$qp_id ";
		if ($education_id > 0) $cond .= " AND VW.education_id=$education_id ";
                 $strUserRoleCondition = 'TRUE';

                $sWhere = " WHERE TRUE ";

                    if ($active_user_role_id == 14 || $active_user_role_id == 11)
                    {
                        $sWhere = " WHERE $user_id=ANY(VW.assigned_user_ids)";
                    }

                     $HierarchyCondition = "";
                            if ($active_user_role_id <> $active_user_role_id)
                            {
                                if ($TeamMemberIdList != '')
                                $HierarchyCondition = " AND (assigned_user_ids && ARRAY[$TeamMemberIdList]) ";
                            }

		$total_records = 0;
		$job_list_rec = $this->db->query("SELECT COUNT(VW.*) AS total_recs
                                                FROM neo_job.vw_job_list AS VW
                                         $sWhere $cond $HierarchyCondition") or die('<pre>' . pg_last_error() . '</pre>');

		if ($job_list_rec->num_rows())
			$total_records = $job_list_rec->row()->total_recs;

		if (!$total_records)
			return array('status' => 'error', 'message' => "<p style='text-align:center'>No result-data found</p>");
		else
		{
			$job_list_detail = $this->db->query("SELECT * FROM neo_job.vw_job_list AS VW
                                                             $sWhere $cond $HierarchyCondition
                                                            ORDER BY VW.id DESC
                                                            LIMIT $limit OFFSET $pg") or die('<pre>' . pg_last_error() . '</pre>');

			$ttl_res_curr = $job_list_detail->num_rows();
			$page_number = ($pg / $limit + 1);
			$pg_count_msg = "Showing " . (1 + $pg) . " to " . ($ttl_res_curr + $pg) . " of " . $total_records;
			$pagination = _prepare_pagination( site_url( "partner/job_board_list/$job_status_id/$qp_id/$education_id"), $total_records, $limit, 6 );
			return array
			(
				'status' => 'success',
				'rdata' => array(
					'job_list' => $job_list_detail->result_array(),
					'pg' => $pg,
					'limit' => $limit,
					'pagination' => $pagination,
					'pg_count_msg' => $pg_count_msg,
					'page_number' => $page_number
				)
			);
		}
	}
	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get application tracker list
	*/
	function get_applicationTracker_employer($requestData=array(),$employer_id=0)
	{
		$cond='';
		$order_by="";
		$data = array();
		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'job_desc',
		    2 => 'applied_count',
		    3 => 'screened_count',
		    4 => 'scheduled_count',
		    5 => 'shortlisted_count',
		    6 => 'selected_count',
		    7 => 'offered_count',
		    8 => 'screening_rejected_count',
		    9 => 'schedule_rejected_count',
		    10 => null
		);
		if($employer_id)
			$cond=" WHERE pj.employer_id=".$employer_id;

		$column_search = array("pj.job_desc"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM job_process.jobs pj $cond")->row()->total_recs;

		$totalData=$total_records*1;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		if($columns[$requestData['order'][0]['column']]!='')
		{
			$order_by=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";
		}

		$pg=$requestData['start'];
		$limit=$requestData['length'];
		if($limit<0)
			$limit='all';
		// ==== filters end =====

		if(!$total_records)
		{
			return array('sEcho'=>'1', "iTotalRecords"=>"0", "iTotalDisplayRecords"=>"0",'aaData'=>array());
		}
		else
		{
			/*
	         * Filtering
	         */
	        $sWhere = "";
	        $sSearchVal = $_POST['search']['value'];
	        if (isset($sSearchVal) && $sSearchVal != '')
	        {
	            $sWhere = "WHERE (";
	            for ($i = 0; $i < count($column_search); $i++)
	            {
	                $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
	            }
	            $sWhere = substr_replace($sWhere, "", -3);
	            $sWhere .= ')';
	        	//for employer_id parameter
	        	if($employer_id)
	        	$sWhere.=' and '. "pj.employer_id=".$employer_id;
	        }
	        else
	        	$sWhere=$cond;

	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM job_process.jobs pj
												$sWhere")->row()->total_filtered;

			$tracker_recs=$this->db->query("SELECT pj.id as scheduled_job_id,pj.qualification_pack_id,pj.job_desc,to_char(pj.created_on, 'DD/Mon/YYYY') as created_on,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = 1) AS applied_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = 2) AS screened_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = 3) AS scheduled_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = 4) AS shortlisted_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = 5) AS selected_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = 6) AS offered_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = -2) AS screening_rejected_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = -3) AS schedule_rejected_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = -4) AS shortlist_rejected_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = -5) AS selection_rejected_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id=pj.id AND status_id = -6) AS offer_rejected_count
											FROM job_process.jobs pj
						        			$sWhere
											$order_by limit $limit OFFSET $pg");

			$slno=$pg;
			$data = array();
			foreach ($tracker_recs->result() as $applicationTracker)
			{  // preparing an array
				$row=array();
				$slno++;
				$row[] = $slno;
				$row[] = $applicationTracker->job_desc;
				$row[] = ($applicationTracker->applied_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',1)">'.$applicationTracker->applied_count.'</b></a>':$applicationTracker->applied_count;
				$row[] = ($applicationTracker->screened_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',2)">'.$applicationTracker->screened_count.'</b></a>':$applicationTracker->screened_count;
				$row[] = ($applicationTracker->scheduled_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',3)">'.$applicationTracker->scheduled_count.'</b></a>':$applicationTracker->scheduled_count;
				$row[] = ($applicationTracker->shortlisted_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',4)">'.$applicationTracker->shortlisted_count.'</b></a>':$applicationTracker->shortlisted_count;
				$row[] = ($applicationTracker->selected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',5)">'.$applicationTracker->selected_count.'</b></a>':$applicationTracker->selected_count;
				$row[] = ($applicationTracker->offered_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',6)">'.$applicationTracker->offered_count.'</b></a>':$applicationTracker->offered_count;
				$row[] = ($applicationTracker->screening_rejected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',-2)">'.$applicationTracker->screening_rejected_count.'</b></a>':$applicationTracker->screening_rejected_count;
				$row[] = ($applicationTracker->schedule_rejected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$applicationTracker->scheduled_job_id."'".',-3)">'.$applicationTracker->schedule_rejected_count.'</b></a>':$applicationTracker->schedule_rejected_count;
				$data[] = $row;
			}


			//  $data[] = $employee_recs->result_array();
			$application_tracker_recs = array(
		            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		            "recordsTotal"    => intval( $totalData ),  // total number of records
		            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		            "data"            => $data   // total data array
		            );
			return $application_tracker_recs;
		}
	}

	function get_tracked_job_candidates($job_id=0,$job_status_id=0)
	{
		$job_det_rec=$this->db->query("SELECT pj.id as job_id,pj.job_desc,to_char(pj.created_on, 'DD/Mon/YYYY') as  created_on,(SELECT name from master.status where value=$job_status_id) as job_status
												from job_process.jobs pj
												where pj.id=?",$job_id);

		$candidate_det_rec=$this->db->query("SELECT uc.name as candidate_name, uc.mobile,uc.gender_code,to_char(cj.created_on, 'DD/Mon/YYYY') as created_on,e.name as employer_name
										from job_process.candidate_jobs cj
										left join users.candidates uc on uc.id=cj.candidate_id
										left join users.employers e on e.user_id=uc.referer_id
										where cj.job_id=? and cj.status_id=?",array($job_id,$job_status_id));

		if($job_det_rec->num_rows())
		{
			$output['status']=true;
			$output['job_detail']=$job_det_rec->row_array();
			if($candidate_det_rec->num_rows())
				$output['candidate_detail']=$candidate_det_rec->result_array();
			else
				$output['candidate_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}

	function do_get_scheduled_candidates($job_id=0,$employer_id=0,$pg=0,$limit=25)
	{
		$scheduled_job_status=3;
		$cond='';
		$total_records=0;

		// ==== filters end =====
		$scheduled_job_list_rec=$this->db->query("SELECT count(*) as total_rec
													from job_process.candidate_jobs cj
													left join job_process.jobs pj on pj.id=cj.job_id
													left join users.candidates uc on uc.id=cj.candidate_id
													inner JOIN master.education e on e.id=uc.education_id
													where  cj.job_id=? and pj.employer_id=? and abs(cj.status_id)>=?", array($job_id,$employer_id,$scheduled_job_status)) or die('<pre>'.pg_last_error().'</pre>');

		if($scheduled_job_list_rec->num_rows())
		{
			$total_records=$scheduled_job_list_rec->row()->total_rec;
		}

		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else
		{
			$scheduled_job_list_detail=$this->db->query("SELECT cj.id as candidate_job_id, uc.id as candidate_id,uc.mobile,uc.name,cj.status_id,to_char(cj.created_on, 'DD/Mon/YYYY') as created_on,st.name as job_status,ls1.name as total_experience,
															e.name as qualification,date_part('year',COALESCE(age(current_date, uc.dob),'0 Years') ) as age
															from job_process.candidate_jobs cj
															left join job_process.jobs pj on pj.id=cj.job_id
															left join users.candidates uc on uc.id=cj.candidate_id
															left join master.status st on st.value=cj.status_id
															inner JOIN master.list ls1 on uc.experience_id=(ls1.value)::int and ls1.code='L0006'
															inner JOIN master.education e on e.id=uc.education_id
															where cj.job_id=?
															and pj.employer_id=?
															and abs(cj.status_id)>=?
															LIMIT $limit OFFSET $pg",array($job_id,$employer_id,$scheduled_job_status)) or die('<pre>'.pg_last_error().'</pre>');


		$ttl_res_curr=$scheduled_job_list_detail->num_rows();
		$page_number=($pg/$limit+1);
		$pg_count_msg = "Showing ".(1+$pg)." to ".($ttl_res_curr+$pg)." of ".$total_records;
		$pagination=_prepare_pagination(site_url("employer/scheduled_candidates_list/$job_id/$employer_id"), $total_records, $limit,5);
		return array('status'=>'success','rdata'=>array('scheduled_candidates_list'=>$scheduled_job_list_detail->result_array()
					,'pg'=>$pg,'limit'=>$limit,'pagination'=>$pagination,'pg_count_msg'=>$pg_count_msg,'page_number'=>$page_number));
		}

	}

	function do_update_joing_status($data,$data2)
	{
		$this->db->trans_start();

		$this->db->where('candidate_id', $data['candidate_id']);
		$this->db->where('job_id', $data['job_id']);
		$this->db->update('neo_job.candidate_placement', $data);
                $this->db->reset_query();
                $this->db->where('candidate_id', $data['candidate_id']);
		$this->db->where('job_id', $data['job_id']);
                $data2['placement_id']=$this->db->get('neo_job.candidate_placement')->row_array()['id'];
                $this->db->reset_query();
                $this->db->delete('neo.candidate_employment_details', ['placement_id'=> $data2['placement_id']]);
                $this->db->reset_query();
                $this->db->insert('neo.candidate_employment_details', $data2);
                $this->db->reset_query();

		$mdata['candidate_id'] = $data['candidate_id'];
		$mdata['job_id'] = $data['job_id'];
		$mdata['candidate_status_id'] = 15;

		$this->db->reset_query();

		$this->db->where('job_id', $data['job_id'])->where('candidate_id', $data['candidate_id'])
		->set('candidate_status_id', 	$mdata['candidate_status_id'])
		->update('neo_job.candidates_jobs');


		$this->db->reset_query();
		$this->db->insert('neo_job.candidates_jobs_logs', $mdata);
		$this->db->trans_complete();

		return $this->db->trans_status();
		// if($this->db->affected_rows()==1)
		// {
		// 		$this->db->reset_query();
		// 		$mdata['candidate_id'] = $data['candidate_id'];
		// 		$mdata['job_id'] = $data['job_id'];
		// 		$mdata['candidate_status_id'] = 15;
		// 		$this->db->insert('neo_job.candidates_jobs_logs', $mdata);
		//
		// 	return true;
		// }
	  // 	return false;
	}



        function do_update_offered_status($data)
	{
            $this->db->trans_start();

		$this->db->where('candidate_id', $data['candidate_id']);
		$this->db->where('job_id', $data['job_id']);
		$this->db->update('neo_job.candidate_placement', $data);

		$mdata['candidate_id'] = $data['candidate_id'];
		$mdata['job_id'] = $data['job_id'];
		$mdata['candidate_status_id'] = 12;

		$this->db->reset_query();

		$this->db->where('job_id', $data['job_id'])->where('candidate_id', $data['candidate_id'])
		->set('candidate_status_id', 	$mdata['candidate_status_id'])
		->update('neo_job.candidates_jobs');

		$this->db->reset_query();

		$this->db->insert('neo_job.candidates_jobs_logs', $mdata);

		$this->db->trans_complete();

		return $this->db->trans_status();
		// if($this->db->affected_rows()==1)
		// {
		// 		$this->db->reset_query();
		// 		$mdata['candidate_id'] = $data['candidate_id'];
		// 		$mdata['job_id'] = $data['job_id'];
		// 		$mdata['candidate_status_id'] = 15;
		// 		$this->db->insert('neo_job.candidates_jobs_logs', $mdata);
		//
		// 	return true;
		// }
	  // 	return false;
	}
        
        
        function do_update_resigned_status($data)
	{
		$this->db->trans_start();
		$this->db->where('candidate_id', $data['candidate_id']);
		$this->db->where('job_id', $data['job_id']);
		$this->db->update('neo_job.candidate_placement', $data);
                $this->db->reset_query();
                $this->db->where('candidate_id', $data['candidate_id']);
		$this->db->where('job_id', $data['job_id']);
                $data2['placement_id']=$this->db->get('neo_job.candidate_placement')->row_array()['id'];
                $this->db->reset_query();             

		$mdata['candidate_id'] = $data['candidate_id'];
		$mdata['job_id'] = $data['job_id'];
		$mdata['candidate_status_id'] = 17;

		$this->db->reset_query();

		$this->db->where('job_id', $data['job_id'])->where('candidate_id', $data['candidate_id'])
		->set('candidate_status_id', 	$mdata['candidate_status_id'])
		->update('neo_job.candidates_jobs');


		$this->db->reset_query();
		$this->db->insert('neo_job.candidates_jobs_logs', $mdata);
		$this->db->trans_complete();

		return $this->db->trans_status();
		// if($this->db->affected_rows()==1)
		// {
		// 		$this->db->reset_query();
		// 		$mdata['candidate_id'] = $data['candidate_id'];
		// 		$mdata['job_id'] = $data['job_id'];
		// 		$mdata['candidate_status_id'] = 15;
		// 		$this->db->insert('neo_job.candidates_jobs_logs', $mdata);
		//
		// 	return true;
		// }
	  // 	return false;
	}

	function do_get_job_detail($job_id=0)
	{

		$job_detail_rec=$this->db->query("SELECT jd.*,concat(jd.min_salary,'-',jd.max_salary) as salary
											from job_process.vw_job_detail jd
											where job_id=?",$job_id);
		if($job_detail_rec->num_rows())
		{
			return $job_detail_rec->result_array();
		}
		else
			return false;
	}

	function get_job_applied_details($job_id=0,$candidate_id=0)
	{

		$job_detail_rec=$this->db->query("SELECT jd.*,
											(select count(*) from job_process.candidate_jobs cj where jd.job_id= cj.job_id and jd.location_id=cj.location_id and cj.candidate_id=?) as applied_status
										  FROM job_process.vw_job_detail jd
										  WHERE job_id=?",array($candidate_id,$job_id));

		if($job_detail_rec->num_rows())
		{
			return $job_detail_rec->result_array();
		}
		else
			return false;
	}

   function do_get_employers_by_exec($bd_exec_id=0)
   {
   	 //$query = "SELECT * FROM users.employers where users.employers.employer=true";
		 $query = $this->db->where('employer', true)->get('users.employers');
   	 //$query_res = $this->db->query($query,array($bd_exec_id));
		 return $query->result_array();
   	 // if($query->num_rows())
   	 // {
   	 // 	return $query->result_array();
   	 // }
   	 // else{
   	 // 	return false;
   	 // }
   }

	function get_joined_candidates($customer_id=0)
	{
		$CustomerName = "";
		$customer_det_rec=$this->db->query("SELECT CUST.customer_name FROM neo_customer.customers AS CUST where CUST.id=?",$customer_id);
		if ($customer_det_rec->num_rows()) $CustomerName = $customer_det_rec->row()->customer_name;

		$candidate_det_rec=$this->db->query("SELECT		CJ.candidate_id,
														C.candidate_name,
														CJ.candidate_number,
														CJ.job_id,
														J.job_title,
														J.customer_id,
														COALESCE(QP.name,J.qualification_pack_name) AS qp_name,
														TO_CHAR(CP.date_of_join,'dd-Mon-yyyy') AS date_of_join,
														COALESCE(CP.employer_name,'NA') AS employer_name,
														COALESCE(CP.employer_contact_phone,'NA') AS employer_contact_phone,
														COALESCE(CP.employer_location,'NA') AS employer_location,
														COALESCE(CP.employment_type,'NA') AS employment_type,
														COALESCE(CP.ctc,'NA') AS ctc,
														CP.offer_letter_uploaded_on
											FROM		neo_job.candidates_jobs AS CJ
											LEFT JOIN	neo_job.jobs AS J ON J.id=CJ.job_id
											LEFT JOIN	neo.candidates AS C ON C.id=CJ.candidate_id
											LEFT JOIN	neo_job.candidate_placement AS CP ON CP.candidate_id=CJ.candidate_id AND CP.job_id=CJ.job_id
											LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
											WHERE 		J.customer_id=?
											AND			CJ.candidate_status_id=15
											AND			COALESCE(CJ.candidate_id,0)>0",$customer_id);
		if($customer_det_rec->num_rows())
		{
			$output['status']=true;
			$output['customer_name']=$CustomerName;
			if($candidate_det_rec->num_rows())
				$output['candidate_detail']=$candidate_det_rec->result_array();
			else
				$output['candidate_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}

	function get_customerwise_joined_candidate_data($requestData=array(),$customer_id=0)
	{
		$cond='';
		$order_by="";
		$data = array();

		$columns = array(
			0 => null,
			1 => null,
			2 => "candidate_name",
			3 => "candidate_number",
			4 => "job_title",
			5 => "qp_name",
			6 => "CP.date_of_join",
			7 => "employer_contact_phone",
			8 => "employer_location",
			9 => "employment_type",
			10 => "ctc",
			11 => "offer_letter_uploaded_on",
                        12 => "CP.resigned_date"
		);

		$column_search = array(
			"C.candidate_name",
			"C.candidate_number",
			"J.job_title",
			"COALESCE(QP.name,J.qualification_pack_name)",
			"TO_CHAR(CP.date_of_join,'dd-Mon-yyyy')",
			"COALESCE(CP.employer_contact_phone,'NA')",
			"COALESCE(CP.employer_location,'NA')",
			"COALESCE(CP.employment_type,'NA')",
			"COALESCE(CP.ctc,'NA')",
			"TO_CHAR(CP.offer_letter_uploaded_on,'dd-Mon-yyyy')",
                        "TO_CHAR(CP.resigned_date,'dd-Mon-yyyy')"
		);


		$cond=" WHERE J.customer_id=" . $customer_id . " AND CJ.candidate_status_id IN (15,17) AND COALESCE(CJ.candidate_id,0)>0 ";

		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
 										 FROM		neo_job.candidates_jobs AS CJ
										 LEFT JOIN	neo_job.jobs AS J ON J.id=CJ.job_id
										 LEFT JOIN	neo.candidates AS C ON C.id=CJ.candidate_id
										 LEFT JOIN	neo_job.candidate_placement AS CP ON CP.candidate_id=CJ.candidate_id AND CP.job_id=CJ.job_id
										 LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
                                         LEFT JOIN	neo_customer.customers AS CUST ON CUST.id=J.customer_id
                                         LEFT JOIN	neo_master.employment_type AS ET ON ET.id=CP.employment_type_id
										 $cond")->row()->total_recs;

		$totalData=$total_records*1;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		if($columns[$requestData['order'][0]['column']]!='')
			$order_by=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";

		$pg=$requestData['start'];
		$limit=$requestData['length'];
		if($limit<0) $limit='all';

		if(!$total_records)
			return array('sEcho'=>'1', "iTotalRecords"=>"0", "iTotalDisplayRecords"=>"0",'aaData'=>array());
		else
		{
			$sWhere = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
			{
				$sWhere = " AND (";
				for ($i = 0; $i < count($column_search); $i++)
					$sWhere .= $column_search[$i] . "::TEXT ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ')';

			}

			$sWhere=$cond . $sWhere;

			$totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM		neo_job.candidates_jobs AS CJ
												LEFT JOIN	neo_job.jobs AS J ON J.id=CJ.job_id
											    LEFT JOIN	neo.candidates AS C ON C.id=CJ.candidate_id
											    LEFT JOIN	neo_job.candidate_placement AS CP ON CP.candidate_id=CJ.candidate_id AND CP.job_id=CJ.job_id
											    LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
                                                LEFT JOIN	neo_customer.customers AS CUST ON CUST.id=J.customer_id
                                                LEFT JOIN	neo_master.employment_type AS ET ON ET.id=CP.employment_type_id
												$sWhere")->row()->total_filtered;

			$result_recs=$this->db->query("SELECT		CJ.candidate_id,
                                                                        C.candidate_name,
                                                                        CJ.candidate_status_id,
                                                                        CS.name AS candidate_status,
                                                                        C.candidate_number,
                                                                        CJ.job_id,
                                                                        J.job_title,
                                                                        J.customer_id,
                                                                         COALESCE(CUST.customer_name,'NA') AS customer_name,
                                                                        COALESCE((CASE COALESCE(QP.name,'') WHEN '' THEN '-NA-' ELSE FORMAT('%s (%s)',QP.name,QP.code) END),J.qualification_pack_name) AS qp_name,
                                                                        TO_CHAR(CP.date_of_join,'dd-Mon-yyyy') AS date_of_join,
                                                                        TO_CHAR(CP.offer_letter_date_of_join,'dd-Mon-yyyy') AS offer_letter_date_of_join,
                                                                        COALESCE(CP.offer_letter_file,'') AS offer_letter_file,
                                                                        COALESCE(CP.employer_name,'NA') AS employer_name,
                                                                        COALESCE(CP.employer_contact_phone,'NA') AS employer_contact_phone,
                                                                        COALESCE(CP.employer_location,'NA') AS employer_location,
                                                                        COALESCE(CP.placement_location,'NA') AS placement_location,
                                                                        COALESCE(ET.id,0) AS employment_type_id,
                                                                        COALESCE(ET.name,'') AS employment_type,
                                                                        COALESCE(CP.reason_to_leave,'') AS reason_to_leave,
                                                                        COALESCE(CP.ctc,'NA') AS ctc,
                                                                        TO_CHAR(CP.offer_letter_uploaded_on,'dd-Mon-yyyy') AS offer_letter_uploaded_on,
                                                                        TO_CHAR(CP.resigned_date,'dd-Mon-yyyy') AS resigned_date
                                                                        FROM		neo_job.candidates_jobs AS CJ
                                                                        LEFT JOIN	neo_job.jobs AS J ON J.id=CJ.job_id
                                                                        LEFT JOIN	neo.candidates AS C ON C.id=CJ.candidate_id
                                                                        LEFT JOIN	neo_job.candidate_placement AS CP ON CP.candidate_id=CJ.candidate_id AND CP.job_id=CJ.job_id
                                                                        LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
                                                                        LEFT JOIN	neo_customer.customers AS CUST ON CUST.id=J.customer_id
                                                                        LEFT JOIN	neo_master.employment_type AS ET ON ET.id=CP.employment_type_id
						        		LEFT JOIN	neo_master.candidate_statuses AS CS ON CS.id=CJ.candidate_status_id
                                                                        $sWhere
											$order_by
											limit $limit
											OFFSET $pg");
                        $CandidateStatusColors = array (
                        15 => '#5cb85c',
                        17 => '#da4453'
                        );
			$slno=$pg;
			$data = array();
			foreach ($result_recs->result() as $candidate_status)
			{  // preparing an array
				$row=array();
				$slno++;
                                 $ActionColumn='';
                                if($candidate_status->candidate_status_id !='17'|| $candidate_status->candidate_status_id !=17) 
				$ActionColumn .= '<a class="btn btn-success btn-sm" href="javascript:void(0)" title="View/Edit Details" style="margin-right: 5px;" onclick="EditDetails(' . $candidate_status->candidate_id .  ',' . $candidate_status->job_id . ',\''. $candidate_status->candidate_name . '\',\''. $candidate_status->customer_name . '\',\''. $candidate_status->job_title  . '\',\''.  $candidate_status->employment_type_id  . '\',\''.  $candidate_status->employment_type . '\',\''. $candidate_status->employer_name . '\',\''. $candidate_status->employer_contact_phone . '\',\''. $candidate_status->employer_location .'\',\''. $candidate_status->placement_location .'\',\''. $candidate_status->ctc .'\',\''. $candidate_status->date_of_join .'\',\''. $candidate_status->offer_letter_date_of_join . '\',\''. $candidate_status->offer_letter_file . '\')"><i class="fa fa-pencil"></i></a>';                                
				if (trim($candidate_status->offer_letter_file)!='')
					$ActionColumn .= '<a class="btn btn-primary btn-sm" href="'. base_url(). OFFER_LETTER_PATH .$candidate_status->offer_letter_file.'" title="Download Offer Letter"><i class="fa fa-download"></i></a>';
                                        $ActionColumn .= '<a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Resignation/Termination Detail" onclick="ResignDetails(' . $candidate_status->candidate_id .  ',' . $candidate_status->job_id . ',\''. $candidate_status->candidate_name . '\',\''. $candidate_status->customer_name . '\',\''. $candidate_status->job_title  . '\',\''. $candidate_status->date_of_join .'\',\''.  $candidate_status->resigned_date . '\',\''.  $candidate_status->reason_to_leave . '\')" style="margin-left: 5px;"><i class="fa fa-sign-out"></i></a>';
				$row[] = $slno;
				$row[] = $ActionColumn;
                                $row[] = '<center><span style="border-radius:4px;color:white;padding:5px;background-color:'. $CandidateStatusColors[$candidate_status->candidate_status_id] .'">' . $candidate_status->candidate_status . '</span></center>';
				$row[] = $candidate_status->candidate_name;
				$row[] = $candidate_status->candidate_number ?? 'N/A';
				$row[] = $candidate_status->job_title;
				$row[] = $candidate_status->qp_name;
				$row[] = $candidate_status->date_of_join;
				$row[] = $candidate_status->employer_contact_phone;
				$row[] = $candidate_status->employer_location;
				$row[] = $candidate_status->employment_type;
				$row[] = $candidate_status->ctc;
				$row[] = $candidate_status->offer_letter_uploaded_on ?? 'N/A';
				$row[] = $candidate_status->placement_location;
                                $row[] = $candidate_status->resigned_date ?? 'N/A';
				$data[] = $row;
			}
			$response_data = array(
				"draw"            => intval( $requestData['draw'] ),
				"recordsTotal"    => intval( $totalData ),
				"recordsFiltered" => intval( $totalFiltered ),
				"data"            => $data
			);
			return $response_data;
		}
	}


	function get_jobwise_joined_candidate_data($requestData=array(),$job_id=0)
	{
		$cond='';
		$order_by="";
		$data = array();

		$columns = array(
			0 => null,
			1 => null,
                        2 => "candidate_status",
			3 => "candidate_name",
			4 => "candidate_number",
			5 => "job_title",
			6 => "qp_name",
			7 => "CP.date_of_join",
			8 => "employer_contact_phone",
			9 => "employer_location",
			10 => "employment_type",
			11 => "ctc",
			12 => "offer_letter_uploaded_on",
                        13 => "CP.resigned_date"
		);

		$column_search = array(
			"C.candidate_name",
			"CJ.candidate_number",
			"J.job_title",
			"COALESCE(QP.name,J.qualification_pack_name)",
			"TO_CHAR(CP.date_of_join,'dd-Mon-yyyy')",
			"COALESCE(CP.employer_contact_phone,'NA')",
			"COALESCE(CP.employer_location,'NA')",
			"COALESCE(CP.employment_type,'NA')",
			"COALESCE(CP.ctc,'NA')",
			"TO_CHAR(CP.offer_letter_uploaded_on,'dd-Mon-yyyy')",
                        "TO_CHAR(CP.resigned_date,'dd-Mon-yyyy')"
		);


		$cond=" WHERE CJ.job_id=" . $job_id . " AND CJ.candidate_status_id IN (15,17) AND COALESCE(CJ.candidate_id,0)>0 ";

		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
 										 FROM		neo_job.candidates_jobs AS CJ
										 LEFT JOIN	neo_job.jobs AS J ON J.id=CJ.job_id
										 LEFT JOIN	neo.candidates AS C ON C.id=CJ.candidate_id
										 LEFT JOIN	neo_job.candidate_placement AS CP ON CP.candidate_id=CJ.candidate_id AND CP.job_id=CJ.job_id
										 LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
                                         LEFT JOIN	neo_customer.customers AS CUST ON CUST.id=J.customer_id
                                         LEFT JOIN	neo_master.employment_type AS ET ON ET.id=CP.employment_type_id
										 $cond")->row()->total_recs;

		$totalData=$total_records*1;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		if($columns[$requestData['order'][0]['column']]!='')
			$order_by=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";

		$pg=$requestData['start'];
		$limit=$requestData['length'];
		if($limit<0) $limit='all';

		if(!$total_records)
			return array('sEcho'=>'1', "iTotalRecords"=>"0", "iTotalDisplayRecords"=>"0",'aaData'=>array());
		else
		{
			$sWhere = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
			{
				$sWhere = " AND (";
				for ($i = 0; $i < count($column_search); $i++)
					$sWhere .= $column_search[$i] . "::TEXT ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ')';

			}

			$sWhere=$cond . $sWhere;

			$totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM		neo_job.candidates_jobs AS CJ
												LEFT JOIN	neo_job.jobs AS J ON J.id=CJ.job_id
											    LEFT JOIN	neo.candidates AS C ON C.id=CJ.candidate_id
											    LEFT JOIN	neo_job.candidate_placement AS CP ON CP.candidate_id=CJ.candidate_id AND CP.job_id=CJ.job_id
											    LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
                                                LEFT JOIN	neo_customer.customers AS CUST ON CUST.id=J.customer_id
                                                LEFT JOIN	neo_master.employment_type AS ET ON ET.id=CP.employment_type_id
												$sWhere")->row()->total_filtered;

			$result_recs=$this->db->query("SELECT		CJ.candidate_id,
														C.candidate_name,
                                                                                                                CJ.candidate_status_id,
                                                                                                                CS.name AS candidate_status,
														C.candidate_number,
														CJ.job_id,
														J.job_title,
														J.customer_id,
                                                                                                                COALESCE(CUST.customer_name,'NA') AS customer_name,
														COALESCE(QP.name,J.qualification_pack_name) AS qp_name,
														TO_CHAR(CP.date_of_join,'dd-Mon-yyyy') AS date_of_join,
														TO_CHAR(CP.offer_letter_date_of_join,'dd-Mon-yyyy') AS offer_letter_date_of_join,
														COALESCE(CP.offer_letter_file,'') AS offer_letter_file,
														COALESCE(CP.employer_name,'NA') AS employer_name,
														COALESCE(CP.employer_contact_phone,'NA') AS employer_contact_phone,
														COALESCE(CP.employer_location,'NA') AS employer_location,
														COALESCE(CP.placement_location,'NA') AS placement_location,
														COALESCE(ET.id,0) AS employment_type_id,
														COALESCE(ET.name,'NA') AS employment_type,
														COALESCE(CP.reason_to_leave,'') AS reason_to_leave,
                                                                                                                COALESCE(CP.ctc,'NA') AS ctc,
														TO_CHAR(CP.offer_letter_uploaded_on,'dd-Mon-yyyy') AS offer_letter_uploaded_on,
                                                                                                                TO_CHAR(CP.resigned_date,'dd-Mon-yyyy') AS resigned_date
											FROM		neo_job.candidates_jobs AS CJ
											LEFT JOIN	neo_job.jobs AS J ON J.id=CJ.job_id
											LEFT JOIN	neo.candidates AS C ON C.id=CJ.candidate_id
											LEFT JOIN	neo_job.candidate_placement AS CP ON CP.candidate_id=CJ.candidate_id AND CP.job_id=CJ.job_id
											LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
                                            LEFT JOIN	neo_customer.customers AS CUST ON CUST.id=J.customer_id
                                            LEFT JOIN	neo_master.employment_type AS ET ON ET.id=CP.employment_type_id
                                            LEFT JOIN	neo_master.candidate_statuses AS CS ON CS.id=CJ.candidate_status_id
						        			$sWhere
											$order_by
											limit $limit
											OFFSET $pg");


                        $CandidateStatusColors = array (
                        15 => '#5cb85c',
                        17 => '#da4453'
                    );
			$slno=$pg;
			$data = array();
			foreach ($result_recs->result() as $candidate_status)
			{  // preparing an array
				$row=array();
				$slno++;
                                $ActionColumn='';
                                if($candidate_status->candidate_status_id !='17'|| $candidate_status->candidate_status_id !=17) 
				$ActionColumn .= '<a class="btn btn-success btn-sm" href="javascript:void(0)" title="View/Edit Details" style="margin-right: 5px;" onclick="EditDetails(' . $candidate_status->candidate_id .  ',' . $candidate_status->job_id . ',\''. $candidate_status->candidate_name . '\',\''. $candidate_status->customer_name . '\',\''. $candidate_status->job_title  . '\',\''.  $candidate_status->employment_type_id  . '\',\''.  $candidate_status->employment_type . '\',\''. $candidate_status->employer_name . '\',\''. $candidate_status->employer_contact_phone . '\',\''. $candidate_status->employer_location .'\',\''. $candidate_status->placement_location .'\',\''. $candidate_status->ctc .'\',\''. $candidate_status->date_of_join .'\',\''. $candidate_status->offer_letter_date_of_join . '\',\''. $candidate_status->offer_letter_file . '\')"><i class="fa fa-pencil"></i></a>';                                
				if (trim($candidate_status->offer_letter_file)!='')
					$ActionColumn .= '<a class="btn btn-primary btn-sm" href="'. base_url(). OFFER_LETTER_PATH .$candidate_status->offer_letter_file.'" title="Download Offer Letter" ><i class="fa fa-download"></i></a>';
                                        $ActionColumn .= '<a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Resignation/Termination Detail" onclick="ResignDetails(' . $candidate_status->candidate_id .  ',' . $candidate_status->job_id . ',\''. $candidate_status->candidate_name . '\',\''. $candidate_status->customer_name . '\',\''. $candidate_status->job_title  . '\',\''. $candidate_status->date_of_join .'\',\''.  $candidate_status->resigned_date . '\',\''.  $candidate_status->reason_to_leave . '\')" style="margin-left: 5px;"><i class="fa fa-sign-out"></i></a>';
				$row[] = $slno;
				$row[] = $ActionColumn;
                                $row[] = '<center><span style="border-radius:4px;color:white;padding:5px;background-color:'. $CandidateStatusColors[$candidate_status->candidate_status_id] .'">' . $candidate_status->candidate_status . '</span></center>';
//                                $row[] = $candidate_status->candidate_status;
				$row[] = $candidate_status->candidate_name;
				$row[] = $candidate_status->candidate_number ?? 'N/A';
				$row[] = $candidate_status->job_title;
				$row[] = $candidate_status->qp_name;
				$row[] = $candidate_status->date_of_join;
				$row[] = $candidate_status->employer_contact_phone;
				$row[] = $candidate_status->employer_location;
				$row[] = $candidate_status->employment_type;
				$row[] = $candidate_status->ctc;
				$row[] = $candidate_status->offer_letter_uploaded_on ?? 'N/A';
				$row[] = $candidate_status->placement_location;                               
                                $row[] = $candidate_status->resigned_date ?? 'N/A';
				$data[] = $row;
			}                       

			$response_data = array(
				"draw"            => intval( $requestData['draw'] ),
				"recordsTotal"    => intval( $totalData ),
				"recordsFiltered" => intval( $totalFiltered ),
				"data"            => $data
			);
			return $response_data;
		}
	}

	function get_customer_data($requestData=array())
	{
		$cond = '';
		$order_by = "ORDER by created_at DESC";
                $search_type_id = isset($requestData['search_type_id']) ? intval($requestData['search_type_id']) : 0;
                $search_value = isset($requestData['search_value']) ? $requestData['search_value'] : '';
		$data = array();
                $hierachy_ids = implode(',', $this->session->userdata('user_hierarchy'));
                $assigned_ids=$this->db->select('lead_id')->where('user_id', $this->session->userdata('usr_authdet')['id'])->get('neo_customer.leads_users')->result_array();
                $assigned_ids_string = implode(',', array_column($assigned_ids, 'lead_id'));
               // $hierachy_ids=$hierachy_ids.$assigned_ids;
		$columns = array(
			0 => null,
			1 => null,
			2 => "R.customer_name",
			3 => "R.lead_type_name",
			4 => "R.source_name",
                        5 => "R.spoc_name",
                        6 => "R.spoc_email",
                        7 => "R.spoc_phone",
                        8 => "R.state",
                        9 => "R.district",
                        10 => "R.buisness_vertical_name",
                        11 => "R.industry_name",
                        12 => "R.functional_area_name"
		);

		$column_search = array(
                        1 =>"R.customer_name",
                        2 =>"R.lead_type_id",
                        3 =>"R.lead_source_id",
                        4 =>"R.spoc_name",
                        5 =>"R.spoc_email",
                        6 =>"R.spoc_phone",
                        7 =>"R.state_id",
                        8 =>"R.business_vertical_id",
                        9 =>"R.industry_id",
                        10 =>"R.functional_area_id"
               );
                   // AND created_by IN ({$hierachy_ids})
		$HierarchyCondition = " AND (R.created_by IN ($hierachy_ids) OR R.assigned_user_ids && ARRAY[$hierachy_ids]) ";

                $TotalRecQuery =    "WITH R AS
                                    (
                                        SELECT 	C.id,
                                                C.created_by,
                                                CD.file_name,
                                                lt.name AS lead_type_name,
                                                ls.name AS source_name,
                                                COALESCE((SELECT ARRAY_AGG(user_id) FROM neo_customer.leads_users WHERE lead_id=C.id),ARRAY[]::INT[]) AS assigned_user_ids
                                        FROM 	neo_customer.customers AS C
                                        LEFT JOIN neo_customer.customer_documents AS CD ON CD.customer_id = c.id
                                        LEFT JOIN neo_master.lead_type AS LT ON lt.id = c.lead_type_id
                                        LEFT JOIN neo_master.lead_sources AS LS ON ls.id=c.lead_source_id
                                        WHERE   C.is_customer
                                    )
                                    SELECT 	COUNT(R.id) AS total_recs
                                    FROM 	R
                                    WHERE	TRUE
                                    $HierarchyCondition";

		$total_records = $this->db->query($TotalRecQuery)->row()->total_recs;

		//$total_records = $this->db->query("SELECT COUNT(C.id)::bigint AS total_recs FROM neo_customer.customers AS C LEFT JOIN neo_customer.customer_documents AS CD ON CD.customer_id = C.id ")->row()->total_recs;

		$totalData = $total_records * 1;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		if ($columns[$requestData['order'][0]['column']] != '')
			$order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";

		$pg = $requestData['start'];
		$limit = $requestData['length'];
		if ($limit < 0) $limit = 'all';

		if (!$total_records)
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		else
                {
                    $FilterCondition = "";      
                        if ($search_type_id > 0)
                        {      
                            switch($search_type_id)
                            {
                                case 2:
                                case 3:                               
                                case 7:
                                case 8:
                                case 9:
                                case 10:
                                    if ($search_value != '0')
                                    {
                                        $FilterCondition = $column_search[$search_type_id] . "=" . $search_value;
                                    }
                                    break;

                                default:
                                    if (trim($search_value) != '')
                                    {
                                        $FilterCondition = $column_search[$search_type_id] . " ~* '" . $search_value . "'";
                                    }
                            }

                            if (trim($FilterCondition) != "")
                            {
                                $FilterCondition = " AND ($FilterCondition) ";
                            }
                        }

                    $totalFiltered = $this->db->query("WITH R AS
                                                        (
                                                            SELECT  C.id,
                                                                    C.customer_name,
                                                                    C.created_by,
                                                                    c.created_at,
                                                                    c.lead_source_id,
                                                                     B.spoc_name
                                                                    || (CASE WHEN COALESCE(TRIM(C.hr_name),'')<>'' THEN ','||TRIM(C.hr_name) ELSE '' END) AS spoc_name,                                        
                                                                    B.spoc_email
                                                                    || (CASE WHEN COALESCE(TRIM(C.hr_email),'')<>'' THEN ','||TRIM(C.hr_email) ELSE '' END) AS spoc_email,                                       
                                                                    B.spoc_phone
                                                                    || (CASE WHEN COALESCE(TRIM(C.hr_phone),'')<>'' THEN ','||TRIM(C.hr_phone) ELSE '' END) AS spoc_phone,
                                                                    d.name AS location,
                                                                    C.business_vertical_id,
                                                                    c.industry_id,
                                                                    c.functional_area_id,
                                                                    c.lead_type_id,
                                                                    CB.state_id,
                                                                    d.name AS district,
                                                            COALESCE((SELECT ARRAY_AGG(user_id) FROM neo_customer.leads_users WHERE lead_id=C.id),ARRAY[]::INT[]) AS assigned_user_ids
                                                            FROM    neo_customer.customers AS C
                                                            LEFT JOIN   neo_customer.customer_documents AS CD ON CD.customer_id = c.id
                                                            LEFT JOIN   neo_master.lead_type AS LT ON lt.id = c.lead_type_id
                                                            LEFT JOIN   neo_master.lead_sources AS LS ON ls.id=c.lead_source_id
                                                            LEFT JOIN   neo_customer.customer_branches AS CB ON CB.id=c.id
                                                            LEFT JOIN   neo_master.districts AS d ON d.id=CB.district_id
                                                            LEFT JOIN   neo_master.business_verticals AS bv ON bv.id=C.business_vertical_id
                                                            LEFT JOIN   neo_master.industries AS i on i.id=c.industry_id
                                                            LEFT JOIN   neo_master.functional_areas AS fa ON fa.id=c.functional_area_id
                                                            LEFT JOIN   neo_master.states AS s ON s.id=CB.state_id
                                                            LEFT JOIN
                                                            (
                                                            SELECT 	CB.customer_id,
                                                                        STRING_AGG(t->>'spoc_name',',') AS spoc_name,
                                                                        STRING_AGG(t->>'spoc_email',',') AS spoc_email,
                                                                        STRING_AGG(t->>'spoc_phone',',') AS spoc_phone
                                                            FROM 	neo_customer.customer_branches AS CB
                                                            CROSS JOIN LATERAL json_array_elements(CB.spoc_detail::json) AS x(t)
                                                            GROUP BY CB.customer_id
                                                            ) AS B ON 	B.customer_id=C.id
                                                            WHERE   C.is_customer
                                                        )
                                                        SELECT COUNT(R.id)::bigint AS total_filtered
                                                        FROM R
                                                        WHERE TRUE                                                        
                                                        $FilterCondition
                                                        $HierarchyCondition")->row()->total_filtered;

                    $result_recs = $this->db->query("WITH R AS
                                                        (
                                                              SELECT C.id,
                                                                C.customer_name,
                                                                C.is_paid,
                                                                C.created_by,
                                                                c.created_at,
                                                                CD.file_name,  
                                                                B.spoc_name
                                                                || (CASE WHEN COALESCE(TRIM(C.hr_name),'')<>'' THEN ','||TRIM(C.hr_name) ELSE '' END) AS spoc_name,                                        
                                                                B.spoc_email
                                                                || (CASE WHEN COALESCE(TRIM(C.hr_email),'')<>'' THEN ','||TRIM(C.hr_email) ELSE '' END) AS spoc_email,                                       
                                                                B.spoc_phone
                                                                || (CASE WHEN COALESCE(TRIM(C.hr_phone),'')<>'' THEN ','||TRIM(C.hr_phone) ELSE '' END) AS spoc_phone,
                                                                d.name AS location,
                                                                C.business_vertical_id,
                                                                bv.name AS buisness_vertical_name,
                                                                c.industry_id,
                                                                i.name AS industry_name,
                                                                c.functional_area_id,
                                                                fa.name AS functional_area_name,
                                                                c.lead_type_id,
                                                                lt.name AS lead_type_name,
                                                                c.lead_source_id,
                                                                ls.name AS source_name,
                                                                CB.state_id,
                                                                s.name AS location,
                                                                CB.district_id,
                                                                d.name AS district,
                                                                COALESCE((SELECT ARRAY_AGG(user_id) FROM neo_customer.leads_users WHERE lead_id=C.id),ARRAY[]::INT[]) AS assigned_user_ids
                                                                FROM    neo_customer.customers AS C
                                                                LEFT JOIN neo_customer.customer_documents AS CD ON CD.customer_id = c.id
                                                                LEFT JOIN neo_master.lead_type AS LT ON lt.id = c.lead_type_id
                                                                LEFT JOIN neo_master.lead_sources AS LS ON ls.id=c.lead_source_id
                                                                LEFT JOIN   neo_customer.customer_branches AS CB ON CB.id=c.id
                                                        LEFT JOIN   neo_master.districts AS d ON d.id=CB.district_id
                                                        LEFT JOIN   neo_master.business_verticals AS bv ON bv.id=C.business_vertical_id
                                                        LEFT JOIN neo_master.industries AS i on i.id=c.industry_id
                                                        LEFT JOIN neo_master.functional_areas AS fa ON fa.id=c.functional_area_id
                                                        LEFT JOIN   neo_master.states AS s ON s.id=CB.state_id                                                       
                                                        LEFT JOIN
                                                        (
                                                        SELECT 	CB.customer_id,
                                                                    STRING_AGG(t->>'spoc_name',',') AS spoc_name,
                                                                    STRING_AGG(t->>'spoc_email',',') AS spoc_email,
                                                                    STRING_AGG(t->>'spoc_phone',',') AS spoc_phone
                                                        FROM 	neo_customer.customer_branches AS CB
                                                        CROSS JOIN LATERAL json_array_elements(CB.spoc_detail::json) AS x(t)
                                                        GROUP BY CB.customer_id
                                                        ) AS B ON 	B.customer_id=C.id
                                                                WHERE   C.is_customer
                                                        )
                                                        SELECT R.*
                                                    FROM R
                                                    WHERE TRUE
                                                    $FilterCondition
                                                    $HierarchyCondition
                                                    $order_by
                                                    LIMIT $limit
                                                    OFFSET $pg");

                    $slno = $pg;
                    $data = array();
                    foreach ($result_recs->result() as $customer) {
                            $row = array();
                            $slno++;
							 $ActionColumn = '<a class="btn btn-success btn-sm" href="javascript:void(0)" title="View Joined Candidates" onclick="ViewJoinedCandidates(' . $customer->id . ')"><i class="fa fa-eye"></i></a>';
							 if (in_array($this->session->userdata('usr_authdet')['user_group_id'], customer_spoc_view_roles()))
							{
							 $ActionColumn .= '<a class="btn btn-danger btn-sm" href="javascript:void(0)" title="View Spocs" onclick="showAdditionalSpocs(' . $customer->id . ')" style="margin-left:5px;"><i class="fa fa-phone" ></i></a>';		
							}
							 if (trim($customer->file_name)!='')
							{
								$ActionColumn .= '<a class="btn btn-warning btn-sm" href="'. base_url(). CUSTOMER_DOCUMENT_PATH .$customer->file_name.'" target="_blank" title="Download Commercial Document" style="margin-left:5px;"><i class="fa fa-download" ></i></a>';
							}
									
							if (in_array($this->session->userdata('usr_authdet')['user_group_id'], customer_commercial_view_roles()))
							{
								if($customer->is_paid)
									{
									  $ActionColumn .= "<button class='btn btn-warning btn-sm' onclick='showCommercialModal({$customer->id})' title='View Commercials' style='margin-left:5px;'><i class='fa fa-rupee' ></i></button>";
									}
							}
									


                            $row[] = $slno;
                            $row[] = $ActionColumn;
                            $row[] = $customer->customer_name ?? 'N/A';
                            $row[] = $customer->lead_type_name ?? 'N/A';
                            $row[] = $customer->source_name ?? 'N/A';                            
                            $row[] = $customer->spoc_name ?? 'N/A';
                            $row[] = $customer->spoc_email ?? 'N/A';
                            $row[] = $customer->spoc_phone ?? 'N/A';
                            $row[] = $customer->location == '' ? 'N/A' : $customer->location;
                            $row[] = $customer->district == '' ? 'N/A' : $customer->district;
                            $row[] = $customer->buisness_vertical_name ?? 'N/A';
                            $row[] = $customer->industry_name ?? 'N/A';
                            $row[] = $customer->functional_area_name ?? 'N/A';
                            $data[] = $row;
                    }
                    $response_data = array(
                            "draw" => intval($requestData['draw']),
                            "recordsTotal" => intval($totalData),
                            "recordsFiltered" => intval($totalFiltered),
                            "data" => $data
                    );
                    return $response_data;
		}
	}



        function get_center_data($requestData=array())
	{
		$cond = '';
		$order_by = " ORDER BY created_at DESC ";
		$data = array();


		$columns = array(
			0 => null,
			1 => null,
			2 => "c.center_name",
			3 => "c.center_type",
                        3 => "c.status",
                        3 => "r.name",
			4 => "c.address",
			5 => "c.city",
			6 => "c.pincode"
		);

		$column_search = array(
			"c.center_name",
			"c.center_type",
                        "c.status",
                        "r.name",
			"c.address",
			"c.city",
			"c.pincode"
		);

		$cond = "WHERE TRUE";

		$total_records = $this->db->query("SELECT COUNT(c.id)::bigint AS total_recs FROM neo_user.centers AS c ")->row()->total_recs;

		$totalData = $total_records * 1;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		if ($columns[$requestData['order'][0]['column']] != '')
			$order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";

		$pg = $requestData['start'];
		$limit = $requestData['length'];
		if ($limit < 0) $limit = 'all';

		if (!$total_records)
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		else {
			$sWhere = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '') {
				$sWhere = " AND (";
				for ($i = 0; $i < count($column_search); $i++)
					$sWhere .= $column_search[$i] . "::TEXT ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ')';
			}

			$sWhere = $cond . $sWhere;

			$totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM neo_user.centers AS c
                                                                                        LEFT JOIN neo_master.region AS r ON r.id = c.region_id
							        			$sWhere")->row()->total_filtered;

			$result_recs = $this->db->query("SELECT C.*,c.id,r.name
                                                                FROM	neo_user.centers AS c
                                                                LEFT JOIN neo_master.region AS r ON r.id = c.region_id
                                                                $sWhere
                                                                $order_by
                                                                LIMIT $limit
                                                                OFFSET $pg");

			$slno = $pg;
			$data = array();

                        $CurrentUserRoleId = $this->session->userdata('usr_authdet')['user_group_id'];

                        $AllowCenterActivateStatus = (in_array($CurrentUserRoleId, center_active_deactive_roles())) ? true : false;
			foreach ($result_recs->result() as $center)
                        {
                            $row = array();
                            $slno++;
                            $row[] = $slno;
                            $row[] = '<a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Edit Center" onclick="center_edit(' . $center->id . ')"><i class="fa fa-pencil"></i></a>';

                            if ($AllowCenterActivateStatus)
                            {
                                $row[] = '<a class="' . ($center->status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="center_status(' .  $center->id .  ','   .$center->status . ')" style="width:80%; color:white;">' . ($center->status ? "Active" : "Inactive") . '</a>';
                            } else {
                                $row[] = ($center->status ==1)? 'Active' : 'InActive'  ?? 'N/A';
                            }
                            $row[] = $center->center_name ?? 'N/A';
                            $row[] = $center->center_type ?? 'N/A';
                            //$row[] = ($center->status ==1)? 'Active' : 'InActive'  ?? 'N/A';
                            $row[] = $center->name ?? 'N/A';
                            $row[] = $center->address ?? 'N/A';
                            $row[] = $center->city ?? 'N/A';
                            $row[] = $center->pincode ?? 'N/A';
                            //$row[] = $customer->location ?? 'N/A';
                            //$row[] = $customer->source ?? 'N/A';
                            $data[] = $row;
			}

			$response_data = array(
				"draw" => intval($requestData['draw']),
				"recordsTotal" => intval($totalData),
				"recordsFiltered" => intval($totalFiltered),
				"data" => $data
			);

			return $response_data;
		}
	}

        function get_user_data($requestData=array())
	{
		$cond = '';
		$order_by = "ORDER BY u.created_at DESC";
		$data = array();


		$columns = array(
			0 => null,
			1 => null,
                        2 => null,
			3 => "u.name",
			4 => "u.email",
                        5 => "ur.name",
                        6 => "UB.name",
                        7 => "u.employee_id"
		);

		$column_search = array(
			"u.name",
			"u.email",
                        "ur.name",
                        "UB.name",
                        "u.employee_id"
		);

		$cond = " ";
                $user_ids_exclude = implode(', ', range(0,1));
                $where_not_in = ' AND u.id NOT IN ('.$user_ids_exclude.') ';
		$total_records = $this->db->query("SELECT COUNT(u.id)::bigint AS total_recs FROM neo_user.users AS u WHERE TRUE ".$where_not_in)->row()->total_recs;

		$totalData = $total_records * 1;
		$totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

		if ($columns[$requestData['order'][0]['column']] != '')
			$order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";

		$pg = $requestData['start'];
		$limit = $requestData['length'];
		if ($limit < 0) $limit = 'all';

		if (!$total_records)
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		else {
			$sWhere = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '') {
				$sWhere = " AND (";
				for ($i = 0; $i < count($column_search); $i++)
					$sWhere .= $column_search[$i] . "::TEXT ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				$sWhere = substr_replace($sWhere, "", -3);
				$sWhere .= ')';
			}

			$sWhere = $cond . $sWhere;

			$totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
                                                            FROM neo_user.users AS u
                                                            LEFT JOIN   neo_user.user_roles AS UR ON ur.id = u.user_role_id
                                                            LEFT JOIN   neo_user.users AS UB ON UB.id = u.reporting_manager_id
                                                            WHERE TRUE
                                                            $sWhere $where_not_in")->row()->total_filtered;

			$result_recs = $this->db->query("SELECT     U.*,
                                                                    ur.name AS user_role_name,
                                                                    UB.name as reporting_manager_name
                                                        FROM        neo_user.users AS u
                                                        LEFT JOIN   neo_user.user_roles AS UR ON ur.id = u.user_role_id
                                                        LEFT JOIN   neo_user.users AS UB ON UB.id = u.reporting_manager_id
                                                        WHERE       TRUE
                                                        $sWhere
                                                        $where_not_in
                                                        $order_by
                                                        LIMIT $limit
                                                        OFFSET $pg");

			$slno = $pg;
			$data = array();
			foreach ($result_recs->result() as $user) {
				$row = array();
				$slno++;
				$row[] = $slno;
				$row[] = '<a class="btn btn-danger btn-sm" href="javascript:void(0)" title="Edit User" onclick="user_edit(' . $user->id . ')"><i class="fa fa-pencil"></i></a>';
                                 $row[] = '<a class="' . ($user->is_active ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="user_status(' .  $user->id .  ','   .$user->is_active . ')" style="width:80%; color:white;">' . ($user->is_active ? "Active" : "Inactive") . '</a>';
				$row[] = $user->name ?? 'N/A';
				$row[] = $user->email ?? 'N/A';
                                $row[] = $user->user_role_name ?? 'N/A';
                                $row[] = $user->reporting_manager_name ?? 'N/A';
                                $row[] = $user->employee_id ?? 'N/A';
				$data[] = $row;
			}

			$response_data = array(
				"draw" => intval($requestData['draw']),
				"recordsTotal" => intval($totalData),
				"recordsFiltered" => intval($totalFiltered),
				"data" => $data
			);

			return $response_data;
		}
	}



}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model :: Partner Model
 * @author Sangamesh.p@pramaan.in
**/
class Partner_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	/*
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to center data list
	*/
	function get_center_datatables($requestData=array(),$partner_id=0)
	{
		$order_by="";
		$cond='';
		$data = array();
		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'c.name',
		    2 => 'c.address',
		    3 => 'u.email',
		    4 => 'c.phone',
		    5 => 'p.partner',
		    6 => null
		);
		if($partner_id)
			$cond=" where c.partner_id=".$partner_id;
		$column_search = array("c.name","c.phone"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.centers as c
											left JOIN users.partners as p ON p.user_id =c.partner_id
											left JOIN users.accounts u ON u.id=c.partner_id
											$cond")->row()->total_recs;

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
	            if($partner_id)
	            	$sWhere .=" and c.partner_id=".$partner_id;
	        }
	    	else
	    		$sWhere =$cond;
	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM users.centers as c
												left JOIN users.partners as p ON p.user_id =c.partner_id
												left JOIN users.accounts u ON u.id=c.partner_id
							        			$sWhere")->row()->total_filtered;

			$center_recs=$this->db->query("SELECT c.id,c.name,c.address,c.phone, p.name as partner_name,u.email
											FROM users.centers as c
											left JOIN users.partners as p ON p.user_id =c.partner_id
											left JOIN users.accounts u ON u.id=c.partner_id
											$sWhere
											$order_by limit $limit OFFSET $pg");
			$slno=$pg;
			$data = array();
			foreach ($center_recs->result() as $center)
			{  // preparing an array
				$row=array();
				$slno++;
				$row[] = $slno;
				$row[] = $center->name;
				$row[] = $center->address;
				$row[] = $center->email;
				$row[] = $center->phone;
				$row[] = $center->partner_name;
				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit Center" onclick="edit_center('."'".$center->id."'".')"><i class="icon-edit"></i></a>
					  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete Center" onclick="delete_center('."'".$center->id."'".')"><i class="icon-android-delete"></i></a>';
				$data[] = $row;
			}


			//  $data[] = $employee_recs->result_array();
			$center_data_recs = array(
		            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		            "recordsTotal"    => intval( $totalData ),  // total number of records
		            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		            "data"            => $data   // total data array
		            );
			return $center_data_recs;
		}

	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get center by id
	*/
	function get_center_by_id($id)
	{
		$this->db->from('users.centers');
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to add center
	*/
	function do_add_center($data)
	{
		$this->db->insert('users.centers', $data);
	  	if($this->db->affected_rows())
	  		return $this->db->insert_id();
	  	else
	  		return false;
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to update center
	*/
	function do_update_center($where, $data)
	{
		$this->db->update('users.centers', $data, $where);
		if($this->db->affected_rows())
	  		return $this->db->affected_rows();
	  	else
	  		return false;
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to delete center
	*/
	function do_delete_center($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('users.centers');
		if($this->db->affected_rows())
	  		return $this->db->affected_rows();
	  	else
	  		return false;

	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to delete associate data list
	*/
	function get_associates_datatables($requestData=array(),$partner_id)
	{

		$order_by="";
		$cond='';
		if($partner_id)
		{
			$cond=" where a.partner_id=".$partner_id;
		}
		$data = array();
		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'a.name',
		    2 => 'a.address',
		    3 => 'u.email',
		    4 => 'a.phone',
		    5 => 'p.name',
		    6 => 'c.name',
		    7 => null
		);
		$column_search = array("a.name","a.phone","p.name","u.email","c.name","a.address"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.associates AS a
											left JOIN users.partners as p ON p.user_id = a.partner_id
											left JOIN users.centers as c ON c.id = a.center_id
											left JOIN users.accounts u ON u.id=a.user_id
											$cond"
										)->row()->total_recs;

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
	            if($partner_id)
	            	$sWhere.=' and '. "a.partner_id=".$partner_id;

	        }
	        else
	        	$sWhere=$cond;


	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.associates AS a
													left JOIN users.partners as p ON p.user_id = a.partner_id
													left JOIN users.centers as c ON c.id = a.center_id
													left JOIN users.accounts u ON u.id=a.user_id
								        			$sWhere")->row()->total_filtered;

			$associates_recs=$this->db->query("SELECT a.user_id as id,a.name,a.address,a.phone, p.name as partner_name,c.name as center_name,u.email
											FROM users.associates AS a
											left JOIN users.partners as p ON p.user_id = a.partner_id
											left JOIN users.centers as c ON c.id = a.center_id
											left JOIN users.accounts u ON u.id=a.user_id
											$sWhere
											$order_by limit $limit OFFSET $pg");

			$slno=$pg;
			$data = array();
			foreach ($associates_recs->result() as $associates)
			{  // preparing an array
				$row=array();
				$slno++;
				$row[] = $slno;
				/*$row[] = '<a href="javascript:void(0)" title="Associte desk" onclick="associate_desk('."'".$associates->id."'".')">'.$associates->name.'</a>';*/
				$row[] = $associates->name;
				$row[] = $associates->address;
				$row[] = $associates->phone;
				$row[] = $associates->email;
				$row[] = $associates->partner_name;
				$row[] = $associates->center_name;
				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit associate" onclick="edit_associates('."'".$associates->id."'".')"><i class="icon-edit"></i></a>
					  	  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Delete associate" onclick="delete_associates('."'".$associates->id."'".')"><i class="icon-android-delete"></i></a>';

				$data[] = $row;
			}
			//  $data[] = $employee_recs->result_array();
			$associates_data_recs = array(
		            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		            "recordsTotal"    => intval( $totalData ),  // total number of records
		            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		            "data"            => $data   // total data array
		            );
			return $associates_data_recs;
		}
	}

	/**ew
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get associate by id
	*/
	function get_associate_by_id($user_id)
	{
		$result=$this->db->query("SELECT a.id,a.name,a.address,a.phone, p.name as partner_name,c.id as center_id,c.name as center_name,u.email
											FROM users.associates AS a
											left JOIN users.partners as p ON p.user_id = a.partner_id
											left JOIN users.centers as c ON c.id = a.center_id
											left JOIN users.accounts u ON u.id=a.user_id
											where a.user_id=?",$user_id);
		if($result->num_rows())
			return $result->row();
		else
			return false;
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to add associate
	*/
	function do_add_associates($data)
	{

		$user_group_id=5;
		$email=$data['email'];
		$pwd= $data['password'];
		$created_on=date('Y-m-d');
		$result=$this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

	  	if($this->db->affected_rows())
	  	{
	  		$user_id= $this->db->insert_id();
	  		$associate_data=array(
				'user_id'=>$user_id,
				'name'=>$data['name'],
				'phone'=> $data['phone'],
				'partner_id' => $data['partner_id'],
				'address' => $data['address'],
				'center_id' => $data['center_id']
				);
	  		$this->db->insert('users.associates', $associate_data);
	  		if($this->db->affected_rows())
		  	{
		  		return true;
		  	}
	  		else
	  		{
	  			return false;
	  		}
	  	}
	  	else
	  	{
	  		return false;
	  	}
	}

	function do_update_associate($where1,$where2,$udata,$adata)
	{

		$is_updated=false;
		$output['msg_info']='';
		$this->db->update('users.accounts', $udata, $where1);
		if($this->db->affected_rows())
		{
			$is_updated=true;
		}

		$this->db->update('users.associates', $adata, $where2);
		if($this->db->affected_rows())
		{
			$is_updated=true;
		}
	  	if($is_updated)
	  	{
	  		$output['msg_info']=$output['msg_info'].'Associate user has been updated';
	  		return $output;
	  	}
	  	else
	  	{
	  		return false;
	  	}
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to delete associate
	*/
	function do_delete_associate($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('users.associates');
		if($this->db->affected_rows())
	  		return $this->db->affected_rows();
	  	else
	  		return false;
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to add candidate list
	*/
	function do_add_candidate($data)
	{
		$this->db->insert('neo.candidates', $data);
	  	if($this->db->affected_rows())
	  		return $this->db->insert_id();
	  	else
	  		return false;

	}
	function do_update_candidate($data,$candidate_id)
	{

		if(isset($data['aadhaar_num']))
		{
			$a_num = $data['aadhaar_num'];
			$aadhaar_num_present=$this->db->query('SELECT * FROM users.labournet_candidates WHERE id='.$candidate_id.'')->result()[0]->aadhaar_num;
			if($a_num != $aadhaar_num_present)
			$data['is_aadhar_verified']=FALSE;
		}
	/*	if($a_num != $aadhaar_num_present)
			{
						$strQuery = "UPDATE users.candidates";
                        $strQuery .= " SET is_aadhar_verified=FALSE";
                        $strQuery .= " WHERE id=".$candidate_id."";
                        $this->db->query($strQuery);
                        if ($this->db->affected_rows())
                        {
        					$this->db->where('id',$candidate_id);
							$this->db->update('users.candidates', $data);

						  	if($this->db->affected_rows())
						  		return true;
						  	else
						  		return false;
						}
			}
			else if($a_num == $aadhaar_num_present)
			{*/
					$this->db->where('id',$candidate_id);
					$this->db->update('users.labournet_candidates', $data);

				  	if($this->db->affected_rows())
				  		return true;
				  	else
				  		return false;
			/*}*/


	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get candidate list
	*/
    function get_candidate_list($education=0,$qualification_pack=0,$search_by_index,$search_original_text='',$pg=0,$limit=25)
	{

            $SearchtextCondition = '';
            $search_text = '';
            if ($search_original_text != 'EMPTY')
            {
                //$search_text = str_replace('%20', ' ', $search_original_text);
                $search_text = urldecode($search_original_text);
            }

            if ($search_by_index > 0)
            {
                switch ($search_by_index) {
                    case 1:
                        $SearchtextCondition = " AND C.id::TEXT ~* '$search_text' ";
                        break;
                    case 2:
                        $SearchtextCondition = " AND C.candidate_name ~* '$search_text' ";
                        break;
                    case 3:
                        $SearchtextCondition = " AND C.email ~* '$search_text' ";
                        break;
                    case 4:
                        $SearchtextCondition = " AND C.mobile ~* '$search_text' ";
                        break;
                    case 5:
                        $SearchtextCondition = " AND QP.batch_code ~* '$search_text' ";
                        break;
                    case 6:
                        $SearchtextCondition = " AND QP.center_name ~* '$search_text' ";
                        break;
                    case 7:
                        $SearchtextCondition = " AND C.candidate_enrollment_id ~* '$search_text' ";
                        break;
                    case 8:
                        $SearchtextCondition = " AND C.aadhaar_number ~* '$search_text' ";
                        break;
                    case 9:
                        $SearchtextCondition = " AND CS.name ~* '$search_text' ";
                        break;
                    case 10:
                        $SearchtextCondition = " AND CED.company_name ~* '$search_text' ";
                        break;
                    case 11:
                        $SearchtextCondition = " AND CED.location ~* '$search_text' ";
                        break;
                    case 12:
                        $SearchtextCondition = " AND CSD.skill_name ~* '$search_text' ";
                        break;
                    case 13:
                        $SearchtextCondition = " AND QP.course_name ~* '$search_text' ";
                        break;
                }
            }


            $Query = "WITH EDU AS
                    (
                            SELECT   CED.candidate_id,
                                             array_agg(CED.education_id) AS education_ids,
                                             STRING_AGG(CED.education_name,',') AS education
                            FROM 	 neo.candidate_education_details AS CED
                            GROUP BY CED.candidate_id
                    ),
                    QP AS
                    (
                            SELECT 	 CQD.candidate_id,
                                             array_agg(CQD.qualification_pack_id) AS qualification_pack_ids,
                                             STRING_AGG(CQD.qualification_pack,',') AS qualification_packs,
                                             STRING_AGG(CQD.course_name,',') AS course_name,
                                             STRING_AGG(CQD.center_name,',') AS center_name,
                                             STRING_AGG(CQD.batch_code,',') AS batch_code
                            FROM 	 neo.candidate_qp_details AS CQD
                            GROUP BY CQD.candidate_id
                    ),
                    CED AS
                    (
                            SELECT 	ED.candidate_id,
                                        STRING_AGG(ED.company_name,',') AS company_name,
                                        STRING_AGG(ED.location,',') AS location
                            FROM 	neo.candidate_employment_details AS ED
                            GROUP BY    ED.candidate_id
                    ),
                    CSD AS
                    (
                            SELECT      SD.candidate_id,
                                        STRING_AGG(SD.skill_name,',') AS skill_name
                            FROM 	neo.candidate_skill_details AS SD
                            GROUP BY    SD.candidate_id
                    )
                    SELECT 		COUNT(C.id) AS total_record_count
                    FROM		neo.candidates AS C
                    LEFT JOIN	EDU ON EDU.candidate_id=C.id
                    LEFT JOIN	QP ON QP.candidate_id=C.id
                    LEFT JOIN   neo_master.candidate_sources CS ON CS.id=C.source_id
                    LEFT JOIN   CED ON CED.candidate_id=c.id
                    LEFT JOIN   CSD ON CSD.candidate_id=c.id
                    WHERE	(COALESCE($qualification_pack,0) < 1 OR $qualification_pack=ANY(QP.qualification_pack_ids))
                    AND		(COALESCE($education,0) < 1 OR $education=ANY(EDU.education_ids))
                    $SearchtextCondition";
                   //echo $Query;
                   //exit;
		$total_records = $this->db->query($Query)->row()->total_record_count;
		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else {
                    $Query = "SELECT 	VW.id,
                                        REPLACE(VW.candidate_name,'','&nbsp;') AS full_name,
                                        VW.email as email_address,
                                        VW.gender_name AS gender,
                                        VW.dob AS date_of_birth,
                                        VW.total_experience as experience_in_years,
                                        VW.created_date,
                                        VW.state_name AS state,
                                        VW.mobile_number,
                                        VW.education AS education_name,
                                        COALESCE(VW.qualification_packs,'-NA-') AS qualification_pack_name,
                                        VW.mt_type,
                                        VW.aadhaar_number,
                                        VW.source_name,
                                        VW.batch_code,
                                        VW.center_name,
                                        VW.course_name,
                                        VW.skill_name,
                                        VW.company_name,
                                        VW.location,
                                        VW.candidate_enrollment_id,
																				VW.igs_customer_name,
																				VW.igs_contract_id
                                FROM	users.fn_get_candidate_data($qualification_pack,$education,$search_by_index,'$search_text',$limit,$pg) AS VW";
                    //echo $Query;
                   // exit;
                    $candidate_list_detail = $this->db->query( $Query) or die( '<pre>' . pg_last_error() . '</pre>' );

                    $ttl_res_curr = $candidate_list_detail->num_rows();
                    $page_number = ( $pg / $limit + 1 );


                    $LowerLimit = (1 + $pg);
                    $UpperLimit = ($page_number * $limit);
                    if ($UpperLimit > $total_records)
                        $UpperLimit = $total_records;

                    //$pg_count_msg = "Showing " . ( 1 + $pg ) . " to " . ( $page_number * $limit ) . " of " . $total_records;
                    $pg_count_msg = "Showing " . $LowerLimit . " to " . $UpperLimit . " of " . $total_records;
                    $pagination = _prepare_pagination( site_url( "partner/candidate_list/$education/$qualification_pack/$search_by_index/$search_original_text" ), $total_records, $limit, 7);

                    return array
                    (
                            'status' => 'success',
                            'rdata' => array(
                                    'candidate_list' => $candidate_list_detail->result_array(),
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
	 * function to get candidate by id
	*/
	function get_candidate_by_id($candidate_id,$associate_id)
	{
		$result=$this->db->query("SELECT * FROM users.candidates
								  WHERE id=? and referer_id=?",array($candidate_id,$associate_id));
		if($result->num_rows())
			return $result->row_array();
		else
			return false;
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get jobboard list
	*/

    function get_jobBoard_list($pg=0,$limit=25)
	{
		$cond='';
		$total_records=0;

		// ==== filters end =====
		$jobBoard_list_rec=$this->db->query("SELECT count(*) as total_rec from job_process.jobs WHERE 1=1 $cond") or die('<pre>'.pg_last_error().'</pre>');

		if($jobBoard_list_rec->num_rows())
		{
			$total_records=$jobBoard_list_rec->row()->total_rec;
		}

		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else
		{

			$jobBoard_list_detail=$this->db->query("SELECT *
														FROM job_process.jobs a
														WHERE 1=1 $cond
														ORDER BY a.created_on::date DESC NULLS LAST
														limit $limit OFFSET $pg") or die('<pre>'.pg_last_error().'</pre>');

		$ttl_res_curr=$jobBoard_list_detail->num_rows();
		$page_number=($pg/$limit+1);
		$pg_count_msg = "Showing ".(1+$pg)." to ".($ttl_res_curr+$pg)." of ".$total_records;
		$pagination=_prepare_pagination(site_url("partner/jobBoard_list"), $total_records, $limit,3);

		return array('status'=>'success','rdata'=>array('jobBoard_list'=>$jobBoard_list_detail->result_array()
					,'pg'=>$pg,'limit'=>$limit,'pagination'=>$pagination,'pg_count_msg'=>$pg_count_msg,'page_number'=>$page_number));
		}
	}


	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get application tracker list using datatables
	*/
	function get_applicationTracker_partner($requestData=array(),$partner_id='')
	{
		$order_by="";
		$data = array();
		$cond='';
		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'candidate_name',
		    2 => 'candidate_mobile',
		    3 => 'applied_count',
		    4 => 'screened_count',
		    5 => 'scheduled_count',
		    6 => 'shortlisted_count',
		    7 => 'selected_count',
		    8 => 'offered_count',
		    9 => 'screening_rejected_count',
		    10 => null
		);
		if($partner_id)
			$cond=" WHERE C.referer_id=".$partner_id;
		$column_search = array("C.name","C.mobile"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.candidates C $cond"
										)->row()->total_recs;

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
	            if($partner_id)
	        	$sWhere.=' and '. "C.referer_id=".$partner_id;
	        }
	        else
	        	$sWhere=$cond;

	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.candidates AS C
								        			$sWhere")->row()->total_filtered;

			$tracker_recs=$this->db->query("SELECT C.id AS candidate_id,C.name AS candidate_name,C.mobile AS candidate_mobile,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = 1) AS applied_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = 2) AS screened_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = 3) AS scheduled_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = 4) AS shortlisted_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = 5) AS selected_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = 6) AS offered_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = -2) AS screening_rejected_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = -3) AS schedule_rejected_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = -4) AS shortlist_rejected_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = -5) AS selection_rejected_count,
						                    (SELECT COUNT(job_id) FROM job_process.candidate_jobs WHERE candidate_id=C.id AND status_id = -6) AS offer_rejected_count
						        			FROM users.candidates AS C
						        			$sWhere
											$order_by limit $limit OFFSET $pg");
			//$totalFiltered=$tracker_recs->num_rows();
			$slno=$pg;
			$data = array();
			foreach ($tracker_recs->result() as $applicationTracker)
			{  // preparing an array
				$row=array();
				$slno++;
				$row[] = $slno;
				$row[] = $applicationTracker->candidate_name;
				$row[] = $applicationTracker->candidate_mobile;
				$row[] = ($applicationTracker->applied_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$applicationTracker->candidate_id."'".',1)">'.$applicationTracker->applied_count.'</b></a>':$applicationTracker->applied_count;
				$row[] = ($applicationTracker->screened_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$applicationTracker->candidate_id."'".',2)">'.$applicationTracker->screened_count.'</b></a>':$applicationTracker->screened_count;
				$row[] = ($applicationTracker->scheduled_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$applicationTracker->candidate_id."'".',3)">'.$applicationTracker->scheduled_count.'</b></a>':$applicationTracker->scheduled_count;
				$row[] = ($applicationTracker->shortlisted_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$applicationTracker->candidate_id."'".',4)">'.$applicationTracker->shortlisted_count.'</b></a>':$applicationTracker->shortlisted_count;
				$row[] = ($applicationTracker->selected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$applicationTracker->candidate_id."'".',5)">'.$applicationTracker->selected_count.'</b></a>':$applicationTracker->selected_count;
				$row[] = ($applicationTracker->offered_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$applicationTracker->candidate_id."'".',5)">'.$applicationTracker->offered_count.'</b></a>':$applicationTracker->offered_count;
				$row[] = ($applicationTracker->screening_rejected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$applicationTracker->candidate_id."'".',-2)">'.$applicationTracker->screening_rejected_count.'</b></a>':$applicationTracker->screening_rejected_count;
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

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get application tracker by id
	*/
	function get_applicationTracker_by_id($id)
	{
		$this->db->from('users.candidates');
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get matching job list
	*/
	function get_matching_job_list($candidate_id=0,$pg=0,$limit=25)
	{
		$cond='';
		$total_records=0;

		$result=$this->db->query("SELECT education_id,experience_id
									FROM users.candidates
									where id=?",$candidate_id)->row_array();
		$education_id=$result['education_id'];
		$experience_id=$result['experience_id'];

		// ==== filters end =====
		$matching_job_list_rec=$this->db->query("SELECT count(*) as total_rec FROM job_process.jobs
												 WHERE min_qualification_id=?
												 AND min_experience::float<=?
												 AND max_experience::float>=?", array($education_id,$experience_id+1, $experience_id+1)) or die('<pre>'.pg_last_error().'</pre>');

		if($matching_job_list_rec->num_rows())
		{
			$total_records=$matching_job_list_rec->row()->total_rec;
		}

		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else
		{
			$matching_job_list_detail=$this->db->query("SELECT j.*,e.name as min_education,l.name as job_category,q.name as qualification_name,
														COALESCE(NULLIF(emp.name,''),'-NA-') as employer_name,COALESCE(NULLIF(cj.status_id,'0') , '0' ) as job_status_id,
														COALESCE(NULLIF(ms.name,'') , 'Apply' ) as job_status,
														COALESCE(( SELECT count(*) AS count
														FROM job_process.job_detail jd
														WHERE jd.job_id = j.id and jd.job_status='t'
														GROUP BY jd.job_id), 0::bigint) AS n_locations,
														COALESCE(( select string_agg(dt.name, ', ')
														from job_process.job_detail jd
														LEFT JOIN master.district dt ON dt.id = jd.location_id
														WHERE jd.job_id = j.id and jd.job_status='t'
														group by jd.job_id), '-NA-') AS location_name

														FROM job_process.jobs j
														LEFT JOIN master.education e on e.id=j.min_qualification_id
														LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
														LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
														LEFT JOIN users.employers emp on emp.user_id=j.employer_id
														LEFT JOIN job_process.candidate_jobs cj on cj.job_id=j.id and cj.candidate_id=$candidate_id
														LEFT JOIN master.status ms on ms.value=cj.status_id
														WHERE min_qualification_id=?
														AND min_experience::float<=?
														AND max_experience::float>=?
														LIMIT $limit OFFSET $pg",array($education_id,$experience_id, $experience_id)) or die('<pre>'.pg_last_error().'</pre>');

		$ttl_res_curr=$matching_job_list_detail->num_rows();
		$page_number=($pg/$limit+1);
		$pg_count_msg = "Showing ".(1+$pg)." to ".($ttl_res_curr+$pg)." of ".$total_records;
		$pagination=_prepare_pagination(site_url("partner/matching_jobs_list/$candidate_id"), $total_records, $limit,4);

		return array('status'=>'success','rdata'=>array('matching_job_list'=>$matching_job_list_detail->result_array()
					,'pg'=>$pg,'limit'=>$limit,'pagination'=>$pagination,'pg_count_msg'=>$pg_count_msg,'page_number'=>$page_number));
		}

	}


	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * Date: 20/12/2016
	 * function to get screened job
	*/
	/*function get_screenedjob_for_candidate($candidate_id=0)
	{
		//$cond='';
		$total_records=0;
		$screened_data=$this->db->query("SELECT cj.id,j.no_of_openings,j.job_location,j.question_one,j.question_two,j.job_desc,j.min_experience,j.max_experience,
												j.created_on,e.name as min_education,l.name as job_category,q.name as qualification_name,
												emp.name as employer_name,cj.id as screen_id,cj.q1_response,cj.q2_response,cj.status_id,COALESCE(NULLIF(ms.name,'') , '-NA-' ) as job_status
												FROM job_process.candidate_jobs cj
												INNER JOIN job_process.jobs j on cj.job_id=j.id
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
												LEFT JOIN master.status ms on cj.status_id=ms.value
												WHERE cj.candidate_id=?",$candidate_id);

		//total_experience>= and total_experience<=
		if($screened_data->num_rows())
		{
			$total_records=$screened_data->num_rows();
		}

		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else
			return array('status'=>'success','rdata'=>array('screened_candidate_list'=>$screened_data->result_array(),'total_rec'=>$total_records ));

	}*/

	function get_job_for_candidate_by_status($candidate_id=0,$status_id=0)
	{
		//$cond='';
		$total_records=0;
		$screened_data=$this->db->query("SELECT cj.id,cj.job_id,j.no_of_openings,j.job_location,j.question_one,j.question_two,j.job_desc,j.min_experience,j.max_experience,
												j.created_on,e.name as min_education,l.name as job_category,q.name as qualification_name,
												COALESCE(NULLIF(emp.name,'') , '-NA-' ) as employer_name,cj.id as screen_id,cj.q1_response,cj.q2_response,cj.status_id,
												COALESCE(NULLIF(ms.name,'') , '-NA-' ) as job_status,
												COALESCE(( SELECT count(*) AS count FROM job_process.job_detail jd WHERE jd.job_id = j.id and jd.job_status='t'
												GROUP BY jd.job_id), 0::bigint) AS n_locations,
												COALESCE(( select string_agg(dt.name, ', ') FROM job_process.job_detail jd
												LEFT JOIN master.district dt ON dt.id = jd.location_id WHERE jd.job_id = j.id and jd.job_status='t'
												group by jd.job_id), '-NA-') AS location_name

												FROM job_process.candidate_jobs cj
												INNER JOIN job_process.jobs j on cj.job_id=j.id
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
												LEFT JOIN master.status ms on cj.status_id=ms.value
												WHERE cj.candidate_id=? and cj.status_id=?",array($candidate_id,$status_id));

		//total_experience>= and total_experience<=
		if($screened_data->num_rows())
		{
			$total_records=$screened_data->num_rows();
		}

		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else
			return array('status'=>'success','rdata'=>array('screened_candidate_list'=>$screened_data->result_array(),'total_rec'=>$total_records ));

	}


	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to get matching candidate list
	*/
	function get_matching_candidate_list($rec_exec_id=0,$min_education_id=0,$partner_id=0,$job_id=0,$location_id=0,$min_experience=0,$max_experience=0,$pg=0,$limit=25)
	{
		$cond='';
		$total_records=0;
		if($location_id)
			$cond.= ' and c.district_id='.$location_id;
		if($rec_exec_id)
			$cond.= ' and c.rec_exec_id='.$rec_exec_id;
		// ==== filters end =====
		$matching_candidates_list_rec=$this->db->query("SELECT COUNT(DISTINCT c.id) as total_rec
														FROM users.candidates c
														LEFT join master.education e on e.id=c.education_id
														LEFT join master.list ml on ml.value::integer=c.experience_id and ml.code='L0006'
														LEFT join job_process.candidate_jobs cj on cj.candidate_id=c.id and cj.job_id=$job_id
														LEFT join master.status ms on cj.status_id=ms.value
														LEFT join users.partners up on up.user_id=c.referer_id
														where c.education_id='$min_education_id'
														and c.experience_id::float BETWEEN $min_experience and $max_experience
														$cond") or die('<pre>'.pg_last_error().'</pre>');
														//and c.id NOT in(select candidate_id from job_process.candidate_jobs where job_id=$job_id)") or die('<pre>'.pg_last_error().'</pre>');

				//total_experience>= and total_experience<=

		if($matching_candidates_list_rec->num_rows())
		{
			$total_records=$matching_candidates_list_rec->row()->total_rec;
		}

		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else
		{
			$matching_candidates_list_detail=$this->db->query("SELECT DISTINCT c.id,COALESCE(NULLIF(initcap(c.name),'') , '-NA-' ) as name,COALESCE(NULLIF(ml.name,'') , '-' ) as total_experience,e.name as education, to_char(c.dob, 'DD/Mon/YYYY') as dob, c.gender_code,c.aadhaar_num,c.email,c.mobile,to_char(c.created_on, 'DD/Mon/YYYY') as created_on, COALESCE(NULLIF(cj.status_id,'0') , 0 ) as job_status_id,COALESCE(NULLIF(ms.name,'') , '' ) as job_status
														FROM users.candidates c
														LEFT join master.education e on e.id=c.education_id
														LEFT join master.list ml on ml.value::integer=c.experience_id and ml.code='L0006'
														LEFT join job_process.candidate_jobs cj on cj.candidate_id=c.id and cj.job_id=$job_id
														LEFT join master.status ms on cj.status_id=ms.value
														LEFT join users.partners up on up.user_id=c.referer_id
														where c.education_id='$min_education_id'
														and c.experience_id::float BETWEEN $min_experience and $max_experience
														$cond
														limit $limit OFFSET $pg") or die('<pre>'.pg_last_error().'</pre>');
														//and c.id NOT in(select candidate_id from job_process.candidate_jobs where job_id=$job_id

			$ttl_res_curr=$matching_candidates_list_detail->num_rows();
		$page_number=($pg/$limit+1);
		$pg_count_msg = "Showing ".(1+$pg)." to ".($ttl_res_curr+$pg)." of ".$total_records;
		$pagination=_prepare_pagination(site_url("partner/matching_candidate_list/$job_id/$location_id"), $total_records, $limit,5);

		return array('status'=>'success','rdata'=>array('matching_candidate_list'=>$matching_candidates_list_detail->result_array()
					,'pg'=>$pg,'limit'=>$limit,'pagination'=>$pagination,'pg_count_msg'=>$pg_count_msg,'page_number'=>$page_number));
		}
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * Date: 20/12/2016
	 * function to get screened candidates
	*/
	function get_screenedcandidates_for_job($job_id=0,$location_id=0)
	{
		$cond='';
		if($job_id)
			$cond.=' and cj.job_id='.$job_id;
		if($location_id)
			$cond.=' and cj.location_id='.$location_id;
		$total_records=0;
		$screened_data=$this->db->query("SELECT cj.id as candidate_job_id,uc.id,initcap(uc.name) as name,ls1.name as total_experience,e.name as education, to_char(uc.dob, 'DD/Mon/YYYY') as dob, uc.gender_code,uc.aadhaar_num,uc.email,uc.mobile,uc.created_on,
											e.name as min_education,q.name as qualification_name,
											emp.name as employer_name,cj.id as screen_id,cj.q1_response,cj.q2_response,cj.status_id,COALESCE(NULLIF(ms.name,'') , '-NA-' ) as job_status
											FROM job_process.candidate_jobs cj
											INNER JOIN users.candidates uc on cj.candidate_id=uc.id
											LEFT JOIN master.education e on e.id=uc.education_id
											LEFT JOIN master.qualification_pack q on q.id=uc.education_id
											LEFT JOIN users.employers emp on emp.user_id=uc.referer_id
											LEFT JOIN master.status ms on cj.status_id=ms.value
											inner JOIN master.list ls1 on uc.experience_id=(ls1.value)::int and ls1.code='L0006'
											WHERE 1=1 $cond");

		//total_experience>= and total_experience<=
		if($screened_data->num_rows())
		{
			$total_records=$screened_data->num_rows();
		}

		if(!$total_records)
			return array('status'=>'error','message'=>"<p style='text-align:center'>No result-data found</p>");
		else
			return array('status'=>'success','rdata'=>array('screened_candidate_list'=>$screened_data->result_array(),'total_rec'=>$total_records ));

	}

	 // Send Gmail to another user
    function send_mail($to='',$subject='',$message='')
	{
		$sent=false;
		if($to)
		{
			$this->load->library('email');
			$this->email->from(EMAIL_FROM, 'Pramaan');
			$this->email->to($to);
			//$this->email->cc(EMAIL_CC);
			$this->email->subject($subject);
			$this->email->message($message);
			$sent=$this->email->send();
		}
		if($sent)
			return true;
		else
			return false;
	}

	function get_tracked_candidate_jobs($candidate_id=0,$job_status_id=0)
	{
		$candidate_det_rec=$this->db->query("SELECT id as candidate_id, name,mobile, (SELECT name from master.status where value=$job_status_id) as candidate_status
												from users.candidates
												where id=?",$candidate_id);

		$job_det_rec=$this->db->query("SELECT cj.id as applied_id,cj.job_id,cj.status_id,j.job_desc,COALESCE(NULLIF(e.name,'') , '-' ) as employer_name
										FROM job_process.candidate_jobs cj
										LEFT JOIN job_process.jobs j on j.id=cj.job_id
										LEFT JOIN users.employers e on e.user_id=j.employer_id
										where cj.candidate_id=? and cj.status_id=?",array($candidate_id,$job_status_id));

		if($candidate_det_rec->num_rows())
		{
			$output['status']=true;
			$output['candidate_detail']=$candidate_det_rec->row_array();
			if($job_det_rec->num_rows())
				$output['job_detail']=$job_det_rec->result_array();
			else
				$output['job_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}

	function get_candidates_byemployerjob($customer_id=0,$candidate_job_status_id=0)
	{
		$employer_det_rec=$this->db->query("SELECT customer_name,spoc_phone FROM neo_customer.customers WHERE id=?",$customer_id);
		$CandidateJobStatusName = "";
		$job_status_rec=$this->db->query("SELECT name AS job_status_name FROM neo_master.candidate_statuses WHERE id=?",$candidate_job_status_id);
		if ($job_status_rec->num_rows()) $CandidateJobStatusName = $job_status_rec->row()->job_status_name;

		$candidate_det_rec=$this->db->query("SELECT 		CJ.id AS job_applied_id,
                                                                        CJ.candidate_id,
                                                                        COALESCE(NULLIF(J.job_title,'') , '-NA-' ) as job_title,
                                                                        COALESCE(NULLIF(J.job_description,'') , '-NA-' ) as job_desc,
                                                                        COALESCE(NULLIF((CASE COALESCE(QP.name,'') WHEN '' THEN '-NA-' ELSE FORMAT('%s (%s)',QP.name,QP.code) END) ,'') , '-NA-' ) as qualification_pack_name,
                                                                        COALESCE(NULLIF(C.candidate_name,'') , '-NA-' ) as candidate_name,
                                                                        COALESCE(NULLIF(C.mobile ,'') , '-NA-' ) as mobile,
                                                                        COALESCE(NULLIF(G.name,'') , '-NA-' ) as gender_name,
                                                                        COALESCE(NULLIF(D.name,''),'-NA') as location_name,
                                                                        CJ.candidate_status_id,
                                                                        CJS.name As candidate_job_status_name
                                                FROM		neo_customer.customers AS CUST
												LEFT JOIN	neo_job.jobs AS J ON J.customer_id=CUST.id
												LEFT JOIN 	neo_job.candidates_jobs AS CJ ON CJ.job_id=J.id AND COALESCE(CJ.candidate_id,0)>0
												LEFT JOIN	neo.candidates AS C ON C.id= CJ.candidate_id
												LEFT JOIN	neo_master.genders AS G ON G.id=C.gender_id
												LEFT JOIN	neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
												LEFT JOIN	neo_master.candidate_statuses AS CJS ON CJS.id=J.job_status_id
                                                                                                LEFT JOIN neo_master.districts AS D ON D.id=j.district_id
												WHERE		CUST.id=?
												AND			CJ.candidate_status_id=?",array($customer_id,$candidate_job_status_id));

		if($employer_det_rec->num_rows())
		{
			$output['status']=true;
			$output['candidate_job_status_name']=$CandidateJobStatusName;
			$output['employer_detail']=$employer_det_rec->row_array();
			if($candidate_det_rec->num_rows())
				$output['candidate_detail']=$candidate_det_rec->result_array();
			else
				$output['candidate_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}

	function get_jobs_by_employer($employer_id=0)
	{
		$employer_det_rec=$this->db->query("SELECT name as employer_name,phone from users.employers
											 where user_id=?",$employer_id);

		$job_det_rec=$this->db->query("SELECT COALESCE(NULLIF(pj.job_desc,'') , '-NA-' ) as job_desc,COALESCE(NULLIF(qp.name,'') , '-NA-' ) as qualification_pack_id,pj.created_on
										FROM job_process.jobs pj
										left join master.qualification_pack qp on qp.id=pj.qualification_pack_id
										where employer_id=?",array($employer_id));

		if($employer_det_rec->num_rows())
		{
			$output['status']=true;
			$output['employer_detail']=$employer_det_rec->row_array();
			if($job_det_rec->num_rows())
				$output['job_detail']=$job_det_rec->result_array();
			else
				$output['job_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * Date: 20/12/2016
	 * function to get employers list datatable
	*/
	function get_employers_list($requestData=array(),$bd_exec_id=0)
	{

		$order_by="";
		$data = array();
		$cond='';
		if($bd_exec_id)
		{
			$cond="where a.recruitment_partner_id=".$bd_exec_id;
		}

		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'a.name',
		    2 => 'a.phone',
		    3 => 'ua.email',
		    4 => 'a.spoc_name',
		    5 => 'a.spoc_phone',
		    6 => null
		);
		$column_search = array("a.name","a.phone","ua.email","a.spoc_name","a.spoc_phone"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.employers a
											left JOIN users.bd_executives as p ON p.user_id = a.created_by
											left JOIN master.list c ON c.value::integer=a.organization_type_id and code='L0002'
											left JOIN master.sector d ON d.id = a.sector_id
											left join users.accounts ua on ua.id=a.user_id $cond"
										)->row()->total_recs;

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
	            if($rec_partner_id)
			    $sWhere .=" and a.recruitment_partner_id=".$rec_partner_id;

	        }
	        else
	        	$sWhere=$cond;

	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.employers a
													left JOIN users.recruitment_partners as p ON p.user_id = a.recruitment_partner_id
													left JOIN master.list c ON c.value::integer=a.organization_type_id and code='L0002'
													left JOIN master.sector d ON d.id = a.sector_id
													left join users.accounts ua on ua.id=a.user_id
								        			$sWhere")->row()->total_filtered;

			$employers_recs=$this->db->query("SELECT a.user_id as employer_id,a.name,a.address,a.pin,a.spoc_name,a.spoc_phone,a.phone,a.hr_name,a.hr_phone,a.hr_email, p.name as partner_name,c.name as organization_type,d.name as sector_name,ua.email
												FROM users.employers a
												left JOIN users.recruitment_partners as p ON p.user_id = a.recruitment_partner_id
												left JOIN master.list c ON c.value::integer=a.organization_type_id and code='L0002'
												left JOIN master.sector d ON d.id = a.sector_id
												left join users.accounts ua on ua.id=a.user_id
											$sWhere
											$order_by limit $limit OFFSET $pg");
			$slno=$pg;
			$data = array();
			foreach ($employers_recs->result() as $employers)
			{  // preparing an array
				$row=array();
				$slno++;
				$row[] = $slno;
				$row[] = '<a href="javascript:void(0)" title="Employer Details" onclick="employer_desk('."'".$employers->employer_id."'".')">'.$employers->name.'</a>';
				$row[] = $employers->phone;
				$row[] = $employers->email;
				$row[] = $employers->spoc_name;
				$row[] = $employers->spoc_phone;
				//add html for action
				$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit Employer" onclick="edit_employer('."'".$employers->employer_id."'".')"><i class="icon-edit"></i></a>
					  	 ';
				$data[] = $row;
			}
			//  $data[] = $employee_recs->result_array();
			$employers_data_recs = array(
		            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		            "recordsTotal"    => intval( $totalData ),  // total number of records
		            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		            "data"            => $data   // total data array
		            );
			return $employers_data_recs;
		}
	}

	/**
	 * @author Sangamesh <sangamesh.p@pramaan.in>
	 * function to  add employer
	*/
	function do_add_employer($data)
	{
		$user_group_id=$this->db->query("SELECT value from master.list
									where code='L0001' and lower(name)=?",strtolower(UER))->row()->value;

		$email=$data['email'];
		$pwd= $data['password'];
		$created_on=date('Y-m-d');
		$result=$this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

	  	if($this->db->affected_rows())
	  	{
	  		$user_id= $this->db->insert_id();
	  		$employer_data=array(
				'user_id'=>$user_id,
				'name'=>$data['name'],
				'phone'=> $data['phone'],
				'sector_id' => $data['sector_id'],
				'spoc_name' => $data['spoc_name'],
				'spoc_phone' => $data['spoc_phone'],
				'organization_type_id' => $data['organization_type_id'],
				'created_by'=>$data['created_by'],
				'created_on'=>date('Y-m-d')
				);
	  		$this->db->insert('users.employers', $employer_data);
	  		if($this->db->affected_rows())
		  	{
		  		return true;
		  	}
	  		else
	  		{
	  			return false;
	  		}
	  	}
	  	else
	  	{
	  		return false;
	  	}

	}

	function do_update_employer($data1,$data2,$employer_id)
	{

	  	$this->db->where('user_id',$employer_id);
		$this->db->update('users.employers', $data1);
		$this->db->where('id',$employer_id);
		$this->db->update('users.accounts', $data2);
	  	if($this->db->affected_rows())
	  		return true;
	  	else
	  		return false;

	}
	function get_employers_tracker($requestData=array(),$bd_exec_id=0)
	{
		$order_by="";
		$cond='';
		$data = array();
		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'employer_name',
		    2 => 'total_jobs',
		    3 => 'applied_count',
		    4 => 'screened_count',
		    5 => 'scheduled_count',
		    6 => 'shortlisted_count',
		    7 => 'selected_count',
		    8 => 'offered_count',
		    9 => 'joined_count',
		    10 => null
		);
		if($bd_exec_id)
			$cond=" where ue.created_by=".$bd_exec_id;
		$column_search = array("ue.name"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.employers ue $cond"
										)->row()->total_recs;

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
	            if($bd_exec_id)
	            $sWhere .=" and ue.created_by=".$bd_exec_id;
	        }
	        else
	        	$sWhere=$cond;

	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.employers ue
								        			$sWhere")->row()->total_filtered;

			$employers_recs=$this->db->query("SELECT ue.user_id,ue.name as employer_name,
											(SELECT COUNT(id) FROM job_process.jobs WHERE employer_id=ue.user_id) AS total_jobs,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT id FROM job_process.jobs WHERE employer_id=ue.user_id) AND status_id = 1) AS applied_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT id FROM job_process.jobs WHERE employer_id=ue.user_id) AND status_id = 2) AS screened_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT id FROM job_process.jobs WHERE employer_id=ue.user_id) AND status_id = 3) AS scheduled_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT id FROM job_process.jobs WHERE employer_id=ue.user_id) AND status_id = 4) AS shortlisted_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT id FROM job_process.jobs WHERE employer_id=ue.user_id) AND status_id = 5) AS selected_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT id FROM job_process.jobs WHERE employer_id=ue.user_id) AND status_id = 6) AS offered_count,
											(SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT id FROM job_process.jobs WHERE employer_id=ue.user_id) AND status_id = 7) AS joined_count
											from users.employers ue
						        			$sWhere
											$order_by limit $limit OFFSET $pg");
			//$totalFiltered=$employers_recs->num_rows();
			$slno=$pg;
			$data = array();
			foreach ($employers_recs->result() as $employers)
			{  // preparing an array
				$row=array();
				$slno++;
				$row[] = $slno;
				$row[] = $employers->employer_name;
				$row[] = ($employers->total_jobs)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs('."'".$employers->user_id."'".')">'.$employers->total_jobs.'</b></a>':$employers->total_jobs;
				$row[] = ($employers->applied_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->user_id."'".',1)">'.$employers->applied_count.'</b></a>':$employers->applied_count;
				$row[] = ($employers->screened_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->user_id."'".',2)">'.$employers->screened_count.'</b></a>':$employers->screened_count;
				$row[] = ($employers->scheduled_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->user_id."'".',3)">'.$employers->scheduled_count.'</b></a>':$employers->scheduled_count;
				$row[] = ($employers->shortlisted_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->user_id."'".',4)">'.$employers->shortlisted_count.'</b></a>':$employers->shortlisted_count;
				$row[] = ($employers->selected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->user_id."'".',5)">'.$employers->selected_count.'</b></a>':$employers->selected_count;
				$row[] = ($employers->offered_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->user_id."'".',6)">'.$employers->offered_count.'</b></a>':$employers->offered_count;
				$row[] = ($employers->joined_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->user_id."'".',7)">'.$employers->joined_count.'</b></a>':$employers->joined_count;
				$data[] = $row;
			}

			//  $data[] = $employee_recs->result_array();
			$employers_data_recs = array(
		            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		            "recordsTotal"    => intval( $totalData ),  // total number of records
		            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		            "data"            => $data   // total data array
		            );
			return $employers_data_recs;
		}
	}
	function do_add_psychometric_results($data)
	{

		$this->db->insert('job_process.psychometric_test_results', $data);

	  	if($this->db->affected_rows())
	  		return $this->db->insert_id();
	  	else
	  		return false;

	}
	/*function matching_jobs_bypsychometric_results($data=array())
	{

		$candidate_id=$data['candidate_id'];
		$dept_id1=$data['interested_dept_id1'];
		$marks1=$data['interested_dept_marks1'];
		$dept_id2=$data['interested_dept_id2'];
		$marks2=$data['interested_dept_marks2'];
		$dept_id3=$data['interested_dept_id3'];
		$marks3=$data['interested_dept_marks3'];
		$matching_rec=$this->db->query("SELECT j.job_desc,concat(j.min_experience,'-',j.max_experience) as experenice,j.job_location,concat(min_salary,'-',max_salary) as salary,e.name as min_education,
											l.name as job_category,q.name as qualification_name,COALESCE(NULLIF(emp.name,''),'-NA-') as employer_name,md.name as department_name,
											CASE
									                WHEN j.department_id = $dept_id1 THEN $marks1
								                    WHEN j.department_id = $dept_id1 THEN $marks2
								                    WHEN j.department_id = $dept_id1 THEN $marks3
								                    ELSE '0'::NUMERIC
									        END AS job_weightage,
									        j.no_of_openings-(select count(candidate_id) from job_process.candidate_jobs where job_id=j.id and status_id=7) as n_available_jobs
									FROM job_process.jobs j
									LEFT JOIN master.education e on e.id=j.min_qualification_id
									LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
									LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
									LEFT JOIN users.employers emp on emp.user_id=j.employer_id
									Left join master.departments md on md.department_id =j.department_id
									WHERE min_qualification_id=(select education_id from users.candidates where id=?)
									order by job_weightage",$candidate_id);
		if($matching_rec->num_rows())
		{
			return $matching_rec->result_array();
		}
		else
			return false;
	}*/

	function matching_jobs_bypsychometric_results($candidate_id=0)
	{
		$matching_rec=$this->db->query("SELECT j.id as job_id,q.name as jobrole,j.job_desc,l.name as job_category_name,e.name as min_qualification_name,j.min_experience,j.max_experience,j.min_salary,j.max_salary,COALESCE(NULLIF(cj.status_id,'0') , '0' ) as job_status_id,COALESCE(NULLIF(ms.name,'') , '' ) as job_status,
										j.question_one,j.question_two,j.job_location,j.contact_name,j.contact_email,j.contact_phone,COALESCE(NULLIF(emp.name,''),'-NA-') as employer_name,COALESCE(NULLIF(md.name,'') , '-NA-' ) as department_name,
									/*	CASE
										WHEN j.department_id =(select interested_dept_id1 from job_process.psychometric_test_results where candidate_id=$candidate_id) THEN (select interested_dept_marks1  from job_process.psychometric_test_results  where candidate_id=$candidate_id)::NUMERIC
										WHEN j.department_id =(select interested_dept_id2 from job_process.psychometric_test_results  where candidate_id=$candidate_id) THEN (select interested_dept_marks2  from job_process.psychometric_test_results  where candidate_id=$candidate_id)::NUMERIC
										WHEN j.department_id =(select interested_dept_id3 from job_process.psychometric_test_results where candidate_id=$candidate_id) THEN (select interested_dept_marks3  from job_process.psychometric_test_results  where candidate_id=$candidate_id)::NUMERIC
										ELSE '0'::NUMERIC
										END AS job_weightage,*/
										j.no_of_openings-(select count(candidate_id) from job_process.candidate_jobs where job_id=j.id and status_id=7) as no_of_openings
										FROM job_process.jobs j
										LEFT JOIN master.education e on e.id=j.min_qualification_id
										LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
										LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
										LEFT JOIN users.employers emp on emp.user_id=j.employer_id
										LEFT JOIN master.departments md on md.department_id =j.department_id
										LEFT JOIN job_process.candidate_jobs cj on cj.job_id=j.id and cj.candidate_id=$candidate_id
										LEFT JOIN master.status ms on ms.value=cj.status_id
										WHERE min_qualification_id=(select education_id from users.candidates where id=$candidate_id)
										",$candidate_id);

		if($matching_rec->num_rows())
		{
			return $matching_rec->result_array();
		}
		else
			return false;
	}


	function do_apply_for_job($candidate_id=0,$job_id=0,$location_id=0,$job_apply_status=0,$q1=0,$q2=0)
	{
		$created_on=date('Y-m-d');
		if(!is_array($location_id))
			$location_id=array($location_id);

		if($job_id>0)
		{
			$exist=$this->db->query("select * from job_process.candidate_jobs where candidate_id = $candidate_id and job_id = $job_id");
			if($exist->num_rows())
			{
					foreach ($location_id as $value_location)
					{
						$result = $this->db->query("UPDATE job_process.candidate_jobs set status_id=?, location_id=? where candidate_id=? and job_id=? ", array($job_apply_status, $value_location, $candidate_id, $job_id));
					}
			}
			else
			{
				foreach ($location_id as $value_location)
				{
					$insert_query = "INSERT into job_process.candidate_jobs (candidate_id, job_id, location_id,status_id, q1_response,q2_response,created_on)
							SELECT $candidate_id, $job_id, $value_location,$job_apply_status,$q1,$q2,'$created_on'
							WHERE not exists (select * from job_process.candidate_jobs where candidate_id = $candidate_id and job_id = $job_id and status_id=$job_apply_status)";
				}
				$result=$this->db->query($insert_query);
			}
			if($result)
				return true;
			else
				return false;
		}
		else
			return false;
	}

	/*function do_schedule_the_job($candidate_id=0,$job_id=0,$job_schedule_status=0)
	{
		$created_on=date('Y-m-d');
		if($job_id>0)
		{
			$exist=$this->db->query("select * from job_process.candidate_jobs where candidate_id = $candidate_id and job_id = $job_id");
			if($exist->num_rows())
			{
				$result=$this->db->query("UPDATE job_process.candidate_jobs set status_id=? where candidate_id=? and job_id=?",array($job_schedule_status,$candidate_id,$job_id));
				if($result)
					return true;
				else
					return false;

			}
			else
			{
				return false;
			}
		}
		else
			return false;
	}*/

	/* Saurabh Sinha's work starts here */
    public function CallAPI($method, $url, $data = false,$id_aadhaar_number,$name)
		{

		    //
		        /*echo "Coming";
		        die;*/
		        $ch = curl_init($url);
		        switch ($method)
		        {
		            case "POST":
		                curl_setopt($ch, CURLOPT_POST, 1);

		                if ($data)
		                    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		                break;
		            case "PUT":
		                curl_setopt($ch, CURLOPT_PUT, 1);
		                break;
		            default:
		                if ($data)
		                    $url = sprintf("%s?%s", $url, http_build_query($data));
		        }

		        $apiKey =BETTERPLACE_APIKEY; // should match with Server key
		        $headers = array();
		        $headers[] = 'Accept: application/json';
		        $headers[] = 'Content-Type: application/json';
		        $headers[] = 'apiKey:'.$apiKey.'';
		        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		        // Get response
		        $response = curl_exec($ch);
		        // Decode
		       /* echo $response;
                die;*/
		        $data2=json_decode($response);

		        /*echo $data;*/
				//print_r($data->messages[0]->message);
				if($data2->data=="true")
				{
					return true;
					/*$this->partner->change_aadhaar_status($id_aadhaar_number);
					$success_msg=$name."'s Aadhaar has been verified!!";*/
					/*redirect('/partner/candidates/'+$response_msg);*/
					/*print_r($this->session->userdata['usr_authdet']['id']);
					die;*/
					/*$ass=$this->session->userdata['usr_authdet']['id'];
                    $this->_candidates($ass,$success_msg);*/
					//redirect('/partner/candidates');
				}
				else
				{
					return false;
					/*echo "NO";
					die;*/
					//User not verified
				     //
					/*echo "<script>
				alert('Error!');
				window.location.href='../candidates';
				</script>";*/
					/*$response_msg=$name."'s Aadhaar has not been matched or Server error!! Try again!!";*/
					/*redirect('/partner/candidates/'+$response_msg);*/
					/*print_r($this->session->userdata['usr_authdet']['id']);
					die;*/
					/*$ass=$this->session->userdata['usr_authdet']['id'];
                    $this->_candidates($ass,$response_msg);*/

				/*	echo "<script>alert('Error!');document.location='../candidates'</script>";*/

					//echo "Sorry! Not Verified! Try again!!";die;

				}
		        /*$result = json_decode($response);*/
		        /*echo $result;*/
		     }

	 public function CallAPIPAN($method, $url,$name)
            {

    //
        $ch = curl_init($url);
        $apiKey=BETTERPLACE_APIKEY;
        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'apiKey:'.$apiKey.'';


        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Send request to Server

        // To save response in a variable from server, set headers;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // Get response
        $response = curl_exec($ch);
        /*
		{"data":{"statusCode":"1","panNumber":"FANPS0047B","panstatus":"E","lastName":"SINHA","firstName":"SAURABH","middleName":null,"panTitle":"Shri","lastUpdateDate":"09/07/2013","message":"Record found in PAN database."},"messages":[]}
        */
        //echo $response;
        $pan_data=json_decode($response);
        /*$candidate_name=strtolower($pan_data->data->firstName." ".$pan_data->data->lastName);
        $candidate_n=strtolower($name);*/

        if($pan_data->data->statusCode==1)
        {
        	return true;
        }
        else
        {
        	return false;
        }


         }
	function change_aadhaar_status($aadhaar)
	{
		$query = $this->db->query("update users.candidates set is_candidate_verified='Verified' where aadhaar_num='".$aadhaar."';");
	}

	function change_id_status($id)
	{
		$query = $this->db->query("update users.candidates set is_candidate_verified='Verified' where id_number='".$id."';");
	}

	function change_verified_status($candidate_id)
	{
		$query = $this->db->query("update users.candidates set is_candidate_verified='Not Verified' where id='".$candidate_id."';");
	}

	 /* Saurabh Sinha's work ends here */


      //Aniket's Work

	function get_associate_role($user_id)
	{


		$query = "Select partner_type_id as partner_role from  users.partners where user_id = ?";

		$partner_role = $this->db->query($query,array($user_id));

		if($partner_role->num_rows())
		{
			return $partner_role->result_array();
		}

		else
		{
			return false;
		}


	}

	function get_candidate_detail($candidate_id=0)
	{
		$cond='';

		if($candidate_id)
			$cond=' where uc.id='.$candidate_id;
		$candidate_rec=$this->db->query("SELECT distinct uc.id as candidate_id, INITCAP(uc.name) as name, uc.mobile, uc.gender_code,md.name as district_name,ms.name as state_name, me.name as education_name,
										mc.name as course_name,relocate_status_code,
										(select COALESCE(NULLIF(ml.name,''),'-NA-') from master.list ml where ml.value::integer= uc.expected_salary_id and ml.code='L0012') as expected_salary,
										(select COALESCE(NULLIF(ml1.name,''),'-NA-') from master.list ml1 where ml1.value::integer= uc.experience_id and ml1.code='L0006') as experience,
										(select COALESCE(NULLIF(string_agg(ml2.name, ', '),''),'-NA-') from master.list ml2 where ml2.value::integer=ANY(uc.language_id) and ml2.code='L0009' group by ml2.code ) as language_name
										from users.candidates uc
										left join master.state ms on ms.id=uc.state_id
										left join master.district md on md.id=uc.district_id
										left join master.education me on me.id=uc.education_id
										left join master.courses mc on mc.id=uc.course_id
										$cond");
		if($candidate_rec->num_rows())
		{
				return $candidate_rec->result_array();
		}
		else
			return false;

	}



	function get_job_detail($job_id=0,$location_id=0)
    {
        $cond='';
        if($job_id)
            $cond.=' and J.job_id='.$job_id;
		if($location_id)
			$cond.=' and J.location_id='.$location_id;
        $job_detail_rec=$this->db->query("SELECT * from job_process.vw_job_detail J where 1=1 $cond");

        if($job_detail_rec->num_rows())
        {
                return $job_detail_rec->result_array();
        }
        else
            return false;

    }

	function get_employers_tracker_new($requestData=array(), $user_id=0)
	{
		$data = array();
		$columns = array(
			0 => null,
			1 => 'customer_name',
			2 => 'interested_count',
			3 => 'profile_submitted_count',
			4 => 'pending_customer_feedback_count',
			5 => 'profile_accepted_count',
			6 => 'profile_rejected_count',
			7 => 'interview_scheduled_count',
			8 => 'interview_attended_count',
			9 => 'interview_unattended_count',
			10 => 'selected_count',
			11 => 'rejected_count',
			12 => 'offer_in_pipeline_count',
			13 => 'offered_count',
			14 => 'offer_accepted_count',
			15 => 'offer_rejected_count',
			16 => 'joined_count',
			17 => 'not_joined_count'
		);

		$total_records=$this->db->query("SELECT COUNT(C.id)::bigint AS total_recs FROM neo_customer.customers AS C ")->row()->total_recs;

		$totalData=$total_records*1;
		$totalFiltered = $totalData;

		$pg=$requestData['start'];
		$limit=$requestData['length'];
		if($limit<0) $limit='all';

		if(!$total_records)
			return array('sEcho'=>'1', "iTotalRecords"=>"0", "iTotalDisplayRecords"=>"0",'aaData'=>array());
		else
		{
			$SearchCondition = '';
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
				$SearchCondition .= " AND COALESCE(C.customer_name,FORMAT('Customer_%s', C.id)) ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' ";

			$SearchQuery = "SELECT      COUNT(C.id)::bigint AS total_filtered
                            FROM        neo_customer.customers AS C
                            WHERE       TRUE
                            $SearchCondition";

			$totalFiltered = $this->db->query($SearchQuery)->row()->total_filtered;

			$FinalQuery = "SELECT 		C.id,
										COALESCE(C.customer_name,FORMAT('Customer_%s', C.id)) AS customer_name,
										SUM(CASE CJ.candidate_status_id WHEN 1 THEN 1 ELSE 0 END) AS interested_count,
										SUM(CASE CJ.candidate_status_id WHEN 2 THEN 1 ELSE 0 END) AS profile_submitted_count,
										SUM(CASE CJ.candidate_status_id WHEN 3 THEN 1 ELSE 0 END) AS pending_customer_feedback_count,
										SUM(CASE CJ.candidate_status_id WHEN 4 THEN 1 ELSE 0 END) AS profile_accepted_count,
										SUM(CASE CJ.candidate_status_id WHEN 5 THEN 1 ELSE 0 END) AS profile_rejected_count,
										SUM(CASE CJ.candidate_status_id WHEN 6 THEN 1 ELSE 0 END) AS interview_scheduled_count,
										SUM(CASE CJ.candidate_status_id WHEN 7 THEN 1 ELSE 0 END) AS interview_attended_count,
										SUM(CASE CJ.candidate_status_id WHEN 8 THEN 1 ELSE 0 END) AS interview_unattended_count,
										SUM(CASE CJ.candidate_status_id WHEN 9 THEN 1 ELSE 0 END) AS selected_count,
										SUM(CASE CJ.candidate_status_id WHEN 10 THEN 1 ELSE 0 END) AS rejected_count,
										SUM(CASE CJ.candidate_status_id WHEN 11 THEN 1 ELSE 0 END) AS offer_in_pipeline_count,
										SUM(CASE CJ.candidate_status_id WHEN 12 THEN 1 ELSE 0 END) AS offered_count,
										SUM(CASE CJ.candidate_status_id WHEN 13 THEN 1 ELSE 0 END) AS offer_accepted_count,
										SUM(CASE CJ.candidate_status_id WHEN 14 THEN 1 ELSE 0 END) AS offer_rejected_count,
										SUM(CASE CJ.candidate_status_id WHEN 15 THEN 1 ELSE 0 END) AS joined_count,
										SUM(CASE CJ.candidate_status_id WHEN 16 THEN 1 ELSE 0 END) AS not_joined_count
							FROM		neo_customer.customers AS C
							LEFT JOIN	neo_job.jobs AS J ON J.customer_id=C.id
							LEFT JOIN 	neo_job.candidates_jobs AS CJ ON CJ.job_id=J.id AND COALESCE(CJ.candidate_id,0)>0
							WHERE		COALESCE(C.id,0) > 0
                            $SearchCondition ";

			$FinalQuery .= " GROUP BY C.id,COALESCE(C.customer_name,FORMAT('Customer_%s', C.id)) ";

			if($columns[$requestData['order'][0]['column']] != '')
				$FinalQuery .=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";
			else
				$FinalQuery .=" ORDER BY customer_name ";

			$FinalQuery .= " LIMIT $limit OFFSET $pg ";

			$result_recs=$this->db->query($FinalQuery);

			$slno=$pg;
			$data = array();
			foreach ($result_recs->result() as $customer)
			{
				$row = array();
				$slno++;
				$row[] = $slno;
				$row[] = $customer->customer_name;
				$row[] = ($customer->interested_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',1)">' . $customer->interested_count . '</a></b></center>' : '<center>'.$customer->interested_count.'</center>';
				$row[] = ($customer->profile_submitted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',2)">' . $customer->profile_submitted_count . '</a></b></center>' : '<center>'.$customer->profile_submitted_count.'</center>';
				$row[] = ($customer->pending_customer_feedback_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',3)">' . $customer->pending_customer_feedback_count . '</a></b></center>' : '<center>'.$customer->pending_customer_feedback_count.'</center>';
				$row[] = ($customer->profile_accepted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',4)">' . $customer->profile_accepted_count . '</a></b></center>' : '<center>'.$customer->profile_accepted_count.'</center>';
				$row[] = ($customer->profile_rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',5)">' . $customer->profile_rejected_count . '</a></b></center>' : '<center>'.$customer->profile_rejected_count.'</center>';
				$row[] = ($customer->interview_scheduled_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',6)">' . $customer->interview_scheduled_count . '</a></b></center>' : '<center>'.$customer->interview_scheduled_count.'</center>';
				$row[] = ($customer->interview_attended_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',7)">' . $customer->interview_attended_count . '</a></b></center>' : '<center>'.$customer->interview_attended_count.'</center>';
				$row[] = ($customer->interview_unattended_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',8)">' . $customer->interview_unattended_count . '</a></b></center>' : '<center>'.$customer->interview_unattended_count.'</center>';
				$row[] = ($customer->selected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',9)">' . $customer->selected_count . '</a></b></center>' : '<center>'.$customer->selected_count.'</center>';
				$row[] = ($customer->rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',10)">' . $customer->rejected_count . '</a></b></center>' : '<center>'.$customer->rejected_count.'</center>';
				$row[] = ($customer->offer_in_pipeline_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',11)">' . $customer->offer_in_pipeline_count . '</a></b></center>' : '<center>'.$customer->offer_in_pipeline_count.'</center>';
				$row[] = ($customer->offered_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',12)">' . $customer->offered_count . '</a></b></center>' : '<center>'.$customer->offered_count.'</center>';
				$row[] = ($customer->offer_accepted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',13)">' . $customer->offer_accepted_count . '</a></b></center>' : '<center>'.$customer->offer_accepted_count.'</center>';
				$row[] = ($customer->offer_rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',14)">' . $customer->offer_rejected_count . '</a></b></center>' : '<center>'.$customer->offer_rejected_count.'</center>';
				$row[] = ($customer->joined_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',15)">' . $customer->joined_count . '</a></b></center>' : '<center>'.$customer->joined_count.'</center>';
				$row[] = ($customer->not_joined_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $customer->id . "'" . ',16)">' . $customer->not_joined_count . '</a></b></center>' : '<center>'.$customer->not_joined_count.'</center>';
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



        function get_center_tracker_new($requestData=array(), $user_id=0)
	{
		$data = array();
		$columns = array(
			0 => null,
			1 => 'C.center_name',
            2 => 'C.interested_count',
			3 => 'C.profile_submitted_count',
			4 => 'C.pending_customer_feedback_count',
			5 => 'C.profile_accepted_count',
			6 => 'C.profile_rejected_count',
			7 => 'C.interview_scheduled_count',
			8 => 'C.interview_attended_count',
			9 => 'C.interview_unattended_count',
			10 => 'C.selected_count',
			11 => 'C.rejected_count',
			12 => 'C.offer_in_pipeline_count',
			13 => 'C.offered_count',
			14 => 'C.offer_accepted_count',
			15 => 'C.offer_rejected_count',
			16 => 'C.joined_count',
			17 => 'C.not_joined_count'
		);

                $search_columns = array(
			'C.center_name',
                        'C.interested_count',
			'C.profile_submitted_count',
			'C.pending_customer_feedback_count',
			'C.profile_accepted_count',
			'C.profile_rejected_count',
			'C.interview_scheduled_count',
			'C.interview_attended_count',
			'C.interview_unattended_count',
			'C.selected_count',
			'C.rejected_count',
			'C.offer_in_pipeline_count',
			'C.offered_count',
			'C.offer_accepted_count',
			'C.offer_rejected_count',
			'C.joined_count',
			'C.not_joined_count'
		);

		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs FROM neo_job.vw_centerwise_application_tracker_data AS C ")->row()->total_recs;

		$totalData=$total_records*1;
		$totalFiltered = $totalData;

		$pg=$requestData['start'];
		$limit=$requestData['length'];
		if($limit<0) $limit='all';

		if(!$total_records)
                    return array('sEcho'=>'1', "iTotalRecords"=>"0", "iTotalDisplayRecords"=>"0",'aaData'=>array());
		else
		{
			$SearchCondition = '';
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
                        {
                            foreach($search_columns as $search_column)
                            {
                                $SearchCondition .=  " $search_column::TEXT ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR";
                            }
                            $SearchCondition = rtrim($SearchCondition, "OR");

                            if ($SearchCondition != '')
                            {
                                $SearchCondition = " AND ($SearchCondition) ";
                            }
                        }

			$SearchQuery = "SELECT COUNT(*)::bigint AS total_filtered
                                        FROM    neo_job.vw_centerwise_application_tracker_data AS C
                                        WHERE   TRUE
                                        $SearchCondition";

			$totalFiltered = $this->db->query($SearchQuery)->row()->total_filtered;

			$FinalQuery = "SELECT *
                                        FROM neo_job.vw_centerwise_application_tracker_data AS C
                                        WHERE TRUE
                                        $SearchCondition ";

			if($columns[$requestData['order'][0]['column']] != '')
                            $FinalQuery .=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";
			else
                            $FinalQuery .=" ORDER BY center_name ";

			$FinalQuery .= " LIMIT $limit OFFSET $pg ";

			$result_recs=$this->db->query($FinalQuery);

			$slno=$pg;
			$data = array();
			foreach ($result_recs->result() as $center)
			{
				$row = array();
				$slno++;
				$row[] = $slno;
				$row[] = $center->center_name;
				$row[] = ($center->interested_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',1)">' . $center->interested_count . '</a></b></center>' : '<center>'.$center->interested_count.'</center>';
				$row[] = ($center->profile_submitted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',2)">' . $center->profile_submitted_count . '</a></b></center>' : '<center>'.$center->profile_submitted_count.'</center>';
				$row[] = ($center->pending_customer_feedback_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',3)">' . $center->pending_customer_feedback_count . '</a></b></center>' : '<center>'.$center->pending_customer_feedback_count.'</center>';
				$row[] = ($center->profile_accepted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',4)">' . $center->profile_accepted_count . '</a></b></center>' : '<center>'.$center->profile_accepted_count.'</center>';
				$row[] = ($center->profile_rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',5)">' . $center->profile_rejected_count . '</a></b></center>' : '<center>'.$center->profile_rejected_count.'</center>';
				$row[] = ($center->interview_scheduled_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',6)">' . $center->interview_scheduled_count . '</a></b></center>' : '<center>'.$center->interview_scheduled_count.'</center>';
				$row[] = ($center->interview_attended_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',7)">' . $center->interview_attended_count . '</a></b></center>' : '<center>'.$center->interview_attended_count.'</center>';
				$row[] = ($center->interview_unattended_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',8)">' . $center->interview_unattended_count . '</a></b></center>' : '<center>'.$center->interview_unattended_count.'</center>';
				$row[] = ($center->selected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',9)">' . $center->selected_count . '</a></b></center>' : '<center>'.$center->selected_count.'</center>';
				$row[] = ($center->rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',10)">' . $center->rejected_count . '</a></b></center>' : '<center>'.$center->rejected_count.'</center>';
				$row[] = ($center->offer_in_pipeline_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',11)">' . $center->offer_in_pipeline_count . '</a></b></center>' : '<center>'.$center->offer_in_pipeline_count.'</center>';
				$row[] = ($center->offered_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',12)">' . $center->offered_count . '</a></b></center>' : '<center>'.$center->offered_count.'</center>';
				$row[] = ($center->offer_accepted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',13)">' . $center->offer_accepted_count . '</a></b></center>' : '<center>'.$center->offer_accepted_count.'</center>';
				$row[] = ($center->offer_rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',14)">' . $center->offer_rejected_count . '</a></b></center>' : '<center>'.$center->offer_rejected_count.'</center>';
				$row[] = ($center->joined_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',15)">' . $center->joined_count . '</a></b></center>' : '<center>'.$center->joined_count.'</center>';
				$row[] = ($center->not_joined_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_center_candidates(' . "'" . $center->center_name . "'" . ',16)">' . $center->not_joined_count . '</a></b></center>' : '<center>'.$center->not_joined_count.'</center>';
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



        function get_candidates_bycenter($center_name,$job_status_id=0)
	{
	    $center_name = strtoupper(trim($center_name));
            $job_det_rec=$this->db->query("SELECT       COALESCE(NULLIF( j.qualification_pack_name,'') , '-NA-' ) as qualification_pack_name,
                                                        j.job_title,
                                                        ca.candidate_name,
                                                        COALESCE(NULLIF( ca.mobile,'') , '-NA-' ) as mobile,
                                                        ca.email,
                                                        COALESCE(upper(btrim(c.center_name)), 'NA'::text) as center_name
                                                        FROM neo_job.candidates_jobs cj
                                                        LEFT JOIN neo.candidates AS CA ON CA.id = CJ.candidate_id
                                                        LEFT JOIN neo.candidate_qp_details qp ON qp.candidate_id = cj.candidate_id
                                                        LEFT JOIN neo_user.centers c ON COALESCE(upper(btrim(c.center_name)), 'NA'::text) = COALESCE(upper(btrim(qp.center_name)), 'NA'::text)
                                                        LEFT JOIN neo_job.jobs j ON j.id = cj.job_id
                                                        WHERE	 COALESCE(cj.candidate_id,0)>0
                                                        AND     CJ.candidate_status_id=$job_status_id
                                                        AND 	COALESCE(upper(btrim(qp.center_name)), 'NA'::text) =?" ,$center_name);

		if($job_det_rec->num_rows()>0)
		{
			$output['status']=true;
			if($job_det_rec->num_rows()>0)
                        {
                            $output['job_detail']=$job_det_rec->result_array();
                        }
			else
                        {
                            $output['job_detail']=array();
                        }
		}
		else{
			$output['status']=false;
                }
		return $output;
	}


        function get_customer_details($company_id=0)
	{
		$employer_det_rec=$this->db->query("SELECT company_name FROM neo_customer.companies WHERE id=?",$company_id);

		$customer_det_rec=$this->db->query("SELECT C.id,
													COALESCE(C.company_name,FORMAT('Customer_%s',C.id)) AS company_name,
													(
													SELECT count(*) AS count
													FROM neo_customer.opportunities o
													WHERE o.company_id = c.id
													) AS opportunity_count,
													COALESCE(NULLIF(t.name,'') , '-NA-' ) as company_type,
													COALESCE(NULLIF(C.company_description,'') , '-NA-' ) as company_description,
													COALESCE(NULLIF(i.name,'') , '-NA-' ) as industry_name,
													COALESCE(NULLIF( B.spoc_name,'') , '-NA-' ) as spoc_name,
													COALESCE(NULLIF(B.spoc_email,'') , '-NA-' ) as spoc_email,
													COALESCE(NULLIF(B.spoc_phone,'') , '-NA-' ) as spoc_phone,      
													COALESCE(NULLIF( LS.name,'') , '-NA-' ) as lead_source_name,
													(SELECT ARRAY_AGG(LU.user_id) FROM neo_customer.leads_users AS LU WHERE LU.lead_id=C.id) AS assigned_user_ids
											FROM		neo_customer.companies AS C
											LEFT JOIN 	neo_master.lead_sources AS LS ON LS.id=C.lead_source_id
											LEFT JOIN   neo_master.industries AS i on i.id=c.industry_id
											LEFT JOIN   neo_master.lead_type AS t ON t.id=C.lead_type_id
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
                                             WHERE c.id=$company_id");

		if($employer_det_rec->num_rows())
		{
			$output['status']=true;
			$output['employer_detail']=$employer_det_rec->row_array();
			if($customer_det_rec->num_rows())
				$output['customer_detail']=$customer_det_rec->result_array();
			else
				$output['customer_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}



	 public function getCenterName() {
		$query = $this->db->select("DISTINCT(center_name)")->order_by('center_name')->get('neo.vw_self_employed_candidate_list');
		return $query->result();
		}
	

	public function getBatchCode() {
	$query = $this->db->select("DISTINCT(batch_code)")->order_by('batch_code')->get('neo.vw_self_employed_candidate_list');
	return $query->result();
	}

	public function getQualificationPack() {
		$query = $this->db->select("DISTINCT(qualification_pack)")->order_by('qualification_pack')->get('neo.vw_self_employed_candidate_list');
		return $query->result();
		}


	public function getEnrollmentId() {
		$query = $this->db->select("DISTINCT(enrollment_no)")->order_by('enrollment_no')->get('neo.vw_self_employed_candidate_list');
		return $query->result();
		}


		function getCompanySpoc($company_id=0)
	    {
		$employer_det_rec=$this->db->query("SELECT company_name FROM neo_customer.companies WHERE id=?",$company_id);

		// $customer_det_rec=$this->db->query("SELECT cb.customer_id,
		// 									initcap(COALESCE(btrim(x.t ->> 'spoc_name'::text), ''::text)) AS spoc_name,
		// 									COALESCE(btrim(x.t ->> 'spoc_email'::text), ''::text) AS spoc_email,
		// 									COALESCE(btrim(x.t ->> 'spoc_phone'::text), ''::text) AS spoc_phone,
		// 									initcap(COALESCE(btrim(x.t ->> 'spoc_designation'::text), ''::text)) AS
		// 									spoc_designation,
		// 									c.is_customer
		// 									FROM neo_customer.customer_branches cb
		// 									LEFT JOIN neo_customer.customers c ON c.id = cb.customer_id
		// 									CROSS JOIN LATERAL json_array_elements(cb.spoc_detail::json) x(t)
		// 									WHERE cb.customer_id=$company_id");


			$customer_det_rec=$this->db->query("WITH UNSP AS (SELECT CB.customer_id, x.spoc_name,x.spoc_phone, x.spoc_email, x.spoc_designation
												FROM neo_customer.customer_branches AS CB, jsonb_to_recordset( CB.spoc_detail::jsonb)
												AS x(\"spoc_name\" text, \"spoc_phone\" text, \"spoc_email\" text, \"spoc_designation\" text)
												WHERE CB.customer_id={$company_id}
												GROUP BY CB.customer_id, x.spoc_phone, x.spoc_name, x.spoc_email, x.spoc_designation 
												HAVING(count(x.spoc_phone)) =1
												)  
												SELECT * FROM UNSP ");

		if($employer_det_rec->num_rows())
		{
			$output['status']=true;
			$output['employer_detail']=$employer_det_rec->row_array();
			if($customer_det_rec->num_rows())
				$output['customer_detail']=$customer_det_rec->result_array();
			else
				$output['customer_detail']=array();
		}
		else
			$output['status']=false;
		return $output;
	}
	


}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Model :: Pramaan Model
 * @author Sangamesh.p@pramaan.in
 **/
class pramaan_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    function _check_module_task_auth($return = false, $group_id = 0)
    {

        $user = $this->session->userdata("usr_authdet");
        if (!$user) {
            redirect("pramaan/home");
            //show_error($message, $status_code, $heading = 'An Error Was Encountered')
            //show_error('You dont have access to this page');
        } else if ($this->uri->segment(1) == '') {
            redirect("pramaan/home");
        } else {
            $user_group_id = $user['user_group_id'];
            if ($return)
                return $user;
            else if ($user_group_id)
                return false;
            else {
                $this->session->set_flashdata('notify_msg', 'You dont have access to this page');
                redirect("pramaan/dashboard");
            }
        }
    }


     /**
     * function for forgot the password
     */
    public function doforget($email = '')
    {

        $this->load->helper('url');
        $q = $this->db->query("select id,email,pwd from users.accounts where email='" . $email . "'");

        $output['status'] = false;
        if ($q->num_rows()) {
            $r = $q->result();
            $user = $r[0];
            $this->resetpassword($user);
            $output['status'] = true;
            $output['info'] = "Password has been reset and has been sent to email id: " . $email;
        } else
            $output['error'] = "!!! The email id you entered is not found on our database ";
        return $output;
    }

     /**
     * function for reset password
     */
    private function resetpassword($user)
    {
        //date_default_timezone_set('GMT');
        $this->load->helper('string');
        $password = random_string('alnum', 8);
        $result = $this->db->query("update users.accounts set pwd=crypt('$password', gen_salt('bf')) where id=" . $user->id);
        $message = "<html><body><br>Hi, " . $user->email;
        $message .= "<br>You have requested a new password for your Pramaan account";
        $message .= "<br>Here is your new password: " . $password;
        $message .= "<br><a href=" . base_url('pramaan/new_password') . ">Reset Password</a></body></html>";
        $this->notify_by_mail($user->email, '', 'Password reset', $message);
        return;
    }

    /**
     * function for get total job ststaics
     */
    function get_total_job_statistics()
    {

        $job_statistics = $this->db->query("SELECT
    	sum(CASE WHEN min_qualification_id =6 or  min_qualification_id=7 THEN 1 else 0 END) as non_metric,
		sum(CASE WHEN min_qualification_id = 1 or min_qualification_id = 2 or min_qualification_id = 3 or min_qualification_id = 4 THEN 1 else 0 END) as metric,
		sum(CASE WHEN min_qualification_id =5 or min_qualification_id = 8 THEN 1 else 0 END) as gradute,
 		sum(CASE WHEN min_experience::float< 0 THEN 1 else 0 END) as notapplicable,
    	sum(CASE WHEN min_experience::float =0 and max_experience::float=0 THEN 1 else 0 END) as fresher,
    	sum(CASE WHEN min_experience::float>=0 and max_experience::float<3 THEN 1 else 0 END) as zero_two_experience,
    	sum(CASE WHEN min_experience::float>=3 and max_experience::float<6 THEN 1 else 0 END) as three_five_experience,
    	sum(CASE WHEN min_experience::float>=6 and max_experience::float>6 THEN 1 else 0 END) as six_above_experience FROM job_process.jobs");
        if ($job_statistics->num_rows()) {
            return $job_statistics->row_array();
        } else
            return false;
    }

    function add_contactus($username = '', $mobile = '', $email = '', $msg = '')
    {

        $output = array("msg" => "");
        $message = "<html><body><br>Person Name: " . $username;
        $message .= "<br>Email: " . $email;
        $message .= "<br>Mobile: " . $mobile;
        $message .= "<br>Message:" . $msg;
        $message .= "</body></html>";
        $upt = array("name" => $username, "mobile" => $mobile, "email" => $email, "message" => $msg);

        $this->db->insert('users.contactus', $upt);

        if ($this->db->affected_rows()) {
            $this->pramaan->notify_by_mail('donotreply@neojobs.in', $email, 'Contact Us', $message);
            return true;
        } else {
            return false;
        }

    }

    public function do_add_sourcing_partner($data)
    {
        $user_group_id = $this->db->query("SELECT value from master.list where code='L0001' and lower(name)=?", strtolower(USP))->row()->value;
        $email = $data['email'];
        $pwd = $data['password'];
        $created_on = date('Y-m-d');
        $phone = $data['phone'];
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");

        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $sourcing_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'partner_type_id' => $data['partner_type_id'],
                'coordinator_id' => $data['coordinator_id']
            );
            $this->db->insert('users.partners', $sourcing_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function do_save_sourcing_partner($data)
    {
        $user_group_id = $this->db->query("SELECT value from master.list
                                    where code='L0001' and lower(name)=?", strtolower('sourcing partner'))->row()->value;
        $email = $data['email'];
        $pwd = $data['password'];
        $phone = $data['phone'];
        $created_on = date('Y-m-d');
        if (intval($data['partner_id']) < 1) {
            $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");
            //$this->db->insert('users.accounts', $user_data);
            if ($this->db->affected_rows()) {
                $user_id = $this->db->insert_id();
                $sourcing_data = array(
                    'user_id' => $user_id,
                    'name' => $data['name'],
                    'partner_type_id' => $data['partner_type_id'],
                    'coordinator_id' => $data['coordinator_id'],
                    'phone' => $phone
                );
                $this->db->insert('users.partners', $sourcing_data);
                if ($this->db->affected_rows())
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
        } else
        {
            $result = $this->db->query("UPDATE users.accounts SET email = ? WHERE id=?",array($email,$data['partner_id']));
            if ($this->db->affected_rows()) {
                $Query = 'UPDATE users.partners ';
                $Query .= 'SET name= ?';
                $Query .= ' WHERE user_id=' . $data['partner_id'];

                $result = $this->db->query($Query, $data['name']);
                if ($this->db->affected_rows())
                    return true;
                else
                    return false;
            } else
                return false;
        }
    }


    function do_add_recruitment_partner($data)
    {
        $user_group_id = $this->db->query("SELECT value from master.list
									where code='L0001' and lower(name)=?", strtolower(URP))->row()->value;

        $email = $data['email'];
        $pwd = $data['password'];
        $created_on = date('Y-m-d');
        $phone= $data['phone'];
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");

        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $recruitment_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'address' => $data['address']
            );
            $this->db->insert('users.recruitment_partners', $recruitment_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function do_add_sourcing_coordinator($data)
    {
        $user_group_id = $this->db->query("SELECT value from master.list
									where code='L0001' and lower(name)=?", strtolower(USC))->row()->value;

        $email = $data['email'];
        $pwd = $data['password'];
        $created_on = date('Y-m-d');
        $phone=$data['phone'];
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");


        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $coordinator_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['parent_id'],
                'department_id' => $data['department_id'],
                'district_id' => $data['district_id'],
                'created_on' => $created_on
            );

            $this->db->insert('users.user_admins', $coordinator_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

  /*  function get_sourcing_partners($district_coordinator_id = 0)
    {
        $Query = "SELECT P.user_id,P.name,P.partner_type_id,L.name AS partner_type_name,P.phone,A.email,P.active_status ";
        $Query .= "FROM users.partners AS P ";
        $Query .= "LEFT JOIN users.accounts AS A ON A.id=P.user_id ";
        $Query .= "LEFT JOIN master.list AS L ON L.code='L0002' AND L.value=P.partner_type_id::TEXT ";
        $Query .= "WHERE P.coordinator_id=?";
        $Query .= "ORDER BY P.name";

        $data = $this->db->query($Query, $district_coordinator_id)->result_array();

        if ($data)
            return $data;
        else
            return array();
    }*/


    public function get_sourcing_partner_data($partner_id)
    {
        $query = "SELECT P.user_id As partner_id , P.name As name ,P.phone As phone ,P.coordinator_id As coordinator_id, P.active_status As active_status,A.email As email FROM users.partners AS P LEFT JOIN users.accounts AS A on P.user_id = A.id  WHERE P.user_id = ?";

        $sourcing_partner_data = $this->db->query($query, $partner_id);
        if ($sourcing_partner_data->num_rows())
            return $sourcing_partner_data->result_array();
    }

    /*function get_sourcing_partners($requestData=array(),$coordinator_id)
	{
		$order_by="";
		$data = array();
		$cond='';
		if($coordinator_id)
			$cond=' where up.coordinator_id='.$coordinator_id;
		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'partner_name',
		    2 => 'registered_count',
		    3 => 'applied_count',
		    4 => 'screened_count',
		    5 => 'scheduled_count',
		    6 => 'shortlisted_count',
		    7 => 'selected_count',
		    8 => 'offered_count',
		    9 => 'joined_count',
		    10 => null
		);

		$column_search = array("up.name"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs FROM users.partners up $cond" )->row()->total_recs;

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
    /*  $sWhere = "";
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
	            if($coordinator_id)
	            $sWhere .=' and up.coordinator_id='.$coordinator_id;
	        }
	        else
	        {
	        	$sWhere = $cond;
	        }


	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.partners up
								        			$sWhere")->row()->total_filtered;

			$sourcing_partner_recs=$this->db->query("SELECT up.user_id,up.name as partner_name,up.partner_type_id,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(id) FROM users.candidates WHERE referer_id=up.user_id)
													ELSE (SELECT COUNT(id) FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id))
													END AS registered_count,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id=up.user_id) AND status_id = 1)
													ELSE (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id)) AND status_id = 1)
													END AS applied_count,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id=up.user_id) AND status_id = 2)
													ELSE (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id)) AND status_id = 2)
													END AS screened_count,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id=up.user_id) AND status_id = 3)
													ELSE (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id)) AND status_id = 3)
													END AS scheduled_count,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id=up.user_id) AND status_id = 4)
													ELSE (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id)) AND status_id = 4)
													END  AS shortlisted_count,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id=up.user_id) AND status_id = 5)
													ELSE (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id)) AND status_id = 5)
													END AS selected_count,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id=up.user_id) AND status_id = 6)
													ELSE (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id)) AND status_id = 6)
													END  AS offered_count,
													CASE WHEN up.partner_type_id<=1
													THEN (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id=up.user_id) AND status_id = 7)
													ELSE (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE candidate_id in(SELECT id FROM users.candidates WHERE referer_id in(select user_id from users.associates where partner_id=up.user_id)) AND status_id = 7)
													END  AS joined_count
													from users.partners up
								        			$sWhere
													$order_by limit $limit OFFSET $pg");*/
    //$totalFiltered=$sourcing_partner_recs->num_rows();
    /*$slno=$pg;
			$data = array();
			$partner_status=array('1'=>'IP','2'=>'OP','3','CC');
			foreach ($sourcing_partner_recs->result() as $sourcingPartners)
			{  // preparing an array
				$row=array();
				$partner_type=$partner_status[$sourcingPartners->partner_type_id];
				$slno++;
				$row[] = $slno;
				if($sourcingPartners->partner_type_id==2)
					$row[] = '<a href="javascript:void(0)" title="Associate desk" onclick="associate_desk('."'".$sourcingPartners->user_id."'".')">'.'<span class='.$partner_type.'>'.$sourcingPartners->partner_name.'</span>'.'</a>';
				else
					$row[] = '<a href="javascript:void(0)" title="Partner desk" onclick="partner_desk('."'".$sourcingPartners->user_id."'".')">'.'<span class='.$partner_type.'>'.$sourcingPartners->partner_name.'</span>'.'</a>';

				$row[] = ($sourcingPartners->registered_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',0)">'.$sourcingPartners->registered_count.'</b></a>':$sourcingPartners->registered_count;
				$row[] = ($sourcingPartners->applied_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',1)">'.$sourcingPartners->applied_count.'</b></a>':$sourcingPartners->applied_count;
				$row[] = ($sourcingPartners->screened_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',2)">'.$sourcingPartners->screened_count.'</b></a>':$sourcingPartners->screened_count;
				$row[] = ($sourcingPartners->scheduled_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',3)">'.$sourcingPartners->scheduled_count.'</b></a>':$sourcingPartners->scheduled_count;
				$row[] = ($sourcingPartners->shortlisted_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',4)">'.$sourcingPartners->shortlisted_count.'</b></a>':$sourcingPartners->shortlisted_count;
				$row[] = ($sourcingPartners->selected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',5)">'.$sourcingPartners->selected_count.'</b></a>':$sourcingPartners->selected_count;
				$row[] = ($sourcingPartners->offered_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',6)">'.$sourcingPartners->offered_count.'</b></a>':$sourcingPartners->offered_count;
				$row[] = ($sourcingPartners->joined_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$sourcingPartners->user_id."'".',7)">'.$sourcingPartners->joined_count.'</b></a>':$sourcingPartners->joined_count;
				$data[] = $row;
			}

			//  $data[] = $employee_recs->result_array();
			$sourcing_data_recs = array(
		            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		            "recordsTotal"    => intval( $totalData ),  // total number of records
		            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		            "data"            => $data   // total data array
		            );
			return $sourcing_data_recs;
		}
	}*/

    //deprecated
    function get_recruitment_partners($requestData = array())
    {
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'recruitment_partner_name',
            2 => 'email',
            3 => 'phone',
            4 => 'employers_count'
        );

        $column_search = array("rp.name", "rp.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.recruitment_partners"
        )->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }


            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.recruitment_partners rp
								        			left join users.accounts ua on ua.id=rp.user_id
								        			$sWhere")->row()->total_filtered;

            $recruitment_partners_recs = $this->db->query("SELECT rp.user_id, rp.name as recruitment_partner_name,rp.address, rp.phone,ua.email,
															(SELECT COUNT(*) FROM users.employers WHERE recruitment_partner_id=rp.user_id) AS employers_count
														 from users.recruitment_partners rp
														 left join users.accounts ua on ua.id=rp.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");
            //$totalFiltered=$recruitment_partners_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($recruitment_partners_recs->result() as $recruitmentPartner) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="Partner desk" onclick="partner_desk(' . "'" . $recruitmentPartner->user_id . "'" . ')">' . $recruitmentPartner->recruitment_partner_name . '</a>';
                $row[] = $recruitmentPartner->email;
                $row[] = $recruitmentPartner->phone;
                $row[] = ($recruitmentPartner->employers_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_employer(' . "'" . $recruitmentPartner->user_id . "'" . ')">' . $recruitmentPartner->employers_count . '</b></a>' : $recruitmentPartner->employers_count;
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $recruitment_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $recruitment_data_recs;
        }

    }

    /*function get_sourcing_coordinators($requestData=array(),$sourcing_manager_id=0)
	{
		$order_by="";
		$cond='';
		$data = array();
		$columns = array(
		// datatable column index  => database column name
		    0=>null,
		    1 => 'coordinator_name',
		    2 => 'email',
		    3 => 'phone',
		    4 => 'partners_count'
		);
		if($sourcing_manager_id)
			$cond=" where sc.sourcing_manager_id=".$sourcing_manager_id;
		$column_search = array("sc.name","sc.phone","ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
		$total_records=$this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.sourcing_coordinator sc $cond"
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
	            if($sourcing_manager_id)
				$sWhere .=" and sc.sourcing_manager_id=".$sourcing_manager_id;
	        }
	        else
			$sWhere =$cond;

	        $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.sourcing_coordinator sc
								        			left join users.accounts ua on ua.id=sc.user_id
								        			$sWhere")->row()->total_filtered;

			$sourcing_coordinators_recs=$this->db->query("SELECT sc.user_id, sc.name as coordinator_name,sc.address, sc.phone,ua.email,
														(SELECT COUNT(*) FROM users.partners WHERE coordinator_id=sc.user_id) AS partners_count
														from users.sourcing_coordinator sc
														left join users.accounts ua on ua.id=sc.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");
			//$totalFiltered=$sourcing_coordinators_recs->num_rows();
			$slno=$pg;
			$data = array();
			foreach ($sourcing_coordinators_recs->result() as $sourcingCoordinator)
			{  // preparing an array
				$row=array();
				$slno++;
				$row[] = $slno;
				$row[] = '<a href="javascript:void(0)" title="Partner Desk" onclick="partner_desk('."'".$sourcingCoordinator->user_id."'".')">'.$sourcingCoordinator->coordinator_name.'</a>';
				$row[] = $sourcingCoordinator->email;
				$row[] = $sourcingCoordinator->phone;
				$row[] = ($sourcingCoordinator->partners_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_partner('."'".$sourcingCoordinator->user_id."'".')">'.$sourcingCoordinator->partners_count.'</b></a>':$sourcingCoordinator->partners_count;
				$data[] = $row;
			}

			//  $data[] = $employee_recs->result_array();
			$sourcing_coordinator_data_recs = array(
		            "draw"            => intval( $requestData['draw'] ),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
		            "recordsTotal"    => intval( $totalData ),  // total number of records
		            "recordsFiltered" => intval( $totalFiltered ), // total number of records after searching, if there is no searching then totalFiltered = totalData
		            "data"            => $data   // total data array
		            );
			return $sourcing_coordinator_data_recs;
		}

	}
*/
    function get_employers_by_partner($rec_partner_id = 0)
    {
        $rec_partner = $this->db->query("SELECT name,phone,address
										FROM users.recruitment_partners
										where user_id=?", $rec_partner_id);
        $data['status'] = false;
        if ($rec_partner->num_rows()) {
            $data['status'] = true;
            $data['rec_partner'] = $rec_partner->row_array();

            $rec_employers = $this->db->query("SELECT ue.user_id,ue.name,ue.phone,COALESCE(NULLIF(ue.address,'') , '' ) as address,ms.name as sector
											FROM users.employers ue
											LEFT JOIN master.sector ms on ms.id=ue.sector_id
											WHERE recruitment_partner_id=?", $rec_partner_id);
            if ($rec_employers->num_rows()) {
                $data['employers_details'] = $rec_employers->result_array();
            }

        }
        return $data;
    }

    function get_employers_by_id($employer_id = 0)
    {


        $rec_employers = $this->db->query("SELECT ue.*,ua.email FROM users.employers ue
											left join users.accounts ua on ua.id=ue.user_id
											WHERE ue.user_id=?", $employer_id);
        if ($rec_employers->num_rows()) {
            $data['employers_details'] = $rec_employers->row_array();
        }

        return $data;
    }

    function get_partners_by_coordinator($coordinator_id = 0)
    {
        $rec_coordinator = $this->db->query("SELECT name,phone,address
										   FROM users.sourcing_coordinator
										   WHERE user_id=?", $coordinator_id);
        $data['status'] = false;
        if ($rec_coordinator->num_rows()) {
            $data['status'] = true;
            $data['rec_coordinator'] = $rec_coordinator->row_array();

            $rec_partners = $this->db->query("SELECT up.user_id,up.name as partner_name, ml.name as partner_type, up.created_on,up.phone
											FROM users.partners up
											LEFT JOIN master.list ml on ml.value::integer=up.partner_type_id and ml.code='L0002'
											WHERE coordinator_id=?", $coordinator_id);
            if ($rec_partners->num_rows()) {
                $data['partners_details'] = $rec_partners->result_array();
            }

        }
        return $data;
    }

    function get_qualification_pack($requestData = array(), $user_id)
    {
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'qualification_name',
            2 => 'sector_name',
            3 => 'applied_count',
            4 => 'screened_count',
            5 => 'scheduled_count',
            6 => 'shortlisted_count',
            7 => 'selected_count',
            8 => 'offered_count',
            9 => 'joined_count',
            10 => null
        );
        $column_search = array("qp.name", "ms.name"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM master.qualification_pack qp
											LEFT join master.sector ms on ms.id=qp.sector_id"
        )->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY qualification_name ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====


        $user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];


        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            //$strUserRoleCondition =  "AND VW.partner_id=$UserId";

            $strUserRoleCondition = '';

            switch ($user_group_id) {
                case 5: //Sourcing Partner
                    $strUserRoleCondition .= ' AND VW.associate_id=' . $user_id . ' ';
                    break;

                case 3: //Sourcing Partner
                    $strUserRoleCondition .= ' AND VW.partner_id=' . $user_id . ' ';
                    break;

                case 22: //Sourcing Partner
                    $strUserRoleCondition .= ' AND VW.coordinator_id=' . $user_id . ' ';
                    break;
                case 21: //Sourcing Partner
                    $strUserRoleCondition .= ' AND VW.state_manager_id=' . $user_id . ' ';
                    break;

                case 20: //Sourcing Partner
                    $strUserRoleCondition .= ' AND VW.regional_manager_id=' . $user_id . ' ';
                    break;
                case 9: //Sourcing Partner
                    $strUserRoleCondition .= ' AND VW.sourcing_head_id=' . $user_id . ' ';
                    break;

                case 10: //Sourcing Partner
                    $strUserRoleCondition .= ' AND VW.sourcing_head_id=' . $user_id . ' ';
                    break;

                default:
            }

            $strQuery = "SELECT 	QP.id AS id,
									QP.name AS qualification_name,
									S.id AS sector_id,
									S.name AS sector_name,
									(SELECT COUNT(VW.id) FROM users.vw_candidate_jobs AS VW WHERE VW.qualification_pack_id=QP.id AND VW.status_id=1 $strUserRoleCondition) AS applied_count,
									(SELECT COUNT(VW.id) FROM users.vw_candidate_jobs AS VW WHERE VW.qualification_pack_id=QP.id AND VW.status_id=2 $strUserRoleCondition) AS screened_count,
									(SELECT COUNT(VW.id) FROM users.vw_candidate_jobs AS VW WHERE VW.qualification_pack_id=QP.id AND VW.status_id=3 $strUserRoleCondition) AS scheduled_count,
									(SELECT COUNT(VW.id) FROM users.vw_candidate_jobs AS VW WHERE VW.qualification_pack_id=QP.id AND VW.status_id=4 $strUserRoleCondition) AS shortlisted_count,
									(SELECT COUNT(VW.id) FROM users.vw_candidate_jobs AS VW WHERE VW.qualification_pack_id=QP.id AND VW.status_id=5 $strUserRoleCondition) AS selected_count,
									(SELECT COUNT(VW.id) FROM users.vw_candidate_jobs AS VW WHERE VW.qualification_pack_id=QP.id AND VW.status_id=6 $strUserRoleCondition) AS offered_count,
									(SELECT COUNT(VW.id) FROM users.vw_candidate_jobs AS VW WHERE VW.qualification_pack_id=QP.id AND VW.status_id=7 $strUserRoleCondition) AS joined_count
							FROM	master.qualification_pack AS QP
							LEFT JOIN master.sector AS S ON S.id=Qp.sector_id ";
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
            }


            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM master.qualification_pack qp
													LEFT join master.sector ms on ms.id=qp.sector_id
								        			$sWhere")->row()->total_filtered;

            $qualification_pack_recs = $this->db->query("$strQuery $sWhere
														$order_by limit $limit OFFSET $pg");
            //$totalFiltered=$qualification_pack_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($qualification_pack_recs->result() as $qualificationPacks) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $qualificationPacks->qualification_name;
                $row[] = $qualificationPacks->sector_name;
                $row[] = ($qualificationPacks->applied_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $qualificationPacks->id . "'" . ',1)">' . $qualificationPacks->applied_count . '</b></a>' : $qualificationPacks->applied_count;
                $row[] = ($qualificationPacks->screened_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $qualificationPacks->id . "'" . ',2)">' . $qualificationPacks->screened_count . '</b></a>' : $qualificationPacks->screened_count;
                $row[] = ($qualificationPacks->scheduled_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $qualificationPacks->id . "'" . ',3)">' . $qualificationPacks->scheduled_count . '</b></a>' : $qualificationPacks->scheduled_count;
                $row[] = ($qualificationPacks->shortlisted_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $qualificationPacks->id . "'" . ',4)">' . $qualificationPacks->shortlisted_count . '</b></a>' : $qualificationPacks->shortlisted_count;
                $row[] = ($qualificationPacks->selected_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $qualificationPacks->id . "'" . ',5)">' . $qualificationPacks->selected_count . '</b></a>' : $qualificationPacks->selected_count;
                $row[] = ($qualificationPacks->offered_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $qualificationPacks->id . "'" . ',6)">' . $qualificationPacks->offered_count . '</b></a>' : $qualificationPacks->offered_count;
                $row[] = ($qualificationPacks->joined_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $qualificationPacks->id . "'" . ',7)">' . $qualificationPacks->joined_count . '</b></a>' : $qualificationPacks->joined_count;
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $qualificationPacks_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $qualificationPacks_data_recs;
        }
    }


    function do_get_partner_candidates($user_id = 0, $job_status_id = 0)
    {
        $output = array("status" => false, "candidate_data" => '', 'ttl_record' => 0);
        $cond = "";
        $param = array();

        if ($user_id) {
            $partner_type_id = $this->db->query("select partner_type_id from users.partners where user_id=?", $user_id)->row()->partner_type_id;
            if ($partner_type_id == 2)
                $user_id = $this->db->query("select user_id from users.associates where partner_id=?", $user_id)->row()->user_id;
            $cond .= " and uc.referer_id=? ";
            $param[] = $user_id;
        }
        if ($job_status_id) {
            $cond .= " and cj.status_id=? ";
            $param[] = $job_status_id;
        }


        $main_sql = "SELECT uc.id,uc.name as candidate_name,uc.mobile, uc.gender_code,COALESCE(NULLIF(uc.address,'') , '-' ) as address,ml.name as expected_salary,me.name as education
					from users.candidates uc
					left join job_process.candidate_jobs cj on cj.candidate_id=uc.id
					left join master.education me on me.id=uc.education_id
					left join master.list ml on ml.value::integer=uc.expected_salary_id and ml.code='L0012'
					where 1=1 $cond";


        $candidate_list_res = $this->db->query($main_sql, $param);
        if ($candidate_list_res->num_rows()) {
            $output['status'] = true;
            $output['ttl_record'] = $candidate_list_res->num_rows();
            $output['candidate_data'] = $candidate_list_res->result_array();
        }

        return $output;
    }

    public function do_job_apply($data)
    {
        $this->db->insert('job_process.applied_jobs', $data);
        return $this->db->insert_id();
    }

    /*
	public function do_get_regions()
	{
		$regions_rec=$this->db->query("SELECT id,region_name from master.regions order by region_name");
		if($regions_rec->num_rows())
		{
			return $regions_rec->result_array();
		}
		else
		{
			return false;
		}
	}
*/
    public function do_get_state($region_id=0)
    {
        $cond='';
        if($region_id)
        {
            $cond=' and region_id=ANY('."'".$region_id."'".'::int[])';
        }

        $state_rec = $this->db->query("SELECT id,name from master.state WHERE 1=1 $cond ORDER BY name");

        if ($state_rec->num_rows())
        {
            return $state_rec->result_array();
        }
        else
        {
            return false;
        }
    }

    /*
	public function do_get_district($state_id)
	{
		$district_rec=$this->db->query("SELECT id,name from master.district
										WHERE state_id=? order by name",$state_id);
		if($district_rec->num_rows())
		{
			return $district_rec->result_array();
		}
		else
		{
			return false;
		}
	}
*/
    public function do_get_qualification()
    {
        $qualification_rec = $this->db->query("SELECT id,name from master.education order by name");
        if ($qualification_rec->num_rows()) {
            return $qualification_rec->result_array();
        } else {
            return false;
        }
    }

    public function do_get_course($education_id)
    {
        $course_rec = $this->db->query("SELECT id,name from master.courses
										      WHERE education_id=? order by sort_order", $education_id);
        if ($course_rec->num_rows()) {
            return $course_rec->result_array();
        } else {
            return false;
        }
    }

    public function do_get_experience()
    {
        $experience_rec = $this->db->query("SELECT value,name from master.list
										where code='L0006'
										order by sort_order");
        if ($experience_rec->num_rows()) {
            return $experience_rec->result_array();
        } else {
            return false;
        }
    }

    public function do_get_salary()
    {
        $salary_rec = $this->db->query("SELECT value,name from master.list
										where code='L0012'
										order by sort_order");
        if ($salary_rec->num_rows()) {
            return $salary_rec->result_array();
        } else {
            return false;
        }
    }

    public function do_get_id_types()
    {
        $id_type_rec = $this->db->query("SELECT value,name from master.list
										where code='L0008' and value::integer!=1
										order by sort_order");
        if ($id_type_rec->num_rows()) {
            return $id_type_rec->result_array();
        } else {
            return false;
        }
    }

    public function get_candidates_by_qp($qp_id = 0, $job_status_id = 0)   //changed to view
    {
        $QualificationPackName="";
        $qp_rec = $this->db->query("SELECT FORMAT('%s (%s)',QP.name,QP.code) AS qualification_pack_name FROM neo_master.qualification_packs AS QP WHERE qp.id=$qp_id");
        if ($qp_rec->num_rows()) $QualificationPackName = $qp_rec->row()->qualification_pack_name;

        $CandidateJobStatusName = "";
        $candidate_job_status_rec = $this->db->query("SELECT name AS candidate_job_status_name FROM neo_master.candidate_statuses WHERE id=$job_status_id");
        if ($candidate_job_status_rec->num_rows()) $CandidateJobStatusName = $candidate_job_status_rec->row()->candidate_job_status_name;

        $candidate_det_rec = $this->db->query("SELECT 		CJ.id AS job_applied_id,
                                                            CJ.candidate_id,
                                                            COALESCE(NULLIF(J.job_title,'') , '-NA-' ) as job_title,
                                                            COALESCE(NULLIF(J.job_description,'') , '-NA-' ) as job_desc,
                                                            COALESCE(NULLIF(CUST.customer_name,'') , '-NA-' ) as customer_name,
                                                            COALESCE(NULLIF(ced.education_name ,'') , '-NA-' ) as education_name,
                                                            COALESCE(NULLIF(C.candidate_name,'') , '-NA-' ) as candidate_name,
                                                            COALESCE(NULLIF(C.mobile ,'') , '-NA-' ) as mobile,
                                                            COALESCE(NULLIF(G.name,'') , '-NA-' ) as gender_name,
                                                            COALESCE(NULLIF(D.name,''),'-NA') as location_name,
                                                            CJ.candidate_status_id,
                                                            CJS.name As candidate_job_status_name
                                                FROM		neo_master.qualification_packs AS QP
                                                LEFT JOIN	neo_master.sectors AS S ON S.id = QP.sector_id
                                                LEFT JOIN	neo_job.jobs AS J ON J.qualification_pack_id=QP.id AND COALESCE(J.customer_id,0)>0
                                                LEFT JOIN 	neo_job.candidates_jobs AS CJ ON CJ.job_id=J.id AND COALESCE(CJ.candidate_id,0)>0
                                                LEFT JOIN	neo_customer.customers AS CUST ON CUST.id=J.customer_id
                                                LEFT JOIN	neo.candidates AS C ON C.id= CJ.candidate_id
                                                LEFT JOIN	neo_master.genders AS G ON G.id=C.gender_id
                                                LEFT JOIN	neo.candidate_education_details AS CED ON CED.id=J.education_id
                                                LEFT JOIN	neo_master.candidate_statuses AS CJS ON CJS.id=CJ.candidate_status_id
                                                LEFT JOIN neo_master.districts AS D ON D.id=j.district_id
                                                WHERE		J.qualification_pack_id=?
                                                AND		CJ.candidate_status_id=?
                                                AND         COALESCE(J.customer_id,0)>0
                                                ORDER BY    candidate_name, job_title", array($qp_id,  $job_status_id));

        if ($candidate_det_rec->num_rows())
        {
            $output['status'] = true;
            $output['qualification_pack_name'] = $QualificationPackName;
            $output['candidate_job_status_name'] = $CandidateJobStatusName;
            $output['candidate_detail'] = $candidate_det_rec->result_array();
        } else
            $output['status'] = false;
        return $output;
    }

    /**
     * @author Sangamesh <sangamesh.p@pramaan.in>
     * Date: 20/12/2016
     * function to get all employers list datatable
     *
     */
    //deprecated  present
    function get_all_employers_list($requestData = array(), $user_group_id = 0, $user_id = 0)
    {
        $order_by = "";
        $cond = '';
        $data = array();

        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'ue.name',
            2 => 'job_count',
            3 => 'applied_count',
            4 => 'screened_count',
            5 => 'scheduled_count',
            6 => 'shortlisted_count',
            7 => 'selected_count',
            8 => 'offered_count',
            9 => 'joined_count'
        );

        if ($user_group_id > 1) {
            if ($user_group_id == 13)                //bd admin
                $cond = ' and ad.user_id=' . $user_id;
            if ($user_group_id == 12)                //bd head
                $cond = ' and hd.user_id=' . $user_id;
            if ($user_group_id == 11)            //bd manager
                $cond = ' and mr.user_id=' . $user_id;
            if ($user_group_id == 8)            //bd coordinator
                $cond = ' and cr.user_id=' . $user_id;
            if ($user_group_id == 18)            //bd executive
                $cond = ' and ex.user_id=' . $user_id;
        }

        $column_search = array("ue.name"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
							        	 FROM users.employers ue
								        	JOIN(select user_id ,name as executive_name,parent_id from users.user_admins where department_id=4) ex on ex.user_id=ue.recruitment_partner_id
											JOIN(select user_id ,name as coordinator_name,parent_id from users.user_admins where department_id=4) cr on cr.user_id=ex.parent_id
											JOIN(select user_id ,name as manager_name,parent_id from users.user_admins where department_id=4) mr on mr.user_id=cr.parent_id
											JOIN(select user_id ,name as head_name,parent_id from users.user_admins where department_id=4) hd on hd.user_id=mr.parent_id
											JOIN(select user_id ,name as admin_name,parent_id from users.user_admins where department_id=4) ad on ad.user_id=hd.parent_id
											where 1=1 $cond")->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++)
                {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($user_group_id > 1)
                    $sWhere .= $cond;
            } else
                $sWhere = "WHERE 1=1 " . $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM users.employers ue
							        			JOIN(select user_id ,name as executive_name,parent_id from users.user_admins where department_id=4) ex on ex.user_id=ue.recruitment_partner_id
												JOIN(select user_id ,name as coordinator_name,parent_id from users.user_admins where department_id=4) cr on cr.user_id=ex.parent_id
												JOIN(select user_id ,name as manager_name,parent_id from users.user_admins where department_id=4) mr on mr.user_id=cr.parent_id
												JOIN(select user_id ,name as head_name,parent_id from users.user_admins where department_id=4) hd on hd.user_id=mr.parent_id
												JOIN(select user_id ,name as admin_name,parent_id from users.user_admins where department_id=4) ad on ad.user_id=hd.parent_id
							        			$sWhere")->row()->total_filtered;

            $employers_recs = $this->db->query("SELECT ue.user_id as employer_id,ue.name as employer_name,
												(SELECT COUNT(id) FROM job_process.jobs WHERE employer_id=ue.user_id) AS job_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 1) AS applied_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 2) AS screened_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 3) AS scheduled_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 4) AS shortlisted_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 5) AS selected_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 6) AS offered_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 7) AS joined_count
												FROM users.employers ue
												JOIN(select user_id ,name as executive_name,parent_id from users.user_admins where department_id=4) ex on ex.user_id=ue.recruitment_partner_id
												JOIN(select user_id ,name as coordinator_name,parent_id from users.user_admins where department_id=4) cr on cr.user_id=ex.parent_id
												JOIN(select user_id ,name as manager_name,parent_id from users.user_admins where department_id=4) mr on mr.user_id=cr.parent_id
												JOIN(select user_id ,name as head_name,parent_id from users.user_admins where department_id=4) hd on hd.user_id=mr.parent_id
												JOIN(select user_id ,name as admin_name,parent_id from users.user_admins where department_id=4) ad on ad.user_id=hd.parent_id
											$sWhere
											$order_by limit $limit OFFSET $pg");
            $slno = $pg;
            $data = array();
            foreach ($employers_recs->result() as $employers) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $employers->employer_name;
                $row[] = ($employers->job_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs(' . "'" . $employers->employer_id . "'" . ')">' . $employers->job_count . '</b></a>' : $employers->job_count;
                $row[] = ($employers->applied_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',1)">' . $employers->applied_count . '</b></a>' : $employers->applied_count;
                $row[] = ($employers->screened_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',2)">' . $employers->screened_count . '</b></a>' : $employers->screened_count;
                $row[] = ($employers->scheduled_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',3)">' . $employers->scheduled_count . '</b></a>' : $employers->scheduled_count;
                $row[] = ($employers->shortlisted_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',4)">' . $employers->shortlisted_count . '</b></a>' : $employers->shortlisted_count;
                $row[] = ($employers->selected_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',5)">' . $employers->selected_count . '</b></a>' : $employers->selected_count;
                $row[] = ($employers->offered_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',6)">' . $employers->offered_count . '</b></a>' : $employers->offered_count;
                $row[] = ($employers->joined_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',7)">' . $employers->joined_count . '</b></a>' : $employers->joined_count;

                //add html for action
                $data[] = $row;
            }
            //  $data[] = $employee_recs->result_array();
            $employers_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $employers_data_recs;
        }
    }

    /**
     * @author Sangamesh <sangamesh.p@pramaan.in>
     * Date: 09/feb/2017
     * function to get all assigned employers list datatable
     */
    function get_all_assigned_employers_list($requestData = array(), $user_group_id = 0, $user_id = 0)
    {
        $order_by = "";
        $cond = '';
        $data = array();

        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'ue.name',
            2 => 'job_count',
            3 => 'applied_count',
            4 => 'screened_count',
            5 => 'scheduled_count',
            6 => 'shortlisted_count',
            7 => 'selected_count',
            8 => 'offered_count',
            9 => 'joined_count'
        );

        if ($user_group_id > 1) {
            if ($user_group_id == 14)                //rs admin
                $cond = ' and ad.user_id=' . $user_id;
            if ($user_group_id == 15)                //rs head
                $cond = ' and hd.user_id=' . $user_id;
            if ($user_group_id == 16)            //rs manager
                $cond = ' and mr.user_id=' . $user_id;
            if ($user_group_id == 17)            //rs coordinator
                $cond = ' and cr.user_id=' . $user_id;
            if ($user_group_id == 19)            //rs executive
                $cond = ' and ex.user_id=' . $user_id;
        }

        $column_search = array("ue.name"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
							        	 FROM users.employers ue
											JOIN users.recruitment_support_assignment rsa on rsa.employer_id=ue.user_id
											JOIN(select user_id ,name as executive_name,parent_id from users.user_admins where department_id=3) ex on ex.user_id=rsa.user_id
											JOIN(select user_id ,name as coordinator_name,parent_id from users.user_admins where department_id=3) cr on cr.user_id=ex.parent_id
											JOIN(select user_id ,name as manager_name,parent_id from users.user_admins where department_id=3) mr on mr.user_id=cr.parent_id
											JOIN(select user_id ,name as head_name,parent_id from users.user_admins where department_id=3) hd on hd.user_id=mr.parent_id
											JOIN(select user_id ,name as admin_name,parent_id from users.user_admins where department_id=3) ad on ad.user_id=hd.parent_id
										where 1=1 $cond")->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($user_group_id > 1)
                    $sWhere .= $cond;
            } else
                $sWhere = "WHERE 1=1 " . $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM users.employers ue
												JOIN users.recruitment_support_assignment rsa on rsa.employer_id=ue.user_id
												JOIN(select user_id ,name as executive_name,parent_id from users.user_admins where department_id=3) ex on ex.user_id=rsa.user_id
												JOIN(select user_id ,name as coordinator_name,parent_id from users.user_admins where department_id=3) cr on cr.user_id=ex.parent_id
												JOIN(select user_id ,name as manager_name,parent_id from users.user_admins where department_id=3) mr on mr.user_id=cr.parent_id
												JOIN(select user_id ,name as head_name,parent_id from users.user_admins where department_id=3) hd on hd.user_id=mr.parent_id
												JOIN(select user_id ,name as admin_name,parent_id from users.user_admins where department_id=3) ad on ad.user_id=hd.parent_id
							        			$sWhere")->row()->total_filtered;

            $employers_recs = $this->db->query("SELECT ue.user_id as employer_id,ue.name as employer_name, ue.phone,executive_name,coordinator_name,manager_name,head_name,admin_name,
												(SELECT COUNT(id) FROM job_process.jobs WHERE employer_id=ue.user_id) AS job_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 1) AS applied_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 2) AS screened_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 3) AS scheduled_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 4) AS shortlisted_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 5) AS selected_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 6) AS offered_count,
												(SELECT COUNT(DISTINCT candidate_id) FROM job_process.candidate_jobs WHERE job_id in(select id from job_process.jobs where employer_id=ue.user_id) AND status_id = 7) AS joined_count
												FROM users.employers ue
												JOIN users.recruitment_support_assignment rsa on rsa.employer_id=ue.user_id
												JOIN(select user_id ,name as executive_name,parent_id from users.user_admins where department_id=3) ex on ex.user_id=rsa.user_id
												JOIN(select user_id ,name as coordinator_name,parent_id from users.user_admins where department_id=3) cr on cr.user_id=ex.parent_id
												JOIN(select user_id ,name as manager_name,parent_id from users.user_admins where department_id=3) mr on mr.user_id=cr.parent_id
												JOIN(select user_id ,name as head_name,parent_id from users.user_admins where department_id=3) hd on hd.user_id=mr.parent_id
												JOIN(select user_id ,name as admin_name,parent_id from users.user_admins where department_id=3) ad on ad.user_id=hd.parent_id
											$sWhere
											$order_by limit $limit OFFSET $pg");
            $slno = $pg;
            $data = array();
            foreach ($employers_recs->result() as $employers) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = 'Name: ' . $employers->employer_name . '<br><small>' . 'Phone : ' . $employers->phone . '</small>';
                $row[] = ($employers->job_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_jobs(' . "'" . $employers->employer_id . "'" . ')">' . $employers->job_count . '</b></a>' : $employers->job_count;
                $row[] = ($employers->applied_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',1)">' . $employers->applied_count . '</b></a>' : $employers->applied_count;
                $row[] = ($employers->screened_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',2)">' . $employers->screened_count . '</b></a>' : $employers->screened_count;
                $row[] = ($employers->scheduled_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',3)">' . $employers->scheduled_count . '</b></a>' : $employers->scheduled_count;
                $row[] = ($employers->shortlisted_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',4)">' . $employers->shortlisted_count . '</b></a>' : $employers->shortlisted_count;
                $row[] = ($employers->selected_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',5)">' . $employers->selected_count . '</b></a>' : $employers->selected_count;
                $row[] = ($employers->offered_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',6)">' . $employers->offered_count . '</b></a>' : $employers->offered_count;
                $row[] = ($employers->joined_count) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates(' . "'" . $employers->employer_id . "'" . ',7)">' . $employers->joined_count . '</b></a>' : $employers->joined_count;

                //add html for action
                $data[] = $row;
            }
            //  $data[] = $employee_recs->result_array();
            $employers_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $employers_data_recs;
        }
    }

    /**
     * @author Sangamesh <sangamesh.p@pramaan.in>
     * function to get candidate list
     */
    function get_all_candidates_list($requestData = array(), $user_group_id = 0, $user_id = 0)
    {
        $order_by = "";
        $data = array();
        $cond = '';
        $sWhere = "";
        $WhereCondition = ' WHERE TRUE';
           $column_search = array("ca.name","ca.mobile","ca.email","ca.gender_code","ca.dob","ca.qualification","ca.total_experience","ca.state_name","ca.district_name","ca.relocate_status_code");
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'ca.name',
            2 => 'ca.mobile',
            3 => 'ca.email',
            4 => 'ca.gender_code',
            5 => 'ca.dob',
            6 => 'ca.qualification',
            7 => 'ca.total_experience',
            8 => 'ca.state',
            9 => 'ca.district',
            10 => 'ca.relocate Status',
            /*11 => 'ca.exp.relocate Salary',*/
            12 => null
        );

        /*if($user_group_id>1)
		{
			if($user_group_id==9)				//admin
				$cond=' where ad.user_id='.$user_id;
			if($user_group_id==10)				//head
				$cond=' where  hd.user_id='.$user_id;
			if($user_group_id==6)			//manager
				$cond=' where  mr.user_id='.$user_id;
			if($user_group_id==4)			//coordinator
				$cond=' where cr.user_id='.$user_id;
			//new
			if($user_group_id==20)				//Regional manaegr
				$cond=' where ad.user_id='.$user_id;
			if($user_group_id==21)				//State manager
				$cond=' where  hd.user_id='.$user_id;
			if($user_group_id==22)				//District coordinartor
				$cond=' where ad.user_id='.$user_id;

		}*/


     //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											from ((SELECT * FROM users.neo_candidates c )
											union
											(SELECT * FROM users.neo_candidates c  )) as ca")->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $WhereCondition =  "";
            $sWhere="WHERE 1=1 AND ";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere .= " (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                   $sWhere.=') AND';
            }

            switch ($user_group_id) {
                case 5: //Sourcing Partner
                    $WhereCondition .= '  ca.associate_id=' . $user_id . ' ';
                    break;


                case 3: //Sourcing Partner
                    $WhereCondition .= ' ca.partner_id=' . $user_id . ' ';
                    break;

                case 22: //Sourcing Partner
                    $WhereCondition .= '  ca.coordinator_id=' . $user_id . ' ';
                    break;
                case 21: //Sourcing Partner
                    $WhereCondition .= 'ca.state_manager_id=' . $user_id . ' ';
                    break;

                case 20: //Sourcing Partner
                    $WhereCondition .= '  ca.regional_manager_id=' . $user_id . ' ';
                    break;
                case 9: //Sourcing Partner
                    $WhereCondition .= ' ca.sourcing_head_id=' . $user_id . ' ';
                    break;

                case 10: //Sourcing Partner
                    $WhereCondition .= '  ca.sourcing_head_id=' . $user_id . ' ';
                    break;

                case 1: //Super admin
                    $WhereCondition .=' 1=1';
                    break;
                default:
                     $WhereCondition .='1!=1';
                    break;

            }


            //  $sWhere .= $RoleFilter;

            $candidates_recs = $this->db->query("SELECT ca.* FROM users.vw_candidates AS ca $sWhere $WhereCondition $order_by limit $limit OFFSET $pg");

            // $str="SELECT * FROM vw_candidates AS VW $sWhere $order_by limit $limit OFFSET $pg";
            $totalFiltered = $candidates_recs->num_rows();

            $totalFiltered = $this->db->query("SELECT COUNT(ca.id)::INTEGER AS total_filtered FROM users.vw_candidates AS ca $sWhere $WhereCondition")->row()->total_filtered;;



            $slno = $pg;
            $data = array();
            foreach ($candidates_recs->result() as $candidates) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $candidates->candidate_name;
                $row[] = $candidates->mobile_number;
                $row[] = $candidates->email;
                $row[] = $candidates->gender_name;
                $row[] = $candidates->dob;
                $row[] = $candidates->education;
                $row[] = $candidates->total_experience;
                $row[] = $candidates->state_name;
                $row[] = $candidates->district_name;
                $data[] = $row;
            }


            // $data[] = $employee_recs->result_array();
            $candidates_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalFiltered),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $candidates_data_recs;
        }

    }

    function get_job_detail($job_id = 0)
    {
        $cond = '';
        if ($job_id)
            $cond = ' where J.job_id=' . $job_id;
        $job_detail_rec = $this->db->query("SELECT job_id,job_desc,job_category_name, min_qualification_name,
                                            min_experience,max_experience,min_salary,max_salary,contact_phone,qualification_pack_name as job_role,
                                            total_openings as no_of_openings,job_address,contact_name,contact_email, employer_name,created_on
                                            FROM job_process.vw_job_list AS J
								            $cond");
        if ($job_detail_rec->num_rows()) {
            return $job_detail_rec->row_array();
        } else
            return false;

    }

   /* public function notify_by_mail($to_email = '', $cc = '', $subject = '', $message = '')
    {
        $this->load->library('email');
        $config['charset'] = 'utf-8';
        $config['wordwrap'] = TRUE;
        $config['mailtype'] = 'html';
        $this->email->initialize($config);
        $this->email->from('donotreply@pramaan.in', 'Pramaan');
        $this->email->to($to_email);
        if ($cc)
            $this->email->cc($cc);
        $this->email->subject($subject);
        $this->email->message($message);
        $this->email->send();
    } */

    public function notify_by_mail($to_email = '', $cc = '', $subject = '', $message = '')
    {

        $this->load->library('email');

            $config['protocol']    = 'smtp';
            $config['smtp_host']    = 'ssl://smtp.gmail.com';
            $config['smtp_port']    = '465';		//Localhost:'465' or Live:'587';
            $config['smtp_user']    = 'donotreply@pramaan.in';
            $config['smtp_pass']    = 'pramaan123';
            $config['charset']    = 'utf-8';
            $config['mailtype'] = 'html'; // text or html
            $config['charset'] =  "iso-8859-1";
            $config['validation'] = TRUE; // bool whether to validate email or not
            $this->email->initialize($config);
            $this->email->set_newline("\r\n");
            $this->email->from('donotreply@pramaan.in', 'Pramaan');
            if ($cc)
                $this->email->cc($cc);
            $this->email->to($to_email);
            $this->email->subject($subject);
            $this->email->message($message);
            if ($this->email->send())
	        {
	        	return true;
	        }
	        else
	        {
	            return false;
	        }

    }
    function get_languages()
    {
        $language_rec = $this->db->query("SELECT value as id,name as language_name
										from master.list
										where code='L0009'
										order by sort_order");

        if ($language_rec->num_rows()) {
            return $language_rec->result_array();
        } else {
            return false;
        }
    }

    function admin_dashboard_statistics($user_id = 0)
    {
        $cond = '';
        if ($user_id)
            $cond = ' and referer_id=' . $user_id;
        $admin_statistics = $this->db->query("SELECT count(distinct id) as n_candidates_todays,
							(select count(distinct id) from users.candidates WHERE created_on> NOW() - INTERVAL '7 days' $cond) as n_this_weeks,
							(SELECT count(distinct id) from users.candidates WHERE date_trunc('month', created_on) = date_trunc('month', CURRENT_DATE) $cond) as n_this_months,
							(SELECT count(distinct id) from users.candidates WHERE date_part('year', created_on) = date_part('year', CURRENT_DATE) $cond) as n_this_years,
							(SELECT count(distinct id) from users.candidates WHERE 1=1 $cond) as n_candidates,

							CASE
							WHEN ($user_id>0)
							THEN (select count(*) from job_process.candidate_jobs where candidate_id in(select id from users.candidates where referer_id=$user_id) and status_id=7 and DATE(joining_date)= CURRENT_DATE)
							ELSE (select count(distinct id) from job_process.candidate_jobs where status_id=7 and DATE(joining_date)= CURRENT_DATE)
							END AS n_placed_today,

							--(select count(distinct id) from job_process.candidate_jobs where status_id=7 and DATE(joining_date)= CURRENT_DATE) as n_placed_today,

							(select count(distinct id) from job_process.candidate_jobs where status_id=7 and joining_date> NOW() - INTERVAL '7 days') as n_placed_week,
							CASE
							WHEN ($user_id>0)
							THEN (select count(*) from job_process.candidate_jobs where candidate_id in(select id from users.candidates where referer_id=$user_id) and status_id=7 and joining_date> NOW() - INTERVAL '7 days')
							ELSE (select count(distinct id) from job_process.candidate_jobs where status_id=7 and joining_date> NOW() - INTERVAL '7 days')
							END AS n_placed_week,

							--(select count(distinct id) from job_process.candidate_jobs where status_id=7 and date_trunc('month', joining_date) = date_trunc('month', CURRENT_DATE)) as n_placed_month,
							CASE
							WHEN ($user_id>0)
							THEN (select count(*) from job_process.candidate_jobs where candidate_id in(select id from users.candidates where referer_id=$user_id) and status_id=7 and date_trunc('month', joining_date) = date_trunc('month', CURRENT_DATE))
							ELSE (select count(distinct id) from job_process.candidate_jobs where status_id=7 and date_trunc('month', joining_date) = date_trunc('month', CURRENT_DATE))
							END AS n_placed_month,

							--(select count(distinct id) from job_process.candidate_jobs where status_id=7 and date_part('year', joining_date) = date_part('year', CURRENT_DATE)) as n_placed_year,
							CASE
							WHEN ($user_id>0)
							THEN (select count(*) from job_process.candidate_jobs where candidate_id in(select id from users.candidates where referer_id=$user_id) and status_id=7 and date_part('year', joining_date) = date_part('year', CURRENT_DATE))
							ELSE (select count(distinct id) from job_process.candidate_jobs where status_id=7 and date_part('year', joining_date) = date_part('year', CURRENT_DATE))
							END AS n_placed_year,
							--(select count(distinct id) from job_process.candidate_jobs where status_id=7)  as n_placed

							CASE
							WHEN ($user_id>0)
							THEN (select count(*) from job_process.candidate_jobs where candidate_id in(select id from users.candidates where referer_id=$user_id) and status_id=7)
							ELSE (select count(distinct id) from job_process.candidate_jobs where status_id=7)
							END AS n_placed,

							(select sum(COALESCE(NULLIF((no_of_openings-(select count(candidate_id) from job_process.candidate_jobs where job_id=pj.id and status_id=7 group by job_id)),'0'),no_of_openings))
							from job_process.jobs pj ) as n_positions_open,
							(select sum(COALESCE(NULLIF((no_of_openings-(select count(candidate_id) from job_process.candidate_jobs where job_id=pj.id and status_id=7 group by job_id)),'0'),no_of_openings))
							from job_process.jobs pj  where DATE(created_on)= CURRENT_DATE) as n_positions_open_today,
							(select sum(COALESCE(NULLIF((no_of_openings-(select count(candidate_id) from job_process.candidate_jobs where job_id=pj.id and status_id=7 group by job_id)),'0'),no_of_openings))
							from job_process.jobs pj where created_on> NOW() - INTERVAL '7 days') as n_positions_open_week,
							(select sum(COALESCE(NULLIF((no_of_openings-(select count(candidate_id) from job_process.candidate_jobs where job_id=pj.id and status_id=7 group by job_id)),'0'),no_of_openings))
							from job_process.jobs pj where date_trunc('month', created_on) = date_trunc('month', CURRENT_DATE)) as n_positions_open_month,
							(select sum(COALESCE(NULLIF((no_of_openings-(select count(candidate_id) from job_process.candidate_jobs where job_id=pj.id and status_id=7 group by job_id)),'0'),no_of_openings))
							from job_process.jobs pj where date_part('year', created_on) = date_part('year', CURRENT_DATE)) as n_positions_open_year,

							(select count(distinct user_id) from users.partners where DATE(created_on)= CURRENT_DATE) as n_partners_today,
							(select count(distinct user_id) from users.partners where created_on> NOW() - INTERVAL '7 days') as n_partners_this_weeks,
							(SELECT count(distinct user_id) from users.partners WHERE date_trunc('month', created_on) = date_trunc('month', CURRENT_DATE)) as n_partners_this_months,
							(SELECT count(distinct user_id) from users.partners WHERE date_part('year', created_on) = date_part('year', CURRENT_DATE)) as n_partners_this_years,
							(SELECT count(distinct user_id) from users.partners ) as n_partners

							from users.candidates uc where DATE(created_on)= CURRENT_DATE $cond");
        if ($admin_statistics->num_rows())
            return $admin_statistics->row_array();
        else
            return false;
    }

    // deprecated
    function get_sourcing_managers($requestData = array(), $parent_id = 0, $department_id = 0)
    {
        $cond = '';
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'user_admin_name',
            2 => 'email',
            3 => 'phone',
            4 => 'states',
            5 => null,
            6 => null
        );
        if ($parent_id)
            $cond = " where sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
        $column_search = array("sa.name", "sa.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.user_admins sa $cond"
        )->row()->total_recs;
        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($parent_id)
                    $sWhere .= " and sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
            } else
                $sWhere = $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.user_admins sa
								        			left join users.accounts ua on ua.id=sa.user_id
								        			$sWhere")->row()->total_filtered;

            $user_admins_recs = $this->db->query("SELECT sa.user_id, sa.name as user_admin_name,sa.address, sa.phone,ua.email,
														(select string_agg(ms.name,', ') from master.state ms where ms.id=ANY(sa.state_id) ) as states,
														(SELECT COUNT(*) FROM users.user_admins WHERE parent_id=sa.user_id) AS sub_user_count
														from users.user_admins sa
														left join users.accounts ua on ua.id=sa.user_id
														 $sWhere
														 $order_by limit $limit OFFSET $pg");
            //$totalFiltered=$sourcing_coordinators_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($user_admins_recs->result() as $userAdmins) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="Coordinator desk" onclick="sub_admin_desk(' . "'" . $userAdmins->user_id . "'" . ')">' . $userAdmins->user_admin_name . '</a>';
                $row[] = $userAdmins->email;
                $row[] = $userAdmins->phone;
                $row[] = $userAdmins->states;
                $row[] = $userAdmins->sub_user_count;
                $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Edit sourcing head" onclick="edit_user_admin(' . "'" . $userAdmins->user_id . "'" . ')"><i class="icon-edit"></i></a>';
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $user_admins_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $user_admins_data_recs;
        }
    }

    function do_add_sourcing_manager($data)
    {
        $user_group_id = $data['user_group_id'];
        $email = $data['email'];
        $pwd = $data['password'];
        $created_on = date('Y-m-d');
        $phone= $data['phone'];
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");


        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $sourcing_manager_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['parent_id'],
                'department_id' => $data['department_id'],
                'state_id' => $data['state_id'],
                'created_on' => $created_on
            );
            $this->db->insert('users.user_admins', $sourcing_manager_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function do_update_sourcing_manager($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.user_admins', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }


    function do_update_sourcing_head($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.sourcing_heads', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }

    //deprecated
    function get_sourcing_admins($requestData = array(), $admin_id = 0)
    {
        $cond = '';
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'sourcing_admin_name',
            2 => 'email',
            3 => 'phone',
            4 => null,
            5 => null
        );
        if ($admin_id)
            $cond = " where sa.admin_id=" . $admin_id;
        $column_search = array("sa.name", "sa.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.sourcing_admins sa $cond"
        )->row()->total_recs;
        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($admin_id)
                    $sWhere .= " and sa.admin_id=" . $admin_id;
            } else
                $sWhere = $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.sourcing_admins sa
								        			left join users.accounts ua on ua.id=sa.user_id
								        			$sWhere")->row()->total_filtered;

            $sourcing_admins_recs = $this->db->query("SELECT sa.user_id, sa.name as sourcing_admin_name,sa.address, sa.phone,ua.email,
														(SELECT COUNT(*) FROM users.sourcing_heads WHERE sourcing_admin_id=sa.user_id) AS sourcing_head_count
														from users.sourcing_admins sa
														left join users.accounts ua on ua.id=sa.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");
            //$totalFiltered=$sourcing_coordinators_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($sourcing_admins_recs->result() as $sourcingAdmins) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="Coordinator desk" onclick="sourcing_head_desk(' . "'" . $sourcingAdmins->user_id . "'" . ')">' . $sourcingAdmins->sourcing_admin_name . '</a>';
                $row[] = $sourcingAdmins->email;
                $row[] = $sourcingAdmins->phone;
                $row[] = $sourcingAdmins->sourcing_head_count;
                $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Edit sourcing head" onclick="edit_sourcing_admin(' . "'" . $sourcingAdmins->user_id . "'" . ')"><i class="icon-edit"></i></a>';
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $sourcing_admins_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $sourcing_admins_data_recs;
        }
    }

    function get_sourcing_coordinators($requestData = array(), $parent_id = 0, $department_id = 0)
    {
        $cond = '';
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'user_admin_name',
            2 => 'email',
            3 => 'phone',
            4 => 'districts',
            5 => null,
            6 => null
        );
        if ($parent_id)
            $cond = " where sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
        $column_search = array("sa.name", "sa.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.user_admins sa $cond"
        )->row()->total_recs;
        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($parent_id)
                    $sWhere .= " and sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
            } else
                $sWhere = $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.user_admins sa
								        			left join users.accounts ua on ua.id=sa.user_id
								        			$sWhere")->row()->total_filtered;

            $user_admins_recs = $this->db->query("SELECT sa.user_id, sa.name as user_admin_name,sa.address, sa.phone,ua.email,
														(select string_agg(md.name,', ') from master.district md where md.id=ANY(sa.district_id) ) as districts,
														(SELECT COUNT(*) FROM users.partners WHERE coordinator_id=sa.user_id) AS partner_count
														from users.user_admins sa
														left join users.accounts ua on ua.id=sa.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");
            //$totalFiltered=$sourcing_coordinators_recs->num_rows();


            $slno = $pg;
            $data = array();
            foreach ($user_admins_recs->result() as $userAdmins) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="Coordinator desk" onclick="sub_admin_desk(' . "'" . $userAdmins->user_id . "'" . ')">' . $userAdmins->user_admin_name . '</a>';
                $row[] = $userAdmins->email;
                $row[] = $userAdmins->phone;
                $row[] = $userAdmins->districts;
                $row[] = $userAdmins->partner_count;
                $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Edit sourcing head" onclick="edit_user_admin(' . "'" . $userAdmins->user_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $user_coordinator_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $user_coordinator_data_recs;
        }
    }

    function get_bd_executives($requestData = array(), $parent_id = 0, $department_id = 0)
    {
        $cond = '';
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'user_admin_name',
            2 => 'email',
            3 => 'phone',
            4 => null,
            5 => null
        );
        if ($parent_id)
            $cond = " where sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
        $column_search = array("sa.name", "sa.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.user_admins sa $cond"
        )->row()->total_recs;
        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($parent_id)
                    $sWhere .= " and sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
            } else
                $sWhere = $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.user_admins sa
								        			left join users.accounts ua on ua.id=sa.user_id
								        			$sWhere")->row()->total_filtered;

            $user_admins_recs = $this->db->query("SELECT sa.user_id, sa.name as user_admin_name,sa.address, sa.phone,ua.email,
														(SELECT COUNT(*) FROM users.employers WHERE recruitment_partner_id=sa.user_id) AS employer_count
														from users.user_admins sa
														left join users.accounts ua on ua.id=sa.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");


            //$totalFiltered=$sourcing_coordinators_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($user_admins_recs->result() as $userAdmins) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="Coordinator desk" onclick="sub_admin_desk(' . "'" . $userAdmins->user_id . "'" . ')">' . $userAdmins->user_admin_name . '</a>';
                $row[] = $userAdmins->email;
                $row[] = $userAdmins->phone;
                $row[] = $userAdmins->employer_count;
                $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Edit sourcing head" onclick="edit_user_admin(' . "'" . $userAdmins->user_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $user_coordinator_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $user_coordinator_data_recs;
        }
    }

    function get_user_admins($requestData = array(), $parent_id = 0, $department_id = 0)
    {
        $cond = '';
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'user_admin_name',
            2 => 'email',
            3 => 'phone',
            4 => null,
            5 => null
        );
        if ($parent_id)
            $cond = " where sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
        $column_search = array("sa.name", "sa.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.user_admins sa $cond"
        )->row()->total_recs;
        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($parent_id)
                    $sWhere .= " and sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
            } else
                $sWhere = $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.user_admins sa
								        			left join users.accounts ua on ua.id=sa.user_id
								        			$sWhere")->row()->total_filtered;

            $user_admins_recs = $this->db->query("SELECT sa.user_id, sa.name as user_admin_name,sa.address, sa.phone,ua.email,
														(SELECT COUNT(*) FROM users.user_admins WHERE parent_id=sa.user_id) AS sub_user_count
														from users.user_admins sa
														left join users.accounts ua on ua.id=sa.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");
            //$totalFiltered=$sourcing_coordinators_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($user_admins_recs->result() as $userAdmins) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="Coordinator desk" onclick="sub_admin_desk(' . "'" . $userAdmins->user_id . "'" . ')">' . $userAdmins->user_admin_name . '</a>';
                $row[] = $userAdmins->email;
                $row[] = $userAdmins->phone;
                $row[] = $userAdmins->sub_user_count;
                $row[] = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Edit sourcing head" onclick="edit_user_admin(' . "'" . $userAdmins->user_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $user_admins_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $user_admins_data_recs;
        }
    }


    function do_add_sourcing_admin($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $user_group_id = $data['user_group_id'];
        $phone= $data['phone'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("INSERT into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");
        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {

            $user_id = $this->db->insert_id();

            $user_admins_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['parent_id'],
                'department_id' => $data['department_id'],
                'region_id' => $data['region_id'],
                'created_on' => date('Y-m-d')
            );

            $this->db->insert('users.user_admins', $user_admins_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function do_update_sourcing_admin($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.user_admins', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }

    function do_update_sourcing_coordinator($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.user_admins', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }

    function do_update_user_admin($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.user_admins', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }

    function get_rs_users($requestData = array(), $parent_id = 0, $department_id = 0)
    {
        $cond = '';
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'RS_admin_name',
            2 => 'email',
            3 => 'phone',
            4 => null,
            5 => null
        );
        if ($parent_id)
            $cond = " where sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
        $column_search = array("sa.name", "sa.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.user_admins sa
											left join users.accounts ua on ua.id=sa.user_id
											$cond"
        )->row()->total_recs;
        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
        return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        }
        else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($parent_id)
                    $sWhere .= " and sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
            } else
                $sWhere = $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.user_admins sa
													left join users.accounts ua on ua.id=sa.user_id
								        			$sWhere")->row()->total_filtered;

            $user_admins_recs = $this->db->query("SELECT sa.user_id, sa.name as user_admin_name,sa.address, sa.phone,ua.email,
														(SELECT COUNT(*) FROM users.user_admins WHERE parent_id=sa.user_id) AS sub_user_count
														from users.user_admins sa
														left join users.accounts ua on ua.id=sa.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");
            //$totalFiltered=$sourcing_coordinators_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($user_admins_recs->result() as $userAdmins) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="user desk" onclick="sub_admin_desk(' . "'" . $userAdmins->user_id . "'" . ')">' . $userAdmins->user_admin_name . '</a>';
                $row[] = $userAdmins->email;
                $row[] = $userAdmins->phone;
                $row[] = $userAdmins->sub_user_count;
                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit sourcing head" onclick="edit_user_admin(' . "'" . $userAdmins->user_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $user_admins_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $user_admins_data_recs;
        }
    }

    //Not using in any controller
    function get_rs_executives_bak($requestData = array(), $parent_id = 0, $department_id = 0)
    {
        $cond = '';
        $order_by = "";
        $data = array();
        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'RS_admin_name',
            2 => 'email',
            3 => 'phone',
            4 => null,
            5 => null
        );
        if ($parent_id)
            $cond = " where sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
        $column_search = array("sa.name", "sa.phone", "ua.email"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
											FROM users.user_admins sa
											left join users.accounts ua on ua.id=sa.user_id
											$cond"
        )->row()->total_recs;
        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        } else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($parent_id)
                    $sWhere .= " and sa.parent_id=" . $parent_id . " and sa.department_id=" . $department_id;
            } else
                $sWhere = $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
								        			FROM users.user_admins sa
													left join users.accounts ua on ua.id=sa.user_id
								        			$sWhere")->row()->total_filtered;

            $user_admins_recs = $this->db->query("SELECT sa.user_id, sa.name as user_admin_name,sa.address, sa.phone,ua.email,
														(SELECT COUNT(*) FROM users.recruitment_support_assignment WHERE user_id=sa.user_id) AS sub_user_count
														from users.user_admins sa
														left join users.accounts ua on ua.id=sa.user_id
									        			 $sWhere
														 $order_by limit $limit OFFSET $pg");
            //$totalFiltered=$sourcing_coordinators_recs->num_rows();
            $slno = $pg;
            $data = array();
            foreach ($user_admins_recs->result() as $userAdmins) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = '<a href="javascript:void(0)" title="user desk" onclick="sub_admin_desk(' . "'" . $userAdmins->user_id . "'" . ')">' . $userAdmins->user_admin_name . '</a>';
                $row[] = $userAdmins->email;
                $row[] = $userAdmins->phone;
                $row[] = $userAdmins->sub_user_count;
                $row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit sourcing head" onclick="edit_user_admin(' . "'" . $userAdmins->user_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $data[] = $row;
            }

            //  $data[] = $employee_recs->result_array();
            $user_admins_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $user_admins_data_recs;
        }
    }

    function do_add_rs_user($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $user_group_id = $data['user_group_id'];
        $created_on = date('Y-m-d');
        $phone= $data['phone'];
        $result = $this->db->query("INSERT into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on'),$phone");
        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $user_admins_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['parent_id'],
                'department_id' => $data['department_id'],
                'created_on' => date('Y-m-d')
            );
            $this->db->insert('users.user_admins', $user_admins_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function do_update_rs_user($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.user_admins', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }

    function do_get_parent_id($user_id = 0)
    {
        if ($user_id)
            $admin_rec = $this->db->query('select parent_id from users.user_admins where user_id=?', $user_id);
        if ($admin_rec->num_rows())
            return $admin_rec->row()->parent_id;
        else
            return false;
    }

    function do_get_partner_id($user_id = 0)
    {
        if ($user_id) {
            $admin_rec = $this->db->query('select recruitment_partner_id from users.employers where user_id=?', $user_id);
            if ($admin_rec->num_rows())
                return $admin_rec->row()->recruitment_partner_id;
        } else
            return false;
    }

    function do_get_sourcing_partner_id($user_id = 0)
    {
        if ($user_id)
            $admin_rec = $this->db->query('select coordinator_id from users.partners where user_id=?', $user_id);
        if ($admin_rec->num_rows())
            return $admin_rec->row()->coordinator_id;
        else
            return false;
    }

//    function do_get_associate_id($user_id = 0)
//    {
//        if ($user_id)
//            $admin_rec = $this->db->query('select partner_id from users.associates where user_id=?', $user_id);
//        if ($admin_rec->num_rows())
//            return $admin_rec->row()->partner_id;
//        else
//            return false;
//    }

    function do_add_rs_executive($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $user_group_id = $data['user_group_id'];
        $created_on = date('Y-m-d');
        $phone=$data['phone'];
        $result = $this->db->query("INSERT into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");
        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $user_admins_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['parent_id'],
                'department_id' => $data['department_id'],
                'created_on' => date('Y-m-d')
            );
            $this->db->insert('users.user_admins', $user_admins_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function do_update_rs_executive($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.user_admins', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }

    /**
     * @author Sangamesh <sangamesh.p@pramaan.in>
     * Date: 20/12/2016
     * function to get all employers list datatable
     */
    function get_assigned_jobs_list($requestData = array(), $user_id = 0)
    {
        $order_by = "";
        $cond = '';
        $data = array();

        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'emp.name',
            2 => 'q.name',
            3 => 'j.job_desc',
            4 => 'j.job_location',
            5 => 'j.created_on',
            6 => 'no_of_openings',
            7 => 'joined',
            8 => null,
        );

        if ($user_id > 1)
            $cond = ' and ua.user_id=' . $user_id;

        $column_search = array("emp.name", "q.name", "j.job_desc", "j.job_location"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
							        	 FROM job_process.jobs j
												join users.user_admins ua on ua.user_id=j.rec_sup_exec_id
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
											where 1=1 $cond")->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        }
        else
        {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';
                if ($user_id)
                    $sWhere .= $cond;
            } else
                $sWhere = "WHERE 1=1 " . $cond;

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM job_process.jobs j
												join users.user_admins ua on ua.user_id=j.rec_sup_exec_id
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
							        			$sWhere")->row()->total_filtered;

            $job_recs = $this->db->query("SELECT j.*,e.name as min_education,l.name as job_category,q.name as qualification_name,COALESCE(NULLIF(emp.name,''),'-NA-') as employer_name,
												(SELECT count(*) from job_process.candidate_jobs cj where cj.job_id=j.id and cj.status_id=7) as joined, q.name as job_role,
												(SELECT count(*) from job_process.candidate_jobs cj where cj.job_id=j.id and cj.status_id=3) as scheduled_candidates
												FROM job_process.jobs j
												join users.user_admins ua on ua.user_id=j.rec_sup_exec_id
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
												$sWhere
												$order_by limit $limit OFFSET $pg");
            $slno = $pg;
            $data = array();
            foreach ($job_recs->result() as $jobs) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $jobs->employer_name;
                $row[] = $jobs->job_role;
                $row[] = $jobs->job_desc;
                $row[] = $jobs->job_location;
                $row[] = date('d-M-Y', strtotime($jobs->created_on));
                $row[] = $jobs->no_of_openings;
                $row[] = $jobs->joined;
                $row[] = ($jobs->scheduled_candidates) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="scheduled_candidates(' . "'" . $jobs->id . "'" . ',' . "'" . $jobs->employer_id . "'" . ')">' . $jobs->scheduled_candidates . '</b></a>' : $jobs->scheduled_candidates;
                $data[] = $row;
            }
            //  $data[] = $employee_recs->result_array();
            $jobs_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $jobs_data_recs;
        }
    }

    /**
     * @author Sangamesh <sangamesh.p@pramaan.in>
     * Date: 20/01/2017
     * function to get job assignment list
     */
    function get_job_assignment_list($location_id = 0, $employer_id = 0, $assignment_status = -1, $pg = 0, $limit = 25)
    {

        $cond = '';
        $total_records = 0;
        if ($location_id) {
            //$cond.=' and J.location_id='.$location_id;
        }
        if ($employer_id) {
            $cond .= ' and J.employer_id=' . $employer_id;
        }
        if ($assignment_status == 0) {
            $cond .= " and J.assignment_status=" . 'false';
        }

        if ($assignment_status == 1) {
            $cond .= " and J.assignment_status=" . 'true';
        }

        // ==== filters end =====
        $job_list_rec = $this->db->query("SELECT count(*) as total_rec FROM job_process.jobs J WHERE 1=1 $cond") or die('<pre>' . pg_last_error() . '</pre>');

        if ($job_list_rec->num_rows()) {
            $total_records = $job_list_rec->row()->total_rec;
        }

        if (!$total_records)
            return array('status' => 'error', 'message' => "<p style='text-align:center'>No result-data found</p>");
        else {

            $job_list_detail = $this->db->query("SELECT J.id AS job_id,J.qualification_pack_id,
														COALESCE(NULLIF(Qp.name,''),'-NA-') AS qualification_pack_name,J.job_desc,
														J.job_category_id,L1.name AS job_category_name,
														J.min_qualification_id,L2.name AS min_qualification_name,
														J.min_experience,J.max_experience,
														J.job_address,J.contact_name,J.contact_email,
														J.contact_phone,J.job_status_id,L3.name AS job_status_name,
														J.employer_id,J.question_one,J.question_two,
														COALESCE(NULLIF(e.name,''),'-NA-') AS employer_name,
														J.employer_id, COALESCE(NULLIF(rse.name,''),'-NA-') as rec_sup_exec_name,J.rec_sup_exec_id,
                                                        (select count(*) from  job_process.job_detail where job_id=J.id and assignment_status=true and job_status='t') as n_assigned,
                                                        COALESCE(( SELECT count(*) AS count FROM job_process.job_detail jd WHERE jd.job_id = J.id and jd.job_status='t'
                                                        GROUP BY jd.job_id), 0::bigint) AS n_locations,
                                                        TO_CHAR(J.created_on, 'dd-Mon-yyyy') AS created_on,
														TO_CHAR(J.modified_on, 'dd-Mon-yyyy') AS modified_on,
														COALESCE(NULLIF(TO_CHAR(J.assignment_date, 'dd-Mon-yyyy'),'') , '' ) AS assignment_date
														FROM job_process.jobs AS J
														LEFT JOIN	master.qualification_pack AS QP ON QP.id = J.qualification_pack_id
														LEFT JOIN	master.list AS L1 ON L1.code='L0015' AND L1.value=J.job_category_id::text
														LEFT JOIN	master.education AS L2 ON L2.id=J.min_qualification_id
														LEFT JOIN	master.status AS L3 ON L3.value=J.job_status_id
														LEFT JOIN   	users.employers e on e.user_id=j.employer_id
														left join users.rs_executive rse on J.rec_sup_exec_id=rse.user_id
														WHERE 1=1 $cond
														order by J.created_on desc
														limit $limit OFFSET $pg") or die('<pre>' . pg_last_error() . '</pre>');

            $ttl_res_curr = $job_list_detail->num_rows();
            $page_number = ($pg / $limit + 1);
            $pg_count_msg = "Showing " . (1 + $pg) . " to " . ($ttl_res_curr + $pg) . " of " . $total_records;
            $pagination = _prepare_pagination(site_url("pramaan/job_assignment_list/$location_id/$employer_id/$assignment_status"), $total_records, $limit, 6);
            return array('status' => 'success', 'job_list' => $job_list_detail->result_array()
            , 'pg' => $pg, 'limit' => $limit, 'pagination' => $pagination, 'pg_count_msg' => $pg_count_msg, 'page_number' => $page_number);

        }

    }

    /**
     * @author Sangamesh <sangamesh.p@pramaan.in>
     * Date: 20/01/2017
     * function to get job assignment list
     */
    function get_candidate_detail($candidate_id = 0)
    {
        $cond = '';
        if ($candidate_id)
            $cond = ' where uc.id=' . $candidate_id;
        $candidate_rec = $this->db->query("SELECT uc.id as candidate_id, INITCAP(uc.name) as name, uc.mobile, uc.gender_code,md.name as district_name,ms.name as state_name, me.name as education_name,
										 ml.name as expected_salary,COALESCE(NULLIF(ml1.name,''),'-NA-') as experience,mc.name as course_name,
										relocate_status_code,
										(select COALESCE(NULLIF(string_agg(name,','),''),'-NA-')
										from master.list
										where value::integer=ANY(uc.language_id)
										and code='L0009') as language_name, uc.aadhaar_num,	uc.id_type,	uc.id_number,uc.is_aadhar,
										to_char(uc.dob, 'DD-Mon-YYYY') as dob
										 from users.candidates uc
										 left join master.state ms on ms.id=uc.state_id
										 left join master.district md on md.id=uc.district_id
										 left join master.education me on me.id=uc.education_id
										 left join master.courses mc on mc.id=uc.course_id
										 left join master.list ml on ml.value::integer= uc.expected_salary_id and ml.code='L0012'
										 left join master.list ml1 on ml1.value::integer=uc.experience_id and ml1.code='L0006'
										 $cond");
        if ($candidate_rec->num_rows()) {
            return $candidate_rec->row_array();
        } else
            return false;
    }

    /**
     * @author Sangamesh <sangamesh.p@pramaan.in>
     * Date: Feb_2017
     * function to get all employers list datatable
     */
    function get_jobs_list($requestData = array())
    {
        $order_by = "";
        $cond = '';
        $data = array();

        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'emp.name',
            2 => 'q.name',
            3 => 'j.job_desc',
            4 => 'j.job_location',
            5 => 'no_of_openings',
            6 => null,
        );

        $column_search = array("emp.name", "q.name", "j.job_desc", "j.job_location"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT COUNT(*)::bigint AS total_recs
							        	 FROM job_process.jobs j
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
											where 1=1 $cond")->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        }
        else {
            /*
	         * Filtering
	         */
            $sWhere = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $sWhere = "WHERE (";
                for ($i = 0; $i < count($column_search); $i++) {
                    $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }
                $sWhere = substr_replace($sWhere, "", -3);
                $sWhere .= ')';

            }

            $totalFiltered = $this->db->query("SELECT COUNT(*)::bigint AS total_filtered
							        			FROM job_process.jobs j
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
							        			$sWhere")->row()->total_filtered;

            $job_recs = $this->db->query("SELECT j.*,e.name as min_education,l.name as job_category,q.name as qualification_name,COALESCE(NULLIF(emp.name,''),'-NA-') as employer_name,
												(SELECT count(*) from job_process.candidate_jobs cj where cj.job_id=j.id and cj.status_id=7) as joined, q.name as job_role,
												(SELECT count(*) from job_process.candidate_jobs cj where cj.job_id=j.id and cj.status_id=3) as scheduled_candidates
												FROM job_process.jobs j
												LEFT JOIN master.education e on e.id=j.min_qualification_id
												LEFT JOIN master.list l on j.job_category_id=(l.value)::int and l.code='L0015'
												LEFT JOIN master.qualification_pack q on q.id=j.qualification_pack_id
												LEFT JOIN users.employers emp on emp.user_id=j.employer_id
												$sWhere
												$order_by limit $limit OFFSET $pg");
            $slno = $pg;
            $data = array();
            foreach ($job_recs->result() as $jobs) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $jobs->employer_name;
                $row[] = $jobs->job_role;
                $row[] = $jobs->job_desc;
                $row[] = $jobs->job_location;
                $row[] = $jobs->no_of_openings;
                $row[] = date('d-M-Y', strtotime($jobs->created_on));
                $data[] = $row;
            }
            //  $data[] = $employee_recs->result_array();
            $jobs_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $jobs_data_recs;
        }
    }

    public function do_get_districts()
    {
        $district_recs = $this->db->query("SELECT md.id,concat(md.name,' (',code,' )') as district_name from master.district md
													 LEFT JOIN master.state ms on ms.id=md.state_id ORDER BY md.name");
        if ($district_recs->num_rows()) {
            return $district_recs->result_array();
        } else
            return false;
    }


//Aniket's Work

    public function get_area_of_interest_list()
    {
        $interest_list_query = "Select name,value from master.list where code ='L0005'";
        $interest_list = $this->db->query($interest_list_query);
        if ($interest_list->num_rows())
        {
            return $interest_list->result_array();
        }
        else
        {
            return false;
        }
    }

    public function get_other_course_code()
    {
        $code_list_query = "Select id from master.courses where name = ?";
        $code_list = $this->db->query($code_list_query, array('others'));

        if ($code_list->num_rows())
            return $code_list->result();
        else
            return false;
    }

    public function get_other_interest_code()
    {


        $interest_code_query = "Select code from master.list_type where type=  ?";


        $interest_code = $this->db->query($interest_code_query, array('Area of Interest'))->result();

        return $interest_code;

        $other_interest_query = "Select value from master.list where code =  ? and name = ?";

        $other_interest_code = $this->db->query($other_interest_query, array($interest_code, 'Others'));

        if ($other_interest_code->num_rows())
            return $$other_interest_code->result();
        else
            return false;
    }



//==Ends


//==Aniket work


    public function do_get_district($state_id, $active_status)
    {
        if ($active_status == 0)
        {
            $district_rec = $this->db->query("SELECT * from master.district
                                        WHERE state_id=? AND (active_status = 0 OR active_status=1) order by name", array($state_id));
        }
        else
        {
            $district_rec = $this->db->query("SELECT * from master.district
                                        WHERE state_id=? AND active_status = ? order by name", array($state_id, $active_status));
        }

        if ($district_rec->num_rows())
        {
            return $district_rec->result_array();
        }
        else
        {
            return array();
        }
    }

    public function do_get_district_by_state($state_id)
    {
        $active_status =1;
        $district_rec = $this->db->query("SELECT * FROM master.district WHERE state_id=$state_id order by name");
        if ($district_rec->num_rows())
        {
            return $district_rec->result_array();
        }
        else
        {
            return array();
        }
    }

    public function get_region_list()
    {
        $strQuery = "SELECT id AS region_id,name AS region_name
                      FROM master.regions AS R
                      WHERE active_status = 1
                      ORDER BY region_name";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    public function do_get_regions($parent_id = 0, $active_status = 1)
    {
        //$country_id_array = $this->get_user_country_id($parent_id);
        $country_id_array = array('99');
        if ($country_id_array)
        {

            for ($i = 0; $i < sizeof($country_id_array); $i++)
            {
                $active_status_list = '';
                if ($active_status == 0)
                {
                    //fetch all regions
                    $active_status_list = '(0,1)';
                }
                else
                {
                    $active_status_list = '(1)';
                }

                $query1 = $this->db->query("select * from master.regions where country_id = " . $country_id_array[$i] . " and active_status in " . $active_status_list . " order by name")->result();
                $j = 0;
                foreach ($query1 as $val)
                {
                    $query2 = $this->db->query("select count(id) as states from master.state where region_id = " . $val->id . "")->result();
                    $regions_rec[$j]['name'] = $val->name;
                    $regions_rec[$j]['id'] = $val->id;
                    $regions_rec[$j]['states'] = $query2[0]->states;
                    $regions_rec[$j]['active_status'] = $val->active_status;

                    $region_mgr_query = $this->db->query("SELECT name from users.regional_manager WHERE ? = ANY(region_id_list)", $val->id);
                    if ($region_mgr_query->num_rows())
                    {
                        $regions_rec[$j]['regional_manager_name'] = $region_mgr_query->result()[0]->name;
                    }
                    else
                    {
                        $regions_rec[$j]['regional_manager_name'] = 'Not Assigned';
                    }
                    $j++;
                }
            }
            return $regions_rec;
        }
        else
        {
            return false;
        }

    }

    public function get_state_list($region_id = 0, $active_status = 0)
    {
        $state_list_rec = array();
        if ($active_status == -1)
        {
            $stae_list_query = "SELECT * FROM master.state WHERE active_status = ? ORDER BY name";
            $state_list_rec = $this->db->query($stae_list_query, array($active_status))->result_array();
        }
        else
        {
            $stae_list_query = "SELECT * FROM master.state WHERE region_id = ? AND active_status != -1 ORDER BY name";
            $state_list_rec = $this->db->query($stae_list_query, array($region_id))->result_array();
        }

        if ($state_list_rec)
            return $state_list_rec;
        else
            return array();
    }

    public function do_add_region($data)
    {
        $insert_region_query = "INSERT INTO master.regions (code,name,country_id) VALUES (?,?,?)";

        $insert_region = $this->db->query($insert_region_query, array($data['code'], $data['name'], $data['country_id']));

        if ($this->db->affected_rows())
        {
            $region_id=$this->db->insert_id();
            $update_state_under_region = $this->db->query("UPDATE master.state SET region_id = ?, active_status = (CASE WHEN (active_status = -1 OR active_status = 1) THEN 1 ELSE 0 END) WHERE id = ANY(?)", array($region_id, $data['states_under_region_id']));
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_user_country_id($parent_id = 0)
    {
        //$country_id_query = "SELECT SH.country_id_list FROM (SELECT created_by FROM users.sourcing_admin WHERE user_id =  ? ) AS SA INNER JOIN  users.sourcing_head as SH ON SA.created_by = SH.user_id; ";
        $country_id_query = "SELECT   SH.country_id_list
                                FROM  users.sourcing_admin AS SA
                                JOIN  users.sourcing_head as SH ON SH.user_id = SA.created_by
                                WHERE SH.user_id = ?";
        $country_id_rec = $this->db->query($country_id_query, array($parent_id));
        if($country_id_rec->num_rows()) {
            $country_id = $country_id_rec->result()[0]->country_id_list;
            $temp = str_replace(array('}', '{'), '', $country_id);
            $country_id_array = explode(',', $temp);
            return $country_id_array;
        }
        else
        {
            return false;
        }
    }

    public function get_region_by_id($region_id = 0)
    {
        //$region_data_query = "SELECT * FROM master.regions WHERE id = ? ORDER BY name";

        $region_data_query = "SELECT R.* ,
			(SELECT string_agg(id::TEXT,',')
			 FROM master.state
			  WHERE region_id=R.id) states_under_region_id ,
			  (SELECT string_agg(name, ',<br>')
			   FROM master.state
			   WHERE region_id=R.id) states_under_region_name
				 FROM master.regions AS R
				  WHERE R.id = ?
				  ORDER BY R.name";

        $region_data = $this->db->query($region_data_query, array($region_id));

        if ($region_data->num_rows()) {
            return $region_data->result_array();
        } else {
            return false;
        }


    }


    public function do_update_region($region_id = 0, $data)
    {

        $update_region_query = "UPDATE master.regions SET code = ? , name = ? ,country_id = ? WHERE id = ?";

        $update_region = $this->db->query($update_region_query, array($data['code'], $data['name'], $data['country_id'], $region_id));


        if ($this->db->affected_rows())
        {
            /*$update_state_under_region = $this->db->query("UPDATE master.state SET region_id = ?, active_status = (CASE WHEN (active_status = -1 OR active_status = 1) THEN 1 ELSE 0 END) WHERE id = ANY(?)", array($region_id, $data['states_under_region_id']));


            if ($this->db->affected_rows())
            {
                $update_state_prev_region = $this->db->query("UPDATE master.state SET region_id = 0, active_status= -1 WHERE region_id = ? AND id != ALL(?)", array($region_id, $data['states_under_region_id']));

                return true;

            }*/

            $update_state_under_region = $this->db->query("UPDATE master.state SET region_id = ? WHERE region_id =?", array(0,$region_id));
            $update_state_under_region = $this->db->query("UPDATE master.state SET region_id = ?, active_status = (CASE WHEN (active_status = -1 OR active_status = 1) THEN 1 ELSE 0 END) WHERE id = ANY(?)", array($region_id, $data['states_under_region_id']));
            return true;
        }
        else
        {


            return false;
        }
    }


    public function do_get_states_data($parent_id = 0, $country_id = 0)
    {
        // $region_id = $this->get_user_($parent_id);

        $query1 = "select * from master.state where (active_status = 1 or active_status = 0) and region_id in (Select id from master.regions where country_id = ?) order by name";

        $query1_result = $this->db->query($query1, array($country_id))->result();
        $j = 0;
        $states_rec = array();

        foreach ($query1_result as $val) {
            $query2 = $this->db->query("select count(id) as districts from master.district where state_id = " . $val->id . " and (active_status = 1 or active_status = 0) ")->result();

            $states_rec[$j]['name'] = $val->name;
            $states_rec[$j]['id'] = $val->id;

            $state_mgr_query = $this->db->query("SELECT name from users.state_manager WHERE ? = ANY(state_id_list)", $val->id);
            if ($state_mgr_query->num_rows()) {
                $states_rec[$j]['state_manager_name'] = $state_mgr_query->result()[0]->name;
            } else {
                $states_rec[$j]['state_manager_name'] = 'Not Assigned';
            }

            $states_rec[$j]['active_status'] = $val->active_status;
            $states_rec[$j]['districts'] = $query2[0]->districts;


            $region_name_query = "SELECT name FROM master.regions WHERE id = ?";
            $region_name = $this->db->query($region_name_query, array($val->region_id))->result_array()[0]['name'];

            $states_rec[$j]['region_name'] = $region_name;

            $j++;
        }

        return $states_rec;
    }


    public function do_update_state($data)
    { //return true;
        $insert_state_query = "UPDATE master.state SET region_id = ? , active_status = ? WHERE id = ?";

        $insert_state = $this->db->query($insert_state_query, array($data['region_id'], 1, $data['state_id']));

        if ($this->db->affected_rows() && $data['submit'] == 'add')
        {
            /*$state_id=$data['state_id'];
            $this->db->query("UPDATE master.district SET state_id = ?, active_status = (CASE WHEN (active_status = -1 OR active_status = 1) THEN 1 ELSE 0 END) WHERE id = ANY(?)", array($state_id, $data['districts_under_state_id']));*/
            return true;
        }
        else if ($data['submit'] == 'update')
        {

            if ($this->db->affected_rows())
            {
                $update_prev_district_under_state = $this->db->query("UPDATE master.district SET active_status = -1 WHERE state_id = ? ", $data['state_id']);
                if(count( $data['districts_under_state_id']))
                {
                    $arr_string='';
                    $districts_under_state_id=$data['districts_under_state_id'];
                    $arr_string = implode(',', $districts_under_state_id);
                    $districts_under_state_id = '{' . $arr_string . '}';
                    $update_district_under_state = $this->db->query("UPDATE master.district SET  active_status = (CASE WHEN (active_status = -1 OR active_status = 1) THEN 1 ELSE 0 END) WHERE id = ANY(?)", array($districts_under_state_id));
                }
                if ($this->db->affected_rows())
                {
                   // $update_prev_district_under_state = $this->db->query("UPDATE master.district SET active_status = -1 WHERE state_id = ? AND id != ALL(?)", array($data['state_id'],  $districts_under_state_id));
                    return true;
                }
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


    public function get_state_by_id($state_id = 0)
    {
        $state_data_query = "SELECT S.*,
								(SELECT string_agg(id::TEXT,',') FROM master.district WHERE (state_id=S.id AND active_status >= 0)) districts_under_state_id ,
								(SELECT string_agg(name, ',<br>') FROM master.district WHERE state_id=S.id) districts_under_state_name
							  FROM 	master.state AS S
							  WHERE S.id = ?
							  ORDER BY S.name";

        $state_data = $this->db->query($state_data_query, array($state_id));
        if ($state_data->num_rows()) {
            return $state_data->result_array();
        } else {
            return false;
        }
    }




    public function do_get_states($parent_id = 0)
    {
        $query1 = $this->db->query("select id,name from master.state where active_status = 1 order by name");

        if ($query1->num_rows()) {
            return $query1->result_array();
        } else {
            return false;
        }

    }


    public function do_update_district($data)
    { //return true;
        $district_query = "UPDATE master.district SET state_id = ? , active_status = ? WHERE id = ?";

        $district_rec = $this->db->query($district_query, array($data['state_id'], 1, $data['district_id']));


        if ($this->db->affected_rows()) {
            return true;
        } else {


            return false;
        }
    }


    public function get_district_by_id($district_id = 0)
    {
        $district_data_query = "SELECT * FROM master.district WHERE id = ?";

        $district_data = $this->db->query($district_data_query, array($district_id));

        if ($district_data->num_rows()) {
            return $district_data->result_array();
        } else {
            return false;
        }
    }


 /*   public function do_change_region_status($region_id)
    {

        $update_status_query = "UPDATE master.regions SET  active_status = active_status

      	   # 1 WHERE id = ? ";

        $update_status = $this->db->query($update_status_query, array($region_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }*/


  /*  public function do_change_state_status($state_id)
    {

        $update_status_query = "UPDATE master.state SET  active_status = active_status

      	   # 1 WHERE id = ? ";

        $update_status = $this->db->query($update_status_query, array($state_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }
*/
    public function do_change_district_status($district_id)
    {

        $update_status_query = "UPDATE master.district SET  active_status = active_status

      	   # 1 WHERE id = ? ";

        $update_status = $this->db->query($update_status_query, array($district_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }


    public function do_get_state_managers($regional_mgr_id)
    {

        $state_mgr_data = $this->db->query("SELECT * FROM users.state_manager WHERE created_by = ?", $regional_mgr_id);

        if ($state_mgr_data->num_rows()) {
            return $state_mgr_data->result_array();
        }


    }


    public function do_add_regional_manager($data)
    {

        $user_group_id = 20;
        $email = $data['email'];
        $pwd = $data['password'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");


        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $sourcing_head_id = $this->db->query("SELECT created_by FROM users.sourcing_admin WHERE user_id = ?", $data["parent_id"])->result()[0]->created_by;

            $user_id = $this->db->insert_id();
            $sourcing_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
                'region_id_list' => $data['region_id_list'],
                'created_by' => $sourcing_head_id,
                'modified_by' => $sourcing_head_id,
                'created_on' => $created_on,
                'modified_on' => $created_on,

            );
            $this->db->insert('users.regional_manager', $sourcing_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


    public function get_regional_manager_data_by_id($regional_mgr_id, $table = '')
    {
        if ($table != '') {
            $regional_mgr_data = $this->db->query('select * from users.' . $table . ' where user_id=' . $regional_mgr_id . '');

            if ($regional_mgr_data->num_rows()) {
                return $regional_mgr_data->result_array();
            }
        }
        $regional_mgr_data = $this->db->query("SELECT * FROM users.regional_manager WHERE user_id = ?", $regional_mgr_id);

        if ($regional_mgr_data->num_rows()) {
            return $regional_mgr_data->result_array();
        }

    }

    public function do_update_regional_manager($data)
    {

        $email = $data['email'];

        $modified_on = date('Y-m-d');
        $phone = $data['phone'];
        $result = $this->db->query("update users.accounts set email=?, modified_on = ?  where id= ? ", array($email, $modified_on,$data['user_id']));


        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            /*$sourcing_head_id = $this->db->query("SELECT created_by FROM users.sourcing_admin WHERE user_id = ?",$data["parent_id"])->result()[0]->created_by;*/

            $user_id = $data['user_id'];
            $sourcing_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'region_id_list' => $data['region_id_list'],
                'modified_on' => $modified_on

            );
            $update_query = $this->db->query("update users.regional_manager set name=?,email=?, modified_on = ? ,phone = ?, region_id_list = ? where user_id= ? ", array($data['name'], $data['email'], $modified_on, $data['phone'], $data['region_id_list'], $user_id));
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


   /* public function do_change_regional_mgr_status($regional_mgr_id)
    {
        $update_status_query1 = "UPDATE users.regional_manager SET  active_status = NOT active_status

      	    WHERE user_id = ? ";

        $update_status_query2 = "UPDATE users.accounts SET  is_active = NOT is_active

      	   WHERE id = ? ";

        $update_status1 = $this->db->query($update_status_query1, array($regional_mgr_id));

        $update_status = $this->db->query($update_status_query2, array($regional_mgr_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }*/





    public function do_get_district_coordinators($state_mgr_id)
    {
        $state_mgr_data = $this->db->query("SELECT * FROM users.district_coordinator WHERE created_by = ?", $state_mgr_id);

        if ($state_mgr_data->num_rows()) {
            return $state_mgr_data->result_array();
        }

    }

    public function do_get_district_list($parent_id = 0)
    {
        //getting specific districts only
        $query = $this->db->query('select * from users.state_manager where user_id=' . $parent_id . '');
        $temp = str_replace(array('}', '{'), '', $query->result()[0]->state_id_list);
        $t = explode(',', $temp);

        $query = $this->db->query('select * from master.district where state_id=' . $t[0] . ' and active_status=1')->result_array();
        return $query;
    }

    public function get_region_by_regional_mgr($regional_mgr_id)
    {
        $region_id_query = $this->db->query("SELECT region_id_list FROM users.regional_manager WHERE user_id = ?", $regional_mgr_id)->result_array()[0]['region_id_list'];

        // return $region_id_query;

        $res = str_replace(array('}', '{'), '', $region_id_query);

        $region_id_array = explode(',', $res);

        return $region_id_array[0];

    }


    public function do_get_state_by_region($region_id, $active_status)
    {
        $state_list_query = $this->db->query("SELECT * FROM master.state WHERE region_id = ? AND active_status = ?", array($region_id, $active_status));

        if ($state_list_query->num_rows())
        {
            return $state_list_query->result_array();
        } else {
            return false;
        }

    }


    public function do_add_state_manager($data)
    {
        $user_group_id = 21;
        $email = $data['email'];
        $pwd = $data['password'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");


        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            /*$regional_mgr_id = $this->db->query("SELECT created_by FROM users.regional_manager WHERE user_id = ?",$data["parent_id"])->result()[0]->created_by;
*/

            $regional_mgr_id = $data['parent_id'];
            $user_id = $this->db->insert_id();
            $sourcing_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'state_id_list' => $data['state_id_list'],
                'created_by' => $regional_mgr_id,
                'modified_by' => $regional_mgr_id,
                'created_on' => $created_on,
                'modified_on' => $created_on,

            );
            $this->db->insert('users.state_manager', $sourcing_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }


    public function do_update_state_manager($data)
    {

        /*$user_group_id=$this->db->query("SELECT value from master.list
									where code='L0001' and lower(name)=?",strtolower('regional manager'))->row()->value;
	*/
        $email = $data['email'];
        $modified_on = date('Y-m-d');
        $result = $this->db->query("update users.accounts set email=?, modified_on = ? where id= ? ", array($email, $modified_on, $data['user_id']));


        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            /*$sourcing_head_id = $this->db->query("SELECT created_by FROM users.sourcing_admin WHERE user_id = ?",$data["parent_id"])->result()[0]->created_by;*/

            $user_id = $data['user_id'];

            $update_query = $this->db->query("update users.state_manager set name=?,email=?,phone=?, modified_on = ? ,state_id_list = ? where user_id= ? ", array($data['name'], $data['email'], $data['phone'],$modified_on, $data['state_id_list'], $user_id));
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    public function get_state_manager_data_by_id($state_mgr_id)
    {

        $state_mgr_data = $this->db->query("SELECT * FROM users.state_manager WHERE user_id = ?", $state_mgr_id);

        if ($state_mgr_data->num_rows()) {
            return $state_mgr_data->result_array();
        }

    }


    public function do_change_state_mgr_status($state_mgr_id)
    {
        $update_status_query1 = "UPDATE users.state_manager SET  active_status = NOT active_status

      	    WHERE user_id = ? ";

        $update_status_query2 = "UPDATE users.accounts SET  is_active = NOT is_active

      	   WHERE id = ? ";

        $update_status1 = $this->db->query($update_status_query1, array($state_mgr_id));

        $update_status = $this->db->query($update_status_query2, array($state_mgr_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    public function do_check_duplicate_region_name($region_name,$id)
    {


        $region_name_query = $this->db->query("SELECT * FROM master.regions WHERE Lower(name) = ? AND id!=? ", array($region_name,$id));

        if ($region_name_query->num_rows()) {
            return false;
        } else {

            return true;
        }

    }

    public function do_check_duplicate_region_short_name($region_short_name,$id)
    {


        $region_short_name_query = $this->db->query("SELECT * FROM master.regions WHERE Lower(code) = ? AND id!=? ", array($region_short_name,$id));

        if ($region_short_name_query->num_rows()) {
            return false;
        } else {

            return true;
        }

    }

    public function do_check_duplicate_rs_sector_name($sector_name,$id)
    {


        $sector_name_query = $this->db->query("SELECT * FROM master.sector WHERE Lower(name) = ? AND id!=? ", array($sector_name,$id));

        if ($sector_name_query->num_rows()) {
            return false;
        } else {

            return true;
        }

    }



    //aniket work ends here


    //Saurabh Sinha work starts here
    function get_state_managers_list_view()
    {
        $query = $this->db->query("select * from users.state_manager");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['state_id_list'] = $query->result()[$i]->state_id_list;


            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->state_id_list);
            $t = explode(',', $temp);


            $re = "";
            $no = 0;
            foreach ($t as $key) {
                # code...

                $query1 = $this->db->query("select name from master.state where id=" . $key . ";")->result()[0]->name;
                if ($no == sizeof($t) - 1)
                    $re .= $query1;
                else
                    $re .= $query1 . "/";
                $no++;
            }
            $row['state'] = $re;

            /*$row[]=$query->result()[$i]->country_id_list;*/
            /*
				Getting list of regional managers here
	         	*/
            $query1 = $this->db->query('select * from users.regional_manager where user_id=' . $query->result()[$i]->created_by . '');
            $row['regional_manager'] = $query1->result()[0]->name;


            $data[] = $row;
        }

        return $data;
    }

    function get_regional_managers_list_view($table = '')
    {
        $query = $this->db->query("select * from users." . $table . " order by name");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/

        //return $query->result_array();
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['region_id_list'] = $query->result()[$i]->region_id_list;

            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->region_id_list);
            $t = explode(',', $temp);


            $re = "";
            $no = 0;
            foreach ($t as $key) {
                # code...

                $query1 = $this->db->query("select name from master.regions where id=" . $key . ";")->result()[0]->name;
                if ($no == sizeof($t) - 1)
                    $re .= $query1;
                else
                    $re .= $query1 . "/";
                $no++;
            }
            $row['regions'] = $re;

            /*$row[]=$query->result()[$i]->country_id_list;*/
            /*
				Getting list of regional managers here
	         	*/

            if ($table == 'bd_regional_manager') {
                $query1 = $this->db->query("Select name from users.bd_head where user_id = ?", $query->result()[$i]->created_by);
                if ($query1->num_rows())
                    $row['bd_head_name'] = $query1->result()[0]->name;
                else
                    return false;

            } else {


                $query1 = $this->db->query('select * from users.sourcing_head where user_id=' . $query->result()[$i]->created_by . '');
                if ($query1->num_rows())
                    $row['sourcing_head'] = $query1->result()[0]->name;
                else
                    return false;
            }


            $data[] = $row;
        }

        return $data;
    }

    function get_dictrict_coordinators_list_view()
    {
        $query = $this->db->query("select * from users.district_coordinator order by name");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['district_id_list'] = $query->result()[$i]->district_id_list;


            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->district_id_list);
            $t = explode(',', $temp);


            $re = "";
            $no = 0;
            foreach ($t as $key) {
                # code...

                $query1 = $this->db->query("select name from master.district where id=" . $key . ";")->result()[0]->name;
                if ($no == sizeof($t) - 1)
                    $re .= $query1;
                else
                    $re .= $query1 . "/";
                $no++;
            }
            $row['districts'] = $re;

            /*$row[]=$query->result()[$i]->country_id_list;*/
            /*
				Getting list of regional managers here
	         	*/
            $query1 = $this->db->query('select * from users.state_manager where user_id=' . $query->result()[$i]->created_by . '');
            if ($query1->num_rows())
                $row['state_manager'] = $query1->result()[0]->name;
            else
                $row['state_manager'] = 'Not assigned';


            $data[] = $row;
        }

        return $data;
    }

    function do_add_user_admin_superadmin($data)
    {
        $user_group_id = $data['user_group_id'];
        $email = $data['email'];
        $pwd = $data['password'];
        $sourcing_head = $data['sourcing_head'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $sourcing_admin_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $email,
                'country_id_list' => '{99}',
                'created_by' => $sourcing_head,
                'created_on' => $created_on,
                'modified_by' => $sourcing_head,
                'modified_on' => $created_on,
                'active_status' => true
            );
            $this->db->insert('users.sourcing_admin', $sourcing_admin_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }




    public function do_add_district_coordinators($data)
    {
        $user_group_id = '22';
        $email = $data['email'];
        $pwd = $data['password'];
        $parent_id = $data['parent_id'];
        $created_on = date('Y-m-d');
        $district_id_list = $data['district_id_list'];
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $sourcing_heads_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $email,
                'district_id_list' => $district_id_list,
                'created_by' => $parent_id,
                'created_on' => $created_on,
                'modified_by' => $parent_id,
                'modified_on' => $created_on,
                'active_status' => true
            );
            $this->db->insert('users.district_coordinator', $sourcing_heads_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }



    function get_sourcing_admin($user_id = 0)
    {
        $query = $this->db->query("select * from users.sourcing_admin where created_by='" . $user_id . "' order by name");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;


            //get sourcing head name
            $query1 = $this->db->query("select * from users.sourcing_head where user_id='" . $query->result()[$i]->created_by . "'");

            $row['created_by'] = $query1->result()[0]->name;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['active_status'] = $query->result()[$i]->active_status;

            $row['sourcing_admin_id'] = $query->result()[$i]->user_id;
            $data[] = $row;
        }

        return $data;
    }

    function get_sourcing_admin_all()
    {
        $query = $this->db->query("select * from users.sourcing_admin order by name");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;

            $sh_id = $query->result()[$i]->created_by;
            if ($sh_id) {
                $sh_name = $this->db->query('select * from users.sourcing_head where user_id=' . $sh_id . '')->result()[0]->name;

            } else {
                $sh_name = 'No Sourcing head';
            }
            //get sourcing head name


            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['sourcing_head_name'] = $sh_name;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['active_status'] = $query->result()[$i]->active_status;

            $row['sourcing_admin_id'] = $query->result()[$i]->user_id;
            $data[] = $row;
        }

        return $data;
    }


    function get_sourcing_admin_list($user_id = 0)
    {
        $query = $this->db->query("select * from users.sourcing_admin where created_by='" . $user_id . "' order by name");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;


            //get sourcing head name
            $query1 = $this->db->query("select * from users.sourcing_head where user_id='" . $query->result()[$i]->created_by . "'");


            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['active_status'] = $query->result()[$i]->active_status;


            $data[] = $row;
        }

        return $data;
    }

    function get_regional_managers_list_by_sh($user_id = 0)
    {

        $query = $this->db->query("select * from users.regional_manager where created_by='" . $user_id . "'");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['region_id_list'] = $query->result()[$i]->region_id_list;
            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->region_id_list);
            $t = explode(',', $temp);


            $re = "";
            $no = 0;
            foreach ($t as $key) {
                # code...

                $query1 = $this->db->query("select name from master.regions where id=" . $key . ";")->result()[0]->name;
                if ($no == sizeof($t) - 1)
                    $re .= $query1;
                else
                    $re .= $query1 . "/";
                $no++;
            }
            $row['regions'] = $re;

            $data[] = $row;
        }

        return $data;
    }


    function get_regional_managers_list_by_sa($user_id = 0)
    {

        $sourcing_head_id = $this->db->query("SELECT created_by FROM users.sourcing_admin WHERE user_id = ?", $user_id)->result()[0]->created_by;


        $query = $this->db->query("select * from users.regional_manager where created_by='" . $sourcing_head_id . "'");


        $slno = 0;
        $data = array();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['active_status'] = $query->result()[$i]->active_status;
            $row['region_id_list'] = $query->result_array()[$i]['region_id_list'];

            $row['region_id_list'] = str_replace(array('}', '{'), '', $row['region_id_list']);

            $region_id_array = explode(',', $row['region_id_list']);

            $region_name = '';
            $j = 0;
            // $row['temp'] = gettype($region_id_array);
            foreach ($region_id_array as $val) { //$row['temp'] = (int)$val;

                $query2 = $this->db->query("SELECT name FROM master.regions WHERE id = ?", $val)->result()[0]->name;

                if ($j == sizeof($region_id_array) - 1) {
                    $region_name .= $query2;
                } else
                    $region_name .= $query2 . "/";

                $j++;

            }

            $row['region_name'] = $region_name;

            $row['state_managers'] = $this->db->query("SELECT count(*) AS state_managers FROM  users.state_manager WHERE created_by = ?", $row['user_id'])->result()[0]->state_managers;


            $data[] = $row;
        }

        return $data;
    }


    function get_sourcing_partners_list_by_district($user_id = 0)
    {


        $query = $this->db->query("select * from users.partners where coordinator_id='" . $user_id . "'");


        $slno = 0;
        $data = array();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;

            $row['phone'] = $query->result()[$i]->phone;

            $data[] = $row;
        }

        return $data;
    }


    //get_sourcing_partners_list_by_district

    //Ends

    function do_get_country_list()
    {
        $query = $this->db->query("select * from master.country where active_status=1 or active_status=0;");
        $country = array();

        $i = 0;
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;

            $row['id'] = $slno;
            $row['country_id'] = $query->result()[$i]->id;
            $row['name'] = $query->result()[$i]->name;
            if ($query->result()[$i]->active_status == 1)
                $row['active_status'] = "1";
            else if ($query->result()[$i]->active_status == 0)
                $row['active_status'] = "0";
            $data[] = $row;
        }

        /*foreach ($query as $key) {
    		# code...
    		$country['sno']=$i;
    		$country['name']=$key->name;
			$country['sn']=$key->code;
    		$i++;


    	}*/

        return $data;


    }

    function get_sourcing_partner_admin_information($sourcing_partner_admin)
    {
        $query = $this->db->query('select * from users.spoc where user_id=' . $sourcing_partner_admin . ';')->result_array();
        return $query;
    }


    public function do_get_country()
    {
        $country_rec = $this->db->query("SELECT id,name from master.country order by name");

        if ($country_rec->num_rows()) {
            return $country_rec->result_array();
        } else {
            return false;
        }
    }

    function change_country_status($country_id, $country_status)
    {
        if ($country_status == "1") {
            $data = array(

                'active_status' => 0
            );
            $this->db->where('id', $country_id);
            $this->db->update("master.country", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            }
            return true;
        } else if ($country_status == "0") {
            $data = array(

                'active_status' => 1
            );
            $this->db->where('id', $country_id);
            $this->db->update("master.country", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            }
            return true;
        }
    }

    function do_edit_sourcing_head($data1)
    {

        $data = array(
            'name' => $data1['name'],
            'email' => $data1['email'],
            'phone' => $data1['phone'],
            'modified_by' => $data1['parent_id'],
            'modified_on' => date('Y-m-d'),

        );
        $this->db->where('user_id', $data1['user_id']);
        $this->db->update("users.sourcing_head", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'email' => $data1['email'],
                'modified_on' => date('Y-m-d')


            );
            $this->db->where('id', $data1['user_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }

    function do_edit_sourcing_partner_admin($data1)
    {

        $data = array(
            'name' => $data1['name'],
            'email' => $data1['email'],
            'phone' => $data1['phone']

        );
        $this->db->where('user_id', $data1['user_id']);
        $this->db->update("users.spoc", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'email' => $data1['email'],
                'created_on' => date('Y-m-d'),
                'modified_on' => date('Y-m-d'),
                'phone' => $data1['phone']
            );
            $this->db->where('id', $data1['user_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }




    function get_qualification_pack_name($q_id)
    {
        $query = $this->db->query('select * from master.qualification_pack where id=' . $q_id . '')->result()[0]->name;
        return $query;
    }

    //change_sourcing_head_status


    function change_sourcing_head_status($sourcing_id)
    {
        $status = $this->db->query('select * from users.sourcing_head where user_id=' . $sourcing_id . '')->result()[0]->active_status;


        if ($status == true) {
            $data = array(
                'active_status' => false,

            );
            $this->db->where('user_id', $sourcing_id);
            $this->db->update("users.sourcing_head", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => false,

                );
                $this->db->where('id', $sourcing_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        } else if ($status == false) {
            $data = array(
                'active_status' => true,

            );
            $this->db->where('user_id', $sourcing_id);
            $this->db->update("users.sourcing_head", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => true,

                );
                $this->db->where('id', $sourcing_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        }
    }


    function change_spoc_status($spoc_id)
    {
        $status = $this->db->query('select * from users.spoc where user_id=' . $spoc_id . '')->result()[0]->active_status;


        if ($status == true) {
            $data = array(
                'active_status' => false,

            );
            $this->db->where('user_id', $spoc_id);
            $this->db->update("users.spoc", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => false,

                );
                $this->db->where('id', $spoc_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        } else if ($status == false) {
            $data = array(
                'active_status' => true,

            );
            $this->db->where('user_id', $spoc_id);
            $this->db->update("users.spoc", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => true,

                );
                $this->db->where('id', $spoc_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        }
    }


    function change_dis_cor_status($dis_cor_id)
    {
        $status = $this->db->query('select * from users.district_coordinator where user_id=' . $dis_cor_id . '')->result()[0]->active_status;


        if ($status == true) {
            $data = array(
                'active_status' => false,

            );
            $this->db->where('user_id', $dis_cor_id);
            $this->db->update("users.district_coordinator", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => false,

                );
                $this->db->where('id', $dis_cor_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        } else if ($status == false) {
            $data = array(
                'active_status' => true,

            );
            $this->db->where('user_id', $dis_cor_id);
            $this->db->update("users.district_coordinator", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => true,

                );
                $this->db->where('id', $dis_cor_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        }
    }


    function change_sourcing_admin_status($sourcing_id)
    {
        $status = $this->db->query('select * from users.sourcing_admin where user_id=' . $sourcing_id . '')->result()[0]->active_status;
        if ($status == true) {
            $data = array(
                'active_status' => false,

            );
            $this->db->where('user_id', $sourcing_id);
            $this->db->update("users.sourcing_admin", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => false,

                );
                $this->db->where('id', $sourcing_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        } else if ($status == false) {
            $data = array(
                'active_status' => true,

            );
            $this->db->where('user_id', $sourcing_id);
            $this->db->update("users.sourcing_admin", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                $data1 = array(
                    'is_active' => true,

                );
                $this->db->where('id', $sourcing_id);
                $this->db->update("users.accounts", $data1);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    return true;
                }
            }
        }
    }

    function do_edit_district_coordinator($data1)
    {
        $data = array(
            'name' => $data1['name'],
            'email' => $data1['email'],
            'phone' => $data1['phone'],
            'district_id_list' => $data1['district_id_list']

        );
        $this->db->where('user_id', $data1['district_coordinator_id']);
        $this->db->update("users.district_coordinator", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'email' => $data1['email'],
                'is_active' => true,
                'created_on' => date('Y-m-d'),
                'modified_on' => date('Y-m-d')


            );
            $this->db->where('id', $data1['district_coordinator_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }

    function get_district_coordinator_heads($parent_id = 0)
    {
        $query = $this->db->query("select * from users.district_coordinator where created_by='" . $parent_id . "'");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;

            $row['active_status'] = $query->result()[$i]->active_status;

            $count = $this->db->query('select count(*) as count_sp from users.partners where coordinator_id=' . $row['user_id'] . '');
            $row['sourcing_partner_count'] = $count->result()[0]->count_sp;
            /*$row[]=$query->result()[$i]->country_id_list;*/
            /*
				Getting list of regional managers here
	         	*/

            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->district_id_list);
            $t = explode(',', $temp);


            $re = "";
            $no = 0;
            foreach ($t as $key) {
                # code...

                $query1 = $this->db->query("select name from master.district where id=" . $key . ";")->result()[0]->name;
                if ($no == sizeof($t) - 1)
                    $re .= $query1;
                else
                    $re .= $query1 . "/";
                $no++;
            }
            $row['district_name'] = $re;


            $data[] = $row;
        }

        return $data;
    }

/*    function change_qualification_status($qualification_id)
    {
        $status = $this->db->query('select * from master.qualification_pack where id=' . $qualification_id . '')->result()[0]->active_status;
        if ($status == true) {
            $data = array(
                'active_status' => false,

            );
            $this->db->where('id', $qualification_id);
            $this->db->update("master.qualification_pack", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                return true;
            }
        } else if ($status == false) {
            $data = array(
                'active_status' => true,

            );
            $this->db->where('id', $qualification_id);
            $this->db->update("master.qualification_pack", $data);
            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else if ($this->db->trans_status() === TRUE) {
                return true;
            }
        }

    }*/


    function do_edit_sourcing_admin($data1)
    {
        /*$ugi=$data['user_group_id'];*/
        $data = array(
            'name' => $data1['name'],
            'email' => $data1['email'],
            'phone' => $data1['phone'],
            'modified_by' => $data1['parent_id'],
            'modified_on' => date('Y-m-d')
        );
        $this->db->where('user_id', $data1['sourcing_admin_id']);
        $this->db->update("users.sourcing_admin", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {

            $data2 = array(
                'email' => $data1['email'],
                'modified_on' => date('Y-m-d')


            );
            $this->db->where('id', $data1['sourcing_admin_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
        }
    }


    function do_get_country_list_2()
    {
        $query = $this->db->query("select * from master.country where active_status=-1;");
        $country = array();

        $i = 0;
        $data = array();

        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $row['name'] = $query->result()[$i]->name;

            $data[] = $row;
        }

        /*foreach ($query as $key) {
    		# code...
    		$country['sno']=$i;
    		$country['name']=$key->name;
			$country['sn']=$key->code;
    		$i++;


    	}*/

        return $data;

    }

    function do_get_qualification_pack_2()
    {
        $query = $this->db->query("select * from master.sector;");
        $country = array();

        $i = 0;
        $data = array();

        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $row['name'] = $query->result()[$i]->name;
            $row['id'] = $query->result()[$i]->id;


            $data[] = $row;
        }

        /*foreach ($query as $key) {
    		# code...
    		$country['sno']=$i;
    		$country['name']=$key->name;
			$country['sn']=$key->code;
    		$i++;


    	}*/

        return $data;
    }

    function add_country_model($country_name)
    {
        $data = array(

            'active_status' => 1
        );
        $this->db->where('name', $country_name);
        $this->db->update("master.country", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        }
        return true;
    }

    function do_add_sourcing_head($data)
    {
        $user_group_id = $data['user_group_id'];
        $email = $data['email'];
        $pwd = $data['password'];
        $parent_id = $data['parent_id'];
        $country = $data['country'];
        $country_list = '{' . $country . '}';
        $created_on = date('Y-m-d');
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $sourcing_heads_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $email,
                'country_id_list' => $country_list,
                'created_by' => $parent_id,
                'created_on' => $created_on,
                'modified_by' => $parent_id,
                'modified_on' => $created_on,
                'active_status' => true
            );
            $this->db->insert('users.sourcing_head', $sourcing_heads_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function do_add_user_admin($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $user_group_id = $data['user_group_id'];
        $sourcing_head_id = $data['sourcing_head_id'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("INSERT into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");
        if ($this->db->affected_rows())
        {
            $user_id = $this->db->insert_id();
            $user_admins_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'country_id_list' => '{99}',
                'created_by' => $data['sourcing_head_id'],
                'created_on' => date('d-M-Y'),
                'modified_by' => $data['sourcing_head_id'],
                'modified_on' => date('d-M-Y'),
                'active_status' => true
            );

            $this->db->insert('users.sourcing_admin', $user_admins_data);
            if ($this->db->affected_rows()) return true;
            else return false;
        }
        else
            return false;
    }

    function get_all_candidates_list_by_region($user_id)
    {

        $query = $this->db->query('select id from master.state where region_id in (select region_id_list from users.regional_manager where user_id=' . $user_id . '');
        if ($query->num_rows())
            return $query->result_array();
        else
            return false;

    }

    function do_get_spoc_list($user_id = 0)
    {
        $query = $this->db->query("select * from users.spoc where created_by='" . $user_id . "'");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;


            $row['sno'] = $slno;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['active_status'] = $query->result()[$i]->active_status;


            $data[] = $row;
        }

        return $data;
    }

    function do_add_spoc($data = 0)
    {
        $user_group_id = $data['user_group_id'];
        $email = $data['email'];
        $pwd = $data['password'];
        $phone=$data['phone'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on,$phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");

        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $sourcing_admin_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $email,
                'phone' => $data['phone'],
                'created_by' => $data['parent_id'],
                'created_on' => $created_on,
                'active_status' => true
            );
            $this->db->insert('users.spoc', $sourcing_admin_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

   /* public function do_change_sourcing_partner_status($sourcing_partner_id)
    {
        $update_status_query1 = "UPDATE users.partners SET  active_status = NOT active_status

     	   WHERE user_id = ? ";

        $update_status_query2 = "UPDATE users.accounts SET  is_active = NOT is_active

     	  WHERE id = ? ";

        $update_status1 = $this->db->query($update_status_query1, array($sourcing_partner_id));

        $update_status = $this->db->query($update_status_query2, array($sourcing_partner_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }*/


    public function get_center_list_by_sourcing_partner($sourcing_partner_id)
    {
        /*$center_list_query = $this->db->query("SELECT * FROM users.centers WHERE partner_id = ?",array($sourcing_partner_id));*/

        $center_list_query = $this->db->query("SELECT  C.*,
	(SELECT string_agg(user_id::TEXT, ',') FROM users.associates WHERE center_id=C.id) associate_id_list,
	(SELECT string_agg(name, ',<br>') FROM users.associates WHERE center_id=C.id) associate_name_list
from users.centers AS C WHERE C.partner_id = ? ", $sourcing_partner_id);


        if ($center_list_query->num_rows()) {
            return $center_list_query->result_array();
        } else {
            return false;
        }
    }
    //Saurabh Sinha ends here

    //after demo saurabh sinha work
    function do_add_bd_head($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $user_group_id = $data['user_group_id'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("INSERT into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");
        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $bd_head_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['parent_id'],

                /*'user_type_id'=> $data['user_type_id'],*/
                'created_on' => date('Y-m-d')
            );
            $this->db->insert('users.user_admins', $bd_head_data);
            if ($this->db->affected_rows()) {
                $bd_head_ = array(
                    'user_id' => $user_id,
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $email,
                    'country_id_list' => '{' . $data['country'] . '}',
                    'created_by' => $data['parent_id'],
                    'created_on' => date('Y-m-d'),
                    'modified_by' => $data['parent_id'],
                    'modified_on' => date('Y-m-d'),
                    'active_status' => true

                );
                $this->db->insert('users.bd_head', $bd_head_);
                if ($this->db->affected_rows()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function do_update_user_admin_bd_head($data1, $data2, $where1, $where2)
    {
        //$this->db->insert('users.accounts', $user_data);
        $this->db->update('users.user_admins', $data1, $where1);
        $this->db->update('users.accounts', $data2, $where2);
        if ($this->db->affected_rows())
            return $this->db->affected_rows();
        else
            return false;
    }


    function get_bd_head_information($bd_head = 0)
    {
        $query = $this->db->query('select * from users.bd_head where user_id=' . $bd_head . '');
        return $query->result_array();
    }

    function do_edit_bd_head($data1)
    {
        $data = array(
            'name' => $data1['name'],
            'email' => $data1['email'],
            'phone' => $data1['phone'],
            'modified_by' => $data1['parent_id'],
            'modified_on' => date('Y-m-d')
        );
        $this->db->where('user_id', $data1['user_id']);
        $this->db->update("users.bd_head", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'email' => $data1['email'],
                'modified_on' => date('Y-m-d'));
            $this->db->where('id', $data1['user_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }

    function get_bd_admin_all()
    {
        $query = $this->db->query("select * from users.bd_admin order by name");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;

            $sh_id = $query->result()[$i]->created_by;
            if ($sh_id) {
                $sh_name = $this->db->query('select bh.*,ua.phone from
                    users.bd_head bh
                    left join users.accounts ua on ua.id=bh.user_id where bh.user_id=' . $sh_id . '')->result()[0]->name;

            } else {
                $sh_name = 'No BD head';
            }
            //get sourcing head name


            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['bd_head_name'] = $sh_name;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['active_status'] = $query->result()[$i]->active_status;

            $row['bd_admin_id'] = $query->result()[$i]->user_id;
            $data[] = $row;
        }

        return $data;
    }

    function do_add_bd_admin_superadmin($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $user_group_id = $data['user_group_id'];
        $created_on = date('Y-m-d');
        $bd_head = $data['bd_head'];
        $phone= $data['phone'];
        $result = $this->db->query("INSERT into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $bd_admin_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['bd_head'],
                /*'user_type_id'=> $data['user_type_id'],*/
                'created_on' => date('Y-m-d')
            );
            $this->db->insert('users.user_admins', $bd_admin_data);
            if ($this->db->affected_rows()) {
                //getting country of head here
                $admin_country = $this->db->query('select * from users.bd_head where user_id=' . $bd_head . '')->result()[0]->country_id_list;
                $bd_admin_ = array(
                    'user_id' => $user_id,
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'email' => $email,
                    'country_id_list' => $admin_country,
                    'created_by' => $data['bd_head'],
                    'created_on' => date('Y-m-d'),
                    'modified_by' => $data['parent_id'],
                    'modified_on' => date('Y-m-d'),
                    'active_status' => true

                );
                $this->db->insert('users.bd_admin', $bd_admin_);
                if ($this->db->affected_rows()) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //Common method for getting information for edit option
    function get_info($table, $id)
    {
        $query_result = $this->db->query('select * from users.' . $table . ' where user_id=' . $id . '')->result_array();
        return $query_result;
    }

    //Common method for changing the status between activate and deactivate
    function change_status($id, $table)
    {
        $status = $this->db->query('select * from users.' . $table . ' where user_id=' . $id . '')->result()[0]->active_status;

        if (gettype($status) == 'integer')          //To change
        {
            if ($status == 1) {
                $data = array(
                    'active_status' => 0,

                );
                $this->db->where('user_id', $id);
                $this->db->update('users.' . $table . '', $data);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    $data1 = array(
                        'is_active' => false,

                    );
                    $this->db->where('id', $id);
                    $this->db->update("users.accounts", $data1);
                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        // generate an error... or use the log_message() function to log your error
                        return false;
                    } else if ($this->db->trans_status() === TRUE) {
                        return true;
                    }
                }
            } else if ($status == 0) {
                $data = array(
                    'active_status' => 1,

                );
                $this->db->where('user_id', $id);
                $this->db->update('users.' . $table . '', $data);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    $data1 = array(
                        'is_active' => true,

                    );
                    $this->db->where('id', $id);
                    $this->db->update("users.accounts", $data1);
                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        // generate an error... or use the log_message() function to log your error
                        return false;
                    } else if ($this->db->trans_status() === TRUE) {
                        return true;
                    }
                }
            }
        } else {

            if ($status == true) {
                $data = array(
                    'active_status' => false,

                );
                $this->db->where('user_id', $id);
                $this->db->update('users.' . $table . '', $data);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    $data1 = array(
                        'is_active' => false,

                    );
                    $this->db->where('id', $id);
                    $this->db->update("users.accounts", $data1);
                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        // generate an error... or use the log_message() function to log your error
                        return false;
                    } else if ($this->db->trans_status() === TRUE) {
                        return true;
                    }
                }
            } else if ($status == false) {
                $data = array(
                    'active_status' => true,

                );
                $this->db->where('user_id', $id);
                $this->db->update('users.' . $table . '', $data);
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {
                    // generate an error... or use the log_message() function to log your error
                    return false;
                } else if ($this->db->trans_status() === TRUE) {
                    $data1 = array(
                        'is_active' => true,

                    );
                    $this->db->where('id', $id);
                    $this->db->update("users.accounts", $data1);
                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {
                        // generate an error... or use the log_message() function to log your error
                        return false;
                    } else if ($this->db->trans_status() === TRUE) {
                        return true;
                    }
                }
            }
        }


    }




    public function do_add_bd_regional_manager($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $created_on = date('Y-m-d');
        $user_group_id = $data['user_group_id'];
        $phone=$data['phone'];
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

        if ($this->db->affected_rows())
        {
            $user_id = $this->db->insert_id();
            $bd_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'region_id_list' => $data['region_id_list'],
                'created_by' => $data['parent_id'],
                'modified_by' => $data['parent_id'],
                'created_on' => $created_on,
                'modified_on' => $created_on
            );

            $this->db->insert('users.bd_regional_manager', $bd_data);
            if ($this->db->affected_rows())
                return true;
            else
                return false;
        }
        else
            return false;
    }

    public function do_update_bd_regional_manager($data)
    {

        /*$user_group_id=$this->db->query("SELECT value from master.list
									where code='L0001' and lower(name)=?",strtolower('regional manager'))->row()->value;
	*/

        $email = $data['email'];
        $phone= $data['phone'];
        $modified_on = date('Y-m-d');
        $result = $this->db->query("update users.accounts set email=?, modified_on = ?,phone=? where id= ? ", array($email, $modified_on, $phone,$data['user_id']));


        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            /*$sourcing_head_id = $this->db->query("SELECT created_by FROM users.sourcing_admin WHERE user_id = ?",$data["parent_id"])->result()[0]->created_by;*/

            $user_id = $data['user_id'];
            $sourcing_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'region_id_list' => $data['region_id_list'],
                'modified_on' => $modified_on

            );
            $update_query = $this->db->query("update users.bd_regional_manager set name=?,email=?, modified_on = ? ,phone = ?, region_id_list = ? where user_id= ? ", array($data['name'], $data['email'], $modified_on, $data['phone'], $data['region_id_list'], $user_id));
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    function do_add_user_admin_bd($data)
    {
        $email = $data['email'];
        $pwd = $data['password'];
        $user_group_id = $data['user_group_id'];
        $phone= $data['phone'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("INSERT into users.accounts(email,pwd,user_group_id,created_on,phone) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on',$phone)");
        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $user_admins_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'parent_id' => $data['parent_id'],

                /*'user_type_id'=> $data['user_type_id'],*/
                'created_on' => date('Y-m-d')
            );
            $this->db->insert('users.user_admins', $user_admins_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_bd_district_coordinator_heads($parent_id = 0)
    {
        $query = $this->db->query("SELECT dc.*,ua.phone from users.bd_district_coordinator dc
                                    left join users.accounts ua on ua.id=dc.user_id
                                    where created_by='" . $parent_id . "'");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;

            $row['active_status'] = $query->result()[$i]->active_status;


            /*$row[]=$query->result()[$i]->country_id_list;*/
            /*
				Getting list of regional managers here
	         	*/

            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->district_id_list);
            $t = explode(',', $temp);


            $re = "";
            $no = 0;
            if(sizeof($t)>0)
            {
                  foreach ($t as $key) {
                    # code...

                    $query1 = $this->db->query("select name from master.district where id=" . $key . ";")->result()[0]->name;
                    if ($no == sizeof($t) - 1)
                        $re .= $query1;
                    else
                        $re .= $query1 . "/";
                    $no++;
                }
                 $row['district_name'] = $re;
            }
            else
            {
                 $row['district_name'] = 'No District';
            }




            $data[] = $row;
        }

        return $data;
    }


    function get_bd_district_coordinator_heads_all()
    {
        $query = $this->db->query("select D.*, R.name as bd_regional_manager_name from users.bd_district_coordinator as D left join users.bd_regional_manager as R on R.user_id= D.created_by;");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;

            $row['active_status'] = $query->result()[$i]->active_status;

            $row['bd_regional_manager_name'] = $query->result()[$i]->bd_regional_manager_name;


            /*$row[]=$query->result()[$i]->country_id_list;*/
            /*
				Getting list of regional managers here
	         	*/

            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->district_id_list);
            $t = explode(',', $temp);


            $re = "";
            $no = 0;
            foreach ($t as $key) {
                # code...

                $query1 = $this->db->query("select name from master.district where id=" . $key . ";")->result()[0]->name;
                if ($no == sizeof($t) - 1)
                    $re .= $query1;
                else
                    $re .= $query1 . "/";
                $no++;
            }
            $row['district_name'] = $re;


            $data[] = $row;
        }

        return $data;
    }

    public function do_add_bd_district_coordinators($data)
    {
        $user_group_id = $data['user_group_id'];
        $email = $data['email'];
        $pwd = $data['password'];
        $parent_id = $data['parent_id'];
        $created_on = date('Y-m-d');
        $district_id_list = $data['district_id_list'];
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $sourcing_heads_data = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $email,
                'district_id_list' => $district_id_list,
                'created_by' => $parent_id,
                'created_on' => $created_on,
                'modified_by' => $parent_id,
                'modified_on' => $created_on,
                'active_status' => true
            );
            $this->db->insert('users.bd_district_coordinator', $sourcing_heads_data);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function do_edit_bd_district_coordinator($data1)
    {
        $data = array(
            'name' => $data1['name'],
            'email' => $data1['email'],
            'district_id_list' =>  $data1['district_id_list'],
            'phone' => $data1['phone']

        );
        $this->db->where('user_id', $data1['district_coordinator_id']);
        $this->db->update("users.bd_district_coordinator", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'email' => $data1['email'],
                'is_active' => true,
                'created_on' => date('Y-m-d'),
                'modified_on' => date('Y-m-d')


            );
            $this->db->where('id', $data1['district_coordinator_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }

    function get_bd_executive_heads($parent_id = 0)
    {
        $query = $this->db->query("SELECT be.*,ua.phone from users.bd_executive be
                                    left join users.accounts ua on ua.id=be.user_id
                                    where created_by='" . $parent_id . "'");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $total_emp = $this->db->query('select count(*) as count_emp from users.employers where created_by=' . $query->result()[$i]->user_id . '')->result()[0]->count_emp;
            $row['count_emp'] = $total_emp;
            $row['active_status'] = $query->result()[$i]->active_status;


            $data[] = $row;
        }

        return $data;
    }


    public function do_add_bd_executive($data)
    {
        $user_group_id = $data['user_group_id'];
        $email = $data['email'];
        $pwd = $data['password'];
        $parent_id = $data['parent_id'];
        $created_on = date('Y-m-d');
        $result = $this->db->query("insert into users.accounts(email,pwd,user_group_id,created_on) values('$email',crypt('$pwd', gen_salt('bf')),$user_group_id,'$created_on')");

        //$this->db->insert('users.accounts', $user_data);
        if ($this->db->affected_rows()) {
            $user_id = $this->db->insert_id();
            $bd_exe = array(
                'user_id' => $user_id,
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $email,
                'created_by' => $parent_id,
                'created_on' => $created_on,
                'modified_by' => $parent_id,
                'modified_on' => $created_on,
                'active_status' => true
            );
            $this->db->insert('users.bd_executive', $bd_exe);
            if ($this->db->affected_rows()) {
                return true;
            } else {
                return false;
            }
            return true;
        } else {
            return false;
        }
    }

    function do_edit_bd_executive($data1)
    {
        $data = array(
            'name' => $data1['name'],
            'email' => $data1['email'],
            'phone' => $data1['phone']
        );
        $this->db->where('user_id', $data1['executive_id']);
        $this->db->update("users.bd_executive", $data);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'email' => $data1['email'],
                'created_on' => date('Y-m-d'),
                'modified_on' => date('Y-m-d')

            );
            $this->db->where('id', $data1['executive_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }


    function do_update_bd_admin($data)
    {
        $data1 = array(
            'email' => $data['email'],
            'modified_on' => date('Y-m-d')
        );
        $this->db->where('id', $data['user_id']);
        $this->db->update("users.accounts", $data1);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone']
            );
            $this->db->where('user_id', $data['user_id']);
            $this->db->update("users.bd_admin", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }

    function get_employers_list($user_id = 0)
    {
        $query = $this->db->query("select * from users.employers where created_by ='" . $user_id . "'");
        $slno = 0;
        $data = array();
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['phone'] = $query->result()[$i]->phone;

            $data[] = $row;
        }

        return $data;
    }

    function get_bd_regional_managers_list_view($user_id = 0)
    {

        $query = $this->db->query("SELECT * from users.bd_regional_manager rm
                                    LEFT join users.account ua on ua.id=rm.user_id
                                    WHERE created_by='" . $user_id . "'");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $query->result()[$i]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['region_id_list'] = $query->result()[$i]->region_id_list;
            $temp = str_replace(array('}', '{'), '', $query->result()[$i]->region_id_list);
            $t = explode(',', $temp);
            $re = "";
            $no = 0;
            foreach ($t as $key) {
                # code...

                $query1 = $this->db->query("select name from master.regions where id=" . $key . ";")->result()[0]->name;
                if ($no == sizeof($t) - 1)
                    $re .= $query1;
                else
                    $re .= $query1 . "/";
                $no++;
            }
            $row['regions'] = $re;

            $data[] = $row;
        }

        return $data;
    }

    function get_bd_emp_heads($parent_id = 0)
    {
        $query = $this->db->query("select * from users.employers where recruitment_partner_id='" . $parent_id . "'");
        //$totalFiltered=$sourcing_coordinators_recs->num_rows();
        /*$slno=$pg;*/
        $data = array();
        $slno = 0;
        for ($i = 0; $i < $query->num_rows(); $i++) {
            $row = Array();
            $slno++;
            $row['user_id'] = $query->result()[$i]->user_id;
            $row['sno'] = $slno;
            $row['name'] = $query->result()[$i]->name;
            $row['email'] = $this->db->query('select * from users.accounts where id=' . $query->result()[$i]->user_id . '')->result()[0]->email;
            $row['phone'] = $query->result()[$i]->phone;
            $row['contact_person'] = $query->result()[$i]->spoc_name;
            $row['contact_phone'] = $query->result()[$i]->spoc_phone;
            $row['active_status'] = $query->result()[$i]->active_status;
            $data[] = $row;
        }

        return $data;
    }


    function do_edit_employers($data1)
    {

        $data = array(
            'name' => $data1['name'],
            'phone' => $data1['phone'],
            'spoc_name' => $data1['spoc_name'],
            'spoc_phone' => $data1['spoc_phone'],
             'modified_by' => $data1['modified_by'],
             'modified_on' => $data1['modified_on']

        );

        $this->db->where('user_id', $data1['emp_id']);
        $this->db->update("users.employers", $data);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            // generate an error... or use the log_message() function to log your error
            return false;
        } else if ($this->db->trans_status() === TRUE) {
            $data2 = array(
                'email' => $data1['email'],
                'modified_on' => date('Y-m-d')
            );
            $this->db->where('id', $data1['emp_id']);
            $this->db->update("users.accounts", $data2);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                // generate an error... or use the log_message() function to log your error
                return false;
            } else
                return true;
            /*return true;*/
        }
    }


    /*function do_get_verticals($parent_id = 0, $active_status = 1)
    {
        $active_status_list = '';

        if ($active_status == 0) {
            //fetch all regions

            $active_status_list = '(0,1)';

        } else {
            $active_status_list = '(1)';
        }

        $query1 = $this->db->query("select   V.* , (select count(S.*) as sectors from master.sector S where V.id = S.vertical_id ), (Select string_agg(VM.name,',') as vertical_manager_name_list from users.rs_vertical_manager VM where  V.id = Any(VM.vertical_id_list) ) from master.vertical V order by V.name");
        $j = 0;
        if ($query1->num_rows()) {
            foreach ($query1->result() as $val) {

                $verticals_rec[$j]['name'] = $val->name;
                $verticals_rec[$j]['id'] = $val->id;
                $verticals_rec[$j]['sectors'] = $val->sectors;
                $verticals_rec[$j]['active_status'] = $val->active_status;

                $verticals_rec[$j]['vertical_manager_name_list'] = 'Not Assigned';
                if ($val->vertical_manager_name_list != '') {
                    $verticals_rec[$j]['vertical_manager_name_list'] = $val->vertical_manager_name_list;
                }


                $j++;
            }

            return $verticals_rec;
        } else {

            return false;

        }

    }
*/

    function get_sector_list_by_vertical($vertical_id, $active_status)
    {
        $active_status_list = '';
        if ($active_status == 0) {

            $active_status_list = '{0,1}';

        } else {
            $active_status_list = '{1}';
        }


        $sector_list_query = "SELECT * FROM master.sector WHERE vertical_id = ? AND active_status = Any(?) ORDER BY name";

        $sector_list_rec = $this->db->query($sector_list_query, array($vertical_id, $active_status_list))->result();

        return $sector_list_rec;

    }


    function do_add_rs_vertical($data)
    {
        //return true;
        $insert_vertical_query = "INSERT INTO master.vertical (code,name,created_by,created_on)
                           VALUES (?,?,?,?)";

        $insert_vertical = $this->db->query($insert_vertical_query, array($data['code'], $data['name'], $data['created_by'], date('Y-m-d')));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function do_update_rs_vertical($vertical_id, $data)
    {


        $update_vertical_query = "UPDATE master.vertical SET code = ? , name = ? , modified_by= ?, modified_on = ? WHERE id = ?";

        $update_vertical = $this->db->query($update_vertical_query, array($data['code'], $data['name'], $data['modified_by'], date('Y-m-d'), $vertical_id));


        if ($this->db->affected_rows()) {

            return true;
        } else {


            return false;
        }

    }


    function do_check_duplicate_vertical_name($vertical_name,$id)
    {
        $vertical_name_query = $this->db->query("SELECT * FROM master.vertical WHERE Lower(name) = ? AND id != ?", array($vertical_name,$id));

        if ($vertical_name_query->num_rows()) {
            return false;
        } else {

            return true;
        }

    }

    function do_check_duplicate_vertical_code($vertical_code,$id)
    {
        $vertical_code_query = $this->db->query("SELECT * FROM master.vertical WHERE Lower(code) = ? AND id != ?", array($vertical_code,$id));


        if ($vertical_code_query->num_rows()) {
            return false;
        } else {

            return true;
        }

    }


    function get_vertical_by_id($vertical_id)
    {

        $vertical_data_query = "SELECT * FROM master.vertical WHERE id = ?";

        $vertical_data = $this->db->query($vertical_data_query, array($vertical_id));

        if ($vertical_data->num_rows()) {
            return $vertical_data->result_array();
        } else {
            return false;
        }
    }


    function do_get_sectors($parent_id, $active_status)
    {


        $active_status_list = '';
        if ($active_status == 0) {
            //fetch all regions

            $active_status_list = '(0,1)';

        } else {
            $active_status_list = '(1)';
        }

        $query1 = $this->db->query("select   S.* , (Select V.name from master.vertical V where V.id = S.vertical_id) as vertical_name, (Select string_agg(SM.name,',') as sector_manager_name_list from users.rs_sector_manager SM where  S.id = Any(SM.sector_id_list) ) from master.sector S order by S.name");
        $j = 0;
        if ($query1->num_rows()) {
            foreach ($query1->result() as $val) {

                $sectors_rec[$j]['name'] = $val->name;
                $sectors_rec[$j]['id'] = $val->id;
                $sectors_rec[$j]['vertical_name'] = $val->vertical_name;
                /*$verticals_rec[$j]['sectors'] = $val->sectors;*/
                $sectors_rec[$j]['active_status'] = $val->active_status;

                $sectors_rec[$j]['sector_manager_name_list'] = 'Not Assigned';
                if ($val->sector_manager_name_list != '') {
                    $sectors_rec[$j]['sector_manager_name_list'] = $val->sector_manager_name_list;
                }


                $j++;
            }

            return $sectors_rec;
        } else {

            return false;

        }
    }

    function do_get_verticals_options($parent_id = 0, $active_status = 1)
    {
        $bool_active_status = false;
        if ($active_status == 1)
            $bool_active_status = True;

        $query = "SELECT * from master.vertical WHERE active_status = ?";

        $vertical_res = $this->db->query($query, array($bool_active_status));

        if ($vertical_res->num_rows()) {
            return $vertical_res->result_array();
        } else {
            return array();
        }

    }

    function get_sector_by_id($sector_id)
    {
        $sector_data_query = "SELECT * FROM master.sector WHERE id = ?";

        $sector_data = $this->db->query($sector_data_query, array($sector_id));

        if ($sector_data->num_rows()) {
            return $sector_data->result_array();
        } else {
            return false;
        }
    }


    function do_add_rs_sector($data)
    {
        //return true;
        $insert_sector_query = "INSERT INTO master.sector (name,vertical_id)
				                           VALUES (?,?)";

        $insert_sector = $this->db->query($insert_sector_query, array($data['name'], $data['vertical_id']));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function do_update_rs_sector($data)
    {
        $update_sector_query = "UPDATE master.sector SET name = ? ,vertical_id = ? WHERE id = ?";

        $update_sector = $this->db->query($update_sector_query, array($data['name'], $data['vertical_id'], $data['sector_id']));


        if ($this->db->affected_rows()) {

            return true;

        } else {


            return false;
        }
    }

    function do_change_vertical_status($vertical_id)
    {
        $update_status_query = "UPDATE master.vertical SET  active_status = NOT active_status WHERE id=?";

        $update_status = $this->db->query($update_status_query, array($vertical_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }

    function do_change_sector_status($sector_id)
    {
        $update_status_query = "UPDATE master.sector SET  active_status = NOT active_status WHERE id=?";

        $update_status = $this->db->query($update_status_query, array($sector_id));

        if ($this->db->affected_rows()) {
            return true;
        } else {
            return false;
        }
    }


    //BEGIN: RS HEAD - By George
    function get_rs_heads($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("RH.rs_head_name", "RH.rs_head_phone", "RH.rs_head_email");
        $arrSortByColumns = array(
            0 => null,
            1 => 'RH.rs_head_name',
            2 => 'RH.rs_head_phone',
            3 => 'RH.rs_head_email',
            4 => null,
            5 => null,
            6 => null
        );

        $strQuery = "SELECT COUNT(RH.rs_head_id)::BIGINT AS total_record_count
					 FROM   users.vw_rs_head AS RH
					 WHERE  TRUE ";

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(RH.rs_head_id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_rs_head AS RH
			             WHERE		TRUE ";
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	RH.rs_head_id,
									RH.rs_head_name,
									RH.rs_head_email,
									RH.rs_head_phone,
									RH.active_status,
									RH.vertical_manager_count
			             FROM    	users.vw_rs_head AS RH
			             WHERE		TRUE ";
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->rs_head_name;
                $ResponseRow[] = $QueryRow->rs_head_phone;
                $ResponseRow[] = $QueryRow->rs_head_email;
                $ResponseRow[] = ($QueryRow->vertical_manager_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View Vertical Manager List" onclick="ViewVerticalManagerList(' . "'" . $QueryRow->rs_head_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->vertical_manager_count . '</a></center>' : '<center><p>' . $QueryRow->vertical_manager_count . '</p></center>';
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->rs_head_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Recruitment Support Head Details" href="javascript:void(0);" onclick="EditRecruitmentSupportHead(' . "'" . $QueryRow->rs_head_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_rs_head_detail($rs_head_id = 0)
    {
        $strQuery = "SELECT  	RH.rs_head_id,
								RH.rs_head_name,
								RH.rs_head_email,
								RH.rs_head_phone
					 FROM    	users.vw_rs_head AS RH
					 WHERE		RH.rs_head_id = $rs_head_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function change_rs_head_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_head";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "Recruitment Support Head Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "Recruitment Support Head Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function save_rs_head_detail($PageRequestData)
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        if (intval($PageRequestData['id']) < 1) //ADD NEW RS HEAD
        {
            $strQuery = "INSERT INTO users.accounts (email, pwd, user_group_id) VALUES (";
            $strQuery .= "'" . $PageRequestData['email'] . "', ";
            $strQuery .= "CRYPT('" . $PageRequestData['password'] . "', GEN_SALT('bf')), ";
            $strQuery .= ROLE_RS_HEAD .")";


            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $user_id = $this->db->insert_id();
                $strQuery = "INSERT INTO users.rs_head (user_id, name, phone, country_id_list, created_by) VALUES (";
                $strQuery .= $user_id . ", ";
                $strQuery .= "'" . $PageRequestData['name'] . "', ";
                $strQuery .= "'" . $PageRequestData['phone'] . "', ";
                $strQuery .= "ARRAY[99], ";
                $strQuery .= "1)";

                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Head Details Added Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Head Details Not Added";
                }
            } else {
                return false;
            }
        } else //UPDATE EXISTING RS HEAD
        {
            $strQuery = "UPDATE users.accounts SET email='" . $PageRequestData['email'] .  "' WHERE id=" . $PageRequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_head SET ";
                $strQuery .= "name='" . $PageRequestData['name'] . "', ";
                $strQuery .= "phone='" . $PageRequestData['phone'] . "', ";
                $strQuery .= "country_id_list=ARRAY[99], ";
                $strQuery .= "modified_by=1, ";
                $strQuery .= "modified_on='" . date("Y-m-d h:i:s a") . "' ";
                $strQuery .= "WHERE user_id=" . $PageRequestData['id'];
                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Head Details Updated Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Head Details Not Updated";
                }
            }
        }

        return $ResponseData;
    }

    function get_vertical_managers_for_rs_head($rs_head_id = 0)
    {
        $strQuery = "SELECT  	VM.name AS vertical_manager_name,
								A.email AS vertical_manager_email,
								VM.phone AS vertical_manager_phone,
								(SELECT STRING_AGG(name, ', ' ORDER BY name) FROM master.vertical WHERE id=ANY(VM.vertical_id_list)) AS vertical_name_list
					 FROM    	users.rs_vertical_manager AS VM
					 LEFT JOIN	users.accounts AS A ON A.id = VM.user_id
					 WHERE		VM.rs_head_id = $rs_head_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function get_candidate_list($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("VW.name", "VW.mobile", "VW.email", "VW.gender_name", "VW.dob", "VW.aadhaar_num", "VW.qualification", "VW.total_experience", "VW.state_name", "VW.district_name", "VW.relocate_status_name", "VW.created_on");
        $arrSortByColumns = array(
            0 => null,
            1 => 'VW.name',
            2 => 'VW.mobile',
            3 => 'VW.email',
            4 => 'VW.gender_name',
            5 => "VW.dob",
            6 => 'VW.aadhaar_num',
            7 => 'VW.qualification',
            8 => 'VW.total_experience',
            9 => 'VW.state_name',
            10 => 'VW.district_name',
            11 => 'VW.relocate_status_name',
            12 => 'VW.created_on'
        );

        $strQuery = "SELECT COUNT(VW.id)::BIGINT AS total_record_count
					 FROM   users.vw_candidates AS VW
					 WHERE  TRUE ";

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(VW.id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_candidates AS VW
			             WHERE		TRUE ";
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	VW.id AS candidate_id,
                                    VW.name AS candidate_name,
                                    VW.mobile AS candidate_mobile,
                                    VW.email AS candidate_email,
                                    VW.gender_name AS candidate_gender,
                                    VW.dob AS candidate_dob,
                                    VW.total_experience AS candidate_experience,
                                    VW.state_name AS candidate_state,
                                    VW.district_name AS candidate_district,
                                    VW.relocate_status_name AS candidate_relocate_status,
                                    VW.qualification AS candidate_qualification,
                                    VW.aadhaar_num AS candidate_aadhaar,
                                    VW.created_on AS registered_on
			             FROM    	users.vw_candidates AS VW
			             WHERE	TRUE";
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->candidate_name;
                $ResponseRow[] = $QueryRow->candidate_mobile;
                $ResponseRow[] = $QueryRow->candidate_email;
                $ResponseRow[] = $QueryRow->candidate_gender;
                $ResponseRow[] = $QueryRow->candidate_dob;
                $ResponseRow[] = $QueryRow->candidate_aadhaar;
                $ResponseRow[] = $QueryRow->candidate_qualification;
                $ResponseRow[] = $QueryRow->candidate_experience;
                $ResponseRow[] = $QueryRow->candidate_state;
                $ResponseRow[] = $QueryRow->candidate_district;
                $ResponseRow[] = $QueryRow->candidate_relocate_status;
                $ResponseRow[] = $QueryRow->registered_on;
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }
    //END: RS HEAD - By George


    //BEGIN: RS ADMIN - By George
    function get_rs_admins($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("RA.rs_admin_name", "RA.rs_admin_phone", "RA.rs_admin_email", "RA.rs_head_name");
        $arrSortByColumns = array(
            0 => null,
            1 => 'RA.rs_admin_name',
            2 => 'RA.rs_admin_phone',
            3 => 'RA.rs_admin_email',
            4 => 'RA.rs_head_name',
            5 => null,
            6 => null,
            7 => null
        );

        $strQuery = "SELECT COUNT(RA.rs_admin_id)::BIGINT AS total_record_count
					 FROM   users.vw_rs_admin AS RA
					 WHERE	TRUE ";

        if ($user['user_group_id'] == ROLE_RS_HEAD)
            $strQuery .= " AND rs_head_id = " . $user['id'];

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(RA.rs_admin_id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_rs_admin AS RA
			             WHERE		TRUE ";

            if ($user['user_group_id'] == 15)
                $strQuery .= " AND RA.rs_head_id = " . $user['id'];

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	RA.rs_admin_id,
									RA.rs_admin_name,
									RA.rs_admin_phone,
									RA.rs_admin_email,
									RA.rs_head_name,
									RA.active_status
			             FROM    	users.vw_rs_admin AS RA
			             WHERE		TRUE ";

            if ($user['user_group_id'] == 15)
                $strQuery .= " AND RA.rs_head_id = " . $user['id'];

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->rs_admin_name;
                $ResponseRow[] = $QueryRow->rs_admin_phone;
                $ResponseRow[] = $QueryRow->rs_admin_email;
                $ResponseRow[] = $QueryRow->rs_head_name;
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->rs_admin_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';
                $ResponseRow[] = '<a class="btn btn-sm btn-primary" title="Edit RS Admin Details" href="javascript:void(0);" onclick="EditRSAdminDetails(' . "'" . $QueryRow->rs_admin_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_rs_admin_detail($rs_admin_id = 0)
    {
        $strQuery = "SELECT  	RA.user_id AS rs_admin_id,
								RA.name AS rs_admin_name,
								A.email AS rs_admin_email,
								RA.phone AS rs_admin_phone,
                                RA.rs_head_id
					 FROM    	users.rs_admin AS RA
					 LEFT JOIN	users.accounts AS A ON A.id = RA.user_id
					 WHERE		RA.user_id = $rs_admin_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function change_rs_admin_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_admin";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "Recruitment Support Admin Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "Recruitment Support Admin Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function save_rs_admin_detail($PageRequestData)
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        if (intval($PageRequestData['id']) < 1) //ADD NEW RS HEAD
        {
            $strQuery = "INSERT INTO users.accounts (email, pwd, user_group_id) VALUES (";
            $strQuery .= "'" . $PageRequestData['email'] . "', ";
            $strQuery .= "CRYPT('" . $PageRequestData['password'] . "', GEN_SALT('bf')), ";
            $strQuery .= ROLE_RS_ADMIN .")";

            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $user_id = $this->db->insert_id();
                $strQuery = "INSERT INTO users.rs_admin (user_id, name, phone, rs_head_id, created_by) VALUES (";
                $strQuery .= $user_id . ", ";
                $strQuery .= "'" . $PageRequestData['name'] . "', ";
                $strQuery .= "'" . $PageRequestData['phone'] . "', ";
                $strQuery .= "'" . $PageRequestData['rs_head_id'] . "', ";
                $strQuery .= "1)";

                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Admin Details Added Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Admin Details Not Added";
                }
            } else {
                return false;
            }
        } else //UPDATE EXISTING RS HEAD
        {
            $strQuery = "UPDATE users.accounts SET email='" . $PageRequestData['email'] . "' WHERE id=" . $PageRequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_admin SET ";
                $strQuery .= "name='" . $PageRequestData['name'] . "', ";
                $strQuery .= "phone='" . $PageRequestData['phone'] . "', ";
                $strQuery .= "rs_head_id=" . $PageRequestData['rs_head_id'] . ",";
                $strQuery .= "modified_by=1, ";
                $strQuery .= "modified_on='" . date("Y-m-d h:i:s a") . "' ";
                $strQuery .= "WHERE user_id=" . $PageRequestData['id'];
                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Admin Details Updated Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Admin Details Not Updated";
                }
            }
        }

        return $ResponseData;
    }

    function get_rs_head_list_data()
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        $strQuery = "SELECT  	user_id AS rs_head_id,
								name AS rs_head_name
					 FROM    	users.rs_head
					 ORDER BY	rs_head_name";

        $QueryData = $this->db->query($strQuery);
        return $QueryData->result_array();
    }
    //END: RS ADMIN - By George


    //BEGIN: RS VERTICAL MANAGERS - By George
    function get_rs_vertical_managers($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("VM.rs_vertical_manager_name", "VM.rs_vertical_manager_phone", "VM.rs_vertical_manager_email", "VM.rs_head_name");
        $arrSortByColumns = array(
            0 => null,
            1 => 'VM.rs_vertical_manager_name',
            2 => 'VM.rs_vertical_manager_phone',
            3 => 'VM.rs_vertical_manager_email',
            4 => 'VM.rs_head_name',
            5 => null,
            6 => null,
            7 => null
        );

        $strCondition = "";
        $strDisableActionButtons = " disabled=\"disabled\" ";
        switch ($user['user_group_id']) {
            case ROLE_RS_HEAD:
                $strCondition = " AND VM.rs_head_id = " . $user['id'] . " ";
                break;

            case ROLE_RS_ADMIN:
                $strQuery = "SELECT rs_head_id FROM users.rs_admin WHERE user_id=" . $user['id'];
                $strRsHeadId = $this->db->query($strQuery)->row()->rs_head_id;
                $strCondition = " AND VM.rs_head_id = " . $strRsHeadId . " ";
                $strCondition = " ";
                break;
        }

        $strQuery = "SELECT COUNT(VM.rs_vertical_manager_id)::BIGINT AS total_record_count
					 FROM   users.vw_rs_vertical_manager AS VM
					 WHERE	TRUE ";
        $strQuery .= $strCondition;

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(VM.rs_vertical_manager_id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_rs_vertical_manager AS VM
			             WHERE		TRUE ";

            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	VM.rs_vertical_manager_id,
									VM.rs_vertical_manager_name,
									VM.rs_vertical_manager_email,
									VM.rs_vertical_manager_phone,
									VM.vertical_name_list,
									VM.active_status,
									VM.rs_head_name,
									VM.sector_manager_count
			             FROM    	users.vw_rs_vertical_manager AS VM
			             WHERE		TRUE ";

            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->rs_vertical_manager_name;
                $ResponseRow[] = $QueryRow->rs_vertical_manager_phone;
                $ResponseRow[] = $QueryRow->rs_vertical_manager_email;
                $ResponseRow[] = $QueryRow->vertical_name_list;
                $ResponseRow[] = $QueryRow->rs_head_name;
                $ResponseRow[] = ($QueryRow->sector_manager_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View Sector Manager List" onclick="ViewSectorManagerList(' . "'" . $QueryRow->rs_vertical_manager_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->sector_manager_count . '</a></center>' : '<center><p>' . $QueryRow->sector_manager_count . '</p></center>';
                $ResponseRow[] = ($user['user_group_id'] == ROLE_RS_ADMIN) ? '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->rs_vertical_manager_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;" >' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>' : '';
                $ResponseRow[] = ($user['user_group_id'] == ROLE_RS_ADMIN) ? '<a class="btn btn-sm btn-danger" title="Edit RS Vertical Manager Details" href="javascript:void(0);" onclick="EditRSVerticalManager(' . "'" . $QueryRow->rs_vertical_manager_id . "'" . ')"><i class="icon-android-create" ' . $strDisableActionButtons . '></i></a>' : '';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_rs_vertical_manager_detail($rs_vertical_manager_id = 0)
    {
        $strQuery = "SELECT  	VM.user_id AS rs_vertical_manager_id,
								VM.name AS rs_vertical_manager_name,
								A.email AS rs_vertical_manager_email,
								VM.phone AS rs_vertical_manager_phone,
								array_to_string(VM.vertical_id_list, ',') AS vertical_id_list
					 FROM    	users.rs_vertical_manager AS VM
					 LEFT JOIN	users.accounts AS A ON A.id = VM.user_id
					 WHERE		VM.user_id = $rs_vertical_manager_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function save_rs_vertical_manager_detail($PageRequestData)
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        $VerticalIdList = implode(',', $PageRequestData['vertical_id_list']);
        $VerticalIdList = 'ARRAY[' . $VerticalIdList . ']::INT[]';

        $strQuery = "SELECT rs_head_id FROM users.rs_admin WHERE user_id = " . $PageRequestData['user_id'];
        $strRsHeadId = $this->db->query($strQuery)->row()->rs_head_id;

        if (intval($PageRequestData['id']) < 1) //ADD NEW RS VERTICAL MANAGER
        {
            $strQuery = "INSERT INTO users.accounts (email, pwd, user_group_id) VALUES (";
            $strQuery .= "'" . $PageRequestData['email'] . "', ";
            $strQuery .= "CRYPT('" . $PageRequestData['password'] . "', GEN_SALT('bf')), ";
            $strQuery .= ROLE_RS_VERTICAL_MANAGER .")";

            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $user_id = $this->db->insert_id();
                $strQuery = "INSERT INTO users.rs_vertical_manager (user_id, name, vertical_id_list, rs_head_id, active_status, created_by) VALUES (";
                $strQuery .= $user_id . ", ";
                $strQuery .= "'" . $PageRequestData['name'] . "', ";

                $strQuery .= $VerticalIdList . ", ";
                $strQuery .= $strRsHeadId . ", ";
                $strQuery .= "TRUE, ";
                $strQuery .= $PageRequestData['user_id'] . ")";

                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Vertical Manager Details Added Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Vertical Manager Not Added";
                }
            } else {
                return false;
            }
        } else //UPDATE EXISTING RS HEAD
          {
            $strQuery = "UPDATE users.accounts SET email='" . $PageRequestData['email'] .  "' WHERE id=" . $PageRequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_vertical_manager SET ";
                $strQuery .= "name='" . $PageRequestData['name'] . "', ";
                $strQuery .= "phone='" . $PageRequestData['phone'] . "', ";
                $strQuery .= "vertical_id_list=" . $VerticalIdList . ", ";
                $strQuery .= "modified_by=" . $PageRequestData['user_id'] . ", ";
                $strQuery .= "modified_on='" . date("Y-m-d h:i:s a") . "' ";
                $strQuery .= "WHERE user_id=" . $PageRequestData['id'];
                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Vertical Manager Details Updated Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Vertical Manager Details Not Updated";
                }
            }
        }

        return $ResponseData;
    }

    function change_rs_vertical_manager_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_vertical_manager";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "RS Vertical Manager Account Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "RS Vertical Manager Account Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function get_vertical_list_for_rs_vertical_manager($vertical_manager_id = 0)
    {
        $ResponseArray = array();
        $query = "SELECT 		V.id AS vertical_id,
								V.name AS vertical_name
				  FROM			master.vertical AS V
                  LEFT JOIN		users.rs_vertical_manager AS VM ON V.id=ANY(VM.vertical_id_list)
                  WHERE			VM.user_id = $vertical_manager_id OR (VM.user_id IS NULL AND V.active_status = TRUE)
				  ORDER BY		vertical_name";

        $vertical_res = $this->db->query($query);

        if ($vertical_res->num_rows())
            $ResponseArray = $vertical_res->result_array();

        return $ResponseArray;
    }

    function get_sector_managers_for_rs_vertical_manager($rs_vertical_manager_id = 0)
    {
        $strQuery = "SELECT  	SM.name AS sector_manager_name,
								A.email AS sector_manager_email,
								SM.phone AS sector_manager_phone,
								(SELECT STRING_AGG(name, ', ' ORDER BY name) FROM master.sector WHERE id=ANY(SM.sector_id_list)) AS sector_name_list
					 FROM    	users.rs_sector_manager AS SM
					 LEFT JOIN	users.accounts AS A ON A.id = SM.user_id
					 WHERE		SM.rs_vertical_manager_id = $rs_vertical_manager_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }
    //END: RS VERTICAL MANAGERS - By George


    //BEGIN: RS SECTOR MANAGERS - By George
    function get_rs_sector_managers($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("SM.rs_sector_manager_name", "SM.rs_sector_manager_phone", "SM.rs_sector_manager_email", 'SM.rs_vertical_manager_name');
        $arrSortByColumns = array(
            0 => null,
            1 => 'SM.rs_sector_manager_name',
            2 => 'SM.rs_sector_manager_phone',
            3 => 'SM.rs_sector_manager_email',
            4 => 'SM.rs_vertical_manager_name',
            5 => null,
            6 => null,
            7 => null
        );

        $strCondition = "";
        switch ($user['user_group_id']) {
            case ROLE_RS_HEAD:
                $strCondition = " AND SM.rs_head_id = " . $user['id'] . " ";
                break;

            case ROLE_RS_ADMIN:
                $strQuery = "SELECT rs_head_id FROM users.rs_admin WHERE user_id=" . $user['id'];
                $strRsHeadId = $this->db->query($strQuery)->row()->rs_head_id;
                $strCondition = " AND SM.rs_head_id = " . $strRsHeadId . " ";
                break;

            case ROLE_RS_VERTICAL_MANAGER:
                $strCondition = " AND SM.rs_vertical_manager_id = " . $user['id'] . " ";
                break;
        }

        $strQuery = "SELECT COUNT(SM.rs_sector_manager_id)::BIGINT AS total_record_count
					 FROM   users.vw_rs_sector_manager AS SM
					 WHERE  TRUE ";

        $strQuery .= $strCondition;

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(SM.rs_sector_manager_id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_rs_sector_manager AS SM
			             WHERE		TRUE ";

            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	SM.rs_sector_manager_id,
									SM.rs_sector_manager_name,
									SM.rs_sector_manager_phone,
									SM.rs_sector_manager_email,
                                    SM.sector_name_list,
									SM.active_status,
									SM.rs_vertical_manager_name,
									SM.coordinator_count
			             FROM    	users.vw_rs_sector_manager AS SM
			             WHERE		TRUE ";

            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->rs_sector_manager_name;
                $ResponseRow[] = $QueryRow->rs_sector_manager_phone;
                $ResponseRow[] = $QueryRow->rs_sector_manager_email;
                $ResponseRow[] = $QueryRow->sector_name_list;
                $ResponseRow[] = $QueryRow->rs_vertical_manager_name;
                $ResponseRow[] = ($QueryRow->coordinator_count > 0) ? '<a class="btn btn-sm btn-primary" title="View Coordinator List" onclick="ViewCoordinatorList(' . "'" . $QueryRow->rs_sector_manager_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->coordinator_count . '</a>' : '<label class="btn btn-sm btn-primary" style="cursor:default;">' . $QueryRow->coordinator_count . '<label>';

                if($user['user_group_id']!=1 && $user['user_group_id']!=15 )
                {
                    $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->rs_sector_manager_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';

                    $ResponseRow[] = '<a class="btn btn-sm btn-primary" title="Edit RS Vertical Manager Details" href="javascript:void(0);" onclick="EditRSSectorManagerDetails(' . "'" . $QueryRow->rs_sector_manager_id . "'" . ')"><i class="icon-android-create"></i></a>';
                }
                else
                {
                    $ResponseRow[]='';
                    $ResponseRow[]='';
                }
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_rs_sector_manager_detail($rs_sector_manager_id = 0)
    {
        $strQuery = "SELECT  	SM.user_id AS rs_sector_manager_id,
								SM.name AS rs_sector_manager_name,
								A.email AS rs_sector_manager_email,
								SM.phone AS rs_sector_manager_phone,
								array_to_string(SM.sector_id_list, ',') AS sector_id_list,
								SM.rs_vertical_manager_id
					 FROM    	users.rs_sector_manager AS SM
					 LEFT JOIN	users.accounts AS A ON A.id = SM.user_id
					 WHERE		SM.user_id = $rs_sector_manager_id";

        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function save_rs_sector_manager_detail($PageRequestData)
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        $SectorIdList = implode(',', $PageRequestData['sector_id_list']);
        $SectorIdList = 'ARRAY[' . $SectorIdList . ']::INT[]';

        if (intval($PageRequestData['id']) < 1) //ADD NEW RS VERTICAL MANAGER
        {
            $strQuery = "INSERT INTO users.accounts (email, pwd, user_group_id) VALUES (";
            $strQuery .= "'" . $PageRequestData['email'] . "', ";
            $strQuery .= "CRYPT('" . $PageRequestData['password'] . "', GEN_SALT('bf')), ";
            $strQuery .= "'".ROLE_RS_SECTOR_MANAGER ."')";

            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $user_id = $this->db->insert_id();
                $strQuery = "INSERT INTO users.rs_sector_manager (user_id, name, phone, sector_id_list, rs_vertical_manager_id, active_status, created_by) VALUES (";
                $strQuery .= $user_id . ", ";
                $strQuery .= "'" . $PageRequestData['name'] . "', ";
                $strQuery .= "'" . $PageRequestData['phone'] . "', ";
                $strQuery .= $SectorIdList . ", ";
                $strQuery .= $PageRequestData['vertical_manager_id'] . ", ";
                $strQuery .= "TRUE, ";
                $strQuery .= $PageRequestData['created_by'] . ")";

                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Sector Manager Details Added Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Sector Manager Not Added";
                }
            } else {
                return false;
            }
        } else //UPDATE EXISTING RS HEAD
        {
            $strQuery = "UPDATE users.accounts SET email='" . $PageRequestData['email'] . "'  WHERE id=" . $PageRequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_sector_manager SET ";
                $strQuery .= "name='" . $PageRequestData['name'] . "', ";
                $strQuery .= "phone='" . $PageRequestData['phone'] . "', ";
                $strQuery .= "sector_id_list=" . $SectorIdList . ", ";
                $strQuery .= "rs_vertical_manager_id=" . $PageRequestData['vertical_manager_id'] . ", ";
                $strQuery .= "modified_by=" . $PageRequestData['modified_by'] . ", ";
                $strQuery .= "modified_on='" . date("Y-m-d h:i:s a") . "' ";
                $strQuery .= "WHERE user_id=" . $PageRequestData['id'];
                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Sector Manager Details Updated Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Sector Manager Details Not Updated";
                }
            }
        }

        return $ResponseData;
    }

    function change_rs_sector_manager_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_sector_manager";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "RS State Manager Account Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "RS State Manager Account Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function get_sector_list_for_rs_sector_manager($sector_manager_id = 0)
    {
        $ResponseArray = array();
        $query = "SELECT 		S.id AS sector_id,
								S.name AS sector_name
				  FROM			master.sector AS S
                  LEFT JOIN		users.rs_sector_manager AS SM ON S.id=ANY(SM.sector_id_list)
                  WHERE			SM.user_id = $sector_manager_id OR (SM.user_id IS NULL AND S.active_status = TRUE)
				  ORDER BY		sector_name";

        $ResponseData = $this->db->query($query);
        if ($ResponseData->num_rows())
            $ResponseArray = $ResponseData->result_array();

        return $ResponseArray;
    }

    function get_coordinators_for_rs_sector_manager($rs_sector_manager_id = 0)
    {
        $strQuery = "SELECT  	C.name AS coordinator_name,
								A.email AS coordinator_email,
								C.phone AS coordinator_phone,
								(SELECT STRING_AGG(name, ', ' ORDER BY name) FROM master.district WHERE id=ANY(C.district_id_list)) AS district_name_list
					 FROM    	users.rs_coordinator AS C
					 LEFT JOIN	users.accounts AS A ON A.id = C.user_id
					 WHERE		C.rs_sector_manager_id = $rs_sector_manager_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function get_vertical_manager_list_for_rs_admin($UserId, $UserGroupId)
    {
        if (intval($UserGroupId) == 14)
        {
            $strQuery = "SELECT  	rs_head_id
                         FROM    	users.rs_admin
                         WHERE      user_id = $UserId";
            $RsHeadId = $this->db->query($strQuery)->row()->rs_head_id;

            if ($RsHeadId)
            {
                $strQuery = "SELECT  	user_id AS rs_vertical_manager_id,
                                        name AS rs_vertical_manager_name
                             FROM    	users.rs_vertical_manager
                             WHERE      rs_head_id = $RsHeadId
                             ORDER BY	rs_vertical_manager_name";

                $QueryData = $this->db->query($strQuery);
                return $QueryData->result_array();
            }
        }

        return array();
    }
    //END: RS SECTOR MANAGERS - By George


    //BEGIN: RS COORDINATORS - By George
    function get_rs_coordinators($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("C.rs_coordinator_name", "C.rs_coordinator_phone", "C.rs_coordinator_email", "C.rs_sector_manager_name");
        $arrSortByColumns = array(
            0 => null,
            1 => 'C.rs_coordinator_name',
            2 => 'C.rs_coordinator_phone',
            3 => 'C.rs_coordinator_email',
            4 => 'C.rs_sector_manager_name',
            5 => null,
            6 => null,
            7 => null
        );

        $strCondition = "";
        switch ($user['user_group_id']) {
            case ROLE_RS_HEAD:
                $strCondition = " AND C.rs_head_id = " . $user['id'] . " ";
                break;

            case ROLE_RS_ADMIN:
                $strQuery = "SELECT rs_head_id FROM users.rs_admin WHERE user_id=" . $user['id'];
                $strRsHeadId = $this->db->query($strQuery)->row()->rs_head_id;
                $strCondition = " AND C.rs_head_id = " . $strRsHeadId . " ";
                break;

            case ROLE_RS_VERTICAL_MANAGER: //RS VERTICAL MANAGER
                $strCondition = " AND C.rs_vertical_manager_id = " . $user['id'] . " ";
                break;

            case ROLE_RS_SECTOR_MANAGER: //RS SECTOR MANAGER
                $strCondition = " AND C.rs_sector_manager_id = " . $user['id'] . " ";
                break;
        }

        $strQuery = "SELECT COUNT(C.rs_coordinator_id)::BIGINT AS total_record_count
					 FROM   users.vw_rs_coordinator AS C
					 WHERE	TRUE ";

        $strQuery .= $strCondition;

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(C.rs_coordinator_id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_rs_coordinator AS C
			             WHERE		TRUE ";

            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	C.rs_coordinator_id,
									C.rs_coordinator_name,
									C.rs_coordinator_phone,
									C.rs_coordinator_email,
                                    C.district_name_list,
									C.active_status,
									C.rs_sector_manager_name,
									C.executive_count
			             FROM    	users.vw_rs_coordinator AS C
			             WHERE		TRUE ";
            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->rs_coordinator_name;
                $ResponseRow[] = $QueryRow->rs_coordinator_phone;
                $ResponseRow[] = $QueryRow->rs_coordinator_email;
                $ResponseRow[] = $QueryRow->district_name_list;
                $ResponseRow[] = $QueryRow->rs_sector_manager_name;
                $ResponseRow[] = ($QueryRow->executive_count > 0) ? '<a class="btn btn-sm btn-primary" title="View RS Executive List" onclick="ViewRSExecutiveList(' . "'" . $QueryRow->rs_coordinator_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->executive_count . '</a>' : '<label class="btn btn-sm btn-primary" style="cursor:default;">' . $QueryRow->executive_count . '<label>';
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->rs_coordinator_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';
                $ResponseRow[] = '<a class="btn btn-sm btn-primary" title="Edit RS coordinator Details" href="javascript:void(0);" onclick="EditRSCoordinatorDetails(' . "'" . $QueryRow->rs_coordinator_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_rs_coordinator_detail($rs_coordinator_id = 0)
    {
        $strQuery = "SELECT  	C.user_id AS rs_coordinator_id,
								C.name AS rs_coordinator_name,
								A.email AS rs_coordinator_email,
								C.phone AS rs_coordinator_phone,
								array_to_string(C.district_id_list, ',') AS district_id_list
					 FROM    	users.rs_coordinator AS C
					 LEFT JOIN	users.accounts AS A ON A.id = C.user_id
					 WHERE		C.user_id = $rs_coordinator_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function change_rs_coordinator_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_coordinator";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "RS Coordinator Account Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "RS Coordinator Account Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function save_rs_coordinator_detail($PageRequestData)
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        $DistrictIdList = implode(',', $PageRequestData['district_id_list']);
        $DistrictIdList = 'ARRAY[' . $DistrictIdList . ']::INT[]';

        if (intval($PageRequestData['id']) < 1) //ADD NEW RS COORDINATOR
        {
            $strQuery = "INSERT INTO users.accounts (email, pwd, user_group_id) VALUES (";
            $strQuery .= "'" . $PageRequestData['email'] . "', ";
            $strQuery .= "CRYPT('" . $PageRequestData['password'] . "', GEN_SALT('bf')), ";
            $strQuery .= ROLE_RS_COORDINATOR . ")";

            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $user_id = $this->db->insert_id();
                $strQuery = "INSERT INTO users.rs_coordinator (user_id, name,phone, district_id_list, rs_sector_manager_id, active_status, created_by) VALUES (";
                $strQuery .= $user_id . ", ";
                $strQuery .= "'" . $PageRequestData['name'] . "', ";
                $strQuery .= "'" . $PageRequestData['phone'] . "', ";
                $strQuery .= $DistrictIdList . ", ";
                $strQuery .= $PageRequestData['user_id'] . ", ";
                $strQuery .= "TRUE, ";
                $strQuery .= $PageRequestData['user_id'] . ")";

                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Coordinator Details Added Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Coordinator Not Added";
                }
            } else {
                return false;
            }
        } else //UPDATE EXISTING RS COORDINATOR
        {
            $strQuery = "UPDATE users.accounts SET email='" . $PageRequestData['email'] . "' WHERE id=" . $PageRequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_coordinator SET ";
                $strQuery .= "name='" . $PageRequestData['name'] . "', ";
                $strQuery .= "district_id_list=" . $DistrictIdList . ", ";
                $strQuery .= "modified_by=" . $PageRequestData['user_id'] . ", ";
                $strQuery .= "modified_on='" . date("Y-m-d h:i:s a") . "' ";
                $strQuery .= "WHERE user_id=" . $PageRequestData['id'];
                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Coordinator Details Updated Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Coordinator Details Not Updated";
                }
            }
        }

        return $ResponseData;
    }

    function get_district_list_for_rs_coordinator($coordinator_id = 0)
    {
        $ResponseArray = array();
        $query = "SELECT 		D.id AS district_id,
                                D.name || ' - ' || S.code  AS district_name
                  FROM			master.district AS D
                  LEFT JOIN		master.state AS S ON S.id = D.state_id
                  LEFT JOIN		users.rs_coordinator AS C ON D.id=ANY(C.district_id_list)
                  WHERE			C.user_id = $coordinator_id OR (C.user_id IS NULL AND D.active_status = 1)
                  ORDER BY		S.code, D.name";

        $ResponseData = $this->db->query($query);
        if ($ResponseData->num_rows())
            $ResponseArray = $ResponseData->result_array();

        return $ResponseArray;
    }

    function get_executives_for_rs_coordinator($rs_coordinator_id = 0)
    {
        $strQuery = "SELECT  	E.name AS executive_name,
								A.email AS executive_email,
								E.phone AS executive_phone
					 FROM    	users.rs_executive AS E
					 LEFT JOIN	users.accounts AS A ON A.id = E.user_id
					 WHERE		E.rs_coordinator_id = $rs_coordinator_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }
    //END: RS COORDINATORS - By George


    //BEGIN: RS EXECUTIVES - By George
    function get_rs_executives($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("E.rs_executive_name", "E.rs_executive_phone", "E.rs_executive_email", "E.rs_coordinator_name");
        $arrSortByColumns = array(
            0 => null,
            1 => 'E.rs_executive_name',
            2 => 'E.rs_executive_phone',
            3 => 'E.rs_executive_email',
            4 => 'E.rs_coordinator_name',
            5 => null,
            6 => null,
            7 => null
        );

        $strCondition = "";
        switch ($user['user_group_id']) {
            case ROLE_RS_HEAD:
                $strCondition = " AND E.rs_head_id = " . $user['id'] . " ";
                break;

            case ROLE_RS_ADMIN:
                $strQuery = "SELECT rs_head_id FROM users.rs_admin WHERE user_id=" . $user['id'];
                $strRsHeadId = $this->db->query($strQuery)->row()->rs_head_id;
                $strCondition = " AND E.rs_head_id = " . $strRsHeadId . " ";
                break;

            case ROLE_RS_VERTICAL_MANAGER:
                $strCondition = " AND E.rs_vertical_manager_id = " . $user['id'] . " ";
                break;

            case ROLE_RS_SECTOR_MANAGER:
                $strCondition = " AND E.rs_sector_manager_id = " . $user['id'] . " ";
                break;

            case ROLE_RS_COORDINATOR:
                $strCondition = " AND E.rs_coordinator_id = " . $user['id'] . " ";
                break;
        }

        $strQuery = "SELECT COUNT(E.rs_executive_id)::BIGINT AS total_record_count
					 FROM   users.vw_rs_executive AS E
					 WHERE	TRUE";

        $strQuery .= $strCondition;

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(E.rs_executive_id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_rs_executive AS E
			             WHERE		TRUE ";

            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	E.rs_executive_id,
									E.rs_executive_name,
									E.rs_executive_email,
									E.rs_executive_phone,
									E.active_status,
									E.rs_coordinator_name
			             FROM    	users.vw_rs_executive AS E
			             WHERE		TRUE ";

            $strQuery .= $strCondition;
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->rs_executive_name;
                $ResponseRow[] = $QueryRow->rs_executive_phone;
                $ResponseRow[] = $QueryRow->rs_executive_email;
                $ResponseRow[] = $QueryRow->rs_coordinator_name;
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->rs_executive_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit RS Executive Details" href="javascript:void(0);" onclick="EditRSExecutiveDetails(' . "'" . $QueryRow->rs_executive_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_rs_executive_detail($rs_executive_id = 0)
    {
        $strQuery = "SELECT  	E.user_id AS rs_executive_id,
								E.name AS rs_executive_name,
								A.email AS rs_executive_email,
								E.phone AS rs_executive_phone
					 FROM    	users.rs_executive AS E
					 LEFT JOIN	users.accounts AS A ON A.id = E.user_id
					 WHERE		E.user_id = $rs_executive_id";
        $ResponseData = $this->db->query($strQuery)->result_array();

        return $ResponseData;
    }

    function save_rs_executive_detail($PageRequestData)
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        if (intval($PageRequestData['id']) < 1) //ADD NEW RS Executive
        {
            $strQuery = "INSERT INTO users.accounts (email, pwd, user_group_id) VALUES (";
            $strQuery .= "'" . $PageRequestData['email'] . "', ";
            $strQuery .= "CRYPT('" . $PageRequestData['password'] . "', GEN_SALT('bf')), ";
            $strQuery .= ROLE_RS_EXECUTIVE . ")";

            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $user_id = $this->db->insert_id();
                $strQuery = "INSERT INTO users.rs_executive (user_id, name,phone, rs_coordinator_id, active_status, created_by) VALUES (";
                $strQuery .= $user_id . ", ";
                $strQuery .= "'" . $PageRequestData['name'] . "', ";
                $strQuery .= "'" . $PageRequestData['phone'] . "', ";
                $strQuery .= "'" . $PageRequestData['user_id'] . "', ";
                $strQuery .= "TRUE, ";
                $strQuery .= $PageRequestData['user_id'] . ")";

                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Executive Added Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Executive Not Added";
                }
            } else {
                return false;
            }
        } else //UPDATE EXISTING RS COORDINATOR
        {
            $this->db->query("UPDATE users.accounts set email=? where id=?", array($PageRequestData['email'],$PageRequestData['id']));
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_executive SET ";
                $strQuery .= "name='" . $PageRequestData['name'] . "', ";
                $strQuery .= "phone='" . $PageRequestData['phone'] . "', ";
                $strQuery .= "modified_by=" . $PageRequestData['user_id'] . ", ";
                $strQuery .= "modified_on='" . date("Y-m-d h:i:s a") . "' ";
                $strQuery .= "WHERE user_id=" . $PageRequestData['id'];
                $this->db->query($strQuery);
                if ($this->db->affected_rows()) {
                    $ResponseData["status"] = true;
                    $ResponseData["message"] = "RS Executive Details Updated Successfully";
                } else {
                    $ResponseData["status"] = false;
                    $ResponseData["message"] = "RS Executive Details Not Updated";
                }
            }
        }

        return $ResponseData;
    }

    function change_rs_executive_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.rs_executive";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "RS Executive Account Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "RS Executive Account Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    //END: RS EXECUTIVES - By George

    //BEGIN: ACADEMIC MAJORS - By George
    function get_academic_majors($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("AM.code", "AM.name", "IT.name");
        $arrSortByColumns = array(
            0 => null,
            1 => 'AM.code',
            2 => 'AM.name',
            3 => 'IT.name',
            4 => null,
            5 => null,
        );

        $strQuery = "SELECT COUNT(AM.id)::BIGINT AS total_record_count
					 FROM   master.academic_majors AS AM
					 WHERE  TRUE ";

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT  	COUNT(AM.id)::BIGINT AS total_filtered_count
			             FROM    	master.academic_majors AS AM
			             WHERE		TRUE ";
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT  	AM.id AS academic_major_id,
									AM.code AS academic_major_code,
									AM.name AS academic_major_name,
									IT.name AS interest_type_name,
									AM.active_status
			             FROM    	master.academic_majors AS AM
			             LEFT JOIN	master.list AS IT ON IT.code='L0005' AND IT.value = AM.interest_type_code::TEXT
			             WHERE		TRUE ";
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->academic_major_code;
                $ResponseRow[] = $QueryRow->academic_major_name;
                $ResponseRow[] = $QueryRow->interest_type_name;
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->academic_major_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Academic Major Details" href="javascript:void(0);" onclick="EditAcademicMajor(' . "'" . $QueryRow->academic_major_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_academic_major_detail($Id = 0)
    {
        $strQuery = "SELECT  	AM.id AS academic_major_id,
                                AM.code AS academic_major_code,
                                AM.name AS academic_major_name,
                                AM.interest_type_code
                     FROM    	master.academic_majors AS AM
					 WHERE		AM.id = $Id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function change_academic_major_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE master.academic_majors";
            $strQuery .= " SET active_status=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows())
            {
                if ($strStatus == "FALSE")
                    $ResponseData["message"] = "Academic Major Has Been Deactivated!";
                else
                    $ResponseData["message"] = "Academic Major Has Been Activated!";
            }
        }

        return $ResponseData;
    }

    function save_academic_major_detail($PageRequestData)
    {
        $ResponseData = array();
        $ResponseData["status"] = false;
        $ResponseData["message"] = "";

        if (intval($PageRequestData['id']) < 1) //ADD NEW ACADEMIC_MAJOR
        {
            $strQuery = "INSERT INTO master.academic_majors (code, name, interest_type_code,created_by) VALUES (";
            $strQuery .= "'" . $PageRequestData['code'] . "', ";
            $strQuery .= "'" . $PageRequestData['name'] . "', ";
            $strQuery .= "'" . $PageRequestData['interest_type_code'] . "', ";
            $strQuery .= $PageRequestData['user_id'] . ")";

            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $ResponseData["status"] = true;
                $ResponseData["message"] = "Academic Major Details Added Successfully";
            } else {
                $ResponseData["status"] = false;
                $ResponseData["message"] = "Academic Major Details Not Added";
            }
        }
        else //UPDATE EXISTING ACADEMIC_MAJOR
        {
            $strQuery = "UPDATE master.academic_majors SET ";
            $strQuery .= "code='" . $PageRequestData['code'] . "', ";
            $strQuery .= "name='" . $PageRequestData['name'] . "', ";
            $strQuery .= "interest_type_code='" . $PageRequestData['interest_type_code'] . "', ";
            $strQuery .= "modified_by=" . $PageRequestData['user_id'] . ", ";
            $strQuery .= "modified_on='" . date("Y-m-d h:i:s a") . "' ";
            $strQuery .= "WHERE id=" . $PageRequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $ResponseData["status"] = true;
                $ResponseData["message"] = "Academic Major Details Updated Successfully";
            } else
            {
                $ResponseData["status"] = false;
                $ResponseData["message"] = "Academic Major Details Not Updated";
            }
        }

        return $ResponseData;
    }

    function get_interest_type_list()
    {
        $ResponseArray = array();
        $query = "SELECT * from master.vw_interest_types";

        $ResponseData = $this->db->query($query);
        if ($ResponseData->num_rows())
            $ResponseArray = $ResponseData->result_array();

        return $ResponseArray;
    }
    //END: ACADEMIC MAJORS - By George


   function get_jobs_list_new($requestData = array(), $user_id = 0)
    {
        $active_user_role_id = $this->session->userdata('usr_authdet')['user_group_id'];
        $HierarchyIds= $this->session->userdata('user_hierarchy');
        // array_push($HierarchyIds, $user_id);
        $TeamMemberIdList = implode(",",$HierarchyIds);
        // var_dump($TeamMemberIdList);
        // exit;
        

        $order_by = " ORDER BY id DESC ";
        $search_type_id = isset($requestData['search_type_id']) ? intval($requestData['search_type_id']) : 0;
        $search_value = isset($requestData['search_value']) ? $requestData['search_value'] : '';
        $cond = '';
        $data = array();

        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => null,
            2 => 'VW.job_title',
            3 => 'VW.job_code',
            4 => 'VW.customer_name',
            5=> 'VW.job_status_name',
            6=> 'VW.placement_officer_ids',
            7=> 'VW.recruiter_ids',
            8 => 'VW.created_at',
            9 => 'VW.no_of_position',
            10 => 'VW.qualification_pack_name',
            11 => 'VW.business_vertical_name',
            12 => 'VW.functional_area_name',
            13 => 'VW.industry_name',
            14 => 'VW.office_location',
            15 => 'VW.education_name',
            16 => 'VW.job_open_type_name',
            17 => 'VW.job_priority_level_name',
            18=>  'VW.job_expiry_date'
        );

        $column_search = array(
            1 => 'VW.job_title', 
            2 => 'VW.customer_name', 
            3 => 'VW.placement_officer_names',
            4 => 'VW.recruiter_names',
            5 => 'VW.job_status_id',
            6 => 'VW.job_code'
            );

        /*********************************/

        $user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];

        $strUserRoleCondition = 'TRUE';

        $sWhere = "  ";

        if ($active_user_role_id == 14 || $active_user_role_id == 11)
        {
           $sWhere = " AND $user_id=ANY(VW.assigned_user_ids) ";
        }

        $sSearchVal = $_POST['search']['value'];
        if (isset($sSearchVal) && $sSearchVal != '')
        {
            $sWhere .= " AND (";
            for ($i = 0; $i < count($column_search); $i++)
                $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ') ';
        }

        $HierarchyCondition = "";
        if ($TeamMemberIdList != '')
            $HierarchyCondition = " AND ((assigned_user_ids||created_user_id) && ARRAY[$TeamMemberIdList]) ";

        $total_records = $this->db->query("SELECT Count(VW.*) AS total_recs FROM neo_job.vw_job_list AS VW WHERE TRUE $HierarchyCondition")->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        }
        else
        {
            $FilterCondition = "";      
                if ($search_type_id > 0)
                {      
                    switch($search_type_id)
                    {
                        case 5:
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
            $totalFiltered = $this->db->query("SELECT Count(*) AS total_filtered FROM neo_job.vw_job_list AS VW  WHERE TRUE $FilterCondition $sWhere $HierarchyCondition")->row()->total_filtered;

            $job_recs = $this->db->query("SELECT    VW.id AS job_id,
                                                    VW.job_title,
                                                    VW.job_code,
                                                    VW.customer_name,                                                    
                                                    VW.created_at,
                                                    VW.no_of_position,
                                                    VW.qualification_pack_name,
                                                    VW.business_vertical_name,
                                                    VW.functional_area_name,
                                                    VW.industry_name,
                                                    VW.office_location,
                                                    VW.education_name,
                                                    VW.job_open_type_name,
                                                    VW.job_priority_level_name,
                                                    VW.job_status_id,
                                                    VW.job_status_name,
                                                    VW.job_expiry_date,
                                                    VW.placement_officer_names,
                                                    VW.recruiter_names
                                              FROM  neo_job.vw_job_list AS VW
                                              WHERE TRUE
                                              $FilterCondition
                                              $sWhere
                                              $HierarchyCondition
                                              $order_by
                                              LIMIT $limit
                                              OFFSET $pg");

            $slno = $pg;
            $data = array();

            $JobStatusColors = array (
                1 => 'info',
                2 => 'success',
                3 => 'danger',
                4 => 'warning',
                5 => 'default'
            );

            foreach ($job_recs->result() as $jobs)
            {
               // if($jobs->job_status_id!= 2)
             // {
                $Actions = '';
                if(in_array($user_group_id, job_status_change_roles())) {
                   $Actions = '<button class="btn btn-sm btn-warning" title="Update Jobs Status" onclick="open_job_status_popup('. $jobs->job_id . ',' . $jobs->job_status_id .')" style="margin-left: 2px;"><i class="fa fa-pencil-square-o"></i></button>';
                }
                 $Actions .= '<a class="btn btn-sm btn-danger" title="Edit job Details" onclick="EditJobDetails(' . $jobs->job_id . ')" style="margin-left: 5px; color: white;"><i class="icon-android-create"></i></a>';
              //}
//              else
//              {
//                   $Actions = '<button class="btn btn-sm btn-warning" title="Update Jobs Status" onclick="open_job_status_popup('. $jobs->job_id . ',' . $jobs->job_status_id .')" style="margin-left: 2px;"><i class="fa fa-pencil-square-o"></i></button>';
//                   $Actions .= '<a class="btn btn-sm btn-primary" title="Assign Job" onclick="assignjob(' . $jobs->job_id . ')" style="margin-left: 5px; color: white;"><i class="fa fa-share"></i></a>';
//                   $Actions .= '<a class="btn btn-sm btn-danger" title="Edit job Details" onclick="EditJobDetails(' . $jobs->job_id . ')" style="margin-left: 5px; color: white;"><i class="icon-android-create"></i></a>';
//               }


                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $Actions;
                $row[] = $jobs->job_title;
                $row[] = $jobs->job_code ?? 'N/A';
                $row[] = $jobs->customer_name;
                $row[] = '<center><span id="span_' . $jobs->job_id . '" class="label ' . $JobStatusColors[$jobs->job_status_id]  . '">' . $jobs->job_status_name . '</span></center>';
                $row[] = $jobs->placement_officer_names;
                $row[] = $jobs->recruiter_names;
                $row[] = $jobs->created_at;
                $row[] = $jobs->no_of_position;
                $row[] = $jobs->qualification_pack_name ?? 'N/A';
                $row[] = $jobs->business_vertical_name ?? 'N/A';
                $row[] = $jobs->functional_area_name ?? 'N/A';
                $row[] = $jobs->industry_name ?? 'N/A';
                $row[] = $jobs->office_location ?? 'N/A';
                $row[] = $jobs->education_name ?? 'N/A';
                $row[] = $jobs->job_open_type_name ?? 'N/A';
                $row[] = $jobs->job_priority_level_name ?? 'N/A';
                $row[] = $jobs->job_expiry_date ?? 'N/A';
                $data[] = $row;
            }

            $jobs_data_recs = array(
                "draw" => intval($requestData['draw']),
                "recordsTotal" => intval($totalData),
                "recordsFiltered" => intval($totalFiltered),
                "data" => $data
            );

            return $jobs_data_recs;
        }
    }

    function get_employers_list_new($requestData = array(),$user_id = 0)
    {


         $order_by = "";
        $cond = '';
        $data = array();

        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'VW.employer_name',
            2 => 'VW.employer_email',
            3 => 'VW.employer_phone',
            4 => null,
            5 => null,
            6 => null,
            7 => null,
            8 => null,
            9 => null

        );

        $column_search = array("VW.employer_name", "VW.employer_email", "VW.employer_phone");


        $user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];

        $strUserRoleCondition = '';

        switch ($user_group_id) {

            case 18://BD Executive
                $strUserRoleCondition = '  VW.bd_executive_id =' . $user_id . ' ';
                break;

            case 8://BD Coordinator
                $strUserRoleCondition .= ' VW.bd_coordinator_id =' . $user_id . ' ';
                break;

            case 11://BD Regional Manager
                $strUserRoleCondition .= ' VW.bd_regional_manager_id =' . $user_id . ' ';
                break;

            case 13://BD ADMIN
                $strUserRoleCondition .= ' VW.bd_head_id = (Select created_by From users.bd_admin Where user_id =' . $user_id . ') ';
                break;

            case 12://BD HEAD
                $strUserRoleCondition .= ' VW.bd_head_id =' . $user_id . ' ';
                break;

            case 1:// Administrator
                 $strUserRoleCondition .= ' 1=1 ';
                 break;
            default:
                $strUserRoleCondition .= ' 1!=1 ';
                break;

        }

        $sWhere = "WHERE 1=1 AND";
        $sSearchVal = $_POST['search']['value'];
        if (isset($sSearchVal) && $sSearchVal != '') {
            $sWhere .= " (";
            for ($i = 0; $i < count($column_search); $i++) {
                $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ') AND ';
        }


        /********************************/

        $total_records = $this->db->query("SELECT Count(*) AS total_recs FROM
                                            users.vw_emp_data AS VW
                                            $sWhere
                                            $strUserRoleCondition")->row()->total_recs;

        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records)
        {
            return array("draw" => intval($requestData['draw']), "recordsTotal" => "0", "recordsFiltered" => "0", 'data' => array());
        }
        else
        {
            /*
             * Filtering
             */


            $totalFiltered = $this->db->query("SELECT Count(*) AS total_filtered FROM
                                                   users.vw_emp_data AS VW
                                                    $sWhere
                                                    $strUserRoleCondition ")->row()->total_filtered;


            /////////////

                $emp_recs = $this->db->query("SELECT VW.* FROM users.vw_emp_data AS VW
                                              $sWhere
                                              $strUserRoleCondition $order_by limit $limit OFFSET $pg");

            /////////////

            $slno = $pg;
            $data = array();
            foreach ($emp_recs->result() as $emps) {  // preparing an array
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $emps->employer_name;
                $row[] = $emps->employer_email;
                $row[] = $emps->employer_phone;
                /*$row[] = $emps->spoc_name;
                $row[] = $emps->spoc_phone;*/
                $row[] = $emps->bd_executive_name;
                $row[] = $emps->bd_coordinator_name;
                $row[] = $emps->bd_regional_manager_name;
                $row[] = $emps->bd_head_name;

               if($user_group_id==18) // For BD-Executive Only
               {
                $row[] = '<a class="' . ($emps->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $emps->employer_id . "'" . ',' . intval($emps->active_status) . ')" style="width:80%;">' . ($emps->active_status ? "Active" : "Inactive") . '</a>';;

                $row[] = '<a class="btn btn-sm btn-danger" title="Edit Employer Details" href="javascript:void(0);" onclick="EditEmployer(' . "'" . $emps->employer_id . "'" . ')"><i class="icon-android-create"></i></a>';;
               }
                else{
                    if(intval($emps->active_status)==1)
                         $status = "Active";
                     else
                         $status = "Inactive";
                    $row[] =  $status;
                    $row[] = "";
                }

                $data[] = $row;
            }
            //  $data[] = $employee_recs->result_array();
            $emp_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $emp_data_recs;
        }


    }

   function get_assigned_jobs_list_new($requestData=array(),$user_id = 0)
   {


        $order_by = "";
        $cond = '';
        $data = array();

        $columns = array(
            // datatable column index  => database column name
            0 => null,
            1 => 'Emp.name',
            2 => 'QP.name',
            3 => 'VW.job_desc',
            4 => null,
            5 => 'VW.created_on',
            6 => 'VW.no_of_openings',
            7 => 'joined',
            8 => null
        );

        $column_search = array("Emp.name", "QP.name", "VW.job_desc");

        /*********************************/

        $user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];

        $strUserRoleCondition = '';

        switch ($user_group_id) {
            case 1://Super Admin
                 $strUserRoleCondition .= ' 1=1 ';
                break;

            case 15: //RS Head
                $strUserRoleCondition .= ' VW.rs_head_id=' . $user_id . ' ';
                break;

            case 14: //RS Admin
                $strUserRoleCondition .= ' VW.rs_head_id= (SELECT rs_head_id FROM users.rs_admin WHERE user_id =' . $user_id . ') ' ;
                break;

            case 16: //RS Vertical Manager
                $strUserRoleCondition .= ' VW.rs_vertical_manager_id =' . $user_id . ' ';
                break;
            case 17: //RS Coordinator
                $strUserRoleCondition .= '  VW.rs_coordinator_id = '.$user_id.' ';
                break;

            case 19: //RS Executive
                    $strUserRoleCondition .=  ' VW.rs_executive_id='.$user_id. ' ';
                    break;

            case 24: //RS Sector Manager
                $strUserRoleCondition .= ' VW.rs_sector_manager_id =' . $user_id . ' ';
                break;

            default:
                $strUserRoleCondition .= ' 1!=1 ';
                break;
        }

        $sWhere = "WHERE 1=1 AND";
        $sSearchVal = $_POST['search']['value'];
        if (isset($sSearchVal) && $sSearchVal != '') {
            $sWhere .= " (";
            for ($i = 0; $i < count($column_search); $i++) {
                $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ') AND ';

        }


        /********************************/


        $total_records = $this->db->query("SELECT Count(*) AS total_recs FROM
                                                   job_process.vw_assigned_job_data AS VW

                                                   LEFT JOIN users.employers AS Emp ON VW.employer_id = Emp.user_id
                                                   LEFT JOIN master.qualification_pack
                                                   AS QP ON QP.id = VW.qualification_pack_id $sWhere
                                                    $strUserRoleCondition")->row()->total_recs;


        $totalData = $total_records * 1;
        $totalFiltered = $totalData;  // when there is no search parameter then total number rows = total number filtered rows.

        if ($columns[$requestData['order'][0]['column']] != '') {
            $order_by = " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . "  ";
        }

        $pg = $requestData['start'];
        $limit = $requestData['length'];
        if ($limit < 0)
            $limit = 'all';
        // ==== filters end =====

        if (!$total_records) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            /*
             * Filtering
             */


            $totalFiltered = $this->db->query("SELECT Count(*) AS total_filtered FROM
                                                   job_process.vw_assigned_job_data AS VW

                                                   LEFT JOIN users.employers AS Emp ON VW.employer_id = Emp.user_id
                                                   LEFT JOIN master.qualification_pack
                                                   AS QP ON QP.id = VW.qualification_pack_id $sWhere
                                                    $strUserRoleCondition ")->row()->total_filtered;


            /////////////

                $job_recs = $this->db->query("SELECT QP.name AS job_role,VW.* ,
                                              (SELECT D.name FROM master.district AS D WHERE
                                              D.id = VW.job_location_id) as job_location, Emp.name AS emp_name ,
                                            (SELECT count(*) from job_process.candidate_jobs cj where cj.job_id=VW.job_id and cj.status_id=7) as joined,
                                                (SELECT count(*) from job_process.candidate_jobs cj where cj.job_id=VW.job_id and cj.status_id=3) as scheduled_candidates,

                                            (SELECT V.name
                                            FROM master.vertical AS V WHERE V.id = VW.vertical_id) AS vertical_name,
                                            (SELECT S.name FROM master.sector AS S WHERE S.id = VW.sector_id)
                                            AS sector_name
                                           FROM job_process.vw_assigned_job_data AS VW

                                          LEFT JOIN users.employers AS Emp ON VW.employer_id = Emp.user_id
                                          LEFT JOIN master.qualification_pack AS QP
                                          ON QP.id = VW.qualification_pack_id $sWhere
                                          $strUserRoleCondition $order_by limit $limit OFFSET $pg");

            /////////////

            $slno = $pg;
            $data = array();
            foreach ($job_recs->result() as $jobs) {  // preparing an array

                $job_status = "Open";

                if($jobs->job_status)
                    $job_status = "Open";
                else
                    $job_status = "Closed";



                $row = array();
                $slno++;
                $row[] = $slno;

                if($user_group_id==19)  //RS Executive
                   {
                    $row[] = '<a href="javascript:void(0)" title="Employer Job" onclick="search_candidates(' . "'" . $jobs->job_id . "'" . ',' . "'" . $jobs->job_location_id . "'" . ')">' . $jobs->emp_name . '</a>';
                }
                else {
                    $row[] = $jobs->emp_name;
                }


                $row[] = $jobs->job_role;
                $row[] = $jobs->job_desc;
                $row[] = $jobs->sector_name;
                $row[] = $jobs->vertical_name;
                $row[] = $jobs->job_location;
                $row[] = date('d-M-Y', strtotime($jobs->created_on));
                $row[] = $jobs->no_of_openings;


                $row[] = $jobs->joined;

                /*
                if($user_group_id==19)  //RS Executive
                   {

                 $row[] = ($jobs->scheduled_candidates) ? '<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="scheduled_candidates(' . "'" . $jobs->job_id . "'" . ',' . "'" . $jobs->employer_id . "'" . ')">' . $jobs->scheduled_candidates . '</b></a>' : $jobs->scheduled_candidates;
                   }
                 else{
                   $row[] = $jobs->scheduled_candidates;
                 }
                */

                $row[] = $job_status;

                $data[] = $row;
            }
            //  $data[] = $employee_recs->result_array();
            $jobs_data_recs = array(
                "draw" => intval($requestData['draw']),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($totalData),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return $jobs_data_recs;
        }

    }




    function do_get_unassigned_regions($user_group_id = 0, $user_id = 0)
    {
        $region_query = "";


        if ($user_group_id == 20) {
            if ($user_id <= 0) {


                $region_query = "SELECT R.* FROM  master.regions AS R
		                   LEFT JOIN users.regional_manager AS RM
		                   ON R.id=ANY(RM.region_id_list) WHERE  RM.user_id IS NULL AND R.active_status = 1";

            } else {
                $region_query = "SELECT R.* FROM  master.regions AS R
		                   LEFT JOIN users.regional_manager AS RM
		                   ON R.id=ANY(RM.region_id_list) WHERE  RM.user_id = ? OR (RM.user_id IS NULL AND R.active_status = 1)";
            }

            $query_res = $this->db->query($region_query, $user_id)->result_array();
            return $query_res;
        } else if ($user_group_id == 11) {
            if ($user_id <= 0) {


                $region_query = "SELECT R.* FROM  master.regions AS R
		                   LEFT JOIN users.bd_regional_manager AS RM
		                   ON R.id=ANY(RM.region_id_list) WHERE  RM.user_id IS NULL AND R.active_status = 1";

            } else {
                $region_query = "SELECT R.* FROM  master.regions AS R
		                   LEFT JOIN users.bd_regional_manager AS RM
		                   ON R.id=ANY(RM.region_id_list) WHERE  RM.user_id = ? OR (RM.user_id IS NULL AND R.active_status = 1)";
            }


            $query_res = $this->db->query($region_query, $user_id)->result_array();


            return $query_res;
        }


    }

    function do_get_unassigned_states($regional_mgr_id = 0, $user_id = 0)
    {
        $region_query = "";
        if ($user_id <= 0) {
            $region_query = "SELECT S.* FROM  master.state AS S
				   LEFT JOIN users.state_manager AS SM
				   ON S.id=ANY(SM.state_id_list)
				   LEFT JOIN users.regional_manager AS RM ON S.region_id = ANY(RM.region_id_list)
				   WHERE RM.user_id = ? AND S.active_status = 1";
            $query_res = $this->db->query($region_query, array($regional_mgr_id))->result_array();
            return $query_res;

        } else {
            $region_query = "SELECT S.* FROM  master.state AS S
				   LEFT JOIN users.state_manager AS SM
				   ON S.id=ANY(SM.state_id_list)
				   LEFT JOIN users.regional_manager AS RM ON S.region_id = ANY(RM.region_id_list)
				   WHERE RM.user_id = ? AND (SM.user_id = ? OR (SM.user_id IS NULL AND S.active_status = 1))";

            $query_res = $this->db->query($region_query, array($regional_mgr_id, $user_id))->result_array();
            return $query_res;
        }


    }


    function do_get_unassigned_districts($user_group_id = 0, $parent_manager_id = 0, $user_id = 0)
    {
        $region_query = "";

        if ($user_group_id == 22)
        {
            if ($user_id <= 0)
            {
                $region_query = "SELECT D.* FROM  master.district AS D
							   LEFT JOIN users.district_coordinator AS DC
							   ON D.id=ANY(DC.district_id_list)
							   LEFT JOIN users.state_manager AS SM ON D.state_id = ANY(SM.state_id_list)
							   WHERE SM.user_id = ? AND DC.user_id IS NULL AND D.active_status = 1";

                $query_res = $this->db->query($region_query, $parent_manager_id)->result_array();
                return $query_res;

            }
            else
            {
                $region_query = "SELECT D.* FROM  master.district AS D
							   LEFT JOIN users.district_coordinator AS DC
							   ON D.id=ANY(DC.district_id_list)
							   LEFT JOIN users.state_manager AS SM ON D.state_id = ANY(SM.state_id_list)
							   WHERE SM.user_id = ? AND (DC.user_id = ? OR (DC.user_id IS NULL AND D.active_status = 1))";

                $query_res = $this->db->query($region_query, array($parent_manager_id, $user_id))->result_array();
                return $query_res;
            }

        } else if ($user_group_id == 8)
        {

            if ($user_id <= 0)
            {
                $region_query = "SELECT D.* FROM  master.district AS D
								   LEFT JOIN users.bd_district_coordinator AS DC
								   ON D.id=ANY(DC.district_id_list)
								   LEFT JOIN users.bd_regional_manager AS RM ON D.state_id = ANY(array(Select id from master.state As S
								   Where S.region_id = ANY(RM.region_id_list)))
								     WHERE RM.user_id = ? AND DC.user_id IS NULL AND D.active_status = 1";

                $query_res = $this->db->query($region_query, $parent_manager_id)->result_array();
                return $query_res;

            }
            else
            {
                $region_query = "SELECT D.* FROM  master.district AS D
								   LEFT JOIN users.bd_district_coordinator AS DC
								   ON D.id=ANY(DC.district_id_list)
								   LEFT JOIN users.bd_regional_manager AS RM ON D.state_id = ANY(array(Select id from master.state As S
								   Where S.region_id = ANY(RM.region_id_list)))
								     WHERE RM.user_id = ? AND (DC.user_id = ? OR (DC.user_id IS NULL AND D.active_status = 1))";

                $query_res = $this->db->query($region_query, array($parent_manager_id, $user_id))->result_array();
                return $query_res;
            }

        }

    }

	function get_sourcing_heads($PageRequestData = array())
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		$arrColumnsToBeSearched = array("SH.sr_head_name", "SH.sr_head_phone", "SH.sr_head_email");
		$arrSortByColumns = array(
			0 => null,
			1 => 'SH.sr_head_name',
			2 => 'SH.sr_head_phone',
			3 => 'SH.sr_head_email',
			4 => null,
			5 => null,
			6 => null
		);

		//Change query here
		$strQuery = "SELECT COUNT(SH.sr_head_id)::BIGINT AS total_record_count
					 FROM   users.vw_sr_head AS SH";

		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
		}

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount) {
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		} else {
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '') {
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				}

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}


			//Change query here
			$strQuery = "SELECT  	COUNT(SH.sr_head_id)::BIGINT AS total_filtered_count
			             FROM    	users.vw_sr_head AS SH
			             WHERE		TRUE ";
			$strQuery .= $SearchCondition;

			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			$strQuery = "SELECT  	SH.sr_head_id,
									SH.sr_head_name,
									SH.sr_head_email,
									SH.sr_head_phone,
									SH.active_status,
									SH.regional_manager_count
			             FROM    	users.vw_sr_head AS SH
			             WHERE		TRUE ";
			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			foreach ($QueryData->result() as $QueryRow) {
				$intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = $SerialNumber;
				$ResponseRow[] = $QueryRow->sr_head_name;
				$ResponseRow[] = $QueryRow->sr_head_phone;
				$ResponseRow[] = $QueryRow->sr_head_email;
				$ResponseRow[] = ($QueryRow->regional_manager_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View Regional Manager List" onclick="ViewRegionalManagerList(' . "'" . $QueryRow->sr_head_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->regional_manager_count . '</a></center>' : '<center><p>' . $QueryRow->regional_manager_count . '</p></center>';;
				$ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->sr_head_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
				$ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Sourcing Head Details" href="javascript:void(0);" onclick="EditSourcingHead(' . "'" . $QueryRow->sr_head_id . "'" . ')"><i class="icon-android-create"></i></a>';;
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" => intval($PageRequestData['draw']),
				"recordsTotal" => intval($intTotalRecordCount),
				"recordsFiltered" => intval($intTotalFilteredCount),
				"data" => $Data
			);

			return $ResponseData;
		}
	}


	function get_regional_managers_for_sr_head($sr_head_id = 0)
			{
				$strQuery = "SELECT  	RM.name AS regional_manager_name,
										A.email AS regional_manager_email,
										COALESCE(RM.phone) AS regional_manager_phone,
										(SELECT STRING_AGG(name, ', ' ORDER BY name) FROM master.regions WHERE id=ANY(RM.region_id_list)) AS region_name_list
							 FROM    	users.regional_manager AS RM
							 LEFT JOIN	users.accounts AS A ON A.id = RM.user_id
							 WHERE		RM.created_by = $sr_head_id";
				$ResponseData = $this->db->query($strQuery)->result_array();
				return $ResponseData;
			}

    function do_get_sector_for_vertical($id)
        {
            $query=$this->db->query('SELECT * FROM master.sector WHERE vertical_id = '.$id.'')->result_array();
            return $query;
        }

    function change_sr_head_active_status($RequestData = array())
    {
    	$ResponseData = array();
    	if (intval($RequestData['id']) > 0) {
    		$strStatus = 'TRUE';
    		if ($RequestData['active_status']) $strStatus = 'FALSE';
    		$strQuery = "UPDATE users.accounts";
    		$strQuery .= " SET is_active=" . $strStatus;
    		$strQuery .= " WHERE id=" . $RequestData['id'];
    		$this->db->query($strQuery);
    		if ($this->db->affected_rows()) {
    			$strQuery = "UPDATE users.sourcing_head";
    			$strQuery .= " SET active_status=" . $strStatus;
    			$strQuery .= " WHERE user_id=" . $RequestData['id'];
    			$this->db->query($strQuery);

    			if ($strStatus == "FALSE") {
								$ResponseData["message"] = "Sourcing Head Has Been Deactivated!";
    			} else {
    				$ResponseData["message"] = "Sourcing Head Has Been Activated!";
    			}
    		}
    	}

    	return $ResponseData;
    }

    function do_change_rs_vertical_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE master.vertical";
            $strQuery .= " SET active_status=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "Vertical Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "vertical Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function do_get_verticals($PageRequestData = array(),$parent_id = 0)
    {
        //$parent_id: RS admin id
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("V.vertical_name","V.vertical_manager_name");
        $arrSortByColumns = array(
            0 => null,
            1 => 'V.vertical_name',
            2 => 'V.vertical_manager_name',
            3 => null,
            4 => null,
            5 => null
        );

        //Change query here
        $strQuery = "SELECT COUNT(V.vertical_id)::BIGINT AS total_record_count
                     FROM   users.vw_vertical AS V";

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here
            $strQuery = "SELECT     COUNT(V.vertical_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_vertical AS V
                         WHERE      TRUE";
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT     V.vertical_id,
                                    V.vertical_name,
                                    V.vertical_manager_name,
                                    V.sector_count,
                                    V.active_status,
                                    V.vertical_manager_id,
                                    V.rs_admin_id
                         FROM       users.vw_vertical AS V
                         WHERE      TRUE";
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->vertical_name;
                $ResponseRow[] = $QueryRow->vertical_manager_name;
                $ResponseRow[] = ($QueryRow->sector_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View Sector List" onclick="SectorList(' . "'" . $QueryRow->vertical_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->sector_count . '</a></center>' : '<center><p>' . $QueryRow->sector_count . '</p></center>';;
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->vertical_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Vertical Details" href="javascript:void(0);" onclick="EditVertical(' . "'" . $QueryRow->vertical_id . "'" . ')"><i class="icon-android-create"></i></a>';;
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

	function get_sourcing_admins_all($PageRequestData = array(),$user_id=0,$user_group_id=0)
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		//Searching columns
		$arrColumnsToBeSearched = array("SA.sr_admin_name", "SA.sr_admin_phone", "SA.sr_admin_email");

		//Sorting columns
		$arrSortByColumns = array(
			0 => null,
			1 => 'SA.sr_admin_name',
			2 => 'SA.sr_admin_phone',
			3 => 'SA.sr_admin_email',
			4 => null,
			5 => null,
			6 => null
		);

		//Change query here for total record
		if($user_group_id == 10)
		{
			$strQuery = "SELECT COUNT(SA.sr_admin_id)::BIGINT AS total_record_count
					     FROM users.vw_sr_admin AS SA WHERE SA.sr_head_id=".$user_id."";
		}
		else
		{
			$strQuery = "SELECT COUNT(SA.sr_admin_id)::BIGINT AS total_record_count
					     FROM users.vw_sr_admin AS SA";
		}


		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
		}

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount) {
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		}
                else
                {
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '') {
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				}

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}


			//Change query here for filtered rows
			if($user_group_id == 10)
			{
				$strQuery = "SELECT COUNT(SA.sr_admin_id)::BIGINT AS total_filtered_count
			                 FROM users.vw_sr_admin AS SA
			                 WHERE TRUE AND SA.sr_head_id=".$user_id."";
			}
			else
			{
				$strQuery = "SELECT  COUNT(SA.sr_admin_id)::BIGINT AS total_filtered_count
			                 FROM users.vw_sr_admin AS SA
			                 WHERE TRUE ";
			}

			$strQuery .= $SearchCondition;

			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			if($user_group_id==10)	//Sourcing Head login
			{
				$strQuery = "SELECT SA.sr_admin_id,
									SA.sr_admin_name,
									SA.sr_admin_email,
									SA.sr_admin_phone,
									SA.active_status,
									SA.sr_head_name
			             FROM    	users.vw_sr_admin AS SA
			             WHERE		TRUE AND SA.sr_head_id=".$user_id."";
			}
			else //Super admin login
			{
				$strQuery = "SELECT SA.sr_admin_id,
									SA.sr_admin_name,
									SA.sr_admin_email,
									SA.sr_admin_phone,
									SA.active_status,
									SA.sr_head_name
			             FROM    	users.vw_sr_admin AS SA
			             WHERE		TRUE ";

			}
			//Main Query here for fetching details

			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			foreach ($QueryData->result() as $QueryRow) {
				$intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = $SerialNumber;
				$ResponseRow[] = $QueryRow->sr_admin_name;
				$ResponseRow[] = $QueryRow->sr_admin_email;
				$ResponseRow[] = $QueryRow->sr_admin_phone;
			/*	$ResponseRow[] = $QueryRow->sr_head_name;*/
                if($QueryRow->sr_head_name)
                    $ResponseRow[] = $QueryRow->sr_head_name;
                else
                    $ResponseRow[] = 'Not Assigned';

				$ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->sr_admin_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
				$ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Sourcing Admin Details" href="javascript:void(0);" onclick="EditSourcingAdmin(' . "'" . $QueryRow->sr_admin_id . "'" . ')"><i class="icon-android-create"></i></a>';;
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" => intval($PageRequestData['draw']),
				"recordsTotal" => intval($intTotalRecordCount),
				"recordsFiltered" => intval($intTotalFilteredCount),
				"data" => $Data
			);

			return $ResponseData;
		}
	}

    function get_bd_admins_all($PageRequestData = array(),$user_id=0,$user_group_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("ba.name", "ba.phone", "ba.email");

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'ba.name',
            2 => 'ba.phone',
            3 => 'ba.email',
            4 => null,
            5 => null,
            6 => null
        );

        //Change query here for total record
        if($user_group_id == 10)
        {
            $strQuery = "SELECT COUNT(SA.sr_admin_id)::BIGINT AS total_record_count
                     FROM   users.vw_sr_admin AS SA WHERE SA.sr_head_id=".$user_id."";
        }
        else
        {
            $strQuery = "SELECT COUNT(ba.id)::BIGINT AS total_record_count
                     FROM   users.vw_bd_admin AS ba";
        }

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '')
        {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount)
        {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '')
            {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++)
                {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            //Change query here for filtered rows
            if($user_group_id == 10)
            {
                $strQuery = "SELECT     COUNT(ba.id)::BIGINT AS total_filtered_count
                         FROM       users.vw_bd_admin AS ba
                         WHERE      TRUE AND ba.bd_head_id=".$user_id."";
            }
            else
            {
                $strQuery = "SELECT     COUNT(ba.id)::BIGINT AS total_filtered_count
                         FROM       users.vw_bd_admin AS ba
                         WHERE      TRUE ";
            }

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            if($user_group_id==10)  //Sourcing Head login
            {
                $strQuery = "SELECT ba.id,
                                    ba.name,
                                    ba.email,
                                    ba.phone,
                                    ba.active_status,
                                    ba.bd_head_name
                         FROM       users.vw_bd_admin AS ba
                         WHERE      TRUE AND SA.sr_head_id=".$user_id."";
            }
            else //Super admin login
            {
                $strQuery = "SELECT ba.id,
                                    ba.name,
                                    ba.email,
                                    ba.phone,
                                    ba.active_status,
                                    ba.bd_head_name
                            FROM   users.vw_bd_admin AS ba
                            WHERE  TRUE ";

            }
            //Main Query here for fetching details

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->name;
                $ResponseRow[] = $QueryRow->email;
                $ResponseRow[] = $QueryRow->phone;
                $ResponseRow[] = $QueryRow->bd_head_name;

                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit BD Admin Details" href="javascript:void(0);" onclick="EditBdAdmin(' . "'" . $QueryRow->id . "'" . ')"><i class="icon-android-create"></i></a>';;
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

	function get_regional_managers_list($PageRequestData = array(),$parent_id=0,$user_group_id)
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		//Searching columns
		$arrColumnsToBeSearched = array("RM.sr_rm_name", "RM.sr_rm_phone", "RM.sr_rm_email");

		//Sorting columns
		$arrSortByColumns = array(
			0 => null,
			1 => 'RM.sr_rm_name',
			2 => 'RM.sr_rm_phone',
			3 => 'RM.sr_rm_email',
			4 => null,
			5 => null,
			6 => null,
            7 => null
		);

		//Change query here for total record
        if($user_group_id == 10)
        {
          $strQuery = "SELECT COUNT(RM.sr_rm_id)::BIGINT AS total_record_count
                     FROM   users.vw_sr_rm AS RM WHERE RM.sr_head_id = ".$parent_id."";
        }
        else
        {
            $strQuery = "SELECT COUNT(RM.sr_rm_id)::BIGINT AS total_record_count
                     FROM   users.vw_sr_rm AS RM";
        }

		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
		}

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount) {
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		} else {
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '') {
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				}

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}

			//Change query here for filtered rows
            if($user_group_id == 10)
            {
                $strQuery = "SELECT COUNT(RM.sr_rm_id)::BIGINT AS total_filtered_count
                            FROM  users.vw_sr_rm AS RM
                            WHERE TRUE AND RM.sr_head_id = ".$parent_id."";
            }
            else
            {
               $strQuery = "SELECT COUNT(RM.sr_rm_id)::BIGINT AS total_filtered_count
                            FROM users.vw_sr_rm AS RM
                            WHERE TRUE ";
            }

			$strQuery .= $SearchCondition;

			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			//Main Query here for fetching details
            if($user_group_id == 10)
            {
                $strQuery = "SELECT RM.sr_rm_id,
                                    RM.sr_rm_name,
                                    RM.sr_rm_email,
                                    RM.sr_rm_phone,
                                    RM.active_status,
                                    RM.sr_head_name,
                                    RM.state_manager_count,
                                    RM.region_name_list
                            FROM users.vw_sr_rm AS RM
                            WHERE TRUE AND RM.sr_head_id = ".$parent_id."";
            }
            else
            {
               $strQuery = "SELECT  RM.sr_rm_id,
                                    RM.sr_rm_name,
                                    RM.sr_rm_email,
                                    RM.sr_rm_phone,
                                    RM.active_status,
                                    RM.sr_head_name,
                                    RM.state_manager_count,
                                    RM.region_name_list
                            FROM users.vw_sr_rm AS RM
                            WHERE TRUE ";
            }

			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			foreach ($QueryData->result() as $QueryRow) {
				$intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = $SerialNumber;
				$ResponseRow[] = $QueryRow->sr_rm_name;
				$ResponseRow[] = $QueryRow->sr_rm_email;
				$ResponseRow[] = $QueryRow->sr_rm_phone;
			    $ResponseRow[] = $QueryRow->region_name_list;
				$ResponseRow[] = $QueryRow->sr_head_name;
				$ResponseRow[] = ($QueryRow->state_manager_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View State Manager List" onclick="ViewStateManagerList(' . "'" . $QueryRow->sr_rm_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->state_manager_count . '</a></center>' : '<center><p>' . $QueryRow->state_manager_count . '</p></center>';;

				$ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->sr_rm_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;

				$ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Regional Manager Details" href="javascript:void(0);" onclick="EditSourcingRM(' . "'" . $QueryRow->sr_rm_id . "'" . ')"><i class="icon-android-create"></i></a>';;
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" => intval($PageRequestData['draw']),
				"recordsTotal" => intval($intTotalRecordCount),
				"recordsFiltered" => intval($intTotalFilteredCount),
				"data" => $Data
			);

			return $ResponseData;
		}
	}


    function get_bd_regional_managers_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("bm.name", "bm.email", "bm.phone");

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'bm.name',
            2 => 'bm.email',
            3 => 'bm.phone',
            4 => null,
            5 => null,
            6 => null

        );

        //Change query here for total record
        if($user_group_id == 13)
        {
            $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_record_count
                     FROM   users.vw_bd_manager AS bm WHERE bm.bd_head_id=".$parent_id."";

        }
        else
        {
            $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_record_count
                     FROM   users.vw_bd_manager AS bm";
        }

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '')
            {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++)
                {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows
            if($user_group_id == 13)
            {
                $strQuery = "SELECT     COUNT(bm.id)::BIGINT AS total_filtered_count
                         FROM       users.vw_bd_manager AS bm
                         WHERE      TRUE AND bm.bd_head_id=".$parent_id."";
            }
            else
            {
                 $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_filtered_count
                                FROM users.vw_bd_manager AS bm
                                WHERE TRUE ";
            }

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            //Main Query here for fetching details
            if($user_group_id == 13)
            {
                 $strQuery = "SELECT bm.id,
                                    bm.name,
                                    bm.email,
                                    bm.phone,
                                    bm.active_status,
                                    bm.region_name_list,
                                    bm.bd_head_name
                                FROM users.vw_bd_manager AS bm
                                WHERE TRUE AND bm.bd_head_id=".$parent_id."";
            }
            else if($user_group_id == 1)
            {
                 $strQuery = "SELECT bm.id,
                                    bm.name,
                                    bm.email,
                                    bm.phone,
                                    bm.active_status,
                                    bm.region_name_list,
                                    bm.bd_head_name
                         FROM       users.vw_bd_manager AS bm
                         WHERE      TRUE";
            }

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->name;
                $ResponseRow[] = $QueryRow->email;
                $ResponseRow[] = $QueryRow->phone;
                $ResponseRow[] = $QueryRow->region_name_list;
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;

                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit BD Regional Details" href="javascript:void(0);" onclick="EditBdRM(' . "'" . $QueryRow->id . "'" . ')"><i class="icon-android-create"></i></a>';;

                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function change_sr_rm_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0)
        {
        	$strStatus = 'TRUE';
        	if ($RequestData['active_status']) $strStatus = 'FALSE';
        	$strQuery = "UPDATE users.accounts";
        	$strQuery .= " SET is_active=" . $strStatus;
        	$strQuery .= " WHERE id=" . $RequestData['id'];
        	$this->db->query($strQuery);
        	if ($this->db->affected_rows())
            {
        		$strQuery = "UPDATE users.regional_manager";
        		$strQuery .= " SET active_status=" . $strStatus;
        		$strQuery .= " WHERE user_id=" . $RequestData['id'];
        		$this->db->query($strQuery);

        		if ($strStatus == "FALSE")
                {
        			$ResponseData["message"] = "Regional Manager Has Been Deactivated!";
        		}
                else
                {
        			$ResponseData["message"] = "Regional Manager Has Been Activated!";
        		}
            }
        }
        return $ResponseData;
    }

    function do_change_bd_rm_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0)
        {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
            $strQuery = "UPDATE users.bd_regional_manager";
            $strQuery .= " SET active_status=" . $strStatus;
            $strQuery .= " WHERE user_id=" . $RequestData['id'];
            $this->db->query($strQuery);

            if ($strStatus == "FALSE")
            {
                $ResponseData["message"] = "BD Regional Manager Has Been Deactivated!";
            } else {
                $ResponseData["message"] = "BD Regional Manager Has Been Activated!";
            }
        }
    }

    return $ResponseData;
    }


    function get_state_managers_for_sr_regional_manager($sr_rm_id = 0)
    {
    	$strQuery = "SELECT  	SM.name AS state_manager_name,
    							A.email AS state_manager_email,
    							SM.phone AS state_manager_phone,
    							(SELECT STRING_AGG(name, ', ' ORDER BY name) FROM master.state WHERE id=ANY(SM.state_id_list)) AS state_name_list
    				 FROM    	users.state_manager AS SM
    				 LEFT JOIN	users.accounts AS A ON A.id = SM.user_id
    				 WHERE		SM.created_by = $sr_rm_id";

    	$ResponseData = $this->db->query($strQuery)->result_array();

    	return $ResponseData;
    }

	function do_get_districts_data($PageRequestData = array())
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		//Searching columns
		$arrColumnsToBeSearched = array("DC.district_name", "DC.state_name", "DC.district_coordinator_name");

		//Sorting columns
		$arrSortByColumns = array(
			0 => null,
			1 => 'DC.district_name',
			2 => 'DC.state_name',
			3 => 'DC.district_coordinator_name',
			4 => null
		);

		//Change query here for total record
		$strQuery = "SELECT COUNT(*)::BIGINT AS total_record_count
					 FROM   users.vw_get_districts AS DC";

		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
		}

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount) {
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		} else {
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '') {
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				}

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}


			//Change query here for filtered rows
			$strQuery = "SELECT  	COUNT(*)::BIGINT AS total_filtered_count
			             FROM    	users.vw_get_districts AS DC
			             WHERE		TRUE ";
			$strQuery .= $SearchCondition;

			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			//Main Query here for fetching details
			$strQuery = "SELECT  	DC.district_name,
									DC.active_status,
									DC.state_name,
									DC.district_coordinator_name,
									DC.district_id
			             FROM    	users.vw_get_districts AS DC
			             WHERE		TRUE ";
			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			foreach ($QueryData->result() as $QueryRow)
                        {
				$intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = $SerialNumber;
				$ResponseRow[] = $QueryRow->district_name;
				$ResponseRow[] = $QueryRow->state_name;
				if($QueryRow->district_coordinator_name)
					$ResponseRow[] = $QueryRow->district_coordinator_name;
				else
					$ResponseRow[] ='Not Assigned';

				$ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->district_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;

				/*$ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Sourcing Admin Details" href="javascript:void(0);" onclick="EditDistrict(' . "'" . $QueryRow->district_id . "'" . ')"><i class="icon-android-create"></i></a>';;*/
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" => intval($PageRequestData['draw']),
				"recordsTotal" => intval($intTotalRecordCount),
				"recordsFiltered" => intval($intTotalFilteredCount),
				"data" => $Data
			);

			return $ResponseData;
		}
	}

	function change_district_status($RequestData = array())
	{
		$ResponseData = array();
		if (intval($RequestData['id']) > 0) {
			$strStatus = 1;
			if ($RequestData['active_status']==1) $strStatus = 0;
			else if($RequestData['active_status']==0) $strStatus = 1;
			$strQuery = "UPDATE master.district";
			$strQuery .= " SET active_status=" . $strStatus;
			$strQuery .= " WHERE id=" . $RequestData['id'];
			$this->db->query($strQuery);
			if ($this->db->affected_rows()) {
				return true;
			}
		}
		if ($strStatus == 0) {
					$ResponseData["message"] = "District Has Been Deactivated!";
				} else {
					$ResponseData["message"] = "District Has Been Activated!";
				}
		return $ResponseData;
	}

	function get_qualification_pack_list($PageRequestData = array())
	{
		$user = $this->pramaan->_check_module_task_auth(true);

		$strOrderBy = "";
		$SearchCondition = "";
		$Data = array();

		//Searching columns
		$arrColumnsToBeSearched = array("QP.qp_code", "QP.qp_name", "QP.qp_sector_name");

		//Sorting columns
		$arrSortByColumns = array(
			0 => null,
			1 => 'QP.qp_code',
			2 => 'QP.qp_name',
			3 => 'QP.qp_sector_name',
			4 => null,
			5 => null,
			6 => null
		);

		//Change query here for total record
		$strQuery = "SELECT COUNT(QP.qp_code)::BIGINT AS total_record_count
					 FROM   master.vw_qualification_pack AS QP";


		$strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
		$intTotalRecordCount = $strTotalRecordCount * 1;

		$intTotalFilteredCount = $intTotalRecordCount;

		if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '')
                {
			$strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
		}

		$StartIndex = $PageRequestData['start'];
		$PageLength = $PageRequestData['length'];
		if ($PageLength < 0) $PageLength = 'all';

		if (!$intTotalRecordCount)
                {
			return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
		}
                else
                {
			$SearchCondition = "";
			$sSearchVal = $_POST['search']['value'];
			if (isset($sSearchVal) && $sSearchVal != '')
                        {
				$SearchCondition = " AND (";
				for ($i = 0; $i < count($arrColumnsToBeSearched); $i++)
                                {
					$SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
				}

				$SearchCondition = substr_replace($SearchCondition, "", -3);
				$SearchCondition .= ')';
			}


			//Change query here for filtered rows
			$strQuery = "SELECT  	COUNT(QP.qp_code)::BIGINT AS total_filtered_count
			             FROM    	master.vw_qualification_pack AS QP
			             WHERE		TRUE ";

			$strQuery .= $SearchCondition;

			$intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

			$strQuery = "SELECT     QP.qp_code,
									QP.qp_id,
									QP.qp_name,
									QP.qp_sector_name,
									QP.active_status,
									QP.qp_interest_type_name
			             FROM    	master.vw_qualification_pack AS QP
			             WHERE		TRUE ";
			//Main Query here for fetching details

			$strQuery .= $SearchCondition;
			$strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

			$QueryData = $this->db->query($strQuery);

			$SerialNumber = $StartIndex;
			$intActiveStatus = 1;
			foreach ($QueryData->result() as $QueryRow) {
				$intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
				$ResponseRow = array();
				$SerialNumber++;
				$ResponseRow[] = $SerialNumber;
				$ResponseRow[] = $QueryRow->qp_code;
				$ResponseRow[] = $QueryRow->qp_name;
				$ResponseRow[] = $QueryRow->qp_sector_name;
				$ResponseRow[] = $QueryRow->qp_interest_type_name;

				$ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->qp_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
				$ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Sourcing Admin Details" href="javascript:void(0);" onclick="EditQualificationPack(' . "'" . $QueryRow->qp_id . "'" . ')"><i class="icon-android-create"></i></a>';;
				$Data[] = $ResponseRow;
			}

			$ResponseData = array(
				"draw" => intval($PageRequestData['draw']),
				"recordsTotal" => intval($intTotalRecordCount),
				"recordsFiltered" => intval($intTotalFilteredCount),
				"data" => $Data
			);

			return $ResponseData;
		}

	}

	function change_qp_status($RequestData = array())
	{
		$ResponseData = array();
		if (intval($RequestData['id']) > 0)
                {
			$strStatus = TRUE;
			if ($RequestData['active_status']==1)
			{
				$strQuery = "UPDATE master.qualification_pack";
				$strQuery .= " SET active_status=FALSE";
				$strQuery .= " WHERE id=" . $RequestData['id'];
			}
			else if($RequestData['active_status']==0)
			{
				$strQuery = "UPDATE master.qualification_pack";
				$strQuery .= " SET active_status=TRUE";
				$strQuery .= " WHERE id=" . $RequestData['id'];
			}

			$this->db->query($strQuery);
			if ($this->db->affected_rows()) {
				return true;
			}
		}
		if ($strStatus == 0)
                {
                     $ResponseData["message"] = "Qualification Pack Has Been Deactivated!";
		}
		else
                {
                  $ResponseData["message"] = "Qualification Pack Has Been Activated!";
                }
		return $ResponseData;
	}


	function do_edit_qualification_list($data1)
	{

		$data = array(
			'sector_id' => $data1['sector_id'],
			'interest_type_code' => $data1['interest_type']

		);
		$this->db->where('id', $data1['id']);
		$this->db->update("master.qualification_pack", $data);
		$this->db->trans_complete();

		if ($this->db->trans_status() === FALSE) {
			// generate an error... or use the log_message() function to log your error
			return false;
		} else if ($this->db->trans_status() === TRUE) {
			return true;
		}
	}

    function do_get_application_tracker_list($requestData=array(), $user_id=0)
    {
        $data = array();
        $columns = array(
            0 => null,
            1 => 'qualification_pack_name',
            2 => 'sector_name',
            3 => 'interested_count',
            4 => 'profile_submitted_count',
            5 => 'pending_customer_feedback_count',
            6 => 'profile_accepted_count',
            7 => 'profile_rejected_count',
            8 => 'interview_scheduled_count',
            9 => 'interview_attended_count',
            10 => 'interview_unattended_count',
            11 => 'selected_count',
            12 => 'rejected_count',
            13 => 'offer_in_pipeline_count',
            14 => 'offered_count',
            15 => 'offer_accepted_count',
            16 => 'offer_rejected_count',
            17 => 'joined_count',
            18 => 'not_joined_count'
        );

        $total_records=$this->db->query("SELECT COUNT(R.id)::bigint AS total_recs FROM neo_master.qualification_packs AS R")->row()->total_recs;

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
                $SearchCondition .= " AND (QP.name ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' ";
                $SearchCondition .= " OR S.name ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%') ";
            }

            $SearchQuery = "SELECT      COUNT(QP.id)::bigint AS total_filtered
                            FROM        neo_master.qualification_packs AS QP
                            LEFT JOIN	master.sector AS S ON S.id=QP.sector_id
                            WHERE       TRUE
                            $SearchCondition";

            $totalFiltered = $this->db->query($SearchQuery)->row()->total_filtered;

            $FinalQuery = "SELECT 	QP.id AS qualification_pack_id,
                                        (CASE COALESCE(QP.name,'') WHEN '' THEN '-NA-' ELSE FORMAT('%s (%s)',QP.name,QP.code) END) AS qualification_pack_name,
                                        S.name AS sector_name,
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
                            FROM		neo_master.qualification_packs AS QP
                            LEFT JOIN	neo_master.sectors AS S ON S.id = QP.sector_id
                            LEFT JOIN	neo_job.jobs AS J ON J.qualification_pack_id=QP.id AND COALESCE(J.customer_id,0)>0
                            LEFT JOIN 	neo_job.candidates_jobs AS CJ ON CJ.job_id=J.id AND COALESCE(CJ.candidate_id,0)>0
                            WHERE       TRUE
                            $SearchCondition ";

            $FinalQuery .= " GROUP BY QP.id,QP.name,S.name ";

            if($columns[$requestData['order'][0]['column']] != '')
                $FinalQuery .=" ORDER BY ". $columns[$requestData['order'][0]['column']]." ".$requestData['order'][0]['dir']."  ";
            else
                $FinalQuery .=" ORDER BY QP.name,S.name  ";

            $FinalQuery .= " LIMIT $limit OFFSET $pg ";

            $result_recs=$this->db->query($FinalQuery);

            $slno=$pg;
            $data = array();
            foreach ($result_recs->result() as $qp)
            {
                $row = array();
                $slno++;
                $row[] = $slno;
                $row[] = $qp->qualification_pack_name;
                $row[] = $qp->sector_name ?? 'N/A';
                $row[] = ($qp->interested_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',1)">' . $qp->interested_count . '</a></b></center>' : '<center>'.$qp->interested_count.'</center>';
                $row[] = ($qp->profile_submitted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',2)">' . $qp->profile_submitted_count . '</a></b></center>' : '<center>'.$qp->profile_submitted_count.'</center>';
                $row[] = ($qp->pending_customer_feedback_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',3)">' . $qp->pending_customer_feedback_count . '</a></b></center>' : '<center>'.$qp->pending_customer_feedback_count.'</center>';
                $row[] = ($qp->profile_accepted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',4)">' . $qp->profile_accepted_count . '</a></b></center>' : '<center>'.$qp->profile_accepted_count.'</center>';
                $row[] = ($qp->profile_rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',5)">' . $qp->profile_rejected_count . '</a></b></center>' : '<center>'.$qp->profile_rejected_count.'</center>';
                $row[] = ($qp->interview_scheduled_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',6)">' . $qp->interview_scheduled_count . '</a></b></center>' : '<center>'.$qp->interview_scheduled_count.'</center>';
                $row[] = ($qp->interview_attended_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',7)">' . $qp->interview_attended_count . '</a></b></center>' : '<center>'.$qp->interview_attended_count.'</center>';
                $row[] = ($qp->interview_unattended_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',8)">' . $qp->interview_unattended_count . '</a></b></center>' : '<center>'.$qp->interview_unattended_count.'</center>';
                $row[] = ($qp->selected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',9)">' . $qp->selected_count . '</a></b></center>' : '<center>'.$qp->selected_count.'</center>';
                $row[] = ($qp->rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',10)">' . $qp->rejected_count . '</a></b></center>' : '<center>'.$qp->rejected_count.'</center>';
                $row[] = ($qp->offer_in_pipeline_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',11)">' . $qp->offer_in_pipeline_count . '</a></b></center>' : '<center>'.$qp->offer_in_pipeline_count.'</center>';
                $row[] = ($qp->offered_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',12)">' . $qp->offered_count . '</a></b></center>' : '<center>'.$qp->offered_count.'</center>';
                $row[] = ($qp->offer_accepted_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',13)">' . $qp->offer_accepted_count . '</a></b></center>' : '<center>'.$qp->offer_accepted_count.'</center>';
                $row[] = ($qp->offer_rejected_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',14)">' . $qp->offer_rejected_count . '</a></b></center>' : '<center>'.$qp->offer_rejected_count.'</center>';
                $row[] = ($qp->joined_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',15)">' . $qp->joined_count . '</a></b></center>' : '<center>'.$qp->joined_count.'</center>';
                $row[] = ($qp->not_joined_count) ? '<center><b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_qp_candidates(' . "'" . $qp->qualification_pack_id . "'" . ',16)">' . $qp->not_joined_count . '</a></b></center>' : '<center>'.$qp->not_joined_count.'</center>';
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

    function get_rs_sector_list($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("S.name", "S.sector_manager_name_list", "S.vertical_name");

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'S.name',
            2 => 'S.sector_manager_name_list',
            3 => 'S.vertical_name',
            4 => null,
            5 => null,
            6 => null
        );

        //Change query here for total record
        $strQuery = "SELECT COUNT(S.id)::BIGINT AS total_record_count
                     FROM   users.vw_sector AS S";
        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows
            $strQuery = "SELECT     COUNT(S.id)::BIGINT AS total_filtered_count
                         FROM       users.vw_sector AS S
                         WHERE      TRUE ";

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT     S.id,
                                    S.name,
                                    S.vertical_id,
                                    S.active_status,
                                    S.vertical_name,
                                    S.sector_manager_name_list
                         FROM       users.vw_sector AS S
                         WHERE      TRUE ";
            //Main Query here for fetching details

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->name;
                if($QueryRow->sector_manager_name_list)
                    $ResponseRow[] = $QueryRow->sector_manager_name_list;
                else
                    $ResponseRow[] = 'Not Assigned';


                $ResponseRow[] = $QueryRow->vertical_name;


                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Sector Status" href="javascript:void(0);" onclick="EditSectorStatus(' . "'" . $QueryRow->id . "'" . ')"><i class="icon-android-create"></i></a>';;
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }

    }

    function change_user_status($RequestData = array())
    {

		$ResponseData = array();

		if (intval($RequestData['id']) > 0)
		{
			$strStatus = TRUE;
			if ($RequestData['is_active']==1)
			{
				$strQuery = "UPDATE neo_user.users";
				$strQuery .= " SET is_active=FALSE";
				$strQuery .= " WHERE id=" . $RequestData['id'];
			}
			else if($RequestData['is_active']==0)
			{
				$strQuery = "UPDATE neo_user.users";
				$strQuery .= " SET is_active=TRUE";
				$strQuery .= " WHERE id=" . $RequestData['id'];
			}

			$this->db->query($strQuery);
			if ($this->db->affected_rows())
			{
				return true;
			}
		}

		if ($strStatus == 0)
		{
			$ResponseData["message"] = "User Has Been Deactivated!";
		}
		else
		{
			$ResponseData["message"] = "User Has Been Activated!";
		}

		return $ResponseData;
    }
    
    
    function change_center_status($RequestData = array())
    {

		$ResponseData = array();

		if (intval($RequestData['id']) > 0)
		{
			$strStatus = TRUE;
			if ($RequestData['status']==1)
			{
				$strQuery = "UPDATE neo_user.centers";
				$strQuery .= " SET status=0";
				$strQuery .= " WHERE id=" . $RequestData['id'];
			}
			else if($RequestData['status']==0)
			{
				$strQuery = "UPDATE neo_user.centers";
				$strQuery .= " SET status=1";
				$strQuery .= " WHERE id=" . $RequestData['id'];
			}

			$this->db->query($strQuery);
			if ($this->db->affected_rows())
			{
				return true;
			}
		}

		if ($strStatus == 0)
		{
			$ResponseData["message"] = "Center Has Been Deactivated!";
		}
		else
		{
			$ResponseData["message"] = "Center Has Been Activated!";
		}

		return $ResponseData;
    }
    

    public function get_state_managers_list($PageRequestData = array(),$parent_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("SM.state_manager_name","SM.state_manager_email","SM.state_manager_phone","SM.regional_manager_name","SM.state_name_list","SM.district_coordinator_count");

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'SM.state_manager_name',
            2 => 'SM.state_manager_email',
            3 => 'SM.state_manager_phone',
            4 => 'SM.regional_manager_name',
            5 => 'SM.state_name_list',
            6 => 'SM.district_coordinator_count',
            7 => null,
            8 => null
        );

        $strQuery = "SELECT COUNT(SM.state_manager_id)::BIGINT AS total_record_count
                     FROM   users.vw_state_manager AS SM WHERE SM.regional_manager_id=".$parent_id."";

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount)
        {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        }
        else
        {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '')
            {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++)
                {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }

            $strQuery = "SELECT     COUNT(SM.state_manager_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_state_manager AS SM
                         WHERE      TRUE AND SM.regional_manager_id=".$parent_id."";

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

             $strQuery = "SELECT SM.state_manager_id,
                                SM.state_manager_name,
                                SM.state_manager_email,
                                SM.state_manager_phone,
                                SM.district_coordinator_count,
                                SM.active_status,
                                SM.state_name_list,
                                SM.regional_manager_name
                     FROM       users.vw_state_manager AS SM
                     WHERE      TRUE AND SM.regional_manager_id=".$parent_id."";


            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->state_manager_name;
                $ResponseRow[] = $QueryRow->state_manager_email;
                $ResponseRow[] = $QueryRow->state_manager_phone;
                $ResponseRow[] = $QueryRow->regional_manager_name;
                $ResponseRow[] = $QueryRow->state_name_list;
                $ResponseRow[] = ($QueryRow->district_coordinator_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View State Manager List" onclick="ViewDistrictCoordinatorList(' . "'" . $QueryRow->state_manager_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->district_coordinator_count . '</a></center>' : '<center><p>' . $QueryRow->district_coordinator_count . '</p></center>';;
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->state_manager_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Sourcing Admin Details" href="javascript:void(0);" onclick="EditStateManager(' . "'" . $QueryRow->state_manager_id . "'" . ')"><i class="icon-android-create"></i></a>';;
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }

    }

    public function get_show_state_managers_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("SM.state_manager_name", "SM.state_manager_email", "SM.state_manager_phone");

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'SM.state_manager_name',
            2 => 'SM.state_manager_email',
            3 => 'SM.state_manager_phone',
            4 => null,
            5 => null

        );

        //Change query here for total record
        if($user_group_id == 10)
        {
            $strQuery = "SELECT COUNT(*)::BIGINT AS total_record_count
                     FROM   users.vw_state_manager AS SM WHERE SM.sr_head_id=".$parent_id."";

        }
        else
        {
            $strQuery = "SELECT COUNT(SM.state_manager_id)::BIGINT AS total_record_count
                     FROM   users.vw_state_manager AS SM";

        }

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows
            if($user_group_id == 10)
            {
                $strQuery = "SELECT     COUNT(SM.state_manager_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_state_manager AS SM
                         WHERE      TRUE AND SM.sr_head_id=".$parent_id."";
            }
            else
            {
                 $strQuery = "SELECT     COUNT(SM.state_manager_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_state_manager AS SM
                         WHERE      TRUE ";
            }

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            //Main Query here for fetching details
            if($user_group_id == 10)
            {
                 $strQuery = "SELECT SM.state_manager_id,
                                    SM.state_manager_name,
                                    SM.state_manager_email,
                                    SM.state_manager_phone,
                                    SM.district_coordinator_count,
                                    SM.active_status,
                                    SM.state_name_list,
                                    SM.regional_manager_name
                         FROM       users.vw_state_manager AS SM
                         WHERE      TRUE AND SM.sr_head_id=".$parent_id."";
            }
            else if($user_group_id == 1)
            {
                 $strQuery = "SELECT SM.state_manager_id,
                                    SM.state_manager_name,
                                    SM.state_manager_email,
                                    SM.state_manager_phone,
                                    SM.district_coordinator_count,
                                    SM.active_status,
                                    SM.state_name_list,
                                    SM.regional_manager_name
                         FROM       users.vw_state_manager AS SM
                         WHERE      TRUE";
            }

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            foreach ($QueryData->result() as $QueryRow) {
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->state_manager_name;
                $ResponseRow[] = $QueryRow->state_manager_email;
                $ResponseRow[] = $QueryRow->state_manager_phone;
                $ResponseRow[] = $QueryRow->state_name_list;
                $ResponseRow[] = $QueryRow->regional_manager_name;


                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    public function get_show_bd_manager_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
            $user = $this->pramaan->_check_module_task_auth(true);
            $strOrderBy = "";
            $SearchCondition = "";
            $Data = array();

            //Searching columns
            $arrColumnsToBeSearched = array("bm.name", "bm.email", "bm.phone");

            //Sorting columns
            $arrSortByColumns = array(
                0 => null,
                1 => 'bm.name',
                2 => 'bm.email',
                3 => 'bm.phone',
                4 => null,
                5 => null

            );

            //Change query here for total record
            if($user_group_id == 12)
            {
                $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_record_count
                         FROM   users.vw_bd_manager AS bm WHERE bm.bd_head_id=".$parent_id."";

            }
            else
            {
                $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_record_count
                         FROM   users.vw_bd_manager AS bm";

            }

            $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
            $intTotalRecordCount = $strTotalRecordCount * 1;

            $intTotalFilteredCount = $intTotalRecordCount;

            if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
                $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
            }

            $StartIndex = $PageRequestData['start'];
            $PageLength = $PageRequestData['length'];
            if ($PageLength < 0) $PageLength = 'all';

            if (!$intTotalRecordCount) {
                return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
            } else {
                $SearchCondition = "";
                $sSearchVal = $_POST['search']['value'];
                if (isset($sSearchVal) && $sSearchVal != '') {
                    $SearchCondition = " AND (";
                    for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                        $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                    }

                    $SearchCondition = substr_replace($SearchCondition, "", -3);
                    $SearchCondition .= ')';
                }


                //Change query here for filtered rows
                if($user_group_id == 12)
                {
                    $strQuery = "SELECT     COUNT(bm.id)::BIGINT AS total_filtered_count
                             FROM       users.vw_bd_manager AS bm
                             WHERE      TRUE AND bm.bd_head_id=".$parent_id."";
                }
                else
                {
                     $strQuery = "SELECT     COUNT(bm.id)::BIGINT AS total_filtered_count
                             FROM       users.vw_bd_manager AS bm
                             WHERE      TRUE ";
                }

                $strQuery .= $SearchCondition;

                $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

                //Main Query here for fetching details
                if($user_group_id == 12)
                {
                     $strQuery = "SELECT bm.id,
                                        bm.name,
                                        bm.email,
                                        bm.phone,
                                        bm.active_status,
                                        bm.region_name_list,
                                        bm.bd_head_name
                             FROM       users.vw_bd_manager AS bm
                             WHERE      TRUE AND bm.bd_head_id=".$parent_id."";
                }
                else if($user_group_id == 1)
                {
                     $strQuery = "SELECT bm.id,
                                        bm.name,
                                        bm.email,
                                        bm.phone,
                                        bm.active_status,
                                        bm.region_name_list,
                                        bm.bd_head_name
                             FROM       users.vw_bd_manager AS bm
                             WHERE      TRUE";
                }

                $strQuery .= $SearchCondition;
                $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

                $QueryData = $this->db->query($strQuery);

                $SerialNumber = $StartIndex;
                foreach ($QueryData->result() as $QueryRow) {
                    $ResponseRow = array();
                    $SerialNumber++;
                    $ResponseRow[] = $SerialNumber;
                    $ResponseRow[] = $QueryRow->name;
                    $ResponseRow[] = $QueryRow->email;
                    $ResponseRow[] = $QueryRow->phone;
                    $ResponseRow[] = $QueryRow->region_name_list;
                    $ResponseRow[] = $QueryRow->bd_head_name;


                    $Data[] = $ResponseRow;
                }

                $ResponseData = array(
                    "draw" => intval($PageRequestData['draw']),
                    "recordsTotal" => intval($intTotalRecordCount),
                    "recordsFiltered" => intval($intTotalFilteredCount),
                    "data" => $Data
                );

                return $ResponseData;
            }

    }

    public function get_show_bd_coordinator_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("bc.name", "bc.email", "bc.phone");

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'bc.name',
            2 => 'bc.email',
            3 => 'bc.phone',
            4 => null,
            5 => null

        );

        //Change query here for total record
        if($user_group_id == 12)
        {
            $strQuery = "SELECT COUNT(bc.id)::BIGINT AS total_record_count
                     FROM   users.vw_bd_coordinator AS bc WHERE bc.bh_head_id=".$parent_id."";

        }
        else
        {
            $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_record_count
                     FROM   users.vw_bd_manager AS bm";

        }

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows
            if($user_group_id == 12)
            {
                $strQuery = "SELECT     COUNT(bc.id)::BIGINT AS total_filtered_count
                         FROM       users.vw_bd_coordinator AS bc
                         WHERE      TRUE AND bc.bh_head_id=".$parent_id."";
            }
            else
            {
                 $strQuery = "SELECT     COUNT(bc.id)::BIGINT AS total_filtered_count
                         FROM       users.vw_bd_coordinator AS bc
                         WHERE      TRUE ";
            }

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            //Main Query here for fetching details
            if($user_group_id == 12)
            {
                 $strQuery = "SELECT bc.id,
                                    bc.name,
                                    bc.email,
                                    bc.phone,
                                    bc.active_status,
                                    bc.district_name_list,
                                    bc.bd_rm_name
                         FROM       users.vw_bd_coordinator AS bc
                         WHERE      TRUE AND bc.bh_head_id=".$parent_id."";
            }
            else if($user_group_id == 1)
            {
                 $strQuery = "SELECT bc.id,
                                    bc.name,
                                    bc.email,
                                    bc.phone,
                                    bc.active_status,
                                    bc.district_name_list,
                                    bc.bd_rm_name
                         FROM       users.vw_bd_coordinator AS bc
                         WHERE      TRUE";
            }

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            foreach ($QueryData->result() as $QueryRow) {
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->name;
                $ResponseRow[] = $QueryRow->email;
                $ResponseRow[] = $QueryRow->phone;
                $ResponseRow[] = $QueryRow->district_name_list;
                $ResponseRow[] = $QueryRow->bd_rm_name;


                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }

    }

    function get_district_coordinator_for_sr_state_manager($sr_sm_id = 0)
    {
        $strQuery = "SELECT     DC.name AS district_coordinator_name,
                                A.email AS district_coordinator_email,
                                DC.phone AS district_coordinator_phone,
                                (SELECT STRING_AGG(name, ', ' ORDER BY name) FROM master.district WHERE id=ANY(DC.district_id_list)) AS district_name_list
                     FROM       users.district_coordinator AS DC
                     LEFT JOIN  users.accounts AS A ON A.id = DC.user_id
                     WHERE      DC.created_by = $sr_sm_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }


    function do_change_state_manager_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0)
        {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows())
            {
            $strQuery = "UPDATE users.state_manager";
            $strQuery .= " SET active_status=" . $strStatus;
            $strQuery .= " WHERE user_id=" . $RequestData['id'];
            $this->db->query($strQuery);

            if ($strStatus == "FALSE")
            {
                $ResponseData["message"] = "State Manager Has Been Deactivated!";
            }
            else
            {
                $ResponseData["message"] = "State Manager Has Been Activated!";
            }
            }
        }

        return $ResponseData;
    }


    function get_district_coordinator_list($PageRequestData = array(),$sm_id=0,$user_group_id=0)
    {
          $user = $this->pramaan->_check_module_task_auth(true);
            $strOrderBy = "";
            $SearchCondition = "";
            $Data = array();

            //Searching columns
            $arrColumnsToBeSearched = array("DC.dcor_name","DC.dcor_email","DC.dcor_phone","DC.sr_partner_count","DC.district_name_list","DC.state_name","DC.state_manager_name");

            //Sorting columns
            $arrSortByColumns = array(
                0 => null,
                1 => 'DC.dcor_name',
                2 => 'DC.dcor_email',
                3 => 'DC.dcor_phone',
                4 => 'DC.district_name_list',
                5 => 'DC.state_name',
                6 => 'DC.state_manager_name',
                7 => 'DC.sr_partner_count',
                8 => null,
                9 => null
            );

            $strQuery = "SELECT COUNT(DC.dcor_user_id)::BIGINT AS total_record_count
                         FROM   users.vw_district_coordinator AS DC WHERE DC.state_manager_id=".$sm_id."";

            $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
            $intTotalRecordCount = $strTotalRecordCount * 1;

            $intTotalFilteredCount = $intTotalRecordCount;

            if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
                $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
            }

            $StartIndex = $PageRequestData['start'];
            $PageLength = $PageRequestData['length'];
            if ($PageLength < 0) $PageLength = 'all';

            if (!$intTotalRecordCount) {
                return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
            } else {
                $SearchCondition = "";
                $sSearchVal = $_POST['search']['value'];
                if (isset($sSearchVal) && $sSearchVal != '') {
                    $SearchCondition = " AND (";
                    for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                        $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                    }

                    $SearchCondition = substr_replace($SearchCondition, "", -3);
                    $SearchCondition .= ')';
                }


                 $strQuery = "SELECT     COUNT(DC.dcor_user_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_district_coordinator AS DC
                         WHERE      TRUE AND DC.state_manager_id=".$sm_id."";

                $strQuery .= $SearchCondition;

                $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;


                 $strQuery = "SELECT DC.dcor_user_id,
                                     DC.dcor_name,
                                     DC.dcor_email,
                                     DC.dcor_phone,
                                     DC.state_manager_id,
                                     DC.sr_partner_count,
                                     DC.district_name_list,
                                     DC.state_name,
                                     DC.state_manager_name,
                                     DC.active_status
                                FROM users.vw_district_coordinator AS DC
                                WHERE TRUE
                                AND DC.state_manager_id=".$sm_id."";

                $strQuery .= $SearchCondition;
                $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

                $QueryData = $this->db->query($strQuery);

                $SerialNumber = $StartIndex;
                $intActiveStatus = 1;
                foreach ($QueryData->result() as $QueryRow) {
                    $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                    $ResponseRow = array();
                    $SerialNumber++;
                    $ResponseRow[] = $SerialNumber;
                    $ResponseRow[] = $QueryRow->dcor_name;
                    $ResponseRow[] = $QueryRow->dcor_email;
                    $ResponseRow[] = $QueryRow->dcor_phone;
                    $ResponseRow[] = $QueryRow->district_name_list;
                    $ResponseRow[] = $QueryRow->state_name;
                    $ResponseRow[] = $QueryRow->state_manager_name;
                    $ResponseRow[] = ($QueryRow->sr_partner_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View State Manager List" onclick="ViewSourcingPartnerList(' . "'" . $QueryRow->dcor_user_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->sr_partner_count . '</a></center>' : '<center><p>' . $QueryRow->sr_partner_count . '<p></center>';;
                    $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->dcor_user_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
                    $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit District Coordinator Details" href="javascript:void(0);" onclick="EditDistrictCoordinator(' . "'" . $QueryRow->dcor_user_id . "'" . ')"><i class="icon-android-create"></i></a>';;
                    $Data[] = $ResponseRow;
                }

                $ResponseData = array(
                    "draw" => intval($PageRequestData['draw']),
                    "recordsTotal" => intval($intTotalRecordCount),
                    "recordsFiltered" => intval($intTotalFilteredCount),
                    "data" => $Data);
                return $ResponseData;
            }
    }


    function get_show_district_coordinator_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
         $user = $this->pramaan->_check_module_task_auth(true);
            $strOrderBy = "";
            $SearchCondition = "";
            $Data = array();

            //Searching columns
            $arrColumnsToBeSearched = array("DC.dcor_name","DC.dcor_email","DC.dcor_phone");

            //Sorting columns
            $arrSortByColumns = array(
                0 => null,
                1 => 'DC.dcor_name',
                2 => 'DC.dcor_email',
                3 => 'DC.dcor_phone',
                4 => null

            );

            //Change query here for total record
            if($user_group_id == 10)
            {
                 $strQuery = "SELECT COUNT(DC.dcor_user_id)::BIGINT AS total_record_count
                         FROM   users.vw_district_coordinator AS DC WHERE DC.sourcing_head_id = ".$parent_id."";


            }
            else
            {
                $strQuery = "SELECT COUNT(DC.dcor_user_id)::BIGINT AS total_record_count
                         FROM   users.vw_district_coordinator AS DC";

            }

            $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
            $intTotalRecordCount = $strTotalRecordCount * 1;

            $intTotalFilteredCount = $intTotalRecordCount;

            if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
                $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
            }

            $StartIndex = $PageRequestData['start'];
            $PageLength = $PageRequestData['length'];
            if ($PageLength < 0) $PageLength = 'all';

            if (!$intTotalRecordCount) {
                return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
            } else {
                $SearchCondition = "";
                $sSearchVal = $_POST['search']['value'];
                if (isset($sSearchVal) && $sSearchVal != '') {
                    $SearchCondition = " AND (";
                    for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                        $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                    }

                    $SearchCondition = substr_replace($SearchCondition, "", -3);
                    $SearchCondition .= ')';
                }


                //Change query here for filtered rows
                if($user_group_id == 10)
                {

                     $strQuery = "SELECT     COUNT(DC.dcor_user_id)::BIGINT AS total_filtered_count
                             FROM       users.vw_district_coordinator AS DC
                             WHERE      TRUE AND  DC.sourcing_head_id = ".$parent_id."";
                }
                else
                {
                     $strQuery = "SELECT     COUNT(DC.dcor_user_id)::BIGINT AS total_filtered_count
                             FROM       users.vw_district_coordinator AS DC
                             WHERE      TRUE";
                }

                $strQuery .= $SearchCondition;

                $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

                //Main Query here for fetching details
                if($user_group_id == 10)
                {
                    $strQuery = "SELECT DC.dcor_user_id,
                                         DC.dcor_name,
                                         DC.dcor_email,
                                         DC.dcor_phone,
                                         DC.state_manager_id,
                                         DC.state_manager_name,
                                         DC.sr_partner_count,
                                         DC.district_name_list,
                                         DC.active_status
                             FROM       users.vw_district_coordinator AS DC
                             WHERE      TRUE AND DC.sourcing_head_id = ".$parent_id."";
                }
                else if($user_group_id == 1)
                {
                     $strQuery = "SELECT DC.dcor_user_id,
                                         DC.dcor_name,
                                         DC.dcor_email,
                                         DC.dcor_phone,
                                         DC.state_manager_id,
                                         DC.state_manager_name,
                                         DC.sr_partner_count,
                                         DC.district_name_list,
                                         DC.active_status
                             FROM       users.vw_district_coordinator AS DC
                             WHERE      TRUE";
                }

                $strQuery .= $SearchCondition;
                $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

                $QueryData = $this->db->query($strQuery);

                $SerialNumber = $StartIndex;
                $intActiveStatus = 1;
                foreach ($QueryData->result() as $QueryRow) {
                    $ResponseRow = array();
                    $SerialNumber++;
                    $ResponseRow[] = $SerialNumber;
                    $ResponseRow[] = $QueryRow->dcor_name;
                    $ResponseRow[] = $QueryRow->dcor_email;
                    $ResponseRow[] = $QueryRow->dcor_phone;
                    $ResponseRow[] = $QueryRow->district_name_list;
                    if($QueryRow->state_manager_name)
                        $ResponseRow[] = $QueryRow->state_manager_name;
                    else
                        $ResponseRow[] = 'Not Assigned';

                    $Data[] = $ResponseRow;
                }

                $ResponseData = array(
                    "draw" => intval($PageRequestData['draw']),
                    "recordsTotal" => intval($intTotalRecordCount),
                    "recordsFiltered" => intval($intTotalFilteredCount),
                    "data" => $Data
                );

                return $ResponseData;
            }
    }

    function do_get_sourcing_partner_for_district_coordinator($dc_id = 0)
    {
        $strQuery = "SELECT     P.name AS sourcing_partner_name,
                                A.email AS sourcing_partner_email,
                                P.phone AS sourcing_partner_phone
                     FROM       users.partners AS P
                     LEFT JOIN  users.accounts AS A ON A.id = P.user_id
                     WHERE      P.coordinator_id = $dc_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function do_change_sr_admin_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.sourcing_admin";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "Sourcing Admin Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "Sourcing Admin Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function do_change_bd_admin_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.bd_admin";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "BD Admin Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "BD Admin Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function do_change_district_coordinator_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.district_coordinator";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "District Coordinator Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "District Coordinator Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function get_district_coordinator_data_by_id($dis_cor_id)
    {

    $query = "SELECT * FROM users.district_coordinator WHERE user_id = ?";

    $query_res = $this->db->query($query,array($dis_cor_id));

    if($query_res->num_rows())
    return $query_res->result_array();
    else
    return false;
    }

    function get_bd_district_coordinator_data_by_id($dis_cor_id)
    {

    $query = "SELECT * FROM users.bd_district_coordinator WHERE user_id = ?";

    $query_res = $this->db->query($query,array($dis_cor_id));

    if($query_res->num_rows())
        return $query_res->result_array();
    else
        return false;
    }


    function do_change_job_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['job_id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['job_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE job_process.job_detail";
            $strQuery .= " SET job_status = NOT job_status , ";
            $strQuery .= " modified_by = ".$RequestData['modified_by']. ", ";
            $strQuery .= " modified_on = NOW()";
            $strQuery .= " WHERE job_id=" . $RequestData['job_id'];
             $strQuery .=" AND location_id=" .$RequestData['location_id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "Job has been closed!";
                } else {
                    $ResponseData["message"] = "Job has been re-opened!";
                }
            }
        }

        return $ResponseData;
    }



    function get_sourcing_partner_list($PageRequestData = array(),$parent_id=0)
    {
        //parent_id: district_coordinator id
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("sp.sr_partner_name","sp.sr_partner_phone","sp.sr_partner_email");
        $arrSortByColumns = array(
            0 => null,
            1 => 'sp.sr_partner_name',
            2 => null,
            3 => 'sp.sr_partner_phone',
            4 => 'sp.sr_partner_email',
            5 => null,
            6 => null
        );

        //Change query here
        $strQuery = "SELECT COUNT(sp.sr_partner_id)::BIGINT AS total_record_count
                     FROM   users.vw_sourcing_partner AS sp WHERE coordinator_id =".$parent_id."";

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here
            $strQuery = "SELECT     COUNT(sp.sr_partner_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_sourcing_partner AS sp
                         WHERE      TRUE AND coordinator_id = ".$parent_id."";
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT     sp.partner_type_id,
                                    sp.partner_type_name,
                                    sp.sr_partner_phone,
                                    sp.sr_partner_email,
                                    sp.sr_partner_id,
                                    sp.sr_partner_name,
                                    sp.active_status
                         FROM       users.vw_sourcing_partner AS sp
                         WHERE      TRUE AND coordinator_id = ".$parent_id."";
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->sr_partner_name;
                $ResponseRow[] = $QueryRow->partner_type_name;
                $ResponseRow[] = $QueryRow->sr_partner_phone;
                $ResponseRow[] = $QueryRow->sr_partner_email;

                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->sr_partner_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
                if($QueryRow->partner_type_id == 1)
                {
                    $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Center and Associate details" href="javascript:void(0);" onclick="EditSourcingPartner(' . "'" . $QueryRow->sr_partner_id . "'" . ')"><i class="icon-android-create"></i></a>';
                }
                else
                {
                    $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Center and Associate details" href="javascript:void(0);" onclick="EditSourcingPartner(' . "'" . $QueryRow->sr_partner_id . "'" . ')"><i class="icon-android-create"></i></a>&nbsp;<a class="btn btn-sm btn-primary" title="Center and Associate details" href="javascript:void(0);" onclick="ViewCenterList(' . "'" . $QueryRow->sr_partner_id . "'" . ')"><i class="icon-person"></i></a>';
                }

                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function do_change_sr_partner_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.partners";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "Sourcing Partner Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "Sourcing Partner Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }


    function get_bd_head_list($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        $arrColumnsToBeSearched = array("bd.bd_head_name", "bd.bd_head_phone", "bd.bd_head_email");
        $arrSortByColumns = array(
            0 => null,
            1 => 'bd.bd_head_name',
            2 => 'bd.bd_head_phone',
            3 => 'bd.bd_head_email',
            4 => null,
            5 => null,
            6 => null
        );

        //Change query here
        $strQuery = "SELECT COUNT(bd.bd_head_id)::BIGINT AS total_record_count
                     FROM   users.vw_bd_head AS bd";

        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here
            $strQuery = "SELECT     COUNT(bd.bd_head_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_bd_head AS bd
                         WHERE      TRUE ";
            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "SELECT     bd.bd_head_id,
                                    bd.bd_head_name,
                                    bd.bd_head_email,
                                    bd.bd_head_phone,
                                    bd.active_status,
                                    bd.bd_regional_manager_count
                         FROM       users.vw_bd_head AS bd
                         WHERE      TRUE ";
            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            $intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->bd_head_name;
                $ResponseRow[] = $QueryRow->bd_head_phone;
                $ResponseRow[] = $QueryRow->bd_head_email;
                $ResponseRow[] = ($QueryRow->bd_regional_manager_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View Bd Regional Manager List" onclick="ViewBdRegionalManagerList(' . "'" . $QueryRow->bd_head_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->bd_regional_manager_count . '</a></center>' : '<center><p>' . $QueryRow->bd_regional_manager_count . '</p></center>';;
                $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->bd_head_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Bd Head Details" href="javascript:void(0);" onclick="EditBdHead(' . "'" . $QueryRow->bd_head_id . "'" . ')"><i class="icon-android-create"></i></a>';;
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }


    function do_change_bd_head_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.bd_head";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "Bd Head Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "Bd Head Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }
    function do_change_bd_coordinator_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.bd_district_coordinator";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "BD Coordinator Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "BD Coordinator Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function do_change_bd_executive_active_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['id']) > 0) {
            $strStatus = 'TRUE';
            if ($RequestData['active_status']) $strStatus = 'FALSE';
            $strQuery = "UPDATE users.accounts";
            $strQuery .= " SET is_active=" . $strStatus;
            $strQuery .= " WHERE id=" . $RequestData['id'];
            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {
                $strQuery = "UPDATE users.bd_executive";
                $strQuery .= " SET active_status=" . $strStatus;
                $strQuery .= " WHERE user_id=" . $RequestData['id'];
                $this->db->query($strQuery);

                if ($strStatus == "FALSE") {
                    $ResponseData["message"] = "BD Executive Has Been Deactivated!";
                } else {
                    $ResponseData["message"] = "BD Executive Has Been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    function get_bd_regional_managers_for_bd_head($bd_head_id = 0)
    {
        $strQuery = "SELECT brm.name AS bd_regional_manager_name,
                            A.email AS bd_regional_manager_email,
                            brm.phone AS bd_regional_manager_phone,
                            (SELECT STRING_AGG(name, ', ' ORDER BY name) FROM master.regions WHERE id=ANY(brm.region_id_list)) AS region_name_list
                     FROM users.bd_regional_manager AS brm
                     LEFT JOIN users.accounts AS A ON A.id = brm.user_id
                     WHERE brm.created_by = $bd_head_id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    function do_get_bd_emp_for_bd_executive($id = 0)
    {
        $strQuery = "SELECT e.name,A.email,e.phone
                        FROM users.employers AS e
                        LEFT JOIN  users.accounts AS A ON A.id = e.user_id
                        WHERE      e.created_by = $id";
        $ResponseData = $this->db->query($strQuery)->result_array();
        return $ResponseData;
    }

    //After lost data
    function do_get_region_list($PageRequestData = array(),$country_id=0)
    {
          $user = $this->pramaan->_check_module_task_auth(true);
            $strOrderBy = "";
            $SearchCondition = "";
            $Data = array();

            //Searching columns
            $arrColumnsToBeSearched = array("R.region_name","R.regional_manager_name");

            //Sorting columns
            $arrSortByColumns = array(
                0 => null,
                1 => 'R.region_name',
                2 => 'R.regional_manager_name',
                3 => null,
                4 => null,
                5 => null

            );

            //Change query here for total record
    /*        if($user_group_id == 20)
            {
                $strQuery = "SELECT COUNT(*)::BIGINT AS total_record_count
                         FROM   users.vw_state_manager AS SM WHERE SM.regional_manager_id=".$parent_id."";

            }
            else
            {*/
                $strQuery = "SELECT COUNT(R.region_id)::BIGINT AS total_record_count
                         FROM   master.vw_region AS R WHERE R.country_id=".$country_id."";

            //}

            $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
            $intTotalRecordCount = $strTotalRecordCount * 1;

            $intTotalFilteredCount = $intTotalRecordCount;

            if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
                $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
            }

            $StartIndex = $PageRequestData['start'];
            $PageLength = $PageRequestData['length'];
            if ($PageLength < 0) $PageLength = 'all';

            if (!$intTotalRecordCount) {
                return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
            } else {
                $SearchCondition = "";
                $sSearchVal = $_POST['search']['value'];
                if (isset($sSearchVal) && $sSearchVal != '') {
                    $SearchCondition = " AND (";
                    for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                        $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                    }

                    $SearchCondition = substr_replace($SearchCondition, "", -3);
                    $SearchCondition .= ')';
                }


                //Change query here for filtered rows
               /* if($user_group_id == 20)
                {
                    $strQuery = "SELECT     COUNT(SM.state_manager_id)::BIGINT AS total_filtered_count
                             FROM       users.vw_state_manager AS SM
                             WHERE      TRUE AND SM.regional_manager_id=".$parent_id."";
                }*/
               /* else
                {*/
                     $strQuery = "SELECT     COUNT(R.region_id)::BIGINT AS total_filtered_count
                             FROM       master.vw_region AS R
                             WHERE      TRUE AND R.country_id=".$country_id."";
               // }

                $strQuery .= $SearchCondition;

                $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

                //Main Query here for fetching details
               /* if($user_group_id == 20)
                {
                     $strQuery = "SELECT SM.state_manager_id,
                                        SM.state_manager_name,
                                        SM.state_manager_email,
                                        SM.state_manager_phone,
                                        SM.district_coordinator_count,
                                        SM.active_status,
                                        SM.state_name_list,
                                        SM.regional_manager_name
                             FROM       users.vw_state_manager AS SM
                             WHERE      TRUE AND SM.regional_manager_id=".$parent_id."";
                }
                else if($user_group_id == 1)
                {*/
                     $strQuery = "SELECT R.region_id,
                                         R.state_count,
                                         R.regional_manager_name,
                                         R.region_name,
                                         R.active_status
                             FROM        master.vw_region AS R
                             WHERE      TRUE AND R.country_id = ".$country_id."";
                //}

                $strQuery .= $SearchCondition;
                $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

                $QueryData = $this->db->query($strQuery);

                $SerialNumber = $StartIndex;
                $intActiveStatus = 1;
                foreach ($QueryData->result() as $QueryRow) {
                    $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                    $ResponseRow = array();
                    $SerialNumber++;
                    $ResponseRow[] = $SerialNumber;
                    $ResponseRow[] = $QueryRow->region_name;
                    $ResponseRow[] = $QueryRow->regional_manager_name;
                    $ResponseRow[] = ($QueryRow->state_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View State List" onclick="ViewStateList(' . "'" . $QueryRow->region_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->state_count . '</a></center>' : '<center><p>' . $QueryRow->state_count . '<p></center>';;

                    $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->region_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;

                    $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Region Details" href="javascript:void(0);" onclick="EditRegion(' . "'" . $QueryRow->region_id . "'" . ')"><i class="icon-edit"></i></a>';;
                    $Data[] = $ResponseRow;
                }

                $ResponseData = array(
                    "draw" => intval($PageRequestData['draw']),
                    "recordsTotal" => intval($intTotalRecordCount),
                    "recordsFiltered" => intval($intTotalFilteredCount),
                    "data" => $Data
                );

                return $ResponseData;
            }
    }


    function do_get_states_for_region($region_id)
    {
        $query=$this->db->query('select * from master.state where region_id = '.$region_id.' and (active_status = 0 or active_status = 1)')->result_array();
        return $query;
    }

    function do_change_region_status($RequestData = array())
    {
                    $ResponseData = array();
                    if (intval($RequestData['id']) > 0) {
                        $strStatus = 1;
                        if ($RequestData['active_status']==1) $strStatus = 0;
                        else if($RequestData['active_status']==0) $strStatus = 1;
                        $strQuery = "UPDATE master.regions";
                        $strQuery .= " SET active_status=" . $strStatus;
                        $strQuery .= " WHERE id=" . $RequestData['id'];
                        $this->db->query($strQuery);
                        if ($this->db->affected_rows()) {
                            return true;
                        }
                    }
                    if ($strStatus == 0) {
                                $ResponseData["message"] = "Region Has Been Deactivated!";
                            } else {
                                $ResponseData["message"] = "Region Has Been Activated!";
                            }
                    return $ResponseData;
    }

    function do_change_state_status($RequestData = array())
    {
                    $ResponseData = array();
                    if (intval($RequestData['id']) > 0) {
                        $strStatus = 1;
                        if ($RequestData['active_status']==1) $strStatus = 0;
                        else if($RequestData['active_status']==0) $strStatus = 1;
                        $strQuery = "UPDATE master.state";
                        $strQuery .= " SET active_status=" . $strStatus;
                        $strQuery .= " WHERE id=" . $RequestData['id'];
                        $this->db->query($strQuery);
                        if ($this->db->affected_rows()) {
                            return true;
                        }
                    }
                    if ($strStatus == 0) {
                                $ResponseData["message"] = "State Has Been Deactivated!";
                            } else {
                                $ResponseData["message"] = "State Has Been Activated!";
                            }
                    return $ResponseData;
    }


    function do_get_state_list($PageRequestData = array(),$country_id=0)
    {
          $user = $this->pramaan->_check_module_task_auth(true);
            $strOrderBy = "";
            $SearchCondition = "";
            $Data = array();

            //Searching columns
            $arrColumnsToBeSearched = array("S.state_name","S.region_name","S.state_manager_name");

            //Sorting columns
            $arrSortByColumns = array(
                0 => null,
                1 => 'S.state_name',
                2 => 'S.region_name',
                3 => 'S.state_manager_name',
                4 => null,
                5 => null

            );

            //Change query here for total record
    /*        if($user_group_id == 20)
            {
                $strQuery = "SELECT COUNT(*)::BIGINT AS total_record_count
                         FROM   users.vw_state_manager AS SM WHERE SM.regional_manager_id=".$parent_id."";

            }
            else
            {*/
                $strQuery = "SELECT COUNT(S.state_id)::BIGINT AS total_record_count
                         FROM   master.vw_states AS S WHERE S.country_id=".$country_id."";

            //}

            $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
            $intTotalRecordCount = $strTotalRecordCount * 1;

            $intTotalFilteredCount = $intTotalRecordCount;

            if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
                $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
            }

            $StartIndex = $PageRequestData['start'];
            $PageLength = $PageRequestData['length'];
            if ($PageLength < 0) $PageLength = 'all';

            if (!$intTotalRecordCount) {
                return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
            } else {
                $SearchCondition = "";
                $sSearchVal = $_POST['search']['value'];
                if (isset($sSearchVal) && $sSearchVal != '') {
                    $SearchCondition = " AND (";
                    for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                        $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                    }

                    $SearchCondition = substr_replace($SearchCondition, "", -3);
                    $SearchCondition .= ')';
                }


                //Change query here for filtered rows
               /* if($user_group_id == 20)
                {
                    $strQuery = "SELECT     COUNT(SM.state_manager_id)::BIGINT AS total_filtered_count
                             FROM       users.vw_state_manager AS SM
                             WHERE      TRUE AND SM.regional_manager_id=".$parent_id."";
                }*/
               /* else
                {*/
                     $strQuery = "SELECT  COUNT(S.state_id)::BIGINT AS total_filtered_count
                                    FROM  master.vw_states AS S
                                    WHERE  TRUE AND S.country_id=".$country_id."";
               // }

                $strQuery .= $SearchCondition;

                $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

                //Main Query here for fetching details
               /* if($user_group_id == 20)
                {
                     $strQuery = "SELECT SM.state_manager_id,
                                        SM.state_manager_name,
                                        SM.state_manager_email,
                                        SM.state_manager_phone,
                                        SM.district_coordinator_count,
                                        SM.active_status,
                                        SM.state_name_list,
                                        SM.regional_manager_name
                             FROM       users.vw_state_manager AS SM
                             WHERE      TRUE AND SM.regional_manager_id=".$parent_id."";
                }
                else if($user_group_id == 1)
                {*/
                     $strQuery = "SELECT S.state_id,
                                         S.district_count,
                                         S.state_manager_name,
                                         S.state_name,
                                         S.active_status,
                                         S.region_name
                                    FROM master.vw_states AS S
                                    WHERE TRUE AND S.country_id = ".$country_id."";
                //}

                $strQuery .= $SearchCondition;
                $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

                $QueryData = $this->db->query($strQuery);

                $SerialNumber = $StartIndex;
                $intActiveStatus = 1;
                foreach ($QueryData->result() as $QueryRow) {
                    $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                    $ResponseRow = array();
                    $SerialNumber++;
                    $ResponseRow[] = $SerialNumber;
                    $ResponseRow[] = $QueryRow->state_name;
                    $ResponseRow[] = $QueryRow->region_name;

                    if($QueryRow->state_manager_name)
                        $ResponseRow[] = $QueryRow->state_manager_name;
                    else
                        $ResponseRow[] = 'Not Assigned';

                    $ResponseRow[] = ($QueryRow->district_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View District List" onclick="ViewDistrictList(' . "'" . $QueryRow->state_id . "'" . ')" style="font-weight:bold;">' . $QueryRow->district_count . '</a></center>' : '<center><p>' . $QueryRow->district_count . '<p></center>';;

                    $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->state_id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;

                    $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit State Details" href="javascript:void(0);" onclick="EditState(' . "'" . $QueryRow->state_id . "'" . ')"><i class="icon-android-create"></i></a>';;
                    $Data[] = $ResponseRow;
                }

                $ResponseData = array(
                    "draw" => intval($PageRequestData['draw']),
                    "recordsTotal" => intval($intTotalRecordCount),
                    "recordsFiltered" => intval($intTotalFilteredCount),
                    "data" => $Data
                );

                return $ResponseData;
            }
    }


    function do_get_districts_for_state($state_id)
    {
        $query=$this->db->query('select * from master.district where state_id = '.$state_id.' and (active_status = 0 or active_status = 1)')->result_array();
        return $query;
    }

    function do_change_employer_status($RequestData = array())
    {
        $ResponseData = array();
        if (intval($RequestData['employer_id']) > 0) {
            $strStatus = 1;
            if ($RequestData['active_status']) $strStatus = 0;
            $strQuery = "UPDATE users.employers";
            $strQuery .= " SET active_status = active_status # 1 , ";
            $strQuery .= " modified_by = ".$RequestData['modified_by']. ", ";
            $strQuery .= " modified_on = NOW()";
            $strQuery .= " WHERE user_id=" . $RequestData['employer_id'];

            $this->db->query($strQuery);
            if ($this->db->affected_rows()) {

                $strQuery = "UPDATE users.accounts";
                $strQuery .= " SET is_active = NOT is_active , ";
                 $strQuery .= " modified_on = NOW()";
                $strQuery .= " WHERE id=" . $RequestData['employer_id'];
                $this->db->query($strQuery);

                if ($strStatus == 0) {
                    $ResponseData["message"] = "Employer has been Deactivated!";
                } else {
                    $ResponseData["message"] = "Employer has been Activated!";
                }
            }
        }

        return $ResponseData;
    }

    public function do_get_bd_coordinator_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
            $user = $this->pramaan->_check_module_task_auth(true);
            $strOrderBy = "";
            $SearchCondition = "";
            $Data = array();

            //Searching columns
            $arrColumnsToBeSearched = array("bc.name", "bc.email", "bc.phone");

            //Sorting columns
            $arrSortByColumns = array(
                0 => null,
                1 => 'bc.name',
                2 => 'bc.email',
                3 => 'bc.phone',
                4 => null,
                5 => null,
                6 => null

            );

            //Change query here for total record
            if($user_group_id == 11)
            {
                $strQuery = "SELECT COUNT(bc.id)::BIGINT AS total_record_count
                         FROM   users.vw_bd_coordinator AS bc WHERE bc.bd_rm_id=".$parent_id."";

            }
            else
            {
                $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_record_count
                         FROM   users.vw_bd_manager AS bm";

            }

            $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
            $intTotalRecordCount = $strTotalRecordCount * 1;

            $intTotalFilteredCount = $intTotalRecordCount;

            if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
                $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
            }

            $StartIndex = $PageRequestData['start'];
            $PageLength = $PageRequestData['length'];
            if ($PageLength < 0) $PageLength = 'all';

            if (!$intTotalRecordCount) {
                return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
            } else {
                $SearchCondition = "";
                $sSearchVal = $_POST['search']['value'];
                if (isset($sSearchVal) && $sSearchVal != '') {
                    $SearchCondition = " AND (";
                    for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                        $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                    }

                    $SearchCondition = substr_replace($SearchCondition, "", -3);
                    $SearchCondition .= ')';
                }


                //Change query here for filtered rows
                if($user_group_id == 11)
                {
                    $strQuery = "SELECT     COUNT(bc.id)::BIGINT AS total_filtered_count
                             FROM       users.vw_bd_coordinator AS bc
                             WHERE      TRUE AND bc.bd_rm_id=".$parent_id."";
                }
                else
                {
                     $strQuery = "SELECT     COUNT(bc.id)::BIGINT AS total_filtered_count
                             FROM       users.vw_bd_coordinator AS bc
                             WHERE      TRUE ";
                }

                $strQuery .= $SearchCondition;

                $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

                //Main Query here for fetching details
                if($user_group_id == 11)
                {
                     $strQuery = "SELECT bc.id,
                                        bc.name,
                                        bc.email,
                                        bc.phone,
                                        bc.active_status,
                                        bc.district_name_list,
                                        bc.bd_rm_name
                             FROM       users.vw_bd_coordinator AS bc
                             WHERE      TRUE AND bc.bd_rm_id=".$parent_id."";
                }
                else if($user_group_id == 1)
                {
                     $strQuery = "SELECT bc.id,
                                        bc.name,
                                        bc.email,
                                        bc.phone,
                                        bc.active_status,
                                        bc.district_name_list,
                                        bc.bd_rm_name
                             FROM       users.vw_bd_coordinator AS bc
                             WHERE      TRUE";
                }

                $strQuery .= $SearchCondition;
                $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

                $QueryData = $this->db->query($strQuery);

                $SerialNumber = $StartIndex;
                $intActiveStatus = 1;
                foreach ($QueryData->result() as $QueryRow) {
                    $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                    $ResponseRow = array();
                    $SerialNumber++;
                    $ResponseRow[] = $SerialNumber;
                    $ResponseRow[] = $QueryRow->name;
                    $ResponseRow[] = $QueryRow->email;
                    $ResponseRow[] = $QueryRow->phone;
                    $ResponseRow[] = $QueryRow->district_name_list;
                      $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;

                    $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit BD Coordinator Details" href="javascript:void(0);" onclick="EditBdCoordinator(' . "'" . $QueryRow->id . "'" . ')"><i class="icon-android-create"></i></a>';;


                    $Data[] = $ResponseRow;
                }

                $ResponseData = array(
                    "draw" => intval($PageRequestData['draw']),
                    "recordsTotal" => intval($intTotalRecordCount),
                    "recordsFiltered" => intval($intTotalFilteredCount),
                    "data" => $Data
                );

                return $ResponseData;
            }

    }


    public function do_get_bd_executive_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
                $user = $this->pramaan->_check_module_task_auth(true);
                $strOrderBy = "";
                $SearchCondition = "";
                $Data = array();

                //Searching columns
                $arrColumnsToBeSearched = array("be.name", "be.email", "be.phone");

                //Sorting columns
                $arrSortByColumns = array(
                    0 => null,
                    1 => 'be.name',
                    2 => 'be.email',
                    3 => 'be.phone',
                    4 => null,
                    5 => null,
                    6 => null

                );

                //Change query here for total record
                if($user_group_id == 8)
                {
                    $strQuery = "SELECT COUNT(be.id)::BIGINT AS total_record_count
                             FROM   users.vw_bd_executive AS be WHERE be.bdc_id=".$parent_id."";

                }
                else
                {
                    $strQuery = "SELECT COUNT(be.id)::BIGINT AS total_record_count
                             FROM   users.vw_bd_executive AS be";

                }

                $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
                $intTotalRecordCount = $strTotalRecordCount * 1;

                $intTotalFilteredCount = $intTotalRecordCount;

                if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
                    $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
                }

                $StartIndex = $PageRequestData['start'];
                $PageLength = $PageRequestData['length'];
                if ($PageLength < 0) $PageLength = 'all';

                if (!$intTotalRecordCount) {
                    return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
                } else {
                    $SearchCondition = "";
                    $sSearchVal = $_POST['search']['value'];
                    if (isset($sSearchVal) && $sSearchVal != '') {
                        $SearchCondition = " AND (";
                        for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                            $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                        }

                        $SearchCondition = substr_replace($SearchCondition, "", -3);
                        $SearchCondition .= ')';
                    }


                    //Change query here for filtered rows
                    if($user_group_id == 8)
                    {
                        $strQuery = "SELECT     COUNT(be.id)::BIGINT AS total_filtered_count
                                 FROM       users.vw_bd_executive AS be
                                 WHERE      TRUE AND be.bdc_id=".$parent_id."";
                    }
                    else
                    {
                         $strQuery = "SELECT     COUNT(be.id)::BIGINT AS total_filtered_count
                                 FROM       users.vw_bd_executive AS be
                                 WHERE      TRUE ";
                    }

                    $strQuery .= $SearchCondition;

                    $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

                    //Main Query here for fetching details
                    if($user_group_id == 8)
                    {
                         $strQuery = "SELECT be.id,
                                            be.name,
                                            be.employer_count,
                                            be.active_status,
                                            be.email,
                                            be.phone
                                 FROM       users.vw_bd_executive AS be
                                 WHERE      TRUE AND be.bdc_id=".$parent_id."";
                    }
                    else if($user_group_id == 1)
                    {
                         $strQuery = "SELECT be.id,
                                            be.name,
                                            be.employer_count,
                                            be.active_status,
                                            be.email,
                                            be.phone
                                 FROM       users.vw_bd_executive AS be
                                 WHERE      TRUE";
                    }

                    $strQuery .= $SearchCondition;
                    $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

                    $QueryData = $this->db->query($strQuery);

                    $SerialNumber = $StartIndex;
                    $intActiveStatus = 1;
                    foreach ($QueryData->result() as $QueryRow) {
                        $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                        $ResponseRow = array();
                        $SerialNumber++;
                        $ResponseRow[] = $SerialNumber;
                        $ResponseRow[] = $QueryRow->name;
                        $ResponseRow[] = $QueryRow->email;
                        $ResponseRow[] = $QueryRow->phone;
                         $ResponseRow[] = ($QueryRow->employer_count > 0) ? '<center><a class="btn btn-sm btn-primary" title="View Employer List" onclick="ViewEmployerList(' . "'" . $QueryRow->id . "'" . ')" style="font-weight:bold;">' . $QueryRow->employer_count . '</a></center>' : '<center><p>' . $QueryRow->employer_count . '<p></center>';;
                          $ResponseRow[] = '<a class="' . ($QueryRow->active_status ? "btn btn-sm btn-success" : "btn btn-sm btn-danger") . '" title="Toggle Active Status" onclick="ToggleActiveStatus(' . "'" . $QueryRow->id . "'" . ',' . $intActiveStatus . ')" style="width:80%;">' . ($QueryRow->active_status ? "Active" : "Inactive") . '</a>';;

                        $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit BD Coordinator Details" href="javascript:void(0);" onclick="EditBdExecutive(' . "'" . $QueryRow->id . "'" . ')"><i class="icon-edit"></i></a>';;


                        $Data[] = $ResponseRow;
                    }

                    $ResponseData = array(
                        "draw" => intval($PageRequestData['draw']),
                        "recordsTotal" => intval($intTotalRecordCount),
                        "recordsFiltered" => intval($intTotalFilteredCount),
                        "data" => $Data
                    );

                    return $ResponseData;
                }

    }

    //Requirement
    public function get_show_bd_admin_list($PageRequestData = array(),$parent_id=0,$user_group_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("bm.name", "bm.email", "bm.phone");

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'bm.name',
            2 => 'bm.email',
            3 => 'bm.phone',

        );

        //Change query here for total record

        $strQuery = "SELECT COUNT(bm.id)::BIGINT AS total_record_count
                             FROM   users.vw_bd_admin AS bm WHERE bm.bh_head_id=".$parent_id."";



        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows

            $strQuery = "SELECT     COUNT(bm.id)::BIGINT AS total_filtered_count
                                 FROM       users.vw_bd_admin AS bm
                                 WHERE      TRUE AND bm.bh_head_id=".$parent_id."";


            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            //Main Query here for fetching details

            $strQuery = "SELECT bm.id,
                                bm.name,
                                bm.email,
                                bm.phone,
                                bm.active_status,
                                bm.bd_head_name
                        FROM    users.vw_bd_admin AS bm
                        WHERE   bm.bh_head_id=".$parent_id."";

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            foreach ($QueryData->result() as $QueryRow) {
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->name;
                $ResponseRow[] = $QueryRow->email;
                $ResponseRow[] = $QueryRow->phone;
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }
    }

    function get_all_assigned_employers_list_new($requestData = array(), $user_group_id = 0, $user_id = 0)
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


        $user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];

        $strUserRoleCondition = '';

        switch ($user_group_id) {

            case 19://RS Executive
                $strUserRoleCondition = '  VW.rs_exec_id =' . $user_id . ' ';
                break;

            case 17://RS Coordinator
                $strUserRoleCondition .= ' VW.rs_coordinator_id =' . $user_id . ' ';
                break;

            case 16://RS Vertical Manager
                $strUserRoleCondition .= ' VW.rs_vertical_manager_id =' . $user_id . ' ';
                break;

            case 24://RS Sector Manager
                $strUserRoleCondition .= ' VW.rs_sector_manager_id =' . $user_id . ' ';
                break;

            case 14://RS ADMIN
                $strUserRoleCondition .= ' VW.rs_head_id = (Select rs_head_id From users.rs_admin Where user_id =' . $user_id . ') ';
                break;

            case 15://RS HEAD
                $strUserRoleCondition .= ' VW.rs_head_id =' . $user_id . ' ';
                break;

            case 1:// Administrator
                $strUserRoleCondition .= ' 1=1 ';
                break;

            default:
                $strUserRoleCondition .= ' 1!=1 ';
                break;

        }


        $sWhere = "WHERE 1=1 AND";
        $sSearchVal = $_POST['search']['value'];
        if (isset($sSearchVal) && $sSearchVal != '') {
            $sWhere .= " (";
            for ($i = 0; $i < count($column_search); $i++) {
                $sWhere .= $column_search[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
            }
            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ') AND ';

        }


        $column_search = array("VW.employer_name"); //set column field database for datatable searchable just firstname , lastname , address are searchable
        $total_records = $this->db->query("SELECT Count(*) AS total_recs FROM
                                                   users.vw_assigned_emp_data AS VW
                                                    $sWhere
                                                    $strUserRoleCondition")->row()->total_recs;
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




            $totalFiltered = $this->db->query("SELECT Count(*) AS total_filtered FROM
                                                   users.vw_assigned_emp_data AS VW
                                                    $sWhere
                                                    $strUserRoleCondition")->row()->total_filtered;



            $strSubRoleCondition = '';

            switch ($user_group_id) {

                case 19://RS Executive
                    $strSubRoleCondition = '  AND jd.rec_sup_exec_id = VW.rs_exec_id ';
                    break;

                case 17://RS Coordinator
                    $strSubRoleCondition .= '  AND jd.rec_sup_exec_id = ANY(SELECT rs_exec_id  FROM users.vw_assigned_emp_data AS VW WHERE VW.rs_coordinator_id = '.$user_id. ') ' ;
                    break;

                case 16://RS Vertical Manager
                    $strSubRoleCondition .= '   AND jd.rec_sup_exec_id = ANY(SELECT rs_exec_id FROM users.vw_assigned_emp_data AS VW WHERE VW.rs_vertical_manager_id = '.$user_id. ') ' ;
                    break;

                case 24://RS Sector Manager
                    $strSubRoleCondition .= '   AND jd.rec_sup_exec_id = ANY(SELECT VW.rs_exec_id FROM users.vw_assigned_emp_data AS VW WHERE VW.rs_sector_manager_id = '.$user_id. ') ' ;
                    break;

                case 14://RS ADMIN
                    $strSubRoleCondition .= '   AND jd.rec_sup_exec_id = ANY(SELECT VW.rs_exec_id FROM users.vw_assigned_emp_data AS VW WHERE VW.rs_head_id= (Select rs_head_id From users.rs_admin Where user_id =' . $user_id . ') ) ' ;
                    break;

                case 15://RS HEAD
                    $strSubRoleCondition .= '    AND jd.rec_sup_exec_id = ANY(SELECT VW.rs_exec_id FROM users.vw_assigned_emp_data AS VW WHERE VW.rs_head_id = '.$user_id. ') ' ;
                    break;

                case 1:// Administrator
                    $strSubRoleCondition .= ' AND 1=1  ';
                    break;

                default:
                    $strSubRoleCondition .= ' AND 1!=1 ';
                    break;

            }


            $employers_recs=$this->db->query("SELECT DISTINCT ON(VW.employer_id) employer_id, VW.employer_id AS emp_id,VW.employer_name as employer_name,
                                                        VW.rs_exec_id,
                                            (SELECT COUNT(distinct j.id) FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id $strSubRoleCondition) AS total_jobs,
                                            (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT j.id FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id  $strSubRoleCondition group by jd.job_id,j.id) AND status_id = 1) AS applied_count,
                                            (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT j.id FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id  $strSubRoleCondition group by jd.job_id,j.id) AND status_id = 2) AS screened_count,
                                            (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT j.id FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id  $strSubRoleCondition group by jd.job_id,j.id) AND status_id = 3) AS scheduled_count,
                                            (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT j.id FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id  $strSubRoleCondition group by jd.job_id,j.id) AND status_id = 4) AS shortlisted_count,
                                            (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT j.id FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id  $strSubRoleCondition group by jd.job_id,j.id) AND status_id = 5) AS selected_count,
                                            (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT j.id FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id  $strSubRoleCondition group by jd.job_id,j.id) AND status_id = 6) AS offered_count,
                                            (SELECT COUNT(candidate_id) FROM job_process.candidate_jobs WHERE job_id in(SELECT j.id FROM job_process.jobs j left join job_process.job_detail jd on jd.job_id=j.id WHERE j.employer_id=VW.employer_id  $strSubRoleCondition group by jd.job_id,j.id) AND status_id = 7) AS joined_count
                                            from  users.vw_assigned_emp_data AS VW
                                            $sWhere
                                            $strUserRoleCondition
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
                $row[] = ($employers->total_jobs)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_assigned_jobs('."'".$employers->emp_id."'".','."'".$user_id."'".')">'.$employers->total_jobs.'</b></a>':$employers->total_jobs;
                $row[] = ($employers->applied_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->emp_id."'".','."'".$user_id."'".',1)">'.$employers->applied_count.'</b></a>':$employers->applied_count;
                $row[] = ($employers->screened_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->emp_id."'".','."'".$user_id."'".',2)">'.$employers->screened_count.'</b></a>':$employers->screened_count;
                $row[] = ($employers->scheduled_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->emp_id."'".','."'".$user_id."'".',3)">'.$employers->scheduled_count.'</b></a>':$employers->scheduled_count;
                $row[] = ($employers->shortlisted_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->emp_id."'".','."'".$user_id."'".',4)">'.$employers->shortlisted_count.'</b></a>':$employers->shortlisted_count;
                $row[] = ($employers->selected_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->emp_id."'".','."'".$user_id."'".',5)">'.$employers->selected_count.'</b></a>':$employers->selected_count;
                $row[] = ($employers->offered_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->emp_id."'".','."'".$user_id."'".',6)">'.$employers->offered_count.'</b></a>':$employers->offered_count;
                $row[] = ($employers->joined_count)?'<b><a href="javascript:void(0)" title="Tracked candidate List" onclick="tracked_candidates('."'".$employers->emp_id."'".','."'".$user_id."'".',7)">'.$employers->joined_count.'</b></a>':$employers->joined_count;
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


    function get_assigned_jobs_by_employer($employer_id,$user_id)
    {

        $user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];

        $strRoleCond = "";

        switch ($user_group_id) {

            case 19://RS Executive
                $strRoleCond.= " and jd.rec_sup_exec_id = ".$user_id." ";
                break;

            case 17://RS Coordinator
                $strRoleCond.= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_coordinator_id = ".$user_id.") ";
                break;

            case 24://RS Sector Manager
                $strRoleCond.= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_sector_manager_id = ".$user_id.") ";
                break;

            case 16://RS Vertical Manager
                $strRoleCond.= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_vertical_manager_id = ".$user_id.") ";
                break;

            case 14://RS ADMIN
                $strRoleCond .= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_head_id  = (Select rs_head_id From users.rs_admin Where user_id =" . $user_id . ")) ";
                break;

            case 15://RS HEAD
                $strRoleCond .= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_head_id  = ". $user_id . ") ";
                break;

            case 1:// Administrator
                $strRoleCond .= ' and 1=1  ';
                break;

            default:
                $strRoleCond.= " and 1!=1 ";
                break;

        }


        $employer_det_rec=$this->db->query("SELECT name as employer_name,phone from users.employers
                                             where user_id=?",$employer_id);

        $job_det_rec=$this->db->query("SELECT COALESCE(NULLIF(pj.job_desc,'') , '-NA-' ) as job_desc,COALESCE(NULLIF(qp.name,'') , '-NA-' ) as qualification_pack_id,pj.created_on
                                        FROM job_process.jobs pj
                                        LEFT JOIN job_process.job_detail jd on jd.job_id=pj.id
                                        LEFT JOIN master.qualification_pack qp on qp.id=pj.qualification_pack_id
                                        WHERE employer_id=? $strRoleCond
                                        GROUP BY jd.job_id,pj.job_desc,qp.name,pj.created_on",array($employer_id));

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


    function get_candidates_byAssignedEmployerjob($employer_id,$rs_user_id,$job_status)
    {

        $employer_det_rec=$this->db->query("SELECT name as employer_name,phone from users.employers
                                             where user_id=?",$employer_id);

         $user_group_id = $this->session->userdata['usr_authdet']['user_group_id'];

         $strRoleCond = "";

            switch ($user_group_id) {

            case 19://RS Executive
                $strRoleCond.= " and jd.rec_sup_exec_id = ".$rs_user_id." ";
               break;

            case 17://RS Coordinator
                 $strRoleCond.= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_coordinator_id = ".$rs_user_id.") ";
                 break;

            case 24://RS Sector Manager
                  $strRoleCond.= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_sector_manager_id = ".$rs_user_id.") ";
                  break;

            case 16://RS Vertical Manager
                  $strRoleCond.= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_vertical_manager_id = ".$rs_user_id.") ";
                  break;

            case 14://RS ADMIN
                $strRoleCond .= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_head_id  = (Select rs_head_id From users.rs_admin Where user_id =" . $rs_user_id . ")) ";
                break;

            case 15://RS HEAD
                $strRoleCond .= " and jd.rec_sup_exec_id = ANY(SELECT rs_executive_id FROM users.vw_rs_executive WHERE rs_head_id  = ". $rs_user_id . ") ";
                break;

            case 1:// Administrator
                 $strRoleCond .= ' and 1=1  ';
                 break;

            default:
               $strRoleCond.= " and 1!=1 ";
               break;

            }

        $candidate_det_rec=$this->db->query("SELECT cj.id as job_applied_id, cj.candidate_id,  COALESCE(NULLIF(pj.job_desc,'') , '-NA-' ) as job_desc, COALESCE(NULLIF(qp.name ,'') , '-NA-' ) as qualification_pack_id,COALESCE(NULLIF(uc.name ,'') , '-NA-' ) as candidate_name,COALESCE(NULLIF(uc.mobile ,'') , '-NA-' ) as mobile,COALESCE(NULLIF(uc.gender_code,'') , '-NA-' ) as gender_code
                                        FROM job_process.candidate_jobs cj
                                        left join users.candidates uc on uc.id=cj.candidate_id
                                        left join master.education me on me.id=uc.education_id
                                        left join master.list ml on ml.value::integer= uc.expected_salary_id and code='L0012'
                                        left join job_process.jobs pj on pj.id=cj.job_id
                                        left join job_process.job_detail jd on jd.job_id=pj.id
                                        left join master.qualification_pack qp on qp.id=pj.qualification_pack_id
                                        where cj.job_id in(select id from job_process.jobs where employer_id=?
                                        $strRoleCond)
                                        and cj.status_id=?
                                        group by jd.job_id,cj.id,pj.job_desc,qp.name,uc.name,uc.mobile,uc.gender_code",array($employer_id,$job_status));

        if($employer_det_rec->num_rows())
        {
            $output['status']=true;
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



    function get_address_book_contact_list($PageRequestData = array(),$user_id=0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("customer_name", "hr_name" ,"hr_phone","hr_email" );

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'customer_name',
            2 => 'hr_name',
            3 => 'designation',
            4 => 'hr_phone',
            5 => 'hr_email',
            6 => 'industry_name',
            7 => null
        );

        //Change query here for total record
        $strQuery = "SELECT COUNT(s.customer_id)::BIGINT AS total_record_count
                     FROM   users.vw_address_book_contact_list AS s WHERE TRUE ";
        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows
            $strQuery = "SELECT     COUNT(s.customer_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_address_book_contact_list AS s
                         WHERE      TRUE ";

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "select * from users.vw_address_book_contact_list AS s WHERE TRUE ";
            //Main Query here for fetching details

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            //$intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
               // $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = '<a title="Show Details" href="javascript:void(0);" onclick="ShowDetails(' . "'" . $QueryRow->customer_id . "'" . ')" style=" font-weight: 600;">'  . $QueryRow->customer_name .  '</a>';
                //$ResponseRow[] = $QueryRow->customer_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->hr_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->designation ?? 'N/A';
                $ResponseRow[] = $QueryRow->hr_phone ?? 'N/A';
                $ResponseRow[] = $QueryRow->hr_email ?? 'N/A';
                $ResponseRow[] = $QueryRow->industry_name ?? 'N/A';
                $ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Contact" href="javascript:void(0);" onclick="EditContact(' . "'" . $QueryRow->customer_id . "'" . ')"><i class="icon-android-create"></i></a>';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );


            return $ResponseData;
        }

    }



    function get_job_applicants_list($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("name", "email" ,"phone","job_title","job_description","customer_name" );

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'name',
            2 => 'email',
            3 => 'phone',
            4 => 'job_title',
            5 => 'job_description',
            6 => 'customer_name',
            7 => null
        );

        //Change query here for total record
        $strQuery = "SELECT COUNT(ja.job_id)::BIGINT AS total_record_count
                     FROM   users.vw_job_applicants_list AS ja WHERE TRUE ";
        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] . " ILIKE '%" . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows
            $strQuery = "SELECT     COUNT(ja.job_id)::BIGINT AS total_filtered_count
                         FROM       users.vw_job_applicants_list AS ja
                         WHERE      TRUE ";

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "select * from users.vw_job_applicants_list AS ja WHERE TRUE ";
            //Main Query here for fetching details

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);

            $SerialNumber = $StartIndex;
            //$intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                // $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->name ?? 'N/A';
                $ResponseRow[] = $QueryRow->email ?? 'N/A';
                $ResponseRow[] = $QueryRow->phone ?? 'N/A';
                $ResponseRow[] = isset($QueryRow->created_at) ? date_format( date_create($QueryRow->created_at), 'd F Y') : 'N/A';
                $ResponseRow[] = $QueryRow->job_title ?? 'N/A';
                $ResponseRow[] = $QueryRow->job_description ?? 'N/A';
                $ResponseRow[] = $QueryRow->customer_name ?? 'N/A';
                /*$ResponseRow[] = '<a class="btn btn-sm btn-danger" title="Edit Contact" href="javascript:void(0);" onclick="EditContact(' . "'" . $QueryRow->job_id . "'" . ')"><i class="icon-android-create"></i></a>';*/
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }

    }


    function get_batches_list($PageRequestData = array())
    {
        $user = $this->pramaan->_check_module_task_auth(true);

        $strOrderBy = "";
        $SearchCondition = "";
        $Data = array();

        //Searching columns
        $arrColumnsToBeSearched = array("batch_code","batch_type","batch_size","customer_name", "center_name" ,"course_name", "course_code","buisness_unit","trainer_email","qp_name","batch_start_date","batch_end_date" );

        //Sorting columns
        $arrSortByColumns = array(
            0 => null,
            1 => 'batch_code',
            2 => 'batch_type',
            3 => 'batch_size',
            4 => 'customer_name',
            5 => 'center_name',
            6 => 'course_name',
            7 => 'course_code',
            8 => 'buisness_unit',
            9 => 'trainer_email',
            10 => 'qp_name',
            11 => 'batch_start_date',
            12 => 'batch_end_date'
        );

        //Change query here for total record
        $strQuery = "SELECT COUNT(b.id)::BIGINT AS total_record_count
                     FROM   users.vw_batches_list AS b WHERE TRUE ";
        $strTotalRecordCount = $this->db->query($strQuery)->row()->total_record_count;
        $intTotalRecordCount = $strTotalRecordCount * 1;

        $intTotalFilteredCount = $intTotalRecordCount;

        if ($arrSortByColumns[$PageRequestData['order'][0]['column']] != '') {
            $strOrderBy = " ORDER BY " . $arrSortByColumns[$PageRequestData['order'][0]['column']] . " " . $PageRequestData['order'][0]['dir'] . "  ";
        }

        $StartIndex = $PageRequestData['start'];
        $PageLength = $PageRequestData['length'];
        if ($PageLength < 0) $PageLength = 'all';

        if (!$intTotalRecordCount) {
            return array('sEcho' => '1', "iTotalRecords" => "0", "iTotalDisplayRecords" => "0", 'aaData' => array());
        } else {
            $SearchCondition = "";
            $sSearchVal = $_POST['search']['value'];
            if (isset($sSearchVal) && $sSearchVal != '') {
                $SearchCondition = " AND (";
                for ($i = 0; $i < count($arrColumnsToBeSearched); $i++) {
                    $SearchCondition .= $arrColumnsToBeSearched[$i] .  "::TEXT ILIKE '%"  . $this->db->escape_like_str($sSearchVal) . "%' OR ";
                }

                $SearchCondition = substr_replace($SearchCondition, "", -3);
                $SearchCondition .= ')';
            }


            //Change query here for filtered rows
            $strQuery = "SELECT     COUNT(b.id)::BIGINT AS total_filtered_count
                         FROM       users.vw_batches_list AS b
                         WHERE      TRUE ";

            $strQuery .= $SearchCondition;

            $intTotalFilteredCount = $this->db->query($strQuery)->row()->total_filtered_count;

            $strQuery = "select * from users.vw_batches_list AS b WHERE TRUE ";
            //Main Query here for fetching details

            $strQuery .= $SearchCondition;
            $strQuery .= $strOrderBy . " LIMIT " . $PageLength . " OFFSET " . $StartIndex;

            $QueryData = $this->db->query($strQuery);
                        
            $SerialNumber = $StartIndex;
            //$intActiveStatus = 1;
            foreach ($QueryData->result() as $QueryRow) {
                // $intActiveStatus = ($QueryRow->active_status) ? 1 : 0;
                $ResponseRow = array();
                $SerialNumber++;
                $ResponseRow[] = $SerialNumber;
                $ResponseRow[] = $QueryRow->batch_code ?? 'N/A';
                $ResponseRow[] = $QueryRow->batch_type ?? 'N/A';
                $ResponseRow[] = ($QueryRow->batch_size) ? '<center><b><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="View Batch Candidates" onclick="showBatchWiseCandidates(' . "'" . $QueryRow->id . "'" . ')">' . $QueryRow->batch_size . '</a></b></center>' : '<center>'.$QueryRow->batch_size.'</center>';
               // $ResponseRow[] = '<center><a class="btn btn-sm btn-primary" title="View Batch Candidates" href="javascript:void(0);" onclick="showBatchWiseCandidates(' . "'" . $QueryRow->id . "'" . ')" >' . $QueryRow->batch_size . '</a></center>';
                $ResponseRow[] = $QueryRow->customer_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->center_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->course_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->course_code ?? 'N/A';
                $ResponseRow[] = $QueryRow->buisness_unit ?? 'N/A';
                $ResponseRow[] = $QueryRow->trainer_email ?? 'N/A';
                $ResponseRow[] = $QueryRow->qp_name ?? 'N/A';
                $ResponseRow[] = $QueryRow->batch_start_date ?? 'N/A';
                $ResponseRow[] = $QueryRow->batch_end_date ?? 'N/A';
                $Data[] = $ResponseRow;
            }

            $ResponseData = array(
                "draw" => intval($PageRequestData['draw']),
                "recordsTotal" => intval($intTotalRecordCount),
                "recordsFiltered" => intval($intTotalFilteredCount),
                "data" => $Data
            );

            return $ResponseData;
        }

    }

 public function get_candidates_by_batchwise($id = 0)   //changed to view
 {
        $output = array();
        $output['status'] = false;
        $BatchCode = '';
        $BatchType = '';
        $CustomerName = '';
        $CenterName = '';
        $CourseName = '';
        $CourseCode = '';
        $BusinessUnit = '';
        $TrainerEmail = '';
        $QualificationPack = '';
        $BatchStartDate = '';
        $BatchEndDate = '';        

        $batch_rec = $this->db->where("id",$id)->get("neo.neo_batches")->row();
        if (!empty($batch_rec))
        {   
            $BatchCode = $batch_rec->batch_code;
            $BatchType = $batch_rec->batch_type;
            $CustomerName = $batch_rec->customer_name;
            $CenterName = $batch_rec->center_name;
            $CourseName = $batch_rec->course_name;
            $CourseCode = $batch_rec->course_code;
            $BusinessUnit = $batch_rec->business_unit;
            $TrainerEmail = $batch_rec->trainer_email;
            $QualificationPack = $batch_rec->qp_name;
            $BatchStartDate = $batch_rec->batch_start_date;
            $BatchEndDate = $batch_rec->batch_end_date;   
            
            $output['status'] = true;
            $output['batch_code'] = $BatchCode;
            $output['batch_type'] = $BatchType;
            $output['customer_name'] = $CustomerName;
            $output['center_name'] = $CenterName;
            $output['course_name'] = $CourseName;
            $output['course_code'] = $CourseCode;
            $output['business_unit'] = $BusinessUnit;
            $output['trainer_email'] = $TrainerEmail;
            $output['qp_name'] = $QualificationPack;
            $output['batch_start_date'] = $BatchStartDate;
            $output['batch_end_date'] = $BatchEndDate;
        }
       

        if ($BatchCode != '')
        {
            $candidate_det_rec = $this->db->query("SELECT   	DISTINCT
                                                                c.id,
                                                                c.candidate_name,
                                                                COALESCE(NULLIF(c.mobile,'') , '-NA-' ) as mobile,
                                                                COALESCE(NULLIF(c.email,'') , '-NA-' ) as email
                                                FROM    	neo.candidates c
                                                LEFT JOIN 	neo.candidate_qp_details AS CB ON CB.candidate_id=C.id
                                                LEFT JOIN 	neo.neo_batches AS B ON UPPER(TRIM(B.batch_code))=UPPER(TRIM(CB.batch_code))
                                                WHERE   	B.id=$id
                                                ORDER BY	2");
            
            if ($candidate_det_rec->num_rows())
            {
                $output['candidate_detail'] = $candidate_det_rec->result_array();
            }
        }

        return $output;
    }
    
}

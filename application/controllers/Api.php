<?php

/**
 * API :: Pramaan api  
 * @author by Sangamesh<sangamesh.p@mpramaan.in_Nov-2016>
 * local $api_url = 'http://localhost/pramaan/api/';
 * remote $api_url = 'www.pramaan.in/api/';
**/

/* Rest API status code
	Status Code 		Info
	200					OK
	201					Created/inserted/updated
	304					Not Modified
	400					InvalidInput:One of the request inputs is not valid.
	401					Unauthorized access
	403					AuthenticationFailed
	404					Not Found
	409					ResourceAlreadyExists
	410					MissingContentLengthHeader: Length Required
	500					Internal server Error/DB Error*/

class Api extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		header('Content-Type: text/html; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		$this->load->library('form_validation');
		$this->load->model("Api_model","api");
		$this->load->model("Common_model","common");
		$this->load->model("Partner_model","partner");
	}

	function index()
	{
		$this->common->_output_handle('json', true,array('api_info'=>"Pramaan API's are working fine.."));
	}

  /* Functions to register the policy ih
   * @author Sangamesh.p@pramaan.in_feb_19_2016
   * @param string json
  */

	function get_register_user_list()
	{ 
		$user_list=array(); 
		$user_list=	$res=$this->db->query('select * from m_admin')->result();
		if(!empty($user_list))
		$this->common->_output_handle('json', true,array('admin_users'=>$user_list));
		else
		$this->common->_output_handle('json',false,array('error_code'=>404,'error_msg'=>"Oops! user details are not found"));
	}

	function api_login()
	{
			$this->load->library('form_validation');
			$email_id=$this->input->get('email_id');
			$password=$this->input->get('password');
			$data = array('email_id' => $email_id,'password' =>$password);
			$this->form_validation->set_data($data);

			$this->form_validation->set_rules('email_id', 'email id', 'required|valid_email');
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');

			if($this->form_validation->run() === FALSE)
			{
				$this->common->_output_handle('json',false,array('error_code'=>202,'error_msg'=>validation_errors() ));
			}
			else
			{
				$sql = "SELECT u.id as user_id 
						FROM users.accounts u 
						where TRIM(u.email) = ? and TRIM(u.pwd) = crypt(?, u.pwd) and u.is_active='t'";
				$res = $this->db->query($sql,array(trim($email_id),trim($password)));
				if($res->num_rows())
				{
					echo json_encode($res->row_array());
					//$this->common->_output_handle('json', true,array('user_details'=> $res->row_array()));
				}
				else
				{
					echo json_encode(array('user_id'=>'-1'));
					//$this->common->_output_handle('json',false,array('error_code'=>204,'error_msg'=>"No Data found" ));
				}
			}
	}
/*	function add_users()
	{ 
		
		$this->load->library('form_validation');
		$data=array();
		if(empty($_POST))
		{
			$this->form_validation->set_data($this->input->get());
			$data=$this->input->get();
		}
		else
		{
			$data=$this->input->post();
		}

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('address','Address','required');
		$this->form_validation->set_rules('city','City','required');
		$this->form_validation->set_rules('state','State','required');
		$this->form_validation->set_rules('pin','PIN','required');
		$this->form_validation->set_rules('email', 'Email',   'trim|required|valid_email',array('required' => 'You have not provided %s.')); 
    	$this->form_validation->set_rules('mobile', 'Phone Number', 'required|numeric|min_length[10]');
		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>202,'error_msg'=>validation_errors() ));
		}

		$this->common->_output_handle('json', true,array('input_data'=>$data));
	}*/
	function get_languages()
	{
		$language_rec=$this->db->query("SELECT value as id,name as language_name 
										from master.list
										where code='L0009' and value::integer in(1,8,21,24,36,52,53)
										order by sort_order");   //only specific languages
		
		if($language_rec->num_rows())
		{
			$this->common->_output_handle('json', true,array('language_details'=>$language_rec->result_array()));
		}
		else
		{
			$this->common->_output_handle('json',false,array('error_code'=>204,'error_msg'=>"No Data found" ));
		}
	}

	function get_assessment_languages()
	{
		$language_rec=$this->db->query("SELECT id,name as language_name 
										FROM master.languages;");   
		
		if($language_rec->num_rows())
		{
			$this->common->_output_handle('json', true,array('language_details'=>$language_rec->result_array()));
		}
		else
		{
			$this->common->_output_handle('json',false,array('error_code'=>204,'error_msg'=>"No Data found" ));
		}
	}
	function candidate_registration()
	{
		$this->load->library('form_validation');
		$dob=$_REQUEST['dob'];
		$dob=date('Y-m-d',strtotime($dob));
		$data = array(
						'name' => $_REQUEST['name'],
						'mobile' => $_REQUEST['mobile'],
						'dob' => $dob,
						'state_id' => $_REQUEST['state_id'],
						'district_id' => $_REQUEST['district_id'],
						'experience_id' => $_REQUEST['experience_id'],
						'education_id' => $_REQUEST['education_id'],
						'course_id' => $_REQUEST['course_id'],
						'expected_salary_id' => $_REQUEST['expected_salary_id'],
						'aadhaar_num' => $_REQUEST['aadhaar_num']
					);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name', 'Candidate Name', 'required');
		$this->form_validation->set_rules('mobile','Phone','required');
		$this->form_validation->set_rules('dob', 'Date Of Birth', 'required');
		$this->form_validation->set_rules('aadhaar_num', 'Aaadhar number', 'required');

		/*$this->form_validation->set_rules('state_id', 'State', 'required');
		$this->form_validation->set_rules('district_id', 'District', 'required');
		$this->form_validation->set_rules('experience_id', 'Experience', 'required');
		$this->form_validation->set_rules('education_id', 'Education', 'required');
		$this->form_validation->set_rules('course_id', 'Course', 'required');
		$this->form_validation->set_rules('expected_salary_id', 'Expected Salary', 'required');*/
		$aadhar= $_REQUEST['aadhar_number'];
		$is_aadhaar=$this->db->query("select aadhaar_num from users.candidates where aadhaar_num=?",$aadhar);
		if($is_aadhaar->num_rows())
		{
			$this->common->_output_handle('json',false,array('error_code'=>409,'error_msg'=>'This Aaadhar number already Exists'));
		}
		// set form validation rules
		if ($this->form_validation->run() == FALSE)
		{
			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($_REQUEST as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
	        $this->common->_output_handle('json',false,array('error_code'=>304,'error_msg'=>"Validation errors" ));
		}
		else
		{

			$data['email']=$_REQUEST['email'];
			$data['language_id']='{'.$_REQUEST['language_id'].'}';
			$data['gender_code']=$_REQUEST['gender_code'];
			$data['address']=$_REQUEST['address'];
			$data['pincode']=$_REQUEST['pincode'];
			$data['referer_id']=$_REQUEST['user_id'];

			$is_aadhar=$_REQUEST['is_aadhar'];
			$data['is_aadhar']= $is_aadhar;
			if($is_aadhar=='t')	
			$data['aadhaar_num']= $_REQUEST['aadhar_number'];
			else
			{
			$data['id_type']= $_REQUEST['id_type'];
			$data['id_number']=$_REQUEST['id_number'];
			}

			$relocate_status_code= $_REQUEST['relocate_status_code'];
			$data['relocate_status_code']=$relocate_status_code;
			if($relocate_status_code="Y")
			$data['expected_relocate_salary'] = $_REQUEST['expected_relocate_salary'];
			$data['created_on']=date('Y-m-d');
			$candidate_type_id=$_REQUEST['candidate_type_id'];
			if($candidate_type_id==2)
			{
				$data['trained_course_name']=$_REQUEST['trained_course_name'];
				$data['inst_name']=$_REQUEST['inst_name'];
			}
			if($candidate_type_id==3)
				$data['certification_id']=$_REQUEST['certification_id'];
			$data['candidate_type_id']=$candidate_type_id;

			$image_filename=$_REQUEST['image_filename'];
			//-----image upload from android
				/*$image=$_REQUEST['image'];
				$file_name=CANDIDATE_IMAGES.$image_filename;
				$decodedImage = base64_decode($image);
				file_put_contents($file_name, $decodedImage);*/

			//----- end ------------
			$data['img']=$image_filename;
			$insert = $this->partner->do_add_candidate($data);
			if($insert)
			{
				$candidate_id=$this->db->insert_id();
				$candidate_details=array('candidate_id'=>$candidate_id);
				$this->common->_output_handle('json', true,array('candidate_details'=>$candidate_details));
			}
			else
			{
				$this->common->_output_handle('json',false,array('error_code'=>500,'error_msg'=>'Candidate registration is not successfull'));
			}			
			
		}
	}
	//candidate registration through mobile
	function candidate_signup()
	{
		$candidate_submit_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_submit_data['candidate_registration'];
		$data = array('name' => $candidate_data['name'],
						'mobile' => $candidate_data['mobile'],
						'dob' => $candidate_data['dob'],
						'gender_code' => $candidate_data['gender_code'],
						'aadhaar_num' => $candidate_data['aadhaar_num']
						);

		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('mobile','Mobile','required');
		$this->form_validation->set_rules('dob', 'Date Of Birth', 'required');
		$this->form_validation->set_rules('aadhaar_num', 'Aaadhar number', 'required');
		$this->form_validation->set_rules('gender_code', 'Gender', 'required');

		$data['email']=$candidate_data['email'];
		$data['pincode']=$candidate_data['pincode'];
		$data['total_experience']=$candidate_data['total_experience'];
		$data['expected_salary']=$candidate_data['expected_salary'];
		$data['relocate_status_code']=$candidate_data['relocate_status_code'];
		$is_aadhaar=$this->db->query("select aadhaar_num from users.candidates where aadhaar_num=?",$candidate_data['aadhaar_num']);
		
		$data['education_id']=$candidate_data['qualification_id'];
		$data['course_id']=$candidate_data['course_id'];
		$data['state_id']=$candidate_data['state_id'];
		$data['district_id']=$candidate_data['district_id'];
		$data['center_id']=$candidate_data['center_id'];
		
		// set form validation rules
		if ($this->form_validation->run() == FALSE)
		{
			$errors = array();
    			// Loop through $_POST and get the keys
	        foreach ($data as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
	        $this->common->_output_handle('json',false,array('error_code'=>304,'error_msg'=>$errors));
		}
		else
		{
			$id= $candidate_data['id'];
			if(!$id)
			{
				if($is_aadhaar->num_rows())
				{
					$this->common->_output_handle('json',false,array('error_code'=>409,'error_msg'=>'This Aaadhar number already Exists'));
				}
				else
				{
					$insert = $this->api->do_candidate_signup($data);
					if($insert)
					{
						$user_id=$this->db->insert_id();
						$login_id=$candidate_data['mobile'];
						$candidate_details=array('user_id'=>$user_id,'login_id'=>$login_id);
						$this->common->_output_handle('json', true,array('candidate_details'=>$candidate_details));
					}
					else
					{
						$this->common->_output_handle('json',false,array('error_code'=>500,'error_msg'=>'Candidate registration is not successfull'));
					}
				}			
			}
			else
			{
				
				$img_base64=$candidate_data['img'];
				if($img_base64)
					$date['img']=$this->base64_to_folder($user_id=$id,$img_base64);
				$update = $this->api->do_candidate_signup($data,$id);
				if($update)
				{
					$user_id=$id;
					$login_id=$candidate_data['mobile'];
					$candidate_details=array('user_id'=>$user_id,'login_id'=>$login_id);
					$this->common->_output_handle('json', true,array('candidate_details'=>$candidate_details));
				}
				else
				{
					$this->common->_output_handle('json',false,array('error_code'=>500,'error_msg'=>'Candidate registration is not successfull'));
				}	
			}
		}

	}
	public function base64_to_folder($user_id=0,$data='')
	{
		/*$path = base_url('uploads/banner/events.jpg');
		$type = pathinfo($path, PATHINFO_EXTENSION);
		$data = file_get_contents($path);
		$base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
		$data=$base64;*/
		//$image_parts = explode(";base64,", $data);
		//$image_type_aux = explode("image/", $image_parts[0]);
		//$image_type = $image_type_aux[1];
		$image_type='jpg';
		$image_base64 = base64_decode($data);
		$file_name='candidate_'.$user_id.'.'.$image_type;
		$file_path=CANDIDATE_IMAGES.$file_name;
		file_put_contents($file_path, $image_base64);
		return $file_name;
	}

	/*
	* function to candidate login
	*/
	function login()
	{

		$candidate_submit_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_submit_data['candidate_login'];
		$data = array('user_mobile' => $candidate_data['mobile']);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('user_mobile','User Mobile','required');
		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}
		$user_mobile=$data['user_mobile'];
		$user_detail=$this->common->do_generate_otp($user_mobile);
        if($user_detail)
        {
        	$this->common->_output_handle('json', true,array('candidate_details'=>$user_detail));
        }
        else
        {
            echo json_encode(array('status'=>false,'msg_info'=>'invalid user'));
        }
	}

	/*
	* function for request assessment
	*/
/*	function request_for_assessment()
	{
		$candidate_submit_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_submit_data['candidate_request'];
		$user_id=$candidate_data['user_id'];
		$request_type=$candidate_data['request_type'];
		$data = array('user_id' => $candidate_data['user_id']);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('user_id','User Id','required');
		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}
		if(!$request_type)
		{
			$result_rec=$this->db->query("SELECT assessment_id, candidate_name,question_paper_id,question_paper_title,
											language_name,assessment_date,
											case
												when A.assessment_status_id=0 and A.request_status=0 and A.qualified_status=true then 'Requested'
												when A.assessment_status_id=0 and A.request_status=1 and A.qualified_status=true then 'Approved'
												when A.assessment_status_id=0 and A.request_status=2 and A.qualified_status=true then 'Incomplete'
												when A.assessment_status_id=1 and A.request_status=2 and A.qualified_status=true then 'Inprogress'
												when A.assessment_status_id=2 and A.request_status=2 and A.qualified_status=true then 'Completed'
												else 'No Assessment'
											end as assessment_status
											from assessment.fn_get_candidate_assessment_data($user_id, 1) A");
			if($result_rec->num_rows())
				$this->common->_output_handle('json', true,array('candidate_details'=>$result_rec->row_array()));
			else
				$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'Invalid candidate details!'));	
		}
		else
		{
			$update_result=$this->db->query("UPDATE assessment.assessments
												SET request_status=2 WHERE status_id=0 and request_status=1
												AND candidate_id=?",$user_id);
			if($this->db->affected_rows())
			{
				$assessment_rec=$this->db->query("SELECT A.id as assessment_id, A.assessment_datetime, A.status_id,A.counseling_status,A.qualified_status,A.candidate_id,
													case
														when A.status_id=0 and A.request_status=0 and A.qualified_status=true then 'Requested'
														when A.status_id=0 and A.request_status=1 and A.qualified_status=true then 'Approved'
														when A.status_id=0 and A.request_status=2 and A.qualified_status=true then 'Incomplete'
														when A.status_id=1 and A.request_status=2 and A.qualified_status=true then 'Inprogress'
														when A.status_id=2 and A.request_status=2 and A.qualified_status=true then 'Completed'
														else 'No Assessment'
													end as assessment_status
													from assessment.assessments A
													where candidate_id=?",$user_id);
				if($assessment_rec->num_rows())
					$this->common->_output_handle('json', true,array('candidate_details'=>$assessment_rec->row_array()));
				else
					$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No records found'));
			
			}
			else
					$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No approved assessment found!'));
		}
	}*/

	/*
	* function for assessment status
	*/
	function get_assessment_status()
	{
		$candidate_submit_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$request_data=$candidate_submit_data['candidate_request'];
		$user_id=$request_data['user_id'];
		$assessment_id=$request_data['assessment_id'];
		$data = array('user_id' => $user_id,
					  'assessment_id'=>$assessment_id);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('user_id','User Id','required');
		$this->form_validation->set_rules('assessment_id','Assessment Id','required');
		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}

		$assessment_rec=$this->db->query("SELECT A.id as assessment_id, A.assessment_datetime,
											CASE
												WHEN A.counseling_status=0 and A.qualified_status then 'Incomplete'
												WHEN A.counseling_status=1 and A.qualified_status then 'Requested'
												WHEN A.counseling_status=2 and A.qualified_status then 'Counseled'
												ELSE 'No Counseling'
											END as counseling_status,

											CASE
												WHEN A.status_id=0 and A.qualified_status=true then 'Downloaded'
												WHEN A.status_id=1 and A.qualified_status=true then 'Inprogress'
												WHEN A.status_id=2 and A.qualified_status=true then 'Completed'
												ELSE 'Incomplete'
											END as assessment_status
											FROM assessment.assessments A
											WHERE candidate_id=?
											AND A.id=?",array($user_id,$assessment_id));
		if($assessment_rec->num_rows())
			$this->common->_output_handle('json',true,array('assessment_details'=>$assessment_rec->row_array()));
		else
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No Assessment found'));	
	}

	/*
	* function for process login
	*/
	function process_login()
	{

		error_reporting(E_ALL);
		ini_set('display_errors',1);
		$this->load->library('form_validation');
		$candidate_submit_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_submit_data['candidate_login'];

		$RequestData = array('user_mobile'=>$candidate_data['mobile'],
							 'user_otp'=> $candidate_data['otp']);
		$this->form_validation->set_data($RequestData);

		$this->form_validation->set_rules('user_otp','User OTP','required');
		$this->form_validation->set_rules('user_mobile','Mobile','required');

		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}

		$user_mobile="'".$RequestData['user_mobile']."'";
		$password=$RequestData['user_otp'];
		$otp_string="'".$password."'";
		$result_rec=$this->db->query("SELECT C.id as user_id,
										C.mobile,C.name as candidate_name,
										COALESCE(C.email,'') AS email,
										C.aadhaar_num,C.gender_code,
										to_char(C.dob::timestamp with time zone, 'dd-Mon-yyyy'::text) AS dob,
										COALESCE(C.pincode,'') AS pincode,
										COALESCE(C.total_experience,'') AS total_experience,
										COALESCE(C.expected_salary,0) AS expected_salary,
										COALESCE(C.relocate_status_code,'') AS relocate_status_code,
										COALESCE(C.img,'') AS img,

										COALESCE(e.id::text, '') as qualification_id,
										COALESCE(e.name,'') as qualification_name,

										COALESCE(cr.id::text,'') as course_id,
										COALESCE(cr.name,'') as course_name,

										COALESCE(s.id::text,'') as state_id,
										COALESCE(s.name,'') as state_name,

										COALESCE(d.id::text,'') as district_id,
										COALESCE(d.name,'') as district_name,

										COALESCE(r.id::text,'') as center_id,
										COALESCE(r.name,'') as center_name,
										COALESCE(A.language_id,1) as language_id,
										case
											when A.counseling_status=0 then 'Incomplete'
											when A.counseling_status=1 then 'Requested'
											when A.counseling_status=2 then 'Counseled'
											else 'No Counseling'
										end as counseling_status,

										case
											when A.status_id=0 and A.qualified_status=true then 'Downloaded'
											when A.status_id=1 and A.qualified_status=true then 'Inprogress'
											when A.status_id=2 and A.qualified_status=true then 'Completed'
											else 'Incomplete'
										end as assessment_status,

										COALESCE(A.id,'0')  as assessment_id

										FROM users.candidates C
										LEFT JOIN master.education e on e.id=C.education_id
										LEFT JOIN master.courses cr on cr.id=C.course_id
										LEFT JOIN master.state s on s.id=C.state_id
										LEFT JOIN master.district d on d.id=C.district_id
										LEFT JOIN users.centers r on r.id=C.center_id
										LEFT JOIN assessment.assessments A ON C.id=A.candidate_id
			                    		WHERE C.mobile=$user_mobile and C.password=CRYPT($otp_string,C.password)
			                    		LIMIT 1");
        if($result_rec->num_rows())
        {
        	$result_array=$result_rec->row_array();
        	$img=$result_array['img'];
        	$result_array['base64_img']='';
        	if($img)
        	{
        		$file_path = base_url(CANDIDATE_IMAGES.$img);
				$data = file_get_contents($file_path);
				$base64 =base64_encode($data);
        		$result_array['base64_img']=$base64;
        	}
            $this->common->_output_handle('json',true, array('login_details'=>$result_array));
        }
        else
        	$this->common->_output_handle('json',false,array('error_code'=>403,'error_msg'=>'Invalid userid/otp'));

	}

	function add_psychometric_results()
	{
		$this->load->library('form_validation');
		$candidate_id=$this->input->get('candidate_id');
		$data = array('candidate_id' => $candidate_id);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('candidate_id', 'Candidate Id', 'required');
		 // set form validation rules

		if ($this->form_validation->run() == FALSE)
		{
			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($this->input->get() as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
	        $this->common->_output_handle('json',false,array('error_code'=>304,'error_msg'=>"Validation errors" ));
		}
		else
		{
			$data['interested_dept_id1']=$this->input->get('interested_dept_id1');
			$data['interested_dept_marks1']=$this->input->get('interested_dept_marks1');
			$data['interested_dept_id2']=$this->input->get('interested_dept_id2');
			$data['interested_dept_marks2']=$this->input->get('interested_dept_marks2');
			$data['interested_dept_id3']=$this->input->get('interested_dept_id3');
			$data['interested_dept_marks3']=$this->input->get('interested_dept_marks3');
			$data['interested_dept_id4']=$this->input->get('interested_dept_id4');
			$data['interested_dept_marks4']=$this->input->get('interested_dept_marks4');
			$data['interested_dept_id5']=$this->input->get('interested_dept_id5');
			$data['interested_dept_marks5']=$this->input->get('interested_dept_marks5');
			$data['interested_dept_id6']=$this->input->get('interested_dept_id6');
			$data['interested_dept_marks6']=$this->input->get('interested_dept_marks6');
			$data['active_status']='t';
			$data['created_on']=date('Y-m-d');
			$insert = $this->partner->do_add_psychometric_results($data);
			if($insert)
			{
				
				$path_url=$this->generate_psy_results_pdf($candidate_id);
				if($path_url!='')
				{
					$path_url_data=array('pdf_result_path'=>$path_url);
					$this->db->update('users.candidates',$path_url_data, array('id'=>$candidate_id));
				}
				$this->common->_output_handle('json', true,array('msg_info'=>"Psychometric Results saved successfully"));	
			}
			else
				$this->common->_output_handle('json',false,array('error_code'=>500,'error_msg'=>'psychometric results not saved'));
	
		}
	}
	function matching_job_by_candidate()
	{
		$this->load->library('form_validation');
		$data = array('candidate_id' => $this->input->get('candidate_id'));
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('candidate_id', 'Candidate Id', 'required');
		 // set form validation rules

		if ($this->form_validation->run() == FALSE)
		{
			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($this->input->get() as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
	        $this->common->_output_handle('json',false,array('error_code'=>304,'error_msg'=>"Validation errors" ));
		}
		else
		{
			$candidate_id= $this->input->get('candidate_id');
			$matching_jobs=$this->partner->matching_jobs_bypsychometric_results($candidate_id);
			if($matching_jobs)
				$this->common->_output_handle('json', true,array('matching_job_details'=>$matching_jobs));
			else
				$this->common->_output_handle('json',false,array('error_code'=>404,'error_msg'=>'-No Data Found-'));		
		}
	}
	function getlanguage_by_candidate_id()
	{
		$candidate_id=$this->input->get('candidate_id');
		$language_rec=$this->db->query("SELECT COALESCE(NULLIF(uc.language_id,'0'),'0') as language_id from users.candidates uc
										left join master.list ml on ml.value::integer= uc.language_id and code='L0009'
										where uc.id=?",$candidate_id);
		if($language_rec->num_rows())
			$this->common->_output_handle('json', true,array('language_details'=>$language_rec->row_array()));
		else
			$this->common->_output_handle('json',false,array('error_code'=>404,'error_msg'=>'-No Data Found-'));		
	}

	function getCandidate_details_by_id()
	{
		$candidate_id=$this->input->get('candidate_id');
		if($candidate_id)
		{
			$candidate_rec=$this->db->query("SELECT uc.*,
											 CASE WHEN jpr.candidate_id>0 THEN 1
											 ELSE 0
											 END AS psychometric_status
											 from users.candidates uc
											 left join job_process.psychometric_test_results jpr on jpr.candidate_id=uc.id
											 where uc.id=?",$candidate_id);
			if($candidate_rec->num_rows())
				$this->common->_output_handle('json', true,array('candidate_details'=>$candidate_rec->row_array()));
			else
				$this->common->_output_handle('json',false,array('error_code'=>404,'error_msg'=>'-No Data Found-'));
		}
		else
		{
			$this->common->_output_handle('json',false,array('error_code'=>400,'error_msg'=>'Invalid inputs'));
		}		
	}

	function apply_for_job()
	{
		$this->load->library('form_validation');
		$candidate_id=$this->input->get('candidate_id');
		$job_id=$this->input->get('job_id');
		$job_apply_status=$this->input->get('job_apply_status');
		$q1_status=$this->input->get('q1_status');
		$q2_status=$this->input->get('q2_status');
		$data = array('candidate_id' => $candidate_id,'job_id'=>$job_id);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('candidate_id', 'Candidate Id', 'required');
		$this->form_validation->set_rules('job_id', 'Job Id', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>400,'error_msg'=>'Invalid inputs'));
		}
		else
		{
			$is_applied=$this->partner->do_apply_for_job($candidate_id,$job_id,$job_apply_status,$q1_status,$q2_status);

			if($is_applied)
				$this->common->_output_handle('json', true,array('job_apply_details'=>array('candidate_id'=>$candidate_id,'job_id'=>$job_id,'job_apply_status'=>$job_apply_status)));
			else
				$this->common->_output_handle('json',false,array('error_code'=>404,'error_msg'=>'-Job Apply error-'));
		}
	}

	/* Functions to upload physical(image) documents 
	* @author Sangamesh.p@pramaan.in_feb_10_2017
	* @param return json
	*/
	function  upload_pdf_result()
	{

		$this->load->library('form_validation');
		$pdf_result=$this->input->get('pdf_result');
		$candidate_id=$this->input->get('candidate_id');
		$data = array('candidate_id' => $candidate_id,'pdf_result'=>$pdf_result);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('candidate_id', 'Candidate Id', 'required');
		$this->form_validation->set_rules('pdf_result', 'Pdf result', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>400,'error_msg'=>'Invalid inputs'));
		}
		else
		{
			$pdf_result_path=$pdf_result;
		    $this->db->query("update users.candidates set pdf_result_path=? where id=?",array($pdf_result_path,$candidate_id));
		   	$pathrec=$this->db->query('select pdf_result_path from users.candidates where id=?',$candidate_id)->row()->pdf_result_path;
		    $result_path=base_url().$pathrec;
		   	$this->common->_output_handle('json', true,array('upload_url'=> $result_path,'upload_msg'=>"Your pdf result document has been uploaded Successfully"));
		}
	}

	/* Functions to upload physical(image) documents 
	* @author Sangamesh.p@pramaan.in_feb_10_2017
	* @param return pdf
	*/
	function  pdf_result_download_url()
	{

		$this->load->library('form_validation');
		$candidate_id=$this->input->get('candidate_id');
		$data = array('candidate_id' => $candidate_id);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('candidate_id', 'Candidate Id', 'required');
		if ($this->form_validation->run() == FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>400,'error_msg'=>'Invalid inputs'));
		}
		else
		{

		   	$path_url=$this->db->query('select name,pdf_result_path from users.candidates where id=?',$candidate_id);
		    if($path_url->num_rows())
		    {
		    	$result_path_rec=$path_url->row_array();
		    	$result_path=$result_path_rec['pdf_result_path'];
		    	$filename='report_'.$result_path_rec['name'].'_'.$candidate_id.'.pdf';
		    	$upload_details=array('upload_url'=> base_url().$result_path,'file_name'=>$filename);
		   		$this->common->_output_handle('json', true,array('download_details'=>$upload_details));
			}
			else
			{
				$this->common->_output_handle('json',false,array('error_code'=>404,'error_msg'=>'-Data Not Found-'));
			}
		}
	}

	/**
	 * function to generate psy results
	 * @author Sangamesh.p@pramaan.in
	*/

	function generate_psy_results_pdf($candidate_id=0)				//generate psychometric results
	{ 
		$candidate_id=$this->input->get('candidate_id');
		$cond='';
		$this->load->helper('file');
		// 	disable DOMPDF's internal autoloader if you are using Composer
		// 	define('DOMPDF_ENABLE_AUTOLOAD', false);
		$template_filename=base_url()."assets/templates/template_psychometric_results.html";
		$file_to_save = PHY_DOC_DIR;
		$data =  file_get_contents($template_filename);
		$top_dept=array();
		$candidate_rec=$this->db->query("SELECT jpp.created_on,uc.name as candidate_name,md.name as location, me.name as qualification from 
										job_process.psychometric_test_results jpp
										join users.candidates uc on uc.id=jpp.candidate_id
										join master.district md on md.id=uc.district_id
										join master.education me on me.id=uc.education_id where uc.id=?",$candidate_id);
		$candi_detail=array();
		if($candidate_rec->num_rows())
		{
			$candi_detail=$candidate_rec->row_array();
		}
		else
			return false;
		$result_rec=$this->db->query("SELECT *,
							CASE WHEN department_id=(select distinct interested_dept_id1 from job_process.psychometric_test_results where candidate_id=$candidate_id ) THEN (select interested_dept_marks1 from job_process.psychometric_test_results where candidate_id=$candidate_id  order by id desc limit 1)
							     WHEN department_id=(select distinct interested_dept_id2 from job_process.psychometric_test_results where candidate_id=$candidate_id ) THEN (select interested_dept_marks2 from job_process.psychometric_test_results where candidate_id=$candidate_id  order by id desc limit 1)
							     WHEN department_id=(select distinct interested_dept_id3 from job_process.psychometric_test_results where candidate_id=$candidate_id ) THEN (select interested_dept_marks3 from job_process.psychometric_test_results where candidate_id=$candidate_id  order by id desc limit 1)
							     WHEN department_id=(select distinct interested_dept_id4 from job_process.psychometric_test_results where candidate_id=$candidate_id ) THEN (select interested_dept_marks4 from job_process.psychometric_test_results where candidate_id=$candidate_id  order by id desc limit 1)
							     WHEN department_id=(select distinct interested_dept_id5 from job_process.psychometric_test_results where candidate_id=$candidate_id ) THEN (select interested_dept_marks5 from job_process.psychometric_test_results where candidate_id=$candidate_id  order by id desc limit 1)
							     WHEN department_id=(select distinct interested_dept_id6 from job_process.psychometric_test_results where candidate_id=$candidate_id ) THEN (select interested_dept_marks6 from job_process.psychometric_test_results where candidate_id=$candidate_id  order by id desc limit 1)
							ELSE '0'
							END AS psychometric_marks
							from master.departments
							order by psychometric_marks desc");
		$top_interest=array('Top 1 Interest','Top 2 Interest','Top 3 Interest');
		if($result_rec->num_rows())
		{
			$marks2=0;
			$x=$result_rec->result_array();
			$marks1=$x[0]['psychometric_marks'];
			$j=0;
			$top=array();
			$top[$x[0]['id']]=$top_interest[$j];
			$marks=array();
			$marks[$x[0]['department_id']]=$x[0]['psychometric_marks'];
			for($i=1;$i<6;$i++)
			{
				$marks2=$x[$i]['psychometric_marks'];
				if($marks1==$marks2)
				{
					$top[$x[$i]['id']]=$top_interest[$j];
				}
				else
				{				
					if($j<2)
						$j++;
					$top[$x[$i]['id']]=$top_interest[$j];
				}
				$marks1=$marks2;
				$marks[$x[$i]['department_id']]=$x[$i]['psychometric_marks'];
			}

		}


		$data = str_replace("%%Candidate_name%%", $candi_detail['candidate_name'], $data);
		$data = str_replace("%%location%%", $candi_detail['location'], $data);
		$data = str_replace("%%qualification%%", $candi_detail['qualification'], $data);
		$data = str_replace("%%assessment_date%%", date('d-m-Y',strtotime($candi_detail['created_on'])), $data);

		$data = str_replace("%%marks1%%", $marks[1], $data);
		$data = str_replace("%%marks2%%", $marks[2], $data);
		$data = str_replace("%%marks3%%", $marks[3], $data);
		$data = str_replace("%%marks4%%", $marks[4], $data);
		$data = str_replace("%%marks5%%", $marks[5], $data);
		$data = str_replace("%%marks6%%", $marks[6], $data);

		$data = str_replace("%%dept1%%", $top[1], $data);
		$data = str_replace("%%dept2%%", $top[2], $data);
		$data = str_replace("%%dept3%%", $top[3], $data);
		$data = str_replace("%%dept4%%", $top[4], $data);
		$data = str_replace("%%dept5%%", $top[5], $data);
		$data = str_replace("%%dept6%%", $top[6], $data);
		$this->load->helper(array('dompdf', 'file'));
		$filename='report_'.$candi_detail['candidate_name'].'_'.$candidate_id.'.pdf';
		$file_path=$file_to_save.$filename;
		$data = pdf_create($data, '', false);
		$result=write_file($file_path, $data);
		return $file_path;
	}

	public function download_pdf()
	{
		//$path = $_GET["path"];test_result.pdf
	/*	$candidate_submit_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$pdf_data=$candidate_submit_data['candidate_registration'];
		$data = array('name' => $candidate_data['name'],
						'mobile' => $candidate_data['mobile'],
*/
		$path = PHY_DOC_DIR.'test_result.pdf';
		$fullfile = $path;
		header('Content-Disposition: attachment; filename=' . urlencode(basename($fullfile)));  
		header("Content-Transfer-Encoding: binary");
		header('Content-Type: application/pdf');
		header("Content-Description: File Transfer");
		header('Expires: 0');
		header('Pragma: public');            
		header("Content-Length: " . Filesize(PHY_DOC_DIR.urlencode(basename($fullfile))));
		echo file_get_contents(PHY_DOC_DIR.urlencode(basename($fullfile)));
	}

	public function upload_image()
	{
	    $file_path = CANDIDATE_IMAGES . basename( $_FILES['fileName']['name']); 	
	    if(move_uploaded_file($_FILES['fileName']['tmp_name'], $file_path)) 
	    {
		move_uploaded_file($_FILES["fileName"]["tmp_name"],$file_path); 
	      	$this->common->_output_handle('json', true,array('status'=>'success'));
	    } 
	    else
	    {
		$this->common->_output_handle('json',false,array('error_code'=>404,'error_msg'=>'fail'));
	    }
			
	}

	function get_states($country_id=0)
	{
		$state_recs=$this->db->query("SELECT id, name from 
										master.state S
										where 1=1 and active_status=?",1);
		if($state_recs->num_rows())
		{
			$this->common->_output_handle('json', true,array('state_list'=>$state_recs->result_array()));
		}
		else
		{
			$this->common->_output_handle('json', false,array('state_list'=>''));
		}
	}

	function get_districts($state_id=0)
	{
		$cond='';
		if($state_id)
			$cond=' and state_id='.$state_id;
		$district_recs=$this->db->query("SELECT id, name from 
										master.district
										where 1=1 $cond
										and active_status=?",1);
		if($district_recs->num_rows())
		{
			$this->common->_output_handle('json', true,array('district_list'=>$district_recs->result_array()));
		}
		else
		{
			$this->common->_output_handle('json', false,array('district_list'=>''));
		}
	}

	function get_qualifications()
	{
		$qualification_recs=$this->db->query("SELECT id, name 
												from master.education
												where 1=1
												order by sortorder");
		if($qualification_recs->num_rows())
		{
			$this->common->_output_handle('json', true,array('qualification_list'=>$qualification_recs->result_array()));
		}
		else
		{
			$this->common->_output_handle('json', false,array('qualification_list'=>''));
		}
	}
	function get_location_course()
	{
		$locations=array();
		$courses=array();
		$centers=array();
		$location_recs=$this->db->query("SELECT COALESCE(s.id::text,'') as state_id, COALESCE(s.name,'') as state_name,
												COALESCE(d.id::text,'') as district_id, COALESCE(d.name,'') as district_name
										 FROM master.district d 
										 LEFT JOIN master.state s on s.id=d.state_id
										 ORDER BY s.name");
		
		$courses_recs=$this->db->query("SELECT e.id as qualification_id, e.name as qualification_name,
											c.id as course_id, c.name as course_name
											FROM master.courses c
											LEFT JOIN master.education e
											ON e.id=c.education_id
											ORDER BY e.sortorder");
		

		if($location_recs)
			$locations=$location_recs->result_array();
		if($courses_recs)
			$courses=$courses_recs->result_array();
		$center_recs=$this->api->get_center(0);
		if($center_recs)
			$centers=$center_recs;
		$location_courses=array('locations'=>$locations,
						  'courses'=>$courses,'centers'=>$centers);
		echo json_encode($location_courses);
	}


	public function get_candidate_questions()
	{	
		$this->load->library('form_validation');
		$candidate_request_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_request_data['download_questions'];

		$RequestData = array('candidate_id'=>$candidate_data['candidate_id'],
							 'language_id'=> $candidate_data['language_id']);
		$this->form_validation->set_data($RequestData);

		$this->form_validation->set_rules('candidate_id','Candidate Id','required');
		$this->form_validation->set_rules('language_id','Language Id','required');

		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}
		
		$candidate_id=$candidate_data['candidate_id'];
		$language_id=$candidate_data['language_id'];
		
		$RequestData = array('candidate_id'=>$candidate_id,
							 'language_id'=> $language_id);
		$candidate_question = $this->api->get_candidate_questions($RequestData);
	
		$part_detail=$this->db->query("SELECT P.id as part_id,
										P.name as part_name, 
										'0' as remaining_time,
										'0' as part_time,
										FALSE as timer_present,
										ROW_NUMBER() OVER(ORDER BY P.id) AS part_index,

										CASE 
										WHEN P.id IN (1,3) AND A.completed_part_index>=p.id THEN 'Completed'
										WHEN P.id  IN (1,3) AND A.completed_part_index<p.id THEN 'Incomplete'

										WHEN P.id = 2 AND A.completed_section_index < 1 THEN 'Incomplete'
										WHEN P.id = 2 AND A.completed_section_index < (select count(*) from content.aptitude_duration
										                                                where question_paper_id=A.question_paper_id) THEN 'Incomplete'
										WHEN P.id = 2 AND A.completed_section_index = (select count(*) from content.aptitude_duration
										                                                where question_paper_id=A.question_paper_id) THEN 'Completed'
										ELSE 'Incomplete'
										END AS  part_status

										FROM		assessment.assessments as A
										INNER JOIN	users.candidates AS C ON C.id=A.candidate_id
										INNER JOIN	assessment.assessment_types AS AT ON AT.id = C.assessment_type_id
										INNER JOIN	content.parts AS P ON P.id = ANY(AT.part_id_list)
										WHERE A.candidate_id=$candidate_id
										AND A.qualified_status=true
										AND A.status_id<2
										ORDER by A.id desc")->result_array();
										

		$section_detail=$this->db->query("SELECT S.part_id,
													S.id as section_id, 
													S.name as section_name, 
													AD.duration_minutes  as remaining_time, 
													AD.duration_minutes as section_time, 
													TRUE as timer_present,
													ROW_NUMBER() OVER(ORDER BY S.sort_order) AS section_index,
													CASE 
														WHEN A.completed_section_index>=s.id THEN 'Completed'
														WHEN A.completed_section_index<s.id THEN 'Incomplete'
														ELSE 'Incomplete'
													END AS  section_status

											FROM		assessment.assessments as A
											INNER JOIN	users.candidates AS C ON C.id=A.candidate_id
											INNER JOIN	assessment.assessment_types AS AT ON AT.id = C.assessment_type_id
											INNER JOIN	content.sections AS S ON S.id = ANY(AT.section_id_list)
											LEFT JOIN 	content.aptitude_duration AD on AD.question_paper_id=A.question_paper_id
											WHERE		S.part_id=2
											AND		S.code IN (SELECT section_code 
														FROM 	content.questions 
														WHERE 	question_paper_id = A.question_paper_id)
											AND 		A.candidate_id=$candidate_id
											AND A.qualified_status=true
											AND A.status_id<2
											GROUP BY	S.id ,A.id, AD.duration_minutes,A.completed_section_index
											ORDER BY	S.sort_order, A.id desc")->result_array();
	
		$assessment_id=$this->db->query("SELECT id as assessment_id
										 FROM assessment.assessments
										 WHERE candidate_id=?
										 AND status_id<2
										 AND qualified_status=true",$candidate_id)->row()->assessment_id;
		$ResponseData['part_detail']=$part_detail;
		$ResponseData['section_detail']=$section_detail;
		$instructions=$this->db->query("SELECT I.part_id::text,string_agg(I.instruction, '<br />' ORDER BY I.language_id) AS instruction,
															CASE 
																WHEN part_id<0 THEN 'Sample'
																WHEN part_id=0 THEN 'General'
																WHEN part_id=1 THEN 'Career Interest'
																WHEN part_id=2 THEN 'Aptitude'
																WHEN part_id=3 THEN 'Personality'
																ELSE ''
															END as instruction_part
														FROM 		content.instructions AS I
														LEFT JOIN	content.parts AS P ON P.id = I.part_id
														where language_id=$language_id
														group by I.part_id
														order by I.part_id")->result_array();

		$sample_instruction=$this->db->query("SELECT '-1' as part_id,string_agg(instruction, '<br />') AS instruction, 'sample' as instruction_part
												FROM content.fn_get_sample_instructions(?)",$language_id)->result_array();
		
		
	
		$ResponseData['instructions']=array_merge($sample_instruction,$instructions);
	
		$ResponseData['questions']=$candidate_question; 
		$ResponseData['sample_questions']=$this->get_sample_questions($language_id);
		echo json_encode(array('status'=>'success','assessment_id'=>$assessment_id,'assessment_details'=>$ResponseData));
	}

	public function get_sample_questions($LanguageId = 1)
	{
		$LanguageId = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $LanguageId;
		$QuestionData = $this->api->get_sample_questions($LanguageId);

		$Data = array();

		$QuestionImgBase64String = "";
		$Option1ImgBase64String  = "";
		$Option2ImgBase64String  = "";
		$Option3ImgBase64String  = "";
		$Option4ImgBase64String  = "";
		$Option5ImgBase64String  = "";
		$QuestionImagePath       = realpath(SAMPLE_QUESTION_IMAGES_SCALED) . "/";

		foreach ($QuestionData as $Question)
		{
			$QuestionImgBase64String = "";
			if (!is_null($Question['question_img']))
				if (trim($Question['question_img']) != '')
					$QuestionImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['question_img']));

			$Option1ImgBase64String = "";
			if (!is_null($Question['option1_img']))
				if (trim($Question['option1_img']) != '')
					$Option1ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option1_img']));

			$Option2ImgBase64String = "";
			if (!is_null($Question['option2_img']))
				if (trim($Question['option2_img']) != '')
					$Option2ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option2_img']));

			$Option3ImgBase64String = "";
			if (!is_null($Question['option3_img']))
				if (trim($Question['option3_img']) != '')
					$Option3ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option3_img']));

			$Option4ImgBase64String = "";
			if (!is_null($Question['option4_img']))
				if (trim($Question['option4_img']) != '')
					$Option4ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option4_img']));

			$Option5ImgBase64String = "";
			if (!is_null($Question['option5_img']))
				if (trim($Question['option5_img']) != '')
					$Option5ImgBase64String = $this->get_image_base64_string($QuestionImagePath . trim($Question['option5_img']));

			$Data[] = array('question_id' 		=> $Question['question_id'],
							'question' 			=> $Question['question_text'],
							'option1' 			=> $Question['option1_text'],
							'option2' 			=> $Question['option2_text'],
							'option3' 			=> $Question['option3_text'],
							'option4' 			=> $Question['option4_text'],
							'option5' 			=> $Question['option5_text'],
							'question_img' 		=> $QuestionImgBase64String,
							'option1_img' 		=> $Option1ImgBase64String,
							'option2_img' 		=> $Option2ImgBase64String,
							'option3_img' 		=> $Option3ImgBase64String,
							'option4_img' 		=> $Option4ImgBase64String,
							'option5_img' 		=> $Option5ImgBase64String,
							'part_id' 			=> $Question['part_id'],
							'part_name' 		=> $Question['part_name'],
							'section_id' 		=> $Question['section_id'],
							'section_name' 		=> $Question['section_name'],
							'question_type'		=> $Question['question_type_name'],
			      			'option_count' 		=> $Question['option_count']
							);
		}
		return $Data;
	}

	public function get_instructions($part_id = -1, $language_id = 1)
	{
		$part_id = isset($_REQUEST['part_id']) ?  $_REQUEST['part_id'] : $part_id;
		$language_id = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $language_id;

		$ResponseData = array(
			"status" => "SUCCESS",
			"instruction_data" => $this->api->get_instructions($part_id, $language_id)
		);
		echo json_encode($ResponseData);
	}

	public function get_sample_instructions($language_id = 1)
	{
		$language_id = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $language_id;
		$ResponseData = $this->api->get_sample_instructions($language_id);

		echo json_encode($ResponseData);
	}

	public function get_image_base64_string($imagePath)
	{
		$strImgData = "";
		if (file_exists($imagePath))
		{
			$type       = get_mime_by_extension($imagePath);
			$strImgData = base64_encode(file_get_contents($imagePath));
		}
		return $strImgData;
	}

	public function get_resource_list($LanguageId = 1, $ResourceCode = '')
	{
		$ResponseData = array();
		$LanguageId = isset($_REQUEST['language_id']) ?  $_REQUEST['language_id'] : $LanguageId;
		$ResourceCode = isset($_REQUEST['code']) ?  $_REQUEST['code'] : $ResourceCode;
		$ResourceList = $this->api->get_resource_list($LanguageId, $ResourceCode);
		foreach ($ResourceList as $Resource)
			$ResponseData[$Resource['resource_code']] = $Resource['resource_text'];

		echo json_encode($ResponseData);
	}

	public function get_candidates_by_batchId($batch_id=0)
	{
		$batch_id = $_REQUEST['batch_id'];
        $resp_data   = $this->api->get_candidate_list($batch_id);
        echo json_encode($resp_data);
	}

	public function get_batches_by_userId($user_id=0)
	{
		$user_id = $_REQUEST['user_id'];
        $resp_data   = $this->api->get_batch_list($user_id);
        echo json_encode($resp_data);
	}

	
	public function submit_candidate_responses()
	{
		$this->load->library('form_validation');
		$candidate_response_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_response=$candidate_response_data['candidate_response'];
		
		$RequestData = array(
			'candidate_id' 				=> $candidate_response['candidate_id'],
			'assessment_id'				=> $candidate_response['assessment_id'],
			'completed_part_index' 		=> $candidate_response['completed_part_index'],
			'completed_section_index' 	=> $candidate_response['completed_section_index'],
			'response_list'				=> $candidate_response['response_list'],
			'assessment_status'			=> $candidate_response['assessment_status']
		);

		$this->form_validation->set_data($RequestData);

		$this->form_validation->set_rules('candidate_id','Candidate Id','required');
		$this->form_validation->set_rules('assessment_id','Assessment Id','required');
		$this->form_validation->set_rules('completed_part_index','Completed Part Index','required');
		$this->form_validation->set_rules('completed_section_index','Completed Section Index','required');
		$this->form_validation->set_rules('response_list','Response List','required');
		$this->form_validation->set_rules('assessment_status','Assessment Status','required');

		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}

		$ResponseData = $this->api->submit_candidate_responses($RequestData);
		echo json_encode($ResponseData);
	}


	function test_sms()
	{
		$to='8660360419';
		$msg='This is a test message.';
		$sms_data=array();
		$sms_data['to']=$to;
		$sms_data['msg']=$msg;
		$result=$this->common->smsvia_gupshup($sms_data);
		print_r($result);
	}
	function reset_assessment()
	{
		$this->load->library('form_validation');
		$candidate_request_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_request_data['reset_assessment'];
		$user_id=$candidate_data['user_id'];
		$assessment_id=$candidate_data['assessment_id'];
		$RequestData = array('user_id'=>$user_id,
							 'assessment_id'=>$assessment_id);
		$this->form_validation->set_data($RequestData);

		$this->form_validation->set_rules('user_id','User Id','required');
		$this->form_validation->set_rules('assessment_id','Assessment Id','required');

		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}
		$this->db->query("update assessment.assessments
							set status_id=0,
						    completed_part_index=0,
							completed_section_index=0
						    where candidate_id=?
						    AND id=?",array($user_id,$assessment_id));
		//$assessment_rec=$this->db->query("SELECT * from assessment.fn_get_assessment_status(?,?)",$user_id,$assessment_id);		
		$assessment_rec=$this->db->query("SELECT assessment_id, A.assessment_date,
											CASE
												WHEN A.counseling_status=0 and A.qualified_status=true then 'Incomplete'
												WHEN A.counseling_status=1 and A.qualified_status=true then 'Requested'
												WHEN A.counseling_status=2 and A.qualified_status=true then 'Counseled'
												ELSE 'No Counseling'
											END as counseling_status,

											CASE
												WHEN A.assessment_status_id=0 and A.qualified_status=true then 'Incomplete'
												WHEN A.assessment_status_id=1 and A.qualified_status=true then 'Inprogress'
												WHEN A.assessment_status_id=2 and A.qualified_status=true then 'Completed'
												ELSE 'No Assessment'
											END as assessment_status
											FROM assessment.fn_get_assessment_status(?,?) A",array($user_id,$assessment_id));
		if($assessment_rec->num_rows())
			$this->common->_output_handle('json',true,array('assessment_details'=>$assessment_rec->row_array()));
		else
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No Assessment found'));
	}


	function request_counseling_slot()
	{
		$this->load->library('form_validation');
		$candidate_request_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_request_data['slot_request'];
		$user_id=$candidate_data['user_id'];
		$center_id=$candidate_data['center_id'];
		$counseling_date=$candidate_data['counseling_date'];
		$assessment_rec=$this->db->query("SELECT id as assessment_id, TO_CHAR(assessment_datetime,'dd-Mon-yyyy') as assessment_date,status_id,counseling_status
											FROM assessment.assessments
											WHERE status_id>=2
											AND qualified_status=true
											AND counseling_status=1
											AND candidate_id=?
											ORDER BY id DESC
											limit 1",$user_id);
		if(!$assessment_rec->num_rows())
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No assessment found'));	
		}
		else
		{
			$RequestData = array('user_id'=>$user_id,
								 'counseling_date'=>$counseling_date);
			$this->form_validation->set_data($RequestData);

			$this->form_validation->set_rules('user_id','User Id','required');
			$this->form_validation->set_rules('counseling_date','Counseling Date','required');

			if($this->form_validation->run() === FALSE)
			{
				$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
			}
			$slot_recs=$this->api->get_counseling_slot($user_id,$center_id,$counseling_date);
			if($slot_recs)
			{
				$this->common->_output_handle('json',true,array('slot_details'=>$slot_recs));
			}
			else
				$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No Slot found'));
		}
	}

	function book_counseling_slot()
	{
		$this->load->library('form_validation');
		$candidate_request_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_request_data['slot_book'];
			
		$user_id=$candidate_data['user_id'];
		$slot_id=$candidate_data['slot_id'];
		$center_id=$candidate_data['center_id'];
		$assessment_id=$candidate_data['assessment_id'];
		$assessment_rec=$this->db->query("SELECT id as assessment_id, TO_CHAR(assessment_datetime,'dd-Mon-yyyy') as assessment_date,status_id,counseling_status
											FROM assessment.assessments
											WHERE status_id>=2
											AND qualified_status=true
											AND counseling_status=1
											AND candidate_id=?
											ORDER BY id DESC
											limit 1",$user_id);
		if(!$assessment_rec->num_rows())
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No assessment found'));	
		}
		else
		{
			$counseling_date=$candidate_data['counseling_date'];
			$remarks='Booked';
			$status=1;
			
			$RequestData = array('assessment_id'=>$assessment_id,
								 'candidate_id'=>$user_id,
								 'counseling_slot_id'=>$slot_id,
								 'center_id'=>$center_id,
								 'counseling_date'=>$counseling_date,
								 'remarks'=>$remarks,
								 'status'=>$status);
			$this->form_validation->set_data($RequestData);

			$this->form_validation->set_rules('candidate_id','User Id','required');
			$this->form_validation->set_rules('counseling_slot_id','Counseling Slot Id','required');
			$this->form_validation->set_rules('counseling_date','Counseling Date','required');
			$this->form_validation->set_rules('center_id','Center Id','required');
			$this->form_validation->set_rules('remarks','Remarks','required');
			if($this->form_validation->run() === FALSE)
			{
				$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
			}
			
			$insert_rec=$this->api->save_counseling_slot($RequestData);
			if($insert_rec)
			{
				if($insert_rec['slot_status'])
					$this->common->_output_handle('json',true,array('slot_details'=>$insert_rec['booked_slots'],'msg'=>'Successfully Booked'));
				else
					$this->common->_output_handle('json',true,array('slot_details'=>$insert_rec['reserved_slots'],'msg'=>'You have already booked a slot '.$insert_rec['reserved_slots'][0]['slot_name'].' on'.$insert_rec['reserved_slots'][0]['counseling_date']));
			}
			else
				$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No Slot booked'));	
		}
	}

	function cancel_counseling_slot()
	{
		$this->load->library('form_validation');
		$candidate_request_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_request_data['slot_cancellation'];
			
		$candidate_id=$candidate_data['candidate_id'];
		$assessment_id=$candidate_data['assessment_id'];
		$center_id=$candidate_data['center_id'];
		$RequestData = array('candidate_id'=>$candidate_id,
							 'assessment_id'=>$assessment_id,
							 'center_id'=>$center_id);
		$this->form_validation->set_data($RequestData);
		$this->form_validation->set_rules('candidate_id','Candidate Id','required');
		$this->form_validation->set_rules('assessment_id','Assessment Id','required');
		$this->form_validation->set_rules('center_id','Center Id','required');
		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}
		
		$cancelation_rec=$this->api->do_cancel_counseling_slot($RequestData);
		if($cancelation_rec)
		{
			$this->common->_output_handle('json',true,array('msg'=>'Reserved slot has been cancelled'));
		}
		else
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No Slot Canceled'));
	}

	function get_counseling_history()
	{
		$this->load->library('form_validation');
		$candidate_request_data = (array) json_decode(file_get_contents('php://input'), TRUE);
		$candidate_data=$candidate_request_data['counseling_history'];
			
		$candidate_id=$candidate_data['candidate_id'];
		
		$RequestData = array('candidate_id'=>$candidate_id);
		$this->form_validation->set_data($RequestData);
		$this->form_validation->set_rules('candidate_id','Candidate Id','required');
		if($this->form_validation->run() === FALSE)
		{
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>validation_errors()));
		}
		
		$counseling_history=$this->api->do_get_counseling_history($candidate_id);
		if($counseling_history)
		{
			$this->common->_output_handle('json',true,array('counseling_history'=>$counseling_history));
		}
		else
			$this->common->_output_handle('json',false,array('error_code'=>2070,'error_msg'=>'No counseling found'));
	}
}
<?php
/**
 * API :: Pramaan api  
 * @author by Sangamesh<sangamesh.p@mpramaan.in_Nov-2016>
 * local $api_url = 'http://localhost/pramaan/pramaan_api/';
 * remote $api_url = 'www.pramaan.in/pramaan_api/';
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

class Pramaan_api extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		header('Content-Type: text/html; charset=utf-8');
		header('Access-Control-Allow-Origin: *');
		$this->load->library('form_validation');
		$this->load->model("Pramaan_api_model","pam");
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
						'expected_salary_id' => $_REQUEST['expected_salary_id']
					);
		$this->form_validation->set_data($data);
		$this->form_validation->set_rules('name', 'Candidate Name', 'required');
		$this->form_validation->set_rules('mobile','Phone','required');
		$this->form_validation->set_rules('dob', 'date_of_birth', 'required');
		$this->form_validation->set_rules('state_id', 'State', 'required');
		$this->form_validation->set_rules('district_id', 'District', 'required');
		$this->form_validation->set_rules('experience_id', 'Experience', 'required');
		$this->form_validation->set_rules('education_id', 'Education', 'required');
		$this->form_validation->set_rules('course_id', 'Course', 'required');
		$this->form_validation->set_rules('expected_salary_id', 'Expected Salary', 'required');
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
	* @param return json
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
		$path = $_GET["path"];
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
}
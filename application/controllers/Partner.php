<?php defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('session_cache_limiter','private'); 
    session_cache_limiter(FALSE);
/**
 * Partner :: Partner Controller
 * @author by Sangamesh<sangamesh.p@mpramaan.in_Nov-2016>
**/
include APPPATH.'/controllers/Pramaan.php';

class Partner extends Pramaan
{
        protected $igs_candidate_fields = ['Centername'=>'center_name',
                              'Center Type' => 'center_type',
                              'FirstName' => 'candidate_name', 
                              'Candidate Registration Id' => 'candidate_number',
                              'Candidate Enrollment Id' => 'candidate_enrollment_id', 
                              'Mobile Number' => 'mobile',
                              'Address' => 'address',
                              'Country' => 'country',
                              'State' => 'state',
                              'District' => 'district',
                              'PinCode' => 'pincode', 
                              'Gender' => 'gender_name',
                              'DateOfBirth' => 'date_of_birth',
                              'Document Type' => 'document_type',
                              'Document Number' => 'document_number',
                              'Employment Type' => 'employment_type',
                              'Candidate Created Date' => 'created_at',
                              'Father Name' => 'father_name',
                              'Family Contact Number' => 'family_contact_number', 
                              'Language Known' => 'language_known',
                              'First Education' => 'education_name',                            
                              'First Education Year Of Passing' => 'year_of_passing',
                              'Religion' => 'religion',                               
                              'Age' => 'age',                             
                              'Marital Status' => 'marital_status',                              
                              'BatchCode' => 'batch_code',
                              'Technical Education' => 'technical_education',                             
                              'Preferred Location' => 'prefered_job_location', 
                              'Willing to Travel' => 'willing_to_travel',
                              'Willing to Work in NightShifts' => 'willing_to_work_at_night',
                              'Computer Knowledge' => 'computer_knowledge',
                              'Enrollment Date' => 'enrollment_date',
                              'Caste Catagory' => 'caste_category'];
        
	public function __construct()
	{
		parent::__construct();
                $this->load->helper('role_helper');
		$this->load->model("Partner_model","partner");                
	}

	public function training_partner()
	{
		$data['page']='training';
		$data['title']='Training list';
		$data['module']="partner";
		$this->load->view('index',$data);
	}
	public function skillmission_partner()
	{
		$data['page']='skill_mission';
		$data['title']='Skill Misssion list';
		$data['module']="partner";
		$this->load->view('index',$data);
	}

	public function center($partner_id=0)
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$data['page']='center_list';
		$data['title']='Center list';
		$data['module']="partner";
		if($partner_id)
			$data['partner_id']=$partner_id;
		else
			$data['partner_id']=$user['id'];
		$this->load->view('index',$data);
	}

	public function center_list($partner_id=0)
	{
		error_reporting(E_ALL);
		$this->pramaan->_check_module_task_auth(false);
		$requestData= $_REQUEST;
		$rep_data=$this->partner->get_center_datatables($requestData,$partner_id);
		echo json_encode($rep_data);  // send data as json format
	}

	public function center_by_id($id)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data = $this->partner->get_center_by_id($id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		echo json_encode($data);
	}

	public function add_center()
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('centername', 'Center Name', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('phone','Phone','required|max_length[12]|callback__unique_center_phone');
		if ($this->form_validation->run() == FALSE)
		{

			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($this->input->post() as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else
		{
			$data = array(
				'name' => $this->input->post('centername'),
				'user_id'=>$this->input->post('partner_id'),
				'partner_id'=>$this->input->post('partner_id'),
				'address' => $this->input->post('address'),
				'phone' => $this->input->post('phone')
			);
			$insert = $this->partner->do_add_center($data);
			if($insert)
			{
				echo json_encode(array('status'=>TRUE,'msg_info'=>'New center has been added'));
			}
		}

	}

	public function update_center()
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('centername', 'Center Name', 'required');
		$this->form_validation->set_rules('address', 'Address', 'required');
		//$this->form_validation->set_rules('phone','Phone','required|max_length[12]');
		$this->form_validation->set_rules('phone','Phone','required|max_length[12]|callback__unique_center_phone');
		if ($this->form_validation->run() == FALSE)
		{

			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($this->input->post() as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else
		{
			$data = array(
				'name' => $this->input->post('centername'),
				'partner_id'=>$this->input->post('partner_id'),
				'address' => $this->input->post('address'),
				'phone' => $this->input->post('phone')
			);
			$is_update = $this->partner->do_update_center(array('id' => $this->input->post('id')), $data);
			if($is_update)
			{
				echo json_encode(array('status'=>TRUE,'msg_info'=>'This Center has been updated'));
			}
		}

	}

	public function delete_center($id)
	{

		$is_delete = $this->partner->do_delete_center($id);
		if($is_delete)
		{
			echo json_encode(array('status'=>TRUE,'msg_info'=>'!!Center has been deleted'));
		}
		else
		{
			echo json_encode(array('status'=>FALSE, 'errors' =>array('!!!Please contact Pramaan admin')));
		}
	}

	public function associates($partner_id=0)
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$cond='';
		$data['page']='associates_list';
		$data['title']='Associates list';
		$data['module']="partner";
		if(!$partner_id)
			$partner_id=$user['id'];
		$data['partner_id']=$partner_id;
		$cond="where partner_id=".$partner_id;
		$data['center_list']=$this->db->query("select id, name from users.centers $cond order by name ")->result_array();
		$sr_partner_id=$this->pramaan->do_get_sourcing_partner_id($partner_id);
		$data['parent_page']="pramaan/sourcing_partner/".$sr_partner_id;
		$data['parent_page_title']="Sourcing Partner";
		$this->load->view('index',$data);
	}


	public function associates_list($partner_id=0)
	{
		error_reporting(E_ALL);
		$this->pramaan->_check_module_task_auth(false);
		$requestData= $_REQUEST;
		$resp_data=$this->partner->get_associates_datatables($requestData,$partner_id);
		echo json_encode($resp_data);  // send data as json format


	}

	public function associates_by_id($user_id)
	{
		$data = $this->partner->get_associate_by_id($user_id);
		//$data->dob = ($data->dob == '0000-00-00') ? '' : $data->dob; // if 0000-00-00 set tu empty for datepicker compatibility
		if($data)
			echo json_encode($data);
	}

	public function add_associates()
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('center', 'Center', 'required');
		$this->form_validation->set_rules('associatename', 'Associate Name', 'required|callback__valid_name');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('phone','Phone','required|callback__valid_phone|max_length[12]|callback__unique_user_phone');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');
		$this->form_validation->set_rules('password','Password','required');
		if ($this->form_validation->run() == FALSE)
		{

			$errors = array();
    			// 	Loop through $_POST and get the keys
	        foreach ($this->input->post() as $key => $value)
	        {
	            // 	Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else
		{
			$password=$this->input->post('password');
			$data = array(
				'center_id'=>$this->input->post('center'),
				'name' => $this->input->post('associatename'),
				'partner_id'=>$this->input->post('partner_id'),
				'address' => $this->input->post('address'),
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'password' => $password

			);

			$insert = $this->partner->do_add_associates($data);
			if($insert)
			{
				echo json_encode(array('status'=>TRUE,'msg_info'=>'New Associate has been added'));
			}
		}
	}


	public function update_associates()
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('center', 'Center', 'required');
		$this->form_validation->set_rules('associatename', 'Associate Name', 'required|callback__valid_name');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_rules('phone','Phone','required|max_length[12]');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');

		if ($this->form_validation->run() == FALSE)
		{

			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($this->input->post() as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else
		{
			$user_group_id=5;
			$password=$this->input->post('password');
			$id=$this->input->post('id');
			$udata = array(
				'email'=> $this->input->post('email'),
				'user_group_id' => $user_group_id,
				'modified_on'=>date('Y-m-d')
			);
			if($password)
				$accountsdata['password']=$password;
			$user_id=$this->db->query('select user_id from users.associates where id=?',$id)->row()->user_id;
			$adata = array('center_id'=>$this->input->post('center'),
							'name' => $this->input->post('associatename'),
							'partner_id'=>$this->input->post('partner_id'),
							'address' => $this->input->post('address'),
							'phone' => $this->input->post('phone')
						  );

			$is_update = $this->partner->do_update_associate(array('id' => $user_id),array('id' => $id), $udata,$adata);
			if($is_update)
			{
				echo json_encode(array('status'=>TRUE,'msg_info'=>$is_update['msg_info']));
			}
			else
			{
				echo json_encode(array('status'=>FALSE, 'errors' =>array('!!!Error please contact Admin')));
			}
		}
	}

	public function delete_associates($id)
	{
		$is_delete=$this->partner->do_delete_associate($id);
		if($is_delete)
		{
			echo json_encode(array('status'=>TRUE,'msg_info'=>'!!Associate has been deleted'));
		}
		else
		{
			echo json_encode(array('status'=>FALSE, 'errors' =>array('!!!Please contact Pramaan admin')));
		}
	}

	public function add_candidate($associate_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['associate_id']=$associate_id;
		$data['page']='add_candidate';
		$data['title']='Add Candidate';
		$data['module']="partner";

		$region_id_rec=$this->db->query("SELECT ARRAY_TO_STRING(region_id_list,',')  AS region_id_list
											from users.vw_sr_rm
											WHERE sr_rm_id=(SELECT regional_manager_id from users.vw_district_coordinator
											WHERE dcor_user_id=(SELECT coordinator_id from users.vw_sourcing_partner
											WHERE sr_partner_id=?))",$associate_id);
		$region_id='0';
		if($region_id_rec->num_rows())
			$region_id=$region_id_rec->row()->region_id_list;

		$strQuery = "SELECT * FROM master.state WHERE region_id IN (" . $region_id . ") ORDER BY name";
		$data['state_list'] = $this->db->query($strQuery)->result_array();

		$data['last_qualification_list']=$this->pramaan->do_get_qualification();
		$data['experience_list']=$this->pramaan->do_get_experience();
		$data['salary_list']=$this->pramaan->do_get_salary();
		$data['id_types']=$this->pramaan->do_get_id_types();
		$data['language_known']=$this->pramaan->get_languages();

            		//Aniket's work
       /*print_r($this->pramaan->get_area_of_interest_list());
       die;*/
        $data['other_course_code'] = $this->pramaan->get_other_course_code();

      /*  print_r( $data['other_course_code'][0]->id);
        die;*/

        $data['area_of_interest_list'] = $this->pramaan->get_area_of_interest_list();

           $i=1;
		   $data['other_interest_code']='';
        foreach ($data['area_of_interest_list'] as $key ) {

                  if($key['name']=='Others')
                  	 {
                  	 	$data['other_interest_code'] = $key['value'];
                  	 	break;
                  	 }

                  	 $i++;
         }

        //ends


		$this->load->view('index',$data);
	}

	public function edit_candidate($candidate_id=0,$associate_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['associate_id']=$associate_id;
		$data['page']='edit_candidate';
		$data['title']='Edit Candidate';
		$data['module']="partner";
		$data['candidate_data']=$this->partner->get_candidate_by_id($candidate_id,$associate_id);

		//$data['state_list']=$this->pramaan->do_get_state();

		$region_id_rec=$this->db->query("SELECT region_id_list from users.vw_sr_rm
											WHERE sr_rm_id=(SELECT regional_manager_id from users.vw_district_coordinator
											WHERE dcor_user_id=(SELECT coordinator_id from users.vw_sourcing_partner
											WHERE sr_partner_id=?))",$associate_id);
		$region_id='';
		if($region_id_rec->num_rows())
			$region_id=$region_id_rec->row()->region_id_list;
		$data['state_list']=$this->pramaan->do_get_state($region_id);
		$data['district_list']=$this->pramaan->do_get_district_by_state($data['candidate_data']['state_id']);
		$data['last_qualification_list']=$this->pramaan->do_get_qualification();
		$data['experience_list']=$this->pramaan->do_get_experience();
		$data['salary_list']=$this->pramaan->do_get_salary();
		$data['id_types']=$this->pramaan->do_get_id_types();
        $data['language_known']=$this->pramaan->get_languages();

		//Aniket's Work
        $data['other_course_code'] = $this->pramaan->get_other_course_code();
        $data['area_of_interest_list'] = $this->pramaan->get_area_of_interest_list();

		$i=1;
		$data['other_interest_code']='';

        foreach ($data['area_of_interest_list'] as $key )
		{
			if($key['name']=='Others')
            {
				$data['other_interest_code'] = $key['value'];
                break;
			}

            $i++;
        }

		//Ends

		//print_r($data['candidate_data']);
		$this->load->view('index',$data);
	}

	public function get_district_bystate($state_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['status']=false;
		$data['message']='No Items found';
		$district_list=$this->pramaan->do_get_district_by_state($state_id);
		if($district_list)
		{
			$data['status']=true;
			$data['district_list']=$district_list;
			$data['message']='';
		}
		echo json_encode($data);
	}

	public function get_course_byqualification($education_id)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['status']=false;
		$data['message']='No Items found';
		$course_list=$this->pramaan->do_get_course($education_id);
		if($course_list)
		{
			$data['status']=true;
			$data['course_list']=$course_list;
			$data['message']='';
		}
		echo json_encode($data);
	}

	public function save_candidate()
	{
		error_reporting(E_ALL);
		$user=$this->pramaan->_check_module_task_auth(true);

		$this->load->library('form_validation');
		$this->form_validation->set_rules('candidate_name', 'Candidate Name', 'required|callback__valid_name');
		$this->form_validation->set_rules('phone','Phone','required|callback__valid_phone|max_length[12]|callback__unique_user_phone');
		$email= $this->input->post('email');
		if($email)
		$this->form_validation->set_rules('email', 'email', 'required|callback__unique_email');
		$this->form_validation->set_rules('date_of_birth', 'date_of_birth', 'required|callback__validateAge');
		$this->form_validation->set_rules('state', 'State', 'required');
		$this->form_validation->set_rules('district', 'District', 'required');
		$this->form_validation->set_rules('experience', 'Experience', 'required');
		$this->form_validation->set_rules('qualification', 'Qualification', 'required');
		$this->form_validation->set_rules('course', 'Course', 'required');
		$this->form_validation->set_rules('expected_salary', 'Expected Salary', 'required');

		// set form validation rules
		if ($this->form_validation->run() == FALSE)
		{
			$errors = array();
			// Loop through $_POST and get the keys
			foreach ($this->input->post() as $key => $value)
			{
				// Add the error message for this field
				$errors[$key] = form_error($key);
			}
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else
		{
			//aniket's work
			///for getting other_interest code

			$data['area_of_interest_list'] = $this->pramaan->get_area_of_interest_list();

			$i=1;
			$data['other_interest_code'] = '';
			foreach ($data['area_of_interest_list'] as $key )
			{
				if($key['name']=='Others')
				{
					$data['other_interest_code'] = $key['value'];
					break;
				}

				$i++;
			}

			$area_of_interest1 = $area_of_interest2 = $area_of_interest3 = 0;

			$area_of_interest1 = $this->input->post('area_of_interest1');
			if($this->input->post('area_of_interest2'))
			{
				$area_of_interest2 = $this->input->post('area_of_interest2');
			}

			if($this->input->post('area_of_interest3'))
			{
				$area_of_interest3 = $this->input->post('area_of_interest3');
			}

			if($this->input->post('area_of_interest1') == $data['other_interest_code'] || $area_of_interest2 ==	$data['other_interest_code'] || $area_of_interest3 == $data['other_interest_code'])
			{
				$other_interest = $this->input->post('other_interest');
			}
			else
			{
				$other_interest = 'N/A';
			}

			//to get other_course_code
			$data['other_course_code'] = $this->pramaan->get_other_course_code();

			$i=0;
			$c = array();
			if ($data['other_course_code'])
				if (count($data['other_course_code']) > 0)
				{
					foreach($data['other_course_code'] as $key)
					{
					  $c[$i] = $key->id;
					  $i++;
					}
				}

			$other_course = 'N/A';
			if (count($c) > 0)
				if ($this->input->post('course')==$c[0])
					$other_course = $this->input->post('other_course');

			if (count($c) > 1)
				if ($this->input->post('course')==$c[1])
					$other_course = $this->input->post('other_course');
			//Ends

			$questions= $this->input->post('questions');
			$associate_id=$this->input->post('associate_id');
			if($associate_id)
				$referer_id=$associate_id;
			else
				$referer_id=$user['id'];

			$dob=$this->input->post('date_of_birth');
			$dob=date('Y-m-d',strtotime($dob));
			$location_id=  $this->input->post('district');

			$data = array(
				'name' => $this->input->post('candidate_name'),
				'mobile' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'language_id' => $this->input->post('language_id'),
				'dob' => $dob,
				'gender_code' => $this->input->post('gender'),
				'address' => $this->input->post('address'),
				'state_id' => $this->input->post('state'),
				'district_id' => $location_id,
				'pincode' => $this->input->post('pincode'),
				'experience_id' => $this->input->post('experience'),
				'education_id' => $this->input->post('qualification'),
				'course_id' => $this->input->post('course'),
				'expected_salary_id' => $this->input->post('expected_salary'),

				//aniket's work
				'area_of_interest' => $area_of_interest1,
				'area_of_interest2' => $area_of_interest2,
				'area_of_interest3' => $area_of_interest3,
				'other_interest' => $other_interest,
				'other_course' => $other_course,
				 //ends
				'referer_id' => $referer_id
			);


			$is_aadhar=$this->input->post('is_aadhar');
			$data['is_aadhar']= $is_aadhar;

			if($is_aadhar=='t')
				$data['aadhaar_num']= $this->input->post('aadhar_number');
			else
			{
				$data['id_type']= $this->input->post('id_type');
				$data['id_number']=$this->input->post('id_number');
			}

			$relocate_status_code= $this->input->post('relocate_status_code');
			$data['relocate_status_code']=$relocate_status_code;

			$language_id=$this->input->post('language_id');
			$arr_string=implode(',',$language_id);
			$language_id= '{'.$arr_string.'}';

			$submit=$this->input->post('submit');
			$rec_sup_exec_rec=$this->db->query("SELECT ue.user_id from users.rs_executive ue
										left join (select rec_exec_id from users.candidates group by rec_exec_id) as uce on ue.user_id=uce.rec_exec_id
										left join users.rs_coordinator rc on rc.user_id=ue.rs_coordinator_id
										where $location_id=ANY(rc.district_id_list)");

			$rec_sup_exec_arr=array();
			if($rec_sup_exec_rec->num_rows())
			{
				foreach($rec_sup_exec_rec->result_array() as $key => $val)
				{
					$rec_sup_exec_arr[] = $val['user_id'];
				}
			}
			else
				$rec_sup_exec_arr[0]=0;

			$data['rec_exec_id']=$rec_sup_exec_arr[0];
			if($submit=='add')
			{
				$data['created_on']=date('Y-m-d');
				$data['language_id']=$language_id;

				$insert = $this->partner->do_add_candidate($data);
				if($insert)
				{
					echo json_encode(array('status'=>TRUE,'msg_info'=>'New candidate has been added'));
				}
				else
				{
					echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the data table')));
				}
			}
			else
			{
				$data['language_id']=$language_id;
				$candidate_id=$this->input->post('candidate_id');
				$data['modified_on']=date('Y-m-d');
				unset($data['referer_id']);
				$update = $this->partner->do_update_candidate($data,$candidate_id);
				if($update)
				{
					echo json_encode(array('status'=>TRUE,'msg_info'=>'Candidate has been updated'));
				}
				else
				{
					echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the data table')));
				}
			}
		}
	}

	public function candidates($associate_id=0)
	{
                $this->authorize(candidate_view_roles());
		$user=$this->pramaan->_check_module_task_auth(true);
		$data['page']='candidate_list';
		$data['title']='Available  Candidate';
		$data['module']="partner";
                $this->load->model('Candidate', 'my_candidate');
                $data['qualification_pack_options'] = $this->my_candidate->getQualificationPacks();
                $data['education_options'] = $this->my_candidate->getEducations();
		$associate_id=($associate_id)?$associate_id:$user['id'];
		$data['associate_id']=$associate_id;
//		$sr_partner_id=$this->pramaan->do_get_associate_id($associate_id);
		$region_id_rec=$this->db->query("SELECT ARRAY_TO_STRING(region_id_list,',')  AS region_id_list
											from users.vw_sr_rm
											WHERE sr_rm_id=(SELECT regional_manager_id from users.vw_district_coordinator
											WHERE dcor_user_id=(SELECT coordinator_id from users.vw_sourcing_partner
											WHERE sr_partner_id=?))",$associate_id);
		$region_id='0';
		if($region_id_rec->num_rows())
			$region_id=$region_id_rec->row()->region_id_list;
		$strQuery = "SELECT * FROM master.state WHERE region_id IN (" . $region_id . ") ORDER BY name";
		$data['state_list'] = $this->db->query($strQuery)->result_array();
//		$data['parent_page']="partner/associates/".$sr_partner_id;
		$data['parent_page_title']="Associates";
		$this->load->view('index',$data);
	}

    public function _candidates($associate_id=0,$response_msg)
	{
		/*echo $response_msg;
		die;
*/		$user=$this->pramaan->_check_module_task_auth(true);
		$data['page']='candidate_list';
		$data['title']='Available Candidates';
		$data['module']="partner";
		$associate_id=($associate_id)?$associate_id:$user['id'];
		$data['associate_id']=$associate_id;
		$data['response_msg']=$response_msg;

        /*$data['is_candidate_verified'] = $c;
*/
      //  print_r($data['is_candidate_verified']);

		$sr_partner_id=$this->pramaan->do_get_associate_id($associate_id);
		$data['parent_page']="partner/associates/".$sr_partner_id;
		$data['parent_page_title']="Associates";

		$this->load->view('index',$data);
	}
	/**
	 * Function to load job list
	 * @author Sangamesh.p@pramaan.in
	 */
	public function candidate_list($experience=0,$qualification=0,$search_by=0,$search_key='',$pg=0,$limit=25)
	{
            //echo $experience;

		$this->pramaan->_check_module_task_auth(true);
                
		$rep_data=$this->partner->get_candidate_list($experience,$qualification,$search_by,$search_key,$pg,$limit);

		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}

  public function candidate_list_search($associate_id=0,$experience=0,$qualification=0,$pg=0,$limit=25)
	{
		$this->pramaan->_check_module_task_auth(true);

			$rep_data=$this->partner->get_candidate_list($associate_id,0,$experience,$qualification,$pg,$limit);

		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}

	public function job_board()
	{
                $this->authorize(job_board_view_roles());
		$this->pramaan->_check_module_task_auth(true);
		$data=array();
		$data['qp_list'] = $this->db->query("SELECT id,TRIM(name) AS name FROM neo_master.qualification_packs ORDER BY name")->result_array();
		$data['education_list'] = $this->db->query("SELECT id,name FROM neo_master.educations ORDER BY id")->result_array();
		$data['status_list'] = $this->db->query("SELECT id,name FROM neo_master.job_statuses ORDER BY sort_order")->result_array();
		$data['page']='job_board';
		$data['title']='Job Board';
        $data['candidate_types']=['MTO', 'MTS'];
		$data['module']="partner";
		$data['partner_role'] = $this->partner->get_associate_role($this->session->userdata['usr_authdet']['id'])[0]['partner_role'];
		$this->load->view('index',$data);
	}

	public function job_board_list($job_status_id=0,$qp_id=0,$education_id=0,$pg=0,$limit=25)
	{                
		//error_reporting(E_ALL);
		$this->load->model("Employer_model","employer");
		$employer_id=0;

		$this->pramaan->_check_module_task_auth(true);

		$rep_data=$this->employer->get_job_list($job_status_id,$qp_id,$education_id,$pg,$limit,$this->session->userdata('usr_authdet')['id']);

		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}

	}



	public function application_tracker()
	{
                $this->authorize(application_tracker_roles());
		$this->pramaan->_check_module_task_auth(true);
		$data['page']='application_tracker';
		$data['title']='Application  Tracker';
		$data['module']="pramaan";
		$data['center_name'] = $this->partner->getCenterName();
		$data['batch_code'] = $this->partner->getBatchCode();
		$data['qualification_pack'] = $this->partner->getQualificationPack();
		$data['enrollment_no'] = $this->partner->getEnrollmentId();
		$this->load->view('index',$data);
	}

	public function application_tracker_list()
	{
		error_reporting(E_ALL);
		$user=$this->pramaan->_check_module_task_auth(true);
		$referrer_id=$user['id'];
		$requestData= $_REQUEST;
		$resp_data=$this->partner->get_applicationTracker_partner($requestData,$referrer_id);
		echo json_encode($resp_data);  // send data as json format
	}

	public function matching_jobs($candidate_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['page']='matching_jobs';
		$data['title']='Matching Jobs';
		$data['module']="partner";
		$result=$this->partner->get_candidate_detail($candidate_id);
		$data['candidate_detail']=$result[0];
		$sr_associate_id=$this->db->query("select referer_id from users.labournet_candidates where id=?",$candidate_id)->row()->referer_id;
		$data['parent_page']="partner/candidates/".$sr_associate_id;
		$data['parent_page_title']="Candidates";

		$this->load->view('index',$data);
	}

	public function matching_jobs_list($candidate_id='',$pg=0,$limit=10)
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$rep_data=$this->partner->get_matching_job_list($candidate_id,$pg,$limit);
		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}
	/*public function add_matching_jobs()
	{
		$this->pramaan->_check_module_task_auth(false);
		$screen_data=array();
		$job_ids=array();
		$candidate_id=$this->input->post('candidate_id');
		$job_ids=$this->input->post('job_id');

			if(count($job_ids)>0)
			{
				foreach ($job_ids as $jobid)
				{
					$created_on=date('Y-m-d');
					$insert_query="insert into job_process.candidate_jobs (candidate_id, job_id, status_id, q1_response,q2_response,created_on)
									select $candidate_id, $jobid, 1,1,1,'$created_on'
									where not exists (select * from job_process.candidate_jobs where candidate_id = $candidate_id and job_id = $jobid and status_id=1)";
					$this->db->query($insert_query);
				}
				$this->session->set_flashdata("notify_msg",'Screening Done!!!');
			}
		redirect('partner/screening_jobs/'.$candidate_id);
	}*/

	//public function add_matching_jobs()

	public function apply_for_matching_jobs()
	{
		$this->pramaan->_check_module_task_auth(false);
		$candidate_id=$this->input->post('candidate_id');
		$job_id=$this->input->post('job_id');
		$location_id=$this->input->post('location_id');
		$job_apply_status=1;
		$data['status']='error';
			if($job_id>0)
			{
				$q1=1;
				$q2=1;
					$is_applied=$this->partner->do_apply_for_job($candidate_id,$job_id,$location_id,$job_apply_status,$q1,$q2);

					if($is_applied)
					{
						$data['status']='success';
					}
			}
			echo json_encode($data);
	}

	public function screening_jobs($candidate_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$screen_data=array();
		$job_ids=array();
		$job_ids=$this->input->post('job_id');

		if(count($job_ids)>0)
		{
			foreach ($job_ids as $jobid)
			{

				$created_on=date('Y-m-d');
				$insert_query="insert into job_process.candidate_jobs (candidate_id, job_id, status_id, q1_response,q2_response,created_on)
								select $candidate_id, $jobid, 1,1,1,'$created_on'
								where not exists (select * from job_process.candidate_jobs where candidate_id = $candidate_id and job_id = $jobid and status_id=1)";
				$this->db->query($insert_query);
			}

		}
		$data['page']='screening_jobs';
		$data['title']='screened Jobs';
		$data['module']="partner";
		$result=$this->partner->get_candidate_detail($candidate_id);
		$data['candidate_detail']=$result[0];
		$this->load->view('index',$data);
	}

	public function screening_jobs_list($candidate_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$status_id=1;
		$rep_data=$this->partner->get_job_for_candidate_by_status($candidate_id,$status_id);

		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}

	public function scheduled_jobs($candidate_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['page']='scheduled_jobs';
		$data['title']='scheduled Jobs';
		$data['module']="partner";
		$result=$this->partner->get_candidate_detail($candidate_id);
		$data['candidate_detail']=$result[0];
		$this->load->view('index',$data);
	}
	public function scheduled_jobs_list($candidate_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$status_id=2;
		$rep_data=$this->partner->get_job_for_candidate_by_status($candidate_id,$status_id);
		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}

	public function add_matching_candidates()
	{
		$this->pramaan->_check_module_task_auth(false);
		$candidate_ids=array();
		$job_id=$this->input->post('job_id');
		$candidate_ids=$this->input->post('candidate_id');

			if(count($candidate_ids)>0)
			{
				foreach ($candidate_ids as $candidate_id)
				{
					/*$data['candidate_id']=$candidate_id;
					$data['job_id']=$jobid;
					$data['status_id']=0;
					$data['q1_response']=1;
					$data['q2_response']=1;
					$data['created_on']=date('Y-m-d');*/
					//$this->db->insert('job_process.candidate_jobs',$data);
					$created_on=date('Y-m-d');
					$insert_query="INSERT into job_process.candidate_jobs (candidate_id, job_id, status_id, q1_response,q2_response,created_on)
						SELECT $candidate_id, $job_id, 1,1,1,'$created_on'
						WHERE not exists (SELECT * FROM job_process.candidate_jobs where candidate_id = $candidate_id and job_id = $job_id and status_id=1)";
					$this->db->query($insert_query);

				}
				$this->session->set_flashdata("notify_msg",'Screening Done!!!');
			}
		redirect('partner/screening_candidates/'.$job_id);
	}

	public function schedule_candidates($job_id=0,$location_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['page']='schedule_candidates';
		$data['title']='schedule Candidates';
		$data['module']="partner";
		$result=$this->partner->get_job_detail($job_id,$location_id);
		$data['job_details']=$result[0];
		$this->load->view('index',$data);
	}

	public function schedule_candidates_list($job_id=0,$location_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$rep_data=$this->partner->get_screenedcandidates_for_job($job_id,$location_id);
		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}

	public function get_jobstatus_list($job_status=0)
	{
		$data=array();
		$data['status']=false;
		$user=$this->pramaan->_check_module_task_auth(true);
		if($user['user_group_id']==3)
		{
			$job_status=3;
            $cond= " where abs(value)<=".$job_status;
			$result=$this->db->query("select value,name FROM  master.status $cond");
		}
		else
			$result=$this->db->query("select value,name FROM  master.status");

		if($result->num_rows())
		{
			$data['status']=true;
			$data['job_status_list']=$result->result_array();
		}

		echo json_encode($data);
	}

	public function matching_candidates($job_id=0,$location_id=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['page']='matching_candidates';
		$data['title']='Matching Jobs';
		$data['module']="partner";
		if(!$job_id)
		{
			$job_id=$this->input->post('job_id');
			$location_id=$this->input->post('location_id');
		}
		$result=$this->partner->get_job_detail($job_id,$location_id);
		$data['job_details']=$result[0];
		$data['location_id']=$location_id;
		$this->load->view('index',$data);
	}

	public function matching_candidate_list($job_id='',$location_id='',$pg=0,$limit=25)
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$result=$this->db->query('select min_qualification_id, min_experience,max_experience from job_process.jobs where id=?',$job_id)->row_array();

		$education_id=$result['min_qualification_id'];
		$min_experience=$result['min_experience'];
		$max_experience=$result['max_experience'];
		$partner_id=$user['id'];

		$associate_result=$this->db->query('select user_id from users.associates where partner_id=?',$partner_id);
		if($associate_result->num_rows())
			$associate_id=$associate_result->row()->user_id;
		else
			$associate_id=0;
		if($user['user_group_id']==3)
			$rec_exec_id=0;
		else
			$rec_exec_id=$user['id'];
		$rep_data=$this->partner->get_matching_candidate_list($rec_exec_id,$education_id,$partner_id,$job_id,$location_id,$min_experience,$max_experience,$pg,$limit);
		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}


	public function update_screened_job_status()
	{
		$screened_job_id=$this->input->post('screened_job_id');
		$status_id=$this->input->post('selected_status');
		$q1_response=$this->input->post('q1_response_status');
		$q2_response=$this->input->post('q2_response_status');

		$data['status']='error';
		$data['msg_info']="!! Error while saving the status";
		if ($this->input->is_ajax_request())
		{
			$result=$this->db->query("update job_process.candidate_jobs set status_id=?,q1_response=?,q2_response=? where id=?",array($status_id,$q1_response,$q2_response,$screened_job_id));

			if($this->db->affected_rows())
			{
				$data['status']='success';
				$data['msg_info']="Status has been updated";
			}
		}
		echo json_encode($data);
	}

	public function update_screened_candidate_status()
	{
		$candidate_job_id=$this->input->post('candidate_job_id');
		$status_id=$this->input->post('selected_status');

		$data['status']=true;
		$data['msg_info']="!!Error while saving the status";
		if ($this->input->is_ajax_request())
		{
			$update_data['status_id']=$status_id;
			$this->db->update('job_process.candidate_jobs', $update_data, array('id' => $candidate_job_id));
			if($this->db->affected_rows())
			{
				$data['status']=true;
				$data['msg_info']="Status has been updated";
			}
		}
		echo json_encode($data);
	}

	/*function send_mail()
	{
		$to_email="sansb2008@gmail.com";
		$subject="User Registration";
		$message="User Name:sansb2008<br>";
		$message.="Password:12345<br>";
		$message.="You can login: <b>eauctionbanks.com</b><br>";
		$message.="For Enquiry contact at:eauctionbankz@gmail.com</b><br>";
		$this->partner->send_email($to_email,$subject,$message);
	}*/

	public function tracked_candidate_jobs($candidate_id=0,$job_status=0)
	{
		//$this->pramaan->_check_module_task_auth(false);
		$tracked_results=$this->partner->get_tracked_candidate_jobs($candidate_id,$job_status);
		echo json_encode($tracked_results);
	}
	public function tracked_candidates_employerjob($customer_id=0,$job_status=0)
	{
		//$this->pramaan->_check_module_task_auth(false);
		$tracked_results=$this->partner->get_candidates_byemployerjob($customer_id,$job_status);
		echo json_encode($tracked_results);
	}
        
        
        public function tracked_candidates_center($center_name,$job_status_id)
	{
		//$this->pramaan->_check_module_task_auth(false);
		$tracked_results=$this->partner->get_candidates_bycenter($center_name, $job_status_id);
		echo json_encode($tracked_results);
	}

	public function jobs_by_employer($employer_id=0)
	{
		//$this->pramaan->_check_module_task_auth(false);
		$tracked_results=$this->partner->get_jobs_by_employer($employer_id);
		echo json_encode($tracked_results);
	}

	function add_employer($bd_exec_id=0)
	{
		//$data['sector_list']=$this->db->query("select value as id,name from master.list where code='L0002'")->result_array();
		$user=$this->pramaan->_check_module_task_auth(true);
		$data['sector_list']=$this->db->query("select id, name from master.sector")->result_array();
		$data['org_list']=$this->db->query("select value,name from master.list where code='L0016'")->result_array();
		$data['page']='add_employer';
		$data['title']='Add Employer';
		$data['module']="partner";
		if(!$bd_exec_id)
			$bd_exec_id=$user['id'];
		$data['bd_exec_id']=$bd_exec_id;
		$this->load->view('index',$data);
	}

	/**
	* function for save employer details
	*/
	function save_employer()
	{
		$user=$this->pramaan->_check_module_task_auth(true);			//return:true,falsse
			$this->load->library('form_validation');
			$this->form_validation->set_rules('employer_name', 'Employer Name', 'required');

			$this->form_validation->set_rules('phone','Phone','required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
			/*$this->form_validation->set_rules('spoc_phone','SPOC Phone','required|callback__valid_phone|max_length[10]|callback__unique_user_phone');*/
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

			$this->form_validation->set_rules('sector', 'Sector', 'required');
			$this->form_validation->set_message('org_type','Organization type','required');

			 // set form validation rules
			if ($this->form_validation->run() == FALSE)
			{
				$errors = array();
        		// Loop through $_POST and get the keys
		        foreach ($this->input->post() as $key => $value)
		        {
		            // Add the error message for this field
		            $errors[$key] = form_error($key);
		        }
				echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
			}
			else
			{
				$spoc_name="spoc_name";
				$spoc_phone="7656565211";

				$id=$this->input->post('id');
				$data = array(
				'name'=>$this->input->post('employer_name'),
				'spoc_name' => $spoc_name,
				'phone' => $this->input->post('phone'),
				'spoc_phone' => $spoc_phone,
				'email' => $this->input->post('email'),
				'password' => "pass123",
				'sector_id' => $this->input->post('sector'),
				'organization_type_id' => $this->input->post('org_type'),
				'created_by'=>$this->input->post('bd_exec_id')
				);

				if(!$id)
				{
					$insert = $this->partner->do_add_employer($data);
					if($insert)
					{
						echo json_encode(array('status'=>TRUE,'msg_info'=>'Employer has been added'));
					}
					else
					{
						echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the data table')));
					}
				}
				else
				{
					$id=$this->input->post('id');
					$data1 = array(
									'name'=>$this->input->post('employer_name'),
									'spoc_name' =>  $spoc_name,
									'spoc_phone' => $spoc_phone,
									'phone' => $this->input->post('phone'),
									'sector_id' => $this->input->post('sector'),
									'organization_type_id' => $this->input->post('org_type'),

									);
					$data2 = array('email' => $this->input->post('email'));

					$update = $this->partner->do_update_employer($data1,$data2,$id);
					if($update)
					{
						echo json_encode(array('status'=>TRUE,'msg_info'=>'Employer has been updated succesfully'));
					}
					else
					{
						echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the data table')));
					}
				}

			}
	}

	public function employers($bd_exec_id=0)
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$data['page']='employers_list';
		$data['title']='Employers list';
		$data['module']="partner";
		$data['sector_list']=$this->db->query("select id, name from master.sector")->result_array();
		$data['org_list']=$this->db->query("select value,name from master.list where code='L0016'")->result_array();

		if(!$bd_exec_id)
			$bd_exec_id=$user['id'];
		$data['bd_exec_id']=$bd_exec_id;

		$data['user_group_id'] = $user['user_group_id'];

		$sr_coordinator_id=$this->pramaan->do_get_parent_id($bd_exec_id);
		$data['parent_page']="pramaan/bd_coordinators/".$sr_coordinator_id;
		$data['parent_page_title']="BD Coordinators";
		$this->load->view('index',$data);
	}

	public function employers_list($bd_exec_id=0)
	{
		error_reporting(E_ALL);
		$this->pramaan->_check_module_task_auth(false);
		$requestData= $_REQUEST;
		$resp_data=$this->partner->get_employers_list($requestData,$bd_exec_id);
		echo json_encode($resp_data);  // send data as json format
	}

	/**
	*function for employers
	*/
	public function employers_tracker($bd_exec_id=0)
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$data['page']='employers_tracker';
		$data['title']='Employers Trackers';
		$data['module']="partner";
		if(!$bd_exec_id)
			$bd_exec_id=$user['id'];
		$data['bd_exec_id']=$bd_exec_id;
		$this->load->view('index',$data);
	}

	/**
	 * function for employers list
	 */
	public function employers_tracker_list($user_id=0)
	{
		error_reporting(E_ALL);
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		if(!$user_id)
			$user_id = $user['id'];
		$resp_data=$this->partner->get_employers_tracker_new($requestData,$user_id);
		echo json_encode($resp_data);  // send data as json format
	}
        
        
        /**
	 * function for center list
	 */
        
        public function center_tracker_list($user_id=0)
	{
		error_reporting(E_ALL);
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		if(!$user_id)
			$user_id = $user['id'];
		$resp_data=$this->partner->get_center_tracker_new($requestData,$user_id);
		echo json_encode($resp_data);  // send data as json format
	}



//Aniket's Work
   public function validate_Interest_Limit()
   {

    $interest_length = $this->input->post('area_of_interest').length();

    if($interest_length>3)
    {

$this->form_validation->set_message('validate_Interest_Limit' , 'Please select maximum of 3 interests.');


return false;
    }
    else{
    	return true;
    }

   }

    /* Saurabh Sinha's work starts here */



    public function verify_id()
	{

     	$id_type=$this->input->post("info[0]");
     	if($id_type=="Adhaar")
     	{
     		$id_name=$this->input->post("info[1]");
     		$id_dob=$this->input->post("info[2]");
     		$id_aadhaar_number=$this->input->post("info[4]");
     		/*echo $id_name." ".$id_dob." ".$id_aadhaar_number;*/
     		//getting the data here
     		$dob_ar=explode('/',$id_dob);
    		$dob = $dob_ar[0]."-".$dob_ar[1]."-".$dob_ar[2];
        	$data=array();
        	$data['name'] = $id_name;
        	$data['mobile']="1111111111";
        	$data['aadhaarNo']=$id_aadhaar_number;
        	$data['gender']="MALE";
        	$data['dobType']="date";
        	$data['dob']=$dob;
        	$data['biometric']="false";

        	$json_data=json_encode($data);
/*
        	echo $json_data;
        	         	*/

        	$url=AADHAAR_URL;
		    /*$data=file_get_contents('rest.json');*/
		    //echo $data;
		/*    die();*/
		 	if($this->partner->CallAPI("POST",$url,$json_data,$id_aadhaar_number,$id_name))
		 	{
		 		$this->partner->change_aadhaar_status($id_aadhaar_number);
				/*$success_msg=$id_name."'s Aadhaar has been verified!!";*/
				/*redirect('/partner/candidates/'+$response_msg);*/
				/*print_r($this->session->userdata['usr_authdet']['id']);
				die;
				$ass=$this->session->userdata['usr_authdet']['id'];
                $this->_candidates($ass,$success_msg);*/
				//redirect('/partner/candidates');
				echo json_encode(array('status'=>1,'msg_info'=>'Aadhaar verified'));
		 	}
		 	else
		 	{
		 		/*$response_msg=$id_name."'s Aadhaar has not been matched or Server error!! Try again!!";*/
				/*redirect('/partner/candidates/'+$response_msg);*/
				/*print_r($this->session->userdata['usr_authdet']['id']);
				die;*/
				/*$ass=$this->session->userdata['usr_authdet']['id'];
                $this->_candidates($ass,$response_msg);*/
                echo json_encode(array('status'=>0,'msg_info'=>$id_name.'\'s Aadhaar not matched'));
		 	}

		 	/*header('Refresh: 5;url=Aadhaar.php');*/


     	}
     	else if($id_type=="PAN")
     	{

     		$id_name=$this->input->post("name");
     		$id_dob=$this->input->post("dob");
     		$id_number=$this->input->post("id_number");
     		/*echo $id_name." ".$id_dob." ".$id_number;*/
     		$url=PAN_URL.$id_number;
		 	if($this->partner->CallAPIPAN("GET",$url,$id_name))
		 	{
		 		$this->partner->change_id_status($id_number);
				redirect('/partner/candidates');
		 	}
		 	else
		 	{
		 		redirect('/partner/candidates');
		 	}



     	}
     	else if($id_type=="Passport")
     	{
     			redirect('/partner/candidates');
     	}
    }

    /* Saurabh Sinha's work ends here */

    public function spoc_list($partner_id=0)
    {
    	$user=$this->pramaan->_check_module_task_auth(true);
		$cond='';
		$data['page']='spoc_list';
		$data['title']='Sourcing partner admin';
		$data['module']="partner";
		if(!$partner_id)
			$partner_id=$user['id'];
		$data['parent_id']=$partner_id;
		$cond="where created_by=".$partner_id;
		$data['spoc_list']=$this->db->query("select * from users.spoc $cond order by name ")->result_array();
		$sr_partner_id=$this->pramaan->do_get_sourcing_partner_id($partner_id);
		$data['parent_page']="pramaan/sourcing_partner/".$sr_partner_id;
		$data['parent_page_title']="Sourcing Partner";
		$this->load->view('index',$data);
    }

    public function get_spoc_list($user_id=0)
    {
    	error_reporting(E_ALL);
		$user=$this->pramaan->_check_module_task_auth(true);
		$resp_data=$this->pramaan->do_get_spoc_list($user_id);
		echo json_encode($resp_data);  // send data as json format
    }

    public function add_spoc($parent_id=0)
    {
    	$user=$this->pramaan->_check_module_task_auth(true);
		$data['page']='add_spoc';
		$data['title']='Add sourcing partner admin';
		$data['module']='partner';

		if(!$parent_id)
		{
			$parent_id=$user['id'];
			$data['parent_page']="";
			$data['parent_page_title']="";
		}
		else
		{
			$sr_admin_id=$this->pramaan->do_get_parent_id($parent_id);
			$data['parent_page']="pramaan/sourcing_admins/".$sr_admin_id;
			$data['parent_page_title']="Sourcing Admins";
		}
		$data['parent_id']=$parent_id;
		$user_group_id = 23;
		//$data['dis_list']=$this->db->query('select * from master.district')->result_array();
		$data['user_group_id']=$user_group_id;
		/*$data['s_list']=$this->db->query('select * from users.sourcing_head')->result_array();*/
		$this->load->view('index',$data);
    }

    public function save_spoc()
    {
    	$user=$this->pramaan->_check_module_task_auth(true);			//return:true(returns user detail)
		$password=$this->input->post('password');
		$submit=$this->input->post('submit');
		$user_group_id=$this->input->post('user_group_id');
		$this->load->library('form_validation');
		$this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
		/*$this->form_validation->set_rules('phone','Phone','required|max_length[10]|callback__unique_partner_phone');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');
		*/
		if($submit=='add')
		{
			$this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
			$this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
		}
		if ($this->form_validation->run() == FALSE)
		{

			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($this->input->post() as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else
		{
			$data = array(
				'name' => $this->input->post('pname'),
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'created_by'=>$this->input->post('parent_id')

				);
			if($submit=='add')
			{
				$data['password']=$password;
				$data['parent_id']=$this->input->post('parent_id');

				$data['user_group_id']=$this->input->post('user_group_id');


				$insert = $this->pramaan->do_add_spoc($data);
				if($insert)
				{
					echo json_encode(array('status'=>TRUE,'msg_info'=>'New Sourcing Partner Admin has been added succesfully'));
				}
				else
				{
					echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the saving the data')));
				}
			}
			else
			{
				/*$data1= array(
								'name' => $this->input->post('pname'),
								'phone' => $this->input->post('phone')
							   );
				$data2= array('email' => $this->input->post('email'));
				$where1=array('user_group_id'=>$user_group_id);
				$where2=array('id'=>$user_id);
				$update = $this->pramaan->do_update_user_admin($data1,$data2,$where1,$where2);
				if($update)
				{
					echo json_encode(array('status'=>TRUE,'msg_info'=>'User has been updated succesfully'));
				}
				else
				{
					echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the saving the data')));
				}*/
			}
		}
    }
//Ends
    public function spoc_center($partner_id=0)
	{
		$partner_id=$this->session->userdata['usr_authdet']['id'];
		$sourcing_partner_id=$this->db->query('select * from users.spoc where user_id='.$partner_id.'')->result()[0]->created_by;
		$user=$this->pramaan->_check_module_task_auth(true);
		$data['page']='center_list';
		$data['title']='Center list';
		$data['module']="partner";
		if($sourcing_partner_id)
			$data['partner_id']=$sourcing_partner_id;
		else
			$data['partner_id']='';
		$this->load->view('index',$data);
	}
	public function spoc_associates($partner_id=0)
	{
		$partner_id=$this->session->userdata['usr_authdet']['id'];
		$sourcing_partner_id=$this->db->query('select * from users.spoc where user_id='.$partner_id.'')->result()[0]->created_by;
		$user=$this->pramaan->_check_module_task_auth(true);
		$cond='';
		$data['page']='associates_list';
		$data['title']='Associates list';
		$data['module']="partner";
		if(!$sourcing_partner_id)
			$sourcing_partner_id=$user['id'];
		$data['partner_id']=$sourcing_partner_id;
		$cond="where partner_id=".$sourcing_partner_id;
		$data['center_list']=$this->db->query("select id, name from users.centers $cond order by name ")->result_array();
		$sr_partner_id=$this->pramaan->do_get_sourcing_partner_id($partner_id);
		$data['parent_page']="pramaan/sourcing_partner/".$sr_partner_id;
		$data['parent_page_title']="Sourcing Partner";
		$this->load->view('index',$data);
	}

	public function edit_spoc($parent_id,$user_id)
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['page']='edit_spoc';
		$data['module']='partner';
		/*$data['parent_page']="pramaan/sourcing_heads/".$parent_id;
		$data['parent_page_title']="Sourcing Heads";
		$data['title']='Sourcing Head Registration';*/
		$data['title']='Edit Spoc';
		$data['parent_id']=$parent_id;
		$data['department_id']=SOURCING;
		$user_group_id = 23;
		$data['parent_id']=$parent_id;
		$data['user_id']=$user_id;
		$data['user_group_id']=$user_group_id;


		//$data['regions_list']=$this->pramaan->do_get_regions();
		//get heading_soruce info here
		$data['sourcing_partner_admin_info']=$this->pramaan->get_sourcing_partner_admin_information($user_id);

		$this->load->view('index',$data);
	}
	public function edit_sourcing_partner_admin_update()
	{
		$user=$this->pramaan->_check_module_task_auth(true);			//return:true(returns user detail)
		$user_id=$this->input->post('user_id');
		$name=$this->input->post('pname');
		$phone=$this->input->post('phone');
		$email=$this->input->post('email');
		$submit=$this->input->post('submit');

		/*$password=$this->input->post('password');

		$cpassword=$this->input->post('cpassword');*/
		/*echo json_encode($user_id);*/

		$this->load->library('form_validation');
		$this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
		$this->form_validation->set_rules('phone','Phone','required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
		/*$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');
		*/

		if ($this->form_validation->run() == FALSE)
		{

			$errors = array();
    		// Loop through $_POST and get the keys
	        foreach ($this->input->post() as $key => $value)
	        {
	            // Add the error message for this field
	            $errors[$key] = form_error($key);
	        }
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else
		{
			$data = array(
				'name' => $this->input->post('pname'),
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'user_id'=>$user_id
				 /* 'sourcing_admin_id'=> $sourcing_admin_id,*/
				/*  'parent_id' => $this->input->post('parent_id'),
				  'user_id'=>$this->input->post('user_id'),
				  'user_group_id'=>$this->input->post('user_group_id')*/
				);
			if($submit=='edit')
			{
					/*echo json_encode(array('status'=>$this->input->post('user_group_id'),'msg_info'=>''));	*/

				if($this->pramaan->do_edit_sourcing_partner_admin($data)==true)
				{
					echo json_encode(array('status'=>TRUE,'msg_info'=>'Sourcing Partner Admin updated succesfully'));
				}
				else
				{
					echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the saving the data')));
				}
				/*

				$update = $this->pramaan->do_edit_sourcing_head($data);
				if($update)
				{
					echo json_encode(array('status'=>TRUE,'msg_info'=>'New Sourcing head has been added succesfully'));
				}
				else
				{
					echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the saving the data')));
				}*/
			}
			/*else
			{
				$data1= array(
								'name' => $this->input->post('pname'),
								'phone' => $this->input->post('phone'),
								'address'=>""
							   );
				$data2= array('email' => $this->input->post('email'));
				$where1=array('user_id'=>$user_id);
				$where2=array('id'=>$user_id);
				$update = $this->pramaan->do_update_sourcing_head($data1,$data2,$where1,$where2);
				if($update)
				{
					echo json_encode(array('status'=>TRUE,'msg_info'=>'Sourcing Head has been updated succesfully'));
				}
				else
				{
					echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the saving the data')));
				}
			}*/
		}
	}

	//Saurabh Sinha's work starts here
	public function change_spoc_status()
	{
		$sourcing_head_id=$this->input->post("ar[0]");

		if($this->pramaan->change_spoc_status($sourcing_head_id))
			{
				echo json_encode(array("status"=>true,"msg_info"=>"Status changed."));
			}
		else
		{

				echo json_encode(array("status"=>false,"msg_info"=>"Status not changed."));

		}
	}

	public function edit_employers($parent_id=0,$emp_id=0)
	{
		/*echo $sourcing_head_id;
				die;*/
				$this->pramaan->_check_module_task_auth(false);
				$data['page']='edit_employers';
				$data['module']='partner';
				$data['title']='Edit Employer';
				$data['parent_id']=$parent_id;
				/*$data['department_id']=BD;*/
				/*$user_group_id=$this->db->query("SELECT value from master.list
											where code='L0001' and lower(name)=?",'employers')->row()->value;*/

				$data['emp_id']=$emp_id;
				$data['emp_name']=$this->db->query('select * from users.employers where user_id='.$emp_id.'')->result()[0]->name;
				$data['emp_email']=$this->db->query('select * from users.accounts where id='.$emp_id.'')->result()[0]->email;
				$data['emp_phone']=$this->db->query('select * from users.employers where user_id='.$emp_id.'')->result()[0]->phone;
				$data['spoc_name'] = $this->db->query('select * from users.employers where user_id='.$emp_id.'')->result()[0]->spoc_name;
				$data['spoc_phone'] = $this->db->query('select * from users.employers where user_id='.$emp_id.'')->result()[0]->spoc_phone;
				$this->load->view('index',$data);
	}

	public function edit_employer_update()
	{

		$user=$this->pramaan->_check_module_task_auth(true);			//return:true(returns user detail)

			$this->load->library('form_validation');
		$this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
		$this->form_validation->set_rules('phone','Phone','required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
		$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');



		/*$password=$this->input->post('password');

		$cpassword=$this->input->post('cpassword');*/
		/*echo json_encode($user_id);*/


		if ($this->form_validation->run() == FALSE)
		{

			$errors = array();
			// Loop through $_POST and get the keys
			foreach ($this->input->post() as $key => $value)
			{
				// Add the error message for this field
				$errors[$key] = form_error($key);
			}
			echo json_encode(array('status'=>FALSE, 'errors' =>$errors));
		}
		else {


			$parent_id = $this->input->post('parent_id');
			$name = $this->input->post('pname');
			$phone = $this->input->post('phone');
			$email = $this->input->post('email');
			$emp_id = $this->input->post('emp_id');

			$submit = $this->input->post('submit');

			$data = array(
				'name' => $this->input->post('pname'),
				'spoc_name' => $this->input->post('contact_name'),
				'spoc_phone' => $this->input->post('spoc_phone'),
				'phone' => $this->input->post('phone'),
				'email' => $this->input->post('email'),
				'parent_id' => $this->input->post('parent_id'),
				'district' => $this->input->post('district'),
				'modified_on' => date('Y-m-d'),
				'modified_by' => $user['id'],
				'emp_id' => $emp_id
			);

			//print_r($this->pramaan->do_edit_employers($data));
			if ($submit == 'edit') {


				if ($this->pramaan->do_edit_employers($data) == true) {
					echo json_encode(array('status' => TRUE, 'msg_info' => 'Employer has been updated succesfully!!'));
				} else {
					echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
				}
			}
		}
	}

    public function download_candidate_list_sample()
    {
        $path = CANDIDATE_LIST.'NeoJobsBulkUploadCandidateTemplate.xlsx';
        header('Content-Disposition: attachment; filename=' . urlencode(basename($path)));
        header("Content-Transfer-Encoding: binary");
       // header('Content-Type: application/pdf');
        header("Content-Type: application/octet-stream");
        header("Content-Description: File Transfer");
        header('Expires: 0');
        header('Pragma: public');
        header("Content-Length: " . Filesize(CANDIDATE_LIST.urlencode(basename($path))));
        echo file_get_contents(CANDIDATE_LIST.urlencode(basename($path)));
    }

	public function upload_candidate_list()
    {
        $user=$this->pramaan->_check_module_task_auth(true);

		if(isset($_FILES['candidate_list']))
		{
			$this->load->library('Excel');
			try
			{
				$objPHPExcel = PHPExcel_IOFactory::load($_FILES['candidate_list']['tmp_name']);
			}
			catch (Exception $e)
			{
				print "Error while uploading file!";
				return;
			}

			$allDataInSheet = $objPHPExcel->getSheetByName("Data")->toArray(null, true, true, true);
			array_shift($allDataInSheet);
			//$nexec = count(0);
			$i = 1;
			$insert_count = 0;

			$MessageList = array();
			$Counter = 0;
			$ValidRowCount = 0;

			$ActualRecordCount = count($allDataInSheet) - 2;
			if ($ActualRecordCount <= 5000) {
				foreach ($allDataInSheet as $import)
				{
					$ErrorCount = 0;
					$Counter++;
					//$indx = $i % ($nexec);
					if ($Counter < 2) continue;
					$ValidRowCount++;

					//GENDER
					$GenderId = 0;
					$GenderName = $import['D'];
					$strQuery = "SELECT id FROM neo_master.genders WHERE UPPER(name)='" . strtoupper(trim($GenderName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $GenderId = $query_result->row()->id;

					if ($GenderName != '' && $GenderId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Gender", "error_type" => "Invalid"));
					}

					//RELIGION
					/*$ReligionId = 0;
                    $ReligionName = $import['I'];
                    $strQuery = "SELECT id FROM master.candidate_religion WHERE UPPER(name)='" . strtoupper(trim($ReligionName)) . "'";
                    $query_result = $this->db->query($strQuery);
                    if($query_result->num_rows()) $ReligionId = $query_result->row()->id;

                    if ($ReligionName != '' && $ReligionId < 1)
                    {
                        $ErrorCount++;
                        array_push($MessageList, array( "column" => "Religion", "error_type" => "Invalid"));
                    }*/

					//CATEGORY
					/*$CategoryId = 0;
                    $CategoryName = $import['L'];
                    $strQuery = "SELECT id FROM master.caste_category WHERE UPPER(name)='" . strtoupper(trim($CategoryName)) . "'";
                    $query_result = $this->db->query($strQuery);
                    if($query_result->num_rows()) $CategoryId = $query_result->row()->id;
                    if ($CategoryName != '' && $CategoryId < 1)
                    {
                        $ErrorCount++;
                        array_push($MessageList, array( "column" => "Category", "error_type" => "Invalid"));
                    }*/

					//MARITAL STATUS
					/*$MaritalStatusId = 0;
                    $MaritalStatusName = $import['M'];
                    $strQuery = "SELECT id FROM master.marital_status WHERE UPPER(name)='" . strtoupper(trim($MaritalStatusName)) . "'";
                    $query_result = $this->db->query($strQuery);
                    if($query_result->num_rows()) $MaritalStatusId = $query_result->row()->id;
                    if ($MaritalStatusName != '' && $MaritalStatusId < 1)
                    {
                        $ErrorCount++;
                        array_push($MessageList, array( "column" => "Marital Status", "error_type" => "Invalid"));
                    }*/

					//COUNTRY
					$CountryId = 0;
					$CountryName = $import['U'];
					$strQuery = "SELECT id FROM neo_master.country WHERE UPPER(name)='" . strtoupper(trim($CountryName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $CountryId = $query_result->row()->id;
					if ($CountryName != '' && $CountryId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Country", "error_type" => "Invalid"));
					}

					//STATE
					$StateId = 0;
					$StateName = $import['T'];
					$strQuery = "SELECT id FROM neo_master.states WHERE UPPER(name)='" . strtoupper(trim($StateName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $StateId = $query_result->row()->id;
					if ($StateName != '' && $StateId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "State", "error_type" => "Invalid"));
					}

					//DISTRICT
					$DistrictId = 0;
					$DistrictName = $import['S'];
					$strQuery = "SELECT id FROM neo_master.districts WHERE UPPER(name)='" . strtoupper(trim($DistrictName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $DistrictId = $query_result->row()->id;
					if ($DistrictName != '' && $DistrictId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "District", "error_type" => "Invalid"));
					}

					//QUALIFICATION PACK
					$QualificationPackId = 0;
					$QualificationPackName = $import['Z'];
					$strQuery = "SELECT id FROM neo_master.qualification_packs WHERE UPPER(name)='" . strtoupper(trim($QualificationPackName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $QualificationPackId = $query_result->row()->id;
					if ($QualificationPackName != '' && $QualificationPackId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Qualification Pack", "error_type" => "Invalid"));
					}

					//EDUCATION
					$EducationId = 0;
					$EducationName = $import['AG'];
					$strQuery = "SELECT id FROM neo_master.educations WHERE UPPER(name)='" . strtoupper(trim($EducationName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $EducationId = $query_result->row()->id;
					if ($EducationName != '' && $EducationId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Education", "error_type" => "Invalid"));
					}

					//WORK AUTHORIZATION
					$WorkAuthorizationId = 0;
					$WorkAuthorizationName = $import['J'];
					$strQuery = "SELECT id FROM neo_master.work_authorizations WHERE UPPER(name)='" . strtoupper(trim($WorkAuthorizationName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $WorkAuthorizationId = $query_result->row()->id;
					if ($WorkAuthorizationName != '' && $WorkAuthorizationId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Work Authorization", "error_type" => "Invalid"));
					}

					//INDUSTRY
					$IndustryId = 0;
					$IndustryName = $import['K'];
					$strQuery = "SELECT id FROM neo_master.industries WHERE UPPER(name)='" . strtoupper(trim($IndustryName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $IndustryId = $query_result->row()->id;
					if ($IndustryName != '' && $IndustryId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Industry", "error_type" => "Invalid"));
					}

					//SOURCE
					$SourceId = 0;
					$SourceName = $import['L'];
					$strQuery = "SELECT id FROM neo_master.candidate_sources WHERE UPPER(name)='" . strtoupper(trim($SourceName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $SourceId = $query_result->row()->id;
					if ($SourceName != '' && $SourceId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Source", "error_type" => "Invalid"));
					}

					//LEARNING TYPE
					$LearningTypeId = 0;
					$LearningTypeName = $import['AN'];
					$strQuery = "SELECT id FROM neo_master.learning_types WHERE UPPER(name)='" . strtoupper(trim($LearningTypeName)) . "'";
					$query_result = $this->db->query($strQuery);
					if ($query_result->num_rows()) $LearningTypeId = $query_result->row()->id;
					if ($LearningTypeName != '' && $LearningTypeId < 1) {
						$ErrorCount++;
						array_push($MessageList, array("column" => "Learning Type", "error_type" => "Invalid"));
					}

					//MOBILE NUMBER
					/*$DuplicateCount = 0;
                                        $MobilePhone = trim($import['O']);
                                        $strQuery = "SELECT COUNT(id) AS count FROM neo.candidates WHERE TRIM(mobile)='$MobilePhone'";
                                        $query_result = $this->db->query($strQuery);
                                        if ($query_result->num_rows()) $DuplicateCount = $query_result->row()->count;

                                        if ($DuplicateCount > 0) {
                                            $ErrorCount++;
                                            array_push($MessageList, array("column" => "Mobile", "error_type" => "Duplicate"));
                                        }*/

					//EMAIL ADDRESS
					/*$DuplicateCount = 0;
                                        $EmailAddress = trim($import['N']);
                                        $strQuery = "SELECT COUNT(id) AS count FROM neo.candidates WHERE TRIM(email)='$EmailAddress'";
                                        $query_result = $this->db->query($strQuery);
                                        if ($query_result->num_rows()) $DuplicateCount = $query_result->row()->count;

                                        if ($DuplicateCount > 0) {
                                            $ErrorCount++;
                                            array_push($MessageList, array("column" => "Email", "error_type" => "Duplicate"));
                                        }*/

                                        /*$salutation = '';
                                        if ($GenderId=='1') {
                                            $salutation= "Mr.";
                                        } elseif ($GenderId=='2') {
                                            $salutation= "Ms.";
                                        }*/
                                       $created_at=date("Y-m-d H:i:s");
					$CandidateId = 0;

					$data = array(
						'candidate_name' => $import['B'],
						'candidate_number' => $import['C'],
						'gender_id' => $GenderId,
						'gender_name' => $import['D'],
						'prefered_job_location' => $import['E'],
						'expected_salary_percentage' => $import['F'],
						'date_of_birth' => $import['G'],
						'age' => $import['H'],
						'nationality' => $import['I'],
						'work_authorization_id' => $WorkAuthorizationId,
						'work_authorization_name' => $import['J'],
						'industry_id' => $IndustryId,
						'industry_name' => $import['K'],
						'source_id' => $SourceId,
						'source_name' => $import['L'],
						'remarks' => $import['M'],
						'email' => $import['N'],
						'mobile' => $import['O'],
						'landline' => $import['P'],
						'address' => $import['Q'],
						'city' => $import['R'],
						'district_id' => $DistrictId,
						'district' => $import['S'],
						'state_id' => $StateId,
						'state' => $import['T'],
						'country_id' => $CountryId,
						'country' => $import['U'],
						'current_location' => $import['V'],
						'linkedin_url' => $import['W'],
						'facebook_url' => $import['X'],
						'twitter_url' => $import['Y'],
						'candidate_type' => $import['BS'],
						'mt_type' => $import['BP'],
						'overall_experience' => $import['BQ'],
						'created_at' => $created_at,
						'last_modified' => $import['BU'],
						'fresher_type' => $import['BR'],
						'created_by_username' => $import['BV'],
						'created_by' => '1'
					);

					$strConcat = '<br>';
					foreach ($data as $key => $value)  $strConcat .= trim($value);
					if ($strConcat != '')
					{
						$ValidColumCount = 0;
						foreach ($data as $key => $value)
						{
							if (trim($value) != '')
							{
								$ValidColumCount++;
								break;
							}
						}

						if ($ErrorCount > 0)
						{
							if (count($MessageList) > 0)
							{
								foreach ($MessageList as $Msg)
								{
									print "Row $Counter: " . $Msg["error_type"] . " " . $Msg["column"] . "\n";
								}
							}
						}
						else
						{
							if ($ValidColumCount > 0)
							{
								$i++;
								$this->db->insert('neo.candidates', $data);
								if ($this->db->affected_rows())
								{
									$CandidateId = $this->db->insert_id();
									if ($CandidateId > 0)
									{
										$insert_count++;

										//QP DETAILS
										$qp_detail_data = array(
											'candidate_id' => $CandidateId,
											'qualification_pack_id' => $QualificationPackId,
											'qualification_pack' => $import['Z'],
											'batch_code' => $import['AA'],
											'center_name' => $import['AB'],
											'center_location' => $import['AC'],
											'course_name' => $import['AD'],
											'funding_source' => $import['AE'],
											'certification_date' => $import['AF'],
											'created_by' => '1'
										);
										$this->db->insert('neo.candidate_qp_details', $qp_detail_data);

										//EDUCATION DETAILS
										$education_detail_data = array(
											'candidate_id' => $CandidateId,
											'education_id' => $EducationId,
											'education_name' => $import['AG'],
											'specialization' => $import['AH'],
											'institution' => $import['AI'],
											'location' => $import['AJ'],
											'from_year' => $import['AK'],
											'to_year' => $import['AL'],
											'year_of_passing' => $import['AM'],
											'learning_type_id' => $LearningTypeId,
											'learning_type' => $import['AN'],
											'education_info' => $import['BW'],
											'created_by' => '1'
										);
										$this->db->insert('neo.candidate_education_details', $education_detail_data);

										//EMPLOYER DETAILS
										$employer_detail_data = array(
											'candidate_id' => $CandidateId,
											'company_name' => $import['AO'],
											'from' => $import['AP'],
											'to' => $import['AQ'],
											'designation' => $import['AR'],
											'country' => $import['AS'],
											'state' => $import['AT'],
											'city' => $import['AU'],
											'location' => $import['AV'],
											'ctc' => $import['AW'],
											'gross_salary' => $import['AX'],
											'currency' => $import['AY'],
											'address' => $import['AZ'],
											'job_profile' => $import['BA'],
											'office_landline' => $import['BB'],
											'employee_code' => $import['BC'],
											'reason_for_leaving' => $import['BD'],
											'current_employer' => false,
											'notice_period' => $import['BF'],
											'joining_location' => $import['BG'],
											'reporting_location' => $import['BH'],
                                                                                        'skilling_type_id' => 1,
											'created_by' => '1'
										);
										$this->db->insert('neo.candidate_employment_details', $employer_detail_data);

										//SKILL DETAILS
										$skill_detail_data = array(
											'candidate_id' => $CandidateId,
											'skill_name' => $import['BI'],
											'skill_description' => $import['BJ'],
											'version' => $import['BK'],
											'last_used_year' => $import['BL'],
											'last_used_month' => $import['BM'],
											'experience_year' => $import['BN'],
											'experience_month' => $import['BO'],
											'created_by' => '1'
										);
										$this->db->insert('neo.candidate_skill_details', $skill_detail_data);
									}
								}
							}
						}
					}
				}

				if ($ValidRowCount == $insert_count)
				{
					print "Candidate data uploaded successfully!";
				}
				else
				{
					print "Errors while uploading the data!";
				}
			} else
			{
				print "Please Upload Max. 5000 Candidate Data.";
			}

		}
    }

	/* ---------Assigned Candidates ----- */
	public function assigned_candidates()
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data['page']    = 'assigned_candidates';
		$data['title']   = 'Assigned Candidates list';
		$data['user_id'] = $user['id'];
		$data['module']  = "partner";
		$this->load->view('index', $data);
	}


	public function assigned_candidates_list($user_id = 0, $experience = 0, $qualification=0)
	{
		error_reporting(E_ALL);
		$resp_data = $this->partner->get_candidate_list(0, $user_id, $experience ,$qualification);
		echo json_encode($resp_data);  // send data as json format
	}

	public function candidate_detail()
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data['page']    = 'candidate_detail';
		$data['title']   = 'Candidate Detail View';
		$data['user_id'] = $user['id'];
		$data['module']  = "partner";
		$this->load->view('index', $data);
	}


        public function download_igs_list_sample()
    {
        $path = IGS_CANDIDATE_LIST.'NeoJobsIGSUploadTemplate.csv';
        header('Content-Disposition: attachment; filename=' . urlencode(basename($path)));
        header("Content-Transfer-Encoding: binary");
       // header('Content-Type: application/pdf');
        header("Content-Type: application/octet-stream");
        header("Content-Description: File Transfer");
        header('Expires: 0');
        header('Pragma: public');
        header("Content-Length: " . Filesize(IGS_CANDIDATE_LIST.urlencode(basename($path))));
        echo file_get_contents(IGS_CANDIDATE_LIST.urlencode(basename($path)));
    }
    
     public function download_center_template()
    {
        $path = CANDIDATE_LIST.'centerDataTemplate.csv';
        header('Content-Disposition: attachment; filename=' . urlencode(basename($path)));
        header("Content-Transfer-Encoding: binary");
       // header('Content-Type: application/pdf');
        header("Content-Type: application/octet-stream");
        header("Content-Description: File Transfer");
        header('Expires: 0');
        header('Pragma: public');
        header("Content-Length: " . Filesize(CANDIDATE_LIST.urlencode(basename($path))));
        echo file_get_contents(CANDIDATE_LIST.urlencode(basename($path)));
    }
    
     public function download_batch_template()
    {
        $path = CANDIDATE_LIST.'BatchUploadTemplate.csv';
        header('Content-Disposition: attachment; filename=' . urlencode(basename($path)));
        header("Content-Transfer-Encoding: binary");
       // header('Content-Type: application/pdf');
        header("Content-Type: application/octet-stream");
        header("Content-Description: File Transfer");
        header('Expires: 0');
        header('Pragma: public');
        header("Content-Length: " . Filesize(CANDIDATE_LIST.urlencode(basename($path))));
        echo file_get_contents(CANDIDATE_LIST.urlencode(basename($path)));
    }
    
    
    public function customer_details($customer_id=0)
	{
		//$this->pramaan->_check_module_task_auth(false);
		$customer_results=$this->partner->get_customer_details($customer_id);
		echo json_encode($customer_results);
	}
    
        public function authorize($data){
        $user_group_id = $this->session->userdata('usr_authdet')['user_group_id'];
        if(!in_array($user_group_id, $data)){
          $this->session->set_flashdata('status', 'You are not authorised to access that page');
          redirect('/pramaan/dashboard', 'refresh');
        }
	 }	 
	 
    
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/Partner.php */

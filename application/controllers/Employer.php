<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Employer :: Employer controller
 * @author by Sangamesh<sangamesh.p@mpramaan.in_Nov-2016>
**/
include APPPATH.'/controllers/Pramaan.php';

class Employer extends Pramaan
{

	public function __construct()
	{

		parent::__construct();
		$this->load->model("Employer_model","employer");
		$this->load->model("Pramaan_model","pramaan");
	}

	/**
	* function for post a job
	*/
	function post_job($user_id=0)
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		//$data['sector_list']=$this->db->query("SELECT id, name from master.sector")->result_array();
		$data['job_role']=$this->db->query("SELECT id,name from master.qualification_pack")->result_array();
		$data['employment_cat_list']=$this->db->query("SELECT value,name from master.list where code='L0015'")->result_array();
		$data['min_qualification_list']=$this->db->query("SELECT id,name from master.education order by sortorder")->result_array();
		$data['department_list']=$this->db->query("SELECT department_id,name from master.departments order by sort_order")->result_array();
		$data['listing_list']=$this->db->query("SELECT value,name from master.list where code='L0019'")->result_array();
		$districts=$this->pramaan->do_get_districts();
		if($districts)
			$data['district_list']=$districts;
		else
			$data['district_list']=array();

		$employers_list = $this->employer->do_get_employers_by_exec($user_id);

		if($employers_list)
			$data['employers_list'] = $employers_list;
		else
		   	$data['employers_list'] = array();
		$data['page']='post_job';
		$data['title']='Post Job';
		$data['module']="employer";
		$user_id=($user_id)?$user_id:$user['id'];
		$data['user_id']=$user_id;

		$data['user_group_id'] = $user['user_group_id'];

		//$data['rec_sup_exec_list']=$this->db->query("SELECT user_id, name from users.user_admins WHERE user_id in(SELECT distinct user_id from users.recruitment_support_assignment where employer_id=?)",$employer_id)->result_array();

		$this->load->view('index',$data);
	}

        /**
	* function for edit a job
	*/
	function edit_job($user_id=0,$job_id=0)
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		//$data['sector_list']=$this->db->query("SELECT id, name from master.sector")->result_array();
		$data['job_role']=$this->db->query("SELECT id,name from master.qualification_pack")->result_array();
		$data['employment_cat_list']=$this->db->query("SELECT value,name from master.list where code='L0015'")->result_array();
		$data['min_qualification_list']=$this->db->query("SELECT id,name from master.education order by sortorder")->result_array();
		$data['department_list']=$this->db->query("SELECT department_id,name from master.departments order by sort_order")->result_array();
		$data['listing_list']=$this->db->query("SELECT value,name from master.list where code='L0019'")->result_array();
		$districts=$this->pramaan->do_get_districts();
		if($districts)
			$data['district_list']=$districts;
		else
			$data['district_list']=array();

		$employers_list = $this->employer->do_get_employers_by_exec($user_id);

		if($employers_list)
			$data['employers_list'] = $employers_list;
		else
		   	$data['employers_list'] = array();
		$data['page']='edit_job';
		$data['title']='Edit Job';
		$data['module']="employer";
		$user_id=($user_id)?$user_id:$user['id'];
		$data['user_id']=$user_id;

		$data['user_group_id'] = $user['user_group_id'];
		$data['job_data']=$this->db->query('select * from job_process.jobs where id=?',$job_id)->row_array();
		$data['job_detail']=$this->db->query('select * from job_process.job_detail where job_id=? and job_status=?',array($job_id,'t'))->result_array();
		//$data['rec_sup_exec_list']=$this->db->query("SELECT user_id, name from users.user_admins WHERE user_id in(SELECT distinct user_id from users.recruitment_support_assignment where employer_id=?)",$employer_id)->result_array();

		$this->load->view('index',$data);
	}


	/**
	* function for save the employer job
	*/
	function save_job()
	{
		error_reporting(E_ALL);
		$user=$this->pramaan->_check_module_task_auth(true);			//return:true(returns user detail)
			$this->load->library('form_validation');
			//$this->form_validation->set_rules('sector', 'Sector', 'required');
			$this->form_validation->set_rules('department_id', 'department', 'required');
			$this->form_validation->set_rules('job_role', 'Job role', 'required');
			$this->form_validation->set_rules('employer_id', 'Employer', 'required');
			$this->form_validation->set_rules('job_category_id', 'Job Category', 'required');
			//$this->form_validation->set_rules('no_of_openings', 'Number of openings', 'required');
			//$this->form_validation->set_rules('job_location[]', 'Job Location', 'required');
			/*$this->form_validation->set_rules('job_address', 'Job address', 'required');*/
			$this->form_validation->set_rules('min_qualification', 'Min Qualification', 'required');
			$this->form_validation->set_rules('min_experience', 'Min experience', 'required');
			$this->form_validation->set_rules('max_experience', 'Max experience', 'required');
			//$this->form_validation->set_rules('min_salary', 'Min salary', 'required');
			//$this->form_validation->set_rules('max_salary', 'Max salary', 'required');
			$this->form_validation->set_rules('contact_name','Contact person name','required|callback__valid_name');
			//$this->form_validation->set_rules('phone','Phone','required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
			//$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');
			$this->form_validation->set_rules('listing_id','Listing','required');


            /* Aniket 's Work */

            $this->form_validation->set_rules('min_age', 'Min Age', 'required');

			$this->form_validation->set_rules('max_age', 'Max Age', 'required');

           $this->form_validation->set_rules('last_date_apply','Last Date of Application','required');

           $last_date_apply = $this->input->post('last_date_apply');


           $last_date_apply2 =  date('Y-m-d',strtotime( $last_date_apply));

             /* End */

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
				$questions= $this->input->post('questions');
				$data = array(
				'employer_id' =>$this->input->post('employer_id'),
				'qualification_pack_id'=>$this->input->post('job_role'),
				'department_id'=>$this->input->post('department_id'),
				'job_desc' => $this->input->post('job_desc'),
				'job_category_id' => $this->input->post('job_category_id'),
				'job_address' => $this->input->post('job_address'),
				'min_qualification_id' => $this->input->post('min_qualification'),
				'min_experience' => $this->input->post('min_experience'),
				'max_experience' => $this->input->post('max_experience'),
				'contact_name' => $this->input->post('contact_name'),
				'contact_phone' => $this->input->post('phone'),
				'contact_email' => $this->input->post('email'),
				'question_one' => ($questions[0])?$questions[0]:'',
				'question_two' => (isset($questions[1]))?$questions[1]:'',
				'question_three' => (isset($questions[2]))?$questions[2]:'',
				'listing_id' => $this->input->post('listing_id'),
				'created_on'=>date('Y-m-d'),
				 /*Aniket's Work */

                   'min_age' => $this->input->post('min_age'),
                   'max_age' => $this->input->post('max_age'),
                   'last_date_apply' => $last_date_apply2,

                  /* Ends */

				);

				$min_salary =$this->input->post('min_salary');
				$max_salary = $this->input->post('max_salary');
				$location=$this->input->post('location');
				$no_of_openings=$this->input->post('no_of_openings');
				$add_location=array();
				for($i=0;$i<count($location);$i++)
				{
					$add_location[$i]['location_id']=$location[$i];
					$add_location[$i]['no_of_openings']=$no_of_openings[$i];
					$add_location[$i]['min_salary']=$min_salary[$i];
					$add_location[$i]['max_salary']=$max_salary[$i];
				}
				$created_by=$this->input->post('job_poster_id');
				if($created_by)
					$data['created_by']=$created_by;
				else
					$data['created_by']=$user['id'];
				$rec_sup_exec_id= $this->input->post('rec_sup_exec_id');
				if($rec_sup_exec_id)
					$data['rec_sup_exec_id']= $rec_sup_exec_id;
				else
					$data['rec_sup_exec_id']=0;



				$submit=$this->input->post('submit');
				if($submit=='add')
				{
					$insert_id = $this->employer->do_add_job($data,$add_location);
					if($insert_id)
					{
						$result_rec=$this->pramaan->get_job_detail($insert_id);
						$message="<html><body><br><u><b>New Job</b></u><br>";
						$message.="<br>Employer Name: ".$result_rec['employer_name'];
						$message.="<br>Job Role: ".$result_rec['job_role'];
						$message.="<br>Job Description: ".$result_rec['job_desc'];
						$message.="<br>No.of Openings: ".$result_rec['no_of_openings'];
						$message.="<br>Job Location: ".($result_rec['job_location'] ?? '');
						$message.="<br>Salary: ".$result_rec['min_salary'].'-'.$result_rec['max_salary'];
						$message.="<br>Min Qualification: ".$result_rec['min_qualification_name'];
						$message.="<br>Work Experience: ".$result_rec['min_experience'].'-'.$result_rec['max_experience'];
						$message.="<br>Created on: ".$result_rec['created_on'];
						$message.="</body></html>";
						$email_ids_rec=$this->db->query("SELECT uc.email from users.user_admins ua
														 JOIN users.accounts uc on ua.user_id=uc.id and uc.user_group_id=4
														 WHERE department_id=2");
						if($email_ids_rec->num_rows())
						{
							$email_id_arr=$email_ids_rec->result_array();
							$email_ids=array();
							foreach ($email_id_arr as $value)
							{
								$email_ids[]=$value['email'];
							}
							$this->pramaan->notify_by_mail('donotreply@pramaan.in',$email_ids,'New Job',$message);
						}
						echo json_encode(array('status'=>TRUE,'msg_info'=>'New Job has been posted.'));
					}
					else
					{
						echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the data table')));
					}
				}
				else
				{
					$job_id=$this->input->post('job_id');

					$update_id = $this->employer->do_update_job($job_id,$data,$add_location);
					if($update_id)
					{

						echo json_encode(array('status'=>TRUE,'msg_info'=>'Job has been updated.'));
					}
					else
					{
						echo json_encode(array("status" => FALSE,'errors' =>array('Errors in the data table')));
					}

				}



			}
	}

	public function employer_jobs($employer_id=0)
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		if(!$employer_id)
			$employer_id=$user['id'];
		$data['min_qualification_list']=$this->db->query("select id,name from master.education order by sortorder")->result_array();
		$data['page']='employer_jobs';
		$data['title']='Job Board';
		$data['module']="employer";
		$data['employer_id']=$employer_id;
		$employer_id=$this->pramaan->do_get_partner_id($employer_id);
		$data['parent_page']="partner/employers/".$employer_id;
		$data['parent_page_title']="Employers";
		$this->load->view('index',$data);
	}

	/**
	 * Function to load employer job list
	 * @author Sangamesh.p@pramaan.in
	**/

	public function employer_job_list($employer_id=0,$non_metric=0,$metric=0,$graduate=0,$experience='',$search_key=0,$pg=0,$limit=25)
	{
		$this->pramaan->_check_module_task_auth(false);
		$rep_data=$this->employer->get_job_list($non_metric,$metric,$graduate,$experience,$employer_id,$search_key,$pg,$limit);

		if($rep_data['status']=='success')
		{
			$pagination=_prepare_pagination(site_url("employer/employer_job_list/$employer_id/$non_metric/$metric/$graduate/$experience/$search_key"), $rep_data['total_records'], $limit,9);

			$rdata=array('status'=>'success','job_list'=>$rep_data['job_list']
					,'pg'=>$rep_data['pg'],'limit'=>$limit,'pagination'=>$pagination,'pg_count_msg'=>$rep_data['pg_count_msg'],'page_number'=>$rep_data['page_number']);
			output_data($rdata);
		}

		else
		{
			output_error($rep_data['message']);
		}
	}

	/**
	 * Function to load scheduled candidates list
	 * @author Sangamesh.p@pramaan.in
	**/
	public function scheduled_candidates($job_id=0,$employer_id=0)
	{
		$user=$this->pramaan->_check_module_task_auth(false);
		$data['min_qualification_list']=$this->db->query("select id,name from master.education order by sortorder")->result_array();
		$data['page']='scheduled_candidates';
		$data['title']='Scheduled Candidates';
		$data['module']="employer";
		$data['job_id']=$job_id;
		$data['employer_id']=$employer_id;
		$this->load->view('index',$data);
	}

	/**
	 * Function to load scheduled candidates list
	 * @author Sangamesh.p@pramaan.in
	**/
	public function scheduled_candidates_list($job_id='',$employer_id='',$pg=0,$limit=25)
	{
		$user=$this->pramaan->_check_module_task_auth(true);

		$rep_data=$this->employer->do_get_scheduled_candidates($job_id,$employer_id,$pg,$limit);
		if($rep_data['status']=='success')
		{
			output_data($rep_data['rdata']);
		}
		else
		{
			output_error($rep_data['message']);
		}
	}
	/**
	 * Function to get job roles
	 * @author Sangamesh.p@pramaan.in
	**/
	public function get_job_role_bysector($sector_id)
	{
		$data['status']=false;
		$result=$this->db->query("select id,name from master.qualification_pack where sector_id=?",$sector_id);
		if($result->num_rows())
		{
			$data['status']=true;
			$data['role_list']=$result->result_array();
		}
		echo json_encode($data);
	}

	/**
	 * Function to application tracker
	 * @author Sangamesh.p@pramaan.in
	**/
	public function application_tracker()
	{
		$this->pramaan->_check_module_task_auth(false);
		$data['page']='application_tracker';
		$data['title']='Application Tracker';
		$data['module']="employer";
		$this->load->view('index',$data);
	}

	/**
	 * Function to load application tracker list
	 * @author Sangamesh.p@pramaan.in
	**/
	public function application_tracker_list()
	{
		error_reporting(E_ALL);
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		$resp_data=$this->employer->get_applicationTracker_employer($requestData,$user['id']);
		echo json_encode($resp_data);  // send data as json format
	}

	/**
	 * Function to load tracked jobs
	 * @author Sangamesh.p@pramaan.in
	**/
	public function tracked_job_candidates($job_id=0,$job_status=0)
	{
		$this->pramaan->_check_module_task_auth(false);
		$tracked_results=$this->employer->get_tracked_job_candidates($job_id,$job_status);
		echo json_encode($tracked_results);
	}

	/**
	 * Function to update joining status
	 * @author Sangamesh.p@pramaan.in
	**/
	public function update_joining_status()
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
//		$this->form_validation->set_rules('employer_name', 'Employer Name', 'required');
		$this->form_validation->set_rules('employer_contact_phone', 'Employer Phone', 'required');
		if ($this->form_validation->run() == false)
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
			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');
                        $PlacementId=$this->input->post('id');
			$EmploymentTypeId=$this->input->post('hidEmploymentId');
			$EmploymentType=$this->input->post('employment_type');
			$EmployerName=$this->input->post('txtCustomerName');
                        $Designation=$this->input->post('txtJobTitle');
			$EmployerContactPhone=$this->input->post('employer_contact_phone');
			$EmployerLocation=$this->input->post('employer_location');
			$PlacementLocation=$this->input->post('placement_location');
			$CTC=$this->input->post('ctc');
			$Dateofjoin=$this->input->post('dateofjoin');
			$Offerletterdate=$this->input->post('offerletterjoindate');                       
			$data = array(
				'candidate_id'=>$CandidateId,
				'job_id'=>$JobId,
				'employment_type_id'=>$EmploymentType,
				'employment_type'=>$EmploymentTypeId,
				'employer_name'=>$EmployerName,
				'employer_contact_phone'=>$EmployerContactPhone,
				'employer_location'=>$EmployerLocation,
				'placement_location'=>$PlacementLocation,
				'ctc'=>$CTC,
				'date_of_join'=>$Dateofjoin,
				'offer_letter_date_of_join'=>$Offerletterdate
			);  
                        
                        $EmployeeTypeName = $this->db->query("SELECT name AS employee_type_name FROM neo_master.employment_type WHERE id=$EmploymentType")->row()->employee_type_name;
                       
                                              
                        $data2 = array(
				'candidate_id'=>$CandidateId,
                                'employment_type'=>$EmployeeTypeName,
				'company_name'=>$EmployerName,				
				'location'=>$EmployerLocation,
                                'designation'=>$Designation,
				'joining_location'=>$PlacementLocation,
				'ctc'=>$CTC,
				'from'=>$this->buildDate($Dateofjoin),
				'current_employer'=>TRUE,
                                'skilling_type_id'=>1
                            
			);    
//                       print_r($data2);
//                       die;

			$this->load->library('upload');
			$this->upload->initialize($this->set_offer_letter_upload_options());
			$this->upload->do_upload('offer_letters');

			if (isset($_FILES['offer_letters']['name'])&&strlen($_FILES['offer_letters']['name'])>0)
			{
				$Extension     = pathinfo($_FILES['offer_letters']['name'], PATHINFO_EXTENSION);
				$OfferLetterFileName = "OfferLetter_" . $CandidateId . "_" . date("Ymdhis.") . $Extension;

				$_FILES['offer_letters']['name'] = $OfferLetterFileName;
				$this->upload->do_upload('offer_letters');

				$data['offer_letter_file'] = $OfferLetterFileName;
				date_default_timezone_set('Asia/Kolkata');
				$data['offer_letter_uploaded_on'] = date("Y-m-d h:i:s A");
			}

			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');


			$check_rec_count = $this->db->query("SELECT COUNT(*) AS rec_count FROM neo_job.candidate_placement WHERE candidate_id=? AND job_id=?", array($CandidateId, $JobId))->row()->rec_count;
                       
			if ($check_rec_count>0)
			{                           
				$is_update = $this->employer->do_update_joing_status($data,$data2);
				if ($is_update)
				{
                                        
					echo json_encode(array('status' => TRUE, 'msg_info' => "Joining status has been updated successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving joining details')));
				}
			}
			else
			{       
                                $this->db->reset_query();
                                $this->db->trans_start();
                                $this->db->reset_query();
				$this->db->insert('neo_job.candidate_placement', $data);
                                $data2['placement_id']=$this->db->insert_id();
                                $this->db->reset_query();
                                $this->db->update('neo.candidate_employment_details', $data2);
                                $this->db->reset_query();
                                $this->db->where('job_id', $JobId)->where('candidate_id', $CandidateId)
                                ->set('candidate_status_id', 15) ->update('neo_job.candidates_jobs');
                                $this->db->trans_complete();
				if($this->db->trans_status())
				{
                                       
					echo json_encode(array('status' => TRUE, 'msg_info' => "Joining status has been added successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving joining details')));
				}
			}
		}
	}
        
        
        public function update_customer_joining_status()
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
//		$this->form_validation->set_rules('employer_name', 'Employer Name', 'required');
		$this->form_validation->set_rules('employer_contact_phone', 'Employer Phone', 'required');
		if ($this->form_validation->run() == false)
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
			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');
                        $PlacementId=$this->input->post('id');
			$EmploymentTypeId=$this->input->post('employment_type');
			$EmploymentType=$this->input->post('hidEmploymentId');
			$EmployerName=$this->input->post('txtCustomerName');
                        $Designation=$this->input->post('txtJobTitle');
			$EmployerContactPhone=$this->input->post('employer_contact_phone');
			$EmployerLocation=$this->input->post('employer_location');
			$PlacementLocation=$this->input->post('placement_location');
			$CTC=$this->input->post('ctc');
			$Dateofjoin=$this->input->post('dateofjoin');
			$Offerletterdate=$this->input->post('offerletterjoindate');                       
			$data = array(
				'candidate_id'=>$CandidateId,
				'job_id'=>$JobId,
				'employment_type_id'=>$EmploymentTypeId,
				'employment_type'=>$EmploymentType,
				'employer_name'=>$EmployerName,
				'employer_contact_phone'=>$EmployerContactPhone,
				'employer_location'=>$EmployerLocation,
				'placement_location'=>$PlacementLocation,
				'ctc'=>$CTC,
				'date_of_join'=>$Dateofjoin,
				'offer_letter_date_of_join'=>$Offerletterdate
			);   
                        
                        $EmployeeTypeName = $this->db->query("SELECT name AS employee_type_name FROM neo_master.employment_type WHERE id=$EmploymentTypeId")->row()->employee_type_name;
                        
                        $data2 = array(
				'candidate_id'=>$CandidateId,
                                'employment_type'=>$EmployeeTypeName,
				'company_name'=>$EmployerName,				
				'location'=>$EmployerLocation,
                                'designation'=>$Designation,
				'joining_location'=>$PlacementLocation,
				'ctc'=>$CTC,
				'from'=>$this->buildDate($Dateofjoin),
				'current_employer'=>TRUE,
                                'skilling_type_id'=>1
                            
			);    
//                        print_r($data2);
//                       die;

			$this->load->library('upload');
			$this->upload->initialize($this->set_offer_letter_upload_options());
			$this->upload->do_upload('offer_letters');

			if (isset($_FILES['offer_letters']['name'])&&strlen($_FILES['offer_letters']['name'])>0)
			{
				$Extension     = pathinfo($_FILES['offer_letters']['name'], PATHINFO_EXTENSION);
				$OfferLetterFileName = "OfferLetter_" . $CandidateId . "_" . date("Ymdhis.") . $Extension;

				$_FILES['offer_letters']['name'] = $OfferLetterFileName;
				$this->upload->do_upload('offer_letters');

				$data['offer_letter_file'] = $OfferLetterFileName;
				date_default_timezone_set('Asia/Kolkata');
				$data['offer_letter_uploaded_on'] = date("Y-m-d h:i:s A");
			}

			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');


			$check_rec_count = $this->db->query("SELECT COUNT(*) AS rec_count FROM neo_job.candidate_placement WHERE candidate_id=? AND job_id=?", array($CandidateId, $JobId))->row()->rec_count;
                       
			if ($check_rec_count>0)
			{                           
				$is_update = $this->employer->do_update_joing_status($data,$data2);
				if ($is_update)
				{
                                        
					echo json_encode(array('status' => TRUE, 'msg_info' => "Joining status has been updated successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving joining details')));
				}
			}
			else
			{       
                                $this->db->reset_query();
                                $this->db->trans_start();
                                $this->db->reset_query();
				$this->db->insert('neo_job.candidate_placement', $data);
//                                $data2['placement_id']=$this->db->insert_id();
//                                $this->db->reset_query();
                                $this->db->update('neo.candidate_employment_details', $data2);
                                $this->db->reset_query();
                                $this->db->where('job_id', $JobId)->where('candidate_id', $CandidateId)
                                ->set('candidate_status_id', 15) ->update('neo_job.candidates_jobs');
                                $this->db->trans_complete();
				if($this->db->trans_status())
				{
                                       
					echo json_encode(array('status' => TRUE, 'msg_info' => "Joining status has been added successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving joining details')));
				}
			}
		}
	}
        
        public function update_placement_joining_status()
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
//		$this->form_validation->set_rules('employer_name', 'Employer Name', 'required');
		$this->form_validation->set_rules('employer_contact_phone', 'Employer Phone', 'required');
		if ($this->form_validation->run() == false)
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
			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');
                        $PlacementId=$this->input->post('id');
			$EmploymentTypeId=$this->input->post('hidEmploymentId');
			$EmploymentType=$this->input->post('employment_type');
			$EmployerName=$this->input->post('customer_name');
                        $Designation=$this->input->post('job_title');
			$EmployerContactPhone=$this->input->post('employer_contact_phone');
			$EmployerLocation=$this->input->post('employer_location');
			$PlacementLocation=$this->input->post('placement_location');
			$CTC=$this->input->post('ctc');
			$Dateofjoin=$this->input->post('dateofjoin');
			$Offerletterdate=$this->input->post('offerletterjoindate');                       
			$data = array(
				'candidate_id'=>$CandidateId,
				'job_id'=>$JobId,
				'employment_type_id'=>$EmploymentType,
				'employment_type'=>$EmploymentTypeId,
				'employer_name'=>$EmployerName,
				'employer_contact_phone'=>$EmployerContactPhone,
				'employer_location'=>$EmployerLocation,
				'placement_location'=>$PlacementLocation,
				'ctc'=>$CTC,
				'date_of_join'=>$Dateofjoin,
				'offer_letter_date_of_join'=>$Offerletterdate
			);  
                        
                        $EmployeeTypeName = $this->db->query("SELECT name AS employee_type_name FROM neo_master.employment_type WHERE id=$EmploymentType")->row()->employee_type_name;
                       
                                              
                        $data2 = array(
				'candidate_id'=>$CandidateId,
                                'employment_type'=>$EmployeeTypeName,
				'company_name'=>$EmployerName,				
				'location'=>$EmployerLocation,
                                'designation'=>$Designation,
				'joining_location'=>$PlacementLocation,
				'ctc'=>$CTC,
				'from'=>$this->buildDate($Dateofjoin),
				'current_employer'=>TRUE,
                                'skilling_type_id'=>1
                            
			);    
//                       print_r($data2);
//                       die;

			$this->load->library('upload');
			$this->upload->initialize($this->set_offer_letter_upload_options());
			$this->upload->do_upload('offer_letters');

			if (isset($_FILES['offer_letters']['name'])&&strlen($_FILES['offer_letters']['name'])>0)
			{
				$Extension     = pathinfo($_FILES['offer_letters']['name'], PATHINFO_EXTENSION);
				$OfferLetterFileName = "OfferLetter_" . $CandidateId . "_" . date("Ymdhis.") . $Extension;

				$_FILES['offer_letters']['name'] = $OfferLetterFileName;
				$this->upload->do_upload('offer_letters');

				$data['offer_letter_file'] = $OfferLetterFileName;
				date_default_timezone_set('Asia/Kolkata');
				$data['offer_letter_uploaded_on'] = date("Y-m-d h:i:s A");
			}

			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');


			$check_rec_count = $this->db->query("SELECT COUNT(*) AS rec_count FROM neo_job.candidate_placement WHERE candidate_id=? AND job_id=?", array($CandidateId, $JobId))->row()->rec_count;
                       
			if ($check_rec_count>0)
			{                           
				$is_update = $this->employer->do_update_joing_status($data,$data2);
				if ($is_update)
				{
                                        
					echo json_encode(array('status' => TRUE, 'msg_info' => "Joining status has been updated successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving joining details')));
				}
			}
			else
			{       
                                $this->db->reset_query();
                                $this->db->trans_start();
                                $this->db->reset_query();
				$this->db->insert('neo_job.candidate_placement', $data);
                                $data2['placement_id']=$this->db->insert_id();
                                $this->db->reset_query();
                                $this->db->update('neo.candidate_employment_details', $data2);
                                $this->db->reset_query();
                                $this->db->where('job_id', $JobId)->where('candidate_id', $CandidateId)
                                ->set('candidate_status_id', 15) ->update('neo_job.candidates_jobs');
                                $this->db->trans_complete();
				if($this->db->trans_status())
				{
                                       
					echo json_encode(array('status' => TRUE, 'msg_info' => "Joining status has been added successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving joining details')));
				}
			}
		}
	}
        
        
        public function update_resigned_status()
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('reason_to_leave', 'Reason to Leave', 'required');
		$this->form_validation->set_rules('resigned_date', 'Resigned Date', 'required');
		if ($this->form_validation->run() == false)
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
			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');                       
			$Reasontoleave=$this->input->post('reason_to_leave');
			$ResignedDate=$this->input->post('resigned_date');                       
			$data = array(
				'candidate_id'=>$CandidateId,
				'job_id'=>$JobId,				
				'reason_to_leave'=>$Reasontoleave,
				'resigned_date'=>$ResignedDate
			);  
//                        print_r($data);
//                        die;
                        
                       
			$check_rec_count = $this->db->query("SELECT COUNT(*) AS rec_count FROM neo_job.candidate_placement WHERE candidate_id=? AND job_id=?", array($CandidateId, $JobId))->row()->rec_count;
                       
			if ($check_rec_count>0)
			{                           
				$is_update = $this->employer->do_update_resigned_status($data);
				if ($is_update)
				{
                                        
					echo json_encode(array('status' => TRUE, 'msg_info' => "Resigned status has been updated successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving resigned details')));
				}
			}
			else
			{       
                                $this->db->reset_query();
                                $this->db->trans_start();
                                $this->db->reset_query();
				$this->db->insert('neo_job.candidate_placement', $data);
                                $data2['placement_id']=$this->db->insert_id();
                                $this->db->reset_query();                               
                                $this->db->where('job_id', $JobId)->where('candidate_id', $CandidateId)
                                ->set('candidate_status_id', 17) ->update('neo_job.candidates_jobs');
                                $this->db->trans_complete();
				if($this->db->trans_status())
				{
                                       
					echo json_encode(array('status' => TRUE, 'msg_info' => "Resigned status has been added successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving resigned details')));
				}
			}
		}
	}
        

	public function get_job_details($job_id=0)
	{
		$output['status']=false;
		$result=$this->employer->do_get_job_detail($job_id);
		if($result)
		{
			$output['status']=true;
			$output['job_details']=$result;
		}
		echo json_encode($output);
	}

	public function get_job_applied_details($job_id=0,$candidate_id=0)
	{
		$output['status']=false;
		$result=$this->employer->get_job_applied_details($job_id,$candidate_id);
		if($result)
		{
			$output['status']=true;
			$output['job_applied_details']=$result;
		}
		echo json_encode($output);

	}

	public function get_customerwise_joined_candidate_data($customer_id=0)
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		$resp_data=$this->employer->get_customerwise_joined_candidate_data($requestData, $customer_id);
		echo json_encode($resp_data);
	}

	public function get_jobwise_joined_candidate_data($job_id=0)
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		$resp_data=$this->employer->get_jobwise_joined_candidate_data($requestData, $job_id);
		echo json_encode($resp_data);
	}

	

	public function get_customer_data()
	{   
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		$resp_data=$this->employer->get_customer_data($requestData);
		echo json_encode($resp_data);
	}
        
        public function get_user_data()
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		$resp_data=$this->employer->get_user_data($requestData);
		echo json_encode($resp_data);
	}


	private function set_offer_letter_upload_options()
	{
		$config                  = array();
		$config['upload_path']   = OFFER_LETTER_PATH;
		$config['allowed_types'] = 'doc|docx|pdf|jpeg|jpg|png';
		$config['max_size']      = '0'; // 0 = no file size limit
		$config['max_width']     = '0';
		$config['max_height']    = '0';
		$config['overwrite']     = TRUE;
		return $config;
	}
        
        
        public function get_center_data()
	{
		$user=$this->pramaan->_check_module_task_auth(true);
		$requestData= $_REQUEST;
		$resp_data=$this->employer->get_center_data($requestData);
		echo json_encode($resp_data);
	}
        
        public function buildDate($input) {
        $date = date_create("$input");
        return date_format($date,"Y");
       }
      
      
      public function update_offered_status()
	{

		$user=$this->pramaan->_check_module_task_auth(true);
		$this->load->library('form_validation');
		$this->form_validation->set_rules('candidateid', 'Candidate ID', 'required|is_natural_no_zero');
		$this->form_validation->set_rules('jobid', 'Job ID', 'required|is_natural_no_zero');
                $this->form_validation->set_rules('offerletterjoindate', 'Offer letter Date', 'required');
                $this->form_validation->set_rules('offered_remarks1', 'Offered Remarks', 'required');
                $this->form_validation->set_rules('offered_ctc', 'Offered CTC', 'required');
		if (!$this->form_validation->run())
		{

			echo json_encode(array('status'=>FALSE, 'errors' =>$this->form_validation->error_array()));
                        exit;
		}
		else
		{
			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');
			$Offerletterdate=$this->input->post('offerletterjoindate');
                        $Offeredremarks=$this->input->post('offered_remarks1');
                        $Offeredctc=$this->input->post('offered_ctc');
			$data = array(
				'candidate_id'=>$CandidateId,
				'job_id'=>$JobId,
				'offer_letter_date_of_join'=>$Offerletterdate,
                                'offered_remarks'=>$Offeredremarks,
                                'offered_ctc'=>$Offeredctc
			);
                                  
                       
			$this->load->library('upload');
			$this->upload->initialize($this->set_offer_letter_upload_options());
			$this->upload->do_upload('offer_letters');

			if (isset($_FILES['offer_letters']['name'])&&strlen($_FILES['offer_letters']['name'])>0)
			{
				$Extension     = pathinfo($_FILES['offer_letters']['name'], PATHINFO_EXTENSION);
				$OfferLetterFileName = "OfferLetter_" . $CandidateId . "_" . date("Ymdhis.") . $Extension;

				$_FILES['offer_letters']['name'] = $OfferLetterFileName;
				$this->upload->do_upload('offer_letters');

				$data['offer_letter_file'] = $OfferLetterFileName;
				date_default_timezone_set('Asia/Kolkata');
				$data['offer_letter_uploaded_on'] = date("Y-m-d h:i:s A");
			}

			$CandidateId=$this->input->post('candidateid');
			$JobId=$this->input->post('jobid');


			$check_rec_count = $this->db->query("SELECT COUNT(*) AS rec_count FROM neo_job.candidate_placement WHERE candidate_id=? AND job_id=?", array($CandidateId, $JobId))->row()->rec_count;
                        
			if ($check_rec_count>0)
			{
				$is_update = $this->employer->do_update_offered_status($data);
				if ($is_update)
				{
					echo json_encode(array('status' => TRUE, 'msg_info' => "Offered status has been updated successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving Offered details')));
				}
			}
			else
			{
                                $mdata['candidate_id'] = $data['candidate_id'];
                                $mdata['job_id'] = $data['job_id'];
                                $mdata['candidate_status_id'] = 12;
                                $this->db->trans_start();
				$this->db->insert('neo_job.candidate_placement', $data);
                                 $this->db->where('job_id', $JobId)->where('candidate_id', $CandidateId)
                                ->set('candidate_status_id', 12) ->update('neo_job.candidates_jobs');
                                $this->db->insert('neo_job.candidates_jobs_logs', $mdata);
                                $this->db->trans_complete();
				if($this->db->trans_status())
				{
                                       
					echo json_encode(array('status' => TRUE, 'msg_info' => "Offered status has been added successfully"));
				}
				else
				{
					echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving Offered details')));
				}
			}
		}
	}
        
        
            
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */

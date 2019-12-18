<?php
/**
 * API :: Policy api model  
 * @author Sangamesh 
**/
class Api_model extends CI_Model
{
	/**
	 * Default Constructor
	 *
	 * @return m_common
	 */

	public function __construct()
    {
        parent::__construct();
    }
	

	function get_user_list($franchise_id=0, $search_string='',$pg=0,$limit=10)
	{
		$cond=''; $order_by='';
		if($franchise_id)
	    {
	        $cond=" and a.franchise_id=$franchise_id  ";
	        $order_by=" order by a.created_on desc limit $pg,$limit";
	    }

	    $search_string=mysql_real_escape_string(urldecode(trim($search_string)));
		if($search_string)
		{
			$cond = " and (a.order_id='".$search_string."' or g.mobile_number='".$search_string."' ) ";
			$order_by=" order by a.created_on desc";
		}
		
	    $policy_order_detail_rec=$this->db->query("SELECT count(distinct a.id) as total_rec
			                          from t_policy_details a
			                          left join m_policy_types b on b.id=a.policy_type_id
			                          left join t_personal_details c on c.id=a.policy_holder_id
			                          left join m_premium_slabs d on d.id=a.premium_slab_id
			                          left join m_clients e on e.client_id=c.client_id
			                          left join m_nominee f on f.policy_order_id=a.order_id
			                          left join t_contact_details g on g.policy_order_id=a.order_id
			                          where 1 $cond")or die('<pre>'.mysql_error().'</pre>');

	    $total_records=$policy_order_detail_rec->row()->total_rec;

	    if(!$total_records)
			return array('status'=>'error','error_code'=>2002,'error_msg'=>"No transactions found");

		else
		{
			
			$policy_order_detail_info=$this->db->query("SELECT a.id,ifnull(a.pb_policy_number,'') as pb_policy_number,a.premium_slab_id,a.premium_amount,a.payment_amount,if(a.payment_status=1,'Paid','Unpaid') as payment_status,ifnull(a.payment_ref_no,'') as payment_ref_no,ifnull(a.payment_partner,'') as payment_partner,a.order_id, date_format(a.created_on,'%d-%b-%Y') as created_on,a.appl_form_type,a.appl_form_status,a.appl_filled_type,
                          a.policy_type_id,a.app_form_status,ifnull(a.physical_doc_path,'') as physical_doc_path,ifnull(a.digital_copy_link,'') as digital_copy_link,b.policy_name,concat(c.name_of_insured,' ',ifnull(c.lastname_of_insured,''))  as insured_name, c.name_of_insured as insured_firstname,c.lastname_of_insured as insured_lastname,c.gender as insured_gender,date_format(c.dob,'%d-%b-%Y')as insured_dob,if(c.marital_status=1,'Yes','No') as marital_status,c.father_husband_name,d.coverage_amount,ifnull(g.address1,'') as address1 ,ifnull(g.address2,'') as address2,g.state,g.district,
                          g.city,g.pin,g.mobile_number,c.email_id,ifnull(c.physical_deformity_detail,'') as physical_deformity_detail,ifnull(c.Illness_disease_detail,'') as Illness_disease_detail,if(a.status=0,'pending',if(c.status=1,'Approved','Rejected')) as status_msg,e.name as client_name,
                          ifnull(f.nominee_name,'') as nominee_name,IFNULL(DATE_FORMAT(f.nominee_dob,'%d-%b-%Y'),'') AS nominee_dob, IFNULL(f.nominee_relationship,'') AS nominee_relationship
                          from t_policy_details a
                          left join m_policy_types b on b.id=a.policy_type_id
                          left join t_personal_details c on c.id=a.policy_holder_id
                          left join m_premium_slabs d on d.id=a.premium_slab_id
                          left join m_clients e on e.client_id=c.client_id
                          left join m_nominee f on f.policy_order_id=a.order_id
                          left join t_contact_details g on g.policy_order_id=a.order_id
                          where 1 $cond group by a.id $order_by") or die('<pre>'.mysql_error().'</pre>');

			$policy_data=array();
			$index=0;
			foreach ($policy_order_detail_info->result_array() as $po)
			{
				$dependants_rec=$this->db->query("SELECT a.id,a.name,ifnull(date_format(a.dob,'%d-%b-%Y'), '') as dob,ifnull(a.relationship,'') as relationship,ifnull(a.dependant_gender,'') as dependant_gender
															from t_dependants a
															left join t_policy_details b on b.order_id=a.policy_order_id
															where b.id=? order by a.dob,FIELD(relationship,'husband','wife','father','mother')",$po['id'])or die('<pre>'.mysql_error().'</pre>');
				$dependants_details=$dependants_rec->result_array();
				$dependant_data=array();
				$i=1;
				foreach ($dependants_details as $do) 
				{
					$title1='';
					if(strtolower($do['relationship'])=='wife' or strtolower($do['relationship'])=='husband')
						$title1='Spouse';
					else if($i<=2)
						$title1="Child_1";
					else
						$title1="Child_2";
					$dependants=array('id'=>$do['id'],$title1."_name"=>$do['name'],$title1."_gender"=>$do['dependant_gender'],$title1."_dob"=>$do['dob'], $title1."_relationship"=>$do['relationship']);
					$dependant_data[]=$dependants;
				$i++;
				}

				$policy_data[$index]['policy_details']=$po;
				$policy_data[$index]['dependants_details']=$dependant_data;
			$index++;
			}

			$ttl_res_curr= $policy_order_detail_rec->num_rows();
			
			$pg_count_msg = "Showing ".(1+$pg)." to ".($ttl_res_curr+$pg)." of ".$total_records;
			
			return array('status'=>'success','rdata'=>array('total_records'=>$total_records,'policy_list'=>$policy_data,'pg'=>$pg,'limit'=>$limit,'pg_count_msg'=>$pg_count_msg,'file_path'=>base_url() ));

		}


	}	

	function do_candidate_signup($data,$user_id=0)
	{
			if($data)
			{
				//$text_pwd=$data['password'];
				if(!$user_id)
				{
					$text_pwd=123456;
					$EncryptedPassword=$this->db->query("select users.fn_get_encrypted_password('".$text_pwd."')")->result()[0]->fn_get_encrypted_password;
					$data['password']=$EncryptedPassword;
					$this->db->insert('users.candidates', $data);
				  	if($this->db->affected_rows())
				  		return $this->db->insert_id();
				  	else
				  		return false;
			  	}
			  	else
			  	{
			  		$this->db->update('users.candidates', $data,array('id'=>$user_id));
				  	if($this->db->affected_rows())
				  		return true;
				  	else
				  		return false;
			  	}
			}
			else
				return false;

	}
	function do_process_login($user_mobile=0,$password=0)
	{
		if($user_mobile and $password)
		{
			$result_rec=$this->db->query("SELECT C.id as user_id,C.mobile,
												C.name as candidate_name,C.email,
												C.aadhaar_num,
											 	C.gender_code,
											 	C.dob
				                    		FROM users.candidates C
				                    		WHERE C.mobile=$user_mobile and C.password=CRYPT($otp_string,C.password)
				                    		LIMIT 1");
		}
	}
	
	function get_candidate_questions($RequestData)
	{
		$ResponseData  = array();
		$CandidateId = $RequestData['candidate_id'];
		$LanguageId = $RequestData['language_id'];
		$strQuery = "SELECT 	question_id,
								question_text,	
								option1_text,
								option2_text,
								option3_text,
								option4_text,
								option5_text,
								question_img,
								option1_img,
								option2_img,
								option3_img,
								option4_img,
								option5_img,
								part_id,
								part_name,
								section_id,
								section_name,
								question_type_name,
								CASE question_type_id
									WHEN 1 THEN 5
									WHEN 2 THEN 4
									WHEN 3 THEN 2
									ELSE 0
								END AS option_count
					FROM 	content.fn_get_candidate_assessment_questions($CandidateId,$LanguageId)
					ORDER BY 	part_id, 
								section_id";
		$CandidateQuestionArray = $this->db->query($strQuery)->result_array();
		foreach ($CandidateQuestionArray as $CandidateQuestion)
		{
			$ResponseData[] = array(
				'question_id' => $CandidateQuestion['question_id'],
				'question' => $CandidateQuestion['question_text'],
				'option1' => $CandidateQuestion['option1_text'],
				'option2' => $CandidateQuestion['option2_text'],
				'option3' => $CandidateQuestion['option3_text'],
				'option4' => $CandidateQuestion['option4_text'],
				'option5' => $CandidateQuestion['option5_text'],
				'question_img' => $this->get_image_data($CandidateQuestion['question_img']),
				'option1_img' => $this->get_image_data($CandidateQuestion['option1_img']),
				'option2_img' => $this->get_image_data($CandidateQuestion['option2_img']),
				'option3_img' => $this->get_image_data($CandidateQuestion['option3_img']),
				'option4_img' => $this->get_image_data($CandidateQuestion['option4_img']),
				'option5_img' => $this->get_image_data($CandidateQuestion['option5_img']),
				'part_id' => $CandidateQuestion['part_id'],
				'part_name' => $CandidateQuestion['part_name'],
				'section_id' => $CandidateQuestion['section_id'],
				'section_name' => $CandidateQuestion['section_name'],
				'question_type' => $CandidateQuestion['question_type_name'],
				'option_count' => $CandidateQuestion['option_count']
			);
		}

		return $ResponseData;
	}

	public function get_part_questions($CandidateId = 0, $LanguageId = 0)
	{
		$ResponseData = array();

		$strQuery = "SELECT  
						question_id,
						question_text,	
						option1_text,
						option2_text,
						option3_text,
						option4_text,
						option5_text,
						question_img,
						option1_img,
						option2_img,
						option3_img,
						option4_img,
						option5_img,
						part_id,
						part_name,
						section_id,
						section_name,
						question_type_name,
						CASE 
						WHEN question_type_id=1 THEN 5
						WHEN question_type_id=2 THEN 4
						WHEN question_type_id=2 THEN 2
						END AS option_count
						FROM content.fn_get_candidate_questions($CandidateId,$LanguageId)
						order by part_id, section_id";
		$CandidateQuestionArray = $this->db->query($strQuery)->result_array();
		foreach ($CandidateQuestionArray as $CandidateQuestion)
		{
			$ResponseData[] = array(
				'question_id' => $CandidateQuestion['question_id'],
				'question' => $CandidateQuestion['question_text'],
				'option1' => $CandidateQuestion['option1_text'],
				'option2' => $CandidateQuestion['option2_text'],
				'option3' => $CandidateQuestion['option3_text'],
				'option4' => $CandidateQuestion['option4_text'],
				'option5' => $CandidateQuestion['option5_text'],
				'question_img' => $this->get_image_data($CandidateQuestion['question_img']),
				'option1_img' => $this->get_image_data($CandidateQuestion['option1_img']),
				'option2_img' => $this->get_image_data($CandidateQuestion['option2_img']),
				'option3_img' => $this->get_image_data($CandidateQuestion['option3_img']),
				'option4_img' => $this->get_image_data($CandidateQuestion['option4_img']),
				'option5_img' => $this->get_image_data($CandidateQuestion['option5_img']),
				'part_id' => $CandidateQuestion['part_id'],
				'part_name' => $CandidateQuestion['part_name'],
				'section_id' => $CandidateQuestion['part_id'],
				'section_name' => $CandidateQuestion['section_name'],
				'question_type' => $CandidateQuestion['question_type_name'],
				'option_count' => $CandidateQuestion['option_count']
			);
		}

		return $ResponseData;
	}

	function get_image_data($ImageName)
	{
		$strImgData = "";
		if (!is_null($ImageName))
			if (trim($ImageName) != '') {
				$ImagePath = realpath(QUESTION_IMAGES) . "/" . $ImageName;
				if (file_exists($ImagePath)) {
					$ImgFileInfo = new finfo(FILEINFO_MIME_TYPE);
					$ImgMimeType = get_mime_by_extension($ImagePath);
					$strImgData  = base64_encode(file_get_contents($ImagePath));
				}
			}

		return $strImgData;
	}
	function get_parts()
	{
		$strQuery = "SELECT  	id AS part_id,
								code AS part_code,
								name AS part_name
					 FROM    	content.parts
					 ORDER BY	id";
		$ResponseData = $this->db->query($strQuery)->result_array();
		return $ResponseData;
	}

	function get_sample_questions($LanuageId = 1)
	{
		$ResponseData  = array();
		$Parameters = array( 1 => $LanuageId );

		$strQuery = "SELECT *,
							CASE 
							WHEN question_type_id=1 THEN 5
							WHEN question_type_id=2 THEN 4
							WHEN question_type_id=2 THEN 2
							END AS option_count
							FROM content.fn_get_sample_questions(?)
							order by part_id, section_id";
		$ResponseData = $this->db->query($strQuery, $Parameters)->result_array();
		return $ResponseData;
	}

	function get_instructions($part_id = -1, $language_id = 1)
	{
		$RequestData = array(
			1 => $part_id,
			2 => $language_id
		);

		$strQuery = "SELECT * FROM content.fn_get_instructions(?, ?)";
		$ResponseData = $this->db->query($strQuery, $RequestData)->result_array();
		echo json_encode($ResponseData);
	}

	function get_sample_instructions($language_id = 1)
	{
		$RequestData = array(1 => $language_id);

		$strQuery = "SELECT * FROM content.fn_get_sample_instructions(?)";
		$ResponseData = $this->db->query($strQuery, $RequestData)->result_array();
		echo json_encode($ResponseData);
	}

	public function get_center($location_id=0)
	{
		$cond='';
		if($location_id)
			$cond= 'and c.location_id='.$location_id;

		$center_recs=$this->db->query("SELECT c.id as center_id, c.name as center_name,
												s.id as state_id,d.id as district_id
												from users.centers c
												left join master.district d on c.location_id=d.id
												left join master.state s on s.id=d.state_id
												where 1=1 $cond");
		if($center_recs->num_rows())
		{
			return $center_recs->result_array();
		}
		else
			return false;
	}

	function submit_candidate_responses($RequestData)
	{
		$ResponseData = array
		(
			"status" 		=> "FAILURE",
			"response_id" 	=> 0
		);
		$Parameters = array(
							$RequestData['candidate_id'],
							$RequestData['assessment_id'],
							$RequestData['completed_part_index'],
							$RequestData['completed_section_index'],
							$RequestData['response_list'],
							$RequestData['assessment_status']
							);
		$ResponseData  = array();

		$assessment_rec=$this->db->query("SELECT * from assessment.fn_get_assessment_status(?,?)",array($RequestData['candidate_id'],$RequestData['assessment_id']));		
		$assessment_detail=$assessment_rec->row_array();

		if($RequestData['assessment_status']==2)
		{
			$this->db->query("UPDATE assessment.assessments
								SET counseling_status=1
								WHERE candidate_id=?
								AND id=?",array($RequestData['candidate_id'],$RequestData['assessment_id']));
		}
		if($assessment_detail['assessment_status_id']==-1)
		{

			$ResponseData = array("status" => "RESET",
								  "response_id" =>0);
		}
		else
		{
			$strQuery = "SELECT * FROM assessment.fn_submit_candidate_responses(?,?,?,?,?,?)";
			$ResponseId = $this->db->query($strQuery, $Parameters)->row()->fn_submit_candidate_responses;

			if (intval($ResponseId) > 0)
			{			
				

				$ResponseData = array("status" => "SUCCESS",
									  "response_id" => $ResponseId,
									  "assessment_status" => $assessment_detail['assessment_status_name'],
									  "counseling_status" => $assessment_detail['counseling_status_name']);
			}
			else
			{
				$ResponseData = array("status" => "FAILURE",
									  "response_id" => 0);
			}
		}
		return $ResponseData;
	}

	function get_counseling_slot($user_id=0,$center_id=0,$counseling_date='')
	{
		
		if($center_id)
		{
			$available_dates_rec=$this->db->query("SELECT * 
													FROM assessment.available_slots
													WHERE slot_date=?
													AND center_id=?",array($counseling_date,$center_id));
			if($available_dates_rec->num_rows())
			{
				
				$slot_recs=$this->db->query("SELECT *,
												CASE 
													when status_id=1 then 'availabe'
													when status_id=2 then 'reserved'
													when status_id=3 then 'booked'
													when status_id=4 then 'completed'
													else 'expired'
												END as status_msg
											FROM assessment.fn_get_available_counseling_slots(?,?)",array($user_id,$counseling_date));
				return $slot_recs->result_array();
			}
			else
				return false;
		}
		else
			return false;
	}

	function save_counseling_slot($RequestData)
	{
		
		$reserved_status_recs=$this->db->query("SELECT ap.assessment_id,ap.counseling_slot_id,sc.slot_name as slot_name,TO_CHAR(ap.counseling_date,'dd-Mon-yyyy') as counseling_date,ap.remarks,ap.status,
												'Reserved' as status_msg
												FROM assessment.counseling_appointments ap
												LEFT JOIN assessment.slot_config sc ON sc.id=ap.counseling_slot_id
												WHERE ap.candidate_id=?
												AND ap.status=1
												ORDER BY ap.counseling_date desc 
												LIMIT 1",$RequestData['candidate_id']);
		if($reserved_status_recs->num_rows())
		{
			return array('slot_status'=>0,'reserved_slots'=>$reserved_status_recs->result_array());
		}
		else
		{
			
			$counseling_recs=$this->db->query("SELECT * 
												FROM assessment.counseling_appointments
												WHERE candidate_id=?
												AND counseling_date=?",array($RequestData['candidate_id'],$RequestData['counseling_date']));
			if($counseling_recs->num_rows())
			{
				$this->db->query("UPDATE assessment.counseling_appointments
									SET status=1, counseling_slot_id=?, remarks=?
									WHERE candidate_id=?
									AND counseling_date=?",array($RequestData['counseling_slot_id'],'Booked',$RequestData['candidate_id'],$RequestData['counseling_date']));
			}
			else
				$this->db->insert('assessment.counseling_appointments',$RequestData);
			
			if($this->db->affected_rows())
			{
				$appointment_id=$this->db->insert_id();
				
				$counseling_log=array('appointment_id'=>$appointment_id,
								  	'operation_status'=>3,
  				 				  	'remarks'=>'Booked the slot',
  				 				  	'log_date'=>date('d-M-Y H:i:s'));
				$this->db->insert('assessment.counseling_logs',$counseling_log);
				
				$this->db->update('assessment.assessments',array('counseling_status'=>1),array('candidate_id'=>$RequestData['candidate_id'],'id'=>$RequestData['assessment_id']));
				$slot_recs=$this->db->query("SELECT assessment_id,counseling_slot_id,remarks,status,
												CASE
													WHEN status=1 then 'Scheduled'
													WHEN status=2 then 'Completed'
													WHEN status=3 then 'Cancelled'
													ELSE 'Not Scheduled'
												END as status_text
												FROM assessment.counseling_appointments
												WHERE id=?",$appointment_id);
				return array('slot_status'=>1,'booked_slots'=>$slot_recs->result_array());
			}
			else
				return false;
		}
	}

	function do_cancel_counseling_slot($RequestData)
	{
		$remarks="'".'Cancelled'."'";
			$this->db->query("update assessment.counseling_appointments
								set status=3, remarks=$remarks
								WHERE candidate_id=?
								AND assessment_id=?
								AND center_id=?",array($RequestData['candidate_id'],$RequestData['assessment_id'],$RequestData['center_id']));
		if($this->db->affected_rows())
		{
			$appointment_id=$this->db->query("SELECT id 
								FROM assessment.counseling_appointments
								WHERE candidate_id=?
								AND assessment_id=?
								AND center_id=?",array($RequestData['candidate_id'],$RequestData['assessment_id'],$RequestData['center_id']))->row()->id;
			
			$counseling_log=array('appointment_id'=>$appointment_id,
								  'operation_status'=>3,
  				 				  'remarks'=>'Cancel the booked slot',
  				 				  'log_date'=>date('d-M-Y H:i:s'));
			$this->db->insert('assessment.counseling_logs',$counseling_log);
			$this->db->query("UPDATE assessment.assessments
									SET counseling_status=1
									WHERE candidate_id=?
									AND id=?",array($RequestData['candidate_id'],$RequestData['assessment_id']));
			return true;
		}
		else
			return false;
	}

	function do_get_counseling_history($candidate_id=0)
	{

		$counseling_history_rec=$this->db->query("SELECT cl.appointment_id,ca.assessment_id, ca.counseling_slot_id,sc.slot_name,ca.counseling_date,ca.remarks,ca.status,h.name as center_name,
													CASE
													WHEN COALESCE(ca.status,0)=1 THEN  'Booked'
													WHEN COALESCE(ca.status,0)=2 THEN  'Completed'
													WHEN COALESCE(ca.status,0)=3 THEN  'Cancelled'
													END As status_msg
													FROM assessment.counseling_logs cl
													LEFT JOIN assessment.counseling_appointments ca on ca.id=cl.appointment_id
													LEFT JOIN assessment.slot_config sc on sc.id=ca.counseling_slot_id
													LEFT JOIN users.candidates c on c.id=ca.candidate_id
													INNER JOIN  users.centers h on c.district_id=h.location_id
													WHERE ca.candidate_id=?
													ORDER BY ca.status",$candidate_id);
		if($counseling_history_rec->num_rows())
			return $counseling_history_rec->result_array();
		else
			return false;
	}
}

?>
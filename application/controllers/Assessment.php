<?php defined('BASEPATH') OR exit('No direct script access allowed');

ini_set('session_cache_limiter','private');
    session_cache_limiter(FALSE);
/**
 * Assessment :: Assessment Controller
 * @author by george.s@navriti.com
**/
include APPPATH.'/controllers/Pramaan.php';

class Assessment extends Pramaan
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Assessment_model","assessment");
	}

	//BEGIN: NAVRITI CAREER ASSESSMENTS - By George
	public function question_papers()
	{
		$user = $this->pramaan->_check_module_task_auth(true);
		$data['page'] = 'question_papers';
		$data['module'] = "assessment";
		$data['title'] = 'Question Papers';
		$data['user_group_id'] = $user['user_group_id'];
		$data['user_id'] = $user['id'];
		$this->load->view('index', $data);
	}

	public function get_question_paper_data()
	{
		error_reporting(E_ALL);
		$requestData = $_REQUEST;
		$resp_data = $this->assessment->get_question_paper_data($requestData);
		echo json_encode($resp_data);
	}

}


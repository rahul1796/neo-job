<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin :: Admin management controller 
 * @author by Sangamesh<sangamesh.p@mpramaan.in_Nov-2016>
**/
include APPPATH.'/controllers/Pramaan.php';

class Admin extends Pramaan 
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model("Admin_model","admin");
		$this->load->model("Common_model","common");
	}

	

}

/* End of file Admin.php */
/* Location: ./system/application/controllers/admin.php */
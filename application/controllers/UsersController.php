<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UsersController extends MY_Controller {

  protected $userFields= [ 'name', 'email', 'pwd', 'user_role_id', 'reporting_manager_id', 'reporting_manager_role_id', 'employee_id'];

  protected $redirectUrl = 'userscontroller/index';

  protected $adminReportees = [2,18];
  protected $msg = '';

  public function __construct() {
    parent::__construct();
    $this->load->model("Pramaan_model", "pramaan");
      $this->load->model('User', 'user');
      $this->load->library('session');
    $this->load->helper('custom_functions_helper');
  }

  public function index() {
    $this->authorize(add_edit_view_user_roles());
    $this->load->view('layouts/header');
		$this->load->view('users/index');
    $this->load->view('layouts/footer');
	}

  public function create() {
    $this->authorize(add_edit_view_user_roles());
    $data = $this->setData();
    $data['data']['action'] = 'create';
    $this->loadFormViews('create', $data);
  }

  public function store() {
    $this->authorize(add_edit_view_user_roles());
    $data = $this->setData();

    $data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
    $data['data']['fields']['updated_by'] = $data['data']['fields']['created_by'];
    if($this->validateRequest()){
      $data['data']['fields']['reporting_manager_role_id'] = (in_array((integer)$data['data']['fields']['user_role_id'], $this->adminReportees)) ? 1 : $data['data']['fields']['reporting_manager_role_id'];
      if($this->user->save($data['data']['fields'])) {
        $this->msg = 'User created successfully';
      } else {
        $this->msg = 'Error creating user, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');

    } else {
      $data['data']['action'] = 'create';
    $this->loadFormViews('create', $data);

    }
  }

  public function edit($id) {
    $this->authorize(add_edit_view_user_roles());
    $reportees_count = count($this->user->findReportiesByManager($id));

    $data = $this->setData($id);
    $data['id'] = $id;
    $data['data']['reportees_count'] = $reportees_count;
    $data['data']['action'] = 'edit';
    $this->loadFormViews('edit', $data);
  }

  public function update($id) {
    $this->authorize(add_edit_view_user_roles());
    $data = $this->setData($id);
    $data['id'] = $id;
    //$data['data']['fields']['created_by'] = $this->session->userdata('usr_authdet')['id'];
    if($this->validateRequest()){
      // echo var_dump(array_diff($data['data']['fields'], $this->exclude_fields));
      // exit;
      $data['data']['fields']['updated_by'] = $this->session->userdata('usr_authdet')['id'];
      $data['data']['fields']['updated_at'] = date('Y-m-d H:i:s');
      $data['data']['fields']['reporting_manager_role_id'] = (in_array((integer)$data['data']['fields']['user_role_id'], $this->adminReportees)) ? 1 : $data['data']['fields']['reporting_manager_role_id'];
      if ($this->user->update($id, $data['data']['fields'])) {
        $this->msg = 'User updated successfully';
      } else {
        $this->msg = 'Error updating user, please try again after sometime';
      }
      $this->session->set_flashdata('status', $this->msg);
      redirect($this->redirectUrl, 'refresh');
    } else {
      $reportees_count = count($this->user->findReportiesByManager($id));

    $data['data']['action'] = 'edit';
    $data['data']['reportees_count'] = $reportees_count;
    $this->loadFormViews('edit', $data);
    }
  }


  public function show($id) {

  }

  public function delete() {

  }

  private function setData($id=0) {
    $data['user_det'] = $this->session->userdata('usr_authdet');
    $data['data']['fields'] = $this->userData($this->userFields, $id);
    $data['data']['user_roles_options'] = $this->user->getUserRoles();
    $data['data']['regions_options'] = $this->user->getRegions();
    $data['data']['user_id']=$id;
    $data['data']['centers'] = $this->user->getCenters();
    return $data;
  }

  private function validateRequest($data=null) {
    $this->load->library('form_validation');
    $this->form_validation->set_error_delimiters('<span class="text text-danger">', '</span>');
    $this->form_validation->set_rules('name', 'Name', 'required|max_length[30]');
    $this->form_validation->set_rules('pwd', 'Password','required|min_length[8]|max_length[15]');
     $this->form_validation->set_rules('employee_id', 'Employee ID', 'required|callback_check_duplicate_employee_id[neo_user.users]|exact_length[6]');
     if(in_array($this->input->post('user_role_id'), [9,11,12,13,14])){
        $this->form_validation->set_rules('centers[]', 'Center Managers', 'required');
     }
    $this->form_validation->set_rules('user_role_id', 'User Role', 'required|is_natural_no_zero', ['is_natural_no_zero'=>'Select a Valid User Role']);
    if(!in_array((integer)$this->input->post('user_role_id'), $this->adminReportees)) {
      $this->form_validation->set_rules('reporting_manager_role_id', 'Manager Role', 'required|is_natural_no_zero', ['is_natural_no_zero'=>'Select a Valid Manager Role']);
    } else {
      $this->form_validation->set_rules('reporting_manager_role_id', 'Manager Role', 'is_natural', ['is_natural_no_zero'=>'Select a Valid Manager Role']);
    }
    $this->form_validation->set_rules('reporting_manager_id', 'Reporting Manager', 'required|is_natural_no_zero', ['is_natural_no_zero'=>'Select Reporting Manager']);
    $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_check_duplicate_email[neo_user.users]|max_length[50]');
    //$this->form_validation->set_rules('region_id', 'Region', 'required|is_natural_no_zero', array('is_natural_no_zero'=>'Select Region'));
    return $this->form_validation->run();
  }

  private function loadFormViews($action, $data=null) {
    $this->load->view('layouts/header');
 		$this->load->view('users/'.$action, $data);
    $this->load->view('layouts/footer');
  }


  private function userData($fields_array, $id=0) {
    $fields = array();
    $data = array();

    if ($id==0) {
      foreach($fields_array as $field_name) {
        $fields[$field_name] = '';
      }
      $data['centers'] = $this->input->post('centers') ?? [];
    } else {
      $fields = $this->user->find($id);
      $data['centers'] = $this->input->post('centers') ?? $this->user->getAssociatedCenters($id);
    }

    foreach($fields_array as $field_name) {
      $data[$field_name] = $this->input->post($field_name) ?? $fields[$field_name];
    }
    return $data;
  }

  public function getReportingManager($id) {
    echo json_encode($this->user->getReportingManager($id));
    exit;
  }

  public function getReportingManagerRoles($id) {
    echo json_encode($this->user->getReportingManagerRoles($id));
    exit;
  }



  /*public function CheckEmailRegisteredStatus()
  {
    $response = array(
        'status' => true,
        'message' => ''
    );

    $email = $this->input->post('email_reset');
    if(!$this->user->findEmail($email))
    {
      $response['status'] = false;
      $response['message'] = '* Email is not registered with us!';
    }
    echo json_encode($response);
  }*/



    public function send_reset_password_info_mail()
    {
      $response = array();

      $email = $this->input->post('email_reset');
      if(!$this->user->findEmail($email)) {
        $response['status'] = false;
        $response['message'] = '* Email is not registered with us!';
        echo json_encode($response);
        exit;
      }

      $this->generatePasswordResetLink($email);

      $response = array(
          'status' => true,
          'message' => 'Password Reset link has been sent to Your Registered Mail Address'
      );

      echo json_encode($response);

     /* $email = $this->input->post('email_reset');

      if($this->user->findEmail($email)) {
        $this->generatePasswordResetLink($email);
        $this->msg = 'Password Reset link has been sent to Your Registered Mail Address';
      } else {
        $this->msg = 'Email is not registered with us';
      }

      $this->session->set_flashdata('message', $this->msg);
      redirect(base_url('pramaan/home'), 'refresh');*/
    }

    public function changePassword() {
      $email = $this->input->post('email');
      $password = $this->input->post('new_password');
      if($this->user->updatePassword($email, $password)) {
        $this->msg = 'Password updated successfully';
      } else {
        $this->msg = 'Error updating Password, please try again after sometime';
      }
      $this->session->set_flashdata('message', $this->msg);
      redirect(base_url('pramaan/home'), 'refresh');
    }

  public function generatePasswordResetLink($email) {

    $this->load->library('encryption');
    $this->encryption->initialize(array('driver' => 'openssl'));

    $expiry_date= date("Y/m/d");
    $email_id = $email;
    //$dd = 'dd='.urlencode(md5($expiry_date));
    $dd = 'dd='.urlencode(base64_encode($this->encryption->encrypt(time())));
    $em ='em='.urlencode(base64_encode($this->encryption->encrypt($email_id)));

    // echo $em;
    // echo '<br>';
    //echo $this->encryption->decrypt(urldecode("=d330125c80a6adddd5c6c99a2772f5af3e78abf9d71b5f1008abc38D18dLFmT%2B1mVA0b=S9MQwL5Jal303B6vV5HpXBNu8IaPrkmNTmBjHDZW1hIqunEu"));
    // exit;

    $redirect_url = base_url("pramaan/change_password?{$dd}&{$em}");//"http://localhost/ln_neo/pramaan/change_password?{$dd}&{$em}";
    $html = "Copy this link and open in browser <br> {$redirect_url}";

    $this->load->library('email');

    $this->email->initialize($this->emailConfig());

    $this->email->from('neo.helpdesk@labournet.in', 'Tech Support Labournet', 'aakash2971993@hotmail.com');
    $this->email->to($email);

    $this->email->subject('Password Reset Instruction');
    $this->email->message($html);

    $this->email->send();


  }

  /***** SUMIT****/
   public function updateUserPassword() {
      $id = $this->session->userdata('usr_authdet')['id'];
      $confirmpassword = $this->input->post('confirmpassword');
      if($this->user->updateUserPassword($id, $confirmpassword))
        {

                echo json_encode(array('status' => TRUE, 'msg_info' => "You have to login again!"));
        }
        else
        {
                echo json_encode(array('status' => FALSE, 'errors' => array('Error occurred while saving Password')));
        }
    }
}

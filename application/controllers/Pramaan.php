<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('session_cache_limiter', 'private');
session_cache_limiter(FALSE);

/**
 * Pramaan :: master controller
 * @author by Sangamesh<sangamesh.p@mpramaan.in_Nov-2016>
 **/

class Pramaan extends CI_Controller
{

    var $is_logged_in = 0;

    public function __construct()
    {
        error_reporting(E_ALL);
        parent::__construct();
        $this->load->helper('role_helper');
        $this->load->library('user_agent');
        ini_set('display_errors', 1);
        $this->load->model("Pramaan_model", "pramaan");
        $this->load->model("jobs_model", "jobs");
        $this->load->model("availablejobs_model", "available");
        $this->load->model('Sale', 'sale');
        $this->load->model('Candidate', 'candidate');
    }

    /**
     * default function redirect to login page
     */
    public function index()
    {
        $this->pramaan->_check_module_task_auth(false);
    }

    /**
     * function for load dashboard
     */

    /********** Common functions **********/
    public function dashboard()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $referer_id            = 0;
        $data['page']          = 'dashboard';
        $data['title']         = 'Dash Board';
        $data['user_group_id'] = $user['user_group_id'];
        if ($user['user_group_id'] == 1)
            $referer_id = 0;
        elseif ($user['user_group_id'] == 4)
            $referer_id = $user['id'];
        $data['statistics'] = $this->pramaan->admin_dashboard_statistics($referer_id);
        $this->load->model('Dashboard', 'dashboard');

        // echo print_r($this->session->userdata);
        // exit;

        if(!isset($_SESSION['user_hierarchy'])) {
          $hierarchy_data=[];
          if($this->session->userdata['usr_authdet']['user_group_id']==15) {
              $hierarchy_data = $this->dashboard->getUserHierarchy(1);
          } else {
              $hierarchy_data = $this->dashboard->getUserHierarchy($this->session->userdata['usr_authdet']['id']);
          }
          $this->session->userdata['user_hierarchy'] = array_column($hierarchy_data, 'user_id');
        }

        // echo print_r($this->session->user_hierarchy);
        // exit;
        $data['data']['total_openings'] = $this->dashboard->getJobOpeningsCount();
        $data['data']['total_employer'] = $this->dashboard->getEmployerCount();
        $data['data']['total_candidates'] = $this->dashboard->getCandidatesCount();
        $data['data']['total_jobs'] = $this->dashboard->getJobsCount();
        $data['data']['total_leads'] = $this->dashboard->getLeadCount();

        $data['data']['interested_candidates'] = $this->dashboard->getInterestedCandidatesCount();
        $data['data']['pending_candidates'] = $this->dashboard->getPendingCandidatesCount();
        $data['data']['profiled_candidates'] = $this->dashboard->getProfileCandidatesCount();
        $data['data']['interview_candidates'] = $this->dashboard->getInterviewCandidatesCount();
        $data['data']['selected_candidates'] = $this->dashboard->getSelectedCandidatesCount();
        $data['data']['offered_candidates'] = $this->dashboard->getOfferedCandidatesCount();
        $data['data']['joined_candidates'] = $this->dashboard->getJoinedCandidatesCount();
        $data['data']['not_joined_candidates'] = $this->dashboard->getNotjoinedCandidates();

        $data['data']['drafted_jobs'] = $this->dashboard->getDraftedJobsCount();
        $data['data']['open_jobs'] = $this->dashboard->getOpenJobsCount();
        $data['data']['closed_jobs'] = $this->dashboard->getClosedJobsCount();
        $data['data']['on_hold_jobs'] = $this->dashboard->getOnHoldJobsCount();

        $data['data']['lead_identified'] = $this->dashboard->getLeadIdentifiedCount();
        $data['data']['initial_meeting_schedule'] = $this->dashboard->getInitialMeetingScheduleCount();
        $data['data']['initial_meeting_completed'] = $this->dashboard->getInitialMeetingCompletedCount();
        $data['data']['op_lost_at_entry_level'] = $this->dashboard->getOpLostAtEntryLevelCount();
        $data['data']['follow_up_meeting_schedual'] = $this->dashboard->getFollowUpMeetingScheduleCount();
        $data['data']['follow_up_meeting_completed'] = $this->dashboard->getFollowUpMeetingCompletedCount();
        $data['data']['op_lost_at_follow_up_level'] = $this->dashboard->getOpLostAtFollowUpLevelCount();
        $data['data']['proposal_shared'] = $this->dashboard->getProposalSharedCount();
        $data['data']['proposal_under_review'] = $this->dashboard->getProposalUnderReviewCount();
        $data['data']['proposal_under_rfe'] = $this->dashboard->getProposalUnderRFECount();
        $data['data']['negotiation_count'] = $this->dashboard->getNegotiationCount();
        $data['data']['proposal_accepted_count'] = $this->dashboard->getProposalAcceptedCount();
        $data['data']['op_lost_at_proposal_level'] = $this->dashboard->getOpLostAtProposalLevelCount();
        $data['data']['contract_signed'] = $this->dashboard->getContractSignedCount();
        $data['data']['contract_not_signed'] = $this->dashboard->getContractNotSignedCount();
        $data['data']['lead_convert_to_client'] = $this->dashboard->getLeadConvertToClientCount();
        $data['data']['on_hold'] = $this->dashboard->getOnHoldCount();

        $this->load->view('index', $data);
    }

    /**
     * function for load the home page
     */
    public function home()
    {
        $this->_check_login(false);

        //$Query = "UPDATE neo_job.jobs SET job_expiry_date=null,job_status_id=1 WHERE COALESCE(job_expiry_date,'01-01-1900')='01-01-1900'";
       // $Query = "UPDATE neo_job.jobs SET job_expiry_date=null WHERE COALESCE(job_expiry_date,'01-01-1900')='01-01-1900'";
        //$this->db->query($Query);

        //$Query = "UPDATE neo_job.jobs SET job_status_id=3 WHERE job_expiry_date IS NOT NULL AND CURRENT_DATE>job_expiry_date AND job_status_id<>3";
       // $this->db->query($Query);

        //$Query = "UPDATE neo_job.jobs SET job_status_id=1 WHERE COALESCE(job_status_id,0)=0";
        //$this->db->query($Query);

        $data['page']  = 'home_page';
        $data['title'] = 'Home';
        $this->load->view('index', $data);
    }

    /**
     * function for load the change Password
     */


    public function change_password()
    {


        $this->_check_login(false);
        $this->load->library('encryption');
        $this->encryption->initialize(array('driver' => 'openssl'));

        $encyptedDate = base64_decode(urldecode($this->input->get('dd')));
        $encryptedEmail = base64_decode(urldecode($this->input->get('em')));

        $date =  $this->encryption->decrypt($encyptedDate); //$encyptedDate;//
        $email = $this->encryption->decrypt($encryptedEmail);

        if((time() - $date) < (24 * 60 * 60)) {
            $data['page']  = 'change_password';
            $data['title'] = 'Change Password';
            $data['email'] = $email;
            $this->load->view('index', $data);
        } else {
            $this->session->set_flashdata('message', 'This Link is expired. Kindly generate Password Reset link again.');
            redirect(base_url('pramaan/home'), 'refresh');
        }

    }


    /**
     * function for login process
     */
    public function process_login()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('username', 'Username', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');
        $this->form_validation->set_rules('_check_login', 'username', 'callback__checkvalidlogin');

        $test1 = 1;

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array('status' => 0, 'msg_info' => validation_errors()));
        }
        else {
            $this->session->set_flashdata('notify_msg', 'Logged in successfully');
            echo json_encode(array('status' => 1, 'msg_info' => 'Login Success'));
        }
    }

    /**
     * login checking process
     * @param unknown_type $str
     * @return boolean
     */
    function _checkvalidlogin($str)
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');



        if ($username && $password) {
            $sql = "SELECT u.id, u.email, p.id AS user_group_id, p.name AS role_name FROM neo_user.users AS u LEFT JOIN
                    neo_user.user_roles AS p ON u.user_role_id = p.id WHERE LOWER(u.email) = ? AND u.pwd = ? AND u.is_active=TRUE";
            $res = $this->db->query($sql, array(trim(strtolower($username)), trim($password)));

            if ($res->num_rows()) {
                $user_det = $res->row_array();

                    $this->session->set_userdata('usr_authdet', $user_det);

                    $dateTime = date("Y-m-d H:i:s");
                    $ip = $this->input->ip_address();
                    $_SERVER['REMOTE_ADDR'];
                    $session_id=hash('ripemd160',time()+$this->session->userdata('usr_authdet')['id']);
                    $post_data=array(
                       "session_id"=>$session_id,
                       "user_id"=>$this->session->userdata('usr_authdet')['id'], // user_id you have to get from session data
                       "browser_name"=>$this->agent->browser(),
                       "platform"=>$this->agent->platform(),
                       "ip_address"=>$ip,
                       'login'=>$dateTime,
                    );
                    $this->db->insert('neo_user.user_logs',$post_data);
                    $this->session->set_userdata(['session_id'=>$session_id]);
                    return true;

            }
            else {
                $this->form_validation->set_message('_checkvalidlogin', 'Invalid login details');
            }
        }
        else {
            $this->form_validation->set_message('_checkvalidlogin', 'Invalid login details');
        }
        return false;
    }

    /**
     * function for check the user loged or not
     * @param unknown_type $redirect
     */
    function _check_login($redirect = false)
    {

        if ($this->session->userdata('usr_authdet')) {
            $this->is_logged_in = 1;
            redirect('pramaan/dashboard');
        }
        else {
            $this->is_logged_in = 0;
            if ($redirect) {
                $this->session->set_flashdata('notify_msg', 'Please Login...');
                redirect('');
            }
        }
    }

    /**
     * function for check the existing user email
     * @param unknown_type $redirect
     */
    function _unique_email($email = '')
    {
        //$id=$_REQUEST['id'];
        $id    = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $email = $this->input->post('email');
        if ($email)
            $cond = " and lower(email)= ? ";
        if ($id)
            $cond .= " and id!=$id";
        $result = $this->db->query("select email from users.accounts where 1=1 $cond", strtolower($email));
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_email', 'Email already exists!');
            return FALSE;
        }
        else {
            return true;
        }
    }

    public function _valid_phone($phone = '')
    {

        //return true;

        $match = '/^\(?[0-9]{3}\)?[-. ]?[0-9]{3}[-. ]?[0-9]{4}$/';

        if (preg_match($match, $phone)) {
            return true;
        }
        else {

            $this->form_validation->set_message('_valid_phone', 'Please enter a valid number!');
            //echo $phone;
            return false;
        }


    }

    public function _unique_user_phone($mobile = '')
    {
        $cond = '';
        $id   = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        if ($mobile)
            $cond = " and phone='$mobile'";
        if ($id)
            $cond .= " and id!=$id";

        $result = $this->db->query("select phone from users.vw_get_contact_details where 1=1 $cond");
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_user_phone', 'Phone number already exists!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    /**
     * function for check the existing center mobile
     * @param unknown_type $redirect
     */
    function _unique_center_phone($mobile = '')
    {
        //-----when update the same phone exist for other than the id
        $cond = '';
        $id   = $_REQUEST['id'];
        if ($mobile)
            $cond = " and phone='$mobile'";
        if ($id)
            $cond .= " and id!=$id";
        //-----ends
        $result = $this->db->query("select phone from users.centers where 1=1 $cond");
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_center_phone', 'Phone number already exists!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    /**
     * function for check the existing partner phone
     * @param unknown_type $redirect
     */

    function _valid_name($name = '')
    {

        if (!preg_match("/^[a-zA-Z’'. -]*$/", $name)) {
            $this->form_validation->set_message('_valid_name', 'Name should contain only alphabet ,hyphen and space');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }


    /**
     * function for check the existing associate phone
     * @param unknown_type $redirect
     */
    function _unique_associate_phone($mobile = '')
    {
        $cond = '';
        $id   = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        if ($mobile)
            $cond = " and phone='$mobile'";
        if ($id)
            $cond .= " and user_id!=$id";
        $result = $this->db->query("select phone from users.associates where 1=1 $cond");
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_associate_phone', 'Phone number already exists!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    /**
     * function for check the existing employer phone
     * @param unknown_type $redirect
     */
    function _unique_employer_phone($mobile = '')
    {
        $cond = '';
        $id   = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        if ($mobile)
            $cond = " and phone='$mobile'";
        if ($id)
            $cond .= " and user_id!=$id";
        $result = $this->db->query("select phone from users.employers where 1=1 $cond");
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_employer_phone', 'Phone number already exists!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    /**
     * function for check the existing candidate phone
     * @param unknown_type $redirect
     */
    function _unique_candidate_phone($mobile = '')
    {
        $cond       = '';
        $id         = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : 0;
        $referer_id = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : 0;
        if ($mobile)
            $cond = " and mobile='$mobile'";
        if ($id)
            $cond .= " and id!=$id";

        $result = $this->db->query("select mobile from users.candidates where 1=1 $cond");
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_candidate_phone', 'Phone number already exists!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    /**
     * function for check the existing candidate phone
     * @param unknown_type $redirect
     */
    function _unique_candidate_email($email = '')
    {
        $cond  = '';
        $id    = isset($_REQUEST['candidate_id']) ? $_REQUEST['candidate_id'] : 0;
        $email = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
        if ($email)
            $cond = " and email='$email'";
        if ($id)
            $cond .= " and id!=$id";

        $result = $this->db->query("select email from users.candidates where 1=1 $cond");
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_candidate_email', 'Email  already exists!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    // validate birthday
    function _validateAge($birthday = '')
    {

        // $birthday can be UNIX_TIMESTAMP or just a string-date.
        if (is_string($birthday)) {
            $birthday = date('d-m-Y', strtotime($birthday));
            $birthday = strtotime($birthday);
        }

        // check
        // 31536000 is the number of seconds in a 365 days year.
        if (time() - $birthday < MIN_CANDIDATE_AGE * 31536000) {
            $this->form_validation->set_message('_validateAge', 'Age must be minimum 18 years!');
            return false;
        }

        if (time() - $birthday > MAX_CANDIDATE_AGE * 31536000) {
            $this->form_validation->set_message('_validateAge', 'Age must be maximum 25 years!');
            return false;
        }

        return true;
    }

    function _validateMinAge($min_age = 0)
    {
        if ($min_age >= intval(MIN_CANDIDATE_AGE))
            return true;
        else {
            $this->form_validation->set_message('_validateMinAge', 'Age must be minimum 18 years!');
            return false;
        }
    }

    function _validateMaxAge($max_age = 0)
    {
        /*if($max_age>intval(MAX_CANDIDATE_AGE))
            return true;
        else
        {
            $this->form_validation->set_message('_validateMaxAge', 'Max Age must be maximum 25 years!');
            return false;
        }*/


    }

    /**
     *log out function
     */
    public function logout()
    {
        $dateTime = date("Y-m-d H:i:s");

        $post_data=array(
            //"user_id"=>$this->session->userdata('usr_authdet')['id'],
            'logout'=>$dateTime,
        );
        $this->db->where('session_id', $this->session->userdata('session_id'));
        $this->db->update('neo_user.user_logs',$post_data);
        $this->session->unset_userdata(['usr_authdet', 'user_hierarchy']);
        $this->session->set_flashdata('notify_msg', 'Logged out successfully');
        redirect('pramaan/home');
    }

    function reset_password()
    {
        $email  = trim($this->input->post('email_reset'));
        $output = $this->pramaan->doforget($email);
        if ($output['status'])
            $this->session->set_flashdata("notify_msg", $output['info']);
        else
            $this->session->set_flashdata("notify_msg", $output['error']);
        redirect('pramaan/home');
    }

    /**
     *function for about us
     */
    function aboutUs()
    {
        $this->_check_login(false);
        $data['page']   = 'aboutus';
        $data['title']  = 'About Us';
        $data['module'] = "pramaan";
        $this->load->view('index', $data);
    }

    /**
     *function for corporate
     */
    function corporate()
    {
        $this->_check_login(false);
        $data['page']   = 'corporates';
        $data['title']  = 'Corporates';
        $data['module'] = "pramaan";
        $this->load->view('index', $data);
    }

    /**
     *function for partners
     */
    function partners()
    {
        $data['page']   = 'partners';
        $data['title']  = 'Partners';
        $data['module'] = "pramaan";
        $this->load->view('index', $data);
    }

    /**
     *function for add contactus
     */
    function contactUs()
    {
        $this->_check_login(false);
        $data['page']   = 'contactus';
        $data['title']  = 'Contact Us';
        $data['module'] = "pramaan";
        $this->load->view('index', $data);
    }

    /**
     *function for send contactus
     */
    function send_contactus()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('user_name', 'Your Name', 'required');
        $this->form_validation->set_rules('mobile', 'mobile', 'required|integer');
        // set form validation rules
        $username = $this->input->post("user_name");
        $mobile   = $this->input->post("mobile");
        $email    = $this->input->post("email");
        $message  = $this->input->post("message");

        if ($this->form_validation->run() == FALSE) {
            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {

            $output = $this->pramaan->add_contactus($username, $mobile, $email, $message);

            if ($output) {
                echo json_encode(array('status' => TRUE, 'msg_info' => 'Thank You, we will contact you soon.'));
            }
            else {
                echo json_encode(array('status' => FALSE, 'msg_info' => 'Errors Please try again.'));
            }
        }
    }

     public function new_password()
    {
        $this->_check_login(false);
        $data['page']   = 'new_password';
        $data['title']  = 'Reset Password';
        $data['module'] = "pramaan";
        $this->load->view('index', $data);
    }

    /**
     *function for save password
     */

    function _unique_sourcing_head_phone($mobile = '')
    {
        $user_id = $this->input->post('id');
        $cond    = '';
        if ($mobile)
            $cond = " and phone='$mobile'";
        if ($user_id)
            $cond .= " and user_id!=$user_id";
        $result = $this->db->query("select phone from users.sourcing_head where 1=1 $cond");
        if ($result->num_rows()) {
            $this->form_validation->set_message('_unique_sourcing_head_phone', 'Phone number already exists!');
            return FALSE;
        }
        else {
            return TRUE;
        }
    }

    public function save_password()
    {
        $this->_check_login(false);
        /*          $this->load->library('form_validation');
            $this->form_validation->set_rules('email', 'email', 'required');
            $this->form_validation->set_rules('old_password','Old Password','required');
            $this->form_validation->set_rules('new_password', 'New Password', 'required');
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
            {*/
        $old_password = trim($this->input->post('old_password'));
        $new_password = trim($this->input->post('new_password'));
        $email        = trim($this->input->post('email'));
        $result       = $this->db->query("update users.accounts set pwd=crypt('$new_password', gen_salt('bf')) where email='$email' and pwd=crypt('$old_password', users.accounts.pwd)");
        if ($this->db->affected_rows()) {
            echo json_encode(array('status' => true, 'msg_info' => 'Your password has been set successfully'));

        }
        else {
            echo json_encode(array('status' => false, 'msg_info' => 'Please enter valid email id and password'));
        }

        /*  }*/
    }

     public function user_admin_by_id($user_admin_id = 0)
    {
        $user_admin_rec = $this->db->query("SELECT ua.email,usa.*
                                              from users.user_admins usa
                                              inner join users.accounts ua on ua.id=usa.user_id
                                              where user_id=?", $user_admin_id);
        $data['status'] = false;
        if ($user_admin_rec->num_rows()) {
            $data['status']     = true;
            $data['user_admin'] = $user_admin_rec->row_array();
        }
        echo json_encode($data);
    }

    public function save_user_admin()
    {
        $user             = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $password         = $this->input->post('password');
        $submit           = $this->input->post('submit');
        $user_id          = $this->input->post('id');
        $sourcing_head_id = $this->input->post('sourcing_head_id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => ""
            );
            if ($submit == 'add') {
                $data['password']         = $password;
                $data['parent_id']        = $this->input->post('parent_id');
                $data['department_id']    = $this->input->post('department_id');
                $data['user_group_id']    = $this->input->post('user_group_id');
                $data['sourcing_head_id'] = $this->input->post('sourcing_head_id');

                $insert = $this->pramaan->do_add_user_admin($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New Sourcing Admin has been added succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
            else {
                $data1  = array(
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'address' => ""
                );
                $data2  = array('email' => $this->input->post('email'));
                $where1 = array('user_id' => $user_id);
                $where2 = array('id' => $user_id);
                $update = $this->pramaan->do_update_user_admin($data1, $data2, $where1, $where2);
                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'User has been updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
        }
    }


    /**
     *function for recruitment partner
     */


  /*
    public function recruitment_partner()
    {
        $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'recruitment_partner';
        $data['title'] = 'Recruitment Partner';
        $this->load->view('index', $data);
    }*/

    /**
     *function for sourcing partner
     */



    /*public function sourcing_partner($coordinator_id=0)
    {
        $user=$this->pramaan->_check_module_task_auth(true);
        $data['page']='sourcing_partner';
        $data['title']='Sourcing Partner';
        if(!$coordinator_id)
            $coordinator_id=$user['id'];
        $data['coordinator_id']=$coordinator_id;
        $sr_coordinator_id=$this->pramaan->do_get_parent_id($coordinator_id);
        $data['parent_page']="pramaan/sourcing_coordinators/".$sr_coordinator_id;
        $data['parent_page_title']="Sourcing Coordinators";
        $this->load->view('index',$data);
    }*/

    /**
     *function for employers by partner
     */


    /**
     *function for partners by coordinator
     */
    /*public function partners_by_coordinator($coordinator_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_partners_by_coordinator($coordinator_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /**
     *function for job role/qualification pack
     */


   /* public function add_recruitment_partner()
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['role_list'] = $this->db->query("select value as id,name from master.list where code='L0002'")->result_array();
        $data['page']      = 'add_recruitment_partner';
        $data['title']     = 'Recruitment Partner Registration';
        $this->load->view('index', $data);
    }*/

    /**
     *function for add sourcing partner
     */



    /*public function add_sourcing_partner($coordinator_id=0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['role_list']=$this->db->query("select value as id,name from master.list where code='L0002'")->result_array();
        $data['page']='add_sourcing_partner';
        $data['title']='Sourcing Partner Registration';
        $data['coordinator_id']=$coordinator_id;
        $this->load->view('index',$data);
    }*/

    /**
     *function for save sourcing partner
     */



    /**
     *function for save recruitment partner
     */

    /**
     * function for sourcing partner list
     */


    /**
     * function for recruitment partner list
     */
    /*public function recruitment_partner_list()
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_recruitment_partners($requestData);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /**
     * function for get partner partner candidates
     */

    /**
     * function for reset the pramaan pwd
     */



    /**
     * Function for jobs
     * @author Sangamesh.p@pramaan.in
     **/


    /**
     *function for candidates by qualification pack
     */


    /**
     *function for all employers
     */


    // $user_id=$user['id'];
    //  $user_group_id=$user['user_group_id'];
    //  $resp_data=$this->pramaan->get_all_candidates_list($requestData,$user_group_id,$user_id);
    /**
     *function for all candidates
     */


    /**
     *function for new password
     */



    /**
     *function for save sourcing manager
     */


    /**
     *function for save sourcing coordinator
     */
    /*public function save_sourcing_coordinator()
    {
        $user     = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $password = $this->input->post('password');
        $submit   = $this->input->post('submit');
        $user_id  = $this->input->post('id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data        = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => ""
            );
            $district_id = $this->input->post('district_id');
            $arr_string  = implode(',', $district_id);

            $district_id = '{' . $arr_string . '}';

            if ($submit == 'add') {
                $data['password']      = $password;
                $data['parent_id']     = $this->input->post('parent_id');
                $data['department_id'] = $this->input->post('department_id');
                $data['user_group_id'] = $this->input->post('user_group_id');
                $data['district_id']   = $district_id;
                $insert                = $this->pramaan->do_add_sourcing_coordinator($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Sourcing coordinator has been added successfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
            else {
                $data1  = array(
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'address' => "",
                    'district_id' => $district_id
                );
                $data2  = array('email' => $this->input->post('email'));
                $where1 = array('user_id' => $user_id);
                $where2 = array('id' => $user_id);
                $update = $this->pramaan->do_update_sourcing_coordinator($data1, $data2, $where1, $where2);
                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Sourcing coordinator has been updated successfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
        }
    }*/

    /**
     *function for sourcing heads
     */
    /*
    public function sourcing_admins($parent_id=0)
    {
        $user=$this->pramaan->_check_module_task_auth(false);
        if(!$parent_id)
            $parent_id=1;   //default end before admin(root)
        $data['page']='sourcing_admins';
        $data['title']='Sourcing Admins';
        $data['parent_id']=$parent_id;
        $this->load->view('index',$data);
    }

*/
    /**
     * function for sourcing head list
     *//*
    public function sourcing_admins_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);
        $department_id = SOURCING;
        $requestData = $_REQUEST;
        $resp_data = $this->pramaan->get_user_admins($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /**
     *function for adding sourcing head
     */
    /*
    public function add_sourcing_admin($parent_id=0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']='add_admin_user';
        $data['parent_page']="pramaan/sourcing_admins/".$parent_id;
        $data['parent_page_title']="Sourcing Admins";
        $data['module']="pramaan";
        $data['title']='Sourcing Admin Registration';
        $data['parent_id']=$parent_id;
        $data['department_id']=SOURCING;
        $user_group_id=$this->db->query("SELECT value from master.list
                                    where code='L0001' and lower(name)=?",'sourcing admin')->row()->value;
        $data['user_group_id']=$user_group_id;
        $this->load->view('index',$data);
    }
    */

    /**
     *function for sourcing heads
     */
    /*
    public function sourcing_heads($parent_id=0)
    {
        $user=$this->pramaan->_check_module_task_auth(true);
        $data['page']='sourcing_heads';
        $data['title']='Sourcing Heads';
        if(!$parent_id||$parent_id==$user['id'])
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
        $this->load->view('index',$data);
    }

*/
    /**
     * function for sourcing head list
     */

    /*
    public function sourcing_heads_list($parent_id=0)
    {
        error_reporting(E_ALL);
        $user=$this->pramaan->_check_module_task_auth(true);
        $requestData= $_REQUEST;
        $department_id=SOURCING;
        $resp_data=$this->pramaan->get_sourcing_heads($requestData,$parent_id,$department_id);
        echo json_encode($resp_data);  // send data as json format
    }
*/
    /**
     *function for adding sourcing head
     */
    /*
    public function add_sourcing_head($parent_id=0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']='add_sourcing_head';
        $data['parent_page']="pramaan/sourcing_heads/".$parent_id;
        $data['parent_page_title']="Sourcing Heads";
        $data['title']='Sourcing Head Registration';
        $data['parent_id']=$parent_id;
        $data['department_id']=SOURCING;
        $user_group_id=$this->db->query("SELECT value from master.list
                                    where code='L0001' and lower(name)=?",'sourcing head')->row()->value;
        $data['user_group_id']=$user_group_id;
        $data['regions_list']=$this->pramaan->do_get_regions();
        $this->load->view('index',$data);
    }
*/
    /**
     *function for sourcing managers
     */
    // public function sourcing_managers($parent_id = 0)
    // {
    //     $user          = $this->pramaan->_check_module_task_auth(true);
    //     $data['page']  = 'sourcing_managers';
    //     $data['title'] = 'Sourcing Managers';
    //     if (!$parent_id || $parent_id == $user['id']) {
    //         $parent_id                 = $user['id'];
    //         $data['parent_page']       = "";
    //         $data['parent_page_title'] = "";
    //     }
    //     else {
    //         $sr_head_id                = $this->pramaan->do_get_parent_id($parent_id);
    //         $data['parent_page']       = "pramaan/sourcing_heads/" . $sr_head_id;
    //         $data['parent_page_title'] = "Sourcing Heads";
    //     }
    //     $data['parent_id'] = $parent_id;
    //     $this->load->view('index', $data);
    // }

    /**
     * function for sourcing manager list
     */
   /* public function sourcing_managers_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = SOURCING;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_sourcing_managers($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /**
     *function for adding sourcing manager
     */
   /* public function add_sourcing_manager($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_sourcing_manager';
        $data['parent_page']       = "pramaan/sourcing_managers/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Managers";
        $data['module']            = "pramaan";

        $data['title'] = 'Sourcing Manager Registration';
//      $data['sourcing_head_id']=$sourcing_head_id;
        $data['parent_id']     = $parent_id;
        $data['department_id'] = SOURCING;
        $user_group_id         = 6;
        $data['user_group_id'] = $user_group_id;
        $data['state_list']    = $this->db->query("SELECT R.region_name,S.id AS state_id,concat(S.name,' (',R.short_name,')') AS state_name
                                                FROM        master.state AS S
                                                INNER JOIN  master.regions AS R ON R.id=S.region_id
                                                INNER JOIN  users.user_admins AS UA ON S.region_id = ANY(ua.region_id)
                                                WHERE       UA.user_id=?
                                                ORDER BY    region_name,
                                                            state_name", $parent_id)->result_array();
        $this->load->view('index', $data);
    }
*/
    /**
     *function for sourcing co-ordinator
     */
   public function sourcing_coordinators($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'sourcing_coordinators';
        $data['title'] = 'Sourcing Coordinators';
        if (!$parent_id || $parent_id == $user['id']) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_manager_id             = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_managers/" . $sr_manager_id;
            $data['parent_page_title'] = "Sourcing Managers";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    /**
     * function for sourcing co-ordinator list
     */
    public function sourcing_coordinator_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = SOURCING;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_sourcing_coordinators($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }

    /**
     *function for adding sourcing coordinator
     */
    public function add_sourcing_coordinator($parent_id = 0)
    {
        $data['page']              = 'add_sourcing_coordinator';
        $data['parent_page']       = "pramaan/sourcing_coordinators/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Co-ordinators";
        $data['module']            = "pramaan";
        $data['title']             = 'Sourcing Coordinator Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 4;
        $data['user_group_id']     = $user_group_id;
        $data['district_list']     = $this->db->query(" SELECT d.id AS district_id,concat(d.name,' ( ',R.code,')') AS district_name
                                                    FROM        master.district AS d
                                                    INNER JOIN  master.state AS R ON R.id=d.state_id
                                                    INNER JOIN  users.user_admins AS UA ON d.state_id = ANY(ua.state_id)
                                                    WHERE       UA.user_id = ?
                                                    ORDER BY    district_name", $parent_id)->result_array();
        $this->load->view('index', $data);
    }

    /**
     *function for save all user_admins
     */


    /**
     *function for get sourcing head by id
     */

    //BD Admin start here

    /**
     *function for business development team
     */
    /*public function bd_admins($parent_id = 0)
    {
        $parent_id = 1;                //default at root
        $this->pramaan->_check_module_task_auth(false);
        $data['page']      = 'bd_admins';
        $data['title']     = 'Business Development Admin';
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }*/

    /**
     * function for sourcing manager list
     */


    /**
     *function for adding sourcing manager
     */
   /* public function add_bd_admin($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_admin_user';
        $data['parent_page']       = "pramaan/bd_admins";
        $data['parent_page_title'] = "BD Admins";
        $data['title']             = 'BD Admin Registration';
        $data['module']            = "pramaan";
        $data['parent_id']         = $parent_id;
        $data['department_id']     = BUSINESS_DEVELOPMENT;
        $user_group_id             = 13;
        $data['user_group_id']     = $user_group_id;
        $this->load->view('index', $data);
    }*/

    /**
     *function for business development team
     */

    /**
     * function for sourcing managerbd head list
     */


    /**
     *function for adding business head
     */

    /**
     *function for business development managers
     */
   /* public function bd_managers($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'bd_managers';
        $data['title'] = 'Business Development Managers';
        if (!$parent_id || $parent_id == $user['id']) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
            $data['user_group_id']     = $user['user_group_id'];
        }
        else {
            $bd_head_id    = $this->pramaan->do_get_parent_id($parent_id);
            $user_group_id = $user['user_group_id'];

            if ($user_group_id == 12) {
                $data['parent_page']       = "pramaan/bd_heads/" . $bd_head_id;
                $data['parent_page_title'] = "BD Heads";

                $data['user_group_id'] = 12;


            }
            else {

                $data['user_group_id'] = 1; //for super_admin


                $data['parent_page']       = "pramaan/bd_heads/" . $bd_head_id;
                $data['parent_page_title'] = "BD Heads";
            }


        }
        $data['parent_id'] = $parent_id;

        $this->load->view('index', $data);
    }
*/

    /**
     * function for sourcing manager list
     */
    /*public function bd_manager_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = BUSINESS_DEVELOPMENT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_user_admins($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /**
     *function for adding sourcing manager
     */
   /* public function add_bd_manager($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_admin_user';
        $data['parent_page']       = "pramaan/bd_managers/" . $parent_id;
        $data['parent_page_title'] = "BD Managers";
        $data['title']             = 'BD Managers Registration';
        $data['module']            = "pramaan";
//      $data['sourcing_head_id']=$sourcing_head_id;
        $data['parent_id']     = $parent_id;
        $data['department_id'] = BUSINESS_DEVELOPMENT;
        $user_group_id         = 11;
        $data['user_group_id'] = $user_group_id;
        $this->load->view('index', $data);
    }*/

    /**
     *function for business development managers
     */




    /**
     * function for sourcing manager list
     */
   /* public function bd_coordinator_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = BUSINESS_DEVELOPMENT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_user_admins($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /**
     *function for adding sourcing manager
     */
   /* public function add_bd_coordinator($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_admin_user_bd';
        $data['parent_page']       = "pramaan/bd_coordinators/" . $parent_id;
        $data['parent_page_title'] = "BD Co-ordinators";
        $data['title']             = 'BD Coordinator Registration';
        $data['module']            = "pramaan";
//      $data['sourcing_head_id']=$sourcing_head_id;
        $data['parent_id']     = $parent_id;
        $data['department_id'] = BUSINESS_DEVELOPMENT;
        $user_group_id         = 8;
        $data['user_group_id'] = $user_group_id;
        $this->load->view('index', $data);
    }
*/
    /**
     *function for business development managers
     */


    /**
     * function for sourcing manager list
     */


    /**
     *function for adding sourcing manager
     */


    /**
     *function for business development managers
     */


    /**
     *function for save all user_admins
     */

    /**
     *function for all employers
     */


    /**
     * @author by Sangamesh<sangamesh.p@mpramaan.in_Feb-2017>
     * function for Pramaan job list
     */











 /*   public function change_state_manager_status($state_mgr_id = 0)
    {

        $change_status = $this->pramaan->do_change_state_mgr_status($state_mgr_id);

        if ($change_status) {
            echo json_encode(array('status' => TRUE, 'msg_info' => "State Manager'status has been updated."));
        }
        else {
            echo json_encode(array("status" => false, 'msg_info' => "State Manager'status could not be updated."));
        }

    }*/


    /**
     *function for district coordinators
     */



    //Ends

    //Saurabh Sinha work
   /* function state_managers_view($parent_id = 0)
    {

        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'state_managers_view';
        $data['title'] = 'Sourcing Heads';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);

    }*/

   /* function district_coordinators_view($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'district_coordinators_view';
        $data['title'] = 'Sourcing Heads';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }*/

   /* public function sourcing_admins($parent_id = 0, $user_id = 0)
    {
        $user = $this->pramaan->_check_module_task_auth(false);
        if (!$parent_id)
            $parent_id = 1;   //default end before admin(root)
        $data['page']  = 'sourcing_admins';
        $data['title'] = 'Sourcing Admins';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;

        $data['user_id'] = $user_id;
        $this->load->view('index', $data);
    }


    public function sourcing_admins_view_mode($parent_id = 0, $user_id = 0)
    {
        $user = $this->pramaan->_check_module_task_auth(false);
        if (!$parent_id)
            $parent_id = 1;   //default end before admin(root)
        $data['page']  = 'sourcing_admins_view';
        $data['title'] = 'Sourcing Admins';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;

        $data['user_id'] = $user_id;
        $this->load->view('index', $data);
    }
*/



   /* public function add_sourcing_admin($parent_id = 0, $user_id = 0)
    {

        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_sourcing_admin';
        $data['parent_page']       = "pramaan/sourcing_admins/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Admins";
        $data['module']            = "pramaan";
        $data['title']             = 'Sourcing Admin Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 9;
        $data['user_group_id']     = $user_group_id;
        $data['sourcing_head_id']  = $user_id;
        $this->load->view('index', $data);
    }*/

    /**
     *function for sourcing heads
     */

   /* public function state_managers_list_view($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_state_managers_list_view($parent_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

   /* public function sourcing_admin_list($user_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_sourcing_admin($user_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /*
    public function sourcing_admin_list_all()
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_sourcing_admin_all();
        echo json_encode($resp_data);  // send data as json format
    }
*/
   /* public function sourcing_admin_list_view($user_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $user_id   = $this->session->userdata['usr_authdet']['id'];
        $resp_data = $this->pramaan->get_sourcing_admin_list($user_id);
        echo json_encode($resp_data);  // send data as json format
    }*/


    /*public function sourcing_partners_list_by_district($user_id)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_sourcing_partners_list_by_district($user_id);
        echo json_encode($resp_data);  // send data as json format
    }*/


    /*public function regional_managers_list_by_sh($user_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_regional_managers_list_by_sh($user_id);
        echo json_encode($resp_data);  // send data as json format
    }*/


    /**
     *function for adding sourcing head
     */
   /* public function regional_managers_view($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'regional_managers_view';
        $data['title'] = 'Sourcing Heads';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }*/

    /*public function regional_managers_view_mode($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'regional_managers_view_mode';
        $data['title'] = 'Sourcing Heads';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }*/

    /*public function state_managers_view_mode($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'state_managers_view_mode';
        $data['title'] = 'Sourcing Heads';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }*/

   /* public function district_coordinators_view_mode($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'district_managers_view_mode';
        $data['title'] = 'Sourcing Heads';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }
*/

    /*
    public function regional_managers_view($parent_id=0)
    {
        $user=$this->pramaan->_check_module_task_auth(true);
        $data['page']='regional_managers_view';
        $data['title']='Sourcing Heads';
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
        $this->load->view('index',$data);
    }
*/


    /*public function regional_managers_list_view($parent_id = 0, $table = '')
    {
        if ($table == '')
            $table = 'regional_manager';
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $resp_data = $this->pramaan->get_regional_managers_list_view($table);


        if ($resp_data)
            echo json_encode($resp_data);  // send data as json format
        else
            echo json_encode(array());

        // print_r($resp_data);
        //die;
    }*/

    //regional_managers_list_view
   /* public function dictrict_coordinators_list_view($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_dictrict_coordinators_list_view();
        echo json_encode($resp_data);  // send data as json format
    }*/







    //*****






   /* public function change_qualification_pack_status()
    {
        $qualification_pack_id = $this->input->post("ar[0]");

        if ($this->pramaan->change_qualification_status($qualification_pack_id)) {
            echo json_encode(array("status" => true, "msg_info" => "Status changed."));
        }
        else {

            echo json_encode(array("status" => false, "msg_info" => "Status not changed."));

        }
    }*/




    /* function district_coordinator_list($parent_id = 0)
     {
         error_reporting(E_ALL);
         $user = $this->pramaan->_check_module_task_auth(true);
         $resp_data = $this->pramaan->get_district_coordinator_heads($parent_id);
         echo json_encode($resp_data);  // send data as json format
     }*/







    //after demo testing





    /*public function change_sourcing_partner_status($sourcing_partner_id = 0)
    {


        if ($this->pramaan->do_change_sourcing_partner_status($sourcing_partner_id)) {
            echo json_encode(array("status" => true, "msg_info" => "Status changed."));
        }
        else {

            echo json_encode(array("status" => false, "msg_info" => "Status not changed."));

        }
    }
*/

    //Saurabh Sinha work ends here


    //after demo saurabh sinha work










    //BD Admins

    /*public function sourcing_admins_all($parent_id=0)
        {
            $user=$this->pramaan->_check_module_task_auth(true);
            if(!$parent_id)
                $parent_id=1;   //default end before admin(root)
            $data['page']='sourcing_admins_all';
            $data['title']='Sourcing Admins';
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
            $data['user_id']=$user_id=$this->session->userdata['usr_authdet']['id'];
            $data['user_group_id']=$user['user_group_id'];

            $this->load->view('index',$data);
        }*/










    /* End of file welcome.php */
    /* Location: ./system/application/controllers/welcome.php */

    /*public function change_status()
    {
        $id    = $this->input->post("ar[0]");
        $table = $this->input->post("ar[1]");

        if ($this->pramaan->change_status($id, $table)) {
            echo json_encode(array("status" => true, "msg_info" => "Status changed."));
        }
        else {

            echo json_encode(array("status" => false, "msg_info" => "Status not changed."));

        }
    }*/



    /*  public function bd_regional_managers_list($parent_id = 0)
      {
          error_reporting(E_ALL);
          $user = $this->pramaan->_check_module_task_auth(true);
          $user_id = $this->session->userdata['usr_authdet']['id'];
          $resp_data = $this->pramaan->get_bd_regional_managers_list($user_id);
          if ($resp_data)
              echo json_encode($resp_data);  // send data as json format
          else
              echo json_encode(array());  // send data as json format

          //echo json_encode($resp_data);  // send data as json format
      }*/








   /* public function save_user_admin_bd()
    {
        $user     = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $password = $this->input->post('password');
        $submit   = $this->input->post('submit');
        $user_id  = $this->input->post('id');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => ""
            );
            if ($submit == 'add') {
                $data['password']      = $password;
                $data['parent_id']     = $this->input->post('parent_id');
                $data['user_type_id']  = $this->input->post('user_type_id');
                $data['user_group_id'] = $this->input->post('user_group_id');

                $insert = $this->pramaan->do_add_user_admin_bd($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New User has been added succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
            else {
                $data1  = array(
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'address' => ""
                );
                $data2  = array('email' => $this->input->post('email'));
                $where1 = array('user_id' => $user_id);
                $where2 = array('id' => $user_id);
                $update = $this->pramaan->do_update_user_admin($data1, $data2, $where1, $where2);
                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'User has been updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
        }
    }*/

   /* public function bd_district_coordinator_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        if ($user['user_group_id'] == 1) {

            $resp_data = $this->pramaan->get_bd_district_coordinator_heads_all();

        }
        else {

            $resp_data = $this->pramaan->get_bd_district_coordinator_heads($parent_id);

        }


        echo json_encode($resp_data);  // send data as json format
    }*/

    /*public function bd_executives_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_bd_executive_heads($parent_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    /*
      public function centers_list_by_sourcing_partner($sourcing_partner_id = 0)
        {
            error_reporting(E_ALL);
            $user = $this->pramaan->_check_module_task_auth(true);
            //$active_status = 1;//1 for active states
            $resp_data = $this->pramaan->get_center_list_by_sourcing_partner($sourcing_partner_id);

            if ($resp_data)
                echo json_encode($resp_data);  // send data as json format

            else {
                echo json_encode(array());
            }
        }
    */



    /*public function bd_admin_list_all()
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_bd_admin_all();
        echo json_encode($resp_data);  // send data as json format
    }*/




    /********** Sourcing Department starts here **********/

    /***** Sourcing Head *****/
    public function sourcing_heads($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'sourcing_heads';
        $data['title'] = 'Sourcing Heads';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    public function add_sourcing_head($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_sourcing_head';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 10;
        $data['user_group_id']     = $user_group_id;
        //$data['regions_list']=$this->pramaan->do_get_regions();
        $data['country_list']     = $this->pramaan->do_get_country();
        $country_name             = 'INDIA';
        $query_result             = $this->db->query('select * from master.country where name=?', $country_name)->result()[0]->id;
        $data['country_selected'] = $query_result;

        $this->load->view('index', $data);
    }

    public function save_sourcing_head()
    {

        $user      = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $user_id   = $this->input->post('id');
        $parent_id = $this->input->post('parent_id');
        $password  = $this->input->post('pname');
        $phone     = $this->input->post('phone');
        $email     = $this->input->post('email');
        $country   = $this->input->post('country');
        $submit    = $this->input->post('submit');

        $password  = $this->input->post('password');
        $cpassword = $this->input->post('cpassword');
        /*echo json_encode($user_id);*/
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'user_group_id' => $this->input->post('user_group_id'),
                'parent_id' => $this->input->post('parent_id'),
                'country' => $this->input->post('country')
            );
            if ($submit == 'add') {
                $data['password'] = $password;
                $insert           = $this->pramaan->do_add_sourcing_head($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Sourcing Head Details Added Successfully!'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
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

    public function sourcing_heads_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_sourcing_heads($requestData);
        echo json_encode($resp_data);  // send data as json format
    }

    public function get_regional_managers_for_sr_head()
    {
        error_reporting(E_ALL);
        $sr_head_id = $this->input->post('id');
        $Response   = $this->pramaan->get_regional_managers_for_sr_head($sr_head_id);
        echo json_encode($Response);
    }

    public function edit_sourcing_head($parent_id = 0, $sourcing_head_id = 0)
    {
        /*echo $sourcing_head_id;
        die;*/
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'edit_sourcing_head';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 10;
        $data['user_group_id']     = $sourcing_head_id;
        $data['user_group']        = $user_group_id;
        $data['user_id']           = $sourcing_head_id;

        //$data['regions_list']=$this->pramaan->do_get_regions();
        //get heading_soruce info here
        $data['sourcing_head_info'] = $this->pramaan->get_info('sourcing_head', $sourcing_head_id);

        $this->load->view('index', $data);
    }

    public function change_sr_head_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_sr_head_active_status($RequestData);
        echo json_encode($Response);
    }

     public function edit_sourcing_head_update()
    {

        $user          = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $user_id       = $this->input->post('user_id');
        $user_group_id = $this->input->post('user_group_id');
        /*  $sourcing_admin_id=$this->input->post('sourcing_admin_id');*/

        $parent_id = $this->input->post('parent_id');
        $name      = $this->input->post('pname');
        $phone     = $this->input->post('phone');
        $email     = $this->input->post('email');
        $submit    = $this->input->post('submit');

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                /* 'sourcing_admin_id'=> $sourcing_admin_id,*/
                'parent_id' => $this->input->post('parent_id'),
                'user_id' => $this->input->post('user_id'),
                'user_group_id' => $this->input->post('user_group_id')
            );
            if ($submit == 'edit') {
                /*echo json_encode(array('status'=>$this->input->post('user_group_id'),'msg_info'=>''));    */

                if ($this->pramaan->do_edit_sourcing_head($data) == true) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Sourcing Head updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
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



    /***** Sourcing Admin *****/
    public function sourcing_admins_all($parent_id = 0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        if (!$parent_id)
            $parent_id = 1;   //default end before admin(root)
        $data['page']  = 'sourcing_admins_all';
        $data['title'] = 'Sourcing Admins';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['user_id']       = $user_id = $this->session->userdata['usr_authdet']['id'];
        $data['user_group_id'] = $user['user_group_id'];

        $this->load->view('index', $data);
    }

    public function add_sourcing_admin($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_sourcing_admin';
        $data['title'] = 'Sourcing Admin';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $user_group_id     = 9;
        //$data['dis_list']=$this->db->query('select * from master.district')->result_array();
        $data['user_group_id']      = $user_group_id;
        $data['sourcing_head_list'] = $this->db->query('select * from users.sourcing_head')->result_array();
        $this->load->view('index', $data);
    }

    public function edit_sourcing_admin_update()
    {

        $user              = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $sourcing_admin_id = $this->input->post('sourcing_admin_id');
        /*$user_group_id=$this->input->post('user_group_id');
        */

        $parent_id = $this->input->post('parent_id');
        $name      = $this->input->post('pname');
        $phone     = $this->input->post('phone');
        $email     = $this->input->post('email');
        $submit    = $this->input->post('submit');

        /*$password=$this->input->post('password');

        $cpassword=$this->input->post('cpassword');*/
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'parent_id' => $this->input->post('parent_id'),
                'sourcing_admin_id' => $sourcing_admin_id
            );
            if ($submit == 'edit') {
                /*echo json_encode($this->pramaan->do_edit_sourcing_head($data));*/
                if ($this->pramaan->do_edit_sourcing_admin($data) == true) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Sourcing Admin has been updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
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

    public function save_sourcing_admin()
    {
        $user           = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $sourcing_heads = $this->input->post('sourcing_head');
        $password       = $this->input->post('password');
        $submit         = $this->input->post('submit');
        $user_id        = $this->input->post('id');
        /*$user_id=$this->input->post('id');*/
        /*$sourcing_head_id=$this->input->post('user_id');*/
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('sourcing_head', 'Sourcing Head', 'required');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'sourcing_head' => $this->input->post('sourcing_head'),
                'user_group_id' => $this->input->post('user_group_id'),

            );
            if ($submit == 'add') {
                $data['password'] = $password;
                /*echo json_encode($this->pramaan->do_add_user_admin($data));           */
                $insert = $this->pramaan->do_add_user_admin_superadmin($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Sourcing Admin Details Added Successfully!'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
            else {
                $data1  = array(
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'address' => ""
                );
                $data2  = array('email' => $this->input->post('email'));
                $where1 = array('user_id' => $user_id);
                $where2 = array('id' => $user_id);
                $update = $this->pramaan->do_update_user_admin($data1, $data2, $where1, $where2);
                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'User has been updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
        }
    }

    public function sourcing_admins_list($user_id = 0, $parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_sourcing_admins_all($requestData, $user_id, $parent_id);
        echo json_encode($resp_data);  // send data as json format
    }

     public function edit_sourcing_admin($sourcing_admin_id = 0, $parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'edit_sourcing_admin';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 10;
        $data['sourcing_admin_id'] = $sourcing_admin_id;
        //getting sourcing head id
        $sourcing_head_id         = $this->db->query('select * from users.sourcing_admin where user_id=' . $sourcing_admin_id . '')->result()[0]->created_by;
        $data['sourcing_head_id'] = $sourcing_head_id;
        $data['user_group']       = $user_group_id;

        //$data['regions_list']=$this->pramaan->do_get_regions();
        //get heading_soruce info here
        $data['sourcing_admin_info'] = $this->pramaan->get_info('sourcing_admin', $sourcing_admin_id);

        $this->load->view('index', $data);
    }

    public function change_sr_admin_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_sr_admin_active_status($RequestData);
        echo json_encode($Response);
    }

    /***** Sourcing Regional Manager *****/
    public function regional_managers($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'regional_managers';
        $data['title'] = 'Regional Managers';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['user_group_id'] = $user['user_group_id'];
        $data['parent_id']     = $parent_id;
        $this->load->view('index', $data);
    }

    public function add_regional_manager($parent_id = 0)
    {

        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_regional_manager';
        $data['title'] = 'Add Regional Manager';

        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }

        $active_status = 1;


        $user_group_id         = 20;
        $data['user_group_id'] = $user_group_id;

        $var = $this->pramaan->do_get_unassigned_regions($user_group_id);

        if ($var)
            $data['region_list'] = $var;
        else {
            $data['region_list'] = array();
        }
        /*  $active_status = -1;//-1 for non-existing states


        $data['state_list']=$this->pramaan->get_state_list($data['region_list'],$active_status);*/
        $data['parent_id'] = $parent_id;

        // print_r($var);
        $this->load->view('index', $data);

    }

    public function save_regional_manager()
    {
        $submit = $this->input->post('submit');

        $user = $this->pramaan->_check_module_task_auth(true);
        $this->load->library('form_validation');
        /*$this->form_validation->set_rules('parent_id', 'Partner type', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('user_group_id', 'User Group', 'required|is_natural_no_zero');*/
        $this->form_validation->set_rules('pname', 'Name', 'required|callback__valid_name');

        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        if ($submit == 'add') {

            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');

            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }

        // set form validation rules

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {


            if ($submit == 'update') {


                $region_id_list = $this->input->post('region_id');
                $arr_string     = implode(',', $region_id_list);
                $region_id_list = '{' . $arr_string . '}';

                $data = array(
                    'parent_id' => $this->input->post('parent_id'),
                    'user_group_id' => $this->input->post('user_group_id'),
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'region_id_list' => $region_id_list,
                    'user_id' => $this->input->post('id')

                );


                $insert = $this->pramaan->do_update_regional_manager($data);

//echo json_encode(array('status'=>TRUE,'msg_info'=>'Regional Manager has been updated'));
                //echo json_encode(array('status'=>TRUE,'msg_info'=>$insert));
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Regional Manager has been updated'));
                }
                else {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Regional Manager could not be updated.'));
                }

            }
            else {


                $region_id_list = $this->input->post('region_id');
                $arr_string     = implode(',', $region_id_list);
                $region_id_list = '{' . $arr_string . '}';

                $password = $this->input->post('password');
                $data     = array(
                    'parent_id' => $this->input->post('parent_id'),
                    'user_group_id' => $this->input->post('user_group_id'),
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'region_id_list' => $region_id_list,
                    'password' => $password
                );

                $insert = $this->pramaan->do_add_regional_manager($data);
                //echo json_encode(array('status'=>TRUE,'msg_info'=>$insert));
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New Regional Manager has been added'));
                }
                else {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Regional Manager could not be added.'));
                }

            }

        }

    }

    public function regional_managers_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_regional_managers_list($requestData, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);  // send data as json format
    }

    public function get_state_managers_for_sr_regional_manager()
    {
        error_reporting(E_ALL);
        $sr_rm_id = $this->input->post('id');
        $Response = $this->pramaan->get_state_managers_for_sr_regional_manager($sr_rm_id);
        echo json_encode($Response);
    }

    public function edit_regional_manager($parent_id = 0, $regional_mgr_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['parent_id']             = $parent_id;
        $data['page']                  = 'edit_regional_manager';
        $data['title']                 = 'Edit Regional Manager';
        $data['module']                = "pramaan";
        $data['regional_manager_data'] = $this->pramaan->get_regional_manager_data_by_id($regional_mgr_id)[0];

        $data['parent_id'] = $parent_id;

        $data['user_id'] = $regional_mgr_id;

        $active_status = 1;


        $user_group_id         = 20;
        $data['user_group_id'] = $user_group_id;

        $var = $this->pramaan->do_get_unassigned_regions($user_group_id, $regional_mgr_id);

        if ($var)
            $data['region_list'] = $var;
        else {
            $data['region_list'] = array();
        }

        $this->load->view('index', $data);
    }

    public function change_sr_rm_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_sr_rm_active_status($RequestData);
        echo json_encode($Response);
    }

    /***** Sourcing State Manager *****/
    public function state_managers($parent_id = 0)
    {


        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'state_managers';
        $data['title'] = 'State Managers';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $regional_mgr_id           = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $regional_mgr_id;
            $data['parent_page_title'] = "Regional Managers";
        }
        $data['user_group_id'] = $this->session->userdata['usr_authdet']['user_group_id'];
        $data['parent_id']     = $parent_id;
        $this->load->view('index', $data);
    }

    public function add_state_manager($parent_id = 0)
    {

        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_state_manager';
        $data['title'] = 'Add State Manager';

        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $regional_mgr_id           = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $regional_mgr_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }

        $active_status = 1;

        $data['region_id'] = $this->pramaan->get_region_by_regional_mgr($parent_id);
        /*print_r($data['region_id']);
        die;*/

        $var = $this->pramaan->do_get_unassigned_states($user['id']);
        if ($var) {
            $data['state_list'] = $var;
        }
        else {
            $data['state_list'] = array();
        }
        $user_group_id         = 21;
        $data['user_group_id'] = $user_group_id;
        //-1 for non-existing states

        $data['parent_id'] = $parent_id;
        //print_r($data['state_list']);
        $this->load->view('index', $data);

    }

    public function save_state_manager()
    {
        $submit = $this->input->post('submit');

        $user = $this->pramaan->_check_module_task_auth(true);
        $this->load->library('form_validation');
        /*$this->form_validation->set_rules('parent_id', 'Partner type', 'required|is_natural_no_zero');
            $this->form_validation->set_rules('user_group_id', 'User Group', 'required|is_natural_no_zero');*/
        $this->form_validation->set_rules('pname', 'Name', 'required|callback__valid_name');
        /*$this->form_validation->set_rules('phone','Phone','required|max_length[10]|callback__unique_partner_phone');*/
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        //$this->form_validation->set_rules('state_id','State','required');


        if ($submit == 'add') {

            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');

            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }

        // set form validation rules

        if ($this->form_validation->run() == FALSE) {


            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {

            /*  echo json_encode(array('status'=>TRUE,'msg_info'=>'State Manager could not be updated.'));
*/

            if ($submit == 'update') {

                //echo json_encode(array('status'=>TRUE,'msg_info'=>'State Manager could not be updated.'));
                $state_id_list = $this->input->post('state_id');
                $arr_string    = implode(',', $state_id_list);
                $state_id_list = '{' . $arr_string . '}';

                $data = array(
                    'parent_id' => $this->input->post('parent_id'),
                    'user_group_id' => $this->input->post('user_group_id'),
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'state_id_list' => $state_id_list,
                    'user_id' => $this->input->post('id')

                );


                $insert = $this->pramaan->do_update_state_manager($data);

//echo json_encode(array('status'=>TRUE,'msg_info'=>'Regional Manager has been updated'));
                //echo json_encode(array('status'=>TRUE,'msg_info'=>$insert));
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'State Manager has been updated'));
                }
                else {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'State Manager could not be updated.'));
                }

            }
            else {

                /*echo json_encode(array('status'=>TRUE,'msg_info'=>'State Manager could not be updated.'));*/

                $state_id_list = $this->input->post('state_id');
                $arr_string    = implode(',', $state_id_list);
                $state_id_list = '{' . $arr_string . '}';

                $password = $this->input->post('password');
                $data     = array(
                    'parent_id' => $this->input->post('parent_id'),
                    'user_group_id' => $this->input->post('user_group_id'),
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'state_id_list' => $state_id_list,
                    'password' => $password
                );

                $insert = $this->pramaan->do_add_state_manager($data);
                //echo json_encode(array('status'=>TRUE,'msg_info'=>$insert));
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New State Manager has been added'));
                }
                else {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'State Manager could not be added.'));
                }

            }

        }

    }

    public function edit_state_manager($parent_id = 0, $state_mgr_id = 0)
    {
        $user                       = $this->pramaan->_check_module_task_auth(true);
        $data['parent_id']          = $parent_id;
        $data['page']               = 'edit_state_manager';
        $data['title']              = 'Edit State Manager';
        $data['module']             = "pramaan";
        $data['state_manager_data'] = $this->pramaan->get_state_manager_data_by_id($state_mgr_id)[0];

        $data['parent_id'] = $parent_id;

        $data['user_id'] = $state_mgr_id;

        $active_status = 1;


        $data['region_id'] = $this->pramaan->get_region_by_regional_mgr($parent_id);
        /*print_r($data['region_id']);
        die;*/

        $array_state = $this->pramaan->do_get_unassigned_states($user['id'], $state_mgr_id);

        if ($array_state)
            $data['state_list'] = $array_state;
        else
            $data['state_list'] = array();

        $user_group_id         = 21;
        $data['user_group_id'] = $user_group_id;

        //print_r($array_state);

        $this->load->view('index', $data);
    }

    public function state_managers_list($parent_id = 0)
    {

        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_state_managers_list($requestData, $parent_id);
        echo json_encode($resp_data);
    }

    public function get_district_coordinator_for_sr_state_manager()
    {
        error_reporting(E_ALL);
        $id       = $this->input->post('id');
        $Response = $this->pramaan->get_district_coordinator_for_sr_state_manager($id);
        echo json_encode($Response);
    }

    public function change_state_manager_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_state_manager_active_status($RequestData);
        echo json_encode($Response);
    }

    public function show_state_managers_list($parent_id = 0)
    {

        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_show_state_managers_list($requestData, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);
    }

    public function show_state_managers($parent_id = 0)
    {


        $user           = $this->pramaan->_check_module_task_auth(true);
        $data['page']   = 'show_state_managers';
        $data['module'] = 'pramaan';
        $data['title']  = 'State Managers';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $regional_mgr_id           = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $regional_mgr_id;
            $data['parent_page_title'] = "Regional Managers";
        }
        $data['user_group_id'] = $this->session->userdata['usr_authdet']['user_group_id'];
        $data['parent_id']     = $parent_id;
        $this->load->view('index', $data);
    }


    /***** Sourcing District Coordinator *****/
    public function district_coordinators($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'district_coordinators';
        $data['title'] = 'District Coordinators';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $user_group_id         = $this->session->userdata['usr_authdet']['user_group_id'];
        $data['user_group_id'] = $user_group_id;
        $data['parent_id']     = $parent_id;
        $this->load->view('index', $data);
    }

    public function add_district_coordinators($parent_id = 0)
    {
        $user                      = $this->pramaan->_check_module_task_auth(true);
        $data['page']              = 'add_district_coordinators';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 22;
        $data['user_group_id']     = $user_group_id;
        //$data['regions_list']=$this->pramaan->do_get_regions();


        $var = $this->pramaan->do_get_unassigned_districts($user_group_id, $user['id']);
        if ($var)
            $data['district_list'] = $var;
        else
            $data['district_list'] = array();


        $this->load->view('index', $data);
    }

    function save_district_coordinator()
    {
        $user          = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $user_group_id = $this->input->post('user_group_id');
        $parent_id     = $this->input->post('parent_id');
        $password      = $this->input->post('pname');
        $phone         = $this->input->post('phone');
        $email         = $this->input->post('email');
        $district      = $this->input->post('district');

        $submit = $this->input->post('submit');

        $password  = $this->input->post('password');
        $cpassword = $this->input->post('cpassword');
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'user_group_id' => $this->input->post('user_group_id'),
                'parent_id' => $this->input->post('parent_id'),

            );
            if ($submit == 'add') {

                $district_id_list = $this->input->post('district');
                $arr_string       = implode(',', $district_id_list);
                $district_id_list = '{' . $arr_string . '}';

                $data['district_id_list'] = $district_id_list;
                $data['password']         = $password;
                $insert                   = $this->pramaan->do_add_district_coordinators($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New District Coordinator has been added succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }

        }
    }


    public function district_coordinator_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_district_coordinator_list($requestData, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);

        echo json_encode($resp_data);  // send data as json format
    }


    public function edit_district_coordinator($parent_id = 0, $dis_cor_id = 0)
    {
        /*echo $sourcing_head_id;
        die;*/
        $user                      = $this->pramaan->_check_module_task_auth(true);
        $data['page']              = 'edit_district_coordinator';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 22;

        $data['district_coordinator_id'] = $dis_cor_id;

        $res = $this->pramaan->get_district_coordinator_data_by_id($dis_cor_id)[0];

        if ($res)
            $data['district_coordinator_data'] = $res;
        else {
            $data['district_coordinator_data'] = array();
        }

        /* $data['dis_name'] = $this->db->query('select * from users.district_coordinator where user_id=' . $dis_cor_id . '')->result()[0]->name;
         $data['dis_email'] = $this->db->query('select * from users.district_coordinator where user_id=' . $dis_cor_id . '')->result()[0]->email;
         $data['dis_phone'] = $this->db->query('select * from users.district_coordinator where user_id=' . $dis_cor_id . '')->result()[0]->phone;*/
        $var = $this->pramaan->do_get_unassigned_districts($user_group_id, $user['id'], $dis_cor_id);
        if ($var) {
            $data['dis_list'] = $var;
        }
        else {
            $data['dis_list'] = array();
        }


        /*$query = $this->db->query('select * from users.district_coordinator where user_id=' . $dis_cor_id . '');
        $temp = str_replace(array('}', '{'), '', $query->result()[0]->district_id_list);
        $t = explode(',', $temp);*/
        /*$data['dis_selected'] = $t[0];*/

        // print_r($);
        $this->load->view('index', $data);
    }

    public function change_district_coordinator_active_status()
    {
        $this->pramaan->_check_module_task_auth(true);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_district_coordinator_active_status($RequestData);
        echo json_encode($Response);
    }

    public function show_district_coordinators($parent_id = 0)
    {
        $user           = $this->pramaan->_check_module_task_auth(true);
        $data['page']   = 'show_district_coordinators';
        $data['module'] = 'pramaan';
        $data['title']  = 'District Coordinators';
        if (!$parent_id)
        {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $user_group_id         = $this->session->userdata['usr_authdet']['user_group_id'];
        $data['user_group_id'] = $user_group_id;
        $data['parent_id']     = $parent_id;
        $this->load->view('index', $data);
    }

    public function edit_district_coordinator_update()
    {

        $user                    = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $district_coordinator_id = $this->input->post('district_coordinator_id');
        /*$user_group_id=$this->input->post('user_group_id');*/
        /*  $sourcing_admin_id=$this->input->post('sourcing_admin_id');*/

        $parent_id = $this->input->post('parent_id');
        $name      = $this->input->post('pname');
        $phone     = $this->input->post('phone');
        $email     = $this->input->post('email');

        $submit = $this->input->post('submit');

        /*$password=$this->input->post('password');

        $cpassword=$this->input->post('cpassword');*/
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'parent_id' => $this->input->post('parent_id'),

                'district_coordinator_id' => $this->input->post('id')
            );
            if ($submit == 'edit') {

                $district_id_list = $this->input->post('district');
                $arr_string       = implode(',', $district_id_list);
                $district_id_list = '{' . $arr_string . '}';

                $data['district_id_list'] = $district_id_list;

                if ($this->pramaan->do_edit_district_coordinator($data) == true) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'District Coordinator has been updated succesfully!!'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
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

        }
    }

    public function show_district_coordinator_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_show_district_coordinator_list($requestData, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);  // send data as json format
    }

    public function get_sourcing_partner_for_district_coordinator()
    {
        error_reporting(E_ALL);
        $id       = $this->input->post('id');
        $Response = $this->pramaan->do_get_sourcing_partner_for_district_coordinator($id);
        echo json_encode($Response);
    }

    /***** Sourcing partner *****/
    public function sourcing_partner($district_coordinator_id = 0)
    {
        $user           = $this->pramaan->_check_module_task_auth(true);
        $data['page']   = 'sourcing_partner';
        $data['title']  = 'Sourcing Partner';
        $data['module'] = 'pramaan';
        if (!$district_coordinator_id)
            $district_coordinator_id = $user['id'];
        $data['district_coordinator_id'] = $district_coordinator_id;
        //$sr_coordinator_id=$this->pramaan->do_get_parent_id($district_coordinator_id);
        $data['parent_page']       = "pramaan/sourcing_partner/" . $district_coordinator_id;
        $data['parent_page_title'] = "Sourcing Partners";
        $this->load->view('index', $data);
    }

    public function sourcing_partner_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_sourcing_partner_list($_REQUEST, $parent_id);
        echo json_encode($resp_data);  // send data as json format
    }

    //
   /* public function sourcing_partners($parent_id = 0)
    {


        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'sourcing_partners';
        $data['title'] = 'Sourcing Partner';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $district_coordinator_id   = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $district_coordinator_id;
            $data['parent_page_title'] = "District Coordinator";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }*/


   /* public function sourcing_partners_list($district_coordinator_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_sourcing_partners($district_coordinator_id);
        echo json_encode($resp_data);  // send data as json format
    }*/

    public function centers_list_by_sourcing_partner()
    {
        error_reporting(E_ALL);
        $id       = $this->input->post('id');
        $Response = $this->pramaan->get_center_list_by_sourcing_partner($id);
        echo json_encode($Response);
    }

    public function add_sourcing_partner($coordinator_id = 0, $partner_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['role_list']  = $this->db->query("select value as id,name from master.list where code='L0002'")->result_array();
        $data['page']       = 'add_sourcing_partner';
        $data['partner_id'] = $partner_id;
        $data['title']      = 'Sourcing Partner Registration';

        if ($partner_id) {
            $data['sourcing_partner_data'] = $this->pramaan->get_sourcing_partner_data($partner_id)[0];

            if (!$data['sourcing_partner_data'])
                $data['sourcing_partner_data'] = array();

        }


        // print_r($data['sourcing_partner_data']);


        $data['coordinator_id'] = $coordinator_id;

        $this->load->view('index', $data);
    }

     public function save_sourcing_partner()
    {


        $partner_id = $this->input->post('hdnPartnerId');

        $password        = '';
        $partner_type_id = 0;

        $user = $this->pramaan->_check_module_task_auth(true);
        $this->load->library('form_validation');
        //$this->form_validation->set_rules('partner_type_id', 'Partner type', 'required|is_natural_no_zero');
        $this->form_validation->set_rules('pname', 'Name', 'required|callback__valid_name');

        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if (intval($partner_id) < 1) {

            $this->form_validation->set_rules('partner_type_id', 'Partner type', 'required');


            ///$this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');

            $password = $this->input->post('password');

            $partner_type_id = $this->input->post('partner_type_id');

        }
        // set form validation rules

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {

            $data   = array(
                'partner_id' => $partner_id,
                'partner_type_id' => $partner_type_id,
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'coordinator_id' => $this->input->post('coordinator_id'),
                'password' => $password
            );
            $insert = $this->pramaan->do_save_sourcing_partner($data);
            if ($insert) {
                if (intval($partner_id) < 1) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New sourcing partner has been added'));
                }
                else {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'sourcing partner has been updated'));
                }

            }
            else {
                echo json_encode(array('status' => TRUE, 'msg_info' => 'Sourcing partner could not be added/updated'));
            }
        }
    }


    public function change_sr_partner_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_sr_partner_active_status($RequestData);
        echo json_encode($Response);
    }

    /***** Country *****/
     public function add_country_new()
    {
        $submit = $this->input->post("submit");
        if ($submit == 'add') {
            $resp = $this->pramaan->add_country_model($this->input->post("country"));
            if ($resp)
                echo json_encode(array("status" => true, "name" => $this->input->post("country")));
            else
                echo json_encode(array("status" => false, "name" => $this->input->post("country")));
        }


    }

    public function add_new_country($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_new_country';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Add Country";
        $data['title']             = 'Add New Country';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 10;
        $data['user_group_id']     = $user_group_id;

        //$data['regions_list']=$this->pramaan->do_get_regions();
        $data['country_list'] = $this->pramaan->do_get_country_list_2();

        $this->load->view('index', $data);
    }

    public function add_country($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_country';
        $data['title'] = 'Country';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    /**
     * function for sourcing head list
     */
    public function change_status_country()
    {
        $country_id     = $this->input->post("country_ar[0]");
        $country_status = $this->input->post("country_ar[1]");
        if ($this->pramaan->change_country_status($country_id, $country_status)) {
            if ($country_status == "0")
                echo json_encode(array("status" => true, "msg_info" => "Country Activated"));
            else
                echo json_encode(array("status" => true, "msg_info" => "Country Deactivated"));
        }
        else {

            echo json_encode(array("status" => true, "msg_info" => "Status not changed."));

        }


    }

    public function add_country_list()
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->do_get_country_list();
        echo json_encode($resp_data);  // send data as json format

    }

    /***** Region *****/
    public function regions($parent_id = 0)
    {

        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'regions';
        $data['title'] = 'Regions';
        if (!$parent_id) {
            $parent_id = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);


    }

    public function add_region($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_region';
        $data['title'] = 'Regions';

        if (!$parent_id)
        {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else
        {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }

        /* print_r((string)$this->pramaan->get_user_country_id($parent_id)[0]);
die;*/

        $data['country_id'] = (string)$this->pramaan->get_user_country_id($parent_id)[0];
        $data['parent_id']  = $parent_id;

        $var =  $this->pramaan->do_get_state();
        if (count($var))
        {
            $data['states_list'] = $var;
        }
        else
        {
            $data['states_list'] = array(0=>'-No State found-');
        }
        $this->load->view('index', $data);


    }

    public function save_region($parent_id = 0, $region_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        /*$this->form_validation->set_rules('region_name', 'Region name', 'trim|required|callback_checkDuplicate_region_name');
            $this->form_validation->set_rules('region_short_name', 'Short-name for Region', 'trim|required|callback_checkDuplicate_region_short_name');
            */


        $submit = $this->input->post('submit');

        $this->form_validation->set_rules('region_name', 'Region name', 'trim|required|callback__valid_name|callback_checkDuplicate_region_name');
        $this->form_validation->set_rules('region_short_name', 'Short-name for Region', 'trim|required|callback__valid_name|callback_checkDuplicate_region_short_name');
       /* if ($submit == 'add') {


            // set form validation rules
        }
        else {
            $this->form_validation->set_rules('region_name', 'Region name', 'trim|callback__valid_name|required');
            $this->form_validation->set_rules('region_short_name', 'Short-name for Region', 'trim|required|callback__valid_name');

        }*/

        if ($this->form_validation->run() == FALSE)
        {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else
        {

            $data = array(
                'code' => $this->input->post('region_short_name'),
                'name' => $this->input->post('region_name'),
               /* 'country_id' => (int)$this->input->post('country_id'),*/
                'country_id' => 99,
            );

            $states_under_region_id = $this->input->post('states_under_region');
            $arr_string='';
            if(count($states_under_region_id))
            $arr_string = implode(',', $states_under_region_id);
            $states_under_region_id         = '{' . $arr_string . '}';
            $data['states_under_region_id'] = $states_under_region_id;
            if ($this->input->post('submit') == 'update')
            {

                $update = $this->pramaan->do_update_region($region_id, $data);
                //echo json_encode($update);
                if ($update)
                {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Region has been updated.'));
                }
                else
                {
                    echo json_encode(array("status" => false, 'msg_info' => 'Region could not be updated.'));
                }
            }
            else
            {
                $insert = $this->pramaan->do_add_region($data);
                if ($insert)
                {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New Region has been added.'));
                }
                else
                {
                    echo json_encode(array("status" => false, 'msg_info' => 'New Region could not be added.'));
                }
            }


        }


    }

    public function region_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        /*
                $user = $this->pramaan->_check_module_task_auth(true);

                $usr_country_id = (string)$this->pramaan->get_user_country_id($parent_id)[0];

                $resp_data = $this->pramaan->do_get_states_data($parent_id, $usr_country_id);
        */
        /*
                if ($resp_data) {
                    echo json_encode($resp_data); // send data as json format
                } else {
                    echo json_encode(array());
                }*/
        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData    = $_REQUEST;
        //$usr_country_id = $this->pramaan->get_user_country_id($parent_id)[0];
        $usr_country_id = 99;
        $resp_data      = $this->pramaan->do_get_region_list($_REQUEST, $usr_country_id);
        echo json_encode($resp_data);

    }

    public function get_states_for_region()
    {
        error_reporting(E_ALL);
        $id       = $this->input->post('id');
        $Response = $this->pramaan->do_get_states_for_region($id);
        echo json_encode($Response);
    }

    public function edit_region($parent_id = 0, $region_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['parent_id']   = $parent_id;
        $data['page']        = 'edit_region';
        $data['title']       = 'Edit Region';
        $data['module']      = "pramaan";
        $data['region_data'] = $this->pramaan->get_region_by_id($region_id)[0];
        $active_status=1;
       // $data['states_list'] = $this->pramaan->do_get_state_by_region($region_id, $active_status);

        //$data['country_id'] = (string)$this->pramaan->get_user_country_id($parent_id)[0];
        //echo json_encode($data['region_data']);
        $var =  $this->pramaan->do_get_state();
        if (count($var))
        {
            $data['states_list'] = $var;
        }
        else
        {
            $data['states_list'] = array(0=>'-No State found-');
        }


        $this->load->view('index', $data);
    }

    public function change_region_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_region_status($RequestData);
        echo json_encode($Response);
    }

    /***** State *****/
    public function states($parent_id = 0)
    {
        /*$resp_data=$this->pramaan->get_state_list(1,-1);
    print_r($resp_data);
    die;*/
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'states';
        $data['title'] = 'States';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);


    }

    public function add_state($parent_id = 0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_state';
        $data['title'] = 'States';

        if (!$parent_id)
        {
            $parent_id = $user['id'];
            $data['parent_page'] = "";
            $data['parent_page_title'] = "";
        }
        else
        {
            $sr_admin_id = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page'] = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }

        $active_status = 1; //to fetch active regions only
        $resp_data = $this->pramaan->get_region_list();
        $data['region_list'] = $resp_data;
        $active_status = -1; //to fetch non-existent states only
        $data['state_list'] = $this->pramaan->get_state_list($data['region_list'], $active_status);
        $data['parent_id'] = $parent_id;

        $this->load->view('index', $data);
    }

    public function do_get_region_list()
    {
        error_reporting(E_ALL);
        $resp_data = $this->pramaan->do_get_region_list();
        return json_encode($resp_data);
    }

    public function save_state($parent_id = 0, $region_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        //$this->form_validation->set_rules('region_id', 'Region name', 'trim|required');
        $this->form_validation->set_rules('state_id', 'State name', 'trim|required');
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
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else
        {
            $region_id=(int)$this->input->post('region_id');
            $data = array('region_id' => $region_id,
                          'state_id' => $this->input->post('state_id') );


            if ($this->input->post('submit') == 'update')
            {
                $data['submit']= 'update';
                $districts_under_state_id = $this->input->post('districts_under_state');

                $data['districts_under_state_id'] = $districts_under_state_id;

                $update = $this->pramaan->do_update_state($data);
                if ($update)
                {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'State has been updated.'));
                }
                else
                {
                    echo json_encode(array("status" => false, 'msg_info' => 'State could not be updated.'));
                }
            }
            else
            {

                $data['submit'] = 'add';

                $insert = $this->pramaan->do_update_state($data);
                if ($insert)
                {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New State has been added.'));
                }
                else
                {
                    echo json_encode(array("status" => false, 'msg_info' => 'New State could not be added.'));
                }
            }
        }


    }

    public function state_list_by_region($region_id = 0, $active_status = -1)
    {

        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);
        //$active_status = 1;//1 for active states
        $resp_data = $this->pramaan->get_state_list($region_id, $active_status);
        echo json_encode($resp_data);  // send data as json format
    }

    public function state_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        /*
                $user = $this->pramaan->_check_module_task_auth(true);

                $usr_country_id = (string)$this->pramaan->get_user_country_id($parent_id)[0];

                $resp_data = $this->pramaan->do_get_states_data($parent_id, $usr_country_id);
        */
        /*
                if ($resp_data) {
                    echo json_encode($resp_data); // send data as json format
                } else {
                    echo json_encode(array());
                }*/
        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData    = $_REQUEST;
        $usr_country_id = 99;
        $resp_data      = $this->pramaan->do_get_state_list($_REQUEST, $usr_country_id);
        echo json_encode($resp_data);

    }

    public function get_districts_for_state($state_id=0)
    {
        error_reporting(E_ALL);
        if($state_id)
            $id=$state_id;
        else
            $id=$this->input->post('id');
        $response['status']=false;
        $result = $this->pramaan->do_get_districts_for_state($id);
        if(count($result))
        {
            $response['status']=true;
            $response['district_list']=$result;
        }
        else
            $response['message']='-District not found-';

        echo json_encode($response);
    }

    public function edit_state($parent_id = 0, $state_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['parent_id']= $parent_id;
        $data['page']= 'edit_state';
        $data['title']= 'Edit State';
        $data['module']= "pramaan";
        $data['state_data']= $this->pramaan->get_state_by_id($state_id)[0];
        $data['state_id']= $state_id;
        $active_status= 1;
        $data['region_list'] = $this->pramaan->do_get_regions($parent_id, $active_status);

        $data['districts_list'] = $this->pramaan->do_get_district_by_state($state_id);
        $data['region_list'] = $this->pramaan->do_get_regions($parent_id, $active_status);
        $data['state_manager']='';
        $state_manager_rec=$this->db->query("SELECT name as state_manager FROM users.state_manager where $state_id=ANY(state_id_list)");
        if($state_manager_rec->num_rows())
        $data['state_manager']=$state_manager_rec->row()->state_manager;

        //$data['country_id'] = (string)$this->pramaan->get_user_country_id($parent_id)[0];
        // print_r($data['districts_list']);
        $this->load->view('index', $data);
    }

    public function change_state_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_state_status($RequestData);
        echo json_encode($Response);
    }

    /***** District *****/
    public function districts($parent_id = 0)
    {
        /*$resp_data=$this->pramaan->get_state_list(1,-1);
        print_r($resp_data);
        die;*/
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'districts';
        $data['title'] = 'Districts';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);


    }

    public function add_district($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_district';
        $data['title'] = 'Add District';

        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }

        /* print_r((string)$this->pramaan->get_user_country_id($parent_id)[0]);
die;*/

        //$data['country_id'] = (string)$this->pramaan->get_user_country_id($parent_id)[0];
        $data['state_list'] = $this->pramaan->do_get_states($parent_id);
        /*  $active_status = -1;//-1 for non-existing states


        $data['state_list']=$this->pramaan->get_state_list($data['region_list'],$active_status);*/
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);


    }

    public function new_districts_list_by_state($state_id = 0, $active_status = -1)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $resp_data = $this->pramaan->do_get_district($state_id, $active_status);
        echo json_encode($resp_data);  // send data as json format


    }

    public function save_district($parent_id = 0, $state_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');

        $this->form_validation->set_rules('state_id', 'State name', 'trim|required');
        $this->form_validation->set_rules('district_id', 'District name', 'trim|required');


        // set form validation rules

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {

            $data = array(
                'district_id' => $this->input->post('district_id'),
                'state_id' => $this->input->post('state_id'),

            );

            if ($this->input->post('submit') == 'update') {
                $update = $this->pramaan->do_update_district($data);

                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'District has been updated.'));
                }
                else {
                    echo json_encode(array("status" => false, 'msg_info' => 'District could not be updated.'));
                }
            }
            else {


                $insert = $this->pramaan->do_update_district($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New District has been added.'));
                }
                else {
                    echo json_encode(array("status" => false, 'msg_info' => 'New District could not be added.'));
                }

            }


        }


    }

    public function districts_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->do_get_districts_data($requestData);
        echo json_encode($resp_data); // send data as json format
    }

    public function edit_district($parent_id = 0, $district_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['parent_id']     = $parent_id;
        $data['page']          = 'edit_district';
        $data['title']         = 'Edit District';
        $data['module']        = "pramaan";
        $data['district_data'] = $this->pramaan->get_district_by_id($district_id)[0];
        $data['district_id']   = $district_id;
        $data['state_list']    = $this->pramaan->do_get_states($parent_id);

        //$data['country_id'] = (string)$this->pramaan->get_user_country_id($parent_id)[0];

        $this->load->view('index', $data);
    }

    public function change_district_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_district_status($RequestData);
        echo json_encode($Response);
    }

    public function get_districts_list()
    {
        $districts = $this->pramaan->do_get_districts();
        if ($districts)
            echo json_encode(array('status' => true, 'district' => $districts));
        else
            echo json_encode(array('status' => false));
    }




    public function regions_list($parent_id = 0)
    {
        error_reporting(E_ALL);

        $active_status = 0; //to fetch inactive regiona also
        $user          = $this->pramaan->_check_module_task_auth(true);
        $resp_data     = $this->pramaan->do_get_regions($parent_id, $active_status);

        if ($resp_data) {
            echo json_encode($resp_data); // send data as json format
        }
        else {
            echo json_encode(array());
        }

    }

    public function checkDuplicate_region_name($region_name)
    {
        $region_name = trim($region_name);
        $region_name = strtolower($region_name);

        $id   = isset($_REQUEST['region_id']) ? $_REQUEST['region_id'] : 0;

        $checkDuplicate_status = $this->pramaan->do_check_duplicate_region_name($region_name,$id);



        if ($checkDuplicate_status) {
            return true;
        }
        else {

            $this->form_validation->set_message('checkDuplicate_region_name', 'Region Name already exists.');

            return false;
        }

    }


    public function checkDuplicate_region_short_name($region_short_name)
    {
        $region_short_name = trim($region_short_name);
        $region_short_name = strtolower($region_short_name);

         $id   = isset($_REQUEST['region_id']) ? $_REQUEST['region_id'] : 0;

        $checkDuplicate_status = $this->pramaan->do_check_duplicate_region_short_name($region_short_name,$id);

        if ($checkDuplicate_status) {
            return true;

        }
        else {

            $this->form_validation->set_message('checkDuplicate_region_short_name', 'Region Short-Name already exists.');

            return false;
        }
    }




    public function districts_list_by_state($state_id = 0, $active_status = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $resp_data = $this->pramaan->do_get_district($state_id, $active_status);
        echo json_encode($resp_data);  // send data as json format


    }


  /*  public function change_region_status($region_id = 0)
    {

        $change_status = $this->pramaan->do_change_region_status($region_id);

        if ($change_status) {
            echo json_encode(array('status' => TRUE, 'msg_info' => 'Region status has been updated.'));
        }
        else {
            echo json_encode(array("status" => false, 'msg_info' => 'Region status could not be updated.'));
        }
    }*/

   /* public function change_state_status($state_id = 0)
    {

        $change_status = $this->pramaan->do_change_state_status($state_id);

        if ($change_status) {
            echo json_encode(array('status' => TRUE, 'msg_info' => "State'status has been updated."));
        }
        else {
            echo json_encode(array("status" => false, 'msg_info' => "State'status could not be updated."));
        }
    }*/


    public function state_manager_list_by_region($regional_mgr_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $resp_data = $this->pramaan->do_get_state_managers($regional_mgr_id);


        echo json_encode($resp_data);  // send data as json format


    }

    /*public function change_region_manager_status($regional_mgr_id = 0)
    {

        $change_status = $this->pramaan->do_change_regional_mgr_status($regional_mgr_id);

        if ($change_status) {
            echo json_encode(array('status' => TRUE, 'msg_info' => "Regional Manager'status has been updated."));
        }
        else {
            echo json_encode(array("status" => false, 'msg_info' => "Regional Manager'status could not be updated."));
        }

    }*/


    /**
     *function for regional_manager
     */


    public function district_coordinator_list_by_state($state_mgr_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $resp_data = $this->pramaan->do_get_district_coordinators($state_mgr_id);


        echo json_encode($resp_data);  // send data as json format
    }

    /********** Sourcing Department ends here **********/


    /********** Business Development starts here **********/

    /***** BD Head *****/
    public function bd_heads($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'bd_heads';
        $data['title'] = 'Business Development Heads';
        $parent_id     = $this->session->userdata['usr_authdet']['id'];;
        $data['parent_id']   = $parent_id;
        $data['parent_page'] = "";
        $this->load->view('index', $data);
    }

    public function add_bd_head($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_bd_head';
        $data['parent_page']       = "pramaan/bd_heads/" . $parent_id;
        $data['parent_page_title'] = "BD Heads";
        $data['title']             = 'BD Head Registration';
        $data['module']            = "pramaan";
//      $data['sourcing_head_id']=$sourcing_head_id;
        $data['parent_id']     = $parent_id;
        $data['department_id'] = BUSINESS_DEVELOPMENT;
        $user_group_id         = 12;
        $data['user_group_id'] = $user_group_id;
        $data['country_list']  = $this->pramaan->do_get_country();
        if (!$data['country_list'])
            $data['country_list'] = array();
        $country_name = 'INDIA';
        $query_result = $this->db->query('select * from master.country where name=?', $country_name)->result()[0]->id;
        if ($query_result)
            $data['country_selected'] = $query_result;
        else
            $data['country_selected'] = '';

        $this->load->view('index', $data);
    }

    public function save_bd_head($parent_id = 0)
    {
        $user     = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $password = $this->input->post('password');
        $submit   = $this->input->post('submit');
        $user_id  = $this->input->post('id');
        $country  = $this->input->post('country');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'address' => ""
            );
            if ($submit == 'add') {
                $data['password']      = $password;
                $data['parent_id']     = $this->input->post('parent_id');
                $data['user_type_id']  = $this->input->post('user_type_id');
                $data['user_group_id'] = $this->input->post('user_group_id');
                $data['country']       = $country;

                $insert = $this->pramaan->do_add_bd_head($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Business Development Head has been added succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
            else {
                $data1  = array(
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'address' => ""
                );
                $data2  = array('email' => $this->input->post('email'));
                $where1 = array('user_id' => $user_id);
                $where2 = array('id' => $user_id);
                $update = $this->pramaan->do_update_bd_head($data1, $data2, $where1, $where2);
                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'User has been updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
        }
    }

    public function bd_head_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = BUSINESS_DEVELOPMENT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_bd_head_list();
        echo json_encode($resp_data);  // send data as json format
    }


    public function bd_heads_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_bd_head_list($requestData);
        echo json_encode($resp_data);  // send data as json format
    }

    public function get_bd_regional_managers_for_bd_head()
    {
        error_reporting(E_ALL);
        $bd_head_id = $this->input->post('id');
        $Response   = $this->pramaan->get_bd_regional_managers_for_bd_head($bd_head_id);
        echo json_encode($Response);
    }

    public function edit_bd_head($parent_id = 0, $bd_head_id = 0)
    {
        /*echo $sourcing_head_id;
        die;*/
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'edit_bd_head';
        $data['module']            = 'module';
        $data['parent_page']       = "pramaan/bd_heads/" . $parent_id;
        $data['parent_page_title'] = "Business Development Heads";
        $data['title']             = 'Business Development Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 12;


        //$data['regions_list']=$this->pramaan->do_get_regions();
        //get heading_soruce info here
        $data['bd_head_info']  = $this->pramaan->get_info('bd_head', $bd_head_id);
        $data['user_id']       = $bd_head_id;
        $data['user_group_id'] = $user_group_id;
        $this->load->view('index', $data);
    }

    public function change_bd_head_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_bd_head_active_status($RequestData);
        echo json_encode($Response);
    }

    public function edit_bd_head_update()
    {
        $user          = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $user_id       = $this->input->post('user_id');
        $user_group_id = $this->input->post('user_group_id');
        $parent_id     = $this->input->post('parent_id');
        $name          = $this->input->post('pname');
        $phone         = $this->input->post('phone');
        $email         = $this->input->post('email');
        $submit        = $this->input->post('submit');

        /*$password=$this->input->post('password');

        $cpassword=$this->input->post('cpassword');*/
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'parent_id' => $this->input->post('parent_id'),
                'user_id' => $this->input->post('user_id')
            );
            if ($submit == 'edit') {
                /*echo json_encode(array('status'=>$this->input->post('user_group_id'),'msg_info'=>''));    */

                if ($this->pramaan->do_edit_bd_head($data) == true) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Head updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
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

    /***** BD Admin *****/
    public function bd_admins_all($parent_id = 0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        /*if(!$parent_id)
            $parent_id=1;   //default end before admin(root)*/
        $data['page']   = 'bd_admins_all';
        $data['module'] = 'pramaan';
        $data['title']  = 'BD Admins';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_id']         = $parent_id;
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $bd_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_id']         = $parent_id;
            $data['parent_page']       = "pramaan/bd_admins_all/" . $bd_admin_id;
            $data['parent_page_title'] = "BD Admins";
        }
        $data['user_id']       = $user_id = $this->session->userdata['usr_authdet']['id'];
        $data['user_group_id'] = $user['user_group_id'];
        //echo json_encode($user);
        $this->load->view('index', $data);
    }

    public function add_bd_admin($parent_id = 0)
    {
        $user           = $this->pramaan->_check_module_task_auth(true);
        $data['page']   = 'add_bd_admin';
        $data['module'] = 'pramaan';
        $data['title']  = 'Add BD admin';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $sr_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/sourcing_admins/" . $sr_admin_id;
            $data['parent_page_title'] = "Sourcing Admins";
        }
        $data['parent_id'] = $parent_id;
        $user_group_id     = 13;
        //$data['dis_list']=$this->db->query('select * from master.district')->result_array();
        $data['user_group_id'] = $user_group_id;
        $data['bd_head_list']  = $this->db->query('select * from users.bd_head')->result_array();
        $this->load->view('index', $data);
    }

    public function bd_admin_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = BUSINESS_DEVELOPMENT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_user_admins($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function edit_bd_admin_update()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $submit = $this->input->post('submit');

            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'user_id' => $this->input->post('id')
            );
            if ($submit == 'edit') {

                if ($this->pramaan->do_update_bd_admin($data) == true) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Admin has been updated succesfully!!'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in saving the data')));
                }

            }
        }
    }


    public function save_bd_admin($parent_id = 0)
    {
        $user           = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $sourcing_heads = $this->input->post('bd_head');
        $password       = $this->input->post('password');
        $submit         = $this->input->post('submit');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('bd_head', 'BD-Head', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'bd_head' => $this->input->post('bd_head'),
                'user_group_id' => $this->input->post('user_group_id'),
                'parent_id' => $this->input->post('parent_id')

            );
            if ($submit == 'add') {
                $data['password'] = $password;
                $insert           = $this->pramaan->do_add_bd_admin_superadmin($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'Business Development Admin has been added succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
            else {
                $data1  = array(
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'address' => ""
                );
                $data2  = array('email' => $this->input->post('email'));
                $where1 = array('user_id' => $user['id']);
                $where2 = array('id' => $user['id']);
                $update = $this->pramaan->do_update_user_admin($data1, $data2, $where1, $where2);
                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'User has been updated succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }
        }
    }

    public function bd_admins_list($user_id = 0, $parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_bd_admins_all($requestData, $user_id, $parent_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function edit_bd_admin($parent_id = 0, $bd_admin_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'edit_bd_admin';
        $data['module']            = 'pramaan';
        $data['parent_page']       = "pramaan/bd_heads/" . $parent_id;
        $data['parent_page_title'] = "BD Heads";
        $data['title']             = 'Edit BD Admin';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = BUSINESS_DEVELOPMENT;
        $user_group_id             = 13;
        $data['bd_admin_id']       = $bd_admin_id;

        $data['user_group_id'] = $user_group_id;
        $data['bd_admin_info'] = $this->pramaan->get_info('bd_admin', $bd_admin_id);

        //echo json_encode($data['bd_admin_info']);
        $this->load->view('index', $data);
    }

    public function change_bd_admin_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_bd_admin_active_status($RequestData);
        echo json_encode($Response);
    }

    /***** BD Regional Manager *****/
    public function bd_regional_manager($parent_id = 0)
    {
        //parent_id : BD admin id
        $data['page']       = 'bd_regional_manager';
        $data['module']     = 'pramaan';
        $user_id            = $this->session->userdata['usr_authdet']['id'];
        $data['bd_head_id'] = $this->db->query('select user_id from users.bd_head where user_id in (select created_by from users.bd_admin where user_id=' . $user_id . ')')->result()[0]->user_id;
        $data['parent_id']  = $user_id;//$parent_id;
        $data['title']      = 'Regional Manager';
        $this->load->view('index', $data);

    }

    public function add_bd_regional_manager($parent_id = 0)
    {
        $data['page']   = 'add_bd_regional_manager';
        $data['module'] = 'pramaan';
        $data['title']  = 'Add BD regional manager';
        $active_status  = 1;


        $user_group_id         = 11;
        $data['user_group_id'] = $user_group_id;

        $var = $this->pramaan->do_get_unassigned_regions($user_group_id);

        if ($var)
            $data['region_list'] = $var;
        else {
            $data['region_list'] = array();
        }
        /*  $active_status = -1;//-1 for non-existing states


        $data['state_list']=$this->pramaan->get_state_list($data['region_list'],$active_status);*/
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

     public function save_bd_regional_manager($parent_id = 0)
    {
        $submit = $this->input->post('submit');

        $user = $this->pramaan->_check_module_task_auth(true);
        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'Name', 'required');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }

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
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else
        {
            if ($submit == 'update')
            {
                $region_id_list = $this->input->post('region_id');
                $arr_string     = implode(',', $region_id_list);
                $region_id_list = '{' . $arr_string . '}';

                $data = array(
                    'parent_id' => $this->input->post('parent_id'),
                    'user_group_id' => $this->input->post('user_group_id'),
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'region_id_list' => $region_id_list,
                    'user_id' => $this->input->post('user_id')
                );

                $insert = $this->pramaan->do_update_bd_regional_manager($data);
                if ($insert)
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Regional Manager has been updated'));
                else
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Regional Manager could not be updated.'));
            }
            else
            {
                $region_id_list = $this->input->post('region_id');
                $arr_string     = implode(',', $region_id_list);
                $region_id_list = '{' . $arr_string . '}';

                $password = $this->input->post('password');
                $data     = array(
                    'parent_id' => $this->input->post('parent_id'),
                    'user_group_id' => $this->input->post('user_group_id'),
                    'name' => $this->input->post('pname'),
                    'phone' => $this->input->post('phone'),
                    'email' => $this->input->post('email'),
                    'region_id_list' => $region_id_list,
                    'password' => $password
                );

                $insert = $this->pramaan->do_add_bd_regional_manager($data);
                //echo json_encode(array('status'=>TRUE,'msg_info'=>$insert));
                if ($insert)
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Regional Manager has been added'));
                else
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Regional Manager could not be added.'));
            }
        }
    }

    public function show_bd_managers($parent_id = 0)
    {
        $user           = $this->pramaan->_check_module_task_auth(true);
        $data['page']   = 'show_bd_managers';
        $data['module'] = 'pramaan';
        $data['title']  = 'Business Development Managers';
        if (!$parent_id || $parent_id == $user['id']) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
            $data['user_group_id']     = $user['user_group_id'];
        }
        else {
            $bd_head_id    = $this->pramaan->do_get_parent_id($parent_id);
            $user_group_id = $user['user_group_id'];

            if ($user_group_id == 12) {
                $data['parent_page']       = "pramaan/bd_heads/" . $bd_head_id;
                $data['parent_page_title'] = "BD Heads";

                $data['user_group_id'] = 12;


            }
            else {

                $data['user_group_id'] = 1; //for super_admin
                //$data['bd_heads'] = $this->pramaan->get_bd_head_list

                $data['parent_page']       = "pramaan/bd_heads/" . $bd_head_id;
                $data['parent_page_title'] = "BD Heads";
            }


        }
        $data['parent_id'] = $parent_id;
        //print_r($data['user_group_id']);
        $this->load->view('index', $data);
    }

     public function show_bd_manager_list($parent_id = 0)
    {

        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_show_bd_manager_list($requestData, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);
    }

    public function show_bd_coordinators($parent_id = 0)
    {
        $user           = $this->pramaan->_check_module_task_auth(true);
        $data['page']   = 'show_bd_coordinators';
        $data['module'] = 'pramaan';
        $data['title']  = 'Business Development Cordinator';
        if (!$parent_id || $parent_id == $user['id']) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['user_group_id']     = $user['user_group_id'];
            $data['parent_page_title'] = "";
        }
        else {
            $bd_mngr_id                = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/bd_managers/" . $bd_mngr_id;
            $data['parent_page_title'] = "BD Managers";
            $data['user_group_id']     = $user['user_group_id'];
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    public function show_bd_coordinator_list($parent_id = 0)
    {

        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_show_bd_coordinator_list($requestData, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);
    }

    public function bd_regional_managers_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $bd_head_id  = $this->db->query('SELECT created_by FROM users.bd_admin WHERE user_id = ' . $parent_id . '')->result()[0]->created_by;
        $resp_data   = $this->pramaan->get_bd_regional_managers_list($requestData, $bd_head_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);  // send data as json format
    }

    public function edit_bd_regional_manager($parent_id = 0, $regional_mgr_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['parent_id']             = $parent_id;
        $data['page']                  = 'edit_bd_regional_manager';
        $data['title']                 = 'Edit BD Regional Manager';
        $data['module']                = "pramaan";
        $data['regional_manager_data'] = $this->pramaan->get_regional_manager_data_by_id($regional_mgr_id, 'bd_regional_manager')[0];

        $data['parent_id'] = $parent_id;

        $data['user_id'] = $regional_mgr_id;

        $active_status = 1;
        //get regions here


        $user_group_id         = 11;
        $data['user_group_id'] = $user_group_id;

        $var = $this->pramaan->do_get_unassigned_regions($user_group_id, $regional_mgr_id);

        if ($var)
            $data['region_list'] = $var;
        else
            $data['region_list'] = array();

        $this->load->view('index', $data);
    }

    public function change_bd_rm_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_bd_rm_active_status($RequestData);
        echo json_encode($Response);
    }

    /***** BD District Coordinator *****/
    public function bd_coordinators($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'bd_coordinators';
        $data['title'] = 'Business Development Cordinator';
        if (!$parent_id || $parent_id == $user['id']) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['user_group_id']     = $user['user_group_id'];
            $data['parent_page_title'] = "";
        }
        else {
            $bd_mngr_id                = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/bd_managers/" . $bd_mngr_id;
            $data['parent_page_title'] = "BD Managers";
            $data['user_group_id']     = $user['user_group_id'];
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    public function add_bd_district_coordinators($parent_id = 0)
    {
        $user                      = $this->pramaan->_check_module_task_auth(true);
        $data['page']              = 'add_bd_district_coordinators';
        $data['module']            = 'pramaan';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 8;
        $data['user_group_id']     = $user_group_id;
        //$data['regions_list']=$this->pramaan->do_get_regions();
        //getting district list
        $regions = $this->db->query('select * from users.bd_regional_manager where user_id=' . $parent_id . '')->result()[0]->region_id_list;

        $district = $this->pramaan->do_get_unassigned_districts($user_group_id, $user['id']);
        if ($district)
            $data['district_list'] = $district;
        else
            $data['district_list'] = array();

        $this->load->view('index', $data);
    }

    function save_bd_district_coordinators()
    {
        $user          = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $user_group_id = $this->input->post('user_group_id');
        $parent_id     = $this->input->post('parent_id');
        $password      = $this->input->post('pname');
        $phone         = $this->input->post('phone');
        $email         = $this->input->post('email');


        $submit = $this->input->post('submit');

        $password  = $this->input->post('password');
        $cpassword = $this->input->post('cpassword');
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'user_group_id' => $this->input->post('user_group_id'),
                'parent_id' => $this->input->post('parent_id'),

            );
            if ($submit == 'add') {

                $district_id_list = $this->input->post('district');
                $arr_string       = implode(',', $district_id_list);
                $district_id_list = '{' . $arr_string . '}';

                $data['district_id_list'] = $district_id_list;

                $data['password'] = $password;
                $insert           = $this->pramaan->do_add_bd_district_coordinators($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New District Coordinator has been added succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
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

    public function get_bd_coordinator_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->do_get_bd_coordinator_list($_REQUEST, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);  // send data as json format
    }

     public function edit_bd_district_coordinator_update()
    {

        $user                    = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $district_coordinator_id = $this->input->post('district_coordinator_id');
        /*$user_group_id=$this->input->post('user_group_id');*/
        /*  $sourcing_admin_id=$this->input->post('sourcing_admin_id');*/

        $parent_id = $this->input->post('parent_id');
        $name      = $this->input->post('pname');
        $phone     = $this->input->post('phone');
        $email     = $this->input->post('email');


        $submit = $this->input->post('submit');

        /*$password=$this->input->post('password');

        $cpassword=$this->input->post('cpassword');*/
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'parent_id' => $this->input->post('parent_id'),

                'district_coordinator_id' => $this->input->post('district_coordinator_id')
            );
            if ($submit == 'edit') {
                /*echo json_encode(array('status'=>$this->input->post('user_group_id'),'msg_info'=>''));    */

                $district_id_list = $this->input->post('district');
                $arr_string       = implode(',', $district_id_list);
                $district_id_list = '{' . $arr_string . '}';

                $data['district_id_list'] = $district_id_list;

                if ($this->pramaan->do_edit_bd_district_coordinator($data) == true) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'District Coordinator has been updated succesfully!!'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
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

    public function edit_bd_district_coordinator($parent_id = 0, $dis_cor_id = 0)
    {
        /*echo $sourcing_head_id;
        die;*/
        $user                      = $this->pramaan->_check_module_task_auth(true);
        $data['page']              = 'edit_bd_district_coordinator';
        $data['module']            = 'pramaan';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 8;

        $data['district_coordinator_id'] = $dis_cor_id;
        /*$data['dis_name'] = $this->db->query('select * from users.bd_district_coordinator where user_id=' . $dis_cor_id . '')->result()[0]->name;
        $data['dis_email'] = $this->db->query('select * from users.bd_district_coordinator where user_id=' . $dis_cor_id . '')->result()[0]->email;
        $data['dis_phone'] = $this->db->query('select * from users.bd_district_coordinator where user_id=' . $dis_cor_id . '')->result()[0]->phone;*/
        //$data['dis_list']=$this->db->query('select * from master.district')->result_array();
        $res = $this->pramaan->get_bd_district_coordinator_data_by_id($dis_cor_id)[0];

        if ($res)
            $data['district_coordinator_data'] = $res;
        else {
            $data['district_coordinator_data'] = array();
        }


        $district = $this->pramaan->do_get_unassigned_districts($user_group_id, $user['id'], $dis_cor_id);
        if ($district)
            $data['dis_list'] = $district;
        else
            $data['dis_list'] = array();

        /* $query = $this->db->query('select * from users.bd_district_coordinator where user_id=' . $dis_cor_id . '');
         $temp = str_replace(array('}', '{'), '', $query->result()[0]->district_id_list);
         $t = explode(',', $temp);
         $data['dis_selected'] = $t[0];*/
        $this->load->view('index', $data);
    }

    public function change_bd_coordinator_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_bd_coordinator_active_status($RequestData);
        echo json_encode($Response);
    }

    /***** BD Executive *****/
    public function bd_executives($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'bd_executives';
        $data['title'] = 'Business Development Executive';
        if (!$parent_id || $parent_id == $user['id']) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $bd_coord_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/bd_coordinators/" . $bd_coord_id;
            $data['parent_page_title'] = "BD Coordinators";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    public function add_bd_executive($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_bd_executive';
        $data['parent_page']       = "pramaan/bd_executives/" . $parent_id;
        $data['parent_page_title'] = "BD Executives";
        $data['title']             = 'BD Executive Registration';
        $data['module']            = "pramaan";
//      $data['sourcing_head_id']=$sourcing_head_id;
        $data['parent_id']     = $parent_id;
        $data['department_id'] = BUSINESS_DEVELOPMENT;
        $user_group_id         = 18;
        $data['user_group_id'] = $user_group_id;
        $this->load->view('index', $data);
    }

    public function edit_bd_executive_update()
    {

        $user         = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $parent_id    = $this->input->post('parent_id');
        $name         = $this->input->post('pname');
        $phone        = $this->input->post('phone');
        $email        = $this->input->post('email');
        $executive_id = $this->input->post('executive_id');

        $submit = $this->input->post('submit');

        /*$password=$this->input->post('password');

                $cpassword=$this->input->post('cpassword');*/
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'parent_id' => $this->input->post('parent_id'),
                'district' => $this->input->post('district'),
                'executive_id' => $executive_id
            );
            if ($submit == 'edit') {
                /*echo json_encode(array('status'=>$this->input->post('user_group_id'),'msg_info'=>''));    */

                if ($this->pramaan->do_edit_bd_executive($data) == true) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Executive has been updated succesfully!!'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
            }

        }
    }


    public function save_bd_executive()
    {
        $user          = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $user_group_id = $this->input->post('user_group_id');
        $parent_id     = $this->input->post('parent_id');
        $password      = $this->input->post('pname');
        $phone         = $this->input->post('phone');
        $email         = $this->input->post('email');

        $submit = $this->input->post('submit');

        $password  = $this->input->post('password');
        $cpassword = $this->input->post('cpassword');
        /*echo json_encode($user_id);*/

        $this->load->library('form_validation');
        $this->form_validation->set_rules('pname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($submit == 'add') {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|matches[cpassword]|min_length[5]');
            $this->form_validation->set_rules('cpassword', 'Confirm Password', 'trim|required');
        }
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data = array(
                'name' => $this->input->post('pname'),
                'phone' => $this->input->post('phone'),
                'email' => $this->input->post('email'),
                'password' => $this->input->post('password'),
                'user_group_id' => $this->input->post('user_group_id'),
                'parent_id' => $this->input->post('parent_id'),

            );
            if ($submit == 'add') {
                $data['password'] = $password;
                $insert           = $this->pramaan->do_add_bd_executive($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'BD Executive has been added succesfully'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
                }
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

    public function bd_executive_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = BUSINESS_DEVELOPMENT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_bd_executives($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function get_bd_executive_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->do_get_bd_executive_list($_REQUEST, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);  // send data as json format
    }

    public function get_bd_emp_for_bd_executive()
    {
        error_reporting(E_ALL);
        $id       = $this->input->post('id');
        $Response = $this->pramaan->do_get_bd_emp_for_bd_executive($id);
        echo json_encode($Response);
    }

    public function edit_bd_executive($parent_id = 0, $executive_id = 0)
    {
        /*echo $sourcing_head_id;
                die;*/
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'edit_bd_executive';
        $data['module']            = 'pramaan';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Sourcing Head Registration';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 10;

        $data['executive_id']    = $executive_id;
        $data['executive_name']  = $this->db->query('select * from users.bd_executive where user_id=' . $executive_id . '')->result()[0]->name;
        $data['executive_email'] = $this->db->query('select * from users.bd_executive where user_id=' . $executive_id . '')->result()[0]->email;
        $data['executive_phone'] = $this->db->query('select * from users.bd_executive where user_id=' . $executive_id . '')->result()[0]->phone;
        $this->load->view('index', $data);
    }

    public function change_bd_executive_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_bd_executive_active_status($RequestData);
        echo json_encode($Response);
    }

    /********** Business Development ends here **********/

    /********** Recuitment Support Department starts here **********/
    //BEGIN: RS HEADS - By George
    public function rs_heads()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'rs_heads';
        $data['module']        = "pramaan";
        $data['title']         = 'Recruitment Support Heads';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function rs_heads_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_rs_heads($requestData);
        echo json_encode($resp_data);
    }

    public function add_rs_head($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'addedit_recruitment_support';
        $data['parent_page']       = "pramaan/rs_heads/" . $parent_id;
        $data['parent_page_title'] = "RS Heads";
        $data['title']             = 'Recruitment Support Head';
        $data['module']            = "pramaan";
        $data['parent_id']         = $parent_id;
        $data['department_id']     = RECRUITMENT_SUPPORT;
        $data['user_group_id']     = 15;
        $this->load->view('index', $data);
    }

    public function addedit_rs_head($id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data = array(
            'page' => 'addedit_rs_head',
            'parent_page' => "pramaan/rs_heads",
            'parent_page_title' => "RS Heads",
            'title' => "Recruitment Support Head",
            'module' => "pramaan",
            'department_id' => RECRUITMENT_SUPPORT,
            'user_group_id' => 15,
            'id' => $id,
            'ResponseData' => $this->pramaan->get_rs_head_detail($id)
        );

        $this->load->view('index', $data);
    }


    public function save_rs_head_detail()
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        // set form validation rules

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys


            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);

            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {


            $RequestData = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password' => $this->input->post('password'),
                'country_id_list' => array(99),
                'created_by' => 1,
                'modified_by' => 1,
                'modified_on' => date('Y-m-d')
            );

            $Response = $this->pramaan->save_rs_head_detail($RequestData);
            echo json_encode($Response);

        }

    }

    public function change_rs_head_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_rs_head_active_status($RequestData);
        echo json_encode($Response);
    }

    public function get_vertical_managers_for_rs_head()
    {
        error_reporting(E_ALL);
        $rs_head_id = $this->input->post('id');
        $Response   = $this->pramaan->get_vertical_managers_for_rs_head($rs_head_id);
        echo json_encode($Response);
    }

    public function candidate_list()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'candidate_list';
        $data['module']        = "pramaan";
        $data['title']         = 'Candidate List';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function get_candidate_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_candidate_list($requestData);
        echo json_encode($resp_data);
    }
    //END: RS HEADS - By George


    //BEGIN: RS ADMINS - By George
    public function rs_admins()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'rs_admins';
        $data['module']        = "pramaan";
        $data['title']         = 'Recruitment Support Admins';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function get_rs_head_list_data()
    {
        error_reporting(E_ALL);
        $resp_data = $this->pramaan->get_rs_head_list_data();
        echo json_encode($resp_data);
    }

    public function rs_admin_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_rs_admins($requestData);
        echo json_encode($resp_data);
    }

    public function addedit_rs_admin($id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data = array(
            'page' => 'addedit_rs_admin',
            'parent_page' => "pramaan/rs_admins",
            'parent_page_title' => "RS Admins",
            'title' => "Recruitment Support Admin",
            'module' => "pramaan",
            'department_id' => RECRUITMENT_SUPPORT,
            'user_group_id' => 14,
            'id' => $id,
            'RsHeadList' => $this->pramaan->get_rs_head_list_data(),
            'ResponseData' => $this->pramaan->get_rs_admin_detail($id)
        );

        //print_r($data['rs_head_list'][0]['rs_head_name']);
        //die;

        $this->load->view('index', $data);
    }

    public function save_rs_admin_detail()
    {

        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        /*       $this->form_validation->set_rules('txtPassword', 'Password', 'trim|required|matches[txtRetypePassword]|min_length[5]');
               $this->form_validation->set_rules('txtRetypePassword', 'Confirm Password', 'trim|required');*/

        // set form validation rules

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {


            $RequestData = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password' => $this->input->post('password'),
                'rs_head_id' => $this->input->post('listRsHead'),
                'created_by' => $user['id'],
                'modified_by' => $user['id']
            );

            $Response = $this->pramaan->save_rs_admin_detail($RequestData);
            echo json_encode($Response);

        }

    }

    public function change_rs_admin_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_rs_admin_active_status($RequestData);
        echo json_encode($Response);
    }
    //END: RS ADMINS - By George


    //BEGIN: RS VERTICAL MANAGERS - By George

    public function rs_vertical_managers()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'rs_vertical_managers';
        $data['module']        = "pramaan";
        $data['title']         = 'RS Vertical Managers';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function rs_vertical_manager_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_rs_vertical_managers($requestData);
        echo json_encode($resp_data);
    }

    public function addedit_rs_vertical_manager($id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data = array(
            'page' => 'addedit_rs_vertical_manager',
            'parent_page' => "pramaan/rs_vertical_managers",
            'parent_page_title' => "RS Vertical Managers",
            'title' => "RS Vertical Manager",
            'module' => "pramaan",
            'department_id' => RECRUITMENT_SUPPORT,
            'user_group_id' => 16,
            'id' => $id,
            'vertical_list' => $this->pramaan->get_vertical_list_for_rs_vertical_manager($id),
            'ResponseData' => $this->pramaan->get_rs_vertical_manager_detail($id)
        );

        $this->load->view('index', $data);
    }

    public function save_rs_vertical_manager_detail()
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');


        // set form validation rules
        /*$this->form_validation->set_rules('txtPassword', 'Password', 'trim|required|matches[txtRetypePassword]|min_length[5]');
        $this->form_validation->set_rules('txtRetypePassword', 'Confirm Password', 'trim|required');*/

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {


            $RequestData = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password' => $this->input->post('password'),
                'vertical_id_list' => $this->input->post('VerticalList'),
                'user_id' => $user['id']
            );

            $Response = $this->pramaan->save_rs_vertical_manager_detail($RequestData);
            echo json_encode($Response);

        }

    }


    public function change_rs_vertical_manager_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_rs_vertical_manager_active_status($RequestData);
        echo json_encode($Response);
    }

    public function get_vertical_list_for_rs_vertical_manager($vertical_manager_id = 0)
    {
        error_reporting(E_ALL);
        $resp_data = $this->pramaan->get_vertical_list_for_rs_vertical_manager($vertical_manager_id);
        echo json_encode($resp_data);
    }

    public function get_sector_managers_for_rs_vertical_manager()
    {
        error_reporting(E_ALL);
        $rs_vertical_manager_id = $this->input->post('id');
        $Response               = $this->pramaan->get_sector_managers_for_rs_vertical_manager($rs_vertical_manager_id);
        echo json_encode($Response);
    }
    //END: RS VERTICAL MANAGERS - By George


    //BEGIN: RS SECTOR MANAGERS - By George

    public function rs_sector_managers()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'rs_sector_managers';
        $data['module']        = "pramaan";
        $data['title']         = 'RS Sector Managers';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function rs_sector_manager_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_rs_sector_managers($requestData);
        echo json_encode($resp_data);
    }

    public function addedit_rs_sector_manager($id = 0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $data = array(
            'page' => 'addedit_rs_sector_manager',
            'parent_page' => "pramaan/rs_sector_managers",
            'parent_page_title' => "RS Sector Managers",
            'title' => "RS Sector Manager",
            'module' => "pramaan",
            'department_id' => RECRUITMENT_SUPPORT,
            'user_group_id' => $user['user_group_id'],
            'id' => $id,
            'vertical_manager_list' => $this->pramaan->get_vertical_manager_list_for_rs_admin($user['id'], $user['user_group_id']),
            'sector_list' => $this->pramaan->get_sector_list_for_rs_sector_manager($id),
            'ResponseData' => ($id > 0 ? $this->pramaan->get_rs_sector_manager_detail($id) : array())
        );

        $this->load->view('index', $data);
    }

    public function save_rs_sector_manager_detail()
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        if ($this->form_validation->run() == FALSE) {
            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $VerticalManagerId = $user['user_group_id'] == 14 ? $this->input->post('VerticalManagerId') : $user['id'];

            $RequestData = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password' => $this->input->post('password'),
                'sector_id_list' => $this->input->post('SectorList'),
                'vertical_manager_id' => $VerticalManagerId,
                'created_by' => $user['id'],
                'modified_by' => $user['id']
            );

            $Response = $this->pramaan->save_rs_sector_manager_detail($RequestData);
            echo json_encode($Response);
        }
    }

    public function change_rs_sector_manager_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_rs_sector_manager_active_status($RequestData);
        echo json_encode($Response);
    }

    public function get_coordinators_for_rs_sector_manager()
    {
        error_reporting(E_ALL);
        $rs_sector_manager_id = $this->input->post('id');
        $Response             = $this->pramaan->get_coordinators_for_rs_sector_manager($rs_sector_manager_id);
        echo json_encode($Response);
    }

    //END: RS SECTOR MANAGERS - By George

    //BEGIN: RS COORDINATORS - By George

    public function rs_coordinators()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'rs_coordinators';
        $data['module']        = "pramaan";
        $data['title']         = 'RS Coordinators';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function rs_coordinator_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_rs_coordinators($requestData);
        echo json_encode($resp_data);
    }

    public function addedit_rs_coordinator($id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data = array(
            'page' => 'addedit_rs_coordinator',
            'parent_page' => "pramaan/rs_coordinators",
            'parent_page_title' => "RS Coordinators",
            'title' => "RS Coordinators",
            'module' => "pramaan",
            'department_id' => RECRUITMENT_SUPPORT,
            'user_group_id' => 24,
            'id' => $id,
            'district_list' => $this->pramaan->get_district_list_for_rs_coordinator($id),
            'ResponseData' => $this->pramaan->get_rs_coordinator_detail($id)
        );

        $this->load->view('index', $data);
    }

    public function save_rs_coordinator_detail()
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        // set form validation rules

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {


            $RequestData = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password' => $this->input->post('password'),
                'district_id_list' => $this->input->post('DistrictList'),
                'user_id' => $user['id']
            );

            $Response = $this->pramaan->save_rs_coordinator_detail($RequestData);
            echo json_encode($Response);

        }

    }

    public function change_rs_coordinator_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_rs_coordinator_active_status($RequestData);
        echo json_encode($Response);
    }

    public function get_executives_for_rs_coordinator()
    {
        error_reporting(E_ALL);
        $rs_coordinator_id = $this->input->post('id');
        $Response          = $this->pramaan->get_executives_for_rs_coordinator($rs_coordinator_id);
        echo json_encode($Response);
    }

    //END: RS COORDINATORS - By George


    //BEGIN: RS EXECUTIVES - By George

    public function rs_executives()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'rs_executives';
        $data['module']        = "pramaan";
        $data['title']         = 'RS Executives';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function addedit_rs_executive($id = 0)
    {
        $user= $this->pramaan->_check_module_task_auth(true);
        $rs_coordinators = array();
        if ($user['user_group_id'] == 24) {
            $rs_coordinator_rec = $this->db->query("SELECT user_id as id,concat(name,' (',uc.email,' )') as name from
                                                    users.rs_coordinator rsc
                                                    LEFT JOIN users.accounts uc on uc.id=rsc.user_id
                                                    WHERE rsc.rs_sector_manager_id=?",$user['id']);
            if ($rs_coordinator_rec->num_rows())
                $rs_coordinators = $rs_coordinator_rec->result_array();
        }

        $data = array(
            'page' => 'addedit_rs_executive',
            'parent_page' => "pramaan/rs_executives",
            'parent_page_title' => "RS Executives",
            'title' => "Recruitment Support Executive",
            'module' => "pramaan",
            'department_id' => RECRUITMENT_SUPPORT,
            'user_group_id' => $user['user_group_id'],
            'id' => $id,
            'rs_coordinators' => $rs_coordinators,
            'ResponseData' => $this->pramaan->get_rs_executive_detail($id)
        );

        $this->load->view('index', $data);
    }

    public function save_rs_executive_detail()
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|callback__valid_name');
        $this->form_validation->set_rules('phone', 'Phone', 'required|callback__valid_phone|max_length[10]|callback__unique_user_phone');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback__unique_email');

        // set form validation rules
        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {

            if ($user['user_group_id'] == 24)
                $user_id = $this->input->post('coordinator');
            else
                $user_id = $user['id'];
            $RequestData = array(
                'id' => $this->input->post('id'),
                'name' => $this->input->post('name'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('phone'),
                'password' => $this->input->post('password'),
                'user_id' => $user_id
            );

            $Response = $this->pramaan->save_rs_executive_detail($RequestData);
            echo json_encode($Response);


        }
    }

    public function change_rs_executive_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_rs_executive_active_status($RequestData);
        echo json_encode($Response);
    }

    public function rs_admins_bak($parent_id = 0)
    {
        $parent_id         = 1;        // root default value
        $user              = $this->pramaan->_check_module_task_auth(false);
        $data['page']      = 'rs_admins';
        $data['title']     = 'Recruitment Support Admins';
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    /**
     * function for sourcing manager list
     */
    public function rs_admins_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = RECRUITMENT_SUPPORT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_rs_users($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }

    /**
     *function for adding sourcing manager
     */
    public function add_rs_admin($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_recruitment_support';
        $data['parent_page']       = "pramaan/rs_admins/" . $parent_id;
        $data['parent_page_title'] = "RS Admins";
        $data['title']             = 'Recruitment Support Admin';
        $data['module']            = "pramaan";
//      $data['sourcing_head_id']=$sourcing_head_id;
        $data['parent_id']     = $parent_id;
        $data['department_id'] = RECRUITMENT_SUPPORT;
        $data['sourcing_user'] = $this->db->query("SELECT uua.user_id as id, uua.name from users.accounts ua
                                                    join users.user_admins uua on uua.user_id=ua.id
                                                    where user_group_id=9")->result_array();
        $data['bd_user']       = $this->db->query("SELECT uua.user_id as id, uua.name from users.accounts ua
                                                    join users.user_admins uua on uua.user_id=ua.id
                                                    where user_group_id=13")->result_array();


        $user_group_id         = 14;
        $data['user_group_id'] = $user_group_id;

        $this->load->view('index', $data);
    }

    /**
     *function for business development managers
     */
    public function rs_managers($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'rs_managers';
        $data['title'] = 'Recruitment Support Managers';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $rs_head_id                = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/rs_heads/" . $rs_head_id;
            $data['parent_page_title'] = "RS Heads";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    /**
     * function for sourcing manager list
     */
    public function rs_managers_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = RECRUITMENT_SUPPORT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_rs_users($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }

    /**
     *function for adding sourcing manager
     */
    public function add_rs_manager($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_recruitment_support';
        $data['parent_page']       = "pramaan/rs_managers/" . $parent_id;
        $data['parent_page_title'] = "RS Managers";
        $data['title']             = 'Recruitment Support Manager';
        $data['module']            = "pramaan";
        $data['parent_id']         = $parent_id;
        $data['department_id']     = RECRUITMENT_SUPPORT;
        $user_group_id             = $this->db->query("SELECT value from master.list
                                          WHERE code='L0001' and lower(name)=?", 'rs manager')->row()->value;
        $data['user_group_id']     = $user_group_id;
        $this->load->view('index', $data);
    }

    /**
     *function for business development managers
     */
    public function rs_coordinators_bak($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'rs_coordinators';
        $data['title'] = 'Recruitment Support Coordinator';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $rs_head_id                = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/rs_managers/" . $rs_head_id;
            $data['parent_page_title'] = "RS Managers";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    /**
     * function for sourcing manager list
     */
    public function rs_coordinators_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = RECRUITMENT_SUPPORT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_rs_users($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }

    /**
     *function for adding sourcing manager
     */
    public function add_rs_coordinator($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_recruitment_support';
        $data['parent_page']       = "pramaan/rs_coordinators/" . $parent_id;
        $data['parent_page_title'] = "RS Coordinators";
        $data['title']             = 'Recruitment Support Coordinator';
        $data['module']            = "pramaan";
//      $data['sourcing_head_id']=$sourcing_head_id;
        $data['parent_id']     = $parent_id;
        $data['department_id'] = RECRUITMENT_SUPPORT;

        $user_group_id         = 17;
        $data['user_group_id'] = $user_group_id;

        $this->load->view('index', $data);
    }

    /**
     *function for business development managers
     */
    public function rs_executives_bak($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'rs_executives';
        $data['title'] = 'Recruitment Support Executives';
        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $rs_coord_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "pramaan/rs_coordinators/" . $rs_coord_id;
            $data['parent_page_title'] = "RS Coordinators";
        }
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    /**
     * function for sourcing manager list
     */
    public function rs_executive_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user          = $this->pramaan->_check_module_task_auth(true);
        $department_id = RECRUITMENT_SUPPORT;
        $requestData   = $_REQUEST;
        $resp_data     = $this->pramaan->get_rs_executives($requestData, $parent_id, $department_id);
        echo json_encode($resp_data);  // send data as json format
    }

    /**
     *function for adding sourcing manager
     */
    public function add_rs_executive($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_recruitment_support_executive';
        $data['parent_page']       = "pramaan/rs_executives/" . $parent_id;
        $data['parent_page_title'] = "RS Executives";
        $data['title']             = 'Recruitment Support Executives';
        $data['module']            = "pramaan";
        $data['parent_id']         = $parent_id;
        $data['department_id']     = RECRUITMENT_SUPPORT;
        //$data['employer_list']=$this->db->query("SELECT user_id,name as employer_name from users.employers")->result_array();

        $user_group_id         = 19;
        $data['user_group_id'] = $user_group_id;
        $this->load->view('index', $data);
    }

    //END: RS EXECUTIVES - By George

    /********** Qualification Pack **********/
    public function qualification_pack()
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']          = 'qualification_pack';
        $data['title']         = 'Qualification pack';
        $data['user_group_id'] = $this->session->userdata['usr_authdet']['user_group_id'];
        $this->load->view('index', $data);
    }

    public function qualification_pack_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_qualification_pack_list($requestData);
        echo json_encode($resp_data);  // send data as json format
    }

    public function change_qp_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_qp_status($RequestData);
        echo json_encode($Response);
    }

    public function edit_qualification_pack($id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'edit_qualification_pack';
        $data['parent_page']       = "pramaan/sourcing_heads/";
        $data['parent_page_title'] = "Sourcing Heads";
        $data['title']             = 'Edit Qualification Pack';
        /*$data['parent_id']=$parent_id;*/
        $data['department_id']      = SOURCING;
        $user_group_id              = $this->db->query("SELECT value from master.list
                                            where code='L0001' and lower(name)=?", 'administrator')->row()->value;
        $data['id']                 = $id;
        $qualification_name         = $this->pramaan->get_qualification_pack_name($id);
        $data['qualification_name'] = $qualification_name;
        $data['sector_id']          = $this->db->query('select * from master.sector where id in(select sector_id from master.qualification_pack where id=' . $id . ')')->result()[0]->id;
        $data['sector_list']        = $this->db->query('select * from master.sector')->result_array();
        $data['interest_type_list'] = $this->db->query("select * from master.list where code='L0005'")->result_array();
        $res_interest_type          = $this->db->query('select * from master.qualification_pack where id=' . $id . '')->result()[0]->interest_type_code;
        $data['interest_type_code'] = $res_interest_type;
        $this->load->view('index', $data);
    }

    /********** RS Sector **********/
     public function rs_sectors($parent_id = 0)
    {

        $user              = $this->pramaan->_check_module_task_auth(true);
        $data['page']      = 'rs_sectors';
        $data['title']     = 'Sectors';
        $data['parent_id'] = $parent_id;


        if (!$parent_id) {
            $parent_id = $user['id'];

        }

        $data['parent_page']       = "";
        $data['parent_page_title'] = "";

        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);

    }


    public function add_rs_sector($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_rs_sector';
        $data['title'] = 'Add RS Sector';

        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $rs_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }

        $active_status = 1; //to fetch active verticals only

        $resp_data              = $this->pramaan->do_get_verticals_options($parent_id, $active_status);
        $data['verticals_list'] = $resp_data;

        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);


    }


    public function edit_rs_sector($parent_id = 0, $sector_id = 0)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        if ($parent_id == 0) {
            $parent_id = $user['id'];
        }

        $data['parent_id']      = $parent_id;
        $data['page']           = 'edit_rs_sector';
        $data['title']          = 'Edit RS Sector';
        $data['module']         = "pramaan";
        $data['rs_sector_data'] = $this->pramaan->get_sector_by_id($sector_id)[0];

        $active_status          = 1; //to fetch active verticals only
        $resp_data              = $this->pramaan->do_get_verticals_options($parent_id, $active_status);
        $data['verticals_list'] = $resp_data;


        $this->load->view('index', $data);
    }


    public function save_rs_sector($parent_id = 0, $vertical_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('vertical_id', 'Vertical name', 'trim|required');
        $this->form_validation->set_rules('rs_sector_name', 'Sector name', 'trim|required|callback_checkDuplicate_rs_sector_name');


        // set form validation rules

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {

            $data = array(
                'vertical_id' => $this->input->post('vertical_id'),
                'name' => $this->input->post('rs_sector_name'),

            );

            if ($this->input->post('submit') == 'update') {
                $data['submit']    = 'update';
                $data['sector_id'] = $this->input->post('sector_id');


                $update = $this->pramaan->do_update_rs_sector($data);

                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'RS Sector has been updated.'));
                }
                else {
                    echo json_encode(array("status" => false, 'msg_info' => 'RS Sector could not be updated.'));
                }
            }
            else {

                $data['submit'] = 'add';

                $insert = $this->pramaan->do_add_rs_sector($data);
                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New RS Sector has been added.'));
                }
                else {
                    echo json_encode(array("status" => false, 'msg_info' => 'New RS Sector could not be added.'));
                }

            }

        }
    }

    public function checkDuplicate_rs_sector_name($rs_sector_name)
    {
        $rs_sector_name = trim($rs_sector_name);
        $rs_sector_name = strtolower($rs_sector_name);

      $id = isset($_REQUEST['sector_id']) ? $_REQUEST['sector_id'] : 0;

        $checkDuplicate_status = $this->pramaan->do_check_duplicate_rs_sector_name($rs_sector_name,$id);

        if ($checkDuplicate_status) {
            return true;
        }
        else {

            $this->form_validation->set_message('checkDuplicate_rs_sector_name', 'RS Sector Name already exists.');

            return false;
        }
    }

    public function rs_sectors_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_rs_sector_list($requestData);
        echo json_encode($resp_data);  // send data as json format
    }

    public function change_active_status()
    {
        //$this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'is_active' => $this->input->post('is_active')
        );

        $Response = $this->pramaan->change_user_status($RequestData);
        echo json_encode($Response);
    }


    public function change_center_active_status()
    {
        //$this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'status' => $this->input->post('status')
        );

        $Response = $this->pramaan->change_center_status($RequestData);
        echo json_encode($Response);
    }

    public function rs_verticals($parent_id = 0)
    {

        $user              = $this->pramaan->_check_module_task_auth(true);
        $data['page']      = 'rs_verticals';
        $data['title']     = 'Recruitment Support Verticals';
        $data['parent_id'] = $parent_id;


        if (!$parent_id) {
            $parent_id = $user['id'];

        }

        $data['parent_page']       = "";
        $data['parent_page_title'] = "";

        $data['parent_id'] = $parent_id;

        $this->load->view('index', $data);

    }

    public function rs_verticals_list($parent_id = 0)
    {
        error_reporting(E_ALL);

        $active_status = 0; //to fetch inactive regiona also
        $user          = $this->pramaan->_check_module_task_auth(true);
       /* $resp_data     = $this->pramaan->do_get_verticals($parent_id, $active_status);


        if ($resp_data) {
            echo json_encode($resp_data); // send data as json format

        }
        else {
            echo json_encode(array());
        }*/

        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->do_get_verticals($requestData,$parent_id);
        echo json_encode($resp_data);  // send data as json format

    }


    public function sector_list_by_vertical($vertical_id = 0, $active_status = 0)
    {

        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);
        //$active_status = 1;//1 for active states
        $resp_data = $this->pramaan->get_sector_list_by_vertical($vertical_id, $active_status);
        echo json_encode($resp_data);  // send data as json format
    }

    public function add_rs_vertical($parent_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'add_rs_vertical';
        $data['title'] = 'Add RS Vertical';

        if (!$parent_id) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }
        else {
            $rs_admin_id               = $this->pramaan->do_get_parent_id($parent_id);
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
        }

        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);
    }

    public function save_rs_vertical($parent_id = 0, $region_id = 0)
    {

        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);
        $this->load->library('form_validation');


        $submit = $this->input->post('submit');

        $this->form_validation->set_rules('rs_vertical_name', 'RS Vertical name', 'trim|required|callback__valid_name|callback_checkDuplicate_rs_vertical_name');
            $this->form_validation->set_rules('rs_vertical_code', 'Code for RS Vertical', 'trim|required|callback__valid_name|callback_checkDuplicate_rs_vertical_code');

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value) {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {

            $rs_head_id=$this->db->query('SELECT * FROM users.rs_admin WHERE user_id ='.$user['id'].'')->result()[0]->rs_head_id;
            $data = array(
                'code' => $this->input->post('rs_vertical_code'),
                'name' => $this->input->post('rs_vertical_name'),
                'created_by' => $rs_head_id,
                'modified_by' => $rs_head_id

            );

            $rs_vertical_id = $this->input->post('rs_vertical_id');

            if ($this->input->post('submit') == 'update') {

                $update = $this->pramaan->do_update_rs_vertical($rs_vertical_id, $data);

                //echo json_encode($update);

                if ($update) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'RS Vertical has been updated.'));
                }
                else {
                    echo json_encode(array("status" => false, 'msg_info' => 'RS Vertical could not be updated.'));
                }
            }
            else {

                $insert = $this->pramaan->do_add_rs_vertical($data);

                if ($insert) {
                    echo json_encode(array('status' => TRUE, 'msg_info' => 'New RS Vertical has been added.'));
                }
                else {
                    echo json_encode(array("status" => false, 'msg_info' => 'New RS Vertical could not be added.'));
                }

            }


        }


    }


    public function checkDuplicate_rs_vertical_name($vertical_name)
    {
        $vertical_name = trim($vertical_name);
        $vertical_name = strtolower($vertical_name);

      $id = isset($_REQUEST['rs_vertical_id']) ? $_REQUEST['rs_vertical_id'] : 0;

        $checkDuplicate_status = $this->pramaan->do_check_duplicate_vertical_name($vertical_name,$id);

        if ($checkDuplicate_status) {
            return true;
        }
        else {

            $this->form_validation->set_message('checkDuplicate_rs_vertical_name', 'RS Vertical Name already exists.');

            return false;
        }
    }

    public function checkDuplicate_rs_vertical_code($vertical_code)
    {
        $vertical_code = trim($vertical_code);
        $vertical_code = strtolower($vertical_code);

        $id = isset($_REQUEST['rs_vertical_id']) ? $_REQUEST['rs_vertical_id'] : 0;

        $checkDuplicate_status = $this->pramaan->do_check_duplicate_vertical_code($vertical_code,$id);

        if ($checkDuplicate_status) {
            return true;
        }
        else {

            $this->form_validation->set_message('checkDuplicate_rs_vertical_code', 'RS Vertical Code already exists.');

            return false;
        }
    }


    public function edit_rs_vertical($parent_id = 0, $vertical_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['parent_id']        = $parent_id;
        $data['page']             = 'edit_rs_vertical';
        $data['title']            = 'Edit RS Vertical';
        $data['module']           = "pramaan";
        $data['rs_vertical_data'] = $this->pramaan->get_vertical_by_id($vertical_id)[0];

        $this->load->view('index', $data);
    }

    public function change_vertical_status($vertical_id = 0)
    {
        $change_status = $this->pramaan->do_change_vertical_status($vertical_id);

        if ($change_status) {
            echo json_encode(array('status' => TRUE, 'msg_info' => 'RS Vertical status has been updated.'));
        }
        else {
            echo json_encode(array("status" => false, 'msg_info' => 'RS Vertical status could not be updated.'));
        }
    }

    public function change_sector_status($sector_id = 0)
    {
        $change_status = $this->pramaan->do_change_sector_status($sector_id);

        if ($change_status) {
            echo json_encode(array('status' => TRUE, 'msg_info' => 'RS Sector status has been updated.'));
        }
        else {
            echo json_encode(array("status" => false, 'msg_info' => 'RS Sector status could not be updated.'));
        }
    }

    /********** Recuitment Support Department ends here **********/

    /********** Aadhaar **********/
    public function aadhar_verify()
    {
        $this->load->model("Common_model", "common");
        $candidate_id = $this->input->post('candidate_id');
        if ($candidate_id) {
            $result      = $this->pramaan->get_candidate_detail($candidate_id);
            $aadhaar_num = $result['aadhaar_num'];
            $data        = array();
            //if($id_type==1)
            if ($aadhaar_num) {
                $data['name']      = $result['name'];
                $data['mobile']    = $result['mobile'];
                $data['aadhaarNo'] = $aadhaar_num;
                $gender            = ($result['gender_code'] == 'F') ? 'FEMALE' : 'MALE';
                $data['gender']    = $gender;
                $data['dobType']   = "date";
                $data['dob']       = date('d-m-Y', strtotime($result['dob']));
                $data['biometric'] = "false";
                $response          = $this->common->request_kyc_api(AADHAAR_URL, $method = 'POST', $data, BETTERPLACE_APIKEY);
                if ($response['data']) {
                    $up_data = array('is_aadhar_verified' => TRUE);
                    $this->db->where("id", $candidate_id);
                    $this->db->update("users.candidates", $up_data);
                    if ($this->db->affected_rows())
                        echo json_encode(array('status' => TRUE, 'msg_info' => 'Candidate Aadhar no. has been verified'));
                    else
                        echo json_encode(array("status" => FALSE, 'errors' => 'Errors in the data table'));
                }
                else {
                    echo json_encode(array("status" => FALSE, 'errors' => 'Sorry Given Aadhar number is Invalid'));
                }
            }
            //else

            /*if($id_type==2)
            {
                $id_name=$this->input->post("name");
                $id_dob=$this->input->post("dob");
                $id_number=$this->input->post("id_number");
                /*echo $id_name." ".$id_dob." ".$id_number;*/
            /*$url=PAN_URL.$id_number;
                if($this->partner->CallAPIPAN("GET",$url,$id_name))
                {
                    $this->partner->change_id_status($id_number);
                    redirect('/partner/candidates');
                }
                else
                {
                    redirect('/partner/candidates');
                }
            }*/
        }
    }

    /********** Job and Assignment **********/
    public function assigned_employers($user_id = 0)
    {
        $user            = $this->pramaan->_check_module_task_auth(true);
        $data['page']    = 'assigned_employers';
        $data['title']   = 'Assigned Employers list';
         if (!$user_id||$user_id==0) {
            $user_id       = $user['id'];
        }
        $data['user_id'] = $user_id;
        $this->load->view('index', $data);
    }

    /**
     *function for all employers list
     */
    public function assigned_employers_list($user_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        if (!$user_id||$user_id==0) {
            $user_id       = $user['id'];
            $user_group_id = $user['user_group_id'];
        }
        else {
            $user_group_id = $this->db->query("select user_group_id from users.accounts where id=?", $user_id)->row()->user_group_id;
        }
        $resp_data = $this->pramaan->get_all_assigned_employers_list_new($requestData, $user_group_id, $user_id);
        echo json_encode($resp_data);  // send data as json format
    }

    /**
     *function for assigned jobs
     */
    public function assigned_jobs()
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'assigned_jobs';
        $data['title'] = 'Assigned Job list';
        $this->load->view('index', $data);
    }

    /**
     *function for assigned jobs list
     */
    public function assigned_job_list($user_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        if (!$user_id)
            $user_id = $user['id'];
        $resp_data = $this->pramaan->get_assigned_jobs_list_new($requestData, $user_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function job_assignment()
    {
        $user                   = $this->pramaan->_check_module_task_auth(true);
        $data['page']           = 'job_assignment';
        $data['title']          = 'Job Assignment';
        $data['employer_list']  = $this->db->query("SELECT distinct user_id as id, name  from users.employers order by name")->result_array();
        $data['location_list']  = $this->db->query("SELECT distinct id,name  from master.district")->result_array();

/*
        $executive_rec          = $this->db->query("select rs_executive_id as id, rs_executive_name as name from users.vw_rs_executive;");
        $data['executive_list'] = array();
        if ($executive_rec->num_rows())
            $data['executive_list'] = $executive_rec->result_array();*/
        $this->load->view('index', $data);
    }

    /**
     *function for assigned jobs list
     */
    public function job_assignment_list($location_id = 0, $employer_id = 0, $assignment_status = 0, $pg = 0, $limit = 15)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(false);
        $resp_data = $this->pramaan->get_job_assignment_list($location_id, $employer_id, $assignment_status, $pg, $limit);
        echo json_encode($resp_data);  // send data as json format
    }

    public function job_assignment_detail($job_id)
    {
        $user = $this->pramaan->_check_module_task_auth(true);
        $this->load->model("Employer_model",'employer');
        $data['page']           = 'job_assignment_detail';
        $data['title']          = 'Job Assignment Detail';
/*        $data['employer_list']  = $this->db->query("SELECT distinct user_id as id, name  from users.employers order by name")->result_array();
        $data['location_list']  = $this->db->query("SELECT distinct id,name  from master.district")->result_array();*/
        $locations=$this->db->query("select location_id from job_process.job_detail where job_id=?",$job_id);
        $executive_rec =array();
        if($locations->num_rows())
        {
            $location_arr_rec=$locations->result_array();
            foreach($location_arr_rec as $key=>$value)
            {
                $location_arr[]=$value['location_id'];
            }
            //array_shift($location_arr);
            $location_arr_str='array['.implode(',', $location_arr)."]";
            $executive_rec = $this->db->query("SELECT rs_executive_id AS id, rs_executive_name as name from users.vw_rs_executive re
                                                LEFT JOIN users.rs_coordinator rc on re.rs_coordinator_id=rc.user_id
                                                WHERE $location_arr_str && rc.district_id_list");

          }

        $data['module']= "pramaan";
        $data['executive_list'] = array();
        if ($executive_rec->num_rows())
            $data['executive_list'] = $executive_rec->result_array();
        $data['job_detail_list'] =$this->employer->do_get_job_detail($job_id);

        $this->load->view('index', $data);
    }


    public function get_job_detail_by_id($job_detail_id = 0)
    {
        $job_rec = $this->db->query("SELECT * from job_process.vw_job_detail
                                        where id=?", $job_detail_id)->row_array();
        echo json_encode(array('status' => 'success', 'job_rec' => $job_rec));
    }

    public function save_assignment()
    {
        $this->load->library('form_validation');
        $this->pramaan->_check_module_task_auth(false);            //return:true(returns user detail)
        $this->form_validation->set_rules('executive_id', 'Executive Id', 'required');
        if ($this->form_validation->run() == FALSE)
        {
            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value)
            {
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else
        {
            $id= $this->input->post('id');
            $executive_id   = $this->input->post('executive_id');
            $executive_data = array('rec_sup_exec_id' => $executive_id, 'assignment_status' => 't', 'assignment_date' => date('Y-m-d'));
            $where = array('id' => $id);
            $this->db->update('job_process.job_detail', $executive_data, $where);
            if ($this->db->affected_rows())
            {
                echo json_encode(array('status' => TRUE, 'msg_info' => 'Support executive successfully assigned '));
            }
            else
            {
                echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the data table')));
            }

        }
    }

    public function pramaan_jobs($user_id = 0)
    {
        $this->authorize(job_view_roles());
        $user = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'pramaan_jobs';
        $data['title'] = 'Jobs';
        if (!$user_id) {
            $user_id = $user['id'];
        }
        $data['user_id']= $user_id;
        $this->load->model('Job', 'job');
        $data['job_title_option'] = $this->job->getJobTitle();
        $data['job_statuses'] = $this->job->getJobStatuses();
        $data['customers_option'] = $this->job->getJobCustomerNames('job');
        $data['placement_officers_option'] = $this->job->getPlacementOfficersNames();
        $data['recruiter_option'] = $this->job->getRecruiterNames();
        $data['job_code_option'] = $this->job->getJobCode();
        $data['user_group_id'] = $user['user_group_id'];
        $this->load->view('index', $data);
    }

    /**
     * @author by Sangamesh<sangamesh.p@mpramaan.in_Feb-2017>
     * function for Pramaan job list
     */
    public function pramaan_job_list()
    {
        error_reporting(E_ALL);
        $user= $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data = $this->pramaan->get_jobs_list_new($requestData,$this->session->userdata('usr_authdet')['id']);
        echo json_encode($resp_data);  // send data as json format
    }

     function jobs()
    {
        $this->_check_login(false);

        $job_statistics         = $this->pramaan->get_total_job_statistics();
        $data['job_statistics'] = $job_statistics;
        $data['page']           = 'job_list';
        $data['title']          = 'Job list';
        $data['module']         = "pramaan";
        $this->load->view('index', $data);
    }

    /**
     * Function to load job list
     * @author Sangamesh.p@pramaan.in
     **/
    public function job_list($non_metric = 0, $metric = 0, $graduate = 0, $experience = '', $search_key = 0, $pg = 0, $limit = 25)
    {
        $this->load->model("Employer_model", "employer");
        $employer_id = 0;
        $rep_data    = $this->employer->get_job_list($non_metric, $metric, $graduate, $experience, 0, $search_key, $pg, $limit);
        //$pagination=_prepare_pagination(site_url("employer/employer_job_list/$employer_id/$non_metric/$metric/$graduate/$experience/$search_key"), $total_records, $limit,9);

        if ($rep_data['status'] == 'success') {
            $pagination = _prepare_pagination(site_url("pramaan/job_list/$non_metric/$metric/$graduate/$experience/$search_key"), $rep_data['total_records'], $limit, 8);

            $rdata = array('status' => 'success', 'job_list' => $rep_data['job_list']
            , 'pg' => $rep_data['pg'], 'limit' => $limit, 'pagination' => $pagination, 'pg_count_msg' => $rep_data['pg_count_msg'], 'page_number' => $rep_data['page_number']);
            output_data($rdata);
        }
        else {
            output_error($rep_data['message']);
        }
    }

    /**
     *function for apply_job
     */
    public function apply_job()
    {

        $this->load->library('form_validation');
        $this->form_validation->set_rules('firstname', 'First Name', 'required|callback__valid_name');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('mobile', 'Mobile', 'required|max_length[12]');

        if ($this->form_validation->run() == FALSE) {

            $errors = array();
            foreach ($this->input->post() as $key => $value) {
                $errors[$key] = form_error($key);
            }
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else {
            $data   = array(
                'job_id' => $this->input->post('id'),
                'candidate_name' => $this->input->post('firstname'),
                'email' => $this->input->post('email'),
                'phone' => $this->input->post('mobile'),
                'applied_on' => date('Y-m-d H:i:s')
            );
            $insert = $this->pramaan->do_job_apply($data);
            if ($insert) {
                echo json_encode(array('status' => TRUE, 'msg_info' => 'Thank you for applying this job'));
            }
            else {
                echo json_encode(array('status' => FALSE, array('error' => 'Errors Please try again.')));
            }

        }

    }

    public function change_job_status()
    {
        $user        = $this->pramaan->_check_module_task_auth(true);
        $RequestData = array(
            'job_id' => $this->input->post('id'),
            'job_status' => $this->input->post('job_status'),
            'location_id' => $this->input->post('location_id'),
            'modified_by' => $user['id']
        );

        $Response = $this->pramaan->do_change_job_status($RequestData);
        echo json_encode($Response);
    }

    /********** Candidate **********/
     public function candidates_by_qp($qp_id = 0, $job_status_id = 0)
    {
        //$this->pramaan->_check_module_task_auth(false);
        $tracked_results = $this->pramaan->get_candidates_by_qp($qp_id, $job_status_id);
        echo json_encode($tracked_results);
    }

    public function pramaan_candidates($user_id = 0)
    {
        $user            = $this->pramaan->_check_module_task_auth(true);
        $data['page']    = 'pramaan_candidates';
        $data['title']   = 'Pramaan Candidates list';
        $data['user_id'] = $this->session->userdata['usr_authdet']['id'];//$user_id;
        $this->load->view('index', $data);
    }

    /**
     *function for all candidates list
     */
    public function pramaan_candidates_list($user_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;

        if (!$user_id) {
            $user_id       = $this->session->userdata['usr_authdet']['id'];
            $user_group_id = $user['user_group_id'];
        }
        else {
            $user_group_id = $this->db->query("select user_group_id from users.accounts where id=?", $user_id)->row()->user_group_id;
        }

        if ($user_group_id == 9) {

            $user_id = $this->db->query('SELECT created_by AS sourcing_head_id from users.sourcing_admin where user_id=' . $user_id)->row()->sourcing_head_id;

        }

        $resp_data = $this->pramaan->get_all_candidates_list($requestData, $user_group_id, $user_id);


        echo json_encode($resp_data);  // send data as json format
    }

    public function get_partner_candidates($user_id = 0, $job_status_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data = $this->pramaan->do_get_partner_candidates($user_id, $job_status_id);
        echo json_encode($data);
    }

    /********** Employers **********/
    public function employers_by_partner($rec_partner_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_employers_by_partner($rec_partner_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function employers_by_id($employer_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_employers_by_id($employer_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function pramaan_employers($user_id = 0)
    {
        $user          = $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'pramaan_employers';
        $data['title'] = 'Pramaan Employers list';
        if (!$user_id) {
            $data['user_id'] = $user['id'];
        }
        else {
            $data['user_id'] = $user_id;
        }

        $this->load->view('index', $data);
    }

    public function change_employer_status()
    {

        $user        = $this->pramaan->_check_module_task_auth(true);
        $RequestData = array(
            'employer_id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status'),
            'modified_by' => $user['id']
        );

        $Response = $this->pramaan->do_change_employer_status($RequestData);
        echo json_encode($Response);

    }

    /**
     *function for all employers list
     */
    public function pramaan_employers_list($user_id = 0)
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;

        $requestData = $_REQUEST;
        if (!$user_id) {
            $user_id       = $user['id'];
            $user_group_id = $user['user_group_id'];
        }
        else {
            $user_group_id = $this->db->query("select user_group_id from users.accounts where id=?", $user_id)->row()->user_group_id;
        }

        $resp_data = $this->pramaan->get_all_employers_list($requestData, $user_group_id, $user_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function get_employers_list($user_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_employers_list($user_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function bd_regional_managers_list_view($user_id = 0)
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_bd_regional_managers_list_view($user_id);
        echo json_encode($resp_data);  // send data as json format
    }

    public function bd_emp_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);
        if (!$parent_id)
            $parent_id = $user['id'];
        $requestData = $_REQUEST;
        //$resp_data=$this->pramaan->get_bd_emp_heads($parent_id);
        $resp_data = $this->pramaan->get_employers_list_new($requestData, $parent_id);
        echo json_encode($resp_data);  // send data as json format
    }

    /********** Application Tracker **********/
    public function application_tracker()
    {
        $this->pramaan->_check_module_task_auth(true);
        $data['page']  = 'application_tracker';
        $data['title'] = 'Application Tracker';
        $this->load->view('index', $data);
    }

    public function application_tracker_list()
    {
        error_reporting(E_ALL);
        $user        = $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->do_get_application_tracker_list($requestData, $this->session->userdata['usr_authdet']['id']);
        echo json_encode($resp_data);  // send data as json format
    }

    /********** Sector **********/
    public function add_new_sector($parent_id = 0)
    {
        $this->pramaan->_check_module_task_auth(false);
        $data['page']              = 'add_new_country';
        $data['parent_page']       = "pramaan/sourcing_heads/" . $parent_id;
        $data['parent_page_title'] = "Add Country";
        $data['title']             = 'Add New Country';
        $data['parent_id']         = $parent_id;
        $data['department_id']     = SOURCING;
        $user_group_id             = 10;
        $data['user_group_id']     = $user_group_id;

        //$data['regions_list']=$this->pramaan->do_get_regions();
        $data['country_list'] = $this->pramaan->do_get_country_list_2();

        $this->load->view('index', $data);
    }

    public function get_sector_for_vertical()
    {
        error_reporting(E_ALL);
        $id = $this->input->post('id');
        $Response   = $this->pramaan->do_get_sector_for_vertical($id);
        echo json_encode($Response);
    }

    public function change_rs_vertical_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->do_change_rs_vertical_active_status($RequestData);
        echo json_encode($Response);
    }

    /********** Qualification Pack **********/
    public function get_qualification_list()
    {
        error_reporting(E_ALL);
        $user      = $this->pramaan->_check_module_task_auth(true);
        $resp_data = $this->pramaan->get_qualification_pack_list();
        echo json_encode($resp_data);  // send data as json format
    }

    public function save_qualification_pack()
    {
        /*echo json_encode($this-input->post('sector'));    */

        $user          = $this->pramaan->_check_module_task_auth(true);            //return:true(returns user detail)
        $id            = $this->input->post('id');
        $name          = $this->input->post('qualification_name');
        $sector_id     = $this->input->post('sector');
        $interest_type = $this->input->post('interest_type');
        $submit        = $this->input->post('submit');

        $this->load->library('form_validation');


        /*if ($this->form_validation->run() == FALSE)
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
        {*/
        $data = array(
            'id' => $id,
            'sector_id' => $sector_id,
            'interest_type' => $interest_type
        );


        if ($submit == 'edit') {
            /*echo json_encode($this->pramaan->do_edit_sourcing_head($data));*/
            if ($this->pramaan->do_edit_qualification_list($data) == true) {
                echo json_encode(array('status' => TRUE, 'msg_info' => 'Qualification Pack has been updated succesfully'));
            }
            else {
                echo json_encode(array("status" => FALSE, 'errors' => array('Errors in the saving the data')));
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
        //}
    }


     public function assigned_jobs_by_employer($employer_id,$rs_exec_id)
  {
        $user = $this->pramaan->_check_module_task_auth(true);
        $tracked_results=$this->pramaan->get_assigned_jobs_by_employer($employer_id,$rs_exec_id);
        echo json_encode($tracked_results);
  }

  public function tracked_candidates_byAssignedEmployerjob($employer_id,$rs_user_id,$job_status)
  {

        $user = $this->pramaan->_check_module_task_auth(true);
        if(!$rs_user_id||$rs_user_id==0)
             $rs_user_id = $user['id'];

        $tracked_results=$this->pramaan->get_candidates_byAssignedEmployerjob($employer_id,$rs_user_id,$job_status);
        echo json_encode($tracked_results);

  }

    /********** Academics **********/
    //BEGIN: ACADEMIC MAJORS - By George
    public function academic_majors()
    {
        $user                  = $this->pramaan->_check_module_task_auth(true);
        $data['page']          = 'academic_majors';
        $data['module']        = "pramaan";
        $data['title']         = 'Academic Majors';
        $data['user_group_id'] = $user['user_group_id'];
        $data['user_id']       = $user['id'];
        $this->load->view('index', $data);
    }

    public function academic_major_list()
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_academic_majors($requestData);
        echo json_encode($resp_data);
    }

    public function addedit_academic_major($id = 0)
    {
        $User = $this->pramaan->_check_module_task_auth(true);
        $data = array(
            'page' => 'addedit_academic_major',
            'parent_page' => "pramaan/academic_majors",
            'parent_page_title' => "Academic Majors",
            'title' => "Academic Major  ",
            'module' => "pramaan",
            'department_id' => ADMIN,
            'user_group_id' => $User['user_group_id'],
            'user_id' => $User['id'],
            'InterestTypeList' => $this->pramaan->get_interest_type_list(),
            'ResponseData' => $this->pramaan->get_academic_major_detail($id)
        );

        $this->load->view('index', $data);
    }

    public function save_academic_major_detail()
    {
        error_reporting(E_ALL);
        $user = $this->pramaan->_check_module_task_auth(true);

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'name', 'trim|required|callback__valid_name');
        $this->form_validation->set_rules('code', 'code', 'trim|required|callback__valid_name');

        // set form validation rules
        if ($this->form_validation->run() == FALSE)
        {
            $errors = array();
            foreach ($this->input->post() as $key => $value)
                $errors[$key] = form_error($key);
            echo json_encode(array('status' => FALSE, 'errors' => $errors));
        }
        else
        {
            $RequestData = array(
                'id' => $this->input->post('id'),
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'interest_type_code' => $this->input->post('interest_type_code'),
                'user_id' => $user['id']
            );

            $Response = $this->pramaan->save_academic_major_detail($RequestData);
            echo json_encode($Response);
        }
    }

    public function change_academic_major_active_status()
    {
        $this->pramaan->_check_module_task_auth(false);
        $RequestData = array(
            'id' => $this->input->post('id'),
            'active_status' => $this->input->post('active_status')
        );

        $Response = $this->pramaan->change_academic_major_active_status($RequestData);
        echo json_encode($Response);
    }
    //END: ACADEMIC MAJORS - By George
    //Requirements
    public function show_bd_admins($parent_id = 0)
    {
        $user           = $this->pramaan->_check_module_task_auth(true);
        $data['page']   = 'show_bd_admins';
        $data['module'] = 'pramaan';
        $data['title']  = 'Business Development Admin';
        if (!$parent_id || $parent_id == $user['id']) {
            $parent_id                 = $user['id'];
            $data['parent_page']       = "";
            $data['parent_page_title'] = "";
            $data['user_group_id']     = $user['user_group_id'];
        }
        else {
            $bd_head_id    = $this->pramaan->do_get_parent_id($parent_id);
            $user_group_id = $user['user_group_id'];

            if ($user_group_id == 12) {
                $data['parent_page']       = "pramaan/bd_heads/" . $bd_head_id;
                $data['parent_page_title'] = "BD Heads";

                $data['user_group_id'] = 12;


            }
            else {

                $data['user_group_id'] = 1; //for super_admin
                //$data['bd_heads'] = $this->pramaan->get_bd_head_list

                $data['parent_page']       = "pramaan/bd_heads/" . $bd_head_id;
                $data['parent_page_title'] = "BD Heads";
            }


        }
        $data['parent_id'] = $parent_id;
        //print_r($data['user_group_id']);
        $this->load->view('index', $data);
    }

    public function show_bd_admin_list($parent_id = 0)
    {

        error_reporting(E_ALL);
        $this->pramaan->_check_module_task_auth(true);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_show_bd_admin_list($requestData, $parent_id, $this->session->userdata['usr_authdet']['user_group_id']);
        echo json_encode($resp_data);
    }

    public function address_book($parent_id = 0)
    {
        $this->authorize(address_view_roles());
        $user              = $this->pramaan->_check_module_task_auth(true);
        $data['page']      = 'address_book';
        $data['title']     = 'Address Book';
        $data['parent_id'] = $parent_id;
        if (!$parent_id) {
            $parent_id = $user['id'];
        }
        $data['parent_page']       = "";
        $data['parent_page_title'] = "";
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);

    }

    public function address_book_contact_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_address_book_contact_list($requestData,$this->session->userdata('usr_authdet')['id']);
        echo json_encode($resp_data);  // send data as json format

    }

    public function customers()
    {
        $this->authorize(customer_view_roles());
        $this->pramaan->_check_module_task_auth(true);
        $data['page']='customers';
        $data['title']='Customers';
        $data['module']="employer";
        $data['business_vertical_options'] = $this->sale->getBusinessVerticals();
        $data['lead_source_options'] = $this->sale->getLeadSources();
        $data['customer_type_options'] = $this->candidate->getLeadType();
        $data['industry_options'] = $this->sale->getIndustries();
        $data['functional_area_options'] = $this->sale->getFunctionalAreas();
        $data['customer_name_options'] = $this->sale->getCustomerNames();
        $data['spoc_name_list_options'] = $this->sale->getCustomerSpocName();
        $data['spoc_email_list_options'] = $this->sale->getCustomerSpocEmail();
        $data['spoc_phone_list_options'] = $this->sale->getCustomerSpocPhone();
        $data['state_options'] = $this->sale->getStates();
        $this->load->view('index',$data);
    }

    public function candidate_joined_customerwise($customer_id = 0)
    {
        $user              = $this->pramaan->_check_module_task_auth(true);
        $data['page']      = 'candidate_joined_customerwise';
        $data['employer_type_list'] = $this->db->query("SELECT id,name FROM neo_master.employment_type WHERE is_active=TRUE AND name!='Self Employed' ORDER BY name")->result_array();
        $data['title']     = 'Joined Candidates';
        $customer_details = array(
            'customer_name' => '',
            'hr_email' => '',
            'hr_phone' => '',
            'location' => ''
        );

        $Query = "SELECT        C.customer_name,
                                C.hr_email,
                                C.hr_phone,
                                d.name AS location
                    FROM 	neo_customer.customer_branches AS CB
                    LEFT JOIN neo_customer.customers AS c ON c.id=cb.customer_id
                    LEFT JOIN neo_master.districts AS d ON d.id=cb.district_id
                    WHERE	C.id=?";
        $job_rec = $this->db->query($Query, $customer_id);
        if ($job_rec->num_rows())
        {
            $customer_details['customer_name'] = $job_rec->row()->customer_name;
            $customer_details['hr_email'] = $job_rec->row()->hr_email;
            $customer_details['hr_phone'] = $job_rec->row()->hr_phone;
            $customer_details['location'] = $job_rec->row()->location;
        }

        $data['customer_details'] = $customer_details;
        $data['id'] = $customer_id;
        $this->load->view('index', $data);

    }


    public function candidate_joined_jobwise($job_id = 0)
    {

        $user              = $this->pramaan->_check_module_task_auth(true);
        $data['employer_type_list'] = $this->db->query("SELECT id,name FROM neo_master.employment_type WHERE is_active=TRUE AND name!='Self Employed'  ORDER BY name")->result_array();
        $data['page']      = 'candidate_joined_jobwise';
        $data['title']     = 'Joined Candidates';

        $job_details;

        $Query = "SELECT    J.job_title,
                            d.name AS district_name,
                            C.customer_name,
                            QP.name AS qualification_pack_name,
                            QP.code AS qualification_code,
                            cp.resigned_date,
                            cp.reason_to_leave
          FROM 	  neo_job.jobs AS J
          LEFT JOIN neo_customer.customers AS C ON C.id=J.customer_id
          LEFT JOIN neo_master.qualification_packs AS QP ON QP.id=J.qualification_pack_id
          LEFT Join neo_master.districts AS d ON d.id = j.district_id
          LEFT JOIN neo_job.candidate_placement AS cp ON cp.job_id=j.id
          WHERE	  J.id=?";
        $job_rec = $this->db->query($Query, $job_id);
        if ($job_rec->num_rows())
        {
            $job_details = $job_rec->row();
        }

        $data['job_details'] = $job_details;
        $data['job_id'] = $job_id;
        $this->load->view('index', $data);
    }


    public function job_applicants($parent_id = 0)
    {

        $user              = $this->pramaan->_check_module_task_auth(true);
        $data['page']      = 'job_applicants';
        $data['title']     = 'Job Applicants';
        $data['parent_id'] = $parent_id;
        if (!$parent_id) {
            $parent_id = $user['id'];
        }
        $data['parent_page']       = "";
        $data['parent_page_title'] = "";
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);

    }

    public function job_applicants_list($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_job_applicants_list($requestData);
        echo json_encode($resp_data);  // send data as json format

    }

    public function get_batches($parent_id = 0)
    {
        error_reporting(E_ALL);
        $requestData = $_REQUEST;
        $resp_data   = $this->pramaan->get_batches_list($requestData);
        echo json_encode($resp_data);  // send data as json format
    }

     public function candidates_by_batchwise($id = 0)
    {

        $this->pramaan->_check_module_task_auth(false);
        $candidate_results = $this->pramaan->get_candidates_by_batchwise($id);
        echo json_encode($candidate_results);
    }


    public function reports($parent_id = 0)
    {

        $user              = $this->pramaan->_check_module_task_auth(true);
        $data['page']      = 'reports';
        $data['title']     = 'Reports';
        $data['parent_id'] = $parent_id;
        if (!$parent_id) {
            $parent_id = $user['id'];
        }
        $data['parent_page']       = "";
        $data['parent_page_title'] = "";
        $data['parent_id'] = $parent_id;
        $this->load->view('index', $data);

    }

    public function authorize($data){
        $user_group_id = $this->session->userdata('usr_authdet')['user_group_id'];
        if(!in_array($user_group_id, $data)){
          $this->session->set_flashdata('status', 'You are not authorised to access that page');
          redirect('/pramaan/dashboard', 'refresh');
        }
      }

}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Model :: Common model  
 * @author Sangamesh.p@pramaan.in
**/
class Common_model extends CI_Model
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
	
	/**
	 * function to handle output details [json]
	 *
	 * @param unknown_type $type [json]
	 * @param unknown_type $status [true|false]
	 * @param unknown_type $response [Array]
	 *
	 * @retrun json content
	 */

	function _output_handle($type='json',$status=false,$response=array() )
    {
        $op = array();
        $op=$response;
        if($status)
        {
          $op['status'] = 'success';
        }
        else
        {
          $op['status'] = 'failure';
          $op['error_code'] = $response['error_code'];
          $op['error_msg'] = strip_tags($response['error_msg']);
        }
    
        $op_data = json_encode($op);
        echo $op_data;
        die();
    }
	
	
	/* Functions for curl request
	* @author Sangamesh.p@pramaan.in_nov_09_2016
	* @param string json
	*/
	function request_http($url, $method = 'GET', $params = array()) 
	{
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
		$http_result = curl_exec($ch);
		$error = curl_error($ch);
		$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
		$curl_totaltime = curl_getinfo($ch,CURLINFO_TOTAL_TIME);
		curl_close($ch);
		return json_decode($http_result, true);
	}
	
	
	/* Functions for curl request
	* @author Sangamesh.p@pramaan.in_Feb_2017
	* @param string json
	*/
	function request_kyc_api($url, $method = 'GET', $params = array(),$apiKey='') 
	{
		$ch = curl_init();
		$headers = array();
		$params=json_encode($params);
    	$headers[] = 'apiKey:'.$apiKey.'';
		$headers[] = 'Accept: application/json';
		$headers[] = 'Content-Type: application/json';
		switch ($method)
		{
			case "POST":
				curl_setopt($ch, CURLOPT_POST, 1);
				if ($params)
				curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
			break;
			case "PUT":
				curl_setopt($ch, CURLOPT_PUT, 1);
			break;
			default:
				if ($params)
				$url = sprintf("%s?%s", $url, http_build_query($params));
		}    
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_FAILONERROR, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$http_result = curl_exec($ch);
		$error = curl_error($ch);
		$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
		$curl_totaltime = curl_getinfo($ch,CURLINFO_TOTAL_TIME);
		curl_close($ch);
		return json_decode($http_result, true);
	}
    /* 
    * Functions for generate otp
    */
    public function do_generate_otp($user_mobile)
    {
        //$otp=substr(number_format(time() * rand(),0,'',''),0,6);
        $otp=123456;
        $result_rec=$this->db->query("SELECT C.name,COALESCE(C.counsellor_id::text,'') AS counsellor_id,C.mobile,C.email
                                      FROM users.candidates C
                                      where C.mobile=?",$user_mobile);
        if($result_rec->num_rows())
        {
            $otp_string="'".$otp."'";
            $EncryptedPassword=$this->db->query('select users.fn_get_encrypted_password('.$otp_string.')')->result()[0]->fn_get_encrypted_password;
            $this->db->where('mobile',$user_mobile);
            $this->db->update('users.candidates', array('password'=> $EncryptedPassword));
            //$this->send_sms($user_mobile,$otp);
            return $result_rec->row_array();
        }
        else
            return false;
    }

    function smsvia_gupshup($sms_data=array())
	{
		$to=$sms_data['to'];
		$msg=($sms_data['msg']);
	
		$request =""; //initialise the request variable
		$param['method']= "sendMessage";
		$param['send_to'] = $to;
		$param['msg'] = $msg;
		$param['userid'] = "2000177171"; //2000177171 
		$param['password'] = "e4XmbePHb"; //e4XmbePHb
		$param['v'] = "1.1";
		$param['msg_type'] = "TEXT"; //Can be "FLASH/"UNICODE_TEXT"/BINARY
		$param['auth_scheme'] = "PLAIN";
	
		//Have to URL encode the values
		foreach($param as $key=>$val) 
		{
			$request.= $key."=".urlencode($val);
			//we have to urlencode the values
			$request.= "&";
			//append the ampersand (&) sign after each parameter/value pair
		}
		$request = substr($request, 0, strlen($request)-1);
		//remove final (&) sign from the request
		$url = "http://enterprise.smsgupshup.com/GatewayAPI/rest?".$request;
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	
		$http_result = curl_exec($ch);
		$error = curl_error($ch);
		$http_code = curl_getinfo($ch ,CURLINFO_HTTP_CODE);
		$curl_totaltime = curl_getinfo($ch,CURLINFO_TOTAL_TIME);
	
		curl_close($ch);
		
		$sms_vendor_ref_id='';
		$resp=explode(' | ',trim($http_result));
		if($resp[0]=='success')
		{
			$sms_vendor_ref_id=$resp['2'];
		}
		else 
		{
			$error['error_code']=$resp[1];
			$error['error_message']=$resp[2];
		}
		
		$resp_data['http_result']=$http_result;
		$resp_data['error']=$error;
		$resp_data['http_code']=$http_code;
		$resp_data['curl_totaltime']=$curl_totaltime;
		$resp_data['sms_vendor_ref_id']= $sms_vendor_ref_id; //$exotel_sid or GupShup Unique Identifier
	
		return $resp_data;
	}
}

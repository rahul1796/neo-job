<?php
/**
 * Pramaan :: custom helper
 * @author Sangamesh 
**/
function curl_request($site_name,$link,$proxy=0,$proxy_iplist=array(),$port=0,$proxy_auth='',$type='GET',$post_params=array())
{
	$c_timeout=120;
	$timeout=60;
	
	$ch = curl_init ();
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.9) Gecko/20071025 Firefox/2.0.0.9');
	curl_setopt($ch, CURLOPT_REFERER,$site_name);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);//n
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);//n
	if($proxy)
	{
		curl_setopt($ch, CURLOPT_PROXY, "http://".$proxy_iplist [0]);
		curl_setopt($ch, CURLOPT_PROXYPORT, $port);
		curl_setopt ($ch, CURLOPT_PROXYUSERPWD, $proxy_auth);
	}
	
	if ($type == 'POST') {
		curl_setopt ( $ch, CURLOPT_POST, true );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_params);
	}
	
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $c_timeout);
	curl_setopt ($ch, CURLOPT_TIMEOUT, $timeout);
	
	// IMITATE CLASSIC BROWSER'S BEHAVIOUR : HANDLE COOKIES
	curl_setopt ($ch, CURLOPT_COOKIEJAR, 'cookie3.txt');
	curl_setopt ( $ch, CURLOPT_URL, $link );
	$webpagehtml = curl_exec ( $ch );
	
	if($webpagehtml)
	{
		return $webpagehtml;
	}else{
		return false;
	}

}


/**
 * Helper function to generate pagination URL
 * @param unknown $pagi_base_url
 * @param unknown $total_rows
 * @param unknown $per_page
 * @param unknown $uri_segment
 * @return unknown
 */
function _prepare_pagination($pagi_base_url,$total_rows,$per_page,$uri_segment)
{
	$CI =& get_instance();
	$CI ->load->library('pagination');
	$config['base_url'] = $pagi_base_url;
	$config['total_rows'] = $total_rows;
	$config['per_page'] = $per_page;
	$config['uri_segment'] = $uri_segment;
	$CI->config->set_item('enable_query_strings',false);
	$CI->pagination->initialize($config);
	$pagination = $CI->pagination->create_links();
	$CI->config->set_item('enable_query_strings',true);
	return $pagination;
}


/**
 * function for addin html tag to codeigniter pagination based on template 
 */
function add_tag_to_pagination($pagination)
{
	$pagination=str_ireplace('<a','<li><a',$pagination);
	$pagination=str_ireplace('</a>','</a></li>',$pagination);
	$pagination=str_ireplace('<b>','<li><a href="#"><b>',$pagination);
	$pagination=str_ireplace('</b>','</a></li></b>',$pagination);
	
	return $pagination;
}


if(!function_exists('valid_mobileno'))
{
	/**
	 * function to validate given mobile no is valid or not
	 */
	function valid_mobileno($no)
	{
		$stat = true;
		$no = (INT)trim($no);
		if(!is_integer($no))
		{
			$stat = false;
		}else if(strlen($no) != 10)
		{
			$stat = false;
		}
		
		return $stat;
	}
}

/**
 * Random characters generator
 *
 * Function to create random characters with small case.
 *
 * @param int $len length
 * @return string random characters of given length
 */
function randomChars($len) 
{
	$str = "";
	$charcode = ord ( "a" );
	$i = 0;
	while ( $i < $len ) {
		$rad = rand ( 0, 3 );
		if ($rad == 0 || $rad == 1)
			$str = $str . chr ( $charcode + rand ( 0, 15 ) );
		else
			$str = $str . rand ( 0, 9 );
		$i = $i + 1;
	}
	return $str;
}

/**
 * Function to return json data.
 * @param $msg $rtype
 * @return json
 */
function output_data($msg,$rtype='json') 
{
	output_error($msg,$rtype,"success"); // redirect to output_error();
}

/**
 * Function to return json error response 
 * @param $msg, $rtype, $status
 */
function output_error($msg,$rtype='json',$status='error') 
{
    if(is_array($msg)) 
    {
        $msg['status'] = $status;
        $rdata = $msg;
    }
    else 
    {
        $rdata = array("status"=>$status,"message"=>$msg);
    }
	if($rtype == 'json')
		echo json_encode($rdata);
	elseif($rtype == 'array' || $rtype === 1)
	{
		return $rdata;
	}
    die();
}

function generateRandomString($length = 5) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

function encode($input_string)
{
	$raw_base64_string = base64_encode($input_string);
	//echo "<br>raw_base64_string=$raw_base64_string";
	$formatted_base64_string = substr($raw_base64_string, 0, 3) . generateRandomString(5) . substr($raw_base64_string, 3);
	$formatted_base64_string = str_replace("=", "", $formatted_base64_string);
	//echo "<br>formatted_base64_string=$formatted_base64_string";
	return $formatted_base64_string;
}

function decode($encoded_string)
{
	//$formatted_base64_string = str_replace("@EQ@", "=", $encoded_string);
	$deformatted_base64_string = substr($encoded_string, 0, 3) . substr($encoded_string, 8);
	//echo "<br>deformatted_base64_string=$deformatted_base64_string";
	$decoded_string = base64_decode($deformatted_base64_string);
	//echo "<br>$decoded_string=$decoded_string";
	return $decoded_string;
}



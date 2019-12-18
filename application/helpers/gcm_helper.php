<?php
if (! defined ( 'BASEPATH' ))
	exit ( 'No direct script access allowed' );

	function sendgcm_notification($regid,$message)
	{
		$output_stat=false;
		
		//$registeration id array()
		$apiKey = "AIzaSyAG_bPlw8Udk5w7WPZlQrqBWblcZh7Myng";
		$registrationIDs = $regid;
		$message = $message;
		// Set POST variables
		$url = 'https://android.googleapis.com/gcm/send';
	
		$fields = array(
				'registration_ids' => $registrationIDs,
				'data' => array( "message" => $message ),
		);
		$headers = array(
				'Authorization: key=' . $apiKey,
				'Content-Type: application/json'
		);
	
		// Open connection
		$ch = curl_init();
		// Set the URL, number of POST vars, POST data
		curl_setopt( $ch, CURLOPT_URL, $url);
		curl_setopt( $ch, CURLOPT_POST, true);
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode( $fields));
		$result = curl_exec($ch);
		
		if ($result === FALSE) {
			//die('Curl failed: ' . curl_error($ch));
			$output_stat=false;
		}else{
			$output_stat=true;
		}
		
		// Close connection
		 curl_close($ch);
		 
		return $output_stat;
	}	

?>
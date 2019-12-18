<?php
/**
 * MY_Log Class
 * @package 	CodeIgniter
 * @subpackage	Controller
 * @category    Error-Logging
 * @author      by Sangamesh<sangamesh.p@mpramaan.in_Nov-2016>
 */
class MY_Log extends CI_Log  {

    function MY_Log ()
    {
        parent::__construct();

        $this->ci =& get_instance();
    }

    public function write_log() 
    { 
    	//here overriding
        if ($this->_enabled === FALSE)
        {
        return FALSE;
        }

        $level = strtoupper($level);

        if ( ! isset($this->_levels[$level]) OR
        ($this->_levels[$level] > $this->_threshold))
        {
        return FALSE;
        }

        /* HERE YOUR LOG FILENAME YOU CAN CHANGE ITS NAME */
        $filepath = $this->_log_path.'log-'.date('Y-m-d').EXT;
        $message  = '';

        if ( ! file_exists($filepath))
        {
        $message .= "<"."?php  if ( ! defined('BASEPATH'))
        exit('No direct script access allowed'); ?".">\n\n";
        }

        if ( ! $fp = @fopen($filepath, FOPEN_WRITE_CREATE))
        {
        return FALSE;
        }

        $message .= $level.' '.(($level == 'INFO') ? ' -' : '-').' ';
        $message .= date($this->_date_fmt). ' --> '.$msg."\n";

        flock($fp, LOCK_EX);
        fwrite($fp, $message);
        flock($fp, LOCK_UN);
        fclose($fp);
        @chmod($filepath, FILE_WRITE_MODE);
        return TRUE;
    }
}
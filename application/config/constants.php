<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/

defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code


//application constants
define("ADMIN_VIEW_PAGES","admin/");
define("PRAMAAN_VIEW_PAGES","pramaan/");
define("TEMP_DIR","/resources/temp_dumps");
define('IMAGES_FOLDER','assets/images/');


//Candidate image sizes details
define('CND_IMAGE_WIDTH','100');
define('CND_IMAGE_HEIGHT','100');
define('CND_ADS_IMAGE_WIDTH','300');
define('CND_ADS_IMAGE_HEIGHT','300');

//Employer image sizes details
define('EMP_IMAGE_WIDTH','100');
define('EMP_IMAGE_HEIGHT','100');
define('EMP_ADS_IMAGE_WIDTH','300');
define('EMP_ADS_IMAGE_HEIGHT','300');


//job posting max price
define("JOB_MIN_PRICE","100");
define("JOB_MAX_PRICE","200");

//default web news link get values representing constant tags
define("WEB_DEFAULT_NEWS_LINK_TAGS","<GEO_LOCATION>,<COUNTRY>,<CATEGORY>,<SEARCH_KEYWORDS>");

//rss validation tag
define("RSS_VALIDATION_TAG","<rss");

// form input maxlength settings settings
define('PHONE_MAX','10');
define('PIN_MAX','6');
define('EMAIL_MAX','50');
define('PASSWORD_MAX','50');
define('AADHAR_MAX','12');
define('IDNUMBER_MAX','16');
define('DECIMAL_MAX','12');
define('CANDIDATE_AGE','16');

define('MIN_CANDIDATE_AGE','18');
define('MAX_CANDIDATE_AGE','25');

/* End of file constants.php */

//email settings
define("EMAIL_FROM","info@pramaan.in");

//user_name_convention
define('UAD',"administrator");
define('URP',"recruitment partner");
define('UAS',"associate");
/*define('UWA',"web admin");*/
define('UER',"employer");
define('USP',"sourcing partner");
define('UTP',"training partner");
/*define('USA',"superadmin");*/
define('USC',"sourcing coordinator");
define('USM',"sourcing manager");
define('USH',"sourcing head");
define('USA',"sourcing admin");

define('BDA',"bd admin");
define('BDH',"bd head");
define('BDM',"bd manager");
define('BDC',"bd coordinator");
define('BDE',"bd executive");

define('RSA',"rs admin");
define('RSH',"rs head");
define('RSM',"rs manager");
define('RSC',"rs coordinator");

/*define user type*/
define('ADMIN',1);
define('SOURCING',2);
define('RECRUITMENT_SUPPORT',3);
define('BUSINESS_DEVELOPMENT',4);

/*define("PHY_DOC_DIR","http://uapdemo.certiplate.com:8080/NavritiTabAssessments/assessmentvideos/");*/

define("PHY_DOC_DIR","uploads/candidate/test_results/");

define("AADHAAR_URL","https://testportal.betterplace.co.in/VishwasAPI/api/public/v2/aadhaar/authenticate");
define("PAN_URL","https://testportal.betterplace.co.in/VishwasAPI/api/public/v2/panVerification/");
define("BETTERPLACE_APIKEY","uSrx9o4N8roapc8qMfuxFrjH/znRJfIYW7abPoXc4TBsBTL1UfuAu1xuLMBeFQyl/mrxUmjip4L/paHEoX0xmA==");
define("CANDIDATE_IMAGES","uploads/candidate/images/");
define("CANDIDATE_LIST","uploads/candidate/");
define("IGS_CANDIDATE_LIST","uploads/candidate/");

//BEGIN: PRAMAAN ROLE CONSTANTS
define('ROLE_SUPER_ADMIN', 1);

//SOURCING DEPARTMENT
define('ROLE_SOURCING_HEAD', 10);
define('ROLE_SOURCING_ADMIN', 9);
define('ROLE_SOURCING_REGIONAL_MANAGER', 20);
define('ROLE_SOURCING_STATE_MANAGER', 21);
define('ROLE_SOURCING_DISTRICT_COORDINATOR', 22);
define('ROLE_SOURCING_PARTNER', 3);
define('ROLE_SOURCING_ASSOCIATE', 5);

//BUSINESS DEVELOPMENT DEPARTMENT
define('ROLE_BD_HEAD', 12);
define('ROLE_BD_ADMIN', 13);
define('ROLE_BD_REGIONAL_MANAGER', 11);
define('ROLE_BD_DISTRICT_COORDINATOR', 8);
define('ROLE_BD_EXECUTIVE', 18);

//RECRUITMENT SUPPORT DEPARTMENT
define('ROLE_RS_HEAD', 15);
define('ROLE_RS_ADMIN', 14);
define('ROLE_RS_VERTICAL_MANAGER', 16);
define('ROLE_RS_SECTOR_MANAGER', 24);
define('ROLE_RS_COORDINATOR', 17);
define('ROLE_RS_EXECUTIVE', 19);
//END: PRAMAAN ROLE CONSTANTS


define("QUESTION_PAPER_DATA","uploads/content/question_paper/");

define("QUESTION_IMAGES","uploads/content/question_paper/question_images/");
define("QUESTION_IMAGES_SCALED","uploads/content/question_paper/question_images/scaled/");

define("SAMPLE_QUESTION_IMAGES","uploads/content/question_paper/sample_question_images/");
define("SAMPLE_QUESTION_IMAGES_SCALED","uploads/content/question_paper/sample_question_images/scaled/");
define('TEMPLATEBULKUPLOAD','uploads/templates/Template_QuestionBulkUpload.xlsx');
define('TEMPLATESAMPLEQUESTIONUPLOAD','uploads/templates/Template_SampleQuestionBulkUpload.xlsx');
define("TEMPLATES","uploads/templates/");
define('MAX_ASSESSMENT_TIME', 5400);

define('OFFER_LETTER_PATH', "uploads/candidate/offer_letters/");
define('CUSTOMER_DOCUMENT_PATH', "documents/");


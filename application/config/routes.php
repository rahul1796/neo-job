<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'pramaan';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//$route['dashboard'] = 'pramaan/dashboard';
//$route['leads/index/'] = 'salescontroller/index/$1';
$route['leads/index/(:num)'] = 'salescontroller/index/$1';
$route['leads/create'] = 'salescontroller/create';
$route['leads/edit/(:num)'] = 'salescontroller/edit/$1';
$route['leads/commercials_documents/(:num)'] = 'salescontroller/commercials_documents/$1';

$route['candidate/create'] = 'candidatescontroller/create';
$route['candidate/edit/(:num)'] = 'candidatescontroller/edit/$1';

$route['jobs'] = 'jobscontroller/index';

$route['employers'] = 'employerscontroller/index';

$route['pramaan/(:any)'] = 'pramaan/$1';
$route['pramaan/(:any)/(:any)']="pramaan/$1/$2";


$route['employer/(:any)'] = 'employer/$1';
$route['employer/(:any)/(:any)']="employer/$1/$2";

$route['partner/(:any)'] = 'partner/$1';
$route['pertner/(:any)/(:any)']="partner/$1/$2";

/** pramaan_api Api route Configs **/
$route['pramaan_api/(:any)']="pramaan_api/$1";
$route['pramaan_api/(:any)/(:any)']="pramaan_api/$1/$2";

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Marketplace';

$route['register'] = 'Authenticate/register';
$route['sign-up'] = 'Authenticate/sign_up';
$route['sign-in'] = 'Authenticate/sign_in';
$route['sign-out'] = 'Authenticate/sign_out';

$route['farm/new-veggy'] = 'Farm/new_veggy';
$route['store'] = 'Marketplace';
$route['store/(:any)'] = 'Farm/store/$1';

$route['help-center'] = 'Support/help_center';
$route['support/help-center'] = 'Support/help_center';

$route['products/(:any)'] = 'Basket/index';

$route['dev-build'] = 'DevBuild/index';
$route['dev-build/run'] = 'DevBuild/run';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
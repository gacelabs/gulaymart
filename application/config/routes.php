<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Marketplace';

$route['register'] = 'Authenticate/register';
$route['sign-up'] = 'Authenticate/sign_up';
$route['sign-in'] = 'Authenticate/sign_in';
$route['sign-out'] = 'Authenticate/sign_out';

$route['farm/my-veggies'] = 'Farm/new_veggy';
$route['store'] = 'Marketplace';
$route['store/(:num)/(:any)'] = 'Farm/store/$1/$2';
$route['store/(:num)/(:num)/(:any)'] = 'Farm/store/$1/$2/$3';
$route['store_farm/(:num)/(:any)'] = 'Farm/store_farm/$1/$2';
$route['store_location/(:num)/(:num)/(:any)'] = 'Farm/store_location/$1/$2/$3';
$route['farm/save-veggy/(:num)/(:any)'] = 'Farm/save_veggy/$1/$2';
$route['farm/remove-veggy/(:num)/(:any)'] = 'Farm/remove_veggy/$1/$2';

$route['help-center'] = 'Support/help_center';
$route['support/help-center'] = 'Support/help_center';

$route['products/(:any)'] = 'Basket/productpage';

$route['basket/checkout'] = 'Basket/checkout';

$route['fulfillment/placed'] = 'Fulfillment/index/placed';
$route['fulfillment/for-pick-up'] = 'Fulfillment/index/for+pick+up';
$route['fulfillment/on-delivery'] = 'Fulfillment/index/on+delivery';
$route['fulfillment/received'] = 'Fulfillment/index/received';
$route['fulfillment/cancelled'] = 'Fulfillment/index/cancelled';

$route['orders/placed'] = 'Orders/index/placed';
$route['orders/for-pick-up'] = 'Orders/index/for+pick+up';
$route['orders/on-delivery'] = 'Orders/index/on+delivery';
$route['orders/received'] = 'Orders/index/received';
$route['orders/cancelled'] = 'Orders/index/cancelled';

$route['orders/thank-you'] = 'Orders/thankyou';

$route['dev-build'] = 'DevBuild/index';
$route['dev-build/run'] = 'DevBuild/run';
$route['dev-build/fetch-cities'] = 'DevBuild/fetch';

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
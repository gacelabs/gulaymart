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

/*FB*/
defined('FB_APPID') OR define('FB_APPID', '298098848531584');
defined('FB_VERSION') OR define('FB_VERSION', 'v10.0');
defined('FBTOKEN') OR define('FBTOKEN', '298098848531584|0aa86567bb40e83406930b68e02c6434');

defined('APP_NAME') OR define('APP_NAME', 'GulayMart');
defined('APP_DESCRIPTION') OR define('APP_DESCRIPTION', 'GulayMart an Online Supermarket near you.');
defined('APP_VERSION') OR define('APP_VERSION', '4.0.3');

/*IMPORTANT PASSSWORDS*/
defined('GACELABS_SUPER_KEY') OR define('GACELABS_SUPER_KEY', '&@c3L4b$-5uP3R-k3y'); // also the ssh root password
defined('GACELABS_KEY') OR define('GACELABS_KEY', '&@c3L4b$-k3y');
defined('GM_ADMIN') OR define('GM_ADMIN', '&m_@DM!n-p@55-K3y');
defined('GM_ADMIN_MYSQL_PASS') OR define('GM_ADMIN_MYSQL_PASS', '&m_@DM!n-mY597-p@55-K3y');
defined('GM_USER_MYSQL_PASS') OR define('GM_USER_MYSQL_PASS', '&m_U53r-mY597-p@55-K3y');
defined('GM_USER_POI_MYSQL_PASS') OR define('GM_USER_POI_MYSQL_PASS', 'Phoinyakz1982..!');
defined('PHPMYADMIN_PASS') OR define('PHPMYADMIN_PASS', 'pHPMy_@DM!n-p@55-K3y');
defined('MYSQL_ROOT_PASS') OR define('MYSQL_ROOT_PASS', 'pHPMy_@DM!n-R00t-p@55-K3y');
/*IMPORTANT PASSSWORDS*/

defined('DROP_ALL_TABLE') OR define('DROP_ALL_TABLE', 1);
if ((bool)strstr($_SERVER['HTTP_HOST'], 'local')) {
	// defined('REALTIME_URL') OR define('REALTIME_URL', 'https://app.send-data.co/get/jsfile/A3193CF4AEC1ADD05F4B78C4E0C61C39');
	// defined('SENDDATA_APPKEY') OR define('SENDDATA_APPKEY', 'A3193CF4AEC1ADD05F4B78C4E0C61C39');
	// defined('REALTIME_URL') OR define('REALTIME_URL', 'http://local.app.send.data/get/jsfile/1E56FA5E54577C98A5FF31F577DFAE75');
	// defined('SENDDATA_APPKEY') OR define('SENDDATA_APPKEY', '1E56FA5E54577C98A5FF31F577DFAE75');
	defined('REALTIME_URL') OR define('REALTIME_URL', 'https://app.send-data.co/get/jsfile/9C0CA25AB5C84B3D6BB27AAD2C7139C8');
	defined('SENDDATA_APPKEY') OR define('SENDDATA_APPKEY', '9C0CA25AB5C84B3D6BB27AAD2C7139C8');
	defined('IS_LOCAL') OR define('IS_LOCAL', 1);
} else {
	defined('REALTIME_URL') OR define('REALTIME_URL', 'https://app.send-data.co/get/jsfile/2DBC7E5261EF425D3D1CF990E3682F56');
	defined('SENDDATA_APPKEY') OR define('SENDDATA_APPKEY', '2DBC7E5261EF425D3D1CF990E3682F56');
	defined('IS_LOCAL') OR define('IS_LOCAL', 0);
	// defined('REALTIME_URL') OR define('REALTIME_URL', 'https://app.send-data.co/get/jsfile/A3193CF4AEC1ADD05F4B78C4E0C61C39');
	// defined('SENDDATA_APPKEY') OR define('SENDDATA_APPKEY', 'A3193CF4AEC1ADD05F4B78C4E0C61C39');
}
defined('MONTHS') OR define('MONTHS', serialize([
	'1'=>'January', '2'=>'February', '3'=>'March', '4'=>'April', '5'=>'May', '6'=>'June', '7'=>'July', '8'=>'August', '9'=>'September', '10'=>'October', '11'=>'November', '12'=>'December'
]));

defined('PROFILE_INFO_MESSAGE') OR define('PROFILE_INFO_MESSAGE', 'Your Profile information is important, and will only be used for delivery purposes.');
defined('DEFAULT_OG_IMAGE') OR define('DEFAULT_OG_IMAGE', 'assets/images/logo.png');
defined('DEVBUILD_PASS') OR define('DEVBUILD_PASS', '1');
defined('PRODUCTSDATALIMIT') OR define('PRODUCTSDATALIMIT', 4);
defined('GOOGLEMAP_KEY') OR define('GOOGLEMAP_KEY', 'AIzaSyBbNbxnm4HQLyFO4FkUOpam3Im14wWY0MA');
defined('REFERRAL_CODE') OR define('REFERRAL_CODE', 'PPS8083189');
defined('NON_LOCATION_KEYS') OR define('NON_LOCATION_KEYS', serialize(['id','lat','lng','address_1','address_2']));
defined('METERS_DISTANCE_TO_USER') OR define('METERS_DISTANCE_TO_USER', 30000); /*30 KM*/
defined('NON_PRODUCT_KEYS') OR define('NON_PRODUCT_KEYS', serialize(['user_id', 'category_id', 'subcategory_id', 'added']));
defined('SECONDS_DISTANCE_TO_USER') OR define('SECONDS_DISTANCE_TO_USER', 7200); /*2 hours*/
defined('SWITCH_DISTANCE') OR define('SWITCH_DISTANCE', 1); /*1 = DISTANCE, 0 = DURATION*/
defined('DISABLE_DISTANCE_COMPARING') OR define('DISABLE_DISTANCE_COMPARING', 0); /*0 = DISABLED*/
defined('ADMIN_PASS') OR define('ADMIN_PASS', '2');
defined('CRON_RERUN_SECONDS') OR define('CRON_RERUN_SECONDS', 60); /*1 minute*/

defined('MARKETPLACE_MAX_ITEMS') OR define('MARKETPLACE_MAX_ITEMS', 10);
defined('MARKETPLACE_MAX_VEGGIES') OR define('MARKETPLACE_MAX_VEGGIES', 6);
defined('MARKETPLACE_MAX_FARMERS') OR define('MARKETPLACE_MAX_FARMERS', 24);

defined('RECAPTCHA_KEY') OR define('RECAPTCHA_KEY', '6Lei4fQUAAAAANUAGGDO7bHtCeydYC93apLLdxZn');
defined('RECAPTCHA_SECRET') OR define('RECAPTCHA_SECRET', '6Lei4fQUAAAAAHuouWMQsjHtPaaGJE201bhTkAaP');

defined('GM_STATUSES_TEST') OR define('GM_STATUSES_TEST', 0);
/*
|---------------------------------------------------------|
|					  STATUS NUMBERS					  |
|---------------------------------------------------------|
|	  GulayMart Status		|		TokTok Status		  |
|---------------------------|-----------------------------|
|-1 = Removed				|							  |
| 0 = Verified (scheduled)	| 2 = Scheduled for Delivery  |
| 1 = Verified (buy now)	| 							  |
| 2 = Placed				| 1 = Placed Order			  |
| 3 = On Delivery			| 5 = On the Way to Recipient |
| 4 = Received				| 6 = Item Delivered		  |
| 5 = Cancelled				| 							  |
| 6 = For Pick Up			| 3 = On the Way to Sender	  |
|							| 4 = Item Picked Up		  |
|---------------------------|-----------------------------|
*/
defined('GM_ITEM_REMOVED')		 OR	define('GM_ITEM_REMOVED',	   -1);
defined('GM_VERIFIED_SCHED')	 OR	define('GM_VERIFIED_SCHED', 	0);
defined('GM_VERIFIED_NOW')		 OR	define('GM_VERIFIED_NOW', 		1);
defined('GM_PLACED_STATUS')		 OR	define('GM_PLACED_STATUS', 		2);
defined('GM_ON_DELIVERY_STATUS') OR define('GM_ON_DELIVERY_STATUS', 3);
defined('GM_RECEIVED_STATUS')	 OR	define('GM_RECEIVED_STATUS', 	4);
defined('GM_CANCELLED_STATUS')	 OR	define('GM_CANCELLED_STATUS', 	5);
defined('GM_FOR_PICK_UP_STATUS') OR define('GM_FOR_PICK_UP_STATUS', 6);

defined('TT_PLACED_STATUS')		 OR	define('TT_PLACED_STATUS', 		1);
defined('TT_VERIFIED_SCHED')	 OR	define('TT_VERIFIED_SCHED', 	2);
defined('TT_FOR_PICK_UP_STATUS') OR define('TT_FOR_PICK_UP_STATUS', 3);
defined('TT_PICKED_UP_STATUS')	 OR	define('TT_PICKED_UP_STATUS', 	4);
defined('TT_ON_DELIVERY_STATUS') OR	define('TT_ON_DELIVERY_STATUS',	5);
defined('TT_RECEIVED_STATUS')	 OR	define('TT_RECEIVED_STATUS', 	6);

/*ORDER TYPES STATUS*/
defined('GM_BUY_NOW') 	OR define('GM_BUY_NOW',   1);
defined('GM_SCHEDULED') OR define('GM_SCHEDULED', 2);

/*MESSAGES STATUS*/
defined('GM_MESSAGE_READ')	 OR	define('GM_MESSAGE_READ', 	0);
defined('GM_MESSAGE_UNREAD') OR define('GM_MESSAGE_UNREAD', 1);
defined('GM_MESSAGE_DELETE') OR define('GM_MESSAGE_DELETE', 2);

/*PRODUCTS ACTIVITY*/
defined('GM_ITEM_DRAFT')		OR define('GM_ITEM_DRAFT',		 0);
defined('GM_ITEM_APPROVED')		OR define('GM_ITEM_APPROVED',	 1);
defined('GM_ITEM_REJECTED')		OR define('GM_ITEM_REJECTED',	 2);
defined('GM_ITEM_DELETED')		OR define('GM_ITEM_DELETED',	 3);
defined('GM_ITEM_NO_INVENTORY')	OR define('GM_ITEM_NO_INVENTORY',4);

/*TOKTOK REQUEST STATUS CANCEL*/
defined('TT_NO_REQUEST')	 OR	define('TT_NO_REQUEST',	  0);
defined('TT_SEND_REQUEST')	 OR define('TT_SEND_REQUEST', 1);
<?php
//define("PUBLIC_DIR", 'public'); // this for server
define("PUBLIC_DIR", '');//this for local


define("ADMIN_PER_PAGE", 10);
define("API_PER_PAGE", 10);
define("ADMIN_LANG_DEFAULT", 'en');
define('DIR_UPLOAD', 'uploads');


//WEB site
define('WEBSITE', 'WebSite');
define('SITE_VIEWS_DIR', 'site');
define('SITE_PUBLIC_DIR', 'web');
define("SITE_ROUTE", 'site');


define("DEFAULT_IMAGE", 'no_image.png');


/*
 * System
 */
define('DATE_FORMAT', 'Y-m-d');
define('DATE_FORMAT_FULL', 'Y-m-d H:i:s');
define('TIME_FORMAT', 'H:i:s');
define('DATE_FORMAT_DOTTED', 'd.m.Y');


define('TIME_FORMAT_WITHOUT_SECONDS', 'H:i');


define('ROOT_NAMESPACE', 'Api\v1');
define('PASSWORD', '123456');

define('API_ACCESS_KEY', "AAAAMTR6lzE:APA91bFrg7HwVT-yo63yqpzII_zGlgKshKJIaP_yoVNmCGkIN_sNG8Ld1pa63PF7z4hjZIccJpOKYrAaOqE9o-j7I2vvnWdHo2teoEw02jBtV4v7R-D_Bq0DIPrfa1cxreQgWzEePI-P");


define("DEFAULT_category_IMAGE", 'category_image.png');
define("DEFAULT_item_IMAGE", 'item_image.png');

//Errors
define('IS_ERROR', 'isError');
define('ERRORS', 'errors');
define('ERROR', 'error');


//boolean
define('YES', 1);
define('NO', 0);


//Gender
define('MALE', 1);
define('FEMALE', 2);


/***
 * API access token name
 */
define('API_ACCESS_TOKEN_NAME', 'PingApp');


/*
 *
 * client numbers
 */
define('PHONE_MERCHANT1', '+966505007896');
define('EMAIL_MERCHANT1', 'merchant@gmail.com');
define('PHONE_CLIENT1', '+966500000000');
define('PHONE_EMPLOYEE1', '+966500000001');
define('CODE_FIXED', '1234');


/*
* Notification types
* 1- Merchant
*/
define('ACCEPTED_ORDER_NOTIFICATION', 1);
define('ORDER_RATED_NOTIFICATION', 2);
define('READY_ORDER_NOTIFICATION', 3);
define('ON_WAY_ORDER_NOTIFICATION', 4);
define('CANCEL_ORDER_NOTIFICATION', 5);
define('CONTACT_US_NOTIFICATION', 6);


/*
 *
 * Notification types
 * 1- Driver
 */
define('Driver_ACCEPTED_ORDER_NOTIFICATION', 6);
define('DRIVER_ON_WAY_ORDER_NOTIFICATION', 7);
//define('DRIVER_ON_WAY_DONE_ORDER_NOTIFICATION', 7);
define('DRIVER_COMPLETED_ORDER_NOTIFICATION', 8);
define('DRIVER_CANCELED_ORDER_NOTIFICATION', 9);


// Wallet charging
define('ADMIN_CHARGING_WALLET_NOTIFICATION', 10);

define('ADMIN_TO_USER_NOTIFICATION', 11);
define('CLIENT_RATE_ORDER_NOTIFICATION', 12);
define('GENERAL_NOTIFICATION', 13);


//Notification receptions
define('ALL_USERS', 1);
define('CLIENTS', 2);
define('RESTAURANTS', 3);
define('BRANCHES', 4);
define('DRIVERS', 5);


/*
 * Permissions
 */

define('MANAGER_PERMISSIONS', [
    'General Settings',
    'Testimonials',
    'Cities',
    'Banks',
    'Sliders',
    'Pages',
    'Notification',
    'Join Us',
    'Payments',
    'Restaurants',
    'Restaurants Categories',
    'Merchant Types',
    'Branches',
    'Users',
    'Meals Categories',
    'Meals',
    'Coupons',
    'Orders',
    'Contact Us',
    'Managers',
    'Roles',
    'Packages',
    'User Packages',
    'Drivers',
    'Ratings',
    'Branch Ratings',
    'Driver Ratings',
    'Client Ratings',
    'Transporters',
    'Nationality',
]);
define('MERCHANT_PERMISSIONS', [
    'Notification',
    'Payments',
    'Branches',
    'Meals Categories',
    'Meals',
    'Orders',
    'Drivers',
    'Ratings',
    'Branch Ratings',
    'Driver Ratings',
    'Client Ratings',
]);
define('BRANCH_PERMISSIONS', [
    'Notification',
    'Payments',
    'Meals Categories',
    'Meals',
    'Orders',
    'Drivers',
    'Ratings',
    'Driver Ratings',
    'Client Ratings',
]);


//DIGIT_NUMBER for number format
define('DECIMAL_DIGIT_NUMBER', 1);
define('DECIMAL_SEPARATOR', '.');
define('DIGIT_THOUSANDS_SEPARATOR', '');


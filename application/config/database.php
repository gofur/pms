<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the "Database Connection"
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the "default" group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

$active_group = "default";
$active_record = TRUE;

// $db['default']['hostname'] = "PMS_LIVE";
// $db['default']['username'] = "user_pms";
// $db['default']['password'] = "Pmshr1s0k3";
// $db['default']['database'] = "PMS";

$db['default']['hostname'] = "PMS_DEV";
$db['default']['username'] = "userPortal";
$db['default']['password'] = "Hrport@l2012";
$db['default']['database'] = "PMS";

$db['default']['dbdriver'] = "odbc";
$db['default']['dbprefix'] = "";
$db['default']['pconnect'] = FALSE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = "";
$db['default']['char_set'] = "utf8";
$db['default']['dbcollat'] = "utf8_general_ci";
$db['default']['swap_pre'] = "";
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;

$active_group = "portal";
$active_record = TRUE;
$db['portal']['hostname'] = "HRPORTAL-DEV";
$db['portal']['username'] = "userPortal";
$db['portal']['password'] = "Hrport@l2012";
$db['portal']['database'] = "PORTAL";
$db['portal']['dbdriver'] = "odbc";
$db['portal']['dbprefix'] = "";
$db['portal']['pconnect'] = FALSE;
$db['portal']['db_debug'] = TRUE;
$db['portal']['cache_on'] = FALSE;
$db['portal']['cachedir'] = "";
$db['portal']['char_set'] = "utf8";
$db['portal']['dbcollat'] = "utf8_general_ci";
$db['portal']['swap_pre'] = "";
$db['portal']['autoinit'] = TRUE;
$db['portal']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./system/application/config/database.php */

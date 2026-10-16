<?php
error_reporting(0);
session_start();
include "variables.php";
include "db.php";
include "function.php";
include "customconfig.php";

//***************************** Standard Function Declarations *******************************//
extract($_POST);
extract($_GET);
//********************************************************************************************//

//***************************** Project Paths ************************************************//
define("SITEURL", "https://". $_SERVER['SERVER_NAME']); // Website URL
define("DOC_ROOT", $_SERVER["DOCUMENT_ROOT"]);
define("ADMINURL", SITEURL . "/admin/"); // Path of admin directory
define("VENDORURL", SITEURL . "/vendors/"); // Path of seller directory
define("SUPPORTURL", SITEURL . "/support/"); // Path of admin directory    
//********************************************************************************************//

//***************************** Document Paths ************************************************//
define('DOCUMENTURL', "https://www.surun.in/documentation/help/");
//********************************************************************************************//

<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Version
define('VERSION', '0.0.0.1');

// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}

require_once DIR_CORE . 'bootstrap.php';

<?php
// Database configuration
define('DB_HOST', 'localhost:3308');
define('DB_NAME', 'blog_platform');
define('DB_USER', 'root'); // Change this to your MySQL username
define('DB_PASS', '3590@Zoro');     // Change this to your MySQL password

// Error reporting (set to false in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Session settings
session_start(); 
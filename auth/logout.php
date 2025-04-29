<?php
require_once '../config/config.php';
require_once '../includes/functions.php';

// Log out the user
logoutUser();

// Redirect to the homepage
header('Location: ../public/index.php');
exit;
?> 
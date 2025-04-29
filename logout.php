<?php
require_once 'config.php';
require_once 'functions.php';

// Log out the user
logoutUser();

// Redirect to the homepage
header('Location: index.php');
exit;
?> 
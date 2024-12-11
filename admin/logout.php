<?php
 require('inc/essentials.php');
 session_start();
 $_SESSION['adminLogin'] = false;
session_regenerate_id(true);
 redirect('index.php');
?>
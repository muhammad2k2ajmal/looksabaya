<?php

if(!isset($_SESSION)){ session_start(); }

error_reporting(E_ALL);
unset($_SESSION['USER_LOGIN']);

if(!isset($_SESSION['USER_LOGIN']) && empty($_SESSION['USER_LOGIN']))
{
	echo "<script>window.location.href='index.php'</script>";
}

?>
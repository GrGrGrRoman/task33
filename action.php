<?php 
	session_start();
	if(isset($_POST['action']) && $_POST['action'] == 'leave')
	{
		require 'db/Users.php';
		$objUser = new Users;
		$objUser->setLoginStatus(0);
	 	$objUser->setLastLogin(date('Y-m-d h:i:s'));
	 	$objUser->setId($_POST['userId']);
	 	if($objUser->updateLoginStatus())
		{
	 		unset($_SESSION['user']);
	 		session_destroy();
	 		echo json_encode(['status'=>1, 'msg'=>"Вышел"]);
	 	}
		else
		{
	 		echo json_encode(['status'=>0, 'msg'=>"Может позже..."]);
	 	}
	}
 ?>
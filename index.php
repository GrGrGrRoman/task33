<?php
session_start();
require_once 'db/Helpers.php';
?>
<!DOCTYPE html>
<html>
<head>
	<title>SF-33. Регистрация</title>
	<link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css" >
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
	<script src="./public/js/jquery.js"></script>
</head>
<body>
	<div class="container">
		<?php
		Helpers::get_alert();
		?>

		<h2 class="text-center" style="margin-top: 5px; padding-top: 0;">Зарегистрируйтесь и войдите в чат</h2>
		<hr>
		<?php

			function d($str): void
			{
				echo '<pre>';
				var_dump($str);
				echo '</pre>';
			}		
			
			if(isset($_POST['join']) and !empty($_POST['email']) and $_POST['token'] == $_COOKIE['PHPSESSID'])
			{
				require 'db/Users.php';
				$objUser = new Users;
				$token = $objUser->createToken();
				$objUser->setEmail($_POST['email']);
				$objUser->setName($_POST['email']);
				$objUser->setPassword(password_hash($_POST['password'], PASSWORD_BCRYPT));
				$objUser->setLoginStatus(0);
				$objUser->setRegStatus(0);
				$objUser->setAvatar(0);
				$objUser->setHideEmail(0);
				$objUser->setToken($token);
			 	$objUser->setLastLogin(date('Y-m-d H:i:s'));
				if ($objUser->getUserByEmail($_POST['email']))
				{
					$_SESSION['errors'][] = 'Пользователь с таким email уже зарегистрирован';
					
				}
				 	if(!$objUser->getUserByEmail($_POST['email']) and $objUser->save())
					{
				 		$lastId = $objUser->dbConn->lastInsertId();
				 		$objUser->setId($lastId);
						$_SESSION['user'][$lastId] = [ 
							'id' => $objUser->getId(), 
							'name' => $objUser->getName(), 
							'email'=> $objUser->getEmail(), 
							'login_status'=>$objUser->getLoginStatus(), 
							'last_login'=> $objUser->getLastLogin() 
						];
						$_SESSION['success'][] = 'Для завершения регистрации подтвердите свой e-mail';
						Helpers::get_alert();
				 	}
					else
					{
						$_SESSION['errors'][] = 'Что-то пошло не так... повторите попытку регистрации';
						Helpers::get_alert();
				 	}
			}

			if (isset($_GET['confirm']))
			{
				require("db/Users.php");
				$objUser = new Users;
				if (!$objUser->checkTokenExists($_GET['confirm']))
        		{
					$_SESSION['errors'][] = 'Ошибка подтверждения регистрации по EMAIL, token не найден';
					Helpers::get_alert();
					exit;
        		}
				$_SESSION['user']['reg_status'] = 1;
				$objUser->activate($_GET['confirm']);
				header("location: login.php");
			}
		 ?>
		<div class="row join-room">
			<div class="col-md-6 col-md-offset-3 mx-auto">
				<form id="join-room-frm" role="form" method="post" action="" class="form-horizontal">
					<!-- CSRF -->
					<input type="hidden" name="token" value='<?=$_COOKIE['PHPSESSID'];?>'>
					<div class="form-group mt-3">
	                	<div class="input-group">
	                    	<input type="email" class="form-control" id="email" name="email" placeholder="Введите Email" value="">
	                	</div>
	                </div>
					<div class="form-group mt-3">
	                	<div class="input-group">
	                    	<input type="password" class="form-control" id="password" name="password" placeholder="Введите пароль" value="">
	                	</div>
	                </div>
	                <div class="form-group mt-3">
	                    <input type="submit" value="Регистрация" class="btn btn-warning btn-block" id="join" name="join">
	                </div>
	                <div class="form-group">
						<p class="text-center mt-3">или, если зарегистрированы: </p>
						<p class="text-center"><a class="btn btn-link" href="login.php">Вход</a></p>
	                </div>
			    </form>				
			</div>
		</div>
	</div>
</body>
</html>
<!DOCTYPE html>
<html>
<head>
	<title>SF-33. Вход</title>
	<link rel= "stylesheet" href= "https://maxst.icons8.com/vue-static/landings/line-awesome/font-awesome-line-awesome/css/all.min.css" >
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
	<script src="./public/js/jquery.js"></script>
</head>
<body>
	<div class="container">
		<h2 class="text-center" style="margin-top: 5px; padding-top: 0;">Войдите в чат</h2>
		<hr>
		<?php
			function d($str): void
			{
				echo '<pre>';
				var_dump($str);
				echo '</pre>';
			}
			
			function dd($str): never
    		{
        		echo '<pre>';
        		var_dump($str);
        		echo '</pre>';
        		exit;
    		}

			if(isset($_POST['join']) and $_POST['token'] == $_COOKIE['PHPSESSID'])
			{
				session_start();
				require("db/Users.php");
				require("db/Helpers.php");
				$objUser = new Users;

				if (!$objUser->checkLogin($_POST['email'], (string)$_POST['password']))
				{
					$_SESSION['errors'][] = 'Email/пароль введены не верно!';
					Helpers::get_alert();

				} elseif (!$objUser->checkReg($_POST['email']))
				{
					$_SESSION['errors'][] = 'Проверьте почту, подтвердите регистрацию по ссылке!';
					Helpers::get_alert();
				}
				else
				{
					$objUser->setEmail($_POST['email']);
					$objUser->setLoginStatus(1);
					$objUser->setLastLogin(date('Y-m-d H:i:s'));
			 		$userData = $objUser->getUserByEmail();
			 		if(is_array($userData) and count($userData)>0)
					{
			 		$objUser->setId($userData['id']);
			 		if($objUser->updateLoginStatus())
					{
			 			$_SESSION['user'][$userData['id']] = $userData;
			 			header("location: chatroom.php");
			 		}
					else
					{
						$_SESSION['errors'][] = 'Вход не удался';
						Helpers::get_alert();
			 		}
			 		}
				}
			
			}

		?>
		<div class="row join-room">
			<div class="col-md-6 col-md-offset-3 mx-auto">
				<form id="join-room-frm" role="form" method="post" action="" class="form-horizontal">
					<!-- CSRF -->
					<input type="hidden" name="token" value="<?=$_COOKIE['PHPSESSID'];?>"> 
					<div class="form-group">
	                </div>
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
	                    <input type="submit" value="Вход" class="btn btn-success btn-block" id="join" name="join">
	                </div>
					<div class="form-group">
						<p class="text-center mt-3">или пройдите регистрацию: </p>
						<p class="text-center"><a class="btn btn-link" href="index.php">Регистрация</a></p>
	                </div>
			    </form>				
			</div>
		</div>
	</div>
</body>
</html>
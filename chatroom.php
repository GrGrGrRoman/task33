<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>SF-33. Чат</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css" integrity="sha384-r4NyP46KrjDleawBgD5tp8Y7UzmLA05oM1iAEQ17CSuDqnUK2+k9luXQOfXJCJ4I" crossorigin="anonymous">
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.min.js" integrity="sha384-oesi62hOLfzrys4LxRF63OJCXdXDipiYWBnvTl9Y9/TRlw5xlKIEHpNyvvDShgf/" crossorigin="anonymous"></script>
	<script src="./public/js/jquery.js"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
	<style type="text/css">
		* {
			font-family: Roboto;
			font-size: 12px;
		}
		#messages {
			height: 350px;
			overflow: auto;
		}
		#chat-room-frm {
			margin-top: 10px;
		}
		#main {
			margin-top: 100px;
		}
		.container {
			width: 922px;
			height: 465px;
			left: -512px;
			gap: 0px;
			opacity: 0px;
		}
		div {
			background: #C4C4C4;
		}
		.btn {
			text-decoration: none;
			color: black;
		}
		.avatar-photo {
			width: 30px;
			height: 30px;
		}
	</style>
</head>
<body>
	<div class="container" id="main">
		<h4 class="text-center pt-2" >Чат</h4>
		<hr>
		<div class="row">
			<div class="col-md-3">
				<?php
					function d($str): void
					{
						echo '<pre>';
						var_dump($str);
						echo '</pre>';
					} 

					if(!isset($_SESSION['user']))
					{
						header("location: index.php");
					}
					require 'db/Users.php';
					require 'db/Chatrooms.php';

					$objChatroom = new Chatrooms;
					$chatrooms   = $objChatroom->getAllChatRooms();

					$objUser = new Users;
					$users = $objUser->getAllUsers();

				?>
				<table class="table">
					<thead>
					<?php 
						foreach ($_SESSION['user'] as $key => $user)
						{
							$userId = $key;
							$hidden = $user['hide_email'];
							echo '<input type="hidden" name="userId" id="userId" value="'.$key.'">';
						}
					?>
						<tr>
							<th class="text-center" colspan="3">Пользователи</th>
						</tr>
					</thead>
					<tbody>
						<?php 
							foreach ($users as $key => $user)
							{
								$status = 'danger';
								$avatar = './public/pic/def_user.png';
								$name = $user['email'];

								if($user['login_status'] == 1)
								{
									$status = 'success';
								}

								if($user['avatar'] == 1)
								{
									$avatar = './public/pic/'.$user['id'].'.png';
								}

								if($user['name'] !== $user['email'] and $user['hide_email'] == 1)
								{
									$name = $user['name'];
								}

								if(!isset($_SESSION['user'][$user['id']]))
								{
								echo "<tr class=''><td class='text-right table-bordered border-".$status."'><img src='".$avatar."' class='avatar-photo' alt='ava'></td>";
								echo "<td class='text-left table-bordered border-".$status."'>".$name."</td>";
								echo "</tr>";
								}
							}
						?>
					</tbody>
				</table>
			</div>
			<div class="col-md-7 border border-dark">
				<div id="messages">
					<table id="chats" class="table">
					  <thead>
					    <tr>
					      <th colspan="4" scope="col">Общий чат</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php 
					  		foreach ($chatrooms as $key => $chatroom)
							{

					  			if($userId == $chatroom['userid'])
								{
					  				$from = 'Я';
					  			}
								else
								{
					  				$from = ($chatroom['name'] == $chatroom['email'] or $chatroom['hide_email'] == 0) ? $chatroom['email'] : $chatroom['name'];
					  			}
					  			echo '<tr><td valign="top"><div class="bg-white text-dark"><strong>'.$from.': '.$chatroom['msg'].'</strong></div><div class="bg-white text-dark">'.date("d.m.Y h:i", strtotime($chatroom['created_on'])).'</div></td><td align="right" valign="top"></td></tr>';
					  		}
					  	 ?>
					  </tbody>
					</table>
				</div>
					
				<form id="chat-room-frm" method="post" action="">
					<div class="d-flex mb-2 bg-white">
                    	<textarea class="form-control" rows="1" id="msg" name="msg" placeholder="Наберите ваше сообщение здесь"></textarea>
						
	                    <input type="image" src="./public/pic/send_btn.png" alt="Отправить сообщение" id="send" name="send">
					</div>
			    </form>
			</div>
			<div class="col-md-2">
				<a href="profile.php" type="button" class="btn btn-link">Профиль</a>
					<?php 
						foreach ($_SESSION['user'] as $key => $user)
						{
						$userId = $key;
						echo '<input type="hidden" name="userId" id="userId" value="'.$key.'">';
						}
					?>
				<p><input type="button" class="btn btn-link" id="leave-chat" name="leave-chat" value="Выйти"></p>
			</div>
		</div>
	</div>
</body>
<script type="text/javascript">
	$(document).ready(function(){
		var conn = new WebSocket('ws://localhost:8080');
		conn.onopen = function(e) {
		    console.log("Connection established!");
		};

		conn.onmessage = function(e) {
		    console.log(e.data);
		    var data = JSON.parse(e.data);
		    var row = '<tr><td valign="top"><div class="bg-white text-dark"><strong>'+data.from+': '+data.msg+'</strong></div><div class="bg-white text-dark">'+data.dt+'</div></td><td align="right" valign="top"></td></tr>';
		    $('#chats > tbody').prepend(row);

		};

		conn.onclose = function(e) {
			console.log("Connection Closed!");
		}

		$("#send").click(function(){
			var userId 	= $("#userId").val();
			var msg 	= $("#msg").val();
			var data = {
				userId: userId,
				msg: msg
			};
			conn.send(JSON.stringify(data));
			$("#msg").val("");
		});

		$("#leave-chat").click(function(){
			var userId 	= $("#userId").val();
			$.ajax({
				url:"action.php",
				method:"post",
				data: "userId="+userId+"&action=leave"
			}).done(function(result){
				var data = JSON.parse(result);
				if(data.status == 1) {
					conn.close();
					location = "index.php";
				} else {
					console.log(data.msg);
				}
				
			});
			
		})

	})
</script>
</html>
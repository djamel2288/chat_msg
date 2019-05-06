<?php 
	include ('database_connection.php');

	session_start();
	
	$message = '';

	if (isset($_SESSION['user_id']))
	{
		header('location:test.php');
	}
	if (isset($_POST['register']))
	{
		$username = trim($_POST['username']);
		$password = trim($_POST['password']);
		$check_query = 
		"
			SELECT * FROM login
			WHERE username = :username
		";	
		$statement = $connect->prepare($check_query);

		$check_data = array(
			':username'  =>  $username
		);

		if ($statement->execute($check_data)) 
		{
			if ($statement->rowCount() > 0) 
			{
				$message .= '<p><label text-danger>Username Taken</label></p>';
			}
			else 
			{
				if (empty($username)) 
				{
					$message.= '<p><label>Username Is required</label></p>';
				}
				if (empty($password)) 
				{
					$message.= '<p><label>Password Is required</label></p>';
				}
				else 
				{
					if ($password != $_POST['confirm_password']) 
					{
						$message.= '<p><label>Password not Match</label></p>';	
					}
				}
				if ($message == '') 
				{
					$data = array(
									':username'   => $username,
									':password'   => password_hash($password,PASSWORD_DEFAULT)
								  );
					$query = '
						INSERT INTO login (username, password)
						VALUES (:username, :password)	
					';

					$statement = $connect->prepare($query);
					if ($statement->execute($data)) 
					{
						$message.= '<p><label>Registration Completed</label></p>';
					}
				}
			}

		}

			
	}
	


?>

<html>  
    <head>  
        <title>Chat Application using PHP Ajax Jquery</title>  
		<meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
	    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.0/jquery.min.js"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
     </head>  
    <body>  
        <div class="container">
			<br />
			<br />
			<div class="panel panel-default">
  				<div class="panel-body">
					<p class="text-danger"><?php echo $message; ?></p>
					<form method="post">
						<div class="form-group">
							<label>Enter Username</label>
							<input type="text" name="username" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Enter Password</label>
							<input type="password" name="password" class="form-control" required />
						</div>
						<div class="form-group">
							<label>Confirm Password</label>
							<input type="password" name="confirm_password" class="form-control" required />
						</div>
						<div class="form-group">
							<input type="submit" name="register" class="btn btn-info" value="Register" />
						</div>
						<div align="center">
							<a href="login.php">Login</a>
						</div>
					</form>
					<br />
					<br />
					
					
				</div>
			</div>
		</div>

    </body>  
</html>
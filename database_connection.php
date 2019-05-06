<?php

//database_connection.php

//PDO connection
$connect = new PDO("mysql:host=localhost;dbname=chat;charset=utf8mb4", "root", "");

//Time Zone
date_default_timezone_set('Africa/Algiers');

//fetch_user_last_activity
function fetch_user_last_activity($user_id, $connect)
{
	$query = "
	SELECT * FROM login_details 
	WHERE user_id = '$user_id' 
	ORDER BY last_activity DESC 
	LIMIT 1
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['last_activity'];
	}
}

//fetch_user_chat_history
function fetch_user_chat_history($from_user_id, $to_user_id, $connect)
{
	$query = "
	SELECT * FROM chat_message 
	WHERE (from_user_id = '".$from_user_id."' 
	AND to_user_id = '".$to_user_id."') 
	OR (from_user_id = '".$to_user_id."' 
	AND to_user_id = '".$from_user_id."') 
	ORDER BY timestamp DESC
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '<ul class="list-unstyled" style="display: flex;flex-direction: column-reverse;">';
	foreach($result as $row)
	{
		$user_name = '';

		//Your msg
		if($row["from_user_id"] == $from_user_id)
		{
			$user_name = '<b class="text-success">You</b>';
			$output .= '
			<li class=" border-0" style="word-break: break-all">
				<div class="mx-2" align="right">
					'.$user_name.'
				</div>
				<div class="rounded bg-success p-1 mx-2">
					'.$row["chat_message"].'
				</div>
					
				<div class="mx-2">
					<small><em>'.$row['timestamp'].'</em></small>
				</div>
					
			</li>
			';
		}

		//His msh
		else
		{
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
			$output .= '
			<li class=" border-0" style="word-break: break-all">
				<div class="mx-2">
					'.$user_name.'
				</div>
				<div class="rounded bg-danger p-1 mx-2">
					'.$row["chat_message"].'
				</div>
					
				<div align="right" class="mx-2">
					<small><em>'.$row['timestamp'].'</em></small>
				</div>
					
			</li>
			';
		}
	}
	$output .= '</ul>';
	$query = "
	UPDATE chat_message 
	SET status = '0' 
	WHERE from_user_id = '".$to_user_id."' 
	AND to_user_id = '".$from_user_id."' 
	AND status = '1'
	";
	//status = 1 -> unseen msg
	//status = 0 -> seen msg

	$statement = $connect->prepare($query);
	$statement->execute();
	return $output;
}

function get_user_name($user_id, $connect)
{
	$query = "SELECT username FROM login WHERE user_id = '$user_id'";
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	foreach($result as $row)
	{
		return $row['username'];
	}
}


//nbr of unsseen msg, status = 1 mean unseen msg
function count_unseen_message($from_user_id, $to_user_id, $connect)
{
	$query = "
	SELECT * FROM chat_message 
	WHERE from_user_id = '$from_user_id' 
	AND to_user_id = '$to_user_id' 
	AND status = '1'
	";
	$statement = $connect->prepare($query);
	$statement->execute();
	$count = $statement->rowCount();
	$output = '';
	if($count > 0)
	{
		if ($count >= 10)
		{
			$output = '<span class="badge badge-danger badge-counter mt-2">9+</span>';
		}
		else 
		{
			$output = '<span class=" badge badge-danger badge-counter mt-2" style="font-size:18px">'.$count.'</span>';		
		}
	}
	return $output;
}

function fetch_is_type_status($user_id, $connect)
{
	$query = "
	SELECT is_type FROM login_details 
	WHERE user_id = '".$user_id."' 
	ORDER BY last_activity DESC 
	LIMIT 1
	";	
	$statement = $connect->prepare($query);
	$statement->execute();
	$result = $statement->fetchAll();
	$output = '';
	foreach($result as $row)
	{
		if($row["is_type"] == 'yes')
		{
			$output = '<small><em><span class="text-muted">Typing...</span></em></small>';
		}
	}
	return $output;
}

function fetch_group_chat_history($connect)
{
	$query = "
	SELECT * FROM chat_message 
	WHERE to_user_id = '0'  
	ORDER BY timestamp DESC
	";

	$statement = $connect->prepare($query);

	$statement->execute();

	$result = $statement->fetchAll();

	$output = '<ul class="list-unstyled">';
	foreach($result as $row)
	{
		$user_name = '';
		if($row["from_user_id"] == $_SESSION["user_id"])
		{
			$user_name = '<b class="text-success">You</b>';
		}
		else
		{
			$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $connect).'</b>';
		}

		$output .= '

		<li style="border-bottom:1px dotted #ccc">
			<p>'.$user_name.' - <span class="bg-info rounded">'.$row['chat_message'].'</span>
				<div align="right">
					- <small><em>'.$row['timestamp'].'</em></small>
				</div>
			</p>
		</li>
		';
	}
	$output .= '</ul>';
	return $output;
}


?>
<?php

//fetch_user.php

include('database_connection.php');

session_start();

$query = "
SELECT * FROM login 
WHERE user_id != '".$_SESSION['user_id']."' 
";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$output = '<ul>';

foreach ($result as $row) 
	{

		$status = '';
		$current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
		$current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
		$last_activity_user = fetch_user_last_activity($row['user_id'], $connect);
		if($last_activity_user > $current_timestamp)
		{
			$status = '<i class="fas fa-circle text-success fa-xs" style="font-size: 10px;"></i>';
		}
		else
		{
			$status = '<i class="fas fa-circle text-danger fa-xs" style="font-size: 10px;"></i>';
		}


		$output .= '
			<li class="">
				<a href="#" class="start_chat" data-touserid="'.$row['user_id'].'" data-tousername="'.$row['username'].'">
		    		<div class="row">
	    				
	    				<div class="col-md-8">
							<img src="avatar-teacher.png" alt="aa" class="mt-1" style="height: 5.5vmin;">
	    					'.$row['username'].'
	    					'.fetch_is_type_status($row['user_id'], $connect).'
						</div>	
	
						<div class="col-md-1">
							'.count_unseen_message($row['user_id'], $_SESSION['user_id'], $connect).'
						</div>

						<div class="col-md-1 text-right mt-2 pt-2 pr-4">
							'.$status.'
	    				</div>

					</div>			
		    	</a>		
			</li>';
	}

$output .='</ul>';

echo $output;

?>
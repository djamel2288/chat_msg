<!--
//index.php
!-->

<?php

include('database_connection.php');

session_start();

if(!isset($_SESSION['user_id']))
{
	header("location:login.php");
}

?>

<html>  
    <head>
    	<script async='async' src='//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js'></script>
		<script>
		  (adsbygoogle = window.adsbygoogle || []).push({
			google_ad_client: "ca-pub-4529508631166774",
			enable_page_level_ads: true
		  });
		</script>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
        <title>Chat Application using PHP Ajax Jquery</title>  
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="emojionearea-master/dist/emojionearea.min.css">
		<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  		<script src="emojionearea-master/dist/emojionearea.min.js"></script>
  		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.2/jquery.form.js"></script>
    </head>  
    <style>

    	.list-amie
    	{
    		position: fixed;
    		right: 0px;
    		height: 100%;
    		padding: 0px;

    	}
	
    	.list-amie ul
    	{
    		list-style-type: none;
    		padding: 0px;
    	}



    	.list-amie ul li
    	{
    		display:block;
    		background-color: rgb(200,200,200);
    		height: 42px;
    	}

    	.list-amie ul li a
    	{
			color: white;
    		text-decoration: none;
    	}

    	.list-amie li:hover
    	{
    		display:block;
    		background-color: rgb(222,222,222);
    	}




    	.msg
    	{
    		position: fixed;
    		bottom: 0px;
    		padding: 0px;
    	}
    	.msg ul
    	{
    		list-style-type: none;

    	}
    	.msg ul li
    	{
    		display: inline-block;
    		float: right;
    		border: solid 1px gray; 
    	}

    	.msg ul li .head,
    	{
			width: 250px;
    	}
    	.body textarea
    	{
    		width:  250px;
    		height: 60px;
    		border: none;
    		border-top: solid 1px gray;
    		border-bottom: solid 1px gray;
    		font-size: 2.3vmin;
    	}
    	.head
    	{
    		width:  250px;
    	}
    	.chat_history
    	{
    		width: 250px;
    		height: 200px;
    		overflow-y:scroll;
    	}


    </style>
    <body>  

		<div class="container-fluid">
			<div class="row">
				<!-- user name + logout -->
				<p align="right">Hi - <?php echo $_SESSION['username']; ?> - <a href="logout.php">Logout</a></p>
				<br>
	    		<!-- liste d'amis -->
		    	<div class="list-amie col-md-2 bg-success" id="user_details">
		    		
				
		    	</div>
						<!--button id="addElem">Add</button-->
		    	
		    	
		    	<div class="msg col-md-10">
		    		<ul id="list">
		    			<!-- liste msg -->
		    		</ul>
		        </div>
		    </div>
		</div>
	    		
    
    </body>  


</html>

<script>
  
   

</script>
<script>
	/*$(document).ready(function() {
		var i = 1;
		var list = document.getElementById("list");
		var add = document.getElementById('addElem');
		add.addEventListener('click', function() {
		  var itemsByTagName = document.getElementsByTagName("li");
		  //list.innerHTML += '<li>item ' + i++ + '</li>'
		  list.innerHTML += '<li class="mx-1 mt-3"><div class="head bg-success text-center"><label for="">hhhhhhh</label></div><div class="hist_msg"></div><div class="body"><textarea ></textarea></div></li>'
		});
	});*/

</script>
<script>


	$(document).ready(function($) {

		//here, is the call of fatch_user() function
		fatch_user();
		

		//functions inside this Inteval,renewed every 9s.
		setInterval(function () {
			last_activiy_update();
			fatch_user();
			update_chat_history_data();
		},5000);


		//this function fetch user details, and plced as a list it in the right of the page
		function fatch_user()
		{
			$.ajax({
				url: 'fatch_user.php',
				type: 'POST',
				success:function(data)
				{
					$('#user_details').html(data);
				}
			})	
		}

		//this function update the last activity from other users
		function last_activiy_update () 
		{
			$.ajax({
					url: 'update_activiy.php',
					type: 'POST',
					success:function()
					{

					}
				})
					
		}

		// create box msg
		function make_msg_box (to_user_id,to_user_name) 
		{

			var msg_box =  			'<li class="mx-1 mt-3 msg_content " data-touserid="'+to_user_id+'" >';
		     msg_box +=  				'<div class="head bg-success text-center  border-bottom border-secondary">';
		     msg_box +=  					'<div class="row">';
		     msg_box +=  						'<div class="col-md-6 bg-da py-1 show_hide_click" data-touserid="'+to_user_id+'">';
		     msg_box +=  							'<img src="avatar-teacher.png" alt="aa" style="height: 5.5vmin;" class="mr-2">';
			 msg_box +=      						'<label for="">'+to_user_name+'</label>'	;
		     msg_box +=  						'</div>';
		    						
			  msg_box +=     					'<div class="col-md-6 text-right">';
			  msg_box +=     						'<a href="#">';
			 msg_box +=  	    						'<i class="fas fa-times-circle fa-lg text-white fa-sm m-2 pt-2 hide_msg_content" data-touserid="'+to_user_id+'"></i>';
			 msg_box +=  	    					'</a>';
			 msg_box +=      					'</div>';
				    					
		     msg_box +=  					'</div>'	;		    							    					
		     msg_box +=  				'</div>';
		     msg_box +=  				'<div class="show_hide_msg" data-touserid="'+to_user_id+'">';
		     msg_box +=  					'<div class="chat_history " id="chat_history_'+to_user_id+'" data-touserid="'+to_user_id+'">';
			 msg_box +=  						fetch_user_chat_history(to_user_id);
		     msg_box +=  					'</div>';
			 msg_box +=      				'<div class="body">';
			   					
			msg_box +=			  						'<div class="form-group">';
			msg_box +=			    						'<textarea placeholder="Write msg..." style="overflow:hidden;resize: none" name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control chat_message"></textarea>';
			msg_box +=			    					'</div>';
			msg_box +=			    					'<div class="text-right form-group">';
			msg_box +=			    						'<a href="#" id="'+to_user_id+'" class=" send_chat">';
			msg_box +=			    							'<i class="m-2 fas fa-paper-plane"></i>	';
			msg_box +=			    						'</a>';
			msg_box +=			    					'</div>';

			 msg_box +=      				'</div>';
		     msg_box +=  				'</div>';
			    				
		     msg_box +=  			'</li>';

		     $('#list').append(msg_box);
		}

		var user = [];
		// make_msg_box 
		$(document).on('click', '.start_chat', function()
		{
			var to_user_id = $(this).data('touserid');
			var to_user_name = $(this).data('tousername');

			var i = 0;
			var bool = 0;
				
			if (user.length == 0)
			{
				make_msg_box(to_user_id, to_user_name);
				user.push(to_user_id);
			}
			else
			{	
				while ( (i < user.length) && (bool == 0) ) 
				{
					if(user[i] == to_user_id)
				    {
				    	bool = 1;
					}
					i++;
				}

				if (bool == 0) 
				{
					make_msg_box(to_user_id, to_user_name);
					user.push(to_user_id);
				}
			}
			$('#chat_history_'+to_user_id+'').scrollTop($('#chat_history_'+to_user_id+'').prop('scrollHeight'));
		
		});


		//clicking on hide icon of msg_box for remove it.
		  $(document).on('click', '.hide_msg_content', function()
		  {
			var to_user_id = $(this).data('touserid');

			var i = 0;
			var j;
			while (i < user.length) 
			{
				if(user[i] == to_user_id)
			    {
			    	j = i;
				}
				i++;
			}
			delete user[j];

			$('li[data-touserid="'+to_user_id+'"]').remove();

		  });   

		  //clicking on the head of msg_box for hide/show the content of the msg_box
		  $(document).on('click', '.show_hide_click', function()
		  {
			var to_user_id = $(this).data('touserid');
			$('.show_hide_msg[data-touserid="'+to_user_id+'"]').slideToggle();
		  });


		// send msg 
		$(document).on('click', '.send_chat', function()
		{
			var to_user_id = $(this).attr('id');
			var chat_message = $('#chat_message_'+to_user_id).val();

			$.ajax({
					url: 'insert_chat.php',
					type: 'POST',
					data: {to_user_id: to_user_id,chat_message: chat_message},
					success:function (data) {
						//var element = $('#chat_message_'+to_user_id).emojioneArea();
						//element[0].emojioneArea.setText('');
						$('#chat_message_'+to_user_id).val('');
						$('#chat_history_'+to_user_id).html(data);
						//this is the code that'll show the last msg sent from the user.
					    $('#chat_history_'+to_user_id+'').scrollTop($('#chat_history_'+to_user_id+'').prop('scrollHeight'));
					}
				})
		});

		//fetch user chat history
		function fetch_user_chat_history(to_user_id)
		{
			$.ajax({
				url:"fetch_user_chat_history.php",
				method:"POST",
				data:{to_user_id:to_user_id},
				success:function(data){
					$('#chat_history_'+to_user_id).html(data);
					//this is the code that'll show the last msg sent in the chat history.
					$('#chat_history_'+to_user_id+'').scrollTop($('#chat_history_'+to_user_id+'').prop('scrollHeight'));
				}
			})
		}

		//update chat history
		function update_chat_history_data()
		{	
			//using .each() method, we can access all HTML field. 
			$('.chat_history').each(function(){
				var to_user_id = $(this).data('touserid');
				//calling fetch_user_chat_function()
				fetch_user_chat_history(to_user_id);
			});
		}


		//this code run if the cursor is in the textarea, a label appears on the sg_box of receiver
		$(document).on('focus', '.chat_message', function(){
			var is_type = 'yes';
			$.ajax({
				url:"update_is_type_status.php",
				method:"POST",
				data:{is_type:is_type},
				success:function()
				{

				}
			})
		});

		//this code run if the cursor live the textarea
		$(document).on('blur', '.chat_message', function(){
			var is_type = 'no';
			$.ajax({
				url:"update_is_type_status.php",
				method:"POST",
				data:{is_type:is_type},
				success:function()
				{
					
				}
			})
		});


	});
</script>

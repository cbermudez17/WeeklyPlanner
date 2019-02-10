<!DOCTYPE html>
<html lang="en-US">

<head>
<meta charset="UTF-8">
<title>Weekly Planner</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Christian Bermudez" />
<link rel="shortcut icon" href="images/favicon4.ico" type="image/x-icon" />
<link rel="icon" href="images/favicon4.ico" type="image/x-icon" />
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Didact%20Gothic">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-fs-modal.min.css" />
<link rel="stylesheet" href="css/custom-colors.css" />
<link rel="stylesheet" href="css/planner-layout.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<?php
    session_start();
    if (!isset($_SESSION['valid']) || $_SESSION['valid'] == false || !isset($_SESSION['user']) || $_SESSION['user'] == false || !isset($_SESSION['userID'])) {
        $background = "background".rand(1, 11);
?>
<style>
body {
    background: url('images/<?php echo $background; ?>.jpg') no-repeat center center fixed; 
    -webkit-background-size: cover;
    -moz-background-size: cover;
    -o-background-size: cover;
    background-size: cover;
}

.container {
	padding: 50px;
}

::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
	color: #ffffff !important;
	opacity: 1; /* Firefox */
	font-size: 18px !important;
}

.form-login {
	background-color: rgba(0, 0, 0, 0.55);
	padding-top: 10px;
	padding-bottom: 20px;
	padding-left: 20px;
	padding-right: 20px;
	border-radius: 15px;
	border-color: #d2d2d2;
	border-width: 5px;
	color: white;
}

.form-control {
	background: transparent !important;
	color: white !important;
	font-size: 18px !important;
}

h1 {
	color: white !important;
}

h4 {
	border: 0 solid #fff;
	border-bottom-width: 1px;
	padding-bottom: 10px;
	text-align: center;
}

.form-control {
	border-radius: 10px;
}

.text-white {
	color: white !important;
}

.wrapper {
	text-align: center;
}

.footer p {
	font-size: 18px;
}
</style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="mx-auto text-center">
                <h1 class="text-white">Weekly Planner</h1>
                <form class="form-login" method="post" action="Login.php"><br />
                    <h4>Secure Login</h4>
                    <br />
                    <input type="text" name="user" class="form-control input-sm chat-input" placeholder="Username" required />
                    <br /><br />
                    <input type="password" name="pass" class="form-control input-sm chat-input" placeholder="Password" required />
                    <br /><br />
                    <div class="wrapper">
                        <span class="group-btn">
                            <button class="btn btn-primary btn-md" type="submit">Login <i class="fas fa-sign-in-alt"></i></button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <br /><br /><br />
        <div class="footer text-white text-center">
            <p>&copy; <script>document.write(new Date().getFullYear());</script> Weekly Planner. All rights reserved | Designed by <a href="https://twitter.com/bermudez_37">Christian Bermudez</a> for <a href="https://twitter.com/ibettecruz">Ibette Cruz-Lopez</a></p>
        </div>
    </div>
<?php
} else {
    require 'ODBC.php';
?>
<script src="js/sortUtil.js"></script>
<script>
	$('document').ready(function() {
		'use strict';

		// Add dates to the planner days
		var today = new Date();
		var day = today.getDay();
		var date = today.getDate();
		var month;
		if (day > 0) {
			today.setDate( date - day + 1 );
		} else {
			today.setDate(date-6);
		}
		var days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
		for (var i = 0; i < 7; i++) {
			date = today.getDate();
			month = today.getMonth() + 1;
			$('#'+days[i]+' > h3').append('<span class="pl-2">'+month+'/'+date+'</span>');
			today.setDate(date+1);
		}
		
		sortTasks();
		
		// This event handler handles opening and populating the edit modal
		$('.day').on('click', '.edit-btn', function() {
			var modal = $(this).closest('.modal');
			var taskID = $(modal).get(0).id.split('modal')[1];
			var color = $(modal).find('.modal-header').get(0).className.split('bg-')[1].split(' ')[0];
			var title = $(modal).find('.modal-title').text();
			var details = $(modal).find('.modal-body .body-details').text();
			var time = $(modal).find('.modal-body .body-time').text().trim();
			var day = $(modal).closest('.day').attr('id');
			
			$('#update-modal #update-color option[value='+color+']').prop('selected', true);
			$('#update-modal #update-day option[value='+day+']').prop('selected', true);
			$('#update-modal #update-time').val(time);
			$('#update-modal #update-details').val(details);
			$('#update-modal #update-title').val(title);
			$('#update-modal #update-id').val(taskID);
			$(modal).modal('hide');
			$('#update-modal .was-validated').removeClass('was-validated');
			$('#update-modal').modal('show');
		});
		
		// This event handler handles adding new tasks
		$('#add-modal .add-btn').on('click', function() {
			var form = $('#add-modal form');
			if (!$(form).get(0).checkValidity()) {
				$(form).addClass('was-validated');
				return;
			}
			
			var color = $('#add-modal #add-color').val();
			var day = $('#add-modal #add-day').val();
			var time = $('#add-modal #add-time').val().trim().toLowerCase();
			var details = $('#add-modal #add-details').val().trim();
			var title = $('#add-modal #add-title').val().trim();
			
		 	$.ajax({
		 	    url : 'AddTask.php',
		 	    type : "POST",
		 	    data : {
		 	        "color" : color,
					"day" : day,
					"time" : time,
					"details" : details,
					"title" : title
		 	    },
		 	    error: function(xhr, jqXHR, textStatus, errorThrown) {
		 	        alert(xhr.responseText);
		 	    },
		 	    success : function(taskID) {
		 	    	var cardTime = '';
		 	    	var modalTime = '';
		 	    	if (time != '') {
				        cardTime = time + "<br />";
				        modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'+time+'</span><br />';
				    }
				    var newHTML =	'<div class="card bg-'+color+' text-white" data-toggle="modal" data-target="#modal'+taskID+'" id="card'+taskID+'">' +
						                '<div class="card-body text-center">'+cardTime+title+'</div>' +
		                            '</div>' +
		             				'<div class="modal fade" id="modal'+taskID+'">' +
		                 				'<div class="modal-dialog modal-dialog-centered">' +
		                     				'<div class="modal-content">' +
		                         				'<div class="modal-header bg-'+color+' text-white">' +
		                             				'<h4 class="modal-title">'+title+'</h4>' +
		                             				'<button type="button" class="close text-white" data-dismiss="modal">&times;</button>' +
		                     				    '</div>' +
		                 				    	'<div class="modal-body">' +
		                 				    		modalTime +
		                 				    		'<span class="body-details">'+details+'</span>' +
		                 				    	'</div>' +
		                 				    	'<div class="modal-footer">' +
		            								'<button type="button" class="btn btn-outline-success edit-btn">Edit</button>' +
		            								'<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>' +
		            							'</div>' +
		                 					'</div>' +
		             					'</div>' +
		            		        '</div>';
		 	    	
		 			$('#'+day).append(newHTML);
		 			sortTasks();
		 			$('#add-modal').modal('hide');
		 			removeBufferData();
		 	    }
	 		});
		});

		// This event handler handles updating tasks
		$('#update-modal .update-btn').on('click', function() {
			var form = $('#update-modal form');
			if (!$(form).get(0).checkValidity()) {
				$(form).addClass('was-validated');
				return;
			}
			
			var color = $('#update-modal #update-color').val();
			var day = $('#update-modal #update-day').val();
			var time = $('#update-modal #update-time').val().trim().toLowerCase();
			var details = $('#update-modal #update-details').val().trim();
			var title = $('#update-modal #update-title').val().trim();
			var taskID = $('#update-modal #update-id').val();
			
		 	$.ajax({
		 	    url : 'UpdateTask.php',
		 	    type : "POST",
		 	    data : {
		 	        "taskID" : taskID,
		 	        "color" : color,
					"day" : day,
					"time" : time,
					"details" : details,
					"title" : title
		 	    },
		 	    error: function(xhr, jqXHR, textStatus, errorThrown) {
		 	        alert(xhr.responseText);
		 	    },
		 	    success : function() {
		 	    	var cardTime = '';
		 	    	var modalTime = '';
		 	    	if (time != '') {
				        cardTime = time + "<br />";
				        modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'+time+'</span><br />';
				    }
				    var prevColor = $('#card'+taskID).get(0).className.split('bg-')[1].split(' ')[0];
		 	        $('#card'+taskID+' .card-body').html(cardTime+title);		 	        
		 	        $('#modal'+taskID+' .modal-body').html(modalTime+'<span class="body-details">'+details+'</span>');
		 			$('#modal'+taskID+' .modal-title').text(title);
		 			$('#card'+taskID+', #modal'+taskID+' .modal-header').removeClass('bg-'+prevColor).addClass('bg-'+color);
		 			$('#modal'+taskID+', #card'+taskID).detach().appendTo('#'+day);
		 			sortTasks();
		 			$('#update-modal').modal('hide');
		 	    }
	 		});
		});
		
		// This event handler handles deleting tasks
		$('.day').on('click', '.delete-btn', function() {
			var deleteTask = confirm('Are you sure you want to delete this task?');
			if (deleteTask) {
				var taskID = $(this).closest('.modal').get(0).id.split('modal')[1];
		 		$.ajax({
		 		    url : 'DeleteTask.php',
		 		    type : "POST",
		 		    data : {
		 		        "taskID" : taskID
		 		    },
		 		    error: function(xhr, jqXHR, textStatus, errorThrown) {
		 		        alert(xhr.responseText);
		 		    },
		 		    success : function() {
		 		    	$('#modal'+taskID).modal('hide');
		 		    	$('#card'+taskID).remove();
		 		    }
	 			});
			}
		});

		// Add handler for ading items in To-Do list
		$('#todo').on('click', '.fa-plus', function() {
			var row = $(this).closest('.row');
			var text = $('#todo-text').val().trim();
			var urgent = $(row).hasClass('urgent') ? 1 : 0;

			if (text == '') {
				return;
			}
			
	 		$.ajax({
	 		    url : 'AddTodo.php',
	 		    type : "POST",
	 		    data : {
	 		        "text" : text,
	 		        "urgent" : urgent
	 		    },
	 		    error: function(xhr, jqXHR, textStatus, errorThrown) {
	 		        alert(xhr.responseText);
	 		    },
	 		    success : function(todoID) {
        			var html =	'<div id="todo'+todoID+'" class="align-items-center row row-action border-top py-3 px-2'+(urgent==1?' urgent':'')+'">' +
            						'<div class="col-md-6 col-9 text-left pl-2"><i class="far fa-square"></i>&nbsp;<span class="ml-1">'+text+'</span></div>' +
            						'<div class="col-md-6 col-3 text-right pr-2"><i class="fas fa-exclamation-circle fa-fw ml-1"></i><i class="fas fa-trash-alt fa-fw ml-1"></i></div>' +
            					'</div>';
					$('#todo').append(html);
					$('#todo'+todoID).insertBefore('#add-todo');
					$('#todo-text').val('');
					$(row).removeClass('urgent');
	 		    }
			});
		});
		
		// Add handler for check boxes in To-Do list
		$('#todo').on('click', '.fa-square, .fa-check-square', function() {
			var row = $(this).closest('.row') ;
			var todoID = $(row).get(0).id.split('todo')[1];
			var checked = $(this).hasClass('fa-square') ? 1 : 0;
			var urgent = $(row).hasClass('urgent') ? 1 : 0;

			$.ajax({
	 		    url : 'UpdateTodo.php',
	 		    type : "POST",
	 		    data : {
	 		        "todoID" : todoID,
	 		        "checked" : checked,
	 		        "urgent" : urgent
	 		    },
	 		    error: function(xhr, jqXHR, textStatus, errorThrown) {
	 		        alert(xhr.responseText);
	 		    },
	 		    success : function() {
	 		    	$(row).find('.text-left i').toggleClass('fa-square').toggleClass('fa-check-square');
	 				$(row).find('span').toggleClass('line-through');
	 		    }
			});
		});
		
		// Add handler for exclamation circle in To-Do list
		$('#todo').on('click', '.fa-exclamation-circle', function() {
			var row = $(this).closest('.row') ;
			var todoID = $(row).get(0).id.split('todo')[1];
			if (todoID == '') {
				$(row).toggleClass('urgent');
				return;
			}
			var checked = $(row).find('.text-left i').hasClass("fa-square") ? 0 : 1;
			var urgent = $(row).hasClass('urgent') ? 0 : 1;

			$.ajax({
	 		    url : 'UpdateTodo.php',
	 		    type : "POST",
	 		    data : {
	 		        "todoID" : todoID,
	 		        "checked" : checked,
	 		        "urgent" : urgent  
	 		    },
	 		    error: function(xhr, jqXHR, textStatus, errorThrown) {
	 		        alert(xhr.responseText);
	 		    },
	 		    success : function() {
	 		    	$(row).toggleClass('urgent');
	 		    }
			});
			
		});

		// Removes row in To-Do List
		$('#todo').on('click', '.fa-trash-alt', function() {
			var deleteRow = confirm('Are you sure you want to delete this item?');
			if (deleteRow) {
				var todoID = $(this).closest('.row').get(0).id.split('todo')[1];

				$.ajax({
		 		    url : 'DeleteTodo.php',
		 		    type : "POST",
		 		    data : {
		 		        "todoID" : todoID
		 		    },
		 		    error: function(xhr, jqXHR, textStatus, errorThrown) {
		 		        alert(xhr.responseText);
		 		    },
		 		    success : function() {
						$('#todo'+todoID).remove();
		 		    }
				});
			}
		});
		
		// Removes row in Deadline
		$('#deadlines').on('click', '.fa-trash-alt', function() {
			var deleteRow = confirm('Are you sure you want to delete this deadline?');
			if (deleteRow) {
				var deadlineID = $(this).closest('.row').get(0).id.split('deadline')[1];

				$.ajax({
		 		    url : 'DeleteDeadline.php',
		 		    type : "POST",
		 		    data : {
		 		        "deadlineID" : deadlineID
		 		    },
		 		    error: function(xhr, jqXHR, textStatus, errorThrown) {
		 		        alert(xhr.responseText);
		 		    },
		 		    success : function() {
		 		    	$('#deadline'+deadlineID).remove();
		 		    }
				});
			}
		});
		
		// Adds row in Deadline
		$('#deadlines').on('click', '.fa-plus', function() {
			var text = $('#deadline-text').val().trim();
			var date = $('#deadline-date').val().trim();

			if (text == '' || date == '') {
				return;
			}
			
	 		$.ajax({
	 		    url : 'AddDeadline.php',
	 		    type : "POST",
	 		    data : {
	 		        "text" : text,
	 		        "date" : date
	 		    },
	 		    error: function(xhr, jqXHR, textStatus, errorThrown) {
	 		        alert(xhr.responseText);
	 		    },
	 		    success : function(deadlineID) {
	 		    	var dateParts = date.substring(2).split('-');
 		    		var html = 	'<div id="deadline'+deadlineID+'" class="align-items-center row row-action border-top py-3 px-2">' +
        							'<div class="col-md-4 col-6 text-left pl-2"><span>'+text+'</span></div>' +
        							'<div class="col-md-4 col-4 text-center"><span>'+dateParts[1]+'/'+dateParts[2]+'/'+dateParts[0]+'</span></div>' +
        							'<div class="col-md-4 col-2 text-right pr-2"><i class="fas fa-trash-alt fa-fw ml-1"></i></div>' +
        						'</div>';
					$('#deadlines').append(html);
					$('#deadline'+deadlineID).insertBefore('#add-deadline');
					sortDeadlines();
					$('#deadline-text').val('');
					$('#deadline-date').val('');
	 		    }
			});
		});

		// Add handler for class select in Classes tab
		$('#class-select').on('change', function() {
			$('.class-btn-group').hide();
			$('#class'+ $(this).val() ).show();
		});
		
		// Disable all class group btns that don't have href
		$('.class-btn-group a').each(function() {
			if ($(this).attr('href') == '') {
				$(this).addClass('disabled');
			}
		});

		// Check all deadlines to send emails
		var deadlinesWeek = [];
		var deadlinesTomorrow = [];
		$('#deadlines .deadline-date').each(function() {
			var deadlineDate = $(this).text();
			var today = new Date();
			var tomorrow = new Date(new Date(today.getFullYear(), today.getMonth(), today.getDate() + 1));
			var nextWeek = Date.parse(new Date(today.getFullYear(), today.getMonth(), today.getDate() + 7));
			
			if (Date.parse(deadlineDate) > Date.parse(today)) { // Check if deadline has not passed yet
				if (nextWeek >= Date.parse(deadlineDate)) { // Check if deadline is within a week away
					if (Date.parse(deadlineDate) == Date.parse(tomorrow)) { // Check if the deadline is tomorrow
						deadlinesTomorrow.push($(this).closest('.row').get(0).id.split('deadline')[1]);
					} else {
						deadlinesWeek.push($(this).closest('.row').get(0).id.split('deadline')[1]);
					}
				}
			}
		});
		if (deadlinesWeek.length + deadlinesTomorrow.length > 0) {
    		$.ajax({
     		    url : 'SendDeadlineAlerts.php',
     		    type : "POST",
     		    data : {
     		        "week" : JSON.stringify(deadlinesWeek),
     		        "tomorrow" : JSON.stringify(deadlinesTomorrow)
     		    }
    		});
		}
	});	// end of $(document).ready(function)
	
	// Handles clearing the add-modal form
	function removeBufferData() {
	    $('#add-title, #add-details, #add-time').val('');
	    $('#add-color, #add-day').prop('selectedIndex', 0);
	    $('#add-modal .was-validated').removeClass('was-validated');
	}
</script>
</head>

<body>
	<a class="weatherwidget-io"
		href="https://forecast7.com/en/40d74n74d17/newark/?unit=us"
		data-label_1="Newark" data-label_2="New Jersey"
		data-icons="Climacons Animated" data-theme="weather_one">Newark
		New Jersey</a>
	<script>
		!function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (!d.getElementById(id)) {
				js = d.createElement(s);
				js.id = id;
				js.src = 'https://weatherwidget.io/js/widget.min.js';
				fjs.parentNode.insertBefore(js, fjs);
			}
		}(document, 'script', 'weatherwidget-io-js');
	</script>
	
	<?php
	$conn = new mysqli($url, $user, $pass, $db);
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	$stmt = $conn->prepare("SELECT taskID, title, details, time, color FROM tasks WHERE day=? AND userID=?");
	$stmt->bind_param("si", $day, $_SESSION["userID"]);
	?>

	<div class="container-fluid mb-3">
		<div class="row">
			<h2 class="text-center mx-auto mt-3">Weekly Planner</h2><br />
		</div>
		<div class="row">
			<button class="btn btn-primary mx-auto mb-3" data-toggle="modal" data-target="#add-modal"><i class="fas fa-plus fa-fw"></i> Add Task</button>
		</div>
		<div class="row border">		
			<div id="monday" class="col-sm border day">
				<h3 class="d-flex justify-content-between">Mon</h3>
				<hr />
				<?php
				$day = "monday";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $details, $time, $color);
				
				while ($stmt->fetch()) {
				    $cardTime = "";
				    $modalTime = "";
				    if (strlen($time) != 0) {
				        $cardTime = $time."<br />";
				        $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
				    }
				    echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
				                <div class="card-body text-center">'.$cardTime.$title.'</div>
                            </div>
             				<div class="modal fade" id="modal'.$taskID.'">
                 				<div class="modal-dialog modal-dialog-centered">
                     				<div class="modal-content">
                         				<div class="modal-header bg-'.$color.' text-white">
                             				<h4 class="modal-title">'.$title.'</h4>
                             				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                     				    </div>
                 				    	<div class="modal-body">
                 				    		'.$modalTime.'
                 				    		<span class="body-details">'.$details.'</span>
                 				    	</div>
                 				    	<div class="modal-footer">
            								<button type="button" class="btn btn-outline-success edit-btn">Edit</button>
            								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
            							</div>
                 					</div>
             					</div>
            		        </div>';
				}
				?>
			</div>
			<div id="tuesday" class="col-sm border day">
				<h3 class="d-flex justify-content-between">Tue</h3>
 				<hr />
 				<?php
				$day = "tuesday";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $details, $time, $color);
				
				while ($stmt->fetch()) {
				    $cardTime = "";
				    $modalTime = "";
				    if (strlen($time) != 0) {
				        $cardTime = $time."<br />";
				        $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
				    }
				    echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
				                <div class="card-body text-center">'.$cardTime.$title.'</div>
                            </div>
             				<div class="modal fade" id="modal'.$taskID.'">
                 				<div class="modal-dialog modal-dialog-centered">
                     				<div class="modal-content">
                         				<div class="modal-header bg-'.$color.' text-white">
                             				<h4 class="modal-title">'.$title.'</h4>
                             				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                     				    </div>
                 				    	<div class="modal-body">
                 				    		'.$modalTime.'
                 				    		<span class="body-details">'.$details.'</span>
                 				    	</div>
                 				    	<div class="modal-footer">
            								<button type="button" class="btn btn-outline-success edit-btn">Edit</button>
            								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
            							</div>
                 					</div>
             					</div>
            		        </div>';
				}
				?>
			</div>
			<div id="wednesday" class="col-sm border day">
				<h3 class="d-flex justify-content-between">Wed</h3>
				<hr />
				<?php
				$day = "wednesday";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $details, $time, $color);
				
				while ($stmt->fetch()) {
				    $cardTime = "";
				    $modalTime = "";
				    if (strlen($time) != 0) {
				        $cardTime = $time."<br />";
				        $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
				    }
				    echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
				                <div class="card-body text-center">'.$cardTime.$title.'</div>
                            </div>
             				<div class="modal fade" id="modal'.$taskID.'">
                 				<div class="modal-dialog modal-dialog-centered">
                     				<div class="modal-content">
                         				<div class="modal-header bg-'.$color.' text-white">
                             				<h4 class="modal-title">'.$title.'</h4>
                             				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                     				    </div>
                 				    	<div class="modal-body">
                 				    		'.$modalTime.'
                 				    		<span class="body-details">'.$details.'</span>
                 				    	</div>
                 				    	<div class="modal-footer">
            								<button type="button" class="btn btn-outline-success edit-btn">Edit</button>
            								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
            							</div>
                 					</div>
             					</div>
            		        </div>';
				}
				?>
			</div>
			<div id="thursday" class="col-sm border day">
				<h3 class="d-flex justify-content-between">Thu</h3>
 				<hr />
 				<?php
				$day = "thursday";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $details, $time, $color);
				
				while ($stmt->fetch()) {
				    $cardTime = "";
				    $modalTime = "";
				    if (strlen($time) != 0) {
				        $cardTime = $time."<br />";
				        $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
				    }
				    echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
				                <div class="card-body text-center">'.$cardTime.$title.'</div>
                            </div>
             				<div class="modal fade" id="modal'.$taskID.'">
                 				<div class="modal-dialog modal-dialog-centered">
                     				<div class="modal-content">
                         				<div class="modal-header bg-'.$color.' text-white">
                             				<h4 class="modal-title">'.$title.'</h4>
                             				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                     				    </div>
                 				    	<div class="modal-body">
                 				    		'.$modalTime.'
                 				    		<span class="body-details">'.$details.'</span>
                 				    	</div>
                 				    	<div class="modal-footer">
            								<button type="button" class="btn btn-outline-success edit-btn" data-target="#update-modal" data-backdrop="static">Edit</button>
            								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
            							</div>
                 					</div>
             					</div>
            		        </div>';
				}
				?>
			</div>
			<div id="friday" class="col-sm border day">
				<h3 class="d-flex justify-content-between">Fri</h3>
 				<hr />
 				<?php
				$day = "friday";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $details, $time, $color);
				
				while ($stmt->fetch()) {
				    $cardTime = "";
				    $modalTime = "";
				    if (strlen($time) != 0) {
				        $cardTime = $time."<br />";
				        $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
				    }
				    echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
				                <div class="card-body text-center">'.$cardTime.$title.'</div>
                            </div>
             				<div class="modal fade" id="modal'.$taskID.'">
                 				<div class="modal-dialog modal-dialog-centered">
                     				<div class="modal-content">
                         				<div class="modal-header bg-'.$color.' text-white">
                             				<h4 class="modal-title">'.$title.'</h4>
                             				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                     				    </div>
                 				    	<div class="modal-body">
                 				    		'.$modalTime.'
                 				    		<span class="body-details">'.$details.'</span>
                 				    	</div>
                 				    	<div class="modal-footer">
            								<button type="button" class="btn btn-outline-success edit-btn">Edit</button>
            								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
            							</div>
                 					</div>
             					</div>
            		        </div>';
				}
				?>
			</div>
			<div id="saturday" class="col-sm border day">
				<h3 class="d-flex justify-content-between">Sat</h3>
 				<hr />
 				<?php
				$day = "saturday";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $details, $time, $color);
				
				while ($stmt->fetch()) {
				    $cardTime = "";
				    $modalTime = "";
				    if (strlen($time) != 0) {
				        $cardTime = $time."<br />";
				        $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
				    }
				    echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
				                <div class="card-body text-center">'.$cardTime.$title.'</div>
                            </div>
             				<div class="modal fade" id="modal'.$taskID.'">
                 				<div class="modal-dialog modal-dialog-centered">
                     				<div class="modal-content">
                         				<div class="modal-header bg-'.$color.' text-white">
                             				<h4 class="modal-title">'.$title.'</h4>
                             				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                     				    </div>
                 				    	<div class="modal-body">
                 				    		'.$modalTime.'
                 				    		<span class="body-details">'.$details.'</span>
                 				    	</div>
                 				    	<div class="modal-footer">
            								<button type="button" class="btn btn-outline-success edit-btn">Edit</button>
            								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
            							</div>
                 					</div>
             					</div>
            		        </div>';
				}
				?>
			</div>
			<div id="sunday" class="col-sm border day">
				<h3 class="d-flex justify-content-between">Sun</h3>
				<hr />
				<?php
				$day = "sunday";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $details, $time, $color);
				
				while ($stmt->fetch()) {
				    $cardTime = "";
				    $modalTime = "";
				    if (strlen($time) != 0) {
				        $cardTime = $time."<br />";
				        $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
				    }
				    echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
				                <div class="card-body text-center">'.$cardTime.$title.'</div>
                            </div>
             				<div class="modal fade" id="modal'.$taskID.'">
                 				<div class="modal-dialog modal-dialog-centered">
                     				<div class="modal-content">
                         				<div class="modal-header bg-'.$color.' text-white">
                             				<h4 class="modal-title">'.$title.'</h4>
                             				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                     				    </div>
                 				    	<div class="modal-body">
                 				    		'.$modalTime.'
                 				    		<span class="body-details">'.$details.'</span>
                 				    	</div>
                 				    	<div class="modal-footer">
            								<button type="button" class="btn btn-outline-success edit-btn">Edit</button>
            								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
            							</div>
                 					</div>
             					</div>
            		        </div>';
				}
				?>
			</div>
		</div>

		<br />
		<div class="container mt-3">
			<ul class="nav nav-tabs">
				<li class="nav-item"><a class="nav-link active"
					data-toggle="tab" href="#todo">To-Do</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab"
					href="#deadlines">Deadlines</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab"
					href="#classes">Classes</a></li>
				<li class="nav-item"><a class="nav-link" data-toggle="tab"
					href="#links">Links</a></li>
			</ul>

			<div class="tab-content">
				<div id="todo" class="container tab-pane active">
					<br />
					<h3>To-Do List</h3>
					<?php
					$stmt = $conn->prepare("SELECT itemID, description, urgent, checked FROM todoItems WHERE userID=? ORDER BY urgent DESC");
					$stmt->bind_param("i", $_SESSION["userID"]);
    				$stmt->execute();
    				$stmt->bind_result($itemID, $description, $urgent, $checked);
    				
    				while ($stmt->fetch()) {
    				    $checkClass = 'square';
    				    $urgentClass = '';
    				    $lineClass = '';
    				    if ($checked) {
    				        $checkClass = 'check-square';
    				        $lineClass = 'line-through';
    				    }
    				    if ($urgent) {
    				        $urgentClass = 'urgent';
    				    }
    				    echo    '<div id="todo'.$itemID.'" class="align-items-center row row-action border-top py-3 px-2 '.$urgentClass.'">
        							<div class="col-md-6 col-9 text-left pl-2"><i class="far fa-'.$checkClass.'"></i>&nbsp;<span class="ml-1 '.$lineClass.'">'.$description.'</span></div>
        							<div class="col-md-6 col-3 text-right pr-2"><i class="fas fa-exclamation-circle fa-fw ml-1"></i><i class="fas fa-trash-alt fa-fw ml-1"></i></div>
        						</div>';
    				}
    				?>
					<div id="add-todo" class="align-items-center row row-action border-top py-3">
						<div class="col-md-6 col-9"><input class="form-control" id="todo-text" type="text" placeholder="Todo" /></div>
						<div class="col-md-6 col-3 text-right"><i class="fas fa-exclamation-circle fa-fw ml-1"></i><i class="fas fa-plus fa-fw ml-1"></i></div>
					</div>
				</div>
				<div id="deadlines" class="container tab-pane fade">
					<br />
					<h3>Deadlines</h3>
    				<?php
					$stmt = $conn->prepare("SELECT deadlineID, description, DATE_FORMAT(dueDate, '%m/%d/%y') FROM deadlines WHERE userID=? ORDER BY dueDate");
					$stmt->bind_param("i", $_SESSION["userID"]);
					$stmt->execute();
    				$stmt->bind_result($deadlineID, $description, $date);
    				
    				while ($stmt->fetch()) {
    				    echo    '<div id="deadline'.$deadlineID.'" class="align-items-center row row-action border-top py-3 px-2">
        							<div class="col-md-4 col-6 text-left pl-2"><span>'.$description.'</span></div>
        							<div class="col-md-4 col-4 text-center"><span class="deadline-date">'.$date.'</span></div>
        							<div class="col-md-4 col-2 text-right pr-2"><i class="fas fa-trash-alt fa-fw ml-1"></i></div>
        						</div>';
    				}
    				?>
					<div id="add-deadline" class="align-items-center row row-action border-top py-3">
						<div class="col-md-4 col-6"><input class="form-control" id="deadline-text" type="text" placeholder="Deadline" /></div>
						<div class="col-md-4 col-4 text-center"><input class="form-control" id="deadline-date" type="date" /></div>
						<div class="col-md-4 col-2 text-right"><i class="fas fa-plus fa-fw ml-1"></i></div>
					</div>
				</div>
				<div id="classes" class="container tab-pane fade">
					<br />
					<h3>Classes</h3>
					<select id="class-select" name="day" class="custom-select">
						<option value="1">Safety</option>
						<option value="2">Thermo II</option>
						<option value="3">Structures</option>
						<option value="4">IE492</option>
						<option value="5">MGMT390</option>
					</select>
					<br />
					<br />
					<div id="class1" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href="https://drive.google.com/drive/folders/1zi-mL5uVPAGT3_xZVS0V-wsY0Xw9alhQ"><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/course/view.php?id=26224"><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1" href="mailto:tadevin@njit.edu"><i
							class="fas fa-envelope"></i> Email</a>
					</div>
					<div id="class2" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href="https://drive.google.com/drive/folders/1zi-mL5uVPAGT3_xZVS0V-wsY0Xw9alhQ"><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/pluginfile.php/1170263/mod_resource/content/1/ChE342-2019S.pdf"><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/course/view.php?id=26006"><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1" href="mailto:gor@njit.edu"><i
							class="fas fa-envelope"></i> Email</a>
					</div>
					<div id="class3" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href="https://drive.google.com/drive/folders/1zi-mL5uVPAGT3_xZVS0V-wsY0Xw9alhQ"><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/pluginfile.php/1171363/mod_resource/content/1/ChE375%20Syllabus_S19-01132019.pdf"><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/course/view.php?id=25269"><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1" href="mailto:Irina.Molodetsky@njit.edu"><i
							class="fas fa-envelope"></i> Email</a>
					</div>
					<div id="class4" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href="https://drive.google.com/drive/folders/1zi-mL5uVPAGT3_xZVS0V-wsY0Xw9alhQ"><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/pluginfile.php/1146361/mod_resource/content/0/Spring%202019%20IE492-452%20-HM2%20Syllabus%20Schedule%20Final.pdf"><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/course/view.php?id=26257"><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1" href="mailto:apk1932@njit.edu"><i
							class="fas fa-envelope"></i> Email</a>
					</div>
					<div id="class5" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href="https://drive.google.com/drive/folders/1zi-mL5uVPAGT3_xZVS0V-wsY0Xw9alhQ"><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="pdfs/NJIT%20MGMT%20390%20Syllabus%20Spring%202019.pdf"><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href="https://njit2.mrooms.net/course/view.php?id=27415"><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1" href="mailto:jtr2@njit.edu"><i
							class="fas fa-envelope"></i> Email</a>
					</div>
				</div>
				<div id="links" class="container tab-pane fade">
					<br />
					<h3>Links</h3>
					<div class="list-group list-group-flush">
						<a class="list-group-item list-group-item-action" target="_blank"
							href="https://portal.njit.edu/">NJIT Portal</a>
						<a class="list-group-item list-group-item-action" target="_blank"
							href="http://njit2.mrooms.net/">Moodle</a>
						<a class="list-group-item list-group-item-action" target="_blank"
							href="https://uisapppr3.njit.edu/scbldr/">Schedule Builder</a>
						<a class="list-group-item list-group-item-action" target="_blank"
							href="https://www.njit.edu/registrar/calendars/">Academic
								Calendar</a>
						<a class="list-group-item list-group-item-action" target="_blank"
							href="https://www.njit.edu/registrar/exams/">Exam Schedules</a>
						<a class="list-group-item list-group-item-action" target="_blank"
							href="https://www.njit.edu/registrar/schedules/">Course
								Schedules</a>
						<a class="list-group-item list-group-item-action" target="_blank"
							href="https://myhub.njit.edu/StudentRegistrationSsb/ssb/registration">Registration
								System</a>
					</div>
				</div>
			</div>
		</div>
		
		<br />

		<div class="text-center mx-auto my-3 w-50"><p>&copy; <script>document.write(new Date().getFullYear());</script> Weekly Planner. All rights reserved | Designed by <a href="https://twitter.com/bermudez_37">Christian Bermudez</a> for <a href="https://twitter.com/ibettecruz">Ibette Cruz-Lopez</a></p></div>
		
		<div class="modal fade modal-fullscreen" id="update-modal">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Edit Task</h4>
						<button type="button" class="close" data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
						<form class="needs-validation" novalidate>
    						<div class="form-group row">
    							<label for="update-title" class="col-sm-2 col-form-label">Title</label>
    							<div class="col-sm-10">
    								<input type="text" class="form-control" name="title" id="update-title"
    									placeholder="Title" required>
    								<div class="invalid-feedback">Must enter a title</div>
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="update-details" class="col-sm-2 col-form-label">Details (opt)</label>
    							<div class="col-sm-10">
    								<input type="text" class="form-control" name="details" id="update-details"
    									placeholder="Details">
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="update-time" class="col-sm-2 col-form-label">Time (opt)</label>
    							<div class="col-sm-10">
    								<input type="text" class="form-control" name="time" id="update-time"
    									placeholder="Time" pattern="((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))[AaPp][Mm](\s?-\s?((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))[AaPp][Mm])?">
    								<div class="invalid-feedback">Must enter a valid time e.g 7:15AM or 10:00AM-11:00PM</div>
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="update-color" class="col-sm-2 col-form-label">Color</label>
    							<div class="col-sm-10">
    								<select id="update-color" name="color" class="custom-select">
    									<option class="text-maroon" value="maroon">Maroon</option>
    									<option class="text-firebrick" value="firebrick">Firebrick</option>
    									<option class="text-red" value="red">Red</option>
    									<option class="text-coral" value="coral">Coral</option>
    									<option class="text-dark-salmon" value="dark-salmon">Dark Salmon</option>
    									<option class="text-dark-orange" value="dark-orange">Dark Orange</option>
    									<option class="text-olive" value="olive">Olive</option>
    									<option class="text-yellow" value="yellow">Yellow</option>
    									<option class="text-highlighter" value="highlighter">Highlighter</option>
    									<option class="text-green" value="green">Green</option>
    									<option class="text-spring-green" value="spring-green">Spring Green</option>
    									<option class="text-aqua" value="aqua">Aqua</option>
    									<option class="text-teal" value="teal">Teal</option>
    									<option class="text-cyan" value="cyan">Cyan</option>
    									<option class="text-dodger-blue" value="dodger-blue">Dodger Blue</option>
    									<option class="text-blue" value="blue">Blue</option>
    									<option class="text-magenta" value="magenta">Magenta</option>
    									<option class="text-purple" value="purple">Purple</option>
    									<option class="text-pink" value="pink">Pink</option>
    									<option class="text-slate-gray" value="slate-gray">Slate Gray</option>
    								</select>
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="update-day" class="col-sm-2 col-form-label">Day</label>
    							<div class="col-sm-10">
    								<select id="update-day" name="day" class="custom-select">
    									<option value="monday">Monday</option>
    									<option value="tuesday">Tuesday</option>
    									<option value="wednesday">Wednesday</option>
    									<option value="thursday">Thursday</option>
    									<option value="friday">Friday</option>
    									<option value="saturday">Saturday</option>
    									<option value="sunday">Sunday</option>
    								</select>
    							</div>
    						</div>
    						<input type="hidden" name="taskID" id="update-id">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary update-btn">Save</button>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade modal-fullscreen" id="add-modal">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="modal-title">Add Task</h4>
						<button type="button" class="close" data-dismiss="modal" onclick="removeBufferData()">&times;</button>
					</div>
					<div class="modal-body">
						<form class="needs-validation" novalidate>
    						<div class="form-group row">
    							<label for="add-title" class="col-sm-2 col-form-label">Title</label>
    							<div class="col-sm-10">
    								<input type="text" class="form-control" name="title" id="add-title"
    									placeholder="Title" required>
    								<div class="invalid-feedback">Must enter a title</div>
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="add-details" class="col-sm-2 col-form-label">Details (opt)</label>
    							<div class="col-sm-10">
    								<input type="text" class="form-control" name="details" id="add-details"
    									placeholder="Details">
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="add-time" class="col-sm-2 col-form-label">Time (opt)</label>
    							<div class="col-sm-10">
    								<input type="text" class="form-control" name="time" id="add-time"
    									placeholder="Time" pattern="((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))[AaPp][Mm](\s?-\s?((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))[AaPp][Mm])?">
    								<div class="invalid-feedback">Must enter a valid time e.g 7:15AM or 10:00AM-11:00PM</div>
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="add-color" class="col-sm-2 col-form-label">Color</label>
    							<div class="col-sm-10">
    								<select id="add-color" name="color" class="custom-select" required>
    									<option selected disabled></option>
    									<option class="text-maroon" value="maroon">Maroon</option>
    									<option class="text-firebrick" value="firebrick">Firebrick</option>
    									<option class="text-red" value="red">Red</option>
    									<option class="text-coral" value="coral">Coral</option>
    									<option class="text-dark-salmon" value="dark-salmon">Dark Salmon</option>
    									<option class="text-dark-orange" value="dark-orange">Dark Orange</option>
    									<option class="text-olive" value="olive">Olive</option>
    									<option class="text-yellow" value="yellow">Yellow</option>
    									<option class="text-highlighter" value="highlighter">Highlighter</option>
    									<option class="text-green" value="green">Green</option>
    									<option class="text-spring-green" value="spring-green">Spring Green</option>
    									<option class="text-aqua" value="aqua">Aqua</option>
    									<option class="text-teal" value="teal">Teal</option>
    									<option class="text-cyan" value="cyan">Cyan</option>
    									<option class="text-dodger-blue" value="dodger-blue">Dodger Blue</option>
    									<option class="text-blue" value="blue">Blue</option>
    									<option class="text-magenta" value="magenta">Magenta</option>
    									<option class="text-purple" value="purple">Purple</option>
    									<option class="text-pink" value="pink">Pink</option>
    									<option class="text-slate-gray" value="slate-gray">Slate Gray</option>
    								</select>
    								<div class="invalid-feedback">Must select a color</div>
    							</div>
    						</div>
    						<div class="form-group row">
    							<label for="add-day" class="col-sm-2 col-form-label">Day</label>
    							<div class="col-sm-10">
    								<select id="add-day" name="day" class="custom-select" required>
    									<option selected disabled></option>
    									<option value="monday">Monday</option>
    									<option value="tuesday">Tuesday</option>
    									<option value="wednesday">Wednesday</option>
    									<option value="thursday">Thursday</option>
    									<option value="friday">Friday</option>
    									<option value="saturday">Saturday</option>
    									<option value="sunday">Sunday</option>
    								</select>
    								<div class="invalid-feedback">Must select a day</div>
    							</div>
    						</div>
    					</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="removeBufferData()">Cancel</button>
						<button type="button" class="btn btn-primary add-btn">Create</button>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
<?php
	$stmt->close();
    $conn->close();
}
?>
	
</body>

</html>
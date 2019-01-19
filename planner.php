<!DOCTYPE html>
<html lang="en-US">

<!--
Fixes:

Fix error messages for each input in both add and update modals
-->
<head>
<meta charset="UTF-8">
<title>Weekly Planner</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" />
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" />
<link rel="stylesheet" href="css/bootstrap-fs-modal.min.css" />
<link rel="stylesheet" href="css/custom-colors.css" />
<link rel="stylesheet" href="css/planner-layout.css" />
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script
	src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
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
			$('#'+days[i]+' > h3').append('<span class="float-right">'+month+'/'+date+'</span>');
			today.setDate(date+1);
		}
		
		sortTasks();
		
		// This event handler handles opening and populating the edit modal
		$('.edit-btn').on('click', function() {
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
			alert('submit');
			return;
			
			var color = $('#add-modal #add-color').val();
			var day = $('#add-modal #add-day').val();
			var time = $('#add-modal #add-time').val().trim();
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
			return;
			
			var color = $('#update-modal #update-color').val();
			var day = $('#update-modal #update-day').val();
			var time = $('#update-modal #update-time').val().trim();
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
		 	        $('#card'+taskID+' .card-body').html(time+'<br />'+title);
		 	        $('#modal'+taskID+' .body-time').html( time=="" ? '<i class="fas fa-clock"></i>&nbsp;'+time : '' );
		 	        $('#modal'+taskID+' .body-details').text(details);
		 			$('#modal'+taskID+' .modal-title').text(title);
		 			$('#card'+taskID+', #modal'+taskID+' .modal-header').removeClass($('#card'+taskID).get(0).className.split('bg-')[1].split(' ')[0]).addClass('bg-'+color);
		 			$('#modal'+taskID+', #card'+taskID).detach().appendTo('#'+day);
		 			sortTasks();
		 			$('#update-modal').modal('hide');
		 	    }
	 		});
		});
		
		// This event handler handles deleting tasks
		$('.delete-btn').on('click', function() {
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
// 		 		        console.log(arguments);
// 		 		        displayError(xhr.responseText);
		 		        alert(xhr.responseText);
		 		    },
		 		    success : function() {
		 		    	$('#modal'+taskID).modal('hide');
		 		        $('#modal'+taskID+', #card'+taskID).remove();
		 		    }
	 			});
			}
		});
		
		// Add listener to water glasses from water goal section
		$('#water-goals i').on('click', function() {
			$(this).toggleClass('text-primary');
			if ($('#water-goals i').length == $('#water-goals i.text-primary').length) {
				$('#water-message').show();
			} else {
				$('#water-message').hide();
			}
		});
		// Display message when water goal is hit
		if ($('#water-goals i').length == $('#water-goals i.text-primary').length) {
			$('#water-message').show();
		}
		
		// Add handler for check boxes in To-Do list
		$('.fa-square, .fa-check-square').on('click', function() {
			$(this).toggleClass('fa-square');
			$(this).toggleClass('fa-check-square');
			$(this).closest('li').find('span > span').toggleClass('line-through');
		});
		
		// Add handler for exclamation circle in To-Do list
		$('.fa-exclamation-circle').on('click', function() {
			var li = $(this).closest('li').toggleClass('urgent');
		});
		
		// Add handler for class select in Classes tab
		$('#class-select').on('change', function() {
			$('.class-btn-group').hide();
			$('#class'+ $(this).val() ).show();
		});

		// Removes row in Deadline
		$('#deadlines .fa-trash-alt').on('click', function() {
			var deleteRow = confirm('Are you sure you want to delete this deadline?');
			if (deleteRow) {
				$(this).closest('li').remove();
			}
		});		
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
	
	$conn = new mysqli("sql2.njit.edu", "cb283", "tJ8YOsDYk", "cb283");
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
	$stmt = $conn->prepare("SELECT taskID, title, details, time, color FROM tasks WHERE day=?");
	$stmt->bind_param("s", $day);
	
	?>

	<div class="container-fluid mb-3">
		<div class="row">
			<h2 class="text-center mx-auto mt-3">Weekly Planner</h2><br />
		</div>
		<div class="row">
			<button class="btn btn-primary mx-auto mb-3" data-toggle="modal" data-target="#add-modal"><i class="fas fa-plus fa-fw"></i> Add Task</button>
		</div>
		<div class="row border">
			
			<?php
			
// 			$days = array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
// 			foreach ($days as $day) {
// 			    echo    '<div id="'.$day.'" class="col-sm border day">
//         				    <h3>'.ucfirst($day).'</h3>
//         				<hr />';
// 			    $dayOfWeek = substr($day, 0, 2);
// 			    $stmt->execute();
// 			    $stmt->bind_result($taskID, $title, $details, $time, $color);
			    
// 			    while ($stmt->fetch()) {
// 			        $cardTime = "";
// 			        $modalTime = "";
// 			        if (strlen($time) != 0) {
// 			            $cardTime = $time."<br />";
// 			            $modalTime = '<span class="body-time"><i class="fas fa-clock"></i>&nbsp;'.$time.'</span><br />';
// 			        }
// 			        echo    '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="card'.$taskID.'">
// 				                <div class="card-body text-center">'.$cardTime.$title.'</div>
//                             </div>
//              				<div class="modal fade" id="modal'.$taskID.'">
//                  				<div class="modal-dialog modal-dialog-centered">
//                      				<div class="modal-content">
//                          				<div class="modal-header bg-'.$color.' text-white">
//                              				<h4 class="modal-title">'.$title.'</h4>
//                              				<button type="button" class="close text-white" data-dismiss="modal">&times;</button>
//                      				    </div>
//                  				    	<div class="modal-body">
//                  				    		'.$modalTime.'
//                  				    		<span class="body-details">'.$details.'</span>
//                  				    	</div>
//                  				    	<div class="modal-footer">
//             								<button type="button" class="btn btn-outline-success edit-btn">Edit</button>
//             								<button type="button" class="btn btn-outline-danger delete-btn">Delete</button>
//             							</div>
//                  					</div>
//              					</div>
//             		        </div>';
// 			    }
// 			    echo '</div>';
// 			}
			
 			?>
		
			<div id="monday" class="col-sm border day">
				<h3>Monday</h3>
				<hr />
				
				<?php
				$day = "monday";
				echo ucfirst("");
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
				<h3>Tuesday</h3>
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
				<h3>Wednesday</h3>
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
				<h3>Thursday</h3>
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
				<h3>Friday</h3>
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
				<h3>Saturday</h3>
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
				<h3>Sunday</h3>
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
				
				$stmt->close();
				$conn->close();
				
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
				<li class="nav-item"><a class="nav-link" data-toggle="tab"
					href="#water-goals">Water</a></li>
			</ul>

			<div class="tab-content">
				<div id="todo" class="container tab-pane active">
					<br />
					<h3>To-Do List</h3>
					<ul class="list-group list-group-flush">
						<li
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center urgent">
							<span><i class="far fa-square"></i>&nbsp;<span
								class="ml-1">Inbox</span></span>
							<span><i class="fas fa-exclamation-circle fa-fw ml-1"></i><i class="fas fa-trash-alt fa-fw ml-1"></i></span>
						</li>
						<li
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
							<span><i class="far fa-check-square"></i>&nbsp;<span
								class="ml-1 line-through">Ads</span></span>
							<span><i class="fas fa-exclamation-circle fa-fw ml-1"></i><i class="fas fa-trash-alt fa-fw ml-1"></i></span>
						</li>
						<li
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center urgent">
							<span><i class="far fa-check-square"></i>&nbsp;<span
								class="ml-1 line-through">Junk</span></span>
							<span><i class="fas fa-exclamation-circle fa-fw ml-1"></i><i class="fas fa-trash-alt fa-fw ml-1"></i></span>
						</li>
						<li
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
							<span><input type="text" /></span>
							<span><i class="fas fa-exclamation-circle fa-fw ml-1"></i><i class="fas fa-plus fa-fw ml-1"></i></span>
						</li>
					</ul>
				</div>
				<div id="deadlines" class="container tab-pane fade">
					<br>
					<h3>Deadlines</h3>
					<ul class="list-group list-group-flush">
						<li id="deadline1"
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
							<span>Deadline 1</span>
							<span>01/09</span>
							<i class="fas fa-trash-alt fa-fw ml-1"></i>
						</li>
						<li id="deadline2"
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
							<span>Deadline 2</span>
							<span>01/27</span>
							<i class="fas fa-trash-alt fa-fw ml-1"></i>
						</li>
						<li id="deadline3"
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
							<span>Deadline 3</span>
							<span>02/13</span>
							<i class="fas fa-trash-alt fa-fw ml-1"></i>
						</li>
						<li
							class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
							<span><input type="text" placeholder="Name" /></span>
							<span><input type="text" /></span>
							<span><i class="fas fa-plus fa-fw ml-1"></i></span>
						</li>
					</ul>
				</div>
				<div id="classes" class="container tab-pane fade">
					<br>
					<h3>Classes</h3>
					<select id="class-select" name="day" class="custom-select">
						<option value="1">Class 1</option>
						<option value="2">Class 2</option>
						<option value="3">Class 3</option>
					</select>
					<br />
					<br />
					<div id="class1" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1 disabled" target="_blank" href=""><i
							class="fas fa-envelope"></i> Email</a>
					</div>
					<div id="class2" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-envelope"></i> Email</a>
					</div>
					<div id="class3" class="class-btn-group">
						<a class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-file-pdf"></i> PDF</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-file-word"></i> Syllabus</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-graduation-cap"></i> Moodle</a> <a
							class="btn btn-primary text-white m-1" target="_blank" href=""><i
							class="fas fa-envelope"></i> Email</a>
					</div>
				</div>
				<div id="links" class="container tab-pane fade">
					<br>
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
				<div id="water-goals" class="container tab-pane fade">
					<br>
					<h3>Water Goals</h3>
					<i class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i><i
						class="fas fa-fw fa-glass-whiskey"></i> <br /> <span
						id="water-message" class="fas fa-award fa-2x text-warning"></span>
				</div>
			</div>
		</div>
		
		<br />

<!--		<blockquote id="quoteOfDay" class="border rounded text-center mx-auto my-3 w-50">I am blessed to have so many great things in my life - family, friends and God. All will be in my thoughts daily.</blockquote> -->
		
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
    								<div class="invalid-feedback">Must enter a title.</div>
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
    									placeholder="Time" pattern="((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))(A|P)M\s?-\s?((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))(A|P)M">
    								<div class="invalid-feedback">Must enter a time (7:15PM).</div>
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
<!-- 						<button type="button" class="btn btn-primary update-btn" data-faicon="save">Save</button> -->
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
    								<div class="invalid-feedback">Must enter a title.</div>
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
    									placeholder="Time" pattern="((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))(A|P)M\s?-\s?((0?[1-9])|1[0-2]):((0[0-9])|([1-5][0-9]))(A|P)M">
    								<div class="invalid-feedback">Must enter a time (7:15PM).</div>
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
    								<div class="invalid-feedback">Must select a color.</div>
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
    								<div class="invalid-feedback">Must select a day.</div>
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
	
</body>

</html>
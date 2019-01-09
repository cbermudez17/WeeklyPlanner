<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<title>Weekly Planner</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet"
	href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script
	src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script
	src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<style>
.card {
 	margin-bottom: 5px;
}
h3 {
	margin-top: 10px;
}
</style>
<script>
	$('document').ready(function() {
		
		// This event handler handles edit button clicks inside modals
		$('.modal .edit-btn').on('click', function() {
			var modal = $(this).closest('.modal');
			var taskID = $(modal).get(0).id.split('modal')[1];
			var color = $(modal).find('.modal-header').get(0).className.split('bg-')[1].split(' ')[0];
			var title = $(modal).find('.modal-title').html();
			var details = $(modal).find('.modal-body').html();
			var day = $(modal).closest('.day').attr('id').substring(0,2);
	// 		alert('taskID:'+taskID+'\ncolor:'+color+'\ntitle:'+title+'\ndetails:'+details+'\nday:'+day);
			
			$('#update-modal select#color option[value='+color+']').prop('selected', true);
			$('#update-modal select#day option[value='+day+']').prop('selected', true);
			$('#update-modal #update-details').val(details);
			$('#update-modal #update-title').val(title);
			$('#update-modal #update-id').val(taskID);
			$(this).closest('.modal').modal('toggle');
			$('#update-modal').modal({backdrop:'static'});
		});
		
		// This event handler handles delete button clicks to delete cards
		$('.card-body .close').on('click', function() {
// 			$('card');
			alert('Hello');
		});
		
	});

	(function() {
		'use strict';
		window.addEventListener('load', function() {
			// Fetch all the forms we want to apply custom Bootstrap validation styles to
			var forms = document.getElementsByClassName('needs-validation');
			// Loop over them and prevent submission
			var validation = Array.prototype.filter.call(forms, function(form) {
				form.addEventListener('submit', function(event) {
					if (form.checkValidity() === false) {
						event.preventDefault();
						event.stopPropagation();
					}
					form.classList.add('was-validated');
				}, false);
			});
		}, false);
	})();
</script>
</head>

<body>

	<?php
	
	$conn = new mysqli("sql2.njit.edu", "cb283", "tJ8YOsDYk", "cb283");
	if ($conn->connect_error) {
	    die("Connection failed: " . $conn->connect_error);
	}
	
	$stmt = $conn->prepare("SELECT taskID, title, description, time, color FROM tasks WHERE day=?");
	$stmt->bind_param("s", $day);
	
	?>
	
	<div class="container-fluid">
		<div class="row">
<!-- 			<div class="col-sm-12"> -->
<!-- 				<h2 class="text-center mt-3">Weekly Planner</h2><br> -->
<!-- 				<button class="btn btn-primary float-sm-right m-3" data-toggle="modal" data-target="#add-modal" data-backdrop="static"><b>+</b> Add New Task</button> -->
<!-- 			</div> -->
			<h2 class="text-center mx-auto my-3">Weekly Planner</h2>
<!-- 			<button class="btn btn-primary float-right" data-toggle="modal" data-target="#add-modal"><b>+</b> Add New Task</button> -->
		</div>
		
<!-- 		<br /> -->
		<div class="row border">
			<div id="monday" class="col-sm border day">
				<h3>Monday</h3>
				<hr />
				
				<?php
				$day = "mo";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $description, $time, $color);
				
				while ($stmt->fetch()) {
				    if (strlen($time) != 0) {
				        $time .= '&nbsp;';
				    }
				    echo '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="'.$taskID.'">
    				        <div class="card-body text-center"><button type="button" class="close text-white float-right">&times;</button>'.$title.'</div>
    				    </div>
    				    <div class="modal fade" id="modal'.$taskID.'">
        				    <div class="modal-dialog modal-dialog-centered">
            				    <div class="modal-content">
                				    <div class="modal-header bg-'.$color.' text-white">
                    				    <h4 class="modal-title">'.$time.$title.'</h4>
                    				    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            				        </div>
        				            <div class="modal-body">'.$description.'</div>
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
				$day = "tu";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $description, $time, $color);
				
				while ($stmt->fetch()) {
				    if (strlen($time) != 0) {
				        $time .= '&nbsp;';
				    }
				    echo '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="'.$taskID.'">
    				        <div class="card-body text-center"><button type="button" class="close text-white float-right">&times;</button>'.$title.'</div>
    				    </div>
    				    <div class="modal fade" id="modal'.$taskID.'">
        				    <div class="modal-dialog modal-dialog-centered">
            				    <div class="modal-content">
                				    <div class="modal-header bg-'.$color.' text-white">
                    				    <h4 class="modal-title">'.$time.$title.'</h4>
                    				    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            				        </div>
        				            <div class="modal-body">'.$description.'</div>
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
				$day = "we";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $description, $time, $color);
				
				while ($stmt->fetch()) {
				    if (strlen($time) != 0) {
				        $time .= '&nbsp;';
				    }
				    echo '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="'.$taskID.'">
    				        <div class="card-body text-center"><button type="button" class="close text-white float-right">&times;</button>'.$title.'</div>
    				    </div>
    				    <div class="modal fade" id="modal'.$taskID.'">
        				    <div class="modal-dialog modal-dialog-centered">
            				    <div class="modal-content">
                				    <div class="modal-header bg-'.$color.' text-white">
                    				    <h4 class="modal-title">'.$time.$title.'</h4>
                    				    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            				        </div>
        				            <div class="modal-body">'.$description.'</div>
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
				$day = "th";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $description, $time, $color);
				
				while ($stmt->fetch()) {
				    if (strlen($time) != 0) {
				        $time .= '&nbsp;';
				    }
				    echo '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="'.$taskID.'">
    				        <div class="card-body text-center"><button type="button" class="close text-white float-right">&times;</button>'.$title.'</div>
    				    </div>
    				    <div class="modal fade" id="modal'.$taskID.'">
        				    <div class="modal-dialog modal-dialog-centered">
            				    <div class="modal-content">
                				    <div class="modal-header bg-'.$color.' text-white">
                    				    <h4 class="modal-title">'.$time.$title.'</h4>
                    				    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            				        </div>
        				            <div class="modal-body">'.$description.'</div>
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
				$day = "fr";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $description, $time, $color);
				
				while ($stmt->fetch()) {
				    if (strlen($time) != 0) {
				        $time .= '&nbsp;';
				    }
				    echo '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="'.$taskID.'">
    				        <div class="card-body text-center"><button type="button" class="close text-white float-right">&times;</button>'.$title.'</div>
    				    </div>
    				    <div class="modal fade" id="modal'.$taskID.'">
        				    <div class="modal-dialog modal-dialog-centered">
            				    <div class="modal-content">
                				    <div class="modal-header bg-'.$color.' text-white">
                    				    <h4 class="modal-title">'.$time.$title.'</h4>
                    				    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            				        </div>
        				            <div class="modal-body">'.$description.'</div>
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
				$day = "sa";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $description, $time, $color);
				
				while ($stmt->fetch()) {
				    if (strlen($time) != 0) {
				        $time .= '&nbsp;';
				    }
				    echo '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="'.$taskID.'">
    				        <div class="card-body text-center"><button type="button" class="close text-white float-right">&times;</button>'.$title.'</div>
    				    </div>
    				    <div class="modal fade" id="modal'.$taskID.'">
        				    <div class="modal-dialog modal-dialog-centered">
            				    <div class="modal-content">
                				    <div class="modal-header bg-'.$color.' text-white">
                    				    <h4 class="modal-title">'.$time.$title.'</h4>
                    				    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            				        </div>
        				            <div class="modal-body">'.$description.'</div>
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
				$day = "su";
				$stmt->execute();
				$stmt->bind_result($taskID, $title, $description, $time, $color);
				
				while ($stmt->fetch()) {
				    if (strlen($time) != 0) {
				        $time .= '&nbsp;';
				    }
				    echo '<div class="card bg-'.$color.' text-white" data-toggle="modal" data-target="#modal'.$taskID.'" id="'.$taskID.'">
    				        <div class="card-body text-center"><button type="button" class="close text-white float-right">&times;</button>'.$title.'</div>
    				    </div>
    				    <div class="modal fade" id="modal'.$taskID.'">
        				    <div class="modal-dialog modal-dialog-centered">
            				    <div class="modal-content">
                				    <div class="modal-header bg-'.$color.' text-white">
                    				    <h4 class="modal-title">'.$time.$title.'</h4>
                    				    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            				        </div>
        				            <div class="modal-body">'.$description.'</div>
        				        </div>
    				        </div>
				        </div>';
				}
				
				$stmt->close();
				$conn->close();
				
				?>
				
			</div>
		</div>
		
		<blockquote id="quoteOfDay" class="text-center mx-auto my-3 w-50">I am blessed to have so many great things in my life - family, friends and God. All will be in my thoughts daily.</blockquote>
		
		<div class="modal fade" id="update-modal">
			<div class="modal-dialog modal-dialog-centered">
				<form method="post" action="UpdateTask.php" class="needs-validation" novalidate>
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h4 class="modal-title">Update Task</h4>
						<button type="button" class="close text-white"
							data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
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
							<label for="update-color" class="col-sm-2 col-form-label">Color</label>
							<div class="col-sm-10">
								<select id="update-color" name="color" class="custom-select">
									<option class="text-primary" value="primary">Blue</option>
									<option class="text-success" value="success">Green</option>
									<option class="text-danger" value="danger">Red</option>
									<option class="text-info" value="danger">Teal</option>
									<option class="text-warning" value="danger">Yellow</option>
								</select>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="update-day" class="col-sm-2 col-form-label">Day</label>
							<div class="col-sm-10">
								<select id="update-day" name="day" class="custom-select">
									<option value="mo">Monday</option>
									<option value="tu">Tuesday</option>
									<option value="we">Wednesday</option>
									<option value="th">Thursday</option>
									<option value="fr">Friday</option>
									<option value="sa">Saturday</option>
									<option value="su">Sunday</option>
								</select>
							</div>
						</div>
						<input type="hidden" name="taskID" id="update-id">
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-sm btn-primary">Save</button>
					</div>
				</div>
				</form>
			</div>
		</div>
		
		<div class="modal fade" id="add-modal">
			<div class="modal-dialog modal-dialog-centered">
				<form method="post" action="AddTask.php" class="needs-validation" novalidate>
				<div class="modal-content">
					<div class="modal-header bg-primary text-white">
						<h4 class="modal-title">Add New Task</h4>
						<button type="button" class="close text-white"
							data-dismiss="modal">&times;</button>
					</div>
					<div class="modal-body">
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
							<label for="add-color" class="col-sm-2 col-form-label">Color</label>
							<div class="col-sm-10">
								<select id="add-color" name="color" class="custom-select" required>
									<option selected disabled></option>
									<option class="text-primary" value="primary">Blue</option>
									<option class="text-success" value="success">Green</option>
									<option class="text-danger" value="danger">Red</option>
									<option class="text-info" value="danger">Teal</option>
									<option class="text-warning" value="danger">Yellow</option>
								</select>
								<div class="invalid-feedback">Must select a color.</div>
							</div>
						</div>
						
						<div class="form-group row">
							<label for="add-day" class="col-sm-2 col-form-label">Day</label>
							<div class="col-sm-10">
								<select id="add-day" name="day" class="custom-select" required>
									<option selected disabled></option>
									<option value="mo">Monday</option>
									<option value="tu">Tuesday</option>
									<option value="we">Wednesday</option>
									<option value="th">Thursday</option>
									<option value="fr">Friday</option>
									<option value="sa">Saturday</option>
									<option value="su">Sunday</option>
								</select>
								<div class="invalid-feedback">Must select a day.</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-sm btn-primary">Save</button>
					</div>
				</div>
				</form>
			</div>
		</div>
		
	</div>
	
</body>

</html>
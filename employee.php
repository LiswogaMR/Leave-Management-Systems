<?php
	include('functions/session_data.php');
	include('functions/connection.php');
	$loggedInUser = $_SESSION['user']['id'];
	$loggedInUserEmail = $_SESSION['user']['email'];
?>
<!DOCTYPE html>
<html>
<head>
	<title>Leave Management Systems - Leave Number</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="vendor/css/bootstrap.min.css">
	<script src="vendor/js/jquery.min.js"></script>
	<script src="vendor/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="vendor/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
	<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="icon" href="images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="vendor/css/jquery-confirm.min.css">
	<script src="vendor/js/jquery-confirm.min.js"></script>
	<link rel="stylesheet" href="vendor/css/jquery-ui.css">
	<script type="text/javascript" src="vendor/js/datatables.min.js"></script>
	<link rel="stylesheet" href="vendor/css/dataTables.jqueryui.min.css">
	<script type="text/javascript" src="vendor/js/dataTables.jqueryui.min.js"></script>
	<link rel="stylesheet" type="text/css" href="vendor/css/jquery.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="vendor/css/buttons.dataTables.min.css"/>
	<link rel="stylesheet" type="text/css" href="vendor/js/jquery.dataTables.min.js"/>
	<script type="text/javascript" src="vendor/js/dataTables.select.min.js"></script>
	<script type="text/javascript" src="vendor/js/dataTables.buttons.min.js"></script>
	<script type="text/javascript" src="vendor/js/jszip.min.js"></script>
	<script type="text/javascript" src="vendor/js/pdfmake.min.js"></script>
	<script type="text/javascript" src="vendor/js/vfs_fonts.js"></script>
	<script type="text/javascript" src="vendor/js/buttons.html5.min.js"></script>
	<script type="text/javascript" src="vendor/js/buttons.print.min.js"></script>

</head>
<body class="container">
	<!-- header starts here -->
    <?php include('admin-header.php'); ?>
	<!-- header ends here -->
	<!-- page content starts here -->
	<section id="page-container">
		<div id="content-wrap">
			<div class="row">
				<button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#exampleModal">Apply For Leave</button>
			</div>
			<br/>
			<div class="employee">
				<div class="row">
					<div class="pa-tbl-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<table id="leave" class="display" style="width:100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Leave Type</th>
									<th>User Comment</th>
									<th>Manager Comment</th>
									<th>Status</th>
									<th>From Date</th>
									<th>To Date</th>
									<th>No Of Days</th>
									<th></th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Leave Type</th>
									<th>User Comment</th>
									<th>Manager Comment</th>
									<th>Status</th>
									<th>From Date</th>
									<th>To Date</th>
									<th>No Of Days</th>
									<th></th>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</div>
		
			<!-- Example Modal apply leave -->
			<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Apply New Leave</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="functions/functions.php" method="POST" id="formUser">
							<div class="form-group">
								<label for="leaveType" class="col-form-label">Leave Type:</label>
								<select data-rule-required="true" id="leaveType" class="form-control" name="leaveType" data-src="leaveType" required>
									<option value="" selected>Please select...</option>
									<?php

										$sql = "SELECT * FROM leave_type where status = 'Active' "; // Adjust the table name and column name as per your database
										$result = mysqli_query($conn, $sql);

										if(mysqli_num_rows($result) > 0){
											while ($row = $result->fetch_assoc()) {
												echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
											}
										}
									
									?>
								</select>
							</div>
							<div class="form-group">
								<label for="startDate">Start Date:</label>
								<input type="date" id="startDate" name="startDate" required>
							</div>
							<div class="form-group">
								<label for="endDate">End Date:</label>
								<input type="date" id="endDate" name="endDate" required>
							</div>
							<div class="form-group">
								<label for="comment" class="col-form-label">Extra Comment(Optional):</label>
								<textarea id="comment" class="form-control" name="comment" rows="2" cols="10" placeholder="Enter your comment here"></textarea>
							</div>
							<div class="form-group">
								<label for="status" class="col-form-label">Status:</label>
								<input type="text" class="form-control" id="status" name="status" data-src="status" value="Awaiting" readonly>
							</div>
							<input type="hidden" name="actionType" value="applyLeave">
							<div class="modal-footer">
								<button type="button" class="btn btn-custom-close" data-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-primary" id="submitUser">Accept</button>
							</div>
						</form>
					</div>
					
					</div>
				</div>
			</div>

			</div>

			<!-- Example Modal 2 -->
			<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Update Leave Information</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="functions/functions.php" method="POST" id="formUser">
								<input type="hidden" name="rec" id="rec" />
								<div class="form-group">
									<label for="status" class="col-form-label">Name:</label>
									<input type="text" class="form-control" id="name" name="name" data-src="name" readonly>
								</div>
								<div class="form-group">
									<label for="status" class="col-form-label">Surname:</label>
									<input type="text" class="form-control" id="surname" name="surname" data-src="surname" readonly>
								</div>
								<div class="form-group">
									<label for="user_comment_convert" class="col-form-label">User Comment:</label>
									<textarea id="user_comment_convert" class="form-control" name="user_comment_convert" readonly></textarea>
								</div>
								<div class="form-group">
									<label for="start_date" class="col-form-label">Start Date:</label>
									<input type="text" class="form-control" id="start_date" name="start_date" data-src="start_date" readonly>
								</div>
								<div class="form-group">
									<label for="end_date" class="col-form-label">End Date:</label>
									<input type="text" class="form-control" id="end_date" name="end_date" data-src="end_date" readonly>
								</div>
								<div class="form-group">
									<label for="no_of_days" class="col-form-label">No Of Days:</label>
									<input type="text" class="form-control" id="no_of_days" name="no_of_days" data-src="no_of_days" readonly>
								</div>
								<div class="form-group">
									<label for="Status" class="col-form-label">Update Status:</label>
									<select data-rule-required="true" id="Status" class="form-control" name="Status" data-src="Status" required>
									<?php
									
										$sql = "SELECT * FROM leave_status where status = 'Active' "; // Adjust the table name and column name as per your database
										$result = mysqli_query($conn, $sql);
										if(mysqli_num_rows($result) > 0){
											while ($row = $result->fetch_assoc()) {
												echo "<option value='" . $row["id"] . "'>" . $row["name"] . "</option>";
											}
										}
									?>
								</select>
								</div>
								<div class="form-group">
									<label for="manager_comments_convert" class="col-form-label">Manager Comment:</label>
									<textarea id="manager_comments_convert" class="form-control" name="manager_comments_convert" rows="2" cols="25" placeholder="Enter your comment here"></textarea>
								</div>
								<input type="hidden" name="actionType" value="EditLeave">
								<div class="modal-footer">
									<button type="button" class="btn btn-custom-close" data-dismiss="modal">Close</button>
									<button type="submit" class="btn btn-primary" id="submitUser">Accept</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<!-- Example Modal 3-->
			<div class="modal fade" id="exampleModal3" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel2">Leave Information</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<div class="form-group">
								<label for="status" class="col-form-label">Name:</label>
								<input type="text" class="form-control" id="name1" name="name" data-src="name" readonly>
							</div>
							<div class="form-group">
								<label for="status" class="col-form-label">Surname:</label>
								<input type="text" class="form-control" id="surname1" name="surname" data-src="surname" readonly>
							</div>
							<div class="form-group">
								<label for="user_comment_convert" class="col-form-label">User Comment:</label>
								<textarea id="user_comment_convert1" class="form-control" name="user_comment_convert" readonly></textarea>
							</div>
							<div class="form-group">
								<label for="start_date" class="col-form-label">Start Date:</label>
								<input type="text" class="form-control" id="start_date1" name="start_date" data-src="start_date" readonly>
							</div>
							<div class="form-group">
								<label for="end_date" class="col-form-label">End Date:</label>
								<input type="text" class="form-control" id="end_date1" name="end_date" data-src="end_date" readonly>
							</div>
							<div class="form-group">
								<label for="no_of_days" class="col-form-label">No Of Days:</label>
								<input type="text" class="form-control" id="no_of_days1" name="no_of_days" data-src="no_of_days" readonly>
							</div>
							<div class="form-group">
								<label for="leave_status_name" class="col-form-label">Leave Status:</label>
								<input type="text" class="form-control" id="leave_status_name1" name="leave_status_name" data-src="leave_status_name" readonly>
							</div>
							<div class="form-group">
								<label for="manager_comments_convert" class="col-form-label">Manager Comment:</label>
								<textarea id="manager_comments_convert1" class="form-control" name="manager_comments_convert" readonly></textarea>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-custom-close" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	<!-- page content ends here -->
	</section>
	<!-- footer starts here -->
	<?php include('footer.php') ?>
	<!-- footer ends here -->	
	<script type="text/javascript">
		$(document).ready(function() {
			// var full.order_notes = 'true';
			var userTable = $('#leave').DataTable( {
				"serverSide": true,
				//"processing": true,
				"pagingType": "full_numbers",
				// "lengthMenu":[[25,50,100],[25,50,100]],
				"order": [[ 1, "desc" ]],
				"ajax": {
					"url": 'functions/tables/leave.php',
					"type": "POST",
				},
				"initComplete": function (settings, json) {
					$('tr.tableLoader').fadeOut('slow');
					$('tr.triggerActions').fadeIn('slow');
				},
					"dom": 'RfBrtlip',//Bfrtip
					"buttons": ['excel'],

				"columns":[
					{ "data": "name", "name": "name", "searchable": true},
					{ "data": "email", "name": "email", "searchable": false},
					{ "data": "leave_type", "name": "leave_type", "searchable": false},
					{ "data": "user_comments", "name": "user_comments", "searchable": false},
					{ "data": "manager_comments", "name": "manager_comments", "searchable": false},
					{ "data": "status", "name": "status", "searchable": false},
					{ "data": "created", "name": "created", "searchable": true},
					{ "data": "updated", "name": "updated", "searchable": false},
					{ "data": "no_of_days", "name": "no_of_days", "searchable": false},
					{ "data": "actions", "name": "actions", "searchable": false},
					// {render: function( data, type, full ){if(full.order_notes != null){return full.order_notes+' <button style="cursor:pointer;" class="btn btn-satgreen editComments"><small>Edit</small></button>'}else{return '<button style="cursor:pointer;" class="btn btn-satgreen editComments"><small>Edit</small></button>'}}, "name": "order_notes", "searchable": false, "orderable": false}
				],
				"createdRow": function( row, data){
					$(row).attr("id", data.rowID);
					$(row).attr("rec", data.rec);
					$(row).attr("class", "triggerActions");
				},
			});

			$('.dataTables_filter').addClass('pull-left');
			$('.dataTables_length').addClass('pull-right');

			//move buttons to container
			$('.buttonsContainer').append(userTable.buttons().containers());

		});
	</script>

	<script>
		$(document).on('click', '.exampleModal2 ', function (){
			let rec = $(this).attr("rec");
			$('#rec').val(rec);

			let name = $(this).attr("name");
			$('#name').val(name);

			let surname = $(this).attr("surname");
			$('#surname').val(surname);

			let user_comment_convert = $(this).attr("user_comment_convert");
			$('#user_comment_convert').val(user_comment_convert);

			let status = $(this).attr("status");
			$('#status').val(status);

			let start_date = $(this).attr("start_date");
			$('#start_date').val(start_date);

			let end_date = $(this).attr("end_date");
			$('#end_date').val(end_date);

			let no_of_days = $(this).attr("no_of_days");
			$('#no_of_days').val(no_of_days);


			let leave_status_name = $(this).attr("leave_status_name");
			$('#leave_status_name').val(leave_status_name);
			

			let manager_comments_convert = $(this).attr("manager_comments_convert");
			$('#manager_comments_convert').val(manager_comments_convert);
		
		});

	</script>

	<script>

		$(document).on('click', '.exampleModal3', function (){

			let rec = $(this).attr("rec");
			$('#rec1').val(rec);

			let name = $(this).attr("name");
			$('#name1').val(name);

			let surname = $(this).attr("surname");
			$('#surname1').val(surname);

			let user_comment_convert = $(this).attr("user_comment_convert");
			$('#user_comment_convert1').val(user_comment_convert);

			let status = $(this).attr("status");
			$('#status1').val(status);

			let start_date = $(this).attr("start_date");
			$('#start_date1').val(start_date);

			let end_date = $(this).attr("end_date");
			$('#end_date1').val(end_date);

			let no_of_days = $(this).attr("no_of_days");
			$('#no_of_days1').val(no_of_days);


			let leave_status_name = $(this).attr("leave_status_name");
			$('#leave_status_name1').val(leave_status_name);

			let manager_comments_convert = $(this).attr("manager_comments_convert");
			$('#manager_comments_convert1').val(manager_comments_convert);
		
		});

	</script>        
</body>
</html>
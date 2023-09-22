<?php
	include('functions/session_data.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Leave Management Systems - User Page</title>
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
									<option value="Vacation">Vacation</option>
									<option value="Sick Leave">Sick Leave</option>
									<option value="Personal Leave">Personal Leave</option>
									<option value="Maternity Leave">Maternity Leave</option>
									<option value="Paternity Leave">Paternity Leave</option>
									<option value="Study Leave">Study Leave</option>
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
								<label for="comment" class="col-form-label">Comment:</label>
								<textarea id="comment" class="form-control" name="comment" rows="4" cols="50" placeholder="Enter your comment here"></textarea>
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

			<!-- Example Modal -->
			<div class="modal fade" id="exampleModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title" id="exampleModalLabel">Leave Information</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<form action="functions/functions.php" method="POST" id="formUser">
								<input type="hidden" name="rec" id="rec" />
								<div class="form-group">
									<label for="Status" class="col-form-label">Update Status:</label>
									<select data-rule-required="true" id="Status" class="form-control" name="Status" data-src="Status" required>
										<option value="" selected>Please select...</option>
										<option value="Approved">Approved</option>
										<option value="Denied">Denied</option>
										<option value="Overide">Overide</option>
										<option value="Waiting">Waiting</option>
										<option value="Processing">Processing</option>
									</select>
								</div>
								<div class="form-group">
									<label for="manager_comments_convert" class="col-form-label">Manager Comment:</label>
									<textarea id="manager_comments_convert" class="form-control" name="manager_comments_convert" rows="4" cols="50" placeholder="Enter your comment here"></textarea>
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
		$(document).on('click', '.exampleModal2 ', function () {
			let rec = $(this).attr("rec");
			$('#rec').val(rec);

			let name = $(this).attr("name");
			$('#name').val(name);

			let surname = $(this).attr("surname");
			$('#surname').val(surname);

			let email = $(this).attr("email");
			$('#email').val(email);

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

			let manager_comments_convert = $(this).attr("manager_comments_convert");
			$('#manager_comments_convert').val(manager_comments_convert);

		

		});
	</script>





                   
</body>
</html>
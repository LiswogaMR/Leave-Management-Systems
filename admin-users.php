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
				<button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#exampleModal">Add User</button>
			</div>
			<br/>
			<div class="employee">
				<div class="row">
					<div class="pa-tbl-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<table id="users" class="display" style="width:100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Group</th>
									<th>Status</th>
									<th>created/Updated</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>Email</th>
									<th>Group</th>
									<th>Status</th>
									<th>created/Updated</th>
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
						<h5 class="modal-title" id="exampleModalLabel">New message</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="functions/functions.php" method="POST" id="formUser">
							<div class="form-group">
								<label for="name" class="col-form-label">Name:</label>
								<input type="text" class="form-control" id="name" name="name">
							</div>
							<div class="form-group">
								<label for="surname" class="col-form-label">Surname:</label>
								<input type="text" class="form-control" id="surname" name="surname" data-src="surname">
							</div>
							<div class="form-group">
								<label for="password" class="col-form-label">Password:</label>
								<input type="text" class="form-control" id="password" name="password" data-src="password">
							</div>
							<div class="form-group">
								<label for="email" class="col-form-label">E-mail:</label>
								<input type="text" class="form-control" id="email" name="email" data-src="email">
							</div>
							<div class="form-group">
								<label for="permission_group_id" class="col-form-label">Group:</label>
								<select data-rule-required="true" id="permission_group_id" class='form-control' name="permission_group_id" data-src="permission_group_id">
									<option value="" selected>Please select...</option>
								</select>
							</div>
							<div class="form-group">
								<label for="status" class="col-form-label">Status:</label>
								<input type="text" class="form-control" id="status" name="status" data-src="status" value="Active" readonly>
							</div>
							<input type="hidden" name="actionType" value="addUser">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="submitUser">Accept</button>
						<button type="button" class="btn btn-danger" id="deleteUser">Delete</button>
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
			var userTable = $('#users').DataTable( {
				"serverSide": true,
				//"processing": true,
				"pagingType": "full_numbers",
				// "lengthMenu":[[25,50,100],[25,50,100]],
				"order": [[ 1, "desc" ]],
				"ajax": {
					"url": 'functions/tables/users.php',
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
					{ "data": "permission_group_name", "name": "permission_group", "searchable": false},
					{ "data": "status", "name": "status", "searchable": false},
					{ "data": "created", "name": "created", "searchable": false},
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

			$('#users').on('click', '.triggerActions', function () {
				const rowClicked = userTable.row($(this).closest('tr'));
				$('#exampleModal').modal('toggle');
				
				$.ajax({
					type: "POST",
					url: 'functions/functions.php',
					data: {
						rec: $(this).attr('rec'),
						actionType: 'getUser'
					},
					success: function(data){
						var jsonResponse = JSON.parse(data);
						var element;
						$.each(jsonResponse, function(index,jsonObject){
							$.each(jsonObject, function(key,val){
								//prepopulate the fields
								element = $('#formUser').find('#'+key);
								if (element.length) {
									element.val(val);
								}
							});
						});
					},
				});

				//set modal attribute rowindex to corresponding row index
				$('#formUser').attr('rowindex', rowClicked.index());

				$('.modal-title').text('Update user');
				$('#formUser input[name ="actionType"]').val('updateUser');
				//Pass the selected records value
				var record = $("<input>").attr("type", "hidden").attr("name", "rec").val($(this).attr('rec'));
				$('#formUser').append(record);

				$('#deleteUser').show();
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			//Calls to populate dropdown fields etc
			$.ajax({
				type: "POST",
				url: 'functions/functions.php',
				data: {
					rec: $(this).attr('rec'),
					actionType: 'getPermissionGroupAll'
				},
				success: function(data){
					var jsonResponse = JSON.parse(data);
					var element;
					element = $('#formUser').find('#permission_group_id');
					element.html('');
					$.each(jsonResponse, function(index,jsonObject){
						//prepopulate the foreign key fields
						element.append('<option value="'+jsonObject.id+'">'+jsonObject.name+'</option>');

					});
				},
				// dataType: dataType
			});

			$('#exampleModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); 
				var modal = $(this);
				modal.find('.modal-title').text('Add new user');
				
				//Clear modal if it has contents
				$.each($('#formUser input'), function () {
					$(this).val('');
				});

				var actionType = $('#formUser input[name ="actionType"]').val('addUser');
				//Pass the selected records value
				var record = $("<input>").attr("type", "hidden").attr("name", "rec").val(null);
				$('#formUser').append(record);

				$('#deleteUser').hide();

				$(function () {
					$('body').on('click', '#submitUser', function (e) {
						$('#formUser').submit();
						$('#myModal').modal('hide');
					});
				});

				$(function () {
					$('body').on('click', '#deleteUser', function (e) {
						$.confirm({
							title: 'Delete',
							content: 'Are you sure you would like to delete this record?',
							buttons: {
								confirm: function () {
									$('#formUser input[name ="actionType"]').val('deleteUser');
									$('#formUser').submit();
									$('#myModal').modal('hide');
								},
								cancel: function () {
									// $.alert('Canceled!');
								}
							}
						});
					});
				});

			});
		})
	</script>
</body>
</html>
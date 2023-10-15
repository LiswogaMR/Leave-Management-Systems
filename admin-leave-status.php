<?php
	include('functions/session_data.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Leave Status</title>
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
	<section  id="page-container">
		
		<div id="content-wrap">
			<div class="row">
				<button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#exampleModal">Add Leave Status</button>
			</div>
			<br/>
			<div class="employee">
				<div class="row">
					<div class="pa-tbl-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
						<table id="jobTitles" class="display" style="width:100%">
							<thead>
								<tr>
									<th>Name</th>
									<th>Updated</th>
									<th>Status</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<th>Name</th>
									<th>Updated</th>
									<th>Status</th>
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
						<form action="functions/functions.php" method="POST" id="formJobTitle">
							<div class="form-group">
								<label for="name" class="col-form-label">Name:</label>
								<input type="text" class="form-control" id="name" name="name" data-src="name">
							</div>
							<div class="form-group">
								<label for="status" class="col-form-label">Status:</label>
								<input type="text" class="form-control" id="status" name="status" data-src="status" value="Active" readonly>
							</div>
							<input type="hidden" name="actionType" value="addJobTitle">
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" id="submitJobTitle">Accept</button>
						<button type="button" class="btn btn-danger" id="deleteJobTitle">Delete</button>
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
			var jobTitlesTable = $('#jobTitles').DataTable( {
				"serverSide": true,
				//"processing": true,
				"pagingType": "full_numbers",
				// "lengthMenu":[[25,50,100],[25,50,100]],
				"order": [[ 1, "desc" ]],
				"ajax": {
					"url": 'functions/tables/leave-titles.php',
					"type": "POST",
					/*"data": function(data){
						console.log(JSON.stringify(data));
					},*/
					/*"success": function(res){
						console.log(JSON.stringify(res));
					},*/
				},
				"initComplete": function (settings, json) {
					$('tr.tableLoader').fadeOut('slow');
					$('tr.triggerActions').fadeIn('slow');
				},
				"dom": 'lfrBtip',
				"buttons": [
					{ extend: 'excel', text: 'Excel', className: 'btn btn-satgreen' },
				
				],
				"columns":[
					{ "data": "name", "name": "name", "searchable": true},
					{ "data": "updated", "name": "updated", "searchable": false},
					{ "data": "status", "name": "status", "searchable": false},
					// {render: function( data, type, full ){if(full.order_notes != null){return full.order_notes+' <button style="cursor:pointer;" class="btn btn-satgreen editComments"><small>Edit</small></button>'}else{return '<button style="cursor:pointer;" class="btn btn-satgreen editComments"><small>Edit</small></button>'}}, "name": "order_notes", "searchable": false, "orderable": false}
					// {render: function( data, type, full ){return '<button style="cursor:pointer;" class="btn btn-satgreen editComments"><small>Edit</small></button>'}, "name": "order_notes", "searchable": false, "orderable": false}
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
			$('.buttonsContainer').append(jobTitlesTable.buttons().containers());

			$('#jobTitles').on('click', '.triggerActions', function () {
				// console.log($(this).attr('id'));
				//get clicked row invoking row() API method
				//against DataTables object assigned to dataTable
				const rowClicked = jobTitlesTable.row($(this).closest('tr'));
				//open up edit form modal
				$('#exampleModal').modal('toggle');
				//populate edit form with row data by corresponding
				//rowClicked property based on 'data-src' attribute
				$.each($('#formJobTitle input'), function () {
					$(this).val(rowClicked.data()[$(this).attr('data-src')]);
				});
				//set modal attribute rowindex to corresponding row index
				$('#formJobTitle').attr('rowindex', rowClicked.index());

				$('.modal-title').text('Update Leave title');
				$('#formJobTitle input[name ="actionType"]').val('updateJobTitle');
				//Pass the selected records value
				var record = $("<input>").attr("type", "hidden").attr("name", "rec").val($(this).attr('rec'));
				$('#formJobTitle').append(record);
				$('#view-spec').show();
				$('#view-spec').prop("href", "view-job-descriptions.php?id=" + $(this).attr('rec'));
				
				$('#deleteJobTitle').show();
			});
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('#exampleModal').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); // Button that triggered the modal
				// If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
				// Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
				var modal = $(this);
				modal.find('.modal-title').text('New Leave Title');
				
				//Clear modal if it has contents
				$.each($('#formJobTitle input'), function () {
					$(this).val('');
				});

				var actionType = $('#formJobTitle input[name ="actionType"]').val('addJobTitle');
				//Pass the selected records value
				var record = $("<input>").attr("type", "hidden").attr("name", "rec").val(null);
				$('#formJobTitle').append(record);

				$('#view-spec').hide();
				$('#deleteJobTitle').hide();

				$(function () {
					$('body').on('click', '#submitJobTitle', function (e) {
						$('#formJobTitle').submit();
						$('#myModal').modal('hide');
					});
				});

				$(function () {
					$('body').on('click', '#deleteJobTitle', function (e) {
						$.confirm({
							title: 'Delete',
							content: 'Are you sure you would like to delete this record?',
							buttons: {
								confirm: function () {
									$('#formJobTitle input[name ="actionType"]').val('deleteJobTitle');
									$('#formJobTitle').submit();
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
<?php
	include('functions/session_data.php');
?>
<!DOCTYPE html>
<html>
   <head>
      <title>Performance Appraisal - Index page</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="vendor/css/bootstrap.min.css">
	  <script src="vendor/js/jquery.min.js"></script>
      <script src="vendor/js/bootstrap.min.js"></script>
      <link rel="stylesheet" type="text/css" href="css/style.css?<?php echo time(); ?>">
      <link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon">
      <link rel="icon" href="images/favicon.ico" type="image/x-icon">
      <link rel="stylesheet" type="text/css" href="vendor/css/datatables.min.css"/>
      <script type="text/javascript" src="vendor/js/datatables.min.js"></script>
      <link rel="stylesheet" href="vendor/css/jquery-confirm.min.css">
      <script src="vendor/js/jquery-confirm.min.js"></script>
      <link rel="stylesheet" href="vendor/css/jquery-ui.css">
      <link rel="stylesheet" href="vendor/css/dataTables.jqueryui.min.css">
      <script type="text/javascript" src="vendor/js/dataTables.jqueryui.min.js"></script>
   </head>
   <body class="container">
      <!-- header starts here -->
       <?php include('admin-header.php'); ?>
      <style>
      </style>
      <!-- header ends here -->
      <!-- page content starts here -->
	   <section id="page-container">
		
         <div id="content-wrap">
            <div class="hr-admin">
               <div class="row">
                  <div class="pa-tbl-cnt col-xs-12 col-sm-12 col-md-12 col-lg-12">
                     <table id="sum_table" class="display" style="width:100%">
                        <thead>
                           <tr>
                              <th>Status</th>
                              <th>Year</th>
                              <th>Total</th>
                           </tr>
                        </thead>
                        <tfoot>
                           <tr>
                              <th>Status</th>
                              <th>Year</th>
                              <th>Total</th>
                           </tr>
                        </tfoot>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- page content ends here -->
      <!-- footer starts here -->
	   <?php include('footer.php') ?>
      <!-- footer ends here -->
	<script type="text/javascript">
		$(document).ready(function() {
			// var full.order_notes = 'true';
			var jobDescriptionTable = $('#sum_table').DataTable( {
				"serverSide": true,
				//"processing": true,
				"pagingType": "full_numbers",
				// "lengthMenu":[[25,50,100],[25,50,100]],
				"order": [[ 1, "desc" ]],
				"ajax": {
					"url": 'functions/tables/leave_totals.php',
					"type": "POST",
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
					{ "data": "status", "name": "status", "searchable": false},
					{ "data": "year", "name": "year", "searchable": false},
					{ "data": "total", "name": "total", "searchable": false},
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
			$('.buttonsContainer').append(jobDescriptionTable.buttons().containers());

			$('#appraisal').on('click', '.triggerActions', function () {
				
			});
		});
	</script>
   </body>
</html>
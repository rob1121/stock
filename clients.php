<?php
require_once 'php_action/db_connect.php';
require_once 'includes/header.php';
require_once 'php_action/orderDeliveryScheduling.php';
?>

<ol class="breadcrumb">
  <li><a href="dashboard.php">Home</a></li>
  <li>Order</li>
  <li class="active">Clients</li>
</ol>

<div id="success-messages"></div>

<h4>
	<i class='glyphicon glyphicon-circle-arrow-right'></i> Clients
</h4>

<div class="panel panel-default">
			<div class="panel-heading">
				<div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Client</div>
			</div> <!-- /panel-heading -->
			<div class="panel-body">

				<div class="remove-messages"></div>

				<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-default button1" id="addClientModalBtn" data-toggle="modal" data-target="#addClientModel"> <i class="glyphicon glyphicon-plus-sign"></i> Add Client </button>
				</div> <!-- /div-action -->

				<table class="table" id="manageClientTable">
					<thead>
						<tr>
							<th>Client Name</th>
							<th>Address</th>
							<th style="width:15%;">Contact</th>
							<th style="width:15%;">Status</th>
							<th style="width:15%;"></th>
						</tr>
					</thead>
					<tbody></tbody>
				</table>
				<!-- /table -->

			</div> <!-- /panel-body -->
		</div> <!-- /panel -->
	</div> <!-- /col-md-12 -->
</div> <!-- /row -->


<div class="modal fade" id="addClientModel" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">

    	<form class="form-horizontal" id="submitClientForm" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Client</h4>
	      </div>
	      <div class="modal-body">

	      	<div id="add-Client-messages"></div>

	        <div class="form-group">
	        	<label for="ClientName" class="col-sm-3 control-label">Client Name: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="clientName" placeholder="Client Name" name="ClientName" autocomplete="off">
				    </div>
	        </div>

	        <div class="form-group">
	        	<label for="ClientName" class="col-sm-3 control-label">Client Address: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="clientAddress" placeholder="Client Adress" name="clientAddress" autocomplete="off">
				    </div>
	        </div
>
	        <div class="form-group">
	        	<label for="ClientName" class="col-sm-3 control-label">Client Contact: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="clientContact" placeholder="Client Phone" name="clientContact" autocomplete="off">
				    </div>
	        </div>

	      </div> <!-- /modal-body -->

	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

	        <button type="submit" class="btn btn-primary" id="createClientBtn" data-loading-text="Loading..." autocomplete="off">Save Changes</button>
	      </div>
	      <!-- /modal-footer -->
     	</form>
	     <!-- /.form -->
    </div>
    <!-- /modal-content -->
  </div>
  <!-- /modal-dailog -->
</div>
<!-- / add modal -->

<script src="custom/js/clients.js"></script>

<?php require_once 'includes/footer.php'; ?>



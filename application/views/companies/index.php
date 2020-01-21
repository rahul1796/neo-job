<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
<div class="content-body" style="padding: 30px; margin-top: 10px;">
  <div class="row">
    <div class="col-md-12">
    <?php $this->load->view('layouts/errors'); ?>
    </div>

  </div>

  <?php if (in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_add_roles())): ?>
    <a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php echo base_url("companiescontroller/create")?>" style="float: right;"><i class="icon-android-add"></i>Add Company</a>
  <?php endif; ?>


    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;margin-top: -34px;">

      <div class="row">
        <div class="col-md-12">
          <h2>Available Companies</h2>
        </div>
        <br>
      </div>
        <div class="breadcrumb-wrapper col-xs-12" style="margin-left: -16px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Company
                </li>
            </ol>
        </div>
    </div>
    <section>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Companies</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>                    
                    <div class="card-body collapse in" style="font-size:0.90rem;">
                        <div class="card-block card-dashboard">
                            <table id="tblList" class="table table-striped table-bordered display responsive nowrap" style="margin-left: 0px!important; ">
                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Actions</th>
                                    <th>Company Name</th>                                    
                                    <th>Opportunities</th>
                                    <th>Description</th>
                                    <th>Industry</th>
                                    <th>Functional Area</th>
				                            <th>SPOC Name</th>
                                    <th>SPOC Email</th>
                                    <th>SPOC Phone</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>Source</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody id="tblBody">
                                </tbody>
                            </table>
                           <?php /*echo $this->pagination->create_links();*/?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->
</div>


   </div>
 </div>
<div class="wrapper">

	<?php

			$this->load->view('common/footer');
	?>
	</div>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  <script>
    var save_method; //for save method string
  var table;

  $(document).ready(function() {

      //datatables
      table = $('#tblList').DataTable({

          "stateSave": true,
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "paging": true,
          "scrollX": true,
          "language": { processing: '<div style="margin-left:-800px;margin-top:50px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
          "aLengthMenu": [[10, 25, 50, 100, 200, -1],[10, 25, 50, 100, 200, "All"]],
          "pageLength": 10,
         // "order": [], //Initial no order.

          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": base_url+"companiescontroller/getCompanyList/",
              "type": "POST",
              error: function()
              {
                $("#table tbody").empty().append('<tr><td align="center" colspan="17">No data found</td></tr>');
              }
          },
          "columnDefs": [
                          { 
                              "targets": [0], //last column
                              "orderable": false, //set not orderable
                          },
                        ],
            "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
                    "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
             buttons: []
      }); 

       /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
  });
  function reload_table()
  {
      table.ajax.reload(null,false); //reload datatable ajax 
  }

</script>
<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  assigned Employers list
 * @date  feb_2017
*/
select.input-sm 
{
 line-height: 10px; 
}
.searchprint
{
  text-align: right;
}
.searchprint .btn-group
{
  padding-bottom: 5px;
}
/*.table td, .table th {
    padding: 0.75rem 0.75rem;
}*/
</style>
<div class="content-body" style="overflow-x: hidden !important;">
    <?php

    if($user_group_id==18)
    {

    ?>
    <a href="<?php echo base_url("partner/add_employer/$bd_exec_id")?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add Employer</button></a>
        <?php
    }
    ?>
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Employers
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Employers</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">

                            <table id="table"  class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th style="width:175px;">Employer</th>
                                    <th>Employer mail</th>
                                    <th>Employer phone</th>
                                    <!--  <th>Spoc Name</th>
                                     <th>Spoc Phone</th> -->
                                    <th>BD Executive</th>
                                    <th>BD Coordinator</th>
                                    <th>BD Regional Manager</th>
                                    <th>BD Head</th>
                                    <th>Status</th>
                                    <th style="width:90px;">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->

</div>

<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_employer_list" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Employer Details</h3>
            </div>
            <div class="modal-body job_status">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<!-- //Modal inner -->
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "paging": true,
        "aLengthMenu": [[25, 50, 100, 200, -1],[25, 50, 100, 200, "All"]],
        "pageLength": 25,
        "language": {"loadingRecords": "Loading...",
                      "processing":     "Processing..",
                      "emptyTable":     "No Employers Found Under this Head"},

       // "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"pramaan/bd_emp_list/",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
                        { 
                            "targets": [ 0,4,5,6,7,8,9, -1 ], //last column
                            "orderable": false, //set not orderable
                        },
                      ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
              "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        /*buttons: ['pdfHtml5','csvHtml5','excelHtml5','print','copyHtml5','colvis']  for simple*/
        buttons: [/*{extend:'pdfHtml5',orientation: 'landscape',pageSize: 'A4',title: 'Employer List',
                              customize: function (doc) 
                              {
                                  doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                              }
                      },

                      'excelHtml5',
                      'copyHtml5',
                      'colvis'*/]
    });
    
     <?php
   if (intval($user_group_id) == 1) {
       echo 'table.columns([4,5,6,8,9]).visible(false);';
   }
   if(intval($user_group_id) == 12 || intval($user_group_id) == 13) {
     echo 'table.columns([4,5,7,8,9]).visible(false);';
   }
   if (intval($user_group_id) == 11) {
       echo 'table.columns([4,6,7,8,9]).visible(false);';
   }
   if (intval($user_group_id) == 8) {
       echo 'table.columns([5,6,7,8,9]).visible(false);';
   }
   if (intval($user_group_id) == 18) {
       echo 'table.columns([4,5,6,7]).visible(false);';
   }
 
 
   ?>

    /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});


function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}




function ToggleActiveStatus(id, active_status) {

    var strStatus = (active_status == 1) ? "Deactivate" : "Activate";
    var strCompletedStatus = (active_status == 1) ? "Deactivated" : "Activated";
    swal(
        {
            title: "",
            text: "Are you sure, you want to " + strStatus + "?",
            showCancelButton: true,
            confirmButtonColor: ((active_status == 1) ? "#d9534f" : "#5cb85c"),
            confirmButtonText: "Yes, " + strStatus + "!",
            cancelButtonText: "No, Cancel!",
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "pramaan/change_employer_status",
                    data: {
                        'id': id,
                        'active_status': active_status
                    },
                    dataType: 'json',
                    success: function (data) {
                        swal({
                                title: "",
                                text: "Account successfully " + strCompletedStatus + "!",
                                confirmButtonColor: ((active_status == 1) ? "#d9534f" : "#5cb85c"),
                                confirmButtonText: 'OK',
                                closeOnConfirm: false,
                                closeOnCancel: false
                            },
                            function(confirmed){
                                window.location.href = base_url + "partner/employers/";
                            });
                    },
                    error: function () {
                        alert("Error Occurred");
                    }
                });

            }
        }
    );
}

function EditEmployer(emp_id)
{
    if (emp_id) {
        document.location.href = base_url+'partner/edit_employers/<?php echo $bd_exec_id; ?>/'+emp_id;
    }
}

</script>





                    


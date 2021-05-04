<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/responsive.dataTables.min.css'?>">
<style type="text/css">
/**
 * @author  George Martin <george.s@navriti.com>
 * @desc  Candidate List
 * @date  March 2017
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
.table td, .table th {
    padding: 0.75rem 0.75rem;
}
</style>
<div class="content-body" style="overflow-x: hidden !important;">
        <div class="row">
            <div class="col-md-12">
                <?php $this->load->view('layouts/errors');?>
            </div>
        </div>

    <div style="float:right; z-index:1000 !important;">
        <?php if (in_array($user['user_group_id'], add_edit_view_user_roles())): ?>
          <a class="btn btn-warning btn-min-width mr-1 mb-1" href="<?= base_url('userscontroller/create')?>"><i class="icon-android-upload"></i> Add User</a>
         <?php endif; ?>

</div>



    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard/","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">User List
                </li>
            </ol>
        </div>
    </div>
    <section id="configuration">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User List</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in" style="font-size:0.90rem;">
                        <div class="card-block card-dashboard">

                            <table id="tblMain" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Actions</th>
                                    <th>Status</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User Role</th>
                                    <th>Reporting Manager</th>
                                    <th>Employee ID</th>
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>

	$(document).ready(function() {
    table = $("#tblMain").DataTable({
        "serverSide": true,
        "processing": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { processing: '<div style="margin-left:-800px;margin-top:50px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
        "ajax": {
            "url": base_url+"employer/get_user_data",
            "type": "POST",
            error: function()
            {
                $("#tblMain tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
            }
        },
        "columnDefs":
            [
                {
                    "targets": [0, 1],
                    "orderable": false
                }
            ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        buttons:
            [
               /* {
                    extend:'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Candidate List',
                    customize: function (doc)
                    {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                'excelHtml5',
                'copyHtml5',
                'colvis'*/
            ],
        "order": [[ 1, "asc" ]]
    });

    $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="icon-refresh"></i> Reload</button>').appendTo('div#table_length');
});
window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove();
      });
  }, 4000);

function reload_table()
{
    table.ajax.reload(null, false);
}

function user_edit(UserId)
{

   document.location.href = base_url + 'userscontroller/edit/' + UserId ;

}

function user_status(id, is_active) {
    var strStatus = (is_active == 1) ? "Deactivate" : "Activate";
    var strCompletedStatus = (is_active == 1) ? "Deactivated" : "Activated";
    swal(
        {
            title: "",
            text: "Are you sure, you want to " + strStatus + "?",
            showCancelButton: true,
            confirmButtonColor: ((is_active == 1) ? "#d9534f" : "#5cb85c"),
            confirmButtonText: "Yes, " + strStatus + "!",
            cancelButtonText: "No, Cancel!",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "pramaan/change_active_status",
                    data: {
                        'id': id,
                        'is_active': is_active
                    },
                    dataType: 'json',
                    success: function (data) {
                        swal({
                                title: "",
                                text: "Account successfully " + strCompletedStatus + "!",
                                confirmButtonColor: ((is_active == 1) ? "#d9534f" : "#5cb85c"),
                                confirmButtonText: 'OK',
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function(confirmed)
                            {
                                reload_table();
                                //window.location.reload();
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
</script>

</div>

<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  recruitment partner list
 * @date  Nov_2016
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
</style>

<div class="content-body" style="overflow-x: hidden !important;">
    <?php
    if (intval($user_group_id) == 9) {
    ?>
    <a href="<?php echo base_url("pramaan/add_regional_manager/$parent_id")?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add Regional Manager</button></a>
        <?php
    }

    ?>
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Regional Managers
                </li>
            </ol>
        </div>
    </div>

    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Regional Managers</h4>
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

                            <table id="tblSrRM" class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Regions</th>
                                    <th>Sourcing Head Name</th>      <!-- In Administrator login -->
                                    <th>State Managers</th>
                                    <th>Status</th>
                                    <th>Actions </th>
                                </tr>
                                </thead>
                                <tbody id="tblBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="divStateManagers" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
                        <div class="modal-dialog modal-lg" role="document" style="width:75%;" >
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h3 class="modal-title">State Manager List</h3>
                                </div>
                                <div class="modal-body">
                                    <table id="tblStateManager" class="table table-striped" cellspacing="0" style="width:100%;">
                                        <thead>
                                        <tr>
                                            <th>SNo.</th>
                                            <th>State Manager Name</th>
                                            <th>Phone</th>
                                            <th>Email</th>
                                            <th>State</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tblStateManagerBody">
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">

$(document).ready(function() {
    table = $('#tblSrRM').DataTable({
       // "stateSave": true,
        "serverSide": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url + "pramaan/regional_managers_list/"+"<?php echo $parent_id;?>",
            "type": "POST",
            error: function()
            {
                $("#tblSrRM tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
            }
        },
        "columnDefs":
            [
                {
                    "targets": [0, 4, 5, 6, 7, -1 ],
                    "orderable": false
                }
            ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        buttons:
            [
                /*{
                    extend:'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Regional Manager',
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

      <?php
    if (intval($user_group_id) == 9) {
        echo 'table.columns([5]).visible(false);';
    }
    else if(intval($user_group_id) == 1)
    {
         echo 'table.columns([6,7,8]).visible(false);';
    }
    else if(intval($user_group_id) == 10)
    {
         echo 'table.columns([5,6,7,8]).visible(false);';
    }
   
    ?>
 

   

    /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
});

function ViewStateManagerList(SrRMId) {
    $("#divStateManagers").modal({ show: true });
    if (SrRMId) {
        $.ajax({
            type: "POST",
            url: base_url + "pramaan/get_state_managers_for_sr_regional_manager",
            data: {
                'id': SrRMId
            },
            dataType: 'json',
            success: function (data) {
                var varTableBodyHtml = "";
                for(var i = 0; i < data.length; i++)
                {
                    varTableBodyHtml += "<tr>";
                    varTableBodyHtml += "<td>" + (i + 1) + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['state_manager_name'] + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['state_manager_phone'] + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['state_manager_email'] + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['state_name_list'] + "</td>";
                    varTableBodyHtml += "</tr>";
                }

                $("#tblStateManagerBody").empty().append(varTableBodyHtml);
                $("#divStateManagers").modal({ show: true });
            },
            error: function () {
                alert("Error Occurred");
            }
        });
    }
}


function EditSourcingRM(SrRMId)
{
    if (SrRMId) {
        document.location.href = base_url + 'pramaan/edit_regional_manager/'+'<?php echo $parent_id;?>'+'/'+ SrRMId;
    }
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
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "pramaan/change_sr_rm_active_status",
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
                                window.location.reload();
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
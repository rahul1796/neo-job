<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  recruitment support list
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

<?php
$options_sectors=array(''=>'-Select Sector-');
$options_orgTypes=array(''=>'-Select Org type-');
?>

<div class="content-body" style="overflow-x: hidden !important;">
    <?php
    if (intval($user_group_id) == 1)
        echo '<a href="' . base_url("pramaan/addedit_rs_head") . '"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add RS Head</button></a>';
    ?>


    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Placement Heads
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Placement Support Heads</h4>
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
                            <div id="divVerticalManagers" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
                                <div class="modal-dialog modal-lg" role="document" style="width:75%;" >
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h3 class="modal-title">Placement Vertical Manager List</h3>
                                        </div>
                                        <div class="modal-body">
                                            <table id="tblVerticalManager" class="table table-striped table-responsive" cellspacing="0" style="width:100%;">
                                                <thead>
                                                <tr>
                                                    <th>SNo.</th>
                                                    <th>Vertical Manager Name</th>
                                                    <th>Phone</th>
                                                    <th>Email</th>
                                                    <th>Verticals</th>
                                                </tr>
                                                </thead>
                                                <tbody id="tblVerticalManagerBody">
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <table id="tblRsHead" class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Placement Head Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Vertical Managers</th>
                                    <th>Status </th>
                                    <th>Actions </th>
                                </tr>
                                </thead>
                                <tbody id="tblBody">


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
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
$(document).ready(function() {
    table = $('#tblRsHead').DataTable({
        //"stateSave": true,
        "serverSide": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url + "pramaan/rs_heads_list/",
            "type": "POST",
            error: function()
            {
                $("#tblRsHead tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
            }
        },
        "columnDefs":
            [
                {
                    "targets": [0, 4, 5, -1 ],
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
                    title: 'Recruitmrnt Support Heads',
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
    if (intval($user_group_id) == 1) {
        echo 'table.columns([5,6]).visible(true);';
    }
    else
    {
        echo 'table.columns([5,6]).visible(false);';
    }
    ?>

    /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
});

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function EditRecruitmentSupportHead(RsHeadId)
{
    document.location.href = base_url + "pramaan/addedit_rs_head/" + RsHeadId;
}

function ViewVerticalManagerList(RsHeadId) {
    $("#divVerticalManagers").modal({ show: true });
    if (RsHeadId) {
        $.ajax({
            type: "POST",
            url: base_url + "pramaan/get_vertical_managers_for_rs_head",
            data: {
                'id': RsHeadId
            },
            dataType: 'json',
            success: function (data) {
                var varTableBodyHtml = "";
                for(var i = 0; i < data.length; i++)
                {
                    varTableBodyHtml += "<tr>";
                    varTableBodyHtml += "<td>" + (i + 1) + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['vertical_manager_name'] + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['vertical_manager_phone'] + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['vertical_manager_email'] + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['vertical_name_list'] + "</td>";
                    varTableBodyHtml += "</tr>";
                }

                $("#tblVerticalManagerBody").empty().append(varTableBodyHtml);
                $("#divVerticalManagers").modal({ show: true });
            },
            error: function () {
                alert("Error Occurred");
            }
        });
    }
}

function EditRecruitmentSupportHead(RsHeadId)
{
    if (RsHeadId) {
        document.location.href = base_url + 'pramaan/addedit_rs_head/' + RsHeadId;
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
                    url: base_url + "pramaan/change_rs_head_active_status",
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
                                closeOnConfirm: true,
                                closeOnCancel: true
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

<!-- Bootstrap modal -->
<div class="inner">
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Recruitment Support Head Form</h3>
            </div>
                <div class="modal-body form">
                <form action="#" id="form_sourcing_admin" class="form-horizontal">
                    <input type="hidden" value="" name="hidRecruitmentSupportHeadId"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Recruitment Head Name<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <input id="txtRecruitmentSupportHeadName" name="txtRecruitmentSupportHeadName" placeholder="Recruitment Support Head Name" class="form-control" type="text">
                                <span class="error_label"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Phone<span class='validmark'>*</span></label>
                             <div class="col-md-9">
                                  <input id="txtRecruitmentSupportHeadPhone" name="txtRecruitmentSupportHeadPhone" placeholder="Phone/mobile" class="form-control" type="text" maxlength="<?= PHONE_MAX?>">
                                  <span class="error_label"></span>
                              </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-3">Email<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <input id="txtRecruitmentSupportHeadEmail" name="txtRecruitmentSupportHeadEmail" placeholder="Email Address" class="form-control" type="email" maxlength="<?= EMAIL_MAX?>">
                                <span class="error_label"></span>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>



                    
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
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
table.dataTable.dtr-inline.collapsed>tbody>tr>td:first-child, table.dataTable.dtr-inline.collapsed>tbody>tr>th:first-child {
    position: relative;
    padding-left: 40px;
    cursor: pointer;
}
</style>

<div class="content-body" style="overflow-x: hidden !important;">
    <!--<a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php /*echo base_url("pramaan/add_address_book/$parent_id")*/?>" style="margin-left: 50px;"><i class="icon-android-add"></i>Add Contact</a>-->

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Address Book
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Contacts</h4>
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
                           <table id="tblSec" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Customer Name</th>
                                    <th>Contact Name</th>
                                    <th>Designation </th>
                                    <th>Contact Phone</th>
                                    <th>Contact Email </th>
                                    <th>Industry </th>
                                    <!--<th>Action </th>-->
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
<script type="text/javascript">
$(document).ready(function() {
    table = $('#tblSec').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "paging": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { processing: '<div style="margin-left:-800px;margin-top:50px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
        "ajax": {
            "url": base_url + "pramaan/address_book_contact_list/"+"<?= $this->session->userdata('usr_authdet')['id'];?>",
            "type": "POST",
            error: function()
            {
                $("#tblSec tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
            }
        },
        "columnDefs":
            [
                {
                    "targets": [0, 4, 5, -1 ],
                    "orderable": false
                }
            ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
        "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        "buttons": [/*'pdf','excel','print','colvis'*/]
    });



   /* $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
});
function reload_table()
{
    table.ajax.reload(null, false);
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
                    url: base_url + "pramaan/change_sector_active_status",
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

function EditSectorStatus(Id)
{
    if (Id) {
        document.location.href = base_url + 'pramaan/edit_rs_sector/' +"<?php echo $parent_id;?>"+"/"+ Id;
    }
}

function ShowDetails(customer_id)
{
  var track_url=base_url+'partner/customer_details/'+customer_id;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var customer_detail_html='';
            if(data.status)
            {
                var employer=data.employer_detail;
                var slno=1;
                customer_detail_html += "<div  style='margin-bottom: 10px'>Customer Name: <span style='font-weight: bold;'>"+employer.customer_name+"</span></div>";               
                
                customer_detail_html += '<div class="row">';
                customer_detail_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; ">';
                customer_detail_html += '<table id="tblApplicationTrackerDetails" class="table table-striped table-bordered display responsive nowrap">';
                customer_detail_html += '<tr><th>Customer Type</th><th>Buisness Vertical</th><th>Spoc Name</th><th>Spoc Email</th><th>Spoc Phone</th><th>Managed by</th><th>Source</th><th>Description</th></tr>';
                
                $.each(data.customer_detail,function(a,b)
                {
                  customer_detail_html += '<tr><td>'+b.customer_type+'</td><td>'+b.buisness_vertical_name+'</td><td>'+b.spoc_name+'</td><td>'+b.spoc_email+'</td><td>'+b.spoc_phone+'</td><td>'+b.lead_managed_by+'</td><td>'+b.lead_source_name+'</td><td>'+b.customer_description+'</td></tr>';
                  slno++;
                });
                
                customer_detail_html += '</table>';
                customer_detail_html += '</div></div>'; 
            }
            $('.candidate_job_status').html(customer_detail_html);

            $("#tblCustomerDetails").DataTable();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_customer').modal('show'); // show bootstrap modal when complete loaded
}
</script>


<div id="modal_form_customer" class="modal fade bs-example-modal-xl" role="dialog">
    <div class="modal-dialog modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:hidden;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Customer Details</h3>
            </div>
            <div class="modal-body candidate_job_status">
                -No records found-
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




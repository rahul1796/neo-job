<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/responsive.dataTables.min.css'?>">
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
.table td, .table th {
    padding: 0.75rem 0.75rem;
}
</style>
<?php
$options_sectors=array(''=>'-Select Sector-');
$options_orgTypes=array(''=>'-Select Org type-');
?>
<div class="content-body" style="overflow-x: hidden !important;">

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Application Tracker
                </li>
            </ol>
        </div>
    </div>
    <div id="divRegionalManagers" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
        <div class="modal-dialog modal-lg" role="document" style="width:75%;" >
            <div class="modal-content">
                <div class="modal-header" style="border-bottom:hidden;">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Sourcing Regional Manager List</h3>
                </div>
                <div class="modal-body">
                    <table id="tblRegionalManager" class="table table-striped" cellspacing="0" style="width:100%; !important;">
                        <thead>
                        <tr>
                            <th>SNo.</th>
                            <th>Regional Manager Name</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Regions</th>
                        </tr>
                        </thead>
                        <tbody id="tblRegionalManagerBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Employers Application Tracker</h4>
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

                            <table id="table" class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SlNo.</th>
                                    <th style="width:15%!important" nowrap>Customer Name</th>
                                    <th>Interested</th>
                                    <th>Profile<br>Submitted</th>
                                    <th>Pending<br>Customer<br>Feedback</th>
                                    <th>Profile<br>Accepted</th>
                                    <th>Profile<br>Rejected</th>
                                    <th>Interview<br>Scheduled</th>
                                    <th>Interview<br>Attended</th>
                                    <th>Interview<br>Not Attended</th>
                                    <th>Selected</th>
                                    <th>Rejected</th>
                                    <th>Offer In<br>Pipeline</th>
                                    <th>Offered</th>
                                    <th>Offer<br>Accepted</th>
                                    <th>Offer<br>Rejected</th>
                                    <th>Joined</th>
                                    <th>Not<br>Joined</th>
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
<div id="modal_form_tracker" class="modal fade bs-example-modal-xl" role="dialog">
    <div class="modal-dialog modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:hidden;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Application Tracker (Customer)</h3>
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
<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_tracker_jobs" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Job Details</h3>
            </div>
            <div class="modal-body employer_job_status" style="max-height: 30em!important;overflow: auto;">
                -No records found-
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
            "url": base_url+"partner/employers_tracker_list/"+"<?php echo $bd_exec_id?>",
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

//function tracked_jobs(employer_id)
//{
//  var track_url=base_url+'partner/jobs_by_employer/'+employer_id+'/';
//  $.ajax({
//        url : track_url,
//        type: "GET",
//        dataType: "JSON",
//        success: function(data)
//        {
//            var candidate_track_list_html='- No Data Found-';
//            if(data.status)
//            {
//                var employer=data.employer_detail;
//                var slno=1;
//                candidate_track_list_html = "<div style='margin-bottom: 10px'>Employer Name: <span style='font-weight: bold;'>"+employer.employer_name+"</span></div>";
//                candidate_track_list_html += "<div style='margin-bottom: 10px'>Status: <span style='font-weight: bold;'>"+data.candidate_job_status_name+"</span></div>";
//
//                candidate_track_list_html += '<div class="row">';
//                candidate_track_list_html += '<div class="col-sm-12 col-md-12">';
//                candidate_track_list_html += '<table class="table">';
//                candidate_track_list_html += '<tr><th>Sl No</th><th>Qualification Pack</th><th>Job Description</th></tr>';
//                
//                $.each(data.job_detail,function(a,b)
//                {
//                  candidate_track_list_html += '<tr><td>'+slno+'</td><td>'+b.qualification_pack_id+'</td><td>'+b.job_desc+'</td></tr>';
//                  slno++;
//                });
//                
//                candidate_track_list_html += '</table>';
//                candidate_track_list_html += '</div></div>'; 
//            }
//            $('.employer_job_status').html(candidate_track_list_html);
//        },
//        error: function (jqXHR, textStatus, errorThrown)
//        {
//            alert('Error get data from ajax');
//        }
//    });
//    $('#modal_form_tracker_jobs').modal('show'); // show bootstrap modal when complete loaded
//}
function tracked_candidates(customer_id,job_status_id)
{
  var track_url=base_url+'partner/tracked_candidates_employerjob/'+customer_id+'/'+job_status_id;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var candidate_track_list_html='';
            if(data.status)
            {
                var employer=data.employer_detail;
                var slno=1;
                candidate_track_list_html += "<div  style='margin-bottom: 10px'>Customer Name: <span style='font-weight: bold;'>"+employer.customer_name+"</span></div>";
                candidate_track_list_html += "<div style='margin-bottom: 10px'>Status: <span style='font-weight: bold;'>"+data.candidate_job_status_name+"</span></div>";
                
                candidate_track_list_html += '<div class="row">';
                candidate_track_list_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; height: 400px;">';
                candidate_track_list_html += '<table id="tblApplicationTrackerDetails" class="table table-striped table-bordered display responsive nowrap">';
                candidate_track_list_html += '<tr><th>SNo</th><th>Candidate Name</th><th>Mobile</th><th>QP</th><th>Job Title</th><th>Job Description</th><th>Job Location</th></tr>';
                
                $.each(data.candidate_detail,function(a,b)
                {
                  candidate_track_list_html += '<tr><td>'+slno+'</td><td>'+b.candidate_name+'</td><td>'+b.mobile+'</td><td>'+b.qualification_pack_name+'</td><td>'+b.job_title+'</td><td>'+b.job_desc+'</td><td>'+b.location_name+'</td></tr>';
                  slno++;
                });
                
                candidate_track_list_html += '</table>';
                candidate_track_list_html += '</div></div>'; 
            }
            $('.candidate_job_status').html(candidate_track_list_html);

            $("#tblApplicationTrackerDetails").DataTable();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_tracker').modal('show'); // show bootstrap modal when complete loaded
}
</script>

<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit associates list
 * @date  Nov_2016
*/

select.input-sm 
{
    line-height: 10px; 
}

table.dataTable#table
{
    /*font-weight: normal;
    font-size: 0.9em!important;*/
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
<div class="inner">
<h4>Job Status</h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("employer/dashboard","Dashboard");?> </li>
    <li class="active"> Application Tracker </li>
  </ul>
</small>
<hr/>
   <table id="table" class="table table-striped table-responsive" cellspacing="0">
    <thead>
        <tr>
            <th>SlNo.</th>
            <th style="width:200px!important" nowrap>Job Role</th>
            <th>Applied</th>
            <th>Screened</th>
            <th>Scheduled</th>
            <th>Shortlisted</th>
            <th>Selected</th>
            <th>Offered</th>
            <th>Screen<br>Rejected</th>
            <th>Schedule<br>Rejected</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
  </table>

    
</div><!-- inner -->

<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_tracker" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Job Status</h3>
            </div>
            <div class="modal-body job_status">
                Modal for Application tracker Details.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<!-- //Modal inner -->

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
        "aLengthMenu": [[25, 50, 100, 200, -1],[25, 50, 100, 200, "All"]],
        "pageLength": 25,
        "language": {
            "loadingRecords": "Loading...",
            "processing":     "Processing.."
        },
       // "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"employer/application_tracker_list",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
                        { 
                            "targets": [0, 2,-1 ], //last column
                            "orderable": false, //set not orderable
                        },
                      ],
          "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
                  "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
           buttons: [/*{extend:'pdfHtml5',orientation: 'landscape',pageSize: 'A4',title: 'Application Tracker',
                              customize: function (doc) 
                              {
                                  doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                              }
                      },
                      'csvHtml5',
                      'excelHtml5',
                      'print',
                      'copyHtml5',
                      'colvis'*/]
    }); 

     $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');
});
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
function tracked_candidates(job_id,job_status)
{
  var track_url=base_url+'employer/tracked_job_candidates/'+job_id+'/'+job_status;
    //Ajax Load data from ajax
    $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var candidate_track_list_list_html='-No data Found-';
            if(data.status)
            {
                var job=data.job_detail;
                var slno=1;
                candidate_track_list_list_html = '<div class="row">';
                candidate_track_list_list_html += '<div class="col-sm-4 col-md-4">';
                candidate_track_list_list_html += 'Employer Name: ';
                candidate_track_list_list_html += '</div>';
                candidate_track_list_list_html += '<div class="col-sm-8 col-md-8">';
                candidate_track_list_list_html += job.job_desc;
                candidate_track_list_list_html += '</div>';
                candidate_track_list_list_html += '<div class="col-sm-4 col-md-4">';
                candidate_track_list_list_html += 'Created On: ';
                candidate_track_list_list_html += '</div>';
                candidate_track_list_list_html += '<div class="col-sm-8 col-md-8">';
                candidate_track_list_list_html += job.created_on;
                candidate_track_list_list_html += '</div>';
                candidate_track_list_list_html += '<div class="col-sm-4 col-md-4">';
                candidate_track_list_list_html += 'Job Status: ';
                candidate_track_list_list_html += '</div>';
                candidate_track_list_list_html += '<div class="col-sm-8 col-md-8">';
                candidate_track_list_list_html += job.job_status;
                candidate_track_list_list_html += '</div>';
                candidate_track_list_list_html += '</div></div>';
                candidate_track_list_list_html += '<div class="row">';
                candidate_track_list_list_html += '<div class="col-sm-12 col-md-12">';
                candidate_track_list_list_html += '<table class="table">';
                candidate_track_list_list_html += '<tr><th>Sl No</th><th>Candidate Name</th><th>Gender</th><th>Mobile</th></tr>';
                
                $.each(data.candidate_detail,function(a,b)
                {
                  candidate_track_list_list_html += '<tr><td>'+slno+'</td><td>'+b.candidate_name+'</td><td>'+b.gender_code+'</td><td>'+b.mobile+'</td></tr>';
                  slno++;
                });
                
                candidate_track_list_list_html += '</table>';
                candidate_track_list_list_html += '</div></div>';
            }
            $('.job_status').html(candidate_track_list_list_html);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_tracker').modal('show'); // show bootstrap modal when complete loaded
}
</script>
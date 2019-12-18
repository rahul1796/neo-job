<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  All Employers list
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
<div class="inner">
<h4>Employers List </h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("pramaan/dashboard","Dashboard");?></li>
    <li class="active"> Employers List </li>
  </ul>
</small> 
<hr/>

        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>ID</th>
                <th>Employer Name</th>
                <th>Total Jobs</th>
                <th>Applied</th>
                <th>Screened</th>
                <th>Scheduled</th>
                <th>Shortlisted</th>
                <th>Selected</th>
                <th>Offered</th>
                <th>Joined</th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
</div>

<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_tracker" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Candidate Details</h3>
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

<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_tracker_jobs" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Jobs Details</h3>
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
                      "processing":     "Processing.."},

       // "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"pramaan/pramaan_employers_list/"+"<?php echo $user_id;?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
                        { 
                            "targets": [ 4,5, -1 ], //last column
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
                      'csvHtml5',
                      'excelHtml5',
                      'print',
                      'copyHtml5',
                      'colvis'*/]
    });
    
/*buttons: [
            {
                extend: 'excelHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: 'Data export'
            },
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                title: 'Data export'
            }
        ]*/

    $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');
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


function tracked_candidates(employer_id,job_status)
{
  var track_url=base_url+'partner/tracked_candidates_employerjob/'+employer_id+'/'+job_status;
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
                var employer=data.employer_detail;
                var slno=1;
                candidate_track_list_list_html = '<div>';
                candidate_track_list_list_html += 'Employer Name: <b>';
                candidate_track_list_list_html += employer.employer_name;
                candidate_track_list_list_html += '</b></div>';
                candidate_track_list_list_html += '<div class="row">';
                candidate_track_list_list_html += '<div class="col-sm-12 col-md-12">';
                candidate_track_list_list_html += '<table class="table">';
                candidate_track_list_list_html += '<tr><th>Sl No</th><th>Candidate Name</th><th>Mobile</th><th>Job Description</th><th>Qualification pack</th></tr>';
                
                $.each(data.candidate_detail,function(a,b)
                {
                  candidate_track_list_list_html += '<tr><td>'+slno+'</td><td>'+b.candidate_name+'</td><td>'+b.mobile+'</td><td>'+b.job_desc+'</td><td>'+b.qualification_pack_id+'</td></tr>';
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

function tracked_jobs(employer_id)
{
  var track_url=base_url+'partner/jobs_by_employer/'+employer_id;
    //Ajax Load data from ajax
    $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var job_track_list_list_html='-No data Found-';
            if(data.status)
            {
                var employer=data.employer_detail;
                var slno=1;
                job_track_list_list_html = '<div>';
                job_track_list_list_html += 'Employer Name: <b>';
                job_track_list_list_html += employer.employer_name;
                job_track_list_list_html += '</b></div>';
                job_track_list_list_html += '<div class="row">';
                job_track_list_list_html += '<div class="col-sm-12 col-md-12">';
                job_track_list_list_html += '<table class="table">';
                job_track_list_list_html += '<tr><th>Sl No</th><th>Job Description</th><th>Qualification pack</th><th>created on</th></tr>';
                
                $.each(data.job_detail,function(a,b)
                {
                  job_track_list_list_html += '<tr><td>'+slno+'</td><td>'+b.job_desc+'</td><td>'+b.qualification_pack_id+'</td><td>'+b.created_on+'</td></tr>';
                  slno++;
                });
                
                job_track_list_list_html += '</table>';
                job_track_list_list_html += '</div></div>';
            }
            $('.job_status').html(job_track_list_list_html);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_tracker_jobs').modal('show'); // show bootstrap modal when complete loaded
}
</script>


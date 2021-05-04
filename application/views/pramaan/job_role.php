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
<?php
$options_sectors=array(''=>'-Select Sector-');
$options_orgTypes=array(''=>'-Select Org type-');
?>
<div class="inner">
<h4>Employers</h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("pramaan/dashboard","Dashboard");?></li>
    <li class="active">Employers </li>
  </ul>
</small>
<hr/>
  <div class="row" style="margin-bottom: 5px;">
    <div class="col-sm-3 col-md-3">

    </div>
    <div class="col-sm-9 col-md-9" style="text-align: right;">
  
     <!--  <button class="btn btn-primary btn-sm btn-outline" onclick="print_ap_tracker()"><i class="glyphicon glyphicon-print"></i> Print</button>
      <button class="btn btn-primary btn-sm btn-outline" onclick="copy_center()"><i class="glyphicon  glyphicon-copy"></i> Copy</button>
      <button class="btn btn-success btn-sm btn-outline" onclick="download_pdf()"><i class="glyphicon glyphicon-download"></i> PDF</button>
      <button class="btn btn-success btn-sm btn-outline" onclick="download_excel()"><i class="glyphicon glyphicon-download"></i> Excel</button>
      <button class="btn btn-success btn-sm btn-outline" onclick="download_csv()"><i class="glyphicon glyphicon-download"></i> CSV</button>
      <button class="btn btn-warning btn-sm btn-outline" onclick="add_columns()"><i class="glyphicon glyphicon-plus"></i>Column</button>  -->
    </div>
  </div>
   <table id="table" class="table table-striped table-responsive" cellspacing="0" style="width:100%">
    <thead>
        <tr>
            <th>SlNo.</th>
            <th style="width:15%!important" nowrap>Qualification Name </th>
            <th style="width:15%!important" nowrap>Sector </th>
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
</div><!-- //page inner -->

<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_tracker" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Candidate Status</h3>
            </div>
            <div class="modal-body candidate_job_status">
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
        "language": {"loadingRecords": "Loading...",
                      "processing":     "Processing.."},
       // "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"pramaan/employers_list",
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
          buttons: [/*'pdfHtml5','csvHtml5','excelHtml5','print','copyHtml5','colvis'*/]
    }); 

     $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');
});
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
function tracked_jobs(candidate_id,job_status)
{
  var track_url=base_url+'partner/tracked_candidate_jobs/'+candidate_id+'/'+job_status;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var candidate_track_list_html='No Data Found-';
            if(data.status)
            {
                var candidate=data.candidate_detail;
                var slno=1;
                candidate_track_list_html = '<div class="row">';
                candidate_track_list_html += '<div class="col-sm-4 col-md-4">';
                candidate_track_list_html += 'Candidate Name: ';
                candidate_track_list_html += '</div>';
                candidate_track_list_html += '<div class="col-sm-8 col-md-8">';
                candidate_track_list_html += candidate.name;
                candidate_track_list_html += '</div>';
                candidate_track_list_html += '<div class="col-sm-4 col-md-4">';
                candidate_track_list_html += 'Candidate Phone: ';
                candidate_track_list_html += '</div>';
                candidate_track_list_html += '<div class="col-sm-8 col-md-8">';
                candidate_track_list_html += candidate.mobile;
                candidate_track_list_html += '</div>';
                candidate_track_list_html += '<div class="col-sm-4 col-md-4">';
                candidate_track_list_html += 'Candidate Name: ';
                candidate_track_list_html += '</div>';
                candidate_track_list_html += '<div class="col-sm-8 col-md-8">';
                candidate_track_list_html += candidate.candidate_status;
                candidate_track_list_html += '</div>';
                candidate_track_list_html += '</div></div>';
                candidate_track_list_html += '<div class="row">';
                candidate_track_list_html += '<div class="col-sm-12 col-md-12">';
                candidate_track_list_html += '<table class="table">';
                candidate_track_list_html += '<tr><th>Sl No</th><th>Job Description</th><th>Employer</th></tr>';
                
                $.each(data.job_detail,function(a,b)
                {
                  candidate_track_list_html += '<tr><td>'+slno+'</td><td>'+b.job_desc+'</td><td>'+b.employer_name+'</td></tr>';
                  slno++;
                });
                
                candidate_track_list_html += '</table>';
                candidate_track_list_html += '</div></div>';
            }
            $('.candidate_job_status').html(candidate_track_list_html);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_tracker').modal('show'); // show bootstrap modal when complete loaded
}
</script>

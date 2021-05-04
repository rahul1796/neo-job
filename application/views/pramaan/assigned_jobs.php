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
.table td, .table th {
    padding: 0.75rem 0.75rem;
}
</style>
<div class="content-body" style="overflow-x: hidden !important;">

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Assigned Job List
                </li>
            </ol>
        </div>
    </div>
    <!-- <div id="divDistrictCoordinator" class="modal fade bs-example-modal-lg" role="dialog">
         <div class="modal-dialog modal-lg" role="document" style="width:75%;" >
             <div class="modal-content">
                 <div class="modal-header" style="border-bottom:hidden;">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                     <h3 class="modal-title">State Managers List</h3>
                 </div>
                 <div class="modal-body">
                     <table id="state_managers_id" class="table table-striped" cellspacing="0" style="width:100%; !important;">
                         <thead>
                         <tr>
                             <th>SNo.</th>
                             <th>Name</th>
                             <th>Email</th>
                             <th>Phone</th>
                             <th>Districts</th>
                         </tr>
                         </thead>
                         <tbody id="tblDistrictCoordinatorBody">
                         </tbody>
                     </table>
                 </div>
             </div>
         </div>
     </div>-->
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Assigned Jobs</h4>
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

                            <table id="table_id" class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>Sl No</th>
                                    <th>Employer name</th>
                                    <th>Job Role(Qp)</th>
                                    <th>Job Desc</th>
                                    <th>Sector</th>
                                    <th>Vertical</th>
                                    <th>Location</th>
                                    <th>Posted on</th>
                                    <th>No of openings</th>
                                    <th>Joined</th>
                                    <th>Job Status</th>
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
<div class="modal fade" id="modal_form_job_tracker" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Job Details</h3>
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
<script>
    $(document).ready(function () {
        $('.dropdown-toggle').dropdown();
    });
</script>
<script type="text/javascript">
    var save_method; //for save method string
    var table;

    $(document).ready(function() {

        //datatables
        table = $('#table_id').DataTable({

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
                "url": base_url+"pramaan/assigned_job_list/",
                "type": "POST"
            },

            //Set column definition initialisation properties.
            "columnDefs": [
                {
                    "targets": [ 0,4,5,7,8,9,10, -1 ], //last column
                    "orderable": false, //set not orderable
                },
            ],
            "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
            "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
            /*buttons: ['pdfHtml5','csvHtml5','excelHtml5','print','copyHtml5','colvis']  for simple*/
            buttons: [{extend:'pdfHtml5',orientation: 'landscape',pageSize: 'A4',title: 'Employer List',
                customize: function (doc)
                {
                    doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                }
            },

                'excelHtml5',
                'copyHtml5',
                'print']
        });

        <?php

        echo 'table.columns([4,5,11]).visible(false);';

        ?>

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

       /* $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
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


    function scheduled_candidates(job_id,employer_id)
    {
        if(job_id)
        {
            var url=(base_url+'employer/scheduled_candidates/'+job_id+'/'+employer_id);
            document.location.href=url;
        }

    }
    function search_candidates(job_id, job_location_id)
    {
        if(job_id)
        {
            var url=base_url+'partner/matching_candidates/'+job_id+'/'+job_location_id;
            document.location.href=url;
        }
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
        $('#modal_form_job_tracker_jobs').modal('show'); // show bootstrap modal when complete loaded
    }
</script>


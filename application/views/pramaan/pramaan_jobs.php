<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/responsive.dataTables.min.css'?>">
<script>
  var statusOptions = JSON.parse('<?= json_encode($job_code_option); ?>');
  console.log(statusOptions);
</script>
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
.label {
    color: white !important;
    padding: 2px 5px 4px 5px;
    width:100% !important;
    border-radius: .21rem;
}
.success {background-color: #4CAF50;} /* Green */
.info {background-color: #2196F3;} /* Blue */
.warning {background-color: #ff9800;} /* Orange */
.danger {background-color: #f44336;} /* Red */
.default {background-color: #e7e7e7;} /* Gray */
</style>
    <script>
        var varJobStatusData = JSON.parse('<?php echo json_encode($job_statuses); ?>');
    </script>

<div class="content-body" style="overflow-x: hidden !important;">
    <div class="row">
    <div class="col-md-12">
      <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-primary" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5><?php echo $_SESSION['status']; ?></h5>
      </div>
      <br><br>
      <?php endif; ?>
    </div>

  </div>

  <?php if (isset($user_group_id)&& in_array($user_group_id, job_add_roles())): ?>
    <a href="<?php echo base_url('jobscontroller/create/')?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1" style="margin-left:10% ; "><i class="icon-android-add"></i> Post Job</button></a>
  <?php endif; ?>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Jobs
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Jobs</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form id="search" style="padding: 15px;">
                            <div class="row">
                              <div class="col-md-2 pl-1">
                                    <div class="form-group">
                                        <label for="search_by">Search By:</label>
                                        <select class="form-control" id="search_by" name="search_by" style="width: 250px;" onchange="searchby_onchange(this.value)">
                                        <option value="0"> -Select-</option>
                                        <option value="1">Job Title</option>
                                        <option value="2">Customer Name</option>
                                        <option value="3">Placement Officer</option>
                                        <option value="4">Recruiter</option>
                                        <option value="5">Status</option>
                                        <option value="6">Job Code</option>
                                    </select>
                                        <input type="text" class="form-control hidden" id="searchbox" name="searchbox" value="" placeholder="Search here" style="width: 380px; margin-top: -33px; margin-left: 270px;">
                                        <select class="form-control hidden" name="status_id" id="status_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                            <option value="0">Select Status</option>
                                            <?php foreach($job_statuses as $option): ?>
                                              <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                            <?php endforeach; ?>
                                          </select>
                                         <div class="hidden" id="job_title_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="job_title_list" name="job_title_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Job Title</option>
                                                <?php foreach($job_title_option as $option): ?>
                                                  <option value="<?php echo $option->job_title; ?>"><?php echo $option->job_title; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                        </div>
                                         <div class="hidden" id="customer_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="customer_list" name="customer_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Customer Name</option>
                                                <?php foreach($customers_option as $option): ?>
                                                  <option value="<?php echo $option->customer_name; ?>"><?php echo $option->customer_name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                        </div>

                                        <div class="hidden" id="placement_officer_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="placement_officer_list" name="placement_officer_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Placement Officer</option>
                                                <?php foreach($placement_officers_option as $option): ?>
                                                  <option value="<?php echo $option->name; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                        </div>
                                        <div class="hidden" id="recruiter_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="recruiter_list" name="recruiter_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Recruiter</option>
                                                <?php foreach($recruiter_option as $option): ?>
                                                  <option value="<?php echo $option->name; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                        </div>
                                        <div class="hidden" id="job_code_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="job_code_list" name="job_code_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Job Code</option>
                                                <?php foreach($job_code_option as $option): ?>
                                                  <option value="<?php echo $option->neo_job_code; ?>"><?php echo $option->neo_job_code; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                        </div>
                                        
                                        <label id="lblsearchbox" style="color:red; display: none;margin-left: 12%;">* Please Enter Search Value</label>

                                       </div>
                                  </div>
                              <label id="lblSearchError" style="color:red;display:block ;margin-left: 120px;  float: left; margin-top: 63px;"></label>
                            </div>
                            <div class="text-center hidden" style="margin-bottom: 5px;  margin-left: 670px;  margin-top: -55px;" name="search_btn" id="search_btn">
                            <a class="btn btn-primary btn-md " onclick="btnSearch_OnClick(event)" style="color: white; cursor: pointer;"><i class="fa fa-search "></i> Search</a>
                                <Button type="button" onclick="window.location.reload();" class="btn btn-secondary btn-md "> Clear Search</Button>
                            </div>
                                </form>
                        </div>
                    </div>
                    <div class="card-body collapse in" style="font-size:0.90rem;">
                        <div class="card-block card-dashboard">

                            <table id="table" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Actions</th>
                                    <th>Job Title</th>
                                    <th>Job Code</th>
                                    <th>Customer</th>
                                    <th>Status</th>
                                    <th>Placement Officers</th>
                                    <th>Recruiter</th>
                                    <th>Posted On</th>
                                    <th>No Of Positions</th>
                                    <th>QP</th>
                                    <th>Business Vertical</th>
                                    <th>Functional Area</th>
                                    <th>Industry</th>
                                    <th>Location</th>
                                    <th>Education</th>
                                    <th>Open Type</th>
                                    <th>Priority</th>
                                    <th>Expires On</th>
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
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<!--<link rel="stylesheet" type="text/css" href="<?php /*echo base_url().'adm-assets/vendors/css/extensions/sweetalert.css'*/?>">
<script src="<?php /*echo base_url().'adm-assets/vendors/js/extensions/sweetalert.min.js'*/?>" type="text/javascript"></script>
<script src="<?php /*echo base_url().'adm-assets/js/scripts/extensions/sweet-alerts.min.js'*/?>" type="text/javascript"></script>-->
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script> $(document).ready(function () { $('.dropdown-toggle').dropdown(); }); </script>
<script type="text/javascript">
      $(document).ready(function(){
        $('#search_by').on('change', function(){
            $('#lblSearchError').html('');
        });
    });
  var varTable;
    function searchby_onchange(varSearchByValue)
    {
        if (varSearchByValue=='0')
        {
            LoadTableData();
            return;
        }
        else if (varSearchByValue=='5')
        {
            LoadJobStatusList();
        }
        //lert(varSearchByValue);
        $("#job_title_list").selectedIndex = "0";
        $("#customer_list").selectedIndex = "0";
        $("#placement_officer_list").selectedIndex = "0";
        $("#recruiter_list").selectedIndex = "0";
        $("#job_code_list").selectedIndex = "0";
        $("#status_id").val('0');
        $("#searchbox").val('');
    }

    function LoadTableData()
    {
        var varSearchTypeId = $("#search_by").val(),
            varSearchValue = $("#searchbox").val();

        switch(varSearchTypeId)
        {
            case "1":
                varSearchValue = $("#job_title_list option:selected").text();
                break;

            case "2":
                varSearchValue = $("#customer_list option:selected").text();
                break;

            case "3":
                varSearchValue = $("#placement_officer_list option:selected").text();
                break;

             case "4":
                varSearchValue = $("#recruiter_list option:selected").text();
                break;

            case "5":
                varSearchValue = $("#status_id").val();
                break;
                
            case "6":
                varSearchValue = $("#job_code_list option:selected").text();
                break;    
        }

        if (varTable != undefined && varTable != null)
        {
            varTable.clear().destroy();
        }

        varTable = $("#table").DataTable({
            "serverSide": true,
            "paging": true,
            "scrollX": true,
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "pageLength": 10,
            "searching": false,
            "language": { "loadingRecords": "Loading..." },
            "ajax": {
                "url": base_url+"pramaan/pramaan_job_list/<?= $this->session->userdata('usr_authdet')['id'];?>",
                "type": "POST",
                "data": function (d) {
                    d.search_type_id = varSearchTypeId;
                    d.search_value = varSearchValue;
                },
                "error": function() {
                    $("#tblList tbody").empty().append('<tr><td style="text-align: center;" colspan="14">No data found</td></tr>');
                }
            },
            "columnDefs":
                [
                    {
                        "targets": [0, 1 ],
                        "orderable": false
                    }
                ],
            "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
            buttons: []
        });
    }

    function btnSearch_OnClick(event)
    {
        event.preventDefault();
        $("#lblSearchError").hide();
        var varSearchTypeId = $("#search_by").val(),
            varSearchValue = $("#searchbox").val();

        switch(varSearchTypeId)
        {
            case "1":
                if ($("#job_title_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select Job Title!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                case "2":
                if ($("#customer_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select Customer!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                case "3":
                if ($("#placement_officer_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select Placement Officer!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "4":
                if ($("#recruiter_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select Recruiter!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            case "5":
                if ($("#status_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select job status!');
                    $("#lblSearchError").show();
                    return;
                }
                break;
             
             case "6":
                if ($("#job_code_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select job code!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            default:
                if (varSearchValue.trim() == '')
                {
                    switch(varSearchTypeId)
                    {
//                        case "3":
//                        $("#lblSearchError").text('* Please input placement officer!');
//                        break;
                    }

                    $("#lblSearchError").show();
                    return;
                }
                break;
        }

        LoadTableData();
    }



    $(document).ready(function() {
        //alert(JSON.stringify(varJobStatusData));
        //$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="icon-refresh"></i> Reload</button>').appendTo('div#table_length');
        LoadTableData();
    });

    function LoadJobStatusList()
    {
        $('#status_id').empty();
        $('#status_id').append(new Option('Select Job Status', '0'));
        for(var i=0; i<varJobStatusData.length;i++)
        {
            $('#status_id').append(new Option(varJobStatusData[i]['name'], varJobStatusData[i]['id']));
        }
    }

window.setTimeout(function() {
      $(".alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove();
      });
  }, 120000);

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
                job_track_list_list_html += '<table class="table  table-striped table-bordered">';
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



function changeJobStatus(job_id,job_status,job_location_id) {

    var strStatus = (job_status == 1) ? "Close" : "Re-Open";
    var strCompletedStatus = (job_status == 1) ? "Closed" : "Re-Opened";
    swal(
        {
            title: "",
            text: "Are you sure, you want to " + strStatus + " the job ?",
            showCancelButton: true,
            confirmButtonColor: ((job_status == 1) ? "#d9534f" : "#5cb85c"),
            confirmButtonText: "Yes, " + strStatus + "!",
            cancelButtonText: "No, Cancel!",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm)
        {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "pramaan/change_job_status",
                    data: {
                        'id': job_id,
                        'job_status': job_status,
                        'location_id':job_location_id
                    },
                    dataType: 'json',
                    success: function (data) {
                        swal({
                                title: "",
                                text: "Job successfully " + strCompletedStatus + "!",
                                confirmButtonColor: ((job_status == 1) ? "#d9534f" : "#5cb85c"),
                                confirmButtonText: 'OK',
                                closeOnConfirm: false,
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

function job_detail(job_id,location_count)
{
    //Ajax Load data from ajax
    if(parseInt(location_count))
    {
        $.ajax({
            url : base_url+"employer/get_job_details/" + job_id,
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {

               if(data.status) //if success close modal and reload ajax table
                {
                     var job_detail_html = '<tr><th>Sl No</th><th>Qualification Pack</th><th>Location</th><th>No of openings</th><th>Salary</th></tr>';
                     var slno=1;
                     $.each(data.job_details,function(a,b)
                      {
                            job_detail_html += '<tr><td>'+slno+'</td><td>'+b.qualification_pack_name+'</td><td>'+b.location_name+'</td><td>'+b.no_of_openings+'</td><td nowrap>'+b.salary+'</td></tr>';
                            slno++;
                      });
                }
                $('#modal_job_detail tbody').html(job_detail_html);
                $('#modal_job_detail').modal('show');
                $('.modal-title').text('Job Detail');
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error get data from ajax');
            }
        });
    }
}
function EditJobDetails(job_id)
{
    var user_id="<?php echo $user_id;?>";
    if(job_id)
    //document.location.href = base_url+'employer/edit_job/'+user_id+'/'+job_id;
    document.location.href = base_url+'jobscontroller/edit/'+job_id;
}


function open_job_status_popup(job_id,job_status_id) {
    $('#job_id_input').val(job_id);
    $("#job_status_id").val(job_status_id).change();
    $('#save_status_btn').prop('disabled', true);
    $('#modal_job_detail').modal('show');
    // $('#lead_feedback_input, .lead_schedule_input_container, .lead_feedback_input_container, #alert-box').addClass('hidden');
    // $('#lead_feedback_input, #name_input, #phone_input, #city_input, #address_input, #lead_schedule_input').val('');
    // $('#lead_status_selector').val(''+job_id);
    //$('#lead_status_selector').val($('#lead_status_cell_'+lead_id).attr('data-value')).change();
}

function assignjob(job_id) {
    $('#job_id_input').val(job_id);
    $('#save_status_btn').prop('disabled', true);
    $('#assign_job_detail').modal('show');
    // $('#lead_feedback_input, .lead_schedule_input_container, .lead_feedback_input_container, #alert-box').addClass('hidden');
    // $('#lead_feedback_input, #name_input, #phone_input, #city_input, #address_input, #lead_schedule_input').val('');
    // $('#lead_status_selector').val(''+job_id);
    //$('#lead_status_selector').val($('#lead_status_cell_'+lead_id).attr('data-value')).change();
}

// $('.datepicker').datepicker({
//        autoclose: true,
//        format: "yyyy-mm-dd",
//        todayHighlight: true,
//        orientation: "top auto",
//        todayBtn: true,
//        todayHighlight: true,
//    });

    //set input/textarea/select event when change value, remove class error and remove text help block
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
//    $("select").change(function(){
//       $(this).parent().parent().removeClass('has-error');
//        $(this).next().empty();
//    });
</script>
<style>
    .search_categories{
        font-size: 13px;
        padding: 10px 8px 10px 14px;
        background: #fff;
        border: 1px solid #ccc;
        border-radius: 6px;
        overflow: hidden;
        position: relative;
    }

    .search_categories .select{
        width: 120%;
        background:url('arrow.png') no-repeat;
        background-position:80% center;
    }

    .search_categories .select select{
        background: transparent;
        line-height: 1;
        border: 0;
        padding: 0;
        border-radius: 0;
        width: 120%;
        position: relative;
        z-index: 10;
        font-size: 1em;
    }
</style>
<!--  job detail -->

<div class="modal fade text-xs-left" id="modal_job_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="myModalLabel34">Update Job Status</h3>
            </div>
                <div class="modal-body" style="margin-bottom: 5%">
                      <div class="form-group">
                          <input type="hidden" name="job_id_input" id="job_id_input" value="">
                          <label for="job_status_id">Select Status </label>
                          <select class="from_control search_categories" name="job_status_id" id="job_status_id" style="width: 50%">
                            <?php foreach ($job_statuses as $option): ?>
                               <option value="<?= $option->id; ?>"><?= $option->name; ?></option>
                            <?php endforeach; ?>
                          </select>
                      </div>
                    <hr>
                      <div class="form-group" style="float: right">
                        <button type="button" class="btn btn-primary" name="button" disabled onclick="updateJobStatus()" id="save_status_btn">Update Status</button>
                      </div>
                </div>

                <script type="text/javascript">
                  $(document).ready(function(){

                    $('#job_status_id').on('change', function() {
                      //$('#save_status_btn').prop('disabled', true);
                      $('#save_status_btn').removeAttr('disabled');
                    });

                  });
                  function updateJobStatus() {
                    let job_status = $('#job_status_id').find(':selected').val();
                    let job_id = $('#job_id_input').val();
                    let job_object = {'job_status_id' : job_status, 'job_id': job_id};

                    $.ajax({
                      url: '<?= base_url('jobscontroller/updateJobStatus'); ?>',
                      type: 'POST',
                      data: job_object
                    }).done(function (response) {
                      let res = JSON.parse(response);
                      if(res.status) {
                          $('#span_'+job_id).html($('#job_status_id').find(':selected').text()).removeClass().addClass('label '+res.color);
                          console.log(res.message);
                          window.location.reload(true);
                      }
                    }).fail(function (jqXHR, textStatus) {
                      console.log(jqXHR);
                    }).always(function(jqXHR, textStatus) {
                      $('#modal_job_detail').modal('hide');
                    }) ;
                  }
                </script>
        </div>
    </div>
</div>


<!--<div class="modal fade text-xs-left" id="assign_job_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
    <div class="modal-dialog modal-lg " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="myModalLabel34">Assign Job</h3>
            </div>
                <div class="modal-body" style="margin-bottom: 5%">
                    <input type="hidden" name="job_id_input" id="job_id_input" value="">
                    <div class="form-group row">
                            <div class="col-md-6">
                             <label for="placement_officer_id">Select Placement Officers: </label>
                             <select class="from_control search_categories" name="assigned_user_id" id="placement_officer_id" multiple="">
                                  <option value="1">Placement officer 1</option>
                                  <option value="2">Placement officer 2</option>
                                  <option value="3">Placement officer 3</option>
                                  <option value="4">Placement officer 4</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                             <label for="recruiter_id">Select Recruiters: </label>
                             <select class="from_control search_categories" name="assigned_user_id" id="recruiter_id" multiple="">
                                  <option value="1">Recruiters 1</option>
                                  <option value="2">Recruiters 2</option>
                                  <option value="3">Recruiters 3</option>
                                  <option value="4">Recruiters 4</option>
                                </select>
                            </div>
                    </div>

                    <hr>
                      <div class="form-group" style="float: right">
                        <button type="button" class="btn btn-primary" name="button" onclick="AssignedUser()" id="save_assignee_btn">Assign</button>
                      </div>
                </div>

                <script type="text/javascript">
                  $(document).ready(function(){

                    $('#assigned_user_id').on('change', function() {
                      //$('#save_status_btn').prop('disabled', true);
                      $('#save_assignee_btn').removeAttr('disabled');
                    });

                  });
                  function AssignedUser() {
                    let assigned_user_id = $('#assigned_user_id').find(':selected').val();
                    let job_id = $('#job_id_input').val();
                    let job_object = {'assigned_user_id' : assigned_user_id, 'job_id': job_id};

                    $.ajax({
                      url: '<?//= base_url('jobscontroller/AssignedUser'); ?>',
                      type: 'POST',
                      data: job_object
                    }).done(function (response) {
                      let res = JSON.parse(response);
                      if(res.status) {
                          $('#span_'+job_id).html($('#assigned_user_id').find(':selected').text()).removeClass().addClass('label '+res.color);
                          console.log(res.message);
                          window.location.reload(true);
                      }
                    }).fail(function (jqXHR, textStatus) {
                      console.log(jqXHR);
                    }).always(function(jqXHR, textStatus) {
                      $('#assign_job_detail').modal('hide');
                    }) ;
                  }
                </script>
        </div>
    </div>
</div>-->
<script>
$(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5'|| $('#search_by').val() == '6') {
                $('#searchbox').addClass('hidden');
            }
            else {
                $('#searchbox').removeClass('hidden');
                $('#searchbox').focus();
            }
        });
    });
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' ||   $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5'|| $('#search_by').val() == '6') {
                $('#job_title_list_container').addClass('hidden');
            }
            else {
                $('#job_title_list_container').removeClass('hidden');
                $('#job_title_list_container').focus();
            }
        });
    });
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' ||   $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '6') {
                $('#status_id').addClass('hidden');
            }
            else {
                $('#status_id').removeClass('hidden');
                $('#status_id').focus();
            }
        });
    });

     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' ||   $('#search_by').val() == '1' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5'|| $('#search_by').val() == '6') {
                $('#customer_list_container').addClass('hidden');
            }
            else {
                $('#customer_list_container').removeClass('hidden');
                $('#customer_list_container').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' ||   $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '4' || $('#search_by').val() == '5'|| $('#search_by').val() == '6') {
                $('#placement_officer_list_container').addClass('hidden');
            }
            else {
                $('#placement_officer_list_container').removeClass('hidden');
                $('#placement_officer_list_container').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' ||   $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '5'|| $('#search_by').val() == '6') {
                $('#recruiter_list_container').addClass('hidden');
            }
            else {
                $('#recruiter_list_container').removeClass('hidden');
                $('#recruiter_list_container').focus();
            }
        });
    });
    
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' ||   $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5') {
                $('#job_code_list_container').addClass('hidden');
            }
            else {
                $('#job_code_list_container').removeClass('hidden');
                $('#job_code_list_container').focus();
            }
        });
    });

   $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0') {
                $('#search_btn').addClass('hidden');
            }
            else {
                $('#search_btn').removeClass('hidden');
                $('#search_btn').focus();
            }
        });
    });
     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0') {
                $('#lblSearchError').addClass('hidden');
            }
            else {
                $('#lblSearchError').removeClass('hidden');
                $('#lblSearchError').focus();
            }
        });
    });
      $(document).ready(function() {
   $('.select2-neo').select2();
 });
</script>

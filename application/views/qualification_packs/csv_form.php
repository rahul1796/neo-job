<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
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

  <div class="row">
    <div class="col-md-12">
      <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-primary" id="server-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5><?php echo $_SESSION['status']; ?></h5>
      </div>
      <br><br>
      <?php endif; ?>
    </div>
  </div>
    <div class="col-md-12" style="margin-bottom: -30px;">
        <div class="col-md-12">
  <div style="float:right; z-index:1000 !important;">
      <?php if (in_array($user['user_group_id'], bulk_upload_clcs_roles())): ?>
           <a class="btn btn-primary btn-min-width mr-1 mb-1" onclick="bulk_candidate_upload()" style="color:white;"><i class="icon-android-upload"></i> Bulk Upload IGS Candidate</a>
        <?php endif; ?>
          <?php if (in_array($user['user_group_id'], bulk_upload_batch_roles())): ?>
           <a class="btn btn-success btn-min-width mr-1 mb-1" onclick="bulk_upload()"><i class="icon-android-upload"></i> Bulk Upload Batch</a>
        <?php endif; ?>      
  </div>
</div>
 
</div>
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px; width: 50%;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Batch
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Batch</h4>
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
                                    <th>Status</th>                                 
                                    <th>Batch Code</th>
                                    <th>Batch Type</th>
                                    <th style="text-align: center;">Batch Size</th>
                                    <th>Customer Name</th>
                                    <th>Center Name</th>
                                    <th>Course Name </th>
                                    <th>Course Code </th>
                                    <th>Buisness Unit</th>
                                    <th>Trainer Email </th>
                                    <th>QP </th>
                                    <th>Batch Start Date </th>
                                    <th>Batch End Date </th>
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
            "url": base_url + "pramaan/get_batches/",
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

$(document).ready(function() {
    $(".dropdown-toggle").dropdown();
});
function reload_table()
{
    table.ajax.reload(null, false);
}

function download_candidate_list()
{
  var url = base_url+"partner/download_igs_list_sample/";
  window.location.href = url;
}

function showBatchWiseCandidates(id)
{
  var track_url=base_url+'pramaan/candidates_by_batchwise/'+id;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
        var candidate_list_html = "<div  style='margin-bottom: 10px'>Batch Code: <span style='font-weight: bold;'>"+data.batch_code+"</span></div>";
        candidate_list_html += "<div  style='margin-bottom: 10px'>Batch Type: <span style='font-weight: bold;'>"+data.batch_type+"</span></div>";
        candidate_list_html += "<div  style='margin-bottom: 10px'>Customer Name: <span style='font-weight: bold;'>"+data.customer_name+"</span></div>";
//        candidate_list_html += "<div  style='margin-bottom: 10px'>Center Name: <span style='font-weight: bold;'>"+data.center_name+"</span></div>";
//        candidate_list_html += "<div  style='margin-bottom: 10px'>Course Name: <span style='font-weight: bold;'>"+data.course_name+"</span></div>";
//        candidate_list_html += "<div  style='margin-bottom: 10px'>Trainer Email: <span style='font-weight: bold;'>"+data.trainer_email+"</span></div>";
            if(data.status)
            {
                var slno=1;

                candidate_list_html += '<div class="row">';
                candidate_list_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; height: 200px;">';
                candidate_list_html += '<table class="table table-striped">';
                candidate_list_html += '<tr><th>SNo</th><th>Candidate Name</th><th>Mobile</th><th>Email</th><th>Status</th></tr>';

                $.each(data.candidate_detail,function(a,b)
                {
                    var varStatusColor = b.active_status.toUpperCase() == 'ACTIVE' ? 'green' : 'red';
                    candidate_list_html += '<tr><td>'+slno+'</td><td>'+b.candidate_name+'</td><td>'+b.mobile+'</td><td>'+ b.email + '</td><td><span style="background-color:'+ varStatusColor +';color:white;padding:5px;border-radius: 4px;">'+ b.active_status + '</span></td></tr>';
                    slno++;
                });

                candidate_list_html += '</table>';
                candidate_list_html += '</div></div>';
            }
            else
                candidate_list_html='<div>-No data Found-</div>';
            $('.candidate_details').html(candidate_list_html);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#batchwise_candidate').modal('show'); // show bootstrap modal when complete loaded
}

function download_batch_template()
{
  var url = base_url+"partner/download_batch_template/";
  window.location.href = url;
}

function bulk_upload()
{

    //$('#form_upload_batch')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    //$("#txtCandidateBulkUploadStatus").val('');
    $('#batchBulkUploadStatus').html('');
    $('#batch_error').html('');
    $('#modal_form_upload').modal(); // show bootstrap modal
    $('.modal-title').text('Batch List Upload'); // Set Title to Bootstrap modal title
}

function bulk_candidate_upload()
{

    //$('#form_upload_candidate')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    //$("#txtCandidateBulkUploadStatus").val('');
    $('#txtCandidateBulkUploadStatus').html('');
    $('#candidate_error').html('');
    $('#candidate_modal_form_upload').modal('show'); // show bootstrap modal
    $('.modal-title').text('Candidate List Upload'); // Set Title to Bootstrap modal title
}
function bulk_upload_save()
{
    $("#txtCandidateBulkUploadStatus").val('');

    if ($("#candidate_list").val().trim() == '')
    {
        if ($("#candidate_list").val().trim() == '')
        {
            $("#txtCandidateBulkUploadStatus").val('No file selected. \nPlease select a valid CSV file to upload.');
            return;
        }
    }

    if ($("#candidate_list").val().trim() != '')
    {
        var varExtensions = ["CSV"];
        var fileName = $("#candidate_list")[0].files[0].name;
        var fileExtension = fileName.split(/[. ]+/).pop();
        if (varExtensions.indexOf(fileExtension.toUpperCase()) < 0) {
            $("#txtCandidateBulkUploadStatus").val('Invalid file!\nPlease select a valid CSV file to upload.');
            return;
        }
    }

    $("#txtCandidateBulkUploadStatus").val('');
    var url= base_url+"partner/upload_igs_candidate_list/";
    var formData = new FormData($('#form_upload_candidate')[0]);
    // ajax adding data to database

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        success: function (result)
        {
            $("#txtCandidateBulkUploadStatus").val($("#txtCandidateBulkUploadStatus").val() + result);
            var textarea = document.getElementById('txtCandidateBulkUploadStatus');
            textarea.value += "\n";
            textarea.scrollTop = textarea.scrollHeight;
            reload_candidates();
        },
        error: function () {
            alert("Error Occurred!");
        }
    });

}


function batch_status(id, is_active) {
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
                    url: base_url + "pramaan/change_batch_active_status",
                    data: {
                        'id': id,
                        'is_active': is_active
                    },
                    dataType: 'json',
                    success: function (data) {
                        swal({
                                title: "",
                                text: "Batch successfully " + strCompletedStatus + "!",
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


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_upload" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Batch Upload</h3>
            </div>
            <div class="modal-body form">
                <div id="msgDisplay"></div>
                <form class="" id="batch_form" action="index.html" method="post">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="file_name" class="label">Upload CSV:</label>
                      <span class="text text-danger">CSV file only</span>
                      <input type="file" class="form-control" id="file_name"name="file_name" value="">
                      <span id="batch_error" class="text-danger"></span>
                    </div>
                  </div>
                </form>

                <div class="form-group row">
                     <label for="txtStatus" class="label-control col-md-3">Upload Status</label>
                     <div class="col-md-9" style="margin-bottom: 10px;">
                         <span id="batchBulkUploadStatus" class="text text-danger"></span>
                     </div>
                 </div>
                 <label style="color: red;">*Note Upload only 1000 Batch data.</label>

            </div>
            <div class="modal-footer">
                <button class='btn btn-warning' href='javascript:void(0);' onclick="download_batch_template();" style="float: left;" id="download_batch_template">Download Template</button>
                    <button class="btn btn-primary" id="batch_upload" onclick="upload_batch();" name=""> Upload</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <script type="text/javascript">
      function upload_batch() {
        let fd = new FormData(document.getElementById("batch_form"));
        document.getElementById("batch_form").reset();
        $.ajax({
          url : '<?= base_url('qualificationpackscontroller/uploadCSV/')?>',
          method: 'POST',
          data: fd,
          processData: false,  // tell jQuery not to process the data
          contentType: false,
        }).done(function(response) {

          let data = JSON.parse(response);
          if(Object.keys(data.errors).length > 0) {
            $('#batch_error').html(data.errors.file_name);
          } else {
            $.each(data.data, function(index, value) {
              console.log(value);
              if(value.status==false) {
                $('#batchBulkUploadStatus').append('Error Uploading Row : '+value.row_number+' - Duplicate Entry<br>');
              }
            });
            $('#batch_error').removeClass('text-danger').addClass('text-success').html(data.message);
          }
        }).fail(function(response, text) {
          $('#batch_error').removeClass('text-danger').addClass('text-success').html("Not able to connect to server please Try again later");
        });
      }
    </script>
</div><!-- /.modal -->



<div class="modal fade" id="candidate_modal_form_upload" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">&times;</span></button>
                <h3 class="modal-title">Candidate List Upload</h3>
            </div>
            <div class="modal-body form">
                <div id="msgDisplay"></div>
                <form  id="form_upload_candidate" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-body">

                         <div class="form-group row">
                            <label class="label-control col-md-3" style="margin-top: 10px;">Select File<span class='validmark' style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="file" name="file_name" class="form-control" id="candidate_list" name="candidate_list" required>
                                <span id="candidate_error" class=" text-danger"></span>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                        <!--   <div id="loading" style="margin-left: 27%;">
                            <img src="<?php echo base_url('/assets/images/please_wait_uploading.gif');?>">
                        </div>-->

                       <div class="form-group row">
                            <label for="txtStatus" class="label-control col-md-3">Upload Status</label>
                            <div class="col-md-9" style="margin-bottom: 10px;">
                                <span id="txtCandidateBulkUploadStatus" class="text text-danger"></span>
                            </div>
                        </div>
                        <label style="color: red;">*Note Upload only 1000 Candidate data.</label>
                        <div style="clear: both;"></div>

                    </div>
                </form>

                <script type="text/javascript">
                  function upload_candidate() {
                    let fd = new FormData(document.getElementById("form_upload_candidate"));
                    document.getElementById("form_upload_candidate").reset();
                    $.ajax({
                      url : '<?= base_url('qualificationpackscontroller/uploadCandidate/')?>',
                      method: 'POST',
                      data: fd,
                      processData: false,  // tell jQuery not to process the data
                      contentType: false,
                    }).done(function(response) {

                      let data = JSON.parse(response);
                      if(Object.keys(data.errors).length > 0) {
                        $('#candidate_error').html(data.errors.file_name);
                      } else {
                        $.each(data.data, function(index, value) {
                          if(value.status==false) {
                            $('#txtCandidateBulkUploadStatus').append('Error Uploading Row : '+value.row_number+' - Duplicate Entry<br>');
                          }
                        });
                        $('#candidate_error').removeClass('text-danger').addClass('text-success').html(data.message);
                      }
                    }).fail(function(response, text) {
                      $('#candidate_error').removeClass('text-danger').addClass('text-success').html("Not able to connect to server please Try again later");
                    });
                  }
                </script>

            </div>
            <div class="modal-footer">
                <button class='btn btn-warning' href='javascript:void(0);' onclick="download_candidate_list();" style="float: left;" id="download_candidate_list">Download Template</button>
                <button type="button" id="btnSave" onclick="upload_candidate();" class="btn btn-primary">Upload Candidate list</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="javascript:window.location.reload();this.disabled = true;">Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->



<!-- Batch wise candidate model-->

<div id="batchwise_candidate" class="modal fade bs-example-modal-xl" role="dialog">
    <div class="modal-dialog modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:hidden;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Batch Candidate List</h3>
            </div>
            <div class="modal-body candidate_details">
                -No records found-
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
  $(document).ready(function(){
    window.setTimeout(function() {
        $("#server-alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 4000);
  });
</script>

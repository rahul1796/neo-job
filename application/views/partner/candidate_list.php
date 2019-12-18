<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
<style>
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  Show Aavailable Job List
 * @date  Nov_2016
*/
#loading { display: none; }
.checkbox {
    position: relative;
    top: -0.375rem;
    margin: 0 1rem 0 0;
    cursor: pointer;
}

.checkbox:before {
    -webkit-transition: all 0.3s ease-in-out;
    -moz-transition: all 0.3s ease-in-out;
    transition: all 0.3s ease-in-out;
    content: "";
    position: absolute;
    left: 0;
    z-index: 1;
    width: 1rem;
    height: 1rem;
    border: 2px solid #f2f2f2;
}

.checkbox:checked:before {
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transform: rotate(-45deg);
    height: .5rem;
    border-color: #009688;
    border-top-style: none;
    border-right-style: none;
}

.checkbox:after {
    content: "";
    position: absolute;
    top: -0.125rem;
    left: 0;
    width: 1.1rem;
    height: 1.1rem;
    background: #fff;
    cursor: pointer;
}
.pagination a{padding:5px 5px;background: #dfdfdf;color: #000;font-weight: bold;font-size: 13px;}
.pagination { float: right; }
table td{border:0px!important;}
table tr{margin-bottom: 5px;}

.list-group-item,.list-group-item-heading
{
    padding: 4px 15px;
}
#candidate_content_block div.row
{
  border-bottom: 1px solid #e1e1e1;
  margin-bottom:5px;
  padding: 5px;
}
.vcenter
{
  height: auto;
  position: relative;
  transform: translateY(40%);
}
.id_span
{
  display: inline-block;
  padding-left: 15%;
}
.rightAligned
{
  text-align: right;
}
.leave_space
{
  padding-left: 20px;
}
</style>

<?php
    $state_options=array('' => '-Select State-');
    foreach ($state_list as $row)
    {
        $state_options[$row['id']]=$row['name'];
    }
?>

<div class="content-body" style="padding: 10px;">
  <div class="col-md-12">
    <?php if(isset($_SESSION['status'])): ?>
    <div class="alert alert-primary" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h5><?php echo $_SESSION['status']; ?></h5>
    </div>
    <?php endif; ?>
  </div>

<div class="col-md-12">
  <div style="float:right; z-index:1000 !important;">
      <?php if (in_array($user['user_group_id'], candidate_add_roles())): ?>
          <a href="<?php echo base_url('candidate/create');?>" class="btn btn-primary btn-min-width mr-1 mb-1"><i class="icon-plus"></i> Add Candidate</a>
        <?php endif; ?>
          <?php if (in_array($user['user_group_id'], candidate_bulk_upload_roles())): ?>
          <a class="btn btn-success btn-min-width mr-1 mb-1" onclick="bulk_upload()"><i class="icon-android-upload"></i> Bulk Upload Candidate</a>
        <?php endif; ?>
  </div>
</div>


    <!--<button type="button" class="btn btn-success btn-min-width mr-1 mb-1"  style="margin-left: 80%;"></button>-->
   <!-- <a href="<?php /*echo base_url('partner/add_candidate/'.$associate_id)*/?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1" style="margin-left: 85%;margin-top: -70px;"><i class="icon-android-add"></i> Add Candidate</button></a>-->

    <div class=" breadcrumbs-top col-md-8 col-xs-12" style="margin-top: -45px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Candidates
                </li>
            </ol>
        </div>
    </div>
    <section id="description" class="card" style="border: hidden;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Candidate List</h4></label>
                    <div class="panel-body" style="float: right;">

                        <div class="col-sm-12" style="">

                            <a class="btn btn-info btn-min-width mr-1 mb-1" onclick="search_candidate()" style="color:white;"><i class="fa fa-search"></i> Search Candidate</a>
                            <a class="btn btn-default btn-min-width mr-1 mb-1" onclick="window.location.reload();" style="color:white;background-color: #a5a5a5;"><i class="fa fa-refresh"></i> Clear Search</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-sm-12">
                        <div class="page_display_log pull-left" style=" color: green;margin-top: 10px;"></div>
                        <!--<div class="pagination pull-right"></div>-->
                    </div>
                    <div class="card-block">
                        <output class="col-sm-12 col-md-12 col-lg-12">
                            <div id="candidate_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                <div id="candidate_content_block">
                                </div>
                                <div class="pagination" align="right"></div>
                            </div>
                        </output>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

<script>
$(document).ready(function()
{
  /*  $(".date_from").datepicker({'changeYear':true,'changeMonth':true});
      $(".date_to").datepicker({'changeYear':true,'changeMonth':true});
  */
  load_candidate_list_content('');
  //check box toggling
  $('.selectex input:checkbox').click(function()
  {
      $('.selectex input:checkbox').not(this).prop('checked', false);
  });

});

//================ ABORT ALL AJAX REQUEST ==========================
//$.xhrPool and $.ajaxSetup are the solution
$.xhrPool = [];
$.xhrPool.abortAll = function()
{
  $(this).each(function(idx, jqXHR)
  {
    jqXHR.abort();
  });
  $.xhrPool = [];
};

$.ajaxSetup({
  beforeSend: function(jqXHR)
  {
    $.xhrPool.push(jqXHR);
  },
  complete: function(jqXHR)
  {
      var index = $.xhrPool.indexOf(jqXHR);
      if (index > -1)
      {
          $.xhrPool.splice(index, 1);
      }
  }
});
//==========================================
/**
 * Onsubmit the filter
*/
window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);


 $(document).ready(function() {

   $('.select2-neo').select2();
 });

function filter_form_submit()
{
    var varReturnValue = true, varFocus = false;
    $("#lblsearchbox").hide();
    if (parseInt($("#search_by").val()) > 0)
    {
        if ($("#searchbox").val().trim() == '')
        {
            $("#lblsearchbox").show();
            if (!varFocus)
            {
                $("#search_by").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }
    }


  $('input[name="sel_page"]').val(0);
  load_candidate_list_content('');
  return false;
}
function reload_candidates()
{
  var pagi_url=''
  load_candidate_list_content(pagi_url);
  return false;
}

$('#candidate_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_candidate_list_content($(this).attr('href'));
});


/**
 * ======== Default load function ====================
 */
function load_candidate_list_content(pagi_url)
{

  var colcount=1;
  var url='';
  var sel_page = $('input[name="sel_page"]').val();
//  var associate_id= $('input[name="associate_id"]').val();
  var experience= $('select[name="experience"]').val();
  var qualification = $('select[name="qualification"]').val();
  var search_key = $('input[name="searchbox"]').val();
  var search_by = $('select[name="search_by"]').val();
    //alert(search_key);


  experience = isNaN(experience)?0:experience;
  qualification = isNaN(qualification)?0:qualification;

  if(search_key=='')
    search_key = 'EMPTY';

  search_key=encodeURIComponent(search_key);
  if(pagi_url == '')
  {
    url = site_url+'partner/candidate_list/'+experience+'/'+qualification+'/'+search_by+'/'+search_key+'/'+sel_page;
  }
  else
    url = pagi_url;

  $('#candidate_list_block #candidate_content_block').html('<table><tr><td colspan="'+colcount+'"><div align="center" style="margin:5px;padding:5px;margin-left: 350px;"><img src="'+base_url+'assets/images/loading_neo_candidates.gif'+'"></div></td></tr></table>');
  $('#candidate_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var candidate_list_html = '';
    var page_display_log='';
    if(resp==null)
    {
      candidate_list_html='<table><tr><td colspan="'+colcount+'" align="center"><div>Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_candidate_list_content(\'\');">Click here to Reload</a>.</div></td></tr></table>';
    }
    else if(resp.status == 'success')
    {
      candidate_list_html = candidate_list_inner_content(resp,colcount);
      $('.pagination').html(resp.pagination);

      $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");
       $('#modal_form_search').modal('hide');
      if(resp.pg_count_msg != undefined) {
        page_display_log=('<span>'+resp.pg_count_msg+'</span>');
      }

    }
    else
    {
      candidate_list_html += '<table><tr><td colspan="'+colcount+'" align="center">'+resp.message+'</td></tr></table>';
    }

    $('#candidate_list_block #candidate_content_block').html(candidate_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function candidate_list_inner_content(resp,colcount)
{
    var candidate_list_html='';

    $.each(resp.candidate_list,function(a,b)
    {

      $('input[name="sel_page"]').val(resp.pg);
      //var candidate_status_flags = ['Inactive','Active','Suspended'];
      var slno=(resp.pg*1+a*1+1);
     /* var kyc=(b.is_aadhar)?'<strong>Aadhar:</strong> '+b.aadhaar_num:'<strong>'+b.id_type+'</strong>: '+b.id_number;*/
      var is_aadhar_verified_flags = (b.is_aadhar_verified)?'<img src="'+base_url+'/assets/images/verified_image.jpeg" height="75" width="75">':'<a class="btn mr-1 mb-1 btn-warning" href="javascript:void(0)" onclick="aadhar_verify('+b.id+',1)" style="width: 184px;margin-top: -26px;"><i class="icon-user"></i> Verify Aadhaar</a>';
          candidate_list_html += '<div class="row">';
          candidate_list_html += '<div class="col-sm-2 col-md-2 vcenter">';
          candidate_list_html += '<img src='+base_url+'/adm-assets/images/portrait/small/blank_avatar.png '+' onerror=this.src='+"'"+base_url+'adm-assets/images/portrait/small/blank_avatar.png'+"'"+' class=img-thumbnail height=75 width=75>';
          candidate_list_html += '</div> ';
          candidate_list_html += '<div class="col-sm-7 col-md-7">';
          candidate_list_html += '<ul>'+
                                    '<li><b class="text-uppercase text-success">'+ b.full_name + '</b></li>'+
                                     '<li><strong>Candidate ID : </strong>'+ b.id + '</b></li>'+
                                      '<li><strong>Enrollment ID : </strong> '+(b.candidate_enrollment_id == null ? '-NA-' : b.candidate_enrollment_id)+'</li>'+
                                    '<li><strong>Educational Qualification : </strong> '+(b.education_name == null ? '-NA-' : b.education_name)+'</li>'+
                                    '<li><strong>QP : </strong> '+(b.qualification_pack_name == null ? '-NA-' : b.qualification_pack_name)+'</li>'+
                                    '<li><strong>Work Experience : </strong>'+(b.experience_in_years == null ? 0 : b.experience_in_years)+' Year(s)</li>'+
                                    '<li><strong>Dob : </strong>'+(b.date_of_birth == null ? '-NA-' : b.date_of_birth)+' <span class="leave_space"> <strong>Gender :</strong></span>' +(b.gender == null ? '-NA-' : b.gender)+'</li>'+
                                    '<li><strong>Mobile : </strong>'+(b.mobile_number == null ? '-NA-' : b.mobile_number)+'<span class="leave_space"> <strong>Email :</strong> </span>' +(b.email_address == null ? '-NA-' : b.email_address)+'</li>' +
                                    '<li><strong>MT Type : </strong> '+(b.mt_type == null ? '-NA-' : b.mt_type)+'</li>'+
                                     '<li><strong>Source : </strong> '+(b.source_name == null ? '-NA-' : b.source_name)+'</li>'+
                                    '<li><strong>Aadhaar Number : </strong> '+(b.aadhaar_number == null ? '-NA-' : b.aadhaar_number)+'</li>'+
                                    '<li><strong>Batch Code : </strong> '+(b.batch_code == null ? '-NA-' : b.batch_code)+'</li>'+
                                    '<li><strong>Center Name: </strong> '+(b.center_name == null ? '-NA-' : b.center_name)+'</li>'+
                                    '<li><strong>IGS Customer Name : </strong> '+(b.igs_customer_name == null ? '-NA-' : b.igs_customer_name)+'</li>'+
                                    '<li><strong>IGS Contract ID : </strong> '+(b.igs_contract_id == null ? '-NA-' : b.igs_contract_id)+'</li>'+
                                    '<li><strong>Course Name : </strong> '+(b.course_name == null ? '-NA-' : b.course_name)+'</li>'+
                                    '<li><strong>Company : </strong> '+(b.company_name == null ? '-NA-' : b.company_name)+'</li>'+
                                    '<li><strong>Skill : </strong> '+(b.skill_name == null ? '-NA-' : b.skill_name)+'</li>'+
                                    '<li><strong>Location : </strong> '+(b.location == null ? '-NA-' : b.location)+'</li>';

          candidate_list_html += '</div> ';
          candidate_list_html += '  <div class="col-sm-3 col-md-3 action">';
           <?php if (in_array($user['user_group_id'], candidate_update_roles())): ?>
                    candidate_list_html += '<a class="btn mr-1 mb-1 btn-default" style="margin-top: 45px;background-color: #008074; color: white;" href="'+base_url + 'candidate/edit/' + b.id + '" title="Edit Candidate Details" style="margin-top: 30px;"><i class="icon-edit"></i> Edit Candidate Details</a></p>';
                  <?php endif; ?>
         // candidate_list_html += '<a class="btn mr-1 mb-1 btn-default" style="background-color: #008074; color: white;" href="'+base_url + 'candidate/edit/' + b.id + '" title="Edit Candidate Details" style="margin-top: 30px;"><i class="icon-edit"></i> Edit Candidate Details</a></p>';
          candidate_list_html += '<a class="btn mr-1 mb-1 btn-info" href="'+base_url + 'candidatescontroller/show/' + b.id + '" title="Candidate Detail" style="width: 183px;margin-top: 30px;"><i class="fa fa-id-card-o"></i> View Profile</a></p>';
          candidate_list_html += '  </div> ';
          candidate_list_html += '  </div> ';
    });

  return candidate_list_html;
}

function search_candidate()
{
    $("#lblsearchbox").hide();
    $('#search_modal_qp, #search_modal_edu, #search_by').val(0).change();
    $('#search_box').val('');
    $('#searchbox').addClass('hidden');
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    $('#modal_form_search').modal('show'); // show bootstrap modal
}


function bulk_upload()
{

    $('#form_upload_candidate')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    $("#txtCandidateBulkUploadStatus").val('');
    $('#modal_form_upload').modal('show'); // show bootstrap modal
    $('.modal-title').text('Candidate List Upload'); // Set Title to Bootstrap modal title
}

function bulk_upload_save()
{
    $("#txtCandidateBulkUploadStatus").val('');

    if ($("#candidate_list").val().trim() == '')
    {
        if ($("#candidate_list").val().trim() == '')
        {
            $("#txtCandidateBulkUploadStatus").val('No file selected. \nPlease select a valid XLSX file to upload.');
            return;
        }
    }

    if ($("#candidate_list").val().trim() != '')
    {
        var varExtensions = ["XLSX"];
        var fileName = $("#candidate_list")[0].files[0].name;
        var fileExtension = fileName.split(/[. ]+/).pop();
        if (varExtensions.indexOf(fileExtension.toUpperCase()) < 0) {
            $("#txtCandidateBulkUploadStatus").val('Invalid file!\nPlease select a valid XLSX file to upload.');
            return;
        }
    }

    $("#txtCandidateBulkUploadStatus").val('');
    var url= base_url+"partner/upload_candidate_list/";
    var formData = new FormData($('#form_upload_candidate')[0]);
    // ajax adding data to database

    $.ajax({
        url: url,
        type: "POST",
        data: formData,
        contentType: false,
        cache: false,
        processData: false,
        beforeSend: loadStart,
        complete: loadStop,
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

    function loadStart() {
        $('#loading').show();
    }
    function loadStop() {
        $('#loading').hide();
    }


    /*
    $.ajax({
        url : url,
        type: "POST",
        data: formData,
        dataType: "json",
        cache:false,
        contentType: false,
        processData: false,
        success: function(result)
        {
            alert("hello" + JSON.stringify(result));
            return;

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_upload').modal('hide');
                $('#form_upload_candidate')[0].reset();
                flashAlert(data.msg_info);
                swal({
                        title: "",
                        text: data.msg_info + "!",
                        confirmButtonColor: "#5cb85c",
                        confirmButtonText: 'OK'
                    });
              //reload_candidates();
            }
            else
            {
                swal({
                    title: "",
                    text: data.errors + "!",
                    confirmButtonColor: "#5cb85c",
                    confirmButtonText: 'OK'
                });

            }

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert(JSON.stringify(jqXHR));
            /*$('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        }
    });
    */


}

function aadhar_verify(candidate_id)
{
  var url = base_url+"pramaan/aadhar_verify";
    $.ajax({
        url : url,
        type: "POST",
        data: {'candidate_id':candidate_id},
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                alert(data.msg_info);
                reload_candidates();
            }
            else
            {
                alert(data.errors);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error');
        }
    });
}

function download_candidate_list()
{
  var url = base_url+"partner/download_candidate_list_sample/";
  window.location.href = url;
}
//load the district on change of state
$(document).on('change', 'select[name="state"]', function()
{
    var sel_state_id = $(this).val();
    $.getJSON(site_url+'partner/get_district_bystate/'+sel_state_id,'',function(resp)
    {
        if(!resp.status)
        {
            $('#district .error_label').html(resp.message);
        }
        else
        {
            var district_list_html = '<option value="" >-Select District-</option>';
            $.each(resp.district_list,function(i,itm)
            {
                district_list_html += '<option value="'+itm.id+'" > '+itm.name+'</option>';
            });
        }
        $('select[name="district"]').html(district_list_html);
    });
});

//$('#search_candidate').submit(function() {
//    $('#modal_form_search').modal('hide');
//});

$(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_upload" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="javascript:window.location.reload()"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Candidate List Upload</h3>
            </div>
            <div class="modal-body form">
                <div id="msgDisplay"></div>
                <form  id="form_upload_candidate" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-body">
                        <input type="hidden" id="associate_id" name="associate_id" value="<?php echo $associate_id;?>"/>
                         <div class="form-group row">
                            <label class="label-control col-md-3" style="margin-top: 10px;">Select File<span class='validmark' style="color: red;">*</span></label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" id="candidate_list" name="candidate_list" required>
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div style="clear: both;"></div>
                        <div id="loading" style="margin-left: 27%;">
                            <img src="<?php echo base_url('/assets/images/please_wait_uploading.gif');?>">
                        </div>

                       <div class="form-group row">
                            <label for="txtStatus" class="label-control col-md-3">Upload Status</label>
                            <div class="col-md-9" style="margin-bottom: 10px;">
                                <textarea id="txtCandidateBulkUploadStatus" name="txtCandidateBulkUploadStatus" class="form-control" spellcheck="false" onkeydown="return false;" style="height: 130px;" readonly=""></textarea>
                            </div>
                        </div>
                        <label style="color: red;">*Note Upload only 5000 Candidate data.</label>
                        <div style="clear: both;"></div>

                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class='btn btn-warning' href='javascript:void(0);' onclick="download_candidate_list();" style="float: left;" id="download_candidate_list">Download Template</button>
                <button type="button" id="btnSave" onclick="bulk_upload_save();this.disabled = true;" class="btn btn-primary">Upload Candidate list</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="javascript:window.location.reload();this.disabled = true;">Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->





<!-- Search modal -->
<div class="modal fade" id="modal_form_search" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Search</h3>
            </div>
            <div class="modal-body form" style="margin-bottom: 25px;padding: 24px;">
                <div id="msgDisplay"></div>
                <form class="form-inline" id="search_candidate">
                                <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
<!--                                <input type="hidden" name="associate_id" value="<?php //echo $associate_id;?>" style="visibility: hidden;" size='1'>-->

                                <div class="form-group row">
                                    <label for="qualification" style="width: 60px;">QP:</label>
                                    <select class="form-control select2-neo" name="qualification" id="search_modal_qp" style="width: 510px; text-overflow: ellipsis;">
                                      <option value="0">All QPs</option>
                                      <?php foreach($qualification_pack_options as $qp): ?>
                                        <option value="<?= $qp->id?>"><?= "{$qp->name} ({$qp->code})"; ?></option>
                                      <?php endforeach;?>
                                    </select>
                                </div>
                                <div style="clear:both"></div>
                                <hr>
                                <div class="form-group row">
                                    <label for="experience">Education:</label>
                                    <select class="form-control select2-neo" name="experience" id="search_modal_edu" style="width: 508px; text-overflow: ellipsis; ">
                                      <option value="0">All Educations</option>
                                      <?php foreach($education_options as $ed): ?>
                                        <option value="<?= $ed->id?>"><?= $ed->name ?></option>
                                      <?php endforeach;?>
                                    </select>
                                </div>

                                <div style="clear:both"></div>
                                <hr>
                                 <div class="form-group row">
                                        <label for="search_by">Search By:</label>
                                        <select class="form-control" id="search_by" name="search_by" style="width: 508px;">
                                        <option value="0"> -Select-</option>
                                        <option value="1">Candidate ID</option>
                                        <option value="2">Name</option>
                                        <option value="3">Email</option>
                                        <option value="4">Mobile</option>
                                        <option value="5">Batch Code</option>
                                        <option value="6">Center Name</option>
                                        <option value="7">Enrollment ID</option>
                                        <option value="8">Aadhaar</option>
                                        <option value="9">Candidate Source</option>
                                        <option value="10">Company Name</option>
                                        <option value="11">Employement Location</option>
                                        <option value="12">Skill Name</option>
                                        <option value="13">Course</option>
                                    </select>
                                        <input type="text" class="form-control hidden" id="searchbox" name="searchbox" value="" placeholder="Select Search by" style="width: 507px;  margin-top: 15px;  height: 45px; margin-left: 67px;">
                                        <label id="lblsearchbox" style="color:red; display: none;margin-left: 12%;">* Please Enter Search Value</label>
                                 </div>
<!--                                <div class="form-group" style="margin-top: 15px;">
                                    <button type="button" data-dismiss="modal" class="btn btn-success btn-xl" style="width: 350px;margin-left: 25%;" onclick="filter_form_submit()">Search</button>
                                </div>
                            </form>-->
            </div>
            <div class="modal-footer">
                <div class="form-group">
                    <button type="button" class="btn btn-success btn-xl" style="width: 165px;" onclick="filter_form_submit()">Search</button>
                    <button type="button" data-dismiss="modal" class="btn btn-danger btn-xl" >Cancel</button>
                </div>
            </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
$(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0') {
                $('#searchbox').addClass('hidden');
            }
            else {
                $('#searchbox').removeClass('hidden');
                $('#searchbox').focus();
            }
        });
    });
</script>

<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<style>
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  Show Aavailable Job List
 * @date  Nov_2016
*/

table td{border:0px!important;}
table tr{margin-bottom: 5px;}

.postedon
{
    padding-top: 10px;
}
.list-group-item,.list-group-item-heading
{
    padding: 4px 15px;
}
#job_content_block div.row
{
  background-color: #fff;
  margin-bottom:5px;
  padding: 5px;
  border-radius: 10px;
}

.vcenter
{
  height: auto;
  position: relative;
  transform: translateY(40%);
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {

        padding: 5px !important; // currently 8px
    }

* {
    box-sizing: border-box;
}

.column {
    float: left;
    width: 33.33%;
    padding: 55px;
    margin-top: -5%;
    margin-bottom: -4%;
}

/* Clearfix (clear floats) */
.row::after {
    content: "";
    clear: both;
    display: table;
}

/* Responsive layout - makes the three columns stack on top of each other instead of next to each other */
@media screen and (max-width: 500px) {
    .column {
        width: 100%;
    }
}

/* Add this attribute to the element that needs a tooltip */
[data-tooltip] {
    position: relative;
    z-index: 2;
    cursor: pointer;
}

/* Hide the tooltip content by default */
[data-tooltip]:before,
[data-tooltip]:after {
    visibility: hidden;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
    filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=0);
    opacity: 0;
    pointer-events: none;
}

/* Position tooltip above the element */
[data-tooltip]:before {
    position: absolute;
    bottom: -50%;
    left: 50%;
    margin-bottom: -35px;
    margin-left: -80px;
    padding: 7px;
    width: 180px;
    -webkit-border-radius: 3px;
    -moz-border-radius: 3px;
    border-radius: 3px;
    background-color: #000;
    background-color: hsla(0, 0%, 20%, 0.9);
    color: #fff;
    content: attr(data-tooltip);
    text-align: center;
    font-size: 14px;
    line-height: 1.2;
}

/* Triangle hack to make tooltip look like a speech bubble */
[data-tooltip]:after {
    position: absolute;
    top: 30px;
    left: 50%;
    margin-left: -5px;
    width: 0;
    border-bottom: 5px solid #000;
    border-bottom: 5px solid hsla(0, 0%, 20%, 0.9);
    border-right: 5px solid transparent;
    border-left: 5px solid transparent;
    content: " ";
    font-size: 0;
    line-height: 0;
}

/* Show tooltip content on hover */
[data-tooltip]:hover:before,
[data-tooltip]:hover:after {
    visibility: visible;
    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
    filter: progid: DXImageTransform.Microsoft.Alpha(Opacity=100);
    opacity: 1;
}
</style>
<div class="content-body" style="padding: 10px;">
   <!-- <a href="<?php /*echo base_url("pramaan/add_sourcing_partner/$district_coordinator_id")*/?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add Sourcing Partner</button></a>-->
    <!-- File export table -->
    <div class="col-md-12">
      <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-primary" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5><?php echo $_SESSION['status']; ?></h5>
      </div>
      <?php endif; ?>
    </div>
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 20px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Job Board
                </li>
            </ol>
        </div>
    </div>


    <section id="description" class="card" style="border: none!important;">

         <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Job Board</h4></label>
                    <div class="form-group">
                        <div class="page_display_log" style="color: green"></div>
                    </div>
                    <div class="panel-body" style="float: right;margin-top: -48px;">
                        <div class="row text-small">
                            <div class="col-sm-12">
                                <form class="form-inline">
                                    <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                    <div class="form-group" style="margin-right: 10px;">
                                        <label for="email">Status:</label>
                                        <select class="form-control select2-neo" id="ddljobstatus" name="ddljobstatus">
                                            <?php
                                            foreach ($status_list as $row) {
                                                $OptionSelected = $row['id'] == 2 ? ' selected ' : '';
                                                echo '<option value="' . $row['id'] . '" ' . $OptionSelected . '>' . $row['name'] . '</option>';
                                            }
                                            ?>
                                            <option value="0"> All </option>
                                        </select>
                                    </div>


                                    <div class="form-group">
                                        <label for="email">QP:</label>
                                        <select class="form-control select2-neo" id="ddlQP" name="ddlQP" style="width:400px;">
                                            <option value="0"> All </option>
                                            <?php
                                                foreach ($qp_list as $row)
                                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                            ?>
                                        </select>
                                    </div>
                                    <!-- <div class="form-group">
                                        <label for="pwd">Education:</label>
                                        <select class="form-control" id="ddlEducation" name="ddlEducation">
                                            <option value="">-All Educations-</option>
                                            <?php
                                            // foreach ($education_list as $row)
                                            //     echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                            ?>
                                        </select>
                                    </div> -->

                                    <div class="form-group" style="display:none;">
                                        <label for="pwd">Candidate Source:</label>
                                        <select class="form-control" id="search_job_type" name="search_job_type">
                                            <option value="0">-All-</option>
                                            <?php foreach ($candidate_types as $type): ?>
                                              <option value="<?= $type; ?>"><?= $type; ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="form-group" style="padding-left: 0.5em!important">
                                        <button type="button" class="btn btn-success btn-md" onclick="filter_form_submit()"><span class="icon-search"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                 <div class="card-body">

                    <div id="jobBoard_list_block" class="page_content" style="overflow-x: hidden;">
                        <div id="job_content_block">

                        </div>
                        <div class="pagination" align="right"></div>
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
  load_jobBoard_list_content('');
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
function filter_form_submit()
{

  $('input[name="sel_page"]').val(0);
  load_jobBoard_list_content('');
  return false;
}

$('#jobBoard_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_jobBoard_list_content($(this).attr('href'));
});

/**
 * ======== Default load function ====================
 */
function load_jobBoard_list_content(pagi_url)
{
  var colcount=4;
  var url='';
  var search_key=0;
  var sel_page = $('input[name="sel_page"]').val();

  var job_status_id = $("#ddljobstatus").val();
  var qp_id = $("#ddlQP").val();
  var education_id = $("#ddlEducation").val();
  var job_mt_type = $("#search_job_type").val();
  if(pagi_url == '')
  {
    url = site_url+'partner/job_board_list/'+job_status_id+'/'+qp_id+'/'+education_id+'/'+sel_page;
  }
  else
    url = pagi_url;

  $('#jobBoard_list_block #job_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
  $('#jobBoard_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var jobBoard_list_html = '';
    var page_display_log='';
    if(resp==null)
    {
      jobBoard_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_jobBoard_list_content(\'\');">Click here to Reload</a>.</div></div>';
    }
    else if(resp.status == 'success')
    {

      jobBoard_list_html = jobBoard_list_inner_content(resp,colcount);

      $('.pagination').html(resp.pagination);

     // $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");

      if(resp.pg_count_msg != undefined) {
        page_display_log=('<span>'+resp.pg_count_msg+'</span>');
      }

    }
    else
    {
      jobBoard_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
    }

    $('#jobBoard_list_block #job_content_block').html(jobBoard_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function jobBoard_list_inner_content(resp,colcount)
{
  var  job_list_html='';
  var page_no=resp.pg;
  //console.log(resp);
    $('input[name="sel_page"]').val(page_no);
    $.each(resp.job_list,function(a,b)
    {
      var slno=(page_no*1+a*1+1);
      job_list_html +='<span class="postedon small" style="float: right; margin-top: 5px; font-weight: 700; margin-right: 62px; font-size: 10px; background-color: #f1f1f1;  padding: 3px;">Posted on: '+b.created_at+'</span>';
        job_list_html +='<span class="postedon small" style="float: right; margin-top: 32px; font-weight: 700; margin-right: -175px; font-size: 10px; background-color: #f1f1f1; padding: 3px; margin-bottom: -20px;">'+ b.job_expiry_text + '</span>';
      job_list_html += '  <div class="row" style="border-bottom: 1px solid #e1e1e1;padding: 20px;">';
          job_list_html += '  <div class="col-md-6" style="width: 37%;">';
              job_list_html += '<p><b class="text-uppercase" style="color: #ef7f1a;font-size: 16px;">'+b.job_title+'</b><br><strong> QP: </strong>'+(b.qualification_pack_name || 'N/A')+'</i></p>'+
                        '<ul>'+
                            '<li><strong> Customer: </strong>'+ (b.customer_name || 'N/A')+'</li>'+
                            '<li><strong>Job Description: </strong>'+(b.job_description || 'N/A')+'</li>'+
                            '<li><strong> Positions: </strong>'+(b.no_of_position || 'N/A')+'</li>'+
                            '<li><strong> Location: </strong>'+(b.office_location || 'N/A')+'</li>'+
                            '<li><strong> Min Education: </strong>'+(b.education_name || 'N/A')+'</li>'+
                           '</ul>';
                job_list_html += '</div> ';
                job_list_html += '  <div class="col-md-6" style=" margin-top: 1%;  margin-left: -25px;">'+
                            '<ul>'+
                            '<li><strong> Work Experience: </strong>'+(b.experience_from || '0')+'-'+(b.experience_to || '0')+' years'+'</li>'+
                            '<li><strong> Functional Area: </strong>'+(b.functional_area_name || 'N/A')+'</li>'+
                            '<li><strong> Industry: </strong>'+(b.industry_name || 'N/A')+'</li>'+
                            '<li><strong> Job Status: </strong>'+(b.job_status_name || 'N/A')+'</li>'+
                            '<li><strong> Job Code: </strong>'+(b.job_code || 'N/A')+'</li>'+                            
                            '<li><strong> Vacancies: </strong>'+(b.no_of_vacancies || 'Position Filled')+'</li>'
                            '<li><strong> CTC: </strong>'+((b.offered_ctc_from || '0')+'-'+(b.offered_ctc_to || '0')+' /Month')+'</li>'+
                            '</ul>';
          job_list_html += '</div> ';
       /* job_list_html += '<div class="column" style="margin-left: -33%; margin-top: -45px;"> ';
        job_list_html += '<a title="Recommended Candidate" data-tooltip="Recommended Candidate" style="margin-bottom: 10px; width: 100%" href="'+site_url+'/candidatescontroller/suggestedCandidates/'+b.id+'"><button class="btn btn-primary" style="margin-right: 5px;" ><i class="fa fa-thumbs-up"></i></button></a>';
        job_list_html += '<a title="Applied Candidate" data-tooltip="Applied Candidate" style="margin-bottom: 10px; width: 100%" href="'+site_url+'/candidatescontroller/appliedCandidates/'+b.id+'"><button class="btn btn-success" style="margin-right: 5px;"><i class="fa fa-hand-paper-o"></i></button></a>';
        job_list_html += '<a title="Joined Candidate" data-tooltip="Joined Candidate" style="margin-bottom: 10px; width: 100%" href="'+site_url+'/Pramaan/candidate_joined_jobwise/'+b.id+'"><button class="btn btn-info" style="margin-right: 5px;"><i class="fa fa-handshake-o"></i></button></a>';
        job_list_html += '<a title="Clone Job" data-tooltip="Clone Job" style="margin-bottom: 10px; width: 100%" href="'+site_url+'/jobscontroller/create/'+b.id+'"><button class="btn btn-warning" style="margin-right: 5px;"><i class="fa fa-clone"></i></button></a>';
        job_list_html += '</div> ';*/
            job_list_html += '  <div class="col-md-12" style=" margin-top: -75px; margin-left: 65%;">';
                  if(b.no_of_vacancies == 0)
                  {
                        job_list_html += '<img src="'+base_url+'/adm-assets/images/position_filled.png" height="120" width="250">';
                        
                        //job_list_html += '<a class="btn btn-info" style="margin-bottom: -109px; width: 271px; margin-left: -89%;"  href="'+site_url+'/Pramaan/candidate_joined_jobwise/'+b.id+'">Joined Candidates ('+b.joined_candidates+')</a>';
                     
                  }             
            job_list_html += '  </div> ';
       
          job_list_html += '  <div class="col-md-12" style="margin-top: 20px;">';
              if(parseInt(b.job_status_id)!= 2 || (b.no_of_vacancies == 0))
              {
                     <?php if (in_array($user['user_group_id'], job_board_cloned_roles())): ?>
                      job_list_html +='<div class="col-md-4">';
                     job_list_html += '<a class="btn btn-warning" style="width: 272px;margin-left: 15px;" href="'+site_url+'/jobscontroller/create/'+b.id+'">Clone Job</a>';
                     job_list_html +='</div>';
                     <?php endif; ?>

              }
              else
              {     
                   <?php if (in_array($user['user_group_id'], job_board_recommanded_roles())): ?>
                    job_list_html +='<div class="col-md-4">';
                    job_list_html += '<a class="btn btn-primary" style="margin-bottom: 10px;width: 90%; "  href="'+site_url+'/candidatescontroller/suggestedCandidates/'+b.id+'">Recommended Candidates ('+b.recommended_candidate_count+')</a>';
                    job_list_html +='</div>';
                  <?php endif; ?>
                       <?php if (in_array($user['user_group_id'], job_board_applied_roles())): ?>
                            job_list_html +='<div class="col-md-4">';
                    job_list_html += '<a class="btn btn-success" style="margin-bottom: 10px;width: 90%; "  href="'+site_url+'/candidatescontroller/appliedCandidates/'+b.id+'">Applied Candidates ('+b.applied_candidate_count+')</a>';
                  job_list_html +='</div>';
                  <?php endif; ?>
                       <?php if (in_array($user['user_group_id'], job_board_joined_roles())): ?>
                            job_list_html +='<div class="col-md-4">';
                    job_list_html += '<a class="btn btn-info" style="margin-bottom: 10px; width: 90%;"  href="'+site_url+'/Pramaan/candidate_joined_jobwise/'+b.id+'">Joined Candidates ('+b.joined_candidates+')</a>';
                  job_list_html +='</div>';
                  <?php endif; ?>
                       job_list_html +='</div>';
                       job_list_html +='<class="col-md-4">';
                  <?php if (in_array($user['user_group_id'], job_board_cloned_roles())): ?>
                       job_list_html +='<div class="col-md-4">';
                    job_list_html += '<a class="btn btn-warning" style="margin-bottom: 10px; width: 87%;margin-left: 15px;"  href="'+site_url+'/jobscontroller/create/'+b.id+'">Clone Job</a>';
                  job_list_html +='</div>';
                  <?php endif; ?>
                  <?php if (in_array($user['user_group_id'], job_board_recommanded_roles())): ?>
                       job_list_html +='<div class="col-md-4">';
                    job_list_html += '<a class="btn btn-danger" style="width: 87%;margin-left: 6px;margin-bottom: 10px; background-color: #b84def; border-color: #872db5;"  href="'+site_url+'/candidatescontroller/mtocandidates/'+b.id+'">MTO Candidates</a>';
                 job_list_html +='</div>';
                   <?php endif; ?>
                  <?php if (in_array($user['user_group_id'], job_board_all_candidates_roles())): ?>
                       job_list_html +='<div class="col-md-4">';
                    job_list_html += '<a class="btn btn-success" style="width: 87%; margin-left: -3px;background-color:#18a765;"  href="'+site_url+'/candidatescontroller/allcandidates/'+b.id+'">Non-matching Candidates</a>';
                  job_list_html +='</div>';
                  <?php endif; ?>
                  job_list_html +='</div>';

              }
        //job_list_html += '<a class="btn btn-primary" href="'+site_url+'/candidatescontroller/suggestedCandidates/'+b.job_id+'">Recommanded Candidates</a>';
        //job_list_html += '<a class="btn btn-primary" href="'+site_url+'/candidatescontroller/appliedCandidates/'+b.job_id+'">Applied Candidates</a>';
        job_list_html += '  </div> ';
        job_list_html += '  </div> ';

  });

  return  job_list_html;
}

//apply modal

function find_matching_candidate(id)
{
   /* apply_method = 'apply';
    $('#form-apply')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('[name="id"]').val(id);
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Apply for a Job'); // Set Title to Bootstrap modal title*/
   // alert(id);
}

window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
        $(this).remove();
    });
}, 4000);

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable
    var url;

   /* if(apply_method == 'add')
    {
        url = base_url+"ajax_add";
    } */
    if(apply_method == 'apply')
    {
        url = base_url+"pramaan/job_apply";
    }
    else
    {
        url = base_url+"pramaan/ajax_update";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form-apply').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                alert("Thank you\n You Will be contacted soon.....");
                $('#modal_form').modal('hide');
            }
            else
            {
              var form="#form-apply";
              $.each(data.errors, function(key, val)
              {
                $('[name="'+ key +'"]',form).parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                $('[name="'+ key +'"]',form).next('span').html(val); //select span help-block class set text error string
              });
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable
        }
    });
}

function job_detail(job_id,n_locations)
{
    //Ajax Load data from ajax
    if(parseInt(n_locations))
    {
      $.ajax({
          url : base_url+"employer/get_job_details/" + job_id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {

             if(data.status) //if success close modal and reload ajax table
              {
                   var job_detail_html = '<tr><th>Sl No</th><th>Qualification Pack</th><th>Location</th><th>No of Openings</th><th>Salary</th></tr>';
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

function select_location(job_id)
{
      $.ajax({
          url : base_url+"employer/get_job_details/" + job_id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {

             if(data.status) //if success close modal and reload ajax table
              {
                   var job_location_html='';
                   $.each(data.job_details,function(a,b)
                    {
                          job_location_html += '<option value="'+b.location_id+'">'+b.location_name+'</option>';
                    });
              }
              $('#modal_job_location select').html(job_location_html);
              $('input[name="job_id"]').val(job_id);
              $('#modal_job_location').modal('show');
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });
}
</script>

<!-- Bootstrap modal -->

<!--  job detail -->

<div class="modal fade text-xs-left" id="modal_job_detail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="myModalLabel34">Job details</h3>
            </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered dataTable no-footer"><!-- used static table in framework if any doubt please refere framework source code -->
                        <tbody>

                        </tbody>
                    </table>
                </div>
        </div>
    </div>
</div>

<!--  select locations -->

<div class="modal fade text-xs-left" id="modal_job_location" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="myModalLabel34">Select location</h3>
            </div>
                <form name="matching_location_jobs" method="post" action="<?php echo base_url('partner/matching_candidates/');?>">
                <input type="hidden" name="job_id">
                <div class="modal-body">
                    <select name="location_id" class="form-control"><!-- used static table in framework if any doubt please refere framework source code -->

                    </select>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn mr-1 mb-1 btn-primary" title="Find Matching Candidate"><i class="icon-android-search"></i> Find Matching Candidates</button>
                </div>
        </div>
    </div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.select2-neo').select2();
    });
    </script>

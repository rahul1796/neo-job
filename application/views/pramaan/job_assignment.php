<style>
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  Job assignment
 * @date  Nov_2016
*/

.pagination a{padding:5px 15px;background: #dfdfdf;color: #000;font-weight: bold;font-size: 13px;}
.pagination { float: left; }

td {
  border: solid 1px #ccc; 
}
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
  background-color: #FEFCFF;
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

.table td, .table th 
{
     padding: 5px; 
}

</style>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
<div class="content-body" style="overflow-x: hidden !important;">
    <?php
    $option_employers=array(''=>'-Select Employer-');

    foreach ($employer_list as $row)
    {
        $option_employers[$row['id']]=$row['name'];
    }

    $option_locations=array(''=>'-Select Location-');
    foreach ($location_list as $row)
    {
        $option_locations[$row['id']]=$row['name'];
    }

    ?>
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Job Assignment List
                </li>
            </ol>
        </div>
    </div>

    <section id="configuration">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Job Assignment List</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <hr>
                        <div class="row text-small">
                            <div class="col-sm-12">
                                <form class="form-inline">
                                    <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                    <div class="form-group">
                                        <label for="locations">Location:</label>
                                        <?php echo form_dropdown('locations',$option_locations, '', 'class="form-control" id="locations"');?>
                                    </div>
                                    <div class="form-group">
                                        <label for="employers">Employer:</label>
                                        <?php echo form_dropdown('employers',$option_employers, '', 'class="form-control" id="employers" style="width:200px;"');?>
                                    </div>
                                    <div class="form-group">
                                        <label for="assignment_status">Job Status:</label>
                                        <select class="form-control" name="assignment_status">
                                            <option value="">-Select All-</option>
                                            <option value="0">Unassigned</option>
                                            <option value="1">Assigned</option>
                                        </select>
                                    </div>
                                    <div class="form-group" style="padding-left: 0.5em!important">
                                        <button type="button" class="btn btn-icon btn-success mr-1" onclick="filter_form_submit()"><span class="icon-android-search"></span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">

                            <div id="job_assignment_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                <div id="job_content_block" style="overflow-x:scroll;">

                                </div>
                                <div class="pagination"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->

</div>


<script>
$(document).ready(function() 
{
/*  $(".date_from").datepicker({'changeYear':true,'changeMonth':true});
  $(".date_to").datepicker({'changeYear':true,'changeMonth':true});
  */
  load_job_assignment_content('');
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
  load_job_assignment_content('');
  return false;
}

$('#job_assignment_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_job_assignment_content($(this).attr('href'));
});

function reload_table()
{
  load_job_assignment_content('');
  return false;
}
/**
 * ======== Default load function ====================
 */
function load_job_assignment_content(pagi_url)
{
  var colcount=4;
  var url='';
  var sel_page = $('input[name="sel_page"]').val();
  var assignment_status = $('select[name="assignment_status"]').val();
  var locations = $('select[name="locations"]').val();
  var employers = $('select[name="employers"]').val();

  if(assignment_status=='')
      assignment_status=-1;
  if(locations=='')
      locations=0;
  if(employers=='')
      employers=0;
  if(pagi_url == '')
  {
    url = site_url+'pramaan/job_assignment_list/'+locations+'/'+employers+'/'+assignment_status+'/'+sel_page;
  }
  else
    url = pagi_url; 
  $('#job_assignment_list_block #job_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
  $('#job_assignment_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var job_assignment_list_html = '';
    var page_display_log='';
    if(resp==null) 
    {
      job_assignment_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_job_assignment_content(\'\');">Click here to Reload</a>.</div></div>';
    }
    else if(resp.status == 'success')
    {


      job_assignment_list_html = job_assignment_inner_content(resp,colcount);

      $('.pagination').html(resp.pagination);

     // $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");
      
      if(resp.pg_count_msg != undefined) {
        page_display_log=('<span>'+resp.pg_count_msg+'</span>');
      }

    }
    else
    {
      job_assignment_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
    }
    
    $('#job_assignment_list_block #job_content_block').html(job_assignment_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function job_assignment_inner_content(resp,colcount)
{
  var  job_list_html='';
  var page_no=resp.pg;
    $('input[name="sel_page"]').val(page_no);
    //var status_flags = ['<a class="button button-success btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',0)">Assign</a>','<a class="button button-primary btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',1)">Reassign</a>'];
    var job_list_html = '<table class="table table-striped" style="width:100%;">';
        job_list_html += '     <tr><th nowrap>Sl No</th><th nowrap>Employer Details</th><th nowrap>Phone</th><th>Job Description</th><th>Experience</th><th>Created on</th><th>No of Locations</th>';
    $.each(resp.job_list,function(a,b)
    {
      var slno=(page_no*1+a*1+1);
      var status_flags = (b.assignment_status)?'<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',1)">Reassign</a>':'<a class="btn btn-success btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',0)">Assign</a>';
                  job_list_html += '     <tr><td>'+slno+'</td>';
                  job_list_html += '      <td><b class="text-uppercase">'+b.employer_name+'</b></td>';
                  job_list_html += '      <td>'+b.contact_phone+'</td>';
                  job_list_html += '      <td>'+b.job_desc+'</td>';
                 /* job_list_html += '      <td>'+b.job_desc+'</td>';
                  job_list_html += '      <td>'+b.job_location+'</td>';*/
/*                  job_list_html += '      <td>'+b.min_salary+'-'+b.max_salary+'</td>';*/
                  job_list_html += '      <td>'+b.min_experience+'-'+b.max_experience+'</td>';
                  

               /*   job_list_html += '      <td>'+b.rec_sup_exec_name+'</td>';
                  job_list_html += '      <td>'+b.assignment_date+'</td>';*/
                 
                  job_list_html += '      <td>'+b.created_on+'</td>';
                  job_list_html += '      <td align="center">'+'<a href="javascript:void(0)" class="nlocations" title="Job Detail" onclick="job_detail('+"'"+b.job_id+"'"+','+"'"+b.n_locations+"'"+')">'+((b.n_assigned==b.n_locations)?'<span class="tag tag tag-danger">':'<span class="tag tag tag-info">')+b.n_locations+'</span></a>'+'</td>';
    });
   job_list_html += '</table>';
  return  job_list_html;
}

function job_detail(job_id,n_locations)
{
    //Ajax Load data from ajax
    if(parseInt(n_locations))
    {
      document.location.href="job_assignment_detail/" + job_id;
    }
}
</script>




<style>
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  Show Aavailable Job List
 * @date  Nov_2016
*/

.pagination a{padding:5px 5px;background: #dfdfdf;color: #000;font-weight: bold;font-size: 13px;}
.pagination { float: right; }
table td{border:0px!important;}
table tr{margin-bottom: 5px;}

.postedon
{
    padding-top: 10px;
    bottom: 0;
}
.list-group-item,.list-group-item-heading
{
    padding: 4px 15px;
}
#scheduled_job_content_block div.row
{
    margin-bottom: 5px;
    padding: 5px;
    border-radius: 10px;
    border-bottom: 1px solid #e1e1e1;
    padding: 22px;
}

.vcenter 
{
  height: auto;
  position: relative;
  transform: translateY(20%);
}
b
{
  color: #999;
}

.job_status_label
{
  padding-bottom: 20px;
  display: inline-block;
  color: green;
}
.table > thead > tr > th, .table > tbody > tr > th, .table > tfoot > tr > th, .table > thead > tr > td, .table > tbody > tr > td, .table > tfoot > tr > td {

        padding: 5px !important; // currently 8px
    }
</style>
<div class="content-body" style="padding: 10px;">
    <a href="javascript:void(0)" title="Candidate Details" onclick="candidate_details()"><button type="button" class="btn btn-info btn-min-width mr-1 mb-1" style="margin-left: 70%;">Candidate: <?php echo $candidate_detail['name'];?> <?php echo $candidate_detail['mobile'];?></button></a>
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-top: -45px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("partner/matching_jobs/". $candidate_detail['candidate_id'],"Matching Jobs");?></a>
                </li>
                <!--<li class="breadcrumb-item"><?php /*echo anchor("partner/job_board","Job Board");*/?></li>-->
                <li class="breadcrumb-item active">Screening Jobs
                </li>
            </ol>
        </div>
    </div>

    <section id="description" class="card" style="border: none!important;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Matched Candidates</h4></label>
                </div>
                <!--<a href="<?php /*echo base_url("partner/scheduled_jobs/".$candidate_detail['candidate_id']);*/?>" style="float: right; margin-top: -43px;"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-calendar"></i>Scheduled Jobs</button></a>-->
                <div class="card-body">
                    <table class="table table-bordered">
                        <form name="form_screening_jobs" method="post" action="<?php echo base_url('partner/update_screened_job_status')?>">
                            <div class="panel-body">

                                <div class="row text-small">
                                    <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                    <input type="hidden" name="candidate_id" value="<?php echo $candidate_detail['candidate_id'];?>" style="visibility: hidden;" size='1'>
                                    <div class="col-sm-3 col-md-3" style="margin-left: 20px;">
                                        <div class="page_display_log" style="color: green"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="scheduled_job_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                <div id="scheduled_job_content_block">

                                </div>
                            </div>
                            <div class="pagination" align="right"></div>
                        </form>
                    </table>

                </div>

            </div>
    </section>
</div>


<script>
var status_resp='';
$(document).ready(function() 
{
/*  $(".date_from").datepicker({'changeYear':true,'changeMonth':true});
  $(".date_to").datepicker({'changeYear':true,'changeMonth':true});
  */
  load_scheduled_job_list_content('');
  //check box toggling
  $('.selectex input:checkbox').click(function() 
  {
      $('.selectex input:checkbox').not(this).prop('checked', false);
  });  
  status_resp= load_job_status();

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
  load_scheduled_job_list_content('');
  return false;
}

$('#scheduled_job_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_scheduled_job_list_content($(this).attr('href'));
});

function reload_job_list()
{
  var pgurl='' 
  var sel_page = $('input[name="sel_page"]').val();
  var candidate_id=$('input[name="candidate_id"]').val();
  if(candidate_id=='')
      candidate_id=0;
  pgurl=site_url+'partner/scheduled_jobs_list/'+candidate_id+'/'+sel_page;
    load_scheduled_job_list_content(pgurl);
return false;
}


/**
 * ======== Default load function ====================
 */
function load_scheduled_job_list_content(pagi_url)
{

  var colcount=4;
  var url='';
  var sel_page = $('input[name="sel_page"]').val();
  var candidate_id=$('input[name="candidate_id"]').val();
  if(candidate_id=='')
      candidate_id=0;

  if(pagi_url == '')
  {
    url = site_url+'partner/scheduled_jobs_list/'+candidate_id+'/'+sel_page;
  }
  else
    url = pagi_url; 

  $('#scheduled_job_list_block #scheduled_job_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
  $('#scheduled_job_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var scheduled_job_list_html = '';
    var page_display_log='';
    if(resp==null) 
    {
      scheduled_job_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_scheduled_job_list_content(\'\');">Click here to Reload</a>.</div></div>';
    }
    else if(resp.status == 'success')
    {

      scheduled_job_list_html = scheduled_job_list_inner_content(resp,colcount);

      $('.pagination').html(resp.pagination);

     // $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");
      
      if(resp.total_rec!= undefined) {
        page_display_log=('<span><b>Total Jobs:</b> '+resp.total_rec+'</span>');
      }

    }
    else
    {
      scheduled_job_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
    }
   
    $('#scheduled_job_list_block #scheduled_job_content_block').html(scheduled_job_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function scheduled_job_list_inner_content(resp,colcount)
{
  var  job_list_html='';
  var page_no=resp.pg;
  var status_yes=['','checked'];
  var status_no=['checked',''];
    $('input[name="sel_page"]').val(page_no);
    $.each(resp.screened_candidate_list,function(a,b)
    {
      //var status_flags = ['Inactive','Active','Suspended'];
      var submit_btn_status=(Math.abs(b.status_id)>3)?'disabled':'';
      var slno=(page_no*1+a*1+1);
          job_list_html += '<div class="row">';
          job_list_html += '  <div class="col-sm-2 col-md-2 vcenter">';
          job_list_html += '  <img src='+base_url+'/assets/images/one.jpg '+' onerror=this.src='+"'"+base_url+'assets/images/default.jpg'+"'"+' class=img-thumbnail height=75 width=75>';
          job_list_html += '</div> ';
          job_list_html += '  <div class="col-sm-7 col-md-7">';
          job_list_html += '<p><b>Employer: '+b.employer_name+'</b></p>'+
                              '<ul>'+
                              '<li><b>Job Description :</b> '+b.job_desc+'</li>'+
                              '<li>No of Location: <a href="javascript:void(0)" class="nlocations" title="Job Detail" onclick="job_detail('+"'"+b.job_id+"'"+','+"'"+b.n_locations+"'"+')"><span class="tag tag tag-info">'+b.n_locations+'</span></a></li>'+
                              '<li><b>Educational Qualification :</b>  '+b.min_education+'</li>'+
                              '</ul>';
          job_list_html += '</div> ';
          job_list_html += '  <div class="col-sm-3 col-md-3 vcenter">';
          job_list_html += '  <b> <label for="select_'+b.id+'">Status:</label></b>';
          job_list_html += '<select class="form-control input-sm job_status" style="width:12em; float:right;" name="select_'+b.id+'"  '+submit_btn_status+'>';
          job_list_html += get_job_status(status_resp,b.status_id);
          job_list_html += '</select>';
          job_list_html += '<a class="btn btn-block btn-primary '+submit_btn_status+'" href="javascript:void(0)" title="Submit" onclick="scheduled_job_status('+b.id+')" style="margin-top:10px;" >Submit</a>';
          job_list_html += '<p class="postedon small">Posted on:'+b.created_on+'</p>';
          job_list_html += '  </div> ';
      job_list_html += '  </div> ';   

    });

  return  job_list_html;
}

function load_job_status()
{
  var resp_data="";
  var scheduled=3;
  $.ajax({
      url : site_url+'/partner/get_jobstatus_list/'+scheduled,
      type: "GET",
      dataType: "JSON",
      async:false,
      success: function(data)
      {
          if(data.status) //if success close modal and reload ajax table
          {
               resp_data=data;
          }
      }
  });
  return resp_data;
}

function get_job_status(resp,id)
{
      var status_html= '<option value="0">-Select Status-</option>';
      $.each(resp.job_status_list,function(a,b)
      {      
        var selected_status=(id==b.value)?'selected':'';
         status_html += '<option value="'+b.value+'" '+selected_status+'>'+b.name+'</option>';
      });
    return status_html;
}
function scheduled_job_status(screened_job_id)
{
 var selected_status=$('select[name=select_'+screened_job_id+']').val();
 var q1_response_status=$('input[name=radio_q1_'+screened_job_id+']:checked').val();
 var q2_response_status=$('input[name=radio_q2_'+screened_job_id+']:checked').val();
  $.ajax({
        url : base_url+'partner/update_screened_job_status',
        type: "POST",
        data: {'screened_job_id':screened_job_id,'selected_status':selected_status,'q1_response_status':q1_response_status,'q2_response_status':q2_response_status},
        dataType: "JSON",
        success: function(data)
        {
            if(data.status=='success') //if success close modal and reload ajax table
            {
                reload_job_list();
            }
        }
  });

}

function candidate_details()
{
    $('#modal_form_candidate').modal('show'); // show bootstrap modal when complete loaded
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
</script>
<!-- Bootstrap modal -->
<div class="inner">
<div class="modal fade" id="modal_form_candidate" role="dialog">
    <div class="modal-dialog modal-sm" style="width:40%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Candidate Details</h3>
            </div>
            <div class="modal-body form">
            <table>
            <tr><td width="35%">Candidate Name:</td><td><b><?php echo $candidate_detail['name'];?></b></td></tr>
            <tr><td>Education:</td><td><b><?php echo $candidate_detail['education_name'];?></td></tr>
            <tr><td>Expected Salary:</td><td><b><?php echo $candidate_detail['expected_salary'];?></b></td></tr>
            <tr><td>Experience:</td><td><b><?php echo $candidate_detail['experience'];?></b></td></tr>
            <tr><td>Language:</td><td><b><?php echo $candidate_detail['language_name'];?></b></td></tr>
            <tr><td>State:</td><td><b><?php echo $candidate_detail['state_name'];?></b></td></tr>
            <tr><td>District:</td><td><b><?php echo $candidate_detail['district_name'];?></b></td></tr>
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>

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
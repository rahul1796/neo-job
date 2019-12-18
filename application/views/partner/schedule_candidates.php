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
#screening_candidate_content_block div.row
{
  /*background-color: #FAFAFA;*/
  margin-bottom:5px;
  padding: 5px;
  border-bottom: 1px solid #e1e1e1;
  /*box-shadow: 2px 2px 2px #CCC;*/
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
/*li span
{
  padding-left: 20px;
}*/
.job_status_label
{
  padding-bottom: 20px;
  display: inline-block;
  color: green;
}

option:disabled
{
  color:red;
}
</style>
<div class="content-body" style="padding: 10px;">
    <a href="javascript:void(0)" onclick="job_details()"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1" style="float: right;">Job Details:<?php echo $job_details['contact_phone'];?></button></a>
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-6 col-xs-6">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("partner/matching_candidates/".$job_details['job_id'],"Matching Candidates");?></a>
                </li>
                 <li class="breadcrumb-item active">Scheduling
                </li>
            </ol>
        </div>
    </div>
    <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
    <input type="hidden" name="job_id" value="<?php echo $job_details['job_id']?>" style="visibility: hidden;" size='1'>

    <section id="description" class="card" style="border: none!important;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Scheduled Candidates</h4></label>
                    <div class="page_display_log" style="color: green; float: right;"></div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <form name="form_screening_candidates" method="post" action="<?php echo base_url('partner/update_screened_candidate_status')?>" style="margin: 0px 33px 5px 25px;">

                                <div id="screening_candidate_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                    <div id="screening_candidate_content_block">

                                    </div>
                                    <div class="pagination" align="right"></div>
                                    <form>
                                </div>

                        </div>
                    </div>
                   <!-- <div class="pagination" align="right"></div>-->
                </div>

            </div>
    </section>
</div>






<script>
var status_resp='';
var location_id="<?php echo $job_details['location_id'];?>";
$(document).ready(function() 
{
/*  $(".date_from").datepicker({'changeYear':true,'changeMonth':true});
  $(".date_to").datepicker({'changeYear':true,'changeMonth':true});
  */
  load_screening_candidate_list_content('');
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
  load_screening_candidate_list_content('');
  return false;
}

$('#screening_candidate_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_screening_candidate_list_content($(this).attr('href'));
});

function reload_candidate_list()
{
  var pgurl=''
  var sel_page = $('input[name="sel_page"]').val();
  var job_id=$('input[name="job_id"]').val();
  if(job_id=='')
      job_id=0;

  pgurl = site_url+'partner/schedule_candidates_list/'+job_id+'/'+location_id+'/'+sel_page;
  load_screening_candidate_list_content(pgurl);
  return false;
}


/**
 * ======== Default load function ====================
 */
function load_screening_candidate_list_content(pagi_url)
{
  var colcount=4;
  var url='';
  var sel_page = $('input[name="sel_page"]').val();
  var job_id=$('input[name="job_id"]').val();
  
  if(job_id=='')
      job_id=0;

  if(pagi_url == '')
  {
    url = site_url+'partner/schedule_candidates_list/'+job_id+'/'+location_id+'/'+sel_page;
  }
  else
    url = pagi_url; 
  $('#screening_candidate_list_block #screening_candidate_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
  $('#screening_candidate_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var screening_candidate_list_html = '';
    var page_display_log='';
    if(resp==null) 
    {
      screening_candidate_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_screening_candidate_list_content(\'\');">Click here to Reload</a>.</div></div>';
    }
    else if(resp.status == 'success')
    {

      screening_candidate_list_html = screening_candidate_list_inner_content(resp,colcount);

      $('.pagination').html(resp.pagination);

     // $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");
      
      if(resp.total_rec!= undefined) {
        page_display_log=('<span><b>Total Candidates:</b> '+resp.total_rec+'</span>');
      }

    }
    else
    {
      screening_candidate_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
    }
   
    $('#screening_candidate_list_block #screening_candidate_content_block').html(screening_candidate_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function screening_candidate_list_inner_content(resp,colcount)
{
  var candidates_list_html='';
  var page_no=resp.pg;
  var status_yes=['','checked'];
  var status_no=['checked',''];
  
    $('input[name="sel_page"]').val(page_no);
    $.each(resp.screened_candidate_list,function(a,b)
    {
      //var candidate_status_flags = ['Inactive','Active','Suspended'];
      var submit_btn_status=(Math.abs(b.status_id)>3)?'':'';
      var slno=(page_no*1+a*1+1);
          candidates_list_html += '  <div class="row">';
          candidates_list_html += '  <div class="col-sm-2 col-md-2 vcenter">';
          candidates_list_html += '  <img src='+base_url+'assets/images/one.jpg '+' onerror=this.src='+"'"+base_url+'assets/images/default.jpg'+"'"+' class=img-thumbnail height=75 width=75>';
          candidates_list_html += '  </div> ';
          candidates_list_html += '  <div class="col-sm-7 col-md-7">';
          candidates_list_html += '<p><b>'+b.name+'</b></p>'+
                                  '<ul>'+
                                  '<li>Work Experience: '+b.total_experience+'</li>'+
                                  '<li>Educational Qualification : '+b.education+'</li>'+
                                  '<li>DOB : '+b.dob+'</li>'+
                                  '<li>Gender : '+b.gender_code+'</li>'+
                                  '<li>Aadhaar : '+b.aadhaar_num+'</li>'+
                                  '<li>Email : '+b.email+'</li>'+
                                  '<li>Mobile : '+b.mobile+'</li>'+
                                  '</ul>';
          candidates_list_html += '</div> ';
          candidates_list_html += '<div class="col-sm-3 col-md-3 vcenter">';
          candidates_list_html += '<b>Status: <span class="tag tag tag-info"> '+b.job_status+'</span></b>';
          candidates_list_html += '<select class="form-control job_status" name="select_'+b.candidate_job_id+'"  '+submit_btn_status+' style="margin: 5% 0% 5% 0%;">';
          candidates_list_html += get_job_status(status_resp,b.status_id);
          candidates_list_html += '</select>';
          candidates_list_html += '<a class="btn btn-block btn-primary '+submit_btn_status+'" href="javascript:void(0)" title="Submit" onclick="submit_candidate_status('+b.candidate_job_id+')" >Submit</a>';
          candidates_list_html += '<p class="postedon small">Posted on:'+b.created_on+'</p>';
          candidates_list_html += '</div> ';
          candidates_list_html += '</div> ';   

    });

  return  candidates_list_html;
}

//apply modal


function load_job_status()
{
  var resp_data="";
  //var scheduled=3;
  $.ajax({
     // url : site_url+'/partner/get_jobstatus_list/'+scheduled,
      url : site_url+'/partner/get_jobstatus_list/',
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
   var status_html= '';
   var disable_status='disabled';
      status_html= '<option value="0">-Select Status-</option>';  
      $.each(resp.job_status_list,function(a,b)
      {      
         var selected_status=(id==b.value)?'selected':'';
         if(id==b.value)
         disable_status='';
         status_html += '<option value="'+b.value+'" '+selected_status+' '+disable_status+'>'+b.name+'</option>';
         if(id==b.value && id<0)
         return false;
      });
  return status_html;
}

function submit_candidate_status(candidate_job_id)
{
 var selected_status=$('select[name=select_'+candidate_job_id+']').val();
  $.ajax({
        url : base_url+'partner/update_screened_candidate_status/',
        type: "POST",
        data: {'candidate_job_id':candidate_job_id,'selected_status':selected_status},
        dataType: "JSON",
        success: function(resp)
        {
            if(resp.status) //if success close modal and reload ajax table
            {
                alert(resp.msg_info);
                reload_candidate_list();
            }
        }
  });
  return false;
}


function job_details()
{
    $('#modal_form_job').modal('show'); // show bootstrap modal when complete loaded
}
</script>
<!-- Bootstrap modal -->
<div class="inner">
<div class="modal fade" id="modal_form_job" role="dialog">
    <div class="modal-dialog modal-lg" style="width:40%;">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Job Details</h3>
            </div>
            <div class="modal-body form">
            <table class="table">
            <tr><td width="35%">Employer Name:</td><td><b><?php echo $job_details['employer_name'];?></b></td></tr>
            <tr><td>Job Description:</td><td><b><?php echo $job_details['job_desc'];?></td></tr>
            <tr><td>Job Category Name:</td><td><b><?php echo $job_details['job_category_name'];?></b></td></tr>
            <tr><td>No. of openings:</td><td><b><?php echo $job_details['no_of_openings'];?></b></td></tr>
            <tr><td>Job Location:</td><td><b><?php echo $job_details['location_name'];?></b></td></tr>
            <tr><td>Contact Person Name:</td><td><b><?php echo $job_details['contact_name'];?></b></td></tr>
            <tr><td>Phone:</td><td><b><?php echo $job_details['contact_phone'];?></b></td></tr>
            <tr><td>Email:</td><td><b><?php echo $job_details['contact_email'];?></b></td></tr>
            </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
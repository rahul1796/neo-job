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
}
.list-group-item,.list-group-item-heading
{
    padding: 4px 15px;
}
#employer_job_content_block div.row
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
.small
{
  font-size:0.7em;
  font-weight: bold;
}

</style>
<div class="inner">

<div class="row">
    <div class="col-sm-9 col-md-9 col-lg-9">
    <h4> Job Board </h4>
    <small>
    <ul class="breadcrumb" style="padding: 0px">
      <li><?php echo anchor("employer/dashboard","Dashboard");?> </li>
      <!-- <li><?php //echo anchor($parent_page,$parent_page_title);?> </li> -->
      <li class="active"> Job Board </li>
    </ul>
    </small>
    </div>
    <!-- <div class="col-sm-3 col-md-3 col-lg-3">
        <div class="form-group" style="text-align: right;">
          <a class="btn btn-success btn-sm" href="<?php //echo base_url('employer/post_job/'.$employer_id)?>" style="margin-top: 1em;"><i class="glyphicon glyphicon-plus"></i>Post Job</a>
        </div>
    </div> -->
</div>
<hr/>
<?php
$options_qualification=array(''=>'-Select Qualification-');
 foreach ($min_qualification_list as $row) 
    {
        $options_qualification[$row['id']]=$row['name'];
    }
?>
  <div class="row">
  <div class="col-sm-12 col-md-12 col-lg-12">
      <div class="panel-body">
          <div class="row text-small">
            <form class="form-inline">
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                     <input type="hidden" name="employer_id" value="<?php echo $employer_id;?>" style="visibility: hidden;" size='1'>
                    <div class="form-group">
                      <div class="page_display_log pull-left" style="padding-right: 1.5em; color: green"></div>
                    </div>
                </div>
                <div class="col-sm-9 col-md-9 col-lg-9" style="text-align: right;">   
                    <div class="form-group">
                      <label for="qualification">Qualification:</label>
                      <select class="form-control" name="qualification">
                      <option value="0">-All-</option>
                      <option value="1">non-metric</option>
                      <option value="2">metric</option>
                      <option value="3">Graduate</option>
                      </select>
                    </div>
                    <div class="form-group">
                      <label for="experience">Experience:</label>
                      <select class="form-control" name="experience">
                      <option value="0">-All-</option>
                      <option value="fresher">fresher</option>
                      <option value="zero_two">0-2</option>
                      <option value="three_five">3-5</option>
                      <option value="six_above">6 & above years</option>
                      </select>
                    </div>
                    <div class="form-group" style="padding-left: 0.5em!important">
                      <button type="button" class="btn btn-success btn-sm" onclick="filter_form_submit()"><span class="glyphicon glyphicon-search"></span></button>
                    </div>
                </div>
            </form>  
          </div>
      </div>
      <div id="employer_job_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
        <div id="employer_job_content_block">
        </div>
      <div class="pagination" align="right"></div>
      </div>
    </div>
  </div>
</div>


<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_job_details" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Job Details</h3>
            </div>
            <div class="modal-body job_details">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<!-- //Modal inner -->


<script>
$(document).ready(function() 
{
/*  $(".date_from").datepicker({'changeYear':true,'changeMonth':true});
  $(".date_to").datepicker({'changeYear':true,'changeMonth':true});
  */
  load_employer_job_list_content('');
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
  // Abort all running ajax request
  //print($.xhrPool.length);
  
  $('input[name="sel_page"]').val(0);
  load_employer_job_list_content('');
  return false;
}

$('#employer_job_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_employer_job_list_content($(this).attr('href'));
});

/**
 * ======== Default load function ====================
 */
function load_employer_job_list_content(pagi_url)
{

  var colcount=4;
  var url='';
  var non_metric=0;
  var metric=0; 
  var graduate=0;
  var search_key=0;
  var sel_page = $('input[name="sel_page"]').val();
  var employer_id = $('input[name="employer_id"]').val();
  var experience = $('select[name="experience"]').val();
  var qualification = $('select[name="qualification"]').val();


  if(experience=='')
      experience=0;
  if(qualification!='')
  {
    if(qualification=='1')
      non_metric=1;
    if(qualification=='2')
      metric=1;
    if(qualification=='3')
      graduate=1;
  }

  if(pagi_url == '')
  {
    url = site_url+'employer/employer_job_list/'+employer_id+'/'+non_metric+'/'+metric+'/'+graduate+'/'+experience+'/'+search_key+'/'+sel_page;
  }
  else
    url = pagi_url; 

  $('#employer_job_list_block #employer_job_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
  $('#employer_job_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var employer_job_list_html = '';
    var page_display_log='';
    if(resp==null) 
    {
      employer_job_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_employer_job_list_content(\'\');">Click here to Reload</a>.</div></div>';
    }
    else if(resp.status == 'success')
    {

      employer_job_list_html = employer_job_list_inner_content(resp,colcount);

      $('.pagination').html(resp.pagination);

     // $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");
      
      if(resp.pg_count_msg != undefined) {
        page_display_log=('<span>'+resp.pg_count_msg+'</span>');
      }

    }
    else
    {
      employer_job_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
    }
   
    $('#employer_job_list_block #employer_job_content_block').html(employer_job_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function employer_job_list_inner_content(resp,colcount)
{
  var  job_list_html='';
  var page_no=resp.pg;
    $('input[name="sel_page"]').val(page_no);
    $.each(resp.job_list,function(a,b)
    {
      //var status_flags = ['Inactive','Active','Suspended'];
     /* <button class="btn btn-success btn-sm" onclick="post_job()"><i class="glyphicon glyphicon-plus"></i>Post Job</button>*/
      var slno=(page_no*1+a*1+1);

      job_list_html += '  <div class="row">';
          job_list_html += '  <div class="col-sm-2 col-md-2 vcenter">';
              job_list_html += '<img src='+base_url+'/assets/images/one.jpg '+' onerror=this.src='+"'"+base_url+'assets/images/default.jpg'+"'"+' class=img-thumbnail height=75 width=75>';
          job_list_html += '</div> ';
          job_list_html += '  <div class="col-sm-7 col-md-7">';
              job_list_html += '<p><b>'+b.employer_name+'</b><br>'+b.qualification_pack_name+'</p>'+
                        '<ul>'+
                        '<li>Job Description: '+b.job_desc+'</li>'+
                        '<li>Min Qualification: '+b.min_qualification_name+'</li>'+
                        '<li>Work Experience: '+b.min_experience+'-'+b.max_experience+'</li>'+
                        '<li>Total Openings: '+b.total_openings+'</li>'+
                        '<b><a href="javascript:void(0)" title="Job Details" onclick="job_details('+b.job_id+')">Job Details</a></b>'+  
                        '</ul>';
          job_list_html += '</div> ';
          job_list_html += '  <div class="col-sm-3 col-md-3 vcenter">';
          /*<a class="btn btn-sm btn-primary" href="'+site_url+'employer/scheduled_candidates/'+b.job_id+'/'+b.employer_id+'" title="Candidate list"><i class="glyphicon glyphicon-search"></i> Scheduled Candidates</a>+*/ 
              job_list_html +=  
              '<p class="postedon small">Posted on:'+b.created_on+'</p>';
          job_list_html += '  </div> ';
      job_list_html += '  </div> ';
    });

  return  job_list_html;
}

function job_details(job_id)
{
  var job_detail_url=base_url+'employer/get_job_details/'+job_id;
    //Ajax Load data from ajax
    $.ajax({
        url : job_detail_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var job_detail_list_html='-No data Found-';
            if(data.status)
            {
                var slno=1;
                job_detail_list_html = '<div>';
                job_detail_list_html += 'Job description: <b>';
                job_detail_list_html +=  data.job_description;
                job_detail_list_html += '</b></div>';
                job_detail_list_html += '<div class="row">';
                job_detail_list_html += '<div class="col-sm-12 col-md-12">';
                job_detail_list_html += '<table class="table">';
                job_detail_list_html += '<tr><th>Sl No</th><th>Location</th><th>No of Openings</th><th>Salary</th></tr>';
                 $.each(data.job_detail,function(a,b)
                {
                  job_detail_list_html += '<tr><td>'+slno+'</td><td>'+b.location+'</td><td>'+b.no_of_openings+'</td><td>'+b.salary+'</td></tr>';
                  slno++;
                });
                
                job_detail_list_html += '</table>';
                job_detail_list_html += '</div></div>';

            }
            $('.job_details').html(job_detail_list_html);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_job_details').modal('show'); // show bootstrap modal when complete loaded
}
</script>

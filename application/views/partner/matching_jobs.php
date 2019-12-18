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
#matching_job_content_block div.row
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
  transform: translateY(40%);
}
b
{
  color: #999;
}

.containers > .switch 
{
  margin: 12px auto;
}

.switch {
  position: relative;
  display: inline-block;
  vertical-align: top;
  width: 85px;
  height: 20px;
  padding: 3px;
  background-color: white;
  border-radius: 18px;
  box-shadow: inset 0 -1px white, inset 0 1px 1px rgba(0, 0, 0, 0.05);
  cursor: pointer;
  background-image: -webkit-linear-gradient(top, #eeeeee, white 25px);
  background-image: -moz-linear-gradient(top, #eeeeee, white 25px);
  background-image: -o-linear-gradient(top, #eeeeee, white 25px);
  background-image: linear-gradient(to bottom, #eeeeee, white 25px);
}

.switch-input {
  position: absolute;
  top: 0;
  left: 0;
  opacity: 0;
}

.switch-label {
  position: relative;
  display: block;
  height: inherit;
  font-size: 10px;
  text-transform: uppercase;
  background: #eceeef;
  border-radius: inherit;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.12), inset 0 0 2px rgba(0, 0, 0, 0.15);
  -webkit-transition: 0.15s ease-out;
  -moz-transition: 0.15s ease-out;
  -o-transition: 0.15s ease-out;
  transition: 0.15s ease-out;
  -webkit-transition-property: opacity background;
  -moz-transition-property: opacity background;
  -o-transition-property: opacity background;
  transition-property: opacity background;
}
.switch-label:before, .switch-label:after {
  position: absolute;
  top: 50%;
  margin-top: -.5em;
  line-height: 1;
  -webkit-transition: inherit;
  -moz-transition: inherit;
  -o-transition: inherit;
  transition: inherit;
}
.switch-label:before {
  content: attr(data-off);
  right: 11px;
  color: #aaa;
  text-shadow: 0 1px rgba(255, 255, 255, 0.5);
}
.switch-label:after {
  content: attr(data-on);
  left: 11px;
  color: white;
  text-shadow: 0 1px rgba(0, 0, 0, 0.2);
  opacity: 0;
}
.switch-input:checked ~ .switch-label {
  background: #47a8d8;
  box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 0 3px rgba(0, 0, 0, 0.2);
}
.switch-input:checked ~ .switch-label:before {
  opacity: 0;
}
.switch-input:checked ~ .switch-label:after {
  opacity: 1;
}

.switch-handle {
  position: absolute;
  top: 4px;
  left: 4px;
  width: 18px;
  height: 18px;
  background: white;
  border-radius: 10px;
  box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.2);
  background-image: -webkit-linear-gradient(top, white 40%, #f0f0f0);
  background-image: -moz-linear-gradient(top, white 40%, #f0f0f0);
  background-image: -o-linear-gradient(top, white 40%, #f0f0f0);
  background-image: linear-gradient(to bottom, white 40%, #f0f0f0);
  -webkit-transition: left 0.15s ease-out;
  -moz-transition: left 0.15s ease-out;
  -o-transition: left 0.15s ease-out;
  transition: left 0.15s ease-out;
}
.switch-handle:before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  margin: -6px 0 0 -6px;
  width: 12px;
  height: 12px;
  background: #f9f9f9;
  border-radius: 6px;
  box-shadow: inset 0 1px rgba(0, 0, 0, 0.02);
  background-image: -webkit-linear-gradient(top, #eeeeee, white);
  background-image: -moz-linear-gradient(top, #eeeeee, white);
  background-image: -o-linear-gradient(top, #eeeeee, white);
  background-image: linear-gradient(to bottom, #eeeeee, white);
}
.switch-input:checked ~ .switch-handle {
  left: 69px;
  box-shadow: -1px 1px 5px rgba(0, 0, 0, 0.2);
}

.switch-green > .switch-input:checked ~ .switch-label {
  background: #4fb845;
}

 .table > thead > tr > th, 
 .table > tbody > tr > th, 
 .table > tfoot > tr > th, 
 .table > thead > tr > td, 
 .table > tbody > tr > td, 
 .table > tfoot > tr > td 
 {

        padding: 5px !important; // currently 8px
  }
  span.select2-selection
  {
   width: 30em;
  }
</style>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">

<div class="content-body" style="padding: 10px;">
    <a href="javascript:void(0)" title="Candidate Details" onclick="candidate_details()"><button type="button" class="btn btn-info btn-min-width mr-1 mb-1" style="margin-left: 68%;">Candidate: <?php echo $candidate_detail['name'];?> <?php echo $candidate_detail['mobile'];?></button></a>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-top: -45px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor($parent_page,$parent_page_title);?></a>
                </li>
                  <li class="breadcrumb-item active">Matching Jobs </li>
            </ol>
        </div>
    </div>

    <section id="description" class="card" style="border: none!important;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Matched Jobs</h4></label>
                </div>
                <a href="<?php echo base_url("partner/screening_jobs/".$candidate_detail['candidate_id']);?>" style="float: right; margin-top: -43px;"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-calendar"></i>Jobs Applied for</button></a>
                <div class="card-body">
                    <table class="table table-bordered">
                        <form name="form_matching">
                            <div class="panel-body">

                                <div class="row text-small">
                                    <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                    <input type="hidden" name="candidate_id" value="<?php echo $candidate_detail['candidate_id'];?>" style="visibility: hidden;" size='1'>
                                    <div class="col-sm-3 col-md-3" style="margin-left: 20px;">
                                        <div class="page_display_log" style="color: green"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="matching_job_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                <div id="matching_job_content_block">

                                </div>
                                <div class="pagination" align="right"></div>

                            </div>
                        </form>
                    </table>

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
  load_matching_job_list_content('');
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

function reload_mathcing_jobs()
{
  var pagi_url=''
  var sel_page = $('input[name="sel_page"]').val();
  var candidate_id = $('input[name="candidate_id"]').val();
  if(candidate_id=='')
    candidate_id=0;
  pagi_url = site_url+'partner/matching_jobs_list/'+candidate_id+'/'+sel_page;
  load_matching_job_list_content(pagi_url)
  return false;
}
//==========================================
/**
 * Onsubmit the filter
*/
function filter_form_submit() 
{
  
  $('input[name="sel_page"]').val(0);
  load_matching_job_list_content('');
  return false;
}

$('#matching_job_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_matching_job_list_content($(this).attr('href'));
});

/**
 * ======== Default load function ====================
 */
function load_matching_job_list_content(pagi_url)
{

  var colcount=4;
  var url='';
  var sel_page = $('input[name="sel_page"]').val();
  var candidate_id=$('input[name="candidate_id"]').val();
  
  if(candidate_id=='')
      candidate_id=0;

  if(pagi_url == '')
  {
    url = site_url+'partner/matching_jobs_list/'+candidate_id+'/'+sel_page;
  }
  else
    url = pagi_url; 

  $('#matching_job_list_block #matching_job_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
  $('#matching_job_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var matching_job_list_html = '';
    var page_display_log='';
    if(resp==null) 
    {
      matching_job_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_matching_job_list_content(\'\');">Click here to Reload</a>.</div></div>';
    }
    else if(resp.status == 'success')
    {

      matching_job_list_html = matching_job_list_inner_content(resp,colcount);

      $('.pagination').html(resp.pagination);

     // $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");
      
      if(resp.pg_count_msg != undefined) {
        page_display_log=('<span>'+resp.pg_count_msg+'</span>');
      }

    }
    else
    {
      matching_job_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
    }
   
    $('#matching_job_list_block #matching_job_content_block').html(matching_job_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function matching_job_list_inner_content(resp,colcount)
{
  var  job_list_html='';
  var page_no=resp.pg;
  var candidate_id=$('input[name="candidate_id"]').val();
    $('input[name="sel_page"]').val(page_no);
    $.each(resp.matching_job_list,function(a,b)
    {
      
      var slno=(page_no*1+a*1+1);
      var is_active=(b.job_status_id)?'':'disabled';
     // var job_status_id=(!b.job_status_id)?'<small><b>Apply</b></small> <input type="checkbox" name="job_id[]" class="job_id" value="'+b.id+'" candidate_id="'+candidate_id+'"  "'+is_active+'">':b.job_status;
      var job_status_id=(!b.job_status_id)?'<a class="btn mr-1 mb-1 btn-primary" href="javascript:void(0)" title="Select location" onclick="select_location('+"'"+b.id+"'"+','+"'"+b.n_locations+"'"+')"> Apply</a>':b.job_status;
    
          job_list_html += '  <div class="row">';
          job_list_html += '  <div class="col-sm-2 col-md-2 vcenter">';
          job_list_html += '  <img src='+base_url+'/assets/images/one.jpg '+' onerror=this.src='+"'"+base_url+'assets/images/default.jpg'+"'"+' class=img-thumbnail height=75 width=75>';
          job_list_html += '  </div> ';
          job_list_html += '  <div class="col-sm-7 col-md-7">';
              job_list_html += '<p><b>Employer: '+b.employer_name+'</b></p>'+
                        '<ul>'+
                        '<li><b>Job Description :</b> '+b.job_desc+'</li>'+
                        '<li><b>Work Experience :</b> '+b.min_experience+'-'+b.max_experience+'</li>'+
                        '<li>No of Location: <a href="javascript:void(0)" class="nlocations" title="Job Detail" onclick="job_detail('+"'"+b.id+"'"+','+"'"+b.n_locations+"'"+')"><span class="tag tag tag-info">'+b.n_locations+'</span></a></li>'+
                        '<li><b>Educational Qualification :</b>  '+b.min_education+'</li>'+
                        '</ul>';
          job_list_html += '</div> ';
          job_list_html += '  <div class="col-sm-3 col-md-3 vcenter">';
          
          //job_list_html += '<a class="btn mr-1 mb-1 btn-primary" href="javascript:void(0)" title="Select location" onclick="select_location('+"'"+b.id+"'"+','+"'"+b.n_locations+"'"+')"> Apply</a>';
          job_list_html += job_status_id;
          job_list_html += '<p class="postedon small">Posted on:'+b.created_on+'</p>';
          

          job_list_html += '  </div> ';
      job_list_html += '  </div> ';   

    });

  return  job_list_html;
}

//apply job

/*$(document).on('change', '.job_id', function()
{

  var job_apply_status=0;
  var location_id=0;
  var candidate_id=$(this).attr('candidate_id');
  var job_id=$(this).val();
  var url=base_url+'partner/apply_for_matching_jobs';

     if (this.checked) 
        job_apply_status=1;
  location_id=$('select[name="location_id"]').val();
    // status is changing to the db table
   // alert(location_id);
    $.ajax({
        url : url,
        type: "POST",
        data: {"candidate_id":candidate_id,"job_id":job_id,"job_apply_status":job_apply_status,'location_id':location_id},
        dataType: "JSON",
        success: function(data)
        {
             if(data.status=='success')  //if success close modal and reload ajax table
              exit;
        }
    });
})*/

$(document).on('change', '.job_id', function()
{
    var job_id=$(this).val();
    var job_apply_status=0;
    if (this.checked) 
      job_apply_status=1;

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
            $('input[name=job_id]').val(job_id);
            $('input[name=job_status]').val(job_apply_status);
            $('#modal_job_location').modal('show'); 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
  
})

function select_location(job_id, location_count)
{
   var candidate_id="<?php echo $candidate_detail['candidate_id'];?>";
   if(location_count>0)
   {
      $.ajax({
          url : base_url+"employer/get_job_applied_details/" + job_id+"/"+ candidate_id,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {

             if(data.status) //if success close modal and reload ajax table
              {
                   var job_location_html='';


                   $.each(data.job_applied_details,function(a,b)
                    {
                          if(b.applied_status>0)
                            job_location_html += '<option value="'+b.location_id+'" selected>'+b.location_name+'</option>';
                          else
                            job_location_html += '<option value="'+b.location_id+'">'+b.location_name+'</option>';
                    });
              }
              $('#modal_job_location select').html(job_location_html);
              $('input[name=job_id]').val(job_id);
              $('input[name=candidate_id]').val(candidate_id);
              $('#location_id').select2(
              {
                maximumSelectionLength: 1
              });
              $('#modal_job_location').modal('show'); 
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });
  }
}
$(document).on('click', '.btn_location_ok', function()
{

  var job_apply_status=0;
  var location_id=0;
  var candidate_id=$('input[name=candidate_id]').val();
  var job_id= $('input[name=job_id]').val();
  //var job_apply_status= $('input[name=job_status]').val();
  var url=base_url+'partner/apply_for_matching_jobs';
  location_id=$('select[name="location_id"]').val();
    // status is changing to the db table
   // alert(location_id);
    $.ajax({
        url : url,
        type: "POST",
        data: {"candidate_id":candidate_id,"job_id":job_id,'location_id':location_id},
        dataType: "JSON",
        success: function(data)
        {
             if(data.status=='success')  //if success close modal and reload ajax table
             {
                reload_mathcing_jobs();
             }
        }
    });
})

/*function get_location_id(job_id)
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
}*/

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
                <input type="hidden" name="job_status">
                <div class="modal-body">
                      <input type="hidden" name="job_id">
                      <input type="hidden" name="candidate_id">
                      <div class="form-group row">
                          <label for="language_id" class="col-sm-3 label-control">Select Location<span class="validmark">*</span></label>
                          <div class="col-sm-9">
                              <select name="location_id" id="location_id" class="form-control select2-tags" multiple onchange ="return onlyOneLocation();"><!-- used static table in framework if any doubt please refere framework source code -->

                              </select>
                          </div>
                      </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn_location_ok" data-dismiss="modal">Ok</button>
                </div>
        </div>
    </div>
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

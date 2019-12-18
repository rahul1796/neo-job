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
 /* background-color: #FEFCFF;
  margin-bottom:5px;*/
  padding: 25px;
  border-radius: 10px;
  border-bottom: 1px solid #e1e1e1;
}

.vcenter 
{
  height: auto;
  position: relative;
  transform: translateY(40%);
}

</style>
<div class="content-body" style="padding: 10px;">
   <!-- <a href="javascript:void(0)" onclick="job_details()"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><?php /*echo $job_details['job_desc'];*/?><i class="icon-android-phone-portrait"></i><?php /*echo $job_details['contact_phone'];*/?></button></a>-->
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/assigned_jobs","Jobs");?></a>
                </li>
                <li class="breadcrumb-item active">Scheduled Candidates
                </li>
            </ol>
        </div>
    </div>
    <?php
    $options_qualification=array(''=>'-Select Qualification-');
    foreach ($min_qualification_list as $row)
    {
        $options_qualification[$row['id']]=$row['name'];
    }
    ?>

    <section id="description" class="card" style="border: none!important;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Scheduled Candidates</h4></label>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12 col-md-12 col-lg-12">
                            <form class="form-inline" style="margin: 10px;">
                                <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                <input type="hidden" name="job_id" value="<?php echo $job_id;?>" style="visibility: hidden;" size='1'>
                                <input type="hidden" name="employer_id" value="<?php echo $employer_id;?>" style="visibility: hidden;" size='1'>
                                <div class="form-group">
                                    <div class="page_display_log pull-left" style="padding-right: 1.5em; color: green"></div>
                                </div>
                                <div class="form-group">
                                </div>
                                <div class="form-group">
                                    <label for="email">Qualification:</label>
                                    <select class="form-control" name="qualification">
                                        <option value="">-Select Qualification-</option>
                                        <option value="1">non-metric</option>
                                        <option value="2">metric</option>
                                        <option value="3">Graduate</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="pwd">Experience:</label>
                                    <select class="form-control" name="experience">
                                        <option value="">-Select Experience-</option>
                                        <option value="fresher">fresher</option>
                                        <option value="zero_two">0-2</option>
                                        <option value="three_five">3-5</option>
                                        <option value="six_above">6 & above years</option>
                                    </select>
                                </div>
                                <div class="form-group" style="padding-left: 0.5em!important">
                                    <button type="button" class="btn btn-success btn-sm" onclick="filter_form_submit()"><span class="icon-android-search"></span></button>
                                </div>
                            </form>
                            <hr>

                                <div id="scheduled_candidate_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                    <div id="employer_job_content_block">

                                    </div>
                                    <div class="pagination" align="right"></div>
                                </div>

                        </div>
                    </div>

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
  load_scheduled_candidate_list_content('');
  //check box toggling
  $('.selectex input:checkbox').click(function() 
  {
      $('.selectex input:checkbox').not(this).prop('checked', false);
  });  
  status_resp= load_job_status();

  var date_input=$('input[name="joining_date"]'); //our date input has the name "date"
  var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";

  var options={
                format: 'dd-M-yyyy',
                container: container,
                todayHighlight: true,
                autoclose: true
                };
  date_input.datepicker(options);

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
  load_scheduled_candidate_list_content('');
  return false;
}

$('#scheduled_candidate_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_scheduled_candidate_list_content($(this).attr('href'));
});

function reload_candidate_list()
{
  var pgurl=''
  var sel_page = $('input[name="sel_page"]').val();
  var job_id=$('input[name="job_id"]').val();
  var employer_id = $('input[name="employer_id"]').val();

  if(job_id=='')
    job_id=0;
  if(employer_id=='')
    employer_id=0;
  pgurl = site_url+'employer/scheduled_candidates_list/'+job_id+'/'+employer_id+'/'+sel_page;
  load_scheduled_candidate_list_content(pgurl);
  return false;
}
/**
 * ======== Default load function ====================
 */
function load_scheduled_candidate_list_content(pagi_url)
{

  var colcount=4;
  var url='';
  var non_metric=0;
  var metric=0; 
  var graduate=0;
  var search_key=0;
  var sel_page = $('input[name="sel_page"]').val();
  
  var job_id = $('input[name="job_id"]').val();
  var employer_id = $('input[name="employer_id"]').val();


  if(job_id=='')
      job_id=0;
  if(employer_id=='')
    employer_id=0;

  if(pagi_url == '')
  {
    url = site_url+'employer/scheduled_candidates_list/'+job_id+'/'+employer_id+'/'+sel_page;
  }
  else
    url = pagi_url; 

  $('#scheduled_candidate_list_block #employer_job_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
  $('#scheduled_candidate_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var scheduled_candidate_list_html = '';
    var page_display_log='';
    if(resp==null) 
    {
      scheduled_candidate_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_scheduled_candidate_list_content(\'\');">Click here to Reload</a>.</div></div>';
    }
    else if(resp.status == 'success')
    {

      scheduled_candidate_list_html = scheduled_candidate_list_inner_content(resp,colcount);

      $('.pagination').html(resp.pagination);
    
      if(resp.pg_count_msg != undefined) 
      {
        page_display_log=('<span>'+resp.pg_count_msg+'</span>');
      }

    }
    else
    {
      scheduled_candidate_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
    }
   
    $('#scheduled_candidate_list_block #employer_job_content_block').html(scheduled_candidate_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function scheduled_candidate_list_inner_content(resp,colcount)
{
  var scheduled_candidate_list_html='-No Data Found-';
  var page_no=resp.pg;
    $('input[name="sel_page"]').val(page_no);
    if(resp.status=='success')
    {
      scheduled_candidate_list_html = '';
      $.each(resp.scheduled_candidates_list,function(a,b)
      {
        var slno=(page_no*1+a*1+1);
        var submit_btn_status=(Math.abs(b.status_id)>6)?'disabled':'';
            scheduled_candidate_list_html += '<div class="row">';
            scheduled_candidate_list_html += '  <div class="col-sm-2 col-md-2 vcenter">';
            scheduled_candidate_list_html += '  <img src='+base_url+'/assets/images/one.jpg '+' onerror=this.src='+"'"+base_url+'assets/images/default.jpg'+"'"+' class=img-thumbnail height=75 width=75>';
            scheduled_candidate_list_html += '  </div> ';
            scheduled_candidate_list_html += '  <div class="col-sm-7 col-md-7">';
            scheduled_candidate_list_html += '  <p><b>'+b.name+'</b></p>'+
                                              ' <ul>'+
                                              ' <li>Mobile: '+b.mobile+'</li>'+
                                              ' <li>Work Experience: '+b.total_experience+'</li>'+
                                              ' <li>Educational Qualification: '+b.qualification+'</li>'+
                                              ' <li>Age: '+b.age+'</li>'+
                                              ' </ul>';
            scheduled_candidate_list_html += '  </div> ';
            scheduled_candidate_list_html += '  <div class="col-sm-3 col-md-3">';
            scheduled_candidate_list_html += '  <b>Status: <span class="job_status_label"> '+b.job_status+'</span></b>';
            scheduled_candidate_list_html += '  <select class="form-control job_status" style="margin-top: 10px;" name="select_'+b.candidate_job_id+'"  '+submit_btn_status+' onchange="dome(this,'+b.candidate_job_id+')">';
            scheduled_candidate_list_html += get_job_status(status_resp,b.status_id);
            scheduled_candidate_list_html += '  </select>';
            scheduled_candidate_list_html += '  <a class="btn btn-block btn-primary '+submit_btn_status+'" href="javascript:void(0)" title="Submit" onclick="submit_candidate_status('+b.candidate_job_id+')" style="margin-top: 10px;" >Submit</a>';
            scheduled_candidate_list_html += '  <p class="postedon small">Posted on:'+b.created_on+'</p>';
            scheduled_candidate_list_html += '  </div> ';
        scheduled_candidate_list_html += '  </div> ';
    });
  }
  return  scheduled_candidate_list_html;
}



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

function load_job_status()
{
  var resp_data="";
  var scheduled=4;
  $.ajax({
      url : site_url+'partner/get_jobstatus_list/'+scheduled,
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
function dome(element,candidate_job_id)
{
  var status=$(element).val();// will give you what you are looking for
  if(status==7)
  {
    $('#candidate_job_id').val(candidate_job_id);
    $('#job_status').val(status);
    $('#modal_form_joining').modal('show');
  }
}
function save_status()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;
    url = base_url+"employer/update_joining_status";

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_joining').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form_joining').modal('hide');
                reload_candidate_list();
            }
            else
            {

                $.each(data.errors, function(key, val) 
                {
                    //$('[name="'+ key +'"]', '#form_center').closest('input').find('.help_block').html(val);
                    $('[name="'+ key +'"]', '#form_joining').closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
                });
             //   $("#form_center").valid();
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

</script>

<!-- Bootstrap modal -->
<div class="inner">
<div class="modal fade" id="modal_form_joining" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Candidate Joining</h3>
            </div>
            <div class="modal-body form">
                <form name="form_joining" id="form_joining" class="form-horizontal">
                    <div class="form-body">
                        <input type="hidden" name="candidate_job_id" id="candidate_job_id" size="1">
                        <input type="hidden" name="job_status" id="job_status" size="1">
                        <div class="form-group row">
                            <label for="joining_date" class="col-sm-3 label-control">Joining Date<span class="validmark">*</span></label>
                            <div class="col-sm-9">
                                <div class='input-group date' id='date_of_birth'>
                                <input type="text" class="form-control" name="joining_date" placeholder="DD-Jan-YYYY">
                                </div>
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="joining_salary" class="col-sm-3 label-control">Joining Salary<span class='validmark'>*</span></label>
                             <div class="col-sm-9">
                                  <input name="joining_salary" placeholder="Joining Salary" class="form-control" type="number">
                                  <span class="error_label"></span>
                              </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save_status()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>

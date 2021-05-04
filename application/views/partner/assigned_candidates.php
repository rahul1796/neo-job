<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
<style>
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  Show Aavailable Job List
 * @date  Nov_2016
*/
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
<div class="content-body" style="padding: 10px;">
     <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-top: -25px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Assigned Candidates
                </li>
            </ol>
        </div>
    </div>
    <section id="description" class="card" style="border: hidden;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Assigned Candidate List</h4></label>
                    <div class="panel-body" style="float: right;">

                        <div class="col-sm-12">
                            <form class="form-inline">
                                <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                <input type="hidden" name="user_id" value="<?php echo $user_id;?>" style="visibility: hidden;" size='1'>
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
                                        <option value="1">fresher</option>
                                        <option value="2">0-2</option>
                                        <option value="3">3-5</option>
                                        <option value="4">6 & above years</option>
                                    </select>
                                </div>
                                <div class="form-group" style="padding-left: 0.5em!important">
                                    <button type="button" class="btn btn-success btn-sm" onclick="filter_form_submit()"><span class="icon-search"></span></button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-sm-3">
                        <div class="page_display_log pull-left" style=" color: green"></div>
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
function filter_form_submit() 
{
  
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
  var user_id= $('input[name="user_id"]').val();
  var experience= $('select[name="experience"]').val();
  var qualification = $('select[name="qualification"]').val();

  experience = isNaN(experience)?0:experience;
  qualification = isNaN(qualification)?0:qualification;

/*  if(search_key=='')
    search_key = 0;

  search_key=encodeURIComponent(search_key);*/
  if(pagi_url == '')
  {
    url = site_url+'partner/assigned_candidates_list/'+user_id+'/'+experience+'/'+qualification+'/'+sel_page;
  }
  else
    url = pagi_url; 

  $('#candidate_list_block #candidate_content_block').html('<table><tr><td colspan="'+colcount+'"><div align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></td></tr></table>');
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
    $.each(resp.rdata.candidate_list,function(a,b)
    {

      $('input[name="sel_page"]').val(resp.pg);
      //var candidate_status_flags = ['Inactive','Active','Suspended'];
      var slno=(resp.pg*1+a*1+1);
      var kyc=(b.is_aadhar)?'<strong>Aadhar:</strong> '+b.aadhaar_num:'<strong>'+b.id_type+'</strong>: '+b.id_number;
      var is_aadhar_verified_flags = (b.is_aadhar_verified)?'<img src="'+base_url+'/assets/images/verified_image.jpeg" height="75" width="75">':'<a class="btn mr-1 mb-1 btn-warning" href="javascript:void(0)" onclick="aadhar_verify('+b.id+',1)">Verify Aadhar</a>';
          candidate_list_html += '<div class="row">';
          candidate_list_html += '<div class="col-sm-2 col-md-2 vcenter">';
          candidate_list_html += '<img src='+base_url+'/adm-assets/images/portrait/small/blank_avatar.png '+' onerror=this.src='+"'"+base_url+'adm-assets/images/portrait/small/blank_avatar.png'+"'"+' class=img-thumbnail height=75 width=75>';
          candidate_list_html += '</div> ';
          candidate_list_html += '<div class="col-sm-7 col-md-7">';
          candidate_list_html += '<p><b class="text-uppercase text-success">'+b.name+'</b></p>'+
                        '<ul>'+
                        '<li><strong>Work Experience : </strong>'+b.total_experience+'</li>'+
                        '<li><strong>Educational Qualification : </strong> '+b.qualification+'</li>'+
                        '<li><strong>Dob : </strong>'+b.dob+' <span class="leave_space"> <strong>Gender :</strong></span>' +b.gender_code+'</li>'+
                        '<li>'+kyc+
                        '<li><strong> Mobile : </strong>'+b.mobile+'<span class="leave_space"> <strong>Email :</strong> </span>' +b.email+'</li>'+
                        '</ul>';
          candidate_list_html += '</div> ';
          candidate_list_html += '  <div class="col-sm-3 col-md-3 action">';
          // candidate_list_html += '<p class="vcenter small"><a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Send SMS" onclick="send_sms('+"'"+b.id+"'"+')"><i class="glyphicon glyphicon-phone"></i></a> ';
              //candidate_list_html += '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Favourite" onclick="do_favourite('+"'"+b.id+"'"+')"><i class="glyphicon glyphicon-star"></i></a></p>';
          /*candidate_list_html += '<a class="btn mr-1 mb-1 btn-danger" href="'+base_url+'partner/edit_candidate/'+b.id+'/'+b.referer_id+'" title="Modify Candidate Details"><i class="icon-edit"></i></a></p>';*/
         /* candidate_list_html += is_aadhar_verified_flags;  */
          candidate_list_html += '<p class="vcenter small">Posted on: '+b.created_on+'</p>';
          candidate_list_html += '  </div> ';
          candidate_list_html += '  </div> ';   
    });

  return candidate_list_html;
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

</script>


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
.action
{
padding: 0px 55px;
}
.postedon
{
   margin: 65px 0px 0px -55px;
}

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
    border: 2px solid #bba5bf;
}

.checkbox:checked:before {
    -webkit-transform: rotate(-45deg);
    -moz-transform: rotate(-45deg);
    -ms-transform: rotate(-45deg);
    -o-transform: rotate(-45deg);
    transform: rotate(-45deg);
    height: .5rem;
    border-color: #9a12b3;
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
.nlocations{color: blue; font-weight: bold;}
</style>

<div class="content-body" style="padding: 10px;">
    <!--<a href="<?php /*echo base_url("pramaan/add_sourcing_partner/$district_coordinator_id")*/?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add Sourcing Partner</button></a>-->
    <!-- File export table -->
    <!--<div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php /*echo anchor("pramaan/dashboard","Dashboard");*/?></a>
                </li>
                <li class="breadcrumb-item active">Job List
                </li>
            </ol>
        </div>
    </div>-->
    <section id="description">
       <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Jobs List</h4></label>
                    <div class="page_display_log pull-right" style="padding-right: 10px; color: green"></div>
                </div>
                <div class="card-body">
                    <div class="card-block">
                        <div id="job_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                            <table class="table b-t text-small"><!-- used static table in framework if any doubt please refere framework source code -->
                                <tbody>

                                </tbody>
                            </table>
                            <div class="pagination" align="right"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Filter by</h4></label>
                </div>
                <div class="card-body">
                    <div class="card-block">
                        <form id="job_list" method='post' onsubmit="return false;"  role="search">
                            <div class="input-group" style="padding-bottom: 5px;">
                                <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                <input type="text" name="search_key" value="" placeholder="Job Role, Location, Salary per anum" class="input-sm form-control" onkeydown="if (event.keyCode == 13) filter_form_submit()" />
                                <span class="input-group-addon"  onclick="filter_form_submit()"><i class="icon-android-search"></i></span>
                            </div>

                            <a href="javascript:void(0)" class="list-group-item active" style="background-color: #ddd!important;border-color:#ddd;color:black;  padding: 3px 15px;">
                                <h5 class="list-group-item-heading">Qualification</h5>
                            </a>

                            <ul class="list-group">
                                <li class="list-group-item selecte">
                                    <input type="checkbox" name="non_metric" class="education checkbox" value="1"> Non metric <span class="tag tag tag-info float-xs-right"><?php echo $job_statistics['non_metric']?></span></li>
                                <li class="list-group-item selecte">
                                    <input type="checkbox" name="metric" class="education checkbox" value="1"> metric <span class="tag tag tag-info float-xs-right">
      <?php echo $job_statistics['metric']?></span></li>
                                <li class="list-group-item selecte">
                                    <input type="checkbox" name="graduate" class="education checkbox" value="1"> Graduate <span class="tag tag tag-info float-xs-right"><?php echo $job_statistics['gradute']?></span></li>
                            </ul>
                            <a href="javascript:void(0)" class="list-group-item active" style="background-color: #ddd!important;border-color:#ddd;color:black; padding: 3px 15px;">
                                <h5 class="list-group-item-heading">Experience</h5>
                            </a>
                            <ul class="list-group">
                                <li class="list-group-item selectex">
                                    <input type="checkbox" class="experience checkbox" value="notapplicable" /> Not Applicable <span class="tag tag tag-info float-xs-right"><?php echo $job_statistics['notapplicable']?></span></li>
                                <li class="list-group-item selectex">
                                    <input type="checkbox" class="experience checkbox" value="fresher" /> Fresher <span class="tag tag tag-info float-xs-right"><?php echo $job_statistics['fresher']?></span></li>
                                <li class="list-group-item selectex">
                                    <input type="checkbox" class="experience checkbox" value="zero_two"/> 0-2 years <span class="tag tag tag-info float-xs-right"><?php echo $job_statistics['zero_two_experience']?></span></li>
                                <li class="list-group-item selectex">
                                    <input type="checkbox" class="experience checkbox" value="three_five" /> 3-5 years <span class="tag tag tag-info float-xs-right"><?php echo $job_statistics['three_five_experience']?></span></li>
                                <li class="list-group-item selectex">
                                    <input type="checkbox" class="experience checkbox" value="six_above" /> 6 & above years <span class="tag tag tag-info float-xs-right"><?php echo $job_statistics['six_above_experience']?></span></li>
                            </ul>

                        </form>
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
  load_job_list_content('');
  //check box toggling
  $('.selectex input:checkbox').click(function() 
  {
      $('.selectex input:checkbox').not(this).prop('checked', false);
  }); 

  $('.selectex').on('click','.experience',function(e) 
  {
      filter_form_submit();
  });

  $('.selecte').on('click','.education',function(e) 
  {
      filter_form_submit();
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
  load_job_list_content('');
  return false;
}

$('#job_list_block .pagination').on('click','a',function(e)
{
  e.preventDefault();
  load_job_list_content($(this).attr('href'));
});

/**
 * ======== Default load function ====================
 */
function load_job_list_content(pagi_url)
{

  var colcount=4;
  var url='';
  var sel_page = $('input[name="sel_page"]').val();
  
  var non_metric = $('input[name=non_metric]:checked').val();
  var metric = $('input[name=metric]:checked').val();
  var graduate = $('input[name=graduate]:checked').val();
  var experience =$('.experience:checked').val();
 
  var search_key = $('input[name="search_key"]').val();
  non_metric = isNaN(non_metric)?0:non_metric;
  metric = isNaN(metric)?0:metric;
  graduate = isNaN(graduate)?0:graduate;



  if(search_key=='')
    search_key = 0;
  if(!experience)
      experience=0;
  search_key=encodeURIComponent(search_key);
  if(pagi_url == '')
  {
    url = site_url+'pramaan/job_list/'+non_metric+'/'+metric+'/'+graduate+'/'+experience+'/'+search_key+'/'+sel_page;
  }
  else
    url = pagi_url; 

  $('#job_list_block tbody').html('<tr><td colspan="'+colcount+'"><div align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'/adm-assets/images/icons/default.gif'+'"></div></td></tr>');
  $('#job_list_block .pagination').html('');
  $.getJSON(url,'',function(resp)
  {
    var job_list_html = '';
    var page_display_log='';
    if(resp==null) 
    {
      job_list_html='<tr><td colspan="'+colcount+'" align="center"><div>Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_job_list_content(\'\');">Click here to Reload</a>.</div></td></tr>';
    }
    else if(resp.status == 'success')
    {
      job_list_html = job_list_inner_content(resp,colcount);
      $('.pagination').html(resp.pagination);

      $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");
      
      if(resp.pg_count_msg != undefined) {
        page_display_log=('<span>'+resp.pg_count_msg+'</span>');
      }

    }
    else
    {
      job_list_html += '<tr><td colspan="'+colcount+'" align="center">'+resp.message+'</td></tr>';
    }
   
    $('#job_list_block tbody').html(job_list_html);
    $('.page_display_log').html(page_display_log);

  });
}

function job_list_inner_content(resp,colcount)
{
  var job_list_html='';
    $.each(resp.job_list,function(a,b)
    {

      $('input[name="sel_page"]').val(resp.pg);
      //var status_flags = ['Inactive','Active','Suspended'];
      var slno=(resp.pg*1+a*1+1);
      job_list_html += '<tr><td>';

                  job_list_html += '  <div class="card-body collapse in">';
                  job_list_html += '  <div class="card-block">';
                 job_list_html += '  <div class="col-sm-12" style="margin-top: -9%;">';
                 job_list_html += '  <div class="card-footer text-muted mt-2">';
                job_list_html += '<a class="btn btn-success btn-block float-xs-right" style=" width: 20%;" href="javascript:void(0)" title="Edit" onclick="apply_job('+"'"+b.job_id+"'"+')"><i class="icon-checkmark"></i> Apply</a>'
            ;
             job_list_html += '</div> ';
                job_list_html += '</div> ';
                  job_list_html += '  <div class="col-sm-2" style="margin-top: -2%">';
                  job_list_html += '<img src='+base_url+'/adm-assets/images/portrait/small/BlankAvatar_1.png '+' onerror=this.src='+"'"+base_url+'/adm-assets/images/portrait/small/BlankAvatar_1.png'+"'"+' class=img-thumbnail height=75 width=75>'+ '<span><small>Posted on:'+b.created_on+'</small></span>';
                  job_list_html += '</div> ';
                  job_list_html += '  <div class="col-sm-10" style="margin-top: -2%">';
                  job_list_html += '<p><b>'+b.qualification_pack_name+'</b></p>'+
                                   '<ul>'+
                                   '<li>Job description: '+b.job_desc+'</li>'+
                                   '<li>No of Location: <a href="javascript:void(0)" class="nlocations" title="Job Detail" onclick="job_detail('+"'"+b.job_id+"'"+','+"'"+b.n_locations+"'"+')"><span class="tag tag tag-info">'+b.n_locations+'</span></a></li>'+
                                   '<li>Work Experience : '+b.min_experience+'-'+b.max_experience+' (Yrs)</li>'+
                                   '<li>Educational Qualification :'+b.min_qualification_name+'</li>'+
                                   '</ul>';
                  job_list_html += '</div> ';

                  job_list_html += '</div> ';
                  job_list_html += '</div> ';

                  job_list_html += '</td></tr>';
    });

  return job_list_html;
}

//apply modal

function apply_job(id)
{
    apply_method = 'apply';
    $('#form-apply')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('[name="id"]').val(id);
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Apply for a Job'); // Set Title to Bootstrap modal title
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
        url = base_url+"pramaan/apply_job";
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
                $('#modal_form').modal('hide');
                $('#form-apply')[0].reset();
/*                flashAlert(data.msg_info);*/
                swal({
                        title: "",

                        text: data.msg_info + "!",
                        confirmButtonColor: "#5cb85c",
                        confirmButtonText: 'OK'
                    });
            }
            else
            {
                $.each(data.errors, function(key, val) 
                {
                    //$('[name="'+ key +'"]', '#form_center').closest('input').find('.help_block').html(val);
                    $('[name="'+ key +'"]', "#form-apply").closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
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

</script>
<!-- Bootstrap modal -->
<div class="modal fade text-xs-left" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h3 class="modal-title" id="myModalLabel34">FILL IN THE FORM BELOW</h3>
            </div>
             <form id="form-apply" class="form-horizontal" method="post">
                <div class="modal-body">
                    <div class="form-group row">
                        <input name="id" type="hidden" >
                        <label class="col-md-2 label-control" for="projectinput1">Name</label>
                        <div class="col-md-10">
                            <input name="firstname" placeholder="First Name" class="form-control" type="text" maxlength="100">
                            <span class="error_label"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 label-control" for="projectinput1">Email</label>
                        <div class="col-md-10">
                            <input name="email" placeholder="Email Address" class="form-control" type="text" maxlength="50">
                            <span class="error_label"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 label-control" for="projectinput1">Mobile</label>
                        <div class="col-md-10">
                            <input name="mobile" placeholder="Mobile" class="form-control" type="text" maxlength="12">
                            <span class="error_label"></span>
                        </div>
                    </div>
                  </div>
                <div class="modal-footer">
                    <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                </div>
            </form>
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

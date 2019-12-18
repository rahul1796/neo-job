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
table th,table td {
    padding:5px!important;
}
.select2
{
 width: 100% !important;
}
</style>
<!--<script src="<?php /*echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'*/?>" type="text/javascript"></script>
<script src="<?php /*echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'*/?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php /*echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'*/?>">-->
<div class="content-body" style="overflow-x: hidden !important;">
    <?php
/*    $option_employers=array(''=>'-Select Employer-');

    foreach ($employer_list as $row)
    {
        $option_employers[$row['id']]=$row['name'];
    }*/

/*    $option_locations=array(''=>'-Select Location-');
    foreach ($location_list as $row)
    {
        $option_locations[$row['id']]=$row['name'];
    }*/
    $executives_options=array();
    foreach ($executive_list as $row)
    {
        $executives_options[$row['id']]=$row['name'];
    }

    ?>
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/job_assignment","Job Assignment");?></a>
                </li>
                <li class="breadcrumb-item active">Job Assignment Detail
                </li>
            </ol>
        </div>
    </div>

    <section id="configuration">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Job Assignment Detail</h4>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">
                            <div id="job_assignment_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                <div id="job_content_block">
                                <table class="table table-striped b-t text-small"><!-- used static table in framework if any doubt please refere framework source code -->
                                  <thead>
                                        <tr><th>Sl No</th><th>Job Detail</th><th>Location</th><th>Salary</th><th>Rec. Sup. Executive</th><th>Assignment Date</th><th>Action</th></tr>
                                  </thead>
                                  <tbody>
                                    <?php 
                                      if($job_detail_list)
                                      {
                                        foreach($job_detail_list as $i=>$r)
                                        {
                                        ?>
                                        <tr>
                                          <td><?php echo ($i+1); ?></td>
                                          <td>Employer: <b><?php echo $r['employer_name']; ?></b><br>
                                              Qualification Pack: <b><?php echo $r['qualification_pack_name']; ?></b><br>
                                              No of Openings: <b><?php echo $r['no_of_openings']; ?></b></td>
                                          <td><?php echo $r['location_name']; ?></td>
                                          <td><?php echo $r['salary']; ?></td>
                                          <td><?php echo $r['rec_sup_exec_name']; ?></td>
                                          <td><?php echo $r['assignment_date']; ?></td>
                                          <td>
                                                <?php 
                                                  if($r['assignment_status'])
                                                  {
                                                ?>
                                                    <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="assignment(<?php echo $r['id']?>,1)">Reassign</a>
                                                <?php
                                                  } 
                                                  else
                                                  {
                                                ?>
                                                    <a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="assignment(<?php echo $r['id']?>,0)">Assign</a>
                                                <?php
                                                  } 
                                                ?>
                                          </td>
                                        </tr>
                                        <?php 
                                        }
                                      }
                                    ?>

                                    </tbody>
                                </table>
                                </div>
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
  //load_job_assignment_content('');
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

/**
 * ======== Default load function ====================
 */
/*function load_job_assignment_content(pagi_url)
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
}*/

/*function job_assignment_inner_content(resp,colcount)
{
  var  job_list_html='';
  var page_no=resp.pg;
    $('input[name="sel_page"]').val(page_no);
    //var status_flags = ['<a class="button button-success btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',0)">Assign</a>','<a class="button button-primary btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',1)">Reassign</a>'];
    var job_list_html = '<table class="table table-striped" style="width:100%;">';
        job_list_html += '     <tr><th nowrap>Sl No</th><th nowrap>Employer Details</th><th>Experience</th><th>Executive Name</th><th>Assignment Date</th><th>Created on</th><th>Assignment</th>';
    $.each(resp.job_list,function(a,b)
    {
      var slno=(page_no*1+a*1+1);
      var status_flags = (b.assignment_status)?'<a class="btn btn-primary btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',1)">Reassign</a>':'<a class="btn btn-success btn-sm" href="javascript:void(0)" onclick="assignment('+b.job_id+',0)">Assign</a>';
                  job_list_html += '     <tr><td>'+slno+'</td>';
                  job_list_html += '      <td><b class="text-uppercase">'+b.employer_name+'</b> <br>Phone: '+b.contact_phone+'<br>Desc.: '+b.job_desc+'<br>No of Locations: '+'<a href="javascript:void(0)" class="nlocations" title="Job Detail" onclick="job_detail('+"'"+b.job_id+"'"+','+"'"+b.n_locations+"'"+')"><span class="tag tag tag-info">'+b.n_locations+'</span></a>'+'</td>';
                   
                 /* job_list_html += '      <td>'+b.job_desc+'</td>';
                  job_list_html += '      <td>'+b.job_location+'</td>';*/
/*                  job_list_html += '      <td>'+b.min_salary+'-'+b.max_salary+'</td>';*/
 /*                 job_list_html += '      <td>'+b.min_experience+'-'+b.max_experience+'</td>';
                  job_list_html += '      <td>'+b.rec_sup_exec_name+'</td>';
                  job_list_html += '      <td>'+b.assignment_date+'</td>';
                  job_list_html += '      <td>'+b.created_on+'</td>';
                  job_list_html += '      <td nowrap>'+status_flags+'</td><tr>';
    });
   job_list_html += '</table>';
  return  job_list_html;
}*/

function assignment(job_id)
{
    $('#form_executive_assignment')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : base_url+"pramaan/get_job_detail_by_id/" + job_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status=='success')
            {
              var job_data=data.job_rec;
              var location_list_html = '';
                $('[name="id"]').val(job_data.id);
                $('[name="job_id"]').val(job_data.job_id);
                $('[name="job_desc"]').val(job_data.job_desc);
                $('[name="executive_id"]').val(job_data.rec_sup_exec_id);
                $('[name="location_name"]').val(job_data.location_name);
                $('[name="employer"]').val(job_data.employer_name);
               /* var loactions= (job_data.location_list.slice(1, -1)).split(",");
                $.each(loactions,function(i,itm)
                {

                    var location_id_name=itm.split("-");
                    location_list_html += '<option value="'+location_id_name[0]+'" > '+location_id_name[1]+'</option>';
                });
                $('select[name="location_id[]"]').html(location_list_html);
                $('#location_id').select2();*/
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Support Executive Assignment'); // Set title to Bootstrap modal title
                // $('[name="phone"]').datepicker('update',data.phone);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save()
{
    var url;
    url = base_url+"pramaan/save_assignment";
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_executive_assignment').serialize(),
        dataType: "JSON",
        success: function(data)
        {
            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                location.reload(true);
                flashAlert(data.msg_info);
            }
            else
            {
                $.each(data.errors, function(key, val) 
                {

                    $('[name="'+ key +'"]', '#form_associates').closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
                });
             //   $("#form_center").valid();
            }
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
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Recruitment Support Coordinator Form</h3>
            </div>
                <div class="modal-body form">
                <form action="#" id="form_executive_assignment" class="form-horizontal">
                    <input type="hidden" name="id"/>
                    <div class="form-body">
                        <div class="form-group row">
                            <label class="label-control col-md-3">Job Id</label>
                            <div class="col-md-9">
                                <input name="job_id" placeholder="job_id" class="form-control" type="text" disabled>
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Job description</label>
                            <div class="col-md-9">
                                <input name="job_desc" class="form-control" type="text" disabled>
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Job Location</label>
                            <div class="col-md-9">
                                <input name="location_name" placeholder="location_name" class="form-control" type="text" disabled>
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Employer</label>
                             <div class="col-md-9">
                                  <input name="employer" placeholder="Employer" class="form-control" type="text" disabled>
                                  <span class="error_label"></span>
                              </div>
                        </div>

                        <div class="form-group row">
                            <label class="label-control col-md-3">Support Executive<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                               <?php echo form_dropdown('executive_id',$executives_options,'','id="executive_id" class="form-control" ');?>
                                <span class="error_label"></span>
                            </div>
                        </div>
                       <!-- <script>

                            $('#executive_id').select2();

                        </script>-->
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>

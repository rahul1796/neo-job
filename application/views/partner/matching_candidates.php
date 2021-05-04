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
#matching_candidates_content_block div.row
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
</style>
<div class="content-body" style="padding: 10px;">

    <a href="javascript:void(0)" title="Job Details" onclick="job_details()"><button type="button" class="btn btn-info btn-min-width mr-1 mb-1" style="margin-left: 80%;">Job Details:  <?php echo $job_details['contact_phone'];?></button></a>
     <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-top: -45px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("partner/assigned_jobs","Assigned Jobs");?></a>
                </li>
                  <li class="breadcrumb-item active">Matching Candidates </li>
            </ol>
        </div>
    </div>

    <section id="description" class="card" style="border: none!important;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Matched Candidates</h4></label>
                </div>
                <a href="<?php echo base_url("partner/schedule_candidates/".$job_details['job_id']."/".$location_id);?>" style="float: right; margin-top: -43px;"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-calendar"></i>Candidates Applied for</button></a>
                <div class="card-body">
                    <table class="table table-bordered">
                        <form name="form_matching">
                            <div class="panel-body">

                                <div class="row text-small">
                                    <input type="hidden" name="sel_page" value="0" style="visibility: hidden;" size='1'>
                                    <input type="hidden" name="job_id" value="<?php echo $job_details['job_id']?>" style="visibility: hidden;" size='1'>
                                    <div class="col-sm-3 col-md-3" style="margin-left: 20px;">
                                        <div class="page_display_log" style="color: green"></div>
                                    </div>
                                </div>
                            </div>
                            <div id="matching_job_list_block" class="page_content table-responsive" style="overflow-x: hidden;">
                                <div id="matching_candidates_content_block">

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
    var location_id="<?php echo $location_id?>";
    $(document).ready(function()
    {
        /*  $(".date_from").datepicker({'changeYear':true,'changeMonth':true});
         $(".date_to").datepicker({'changeYear':true,'changeMonth':true});
         */
        load_matching_candidate_list_content('');
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

    function reload_mathcing_candidates()
    {

        var pagi_url=''
        var sel_page = $('input[name="sel_page"]').val();
        var job_id = $('input[name="job_id"]').val();

        if(job_id=='')
            job_id=0;
        pagi_url = site_url+'partner/matching_candidate_list/'+job_id+'/'+location_id+'/'+sel_page;
        load_matching_candidate_list_content(pagi_url);
        return false;
    }
    //==========================================
    /**
     * Onsubmit the filter
     */
    function filter_form_submit()
    {
        // Abort all running ajax request
        //print($.xhrPool.length);

        $('input[name="sel_page"]').val(0);
        load_matching_candidate_list_content('');
        return false;
    }

    $('#matching_candidates_list_block .pagination').on('click','a',function(e)
    {
        e.preventDefault();
        load_matching_candidate_list_content($(this).attr('href'));
    });

    /**
     * ======== Default load function ====================
     */
    function load_matching_candidate_list_content(pagi_url)
    {
        var colcount=4;
        var url='';
        var sel_page = $('input[name="sel_page"]').val();
        var job_id=$('input[name="job_id"]').val();

        if(job_id=='')
            job_id=0;

        if(pagi_url == '')
        {
            url = site_url+'partner/matching_candidate_list/'+job_id+'/'+location_id+'/'+sel_page;
        }
        else
            url = pagi_url;

        $('#matching_candidates_list_block #matching_candidates_content_block').html('<div class="row"><div class="col-sm-12 col-md-12 col-lg-12" align="center" style="margin:5px;padding:5px;"><img src="'+base_url+'assets/images/loading_bar.gif'+'"></div></div>');
        $('#matching_candidates_list_block .pagination').html('');
        $.getJSON(url,'',function(resp)
        {
            var matching_candidates_list_html = '';
            var page_display_log='';
            if(resp==null)
            {
                matching_candidates_list_html='<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">Unable to load the content, Please <a href="javascript:void(0)" onclick="return load_matching_candidate_list_content(\'\');">Click here to Reload</a>.</div></div>';
            }
            else if(resp.status == 'success')
            {

                matching_candidates_list_html = matching_candidates_list_inner_content(resp,colcount);

                $('.pagination').html(resp.pagination);

                // $('.blk_execution_time').html("<small>Loaded in <b>"+resp.execution_time+"</b> Sec</small>");

                if(resp.pg_count_msg != undefined) {
                    page_display_log=('<span>'+resp.pg_count_msg+'</span>');
                }

            }
            else
            {
                matching_candidates_list_html += '<div class="row"><div class="col-sm-12 col-md-12 col-lg-12"  align="center">'+resp.message+'</div></div>';
            }
            $('#matching_candidates_content_block').html(matching_candidates_list_html);
            $('.page_display_log').html(page_display_log);

        });
    }

    function matching_candidates_list_inner_content(resp,colcount)
    {
        var  candidates_list_html='';
        var page_no=resp.pg;
        $('input[name="sel_page"]').val(page_no);
        $.each(resp.matching_candidate_list,function(a,b)
        {
            //var status_flags = ['Inactive','Active','Suspended'];
            var slno=(page_no*1+a*1+1);
            // var job_status_detail=(!b.job_status_id)?'<small><b>Apply</b></small> <input type="checkbox" name="candidate_id[]" class="candidate_id" value="'+b.id+'">':b.job_status;
            var job_status_detail=(!b.job_status_id)?'<div class="containers"><label class="switch"><input type="checkbox" class="switch-input candidate_id" name="candidate_id" value="'+b.id+'"><span class="switch-label" data-on="Applied" data-off="Apply"></span><span class="switch-handle"></span></label></div>':b.job_status;



            candidates_list_html += ' <div class="row">';
            candidates_list_html += ' <div class="col-sm-2 col-md-2 vcenter">';
            candidates_list_html += ' <img src='+base_url+'assets/images/user_icon.png '+' onerror=this.src='+"'"+base_url+'assets/images/default.jpg'+"'"+' class=img-thumbnail height=75 width=75>';
            candidates_list_html += ' </div> ';
            candidates_list_html += ' <div class="col-sm-7 col-md-7">';
            candidates_list_html += ' <p><b>'+b.name+'</b></p>'+
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
            candidates_list_html += '  <div class="col-sm-3 col-md-3 vcenter">';
            /* candidates_list_html += '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="select_candidate('+"'"+b.o_job_id+"'"+')"><i class="glyphicon glyphicon-pencil"></i> Apply</a>'+
             '<p class="postedon small">Posted on:'+b.created_on+'</p>';*/
            candidates_list_html += job_status_detail+
                '<p class="postedon small">Posted on: '+b.created_on+'</p>';
            candidates_list_html += '  </div> ';
            candidates_list_html += '  </div> ';

        });

        return  candidates_list_html;
    }

    //apply modal

    $(document).on('change', '.candidate_id', function()
    {

        var job_apply_status=0;
        var candidate_id=$(this).val();
        var job_id=$('input[name=job_id]').val();
        var location_id="<?php echo $job_details['location_id'];?>"
        var url=base_url+'partner/apply_for_matching_jobs';
        if (this.checked)
            job_apply_status=1;
        // status is changing to the db table
        $.ajax({
            url : url,
            type: "POST",
            data: {"candidate_id":candidate_id,"job_id":job_id,"job_apply_status":job_apply_status,"location_id":location_id},
            dataType: "JSON",
            success: function(data)
            {
                if(data.status=='success') //if success close modal and reload ajax table
                //  reload_mathcing_candidates();
                    exit;
            }
        });
    })

    function job_details()
    {
        $('#modal_form_job').modal('show'); // show bootstrap modal when complete loaded
    }
</script>


<!-- Bootstrap modal -->
<div class="inner">
    <div class="modal fade" id="modal_form_job" role="dialog">
        <div class="modal-dialog modal-sm" style="width:100%;">
            <div class="modal-content" style="width:150%;">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Job Details</h3>
                </div>
                <div class="modal-body form">
                    <table style="font-size: 12px;">
                        <tr><td width="30%">Employer Name:</td><td><b><?php echo ucfirst ($job_details['employer_name']);?></b></td></tr>
                        <tr><td>Job Description:</td><td><b><?php echo $job_details['job_desc'];?></td></tr>
                        <tr><td>Job Category Name:</td><td><b><?php echo $job_details['job_category_name'];?></b></td></tr>
                        <tr><td>No. of openings:</td><td><b><?php echo $job_details['no_of_openings'];?></b></td></tr>
                        <tr><td>Job Location:</td><td><b><?php echo $job_details['location_name'];?></b></td></tr>
                        <tr><td>Contact Person Name:</td><td><b><?php echo ucfirst($job_details['contact_name']);?></b></td></tr>
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
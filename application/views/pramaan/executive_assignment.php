<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  add candidate
 * @date  Nov_2016
*/
select.input-sm 
{
 line-height: 10px; 
}
.error_label, .validmark{color: red;
</style>
<?php
    $executives_options=array('' => '-Select Executives-');
    foreach ($executive_list as $row) 
    {
        $executives_options[$row['id']]=$row['name'];
    }

   
?>
<div class="inner">
<h4>Support Executive Assignment</h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("pramaan/job_assignment","Job Assignment");?></li>
    <li class="active">Support Executive Assignment</li>
  </ul>
</small>
<hr/>
    <form id="add_assignment_form"  method="post" class="form-horizontal" style="padding-top:10px;">
    <div class="row form-box">

        <div class="col-sm-6 col-md-6">
           
                <div class="form-group">
                    <input type="hidden" id="job_id" name="job_id" value="<?php echo $job_id;?>"/>
                    <div class="col-sm-8" style="padding-left:38%;">
                            <h5>Job Id : <?php echo $job_id;?></h5>
                            <h5>Job Name : <?php echo $job_desc;?></h5>
                    </div>
                </div> 
                <div class="form-group">
                    <label for="state" class="col-sm-4 control-label">State<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                    <?php echo form_dropdown('executive_id',$executives_options,'','id="executive_id" class="form-control"');?>
                    <span class="error_label"></span>
                    </div>
                </div>
                <div class="form-group pull-right">
                    <div class="col-sm-6">
                        <button type="reset" class="btn btn-primary btn-block">Reset</button>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-success btn-block" name="submit" value="add">Save</button>
                    </div>
                </div>
        </div>
       
    </div>

    </form>
</div><!-- inner -->

<script type="text/javascript">
$(document).ready(function()
{
  
//------validation for the candidate form
    $("#add_assignment_form").validate({
     ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
        // name attrib of the field
         $(element).closest('.form-group').find('.error_label').html(error);
        },
        messages: 
        {
            executive_id: 
            {
                required: "Please enter Candidate name",
            },
            
        },
     rules: {
            executive_id: 
            {
               required: true
            }
     },

     submitHandler: function (form) 
     {
          $.ajax({
             type: "POST",
             url: base_url+"partner/save_candidate",
             data: $('#add_assignment_form').serialize(),
             dataType:'json',
             success: function (data) 
             {
                var form="#add_assignment_form";
                if (data.status == true) 
                {
                    $(form)[0].reset();
                    flashAlert(data.msg_info);
                    window.location.href = base_url+'partner/save_assignment/'
                    
                }
                else
                {
                    $.each(data.errors, function(key, val) 
                    {
                        $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                    });
                    $("#add_assignment_form").valid();
                }
             }
         });
         return false; // required to block normal submit since you used ajax
     }

    });
   
    //load the course on change qualification

})

</script>


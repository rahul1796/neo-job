<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<link href="<?php echo base_url().'assets/fastselect/build.min.css'?>" rel="stylesheet">
<script src="<?php echo base_url().'assets/fastselect/build.min.js'?>" >
</script>

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
     
    $state_options=array('' => '-Select State-');
    foreach ($state_list as $row) 
    {
        $state_options[$row['id']]=$row['name'];
    }
/*
    $state_options=array('' => '-Select State-');
    foreach ($state_list as $row) 
    {
        $state_options[$row['id']]=$row['name'];
    }*/
?>

<script type="text/javascript">
    


</script>

<div class="inner">
<h4>Edit District</h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("pramaan/districts","Districts");?></li>
    <li class="active">Edit District</li>
  </ul>
</small>
<hr/>
    <form id="edit_district"  method="post" class="form-horizontal" style="padding-top:10px;">
    <div class="row form-box">

        <div class="col-sm-6 col-md-6">

              <div class="form-group">
                       <div class="col-sm-8">
                       <div class='input-group' id='district_id'>
                        <input type="hidden" class="form-control" name="district_id" value="<?php echo $district_data['id']; ?>" />
                        </div>
                    <span class="error_label"></span>
                    </div>
                </div>

              <div class="form-group">
                    <label for="district_name" class="col-sm-4 control-label">District Name<span class="validmark">*</span></label>
                     <div class="col-sm-8">
                 
                   <!--  <?php //echo form_dropdown('state_id',$state_options,$state_data['state_id'],'id="state_id" class="form-control"');?> -->

                      <div class='input-group' id='district_name'>
                        <input type="text" class="form-control" name="district_name" value="<?php echo $district_data['name']; ?>" disabled />
                        </div> 

                    <span class="error_label"></span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="state_name" class="col-sm-4 control-label">State Name<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                 
                    <?php echo form_dropdown('state_id',$state_options,$district_data['state_id'],'id="district_id" class="form-control"');?> 

                    

                    <span class="error_label"></span>
                    </div>
                    
                </div>

               

        </div>
       
    </div>
    <div class="form-group">
    
        <div class="col-sm-4">
            <button type="submit" class="btn btn-success btn-block" name="submit" value="update">Save</button>

        </div>
         <div class="col-sm-offset-2 col-sm-4">
            <button type="reset" class="btn btn-primary btn-block">Reset</button>
        </div> 
        
    </div>
    </form>
</div><!-- inner -->

<script type="text/javascript">
$(document).ready(function()
{
  

    $("#edit_district").validate({
     ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
        // name attrib of the field
         $(element).closest('.form-group').find('.error_label').html(error);
        },
         messages: 
        {
            
            
            state_id:
            {
                required: "Please select a State"
            },

            district_id:
            {
                required: "Please select a District" 
            }
           
        },
     rules: {
           
            state_id: {
                required: true
            },

            district_id: {
                required:true
            }
           
           
     },

     submitHandler: function (form) 
     {
          $.ajax({
             type: "POST",
             url: base_url+"pramaan/save_district/<?php echo $parent_id; ?>/<?php echo $district_data['id']; ?>",
             data: $('#edit_district').serialize(),
             dataType:'json',
             success: function (msg) 
             {   
                 //alert(msg);
                 var form="#edit_district";
               	if(msg.status==true)
               		{
                      $(form)[0].reset();
                    flashAlert(msg.msg_info);
                    window.location.href = base_url+'pramaan/districts/'+"<?php echo $parent_id;?>";
                    }

                else
                {
                    $.each(msg.errors, function(key, val) 
                    {
                        $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                    });
                    $("#edit_district").valid();
                }
             },
             error:function()
             {
             	alert('Error!!');
             }
         });
     }

    });



})


</script>

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
    $country_options=array('' => '-Select Country-');
    foreach ($country_list as $row) 
    {
        $country_options[$row['name']]=$row['name'];
    }

 ?> 
<div class="inner">
<h4>Add Country</h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("partner/candidates","Sourcing Head");?></li>
    <li class="active"> Add Country</li>
  </ul>
</small>
<hr/>
    <form id="add_country"  method="post" class="form-horizontal" style="padding-top:10px;">
    <div class="row form-box">

        <div class="col-sm-6 col-md-6">
                <div class="form-group">
                    <label for="country" class="col-sm-4 control-label">Country<span class="validmark">*</span></label>
                    <div class="col-sm-8">
                    <?php echo form_dropdown('country',$country_options,'','id="country" class="form-control"');?>
                    <span class="error_label"></span>
                    </div>
                </div>

        </div>
       
    </div>
    <div class="form-group">
<div class="col-sm-2">
           
        </div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-success btn-block" name="submit" value="add">Add Country</button>

        </div>
        <div class="col-sm-8">
           

        </div>
    </div>
    </form>
</div><!-- inner -->

<script type="text/javascript">
$(document).ready(function()
{
  

    $("#add_country").validate({
     ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
        // name attrib of the field
         $(element).closest('.form-group').find('.error_label').html(error);
        },
        messages: 
        {
            
            country:
            {
                required: "Please select country", 
            }
           
        },
     rules: {
           
            country: {
                required: true
            }
           
     },

     submitHandler: function (form) 
     {
          $.ajax({
             type: "POST",
             url: base_url+"pramaan/add_country_new",
             data: $('#add_country').serialize(),
             dataType:'json',
             success: function (msg) 
             {
               	if(msg.status==true)
               		alert("Country "+msg.name+" added.");
               	else
               		alert("Failed to add country "+msg.name);
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

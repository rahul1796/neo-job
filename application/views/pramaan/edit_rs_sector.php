<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!--<link href="<?php /*echo base_url().'assets/fastselect/build.min.css'*/?>" rel="stylesheet">
<script src="<?php /*echo base_url().'assets/fastselect/build.min.js'*/?>" ></script>-->

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
     
    $vertical_options=array('' => '-Select Verticals-');
    foreach ($verticals_list as $row) 
    {
        $vertical_options[$row['id']]=$row['name'];
    }

?>

<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/rs_sectors","RS Sectors");?></li>
                <li class="breadcrumb-item active">Edit RS Sector</li>
            </ol>
        </div>
    </div>
    <section id="basic-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="bordered-layout-basic-form">Edit User Info</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block">
                            <form class="form form-horizontal form-bordered" id="edit_sector" method="post">
                                <div class="form-body">
                                    <div class="form-group row">
                                        <input type="hidden" class="form-control" name="sector_id" value="<?php echo $rs_sector_data['id']; ?>" />
                                        <label class="col-md-3 label-control" for="pname">RS Vertical Name<span class='validmark'>*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('vertical_id',$vertical_options,$rs_sector_data['vertical_id'],'id="vertical_id" class="form-control" placeholder="Select Vertical"');?>
                                            <span class="error_label"></span>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="email">RS Sector Name<span class='validmark'>*</span></label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" name="rs_sector_name" value="<?php echo $rs_sector_data['name']; ?>" placeholder="RS Sector Name" />
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="form-actions">
                                    <button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
                                    <button type="submit" class="btn btn-primary"  name="submit" value="update"><i class="icon-check2"></i>Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
    <!-- // Basic form layout section end -->
</div>


<script type="text/javascript">
$(document).ready(function()
{
  

    $("#edit_sector").validate({
     ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
        // name attrib of the field
         $(element).closest('.form-group').find('.error_label').html(error);
        },
        messages: 
        {
            
            vertical_id:
            {
                required: "Please select a Vertical"
            },

            sector_id:
            {
                required: "Please select a Sector" 
            }
           
        },
     rules: {
           
            vertical_id: {
                required: true
            },

            sector_id: {
                required:true
            }
           
     },

     submitHandler: function (form) 
     {
          $.ajax({
             type: "POST",
             url: base_url+"pramaan/save_rs_sector",
             data: $('#edit_sector').serialize(),
             dataType:'json',
             success: function (data)
             {   

                 if (data.status == true)
                 {
                     //alert(data.msg_info); //show success message
                     swal({
                             title: "",

                             text: data.msg_info + "!",
                             confirmButtonColor: "#5cb85c",
                             confirmButtonText: 'OK'
                         },

                         function (confirmed) {
                             window.location.href = base_url+'pramaan/rs_sectors/';
                         });

                 }
                 else
                 {
                     /*alert("ERROR!!");*/

                     $.each(data.errors, function(key, val)
                     {
                         $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                     });
                     $("#edit_sector").valid();
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

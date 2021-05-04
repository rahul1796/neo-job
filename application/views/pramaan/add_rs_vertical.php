<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<!--<link href="<?php /*echo base_url().'assets/fastselect/build.min.css'*/?>" rel="stylesheet">
<script src="<?php /*echo base_url().'assets/fastselect/build.min.js'*/?>" </script>-->

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
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/rs_verticals","RS Verticals");?></li>
                <li class="breadcrumb-item active">Add RS Vertical</li>
            </ol>
        </div>
    </div>
    <section id="basic-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="bordered-layout-basic-form">Add Vertical Info</h4>
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
                            <form class="form form-horizontal form-bordered" id="add_rs_vertical" method="post">
                                <div class="form-body">
                                    <div class="form-group row">
                                        <label for="rs_vertical_name" class="col-sm-3 label-control">RS Vertical Name<span class="validmark">*</span></label>
                                        <div class="col-sm-9">

                                            <div class='input-group' id='rs_vertical_name'>
                                                <input type="text" class="form-control" name="rs_vertical_name" placeholder="RS Vertical Name" />
                                            </div>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="rs_vertical_code" class="col-sm-3 label-control">RS Vertical Code<span class="validmark">*</span></label>
                                        <div class="col-sm-9">

                                            <div class='input-group' id='rs_vertical_code'>
                                                <input type="text" class="form-control" name="rs_vertical_code" placeholder="RS Vertical Code" />
                                            </div>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
                                    <button type="submit" class="btn btn-primary"  value="add" name="submit"><i class="icon-check2"></i>Save</button>
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
  

    $("#add_rs_vertical").validate({
     ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
        // name attrib of the field
         $(element).closest('.form-group').find('.error_label').html(error);
        },
        messages: 
        {
            
            rs_vertical_name:
            {
                required: "Please provide name of the Vertical"
            },

            rs_vertical_code:
            {
                required: "Please provide short-name for the Vertical" 
            }
           
        },
     rules: {
           
            rs_vertical_name: {
                required: true
            },

            rs_vertical_code: {
                required:true
            }
           
     },

     submitHandler: function (form) 
     {
          $.ajax({
             type: "POST",
             url: base_url+"pramaan/save_rs_vertical",
             data: $('#add_rs_vertical').serialize(),
             dataType:'json',
             success: function (data)
             {
                 var form="#add_rs_vertical";
                 if (data.status == true)
                 {
                     swal({
                             title: "",
                             type: "success",
                             text: data.msg_info + "!",
                             confirmButtonColor: "#5cb85c",
                             confirmButtonText: 'OK'
                         },
                         function (confirmed) {
                             window.location.href = base_url+'pramaan/rs_verticals/'+"<?php echo $parent_id;?>";
                         });
                     //window.location.href = base_url+'pramaan/sourcing_heads/'+"<?=$parent_id ?>";
                 }
                 else
                 {
                     /*alert("ERROR!!");*/

                     $.each(data.errors, function(key, val)
                     {
                         $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                     });
                     $("#add_rs_vertical").valid();
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

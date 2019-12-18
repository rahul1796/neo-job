<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">

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
                <li class="breadcrumb-item"><?php echo anchor("pramaan/regions","Regions");?></li>
                <li class="breadcrumb-item active">Edit Regions</li>
            </ol>
        </div>
    </div>
    <section id="basic-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="bordered-layout-basic-form">Edit Info</h4>
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
                            <form class="form form-horizontal form-bordered" id="add_region" method="post">
                                <div class="form-body">
                                    <div class="form-group row">
                                        <div class="col-sm-8">
                                            <div class='input-group' id='country_id'>
                                                <input type="hidden" class="form-control" name="country_id" value="<?php echo $region_data['country_id']; ?>" />
                                            </div>
                                            <span class="error_label"></span>
                                        </div>
                                        <div class="col-sm-8">
                                            <div class='input-group' id='region_id'>
                                                <input type="hidden" class="form-control" name="region_id" value="<?php echo $region_data['id']; ?>" />
                                            </div>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="region_name">Region Name<span class='validmark'>*</span></label>
                                        <div class="col-md-9" id="region_name">
                                            <input type="text" class="form-control" name="region_name" placeholder="Region Name" value="<?php echo $region_data['name']; ?>" />
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="region_short_name">Region Short-name<span class='validmark'>*</span></label>
                                        <div class="col-md-9" id="region_short_name">
                                            <input type="text" class="form-control" name="region_short_name" value="<?php echo $region_data['code']; ?>" placeholder="Short-name for Region" />
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="states_under_region" class="col-sm-3 label-control">States <span class="validmark">*</span></label>

                                        <div class="col-sm-9" id="states_under_region_dropdown">

                                            <select name="states_under_region[]" id="states_under_region_id" multiple="multiple" placeholder="Select States" class="select2 form-control"  required>
                                                <?php
                                                $i=1;
                                                foreach ($states_list as $key) {

                                                    ?>

                                                    <option value=<?php echo $key['id'];  ?> ><?php echo $key['name']; ?></option>

                                                    <?php
                                                    $i++;
                                                }

                                                ?>
                                            </select>

                                            <span class="error_label"></span>
                                        </div>
                                    </div>


                                    <script type="text/javascript">

                                        var selectArray = [];
                                        var i=0;
                                        <?php

                                        $states_under_region_list = explode(',', $region_data['states_under_region_id']);

                                        foreach ($states_under_region_list as $key) {

                                        ?>
                                        selectArray[i] =  '<?php echo $key;  ?>';
                                        i++;
                                        <?php
                                        }


                                        ?>

                                        $('#states_under_region_id').val(selectArray);


                                    </script>
                                </div>

                                <div class="form-actions">
                                    <button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
                                    <button type="submit" class="btn btn-primary"  value="update" name="submit"><i class="icon-check2"></i>Save</button>
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

<!--States Under Region -->




<script type="text/javascript">
$(document).ready(function()
{
  

    $("#add_region").validate({
     ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
        // name attrib of the field
         $(element).closest('.form-group').find('.error_label').html(error);
        },
        messages: 
        {
            
            region_name:
            {
                required: "Please provide name of the region"
            },

            region_short_name:
            {
                required: "Please provide short-name for the region" 
            }
           
        },
     rules: {
           
            region_name: {
                required: true
            },

            region_short_name: {
                required:true
            }
           
     },

     submitHandler: function (form) 
     {
          $.ajax({
             type: "POST",
             url: base_url+"pramaan/save_region/<?php echo $parent_id; ?>/<?php echo $region_data['id']; ?>",
             data: $('#add_region').serialize(),
             dataType:'json',
             success: function (data)
             {
                 var form="#add_region";
                 if(data.status==true)
                 {
                     swal({
                             title: "",

                             text: data.msg_info + "!",
                             confirmButtonColor: "#5cb85c",
                             confirmButtonText: 'OK'
                         },
                         // window.location.href = base_url + 'pramaan/bd_heads/'+"<?=$parent_id ?>";
                         function (confirmed) {
                             window.location.href = base_url+'pramaan/regions/'+"<?php echo $parent_id;?>";
                         });

                 }
                 else
                 {


                     $.each(data.errors, function(key, val)
                     {
                         $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                     });
                     $("#add_region").valid();
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

<script type="text/javascript">

  $('#states_under_region_id').select2();

</script>
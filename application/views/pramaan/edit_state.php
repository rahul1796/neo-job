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


<?php
     
    $region_options=array('' => '-Select Region-');
    foreach ($region_list as $row) 
    {
        $region_options[$row['id']]=$row['name'];
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
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/states","States");?></li>
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
                            <form class="form form-horizontal form-bordered" id="edit_state" method="post">
                                <div class="form-body">
                                    <input type="hidden" class="form-control" name="state_id" value="<?php echo $state_data['id']; ?>" />
                                    <!-- <div class="form-group row">
                                          <label class="col-md-3 label-control">Country<span class='validmark'>*</span></label>
                                          <div class="col-md-9" id="country_id">

                                              <span class="error_label"></span>
                                          </div>

                                      </div>-->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="state_manager">State Manager<span class='validmark'>*</span></label>
                                        <div class="col-md-9" id="state_manager">
                                            <input type="text" class="form-control" name="state_manager" value="<?php echo $state_manager; ?>" disabled />
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="state_name">State Name<span class='validmark'>*</span></label>
                                        <div class="col-md-9" id="state_name">
                                            <input type="text" class="form-control" name="state_name" value="<?php echo $state_data['name']; ?>" disabled />
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="region_name">Region Name<span class='validmark'>*</span></label>
                                        <div class="col-md-9" id="region_name">
                                            <?php echo form_dropdown('region_id',$region_options,$state_data['region_id'],'id="region_id" class="form-control" onchange="getState_byRegion()"');?>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="districts_under_state_dropdown" class="col-sm-3 label-control">District</label>

                                        <div class="col-sm-9" id="districts_under_state_dropdown">

                                            <select  name="districts_under_state[]" id="districts_under_state_id" multiple="multiple"  class="select2 form-control" placeholder="Select District">
                                                <?php
                                                $i=1;
                                                foreach ($districts_list as $key) {

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


               
<script type="text/javascript">
    
var selectArray = [];
var i=0;
<?php
$districts_under_state_list = explode(',', $state_data['districts_under_state_id']);
foreach ($districts_under_state_list as $key) 
{
?>
     selectArray[i] =  '<?php echo $key;  ?>';
      i++;
<?php
}


?>
var tempArray = [1,2];

$('#districts_under_state_id').val(selectArray);


</script>               



<script type="text/javascript">
$(document).ready(function()
{
  

    $("#edit_state").validate({
     ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
        // name attrib of the field
         $(element).closest('.form-group').find('.error_label').html(error);
        },
         messages: 
        {
            
            region_id:
            {
                required: "Please select a region"
            },

            state_id:
            {
                required: "Please select a state" 
            }
           
        },
     rules: {
           
            region_id: {
                required: true
            },

            state_id: {
                required:true
            }
           
     },

     submitHandler: function (form) 
     {
          $.ajax({
             type: "POST",
             url: base_url+"pramaan/save_state/<?php echo $parent_id; ?>/<?php echo $state_data['id']; ?>",
             data: $('#edit_state').serialize(),
             dataType:'json',
             success: function (data)
             {
                 var form="#edit_state";
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
                             window.location.href = base_url+'pramaan/states/'+"<?php echo $parent_id;?>";
                         });

                 }
                 else
                 {


                     $.each(data.errors, function(key, val)
                     {
                         $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                     });
                     $("#edit_state").valid();
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

  $('#districts_under_state_id').select2();

</script>

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
    
function getDistrict_byState()
{ 
    var sel_state_id = document.getElementById('state_id').value;
   /* var x = document.getElementById("district_id");
    x.remove(x.selectedIndex);*/

     $.ajax({
             type: "POST",
             url: base_url+"pramaan/new_districts_list_by_state/"+sel_state_id,
             
             dataType:'json',
             success: function (msg) 
             {   
                             if(!msg)
                        {
                            $('#district_id .error_label').html("Error");
                        }
                        else
                        {
                            //alert(msg[0]['name']);

                            var district_list_html = '<option value="" >-Select District-</option>';

                            for(i=0;i<msg.length;i++)
                            {
                             district_list_html += '<option value="'+msg[i]['id']+'" > '+msg[i]['name']+'</option>';
                            }
                                    }
                        $('select[name="district_id"]').html(district_list_html);
             },
             error:function()
             {
                alert('Error!!');
             }
         });
    
}

</script>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/districts","Districts");?></li>
                <li class="breadcrumb-item active">Add District</li>
            </ol>
        </div>
    </div>
    <section id="basic-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="bordered-layout-basic-form">District Info</h4>
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
                            <form class="form form-horizontal form-bordered" id="add_district" method="post">
                                <div class="form-body">
                                   <!-- <input type="hidden" class="form-control" name="country_id" value="<?php /*echo $country_id; */?>" />-->
                                    <!-- <div class="form-group row">
                                          <label class="col-md-3 label-control">Country<span class='validmark'>*</span></label>
                                          <div class="col-md-9" id="country_id">

                                              <span class="error_label"></span>
                                          </div>

                                      </div>-->
                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="state_name">State Name<span class='validmark'>*</span></label>
                                        <div class="col-md-9">
                                            <?php echo form_dropdown('state_id',$state_options,'','id="state_id" class="form-control" placeholder="Select State" "');?>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-3 label-control" for="district_name">District Name<span class='validmark'>*</span></label>
                                        <div class="col-md-9" id="district_name">
                                            <select class="form-control" id="district_id" name="district_id">

                                            </select>
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
  

    $("#add_district").validate({
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
             url: base_url+"pramaan/save_district",
             data: $('#add_district').serialize(),
             dataType:'json',
             success: function (data)
             {
                 var form="#add_district";
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
                             window.location.href = base_url+'pramaan/districts/'+"<?php echo $parent_id;?>";
                         });

                 }
                 else
                 {


                     $.each(data.errors, function(key, val)
                     {
                         $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                     });
                     $("#add_district").valid();
                 }
             },
             error:function()
             {
             	alert('Error!!');
             }
         });
     }

    });


$(document).on('change', 'select[name="state_id"]', function()
    {
        var sel_state_id = $(this).val();

         $.ajax({
             type: "POST",
             url: base_url+"pramaan/new_districts_list_by_state/"+sel_state_id,
             
             dataType:'json',
             success: function (msg) 
             {   
                             if(!msg)
                        {
                            $('#district_id .error_label').html("Error");
                        }
                        else
                        {
                            //alert(msg[0]['name']);

                            var district_list_html = '<option value="" >-Select District-</option>';

                            for(i=0;i<msg.length;i++)
                            {
                             district_list_html += '<option value="'+msg[i]['id']+'" > '+msg[i]['name']+'</option>';
                            }
                                    }
                        $('select[name="district_id"]').html(district_list_html);
             },
             error:function()
             {
                alert('Error!!');
             }
         });

    });




})


</script>

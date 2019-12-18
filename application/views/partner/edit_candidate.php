<link href='https://fonts.googleapis.com/css?family=Lato:400,300,700,900&subset=latin,latin-ext' rel='stylesheet' type='text/css'>
<script src="<?php echo base_url().'adm-assets/vendors/js/forms/select/select2.full.min.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'adm-assets/js/scripts/forms/select/form-select2.min.js'?>" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/forms/selects/select2.min.css'?>">
<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit associates list
 * @date  Nov_2016
*/
label {
    width: 200px;
}

/* hide input */
input.radio:empty {
    margin-left: -999px;
}

/* style label */
input.radio:empty ~ label {
    position: relative;
    float: left;
    line-height: 2.5em;
    text-indent: 3.25em;
    margin-top: 2em;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
}

input.radio:empty ~ label:before {
    position: absolute;
    display: block;
    top: 0;
    bottom: 0;
    left: 0;
    content: '';
    width: 2.5em;
    background: #D1D3D4;
    border-radius: 22px;
}

/* toggle hover */
input.radio:hover:not(:checked) ~ label:before {
    content:'\2714';
    text-indent: .9em;
    color: #C2C2C2;
}

input.radio:hover:not(:checked) ~ label {
    color: #888;
}

/* toggle on */
input.radio:checked ~ label:before {
    content:'\2714';
    text-indent: .9em;
    color: #9CE2AE;
    background-color: #4DCB6D;
}

input.radio:checked ~ label {
    color: #777;
}

/* radio focus */
input.radio:focus ~ label:before {
    box-shadow: 0 0 0 3px #999;
}
select.input-sm 
{
 line-height: 10px; 
}
.error_label, .validmark{color: red;}
.fstElement { font-size: 1.2em; }
.fstToggleBtn { min-width: 16.5em; }

.submitBtn { display: none; }

.fstMultipleMode { display: block; }
.fstMultipleMode .fstControls { width: 100%; }
</style>



<script type="text/javascript">
    

function interestFunction()
{

var flag=false;

var select2 = document.getElementById('interest_dropdown_id');

var s = [];
var count = select2.selectedOptions.length;

if( count==3)
{


$('.select2-dropdown').hide();

}
else{
   $('.select2-dropdown').show();
   // $(".fstQueryInput .fstQueryInputExpanded").keydown(true);
}


for ( var i = 0; i < select2.selectedOptions.length; i++) {
   
   s[i] = select2.selectedOptions[i].value;



    if(s[i]=='<?php echo $other_interest_code ?>')
    { 
       $('#other_interest').show();
       flag=true;
    }


}
var x= [];
x[0]=x[1]=x[2]=0;


for ( var i = 0; i < select2.selectedOptions.length; i++) {
   
   x[i] = select2.selectedOptions[i].value;

}

var input = document.createElement("input");

input.setAttribute("type", "hidden");

input.setAttribute("name", "area_of_interest1");

input.setAttribute("value", x[0]);

document.getElementById("add_candidate_form").appendChild(input);

///////////////////

var input = document.createElement("input");

input.setAttribute("type", "hidden");

input.setAttribute("name", "area_of_interest2");

input.setAttribute("value", x[1]);

document.getElementById("add_candidate_form").appendChild(input);
////////////////

var input = document.createElement("input");

input.setAttribute("type", "hidden");

input.setAttribute("name", "area_of_interest3");

input.setAttribute("value", x[2]);

document.getElementById("add_candidate_form").appendChild(input);





if(flag==false)
   $('#other_interest').hide();



}






</script>


<!-- Ends -->


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
.error_label,
.validmark,
.error
{color: red};
</style>
<?php
    $state_options=array('' => '-Select State-');
    foreach ($state_list as $row) 
    {
        $state_options[$row['id']]=$row['name'];
    }

    $last_qualification_options=array('' => '-Select Qualification-');
    foreach ($last_qualification_list as $row) 
    {
        $last_qualification_options[$row['id']]=$row['name'];
    }

    $experience_options=array('' => '-Select Experience-');
    foreach ($experience_list as $row) 
    {
        $experience_options[$row['value']]=$row['name'];
    }

    $expected_salary_options=array('' => '-Select Expected Salary-');
    foreach ($salary_list as $row) 
    {
        $expected_salary_options[$row['value']]=$row['name'];
    }
    $id_type_options=array('' => '-Select Id types-');
    foreach ($id_types as $row) 
    {
        $id_type_options[$row['value']]=$row['name'];
    }
    
   //Aniket's Work
   
    $language_known_options=array();
    foreach ($language_known as $row) 
    {
        $language_known_options[$row['id']]=$row['language_name'];
    }

	$interest_options=array('' => '-Select area_of_interest-');
    foreach ($area_of_interest_list as $row)
    {
        $interest_options[$row['value']] = $row['name'];
    }

     $i=0;
	$c = array();
	if ($other_course_code)
		if (count($other_course_code) > 0)
		{
			foreach($other_course_code as $key)
			{
			  $c[$i] = $key->id;

			  $i++;
			}
		}
 
	$i=0;
	$other_interest_val = $other_interest_code;
	//Ends ?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("partner/candidates","Candidate List");?></li>
                <li class="breadcrumb-item active">Edit Candidate</li>
            </ol>
        </div>
    </div>
    <section id="basic-form-layouts">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title" id="bordered-layout-basic-form">Candidate Info</h4>
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
                            <form class="form form-horizontal form-bordered" id="add_candidate_form" method="post">
                                <div class="form-body">
                                    <div class="form-group row">
                                        <label for="candidate_name" class="col-sm-3 label-control">Candidate Name<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="hidden" id="associate_id" name="associate_id" value="<?php echo $associate_id;?>"/>
                                            <input type="hidden" id="candidate_id" name="candidate_id" value="<?php echo $candidate_data['id'];?>"/>
                                            <input type="text" class="form-control" id="candidate_name" name="candidate_name" placeholder="Candidate Name" maxlength="100" value="<?php echo $candidate_data['name'];?>">
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="gender" class="col-sm-3 label-control">Gender<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <div style="width: 5%; margin-top: -27px;">
                                                <input type="radio" name="gender" id="radio1" class="radio gender" value="F" <?php echo ($candidate_data['gender_code']=='F')?'checked':'';?>/>
                                                <label for="radio1">Female</label>
                                            </div>

                                            <div style=" margin-top: -22px;">
                                                <input type="radio" name="gender" id="radio2"  class="radio gender" value="M" <?php echo ($candidate_data['gender_code']=='M')?'checked':'';?>/>
                                                <label for="radio2" style="margin-left: -12%;">Male</label>
                                            </div>

                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="language_id" class="col-sm-3 label-control">Language Known<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <?php echo form_dropdown('language_id[]',$language_known_options,'','id="language_id" class="form-control" placeholder="Select Languages" multiple');?>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <script>
                                        var varIdList = '<?= isset($candidate_data['language_id']) ? $candidate_data['language_id'] : '' ?>';
                                        var varIdList = varIdList.replace(/[{()}]/g, '');
                                        var varIdArray = varIdList.split(',');
                                        $('#language_id').val(varIdArray);

                                       $('#language_id').select2();

                                    </script>

                                    <div class="form-group row">
                                        <label for="date_of_birth" class="col-sm-3 label-control">Date Of Birth<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <div class='input-group date' id='date_of_birth'>
                                                <input type="text" class="form-control" name="date_of_birth" placeholder="DD/MM/YYYY"  value="<?php echo date('d-M-Y',strtotime($candidate_data['dob']));?>"  onkeydown="return false;"/>
                                            </div>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="phone" class="col-sm-3 label-control">Phone<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="phone" name="phone" placeholder="Phone" maxlength="<?= PHONE_MAX?>" value="<?php echo $candidate_data['mobile'];?>" pattern="[0-9]+">
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="email" class="col-sm-3 label-control">Email</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email" maxlength="<?= EMAIL_MAX?>" value="<?php echo $candidate_data['email'];?>">
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="is_aadhar" class="col-sm-3 label-control">Aadhaar card?</label>
                                        <div class="col-sm-9">
                                            <div style="width: 5%; margin-top: -27px;">
                                                <input type="radio" name="is_aadhar" id="radio3" class="radio is_aadhar_no" value="f" <?php echo (!$candidate_data['is_aadhar'])?' checked':'';?> />
                                                <label for="radio3">No</label>
                                            </div>

                                            <div style=" margin-top: -22px;">
                                                <input type="radio" name="is_aadhar" id="radio4"  class="radio is_aadhar_yes" value="t" <?php echo ($candidate_data['is_aadhar'])?' checked':'';?>/>
                                                <label for="radio4" style="margin-left: -12%;">Yes</label>
                                            </div>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row aadhar_div" <?php if(isset($candidate_data['aadhaar_num'])) echo 'style="display: block"'; else echo 'style="display: none"';?> >
                                        <label for="aadhar_number" class="col-sm-3 label-control">Aadhaar Number<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="aadhar_number" name="aadhar_number" placeholder="Aadhaar Number" maxlength="<?= AADHAR_MAX;?>" value="<?php echo $candidate_data['aadhaar_num'];?>"  required>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row no_aadhar_div" <?php if(isset($candidate_data['id_number'])) echo 'style="display: block"'; else echo 'style="display: none"';?> >
                                        <label for="aadhar_number" class="col-sm-3 label-control">Id Type<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <?php echo form_dropdown('id_type',$id_type_options, $candidate_data['id_type'],'id="id_type" class="form-control"');?>
                                                    <hr>
                                                    <input type="text" class="form-control" id="id_number" name="id_number" placeholder="ID Number" maxlength="<?= IDNUMBER_MAX;?>" value="<?php echo $candidate_data['id_number'];?>">
                                                    <span class="error_label"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label for="state" class="col-sm-3 label-control">State<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <?php echo form_dropdown('state',$state_options,$candidate_data['state_id'],'id="state" class="form-control"');?>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="district" class="col-sm-3 label-control">District<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="district" name="district" prev_district_id="<?php echo $candidate_data['district_id']?>">

                                            </select>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label for="pincode" class="col-sm-3 label-control">Pin Code</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="pincode" name="pincode" placeholder="Candidate pin" maxlength="<?= PIN_MAX?>" value="<?php echo $candidate_data['pincode'];?>" pattern="[0-9]+">
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="qualification" class="col-sm-3 label-control">Last Qualification<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <?php echo form_dropdown('qualification',$last_qualification_options,$candidate_data['education_id'],'id="qualification" class="form-control"');?>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
									
                                    <div class="form-group row">
                                        <label for="course" class="col-sm-3 label-control">Course<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <select class="form-control" id="course" name="course" placeholder="Select Course" prev_course_id="<?php echo $candidate_data['course_id']?>">

                                            </select>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>

                                    <!-- for others courses -->
                                    <div class="form-group row" >
                                        <div id="courses_dropdown_id" style="display:none;">

                                            <label for="other_course" class="col-sm-3 label-control">Please Specify<span class="validmark">*</span>
                                            </label>
                                            <div class="col-sm-9" >
                                                <input type="text" class="form-control" name="other_course" id="other_course" placeholder="" value="<?php echo $candidate_data['other_course'];?>" required/>
                                                <span class="error_label"></span></div>
                                        </div>
                                    </div>
                                    <?php									
									if (count($c) > 0)
										if($candidate_data['course_id']==$c[0])
											echo '<script>$("#courses_dropdown_id").show(); </script>';
									if (count($c) > 1)
										if($candidate_data['course_id']==$c[1])
											echo '<script>$("#courses_dropdown_id").show(); </script>';
                                    ?>

                                    <!--Area of Interest -->

                                    <div class="form-group row">
                                        <label for="area_of_interest" class="col-sm-3 label-control">Area of interest<span class="validmark">*</span></label>

                                        <div class="col-sm-9" id="interest_dropdown">
                                            <select name="area_of_interest" id="interest_dropdown_id" class="form-control select2" multiple="multiple" placeholder="Select Area of Interest" onchange ="interestFunction();" required>

                                                <?php
                                                $i=1;
                                                foreach ($area_of_interest_list as $key) {
                                                    ?>
                                                    <option value=<?php echo $key['value'];  ?> ><?php echo $key['name']; ?></option>
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
                                        selectArray[0] = '<?php echo $candidate_data["area_of_interest"]; ?>';
                                        selectArray[1] = '<?php echo $candidate_data["area_of_interest2"]; ?>';
                                        selectArray[2] = '<?php echo $candidate_data["area_of_interest3"]; ?>';

                                        $('#interest_dropdown_id').val(selectArray);

                                        var x= [];
                                        x[0]=x[1]=x[2]=0;

                                        var select2 = document.getElementById('interest_dropdown_id');

                                        for ( var i = 0; i < select2.selectedOptions.length; i++) {
                                            x[i] = select2.selectedOptions[i].value;
                                        }

                                        var input = document.createElement("input");
                                        input.setAttribute("type", "hidden");
                                        input.setAttribute("name", "area_of_interest1");
                                        input.setAttribute("value", x[0]);
                                        document.getElementById("add_candidate_form").appendChild(input);

                                        ///////////////////

                                        var input = document.createElement("input");
                                        input.setAttribute("type", "hidden");
                                        input.setAttribute("name", "area_of_interest2");
                                        input.setAttribute("value", x[1]);
                                        document.getElementById("add_candidate_form").appendChild(input);
                                        ////////////////

                                        var input = document.createElement("input");
                                        input.setAttribute("type", "hidden");
                                        input.setAttribute("name", "area_of_interest3");
                                        input.setAttribute("value", x[2]);
                                        document.getElementById("add_candidate_form").appendChild(input);
                                    </script>

                                    <!-- for other interests -->
                                    <div class="form-group row" >
                                        <div id="other_interest" style="display:none;">
                                            <label for="other_interest" class="col-sm-3 label-control">Please Specify<span class="validmark">*</span></label><div class="col-sm-9" ><input type="text" class="form-control" name="other_interest" id="other_interest" value="<?php echo $candidate_data['other_interest'];?>"
                                        </div>
                                    </div>
                                    <?php
                                    if($candidate_data["area_of_interest"]==$other_interest_code||$candidate_data["area_of_interest2"]==$other_interest_code||$candidate_data["area_of_interest3"]==$other_interest_code)
                                    {
                                        echo '<script>$("#other_interest").show();</script>';
                                    }
                                    ?>

                                    <!--Aniket's Work Ends -->

                                    <div class="form-group row">
                                        <label for="experience" class="col-sm-3 label-control">Experience<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <?php echo form_dropdown('experience',$experience_options,$candidate_data['experience_id'],'id="experience" class="form-control"');?>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputPassword3" class="col-sm-3 label-control">Expected Salary<span class="validmark">*</span></label>
                                        <div class="col-sm-9">
                                            <?php echo form_dropdown('expected_salary',$expected_salary_options,$candidate_data['expected_salary_id'],'id="expected_salary" class="form-control"');?>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="relocate_status_code" class="col-sm-3 label-control">Willing To Relocate</label>
                                        <div class="col-sm-9">
                                            <div style="width: 5%; margin-top: -27px;">
                                                <input type="radio" name="relocate_status_code" id="radio5" class="radio relocate_status_code" value="N" <?php echo ($candidate_data['relocate_status_code']=='N')?'checked':'';?>/>
                                                <label for="radio5">No</label>
                                            </div>

                                            <div style=" margin-top: -22px;">
                                                <input type="radio" name="relocate_status_code" id="radio6"  class="radio relocate_status_code" value="Y" <?php echo ($candidate_data['relocate_status_code']=='Y')?'checked':'';?>/>
                                                <label for="radio6" style="margin-left: -12%;">Yes</label>
                                            </div>
                                            <span class="error_label"></span>
                                        </div>
                                    </div>									
                                </div>
								
								<div class="form-actions">
									<label class="col-sm-3 label-control"></label>
									<div class="col-sm-9" style="margin-bottom:20px;">
										<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
										<button type="submit" class="btn btn-primary"  value="update" name="submit"><i class="icon-check2"></i>Save</button>
									</div>
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
var url_referer='<?php echo isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 0;?>';
$(document).ready(function()
{
	var date_input=$('input[name="date_of_birth"]'); //our date input has the name "date"
	var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
	var start_date = new Date();
    var start='01/Jan/'+(start_date.getFullYear()-60);
	var end_date = new Date();
    var end='31/Dec/'+(end_date.getFullYear()-15);
	var options={
		format: 'dd-M-yyyy',
		container: container,
		todayHighlight: true,
		autoclose: true,
		startDate:start,
		endDate:end
	};


	date_input.datepicker(options);
	jQuery.validator.addMethod("alphanumeric", function(value, element) 
	{
		return this.optional(element) || /^[a-zA-Z0-9]+$/.test(value);
	});	
		
	$("#state").on('change', function()
	{

		$("#district").empty();
		var option = new Option("-Select District-", "0");
		$("#district").append(option);

		$.ajax({
				type: "POST",
				url: base_url+"partner/get_district_bystate/" + $("#state").val(),
				dataType:'json',
				success: function (resultData)
				{
					if (resultData.district_list != null)
					{
						for(var i = 0; i < resultData.district_list.length; i++)
						{
							option = new Option(resultData.district_list[i].name, resultData.district_list[i].id);
							$("#district").append(option);
						}
					}
				}
		});
	});
	
	$("#qualification").on('change', function()
	{
		$("#course").empty();
		var option = new Option("-Select Course-", "0");
		$("#course").append(option);
						
		$.ajax({
				type: "POST",
				url: base_url+"partner/get_course_byqualification/" + $("#qualification").val(),
				dataType:'json',
				success: function (resultData)
				{
					if (resultData.course_list != null)
					{
						for(var i = 0; i < resultData.course_list.length; i++)
						{
							option = new Option(resultData.course_list[i].name, resultData.course_list[i].id);
							$("#course").append(option);
						}
					}
				}
		});
	});	
		
		
    $("#add_candidate_form").validate({
		ignore: ":hidden",
        errorPlacement: function(error, element) 
        {
			$(element).closest('.form-group').find('.error_label').html(error);
        },
        messages: 
        {
            candidate_name: 
            {
                required: "Please enter Candidate name",
            },
            phone: 
            {
                required: "Please enter Phone number",
            },
            date_of_birth:
            {
                required: "Please select date of birth", 
            },
            address:
            {
                //required:"Please enter address",
            },
            state:
            {
                required: "Please  select state", 
            },
            district:
            {
                required: "Please select district",      
            },
            aadhar_number:
            {
                required: "Please enter aadhar Number",
            },
            id_number:
            {
                required: "Please enter Id Number",
                alphanumeric:"Please enter valid Id"
            },
            experience: 
            {
                required: "Please select experience",
            },
            qualification: 
            {
                required: "Please enter qualification",
            },
            course: 
            {
                required: "Please select the course",
            },
            expected_salary:
            {
                required:"Please select expected salary",
            }
        },
		rules: {
            candidate_name: {
               required: true,
               minlength: 3
            },
            date_of_birth: {
               required: true,
               minlength: 3
            },
            phone: {
               required: true,
               number:true,
               minlength: 10
            },
            aadhar_number:
            {
                required: 
                {
                  depends: function() 
                  {
                  return $('input[name=is_aadhar]:checked').val() == 't';
                  }
                },
                number: true
            },
            id_number:
            {
                required: 
                {
                  depends: function() 
                  {
                    return $('input[name=is_aadhar]:checked').val() == 'f';
                  }
                },
                alphanumeric:true
            },
            address:
            {
                //required:true
            },
            state: {
                required: true
            },
            district: {
               required: true
            },
            course: {
               required: true
            },
            qualification:
            {
               required: true
            },
            experience:
            {
              required: true,
              number: true
            },
            expected_salary: 
            {
               required: true,
               number: true
            }
     },
     submitHandler: function (form) 
     {
         if ( $("#add_candidate_form").valid())
         {
             $("button[name=submit]").prop('disabled',true);
         }
          $.ajax({
             type: "POST",
             url: base_url+"partner/save_candidate",
             data: $('#add_candidate_form').serialize(),
             dataType:'json',
             success: function (data) 
             {
                 $("button[name=submit]").prop('disabled',false);
                 var form="#add_candidate_form";
                 if (data.status == true)
                 {

                     swal({
                             title: "",

                             text: data.msg_info + "!",
                             confirmButtonColor: "#5cb85c",
                             confirmButtonText: 'OK'
                         },
                         function (confirmed) {
                             window.location.href = url_referer;
                         });


                 }
                 else
                 {
                     $.each(data.errors, function(key, val)
                     {
                         $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
                     });
                     $("#add_candidate_form").valid();
                 }

             }
         });
         return false; // required to block normal submit since you used ajax
		}

    });

    
    $(document).on('click', 'input[name=is_aadhar]', function()
    {
        var is_adhar=$(this).val();
        if(is_adhar=='t')
        {
            $('.no_aadhar_div').hide();
            $('.aadhar_div').show();
        }
        else
        {
            $('.no_aadhar_div').show();
            $('.aadhar_div').hide();
        }
    
    });

    $("select[name=state]").trigger('change');
    $("select[name=qualification]").trigger('change');

})
</script>

<!-- Aniket's Work -->

<script type="text/javascript">
    
$(document).ready(function()
{
	$(document).on('change', 'select[name="course"]', function()
	{
		sel_course_id = $(this).val(); 
		// alert(document.write(sel_course_id));
		
		var varCourseId1 = '<?php echo isset($c[0]) ? $c[0] : ''; ?>';
		var varCourseId2 = '<?php echo isset($c[1]) ? $c[1] : ''; ?>';
		
		if(sel_course_id==varCourseId1 || sel_course_id==varCourseId2) //93-> iti other course, 97-> 12th other course
		{
			$('#courses_dropdown_id').show();
		}
		else
		{
			$('#courses_dropdown_id').hide();
		}

	});
});

</script>


<script type="text/javascript">

  $('#interest_dropdown_id').select2();

 

</script>

<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<!-- Ends -->
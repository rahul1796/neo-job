<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit associates list
 * @date  Nov_2016
*/
select.input-sm
{
 line-height: 10px;
}
/*.error {
	color: red !important;
}*/
.error_label, .error,.validmark{color: red;}
#multilocation div.form-group
{
	border-bottom:none!important;
    /* border-bottom: 1px solid #EBEBEB; */
}
</style>
<?php
/*$options_sectors=array(''=>'-Select Sector-');
 foreach ($sector_list as $row)
    {
        $options_sectors[$row['id']]=$row['name'];
    }*/

$options_employers = array(''=>'-Select Employers-');
   foreach ($employers_list as $row) {
      $options_employers[$row['id']]=$row['name'];
   }

$options_jobroles=array(''=>'-Select Job Role-');
 foreach ($job_role as $row)
    {
        $options_jobroles[$row['id']]=$row['name'];
    }
$options_category=array(''=>'-Select Category-');
    foreach ($employment_cat_list as $row)
    {
        $options_category[$row['value']]=$row['name'];
    }
$options_qualification=array(''=>'-Select Min. Qualification-');
	foreach ($min_qualification_list as $row)
    {
        $options_qualification[$row['id']]=$row['name'];
    }
$options_departments=array(''=>'-Select Department-');
	foreach ($department_list as $row)
    {
        $options_departments[$row['department_id']]=$row['name'];
    }
/*$options_rec_sup_exec=array(''=>'-Select Rec.Support Executive-');
	foreach ($rec_sup_exec_list as $row)
    {
        $options_rec_sup_exec[$row['user_id']]=$row['name'];
    } */
$options_listings=array(''=>'-Select Listing Type-');
	foreach ($listing_list as $row)
	{
		$options_listings[$row['value']]=$row['name'];
	}
$options_districts=array(''=>'-Select Location-');
	foreach ($district_list as $row)
	{
		$options_districts[$row['id']]=$row['district_name'];
	}
?>
<div class="content-body" style="overflow-x: hidden;"><!-- Basic form layout section start -->
	<div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;">
		<div class="breadcrumb-wrapper col-xs-12">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><?php echo anchor("pramaan/pramaan_jobs","Pramaan Jobs");?></li>
				<li class="breadcrumb-item active">Post Job</li>
			</ol>
		</div>
	</div>
	<section id="basic-form-layouts">
		<div class="row">
			<div class="col-md-12">
				<div class="card">
					<div class="card-header">
						<h4 class="card-title" id="bordered-layout-basic-form">Job Info</h4>
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
							<form class="form form-horizontal form-bordered" id="post_job_form" method="post">
								<div class="form-body">
									<input type="hidden" id="job_poster_id" name="job_poster_id" value="<?php echo $user_id;?>"/>
									<div class="form-group row">
										<label for="employer_id" class="col-sm-3 label-control">Select Employer<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('employer_id',$options_employers, '', 'class="form-control" data-placeholder="Select Employer" id="employer_id"');?>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label for="job_role" class="col-sm-3 label-control">Job Role<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('job_role',$options_jobroles, '', 'class="form-control" data-placeholder="Select Job Role" id="job_role"');?>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="department_id" class="col-sm-3 label-control">Department<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('department_id',$options_departments, '', 'class="form-control" id="department_id"');?>
											<span class="error_label"></span>
										</div>
									</div>

									<div class="form-group row">
										<label for="job_category_id" class="col-sm-3 label-control">Employment Category<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('job_category_id',$options_category, '', 'class="form-control" id="category"');?>
											<span class="error_label"></span>
										</div>
									</div>

									<!--<div class="form-group row" style="width: 50%; margin-left: 25%;">
										<div class="repeater-default">
											<div data-repeater-list="car">
												<div data-repeater-item="" >
													<div class="col-sm-12" style="margin: -17px 10px 12px -17px;">
														<label for="questions" class="col-sm-3 label-control" style="text-align: left;width:auto!important">Add Screening Question</label>
														<div class="col-sm-9">
															<div class="input-group">
																<input type="text" class="form-control" name="questions[]" value="" placeholder="Type a Question 1" />
															</div>
														</div>
														<span class="error_label"></span>
													</div>
													<div class="col-sm-12" style="float: right; margin-right: -90%;margin-top: -43px;">
														<button type="button" class="btn btn-danger" data-repeater-delete=""> <i class="icon-cross2"></i> Delete</button>
													</div>
												</div>
											</div>

											<div class="col-sm-6" style=" float: right; margin-bottom: -26px; margin-top: -43px;  margin-right: -22%;">
												<button data-repeater-create="" class="btn btn-info">
													<i class="icon-plus4"></i> Add
												</button>
											</div>

										</div>

									</div>-->
									<div class="form-group row">
										<label for="questions" class="col-sm-3 label-control">Questions</label>
										<div class="col-sm-9">

											<div class="field_wrapper">
												<div class="input-group">
													<input class="form-control" name="questions[]" type="text" placeholder="Type a Question 1" />
													<span class="input-group-btn"><button class="btn btn-primary add-button" type="button">+</button></span>
												</div>
											</div>
											<span class="error_label"></span>
										</div>
									</div>



									<div class="form-group row">
										<label for="job_desc" class="col-sm-3 label-control">Job Description</label>
										<div class="col-sm-9">
											<textarea class="form-control" rows="2" id="job_desc" name="job_desc" placeholder="Job description" maxlength="500"></textarea>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="job_address" class="col-sm-3 label-control">Job Address</label>
										<div class="col-sm-9">
											<textarea class="form-control" rows="2" id="job_address" name="job_address" placeholder="Address"></textarea>
											<span class="error_label"></span>
										</div>
									</div>
								</div>

									<div class="form-group row">
										<label for="min_qualification" class="col-sm-3 label-control">Min. Qualification<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<?php echo form_dropdown('min_qualification',$options_qualification, '', 'class="form-control" id="min_qualification"');?>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="min_experience" class="col-sm-3 label-control">Experience<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<div class="row">
												<div class="col-sm-6">
													<input type="number" class="form-control" name="min_experience" id="min_experience" maxlength="10" placeholder="Min. Experience(in years)" required/>
												</div>
												<div class="col-sm-6">
													<input type="number" class="form-control" name="max_experience" id="max_experience" maxlength="10" placeholder="Max. Experience (in years)" required/>
												</div>
											</div>
											<span class="error_label"></span>

										</div>
									</div>


									<!-- Aniket's Work  -->

									<div class="form-group row">
										<label for="min_age" class="col-sm-3 label-control">Age Group Required<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<div class="row">
												<div class="col-sm-6">
													<input type="number" class="form-control" name="min_age" id="min_age" maxlength="10" placeholder="Min. Age" required/>
												</div>

												<div class="col-sm-6">
													<input type="number" class="form-control" name="max_age" id="max_age" maxlength="10" placeholder="Max. Age" required/>
												</div>

											</div>
											<span class="error_label"></span>


										</div>
									</div>


									<div class="form-group row">
										<label for="last_date_apply" class="col-sm-3 label-control">Last Date of Application <span class="validmark">*</span></label>
										<div class="col-sm-9">
											<div class='input-group date' id='last_date_apply'>

												<input type="text" class="form-control" name="last_date_apply" placeholder="DD/MM/YYYY"/>
											</div>
											<span class="error_label"></span>
										</div>
									</div>

									<!--      End    -->



									<div class="form-group row">
										<label for="contact_name" class="col-sm-3 label-control">Contact Person<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="contact_name" id="contact_name" maxlength="50" placeholder="Contact Person Name" />
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="email" class="col-sm-3 label-control">Email<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="email" id="email" maxlength="<?= EMAIL_MAX?>"  placeholder="Email"/>
											<span class="error_label"></span>
										</div>
									</div>
									<div class="form-group row">
										<label for="phone" class="col-sm-3 label-control">Phone<span class="validmark">*</span></label>
										<div class="col-sm-9">
											<input type="text" class="form-control" name="phone" id="phone" maxlength="<?= PHONE_MAX?>" placeholder="Phone"/>
											<span class="error_label"></span>
										</div>
									</div>
									<!--  <div class="form-group">
                    <label for="phone" class="col-sm-4 control-label">Recruitment Support Executive</label>
                    <div class="col-sm-8">
					<?php //echo form_dropdown('rec_sup_exec_id',$options_rec_sup_exec, '', 'class="form-control" id="rec_sup_exec_id"');?>
				  	<span class="error_label"></span>
                    </div>
                </div> -->
								<div class="form-group row">
									<label for="listing_id" class="col-sm-3 label-control">Listing Type<span class="validmark">*</span></label>
									<div class="col-sm-9">
										<?php echo form_dropdown('listing_id',$options_listings, '', 'class="form-control" id="listing_id"');?>
										<span class="error_label"></span>
									</div>
								</div>

								<div class="form-group row" id="multilocation">
									<label for="add_location" class="col-md-3 label-control">Add Location<span class="validmark">*</span></label>
									<div class="col-md-9">
										<div class="field_wrapper1">
											<div class="input-group add_location" style="padding-bottom:10px!important;">
												<div class="row">

													<div class="col-md-3">
														<div class="form-group row" style="border-bottom: none;">
															<?php echo form_dropdown('location[]',$options_districts, '', 'class="form-control location" required');?>
															<span class="error_label"></span>
														</div>
													</div>

													<div class="col-md-3">
														<div class="form-group row">
															<input type="number" class="form-control" name="no_of_openings[]" value="" placeholder="No of openings" required/>
															<span class="error_label"></span>
														</div>
													</div>
													<div class="col-md-5">
														<div class="row">
															<div class="col-md-6">
																<div class="form-group row">
																	<input type="text" class="form-control min_salary" name="min_salary[]" maxlength="10" placeholder="Min. Salary" required/>
																	<span class="error_label"></span>
																</div>
															</div>
															<div class="col-md-6">

																<div class="form-group row">
																	<input type="text" class="form-control max_salary" name="max_salary[]" maxlength="10" placeholder="Max. Salary" required "/>
																	<span class="error_label"></span>
																</div>
															</div>
														</div>
													</div>

													<span class="input-group-btn"><button class="btn btn-primary add-div" type="button">+</button></span>
												</div>
											</div>
										</div>
									</div>
								</div>
									<div class="form-actions" style=" float: right; margin-top: 10px;">
										<button type="reset" class="btn btn-warning mr-1"><i class="icon-cross2"></i> Reset</button>
										<button type="submit" class="btn btn-primary" name="submit" value="add"><i class="icon-check2"></i>Post Job</button>
									</div>
							</form>


	</section>


</div>
<!--<script src="<?php /*echo base_url().'adm-assets/vendors/js/forms/repeater/jquery.repeater.min.js'*/?>" type="text/javascript"></script>
<script src="<?php /*echo base_url().'adm-assets/js/scripts/forms/form-repeater.min.js'*/?>" type="text/javascript"></script>-->

<script>
$(document).ready(function(){
	var date_input=$('input[name="date"]'); //our date input has the name "date"
	var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
	date_input.datepicker({
		format: 'mm/dd/yyyy',
		container: container,
		todayHighlight: true,
		autoclose: true,
	})
})
var districts_list='';
$(document).ready(function()
{
//Anikets Work
  var date_input=$('input[name="last_date_apply"]'); //our date input has the name "date"
  var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
  var start_date = new Date();
      start='01/Jan/'+(start_date.getFullYear());
  var end_date = new Date();
      end='31/Dec/'+(end_date.getFullYear()+10);
  var options={
                format: 'dd-M-yyyy',
                container: container,
                todayHighlight: true,
                autoclose: true,
                startDate:start,
                endDate:end
                };
  date_input.datepicker(options);

//Ends

	//------validation for the candidate form
	/*$("#job_role").chosen();
	$("#employer_id").chosen();*/
/*	$.validator.addMethod('greater_than', function (value, element, param)
	{
		return +$(element).val() > +$(param).val();
	});*/
	$.validator.addMethod("greaterThan",function (value, element, param)
	{
		var $min = $(param);
		if (this.settings.onfocusout)
		{
			$min.off(".validate-greaterThan").on("blur.validate-greaterThan", function ()
			{
				$(element).valid();
			});
		}
		return parseInt(value) > parseInt($min.val());
	}, "Max salary must be greater than min salary");


    $.validator.setDefaults({ ignore: ":hidden:not(select)" })
	$("#post_job_form").validate({
	// ignore: ":hidden",
		errorPlacement: function(error, element)
		{
		// name attrib of the field
		 $(element).closest('.form-group').find('.error_label').html(error);
		},
		messages:
		{
			/*sector:
			{
				required: "Please select sector",
			},*/
			employer_id:
			{
                required: "Please select Employer",
			},

			department_id:
			{
				required: "Please select department",
			},

			job_role:
			{
				required: "Please select job role",
			},
			job_category_id:
			{
				required: "Please select category",
			},
			email:
			{
				required: "Please enter your email",
			},
			'no_of_openings[]':
			{
				required: "Please enter number of openings",
			},
			job_location:
			{
				required: "Please select location",
			},
			min_qualification:
			{
				required: "Please select min. qualification",
			},
			min_experience:
			{
				required: "Please enter min. experience",
			},
			max_experience:
			{
				required: "Please enter max. experience",
			},
			'min_salary[]':
			{
				required: "Please enter min. salary",
			},
			'max_salary[]':
			{
				required: "Please enter max. salary",
			},

     //Aniket's work


            min_age:
            {
               required: 'Please enter min. age',
            },
            max_age:
            {
                required:'Please enter max. age',

            },
             last_date_apply:
             {
              required:'Please enter the last date to apply',
             },
//ends

			contact_name:
			{
				required: "Please enter contact person name",
			},
			phone:
			{
				required: "Please enter phone",
			},
			listing_id:
			{
				required: "Please select listing type"
			}

		},
		rules:
		{
			/*sector:
			{
				required:true
			},*/
			employer_id:
			{
             required:true
			},
			department_id:
			{
				required:true
			},
			job_role:
			{
				required:true
			},
			job_category_id: {
			 required: true
			},
			phone: {
			 required: true,
			 number: true,
			 minlength: 10
			},
			email: {
			    required: true,
			    email: true
			},
			'no_of_openings[]':
			{
				 required: true,
				 number: true
			},
			job_location: {
			 required: true
			},
			min_qualification:
			{
				required: true
			},
			min_experience:
			{
				required: true,
				number: true,
				maxlength:2
			},
			max_experience:
			{
				required: true,
				number: true,
				maxlength:2
			},
			'min_salary[]':
			{
				required: true,
				number: true,
				maxlength:10
			},
			'max_salary[]':
			{
				required: true,
				number: true,
				maxlength:10,
				greaterThan: '.min_salary'
			},
			contact_name:
			{
				required: true
			},
			listing_id:
			{
				required: true
			},
			min_age:
			{
				required:true,
				number: true,
				maxlength:2
			},
			max_age:
			{
				required:true,
				number: true,
				maxlength:2
			}

		},
	submitHandler: function (form)
	{
		if ( $("#post_job_form").valid())
		{
			$("button[name=submit]").prop('disabled',true);
		}
	      $.ajax({
	         type: "POST",
	         url: base_url+"/employer/save_job",
	         data: $('#post_job_form').serialize(),
	         dataType:'json',
	         success: function (data)
	         {
	         	var form="#post_job_form";

				if (data.status == true)
				{
					/*$('#post_job_form')[0].reset();
					flashAlert(data.msg_info);
					window.location.href = base_url+'pramaan/pramaan_jobs/'+<?php //echo $user_id;?>;*/
					$("button[name=submit]").prop('disabled',false);
					swal({
                            title: "",

                            text: data.msg_info + "!",
                            confirmButtonColor: "#5cb85c",
                            confirmButtonText: 'OK'
                        },

                        function (confirmed) {
                            window.location.href = base_url+'pramaan/pramaan_jobs/'+<?php echo $user_id;?>;
                        });
				}
				else
				{
					$.each(data.errors, function(key, val)
					{
						$('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
					});
				}
	         }
	     });
	     return false; // required to block normal submit since you used ajax
	 }

	});

    var maxField = 3; //Input fields increment limitation
    var x = 1; //Initial field counter is 1
    var addButton = $('.add-button'); //Add button selector
    var addDiv = $('.add-div'); //Add button selector
    var wrapper = $('.field_wrapper'); //Input field wrapper
    var wrapper1 = $('.field_wrapper1'); //Input field wrapper
    var placeholder='';
    var fieldHTML = '';
    var fieldSET = '';
    $(addButton).click(function()
    { //Once add button is clicked
        if(x < maxField)
        { //Check maximum number of input fields
            x++; //Increment field counter
            placeholder="Type a Question "+x;
            fieldHTML = '<div class="input-group" style="margin-top:10px;">\
	 					 <input class="form-control" name="questions[]" type="text" placeholder="'+placeholder+'" />\
     					 <span class="input-group-btn"><button class="btn btn-danger remove_button" type="button">-</button></span>\
     				     </div>';
            $(wrapper).append(fieldHTML); // Add field html
        }
    });

    $(wrapper).on('click', '.remove_button', function(e)
    { //Once remove button is clicked
        e.preventDefault();
      //  $(this).parents('div').remove(); //Remove field html
        $(this).parents('div:first').remove();
        x--; //Decrement field counter
    });

    $(addDiv).click(function()
    { //Once add button is clicked
    	var districts=get_district_list();
        fieldSET = '<div class="input-group" style="padding-bottom:10px!important;">'+
					'<div class="row">'+
					'<div class="col-md-3">';
		fieldSET +='<select class="form-control location" name="location[]">'+districts+
				   '</select></div>';
		fieldSET += '<div class="col-md-3">\
					        <div class="input-group">\
					            <input type="number" class="form-control" name="no_of_openings[]" value="" placeholder="No of openings" />\
					        </div>\
					 </div>\
				    <div class="col-md-5">\
					        <div class="input-group">\
								<div class="row">\
									<div class="col-md-6">\
										<input type="text" class="form-control min_salary" name="min_salary[]" maxlength="10" placeholder="Min. Salary"/>\
									</div>\
									<div class="col-md-6">\
										<input type="text" class="form-control max_salary" name="max_salary[]" maxlength="10" placeholder="Max. Salary"/>\
									</div>\
								</div>\
								<span class="error_label"></span>\
					        </div>\
				    </div>\
					</div>\
					<span class="input-group-btn"><button class="btn btn-danger remove_div" type="button">-</button></span>\
					</div>';
        $(wrapper1).append(fieldSET); // Add field html
    });

    $(wrapper1).on('click', '.remove_div', function(e)
    { //Once remove button is clicked
        e.preventDefault();
      //  $(this).parents('div').remove(); //Remove field html
        $(this).parents('div:first').remove();
        x--; //Decrement field counter
    });


    /*$(document).on('change','select[name="sector"]', function()
	{
			sel_sector_id = $(this).val();
			$.getJSON(site_url+'/employer/get_job_role_bysector/'+sel_sector_id,'',function(resp)
			{
				if(resp.status == true)
				{
					var role_list_html = '<option value="">-Select Job Role-</option>';
					$.each(resp.role_list,function(i,itm)
					{
						role_list_html += '<option value="'+itm.id+'">'+itm.name+'</option>';
					});
					$('select[name="job_role"]').html(role_list_html);
				}
			});
	});*/
districts_list= load_districts();
});

function load_districts()
{
  var resp_data="";
  var scheduled=2;
  $.ajax({
      url : site_url+'pramaan/get_districts_list/',
      type: "GET",
      dataType: "JSON",
      async:false,
      success: function(data)
      {
          if(data.status) //if success close modal and reload ajax table
          {
               resp_data=data.district;
          }
      }
  });
  return resp_data;
}

function get_district_list()
{
	var district_html= '<option value="0">-Select Location-</option>';
	$.each(districts_list,function(a,b)
	{
	 	district_html += '<option value="'+b.id+'">'+b.district_name+'</option>';
	});
    return district_html;
}

</script>

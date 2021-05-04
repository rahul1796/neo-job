    <div class="form-group row" id="multilocation" style="overflow-x: hidden; margin-top: 20px;">
        <div class="col-md-12">
            <div class="field_wrapper2">
                    <div class="form-group row" style="width: 95%;">

                        <div class="col-md-4">
                            <label for="date_of_birth">From Date:</label>
                            <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="date_of_birth" placeholder="Enter From Date" name="from_date" value="<?php echo $fields['date_of_birth']; ?>">
                            <?php echo form_error('date_of_birth'); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="date_of_birth">To Date:</label>
                            <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="date_of_birth" placeholder="Enter To Date" name="to_date" value="<?php echo $fields['date_of_birth']; ?>">
                            <?php echo form_error('date_of_birth'); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="current_designation">Designation:</label>
                            <input type="text" class="form-control" id="current_designation" placeholder="Enter Designation " name="designation" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('current_designation'); ?>
                        </div>
                     </div>
                    <div class="form-group row">
                        <div class="col-md-4">
                            <label for="country_id" class="label">Select Country:</label>
                            <select class="form-control" name="country_id" id="country_id">
                                <option value="0" <?php echo ($fields['country_id']==0) ? 'selected' : '' ?>>Select Country</option>
                                <?php foreach($countries_options as $country_option): ?>
                                    <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$fields['country_id']) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php echo form_error('country_id'); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="state_id" class="label">Select State:</label>
                            <select class="form-control" name="state_id" id="state_id">
                                <option value="0" <?php echo ($fields['main_state_id']==0) ? 'selected' : '' ?>>Select State</option>
                                <option value="<?php echo $fields['main_state_id']; ?>" selected><?php echo $fields['state_name']; ?></option>
                            </select>
                            <?php echo form_error('state_id'); ?>
                        </div>


                        <div class="col-md-4">
                            <label for="city" class="label">City:</label>
                            <input type="text" class="form-control" id="city" placeholder="Enter city" name="city" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('city'); ?>
                        </div>
                    </div>

                     <div class="form-group row">
                           <div class="col-md-3">
                                <label for="current_designation">Location:</label>
                                <input type="text" class="form-control" id="current_designation" placeholder="Enter Location " name="location" value="<?php echo $fields['current_designation']; ?>">
                                <?php echo form_error('current_designation'); ?>
                           </div>

                            <div class="col-md-3">
                                <label for="ctc">CTC:</label>
                                <input type="number" class="form-control" id="ctc" placeholder="Enter CTC" name="ctc" value="<?php echo $fields['date_of_birth']; ?>">
                                <?php echo form_error('ctc'); ?>
                            </div>
                         <div class="col-md-3">
                             <label for="gross_salary">Gross Salary:</label>
                             <input type="number" class="form-control" id="ctc" placeholder="Enter gross_salary" name="gross_salary" value="<?php echo $fields['date_of_birth']; ?>">
                             <?php echo form_error('gross_salary'); ?>
                         </div>
                         <div class="col-md-3">
                             <label for="currency">Currency:</label>
                             <input type="text" class="form-control" id="currency" placeholder="Enter currency" name="currency" value="<?php echo $fields['date_of_birth']; ?>">
                             <?php echo form_error('currency'); ?>
                         </div>
                         <!--<span class="input-group-btn" style="float: right; margin-top: -32px;"><button class="btn btn-primary add-employer" type="button">+</button></span>-->
                    </div>
                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="address" class="label">Address:</label>
                            <input type="text" class="form-control" id="address" placeholder="Enter Address" name="address" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('address'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="current_location" class="label">Job Profile:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Current Location" name="current_location" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('current_location'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="current_location" class="label">Office Landline:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Current Location" name="current_location" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('current_location'); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-3">
                            <label for="address" class="label">Employee Code:</label>
                            <input type="text" class="form-control" id="address" placeholder="Enter Employee Code" name="address" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('address'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="current_location" class="label">Reason for Leaving:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Reason for leaving" name="current_location" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('current_location'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="current_location" class="label">Current Employer:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Current Employer" name="current_location" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('current_location'); ?>
                        </div>
                        <div class="col-md-3">
                            <label for="current_location" class="label">Notice Period:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Notice Period" name="current_location" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('current_location'); ?>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-md-6">
                            <label for="joining_location" class="label">Joining Location:</label>
                            <input type="text" class="form-control" id="address" placeholder="Enter Joining Location" name="address" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('address'); ?>
                        </div>

                        <div class="col-md-6">
                            <label for="reporting_location" class="label">Reporting Location:</label>
                            <input type="text" class="form-control" id="current_location" placeholder="Enter Reporting Location" name="current_location" value="<?php echo $fields['location']; ?>">
                            <?php echo form_error('current_location'); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

<script type="text/javascript">
 $(document).ready(function() {
   $('#country_id').on('change', function() {
     var c_id = $(this).val();
     var request = $.ajax({
       url: "<?php echo base_url(); ?>CandidatesController/getStates/"+c_id,
       type: "GET",
     });

     request.done(function(msg) {
       var response = JSON.parse(msg);
       // alert(response);
       $('#state_id').html('');
       $('#state_id').append($('<option>').text('Select State').attr('value', 0));
       response.forEach(function(state) {
          $('#state_id').append($('<option>').text(state.name).attr('value', state.id));
       })
       console.log(response);
     });

     request.fail(function(jqXHR, textStatus) {
       alert( "Request failed: " + textStatus );
     });
   });

   $('#state_id').on('change', function() {
     $('#district_id').html('');
     $('#district_id').append($('<option>').text('Select District').attr('value', 0));
     var s_id = $(this).val();
     var request = $.ajax({
       url: "<?php echo base_url(); ?>CandidatesController/getDistricts/"+s_id,
       type: "GET",
     });

     request.done(function(msg) {
       var response = JSON.parse(msg);
       // alert(response);

       response.forEach(function(district) {
          $('#district_id').append($('<option>').text(district.name).attr('value', district.id));
       })
       console.log(response);
     });

     request.fail(function(jqXHR, textStatus) {
       alert( "Request failed: " + textStatus );
     });
   });

 });


 var maxField = 3; //Input fields increment limitation
 var x = 1; //Initial field counter is 1
 var addButton = $('.add-button'); //Add button selector
 var addEmployer = $('.add-employer'); //Add button selector
 var wrapper = $('.field_wrapper'); //Input field wrapper
 var wrapper2 = $('.field_wrapper2'); //Input field wrapper
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

 $(addEmployer).click(function()
 { //Once add button is clicked
     //var districts=get_district_list();
     fieldSET = '<div class="input-group" style="padding-bottom:10px!important;">'+
         '<div class="row">';
     fieldSET += '<div class="col-md-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="employer_name" value="" placeholder="Enter Employer Name" />\
					        </div>\
					 </div>\
					 <div class="col-md-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="designation" value="" placeholder="Enter Designation" />\
					        </div>\
					 </div>\
					 <div class="col-md-2">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="Location" value="" placeholder="Enter Location" />\
					        </div>\
					 </div>\
					 <div class="col-md-2">\
					        <div class="input-group">\
					            <input type="text" class="form-control" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="date_of_birth" name="from_date" value="" placeholder="From Date" />\
					        </div>\
					 </div>\
					 <div class="col-md-2">\
					        <div class="input-group">\
					            <input type="text" class="form-control" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="date_of_birth" name="from_date" value="" placeholder="From Date" />\
					        </div>\
					 </div>\
					</div>\
					<span class="input-group-btn"><button class="btn btn-danger remove_div" type="button">-</button></span>\
					</div>';
     $(wrapper2).append(fieldSET); // Add field html
 });

 $(wrapper2).on('click', '.remove_div', function(e)
 { //Once remove button is clicked
     e.preventDefault();
     //  $(this).parents('div').remove(); //Remove field html
     $(this).parents('div:first').remove();
     x--; //Decrement field counter
 });
</script>

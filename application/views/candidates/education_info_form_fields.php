    <div class="form-group row" id="multieducation" style="overflow-x: hidden; margin-top: 20px;">
        <div class="col-md-12">
            <div class="field_wrapper1">
                    <div class="form-group row" style="width: 95%;">

                        <div class="col-md-3">
                            <label for="education_type">Education Type:</label>
                            <input type="text" class="form-control" id="center_type" placeholder="Enter Education Type" name="education_type" value="<?php echo $fields['center_type']; ?>">
                            <?php echo form_error('education_type'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="education_name">Education Name:</label>
                            <input type="text" class="form-control" id="education_name" placeholder="Enter Education Name" name="education_name" value="<?php echo $fields['center_type']; ?>">
                            <?php echo form_error('education_name'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="institution">Institution:</label>
                            <input type="text" class="form-control" id="institution" placeholder="Enter Institution " name="institution" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('institution'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="location">Location:</label>
                            <input type="text" class="form-control" id="location" placeholder="Enter Location " name="location" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('location'); ?>
                        </div>
                    </div>

                     <div class="form-group row" style="width: 95%; margin-top: 15px;">

                        <div class="col-md-3">
                            <label for="from_date">From Date:</label>
                            <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="from_date" placeholder="Enter From Date" name="from_date" value="<?php echo $fields['date_of_birth']; ?>">
                            <?php echo form_error('from_date'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="to_date">To Date:</label>
                            <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="to_date" placeholder="Enter To Date" name="to_date" value="<?php echo $fields['date_of_birth']; ?>">
                            <?php echo form_error('to_date'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="year_of_passing">Year of paasing:</label>
                            <input type="text" class="form-control" id="year_of_passing" placeholder="Enter Year of Passing " name="year_of_passing" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('year_of_passing'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="learning_type">Learning Type:</label>
                            <input type="text" class="form-control" id="learning_type" placeholder="Enter Learning Type " name="learning_type" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('learning_type'); ?>
                        </div>


                       <!-- <span class="input-group-btn" style="float: right; margin-top: -32px;"><button class="btn btn-primary add-education" type="button">+</button></span>-->
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
 var addEducation = $('.add-education'); //Add button selector
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

 $(addEducation).click(function()
 { //Once add button is clicked
     //var districts=get_district_list();
     fieldSET = '<div class="input-group" style="padding-bottom:10px!important;">'+
         '<div class="row">';
     fieldSET += '<div class="col-md-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="education_name" value="" placeholder="Enter Education Name" />\
					        </div>\
					 </div>\
					 <div class="col-md-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="institution" value="" placeholder="Enter Institution" />\
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
     $(wrapper1).append(fieldSET); // Add field html
 });

 $(wrapper1).on('click', '.remove_div', function(e)
 { //Once remove button is clicked
     e.preventDefault();
     //  $(this).parents('div').remove(); //Remove field html
     $(this).parents('div:first').remove();
     x--; //Decrement field counter
 });
</script>

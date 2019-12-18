    <div class="form-group row" id="multilocation" style="overflow-x: hidden; margin-top: 20px;">
        <div class="col-md-12">
            <div class="field_wrapper3">
                    <div class="form-group row" style="width: 95%;">
                        <div class="col-md-4">
                            <label for="center_type">Skill Name:</label>
                            <input type="text" class="form-control" id="center_type" placeholder="Enter Skill Name" name="skill_name" value="<?php echo $fields['center_type']; ?>">
                            <?php echo form_error('center_type'); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="current_designation">Skill Description:</label>
                            <input type="text" class="form-control" id="skill_description" placeholder="Enter Skill Description " name="skill_description" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('current_designation'); ?>
                        </div>

                        <div class="col-md-4">
                            <label for="current_designation">Version:</label>
                            <input type="text" class="form-control" id="current_designation" placeholder="Enter Version " name="version" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('current_designation'); ?>
                        </div>

                    </div>
                     <div class="form-group row" style="width: 95%; margin-top: 15px;">

                         <div class="col-md-3">
                             <label for="date_of_birth">Last Used Years:</label>
                             <input type="number" class="form-control" id="current_designation" placeholder="Enter Last Used Years " name="last_used" value="<?php echo $fields['current_designation']; ?>">
                             <?php echo form_error('date_of_birth'); ?>
                         </div>

                        <div class="col-md-3">
                            <label for="date_of_birth">Last Used Months:</label>
                            <input type="number" class="form-control" id="current_designation" placeholder="Enter Last Used Years " name="last_used" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('date_of_birth'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="date_of_birth">Experience in Years:</label>
                            <input type="number" class="form-control" id="current_designation" placeholder="Enter Experience " name="experience" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('date_of_birth'); ?>
                        </div>

                        <div class="col-md-3">
                            <label for="date_of_birth">Experience in Months:</label>
                            <input type="number" class="form-control" id="current_designation" placeholder="Enter Experience " name="experience" value="<?php echo $fields['current_designation']; ?>">
                            <?php echo form_error('date_of_birth'); ?>
                        </div>


                        <!--<span class="input-group-btn" style="float: right; margin-top: -32px;"><button class="btn btn-primary add-skill" type="button">+</button></span>-->
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
 var addSkill = $('.add-skill'); //Add button selector
 var wrapper = $('.field_wrapper'); //Input field wrapper
 var wrapper3 = $('.field_wrapper3'); //Input field wrapper
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

 $(addSkill).click(function()
 { //Once add button is clicked
     //var districts=get_district_list();
     fieldSET = '<div class="input-group" style="padding-bottom:10px!important;">'+
         '<div class="row">';
     fieldSET += '<div class="col-md-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="skill_name" value="" placeholder="Enter Skill Name" />\
					        </div>\
					 </div>\
					 <div class="col-md-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="skill_description" value="" placeholder="Enter Skill Description" />\
					        </div>\
					 </div>\
					 <div class="col-md-2">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="version" value="" placeholder="Enter version" />\
					        </div>\
					 </div>\
					 <div class="col-md-2">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="last_used" value="" placeholder="Enter Last Used" />\
					        </div>\
					 </div>\
					 <div class="col-md-2">\
					        <div class="input-group">\
					           <input type="text" class="form-control" name="experience" value="" placeholder="Enter Experience" />\
					        </div>\
					 </div>\
					</div>\
					<span class="input-group-btn"><button class="btn btn-danger remove_div" type="button">-</button></span>\
					</div>';
     $(wrapper3).append(fieldSET); // Add field html
 });

 $(wrapper3).on('click', '.remove_div', function(e)
 { //Once remove button is clicked
     e.preventDefault();
     //  $(this).parents('div').remove(); //Remove field html
     $(this).parents('div:first').remove();
     x--; //Decrement field counter
 });
</script>

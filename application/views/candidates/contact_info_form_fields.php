
<div class="form-group row" style="margin-top: 20px;">
 <div class="col-md-4">
   <label for="email_address">Email:</label>
   <input type="email_address" class="form-control" id="email_address" placeholder="Enter email" name="email_address" value="<?php echo $fields['email_address']; ?>">
   <?php echo form_error('email_address'); ?>
 </div>

 <div class="col-md-4">
   <label for="mobile_number">Mobile Number:</label>
   <input type="phone" class="form-control" id="mobile_number" placeholder="Enter Phone" name="mobile_number" maxlength="10" value="<?php echo $fields['mobile_number']; ?>">
   <?php echo form_error('mobile_number'); ?>
 </div>

 <div class="col-md-4">
   <label for="age">Landline Number:</label>
   <input type="number" class="form-control" id="age" placeholder="Enter Landline Number" name="age" value="<?php echo $fields['age']; ?>">
   <?php echo form_error('age'); ?>
 </div>
</div>

<div class="form-group row">
    <div class="col-md-4">
        <label for="country_id">Select Country:</label>
        <select class="form-control" name="country_id" id="country_id">
            <option value="0" <?php echo ($fields['country_id']==0) ? 'selected' : '' ?>>Select Country</option>
            <?php foreach($countries_options as $country_option): ?>
                <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$fields['country_id']) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('country_id'); ?>
    </div>

    <div class="col-md-4">
        <label for="state_id">Select State:</label>
        <select class="form-control" name="state_id" id="state_id">
            <option value="0" <?php echo ($fields['main_state_id']==0) ? 'selected' : '' ?>>Select State</option>
            <option value="<?php echo $fields['main_state_id']; ?>" selected><?php echo $fields['state_name']; ?></option>
        </select>
        <?php echo form_error('state_id'); ?>
    </div>


    <div class="col-md-4">
        <label for="location">City:</label>
        <input type="text" class="form-control" id="location" placeholder="Enter Address" name="location" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('location'); ?>
    </div>
</div>


<div class="form-group row">
    <div class="col-md-6">
        <label for="location">Address:</label>
        <input type="textarea" class="form-control" id="location" placeholder="Enter Address" name="location" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('location'); ?>
    </div>

    <div class="col-md-6">
        <label for="location">Current Location:</label>
        <input type="textarea" class="form-control" id="location" placeholder="Enter Current Location" name="location" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('location'); ?>
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
</script>

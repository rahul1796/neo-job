<style>
    .label{
        float: left;
        height: 30px;
        padding-right: 4px;
        padding-top: 2px;
        position: relative;
        text-align: right;
        vertical-align: middle;
    }
    .label:before{
        content:"*" ;
        color:red
    }

</style>
<div class="form-group row" style="margin-top: 20px;">
 <div class="col-md-6">
   <label for="candidate_name" class="label">Candidate Name:</label>
   <input type="text" class="form-control" id="candidate_name" placeholder="Enter Candidate Name" name="candidate_name" value="<?php echo $fields['first_name']; ?>">
   <?php echo form_error('candidate_name'); ?>
 </div>

 <div class="col-md-3">
   <label for="candidate_number" class="label">Candidate Registration Number:</label>
   <input type="text" class="form-control" id="candidate_number" placeholder="Enter Candidate Registration Number" name="candidate_number" value="<?php echo $fields['last_name']; ?>">
   <?php echo form_error('candidate_number'); ?>
 </div>

    <div class="col-md-3">
        <label for="gender_id" class="label">Gender:</label>
        <select class="form-control" name="gender_id">
            <option value="">Select Gender</option>
            <?php foreach($gender_options as $gender):?>
                <option value="<?php echo $gender->id; ?>" <?php echo ($gender->id==$fields['gender_id']) ? 'selected' : '' ?> ><?php echo $gender->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('gender_id'); ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-3">
        <label for="preferred_job_location" class="label">Preferred Job Location:</label>
        <input type="text" class="form-control" id="preferred_job_location" placeholder="Enter Preferred Job Location" name="preferred_job_location" value="<?php echo $fields['first_name']; ?>">
        <?php echo form_error('preferred_job_location'); ?>
    </div>

    <div class="col-md-3">
        <label for="expected_salary_percentage" class="label">Expected Salary(in %) :</label>
        <input type="text" class="form-control" id="expected_salary_percentage" placeholder="Enter Expected Salary" name="expected_salary" value="<?php echo $fields['first_name']; ?>">
        <?php echo form_error('expected_salary_percentage'); ?>
    </div>
    <div class="col-md-3">
        <label for="date_of_birth" class="label">Date of Birth:</label>
        <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="date_of_birth" placeholder="Enter DOB" name="date_of_birth" value="<?php echo $fields['date_of_birth']; ?>">
        <?php echo form_error('date_of_birth'); ?>
    </div>

    <div class="col-md-3">
        <label for="age" class="label">Age:</label>
        <input type="number" class="form-control" id="age" placeholder="Enter Age" name="age"  maxlength="2" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('age'); ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-3">
        <label for="nationality" class="label">Nationality:</label>
        <input type="text" class="form-control" id="nationality" placeholder="Enter Nationality" name="nationality" value="<?php echo $fields['first_name']; ?>">
        <?php echo form_error('nationality'); ?>
    </div>

    <div class="col-md-3">
        <label for="work_authorization" class="label">Work Authorization:</label>
        <input type="text" class="form-control" id="work_authorization" placeholder="Enter Work Authorization" name="work_authorization" value="<?php echo $fields['first_name']; ?>">
        <?php echo form_error('work_authorization'); ?>
    </div>
    <div class="col-md-3">
        <label for="industry" class="label">Industry:</label>
        <input type="text" class="form-control" id="industry" placeholder="Enter Industry" name="industry" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('industry'); ?>
    </div>

    <div class="col-md-3">
        <label for="candidate_source" class="label">Candidate Source:</label>
        <input type="text" class="form-control" id="candidate_source" placeholder="Enter Candidate Source" name="candidate_source" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('candidate_source'); ?>
    </div>
</div>


<div class="form-group row">
    <div class="col-md-12">
        <label for="remarks" class="label">Remarks:</label>
        <textarea class="form-control" name="remarks" placeholder="Enter Remarks" maxlength="255" title="remarks" id="remarks" value="<?php echo $fields['first_name']; ?>"></textarea>
        <?php echo form_error('remarks'); ?>
    </div>
</div>

<h3>Contact Info</h3>
<hr>
<div class="form-group row" style="margin-top: 20px;">
    <div class="col-md-4">
        <label for="email_address" class="label">Email:</label>
        <input type="email_address" class="form-control" id="email_address" placeholder="Enter email" name="email_address" value="<?php echo $fields['email_address']; ?>">
        <?php echo form_error('email_address'); ?>
    </div>

    <div class="col-md-4">
        <label for="mobile_number" class="label">Mobile Number:</label>
        <input type="phone" class="form-control" id="mobile_number" placeholder="Enter Mobile Number" name="mobile_number" maxlength="10" value="<?php echo $fields['mobile_number']; ?>">
        <?php echo form_error('mobile_number'); ?>
    </div>

    <div class="col-md-4">
        <label for="landline_number" class="label">Landline Number:</label>
        <input type="number" class="form-control" id="landline_number" placeholder="Enter Landline Number" name="landline_number" value="<?php echo $fields['age']; ?>">
        <?php echo form_error('landline_number'); ?>
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
    <div class="col-md-6">
        <label for="address" class="label">Address:</label>
        <input type="textarea" class="form-control" id="address" placeholder="Enter Address" name="address" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('address'); ?>
    </div>

    <div class="col-md-6">
        <label for="current_location" class="label">Current Location:</label>
        <input type="textarea" class="form-control" id="current_location" placeholder="Enter Current Location" name="current_location" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('current_location'); ?>
    </div>
</div>

<h3>Social Network</h3>
<hr>
<div class="form-group row">
    <div class="col-md-4">
        <label for="linkedin_url">LinkedIn Url:</label>
        <input type="url" class="form-control" id="linkedin_url" placeholder="Enter LinkedIn Url" name="linkedin" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('linkedin_url'); ?>
    </div>
    <div class="col-md-4">
        <label for="facebook_url">Facebook Url:</label>
        <input type="url" class="form-control" id="facebook_url" placeholder="Enter Facebook Url" name="facebook" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('facebook_url'); ?>
    </div>
    <div class="col-md-4">
        <label for="twitter_url">Twitter Url:</label>
        <input type="url" class="form-control" id="twitter_url" placeholder="Enter Twitter Url" name="twitter" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('twitter_url'); ?>
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

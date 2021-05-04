   <div class="form-group row">
    <div class="col-md-6">
      <label for="center_name">Center Name:</label>
      <input type="text" class="form-control" id="center_name" placeholder="Enter Center Name" name="center_name" value="<?php echo $fields['center_name']; ?>">
      <?php echo form_error('center_name'); ?>
    </div>

    <div class="col-md-6">
      <label for="center_type">Center Type:</label>
      <input type="text" class="form-control" id="center_type" placeholder="Enter Center Type" name="center_type" value="<?php echo $fields['center_type']; ?>">
      <?php echo form_error('center_type'); ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-6">
      <label for="first_name">Candidate First Name:</label>
      <input type="text" class="form-control" id="first_name" placeholder="Enter Candidate First Name" name="first_name" value="<?php echo $fields['first_name']; ?>">
      <?php echo form_error('first_name'); ?>
    </div>

    <div class="col-md-6">
      <label for="last_name">Candidate Last Name:</label>
      <input type="text" class="form-control" id="last_name" placeholder="Enter Candidate Last Name" name="last_name" value="<?php echo $fields['last_name']; ?>">
      <?php echo form_error('last_name'); ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-6">
      <label for="candidate_registration_id">Candidate Registration ID:</label>
      <input type="text" class="form-control" id="candidate_registration_id" placeholder="Candidate Registration ID" name="candidate_registration_id" value="<?php echo $fields['candidate_registration_id']; ?>">
      <?php echo form_error('candidate_registration_id'); ?>
    </div>

    <div class="col-md-6">
      <label for="candidate_enrollment_id">Candidate Enrollment ID:</label>
      <input type="text" class="form-control" id="candidate_enrollment_id" placeholder="Candidate Enrollment ID" name="candidate_enrollment_id" value="<?php echo $fields['candidate_enrollment_id']; ?>">
      <?php echo form_error('candidate_enrollment_id'); ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-6">
      <label for="education_id">Minimum Education:</label>
      <select class="form-control" name="education_id">
        <option value="0" <?php echo ($fields['education_id']==0) ? 'selected' : '' ?>>Select Education</option>
        <?php foreach($education_options as $edu_option): ?>
          <option value="<?php echo $edu_option->id; ?>" <?php echo ($edu_option->id==$fields['education_id']) ? 'selected' : '' ?> > <?php echo $edu_option->name; ?></option>
        <?php endforeach; ?>
      </select>
      <?php echo form_error('education_id'); ?>
    </div>
    <div class="col-md-6">
      <label for="qualification_pack_id" <?php echo ($fields['qualification_pack_id']==0) ? 'selected' : '' ?> >Qualification Pack:</label>
      <select class="form-control" name="qualification_pack_id">
        <option value="0">Select Qualification Pack</option>
        <?php foreach($qualification_pack_options as $qp_option): ?>
          <option value="<?php echo $qp_option->id; ?>" <?php echo ($qp_option->id==$fields['qualification_pack_id']) ? 'selected' : '' ?> ><?php echo $qp_option->name; ?></option>
        <?php endforeach; ?>
      </select>
      <?php echo form_error('qualification_pack_id'); ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-6">
      <label for="email">Email:</label>
      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php echo $fields['email']; ?>">
      <?php echo form_error('email'); ?>
    </div>

    <div class="col-md-6">
      <label for="mobile_number">Mobile Number:</label>
      <input type="phone" class="form-control" id="mobile_number" placeholder="Enter Phone" name="mobile_number" value="<?php echo $fields['mobile_number']; ?>">
      <?php echo form_error('mobile_number'); ?>
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
      <label for="state">Select State:</label>
      <select class="form-control" name="state" id="state_id">
        <option value="0" <?php echo ($fields['main_state_id']==0) ? 'selected' : '' ?>>Select State</option>
        <option value="<?php echo $fields['main_state_id']; ?>" selected><?php echo $fields['state_name']; ?></option>
      </select>
      <?php echo form_error('state'); ?>
    </div>

    <div class="col-md-4">
      <label for="district_id">District:</label>
      <select class="form-control" name="district_id" id="district_id">
        <option value="<?php echo $fields['main_district_id']; ?>" selected><?php echo $fields['district_name']; ?></option>
      </select>
      <?php echo form_error('district_id'); ?>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-md-6">
      <label for="marital_status">Marital Status:</label>
      <select class="form-control" name="marital_status">
        <?php foreach($marrige_options as $marrige):?>
          <option value="<?php echo $marrige; ?>" <?php echo ($marrige==$fields['marital_status']) ? 'selected' : '' ?> ><?php echo $marrige; ?></option>
        <?php endforeach; ?>
      </select>
      <?php echo form_error('marital_status'); ?>
    </div>

    <div class="col-md-6">
      <label for="gender_code">Gender:</label>
      <select class="form-control" name="gender_code">
        <?php foreach($gender_options as $gender):?>
          <option value="<?php echo $gender; ?>" <?php echo ($gender==$fields['gender_code']) ? 'selected' : '' ?> ><?php echo $gender; ?></option>
        <?php endforeach; ?>
      </select>
      <?php echo form_error('gender_code'); ?>
    </div>
  </div>

  <button type="submit" class="btn btn-primary">Submit</button>

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

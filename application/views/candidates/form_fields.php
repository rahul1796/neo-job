<style>
    .label{
        float: left;
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
<?php $this->load->view('layouts\soft_error'); ?>
<div class="form-group row" style="margin-top: 20px;">
 <div class="col-md-4">
   <label for="candidate_name" class="label">Candidate Name:</label>
   <input type="text" class="form-control" id="candidate_name" placeholder="Enter Candidate Name" name="candidate_name" value="<?php echo $fields['candidate_name']; ?>">
   <?php echo form_error('candidate_name'); ?>
 </div>

<!-- <div class="col-md-4">
   <label for="candidate_number" class="label">Candidate Registration Number:</label>
   <input type="text" class="form-control" id="candidate_number" placeholder="Enter Candidate Registration Number" name="candidate_number" value="<?php //echo $fields['candidate_number']; ?>">
   <?php //echo form_error('candidate_number'); ?>
 </div>-->

    <div class="col-md-4">
        <label for="gender_id" class="label">Gender:</label>
        <select class="form-control" name="gender_id">
            <option value="">Select Gender</option>
            <?php foreach($gender_options as $gender):?>
              <?php if($gender->id!=4): ?>
                <option value="<?php echo $gender->id; ?>" <?php echo ($gender->id==$fields['gender_id']) ? 'selected' : '' ?> ><?php echo $gender->name; ?></option>
              <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('gender_id'); ?>
    </div>

     <div class="col-md-4">
       <input type="hidden" name="id" value="<?= $id;?>">
   <label for="aadhaar_number" class="label">Aadhaar Number:</label>
   <input type="text" class="form-control" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "12" id="aadhaar_number" placeholder="Enter Aadhaar Number" name="aadhaar_number" value="<?php echo $fields['aadhaar_number']; ?>">
   <?php echo form_error('aadhaar_number'); ?>
 </div>
</div>

<div class="form-group row">
    <div class="col-md-3">
        <label for="prefered_job_location" class="">Preferred Job Location:</label>
        <input type="text" class="form-control" id="prefered_job_location" placeholder="Enter Preferred Job Location" name="prefered_job_location" value="<?php echo $fields['prefered_job_location']; ?>">
        <?php echo form_error('prefered_job_location'); ?>
    </div>

    <div class="col-md-3">
        <label for="expected_salary_percentage" class="label">Expected Salary(in %) :</label>
        <input type="text" class="form-control" id="expected_salary_percentage" placeholder="Enter Expected Salary" name="expected_salary_percentage" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "3" value="<?php echo $fields['expected_salary_percentage']; ?>">
        <?php echo form_error('expected_salary_percentage'); ?>
    </div>
    <div class="col-md-3">
        <label for="date_of_birth" class="label">Date of Birth:</label>
        <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="date_of_birth" placeholder="Enter Date of Birth" name="date_of_birth" value="<?php echo $fields['date_of_birth']; ?>">
        <?php echo form_error('date_of_birth'); ?>
    </div>

    <div class="col-md-3">
        <label for="age" class="label">Age:</label>
        <input type="number" class="form-control" id="age" placeholder="Enter Age" name="age" readonly maxlength="2" value="<?php echo $fields['age']; ?>">
        <?php echo form_error('age'); ?>
    </div>
</div>

<div class="form-group row">
  <div class="col-md-6">
    <input type="hidden" name="action" value="<?= ($action=='edit') ? 'edit' : 'create'; ?>">
    <label for="marital_status_id" class="label">Marital Status:</label>
    <select class="form-control" name="marital_status_id">
      <option value="">Select Marital Status</option>
      <?php foreach($marrige_options as $option):?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['marital_status_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('marital_status_id'); ?>
  </div>

<!--  <div class="col-md-4">
    <label for="religion_id" class="label">Religion:</label>
    <select class="form-control" name="religion_id">
      <option value="">Select Religion</option>
      <?php //foreach($religion_options as $option):?>
        <option value="<?php //echo $option->id; ?>" <?php //echo ($option->id==$fields['religion_id']) ? 'selected' : '' ?> ><?php //echo $option->name; ?></option>
      <?php //endforeach; ?>
    </select>
    <?php //echo form_error('religion_id'); ?>
  </div>-->

<!--  <div class="col-md-4">
    <label for="caste_category_id" class="label">Caste Category:</label>
    <select class="form-control" name="caste_category_id">
      <option value="">Select Caste Category</option>
      <?php //foreach($caste_category_options as $option):?>
        <option value="<?php //echo $option->id; ?>" <?php //echo ($option->id==$fields['caste_category_id']) ? 'selected' : '' ?> ><?php //echo $option->name; ?></option>
      <?php //endforeach; ?>
    </select>
    <?php //echo form_error('caste_category_id'); ?>
  </div>-->
</div>

<div class="form-group row">
    <div class="col-md-4">
        <label for="nationality" class="label">Nationality:</label>
        <input type="text" class="form-control" id="nationality" placeholder="Enter Nationality" name="nationality" value="<?php echo $fields['nationality']; ?>">
        <?php echo form_error('nationality'); ?>
    </div>

<!--    <div class="col-md-3">
        <label for="work_authorization_id" class="label">Work Authorization:</label>
        <select class="form-control" name="work_authorization_id">
          <option value="">Select Work Authorization</option>
          <?php //foreach($work_authorization_options as $option): ?>
            <option value="<?php //echo $option->id; ?>" <?php //echo ($option->id==$fields['work_authorization_id']) ? 'selected' : '' ?> ><?php //echo $option->name; ?></option>
          <?php //endforeach; ?>
        </select>
        <?php //echo form_error('work_authorization_id'); ?>
    </div>-->

    <div class="col-md-4">
        <label for="industry_id" class="label">Industry:</label>
        <select class="form-control" name="industry_id">
            <option value="">Select Industry</option>
            <?php foreach($industry_options as $option):?>
                <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['gender_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('industry_id'); ?>
    </div>

    <div class="col-md-4">
        <label for="source_id" class="label">Candidate Source:</label>
        <select class="form-control" name="source_id">
            <option value="">Select Candidate Source</option>
            <?php foreach($candidate_source_options as $option):?>
                <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['source_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('source_id'); ?>
    </div>
</div>


<div class="form-group row">
    <div class="col-md-12">
        <label for="remarks" class="">Remarks:</label>
        <textarea class="form-control" name="remarks" placeholder="Enter Remarks" maxlength="255" title="remarks" id="remarks" value=""><?php echo $fields['remarks']; ?></textarea>
        <?php echo form_error('remarks'); ?>
    </div>
</div>

<h3>Contact Info</h3>
<hr>
<div class="form-group row" style="margin-top: 20px;">
    <div class="col-md-6">
        <label for="email" class="label">Email:</label>
        <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?php echo $fields['email']; ?>">
        <?php echo form_error('email'); ?>
    </div>

    <div class="col-md-6">
        <label for="mobile" class="label">Contact:</label>
        <input type="text" class="form-control" id="mobile" placeholder="Enter Contact Number" name="mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, ''); this.value = this.value.replace(/(\..*)\./g, '$1');" onKeyDown="if(this.value.length==10 && event.keyCode!=8) return false;"value="<?php echo $fields['mobile']; ?>">
        <?php echo form_error('mobile'); ?>
    </div>

<!--    <div class="col-md-4">
        <label for="landline" class="label">Landline Number:</label>
        <input type="tel" class="form-control" id="landline" placeholder="Enter Landline Number" name="landline" maxlength="10" value="<?php echo $fields['landline']; ?>">
        <?php //echo form_error('landline'); ?>
    </div>-->
</div>

<div class="form-group row">
  <div class="col-md-4">
      <label for="country_id" class="label">Country:</label>
      <select class="form-control" name="country_id" id="country_id">
          <option value="0">Select Country</option>
          <?php foreach($countries_options as $country_option): ?>
              <option value="<?php echo $country_option->id; ?>" <?php echo (intval($country_option->id)==99) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('country_id'); ?>
  </div>

  <div class="col-md-4">
      <label for="state_id" class="label">State:</label>
      <select class="form-control" name="state_id" id="state_id">
          <option value="0">Select State</option>
      </select>
      <?php echo form_error('state_id'); ?>
  </div>

  <div class="col-md-4">
      <label for="district_id" class="label">District/City:</label>
      <select class="form-control" name="district_id" id="district_id">
          <option value="0">Select District/City</option>
      </select>
      <?php echo form_error('district_id'); ?>
  </div>


</div>

<div class="form-group row">

    <div class="col-md-4">
   <label for="city" class="">Town / Village:</label>
   <input type="text" class="form-control" id="city" placeholder="Enter City" name="city" value="<?php echo $fields['city']; ?>">
   <?php echo form_error('city'); ?>
 </div>

   <div class="col-md-4">
     <label for="landline" class="label">Pincode:</label>
     <input type="text" class="form-control" id="pincode" placeholder="Enter Pincode" name="pincode" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "6" value="<?php echo $fields['pincode'] ?? ''; ?>">
     <?php echo form_error('pincode'); ?>
   </div>

</div>


<div class="form-group row">
    <div class="col-md-4">
        <label for="address" class="label">Address:</label>
        <input type="textarea" class="form-control" id="address" placeholder="Enter Address" name="address" value="<?php echo $fields['address']; ?>">
        <?php echo form_error('address'); ?>
    </div>

    <div class="col-md-4">
        <label for="current_location" class="">Current Location:</label>
        <input type="textarea" class="form-control" id="current_location" placeholder="Enter Current Location" name="current_location" value="<?php echo $fields['current_location']; ?>">
        <?php echo form_error('current_location'); ?>
    </div>
</div>

<h3>Social Network</h3>
<hr>
<div class="form-group row">
  <input type="hidden" name="mt_type" value="<?= ($fields['mt_type']!='') ? $fields['mt_type'] : 'MTO'; ?>">
    <div class="col-md-4">
        <label for="linkedin_url">LinkedIn Url:</label>
        <input type="text" class="form-control" id="linkedin_url" placeholder="Enter LinkedIn Url" name="linkedin_url" value="<?php echo $fields['linkedin_url']; ?>">
        <?php echo form_error('linkedin_url'); ?>
    </div>
    <div class="col-md-4">
        <label for="facebook_url">Facebook Url:</label>
        <input type="text" class="form-control" id="facebook_url" placeholder="Enter Facebook Url" name="facebook_url" value="<?php echo $fields['facebook_url']; ?>">
        <?php echo form_error('facebook_url'); ?>
    </div>
    <div class="col-md-4">
        <label for="twitter_url">Twitter Url:</label>
        <input type="text" class="form-control" id="twitter_url" placeholder="Enter Twitter Url" name="twitter_url" value="<?php echo $fields['twitter_url']; ?>">
        <?php echo form_error('twitter_url'); ?>
    </div>
</div>
<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

<script type="text/javascript">

$(document).ready(function() {
  $('#date_of_birth').datepicker()
    .on('changeDate', function(e) {
      console.log(e);
      let years = new Date(new Date() - new Date(e.date)).getFullYear() - 1970;
      $('#age').val(years);
    });
});
</script>
<script type="text/javascript">

  var country_id = <?= (!empty($fields['country_id'])) ? $fields['country_id'] : 99 ?>;
  var state_id = <?= (!empty($fields['state_id'])) ? $fields['state_id'] : 0 ?>;
  var district_id = <?= (!empty($fields['district_id'])) ? $fields['district_id'] : 0 ?>;


 $(document).ready(function() {

   if(country_id!=0){
     getStates(country_id);
     if(state_id!=0) {
        getDistricts(state_id);

     }
   }

   $('#country_id').on('change', function() {
     country_id = $(this).val();
     $('#state_id').html('');
     $('#state_id').append($('<option>').text('Select State').attr('value', 0));
     state_id=0;
     $('#state_id').val(state_id).change();
     district_id=0;
     $('#district_id').val(district_id).change();
     if(country_id!=0) {
        getStates(country_id);
     }
   });

   $('#state_id').on('change', function() {
     state_id = $(this).val();
     $('#district_id').html('');
     $('#district_id').append($('<option>').text('Select District').attr('value', 0));
     if(state_id!=0) {
        getDistricts(state_id);
     }
   });

 });

 function getDistricts(id){
   var request = $.ajax({
     url: "<?= base_url(); ?>master/getDistricts/"+id,
     type: "GET",
   });

   request.done(function(msg) {
     var response = JSON.parse(msg);
     // alert(response);
     $('#district_id').html('');
     $('#district_id').append($('<option>').text('Select District').attr('value', 0));
     response.forEach(function(district) {
        $('#district_id').append($('<option>').text(district.name).attr('value', district.id));
     })
     $('#district_id').val(district_id).change();
   });

   request.fail(function(jqXHR, textStatus) {
     alert( "Request failed: " + textStatus );
   });
 }


 function getStates(id) {
   var request = $.ajax({
     url: "<?php echo base_url(); ?>master/getStates/"+id,
     type: "GET",
   });

   request.done(function(msg) {
     var response = JSON.parse(msg);
     // alert(response);
     $('#state_id').html('');
     $('#state_id').append($('<option>').text('Select State').attr('value', 0));
     response.forEach(function(state) {
        $('#state_id').append($('<option>').text(state.name).attr('value', state.id));
     });
     $('#state_id').val(state_id).change();
   });

   request.fail(function(jqXHR, textStatus) {
     alert( "Select Valid Value for the Country" );
   });
 }

  $('#date_of_birth').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
</script>

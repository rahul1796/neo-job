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
        <label for="customer_id" class="label">Client:</label>
        <select class="form-control select2-neo" name="customer_id">
            <option value="">Select Client</option>
            <?php foreach($customer_options as $customer):?>
                <option value="<?php echo $customer->id; ?>" <?php echo ($customer->id==$fields['customer_id']) ? 'selected' : '' ?> ><?php echo $customer->customer_name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('customer_id'); ?>
    </div>
 <div class="col-md-4">
   <label for="job_title" class="label">Job Title:</label>
   <input type="text" class="form-control" id="job_title" placeholder="Enter Job Title" name="job_title" value="<?php echo $fields['job_title']; ?>">
   <?php echo form_error('job_title'); ?>
 </div>

 <div class="col-md-4">
   <label for="job_description" class="label">Job Description:</label>
   <input type="text" class="form-control" id="job_description" placeholder="Enter Job Description" name="job_description" value="<?php echo $fields['job_description']; ?>">
   <?php echo form_error('job_description'); ?>
 </div>

</div>

<div class="form-group row">
    <div class="col-md-4">
        <label for="no_of_position" class="label">Number of Vacancies:</label>
        <input type="tel" class="form-control" id="no_of_position" placeholder="Enter Number of Vacancies" name="no_of_position" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "3" value="<?php echo $fields['no_of_position']; ?>">
        <?php echo form_error('no_of_position'); ?>
    </div>

    <div class="col-md-4">
        <label for="job_expiry_date" class="label">Job Expiry Date:</label>
        <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="job_expiry_date" placeholder="Select Date" name="job_expiry_date" value="<?= ($fields['job_expiry_date']!='') ? date_format(date_create($fields['job_expiry_date']),'d-M-Y') : '' ;?>">
        <?php echo form_error('job_expiry_date'); ?>
    </div>

    <div class="col-md-4">
        <label for="customer_manager" class="">Client Manager:</label>
        <input type="text" class="form-control" id="customer_manager" placeholder="Enter Client Manager" name="customer_manager" value="<?php echo $fields['customer_manager']; ?>">
        <?php echo form_error('customer_manager'); ?>
    </div>
</div>

<div class="form-group row">
<!--    <div class="col-md-3" style="display:none;">
        <label for="applicable_consulting_fee" class="">Applicable Consulting Fee:</label>
        <input type="tel" class="form-control" id="applicable_consulting_fee" placeholder="Enter Applicable Consulting Fee in INR" name="applicable_consulting_fee" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php //echo $fields['applicable_consulting_fee']; ?>">
        <?php// echo form_error('applicable_consulting_fee'); ?>
    </div>-->

    <div class="col-md-6">
        <label for="business_vertical_id" class="label">Business Vertical:</label>
        <select class="form-control" name="business_vertical_id">
            <option value="">Select Business Vertical</option>
            <?php foreach($business_vertical_options as $bv):?>
                <option value="<?php echo $bv->id; ?>" <?php echo ($bv->id==$fields['business_vertical_id']) ? 'selected' : '' ?> ><?php echo $bv->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('business_vertical_id'); ?>
    </div>

<!--    <div class="col-md-3">
        <label for="practice" class="">Business Practice:</label>
        <input type="text" class="form-control" id="Practice" placeholder="Enter Business Practice" name="practice" value="<?php //echo $fields['practice']; ?>">
        <?php //echo form_error('practice'); ?>
    </div>-->

    <div class="col-md-6">
        <label for="office_location" class="label">Office Location:</label>
        <input type="text" class="form-control" id="office_location" placeholder="Enter Office Location " name="office_location" value="<?php echo $fields['office_location']; ?>">
        <?php echo form_error('office_location'); ?>
    </div>
</div>


<div class="form-group row">
    <div class="col-md-3">
        <label for="age_from" class="label">Candidate Min Age:</label>
        <input type="text" class="form-control" id="age_from" placeholder="Enter Min Candidate Age" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" name="age_from" value="<?php echo $fields['age_from']; ?>">
        <?php echo form_error('age_from'); ?>
    </div>

    <div class="col-md-3">
        <label for="age_to" class="label">Candidate Max Age:</label>
        <input type="text" class="form-control" id="age_to" placeholder="Enter Max Candidate Age" name="age_to" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['age_to']; ?>">
        <?php echo form_error('age_to'); ?>
    </div>

    <div class="col-md-3">
        <label for="offered_ctc_from" class="label">Min CTC per Month:</label>
        <input type="tel" class="form-control" id="offered_ctc_from" placeholder="Min CTC Offered by Employer" name="offered_ctc_from" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "8" value="<?php echo $fields['offered_ctc_from']; ?>">
        <?php echo form_error('offered_ctc_from'); ?>
    </div>

    <div class="col-md-3">
        <label for="offered_ctc_to" class="label">Max CTC per Month:</label>
        <input type="tel" class="form-control" id="offered_ctc_to" placeholder="Max CTC Offered by Employer" name="offered_ctc_to" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "8" value="<?php echo $fields['offered_ctc_to']; ?>">
        <?php echo form_error('offered_ctc_to'); ?>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-12">
        <label for="key_skills" class="label">Key Skills:</label>
        <textarea class="form-control" name="key_skills" placeholder="Enter Key Skill eg: (Java, Plumbing, PHP, hardware)" maxlength="255" title="key_skills" id="key_skills" value=""><?php echo $fields['key_skills']; ?></textarea>
        <?php echo form_error('key_skills'); ?>
    </div>
</div>

<div class="form-group row" style="margin-top: 20px;">

  <div class="col-md-4">
      <label for="functional_area_id" class="">Functional Area:</label>
      <select class="form-control" name="functional_area_id">
          <option value="">Select Functional Area</option>
          <?php foreach($functional_area_options as $option):?>
              <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['functional_area_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('functional_area_id'); ?>
  </div>

  <div class="col-md-4">
      <label for="industry_id" class="label">Industry :</label>
      <select class="form-control" name="industry_id">
          <option value="">Select Industry</option>
          <?php foreach($industry_options as $option):?>
              <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['industry_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('industry_id'); ?>
  </div>

    <div class="col-md-4">
        <label for="primary_skills" class="">Primary Skills:</label>
        <input type="text" class="form-control" id="primary_skills" placeholder="Enter Primary Skills" name="primary_skills" value="<?php echo $fields['primary_skills']; ?>">
        <?php echo form_error('primary_skills'); ?>
    </div>
</div>


<div class="form-group row">
    <div class="col-md-4">
        <label for="reference_id">Job Profile ID:</label>
        <input type="text" class="form-control" id="reference_id" placeholder="Enter Job Profile ID" name="reference_id" value="<?php echo $fields['reference_id']; ?>">
        <?php echo form_error('reference_id'); ?>
    </div>

    <div class="col-md-4">
        <label for="education_id" class="label">Min Education:</label>
        <select class="form-control" name="education_id">
            <option value="">Select Education</option>
            <?php foreach($education_options as $option):?>
                <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['education_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('education_id'); ?>
    </div>

<!--    <div class="col-md-4">
        <label for="job_open_type_id" class="">Job Open Type:</label>
        <select class="form-control" name="job_open_type_id">
            <option value="">Select Type </option>
            <?php //foreach($job_open_type_options as $option):?>
                <option value="<?php //echo $option->id; ?>" <?php //echo ($option->id==$fields['job_open_type_id']) ? 'selected' : '' ?> ><?php //echo $option->name; ?></option>
            <?php //endforeach; ?>
        </select>
        <?php //echo form_error('job_open_type_id'); ?>
    </div>-->

</div>

<div class="form-group row">

    <div class="col-md-6">
      <label for="district_id" class="label">Assign Recruiter:</label>
      <select class="select2-neo form-control" multiple name="recruiters[]">
        <option value="">Select Recruiters</option>
        <?php foreach($recruiters_options as $option): ?>
            <option value="<?php echo $option->id; ?>" <?= (in_array($option->id, $fields['recruiters'])) ? 'selected':'' ?> ><?php echo $option->name; ?></option>
        <?php endforeach; ?>
    </select>
    <?php echo form_error('recruiters[]'); ?>
    </div>

    <div class="col-md-6">
      <label for="district_id" class="label">Assign Placement Manager:</label>
      <select class="select2-neo form-control" multiple name="placement_officers[]">
        <option value="">Select Placement Officers</option>
        <?php foreach($placement_officer_options as $option): ?>
            <option value="<?php echo $option->id; ?>" <?= (in_array($option->id, $fields['placement_officers'])) ? 'selected':'' ?> ><?php echo $option->name; ?></option>
        <?php endforeach; ?>
    </select>
    <?php echo form_error('placement_officers[]'); ?>
    </div>


</div>

<div class="form-group row">
  <div class="col-md-4">
      <label for="qualification_pack_id" class="label">Qualification Pack:</label>
      <select class="form-control select2-neo" name="qualification_pack_id">
          <option value="">Select Qualification Pack</option>
          <?php foreach($qualification_pack_options as $option):?>
              <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['qualification_pack_id']) ? 'selected' : '' ?> ><?php echo "{$option->name} ({$option->code})"; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('qualification_pack_id'); ?>
  </div>

    <div class="col-md-4">
        <label for="job_priority_level_id" class="">Job Priority Level:</label>
        <select class="form-control" name="job_priority_level_id">
            <option value="">Select Priority</option>
            <?php foreach($job_priority_level_options as $option):?>
                <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['job_priority_level_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('job_priority_level_id'); ?>
    </div>

    <div class="col-md-4">
        <label for="gender_id" class="label">Preferred Gender:</label>
        <select class="form-control" name="gender_id">
            <option value="">Select Gender</option>
            <?php foreach($gender_options as $option):?>
                <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['gender_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
            <?php endforeach; ?>
        </select>
        <?php echo form_error('gender_id'); ?>
    </div>

    <!-- <div class="col-md-3">
        <label for="job_status_id" class="label">Job Status:</label>
        <select class="form-control" name="job_status_id">
            <option value="">Select Status</option>
            <?php /* foreach($job_status_options as $option):?>
                <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['job_status_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
            <?php endforeach; */?>
        </select>
        <?php //echo form_error('job_status_id'); ?>
    </div> -->
</div>

<div class="form-group row">
  <div class="col-md-4">
      <label for="country_id" class="label">Country:</label>
      <select class="form-control" name="country_id" id="country_id">
          <option value="0">Select Country</option>
          <?php foreach($countries_options as $country_option): ?>
              <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$fields['country_id']) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
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
<!--  <div class="col-md-3">
    <label for="location_id" class="label">Location:</label>
    <select class="form-control select2-neo" name="location_id" id="location_id">
      <option value="">Select Location</option>
      <?php // foreach($location_options as $option): ?>
        <option value="<?//= $option->location_id; ?>" <?//= ($option->location_id==$fields['location_id']) ? 'selected' : '' ?> ><? //= $option->location_name; ?></option>
      <?php // endforeach; ?>
    </select>
    <?php // echo form_error('location_id'); ?>
  </div>-->

    <div class="col-md-4">
        <label for="shifts_available" class="">Shifts Avaiable:</label>
        <input type="text" class="form-control" id="shifts_available" placeholder="Enter Shifts" name="shifts_available" value="<?php echo $fields['shifts_available']; ?>">
        <?php echo form_error('shifts_available'); ?>
    </div>

    <div class="col-md-4">
        <label for="preferred_nationality" class="">Preferred Nationality:</label>
        <input type="text" class="form-control" id="preferred_nationality" placeholder="Enter Preferred Nationality" name="preferred_nationality" value="<?php echo $fields['preferred_nationality']; ?>">
        <?php echo form_error('preferred_nationality'); ?>
    </div>

    <div class="col-md-4">
        <label for="no_poach_companies">Poach Companies:</label>
        <input type="text" class="form-control" id="no_poach_companies" placeholder="Enter Poach Companies number" name="no_poach_companies" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['no_poach_companies']; ?>">
        <?php echo form_error('no_poach_companies'); ?>
    </div>

  </div>


  <div class="form-group row">
      <div class="col-md-3">
          <label for="experience_from" class="">Experience From:</label>
          <input type="text" class="form-control" id="experience_from" placeholder="Enter Experience From" name="experience_from" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['experience_from']; ?>">
          <?php echo form_error('experience_from'); ?>
      </div>

      <div class="col-md-3">
          <label for="experience_to" class="">Experience To:</label>
          <input type="text" class="form-control" id="experience_to" placeholder="Enter Experience To" name="experience_to" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['experience_to']; ?>">
          <?php echo form_error('experience_to'); ?>
      </div>

      <div class="col-md-3">
          <label for="relevent_experience_from" class="label">Relevent Experience From:</label>
          <input type="text" class="form-control" id="relevent_experience_from" placeholder="Enter Relevent Experince from" name="relevent_experience_from" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['relevent_experience_from']; ?>">
          <?php echo form_error('relevent_experience_from'); ?>
      </div>

      <div class="col-md-3">
          <label for="relevant_experience_to" class="label">Relevent Experience To:</label>
          <input type="text" class="form-control" id="relevant_experience_to" placeholder="Enter Relevent Experince to" name="relevant_experience_to" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['relevant_experience_to']; ?>">
          <?php echo form_error('relevant_experience_to'); ?>
      </div>

    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label for="remarks">Remarks:</label>
            <input type="text" class="form-control" id="remarks" placeholder="Enter Remarks" name="remarks" value="<?php echo $fields['remarks']; ?>">
            <?php echo form_error('remarks'); ?>
        </div>

        <div class="col-md-4">
            <label for="comments">Comments:</label>
            <input type="text" class="form-control" id="comments" placeholder="Enter Comments" name="comments" value="<?php echo $fields['comments']; ?>">
            <?php echo form_error('comments'); ?>
        </div>

        <div class="col-md-4">
            <label for="target_employers">Target Employers:</label>
            <input type="text" class="form-control" id="target_employers" placeholder="Target Employers detail" name="target_employers" value="<?php echo $fields['target_employers']; ?>">
            <?php echo form_error('target_employers'); ?>
        </div>

      </div>

      <button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

      <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

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

 $(document).ready(function() {

   $('.select2-neo').select2();
 });

 $('#job_expiry_date').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
</script>
 <script type="text/javascript">

  var country_id = <?= (!empty($fields['country_id'])) ? $fields['country_id'] : 0 ?>;
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
</script>

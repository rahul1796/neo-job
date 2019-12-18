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
<div class="form-group row"  style="margin-top: 20px;">

  <div class="col-md-4">
    <label for="center_id" class="label">Center ID:</label>
    <input type="text" class="form-control" id="center_id" placeholder="Enter Center ID" name="center_id" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "6" value="<?php echo $fields['center_id']; ?>">
    <?php echo form_error('center_id'); ?>
  </div>

  <div class="col-md-4">
    <label for="center_name" class="label">Center Name:</label>
    <input type="text" class="form-control" id="center_name" placeholder="Enter Center Name" name="center_name" maxlength="30" value="<?php echo $fields['center_name']; ?>">
    <?php echo form_error('center_name'); ?>
  </div>

<!--  <div class="col-md-4">
    <label for="status" class="label">Status:</label>
    <select class="form-control" name="status" id="status">
        <?php //foreach([0=>'Inactive',1=>'Active'] as $key => $value):?>
            <option value="<?//= $key; ?>" <?php //echo ($key==$fields['status']) ? 'selected' : '' ?> ><?//= $value; ?></option>
        <?php //endforeach; ?>
    </select>
    <?php //echo form_error('status'); ?>
  </div>-->

</div>

<div class="form-group row">
  <div class="col-md-6">
      <label for="center_type" class="label">Select Center Type:</label>
      <select class="form-control" name="center_type" id="center_type">
          <option value="">Select Center Type</option>
          <?php foreach($center_types_options as $option): ?>
              <option value="<?php echo $option->name; ?>" <?php echo ($option->name==$fields['center_type']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('center_type'); ?>
  </div>

  <div class="col-md-6">
    <label for="address" class="label">Address:</label>
    <input type="text" class="form-control" id="address" placeholder="Enter address" name="address" value="<?php echo $fields['address']; ?>">
    <?php echo form_error('address'); ?>
  </div>
</div>

<div class="form-group row">
 <div class="col-md-4">
   <label for="location" class="label">Location/Area:</label>
   <input type="text" class="form-control" id="location" placeholder="Enter Location" name="location" value="<?php echo $fields['location']; ?>">
   <?php echo form_error('location'); ?>
 </div>

 <div class="col-md-4">
   <label for="pincode" class="label">Pincode:</label>
   <input type="text" class="form-control" id="pincode" placeholder="Enter Area/Location Pincode" name="pincode" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "6" value="<?php echo $fields['pincode']; ?>">
   <?php echo form_error('pincode'); ?>
 </div>

 <div class="col-md-4">
   <label for="city" class="label">City / Town / Village:</label>
   <input type="text" class="form-control" id="city" placeholder="Enter City" name="city" value="<?php echo $fields['city']; ?>">
   <?php echo form_error('city'); ?>
 </div>

</div>

<div class="form-group row">
  <div class="col-md-3">
      <label for="country_id" class="label">Select Country:</label>
      <select class="form-control" name="country_id" id="country_id">
          <option value="0">Select Country</option>
          <?php foreach($countries_options as $country_option): ?>
              <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$fields['country_id']) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('country_id'); ?>
  </div>

     <div class="col-md-3">
      <label for="region_id" class="label">Select Region:</label>
      <select class="form-control" name="region_id" id="region_id">
          <option value="0">Select Region</option>
          <?php foreach($regions_options as $region_option): ?>
              <option value="<?php echo $region_option->id; ?>" <?php echo ($region_option->id==$fields['region_id']) ? 'selected' : '' ?> ><?php echo $region_option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('region_id'); ?>
  </div>

  <div class="col-md-3">
      <label for="state_id" class="label">Select State:</label>
      <select class="form-control" name="state_id" id="state_id">
          <option value="0">Select State</option>
      </select>
      <?php echo form_error('state_id'); ?>
  </div>

  <div class="col-md-3">
      <label for="district_id" class="label">Select District:</label>
      <select class="form-control" name="district_id" id="district_id">
          <option value="0">Select District</option>
      </select>
      <?php echo form_error('district_id'); ?>
  </div>


</div>

<div class="row form-group">
  <div class="col-md-12">
    <label for="district_id" class="">Assign Center Managers:</label>
    <select class="select2-neo form-control" multiple name="center_managers[]">
      <option value="0">Select Center Managers</option>
      <?php foreach($center_managers_options as $option): ?>
          <option value="<?php echo $option->id; ?>" <?= (in_array($option->id, $fields['center_managers'])) ? 'selected':'' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
  </select>
  <?php echo form_error('center_managers[]'); ?>
  </div>
</div>

<div class="row form-group">
  <div class="col-md-12">
    <input type="submit" name="submit" value="Submit" class="btn btn-primary">
  </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

  $('.select2-neo').select2();
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
     url: "<?= base_url(); ?>candidatescontroller/getDistricts/"+id,
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
     url: "<?php echo base_url(); ?>candidatescontroller/getStates/"+id,
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

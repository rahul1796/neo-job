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
.phone-input{
	margin-bottom:8px;
}
</style>
<?php $this->load->view('layouts\soft_error'); ?>
<div class="form-group row">
    <div class="col-md-6">
   <label for="customer_name" class="label">Company Name:</label>
   <input type="text"  class="form-control" id="customer_name" placeholder="Enter Company Name" name="customer_name" value="<?php echo $fields['customer_name'] ?? ''; ?>">
   <?php echo form_error('customer_name'); ?>
 </div>
 <!--
 <div class="col-md-4">
   <label for="lead_name" class="label">Lead Name:</label>
   <input type="text" class="form-control" id="lead_name" placeholder="Enter Company Name" name="lead_name" value="<?php //echo $fields['lead_name'] ?? ''; ?>">
   <?php //echo form_error('lead_name'); ?>
 </div> -->


 <div class="col-md-6">
   <label for="lead_source_id" class="label">Lead Source:</label>
   <select class="form-control" id="lead_source_id" placeholder="Enter Lead Source" name="lead_source_id">
     <option value="">Select a Lead Source</option>
     <?php foreach($lead_source_options as $lead_option): ?>
       <option value="<?php echo $lead_option->id; ?>" <?php echo ($lead_option->id==$fields['lead_source_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $lead_option->name; ?></option>
     <?php endforeach; ?>
   </select>
  <?php echo form_error('lead_source_id'); ?>
 </div>

</div>

<div class="form-group row">
  <div class="col-md-4">
    <label for="address" class="label">Company Address:</label>
    <input type="text" class="form-control" id="address" placeholder="Enter company address" name="address" value="<?php echo $location_fields['address'] ?? ''; ?>">
    <?php echo form_error('address'); ?>
  </div>
  <div class="col-md-4">
    <label for="lead_managed_by" class="label">Lead Managed By:</label>
    <input type="text" class="form-control" id="lead_managed_by" placeholder="Person Assigned to Lead" name="lead_managed_by" value="<?php echo $fields['lead_managed_by'] ?? ''; ?>">
    <?php echo form_error('lead_managed_by'); ?>
  </div>
     <div class="col-md-4">
    <label for="customer_type" class="label">Select Lead Type:</label>
    <select class="form-control" name="lead_type_id" id="lead_type_id">
      <option value="">Select Lead Type</option>
      <?php foreach($customer_type_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['lead_type_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('lead_type_id'); ?>
  </div>
</div>

<div class="form-group row">
  <div class="col-md-12">
    <label for="customer_description" class="label">Client/Organization Description:</label>
    <textarea name="customer_description" rows="8" class="form-control"><?php echo $fields['customer_description'] ?? ''; ?></textarea>
    <?php echo form_error('customer_description'); ?>
  </div>
</div>

<div class="form-group row">
  <div class="col-md-3">
    <label for="spoc_name" class="label">Company Spoc Name:</label>
    <input type="text" class="form-control" id="spoc_name" placeholder="Enter Spoc Name" name="spoc_detail[0][spoc_name]" value="<?php echo $location_fields['spoc_detail'][0]['spoc_name'] ?? $fields['spoc_name']; ?>">
    <?php echo form_error("spoc_detail[0][spoc_name]"); ?>
  </div>

  <div class="col-md-3">
    <label for="spoc_email" class="label">Company Spoc Email:</label>
    <input type="email" class="form-control" id="spoc_email" placeholder="Enter Spoc Email" name="spoc_detail[0][spoc_email]" value="<?php echo $location_fields['spoc_detail'][0]['spoc_email'] ?? $fields['spoc_email'] ?? ''; ?>">
    <?php echo form_error("spoc_detail[0][spoc_email]"); ?>
  </div>

  <div class="col-md-3">
    <label for="spoc_phone" class="label">Company Spoc Phone:</label>
    <input type="text" class="form-control" id="spoc_phone" placeholder="Enter Spoc Phone" name="spoc_detail[0][spoc_phone]" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10" value="<?php echo $location_fields['spoc_detail'][0]['spoc_phone']?? $fields['spoc_phone']; ?>">
    <?php echo form_error("spoc_detail[0][spoc_phone]"); ?>
  </div>

  <div class="col-md-3">
    <label for="spoc_designation" class="label">Company Spoc Designation:</label>
    <input type="text" class="form-control" id="spoc_designation" placeholder="Spoc Designation" name="spoc_detail[0][spoc_designation]" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "30" value="<?php echo $location_fields['spoc_detail'][0]['spoc_designation']?? ($fields['spoc_designation'] ?? ''); ?>">
    <?php echo form_error("spoc_detail[0][spoc_designation]"); ?>
  </div>
   </div>
<!--<h5>Add Additional Spoc Details</h5>
<hr>-->

<div class="form-group row" id="multispoc">
    <div class="col-md-12" id="spoc-field-container">
        <?php if(!empty($location_fields['spoc_detail'])): ?>
        <?php $x=0; ?>
        <?php foreach($location_fields['spoc_detail'] as $x=>$spoc): ?>
          <?php if($x==0):?>
            <?php //$x++; ?>
            <?php continue;?>
          <?php endif; ?>
        <div class="form-group row" id="spoc_<?= $x;?>">

                                <div class="col-xs-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="spoc_detail[<?=$x;?>][spoc_name]" value="<?= $spoc['spoc_name']?>" placeholder="Enter Spoc Name" />
                                            <?php echo form_error("spoc_detail[{$x}][spoc_name]"); ?>
                                        </div>
                                 </div>
                                 <div class="col-xs-3">
                                        <div class="input-group">
                                            <input type="email" class="form-control" name="spoc_detail[<?=$x;?>][spoc_email]" value="<?= $spoc['spoc_email']?>" placeholder="Enter Spoc Email" />
                                              <?php echo form_error("spoc_detail[{$x}][spoc_email]"); ?>
                                        </div>
                                 </div>
                                 <div class="col-xs-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10" name="spoc_detail[<?=$x;?>][spoc_phone]" value="<?= $spoc['spoc_phone']?>" placeholder="Enter Spoc Phone"  />
                                            <?php echo form_error("spoc_detail[{$x}][spoc_phone]"); ?>
                                        </div>
                                 </div>
                                 <div class="col-xs-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "30" name="spoc_detail[<?=$x;?>][spoc_designation]" value="<?= $spoc['spoc_designation']?>" placeholder="Spoc Designation"  />
                                            <?php echo form_error("spoc_detail[{$x}][spoc_designation]"); ?>
                                        </div>
                                 </div>
                 <div class="col-xs-1"> 
                                 <span class="input-group-btn"><button class="btn btn-danger remove_div" type="button" data-value=<?= $x ?>><i class="fa fa-trash"></i></button></span>
                </div>
        </div>
        <div style="clear:both;"></div>

              <?php $x++; ?>
            <?php endforeach; ?>
        <?php endif;?>
     </div>
    <div class="col-md-12">
        <button class="btn btn-primary add-div" type="button">Add Additional Spoc</button>
    </div>
</div>
<hr>
<div class="form-group row">

     <div class="col-md-3">
       <label for="hr_name" class="">HR Name:</label>
       <input type="text" class="form-control" id="hr_name" placeholder="Enter HR Name" name="hr_name" value="<?php echo $fields['hr_name'] ?? ''; ?>">
       <?php echo form_error('hr_name'); ?>
     </div>

   <div class="col-md-3">
     <label for="hr_email" class="">HR Email:</label>
     <input type="text" class="form-control" id="hr_email" placeholder="Enter HR Email" name="hr_email" value="<?php echo $fields['hr_email'] ?? ''; ?>">
     <?php echo form_error('hr_email'); ?>
   </div>

    <div class="col-md-3">
      <label for="hr_phone" class="">HR Phone:</label>
      <input type="text" class="form-control" id="hr_phone" placeholder="Enter HR Phone" name="hr_phone" maxlength="10" value="<?php echo $fields['hr_phone'] ?? ''; ?>">
      <?php echo form_error('hr_phone'); ?>
    </div>

    <div class="col-md-3">
      <label for="hr_designation" class="">HR Designation:</label>
      <input type="text" class="form-control" id="hr_designation" placeholder="Enter Designation" name="hr_designation" maxlength="30" value="<?php echo $fields['hr_designation'] ?? ''; ?>">
      <?php echo form_error('hr_designation'); ?>
    </div>
</div>



<div class="form-group row">
  <div class="col-md-6">
    <label for="functional_area_id" class="label">Select Functional Area:</label>
    <select class="form-control" name="functional_area_id" id="functional_area_id">
      <option value="">Select Functional Area</option>
      <?php foreach($functional_area_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['functional_area_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('functional_area_id'); ?>
  </div>

  <div class="col-md-6">
    <label for="industry_id" class="label">Select Industry:</label>
    <select class="form-control" name="industry_id" id="industry_id">
      <option value="">Select Industry</option>
      <?php foreach($industry_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['industry_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('industry_id'); ?>
  </div>

  <!-- <?php //if(!(isset($action) && $action=='create')): ?>
    <div class="col-md-4">
      <label for="lead_status_id" class="label">Select Lead Status:</label>
      <select class="form-control" name="lead_status_id" id="lead_status_id">
        <option value="0">Select Lead Status</option>
        <?php // foreach($lead_status_options as $option): ?>
          <option value="<?php// echo $option->id; ?>" <?php// ($option->id==$fields['lead_status_id'] ?? '0') ? 'selected' : '' ?> ><?php // echo $option->name; ?></option>
        <?php //endforeach; ?>
      </select>
      <?php // echo form_error('lead_status_id'); ?>
    </div>
 <?php //else: ?>
   <input type="hidden" value="1" name="lead_status_id">
 <?php //endif; ?> -->

</div>

<div class="form-group row">
  <div class="col-md-6">
    <label for="business_vertical_id" class="label">Business Vertical:</label>
    <select class="form-control" name="business_vertical_id" id="business_vertical_id">
      <option value="">Select Business Vertical</option>
      <?php foreach($business_vertical_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['business_vertical_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('business_vertical_id'); ?>
  </div>


        <div class="col-md-6">
          <label for="website">Website:</label>
          <input type="url" pattern="https?://.+" class="form-control" id="website" placeholder="Enter website" name="website" value="<?php echo $fields['website'] ?? ''; ?>">
          <?php echo form_error('website'); ?>
        </div>
  </div>


  <div class="form-group row">

    <div class="col-md-6">
      <label for="skype_id">Skype Id:</label>
      <input type="text" class="form-control" id="skype_id" placeholder="Enter Skype ID" name="skype_id" value="<?php echo $fields['skype_id'] ?? ''; ?>">
      <?php echo form_error('skype_id'); ?>
    </div>

    <!-- <div class="col-md-3">
      <label for="no_of_employees" class="">No. of Employees:</label>
      <input type="number" class="form-control" id="no_of_employees" placeholder="Enter No. of Employees" name="no_of_employees" value="<?php //echo $fields['no_of_employees'] ?? ''; ?>">
      <?php //echo form_error('no_of_employees'); ?>
    </div> -->

   <div class="col-md-6">
     <label for="annual_revenue" class="">Annual Revenue:</label>
     <input type="text" class="form-control" id="annual_revenue" placeholder="Enter Annual Revenue" name="annual_revenue" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "8" value="<?php echo $fields['annual_revenue'] ?? ''; ?>">
     <?php echo form_error('annual_revenue'); ?>
   </div>

<!--    <div class="col-md-4">
      <label for="business_value" class="label">Business Value:</label>
      <input type="text" class="form-control" id="business_value" placeholder="Enter Business Value" name="business_value" value="<?php// echo $fields['business_value'] ?? ''; ?>">
      <?php// echo form_error('business_value'); ?>
    </div>-->

  </div>



    <div class="form-group row">

      <div class="col-md-6">
        <label for="fax_number" class="">Fax Number:</label>
        <input type="text" class="form-control" id="fax_number" placeholder="Enter Fax Number" name="fax_number" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "10" value="<?php echo $fields['fax_number'] ?? ''; ?>">
        <?php echo form_error('fax_number'); ?>
      </div>

     <div class="col-md-6">
       <label for="tagert_employers">Target Employers:</label>
       <input type="text" class="form-control" id="tagert_employers" placeholder="Enter Target Employers" name="tagert_employers" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "3" value="<?php echo $fields['tagert_employers'] ?? ''; ?>">
       <?php echo form_error('tagert_employers'); ?>
     </div>

    </div>


<div class="form-group row">

   <div class="col-md-6">
     <label for="landline" class="">Landline:</label>
     <input type="text" class="form-control" id="landline" placeholder="Enter landline number" name="landline" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "10" value="<?php echo $fields['landline'] ?? ''; ?>">
     <?php echo form_error('landline'); ?>
   </div>

<!--   <div class="col-md-6">
     <label for="location_id" class="label">Location:</label>
     <select class="form-control select2-neo" name="location_id" id="location_id">
       <option value="">Select Location</option>
       <?php// foreach($location_options as $option): ?>
         <option value="<?// $option->location_id; ?>" <?// ($option->location_id==$location_fields['location_id']) ? 'selected' : '' ?> ><?// $option->location_name; ?></option>
       <?php// endforeach; ?>
     </select>
     <?php// echo form_error('location_id'); ?>
   </div>-->

</div>


<div class="form-group row">
  <div class="col-md-4">
      <label for="country_id" class="label">Country:</label>
      <select class="form-control" name="country_id" id="country_id">
          <option value="0">Select Country</option>
          <?php foreach($countries_options as $country_option): ?>
              <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$location_fields['country_id']) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
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
   <input type="text" class="form-control" id="city" placeholder="Enter City" name="city" value="<?php echo $location_fields['city']; ?>">
   <?php echo form_error('city'); ?>
 </div>

   <div class="col-md-4">
     <label for="landline" class="label">Pincode:</label>
     <input type="text" class="form-control" id="pincode" placeholder="Enter Pincode" name="pincode" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "6"  value="<?php echo $location_fields['pincode']; ?>">
     <?php echo form_error('pincode'); ?>
   </div>

</div>

<div class="form-group row">
  <div class="col-md-12">
    <label for="remarks">Remarks:</label>
    <textarea name="remarks" rows="8" class="form-control"><?php echo $fields['remarks'] ?? ''; ?></textarea>
    <?php echo form_error('remarks'); ?>
  </div>
</div>



<button type="submit" class="btn btn-primary">Submit</button>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

  $('.select2-neo').select2();
});
</script>
 <script type="text/javascript">

  var country_id = <?= (!empty($location_fields['country_id'])) ? $location_fields['country_id'] : 0 ?>;
  var state_id = <?= (!empty($location_fields['state_id'])) ? $location_fields['state_id'] : 0 ?>;
  var district_id = <?= (!empty($location_fields['district_id'])) ? $location_fields['district_id'] : 0 ?>;


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
function customer_name(event) {
   var value = String.fromCharCode(event.which);
   var pattern = new RegExp(/[a-zåäö ]/i);
   return true;
}

$('#customer_name').bind('keypress', customer_name);

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

<script>
var maxField = 100; //Input fields increment limitation
    var x = <?= (empty($location_fields['spoc_detail'])) ? 1 : count($location_fields['spoc_detail']); ?> || 1; //Initial field counter is 1
    var addButton = $('.add-button'); //Add button selector
    var addDiv = $('.add-div'); //Add button selector
    var wrapper1 = $('#spoc-field-container'); //Input field wrapper
    var placeholder='';
    var fieldHTML = '';
    var fieldSET = '';

    $(addDiv).click(function()
    { //Once add button is clicked
         if(x < maxField)
        { //Check maximum number of input fields
            //Increment field counter
        fieldSET = '<div class="form-group row" id="spoc_'+x+'">';
		fieldSET +=           '<div class="col-xs-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="spoc_detail['+x+'][spoc_name]" value="" placeholder="Enter Spoc Name" />\
					        </div>\
					 </div>\
                                         <div class="col-xs-3">\
					        <div class="input-group">\
					            <input type="email" class="form-control" name="spoc_detail['+x+'][spoc_email]" value="" placeholder="Enter Spoc Email" />\
					        </div>\
					 </div>\
                                         <div class="col-xs-2">\
					        <div class="input-group">\
					            <input type="text" maxlength="10" class="form-control" name="spoc_detail['+x+'][spoc_phone]" value="" placeholder="Enter Spoc Phone" />\
					        </div>\
					 </div>\
           <div class="col-xs-3">\
              <div class="input-group">\
                <input type="text" maxlength="30" class="form-control" name="spoc_detail['+x+'][spoc_designation]" value="" placeholder="Spoc Designation" />\
              </div>\
            </div>\
            <div class="col-xs-1">\
                    <span class="input-group-btn"><button class="btn btn-danger remove_div" data-value='+x+' type="button"><i class="fa fa-trash"></i></button></span>\
                    </div>\
            </div>';

        $(wrapper1).append(fieldSET); // Add field html
         x++;
    }
    });

    $(wrapper1).on('click', '.remove_div', function(e)
    { //Once remove button is clicked
        e.preventDefault();
       // alert ($(this).attr('data-value'));
      //  $(this).parents('div').remove(); //Remove field html
        let y = $(this).attr('data-value');
        $('#spoc_'+y).remove();
      //  x--; //Decrement field counter
    });
</script>


<div class="form-group row">
 <div class="col-md-4">
   <label for="country_id" class="">Select Country:</label>
   <select class="form-control" name="country_id" id="country_id">
     <option value="0" <?php echo ($fields['country_id']==0) ? 'selected' : '' ?>>Select Country</option>
     <?php foreach($country_options as $country_option): ?>
       <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$fields['country_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
     <?php endforeach; ?>
   </select>
   <?php echo form_error('country_id'); ?>
 </div>

 <div class="col-md-4">
   <label for="state" class="">Select State:</label>
   <select class="form-control" name="state_id" id="state_id">
     <option value="0">Select State</option>
   </select>
   <?php echo form_error('state_id'); ?>
 </div>

 <div class="col-md-4">
   <label for="district_id" class="">District:</label>
   <select class="form-control" name="district_id" id="district_id">
     <option value="0">Select District</option>
   </select>
   <?php echo form_error('district_id'); ?>
 </div>
</div>

<script type="text/javascript">

 $(document).ready(function() {

   $('#country_id').on('change', function() {
     $('#state_id').html('').append('<option selected> Select State </option>');
     var c_id = $(this).val();
      if(c_id != 0 && c_id != '') {
        getStates(c_id);
      }
   });

   <?php if(isset($fields['country_id']) && $fields['country_id'] != '' &&$fields['country_id'] != '0'): ?>
    getStates(<?= $fields['country_id']; ?>);
   <?php endif; ?>

   function getStates(id) {
     var request = $.ajax({
       url: "<?php echo base_url(); ?>CandidatesController/getStates/"+id,
       type: "GET",
     });

     request.done(function(msg) {
       var response = JSON.parse(msg);
       // alert(response);
        $('#state_id').html('').append($('<option>').text('Select State').attr('value', 0));
       // $("#state_id").val('0').change();
       response.forEach(function(state) {
          $('#state_id').append($('<option>').text(state.name).attr('value', state.id));
       });

       <?php if(isset($fields['state_id']) && $fields['state_id'] != ''):?>
       console.log('coming here');
         $("#state_id").val("<?= $fields['state_id']?>").change();
         getDistricts(<?= $fields['state_id']; ?>);
       <?php endif; ?>

     });

     request.fail(function(jqXHR, textStatus) {
       alert( "Request failed: " + textStatus );
     });
   }


   $('#state_id').on('change', function() {
     // $('#state_id').html('');
     // $('#state_id').append($('<option>').text('Select State').attr('value', 0));
     $('#district_id').html('').append('<option selected> Select State </option>');
     var s_id = $(this).val();
     if(s_id != 0 && s_id != '') {
     getDistricts(s_id);
    }
   });

   function getDistricts(id) {

     var request = $.ajax({
       url: "<?= base_url(); ?>CandidatesController/getDistricts/"+id,
       type: "GET",
     });

     request.done(function(msg) {
       var response = JSON.parse(msg);
       // alert(response);
       $('#district_id').html('').append($('<option>').text('Select District').attr('value', 0));
       response.forEach(function(district) {
          $('#district_id').append($('<option>').text(district.name).attr('value', district.id));
       });


       <?php if(isset($fields['district_id']) && $fields['district_id'] != ''):?>
         $("#district_id").val("<?= $fields['district_id']?>").change();
       <?php endif; ?>

     });

     request.fail(function(jqXHR, textStatus) {
       alert( "Request failed: " + textStatus );
     });
   }


 });


</script>

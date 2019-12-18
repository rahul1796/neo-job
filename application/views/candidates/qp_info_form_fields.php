
            <div class="form-group row" style="width: 95%; margin-top: 15px;">
                    <div class="col-md-3">
                        <label for="batch_id">Batch ID:</label>
                        <input type="text" class="form-control" id="batch_id" placeholder="Enter Batch ID" name="batch_id" value="<?php echo $fields['date_of_birth']; ?>">
                        <?php echo form_error('batch_id'); ?>
                    </div>
                    <div class="col-md-3">
                        <label for="batch_code">Batch Code:</label>
                        <input type="text" class="form-control" id="batch_code" placeholder="Enter batch_code" name="batch_code" value="<?php echo $fields['date_of_birth']; ?>">
                        <?php echo form_error('batch_code'); ?>
                    </div>

                    <div class="col-md-3">
                        <label for="center_id">Center ID:</label>
                        <input type="text" class="form-control" id="center_id" placeholder="Enter Center ID" name="center_id" value="<?php echo $fields['date_of_birth']; ?>">
                        <?php echo form_error('center_id'); ?>
                    </div>

                    <div class="col-md-3">
                        <label for="center_name">Center Name:</label>
                        <input type="text" class="form-control" id="center_name" placeholder="Enter Center Name" name="center_name" value="<?php echo $fields['last_name']; ?>">
                        <?php echo form_error('center_name'); ?>
                    </div>
            </div>
            <div class="form-group row" style="width: 95%;">
                    <div class="col-md-3">
                        <label for="center_location">Center Location:</label>
                        <input type="text" class="form-control" id="center_location" placeholder="Enter Center Location" name="center_location" value="<?php echo $fields['first_name']; ?>">
                        <?php echo form_error('center_location'); ?>
                    </div>


                    <div class="col-md-3">
                        <label for="first_name">Course:</label>
                        <input type="text" class="form-control" id="first_name" placeholder="Enter Variable" name="variable" value="<?php echo $fields['first_name']; ?>">
                        <?php echo form_error('first_name'); ?>
                    </div>

                    <div class="col-md-3">
                        <label for="funding_source">Funding Source:</label>
                        <input type="text" class="form-control" id="funding_source" placeholder="Enter Funding Source" name="funding_source" value="<?php echo $fields['first_name']; ?>">
                        <?php echo form_error('funding_source'); ?>
                    </div>
                    <div class="col-md-3">
                        <label for="certification_date">Certification Date:</label>
                        <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="certification_date" placeholder="Enter certification_date" name="certification_date" value="<?php echo $fields['date_of_birth']; ?>">
                        <?php echo form_error('certification_date'); ?>
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

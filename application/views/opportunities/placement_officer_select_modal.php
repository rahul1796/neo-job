

<!-- Bootstrap modal -->
<div class="modal fade" id="placement_officer_assign_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Assign Placement Officer</h3>
            </div>
            <div class="modal-body ">
              <div class="row">

                  <div class="col-md-12">
                    <label for="placement_officers" class="label">Assign Placement Officer:</label>
                    <select class="select2-neo form-control" name="placement_officers" id="placement_officer_assign_select" style="width: 50%">
                      <option value='0'>Select Placement Officers</option>
                      <?php foreach($placement_officer_options as $option): ?>
                          <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                      <?php endforeach; ?>
                  </select>
                  <input type="hidden" name="customer_id" id="assign_placement_officer_customer_id" value="">
                  <br>
                  <h3 class="" id="assignee_status"></h3>
                  </div>
                  <div class="col-md-12">
                    <br>
                    <button type="button" class="btn btn-primary" onclick="change_lead_assignee();" name="button" id="lead_assignee_update_btn">Assign Opportunity</button>
                    <br>

                  </div>
              </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

<script type="text/javascript">
  $(document).ready(function() {
      $('#placement_officer_assign_select').change(function(){
          $('#lead_assignee_update_btn').prop('disabled', false);
      });
  });

  function open_placement_officer_assign_model(customer_id) {
    $('#assignee_status').text('');
    $('#placement_officer_assign_select').val(0).change();
    $('#placement_officer_assign_modal').modal();
    $('#assign_placement_officer_customer_id').val(customer_id);
    $('#lead_assignee_update_btn').prop('disabled', true);
    $.ajax({
      url: '<?= base_url('opportunitiesController/getAssignedUserToLead');?>',
      data: {'customer_id' : customer_id},
      method: 'POST',
    }).done(function(response){
      let ids = JSON.parse(response);
      if(ids.data.length>0) {
          $('#placement_officer_assign_select').val(ids.data).change();
          $('#lead_assignee_update_btn').prop('disabled', true);
      }
    }).fail(function(response, text){
      console.log(response);
    });
  }

  function change_lead_assignee() {
    let placement_officer = $('#placement_officer_assign_select').find(':selected').val();
    let customer_id = $('#assign_placement_officer_customer_id').val();
    $.ajax({
      url: '<?= base_url('opportunitiesController/changeLeadAssignee');?>',
      data: {'customer_id' : customer_id, 'placement_officer' : placement_officer},
      method: 'POST',
    }).done(function(response){
      let ids = JSON.parse(response);
      $('#assignee_status').text(ids.msg);
    }).fail(function(response, text){
      $('#assignee_status').text('Something went wrong, Try again after sometime');
      console.log(response);
    }).always(function(jqXHR, textStatus){
      //$('#placement_officer_assign_modal').modal('hide');
    });
  }
</script>

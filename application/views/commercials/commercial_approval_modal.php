<div class="modal fade" id="commercial-approve-modal" role="dialog">
   <div class="modal-dialog">

     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title">Commerical Status</h4>
       </div>
       <div class="modal-body">
         <br>
         <label for="lead_status_id">Choose Status</label>
         <select class="form-control" id="commercial_status_selector" placeholder="Select Status">
           <option value="0">Select a Status</option>
           <?php foreach($commercial_options as $option): ?>
             <option value="<?php echo $option->id; ?>" data-svalue="<?php echo $option->value; ?>" data-notification="<?php echo $option->notification_status; ?>" ><?= (intval($option->id)==20) 'Approved' : 'Rejected' ?></option>
           <?php endforeach; ?>
         </select>
         <br>

         <div class="hidden form-group row" id="commercial_form_container">
           <form class="form-group" id="commercial_form" >
             <div class="col-md-12">
               <input type="hidden" name="lead_status_id" id="lead_status_id" value="">
               <input type="hidden" name="status" id="status" value="">
               <input type="hidden" id="customer_id" name="customer_id" value="<?= $customer_id?>">
               <input type="text" id="commercial-remarks" name="remarks" maxlength="150" class="form-control" placeholder="Enter remarks"  value="">
               <span class="text-danger hidden" id="commercial-form-error-validation">Remark Field is mandatory</span>
             </div>
           </form>
         </div>
          <button type="button" id="update-status" class="btn btn-primary" onclick="validateLegalUserForm();" name="button" >Update Status</button>
       </div>

       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
     </div>

   </div>
 </div>

<script type="text/javascript">

function openCommercialStatusModal() {
  $("#commercial_form")[0].reset();
  $('#commercial_status_selector').val(0);
  $('#commercial_form_container').addClass('hidden');
  $('#commercial-approve-modal').modal();
  $('#commercial-form-error-validation').addClass('hidden');
  $('#update-status').prop('disabled', true);
}

$('#commercial_status_selector').change(function() {
  let status_id = $(this).val();
  $('#commercial-form-error-validation').addClass('hidden');
  $('#commercial-remarks').val('');
  if(status_id==20) {
    $('#commercial_form_container').addClass('hidden');
    $('#lead_status_id').val(status_id);
    $('#status').val('accept');
    $('#update-status').prop('disabled', false);

  } else if(status_id==21) {
    $('#commercial_form_container').removeClass('hidden');
    $('#update-status').prop('disabled', false);
    $('#lead_status_id').val(status_id);
    $('#status').val('reject');

  } else {
    swal(
            {
                title: "Select a Valid Status!",
                text: "",
                showCancelButton: false,
                confirmButtonText: "OK",
                closeOnConfirm: true
            })
    //alert('Select a Valid Status');
    $('#update-status').prop('disabled', true);
  }
});

function validateLegalUserForm() {
  let commercial_status_selector = $('#commercial_status_selector').find(':selected').val();
  let remark_commercial = $('#commercial-remarks').val();
  if(parseInt(commercial_status_selector)==21 &&(remark_commercial.trim()==undefined || remark_commercial.trim()=='')){
    $('#commercial-form-error-validation').removeClass('hidden');
  } else {
    updateCommercialStatusLegalUser();
  }
}
function updateCommercialStatusLegalUser() {
  $.ajax({
    url : '<?= base_url("commercialverificationcontroller/verify_documents_commercial");?>',
    data : $('#commercial_form').serializeArray(),
    method: 'POST'
  }).done(function (data) {
    location.href = "<?= base_url('/opportunitiescontroller/index'); ?>";
  }).fail(function(response, text) {
    alert('something went wrong, try again later');
  });
}

</script>

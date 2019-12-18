
<?php if(validation_errors()): ?>
  <div class="row" id="soft-alert">
    <div class="col-md-12">
      <br>
      <div class="alert alert-danger" role="alert" >
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5>Validation Errors, check form for Errors.</h5>
      </div>
    </div>
  </div>
<?php endif; ?>
<script>
  window.setTimeout(function() {
    $("#soft-alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove();
    });
  }, 4000);
</script>

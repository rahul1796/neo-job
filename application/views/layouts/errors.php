<style>
.success-alert{
  border-color: #4cae4c!important;
  background-color: #5cb85c!important;
  color: #023c02!important;
}

</style>
<?php if(isset($_SESSION['status'])): ?>
  <div class="alert <?= ($_SESSION['status_code']==1) ? 'success-alert':'alert-danger' ?>" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h5><?php echo $_SESSION['status']; ?></h5>
  </div>
<?php endif; ?>
<script>
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove();
    });
  }, 10000);
</script>

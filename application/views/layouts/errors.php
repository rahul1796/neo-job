
<?php if(isset($_SESSION['status'])): ?>
  <div class="alert alert-primary" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <h5><?php echo $_SESSION['status']; ?></h5>
  </div>
<?php endif; ?>
<script>
  window.setTimeout(function() {
    $(".alert").fadeTo(500, 0).slideUp(500, function(){
      $(this).remove();
    });
  }, 4000);
</script>

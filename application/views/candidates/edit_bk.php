  <div class="content-body" style="padding: 30px;">
<div class="row">
  <div class="col-md-12">
    <h3>Edit Candidate</h3>
  </div>
</div>
<br>
<form action="<?php echo base_url('/candidatescontroller/update/').$id;?>" method="POST">

<?php $this->load->view('candidates/form_fields', $data); ?>

</form>

</div>

<br><br><br>

<div class="content-body" style="padding: 30px; margin-top: 10px;">
  <div class="row">
    <div class="col-md-12">
      <?php $this->load->view('layouts/errors'); ?>
    </div>
  </div>
  <?php if (in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_add_roles())): ?>
    <a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php echo base_url("companiescontroller/create")?>" style="float: right;"><i class="icon-android-add"></i>Add Company</a>
  <?php endif; ?>
</div>

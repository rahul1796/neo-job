
<div class="content-body" style="padding: 0px 10px 60px 10px;">
  <div class="w3-container" style="margin-bottom: 45px;">

    <h2>Edit User</h2>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-left: -25px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("UsersController/index","Users");?></li>
          <li class="breadcrumb-item active">Edit User</li>
        </ol>
      </div>
    </div>
  </div>

  <div id="Personal" class="w3-container info" style="background: white;padding: 15px; ">
    <form action="<?php echo base_url('/UsersController/update/').$id;?>" method="POST">

      <?php $this->load->view('users/form_fields', $data); ?>

    </form>
  </div>
</div>

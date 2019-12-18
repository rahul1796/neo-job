<div class="w3-container" style="margin-left: 288px; margin-bottom: 50px;">
  <h2>Edit Candidate</h2>
  <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
    <div class="breadcrumb-wrapper col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo anchor("candidatescontroller/show/$id","Candidate Profile");?></li>
        <li class="breadcrumb-item active">Edit Candidate</li>
      </ol>
    </div>
  </div>
</div>
<div class="content-body" style="padding: 0px 10px 60px 10px;">

  <div id="info" class="w3-container info" style="background: white;padding: 25px; ">
    <form action="<?php echo base_url('/candidatescontroller/update/').$id;?>" method="POST">
      <?php $this->load->view('candidates/form_fields', $data); ?>
    </form>
  </div>
</div>

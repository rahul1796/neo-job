<link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/tab.css')?>">
<style>
  /*.active{
    background: #fff !important;
  }*/
</style>
<div class="content-body" style="padding: 0px 10px 60px 10px;">

  <?php $this->load->view('documents/list', $data); ?>

  <div class="w3-container">
    <h2>Upload Document</h2>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("candidatescontroller/show/".$data['fields']['candidate_id'],"Candidate Profile");?></li>
          <li class="breadcrumb-item active">Upload Document</li>
        </ol>
      </div>
    </div>
  </div>

  <div id="Personal" class="w3-container info" style="background: white; padding: 25px; ">
    <!--<h2>Personal Info</h2>-->
    <form action="<?php echo base_url('documentscontroller/store/').$data['fields']['candidate_id'];?>" method="POST" enctype="multipart/form-data">
      <?php $this->load->view('documents/form_fields', $data); ?>
    </form>
  </div>
</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/tab.css')?>">
<style>
  /*.active{
    background: #fff !important;
  }*/
</style>
<div class="content-body" style="padding: 0px 10px 60px 10px;">
  <div class="w3-container">
    <h2>Add Candidate</h2>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("partner/candidates","Candidates");?></li>
          <li class="breadcrumb-item active">Add Candidate</li>
        </ol>
      </div>
    </div>
  </div>
  <div id="Personal" class="w3-container info" style="background: white; ">
    <form action="<?php echo base_url();?>/candidatescontroller/store" method="POST">
      <?php $this->load->view('candidates/form_fields', $data); ?>
    </form>
  </div>
</div>

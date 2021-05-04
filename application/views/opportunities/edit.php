1<div class="content-body" style="padding: 0px 10px 60px 10px;">
  <div class="w3-container" style="margin-bottom: 40px;">
    <!-- <a class="btn btn-warning btn-min-width mr-1 mb-1" href="<?php //echo base_url("leads/index/1")?>" style="float: right;"><i class=""></i>Back to Leads</a> -->
    <h2>Edit Opportunity</h2>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("opportunitiescontroller/index","Opportuities");?></li>
          <li class="breadcrumb-item active">Edit Opportunity</li>
        </ol>
      </div>
    </div>
  </div>
  <div id="Personal" class="w3-container info" style="background: white;padding: 30px; ">
    <form action="<?php echo base_url('/OpportunitiesController/update/').$id;?>" method="POST">
      <?php $this->load->view('opportunities/form_fields', $data); ?>
    </form>
  </div>
</div>

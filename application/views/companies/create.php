<div class="content-body" style="padding: 0px 10px 60px 10px;">
  <div class="w3-container" style="margin-bottom: 40px;">
    <h2>Add Company</h2>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("companiescontroller/index","Companies");?></li>
          <li class="breadcrumb-item active">Add Company</li>
        </ol>
      </div>
    </div>
  </div>
  <div id="Personal" class="w3-container info" style="background: white;padding: 30px; ">
    <form action="<?php echo base_url();?>/companiescontroller/store" method="POST">
      <?php $this->load->view('companies/form_fields', $data); ?>
    </form>
  </div>
</div>

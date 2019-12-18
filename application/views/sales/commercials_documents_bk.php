<style>
    .label{
        float: left;
        padding-right: 4px;
        padding-top: 2px;
        position: relative;
        text-align: right;
        vertical-align: middle;
    }
    .label:before{
        content:"*" ;
        color:red
    }

</style>
<div class="content-body" style="padding: 30px; margin-top: 10px;">
  <form action="<?php echo base_url('salescontroller/commericals_store/').$id;?>" method="POST">

  <div class="row">
    <div class="col-md-12">
      <label for="onboarding_fee">OnBoarding Fee</label>
      <input type="hidden" id="customer_id" class="form-control" name="commercial[0][customer_id]" value="<?=$id?>">
      <input type="hidden" id="onboarding_fee" class="form-control" name="commercial[0][title]" value="onboarding_fee">
      <input type="number" id="onboarding_fee" class="form-control" name="commercial[0][value]" value="">
      <label for="service_fee">Fee Type</label>
      <select class="form-control" name="commercial[0][fee_type]">
        <?php foreach ($fee_types_option as $key=>$value): ?>
        <option value="<?= $key; ?>"><?= $value; ?></option>
      <?php endforeach; ?>
      </select>
      <br>
      <br>
    </div>
    <div class="col-md-12">
      <label for="service_fee">Service Fee</label>
      <input type="hidden" id="customer_id" class="form-control" name="commercial[1][customer_id]" value="<?=$id?>">
      <input type="hidden" id="service_fee" class="form-control" name="commercial[1][title]" value="service_fee">
      <input type="number" id="service_fee" class="form-control" name="commercial[1][value]" value="">
      <label for="service_fee">Fee Type</label>
      <select class="form-control" name="commercial[1][fee_type]">
        <?php foreach ($fee_types_option as $key=>$value): ?>
        <option value="<?= $key; ?>"><?= $value; ?></option>
      <?php endforeach; ?>
      </select>
      <br>
      <br>
    </div>
    <div class="col-md-12">
      <label for="sourcing_fee">Sourcing Fee</label>
      <input type="hidden" id="customer_id" class="form-control" name="commercial[2][customer_id]" value="<?=$id?>">
      <input type="hidden" id="sourcing_fee" class="form-control" name="commercial[2][title]" value="sourcing_fee">
      <input type="number" id="sourcing_fee" class="form-control" name="commercial[2][value]" value="">
      <label for="service_fee">Fee Type</label>
      <select class="form-control" name="commercial[2][fee_type]">
        <?php foreach ($fee_types_option as $key=>$value): ?>
        <option value="<?= $key; ?>"><?= $value; ?></option>
      <?php endforeach; ?>
      </select>
      <br>
      <br>
    </div>
    <div class="col-md-12">
      <label for="absorption_fee">Absorption Fee</label>
      <input type="hidden" id="customer_id" class="form-control" name="commercial[3][customer_id]" value="<?=$id?>">
      <input type="hidden" id="absorption_fee" class="form-control" name="commercial[3][title]" value="absorption_fee">
      <input type="number" id="absorption_fee" class="form-control" name="commercial[3][value]" value="">
      <label for="service_fee">Fee Type</label>
      <select class="form-control" name="commercial[3][fee_type]">
        <?php foreach ($fee_types_option as $key=>$value): ?>
        <option value="<?= $key; ?>"><?= $value; ?></option>
      <?php endforeach; ?>
      </select>
      <br>
      <br>
    </div>
    <div class="col-md-12">
      <label for="reimbursement_fee">Reimbursement Fee</label>
      <input type="hidden" id="customer_id" class="form-control" name="commercial[4][customer_id]" value="<?=$id?>">
      <input type="hidden" id="reimbursement_fee" class="form-control" name="commercial[4][title]" value="reimbursement_fee">
      <input type="number" id="reimbursement_fee" class="form-control" name="commercial[4][value]" value="">
      <label for="service_fee">Fee Type</label>
      <select class="form-control" name="commercial[4][fee_type]">
        <?php foreach ($fee_types_option as $key=>$value): ?>
        <option value="<?= $key; ?>"><?= $value; ?></option>
      <?php endforeach; ?>
      </select>
      <br>
      <br>
    </div>
    <div class="col-md-12">
      <label for="invoice_processing_charge">Invoice Processing Charge</label>
      <input type="hidden" id="customer_id" class="form-control" name="commercial[5][customer_id]" value="<?=$id?>">
      <input type="hidden" id="invoice_processing_charge" class="form-control" name="commercial[5][title]" value="invoice_processing_charge">
      <input type="number" id="invoice_processing_charge" class="form-control" name="commercial[5][value]" value="">
      <label for="service_fee">Fee Type</label>
      <select class="form-control" name="commercial[5][fee_type]">
        <?php foreach ($fee_types_option as $key=>$value): ?>
        <option value="<?= $key; ?>"><?= $value; ?></option>
      <?php endforeach; ?>
      </select>
      <br>
      <br>
    </div>
  </div>
  <input type="submit" class="btn btn-warning" name="" value="Save">
</form>
</div>

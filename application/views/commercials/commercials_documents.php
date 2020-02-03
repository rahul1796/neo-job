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
    .td-width
    {
        max-width: 80px !important;
    }
    .font-color{
      color:#000;
      background:#e0dfde;
       }
       .text-black{
         color:#000;
       }

</style>
<div class="content-body" style="padding: 0px 10px 60px 10px;">

    <div class="col-md-12">
      <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-primary" id="commercial-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5><?php echo $_SESSION['status']; ?></h5>
      </div>
      <br><br>
      <?php endif; ?>
    </div>

  <div class="w3-container">
    <h2>Opportunity Commercials & Document</h2>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("opportunitiescontroller/index","opportunities");?></li>
          <li class="breadcrumb-item active">Opportunity Commercials & Document</li>
        </ol>
      </div>
    </div>
  </div>



  <div id="Personal" class="w3-container info" style="background: white;padding: 40px;margin-top: 45px; ">
  <form action="<?php echo base_url('commercialverificationcontroller/commericalsStore/').$id;?>" method="POST" id="commercial-form" enctype="multipart/form-data" onsubmit="return check_notzeros()">
      <div class="row">
        <?php $i=0; ?>
        <div class="col-md-12">
          <h3 class="text-black">Commercial Information</h3>
          <hr>
          <br>
        </div>
           <table id="tblMain" class="table table-bordered display responsive nowrap" style="width:100% !important;">
                                <thead>
                                <tr class="font-color">
                                    <th>Fee</th>
                                    <th>Fee Type</th>
                                    <th>Price/Rate</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>


           <tbody>
        <?php foreach($commercials as $com ): ?>
               <tr>
                   <td>
            <input type="hidden" id="customer_id" class="form-control" name="commercial[<?= $i; ?>][customer_id]" value="<?=$id?>">
            <input type="hidden" id="onboarding_fee" class="form-control" name="commercial[<?= $i; ?>][title]" value="<?= $com['title']?>">

            <div class="col-md-12">
              <h6 style="color:#333; "><?= humanize($com['title'])?></h6>
            </div>
                   </td>


                <td >
            <div class="col-md-12">
              <select class="form-control" name="commercial[<?= $i; ?>][fee_type]" id="fee_type_<?= $i; ?>" data-value="<?= $i; ?>" onchange="toggleRemark(this)">
                <?php foreach ($fee_types_option as $key=>$value): ?>
                <option value="<?= $key; ?>" <?= ($key== $com['fee_type']) ? 'selected' : '' ;?>><?= $value; ?></option>
              <?php endforeach; ?>
              </select>
              <?= form_error("commercial[{$i}][fee_type]"); ?>
              <br>
            </div>
            </td>

            <td>
            <div class="col-md-12">
              <input type="text" id="onboarding_fee" data-toggle="tooltip" class="form-control" name="commercial[<?= $i; ?>][value]" value="<?= $com['value']?>">
              <?= form_error("commercial[{$i}][value]"); ?>
              <br>
            </div>
                 </td>
                 <td>
            <div class="col-md-12">
              <div class="<?= ($com['fee_type'] != '1')? '' : 'hidden'; ?>" id="option_remark_container_<?= $i; ?>">
                <select class="form-control" name="commercial[<?= $i; ?>][option_remarks]" id="option_remark_<?= $i; ?>">
                  <option value="">Select Remark</option>
                  <?php foreach($remark_options as $option): ?>
                    <option value="<?= $option->name; ?>" <?= ($option->name == ($com['option_remarks'] ?? '')) ? 'selected' : '' ;?> ><?= $option->name; ?></option>
                  <?php endforeach; ?>
                </select>
                <?= form_error("commercial[{$i}][option_remarks]"); ?>
              </div>

              <div class="<?= ($com['fee_type'] == '1')? '' : 'hidden'; ?>" id="remark_container_<?= $i; ?>">
                <input type="text" name="commercial[<?= $i; ?>][remarks]" class="form-control" id="remarks_<?= $i; ?>" value="<?= $com['remarks']; ?>">
                <?= form_error("commercial[{$i}][remarks]"); ?>
              </div>
            </div>
                 </td>
        <?php $i++; ?>
           </tr>
      <?php endforeach; ?>
             </tbody>
                            </table>
      <div class="col-md-12">
        <br>
        <h3 class="text-black">Document Upload</h3>
        <hr>
        <br>
      </div>
      <?php if(count($documents)!=0): ?>
        <?php if(in_array( $this->session->userdata('usr_authdet')['user_group_id'], lead_commercial_update_roles())): ?>
          <div class="col-md-12">
          <?php if(($customer['lead_status_id']==16 && $customer['has_commercial']==false) || $customer['lead_status_id']==21):?>
            <h5 class="text-black">To change the document file, delete existing document and upload again</h5>
          <?php else: ?>
              <h5 class="text-black">Editing or Re-submitting document is locked until it is not verified by Legal !</h5>
          <?php endif;?>
          </div>
        <?php endif; ?>
          <div class="col-md-12">
            <?php foreach($documents as $document): ?>
              <a class="btn btn-warning mr-1 mb-1" href="<?= base_url('documents/').$document->file_name; ?>" target="_blank"><i class="fa fa-download"></i> Download <?= $document->file_name?></a>
              <?php if(in_array( $this->session->userdata('usr_authdet')['user_group_id'], lead_commercial_update_roles())): ?>
                <?php if($customer['lead_status_id']==21):?>
                  <a class="btn btn-danger mr-1 mb-1" href="<?= base_url('commercialverificationcontroller/document_delete/'.$id.'/'.$document->id); ?>" onclick="confirmDelete(event);"><i class="fa fa-trash"></i></a>
                <?php endif; ?>
              <?php endif; ?>
              <?php endforeach; ?>
          </div>
          <input type="hidden" name="action" value="edit">
      <?php else: ?>
        <div class="col-md-12">
          <input type="hidden" name="action" value="create">
          <label for="file_name" class="label">Upload File:</label>
          <span class="text text-danger">( jpg, png, pdf, doc, docx ) files only</span>
          <input type="file" class="form-control" name="file_name" value="">
          <?php echo form_error('file_name'); ?>
          <br>
        </div>
      <?php endif; ?>

      <div class="col-md-12">
        <br>
        <?php if(in_array( $this->session->userdata('usr_authdet')['user_group_id'], lead_commercial_update_roles())): ?>
            <?php if(($customer['lead_status_id']==16 && $customer['has_commercial'])==false || $customer['lead_status_id']==21): ?>
              <input type="submit" class="btn btn-primary" name="" value="Save Commercial Details">
            <?php endif;?>
        <?php endif;?>

      </div>
    </div>
</form>

<div class="row">
  <div class="col-md-12">
    <br>
    <?php if(in_array( $this->session->userdata('usr_authdet')['user_group_id'], lead_commercial_approve_roles())): ?>
      <?php if($legal_verified && $customer['lead_status_id']==16): ?>
        <h5 class="text-black">Click the below button to provide Legal Approval</h5>
        <button type="button" class="btn btn-danger" onclick="openCommercialStatusModal();">Update Commercial Status</button>
        <?php endif;?>
    <?php endif;?>
  </div>
</div>
  </div>
</div>

<?php $this->load->view('commercials/commercial_approval_modal', ['customer_id'=>$id, 'commercial_options' => $commercial_options]); ?>

<script type="text/javascript">
  $(document).ready(function() {
    $('#commercial-form').submit(function(){

      //$(this).find(':input[type=submit]').prop('disabled', true);
    });
  });
  window.setTimeout(function() {
      $("#commercial-alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove();
      });
  }, 4000);
</script>


<script type="text/javascript">
  function confirmDelete(event) {
    event.preventDefault();
    let url = event.currentTarget.href;
    console.log(url);
    let result = confirm("Sure about deleting this item?");
    if(result) {
      console.log(url);
      window.location.href = url;
    }
  }

  function toggleRemark(e) {
    let input_value = e.getAttribute('data-value');
    let input_id = e.value;
    if(input_id==0) {
      $('#remark_container_'+input_value).addClass('hidden');
      $('#option_remark_container_'+input_value).removeClass('hidden');
    } else {
      $('#option_remark_container_'+input_value).addClass('hidden');
      $('#remark_container_'+input_value).removeClass('hidden');
    }
  }
  function check_notzeros()
  {

    var v1=$('input[name="commercial[0][value]"]').val();
    var v2=$('input[name="commercial[1][value]"]').val();
    var v3=$('input[name="commercial[2][value]"]').val();
    var v4=$('input[name="commercial[3][value]"]').val();
    var v5=$('input[name="commercial[4][value]"]').val();

    var sum=parseFloat(v1)+parseFloat(v2)+parseFloat(v3)+parseFloat(v4)+parseFloat(v5);
    if(sum>0)
    {
      $(this).find(':input[type=submit]').prop('disabled', true);
      return true;
    }
    else
    {
      swal({
            title: "",
            text: "Please Ensure any one of the 5 input value must greater than zero!",
            //type: "warning",
            showCancelButton: false,
            confirmButtonColor: '#DD6B55',
            cancelButtonText: "Close",
            closeOnCancel: false
        });
      //alert('Please Ensure any one of the 5 input value must greater than zero');
      return false;
    }
  }

</script>

<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<?php $this->load->view('layouts/errors');?>
<table id="tblSec" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;background: white;">
  <a class="btn btn-primary btn-min-width mr-1 mb-1" href="<?= base_url('employmentscontroller/create/').$fields['candidate_id']; ?>" style="float: right;"><i class="icon-android-add"></i>Add New Employement</a>
  <thead>
  <tr>
    <th style="background-color: white;">Company Name</th>
    <th style="background-color: white;">Designation</th>
    <th style="background-color: white;">Location</th>
    <th style="background-color: white;">CTC</th>
    <th style="background-color: white;">Skilling Type</th>
    <th style="background-color: white;">Employment Type</th>
    <th style="background-color: white;">Action </th>
  </tr>
  </thead>
  <tbody>

  <?php foreach($employments as $employment): ?>
    <tr>
    <td><?= $employment->company_name ?? 'N/A'; ?></td>
    <td><?= $employment->designation ?? 'N/A'; ?></td>
    <td><?= $employment->location ?? 'N/A'; ?></td>
    <td><?= $employment->ctc ?? 'N/A'; ?></td>
    <td><?= ($employment->skilling_type_id==1) ? 'Post Skilling' : 'Pre Skilling'; ?></td>
    <td><?= $employment->employment_type ?? 'N/A'; ?></td>

    <td>
      <?php if($employment->employment_type=='Self Employed' || $employment->employment_type=='Self Employment'): ?>
        <?php if(!empty($employment->file_name)):?>
          <a class="btn btn-primary" href="<?= base_url('documents/').$employment->file_name;?>"><i class="fa fa-file"></i></a>
        <?php endif; ?>
        <button type="button" class="btn btn-success " title="Document Upload" onclick="employment_doc_upload('<?= $employment->id; ?>');"><i class="fa fa-upload"></i></button>
      <?php endif;?>
      <?php if(($employment->employment_type=='Self Employed' || $employment->employment_type=='Self Employment') && $employment->skilling_type_id==1 && (!in_array($user['user_group_id'], candidate_employments_delete_edit_roles()))):?>
        <a class="btn btn-warning" href="<?= base_url('employmentscontroller/edit/').$fields['candidate_id'].'/'.$employment->id; ?>"><i class="fa fa-pencil"></i></a>
      <?php endif; ?>
      <?php if($employment->skilling_type_id==1 && in_array($user['user_group_id'], candidate_employments_delete_edit_roles())):?>
        <a class="btn btn-warning" href="<?= base_url('employmentscontroller/edit/').$fields['candidate_id'].'/'.$employment->id; ?>"><i class="fa fa-pencil"></i></a>
      <?php endif; ?>
    <?php if($employment->skilling_type_id==1 && in_array($user['user_group_id'], candidate_employments_delete_edit_roles())):?>
      <a class="btn btn-danger " onclick="confirmDelete(event);" href="<?= base_url('employmentscontroller/delete/').$fields['candidate_id'].'/'.$employment->id; ?>"><i class="fa fa-trash"></i></a>
    <?php endif; ?>
    </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>

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

  function employment_doc_upload(id)
  {
    //event.preventDefault();
      //$('#form_upload_candidate')[0].reset(); // reset form on modals
      $('#upload_modal_employment_id').val(id);
      $('.form-group').removeClass('has-error'); // clear error class
      $('.error_label').empty(); // clear error string
      //$("#txtCandidateBulkUploadStatus").val('');
      $('#txtCandidateBulkUploadStatus').html('');
      $('#candidate_error').html('');
      $('#candidate_modal_form_upload').modal('show'); // show bootstrap modal
  }
</script>

<!-- form modal -->

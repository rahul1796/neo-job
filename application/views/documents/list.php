<?php $this->load->view('layouts/errors');?>
<table id="tblSec" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;background: white;">
  <a class="btn btn-primary btn-min-width mr-1 mb-1" href="<?= base_url('documentscontroller/create/').$fields['candidate_id']; ?>" style="float: right;"><i class="icon-android-add"></i>Add New Document</a>
  <thead>
  <tr>
    <th style="background-color: white;">Document Type</th>
    <th style="background-color: white;">File</th>
  </tr>
  </thead>
  <tbody>

  <?php foreach($documents as $document): ?>
  <tr>
    <td><?= $document->document_name; ?></td>
    <td> <a class="btn btn-success mr-1 mb-1" href="<?= base_url('documents/').$document->file_name; ?>" target="_blank"><i class="fa fa-download"></i></a></td>
    <td> <a class="btn btn-danger mr-1 mb-1" onclick="confirmDelete(event);" href="<?= base_url('DocumentsController/delete/').$fields['candidate_id'].'/'.$document->id; ?>"><i class="fa fa-trash"></i></a></td>
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
</script>

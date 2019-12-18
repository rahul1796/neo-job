<?php $this->load->view('layouts/errors');?>
<table id="tblSec" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;background: white;">
  <a class="btn btn-primary btn-min-width mr-1 mb-1" href="<?= base_url('educationscontroller/create/').$fields['candidate_id']; ?>" style="float: right;"><i class="icon-android-add"></i>Add New Education</a>
  <thead>
  <tr>
    <th style="background-color: white;">Education Name</th>
    <th style="background-color: white;">Learning Type</th>
    <th style="background-color: white;">Specialization</th>
    <th style="background-color: white;">Institution</th>
    <th style="background-color: white;">Year Of Passing</th>
    <th style="background-color: white;">Action </th>
  </tr>
  </thead>
  <tbody>

  <?php foreach($educations as $education): ?>
      <tr>
    <td><?= $education->edu_name ?? ($education->education_name ?? 'N/A'); ?></td>
    <td><?= $education->learning_type ?? ($education->learning_type_name ?? 'N/A'); ?></td>
    <td><?= $education->specialization ?? 'N/A'; ?></td>
    <td><?= $education->institution ?? 'N/A'; ?></td>
    <td><?= $education->year_of_passing ?? 'N/A'; ?></td>

    <td> <a class="btn btn-warning mr-1 mb-1" href="<?= base_url('educationscontroller/edit/').$fields['candidate_id'].'/'.$education->id; ?>"><i class="fa fa-pencil"></i></a>
    <a class="btn btn-danger mr-1 mb-1" onclick="confirmDelete(event);" href="<?= base_url('educationscontroller/delete/').$fields['candidate_id'].'/'.$education->id; ?>"><i class="fa fa-trash"></i></a></td>

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

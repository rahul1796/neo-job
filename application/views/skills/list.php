<?php $this->load->view('layouts/errors');?>
<table id="tblSec" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;background: white;">
  <a class="btn btn-primary btn-min-width mr-1 mb-1" href="<?= base_url('skillscontroller/create/').$fields['candidate_id']; ?>" style="float: right;"><i class="icon-android-add"></i>Add New Skill</a>
  <thead>
  <tr>
    <th style="background-color: white;">Skill Name</th>
    <th style="background-color: white;">Version</th>
    <th style="background-color: white;">Last Used</th>
    <th style="background-color: white;">Experience</th>
    <th style="background-color: white;">Action </th>
  </tr>
  </thead>
  <tbody>

  <?php foreach($skills as $skill): ?>
  <tr>
    <td><?= $skill->skill_name ?? 'N/A'; ?></td>
    <td><?= $skill->version ?? 'N/A'; ?></td>
    <td><?= $skill->last_used_year ?? 'N/A'; ?></td>
    <td><?= $skill->experience_year ?? 'N/A'; ?></td>

    <td> <a class="btn btn-warning mr-1 mb-1" href="<?= base_url('skillscontroller/edit/').$fields['candidate_id'].'/'.$skill->id; ?>"><i class="fa fa-pencil"></i></a>
     <a class="btn btn-danger mr-1 mb-1"  onclick="confirmDelete(event);" href="<?= base_url('skillscontroller/delete/').$fields['candidate_id'].'/'.$skill->id; ?>"><i class="fa fa-trash"></i></a></td>

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

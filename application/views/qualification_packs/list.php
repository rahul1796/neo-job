<?php $this->load->view('layouts/errors');?>
<table id="tblSec" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;background: white;">
     <a class="btn btn-primary btn-min-width mr-1 mb-1" href="<?= base_url('qualificationpackscontroller/create/').$fields['candidate_id']; ?>" style="float: right;"><i class="icon-android-add"></i>Add New QP</a>
  <thead>
  <tr>
    <th style="background-color: white;">Qualification Pack</th>
    <th style="background-color: white;">Center Name</th>
    <th style="background-color: white;">Center Location</th>
    <th style="background-color: white;">Course</th>
    <th style="background-color: white;">Action </th>
  </tr>
  </thead>
  <tbody>

  <?php foreach($qps as $qp): ?>
    <tr>
    <td><?= $qp->qp_name ?? ($qp->qualification_pack ?? 'N/A'); ?></td>
    <td><?= $qp->center_name ?? 'N/A'; ?></td>
    <td><?= $qp->center_location ?? 'N/A'; ?></td>
    <td><?= $qp->course_name ?? 'N/A'; ?></td>

    <td> <a class="btn btn-warning mr-1 mb-1" href="<?= base_url('qualificationpackscontroller/edit/').$fields['candidate_id'].'/'.$qp->id; ?>"><i class="fa fa-pencil"></i></a>
    <a class="btn btn-danger mr-1 mb-1" onclick="confirmDelete(event);" href="<?= base_url('qualificationpackscontroller/delete/').$fields['candidate_id'].'/'.$qp->id; ?>"><i class="fa fa-trash"></i></a>
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
</script>

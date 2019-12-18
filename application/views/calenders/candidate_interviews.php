<?php if(isset($candidate_schedule) && count($candidate_schedule)>0): ?>
<h5> <strong>Candidate Interviews</strong> </h5>
<table class="table table-striped table-bordered display table-responsive nowrap" style="overflow-x: scroll;">
  <tr>
    <th>Schedule Date</th>
    <th>Candidate Name</th>
    <th>Candidate Email</th>
    <th>Candidate Mobile</th>
    <th>Company Name</th>
    <th>HR Email</th>
    <th>HR Phone</th>
    <th>Location</th>
    <th>Address</th>
  </tr>

  <?php foreach($candidate_schedule as $event): ?>
    <tr>

    <td><?= isset($event->schedule_date) ? date_format( date_create($event->schedule_date), 'l jS \of F Y h:i A') : 'N/A'; ?></td>
    <td><?= $event->candidate_name ?? 'N/A'; ?></td>
    <td><?= $event->candidate_email ?? 'N/A'; ?></td>
    <td><?= $event->candidate_mobile ?? 'N/A'; ?></td>
    <td><?= $event->customer_name ?? 'N/A'; ?></td>
    <td><?= $event->hr_email ?? 'N/A'; ?></td>
    <td><?= $event->hr_phone ?? 'N/A'; ?></td>
    <td><?= $event->location ?? 'N/A'; ?></td>
    <td><?= $event->address ?? 'N/A'; ?></td>
    </tr>
  <?php endforeach; ?>
</table>

  <?php endif; ?>

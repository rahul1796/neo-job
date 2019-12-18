<?php if(isset($lead_schedule) && count($lead_schedule)>0): ?>
  <h5> <strong>Lead / Customer Meetings</strong> </h5>
<table class="table table-striped table-bordered display table-responsive nowrap" style="overflow-x: scroll;">
  <tr>
    <th>Lead Managed By</th>
    <th>Status Name</th>
    <th>Schedule Date</th>
    <th>Contact Person</th>
    <th>Contact Mobile</th>
    <th>Address</th>
    <th>City</th>
  </tr>

  <?php foreach($lead_schedule as $event): ?>
    <tr>
    <td><?= $event->managed_by ?? 'N/A'; ?></td>
    <td><?= $event->status ?? 'N/A'; ?></td>
    <td><?= isset($event->schedule_date) ? date_format( date_create($event->schedule_date), 'l jS \of F Y h:i A') : 'N/A'; ?></td>
    <td><?= $event->meeting_person ?? 'N/A'; ?></td>
    <td><?= $event->contact_phone ?? 'N/A'; ?></td>
    <td><?= $event->contact_address ?? 'N/A'; ?></td>
    <td><?= $event->contact_city ?? 'N/A'; ?></td>
    </tr>
  <?php endforeach; ?>
  </table>
  <?php endif; ?>

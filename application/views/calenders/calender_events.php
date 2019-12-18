<?php if(isset($event_schedule) && count($event_schedule)>0): ?>
<h5> <strong>Events</strong> </h5>
<table class="table table-striped table-bordered display responsive nowrap">
  <tr>
    <th>Title</th>
    <th>Description</th>
    <th>Start Date</th>
    <th>End Date</th>
  </tr>

  <?php foreach($event_schedule as $event): ?>
    <tr>
    <td><?= $event->title ?? 'N/A'; ?></td>
    <td><?= $event->description ?? 'N/A'; ?></td>
    <td><?= isset($event->event_start) ? date_format( date_create($event->event_start), 'l jS \of F Y h:i A') : 'N/A'; ?></td>
    <td><?= isset($event->event_end) ? date_format( date_create($event->event_end), 'l jS \of F Y h:i A') : 'N/A'; ?></td>
    </tr>
  <?php endforeach; ?>
  </table>
  <?php endif; ?>

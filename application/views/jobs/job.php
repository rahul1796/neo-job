
<?php if(count($jobs) > 0): ?>
  <?php foreach($jobs as $job): ?>
  <div class="col-md-6 card" style="margin-bottom: 1px !important;height: 170px;">
    <div class="card-body" style="padding: 20px;">
      <div class="col-sm-8" style="margin-bottom: 25px;">
      <h5 id="job-title-<?= $job->id; ?>"><?= htmlspecialchars($job->job_title ?? 'N/A') ; ?></h5>
      <ul style="font-size: 14px;">
        <li>Job Qualificaiton Pack: <?= htmlspecialchars($job->qp_name ?? 'N/A') ; ?></li>
        <li>Job Location : <?= htmlspecialchars($job->job_location ?? 'N/A') ; ?></li>
        <li>Experience : <?= ($job->experience_from ?? 0).' - '.($job->experience_to ?? 0) ?> Years</li>
        <li>Educational Qualification :<?= htmlspecialchars($job->edu_name ?? 'N/A') ; ?></li>
        <?php
          if (isset($job->job_description) && $job->job_description!='')
          {
            ?>
            <a href="JavaScript:void(0);" data-toggle="tooltip" title="<?= htmlspecialchars($job->job_description ?? 'N/A') ; ?>">
              <p style="color: orange">More..</p>
            </a>
            <?php
          }
        ?>
      </ul>
      </div>
    <button type="button" class="btn btn-info" name="button" style="float: right;" data-toggle="modal"  onclick="apply('<?= $job->id?>')">Apply</button>
  </div>
  </div>
  <?php endforeach; ?>
<?php else: ?>
  <div class="col-md-6 card" style="margin-bottom: 1px !important;">
    <div class="card-body" style="padding: 20px;">
      <h5 style="text-align:center;"> <strong>No Matching Jobs Found</strong> </h5>
    </div>
  </div>
<?php endif; ?>

<div class="content-body" style="padding: 30px;margin-top: -15px;">

  <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-left: -25px;">
    <div class="breadcrumb-wrapper col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo anchor("partner/job_board","Job Board");?></a>
        </li>
        <li class="breadcrumb-item active">All Candidates
        </li>
      </ol>
    </div>
  </div>

  <?php $this->load->view('jobs/job_details', ['job_id' => $job_id, 'job'=> $data['job'],'is_filled'=> $is_filled]); ?>

  <div class="row">
    <div class="col-md-12">
      <form class="" action="<?php echo base_url('CandidatesController/allcandidates/'.$job_id);?>" method="GET">
        <?php $this->load->view('candidates/candidate_search_form', $data);?>
      </form>
    </div>
  </div>


<div class="row">
  <div class="col-md-6">
    <h3 class="text-success text-uppercase"> <strong><?php echo $page;?></strong> </h3>
  </div>
  <div class="col-md-6">
    <h5 style="text-align:right; font-weight: bold;"><?= ($candidates_count!=0) ? "<span id='candidate-count'>{$candidates_count}</span> Candidates found" : '' ?></h5>
  </div>
</div>
<br>

  <section id="description" class="card" style="border: none!important;">

    <div class="col-md-12">
      <div class="card">
        <div class="card-header">
          <label class="card-title" for="color" style="margin-bottom: -8px;"><h4></h4></label>
          <div class="form-group">
            <div class="page_display_log" style="color: green"></div>
          </div>
          <div class="panel-body" style="margin-top: -10px;">
            <div class="row text-small">
              <div class="col-sm-12">
                <div class="row">
                  <?php if(count($suggestedCandidates)>0): ?>
                    <?php foreach($suggestedCandidates as $suggested): ?>
                      <div class="col-md-12 card" style="padding:15px;" id="container_<?= $suggested->id?>">
                        <div class="col-md-2">
                          <img src="<?= base_url('assets/images/no_user.jpg');?>" class="img-thumbnail" width="100" height="100">
                        </div>
                        <div class="col-md-4">
                          <p><strong>Name:</strong> <?php echo $suggested->candidate_name ?? 'N/A';?></p>
                          <p><strong>Email:</strong> <?php echo $suggested->email?? 'N/A'; ?></p>
                          <p><strong>Phone:</strong> <?php echo $suggested->mobile?? 'N/A'; ?></p>
                          <p><strong>Candidate Number:</strong> <?php echo $suggested->candidate_number?? 'N/A'; ?></p>
                          <p><strong>Age:</strong> <?php echo $suggested->age?? 'N/A'; ?></p>
                          <p><strong>QP:</strong> <?php echo $suggested->qp_name?? 'N/A'; ?></p>
                          <p><strong>Education Name:</strong> <?php echo $suggested->edu_name?? 'N/A'; ?></p>
                          <p><strong>Batch Code:</strong> <?php echo $suggested->batch_code?? 'N/A'; ?></p>
                        </div>
                        <div class="col-md-4">
                          <p><strong>Gender:</strong> <?php echo $suggested->gender_code?? 'N/A'; ?></p>
                          <p><strong>Marital Status:</strong> <?php echo $suggested->marital_status?? 'N/A'; ?></p>
                          <p><strong>Caste Category:</strong> <?php echo $suggested->caste_cat?? 'N/A'; ?></p>
                          <p><strong>Religion:</strong> <?php echo $suggested->religion?? 'N/A'; ?></p>
                          <p><strong>Employment Type:</strong> <?php echo $suggested->emp_type ?? 'N/A'; ?></p>
                          <p><strong>Designation:</strong> <?php echo $suggested->emp_designation?? 'N/A'; ?></p>
                          <p><strong>Center Name:</strong> <?php echo $suggested->center_name?? 'N/A'; ?></p>
                          <p><strong>Course Name:</strong> <?php echo $suggested->course_name?? 'N/A'; ?></p>
                        </div>
                        <div class="col-md-2">
                          <button type="button" name="button" class="btn btn-primary" id="button_<?php echo $suggested->id; ?>" onclick="changeCandidateJobStatus('<?php echo $suggested->id;?>', '<?php echo $job_id;?>', 'insert')">Select As Intrested</button>
                        </div>

                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="col-md-12 card-block" style="text-align:center;">
                      <h5 class="text-center">No Candidates Found</h5>
                    </div>
                  <?php endif;?>
                  <div class="col-md-12">
                    <?php echo $this->pagination->create_links();?>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
    </div>
<?php $this->load->view('candidates/script');?>

<?php $this->load->view('candidates/candidates_jobs_modal', $data['job']);?>

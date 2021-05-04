
<div class="content-body" style="padding: 30px;margin-top: -15px;">
  <div class=" breadcrumbs-top col-md-9 col-xs-12">
    <div class="breadcrumb-wrapper col-xs-12">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><?php echo anchor("partner/job_board","Job Board");?></a>
        </li>
        <li class="breadcrumb-item active">Applied Candidates
        </li>
      </ol>
    </div>
  </div>

  <?php $this->load->view('jobs/job_details', ['job_id' => $job_id, 'job'=> $data['job'],'is_filled'=> $is_filled]); ?>

  <div class="row">
    <div class="col-md-12">
      <form class="" action="<?php echo base_url('CandidatesController/appliedCandidates/'.$job_id);?>" method="GET">
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

    <div class="col-md-12" style="margin-bottom: 5%;">
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
                  <?php //echo var_dump($appliedCandidates); ?>
                  <?php if(count($appliedCandidates)>0):?>
                    <?php foreach($appliedCandidates as $applied): ?>
                      <div class="col-md-12 card-block" style="background:white; border-bottom: 1px solid #f3f3f3;">

                        <div class="col-md-2">
                          <img src="<?= base_url('assets/images/no_user.jpg');?>" class="img-thumbnail" width="100" height="100">
                        </div>
                        <div class="col-md-3">
                          <p><strong>Name:</strong> <span id="candidate_name_<?= $applied->id; ?>"><?php echo $applied->candidate_name;?></span></p>
                          <p><strong>Email:</strong> <?php echo $applied->email?? 'N/A'; ?></p>
                          <p><strong>Phone:</strong> <?php echo $applied->mobile?? 'N/A'; ?></p>
                          <p><strong>Candidate Number:</strong> <?php echo $applied->candidate_number?? 'N/A'; ?></p>
                          <p><strong>Age:</strong> <?php echo $applied->age?? 'N/A'; ?></p>
                          <p><strong>QP:</strong> <?php echo $applied->qp_name?? 'N/A'; ?></p>
                          <p><strong>Education Name:</strong> <?php echo $applied->edu_name?? 'N/A'; ?></p>
                          <p><strong>Batch Code:</strong> <?php echo $applied->batch_code?? 'N/A'; ?></p>
                        </div>
                        <div class="col-md-3">
                          <p><strong>Gender:</strong> <?php echo $applied->gender_code?? 'N/A'; ?></p>
                          <p><strong>Marital Status:</strong> <?php echo $applied->marital_status?? 'N/A'; ?></p>
                          <p><strong>Caste Category:</strong> <?php echo $applied->caste_cat?? 'N/A'; ?></p>
                          <p><strong>Religion:</strong> <?php echo $applied->religion?? 'N/A'; ?></p>
                          <p><strong>Employment Type:</strong> <?php echo $applied->emp_type ?? 'N/A'; ?></p>
                          <p><strong>Designation:</strong> <?php echo $applied->emp_designation?? 'N/A'; ?></p>
                          <p><strong>Center Name:</strong> <?php echo $applied->center_name?? 'N/A'; ?></p>
                          <p><strong>Course Name:</strong> <?php echo $applied->course_name?? 'N/A'; ?></p>
                        </div>
                        <div class="col-md-4">
                          <p><strong>Current Status:</strong> </p>
                          <select class="status_selector form-control" id="selected_<?php echo $applied->id; ?>" name="">
                            <?php foreach($job_status as $status): ?>
                              <option value="<?php echo $status->id ?>" data-job="<?= $job_id; ?>" data-candidate="<?= $applied->id; ?>" <?php echo ($status->id == $applied->candidate_status_id) ? 'selected' : '' ; ?> data-svalue="<?php echo $status->value; ?>" data-notification="<?php echo $status->notification_status; ?>" > <?php echo $status->name ?></option>
                            <?php endforeach; ?>
                          </select>
                          <!-- data-provide="datepicker" data-date-format="dd-M-yyyy" -->
                          <input type="text" id="lead_schedule_input_<?php echo $applied->id; ?>" style="margin-top:10px;" placeholder="Choose Date" class="form-control hidden feedback-date" value="">
                          <input type="text" placeholder="Enter Remark" style="margin-top:10px;" id="text_<?php echo $applied->id; ?>" class="form-control hidden feedback-input" value="">
                          <div class="">
                            <span class='text text-danger' id="error_<?php echo $applied->id; ?>"></span>
                          </div>
                          <button type="button" class="btn btn-success" style="margin-top:10px;" name="button" id="button_<?php echo $applied->id; ?>" onclick="changeCandidateJobStatus('<?php echo $applied->id;?>', '<?php echo $job_id;?>', 'update')">Change Status</button>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <div class="col-md-12 card-block" style="text-align:center;">
                      <h5 class="text-center">No Applied Candidates Found</h5>
                    </div>
                  <?php endif; ?>
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/datetimepicker/css/bootstrap-datetimepicker.css')?>">
<script src="<?php echo base_url().'adm-assets/datetimepicker/js/bootstrap-datetimepicker.js'?>" type="text/javascript"></script>
<?php $this->load->view('candidates/script');?>

<?php $this->load->view('common/placement_modal',$data);?>


  <div class="content-body" style="padding: 30px;">

          <div class="row">
            <div class="col-md-12">
              <form class="" action="<?php echo base_url('CandidatesController/appliedCandidates/'.$job_id);?>" method="GET">
                <?php $this->load->view('candidates/candidate_search_form', $data);?>
              </form>
            </div>
          </div>

  <div class="row">
    <div class="col-md-12">
      <h3><?php echo $page;?></h3>
    </div>
  </div>
  <div class="row">
    <?php //echo var_dump($appliedCandidates); ?>
    <?php foreach($appliedCandidates as $applied): ?>
    <div class="col-md-12 card-block" style="background:white; margin:10px">

        <div class="col-md-2">
          <img src="<?= base_url('assets/images/no_user.jpg');?>" class="img-thumbnail" width="100" height="100">
        </div>
        <div class="col-md-3">
          <p><strong>Name:</strong> <?php echo $applied->first_name;?> <?php echo $applied->last_name;?></p>
          <p><strong>Email:</strong> <?php echo $applied->email; ?></p>
          <p><strong>Phone:</strong> <?php echo $applied->mobile_number; ?></p>
          <p><strong>Center Name:</strong> <?php echo $applied->center_name; ?></p>
        </div>
        <div class="col-md-3">
          <p><strong>Registration Id:</strong> <?php echo $applied->candidate_registration_id; ?></p>
          <p><strong>Enrollment Number:</strong> <?php echo $applied->candidate_enrollment_id; ?></p>
          <p><strong>Gender:</strong> <?php echo $applied->gender_code; ?></p>
          <p><strong>Marital Status:</strong> <?php echo $applied->marital_status; ?></p>
        </div>
        <div class="col-md-4">
          <p><strong>Current Status:</strong> </p>
          <select class="status_selector form-control" id= "selected_<?php echo $applied->id; ?>" name="">
            <?php foreach($job_status as $status): ?>
              <option value="<?php echo $status->id ?>" <?php echo ($status->id == $applied->status_id) ? 'selected' : '' ; ?> data-svalue="<?php echo $status->value; ?>"> <?php echo $status->name ?></option>
            <?php endforeach; ?>
          </select>
          <input type="text" placeholder="Enter Remark" style="margin-top:10px;" id="text_<?php echo $applied->id; ?>" class="form-control hidden feedback-input" value="">
          <button type="button" class="btn btn-success" style="margin-top:10px;" name="button" id="button_<?php echo $applied->id; ?>" onclick="changeCandidateJobStatus('<?php echo $applied->id;?>', '<?php echo $job_id;?>', 'update')">Change Status</button>
        </div>
    </div>
  <?php endforeach; ?>
  <div class="col-md-12">
    <?php echo $this->pagination->create_links();?>
  </div>
  </div>
</div>

<?php $this->load->view('candidates/script');?>

<br><br><br>

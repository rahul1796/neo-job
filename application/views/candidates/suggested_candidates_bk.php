
<div class="content-body" style="padding: 30px;">
   <div class="row">
     <div class=" breadcrumbs-top col-xs-12">
          <div class="breadcrumb-wrapper col-xs-12">
              <ol class="breadcrumb">

              </ol>
          </div>
      </div>
   </div>

<div class="row">
  <div class="col-md-12">
    <h3><?php echo $page?></h3>
  </div>
</div>
<br>

  <div class="row">
    <?php foreach($suggestedCandidates as $suggested): ?>
    <div class="col-md-12 card" style="padding:15px;" id="container_<?= $suggested->id?>">
      <div class="col-md-2">
        <img src="<?= base_url('assets/images/no_user.jpg');?>" class="img-thumbnail" width="100" height="100">
      </div>
      <div class="col-md-4">
        <p><strong>Name:</strong> <?php echo $suggested->first_name;?> <?php echo $suggested->last_name;?></p>
        <p><strong>Email:</strong> <?php echo $suggested->email; ?></p>
        <p><strong>Phone:</strong> <?php echo $suggested->mobile_number; ?></p>
        <p><strong>Center Name:</strong> <?php echo $suggested->center_name; ?></p>
      </div>
      <div class="col-md-4">
        <p><strong>Registration Id:</strong> <?php echo $suggested->candidate_registration_id; ?></p>
        <p><strong>Enrollment Number:</strong> <?php echo $suggested->candidate_enrollment_id; ?></p>
        <p><strong>Gender:</strong> <?php echo $suggested->gender_code; ?></p>
        <p><strong>Marital Status:</strong> <?php echo $suggested->marital_status; ?></p>
      </div>
      <div class="col-md-2">
        <button type="button" name="button" class="btn btn-primary" id="button_<?php echo $suggested->id; ?>" onclick="changeCandidateJobStatus('<?php echo $suggested->id;?>', '<?php echo $job_id;?>', 'insert')">Select As Intrested</button>
      </div>

    </div>
  <?php endforeach; ?>
  <div class="col-md-12">
    <?php echo $this->pagination->create_links();?>
  </div>
  </div>
    </div>
<?php $this->load->view('candidates/script');?>

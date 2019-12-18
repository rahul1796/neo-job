<div class="card" style="margin-top: 35px; padding: 16px;">
    <div class="card-body">
        <h5 class="card-title">Job Details</h5>
        <a href="<?php echo base_url('/Pramaan/candidate_joined_jobwise/'.$job_id) ;?>" class="btn btn-info btn-min-width mr-1 mb-1" style="float: right; margin-top: -41px;">Joined Candidates</a>
        <a href="<?php echo base_url('/candidatescontroller/appliedCandidates/'.$job_id) ;?>" class="btn btn-success btn-min-width mr-1 mb-1" style="float: right; margin-top: -41px;">Applied Candidates</a>

          <div class="form-group row" style="margin-top: 20px;">
            <div class="col-md-6">
              <label for="placement_location">Customer Name:</label>
              <input type="text" class="form-control" id="customer_name"  name="customer_name" value="<?= $job->customer_name ?? 'N/A'; ?>" onkeydown="return false;" disabled="disabled"/>
            </div>

            <div class="col-md-6">
              <label for="ctc">Job Title:</label>
              <input type="text" class="form-control" id="job_title"  name="job_title" value="<?= $job->job_title ?? 'N/A'; ?>" onkeydown="return false;" disabled="disabled"/>
            </div>
          </div>
               <div class="form-group row" style="margin-top: 20px;">
            <div class="col-md-6">
              <label for="date_of_join">QP:</label>
              <input type="text" class="form-control" id="qualification_pack_name"  name="qualification_pack_name" value="<?= $job->qualification_pack_name.' ('.$job->qualification_code.')' ?? 'N/A'; ?>" onkeydown="return false;" disabled="disabled"/>
            </div>
            <div class="col-md-6">
              <label for="offer_letter_date">Job Location:</label>
              <input type="text" class="form-control" id="job_location"  name="job_location" value="<?= $job->district_name ?? 'N/A'; ?>" onkeydown="return false;" disabled="disabled"/>
            </div>
          </div>

    </div>
</div>

<div class="modal fade" id="candidates_jobs_modal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Candidates Working Jobs</h4>
        </div>
        <div class="modal-body">
          <?php if($job->business_vertical_id==3):?>
            <div class="alert alert-warning">
              <h4>Current candidate is still working in below jobs</h4>
            </div>
          <?php else:?>
            <div class="alert alert-danger">
              <h4>Candidate needs to resign from Current Job in order to proceed further.</h4>
            </div>
          <?php endif; ?>
          <div class="">
            <input type="hidden" name="" id="candidate_id_modal" value="">
            <input type="hidden" name="" id="job_id_modal" value="">
            <input type="hidden" name="" id="status_id_modal" value="">
            <input type="hidden" name="" id="schedule_date_modal" value="">
            <input type="hidden" name="" id="flag_modal" value="">
            <input type="hidden" name="" id="remark_modal" value="">
          </div>
          <table class="table" id="candidates_jobs_table">

          </table>
        </div>
        <div class="modal-footer">
          <?php if($job->business_vertical_id==3):?>
            <button type="button" class="btn btn-danger" onclick="proceedAnyway();" id="proceed-anyway-btn" data-dismiss="modal">Proceed Anyway</button>
          <?php endif; ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>

    </div>
  </div>


<div class="modal fade" id="jobs_handler_modal" role="dialog" >
      <div class="modal-dialog" style="margin-top: 45px; transform: scale(1.1); box-shadow: 0 0 15px #000;">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User Associated with Job</h4>
          </div>
          <div class="modal-body">
            <div class="alert alert-danger">
              <h5>Please Contact respected Job Handler to update Candidate Resignation Details</h5>
            </div>
            <table class="table" id="jobs_handler_table">

            </table>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

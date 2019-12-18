
<div class="content-body" style="padding: 30px; margin-top: -32px;">
  <h3 style=" margin-left: 5px;">Calendar</h3>
  <div class="row">
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-left: -7px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
          </li>
          <li class="breadcrumb-item active">Calendar
          </li>
        </ol>
      </div>
    </div>
      <div class="col-md-2">
      <button type="button" class="btn btn-primary" id="event-modal-btn" onclick="openEventModal();" name="button" style="margin-left: 44px;"><i class="fa fa-plus"></i> Add Event</button>
      </div>
    </div>


  <div class="card" style="margin-top: 15px; padding: 16px;">
    <div class="card-body">
      <h5 class="card-title">Event Details</h5>
      <div class="form-group " style="margin-top: 20px;">
        <div class="row">
          <div class="col-md-5">
            <?php echo $this->calendar->generate($year, $month, $my_dates); ?>
            <br>

          </div>
          <div class="col-md-7">
            <?php if(!empty($current_date)): ?>
              <div class="row">
                <div class="col-md-12">
                  <h5>Showing schedules and events for <strong><?= $current_date ?? ''; ?></strong> </h5>
                  <hr>
                </div>
              </div>
            <?php endif; ?>
            <div class="row">
              <?php if(isset($data)): ?>
                <div class="col-md-12">
                  <?php $this->load->view('calenders/calender_events', $data)?>
                </div>
                <div class="col-md-12">
                  <?php $this->load->view('calenders/candidate_interviews', $data)?>
                </div>
                <div class="col-md-12">
                  <?php $this->load->view('calenders/leads_schedules', $data)?>
                </div>
              <?php endif; ?>

            </div>
          </div>
        </div>
      </div>

    </div>
  </div>


</div>

<?php $this->load->view('calenders/style')?>

<?php $this->load->view('calenders/event_modal')?>

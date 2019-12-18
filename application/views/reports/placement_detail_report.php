<link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/datetimepicker/css/bootstrap-datetimepicker.css')?>">
<style type="text/css">
    .label{
    float: left;
    padding-right: 4px;
    padding-top: 2px;
    position: relative;
    text-align: right;
    vertical-align: middle;
    }
    .label:before{
    content:"*" ;
    color:red
    }
</style>
<div class="content-body" style="overflow-x: hidden !important;">
    <!--<a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php /*echo base_url("pramaan/add_address_book/$parent_id")*/?>" style="margin-left: 50px;"><i class="icon-android-add"></i>Add Contact</a>-->
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Download <?= $report_list[$report]; ?>
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title"><?= $report_list[$report]; ?></h4>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert hidden alert-danger" id="alert-box" style="">
                                        <h4 id="status_message"></h4>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <input type="hidden" name="report_id" id= "report_id" value="<?= $report; ?>">
                                <div class="col-md-6 form-group">
                                    <label for="" class="label">Start Date</label>
                                    <input type='text' class="form-control" readonly id='start_date_input'>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="" class="label">End Date</label>
                                    <input type='text' class="form-control" readonly id='end_date_input' />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="batch_start_from_date" class="">Batch Start From Date</label>
                                    <input type='text' class="form-control" readonly id='batch_start_from_date'>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="batch_start_to_date" class="">Batch Start To Date</label>
                                    <input type='text' class="form-control" readonly id='batch_start_to_date' />
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="batch_end_from_date" class="">Batch End From Date</label>
                                    <input type='text' class="form-control" readonly id='batch_end_from_date'>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="batch_end_to_date" class="">Batch End To Date</label>
                                    <input type='text' class="form-control" readonly id='batch_end_to_date' />
                                </div>
                            </div>


                            <div class="row">
                              <div class="col-md-6 form-group">
                                  <label for="state_id" class="">State:</label>
                                  <select class="form-control select2-neo" name="state_id" id="state_id">
                                      <option value="0">Select State</option>
                                      <?php foreach($state_options as $option):?>
                                      <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                      <?php endforeach; ?>
                                  </select>
                              </div>

                              <div class="col-md-6 form-group">
                                  <label for="district_id" class="">District/City:</label>
                                  <select class="form-control select2-neo" name="district_id" id="district_id">
                                      <option value="0">Select District/City</option>
                                  </select>
                              </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="customer_id" class="">Client:</label>
                                    <select class="form-control select2-neo" name="customer_id" id="customer_id">
                                        <option value="">Select Client</option>
                                        <?php foreach($customer_options as $customer):?>
                                        <option value="<?php echo $customer->id; ?>" ><?php echo $customer->customer_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="gender_id" class="">Gender:</label>
                                    <select class="form-control select2-neo" name="gender_id" id="gender_id">
                                        <option value="">Select Gender</option>
                                        <?php foreach($gender_options as $gender):?>
                                        <?php if($gender->id!=4): ?>
                                        <option value="<?php echo $gender->id; ?>" ><?php echo $gender->name; ?></option>
                                        <?php endif; ?>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="job_location" class="">Job Location:</label>
                                    <input type="text" class="form-control" name="job_location" id="job_location" value="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="job_title" class="">Job Title:</label>
                                    <input type="text" class="form-control" name="job_title" id="job_title" value="">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="pincode" class="">Pincode:</label>
                                    <input type="text" class="form-control" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "6" name="pincode" id="pincode" value="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="candidate_status_id" class="">Select Candidate Status:</label>
                                    <select class="form-control select2-neo" name="candidate_status_id" id="candidate_status_id">
                                        <option value="">Select Candidate Statuses</option>
                                        <?php foreach($candidate_statuses_options as $option):?>
                                        <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="center_name" class="">Select Center:</label>
                                    <select class="form-control select2-neo" name="center_name" id="center_name">
                                        <option value="">Select Center</option>
                                        <?php foreach($center_options as $option):?>
                                        <option value="<?php echo $option->center_name; ?>" ><?php echo $option->center_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="employment_type" class="">Select Employment Type:</label>
                                    <select class="form-control select2-neo" name="employment_type" id="employment_type">
                                        <option value="">Select Employment Type</option>
                                        <?php foreach($employment_type_options as $option):?>
                                        <option value="<?php echo $option->name; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="qp_id" class="">Select Qualification Pack:</label>
                                    <select class="form-control select2-neo" name="qp_id" id="qp_id">
                                        <option value="">Select Qualification Packs</option>
                                        <?php foreach($qp_options as $option):?>
                                        <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="bv_id" class="">Select Business Vertical:</label>
                                    <select class="form-control select2-neo" name="bv_id" id="bv_id">
                                        <option value="">Select Business Vertical</option>
                                        <?php foreach($business_vertical_options as $option):?>
                                        <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary" name="button" onclick="downloadreport();" style="margin-left: 15px; margin-top:27px;">Download Report</button>

                                    <a name="reset-form" class="btn btn-default" style="margin-left: 15px; margin-top:27px;" href="<?= current_url().'?slug=getPlacementDetailReport'; ?>">Reset</a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script src="<?php echo base_url().'adm-assets/datetimepicker/js/bootstrap-datetimepicker.js'?>" type="text/javascript"></script>
<script type="text/javascript">
const _MS_PER_DAY = 1000 * 60 * 60 * 24;

$(document).ready(function() {

  $('.select2-neo').select2();
});

$(document).ready(function() {

    $('#batch_start_from_date, #batch_start_to_date, #batch_end_from_date, #batch_end_to_date, #start_date_input, #end_date_input').datetimepicker({
      //language:  'fr',
      weekStart: 1,
      endDate: '+0d',
      startView: 2,
      todayHighlight:'TRUE',
      forceParse: 0,
      showMeridian: 1,
      format: 'dd-MM-yyyy',
      autoclose: true,
      minuteStep: 1,
      todayBtn: true,
      maxView: 4,
      minView: 2
    });


      $('#state_id').on('change', function() {
        let state_id = $(this).val();
        $('#district_id').html('');
        $('#district_id').append($('<option>').text('Select District').attr('value', 0));
        if(state_id!=0) {
           getDistricts(state_id);
        }
      });

  });

  function getDistricts(id){
    var request = $.ajax({
      url: "<?= base_url(); ?>master/getDistricts/"+id,
      type: "GET",
    });

    request.done(function(msg) {
      var response = JSON.parse(msg);
      // alert(response);
      $('#district_id').html('');
      $('#district_id').append($('<option>').text('Select District').attr('value', 0));
      response.forEach(function(district) {
         $('#district_id').append($('<option>').text(district.name).attr('value', district.id));
      })
    });

    request.fail(function(jqXHR, textStatus) {
      alert( "Request failed: " + textStatus );
    });
  }

      function downloadreport() {
        $('#alert-box').addClass('hidden');
        $('#alert-box').removeAttr('style');

        let start_date = $('#start_date_input').val();
        let end_date = $('#end_date_input').val();
        let batch_start_from_date = $('#batch_start_from_date').val();
        let batch_start_to_date = $('#batch_start_to_date').val();
        let batch_end_from_date = $('#batch_end_from_date').val();
        let batch_end_to_date = $('#batch_end_to_date').val();
        let gender_id = ($('#gender_id').find(':selected').val()) || 0;
        let customer_id = ($('#customer_id').find(':selected').val()) || 0;
        let bv_id = ($('#bv_id').find(':selected').val()) || 0;
        let qp_id = ($('#qp_id').find(':selected').val()) || 0;
        let state_id = ($('#state_id').find(':selected').val()) || 0;
        let district_id = ($('#district_id').find(':selected').val()) || 0;
        let center_name = ($('#center_name').find(':selected').val()) || '';
        let employment_type = ($('#employment_type').find(':selected').val()) || '';
        let candidate_status_id = ($('#candidate_status_id').find(':selected').val()) || 0;
        let job_location = $('#job_location').val();
        let job_title = $('#job_title').val();
        let pincode = $('#pincode').val();

        let batch_start_from = new Date(batch_start_from_date);
        let batch_start_to = new Date(batch_start_to_date);

        let batch_end_from = new Date(batch_end_from_date);
        let batch_end_to = new Date(batch_end_to_date);

        let start = new Date(start_date);
        let end = new Date(end_date);
        let today = new Date();
        //let report = ($('#report_id').find(':selected').val()) || '';
        let report = ($('#report_id').val()) || '';
        let reportURL = '<?= base_url('reports/');?>'+report;
        //console.log(dateDiffInDays(end, start));
        if(report=='' || start_date=='' || end_date=='') {
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html("Select Start & End date");
          hideErrorAlert();
          return;
        }
        else if(start>today || end>today) {
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html("Start/End date must be less than today's date");
          hideErrorAlert();
          return;
        } else if(end<start) {
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html('Start date must be less than End date ');
          hideErrorAlert();
          return;
        } else if(dateDiffInDays(end, start)>360){
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html('Maximum 360 Days report allowed at this time');
          hideErrorAlert();
          return;
        } else if((batch_start_from_date!='' && batch_start_to_date=='')||(batch_start_from_date=='' && batch_start_to_date!='')){
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html("Select Batch Start From and Batch Start To date");
          hideErrorAlert();
          return;
        } else if(batch_start_from>today || batch_start_to>today) {
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html("Batch Start from/to date must be less than today's date");
          hideErrorAlert();
          return;
        } else if(batch_start_to<batch_start_from) {
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html('Batch Start From date must be less than Batch Start To date ');
          hideErrorAlert();
          return;
        } else if((batch_end_from_date!='' && batch_end_to_date=='')||(batch_end_from_date=='' && batch_end_to_date!='')){
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html("Select Batch End From and Batch End To date");
          hideErrorAlert();
          return;
        } else if(batch_end_from>today || batch_end_to>today) {
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html("Batch End from/to date must be less than today's date");
          hideErrorAlert();
          return;
        } else if(batch_end_to<batch_end_from) {
          $('#alert-box').removeClass('hidden');
          $('#status_message').html('').html('Batch End From date must be less than Batch End To date ');
          hideErrorAlert();
          return;
        }
          else {
          reportURL = reportURL+'?start_date='+start_date.toString()+'&end_date='+end_date.toString()+
                      '&batch_start_from_date='+batch_start_from_date+'&batch_start_to_date='+batch_start_to_date+
                      '&batch_end_from_date='+batch_end_from_date+'&batch_end_to_date='+batch_end_to_date+
                      '&gender_id='+gender_id+'&customer_id='+customer_id+'&bv_id='+bv_id+'&qp_id='+qp_id+
                      '&center_name='+center_name+'&employment_type='+employment_type+
                      '&candidate_status_id='+candidate_status_id+'&job_location='+job_location+
                      '&job_title='+job_title+'&pincode='+pincode+'&district_id='+district_id+'&state_id='+state_id;
          window.open(encodeURI(reportURL), '_blank');
          //window.location.href = reportURL;
        }
     }

     function hideErrorAlert() {
       window.scrollTo(0,0);
       window.setTimeout(function() {
         $("#alert-box").fadeTo(500, 0).slideUp(500, function(){
           $("#alert-box").addClass('hidden');
         });
       }, 3000);
     }
      // a and b are javascript Date objects
      function dateDiffInDays(a, b) {
        // Discard the time and time-zone information.
        const utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate());
        const utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate());

        return Math.abs(Math.floor((utc2 - utc1) / _MS_PER_DAY));
      }

</script>

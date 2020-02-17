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

                            <div class="card" style="margin-top: 5px; padding: 16px;">
                                <div class="card-body">
                                    <h5 class="card-title">Job Created</h5>
                                    <div class="row">
                                        <input type="hidden" name="report_id" id= "report_id" value="<?= $report; ?>">
                                        <div class="col-md-6 form-group">
                                            <label for="" class="label">From</label>
                                            <input type='text' class="form-control" readonly id='start_date_input'>
                                        </div>
                                        <div class="col-md-6 form-group">
                                            <label for="" class="label">To</label>
                                            <input type='text' class="form-control" readonly id='end_date_input' />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="customer_id" class="">Client:</label>
                                    <select class="form-control select2-neo" name="customer_id" id="customer_id">
                                        <option value="">Select Client</option>
                                        <?php foreach($customer_options as $customer):?>
                                        <option value="<?php echo $customer->id; ?>" ><?php echo $customer->company_name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="user_id" class="">Job Posted By User:</label>
                                    <select class="form-control select2-neo" name="user_id" id="user_id">
                                        <option value="">Select User</option>
                                        <?php foreach($user_options as $option):?>
                                        <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="education_id" class="">Education:</label>
                                    <select class="form-control select2-neo" name="education_id" id="education_id">
                                        <option value="">Select Education</option>
                                        <?php foreach($education_options as $option):?>
                                        <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="industry_id" class="">Industry:</label>
                                    <select class="form-control select2-neo" name="industry_id" id="industry_id">
                                        <option value="">Select Industry</option>
                                        <?php foreach($industry_options as $option):?>
                                        <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 form-group">
                                    <label for="job_location" class="">Job Location:</label>
                                    <input type="text" class="form-control" name="job_location" id="job_location" value="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="job_title" class="">Job Title:</label>
                                    <input type="text" class="form-control" name="job_title" id="job_title" value="">
                                </div>
                                <div class="col-md-4 form-group">
                                    <label for="pincode" class="">Pincode:</label>
                                    <input type="text" class="form-control" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "6" name="pincode" id="pincode" value="">
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
                                    <label for="bv_id" class="">Select Product:</label>
                                    <select class="form-control select2-neo" name="bv_id" id="bv_id">
                                        <option value="">Select Product</option>
                                        <?php foreach($business_vertical_options as $option):?>
                                        <option value="<?php echo $option->id; ?>" ><?php echo $option->name; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary" name="button" onclick="downloadreport();" style="margin-top: 27px;margin-left: 15px;">Download Report</button>
                                    <a name="reset-form" class="btn btn-default" style="margin-left: 15px; margin-top:27px;" href="<?= current_url().'?slug=getJobDetailedReport'; ?>">Reset</a>
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
  });


      function downloadreport() {
        $('#alert-box').addClass('hidden');
        $('#alert-box').removeAttr('style');

        let start_date = $('#start_date_input').val();
        let end_date = $('#end_date_input').val();
        let customer_id = ($('#customer_id').find(':selected').val()) || 0;
        let bv_id = ($('#bv_id').find(':selected').val()) || 0;
        let qp_id = ($('#qp_id').find(':selected').val()) || 0;
        let industry_id = ($('#industry_id').find(':selected').val()) || 0;
        let education_id = ($('#education_id').find(':selected').val()) || 0;
        let user_id = ($('#user_id').find(':selected').val()) || 0;
        let job_location = $('#job_location').val();
        let job_title = $('#job_title').val();
        let pincode = $('#pincode').val();

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
        }
          else {
          reportURL = reportURL+'?start_date='+start_date.toString()+'&end_date='+end_date.toString()+
                      '&customer_id='+customer_id+'&bv_id='+bv_id+'&qp_id='+qp_id+
                      '&industry_id='+industry_id+'&education_id='+education_id+
                      '&job_location='+job_location+'&job_title='+job_title+'&user_id='+user_id+'&pincode='+pincode;
          window.open(encodeURI(reportURL), '_blank');
          //window.location.href = reportURL;
        }
     }

     function hideErrorAlert() {
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

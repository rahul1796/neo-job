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
                <li class="breadcrumb-item active">Reports
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Activity Reports</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">
                            <div class="col-md-4">
                              <label for="report_id" class="label">Report:</label>
                              <select class="form-control" name="gender_id">
                                  <option value="">Select Report Type</option>
                                  <option value="volvo">Report 1</option>
                                  <option value="saab">Report 2</option>
                                  <option value="mercedes">Report 3 </option>
                                  <option value="audi">Report 4</option>
                              </select>
                            </div>
                                                         
                                 <div class="col-md-4 form-group">
                                    <label for="" class="label">Start Date</label>
                                     <input type='text' class="form-control" id='start_date_input'>
                                  </div>
                                  <div class="col-md-4 form-group">
                                    <label for="" class="label">End Date</label>
                                     <input type='text' class="form-control" id='end_date_input' />
                                  </div>                      
                            
                           <button type="button" class="btn btn-primary" name="button" onclick="downloadreport();" style="margin-top: 27px;margin-left: 15px;">Download Report</button>
                           
                            
                       </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->
</div>
<script src="<?php echo base_url().'adm-assets/datetimepicker/js/bootstrap-datetimepicker.js'?>" type="text/javascript"></script>
<script>
    
    function downloadreport() {
      $('#alert-box').addClass('hidden');     
      let start_date = $('#start_date_input').val();
      let end_date = $('#end_date_input').val();
      let start = new Date(start_date);
      let end = new Date(end_date);
      let today = new Date();
     if(start<today || end<today) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html("Event date must be greater than today's date");
      } else if(end<=start) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html('Start date/time must be less than End date/time ');
      }
  }
  
   
  $(document).ready(function() {

      $('#start_date_input, #end_date_input').datetimepicker({
        //language:  'fr',
        weekStart: 1,
        todayHighlight: 1,
        startView: 2,
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
</script>





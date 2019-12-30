<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/responsive.dataTables.min.css'?>">
<script>
  var statusOptions = JSON.parse('<?= json_encode($lead_status_options); ?>');
  console.log(statusOptions);
</script>
<style>
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

<div class="content-body" style="padding: 30px; margin-top: 10px;">
  <div class="row">
    <div class="col-md-12">
      <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-primary" id="server-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5><?php echo $_SESSION['status']; ?></h5>
      </div>
      <br><br>
      <?php endif; ?>
    </div>

  </div>

  <?php if (in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_add_roles())): ?>
    <a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php echo base_url("leads/create")?>" style="float: right;"><i class="icon-android-add"></i>Add Lead</a>
  <?php endif; ?>


    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;margin-top: -34px;">

      <div class="row">
        <div class="col-md-12">
          <h2>Available Leads</h2>
        </div>
        <br>
      </div>
        <div class="breadcrumb-wrapper col-xs-12" style="margin-left: -16px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Lead
                </li>
            </ol>
        </div>
    </div>
    <section>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Leads</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form id="search" style="padding: 15px;">
                                <div class="row">
                                  <div class="col-md-2 pl-1">
                                        <div class="form-group">
                                            <label for="search_by">Search By:</label>
                                            <select class="form-control" id="search_by" name="search_by" style="width: 250px;" onchange="searchby_onchange(this.value)">
                                                <option value="0"> -Select-</option>
                                                <option value="1">Customer Name</option>
                                                <option value="2">Business vertical</option>
                                                <option value="3">Status</option>
                                                <option value="4">Managed By</option>
                                                <option value="5">Spoc Name</option>
                                                <option value="6">Spoc Email</option>
                                                <option value="7">Spoc Phone</option>
                                                <option value="8">State</option>
                                                <option value="9">Source</option>
                                            </select>
                                            <input type="text" class="form-control hidden" id="searchbox" name="searchbox" value="" placeholder="Search here" style="width: 380px; margin-top: -33px; margin-left: 270px;">
                                            <select class="form-control hidden" name="business_vertical_id" id="business_vertical_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select Business Vertical</option>
                                                <?php foreach($business_vertical_options as $option): ?>
                                                  <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            <select class="form-control hidden" name="status_id" id="status_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select Status</option>
                                                <?php foreach($lead_status_options as $option): ?>
                                                  <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            <select class="form-control hidden" name="source_id" id="source_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select Source</option>
                                                <?php foreach($lead_source_options as $option): ?>
                                                  <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            <div class="hidden" id="lead_managed_by_id" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" name="lead_managed_by_id"  style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Lead Managed By</option>
                                                <?php foreach($lead_managed_by_options as $option): ?>
                                                  <option value="<?php echo $option->lead_managed_by; ?>"><?php echo $option->lead_managed_by; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>
                                            <select class="form-control hidden" name="state_id" id="state_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select State</option>
                                                <?php foreach($state_options as $option): ?>
                                                  <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                             <div class="hidden" id="customer_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="customer_list" name="customer_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Customer Name</option>
                                                <?php foreach($customer_name_options as $option): ?>
                                                  <option value="<?php echo $option->customer_name; ?>"><?php echo $option->customer_name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                             <div class="hidden" id="spoc_name_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="spoc_name_list" name="spoc_name_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Spoc Name</option>
                                                <?php foreach($spoc_name_list_options as $option): ?>
                                                  <option value="<?php echo $option->spoc_name; ?>"><?php echo $option->spoc_name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <div class="hidden" id="spoc_email_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="spoc_email_list" name="spoc_email_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Spoc Email</option>
                                                <?php foreach($spoc_email_list_options as $option): ?>
                                                  <option value="<?php echo $option->spoc_email; ?>"><?php echo $option->spoc_email; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <div class="hidden" id="spoc_phone_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="spoc_phone_list" name="spoc_phone_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Spoc Phone</option>
                                                <?php foreach($spoc_phone_list_options as $option): ?>
                                                  <option value="<?php echo $option->spoc_phone; ?>"><?php echo $option->spoc_phone; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <label id="lblsearchbox" style="color:red; display: none;margin-left: 12%;">* Please Enter Search Value</label>

                                           </div>
                                      </div>
                                    <label id="lblSearchError" style="color:red;display:block ;margin-left: 120px;  float: left; margin-top: 63px;"></label>
                                </div>
                                <div class="text-center hidden" style="margin-bottom: 18px;  margin-left: 670px;  margin-top: -55px;" name="search_btn" id="search_btn">
                                        <a class="btn btn-primary btn-md" id="btn_search" onclick="btnSearch_OnClick()" style="color: white; cursor: pointer;"><i class="fa fa-search "></i> Search</a>
                                        <Button type="button" onclick="window.location.reload();" class="btn btn-secondary btn-md "> Clear Search</Button>
                                    </div>
                            </form>
                        </div>
                    </div>
                    <div class="card-body collapse in" style="font-size:0.90rem;">
                        <div class="card-block card-dashboard">
                            <table id="tblList" class="table table-striped table-bordered display responsive nowrap" style="margin-left: 0px!important; ">
                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Actions</th>
                                    <th>Customer Name</th>
                                    <th>Business Vertical</th>
                                    <th>Status</th>
                                    <th>Managed By</th>
				    <th>SPOC Name</th>
                                    <th>SPOC Email</th>
                                    <th>SPOC Phone</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>Business Probability (%)</th>
                                    <th>Source</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody id="tblBody">
                                </tbody>
                            </table>
                           <?php /*echo $this->pagination->create_links();*/?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->
</div>

<div class="modal fade" id="leadModel" role="dialog">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title">Lead Status</h4>
       </div>
       <div class="modal-body">
         <br>
         <label for="lead_status_id">Choose Status</label>
         <select class="form-control" id="lead_status_selector" placeholder="Enter Lead Status" name="lead_status_id">
         <?php foreach($lead_status_options as $lead_status_option): ?>
           <option value="<?php echo $lead_status_option->id; ?>" data-svalue="<?php echo $lead_status_option->value; ?>" data-notification="<?php echo $lead_status_option->notification_status; ?>" ><?php echo $lead_status_option->name; ?></option>
         <?php endforeach; ?>
         </select>
         <br>

         <div class="hidden" id="customer_commercial_input_container">
           <label for="customer_commercial_input">Choose Customer Commercial Info</label>
           <select class="form-control " id="customer_commercial_input">
             <option value="-1">Select Commercial Type</option>
             <option value="0">Free</option>
             <option value="1">Commercial</option>
           </select>
           <br>
         </div>

         <input type="hidden" id="employer_id" name="" value="">
         <div class="col-md-12">
           <div class="alert alert-danger hidden" id="alert-box">
             <h4>All the fields are required</h4>
           </div>
         </div>
         <div class="hidden form-group row" id="proposal_shared_input_container">
           <form class="form-group" id="proposal_form" enctype="multipart/form-data">
             <br>
             <div class="col-md-6">
               <label for="" class="label">Proposal Date</label>
               <input type="text" id="proposal_date_input" name="schedule_date" class="form-control feedback-date"  value="">
             </div>
             <div class="col-md-6">
               <label for="" class="label">Proposal Shared to</label>
               <input type="text" class="form-control" name="remarks" id="proposal_shared_to_input" value="" >
             </div>
             <div class="col-md-6">
               <label for="" class="label">Potential Numbers</label>
               <input type="text" pattern="^[1-9]*" maxlength="8" id="potential_number" name="potential_number" class="form-control"  value="">
             </div>
             <div class="col-md-6">
               <label for="" class="label">Potential Order Value Per Month</label>
               <input type="text" pattern="^[1-9]*" maxlength="8" class="form-control" name="potential_order_value_per_month" id="potential_order_value_per_month" value="" >
             </div>
             <input type="hidden" id="proposal_customer_id" name="customer_id" value="">
             <input type="hidden" name="lead_status_id" id="proposal_lead_status_id" value="">
             <div class="col-md-12">
               <br>
               <label for="" class="label">Choose a file <span class="danger">(max size 3 MB & doc, docx, jpg, png, pdf files only)</span> </label>
               <input type="file" name="file_name" id="proposal_shared_file_input" value="" onchange="return fileValidation()">
             </div>
           </form>

         </div>
         <div class="hidden lead_schedule_input_container form-group row">

           <div class="col-md-6">
             <label for="" class="label">Select Schedule Date</label>
             <input type="text" id="lead_schedule_input" class="form-control feedback-date"  value="">
           </div>
           <div class="col-md-6">
             <label for="" class="label">Name</label>
             <input type="text" class="form-control" name="Name" id="name_input" value="" >
           </div>
           <div class="col-md-6">
             <label for="" class="label">Phone</label>
             <input type="text" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "10" class="form-control numbers" name="phone" id="phone_input" maxlength = "10" value="" required>
           </div>
           <div class="col-md-6">
             <label for="" class="label">City</label>
             <input type="text" class="form-control" name="city" id="city_input" value="" >
           </div>
           <div class="col-md-12">
             <label for="" class="label">Address</label>
             <input type="text" class="form-control" name="address" id="address_input" value="" >
           </div>
         </div>
         <div class="lead_feedback_input_container hidden row">
           <div class="col-md-12">
             <label for="" class="label">Enter Remarks</label>
             <input type="text" id="lead_feedback_input" class="form-control hidden" placeholder="Enter Remarks here" value="">
           </div>
         </div>
          <br>
         <button type="button" id="update-status" class="btn btn-primary" disabled name="button" onclick="changeLeadStatus();">Update Status</button>
         <a href="" class="btn btn-warning hidden" id="commerical-button">Add Documents Details</a>
          <br><br>
       </div>

       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
       </div>
     </div>

   </div>
 </div>
<div class="wrapper">

	<?php

			$this->load->view('common/footer');
	?>
	</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/datetimepicker/css/bootstrap-datetimepicker.css')?>">
<script src="<?php echo base_url().'adm-assets/datetimepicker/js/bootstrap-datetimepicker.js'?>" type="text/javascript"></script>
<script>
    $(document).ready(function(){
        $('#search_by').on('change', function(){
            $('#lblSearchError').html('');
        });
    });
    var varTable;
    function searchby_onchange(varSearchByValue)
    {
        if (varSearchByValue=='0')
        {
            LoadTableData();
            return;
        }
        //lert(varSearchByValue);
        $("#business_vertical_id").val('0');
        $("#customer_list").selectedIndex = "0";
        $("#lead_managed_by_id").selectedIndex = "0";
        $("#spoc_name_list").selectedIndex = "0";
        $("#spoc_email_list").selectedIndex = "0";
        $("#spoc_phone_list").selectedIndex = "0";
        $("#status_id").val('0');
        $("#state_id").val('0');
        $("#source_id").val('0');
        $("#searchbox").val('');
    }

    function LoadTableData()
    {
        var varSearchTypeId = $("#search_by").val(),
            varSearchValue = $("#searchbox").val();

        switch(varSearchTypeId)
        {
            case "1":
                varSearchValue = $("#customer_list option:selected").text();
                break;
            case "2":
                varSearchValue = $("#business_vertical_id").val();
                break;

            case "3":
                varSearchValue = $("#status_id").val();
                break;

            case "4":
                varSearchValue = $("#lead_managed_by_id option:selected").text();
                break;

            case "5":
            varSearchValue = $("#spoc_name_list option:selected").text();
            break;

             case "6":
            varSearchValue = $("#spoc_email_list option:selected").text();
            break;

             case "7":
            varSearchValue = $("#spoc_phone_list option:selected").text();
            break;

            case "8":
            varSearchValue = $("#state_id").val();
            break;

            case "9":
                varSearchValue = $("#source_id").val();
                break;
        }

        if (varTable != undefined && varTable != null)
        {
            varTable.clear().destroy();
        }

        varTable = $("#tblList").DataTable({
            "serverSide": true,
            "paging": true,
            "scrollX": true,
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "pageLength": 10,
            "searching": false,
            "language": { "loadingRecords": "Loading..." },
            "ajax": {
                "url": base_url+"SalesController/get_lead_data/",
                "type": "POST",
                "data": function (d) {
                    d.search_type_id = varSearchTypeId;
                    d.search_value = varSearchValue;
                },
                "error": function() {
                    $("#tblList tbody").empty().append('<tr><td style="text-align: center;" colspan="14">No data found</td></tr>');
                }
            },
            "columnDefs":
                [
                    {
                        "targets": [0, 1 ],
                        "orderable": false
                    }
                ],
            "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
            buttons: []
        });
    }

    function btnSearch_OnClick()
    {
        $("#lblSearchError").hide();
        var varSearchTypeId = $("#search_by").val(),
            varSearchValue = $("#searchbox").val();

        switch(varSearchTypeId)
        {
            case "1":
                if ($("#customer_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select customer!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            case "2":
                if ($("#business_vertical_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select business vertical!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            case "3":
                if ($("#status_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select lead status!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                case "4":
                if ($("#lead_managed_by_id option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select lead Managed by!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "5":
                if ($("#spoc_name_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select spoc name!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "6":
                if ($("#spoc_email_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select spoc email!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "7":
                if ($("#spoc_phone_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select spoc phone!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

               case "8":
                if ($("#state_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select state!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            case "9":
                if ($("#source_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select lead source!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            default:
                if (varSearchValue.trim() == '')
                {
                    switch(varSearchTypeId)
                    {
//                        case "1":
//                            $("#lblSearchError").text('* Please input customer name!');
//                            break;
//                        case "4":
//                        $("#lblSearchError").text('* Please input Managed By!');
//                        break;
//                         case "5":
//                        $("#lblSearchError").text('* Please input Spoc Name!');
//                        break;
//                         case "6":
//                        $("#lblSearchError").text('* Please input Spoc Email');
//                        break;
//                         case "7":
//                        $("#lblSearchError").text('* Please input Spoc Phone!');
//                        break;
//                         case "8":
//                        $("#lblSearchError").text('* Please input Location!');
//                        break;
                    }

                    $("#lblSearchError").show();
                    return;
                }
                break;
        }

        LoadTableData();
    }

    $(document).ready(function() {
        $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="icon-refresh"></i> Reload</button>').appendTo('div#table_length');
        LoadTableData();
    });
     $('.feedback-date').datetimepicker({
        //language:  'fr',
        startDate: "+0d",
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1
      });

  $('.numbers, #potential_order_value_per_month, #potential_number').keyup(function () {
    this.value = this.value.replace(/[^0-9\.]/g,'');
  });


function name_input(event) {
   var value = String.fromCharCode(event.which);
   var pattern = new RegExp(/[a-zåäö ]/i);
   return pattern.test(value);
}

$('#name_input').bind('keypress', name_input);

</script>

<script type="text/javascript">

  $('#lead_status_selector').change(function() {

    let data_value = $('#lead_status_selector').find(':selected').attr('data-svalue');
    let data_notification = $('#lead_status_selector').find(':selected').attr('data-notification');
    let value = $('#lead_status_selector').find(':selected').val();
    $('#proposal_lead_status_id').val(value);
    $('#update-status').prop("disabled", false);
    $('#alert-box').addClass('hidden');
    if(data_value<0) {
      $('#lead_feedback_input').removeClass('hidden');
      $('.lead_feedback_input_container').removeClass('hidden');
    } else {
      $('#lead_feedback_input').addClass('hidden');
      $('.lead_feedback_input_container').addClass('hidden');
    }
    if(value==8) {
      $('#proposal_shared_input_container').removeClass('hidden');
    } else {
      $('#proposal_shared_input_container').addClass('hidden');
    }
    if(value==16) {
      $('#customer_commercial_input_container').removeClass('hidden');
    } else {
      $('#customer_commercial_input_container').addClass('hidden');
      $('#update-status').removeClass('hidden');
      $('#commerical-button').addClass('hidden');
    }
    if(data_notification==1) {
      $('.lead_schedule_input_container').removeClass('hidden');
      $('#lead_feedback_input').removeClass('hidden');
      $('.lead_feedback_input_container').removeClass('hidden');
    } else {
      $('.lead_schedule_input_container').addClass('hidden');
    }
    if(value==0){
      $('#update-status').prop('disabled', true);
    }
  });

  function open_lead_popup(lead_id,lead_status_id) {
    $('#customer_commercial_input').val('-1');
    $('#customer_commercial_input_container').addClass('hidden');
    $('#proposal_shared_input_container').addClass('hidden');
    if(lead_status_id == 16) {
      $('#update-status').addClass('hidden');
      $('#commerical-button').removeClass('hidden');
    } else {
      $('#update-status').removeClass('hidden');
      $('#commerical-button').addClass('hidden');
    }
    $('#update-status').prop("disabled", true);
    $('#employer_id').val(lead_id);
    $('#proposal_customer_id').val(lead_id);
    $('#proposal_lead_status_id').val(lead_status_id);
    $('#commerical-button').attr('href', '<?= base_url('leads/commercials_documents/')?>'+lead_id);
    //$('.modal-title').text('Lead Status');
    $('#leadModel').modal('show');
    $('#lead_feedback_input, .lead_schedule_input_container, .lead_feedback_input_container, #alert-box').addClass('hidden');
    $('#lead_feedback_input, #name_input, #phone_input, #city_input, #address_input, #lead_schedule_input').val('');
    $('#lead_status_selector').html('');

    $.each(statusOptions, function(index, op){
        let options ='';

        if(lead_status_id==19 && op.id!=1) {
          options = $('<option>').attr('value', op.id).attr('data-svalue', op.value).attr('data-notification', op.notification_status).text(op.name);
        } else if(lead_status_id==21 && op.id==18) {
          options = $('<option>').attr('value', 0).text('Select an Option');
          $('#lead_status_selector').append(options);
          options = $('<option>').attr('value', op.id).attr('data-svalue', op.value).attr('data-notification', op.notification_status).text(op.name);
        } else {
          if(!(lead_status_id==18 && op.id ==1) && !(lead_status_id!=18 && op.id ==19)) {
            if(lead_status_id <= op.id) {
                options = $('<option>').attr('value', op.id).attr('data-svalue', op.value).attr('data-notification', op.notification_status).text(op.name);
            }
          }
        }

        $('#lead_status_selector').append(options);
    });

    //$('#lead_status_selector').append(options);
    if(lead_status_id!=21) {
        $('#lead_status_selector').val(lead_status_id).change();
    } else {
      $('#lead_status_selector').val(0).change();
    }

    //$('#lead_status_selector').val($('#lead_status_cell_'+lead_id).attr('data-value')).change();

  }

  window.setTimeout(function() {
      $("#server-alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove();
      });
  }, 4000);

  function edit_lead(lead_id)
  {
      window.location.href='<?= base_url('leads/edit/') ?>' + lead_id;
  }

  function changeLeadStatus() {
    var employer_id = $('#employer_id').val();
    var remark = $('#lead_feedback_input').val();
    var name = $('#name_input').val();
    var phone = $('#phone_input').val();
    var city = $('#city_input').val();
    var address = $('#address_input').val();
    var schedule_date = $('#lead_schedule_input').val();
    var lead_status_id = $('#lead_status_selector').find(':selected').val();
    var customer_commercial_type = $('#customer_commercial_input').find(':selected').val();
    var proposal_date_input = $('#proposal_date_input').val();
    var proposal_shared_to_input = $('#proposal_shared_to_input').val();
    var potential_order_value_per_month = $('#potential_order_value_per_month').val();
    var potential_number = $('#potential_number').val();
    var form_data;
    if(lead_status_id==8){
      form_data = new FormData(document.getElementById('proposal_form'));
    } else {
      form_data = {"lead_status_id" : lead_status_id, "customer_id" : employer_id, "remark" : remark, "schedule_date": schedule_date,
            "name":name, "phone" : phone, "city": city, "address" : address, "is_paid" : customer_commercial_type};
    }
    console.log(form_data);
    if(lead_status_id == 16 && customer_commercial_type==-1) {
      $('#alert-box').removeClass('hidden');
      return;
    }
    if(lead_status_id == 8 ) {
      if(proposal_shared_to_input.trim()=='' || proposal_date_input=='' || proposal_shared_file_input.files.length==0 || potential_order_value_per_month=='' || potential_number=='') {
        $('#alert-box').removeClass('hidden');
        console.log('inside2');
        return;
      } else {
        updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id);
      }
    }
    if($('#lead_status_selector').find(':selected').attr('data-notification')==1) {
      if(name=='' || schedule_date=='' || phone=='' || remark.trim()=="") {
        $('#alert-box').removeClass('hidden');
        console.log('inside2');
        return;
      } else {
        updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id);
      }
    }
    if($('#lead_status_selector').find(':selected').attr('data-svalue')<0) {
      if(remark.trim()=="") {
        $('#alert-box').removeClass('hidden');
        return;
      } else{
        updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id);
      }
    }
    if(lead_status_id==11 || lead_status_id==12 || lead_status_id==19) {
        updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id);
    }
    if(lead_status_id==16){
      lead_commercial_confirm(lead_status_id, form_data, customer_commercial_type, employer_id);
    }
    if(lead_status_id==0){
      alert('Select a Valid Value');
    }

  }

  function lead_commercial_confirm(lead_status_id, form_data, customer_commercial_type, employer_id) {
      let commercial_text = customer_commercial_type==0 ? 'Free' : 'Commercial';
      swal(
          {
              title: "",
              text: "Sure about commercial of this item? You Selected "+commercial_text ,
              showCancelButton: true,
              confirmButtonText: "Yes",
              cancelButtonText: "No, Cancel!",
              closeOnConfirm: false,
              closeOnCancel: true
          },
          function(isConfirm) {
              if (isConfirm) {
                updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id);
              }
          }
      );
  }

  function updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id) {
    var request = $.ajax({
      url: "<?php echo base_url(); ?>salesController/leadStatusUpdate",
      type: "POST",
       processData: (lead_status_id==8) ? false : true,
      contentType: (lead_status_id==8) ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
      data: form_data,
    });

    request.done(function(msg) {
      var response = JSON.parse(msg);
      if(response.status) {
        if(lead_status_id == 16 && customer_commercial_type==1) {
          //$('#update-status').addClass('hidden');
          //$('#customer_commercial_input_container').addClass('hidden');
          //$('#commerical-button').removeClass('hidden');
          window.location = '<?= base_url("salescontroller/commericals_store/"); ?>'+employer_id;
        } else {
          $('#leadModel').modal('hide');
        }
      }
    });

    request.fail(function(jqXHR, textStatus) {
      $('#leadModel').modal('hide');
    });

    request.always(function() {
      if(lead_status_id != 16 || (lead_status_id == 16 &&customer_commercial_type==0)) {
        location.reload();
      }
      //location.reload();
    });
  }

function secondary_spoc_count(lead_id)
{
  var track_url=base_url+'partner/additional_spoc_details/'+lead_id;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var additional_spoc_detail_html='';
            if(data.status)
            {
                var employer=data.employer_detail;
                var slno=1;
                additional_spoc_detail_html += "<div  style='margin-bottom: 10px'>Customer Name: <span style='font-weight: bold;'>"+employer.customer_name+"</span></div>";

                additional_spoc_detail_html += '<div class="row">';
                additional_spoc_detail_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; height: 400px;">';
                additional_spoc_detail_html += '<table id="tblAdditionalSpocDetails" class="table table-striped table-bordered display responsive nowrap">';
                additional_spoc_detail_html += '<tr><th>SNo</th><th>Spoc Name</th><th>Spoc Email</th><th>Spoc Phone</th></tr>';

                $.each(data.additional_spoc_detail,function(a,b)
                {
                  additional_spoc_detail_html += '<tr><td>'+slno+'</td><td>'+b.spoc_name+'</td><td>'+b.spoc_email+'</td><td>'+b.spoc_phone+'</td></tr>';
                  slno++;
                });

                additional_spoc_detail_html += '</table>';
                additional_spoc_detail_html += '</div></div>';
            }
            $('.additional_spoc_detail').html(additional_spoc_detail_html);

            $("#tblAdditionalSpocDetails").DataTable();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_tracker').modal('show'); // show bootstrap modal when complete loaded
}

</script>

<br><br><br>
<div id="modal_form_tracker" class="modal fade bs-example-modal-xl" role="dialog">
    <div class="modal-dialog modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:hidden;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Additional Spocs</h3>
            </div>
            <div class="modal-body additional_spoc_detail">
                -No records found-
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<?php $this->load->view('sales/lead_history_modal'); ?>
<?php $this->load->view('sales/spoc_list_modal'); ?>
<?php $this->load->view('sales/placement_officer_select_modal', $data); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/js/extensions/moment.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script> $(document).ready(function () { $('.dropdown-toggle').dropdown(); }); </script>
<script>
$(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8' || $('#search_by').val() == '9') {
                $('#searchbox').addClass('hidden');
            }
            else {
                $('#searchbox').removeClass('hidden');
                $('#searchbox').focus();
            }
        });
    });
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8' || $('#search_by').val() == '9') {
                $('#status_id').addClass('hidden');
            }
            else {
                $('#status_id').removeClass('hidden');
                $('#status_id').focus();
            }
        });
    });
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#source_id').addClass('hidden');
            }
            else {
                $('#source_id').removeClass('hidden');
                $('#source_id').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8'|| $('#search_by').val() == '9') {
                $('#lead_managed_by_id').addClass('hidden');
            }
            else {
                $('#lead_managed_by_id').removeClass('hidden');
                $('#lead_managed_by_id').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0') {
                $('#search_btn').addClass('hidden');
            }
            else {
                $('#search_btn').removeClass('hidden');
                $('#search_btn').focus();
            }
        });
    });
</script>
<script>
$(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8' || $('#search_by').val() == '9') {
                $('#business_vertical_id').addClass('hidden');
            }
            else {
                $('#business_vertical_id').removeClass('hidden');
                $('#business_vertical_id').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '9') {
                $('#state_id').addClass('hidden');
            }
            else {
                $('#state_id').removeClass('hidden');
                $('#state_id').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8' || $('#search_by').val() == '9') {
                $('#customer_list_container').addClass('hidden');
            }
            else {
                $('#customer_list_container').removeClass('hidden');
                $('#customer_list_container').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' ||  $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8' || $('#search_by').val() == '9') {
                $('#spoc_name_list_container').addClass('hidden');
            }
            else {
                $('#spoc_name_list_container').removeClass('hidden');
                $('#spoc_name_list_container').focus();
            }
        });
    });


     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' ||  $('#search_by').val() == '5' || $('#search_by').val() == '7' || $('#search_by').val() == '8' || $('#search_by').val() == '9') {
                $('#spoc_email_list_container').addClass('hidden');
            }
            else {
                $('#spoc_email_list_container').removeClass('hidden');
                $('#spoc_email_list_container').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' ||  $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '8' || $('#search_by').val() == '9') {
                $('#spoc_phone_list_container').addClass('hidden');
            }
            else {
                $('#spoc_phone_list_container').removeClass('hidden');
                $('#spoc_phone_list_container').focus();
            }
        });
    });


    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0') {
                $('#lblSearchError').addClass('hidden');
            }
            else {
                $('#lblSearchError').removeClass('hidden');
                $('#lblSearchError').focus();
            }
        });
    });

     $(document).ready(function() {
        $('.select2-neo').select2();
      });

// $(document).keypress(function(e){
//    if (e.which == 13){
//        $("#btn_search").click();
//    }
//});
$('#leadModel').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
})
function fileValidation(){
    var fileInput = document.getElementById('proposal_shared_file_input');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.doc|\.docx|\.jpeg|\.jpg|\.png|\.pdf)$/i;
    if(!allowedExtensions.exec(filePath)){
      swal(
            {
                title: "Invalid File Type!",
                text: "Please upload file having extensions .doc, .docx, .jpeg, .jpg, .png, .pdf only.",
                showCancelButton: false,
                confirmButtonText: "OK",
                closeOnConfirm: true
            })
        fileInput.value = '';
        return false;
    }
}

$("#potential_number").on("keypress keyup",function(){
    if($(this).val() == '0'){
      $(this).val('');
    }
});
$("#potential_order_value_per_month").on("keypress keyup",function(){
    if($(this).val() == '0'){
      $(this).val('');
    }
});
</script>

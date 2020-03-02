
<div class="modal fade" id="leadModel" role="dialog">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal">&times;</button>
         <h4 class="modal-title">Opportunity Status</h4>
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
           <label for="customer_commercial_input">Customer Commercial Info</label>
           <select class="form-control " id="customer_commercial_input" disabled>
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
             <div class="col-md-12">
               <h5 id="current-product-label"></h5>
               <label for="product_selector">Change Product</label>
               <select class="form-control" id="product_selector" placeholder="Change Product" name="product_selector">
               <?php foreach($business_vertical_options as $option): ?>
                 <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
               <?php endforeach; ?>
               </select>
             </div>
             <br>
             <div class="col-md-6">
               <label for="" class="label">Proposal Date</label>
               <input type="text" id="proposal_date_input" name="schedule_date" class="form-control feedback-date"  value="">
             </div>
             <div class="col-md-6">
               <label for="" class="label">Proposal Shared to</label>
               <input type="text" class="form-control" name="proposal_shared_to" id="proposal_shared_to_input" value="" >
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
         <div class="hidden form-group row" id="lead_schedule_input_container">

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
<script type="text/javascript">
var main_lead_status_id = -1;
function open_lead_popup(lead_id,lead_status_id) {
  main_lead_status_id=lead_status_id;
  $('#customer_commercial_input').val('-1');
  $('#customer_commercial_input_container').addClass('hidden');
  $('#proposal_shared_input_container').addClass('hidden');
  $('#lead_schedule_input_container').addClass('hidden');
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
  $('#commerical-button').attr('href', '<?= base_url('CommercialVerificationController/commericalsStore/')?>'+lead_id);
  //$('.modal-title').text('Lead Status');
  $('#leadModel').modal('show');
  $('#lead_feedback_input, #lead_schedule_input_container, .lead_feedback_input_container, #alert-box').addClass('hidden');
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
      $('#lead_status_selector').val(lead_status_id);
  } else {
    $('#lead_status_selector').val(0).change();
  }

  //$('#lead_status_selector').val($('#lead_status_cell_'+lead_id).attr('data-value')).change();

}

$(document).ready(function() {

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
        get_product_opportunity();
        $('#proposal_shared_input_container').removeClass('hidden');
      } else {
        $('#proposal_shared_input_container').addClass('hidden');
      }
      if(value==16) {
        get_product_commercial_type();
        $('#customer_commercial_input_container').removeClass('hidden');
      } else {
        $('#customer_commercial_input_container').addClass('hidden');
        $('#update-status').removeClass('hidden');
        $('#commerical-button').addClass('hidden');
      }
      if(data_notification==1) {
        $('#lead_schedule_input_container').removeClass('hidden');
        $('#lead_feedback_input').removeClass('hidden');
        $('.lead_feedback_input_container').removeClass('hidden');
      } else {
        $('#lead_schedule_input_container').addClass('hidden');
      }
      if(value==0){
        $('#update-status').prop('disabled', true);
      }
    });

    $('#leadModel').on('hidden.bs.modal', function () {
        $(this).find('form').trigger('reset');
    });

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

    $('#lead_status_selector').on('change', function()
      {
          var employer_id = $('#employer_id').val();
          if ( $('#lead_status_selector').val() == '11' )
          proposaldocumentchecked(employer_id);
      });
      $('#lead_status_selector').on('change', function()
      {
          var employer_id = $('#employer_id').val();
          if ( $('#lead_status_selector').val() == '12' )
          proposaldocumentchecked(employer_id);
      });
      $('#customer_commercial_input').on('change', function()
      {
          var employer_id = $('#employer_id').val();
          if ( $('#customer_commercial_input').val() == '1' )
          proposaldocumentchecked(employer_id);
      });

    $('#customer_commercial_input').change(function () {
      if($(this).val()==0){
        $('#update-status').prop('disabled', false);
      }
    });



     //$('body').on('click', '.feedback-date', function() {
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
     //});

    $('.numbers, #potential_order_value_per_month, #potential_number').keyup(function () {
     this.value = this.value.replace(/[^0-9\.]/g,'');
    });

    $('#name_input').bind('keypress', name_input);

});

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
      lead_commercial_confirm(lead_status_id, form_data, customer_commercial_type, employer_id);
      //updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id);
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
    //lead_commercial_confirm(lead_status_id, form_data, customer_commercial_type, employer_id);
    updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id);
  }
  if(lead_status_id==0){
    alert('Select a Valid Value');
  }

}


  function updateLeadStatus(lead_status_id, form_data, customer_commercial_type, employer_id) {
    var request = $.ajax({
      url: "<?php echo base_url(); ?>opportunitiesController/leadStatusUpdate",
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
          window.location = '<?= base_url("CommercialVerificationController/commericalsStore/"); ?>'+employer_id;
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

  function lead_commercial_confirm(lead_status_id, form_data, customer_commercial_type, employer_id) {
      let commercial_text = customer_commercial_type==0 ? 'Free' : 'Commercial';
      swal(
          {
              title: "Are you sure about the product for this opportunity?",
              text: ' <span style="color:#c0392b">Changing Product Name would impact<br>Commercial Type of the Product!<span>',
              html: true,
              //text: 'Changing Product Name would impact Commercial Type of the Product!' ,
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

  function proposaldocumentchecked(employer_id)
      {
          var proposal_shared=base_url+'opportunitiesController/check_commercial_document/'+employer_id;
          $.ajax({
                url : proposal_shared,
                type: "POST",
                dataType: "JSON",
                success: function(data)
                {
                    if (data.file_name.trim() == "")
                    {
                       $('#update-status').attr("disabled", true);
                        $('#customer_commercial_input').val('-1');

                        swal(
                            {
                                title: "Proposal Document Missing!",
                                text: "Please upload proposal Document.",
                                showCancelButton: false,
                                confirmButtonText: "OK",
                                closeOnConfirm: true
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                  //$('#customer_commercial_input').val('-1');
                                }
                            }
                        );
                    }
                },
                error: function (jqXHR, textStatus, errorThrown)
                {
                    alert('Error get data from ajax');
                }
            });
      }


  function name_input(event) {
    var value = String.fromCharCode(event.which);
    var pattern = new RegExp(/[a-zåäö ]/i);
    return pattern.test(value);
  }

  function get_product_opportunity() {
    let opp_id=$('#proposal_customer_id').val();
    $.ajax({
      url:'<?= base_url('opportunitiesController/getCurrentProduct/'); ?>'+opp_id,
      async: false,
    }).done(function(data, textStatus, jqXHR ) {
      let reponse = JSON.parse(data);
      $('#product_selector').val(reponse.data.business_vertical_id);
      let status_text = $('#product_selector').find(':selected').text();
        $('#current-product-label').html('Current Product - <span class="text-success">'+status_text + '</span>');
    }).fail(function(jqXHR, textStatus, errorThrown) {
      $('#product_selector').val(0);
    });
  }

  function get_product_commercial_type() {
    let opp_id=$('#proposal_customer_id').val();
    $.ajax({
      url:'<?= base_url('opportunitiesController/getCurrentProduct/'); ?>'+opp_id,
      async: false,
    }).done(function(data, textStatus, jqXHR ) {
      let reponse = JSON.parse(data);
      $('#product_selector').val();
      if(parseInt(reponse.data.business_vertical_id)==3) {
        $('#customer_commercial_input').val('0').change();
      } else {
          $('#customer_commercial_input').val('1').change();
      }
    }).fail(function(jqXHR, textStatus, errorThrown) {

    });
  }

</script>

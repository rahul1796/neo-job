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
.phone-input{
	margin-bottom:8px;
}

</style>

<?php $this->load->view('layouts\soft_error'); ?>

<div class="form-group row">
  <div class="col-md-6">
   <label for="company_name" class="label">Company:</label>
   <input type="text" class="form-control" id="company_name" placeholder="" readonly value="<?= $company['company_name']; ?>">
 </div>
 <div class="col-md-6">
  <label for="managed_by" class="">Managed By:</label>
  <input type="text" class="form-control" id="managed_by" name="managed_by" placeholder="" value="<?= $fields['managed_by']; ?>">
</div>
</div>

<div class="form-group row">
  <div class="col-md-6">
    <label for="business_vertical_id" class="label">Product:</label>
    <select class="form-control" name="business_vertical_id" id="business_vertical_id">
      <option value="">Select Product</option>
      <?php foreach($business_vertical_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['business_vertical_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('business_vertical_id'); ?>
  </div>

  <div class="col-md-6">
    <label for="labournet_entity_id" class="label">Labournet Entity:</label>
    <select class="form-control" name="labournet_entity_id" id="labournet_entity_id">
      <option value="">Select Labournet Entity</option>
      <?php foreach($labournet_entity_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['labournet_entity_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('labournet_entity_id'); ?>
  </div>
</div>

<div class="form-group row">

  <div class="col-md-6">
    <label for="functional_area_id" class="label">Select Functional Area:</label>
    <select class="form-control" name="functional_area_id" id="functional_area_id">
      <option value="">Select Functional Area</option>
      <?php foreach($functional_area_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['functional_area_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('functional_area_id'); ?>
  </div>

  <div class="col-md-6">
    <label for="industry_id" class="label">Select Industry:</label>
    <select class="form-control" name="industry_id" id="industry_id">
      <option value="">Select Industry</option>
      <?php foreach($industry_options as $option): ?>
        <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['industry_id'] ?? '0') ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
      <?php endforeach; ?>
    </select>
    <?php echo form_error('industry_id'); ?>
  </div>
  <input type="hidden" name="company_id" value="<?= ($fields['company_id']=='')? $company['id'] : $fields['company_id'] ?>">
  <input type="hidden" name="lead_status_id" value="<?= ($fields['lead_status_id']=='')? '1': $fields['lead_status_id'] ?>">
</div>

<div class="form-group row">
  <div class="col-md-3">
    <label for="spoc_name" class="">Branch Spoc Name:</label>
    <input type="text" class="form-control" id="spoc_name" placeholder="Enter Spoc Name" name="spoc_detail[0][spoc_name]" value="<?php echo $location_fields['spoc_detail'][0]['spoc_name'] ?? $fields['spoc_name'] ?? ''; ?>" >
    <?php echo form_error("spoc_detail[0][spoc_name]"); ?>
  </div>

  <div class="col-md-3">
    <label for="spoc_email" class="">Branch Spoc Email:</label>
    <input type="email" class="form-control" id="spoc_email" placeholder="Enter Spoc Email" name="spoc_detail[0][spoc_email]" value="<?php echo $location_fields['spoc_detail'][0]['spoc_email'] ?? $fields['spoc_email'] ?? ''; ?>" >
    <?php echo form_error("spoc_detail[0][spoc_email]"); ?>
  </div>

  <div class="col-md-2">
    <label for="spoc_phone" class="">Branch Spoc Phone:</label>
    <input type="text" class="form-control" id="spoc_phone" placeholder="Enter Spoc Phone" name="spoc_detail[0][spoc_phone]" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10" value="<?php echo $location_fields['spoc_detail'][0]['spoc_phone'] ?? $fields['spoc_phone'] ?? ''; ?>" >
    <?php echo form_error("spoc_detail[0][spoc_phone]"); ?>
  </div>

  <div class="col-md-3">
    <label for="spoc_designation" class="">Branch Spoc Designation:</label>
    <input type="text" class="form-control" id="spoc_designation" placeholder="Spoc Designation" name="spoc_detail[0][spoc_designation]" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "30" value="<?php echo $location_fields['spoc_detail'][0]['spoc_designation'] ?? $fields['spoc_designation'] ?? ''; ?>" >
    <?php echo form_error("spoc_detail[0][spoc_designation]"); ?>
  </div>
   </div>
<!--<h5>Add Additional Spoc Details</h5>
<hr>-->

<div class="form-group row" id="multispoc">
    <div class="col-md-12" id="spoc-field-container">
        <?php if(!empty($location_fields['spoc_detail'])): ?>
        <?php $x=0; ?>
        <?php foreach($location_fields['spoc_detail'] as $x=>$spoc): ?>
          <?php if($x==0):?>
            <?php //$x++; ?>
            <?php continue;?>
          <?php endif; ?>
        <div class="form-group row" id="spoc_<?= $x;?>">

                                <div class="col-xs-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="spoc_detail[<?=$x;?>][spoc_name]" value="<?= $spoc['spoc_name']?>" placeholder="Enter Spoc Name" />
                                            <?php echo form_error("spoc_detail[{$x}][spoc_name]"); ?>
                                        </div>
                                 </div>
                                 <div class="col-xs-3">
                                        <div class="input-group">
                                            <input type="email" class="form-control" name="spoc_detail[<?=$x;?>][spoc_email]" value="<?= $spoc['spoc_email']?>" placeholder="Enter Spoc Email" />
                                              <?php echo form_error("spoc_detail[{$x}][spoc_email]"); ?>
                                        </div>
                                 </div>
                                 <div class="col-xs-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "10" name="spoc_detail[<?=$x;?>][spoc_phone]" value="<?= $spoc['spoc_phone']?>" placeholder="Enter Spoc Phone"  />
                                            <?php echo form_error("spoc_detail[{$x}][spoc_phone]"); ?>
                                        </div>
                                 </div>
                                 <div class="col-xs-3">
                                        <div class="input-group">
                                            <input type="text" class="form-control" min="0" oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" maxlength = "30" name="spoc_detail[<?=$x;?>][spoc_designation]" value="<?= $spoc['spoc_designation']?>" placeholder="Spoc Designation"  />
                                            <?php echo form_error("spoc_detail[{$x}][spoc_designation]"); ?>
                                        </div>
                                 </div>
                 <div class="col-xs-1">
                                 <span class="input-group-btn"><button class="btn btn-danger remove_div" type="button" data-value=<?= $x ?>><i class="fa fa-trash"></i></button></span>
                </div>
        </div>
        <div style="clear:both;"></div>

              <?php $x++; ?>
            <?php endforeach; ?>
        <?php endif;?>
     </div>
    <div class="col-md-12">
        <button class="btn btn-primary add-div" type="button">Add Additional Spoc</button>
        <a class="btn btn-success" id="selectSpoc" style="margin-left: 25px;" onclick="selectspocmodal()">Select Spoc</a>
    </div>
</div>
<hr>


<div class="form-group row">

   <div class="col-md-12">
     <label for="address" class="label">Branch Address:</label>
     <input type="text" class="form-control" id="address" placeholder="Enter company address" name="address" value="<?php echo $location_fields['address'] ?? ''; ?>">
     <?php echo form_error('address'); ?>
   </div>


</div>


<div class="form-group row">
  <div class="col-md-4">
      <label for="country_id" class="label">Country:</label>
      <select class="form-control" name="country_id" id="country_id">
          <option value="0">Select Country</option>
          <?php foreach($countries_options as $country_option): ?>
              <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$location_fields['country_id']) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('country_id'); ?>
  </div>

  <div class="col-md-4">
      <label for="state_id" class="label">State:</label>
      <select class="form-control" name="state_id" id="state_id">
          <option value="0">Select State</option>
      </select>
      <?php echo form_error('state_id'); ?>
  </div>

  <div class="col-md-4">
      <label for="district_id" class="label">District/City:</label>
      <select class="form-control" name="district_id" id="district_id">
          <option value="0">Select District/City</option>
      </select>
      <?php echo form_error('district_id'); ?>
  </div>


</div>

<div class="form-group row">

    <div class="col-md-4">
   <label for="city" class="">Town / Village:</label>
   <input type="text" class="form-control" id="city" placeholder="Enter City" name="city" value="<?php echo $location_fields['city']; ?>">
   <?php echo form_error('city'); ?>
 </div>

   <div class="col-md-4">
     <label for="landline" class="label">Pincode:</label>
     <input type="text" class="form-control" id="pincode" placeholder="Enter Pincode" name="pincode" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "6"  value="<?php echo $location_fields['pincode']; ?>">
     <?php echo form_error('pincode'); ?>
   </div>

</div>


<button type="submit" class="btn btn-primary">Submit</button>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

  $('.select2-neo').select2();
});
</script> -->
 <script type="text/javascript">

  var country_id = <?= (!empty($location_fields['country_id'])) ? $location_fields['country_id'] : 0 ?>;
  var state_id = <?= (!empty($location_fields['state_id'])) ? $location_fields['state_id'] : 0 ?>;
  var district_id = <?= (!empty($location_fields['district_id'])) ? $location_fields['district_id'] : 0 ?>;
  var varSpocArray = [];

 $(document).ready(function() {

   if(country_id!=0){
     getStates(country_id);
     if(state_id!=0) {
        getDistricts(state_id);

     }
   }

   $('#country_id').on('change', function() {
     country_id = $(this).val();
     $('#state_id').html('');
     $('#state_id').append($('<option>').text('Select State').attr('value', 0));
     state_id=0;
     $('#state_id').val(state_id).change();
     district_id=0;
     $('#district_id').val(district_id).change();
     if(country_id!=0) {
        getStates(country_id);
     }
   });

   $('#state_id').on('change', function() {
     state_id = $(this).val();
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
     $('#district_id').val(district_id).change();
   });

   request.fail(function(jqXHR, textStatus) {
     alert( "Request failed: " + textStatus );
   });
 }
function company_name(event) {
   var value = String.fromCharCode(event.which);
   var pattern = new RegExp(/[a-zåäö ]/i);
   return true;
}

$('#company_name').bind('keypress', company_name);

 function getStates(id) {
   var request = $.ajax({
     url: "<?php echo base_url(); ?>master/getStates/"+id,
     type: "GET",
   });

   request.done(function(msg) {
     var response = JSON.parse(msg);
     // alert(response);
     $('#state_id').html('');
     $('#state_id').append($('<option>').text('Select State').attr('value', 0));
     response.forEach(function(state) {
        $('#state_id').append($('<option>').text(state.name).attr('value', state.id));
     });
     $('#state_id').val(state_id).change();
   });

   request.fail(function(jqXHR, textStatus) {
     alert( "Select Valid Value for the Country" );
   });
 }
</script>

<script>
var maxField = 100; //Input fields increment limitation
    var x = <?= (empty($location_fields['spoc_detail'])) ? 1 : count($location_fields['spoc_detail']); ?> || 1; //Initial field counter is 1
    var addButton = $('.add-button'); //Add button selector
    var addDiv = $('.add-div'); //Add button selector
    var wrapper1 = $('#spoc-field-container'); //Input field wrapper
    var placeholder='';
    var fieldHTML = '';
    var fieldSET = '';

    $(addDiv).click(function()
    { //Once add button is clicked
         if(x < maxField)
        { //Check maximum number of input fields
            //Increment field counter
        fieldSET = '<div class="form-group row" id="spoc_'+x+'">';
		fieldSET +=           '<div class="col-xs-3">\
					        <div class="input-group">\
					            <input type="text" class="form-control" name="spoc_detail['+x+'][spoc_name]" value="" placeholder="Enter Spoc Name" />\
					        </div>\
					 </div>\
                                         <div class="col-xs-3">\
					        <div class="input-group">\
					            <input type="email" class="form-control" name="spoc_detail['+x+'][spoc_email]" value="" placeholder="Enter Spoc Email" />\
					        </div>\
					 </div>\
                                         <div class="col-xs-2">\
					        <div class="input-group">\
					            <input type="text" maxlength="10" class="form-control" name="spoc_detail['+x+'][spoc_phone]" value="" placeholder="Enter Spoc Phone" />\
					        </div>\
					 </div>\
           <div class="col-xs-3">\
              <div class="input-group">\
                <input type="text" maxlength="30" class="form-control" name="spoc_detail['+x+'][spoc_designation]" value="" placeholder="Spoc Designation" />\
              </div>\
            </div>\
            <div class="col-xs-1">\
                    <span class="input-group-btn"><button class="btn btn-danger remove_div" data-value='+x+' type="button"><i class="fa fa-trash"></i></button></span>\
                    </div>\
            </div>';

        $(wrapper1).append(fieldSET); // Add field html
         x++;
    }
    });

    $(wrapper1).on('click', '.remove_div', function(e)
    { //Once remove button is clicked
        e.preventDefault();
       // alert ($(this).attr('data-value'));
      //  $(this).parents('div').remove(); //Remove field html
        let y = $(this).attr('data-value');
        $('#spoc_'+y).remove();
      //  x--; //Decrement field counter
    });

// $(document).ready(function(){
//     $('.spoc_detail').click(function(){
//       var rBtnVal = $(this).val();
//       if(rBtnVal == "yes"){  
//         $('#selectSpoc').show();     
//           $("#spoc_name,#spoc_email,#spoc_phone,#spoc_designation").attr("readonly", true); 
//       }
//       else{   
//         $('#selectSpoc').hide();     
//           $("#spoc_name,#spoc_email,#spoc_phone,#spoc_designation").attr("readonly", false); 
//       }
//     });
// });


function selectspocmodal()
{
  var track_url=base_url+'partner/companySpocDetails/'+<?= ($fields['company_id']=='')? $company['id'] : $fields['company_id'] ?>;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",        
        success: function(data)
        {
            var customer_detail_html='';
            if(data.status)
            {
                var employer=data.employer_detail;
                var slno=1;
                customer_detail_html += "<div  style='margin-bottom: 10px'>Company Name: <span style='font-weight: bold;'>"+employer.company_name+"</span></div>"; 
                
                customer_detail_html += '<div class="row">';
                customer_detail_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; ">';
                customer_detail_html += '<table id="tblApplicationTrackerDetails" class="table table-bordered display responsive nowrap">';
                customer_detail_html += '<thead>';
                customer_detail_html += '<tr><th></th><th>Spoc Name</th><th>Spoc Email</th><th>Spoc Phone</th><th>Spoc Designation</th></tr>';
                customer_detail_html += '<tbody id="tbodySpocs">';
                customer_detail_html += '</thead>';
                $.each(data.customer_detail,function(a,b)
                {
                  let id="checkbox_"+slno;
                  customer_detail_html += '<tr id="trSpocs"><td class="spocchecktd"><input id='+id+ ' class="checkevent" type="checkbox" name="spoc"></td><td class="spocnametd">'+b.spoc_name+'</td><td class="spocemailtd">'+b.spoc_email+'</td><td class="spocphonetd">'+b.spoc_phone+'</td><td class="spocdesignationtd">'+b.spoc_designation+'</td></tr>';
                  slno++;
                });
                //<td><a class="btn btn-success mr-1 mb-1" onclick="ShowOpportunityDetails('+b.id+')">'+b.opportunity_count+'</a></td>
                customer_detail_html += '</tbody>';
                customer_detail_html += '</table>';
                customer_detail_html += '</div></div>'; 
            }
            $('.candidate_job_status').html(customer_detail_html);

            $("#tblCustomerDetails").DataTable();
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#selectSpocModal').modal('show'); // show bootstrap modal when complete loaded
}


  

$(document).ready(function() { 
  $('body').on('change', '.checkevent', function() {
    let spocname = $(this).parent('td').siblings('.spocnametd').first().text();
    let spocemail = $(this).parent('td').siblings('.spocemailtd').first().text();
    let spocphone = $(this).parent('td').siblings('.spocphonetd').first().text();
    let spocdesignation = $(this).parent('td').siblings('.spocdesignationtd').first().text();
      
    let spocObject = {
                    spocName: spocname,
                    spocEmail: spocemail,
                    spocPhone: spocphone,
                    spocDsignation: spocdesignation
                  }    
 
    var JSONObject = JSON.stringify(spocObject);
    varSpocArray.push(JSONObject);
    console.log(varSpocArray);                                    
  }); 
  
});


// function GetSpocArray()
// {
//     var varSpocArray = [];
//     var varColumnS = ["Select", "SpocName", "SpocEmail", "SpocPhone", "SpocDesignation"]
//     var varTBody = document.getElementById('tbodySpocs');
//     var varTrList = varTBody.getElementsByTagName('tr');
//     for(var i=0; i < varTrList.length; i++)
//     {
//         var varTdList = varTrList[i].getElementsByTagName('td');
//         var varChkList = varTrList[i].getElementsByTagName('input');

//         //var chk = ()
//         alert(varChkList[i].checked);
//         var varSpoc = {};
//         for(var j=1; j < varTdList.length; j++)
//         {        
//             varSpoc[varColumnS[j]] = varTdList[j].innerText;
//         }
//         varSpocArray.push(varSpoc);
//     }

//     return varSpocArray;
// }

</script>




<div id="selectSpocModal" class="modal fade bs-example-modal-xl" role="dialog" style="color: black;">
    <div class="modal-dialog modal-xl" role="document" >
        <div class="modal-content">
            <div class="modal-header" style="border-bottom:hidden;">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Company Spoc List</h3>
            </div>
            <div class="modal-body candidate_job_status">
                -No records found-
            </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-primary" onclick="btnSelect_OnClick()">Select</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
            </div>
        </div>
    </div>
</div>
<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
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
<?php $this->load->view('layouts\soft_error'); ?>
<div class="form-group row">

  <div class="col-md-3">
    <input type="hidden" name="skilling_type_id" value="<?= ($fields['skilling_type_id']=='0') ? 0 : 1; ?>">
    <label for="employment_type" class="label">Employment Type:</label>
    <select class="form-control" name="employment_type" id="employment_type">
      <?php if(in_array($user['user_group_id'], candidate_employments_delete_edit_roles())):?>
        <?php foreach($employment_type_options as $option): ?>
            <option value="<?php echo $option->name; ?>" <?php echo ($option->name==$fields['employment_type']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
        <?php endforeach; ?>
      <?php else: ?>
        <option value="Self Employed">Self Employed</option>
      <?php endif; ?>
    </select>
      <?php echo form_error('employment_type'); ?>
  </div>

  <div class="col-md-6 ">
      <label for="company_name" class="" id="company_name_label">Company Name:</label>
      <input type="text" class="form-control" id="company_name" placeholder="Enter Company Name " name="company_name" value="<?= (trim($fields['company_name'])=='') ? '' : $fields['company_name']; ?>">
      <?php echo form_error('company_name'); ?>
  </div>

  <div class="col-md-3 ">
    <div class="<?= ($fields['employment_type'] == 'Self Employed' || $fields['employment_type'] =='') ? '': 'hidden' ?>" id="employment_start_date_container">
      <label for="employment_start_date" class="label">Self Employed Start Date</label>
      <input type="text" class="form-control" data-provide="datepicker" data-date-format="dd-M-yyyy" id="employment_start_date" placeholder="Enter Start date " name="employment_start_date" value="<?= ($fields['employment_start_date']!='') ? date_format(date_create($fields['employment_start_date']),'d-M-Y') : '' ;?>">
      <?php echo form_error('employment_start_date'); ?>
    </div>
  </div>
 </div>

<div class="form-group row">

  <div class="col-md-3">
       <label for="location" class="label">Location:</label>
       <input type="text" class="form-control" id="location" placeholder="Enter Location" name="location" value="<?php echo $fields['location']; ?>">
       <?php echo form_error('location'); ?>
  </div>

   <div class="col-md-4">
       <label for="ctc" class="label" id="ctc_label">Projected Earnings (INR Per Month):</label>
       <input type="text" class="form-control" id="ctc" placeholder="Enter Amount" name="ctc" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "7" value="<?php echo $fields['ctc']; ?>">
       <?php echo form_error('ctc'); ?>
   </div>

</div>

  <div id="emp-row-0" class="form-group row  <?= ($fields['employment_type'] == 'Self Employed' || $fields['employment_type'] =='') ? '': 'hidden' ?>">
    <div class="col-md-12">
      <label for="file_name" class="<?= ($fields['action']=='edit')? '' : 'label'; ?>">Upload Self Declaration Form:</label>
      <span class="text text-danger">( jpg, png, pdf, doc, docx ) files only</span>
      <input type="file" class="form-control" name="file_name" value="">
      <?php echo form_error('file_name'); ?>
    </div>
  </div>

 <div id="emp-row-1" class="form-group row <?= ( $fields['employment_type'] =='' || $fields['employment_type'] == 'Self Employed') ? 'hidden': '' ?>">

   <div class="col-md-3">
       <label for="designation" class="label">Job Role:</label>
       <input type="text" class="form-control" id="designation" placeholder="Enter Job Role " name="designation" value="<?php echo $fields['designation']; ?>">
       <?php echo form_error('designation'); ?>
   </div>

       <div class="col-md-3 ">
           <label for="country_id" class="label">Select Country:</label>
           <select class="form-control" name="country_id" id="country_id">
               <option value="" <?php echo ($fields['country_id']==0) ? 'selected' : '' ?>>Select Country</option>
               <?php foreach($countries_options as $country_option): ?>
                   <option value="<?php echo $country_option->id; ?>" <?php echo ($country_option->id==$fields['country_id']) ? 'selected' : '' ?> ><?php echo $country_option->name; ?></option>
               <?php endforeach; ?>
           </select>
           <?php echo form_error('country_id'); ?>
       </div>


       <div class="col-md-3">
           <label for="city" class="label">City:</label>
           <input type="text" class="form-control" id="city" placeholder="Enter city" name="city" value="<?php echo $fields['city']; ?>">
           <?php echo form_error('city'); ?>
       </div>

     <!--<span class="input-group-btn" style="float: right; margin-top: -32px;"><button class="btn btn-primary add-employer" type="button">+</button></span>-->
</div>
<div id="emp-row-2" class="form-group row <?= ($fields['employment_type'] == 'Self Employed' || $fields['employment_type'] =='') ? 'hidden': '' ?>">
    <div class="col-md-6 ">
        <label for="address" class="label">Address:</label>
        <input type="text" class="form-control" id="address" placeholder="Enter Address" name="address" value="<?php echo $fields['address']; ?>">
        <?php echo form_error('address'); ?>
    </div>

    <div class="col-md-3 ">
        <label for="job_profile" class="">Job Profile:</label>
        <input type="text" class="form-control" id="job_profile" placeholder="Enter Job Profile" name="job_profile" value="<?php echo $fields['job_profile']; ?>">
        <?php echo form_error('job_profile'); ?>
    </div>
    <div class="col-md-3 ">
        <label for="office_landline">Office Landline:</label>
        <input type="text" class="form-control" id="office_landline" placeholder="Enter Office Landline" name="office_landline" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "10" value="<?php echo $fields['office_landline']; ?>">
        <?php echo form_error('office_landline'); ?>
    </div>
</div>

<div id="emp-row-3" class="form-group row <?= ($fields['employment_type'] == 'Self Employed' || $fields['employment_type'] =='')? 'hidden': '' ?>">
    <div class="col-md-3 ">
        <label for="employee_code">Employee Code:</label>
        <input type="text" class="form-control" id="employee_code" placeholder="Enter Employee Code" name="employee_code" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "10" value="<?php echo $fields['employee_code']; ?>">
        <?php echo form_error('employee_code'); ?>
    </div>

    <div class="col-md-3 ">
        <label for="reason_for_leaving">Reason for Leaving:</label>
        <input type="text" class="form-control" id="reason_for_leaving" placeholder="Enter Reason for leaving" name="reason_for_leaving" value="<?php echo $fields['reason_for_leaving']; ?>">
        <?php echo form_error('reason_for_leaving'); ?>
    </div>


    <div class="col-md-3 ">
      <label for="current_employer" class="label">Current Employer:</label>
      <select class="form-control" name="current_employer" id="country_id">
          <option value="">Select Option</option>
          <?php foreach($current_employer_options as $key=>$value): ?>
              <option value="<?php echo $key; ?>" <?php echo ($key==$fields['current_employer']) ? 'selected' : '' ?> ><?php echo $value; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('current_employer'); ?>
    </div>


    <div class="col-md-3 ">
        <label for="notice_period">Notice Period:</label>
        <input type="text" class="form-control" id="notice_period" placeholder="Enter Notice Period in Days" name="notice_period" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['notice_period']; ?>">
        <?php echo form_error('notice_period'); ?>
    </div>
    <input type="hidden" name="candidate_id" value="<?= $fields['candidate_id']?>">
    <input type="hidden" name="action" value="<?= $fields['action']?>">
</div>

<div id="emp-row-4" class="form-group row <?= ($fields['employment_type'] == 'Self Employed' || $fields['employment_type'] =='')? 'hidden': '' ?>">
    <div class="col-md-3">
        <label for="joining_location" >Joining Location:</label>
        <input type="text" class="form-control" id="joining_location" placeholder="Enter Joining Location" name="joining_location" value="<?php echo $fields['joining_location']; ?>">
        <?php echo form_error('joining_location'); ?>
    </div>

    <div class="col-md-3">
        <label for="reporting_location" >Reporting Location:</label>
        <input type="text" class="form-control" id="reporting_location" placeholder="Enter Reporting Location" name="reporting_location" value="<?php echo $fields['reporting_location']; ?>">
        <?php echo form_error('reporting_location'); ?>
    </div>

    <div class="col-md-3">
        <label for="from" class="">From Year:</label>
        <input type="text" class="form-control"  id="from" placeholder="Enter Starting Year" name="from" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['from']; ?>">
        <?php echo form_error('from'); ?>
    </div>

    <div class="col-md-3">
        <label for="to" class="">To Year:</label>
        <input type="text" class="form-control" id="to" placeholder="Enter Ending Year" name="to" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['to']; ?>">
        <?php echo form_error('to'); ?>
    </div>
</div>

<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

<script type="text/javascript">
  $(document).ready(function () {

    let emp_type = '<?= $fields["employment_type"];?>';
    changeLabelsClass(emp_type);

    $('#employment_type').change(function() {
      let employment_type = $(this).find(':selected').val();
      changeLabelsClass(employment_type);
    });

    $('#employment_start_date').on('changeDate', function(ev){
       $(this).datepicker('hide');
   });

  });

  function changeLabelsClass(employment_type) {
    if(employment_type.trim().toLowerCase()==('Self Employment').trim().toLowerCase()
        || employment_type.trim().toLowerCase()==('Self Employed').trim().toLowerCase() || employment_type=='') {
      $('#emp-row-1, #emp-row-2, #emp-row-3, #emp-row-4').addClass('hidden');
      $('#emp-row-0').removeClass('hidden');
      $('#company_name_label').removeClass('label');
      $('#ctc_label').html('Projected Earnings (INR Per Month):');
      $('#employment_start_date_container').removeClass('hidden');
    } else {
      $('#emp-row-1, #emp-row-2, #emp-row-3, #emp-row-4').removeClass('hidden');
      $('#emp-row-0').addClass('hidden');
      $('#employment_start_date_container').addClass('hidden');
      $('#company_name_label').addClass('label');
      $('#ctc_label').html('Last CTC:');
    }
  }

  function checkValidNumber()
  {
      return this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
  }
</script>

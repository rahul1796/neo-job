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
<div class="form-group row" style="margin-top: 20px;">
 <div class="col-md-4">
   <label for="skill_name" class="label">Skill Name:</label>
   <input type="text" class="form-control" id="skill_name" placeholder="Enter Skill Name" name="skill_name" value="<?php echo $fields['skill_name']; ?>">
   <?php echo form_error('skill_name'); ?>
 </div>

 <div class="col-md-4">
   <label for="skill_description" class="label">Skill Description:</label>
   <input type="text" class="form-control" id="skill_description" placeholder="Enter Skill Description" name="skill_description" value="<?php echo $fields['skill_description']; ?>">
   <?php echo form_error('skill_description'); ?>
 </div>

 <div class="col-md-4">
     <label for="version">Version:</label>
     <input type="text" class="form-control" id="version" placeholder="Enter Version" name="version" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "5" value="<?php echo $fields['version']; ?>">
     <?php echo form_error('version'); ?>
     <input type="hidden" name="candidate_id" value="<?php echo $fields['candidate_id']; ?>">
 </div>

</div>

<div class="form-group row">

    <div class="col-md-3">
        <label for="last_used_year" class="label">Last Used Year:</label>
        <input type="text" class="form-control" id="last_used_year" placeholder="Enter Year" name="last_used_year" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['last_used_year']; ?>">
        <?php echo form_error('last_used_year'); ?>
    </div>
    <div class="col-md-3">
        <label for="last_used_month" class="label">Last Used Month:</label>
        <input type="text" class="form-control" id="last_used_month" placeholder="Enter Month" name="last_used_month" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['last_used_month']; ?>">
        <?php echo form_error('last_used_month'); ?>
    </div>

    <div class="col-md-3">
        <label for="experience_year" class="label">Experience In Year(s):</label>
        <input type="text" class="form-control" id="experience_year" placeholder="Enter Experience Year(s)" name="experience_year"  min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['experience_year']; ?>">
        <?php echo form_error('experience_year'); ?>
    </div>

    <div class="col-md-3">
        <label for="experience_month" class="label">Experience In Month(s):</label>
        <input type="text" class="form-control" id="experience_month" placeholder="Enter Experience Month(s)" name="experience_month" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "2" value="<?php echo $fields['experience_month']; ?>">
        <?php echo form_error('experience_month'); ?>
    </div>

</div>

<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

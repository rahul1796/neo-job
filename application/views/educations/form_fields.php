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
<div class="form-group row" style="">

    <div class="col-md-3">
      <label for="education_id" class="label">Education:</label>
      <select class="form-control" name="education_id">
          <option value="">Select Education</option>
          <?php foreach($education_options as $option):?>
              <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['education_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('education_id'); ?>
    </div>

    <div class="col-md-3">
        <label for="specialization" class="label">Specialization:</label>
        <input type="text" class="form-control" id="specialization" placeholder="Enter Specialization" name="specialization" value="<?php echo $fields['specialization']; ?>">
        <?php echo form_error('specialization'); ?>
        <input type="hidden" name="candidate_id" value="<?= $fields['candidate_id']?>">
    </div>

    <div class="col-md-3">
        <label for="institution" class="label">Institution/School:</label>
        <input type="text" class="form-control" id="institution" placeholder="Enter Institution " name="institution" value="<?php echo $fields['institution']; ?>">
        <?php echo form_error('institution'); ?>
    </div>

    <div class="col-md-3">
        <label for="location" class="label">Location:</label>
        <input type="text" class="form-control" id="location" placeholder="Enter Location " name="location" value="<?php echo $fields['location']; ?>">
        <?php echo form_error('location'); ?>
    </div>
</div>

 <div class="form-group row">

    <div class="col-md-3">
        <label for="from_year" class="label">From Year:</label>
        <input type="text"  class="form-control" id="from_year" placeholder="Enter From Year" name="from_year" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['from_year']; ?>">
        <?php echo form_error('from_year'); ?>
    </div>

    <div class="col-md-3">
        <label for="to_year" class="label">To Year:</label>
        <input type="text" class="form-control" id="to_year" placeholder="Enter To Year" name="to_year" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['to_year']; ?>">
        <?php echo form_error('to_year'); ?>
    </div>

    <div class="col-md-3">
        <label for="year_of_passing" class="label">Year of passing:</label>
        <input type="text" class="form-control" id="year_of_passing" placeholder="Enter Year of Passing " name="year_of_passing" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "4" value="<?php echo $fields['year_of_passing']; ?>">
        <?php echo form_error('year_of_passing'); ?>
    </div>



    <div class="col-md-3">
      <label for="learning_type_id" class="label">Learning Type:</label>
      <select class="form-control" name="learning_type_id">
          <option value="">Select Learning Type</option>
          <?php foreach($learning_type_options as $option):?>
              <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['learning_type_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('learning_type_id'); ?>
    </div>

</div>
<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

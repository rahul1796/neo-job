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
            <div class="form-group row" style="margin-top: 15px;">
                <div class="col-md-3">
                    <label for="qualification_pack_id" class="label">Qualification Pack:</label>
                    <select class="form-control select2-neo" name="qualification_pack_id">
                        <option value="">Select Qualification Pack</option>
                        <?php foreach($qualification_pack_options as $option):?>
                            <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['qualification_pack_id']) ? 'selected' : '' ?> ><?php echo "{$option->name} ({$option->code})"; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php echo form_error('qualification_pack_id'); ?>
                </div>

                    <div class="col-md-3">
                        <label for="batch_code">Batch Code:</label>
                        <input type="text" class="form-control" id="batch_code" placeholder="Enter Batch Code" name="batch_code" value="<?php echo $fields['batch_code']; ?>">
                        <?php echo form_error('batch_code'); ?>
                    </div>

                    <div class="col-md-3">
                        <label for="center_name" class="label">Center Name:</label>
                        <input type="text" class="form-control" id="center_name" placeholder="Enter Center Name" name="center_name" value="<?php echo $fields['center_name']; ?>">
                        <?php echo form_error('center_name'); ?>
                    </div>

                    <div class="col-md-3">
                        <label for="center_location" class="label">Center Location:</label>
                        <input type="text" class="form-control" id="center_location" placeholder="Enter Center Location" name="center_location" value="<?php echo $fields['center_location']; ?>">
                        <?php echo form_error('center_location'); ?>
                    </div>
                    <input type="hidden" name="candidate_id" value="<?= $fields['candidate_id']?>">
            </div>
            <div class="form-group row" >
                    <div class="col-md-3">
                        <label for="course_name" class="label">Course:</label>
                        <input type="text" class="form-control" id="course_name" placeholder="Enter Course Name" name="course_name" value="<?php echo $fields['course_name']; ?>">
                        <?php echo form_error('course_name'); ?>
                    </div>

                    <div class="col-md-3">
                        <label for="funding_source">Funding Client:</label>
                        <input type="text" class="form-control" id="funding_source" placeholder="Enter Funding Client" name="funding_source" value="<?php echo $fields['funding_source']; ?>">
                        <?php echo form_error('funding_source'); ?>
                    </div>
                    <div class="col-md-3">
                        <label for="certification_date" class="label">Certification Date:</label>
                        <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="certification_date" placeholder="Enter Certification Date" name="certification_date" value="<?=($fields['certification_date']=='') ? '' : date_format(date_create($fields['certification_date']),'d-M-Y') ?>">
                        <?php echo form_error('certification_date'); ?>
                    </div>
            </div>
 <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
            <script>
              $(document).ready(function() {
                $(function(){
                    $('#certification_date').datepicker({
                        endDate: '+0d',
                        autoclose: true
                    });
                });
              });
                $(document).ready(function() {
                    $('.select2-neo').select2();
                  });
            </script>

<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

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


<div class="form-group row">
  <div class="col-md-4">
      <label for="document_type_id" class="label">Document Type:</label>
      <select class="form-control" name="document_type_id">
          <option value="">Select Document Type</option>
          <?php foreach($document_options as $option):?>
              <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['document_type_id']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
          <?php endforeach; ?>
      </select>
      <?php echo form_error('document_type_id'); ?>
      <input type="hidden" name="candidate_id" value="<?php echo $fields['candidate_id']; ?>">
  </div>
  <div class="col-md-6">
    <label for="file_nmae" class="label">Upload File:</label>
    <span class="text text-danger">( jpg, png, pdf, doc, docx ) files only</span>
    <input type="file" class="form-control" name="file_name" value="">
    <?php echo form_error('file_name'); ?>
  </div>
</div>
<button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

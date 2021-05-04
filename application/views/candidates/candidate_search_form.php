<div class="card" style="margin-top: 10px; padding: 16px;">
    <div class="card-header" onclick="myFunction()" style="cursor:pointer;">
                        <h5 class="card-title">Click to Search Candidate</h5>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                    </div>
    <div class="card-body" id="myDIV" style="display:none;">

        <div class="form-group row" style="margin-top: 20px;">
            <div class="col-md-4">
                <label for="search_name">Name:</label>
                <input type="text" class="form-control" id="search_name" placeholder="Enter Name" name="search_name" value="<?php echo $search_data['search_name']; ?>">
            </div>

            <div class="col-md-4">
                <label for="search_phone">Phone:</label>
                <input type="text" class="form-control" id="search_phone" placeholder="Enter Phone" name="search_phone" value="<?php echo $search_data['search_phone']; ?>">
            </div>

            <div class="col-md-4">
                <label for="search_email">Email:</label>
                <input type="text" class="form-control" id="search_email" placeholder="Enter Email" name="search_email" value="<?php echo $search_data['search_email']; ?>">
            </div>

            </div>
            <div class="form-group row">
              <div class="col-md-4">
                  <label for="search_employment_type">Employment Type:</label>
                  <select class="form-control" id="search_employment_type" name="search_employment_type">
                      <option value="">All</option>
                      <?php foreach($employment_type as $option): ?>
                          <option value="<?= $option->name; ?>" <?php echo ($option->name==$search_data['search_employment_type']) ? 'selected' : '' ?> ><?= $option->name; ?></option>
                      <?php endforeach;?>
                  </select>
              </div>


            <div class="col-md-4">
                <label for="search_candidate_type">Candidate Type:</label>
                <select class="form-control" id="search_education" name="search_candidate_type">
                    <option value="">All</option>
                    <?php foreach($candidate_type as $type): ?>
                        <option value="<?php echo $type; ?>" <?php echo ($type==$search_data['search_candidate_type']) ? 'selected' : '' ?> ><?php echo $type; ?></option>
                    <?php endforeach;?>
                </select>
            </div>

            <div class="col-md-4">
                <label for="search_education">Educational Qualification:</label>
                <select class="form-control" id="search_education" name="search_education">
                    <option value="">All</option>
                    <?php foreach($education_options as $edu_option): ?>
                        <option value="<?php echo $edu_option->id; ?>" <?php echo ($edu_option->id==$search_data['search_education']) ? 'selected' : '' ?> ><?php echo $edu_option->name; ?></option>
                    <?php endforeach;?>
                </select>
            </div>
        </div>
        <div class="form-group row">
          <div class="col-md-4">
            <label for="search_gender">Search Gender:</label>
            <select class="form-control" id="search_education" name="search_gender">
                <option value="">All</option>
                <?php foreach($gender_options as $option): ?>
                    <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$search_data['search_gender']) ? 'selected' : '' ?> ><?php echo $option->name; ?></option>
                <?php endforeach;?>
            </select>
          </div>
          <div class="col-md-4">
              <label for="search_qualification_pack">Search Qualification Pack:</label>
              <select class="form-control select2-neo" style="width:100%" id="search_qualification_pack" name="search_qualification_pack">
                  <option value="">All</option>
                  <?php foreach($qp_options as $option): ?>
                      <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$search_data['search_qualification_pack']) ? 'selected' : '' ?> ><?= $option->name.' ('.$option->code.')'; ?></option>
                  <?php endforeach;?>
              </select>
          </div>
          <div class="col-md-4">
              <label for="search_course_name">Course Name:</label>
              <input type="text" class="form-control" id="search_course_name" placeholder="Enter Course Name" name="search_course_name" value="<?php echo $search_data['search_course_name']; ?>">
          </div>
        </div>
        <div class="row form-group">
          <div class="col-md-4">
              <label for="search_batch_code">Batch Code:</label>
              <input type="text" class="form-control" id="search_batch_code" placeholder="Enter Batch Code" name="search_batch_code" value="<?php echo $search_data['search_batch_code']; ?>">
          </div>
          <div class="col-md-4">
              <label for="search_center_name">Center Name:</label>
              <input type="text" class="form-control" id="search_center_name" placeholder="Enter Center Name" name="search_center_name" value="<?php echo $search_data['search_center_name']; ?>">
          </div>
        </div>
        <div class="form-group row">
            <div class="col-md-1">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
            <div class="col-md-1">
              <a name="reset-form" class="btn btn-default" href="<?= current_url(); ?>">Reset</a>
            </div>
        </div>
    </div>
</div>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script>
function myFunction() {
  var x = document.getElementById("myDIV");
  if (x.style.display === "none") {
    x.style.display = "block";
  } else {
    x.style.display = "none";
  }
}

$(document).ready(function() {

  $('.select2-neo').select2();
});

</script>

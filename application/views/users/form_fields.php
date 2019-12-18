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
<div class="form-group row" style="margin-top:30px;">
    <div class="col-md-4">
        <label for="name" class="label">Name:</label>
        <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name" value="<?php echo $fields['name']; ?>">
        <?php echo form_error('name'); ?>
    </div>

    <div class="col-md-4">
        <label for="email" class="label" >Email:</label>
        <input type="text" class="form-control" <?= ($action=='edit') ? 'readonly' : ''; ?> id="email" placeholder="Enter Email" name="email" value="<?php echo $fields['email']; ?>">
        <?php echo form_error('email'); ?>
    </div>

    <div class="col-md-4">
        <label for="pwd" class="label">Password:</label>
        <input type="text" class="form-control" id="pwd" placeholder="Enter Password " maxlength="15" name="pwd" value="<?php echo $fields['pwd']; ?>">
        <?php echo form_error('pwd'); ?>
    </div>
</div>

<div class="form-group row" style="margin-top: 20px;">
  <input type="hidden" name="action" value="<?= ($action=='edit') ? 'edit' : 'create'; ?>">
   <div class="col-md-4">
        <label for="name" class="label">Employee ID:</label>
        <input type="text" maxlength="6" class="form-control" placeholder="Enter Employee ID" name="employee_id" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "6" value="<?php echo $fields['employee_id']; ?>">
        <?php echo form_error('employee_id'); ?>
    </div>
<!--  <div class="col-md-3">
      <label for="region_id" class="label">Select Region:</label>
      <select class="form-control" name="region_id" id="region_id">
          <option value="0">Select Region</option>
          <?php //foreach($regions_options as $region_option): ?>
              <option value="<?php// echo $region_option->id; ?>" <?php// echo ($region_option->id==$fields['region_id']) ? 'selected' : '' ?> ><?php// echo $region_option->name; ?></option>
          <?php //endforeach; ?>
      </select>
      <?php// echo form_error('region_id'); ?>
  </div>-->
  <div class="col-md-4">
      <?php if($action == 'edit' && $reportees_count>0): ?>
      <label for="user_role_id" class="label">User Role: </label><span class="text text-danger"><?= "Currently {$reportees_count} user reporting to this role"?></span>
        <?php foreach($user_roles_options as $option):?>
            <?php if($option->id==$fields['user_role_id']):?>
              <input type="text" class="form-control" readonly name="user_role_dummy" value="<?= $option->name; ?>">
            <?php endif; ?>
        <?php endforeach; ?>
        <input type="hidden" name="user_role_id" value="<?= $fields['user_role_id']; ?>">
      <?php else: ?>
        <label for="user_role_id" class="label">User Role:</label>
        <select class="form-control" name="user_role_id" id="user_role_id">
            <option value="0">Select User Role</option>
            <?php foreach($user_roles_options as $option):?>
                <option value="<?php echo $option->id; ?>" <?php echo ($option->id==$fields['user_role_id']) ? 'selected' : '' ?>  data-rp-id="<?php echo $option->reporting_manager_role_id; ?>"><?php echo $option->name; ?></option>
            <?php endforeach; ?>
        </select>
    <?php endif; ?>
      <?php echo form_error('user_role_id'); ?>
  </div>

  <div class="col-md-4">
    <div class="" id="reporting_manager_role_id_container">
      <label for="reporting_manager_role_id" class="label">Reporting Manager Role:</label>
      <select class="form-control" name="reporting_manager_role_id" id="reporting_manager_role_id">

      </select>
      <?php echo form_error('reporting_manager_role_id'); ?>
    </div>
  </div>

</div>

<div class="row form-group">
    <div class="col-md-12">
      <div class="" id="reporting_manager_id_container">
        <label for="reporting_manager_id" class="label">Reporting Manager:</label>
        <select class="form-control select2-neo" style="width:100%;" name="reporting_manager_id" id="reporting_manager_id">

        </select>
        <?php echo form_error('reporting_manager_id'); ?>
      </div>
    </div>
</div>


<div class="row form-group" >
  <div class="col-md-12 hidden" id="center_user_select">
    <label for="centers" class="label">Assign Centers:</label>
    <select class="select2-neo form-control" style="width:100%;" multiple name="centers[]">
      <?php foreach($centers as $option): ?>
          <option value="<?php echo $option->id; ?>" <?= (in_array($option->id, $fields['centers'])) ? 'selected':'' ?> ><?php echo $option->center_name; ?></option>
      <?php endforeach; ?>
  </select>
  <?php echo form_error('centers[]'); ?>
  </div>
</div>


      <button type="submit" class="btn btn-primary" style="margin-bottom: 30px;">Submit</button>

      <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
      <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script type="text/javascript">
 var reporting_manager = '<?= $fields['reporting_manager_id'];?>' || '0';
 var role_id = '<?= $fields['user_role_id'];?>' || '0';
 var updated_user_id = '<?= $user_id ?>';
 var reporting_manager_role_id = '<?= $fields['reporting_manager_role_id'] ?>' || '0';


 $(document).ready(function() {

   $('.select2-neo').select2();

   $('#name').bind('keypress', name);

 });

  function name(event) {
     var value = String.fromCharCode(event.which);
     var pattern = new RegExp(/[a-zåäö ]/i);
     return pattern.test(value);
  }


 $(document).ready(function() {

   if(parseInt(role_id)!=0) {

    if(parseInt(role_id)!=2) {
      getReportingManagerRoles(role_id);
      if(parseInt(reporting_manager_role_id)!=0) {
        $('#reporting_manager_role_id').val(reporting_manager_role_id).change();
      }

      let c_id = $('#reporting_manager_role_id').find(':selected').val();
      if(parseInt(c_id)!=0){
          getReportingManagers(c_id);
      }

      if(parseInt(reporting_manager)!=0) {
        $('#reporting_manager_id').val(reporting_manager).change();
      }
    } else {
      let c_id = $('#user_role_id').find(':selected').attr('data-rp-id') || '1';
      //alert(c_id);
      if(parseInt(c_id)!=0){
          getReportingManagers(c_id);
      }
      if(parseInt(reporting_manager)!=0) {
        $('#reporting_manager_id').val(reporting_manager).change();
      }
    }

    if(parseInt(role_id)==2) {
      $('#reporting_manager_id_container').addClass('hidden');
      $('#reporting_manager_role_id_container').addClass('hidden');
    }
    if(parseInt(role_id)==9 || parseInt(role_id)==11 || parseInt(role_id)==14 || parseInt(role_id)==13 || parseInt(role_id)==12){
      $('#center_user_select').removeClass('hidden');
    } else {
      $('#center_user_select').addClass('hidden');
    }
   }

   $('#user_role_id').on('change', function() {
     let c_id = $(this).find(':selected').attr('data-rp-id');
     let selected_id = $(this).find(':selected').val();
     //if(selected_id==0){
       $('#reporting_manager_id').html('');
       $('#reporting_manager_id').append($('<option>').text('Select Manager'));
     //}
     if(selected_id==2) {
       $('#reporting_manager_id_container').addClass('hidden');
       $('#reporting_manager_role_id_container').addClass('hidden');
     } else {
       $('#reporting_manager_id_container').removeClass('hidden');
       $('#reporting_manager_role_id_container').removeClass('hidden');
     }
     if(parseInt(selected_id)==9 || parseInt(selected_id)==11 || parseInt(selected_id)==14 || parseInt(selected_id)==13 || parseInt(selected_id)==12){
       $('#center_user_select').removeClass('hidden');
     } else {
       $('#center_user_select').addClass('hidden');
     }
     if(selected_id!=0) {
        if(selected_id==2) {
          getReportingManagers(c_id);
          $('#reporting_manager_id').val(1).change();
        } else {
          getReportingManagerRoles(selected_id);
        }
     }
   });

   $('#reporting_manager_role_id').on('change', function() {
     let manager_role_id = $(this).find(':selected').val();
     getReportingManagers(manager_role_id);
   });

 });

 function getReportingManagerRoles(r_id){
   let request = $.ajax({
     url: "<?php echo base_url(); ?>UsersController/getReportingManagerRoles/"+r_id,
     type: "GET",
     async: false,
   });

   request.done(function(msg) {
     let response = JSON.parse(msg);
     // alert(response);
     $('#reporting_manager_role_id').html('');
     let count = 0;
     console.log(response);
     response.forEach(function(manager) {
         if(count==0){
           $('#reporting_manager_role_id').append($('<option>').text('Select Manager Role').attr('value', 0));
         }
        $('#reporting_manager_role_id').append($('<option>').text(manager.name).attr('value', manager.id));

       count++;
     });
   });

   request.fail(function(jqXHR, textStatus) {
     alert( "Request failed: " + textStatus );
   });
 }

 function getReportingManagers(r_id) {
   let request = $.ajax({
     url: "<?php echo base_url(); ?>UsersController/getReportingManager/"+r_id,
     type: "GET",
     async: false,
   });

   request.done(function(msg) {
     let response = JSON.parse(msg);
     // alert(response);
     $('#reporting_manager_id').html('');
     let count = 0;
     response.forEach(function(manager) {
       if(parseInt(updated_user_id)!=manager.id) {
         if(count==0){
           $('#reporting_manager_id').append($('<option>').text('Select Manager'));
         }
        $('#reporting_manager_id').append($('<option>').text(manager.name +' ('+manager.role_name+') ').attr('value', manager.id));
       }
       count++;
     });
   });

   request.fail(function(jqXHR, textStatus) {
     alert( "Request failed: " + textStatus );
   });
 }

 $(document).ready(function() {

   $('.select2-neo').select2();
 });
</script>

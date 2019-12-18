
<div class="container-fluid">
  <div class="row">
    <div class="col-md-12" style="background: white; margin-top: 3px;">
      <div class="card-header">
        <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Filter by</h4></label>
      </div>
      <div class="card-body" style="padding: 20px;margin-bottom: 5%">
       <div class="col-sm-3">
      <label for="qp_search">Job Role</label>
      <select class="form-control select2-neo" name="qp_search" id= "qp_search" multiple size="5">
        <?php foreach($qualification_pack_options as $qualification_pack_option): ?>
          <option value="<?= $qualification_pack_option->id; ?>"><?= $qualification_pack_option->name; ?></option>
        <?php endforeach; ?>
      </select>
        </div>
        <div class="col-sm-3">
      <label for="edu_search">Education</label>
      <select class="form-control select2-neo" name="edu_search" id="edu_search" multiple size="5">
        <?php foreach($education_options as $education_option): ?>
          <option value="<?= $education_option->id; ?>"><?= $education_option->name; ?></option>
        <?php endforeach; ?>
      </select>
         </div>
        <div class="col-sm-3">
      <label for="location_search">Location</label>
      <input type="text" class="form-control" id="location_search" name="location_search" value="">
       </div>
        <div class="col-sm-3" style="margin-top: 30px;">
      <button type="button" onclick="searchJob();" name="button" class="btn btn-success" id="search_btn" style="width: 50%;">Search</button>
       </div>
    </div>
      </div>
    <div class="col-md-12" style="padding: 40px;">
      <div class="row">
        <div class="card-header">
          <label class="card-title" for="color" style="margin-bottom: -8px;"><h4>Available Job List</h4></label>
        </div>
        <div  id="job-list-container">
          <?= $job_list ?>
        </div>

          <div class="col-md-12" style="text-align:center;">
            <br>
            <h3 id="job-message" class="hidden" style="text-align:center">No Jobs to Show</h3>
            <button type="button" class="btn btn-warning" onclick="loadMoreJob();" id="load-more" name="button">Load More Jobs</button>
          </div>
      </div>
    </div>
  </div>
</div>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {

  $('.select2-neo').select2();
});

var pagenum = 2;

function loadMoreJob() {

  let request = makeJobRequest(pagenum);

  request.done(function (response ) {
    let res = JSON.parse(response);
    jobList(res.data);
    hideLoadMore(res.data);
    pagenum++;
  });

  request.fail(function (jqXHR, textStatus) {
    //console.log(jqXHR.message);
  });
}
//
$('a[data-toggle="tooltip"]').tooltip({
  animated: 'fade',
  placement: 'bottom',
  trigger: 'click'
});

$('#job-list-container').on('click', 'a[data-toggle="tooltip"]', function(event) {

  $('a[data-toggle="tooltip"]').tooltip({
    animated: 'fade',
    placement: 'bottom',
    trigger: 'click'
  });
  //$('a[data-toggle="tooltip"]').tooltip('hide');
  $(this).tooltip('show');
  event.stopPropagation();
});

/*$('a[data-toggle="tooltip"]').click(function(event){
  event.stopPropagation();
});*/

$('body').click(function(){
  $('a[data-toggle="tooltip"]').tooltip('hide');
});


function searchJob() {

  let request = makeJobRequest(0);

  request.done(function (response) {
    let res = JSON.parse(response);
    $('#job-list-container').html('');
    jobList(res.data);
    hideLoadMore(res.data);
  });

  request.fail(function (jqXHR, textStatus) {

  });
  request.always(function (jqXHR, textStatus) {
    pagenum = 2;
  });

}

function makeJobRequest(page) {
  var education_ids_val = getIntArray($('#edu_search').val());
  var qp_ids_val = getIntArray($('#qp_search').val());
  var location = $('#location_search').val();
  var obj = {'education_ids': education_ids_val, 'qp_ids': qp_ids_val, 'page': page, 'job_location': location};
//  console.log(obj);
  return $.ajax({
    method: "POST",
    url: "<?= base_url('JobsController/getJobs')?>",
    data: obj,
  });
}

function hideLoadMore(data) {
  if(data.length<=0) {
    $('#load-more').addClass('hidden');
    $('#job-message').removeClass('hidden');
  } else {
    $('#load-more').removeClass('hidden');
    $('#job-message').addClass('hidden');
  }
}

function jobList(job_data) {

  var varDescription = '';
  $.each(job_data, function(index, value) {
    //let h5 = $('<h5>').html('<b>'+(value.job_title || 'N/A')+'</b>');
    let h5 = $('<h5>').html((value.job_title || 'N/A')).attr('id', 'job-title-'+value.id);
    let ul = $('<ul style="font-size: 14px;">');
    ul.append($('<li>').text('Job Qualificaiton Pack: '+(value.qp_name || 'N/A')));
    ul.append($('<li>').text('Job Location: '+(value.job_location || 'N/A')));
    ul.append($('<li>').text('Experience: '+(value.experience_from || 0)+' - '+(value.experience_to || 0)));
    ul.append($('<li>').text('Educational Qualification: '+(value.edu_name || 'N/A')));
    if (value.job_description != null && value.job_description != undefined && value.job_description != '') {
      varDescription = '<a href="JavaScript:void(0);" data-toggle="tooltip" title="' + value.job_description + '"><p style="color: orange">More..</p></a>';
      ul.append(varDescription);
    }
    ul.append($('<button type="button" class="btn btn-info" name="button" style=" float:right; margin-top: -32%; margin-right: -59%;" data-toggle="modal" onclick="apply('+value.id+')">Apply</button>'));
    let card = $('<div class="col-md-6 card" style="margin-bottom: 1px !important;height: 170px;">');
    let card_body = $('<div class="card-body" style="padding: 20px;">');
    let card_container = $('<div class="col-sm-8" style="margin-bottom: 25px;">').append(h5).append(ul);
    card_body.append(card_container);
    card.append(card_body);
    $('#job-list-container').append(card);
  });
}

function getIntArray(stringArray) {
  return  (stringArray==null) ? [] : stringArray.map(function(i){
                          return parseInt(i, 10);
                  });
}

function apply(id)
{
  $('#job-id').val(id);
  $('#phone, #name, #email').val('');
  $('.error-span').html('');
  $('#job-name').val($('#job-title-'+id).html());
  $('#alert-box').addClass('hidden');
  $("#apply_job").modal("show");
  $("#msgDisplay").html('');
}

</script>

<div class="modal fade text-xs-left" id="apply_job" tabindex="-1" role="dialog" aria-labelledby="myModalLabel34" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary white">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel34" style="color: white;">Apply job</h4>
      </div>
      <div id="msgDisplay" style="padding: 10px;"></div>
        <div class="modal-body">
        <div class="col-md-12">
          <div class="alert hidden" id="alert-box">

          </div>
        </div>
        <div class="form-group row">
          <label class="col-md-2 label-control" for="projectinput1"><label style="color: red;">*</label>Job Title</label>
          <div class="col-md-10">
            <input type="hidden" name="" id="job-id" value="">
            <input type="text" disabled class="form-control" id="job-name" name="" value="">
            <span class="text error-span" id="job_id-error"></span>
          </div>
          </div>
          <div class="form-group row">
            <label class="col-md-2 label-control" for="projectinput1"><label style="color: red;">*</label>Name</label>
            <div class="col-md-10">
              <input  type="text" class="form-control" placeholder="Enter Name" name="name" id="name" autocomplete="off">
              <span class="text error-span" id="name-error"></span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-2 label-control" for="projectinput1"><label style="color: red;">*</label>Email</label>
            <div class="col-md-10">
              <input  type="email" class="form-control" placeholder="Enter Email Address" name="email" id="email" autocomplete="off">
              <span class="text error-span" id="email-error"></span>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-md-2 label-control" for="projectinput1"><label style="color: red;">*</label>Phone</label>
            <div class="col-md-10">
              <input  type="tel" class="form-control" placeholder="Enter phone Number" name="phone" id="phone" maxlength="10" autocomplete="off">
              <span class="text error-span" id="phone-error"></span>
            </div>
          </div>
          <div class="form-group row">
            <button type="submit" class="btn btn-success " onclick="applyJob();" name="btn_apply" id="btn_apply" style="width: 23%; margin-left: 445px; margin-bottom: -16px;">Submit</button>
          </div>
        </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  function applyJob() {
    $('.error-span').html('');
    let phone = $('#phone').val();
    let email = $('#email').val();
    let name = $('#name').val();
    let job_id = $('#job-id').val();
    $.ajax({
      url:'<?= base_url('jobscontroller/applyJob'); ?>',
      data: {'name': name, 'email': email, 'phone':phone, 'job_id' : job_id},
      method: 'POST',
    }).done(function(response) {
      let data = JSON.parse(response);
      if(data.status) {
          $("#apply_job").modal("hide");
          //$('#alert-box').removeClass('hidden').addClass('alert-success').html('Applied Successfully');
          openDialog('Applied Successfully');
      } else {
        let err ="Some Error Occurred, Try again later";
        if( Object.keys(data.errors).length>0) {
          err = 'Check Errors';
              $.each(data.errors, function (key, val) {
                  $('#'+key+'-error').html(val).addClass('text-danger');
              });

        }
        $('#alert-box').removeClass('hidden').addClass('alert-danger').html(err);
      }
    }).fail(function(jxQHR, textStatus) {
      console.log(jxQHR);
    });
  }

  function openDialog(msg) {
    swal({
  title: "",
  text: msg,
  confirmButtonColor: "#5cb85c",
  confirmButtonText: 'OK',
  closeOnConfirm: true,
  closeOnCancel: true
  },
  function (confirmed) {
  //window.location.reload(true);
  });
  }
</script>

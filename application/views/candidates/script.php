
<script type="text/javascript">

  $(document).ready(function() {
    $('.status_selector').on('change', function() {
      let data_value = $(this).find(':selected').attr('data-svalue');
      let not_value = $(this).find(':selected').attr('data-notification');
      let candidate_value = $(this).find(':selected').attr('data-candidate');
      let option_value = $(this).find(':selected').val();
      $('#error_'+candidate_value).text('');
      if(data_value<0) {
        $('#text_'+candidate_value).removeClass('hidden');
      } else {
        $('#text_'+candidate_value).addClass('hidden');
      }
      if(not_value==1) {
        $('#lead_schedule_input_'+candidate_value).removeClass('hidden');
        $('#text_'+candidate_value).removeClass('hidden');
      } else {
        $('#lead_schedule_input_'+candidate_value).addClass('hidden');
      }
      if(option_value==15 || option_value==12) {
        $('#button_'+candidate_value).addClass('hidden');
      } else {
        $('#button_'+candidate_value).removeClass('hidden');
      }
    });


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
  });

  function changeCandidateJobStatus(candidate_id, job_id, flag) {

    let data_input = $('#selected_'+candidate_id).find(':selected').attr('data-svalue');
    let not_value = $('#selected_'+candidate_id).find(':selected').attr('data-notification');

    var status_id = (flag=='update') ? $('#selected_'+candidate_id).val() : 1;
    var remark = $('#text_'+candidate_id).val();
    var schedule_date = $('#lead_schedule_input_'+candidate_id).val();

    if(flag=='update') {
    //  alert(data_input+' -- not_value :: '+not_value+' -- status_id :: '+status_id+' -- remark :: '+remark+' -- schedule_date ::'+schedule_date);
      if(data_input<0) {
        if(remark.trim() == ''){
          $('#error_'+candidate_id).text('Remark is mandatory');
          return
        }
      } else if(not_value==1) {
        if(remark.trim() == '' || schedule_date.trim() == ''){
          $('#error_'+candidate_id).text('Fields are mandatory');
          return
        }
      } 
        $('#error_'+candidate_id).text('');
        updateCandidateStatus(candidate_id, job_id, status_id, schedule_date, flag, remark);

    //  updateCandidateStatus(candidate_id, job_id, status_id, schedule_date, flag, remark);
    } else {
      let request_status = checkCandidatePlacement(candidate_id, job_id);
      if(request_status==-1) {
        alert('request failed try again later');
      } else if (request_status>0) {
          $('#candidates_jobs_modal').modal();

          $('#candidate_id_modal').val(candidate_id);
          $('#job_id_modal').val(job_id);
          $('#status_id_modal').val(status_id);
          $('#schedule_date_modal').val(schedule_date);
          $('#flag_modal').val(flag);
          $('#remark_modal').val(remark);
      } else {
          updateCandidateStatus(candidate_id, job_id, status_id, schedule_date, flag, remark);
      }
    }

  }

  function checkCandidatePlacement(candidate_id, job_id) {
    let request_status = -1;
    $('#proceed-anyway-btn').removeClass('hidden');
    $.ajax({
      url:"<?= base_url('jobscontroller/getPlacementDetails/');?>"+candidate_id+"/"+job_id,
      type:"GET",
      async:false
    }).done(function(data){
      let joined_url = '<?= base_url('/Pramaan/candidate_joined_jobwise/'); ?>';
      let response = JSON.parse(data);
      request_status = response.data.length;
      if(request_status>0) {
        $('#candidates_jobs_table').html('').html('<tr><th>Job Title</th><th>Customer Name</th><th>Joining Date</th><th>Job Business Vertical</th><th>Job Handlers</th></tr>');
        $.each(response.data, function(index, value){
          let joined_url_job = joined_url+value.job_id;
          $('#candidates_jobs_table').append('<tr>'+'<td>'+value.job_title+'</td>'+'<td>'+value.customer_name+'</td>'+'<td>'+value.joining_date+'</td>'+'<td>'+value.bv_name+'</td>'+'<td><button class="btn btn-warning btn-sm" onclick=getJobUsers('+value.job_id+')>'+value.handler_count+'</button></td>'+'</tr>');
          if(value.bv_id!=3) {
              $('#proceed-anyway-btn').addClass('hidden');
          }
        });
      }

      console.log(response);
    }).fail(function(jqXHR, textStatus) {
      request_status=-1;
    });
    return request_status;
  }


  function updateCandidateStatus(candidate_id, job_id, status_id, schedule_date, flag, remark) {
    var request = $.ajax({
      url: "<?php echo base_url(); ?>CandidatesController/candidateJobStatus",
      type: "POST",
      async: false,
      data: {"candidate_id" : candidate_id, "job_id" : job_id, "candidate_status_id" : status_id, "schedule_date":schedule_date,"flag" : flag, "remarks" : remark},
    });

    request.done(function(msg) {
      var response = JSON.parse(msg);
      if(response.status) {
        if(flag=="insert") {
            $('#container_'+candidate_id).remove();
        } else {
          $('#text_'+candidate_id).addClass('hidden');
          $('#lead_schedule_input_'+candidate_id).addClass('hidden');
          $('#text_'+candidate_id).val('');
          $('#lead_schedule_input_'+candidate_id).val('');
        }

        swal({
              title: "",
              text: 'Status Updated Successfully',
              confirmButtonColor: "#5cb85c",
              confirmButtonText: 'OK',
              closeOnConfirm: true,
              closeOnCancel: true
            },
            function (confirmed) {
              if(flag=="update") {
                  window.location.reload(true);
              } else {
                let candidate_count = parseInt($('#candidate-count').html());
                if(candidate_count!=0) {
                  $('#candidate-count').html(candidate_count-1);
                }
              }

            });
       // alert('Record updated successfully');
      }
    });

    request.fail(function(jqXHR, textStatus) {
      if(flag=="update"){
        $('#text_'+candidate_id).addClass('hidden');
        $('#lead_schedule_input_'+candidate_id).addClass('hidden');
        $('#text_'+candidate_id).val('');
        $('#lead_schedule_input_'+candidate_id).val('');
      }
      alert( "Request failed: " + textStatus );
    });
  }

  function proceedAnyway() {
    let candidate_id = $('#candidate_id_modal').val();
    let job_id = $('#job_id_modal').val();
    let status_id = $('#status_id_modal').val();
    let schedule_date = $('#schedule_date_modal').val();
    let flag= $('#flag_modal').val();
    let remark = $('#remark_modal').val();
    $('#candidates_jobs_modal').modal();
    updateCandidateStatus(candidate_id, job_id, status_id, schedule_date, flag, remark);
  }

  function getJobUsers(j_id) {
    $('#jobs_handler_table').html('');
    $('#jobs_handler_modal').modal();
    $.ajax({
      url : '<?= base_url('jobscontroller/getJobHandlers/');?>'+j_id,
      type : 'GET',
      async : false
    }).done(function(response) {
      console.log(response);
      let data = JSON.parse(response);
      if(data.data.length>0) {
          $('#jobs_handler_table').html('').html('<tr><th>Name</th><th>Email</th><th>User Role</th></tr>');
          $.each(data.data, function(index, value){
            $('#jobs_handler_table').append('<tr>'+'<td>'+value.name+'</td>'+'<td>'+value.email+'</td>'+'<td>'+value.user_type+'</td>');
          });
      } else {
        $('#jobs_handler_table').html('<h4> No user found for this job</h4>')
      }
    }).fail(function(jqXHR, text) {
      alert('Something went wrong. Try again later');
    });
  }
</script>

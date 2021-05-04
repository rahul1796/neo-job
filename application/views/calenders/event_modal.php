
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/datetimepicker/css/bootstrap-datetimepicker.css')?>">
<div class="modal fade" id="eventmodal" role="dialog">
   <div class="modal-dialog">
     <div class="modal-content">
       <div class="modal-header">
         <button type="button" class="close" data-dismiss="modal" onclick="javascript:window.location.reload()">&times;</button>
         <h4 class="modal-title">Add Event</h4>
       </div>
       <div class="modal-body">
         <br>
         <div class="col-md-12">
           <div class="alert hidden alert-warning" id="alert-box">
             <h4 id="status_message"></h4>
           </div>
         </div>
         <div class="event_container form-group row">
           <div class="col-md-12 form-group">
             <label for="" class="label">Title</label>
             <input type="text" class="form-control" name="title" id="title" value="" >
           </div>
           <div class="col-md-12 form-group">
             <label for="" class="label">Description</label>
             <textarea type="text" class="form-control" name="description" id="description" > </textarea>
           </div>

           <div class="col-md-6 form-group">
             <label for="" class="label">Start Date</label>
              <input type='text' class="form-control" id='start_date_input'>
           </div>
           <div class="col-md-6 form-group">
             <label for="" class="label">End Date</label>
              <input type='text' class="form-control" id='end_date_input' />
           </div>
         </div>
          <br>
         <button type="button" class="btn btn-primary" name="button" onclick="addEvent();">Save Event</button>
         <br><br>
       </div>
       <div class="modal-footer">
         <button type="button" class="btn btn-default" data-dismiss="modal" onclick="javascript:window.location.reload()">Close</button>
       </div>
     </div>

   </div>
 </div>

<script src="<?php echo base_url().'adm-assets/datetimepicker/js/bootstrap-datetimepicker.js'?>" type="text/javascript"></script>
<script type="text/javascript">

    function addEvent() {
      $('#alert-box').addClass('hidden');
      let title = $('#title').val();
      let description = $('#description').val();
      let start_date = $('#start_date_input').val();
      let end_date = $('#end_date_input').val();
      let start = new Date(start_date);
      let end = new Date(end_date);
      let today = new Date();
      if(title=='' || description == '' || start_date=='' || end_date=='') {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html('All the fields are required');
      }else if(title.length>=30) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html('Title length must be less than 30 characters');
      } else if(description.length>=250) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html('Description length must be less than 30 characters');
      } else if(start<today || end<today) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html("Event date must be greater than today's date");
      } else if(end<=start) {
        $('#alert-box').removeClass('hidden');
        $('#status_message').html('').html('Start date/time must be less than End date/time ');
      } else {
        let request_data = {'title' : title, 'description' : description, 'start_date' : start_date, 'end_date' : end_date};
        saveEventCall(request_data);
      }
    }


    function saveEventCall(request_data) {
      $.ajax({
        url:'<?= base_url("events/store")?>',
        type : 'POST',
        dataType : 'json',
        content_type:'json',
        data: request_data,

      }).done(function(response) {

          if(response.status==true) {
            $('#title, #description, #start_date_input, #end_date_input').val('');
            $('#status_message').html(response.message);
            $('#alert-box').removeClass('hidden');
          }

        }).fail(function(jqXHR, textStatus) {
          $('#status_message').html('Something went wrong try again');
          $('#alert-box').removeClass('hidden');
      });
    }

  $(document).ready(function() {

      $('#start_date_input, #end_date_input').datetimepicker({
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
</script>

<script type="text/javascript">
  function openEventModal() {
    $('#alert-box').addClass('hidden');
    $('#title, #description, #start_date_input, #end_date_input').val('');
    $('#eventmodal').modal();
  }
</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_upload" role="dialog">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Opportunity History</h3>
            </div>
            <div class="modal-body form">
                <div id="msgDisplay"></div>

                    <div class="form-body">
                       <!-- <input type="hidden" id="associate_id" name="associate_id" value="<?php /*echo $associate_id;*/?>"/>-->


                            <table id="tblSec" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>Remarks</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>City</th>
                                    <th>Proposal Shared To</th>
                                    <th>POV Per Month</th>
                                    <th>Potential Number</th>
                                    <th>Attachment</th>
                                    <th>Updated At</th>
                                    <!--<th>Action </th>-->
                                </tr>
                                </thead>
                                <tbody id="historytblBody">
                                </tbody>
                            </table>


                        <div style="clear: both;"></div>

                    </div>
            </div>
            <div class="modal-footer">
                <!--<button type="button" id="btnSave" onclick="bulk_upload_save()" class="btn btn-primary">Upload Candidate list</button>-->
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script type="text/javascript">

  function lead_history(lead_id)
  {
      $('.form-group').removeClass('has-error'); // clear error class
      $('.error_label').empty(); // clear error string
      $('#modal_form_upload').modal('show'); // show bootstrap modal
      //$('.modal-title').text('Lead History'); // Set Title to Bootstrap modal title
      getLeadHistory(lead_id);
  }

  function getLeadHistory(lead_id) {
      $('#historytblBody').html('');
      $.ajax({
      url: "<?= base_url('opportunitiescontroller/getLeadHistory/')?>"+lead_id,
      dataType: 'script',
      type: "GET",
      success: function(response) {
        var leadHistories  = JSON.parse(response);
        console.log(leadHistories);
        if(leadHistories.length>0) {
          $.each(leadHistories, function(index, history) {
            $('#historytblBody').append('<tr>');
            $('#historytblBody').append('<td>'+history.status_name+'</td>');
            if(history.lead_status_id == 8){
              $('#historytblBody').append('<td>'+('Proposal Shared Date: '+history.schedule_date)+'</td>');
            } else {
              $('#historytblBody').append('<td>'+((history.schedule_date!=null && history.schedule_date!='')? 'Meeting Date: '+history.schedule_date : 'N/A')+'</td>');
            }
            $('#historytblBody').append('<td>'+((history.remarks!='')? history.remarks : 'N/A')+'</td>');

            $('#historytblBody').append('<td>'+((history.name!=null && history.name!='')? history.name : 'N/A')+'</td>');
            $('#historytblBody').append('<td>'+((history.phone!=null && history.phone!='')? history.phone : 'N/A')+'</td>');
            $('#historytblBody').append('<td>'+((history.address!=null && history.address!='')? history.address : 'N/A')+'</td>');
            $('#historytblBody').append('<td>'+((history.city!=null && history.city!='')? history.city : 'N/A')+'</td>');
            $('#historytblBody').append('<td>'+((history.proposal_shared_to!=null && history.proposal_shared_to!='')? history.proposal_shared_to : 'N/A')+'</td>');
            $('#historytblBody').append('<td>'+((history.potential_order_value_per_month!=null && history.potential_order_value_per_month!='') ? history.potential_order_value_per_month : 'N/A')+'</td>');
            $('#historytblBody').append('<td>'+((history.potential_number!=null && history.potential_number!='') ? history.potential_number : 'N/A')+'</td>');

            if(history.file_name!=null && history.file_name!='') {
              $('#historytblBody').append('<td><a class="btn btn-success mr-1 mb-1" href="<?= base_url('documents/');?>'+history.file_name+'" target="_blank"><i class="fa fa-download"></i></a></td>');
            } else {
              $('#historytblBody').append('<td>N/A</td>');
            }
            $('#historytblBody').append('<td>'+moment(history.created_at).format('YYYY-MM-DD HH:mm:ss')+'</td>');
            $('#historytblBody').append('</tr>');
          });
        } else {
            $('#historytblBody').append('<br><h3><strong>No data available</strong></h3><br>');
        }
      },
      error: function(jqXHR, textStatus, error) {
        $('#historytblBody').append('<h3><strong>Could not fetch data, please try again</strong></h3>');
      }
    });

  }

</script>

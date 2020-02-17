<!-- Bootstrap modal -->
<div class="modal fade" id="commercial_list_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Commercial Details</h3>
            </div>
            <div class="modal-body ">
                  <div class="row">
                    <div class="col-md-12">
                      <table id='commercial-list-table' class="table">

                      </table>
                    </div>
                  </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

    <script type="text/javascript">
        function showCommercialModal(customer_id) {
          $('#commercial_list_modal').modal('show');
          $('#commercial-list-table').html('');

          getCommercials(customer_id);

        }
        function getCommercials(customer_id) {
          $.ajax({
            'url' : '<?= base_url("/CommercialVerificationController/getCommercialsByCustomerID/"); ?>'+customer_id,
            type: "GET",
          }).done(function(response) {
            let data = JSON.parse(response);
            //let data = JSON;
            if(data.status) {
              console.log(data);
                let commercial_details = data.data;
                $('#commercial-list-table').append('<tr><td>Type</td><td>Value</td><td>Fee Type</td><td>Remarks</td></tr>');
              //  alert(commercial_details);
                $.each(commercial_details, function(index, value){
                //  console.log(value);
                  let commercial = value;
                  $('#commercial-list-table').append('<tr><td>'+humanize(commercial.title)+'</td><td>'+commercial.value+'</td><td>'+((commercial.fee_type==0) ? 'Percentage' : 'Flat')+'</td><td>'+((commercial.fee_type==0) ? (commercial.option_remarks || 'N/A') : (commercial.remarks || 'N/A'))+'</td></tr>');
                });
            } else {
              $('#commercial-list-table').append('No Commercial Information Found');
            }

          }).fail(function (response, text) {
            alert('error');
            $('#commercial-list-table').append('Something went wrong, try again after sometime');
          });
        }

        function humanize(text) {
          return text.replace(/_/g, ' ').toUpperCase();
        }
    </script>
  </div><!-- /.modal-dialog -->

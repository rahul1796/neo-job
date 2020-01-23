<!-- Bootstrap modal -->
<div class="modal fade" id="spoc_list_modal" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Spoc List & Details</h3>
            </div>
            <div class="modal-body ">
                  <div class="row">
                    <div class="col-md-12">
                      <table id='spoc-list-table' class="table">

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
        function showAdditionalSpocs(job_id) {
          $('#spoc_list_modal').modal('show');
          $('#spoc-list-table').html('');

          getSpocs(job_id);

        }
        function getSpocs(job_id) {
          $.ajax({
            'url' : '<?= base_url("/salescontroller/getSpocsByCustomerID/"); ?>'+job_id,
            type: "GET",
          }).done(function(response) {
            //console.log(response);
            let data = JSON.parse(response);
            //let data = JSON;
            if(data.status) {
                let spoc_details = data.data;
                $('#spoc-list-table').append('<tr><td>Name</td><td>Email</td><td>Phone</td><td>Designation</td></tr>');
              //  alert(spoc_details);
                $.each(spoc_details, function(index, value){
                //  console.log(value);
                  let spoc = value;
                  $('#spoc-list-table').append('<tr><td>'+(spoc.spoc_name || 'N/A')+'</td><td>'+(spoc.spoc_email || 'N/A')+'</td><td>'+(spoc.spoc_phone || 'N/A')+'</td><td>'+(spoc.spoc_designation || 'N/A')+'</td></tr>');
                });
            } else {
              $('#spoc-list-table').append('No Spoc Information Found');
            }

          }).fail(function (response, text) {
            alert('error');
            $('#spoc-list-table').append('Something went wrong, try again after sometime');
          });
        }
    </script>
  </div><!-- /.modal-dialog -->

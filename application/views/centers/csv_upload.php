

<script type="text/javascript">
function bulk_upload()
{

    //$('#form_upload_batch')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    //$("#txtCandidateBulkUploadStatus").val('');
    $('#batchBulkUploadStatus').html('');
    $('#batch_error').html('');
    $('#modal_form_upload').modal(); // show bootstrap modal
}
function download_center_template()
{
  var url = base_url+"partner/download_center_template/";
  window.location.href = url;
}

</script>


<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form_upload" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Center Upload</h3>
            </div>
            <div class="modal-body form">
                <div id="msgDisplay"></div>
                <form class="" id="batch_form" action="index.html" method="post">
                  <div class="row">
                    <div class="col-md-12">
                      <label for="file_name" class="label">Upload CSV:</label>
                      <span class="text text-danger">CSV file only</span>
                      <input type="file" class="form-control" id="file_name"name="file_name" value="">
                      <span id="batch_error" class="text-danger"></span>
                    </div>
                  </div>
                </form>

                <div class="form-group row">
                     <label for="txtStatus" class="label-control col-md-3">Upload Status</label>
                     <div class="col-md-9" style="margin-bottom: 10px;">
                         <span id="batchBulkUploadStatus" class="text text-danger"></span>
                     </div>
                 </div>
                 <label style="color: red;">*Note Upload only 1000 Center data.</label>

            </div>
            <div class="modal-footer">
                <button class='btn btn-warning' href='javascript:void(0);' onclick="download_center_template();" style="float: left;" id="download_center_template">Download Template</button>
                    <button class="btn btn-primary" id="batch_upload" onclick="upload_batch();" name=""> Upload</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal" >Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
    <script type="text/javascript">
      function upload_batch() {
        let fd = new FormData(document.getElementById("batch_form"));
        document.getElementById("batch_form").reset();
        $.ajax({
          url : '<?= base_url('centerscontroller/uploadCSV/')?>',
          method: 'POST',
          data: fd,
          processData: false,  // tell jQuery not to process the data
          contentType: false,
        }).done(function(response) {

          let data = JSON.parse(response);
          if(Object.keys(data.errors).length > 0) {
            $('#batch_error').html(data.errors.file_name);
          } else {
            $.each(data.data, function(index, value) {
              console.log(value);
              if(value.status==false) {
                $('#batchBulkUploadStatus').append('Error Uploading Row : '+value.row_number+' - Duplicate Entry<br>');
              }
            });
            $('#batch_error').removeClass('text-danger').addClass('text-success').html(data.message);
          }
        }).fail(function(response, text) {
          $('#batch_error').removeClass('text-danger').addClass('text-success').html("Not able to connect to server please Try again later");
        });
      }
    </script>
</div><!-- /.modal -->

<link rel="stylesheet" type="text/css" href="<?php echo base_url('adm-assets/tab.css')?>">
<style>
 /* .active{
    background: #fff !important;
  }*/
</style>
<div class="content-body" style="padding: 0px 10px 60px 10px;">

  <?php $this->load->view('employments/list', $data); ?>

  <div class="w3-container">
    <h2>Add Employment</h2>
    <div class=" breadcrumbs-top col-md-10 col-xs-12" style="margin-bottom: 10px;margin-left: -25px;">
      <div class="breadcrumb-wrapper col-xs-12">
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><?php echo anchor("candidatescontroller/show/".$data['fields']['candidate_id'],"Candidate Profile");?></li>
          <li class="breadcrumb-item active">Add Employment</li>
        </ol>
      </div>
    </div>
  </div>

  <div id="Personal" class="w3-container info" style="background: white;padding: 25px; ">
    <!--<h2>Personal Info</h2>-->
    <form action="<?php echo base_url('employmentscontroller/store/').$data['fields']['candidate_id'];?>" enctype="multipart/form-data" method="POST">
      <?php $this->load->view('employments/form_fields', $data); ?>
    </form>
  </div>
</div>



<!-- //take modal from here -->


<div class="modal fade" id="candidate_modal_form_upload" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span>&times;</span></button>
                <h3 class="modal-title">Self Employment Document Upload</h3>
            </div>

            <div class="modal-body form">
                <div id="msgDisplay"></div>

                    <div class="form-body">
                      <form  id="form_upload_emp_doc" method="POST" class="form-horizontal" enctype="multipart/form-data">
                         <div class="form-group row">
                           <div class="col-md-12">
                              <label class="label-control" style="margin-top: 10px;">Select File <span class="text text-danger">(.pdf, .png, .jpeg, .doc, .docx) files are allowed</span> </label>
                           </div>

                            <div class="col-md-12">
                                <input type="file" name="file_name" class="form-control" id="candidate_list" >
                                <input type="hidden" name="employment_id" value="" id="upload_modal_employment_id">
                                <span id="candidate_error" class=" text-danger"></span>
                            </div>
                        </div>
                        <div style="clear: both;"></div>

                       <div class="form-group row">
                         <div class="col-md-12">
                          <label for="txtStatus" class="label-control">Upload Status</label>
                         </div>
                            <div class="col-md-12" style="margin-bottom: 10px;">
                                <span id="txtCandidateBulkUploadStatus" class="text text-danger"></span>
                            </div>
                        </div>
                          </form>
                    </div>
            </div>


            <div class="modal-footer">
              <div class="form-group row">
                <div class="col-md-12">
                    <button type="button" id="btnSave" onclick="upload_candidate();" class="btn btn-primary">Upload Doc</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal" onclick="location.reload();"><span>Close</span></button>
                </div>

              </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<script type="text/javascript">

    function upload_candidate() {
      console.log(document.querySelector("#form_upload_emp_doc"));
      let fd = new FormData(document.getElementById("form_upload_emp_doc"));
      document.getElementById("form_upload_emp_doc").reset();
      $.ajax({
        url : '<?= base_url('employmentscontroller/uploadDocument/')?>',
        method: 'POST',
        data: fd,
        processData: false,  // tell jQuery not to process the data
        contentType: false,
      }).done(function(response) {
        console.log(response);
        let data = JSON.parse(response);

        if(Object.keys(data.errors).length > 0) {
          $('#candidate_error').html(data.errors.file_name);
        } else {
            if(data.status==true) {
              $('#txtCandidateBulkUploadStatus').append('File Uploaded Successfully, Kindly refresh the page');
              $('#txtCandidateBulkUploadStatus').removeClass('text-danger').addClass('text-success')

            } else {
              $('#txtCandidateBulkUploadStatus').append('Error Uploading File Successfully');
              $('#txtCandidateBulkUploadStatus').addClass('text-danger').removeClass('text-success')
            }
            $('#candidate_error').html('');
        }
      }).fail(function(response, text) {
        $('#candidate_error').removeClass('text-danger').addClass('text-success').html("Not able to connect to server please Try again later");
      });
    }

</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" type="text/javascript"></script>
<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<style>

    ul.tabs{
        margin: 0px;
        padding: 0px;
        list-style: none;
    }
    ul.tabs li{
        background: none;
        color: #222;
        display: inline-block;
        padding: 10px 15px;
        cursor: pointer;
    }

    ul.tabs li.current{
        background: #afadad;
        color: #222;
        font-weight: 700;
    }

    .tab-content{
        display: none;
        background: #afadad;
        padding: 15px;
    }

    .tab-content.current{
        display: inherit;
    }
   .emp-profile{
        padding: 3%;
        /*margin-top: 3%;*/
        margin-bottom: 3%;
        border-radius: 0.5rem;
        background: #fff;
    }
    .profile-img{
        text-align: center;
    }
    .profile-img img{
        width: 50%;
        height: 145px;
        border-radius: 100%;
        border: 1px solid lightgray;
    }
    .profile-img .file {
        position: relative;
        overflow: hidden;
        margin-top: -20%;
        width: 70%;
        border: none;
        border-radius: 0;
        font-size: 15px;
        background: #212529b8;
    }
    .profile-img .file input {
        position: absolute;
        opacity: 0;
        right: 0;
        top: 0;
    }
    .profile-head h5{
        color: #333;
    }
    .profile-head h6{
        color: #0062cc;
    }
    .profile-edit-btn{
        border: none;
        border-radius: 1.5rem;
        width: 70%;
        padding: 2%;
        font-weight: 600;
        color: #6c757d;
        cursor: pointer;
    }
    .proile-rating{
        font-size: 12px;
        color: #818182;
        margin-top: 5%;
    }
    .proile-rating span{
        color: #495057;
        font-size: 13px;
       /* font-weight: 600;*/
    }
    .profile-head .nav-tabs{
        margin-bottom:5%;
    }
    .profile-head .nav-tabs .nav-link{
        font-weight:600;
        border: none;
    }
    .profile-head .nav-tabs .nav-link.active{
        border: none;
        border-bottom:2px solid #0062cc;
    }
    .profile-work{
        padding: 10%;
        margin-top: -15%;
    }
    .profile-work p{
        font-size: 12px;
        color: #818182;
        font-weight: 600;
        margin-top: 10%;
    }
    .profile-work a{
        text-decoration: none;
        color: #495057;
        font-weight: 600;
        font-size: 14px;
    }
    .profile-work ul{
        list-style: none;
    }
    .profile-tab label{
        font-weight: 600;
    }
    .profile-tab p{
        font-weight: 600;
        color: #0062cc;
    }
</style>

<div class="content-body">
    <div class=" breadcrumbs-top col-md-8 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("partner/candidates","Candidate List");?></a>
                </li>
                <li class="breadcrumb-item active">Candidate Profile
                </li>
            </ol>
        </div>
    </div>

    <section id="description" class="card" style="border: hidden;">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-sm-3">
                        <div class="page_display_log pull-left" style=" color: green"></div>
                    </div>
                    <div class="card-block">
                        <div class="container emp-profile">
                            <form method="post">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="profile-img">
                                            <img src="<?php echo base_url('adm-assets/images/portrait/small/blank_avatar.png'); ?>" alt=""/>

                                        </div>
                                        <div class="profile-work" style="margin-left: 45px;">
                                            <p><span style="font-size: x-small;">Created On: <?php echo $candidate_details['created_at'] ?? 'N/A'; ?></span></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="profile-head">
                                                <h3><b><?php echo $candidate_details['candidate_name']?? 'N/A'; ?></b></h3>
                                            <h6><?php echo $candidate_details['industry_name']; ?></h6>
                                            <p class="proile-rating"><i class="fa fa-map-marker"></i> Location: <span><?php echo $candidate_details['address'] ?? 'N/A'; ?></span></p>
                                            <p class="proile-rating"><i class="fa fa-envelope"></i> Email: <span><?php echo $candidate_details['email']?? 'N/A'; ?></span></p>
                                            <p class="proile-rating"><i class="fa fa-phone"></i> Mobile: <span><?php echo $candidate_details['mobile']?? 'N/A'; ?></span></p>
                                        </div>
                                    </div>
                                  <!--  <div class="col-md-3">
                                        <p class="proile-rating"><i class="fa fa-wrench"></i> <span><?php /*echo $candidate_details['candidate_name']; */?></span></p>
                                        <p class="proile-rating"><i class="fa fa-inr"></i> <span>6.50( Lac)</span></p>
                                    </div>-->
                                    <div class="col-md-2">
                                           <?php if (in_array($user['user_group_id'], candidate_add_roles())): ?>
                                            <a class="btn btn-warning mr-1 mb-1" href="<?= base_url('candidate/edit/').$candidate_details['id'] ?> ">Edit Profile</a>
                                          <?php endif; ?>

                                        <!--<input type="submit" class="btn btn-success btn-min-width mr-1 mb-1" name="btnEdit" value="Edit Profile" href="<?/*= base_url('candidate/edit/') */?>" />-->
                                    </div>
                                </div>
                                <div class="row">
                                    <!--<div class="col-md-4">
                                        <div class="profile-work">

                                        </div>
                                    </div>
                                    <br>-->
                                    <div class="col-md-12">
                                        <div class="container">
                                            <ul class="tabs">
                                                <li class="tab-link <?= (!isset($_GET['type']) || $_GET['type']=='')? 'current' : ''?>" data-tab="tab-1">Education</li>
                                                <li class="tab-link <?= (isset($_GET['type']) && $_GET['type']=='employment')? 'current' : ''?>" data-tab="tab-2">Employment</li>
                                                <li class="tab-link <?= (isset($_GET['type']) && $_GET['type']=='skill')? 'current' : ''?>" data-tab="tab-3">Skill</li>
                                                <li class="tab-link <?= (isset($_GET['type']) && $_GET['type']=='qp')? 'current' : ''?>" data-tab="tab-4">QP</li>
                                                <li class="tab-link <?= (isset($_GET['type']) && $_GET['type']=='doc')? 'current' : ''?>" data-tab="tab-5">Upload</li>
                                            </ul>

                                            <div id="tab-1" class="tab-content <?= (!isset($_GET['type']) || $_GET['type']=='')? 'current' : ''?>">
                                                <?php $this->load->view('educations/list', $data); ?>
                                            </div>
                                            <div id="tab-2" class="tab-content <?= (isset($_GET['type']) && $_GET['type']=='employment')? 'current' : ''?>">
                                                <?php $this->load->view('employments/list', $data); ?>
                                            </div>
                                            <div id="tab-3" class="tab-content <?= (isset($_GET['type']) && $_GET['type']=='skill')? 'current' : ''?>">
                                                <?php $this->load->view('skills/list', $data); ?>
                                            </div>
                                            <div id="tab-4" class="tab-content <?= (isset($_GET['type']) && $_GET['type']=='qp')? 'current' : ''?>">
                                                <?php $this->load->view('qualification_packs/list', $data); ?>
                                            </div>
                                            <div id="tab-5" class="tab-content <?= (isset($_GET['type']) && $_GET['type']=='doc')? 'current' : ''?>">
                                                <?php $this->load->view('documents/list', $data); ?>
                                            </div>

                                        </div><!-- container -->
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js" type="text/javascript"></script>
<script>
    $(document).ready(function(){

        $('ul.tabs li').click(function(){
            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#"+tab_id).addClass('current');
        })

    })

</script>



<div class="modal fade" id="candidate_modal_form_upload" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();"><span>&times;</span></button>
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

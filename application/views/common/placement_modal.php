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
<script>
    //Use the id of the form instead of #change
    $('.status_selector').on('change', function() {
        var selector_id = $(this).find(':selected').val();
        //console.log(selector_id);
        //console.log($(this).parent().find().next('#applied_candidate_id'));
        var candidate_id = $(this).find(':selected').attr('data-candidate');
        var job_id = $(this).find(':selected').attr('data-job');
        if(selector_id==15)
        {
            $.ajax({
               type: "Post",
               url: '<?=base_url()?>' + "CandidatesController/getJoiningDetails",
               data:{
                'candidate_id': candidate_id,
                'job_id': job_id
             },
             async:false
            }).done(function (data){
                //console.log(data);
                let response = JSON.parse(data);
                console.log(response);
                if(response.status){
                    $('#offerletterjoindate').val(response.data.offer_letter_date_of_join);
                    $('#hidOfferLetterFileName').val(response.data.offer_letter_file);
                    $('#hidPlacementId').val(response.data.id);
                    $('#lnkOfferLetter').attr('href','<?= base_url('uploads/candidate/offer_letters/')?>'+response.data.offer_letter_file)
                    $('#offered_remarks').val(response.data.offered_remarks);
                    
                    //console.log(response.data.offer_letter_date_of_join);
                }
            })
            $("#joined_modal").modal("show");
            $('#joined_modal').on('hidden.bs.modal', function () {
                $('#joined_modal form')[0].reset();
            });
            $('#hidCandidateId').val(candidate_id);
            $('#hidJobId').val(job_id);           
            $('#txtCandidateName').val($('#candidate_name_'+candidate_id).text());
            $('#txtCustomerName').val($('#job_customer_name').text());
            $('#txtJobTitle').val($('#job_title').text());
          //  console.log('hello');
        }       
       
    });


    $(document).on('submit', '#frmJoiningDetails', function(event){
        event.preventDefault();
        $("#lblEmploymentTypeError").hide();
//        $("#lblEmployerNameError").hide();
        $("#lblEmployerPhone").hide();
        $("#lblEmployerLocation").hide();
        $("#lblPlacementLocation").hide();
        $("#lblCtc").hide();
        $("#lblDateofjoin").hide();
//        $("#lblOfferDateofjoin").hide();
//        $("#lblFileUpload").hide();

        var varReturnValue = true, varFocus = false;

        if($("#employment_type").val() == null || $("#employment_type").val()==undefined || parseInt($("#employment_type").val()) < 1)
        {
            $("#lblEmploymentTypeError").show();
            if (!varFocus)
            {
                $("#employment_type").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }
//        if($("#employer_name").val().trim() == '')
//        {
//            $("#lblEmployerNameError").show();
//            if (!varFocus)
//            {
//                $("#employer_name").focus();
//                varFocus=true;
//            }
//            varReturnValue=false;
//        }
        if($("#employer_contact_phone").val().trim() == '')
        {
            $("#lblEmployerPhone").show();
            if (!varFocus)
            {
                $("#employer_contact_phone").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if($("#employer_location").val().trim() == '')
        {
            $("#lblEmployerLocation").show();
            if (!varFocus)
            {
                $("#employer_location").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if($("#placement_location").val().trim() == '')
        {
            $("#lblPlacementLocation").show();
            if (!varFocus)
            {
                $("#placement_location").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if($("#ctc").val().trim() == '')
        {
            $("#lblCtc").show();
            if (!varFocus)
            {
                $("#ctc").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if($("#dateofjoin").val().trim() == '')
        {
            $("#lblDateofjoin").show();
            if (!varFocus)
            {
                $("#dateofjoin").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

//        if($("#offerletterjoindate").val().trim() == '')
//        {
//            $("#lblOfferDateofjoin").show();
//            if (!varFocus)
//            {
//                $("#offerletterjoindate").focus();
//                varFocus=true;
//            }
//            varReturnValue=false;
//        }
//
//        if($("#offerletterupload").val().trim() == '')
//        {
//            $("#lblFileUpload").show();
//            if (!varFocus)
//            {
//                $("#offerletterupload").focus();
//                varFocus=true;
//            }
//            varReturnValue=false;
//        }

        if (!varReturnValue) return;

        $.ajax({
            url: base_url + "employer/update_placement_joining_status",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result)
            {
                //$("#txtStatus").val($("#txtStatus").val() + result);
                //var textarea = document.getElementById('txtStatus');
                //textarea.value += "\n";
                //textarea.scrollTop = textarea.scrollHeight;
                var varResult = JSON.parse(result);
                swal({
                        title: "",
                        text: varResult.msg_info,
                        confirmButtonColor: "#5cb85c",
                        confirmButtonText: 'OK',
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (confirmed) {
                        $('#joined_modal').modal('hide');
                    });
                //$('#joined_modal').modal('hide');
            },
            error: function () {
                alert("Please update Offered Status");
            }
        });
    });
    
    $(document).ready(function () {
    $('#dateofjoin').datepicker({
        format: "dd-M-yyyy",
        autoclose: true
    });

    
});
</script>
<script>
    //Use the id of the form instead of #change
    $('.status_selector').on('change', function() {
        
        var selector_id = $(this).find(':selected').val();
        //console.log(selector_id);
        //console.log($(this).parent().find().next('#applied_candidate_id'));
        var candidate_id = $(this).find(':selected').attr('data-candidate');
        var job_id = $(this).find(':selected').attr('data-job');
        
        if(selector_id==12)
        {
            $("#offered_modal").modal("show");
            $('#offered_modal').on('hidden.bs.modal', function () {
                $('#offered_modal form')[0].reset();
            });
            $('#hidCandidateId1').val(candidate_id);
            $('#hidJobId1').val(job_id);
            $('#txtCandidateName1').val($('#candidate_name_'+candidate_id).text());
            $('#txtCustomerName').val($('#job_customer_name').text());
            $('#txtJobTitle').val($('#job_title').text());


          //  console.log('hello');
        }
    });


    $(document).on('submit', '#frmofferedDetails', function(event){      
        event.preventDefault();       
        $("#lblOfferDateofjoin1").hide();
        $("#lblofferedremarks1").hide();
        $("#lblFileUpload1").hide();

        var varReturnValue = true, varFocus = false;
        if($("#offerletterjoindate1").val().trim() == '')
        {
            $("#lblOfferDateofjoin1").show();
            if (!varFocus)
            {
                $("#offerletterjoindate1").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }
        
        if($("#offered_remarks1").val().trim() == '')
        {
            $("#lblofferedremarks1").show();
            if (!varFocus)
            {
                $("#offered_remarks1").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }
        
        
        if($("#offered_ctc").val().trim() == '')
        {
            $("#lblofferedctc").show();
            if (!varFocus)
            {
                $("#offered_ctc").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }
        
        if($("#offerletterupload1").val().trim() == '')
        {
            $("#lblFileUpload1").show();
            if (!varFocus)
            {
                $("#offerletterupload1").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        
        if ($("#offerletterupload1").val().trim() != '')
        {
            var varExtensions = ["JPG", "JPEG", "PNG", "PDF", "DOC", "DOCX"];
            var fileName = $("#offerletterupload1")[0].files[0].name;
            var fileExtension = fileName.split(/[. ]+/).pop();
            if (varExtensions.indexOf(fileExtension.toUpperCase()) < 0) {
                $("#lblFileUpload1").text('* Please select only DOC, DOCX, PDF, JPG, JPEG or PNG formats');
                $("#lblFileUpload1").show();
                if (!varFocus)
                {
                    $("#offerletterupload1").focus();
                    varFocus = true;
                }
                varReturnValue = false;
            }
        }
        
         if (!varReturnValue) return;
        $.ajax({
            url: base_url + "employer/update_offered_status",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function (result)
            {
                //$("#txtStatus").val($("#txtStatus").val() + result);
                //var textarea = document.getElementById('txtStatus');
                //textarea.value += "\n";
                //textarea.scrollTop = textarea.scrollHeight;
                var varResult = JSON.parse(result);
                swal({
                        title: "",
                        text: varResult.msg_info,
                        confirmButtonColor: "#5cb85c",
                        confirmButtonText: 'OK',
                        closeOnConfirm: true,
                        closeOnCancel: true
                    },
                    function (confirmed) {
                        $('#offered_modal').modal('hide');
                    });
                //$('#joined_modal').modal('hide');
            },
            error: function () {
                alert("Error Occurred");
            }
        });
    });

</script>


    <div id="joined_modal" class="modal fade bs-example-modal-lg" role="dialog">
        <form  id="frmJoiningDetails" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 id="hdrPopupTitle" class="modal-title">Candidate Joining Detail</h3>
                </div>
                <div class="modal-body">

                    <div class="form-group" style="display:block;">
                        <input name="candidateid" id="hidCandidateId" type="hidden" value="">
                        <input name="jobid" id="hidJobId" type="hidden" value="">
                        <input name="placementid" id="hidPlacementId" type="hidden" value="">
<!--                        <input name="OfferLetterFile" id="hidOfferLetterFileName" type="hidden" value="">-->
                        <label for="txtCandidateName" class="col-sm-2 control-label" style="margin-top: 5px;">Candidate Name</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtCandidateName" name="txtCandidateName" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>
                     <div style="clear: both;"></div>
<!--                    <div class="form-group" style="display:block;">
                        <label for="txtCustomerName" class="col-sm-2 control-label" style="margin-top: 5px;">Customer Name</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="customer_name"  name="customer_name" value="<? //= $job->customer_name ?? 'N/A' ; ?>" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>-->
                  
                       <div class="form-group" style="display:block;">
                        <label for="txtJobTitle" class="col-sm-2 control-label" style="margin-top: 5px;">Job Title</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                           <input type="text" class="form-control" id="job_title"  name="job_title" value="<?= $job->job_title ?? 'N/A'; ?>" onkeydown="return false;" readonly/>
                        </div>
                    </div>



                    <div class="form-group row" style="margin-top: 20px;">
                        <div class="col-md-3">
                           <label for="employer_name" class="">Customer Name:</label>
                           <input type="text" class="form-control" id="customer_name"  name="customer_name" value="<?= $job->customer_name ?? 'N/A' ; ?>" onkeydown="return false;" readonly/>
                           <label id="lblEmployerNameError" style="color:red;display: none;">* Please Enter Employer Name</label>
                        </div>
                        <div class="col-md-3">
                            <label for="employment_type" class="label">Employment Type:</label>
                            <!--  <input name="hidEmploymentId" id="hidEmploymentId" type="hidden" value="">-->
                            <select class="form-control" id="employment_type" name="employment_type" >
                                <option value="0">-Select Employment Type-</option>
                                <?php
                                foreach ($employer_type_list as $row)
                                    echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                                ?>
                            </select>
                            <label id="lblEmploymentTypeError" style="color:red;display: none;">* Select Employment Type!</label>
                        </div>
                        <div class="col-md-3">
                            <label for="employer_contact_phone" class="label">Employer Phone:</label>
                            <input type="text" class="form-control" id="employer_contact_phone" placeholder="Enter Employer Phone" name="employer_contact_phone" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "10" value="">
                            <label id="lblEmployerPhone" style="color:red;display: none;">* Please Enter Employer Phone</label>
                        </div>

                        <div class="col-md-3">
                            <label for="employer_location" class="label">Employer Location:</label>
                            <input type="text" class="form-control" id="employer_location" placeholder="Enter Employer Location" name="employer_location" value="">
                            <label id="lblEmployerLocation" style="color:red;display: none;">* Please Enter Employer Location</label>
                        </div>

                    </div>

                    <div style="clear: both;"></div>

                    <div class="form-group row" style="margin-top: 20px;">
                        <div class="col-md-3">
                            <label for="placement_location" class="label">Placement Location:</label>
                            <input type="placement_location" class="form-control" id="placement_location" placeholder="Enter Placement Location" name="placement_location" value="">
                            <label id="lblPlacementLocation" style="color:red;display: none;">* Please Enter Placement Location</label>
                        </div>

                        <div class="col-md-3">
                            <label for="ctc" class="label">Annual CTC:</label>
                            <input type="text"  class="form-control" id="ctc" placeholder="Enter CTC" name="ctc" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "8" value="">
                            <label id="lblCtc" style="color:red;display: none;">* Please Enter CTC</label>
                        </div>

                        <div class="col-md-3">
                            <label for="dateofjoin" class="label">Date of Join:</label>
                            <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="dateofjoin" placeholder="Enter DOJ" name="dateofjoin" value="">
                            <label id="lblDateofjoin" style="color:red;display: none;">* Please Select Date of Join</label>
                        </div>
                        <div class="col-md-3">
                            <label for="offerletterjoindate" class="">Offer Letter Date of Join:</label>
                            <input type="text" class="form-control" id="offerletterjoindate" placeholder="Enter Offer Letter Date" name="offerletterjoindate" value="" readonly>
                            <label id="lblOfferDateofjoin" style="color:red;display: none;">* Please Select Offer Letter Date of Join</label>
                        </div>
                    </div>

                    <div style="clear: both;"></div>
                    <div class="form-group row" style="margin-top: 20px;">
                        <div class="col-md-6">
                            <label for="OfferLetterFile">Offer Letter:</label>
                            <input class="form-control" name="OfferLetterFile" id="hidOfferLetterFileName" type="text" value="" onkeydown="return false;" disabled="disabled">
                            <a  target="_blank" id="lnkOfferLetter" class="btn btn-primary btn-sm" title="Download Offer Letter" style="margin-right: 4px; float: right; margin-top: -29px;"><i class="fa fa-download"></i></a>
                        </div>
                        <div class="col-md-6">
                            <label for="file_upload" class="">Update Offer Letter:</label>
                             <div class="form-group files">
                                <input type="file" id="offerletterupload" name="offer_letters" class="form-control" accept=".png, .jpg, .jpeg, .pdf, .doc, .docx">
                            </div>
                            <label id="lblFileUpload" style="color:red;display: none;">* Please Select the file</label>
                        </div>
                    </div>
                     <div style="clear: both;"></div>
                    <div class="form-group row">
                        <div class="col-md-12">
                            <label for="offered_remarks" class="">Remarks:</label>
                            <input type="text"  class="form-control" id="offered_remarks" placeholder="Enter Offered Remarks" name="offered_remarks" value="" readonly>
                            <label id="lblofferedremarks" style="color:red;display: none;">* Please Enter Offered Remarks </label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button id="btnSave" type="submit" class="btn btn-success">Save</button>
                    <button id="btnCancel" class="btn btn-danger" data-dismiss="modal" onclick="javascript:window.location.reload()" >Cancel</button>
                </div>
            </div>
        </div>
        </form>
    </div>


<div id="offered_modal" class="modal fade bs-example-modal-lg" role="dialog">
        <form  id="frmofferedDetails" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 id="hdrPopupTitle" class="modal-title">Candidate Offered Detail</h3>
                </div>
                <div class="modal-body">

                    <div class="form-group" style="display:block;">
                        <input name="candidateid" id="hidCandidateId1" type="hidden" value="">
                        <input name="jobid" id="hidJobId1" type="hidden" value="">                        
                        <label for="txtCandidateName" class="col-sm-2 control-label" style="margin-top: 5px;">Candidate Name</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtCandidateName1" name="txtCandidateName" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>
                    
                    <div class="form-group" style="display:block;">
                        <label for="txtCustomerName" class="col-sm-2 control-label" style="margin-top: 5px;">Customer Name</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="customer_name"  name="customer_name" value="<?= $job->customer_name ?? 'N/A' ; ?>" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>
                    <div class="form-group" style="display:block;">
                        <label for="txtJobTitle" class="col-sm-2 control-label" style="margin-top: 5px;">Job Title</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="job_title"  name="job_title" value="<?= $job->job_title ?? 'N/A'; ?>" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>

                        <div class="form-group row" >
                            <div class="col-md-4">
                                <label for="offerletterjoindate1" class="label">Offer Letter Date Of Join:</label>
                                <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy"  class="form-control" id="offerletterjoindate1" placeholder="Enter Offer Letter Date of Join" name="offerletterjoindate" value="">
                                <label id="lblOfferDateofjoin1" style="color:red;display: none;">* Please Select Offer Letter Date of Join </label>
                            </div>
                            <div class="col-md-4">
                                <label for="offered_remarks1" class="label">Offered Remarks:</label>
                                <input type="text"  class="form-control" id="offered_remarks1" placeholder="Enter Offered Remarks" name="offered_remarks1" value="">
                                <label id="lblofferedremarks1" style="color:red;display: none;">* Please Enter Offered Remarks </label>
                            </div>
                             <div class="col-md-4">
                            <label for="ctc" class="label">Offered CTC Per Month:</label>
                            <input type="text"  class="form-control" id="offered_ctc" placeholder="Enter Offered CTC Per Month" name="offered_ctc" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "8" value="">
                            <label id="lblofferedctc" style="color:red;display: none;">* Please Enter Offered CTC Per Month</label>
                          </div>
                        </div>
                         <div class="form-group row" >
                              <div class="col-md-6">
                                <label for="file_upload" class="label">Offer Letter Upload:</label>
                                 <div class="form-group files">
                                    <input type="file" id="offerletterupload1" name="offer_letters" class="form-control" accept=".png, .jpg, .jpeg, .pdf, .doc, .docx">
                                </div>
                                <label id="lblFileUpload1" style="color:red;display: none;">* Please Select the file</label>
                            </div>                             
                        </div>

                </div>

                <div class="modal-footer">
                    <button id="btnSave" type="submit" class="btn btn-success">Save</button>
                    <button id="btnCancel" class="btn btn-danger" data-dismiss="modal" onclick="javascript:window.location.reload()" >Cancel</button>
                </div>
            </div>
        </div>
        </form>
    </div>


<script>
    $(document).ready(function() {
        $('#employment_type').on('change', function () {
            $('#hidEmploymentId').val($('#employment_type').find(":selected").text());
        });
    });
     $('#offerletterjoindate1').on('changeDate', function(ev){
    $(this).datepicker('hide');
});
</script>

<?php $this->load->view('candidates/script');?>
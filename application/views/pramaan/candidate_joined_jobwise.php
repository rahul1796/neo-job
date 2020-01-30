<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/responsive.dataTables.min.css'?>">
<script>
    var varBaseUrl = '<?= base_url() ?>';
</script>
<style type="text/css">
    /**
     * @author  George Martin <george.s@navriti.com>
     * @desc  Candidate List
     * @date  March 2017
    */
    select.input-sm
    {
        line-height: 10px;
    }

    .searchprint
    {
        text-align: right;
    }

    .searchprint .btn-group
    {
        padding-bottom: 5px;
    }
    .table td, .table th {
        padding: 0.75rem 0.75rem;
    }
    .files input {
        outline: 2px dashed #92b0b3;
        outline-offset: -10px;
        -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
        transition: outline-offset .15s ease-in-out, background-color .15s linear;
        padding: 25px 0px 74px 35%;
        text-align: center !important;
        margin: 0;
        width: 100% !important;
    }
    .files input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
        -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
        transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
    }
    .files{ position:relative}
    .files:after {  pointer-events: none;
        position: absolute;
        top: 60px;
        left: 0;
        width: 50px;
        right: 0;
        height: 56px;
        content: "";
        /*background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);*/
        display: block;
        margin: 0 auto;
        background-size: 100%;
        background-repeat: no-repeat;
    }
    .color input{ background-color:#f1f1f1;}
    .files:before {
        position: absolute;
        bottom: 10px;
        left: 0;  pointer-events: none;
        width: 100%;
        right: 0;
        height: 57px;
        content: " or drag it here. ";
        display: block;
        margin: 0 auto;
        color: #2ea591;
        font-weight: 600;
        text-transform: capitalize;
        text-align: center;
    }

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


<div class="content-body" style="overflow-x: hidden !important;">
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("partner/job_board","Job Board");?></a>
                </li>
                <li class="breadcrumb-item active">Joined Candidate List
                </li>
            </ol>
        </div>
    </div>
     <?php $this->load->view('jobs/job_details', ['job_id' => $job_id, 'job'=> $job_details, 'is_filled'=> $is_filled]); ?>
    
    <section id="configuration">
        <div class="row">

            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Joined Candidate List</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">

                            <table id="tblMain" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo.</th>
                                    <th>Action</th>
                                    <th>Status</th>
                                    <th>Candidate Name</th>
                                    <th>Enrollment Number</th>
                                    <th>Job Title</th>
                                    <th>QP</th>
                                    <th>Date of Join</th>
                                    <th>Employer Phone</th>
                                    <th>Employer Location</th>
                                    <th>Employement Type</th>
                                    <th>CTC</th>
                                    <th>Offer Letter Upload Date</th>
                                    <th>Placement Location</th>
                                    <th>Resigned On</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->

</div>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script>
    var varJobId = '<?= $job_id ?>';
	$(document).ready(function() {
    table = $("#tblMain").DataTable({
        "serverSide": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url+"employer/get_jobwise_joined_candidate_data/" + varJobId,
            "type": "POST",
            error: function()
            {
                $("#tblMain tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
            }
        },
        "columnDefs":
            [
                {
                    "targets": [0, 4, 5, -1 ],
                    "orderable": false
                }
            ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        buttons:
            [

            ],
        "order": [[ 1, "asc" ]]
    });

    $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="icon-refresh"></i> Reload</button>').appendTo('div#table_length');
});

function reload_table()
{
    table.ajax.reload(null, false);
}
    
    
    function EditDetails(CandidateId, JobId, CandidateName,CustomerName, JobName,EmployerTypeId, EmployerType,EmployerName, EmployerContactPhone,EmployerLocation,   PlacementLocation, CTC, DateOfJoin, OfferLetterJoiningDate, OfferLetterFile)
    {

        $("#hidCandidateId").val(CandidateId);
        $("#hidJobId").val(JobId);
        $("#txtCandidateName").val(CandidateName);
        $("#txtCustomerName").val(CustomerName);
        $("#employment_type").val(EmployerTypeId);
         $("#employer_name").val(EmployerName);
        $("#employer_contact_phone").val(EmployerContactPhone);
        $("#employer_location").val(EmployerLocation);
        $("#txtJobTitle").val(JobName);
        $("#placement_location").val(PlacementLocation);
        $("#ctc").val(CTC);
        $("#dateofjoin").val(DateOfJoin);
        $("#offerletterjoindate").val(OfferLetterJoiningDate);

        $("#hidOfferLetterFileName").val("Not Uploaded");
        $("#lnkOfferLetter").hide();
        var varOfferLetterUrl = 'Not Uploaded';
        if (OfferLetterFile.trim() != '')
        {
            $("#hidOfferLetterFileName").val(OfferLetterFile);
            $("#lnkOfferLetter").attr("href", base_url + 'uploads/candidate/offer_letters/' + OfferLetterFile);
            $("#lnkOfferLetter").show();
        }

        $("#divJoiningDetails").modal({ show: true });
    }



    $(document).on('submit', '#frmJoiningDetails', function(event){
        event.preventDefault();

        $("#lblEmploymentTypeError").hide();
//        $("#lblEmployerNameError").hide();
        $("#lblEmployerPhone").hide();
        $("#lblEmployerLocation").hide();
        $("#lblPlacementLocation").hide();
        $("#lblCtc").hide();
        $("#lblDateofjoin").hide();
        $("#lblOfferDateofjoin").hide();
        $("#lblFileUpload").hide();

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

        if($("#offerletterjoindate").val().trim() == '')
        {
            $("#lblOfferDateofjoin").show();
            if (!varFocus)
            {
                $("#offerletterjoindate").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if ($("#hidOfferLetterFileName").val().trim() == '')
        {
            if ($("#offerletterupload").val().trim() == '')
            {
                $("#lblFileUpload").text('* Please select a file to upload');
                $("#lblFileUpload").show();
                if (!varFocus)
                {
                    $("#offerletterupload").focus();
                    varFocus = true;
                }
                varReturnValue = false;
            }
        }

        if ($("#offerletterupload").val().trim() != '')
        {
            var varExtensions = ["JPG", "JPEG", "PNG", "PDF", "DOC", "DOCX"];
            var fileName = $("#offerletterupload")[0].files[0].name;
            var fileExtension = fileName.split(/[. ]+/).pop();
            if (varExtensions.indexOf(fileExtension.toUpperCase()) < 0) {
                $("#lblFileUpload").text('* Please select only DOC, DOCX, PDF, JPG, JPEG or PNG formats');
                $("#lblFileUpload").show();
                if (!varFocus)
                {
                    $("#offerletterupload").focus();
                    varFocus = true;
                }
                varReturnValue = false;
            }
        }

        if (!varReturnValue) return;

        $.ajax({
            url: base_url + "employer/update_joining_status",
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
                        window.location.reload(true);
                    });
            },
            error: function () {
                alert("Error Occured");
            }
        });
    });


</script>

</div>

<form  id="frmJoiningDetails" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div id="divJoiningDetails" class="modal fade bs-example-modal-lg" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 id="hdrPopupTitle" class="modal-title">Candidate Joining Detail</h3>
                </div>
                <div class="modal-body">
                    <div class="form-group" style="display:block;">
                        <input name="candidateid" id="hidCandidateId" type="hidden" value="">
                        <input name="jobid" id="hidJobId" type="hidden" value="">
                        <label for="txtCandidateName" class="col-sm-2 control-label" style="margin-top: 5px;">Candidate Name</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtCandidateName" name="txtCandidateName" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>
<!--                    <div class="form-group" style="display:block;">
                        <label for="txtCustomerName" class="col-sm-2 control-label" style="margin-top: 5px;">Customer Name</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtCustomerName" name="txtCustomerName" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>-->
                    <div class="form-group" style="display:block;">
                        <label for="txtJobTitle" class="col-sm-2 control-label" style="margin-top: 5px;">Job Title</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtJobTitle" name="txtJobTitle" value="" onkeydown="return false;" readonly/>
                        </div>
                    </div>

                    <div class="form-group row" style="margin-top: 20px;">
                        <div class="col-md-3">
                           <label for="employer_name" class="label">Company Name:</label>
                           <input type="text" class="form-control" id="txtCustomerName" name="txtCustomerName" value="" onkeydown="return false;" readonly/>
                           <label id="lblEmployerNameError" style="color:red;display: none;">* Please Enter Employer Name</label>
                        </div>
                        <div class="col-md-3">
                            <label for="employment_type" class="label">Employment Type:</label>
<!--                            <input name="hidEmploymentId" id="hidEmploymentId" type="hidden" value="">-->
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
                            <input type="text" class="form-control" id="ctc" placeholder="Enter CTC" name="ctc" min="0" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');"  maxlength = "8" value="">
                            <label id="lblCtc" style="color:red;display: none;">* Please Enter CTC</label>
                        </div>

                        <div class="col-md-3">
                            <label for="dateofjoin" class="label">Date of Join:</label>
                            <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="dateofjoin" placeholder="Enter DOJ" name="dateofjoin" value="">
                            <label id="lblDateofjoin" style="color:red;display: none;">* Please Select Date of Join</label>
                        </div>
                        <div class="col-md-3">
                            <label for="offerletterjoindate" class="label">Offer Letter Date of Join:</label>
                            <input type="text"  class="form-control" id="offerletterjoindate" placeholder="Enter Offer Letter Date" name="offerletterjoindate" value="" readonly>
                            <label id="lblOfferDateofjoin" style="color:red;display: none;">* Please Select Offer Letter Date of Join</label>
                        </div>
                    </div>

                    <div class="form-group row" style="margin-top: 20px;">
                        <div class="col-md-6">
                            <label for="OfferLetterFile">Offer Letter:</label>
                            <input class="form-control" name="OfferLetterFile" id="hidOfferLetterFileName" type="text" value="" onkeydown="return false;" disabled="disabled">
                            <a target="_blank" id="lnkOfferLetter" class="btn btn-primary btn-sm" title="Download Offer Letter" style="margin-right: 4px; float: right; margin-top: -29px;"><i class="fa fa-download"></i></a>
                        </div>
                        <div class="col-md-6">
                            <label for="file_upload" class="label">Offer Letter Upload:</label>
                            <div class="form-group files">
                                <input type="file" id="offerletterupload" name="offer_letters" class="form-control" accept=".png, .jpg, .jpeg, .pdf, .doc, .docx">
                            </div>
                            <label id="lblFileUpload" style="color:red;display: none;">* Please Select the file</label>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="margin-top: 0%;">
                    <button id="btnSave" type="submit" class="btn btn-success">Save</button>
                    <button id="btnCancel" class="btn btn-danger" data-dismiss="modal" onclick="javascript:window.location.reload()" >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>


    function ResignDetails(CandidateId, JobId, CandidateName,CustomerName, JobName, DateOfJoin,ResignedDate, ReasonToLeave)
        {
            $("#hidCandidateId1").val(CandidateId);
            $("#hidJobId1").val(JobId);
            $("#txtCandidateName1").val(CandidateName);
            $("#txtCustomerName1").val(CustomerName);
            $("#txtJobTitle1").val(JobName);
            $("#dateofjoin1").val(DateOfJoin);
            $("#reason_to_leave").val(ReasonToLeave);
            $("#resigned_date").val(ResignedDate);

            $("#divResignDetails").modal({ show: true });
        }
    
    
    $(document).on('submit', '#frmResignedDetails', function(event){
        event.preventDefault();

        $("#lblReasonToLeave").hide();
        $("#lblResignedDate").hide();

        var varReturnValue = true, varFocus = false;

       
        if($("#reason_to_leave").val().trim() == '')
        {
            $("#lblReasonToLeave").show();
            if (!varFocus)
            {
                $("#reason_to_leave").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        if($("#resigned_date").val().trim() == '')
        {
            $("#lblResignedDate").show();
            if (!varFocus)
            {
                $("#resigned_date").focus();
                varFocus=true;
            }
            varReturnValue=false;
        }

        
        if (!varReturnValue) return;

        $.ajax({
            url: base_url + "employer/update_resigned_status",
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
                        window.location.reload(true);
                    });
            },
            error: function () {
                alert("Error Occured");
            }
        });
    });
    
        $(function(){
    $('.datepicker').datepicker({
        format: 'dd-M-yyyy',
        endDate: '+0d',
        autoclose: true
    });
});
</script>

<form  id="frmResignedDetails" method="post" enctype="multipart/form-data" class="form-horizontal">
    <div id="divResignDetails" class="modal fade bs-example-modal-lg" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" >
                <div class="modal-header">
                    <button type="button" onclick="javascript:window.location.reload()" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 id="hdrPopupTitle" class="modal-title">Candidate Resign Detail</h3>
                </div>
                <div class="modal-body" style="height: 445px;">
                    <div class="form-group row" style="margin-top: 20px;">
                        <input name="candidateid" id="hidCandidateId1" type="hidden" value="">
                        <input name="jobid" id="hidJobId1" type="hidden" value="">
                        <div class="col-md-3">
                            <label for="txtCandidateName" class="control-label">Candidate Name:</label>
                            <input type="text" class="form-control" id="txtCandidateName1" name="txtCandidateName" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>

                        <div class="col-md-3">
                            <label for="customer_name" class="control-label">Company Name:</label>
                            <input type="text" class="form-control" id="txtCustomerName1" name="txtCustomerName" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>

                        <div class="col-md-3">
                            <label for="job_title" class="control-label">Job Title</label>
                            <input type="text" class="form-control" id="txtJobTitle1" name="txtJobTitle" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>
                        <div class="col-md-3">
                            <label for="dateofjoin" class="control-label">Date of Join:</label>
                             <input type="text" class="form-control" id="dateofjoin1" name="dateofjoin1" value="" onkeydown="return false;" disabled="disabled"/>                            
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row" style="margin-top: 20px;">                        
                        <div class="col-md-3">
                            <label for="resigned_date" class="label">Resigned Date:</label>
                            <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control datepicker" id="resigned_date" name="resigned_date" placeholder="Enter Resigned Date"  value="" style="height: 35px;">
                            <label id="lblResignedDate" style="color:red;display: none;">* Please Select Resigned Date</label>
                        </div>  
                        <div class="col-md-8">
                            <label for="reason_to_leave" class="label">Reason to Leave:</label>
                            <input type="text" class="form-control" id="reason_to_leave" name="reason_to_leave" placeholder="Enter Reason to Leave"  value="">
                            <label id="lblReasonToLeave" style="color:red;display: none;">* Please Enter Reason to Leave</label>
                        </div>
                     
                    </div>
                    
                <div class="modal-footer" style="margin-top: 0%;">
                    <button id="btnSave1" type="submit" class="btn btn-success">Save</button>
                    <button id="btnCancel1" class="btn btn-danger" data-dismiss="modal" onclick="javascript:window.location.reload()" >Cancel</button>
                </div>
            </div>
        </div>
    </div>
</form>


<script>
    $(document).ready(function() {
        $('#employment_type').on('change', function () {
            $('#hidEmploymentId').val($('#employment_type').find(":selected").text());
        });
    });
</script>


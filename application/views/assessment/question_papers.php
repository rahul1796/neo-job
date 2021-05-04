<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  question papers
 * @date  Nov_2016
*/
.dataTables_filter
{
    text-align: right!important;
}
</style>

<?php
$options_sectors=array(''=>'-Select Sector-');
$options_orgTypes=array(''=>'-Select Org type-');
?>
<div class="content-body" style="overflow-x: hidden !important;">
    <?php
    if (intval($user_role_id) == 1) {
        echo '<div style="float:right;">';
        echo '<a class="btn btn-success btn-min-width mr-1 mb-1" onclick="AddQuestionPaper()" style="color: white;"><i class="icon-android-add"></i> Add Question Paper</a>';
        echo '<a class="btn btn-success btn-min-width mr-1 mb-1" href="' . base_url('content/sample_questions') . '" style="color: white;"><i class="icon-android-add"></i> View Sample Question Paper</a>';
        echo '</div>';
    }
    ?>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-4 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?></a> </li>
                <li class="breadcrumb-item active">Question Papers</li>
            </ol>
        </div>
    </div>
    <section id="configuration" style="margin-top: 15px;">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Question Papers</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m inus4"></i></a></li>
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
                                    <th style="text-align: center; width:10%;">SNo.</th>
                                    <th style="text-align: center; width:25%;">Question Paper Title</th>
                                    <th style="text-align: center; width:10%;">Duration <br>(Minutes)</th>
                                    <th style="text-align: center; width:10%;">Questions</th>
                                    <th style="text-align: center; width:10%;" >Status</th>
                                    <th>Actions </th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->

</div>

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen-bootstrap.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'nist-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>

<form id="frmEntry" method="post" enctype="multipart/form-data" class="form-horizontal" style="padding-top:10px;">
    <input type="hidden" id="hidQuestionPaperId" name="hidQuestionPaperId" value=""/>
    <div id="divAddEditQuestionPaper" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"  style="width:120%;" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 id="hdrPopupTitle" class="modal-title">Question Paper</h3>
                </div>
                <div class="modal-body">
                    <div id="divQuestionPaperTitle" class="form-group" style="display:block;">
                        <label for="txtQuestionPaperTitle" class="col-sm-2 control-label" style="margin-top: 5px;">Question Paper</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtQuestionPaperTitle" name="txtQuestionPaperTitle" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div id="divDuration" class="form-group" style="display:block;">
                        <label for="txtDuration" class="col-sm-2 control-label" style="margin-top: 5px;">Duration (Minutes)</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtDuration" name="txtDuration" value="" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div id="divAnchorDownloadTemplate" class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10" style="margin-bottom: 0px;">
                            <a id="anchorDownloadTemplate" class="btn btn-success" onclick="DownloadQuestionTemplate();" title="Download Candidate Template" style="color:DarkBlue; font-size:small;color:white; font-weight:bold; font-style:italic; padding: 3px;margin-bottom: 2px;">Download Template</a>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div id="divQuestionData" class="form-group">
                        <label for="fileQuestionData" class="col-sm-2 control-label" style="margin-top: 5px;">Question Data File<span class='validmark'>*</span></label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="file" id="fileQuestionData" name="questionexcel" class="form-control" style="cursor: pointer;" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel|Excel"/>
                            <label id="lblFileQuestionDataError" style="color:red; display: none;"></label>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div id="divQuestionImages"  class="form-group">
                        <label for="fileQuestionImages" class="col-sm-2 control-label" style="margin-top: 5px;">Question Images</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="file" id="fileQuestionImages" name="questionimages[]" multiple="multiple" accept="image/*" class="form-control" style="cursor: pointer;"/>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div class="form-group">
                        <label for="txtStatus" class="col-sm-2 control-label">Upload Status</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <textarea id="txtStatus" name="txtStatus" class="form-control" spellcheck="false" onkeydown="return false;" style="height: 130px;"></textarea>
                        </div>
                    </div>

                    <div style="clear: both;"></div>
                </div>

                <div class="modal-footer">
                    <button id="btnSave" class="btn btn-success" type="submit">Upload</button>
                    <button id="btnCancel" class="btn btn-danger" data-dismiss="modal" onclick="reload_table()">Exit</button>
                </div>
            </div>
        </div>
    </div>
    </form>

<div id="divDownloadQuestionPaperTemplate" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"  style="width:120%;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 id="hdrDownloadPopupTitle" class="modal-title">Question Paper</h3>
            </div>
            <div class="modal-body">
                <div class="form-group" style="display:block;">
                    <label for="txtQuestionPaperVersionTitle" class="col-sm-2 control-label" style="margin-top: 5px;">Question Paper</label>
                    <div class="col-sm-10" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" id="txtQuestionPaperVersionTitle" name="txtQuestionPaperVersionTitle" value="" onkeydown="return false;" disabled="disabled"/>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="form-group" style="display:block;">
                    <label for="txtVersionDuration" class="col-sm-2 control-label" style="margin-top: 5px;">Duration (Minutes)</label>
                    <div class="col-sm-10" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" id="txtVersionDuration" name="txtVersionDuration" value="" onkeydown="return false;" disabled="disabled"/>
                    </div>
                </div>

                <div style="clear: both;"></div>

                <div class="form-group" style="display: block;">
                    <input type="hidden" id="hidOperationType" name="hidOperationType" value=""/>
                    <label for="listLanguage" class="col-sm-2 control-label" style="margin-top:5px;">Question Template</label>
                    <div class="col-sm-10" style="margin-bottom: 10px;">
                        <select name="listLanguage" id="listLanguage" required class="form-control" style="cursor: pointer;">
                            <?php
                            echo '<option value="0" selected="selected">Select Language Version</option>';
                            if ($LanguageList) {
                                foreach ($LanguageList AS $Language) {
                                    if ($Language['language_id'] == 1) continue;
                                    echo '<option value="' . $Language['language_id'] . '" >' . $Language['language_name'] . '</option>';
                                }
                            }
                            ?>
                        </select>
                        <label id="lblLanguageError" style="color: red; display: none;"></label>
                    </div>
                </div>

                <div style="clear: both;"></div>
            </div>

            <div class="modal-footer">
                <a id="anchorDownloadTemplate" class="btn btn-success" onclick="DownloadQuestionVersionTemplate();" title="Download Question Paper Template" style="color:white;">Download</a>
                <button id="btnCancel" class="btn btn-danger" data-dismiss="modal">Exit</button>
            </div>
        </div>
    </div>
</div>

<script>
var table;
$(document).ready(function() {
    table = $('#tblMain').DataTable({
        "serverSide": true,
        "paging": true,
        "scrollX": false,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url + "content/get_question_paper_data/",
            "type": "POST",
            error: function()
            {
                $("#tblMain tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
            }
        },
        "columnDefs":
            [
                {
                    "targets": [0, 4, -1 ],
                    "orderable": false
                }
            ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        buttons:
            [
                {
                    extend:'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Question Papers',
                    customize: function (doc)
                    {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                'csvHtml5',
                'excelHtml5',
                'print',
                'copyHtml5',
                'colvis'
            ],
        "order": [[ 1, "asc" ]]
    });

    $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="icon-refresh"></i> Reload</button>').appendTo('div#table_length');
});

function reload_table()
{
    table.ajax.reload(null, false);
}

function EditQuestionPaper(QuestionPaperId)
{
    document.location.href = base_url + "content/addedit_question_paper/" + QuestionPaperId;
}

function AddQuestionPaper()
{
    document.location.href = base_url + "content/addedit_question_paper";
}

function PreviewQuestionPaper(QuestionPaperId)
{
    document.location.href = base_url + "content/preview_question_paper/" + QuestionPaperId;
}

function DownloadQuestionTemplate()
{
    location.href = base_url + "content/Template_QuestionBulkUpload.xlsx";
}

function DownloadQuestionVersionTemplate()
{
    $("#lblLanguageError").hide();
    if ($("#listLanguage option:selected").index() < 1)
    {
        $("#lblLanguageError").text('* Select a language to download its template!');
        $("#lblLanguageError").show();
        $("#listLanguage").focus();
        return;
    }

    var varQuestionPaperId = $("#hidQuestionPaperId").val();
    location.href = base_url + "content/download_version_bulk_upload_template/" + $("#hidQuestionPaperId").val() + "/" + $("#listLanguage").val();
}

function ToggleActiveStatus(id, active_status) {
    var strStatus = (active_status == 1) ? "Deactivate" : "Activate";
    var strCompletedStatus = (active_status == 1) ? "Deactivated" : "Activated";
    swal(
        {
            title: "",
            text: "Are you sure, you want to " + strStatus + "?",
            showCancelButton: true,
            confirmButtonColor: ((active_status == 1) ? "#d9534f" : "#5cb85c"),
            confirmButtonText: "Yes, " + strStatus + "!",
            cancelButtonText: "No, Cancel!",
            closeOnConfirm: true,
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "content/change_question_paper_active_status",
                    data:
                    {
                        'id': id,
                        'active_status': active_status
                    },
                    dataType: 'json',
                    success: function (data) 
                    {
                        swal({
                                title: "",
                                text: data['message'] + "!",
                                confirmButtonColor: ((active_status == 1) ? "#d9534f" : "#5cb85c"),
                                confirmButtonText: 'OK',
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function(confirmed)
                            {
                                reload_table();
                                //window.location.reload();
                            });
                    },
                    error: function () {
                        alert("Error Occurred");
                    }
                });

            }
        }
    );
}

function ShowVersionBulkUploadTemplatePopup(QuestionPaperId, QuestionPaperTitle, DurationMinutes)
{
    $("#hdrDownloadPopupTitle").html('Question Paper - Download Version Bulk Upload Template');
    $("#hidQuestionPaperId").val(QuestionPaperId);
    $("#txtQuestionPaperVersionTitle").val(QuestionPaperTitle);
    $("#txtVersionDuration").val(DurationMinutes);
    $("#listLanguage").val('0');
    $("#divDownloadQuestionPaperTemplate").modal({ show: true });
}

function UploadQuestions(QuestionPaperId, QuestionPaperTitle, DurationMinutes)
{
    $("#lblLanguageError").hide();
    $("#lblFileQuestionDataError").hide();

    $("#divQuestionImages").show();
    $("#divListLanguage").hide();

    $("#hdrPopupTitle").html('Question Paper - Upload English Version Questions');
    $("#hidQuestionPaperId").val(QuestionPaperId);
    $("#txtQuestionPaperTitle").val(QuestionPaperTitle);
    $("#txtDuration").val(DurationMinutes);
    $("#divQuestionPaperTitle").show();
    $("#divDuration").show();
    $("#hidOperationType").val('');
    $("#txtStatus").val('');
    $("#divAnchorDownloadTemplate").show();

    if (/MSIE/.test(navigator.userAgent))
    {
        $("#fileQuestionData").replaceWith($("#fileQuestionData").clone(true));
        $("#fileQuestionImages").replaceWith($("#fileQuestionImages").clone(true));
    }
    else
    {
        $("#fileQuestionData").val('');
        $("#fileQuestionImages").val('');
    }

    document.getElementById("btnSave").disabled = false;
    $("#divAddEditQuestionPaper").modal({ show: true });
    $("#fileQuestionData").focus();
}

function UploadVersions()
{
    $("#lblFileQuestionDataError").hide();

    $("#divQuestionImages").hide();
    $("#hdrPopupTitle").html('Question Paper - Upload Non English Version Questions');
    $("#divQuestionPaperTitle").hide();
    $("#divDuration").hide();
    $("#hidOperationType").val('VERSION');
    $("#txtStatus").val('');
    $("#hidQuestionPaperId").val('0');
    $("#divAnchorDownloadTemplate").hide();

    if (/MSIE/.test(navigator.userAgent))
    {
        $("#fileQuestionData").replaceWith($("#fileQuestionData").clone(true));
        $("#fileQuestionImages").replaceWith($("#fileQuestionImages").clone(true));
    }
    else
    {
        $("#fileQuestionData").val('');
        $("#fileQuestionImages").val('');
    }

    $("#divAddEditQuestionPaper").modal({ show: true });
    $("#fileQuestionData").focus();
}

function ShowQuestions(QuestionPaperId)
{
    document.location.href = base_url + "content/questions/" + QuestionPaperId;
}

function DownloadQuestionPaperInPdf(QuestionPaperId)
{
    document.location.href = base_url + "content/download_question_paper_preview_in_pdf/" + QuestionPaperId;
}

$("#frmEntry").on('submit',(function(e)
{
    $("#txtStatus").val('');
    e.preventDefault();

    if (!ValidateInputs()) return;

    var varApiName = $("#hidOperationType").val() == "VERSION" ? "UploadQuestionPaperVersionData" : "UploadQuestionPaperData";
    document.getElementById("btnSave").disabled = true;
    $.ajax({
        url: base_url + "content/" + varApiName,
        type: "POST",
        data: new FormData(this),
        contentType: false,
        cache: false,
        processData: false,
        success: function (result)
        {
            $("#txtStatus").val($("#txtStatus").val() + result);
            var textarea = document.getElementById('txtStatus');
            textarea.value += "\n";
            textarea.scrollTop = textarea.scrollHeight;            
        },
        error: function () {
            alert("Error Occurred");
        }
    });
}));


function ValidateInputs()
{
    var varReturnValue = true;
    var varFocus = false;

     $("#lblFileQuestionDataError").hide();

    if (!$("#fileQuestionData").val())
    {
        $("#lblFileQuestionDataError").text('* Select question data file to upload!');
        $("#lblFileQuestionDataError").show();
        if (!varFocus)
        {
            $("#fileQuestionData").focus();
            varFocus = true;
        }
        varReturnValue = false;
    }

    return varReturnValue;
}
</script>


                    
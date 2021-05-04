<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  Sample Questions list
 * @date  Nov_2016
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

.dataTables_wrapper .dataTables_scroll div.dataTables_scrollBody>table>tbody>tr>td
{
    vertical-align: top !important;
}
</style>

<?php
$options_sectors=array(''=>'-Select Sector-');
$options_orgTypes=array(''=>'-Select Org type-');
?>
<div class="content-body" style="overflow-x: hidden !important;">
    <?php
    if (intval($user_role_id) == 1)
    {
        echo '<div style="float:right">';
        echo '    <a class="btn btn-success btn-min-width mr-1 mb-1" onclick="PreviewSampleQuestionPaper()" style="color: white;"><i class="icon-eye"></i> Preview</a>';
        echo '    <a class="btn btn-success btn-min-width mr-1 mb-1" onclick="ShowSampleQuestionEntry(0)" style="color:white;" ><i class="icon-android-add"></i> Add Question</a>';
        echo '    <a class="btn btn-success btn-min-width mr-1 mb-1" onclick="ShowUploadSampleQuestionPopup()" style="color:white;" ><i class="icon-android-upload"></i> Upload Questions</a>';
        echo '    <a class="btn btn-success btn-min-width mr-1 mb-1" onclick="ShowDownloadVersionTemplatePopup()" style="color: white;"><i class="icon-arrow-down-a"></i> Download Version Template</a>';
        echo '    <a class="btn btn-success btn-min-width mr-1 mb-1" onclick="ShowUploadSampleQuestionVersionPopup()" style="color:white;" ><i class="icon-android-upload"></i> Upload Versions</a>';
        echo '</div>';
    }
    ?>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-12 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?> </a>
                </li>
                <li class="breadcrumb-item "><?php echo anchor("content/question_papers","Question Papers");?>
                </li>
                <li class="breadcrumb-item active">Sample
                </li>
            </ol>
        </div>
    </div>

    <section id="configuration" style="margin-top: 2%;">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Sample Question Paper - Questions</h4>
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
                            <table id="tblMain" class="table table-striped table-bordered display responsive" style="width:100% !important; ">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;width:10px;"><i class="icon-arrow-expand"></i></th>
                                        <th style="text-align: center;"><i class="icon-flash"></i></th>
                                        <th>SNo.</th>
                                        <th>Question</th>
                                        <th>Type</th>
                                        <th>Section</th>
                                        <th>Part</th>
                                        <th>Option1</th>
                                        <th>Option2</th>
                                        <th>Option3</th>
                                        <th>Option4</th>
                                        <th>Option5</th>
                                        <th>Correct Option</th>
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
<form id="frmEntry" method="post" enctype="multipart/form-data" class="form-horizontal" style="padding-top:10px;">
    <div id="divUploadSampleQuestions" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content"  style="width:120%;" >
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 id="hdrPopupTitle" class="modal-title">Sample Question Paper - Bulk Upload Questions</h3>
                </div>
                <div class="modal-body">
                    <div id="divQuestionPaperTitle" class="form-group" style="display:block;">
                        <label for="txtQuestionPaperTitle" class="col-sm-2 control-label" style="margin-top: 5px;">Question Paper</label>
                        <div class="col-sm-10" style="margin-bottom: 10px;">
                            <input type="text" class="form-control" id="txtQuestionPaperTitle" name="txtQuestionPaperTitle" value="Sample Question Paper" onkeydown="return false;" disabled="disabled"/>
                        </div>
                    </div>

                    <div style="clear: both;"></div>

                    <div id="divAnchorDownloadTemplate" class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <div class="col-sm-10" style="margin-bottom: 0px;">
                            <a id="anchorDownloadTemplate" class="btn btn-success" onclick="DownloadQuestionTemplate()" title="Download Sample Question Bulk Upload Template" style="color:DarkBlue; font-size:small;color:white; font-weight:bold; font-style:italic; padding: 3px;margin-bottom: 2px;">Download Template</a>
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
                    <button id="btnUpload" class="btn btn-success" type="submit">Upload</button>
                    <button id="btnCancel" class="btn btn-danger" data-dismiss="modal" onclick="reload_table()">Exit</button>
                </div>
            </div>
        </div>
    </div>
</form>

<div id="divDownloadVersionTemplate" class="modal fade bs-example-modal-lg" id="modal_form" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"  style="width:120%;" >
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 id="hdrDownloadPopupTitle" class="modal-title">Sample Question Paper - Download Language Version Template</h3>
            </div>
            <div class="modal-body">
                <div class="form-group" style="display:block;">
                    <label for="txtQuestionPaperVersionTitle" class="col-sm-2 control-label" style="margin-top: 5px;">Question Paper</label>
                    <div class="col-sm-10" style="margin-bottom: 10px;">
                        <input type="text" class="form-control" id="txtQuestionPaperVersionTitle" name="txtQuestionPaperVersionTitle" value="Sample Question Paper" onkeydown="return false;" disabled="disabled"/>
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
                            if ($LanguageList)
                            {
                                foreach ($LanguageList AS $Language)
                                {
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

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen-bootstrap.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'nist-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>

<script>
var table;
$(document).ready(function() 
{
    table = $("#tblMain").DataTable({
        "serverSide": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": 
        {
            "url": base_url + "content/get_sample_question_data",
            "type": "POST",
            error: function()
            {
                $("#tblMain tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
            }
        },
        "columnDefs":
            [
                {
                    "targets": [0,1,2,3,4,5,6,7,8,9,10,11,-1],
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
                    title: 'Questions',
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
        //"order": [[ 2, "asc" ]]
    });

    reload_table();

    $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');
});


function reload_table()
{
    table.ajax.reload(null,false);
}

function ShowSampleQuestionEntry(QuestionId)
{
    if (QuestionId > 0)
        document.location.href = base_url + "content/addedit_sample_question/" + QuestionId;
    else
        document.location.href = base_url + "content/addedit_sample_question";
}
    
function AddEditSampleQuestionLanguageVersions(QuestionId)
{
    if (QuestionId > 0)
        document.location.href = base_url + "content/addedit_sample_question_language_version/" + QuestionId;
    else
        document.location.href = base_url + "content/addedit_sample_question_language_version";
}

function PreviewSampleQuestionPaper()
{
    document.location.href = base_url + "content/preview_sample_question_paper";
}

function ShowUploadSampleQuestionPopup()
{
    $("#hdrPopupTitle").html('Sample Question Paper - Bulk Upload Questions');
    $("#divAnchorDownloadTemplate").show();
    $("#hidOperationType").val('');
    $("#divQuestionImages").show();
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
    $("#divUploadSampleQuestions").modal({ show: true });
}

function ShowUploadSampleQuestionVersionPopup()
{
    $("#hdrPopupTitle").html('Sample Question Paper - Bulk Upload Question Versions');
    $("#hidOperationType").val('VERSION');
    $("#divAnchorDownloadTemplate").hide();
    $("#divQuestionImages").hide();
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
    $("#divUploadSampleQuestions").modal({ show: true });
}

function ShowDownloadVersionTemplatePopup()
{
    $("#divDownloadVersionTemplate").modal({ show: true });
}

$("#frmEntry").on('submit',(function(e)
{
    $("#txtStatus").val('');
    e.preventDefault();
    //if (!ValidateInputs()) return;
    var varApiName = $("#hidOperationType").val() == "VERSION" ? "UploadSampleQuestionPaperVersionData" : "UploadSampleQuestionPaperData";
    if ($("#hidOperationType").val() == "")
    {
        swal({
                title: "",
                text: "The existing sample questions will be overwritten with these questions. Do you want to proceed?",
                html: true,
                showCancelButton: true,
                confirmButtonColor: "#d9534f",
                confirmButtonText: "Yes, Upload!",
                cancelButtonText: "No, Cancel!",
                closeOnConfirm: true,
                closeOnCancel: true
            },
            function (isConfirm)
            {
                if (!isConfirm) return;
            }
        );
    }

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
        error: function ()
        {
            alert("Error Occurred");
        }
    });

    return;
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

function DownloadQuestionTemplate()
{
    location.href = base_url + '<?= TEMPLATES ?>Template_SampleQuestionBulkUpload.xlsx';
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
    location.href = base_url + "content/download_sample_version_bulk_upload_template/" + $("#listLanguage").val();
}
</script>


                    
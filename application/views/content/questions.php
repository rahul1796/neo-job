<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  questions list
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
        echo '<a class="btn btn-success btn-sm" onclick="ShowQuestionEntry(' . $question_paper_id . ',0)" style="color:white" ><i class="icon-android-add"></i> Add Question</a>';
    ?>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("ist/dashboard","Dashboard");?> </a>
                </li>
                <li class="breadcrumb-item "><?php echo anchor("content/question_papers","Question Papers");?>
                </li>
                <li class="breadcrumb-item active">Question Paper
                </li>
            </ol>
        </div>
    </div>

    <section id="configuration" style="margin-top: 2%;">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Question Paper : <?= $question_paper_title ?></h4>
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
                            <input type="hidden" id="hidQuestionPaperId" name="hidQuestionPaperId" value=""/>
                            <table id="tblMain" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important; ">
                                <thead>
                                    <tr>
                                        <th style="text-align: left;"><i class="icon-arrow-expand"></i></th>
                                        <th style="text-align: center;"><i class="icon-flash"></i></th>
                                        <th style="text-align: center;">SNo.</th>
                                        <th style="text-align: center;width:20%!important;">Question</th>
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

<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen-bootstrap.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'nist-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script>
$(document).ready(function() {
    table = $("#tblMain").DataTable({
        "serverSide": true,
        "paging": true,
        "scrollX": false,
        "fixedCoulmn":true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url + "content/get_question_data/<?= $question_paper_id ?>",
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
        "columns": 
            [
                null,
                null,
                null,
                { "width": "20%" },
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null,
                null
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

function ShowQuestionEntry(QuestionPaperId, QuestionId)
{
    document.location.href = base_url + "content/addedit_question/" + QuestionPaperId + "/" + QuestionId;
}
    
function AddEditQuestionLanguageVersions(QuestionPaperId, QuestionId)
{
    document.location.href = base_url + "content/addedit_question_language_version/" + QuestionPaperId + "/" + QuestionId;
}
</script>


                    
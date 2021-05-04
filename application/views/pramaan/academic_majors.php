<style type="text/css">
/**
 * @author  George Martin <george.s@pramaan.in>
 * @desc  Academic Major List
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

</style>

<div class="inner">
<h4>Academic Majors</h4>
<small>
  <ul class="breadcrumb">
    <li><?php echo anchor("pramaan/dashboard","Dashboard");?> </li>
    <li class="active">Academic Majors</li>
  </ul>
</small>
<hr/>
  <div class="row" style="margin-bottom: 5px;">
    <div class="col-sm-3 col-md-3">
        <?php
        if (intval($user_group_id) == 1)
            echo '<a class="btn btn-success btn-sm" href="' . base_url("pramaan/addedit_academic_major") . '"><i class="glyphicon glyphicon-plus"></i> Add Academic Major</a>';
        ?>
    </div>
    <div class="col-sm-9 col-md-9" style="text-align: right;">
    </div>
  </div>
   <table id="tblMain" class="table table-striped table-responsive" cellspacing="0" style="width:100%;">
    <thead>
        <tr>
            <th>SNo.</th>
            <th>Code</th>
            <th>Name</th>
            <th>Interest Type</th>
            <th>Status </th>
            <th>Actions </th>
        </tr>
    </thead>
    <tbody id="tblBody">
    </tbody>
   </table>
</div><!-- //page inner -->

<script>
$(document).ready(function() {
    table = $('#tblMain').DataTable({
        "serverSide": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url + "pramaan/academic_major_list/",
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
                {
                    extend:'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'Recruitmrnt Support Heads',
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

    <?php
    if (intval($user_group_id) == 1) {
        echo 'table.columns([5,6]).visible(true);';
    }
    else
    {
        echo 'table.columns([5,6]).visible(false);';
    }
    ?>

    $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');
});

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function EditAcademicMajor(AcademicMajorId)
{
    document.location.href = base_url + "pramaan/addedit_academic_major/" + AcademicMajorId;
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
            closeOnConfirm: false,
            closeOnCancel: false
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "pramaan/change_academic_major_active_status",
                    data: {
                        'id': id,
                        'active_status': active_status
                    },
                    dataType: 'json',
                    success: function (data) {
                        swal({
                                title: "",
                                text: "Academic Major successfully " + strCompletedStatus + "!",
                                confirmButtonColor: ((active_status == 1) ? "#d9534f" : "#5cb85c"),
                                confirmButtonText: 'OK',
                                closeOnConfirm: true,
                                closeOnCancel: true
                            },
                            function(confirmed){
                                reload_table();
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
</script>
</div>



                    
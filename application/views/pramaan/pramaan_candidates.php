
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit Employers list
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
.table td, .table th {
    padding: 0.75rem 0.75rem;
}
</style>

<div class="content-body" style="overflow-x: hidden !important;">
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Candidate List
                </li>
            </ol>
        </div>
    </div>

    <section id="configuration">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Candidate List</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-minus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">



                            <table id="table" class="table table-striped table-bordered display responsive nowrap" style="width:100% !important;">
                                <thead>
                                <tr>

                                    <th>Sl No</th>
                                    <th>Candidate Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th width="5px">Gender</th>
                                    <th>Dob</th>
                                    <th>Qualification</th>
                                    <th>Experience</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <!--<th>Relocate(?)</th>-->
                                    <!--  <th>Expected<br>Relocate Salary</th> -->
                                   <!-- <th>Partner Name</th>
                                    <th>Created on</th>-->
                                </tr>
                                </thead>
                                <tbody id="tblBody">

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
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/chosen-bootstrap.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<!--<link rel="stylesheet" type="text/css" href="<?php /*echo base_url().'adm-assets/vendors/css/extensions/sweetalert.css'*/?>">
<script src="<?php /*echo base_url().'adm-assets/vendors/js/extensions/sweetalert.min.js'*/?>" type="text/javascript"></script>
<script src="<?php /*echo base_url().'adm-assets/js/scripts/extensions/sweet-alerts.min.js'*/?>" type="text/javascript"></script>-->

<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>


<script type="text/javascript">
    var save_method; //for save method string
    var table;

$(document).ready(function() {
   //datatables
    table = $('#table').DataTable({
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "paging": true,
        "bAutoWidth": false,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 500],[10, 25, 50, 100, 500]],
        "pageLength": 10,
        "language": {"loadingRecords": "Loading...",
                      "processing":     " "},
       // "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"pramaan/pramaan_candidates_list/"+"<?php echo $user_id;?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
                        { 
                            "targets": [0], //last column
                            "orderable": false, //set not orderable
                        },
                      ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
              "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        "buttons": [],

        /*"buttons": [
            {
                extend: 'collection',
                text: 'Export',
                buttons: [
                    'excel',
                    'pdf',
                    'print'
                    'colvis'
                ]
            }
        ]*/
    });
 
     /*$(' <button class="btn btn-sm btn-default" "><i class="icon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});




function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') 
    {
        url = base_url+"partner/add_employer";
    } 
    else 
    {
        url = base_url+"partner/save_employer";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_Employers').serialize(),
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                $('#modal_form').modal('hide');
                reload_table();
                flashAlert(data.msg_info);
            }
            else
            {

                $.each(data.errors, function(key, val) 
                {
                    //$('[name="'+ key +'"]', '#form_center').closest('input').find('.help_block').html(val);
                    $('[name="'+ key +'"]', '#form_Employers').closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
                });
             //   $("#form_center").valid();
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
}


</script>

<!-- Bootstrap modal -->

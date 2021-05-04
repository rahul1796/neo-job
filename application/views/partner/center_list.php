<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit center list
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
td:nth-child(4)
{

  width:10em!important;

  /* These are technically the same, but use both */
  overflow-wrap: break-word;
  word-wrap: break-word;

  -ms-word-break: break-all;
  /* This is the dangerous one in WebKit, as it breaks things wherever */
  word-break: break-all;
  /* Instead use this non-standard one: */
  word-break: break-word;

  /* Adds a hyphen where the word breaks, if supported (No Blink) */
  -ms-hyphens: auto;
  -moz-hyphens: auto;
  -webkit-hyphens: auto;
  hyphens: auto;
}
</style>
<div class="content-body" style="overflow-x: hidden !important;">
    <a><button type="button" onclick="add_center()" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add Center</button></a>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Center List
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Centers </h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                         <div class="heading-elements">
                             <ul class="list-inline mb-0">
                                 <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                 <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                 <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                             </ul>
                         </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">

                            <table id="table" class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Center Name</th>
                                    <th>Address</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Partner</th>
                                    <th style="width: 75px!important">Action</th>
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
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script type="text/javascript">
var save_method; //for save method string
var table;

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 

        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "paging": true,
        "aLengthMenu": [[25, 50, 100, 200, -1],[25, 50, 100, 200, "All"]],
        "pageLength": 25,
        "language": {"loadingRecords": "Loading...",
                      "processing":     "Processing.."},
      //  "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"partner/center_list/"+"<?= $partner_id?>",
            "type": "POST",
            error: function()
            {  // error handling
              $("#table tbody").empty().append('<tr><td align="center" colspan="9">No data found</td></tr>');
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
                        { 
                            "targets": [ 5,-1 ], //last column
                            "orderable": false, //set not orderable
                        },
                      ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
              "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        /*buttons: ['pdfHtml5','csvHtml5','excelHtml5','print','copyHtml5','colvis']*/
          buttons: [/*{extend:'pdfHtml5',orientation: 'landscape',pageSize: 'A4',title: 'Center List',
                              customize: function (doc) 
                              {
                                  doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                              }
                      },
                      'excelHtml5',
                      'copyHtml5',
                      'colvis'*/]
    });

    /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
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

function add_center()
{
    save_method = 'add';
    $('#form_center')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add center'); // Set Title to Bootstrap modal title
}

function edit_center(id)
{
    save_method = 'update';
    $('#form_center')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : base_url+"partner/center_by_id/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.id);
            $('[name="centername"]').val(data.name);
            $('[name="address"]').val(data.address);
            $('[name="phone"]').val(data.phone);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit center'); // Set title to Bootstrap modal title

        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') 
    {
       url = base_url+"partner/add_center";
    }  
    else 
    {
        url = base_url+"partner/update_center";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_center').serialize(),
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
                    $('[name="'+ key +'"]', '#form_center').closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
                });
                $("#form_center").valid();
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

function delete_center(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"partner/delete_center/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                reload_table();
                flashAlert(data.msg_info);
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Center Form</h3>
            </div>
            <div class="modal-body form">
                <div id="msgDisplay"></div>
                <form  id="form_center" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
                        <div class="form-group row">
                            <input name="partner_id" type="hidden" value="<?= $partner_id?>">
                            <label class="label-control col-md-3">Center Name<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <input name="centername" placeholder="Center Name" class="form-control" type="text" maxlength="150">
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Address<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="4" name="address" id="address" placeholder="Center address" maxlength="250"></textarea>
                                <span class="error_label"></span>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="label-control col-md-3">Mobile<span class='validmark'>*</span></label>
                             <div class="col-md-9">
                                  <input name="phone" placeholder="Mobile" class="form-control" type="text" maxlength="<?= PHONE_MAX?>">
                                  <span class="error_label"></span>
                              </div>
                        </div>
                      
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
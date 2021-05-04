<style type="text/css">
/**
 * @author  Sangamesh <sangamesh.p@pramaan.in>
 * @desc  edit associates list
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
td:nth-child(5)
{

  width:12em!important;

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
td:nth-child(8)
{
    padding:10px;
}
</style>
<?php
    $center_options=array('' => '-Select Center-');
    foreach ($center_list as $row) 
    {
        $center_options[$row['id']]=$row['name'];
    }
?>
<div class="content-body" style="overflow-x: hidden !important;">
    <a><button type="button" onclick="add_associates()" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add Associates</button></a>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Associates List
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Associates </h4>
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
                                    <th>Associate Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>email</th>
                                    <th>Partner name</th>
                                    <th>Center Name</th>
                                    <th style="width: 5.5em!important;" nowrap>Action</th>
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
       // "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"partner/associates_list/<?= $partner_id?>",
            "type": "POST",
            error: function()
            {  // error handling
              $("#table tbody").empty().append('<tr><td align="center" colspan="9">No data found</td></tr>');
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
                        { 
                            "targets": [ 5,6, -1 ], //last column
                            "orderable": false, //set not orderable
                        },
                      ],
        "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
              "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
        /*buttons: ['pdfHtml5','csvHtml5','excelHtml5','print','copyHtml5','colvis']*/
          buttons: [/*{extend:'pdfHtml5',orientation: 'landscape',pageSize: 'A4',title: 'Associate Partners',
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



function add_associates()
{
    save_method = 'add';
    $('#form_associates')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add Associates'); // Set Title to Bootstrap modal title
    $('.add_edit').show();
}


function edit_associates(id)
{
    save_method = 'update';
    $('#form_associates')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : base_url+"partner/associates_by_id/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            $('[name="id"]').val(data.id);
            $('[name="center"]').val(data.center_id);
            $('[name="associatename"]').val(data.name);
            $('[name="address"]').val(data.address);
            $('[name="email"]').val(data.email);
            $('[name="phone"]').val(data.phone);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit associates'); // Set title to Bootstrap modal title
            // $('[name="phone"]').datepicker('update',data.phone);
            $('.add_edit').hide();
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
   /* $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable */
    var url;

    if(save_method == 'add') 
    {
        url = base_url+"partner/add_associates";
    } 
    else 
    {
        url = base_url+"partner/update_associates";
    }

    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_associates').serialize(),
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
                    $('[name="'+ key +'"]', '#form_associates').closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
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

function delete_associates(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : base_url+"partner/delete_associates/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}

function associate_desk(associate_id)
{
    if(associate_id)
    {
        var url=(base_url+'partner/candidates/'+associate_id);
        document.location.href=url;
        
    }
}

</script>

<!-- Bootstrap modal -->
<div class="inner">
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Associates Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form_associates" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <input type="hidden" value="<?php echo $partner_id?>" name="partner_id"/>
                    <div class="form-body">

                        <div class="form-group row">
                            <label class="label-control col-md-3">center<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <?php echo form_dropdown('center',$center_options,'','id="center" class="form-control"');?>
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Associates Name<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <input name="associatename" placeholder="Associates Name" class="form-control" type="text">
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Address<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <textarea class="form-control" rows="4" name="address" id="address" placeholder="Associates address" ></textarea>
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Phone<span class='validmark'>*</span></label>
                             <div class="col-md-9">
                                  <input name="phone" placeholder="Phone/mobile" class="form-control" type="text" maxlength="<?= PHONE_MAX?>">
                                  <span class="error_label"></span>
                              </div>
                        </div>
                        <div class="form-group row">
                            <label class="label-control col-md-3">Email<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <input name="email" placeholder="Email Address" class="form-control" type="email" maxlength="<?= EMAIL_MAX?>">
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group row add_edit">
                            <label for="password" class="col-sm-3 label-control">Password<span class='validmark'>*</span></label>
                            <div class="col-sm-9">
                                <input class="form-control" name="password" id="mainpassword" type="password" maxlength="<?= PASSWORD_MAX?>">
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
</div>
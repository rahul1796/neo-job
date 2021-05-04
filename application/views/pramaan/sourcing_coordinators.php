<style type="text/css">
    /**
     * @author  Sangamesh <sangamesh.p@pramaan.in>
     * @desc  recruitment partner list
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
</style>
<?php
$options_sectors=array(''=>'-Select Sector-');
$options_orgTypes=array(''=>'-Select Org type-');
?>
<div class="content-body" style="overflow-x: hidden !important;">
    <a href="<?php echo base_url("pramaan/add_sourcing_coordinator/$parent_id")?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add Sourcing Coordinator</button></a>
    <div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Sourcing Co-ordinator
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Sourcing Partners</h4>
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

                            <table id="table" class="table table-striped table-responsive" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SlNo.</th>
                                    <th style="width:15%!important" nowrap>Co-ordinator Name </th>
                                    <th>Email </th>
                                    <th>phone</th>
                                    <th>Assigned Districts</th>
                                    <th>Sourcing Partners</th>
                                    <th>Action</th>
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



<!-- Inner div for modal -->
<div class="inner">
<div class="modal fade" id="modal_form_tracker" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Sourcing Partner Details</h3>
            </div>
            <div class="modal-body sourcing_partner_details">
                -No Records Found-
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
</div>
<!-- //Modal inner -->
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

        "stateSave": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[25, 50, 100, 200, -1],[25, 50, 100, 200, "All"]],
        "pageLength": 25,
        "language": {"loadingRecords": "Loading...",
                      "processing":     "Processing.."},
       // "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": base_url+"pramaan/sourcing_coordinator_list/"+"<?php echo $parent_id;?>",
            "type": "POST",
            error: function()
            {  // error handling
              $("#table tbody").empty().append('<tr><td align="center" colspan="9">No data found</td></tr>');
            }
        },

        //Set column definition initialisation properties.
        "columnDefs": [
                        { 
                            "targets": [0, 2,-1 ], //last column
                            "orderable": false, //set not orderable
                        },
                      ],
          "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
                  "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
          /*buttons: ['pdfHtml5','csvHtml5','excelHtml5','print','copyHtml5','colvis']*/
           buttons: [/*{extend:'pdfHtml5',orientation: 'landscape',pageSize: 'A4',title: 'Sourcing Co-ordinator',
                              customize: function (doc) 
                              {
                                  doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                              }
                      },
                      'csvHtml5',
                      'excelHtml5',
                      'print',
                      'copyHtml5',
                      'colvis'*/]
    }); 

     $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');
});
function reload_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}
function tracked_partner(coordinator_id)
{
  var track_url=base_url+'partner/partners_by_coordinator/'+coordinator_id;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var partners_track_list_html='<div>-No Data Found-</div>';
            if(data.status)
            {
                var coordinator=data.rec_coordinator;
                var slno=1;
                partners_track_list_html = '<div class="row">';
                partners_track_list_html += '<div class="col-sm-4 col-md-4">';
                partners_track_list_html += 'Coordinator Name:';
                partners_track_list_html += '</div>';
                partners_track_list_html += '<div class="col-sm-8 col-md-8">';
                partners_track_list_html += coordinator.name;
                partners_track_list_html += '</div>';
                partners_track_list_html += '<div class="col-sm-4 col-md-4">';
                partners_track_list_html += 'Coordinator Phone:';
                partners_track_list_html += '</div>';
                partners_track_list_html += '<div class="col-sm-8 col-md-8">';
                partners_track_list_html += coordinator.phone;
                partners_track_list_html += '</div>';
                partners_track_list_html += '<div class="col-sm-4 col-md-4">';
                partners_track_list_html += 'Coordinator Address:';
                partners_track_list_html += '</div>';
                partners_track_list_html += '<div class="col-sm-8 col-md-8">';
                partners_track_list_html += coordinator.address;
                partners_track_list_html += '</div>';
                partners_track_list_html += '</div></div>';
                partners_track_list_html += '<div class="row">';
                partners_track_list_html += '<div class="col-sm-12 col-md-12">';
                partners_track_list_html += '<table class="table">';
                partners_track_list_html += '<tr><th>Sl No</th><th>Partner Name</th><th>Type</th><th>Phone</th></tr>';
                
                $.each(data.partners_details,function(a,b)
                {
                  partners_track_list_html += '<tr><td>'+slno+'</td><td>'+b.partner_name+'</td><td>'+b.partner_type+'</td><td>'+b.phone+'</td></tr>';
                  slno++;
                });
                
                partners_track_list_html += '</table>';
                partners_track_list_html += '</div></div>';
            }
            $('.sourcing_partner_details').html(partners_track_list_html);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_tracker').modal('show'); // show bootstrap modal when complete loaded
}
function edit_user_admin(sourcing_coordinator_id)
{

    save_method = 'update';
    $('#form_sourcing_coordinator')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.error_label').empty(); // clear error string
    //Ajax Load data from ajax
    $.ajax({
        url : base_url+"pramaan/user_admin_by_id/" + sourcing_coordinator_id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            if(data.status)
            {
              var admin_data=data.user_admin;
                $('[name="id"]').val(admin_data.user_id);
                $('[name="pname"]').val(admin_data.name);
                $('[name="phone"]').val(admin_data.phone);
                $('[name="email"]').val(admin_data.email);
                $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Edit Sourcing Cordinator'); // Set title to Bootstrap modal title
                // $('[name="phone"]').datepicker('update',data.phone);
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function save()
{
    var url;
    url = base_url+"pramaan/save_user_admin";
    // ajax adding data to database
    $.ajax({
        url : url,
        type: "POST",
        data: $('#form_sourcing_coordinator').serialize(),
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

                    $('[name="'+ key +'"]', '#form_sourcing_coordinator').closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
                });
             //   $("#form_center").valid();
            }
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
}

function sub_admin_desk(coordinator_id)
{
  if(coordinator_id)
  {
    var url=(base_url+'pramaan/sourcing_partner/'+coordinator_id);
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
                <h3 class="modal-title">Sourcning Coordinator Form</h3>
            </div>
                <div class="modal-body form">
                <form action="#" id="form_sourcing_coordinator" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-4">Sourcing Coordinator Name<span class='validmark'>*</span></label>
                            <div class="col-md-8">
                                <input name="pname" placeholder="Sourcing coordinator Name" class="form-control" type="text">
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Phone<span class='validmark'>*</span></label>
                             <div class="col-md-8">
                                  <input name="phone" placeholder="Phone/mobile" class="form-control" type="text" maxlength="<?= PHONE_MAX?>">
                                  <span class="error_label"></span>
                              </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-4">Email<span class='validmark'>*</span></label>
                            <div class="col-md-8">
                                <input name="email" placeholder="Email Address" class="form-control" type="email" maxlength="<?= EMAIL_MAX?>">
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
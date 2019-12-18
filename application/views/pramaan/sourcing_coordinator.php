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
<div class="inner">
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("pramaan/dashboard","Dashboard");?> </li>
    <li class="active"> Sourcing Co-ordinator </li>
  </ul>
</small>
<hr/>
  <div class="row" style="margin-bottom: 5px;">
    <div class="col-sm-3 col-md-3">
        <a class="btn btn-success btn-sm" href="<?php echo base_url("pramaan/add_sourcing_coordinator/$sourcing_manager_id")?>"><i class="glyphicon glyphicon-plus"></i> Add Sourcing Co-ordinator</a>
    </div>
    <div class="col-sm-9 col-md-9" style="text-align: right;">
  
     <!--  <button class="btn btn-primary btn-sm btn-outline" onclick="print_ap_tracker()"><i class="glyphicon glyphicon-print"></i> Print</button>
      <button class="btn btn-primary btn-sm btn-outline" onclick="copy_center()"><i class="glyphicon  glyphicon-copy"></i> Copy</button>
      <button class="btn btn-success btn-sm btn-outline" onclick="download_pdf()"><i class="glyphicon glyphicon-download"></i> PDF</button>
      <button class="btn btn-success btn-sm btn-outline" onclick="download_excel()"><i class="glyphicon glyphicon-download"></i> Excel</button>
      <button class="btn btn-success btn-sm btn-outline" onclick="download_csv()"><i class="glyphicon glyphicon-download"></i> CSV</button>
      <button class="btn btn-warning btn-sm btn-outline" onclick="add_columns()"><i class="glyphicon glyphicon-plus"></i>Column</button>  -->
    </div>
  </div>
   <table id="table" class="table table-striped table-responsive" cellspacing="0" style="width:100%">
    <thead>
        <tr>
            <th>SlNo.</th>
            <th style="width:15%!important" nowrap>Co-ordinator Name </th>
            <th>Email </th>
            <th>phone</th>
            <th>Sourcing Partners</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</div><!-- //page inner -->

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
            "url": base_url+"pramaan/sourcing_coordinator_list/"+"<?php echo $sourcing_manager_id;?>",
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

function partner_desk(coordinator_id)
{
  if(coordinator_id)
    {
        var url=(base_url+'pramaan/sourcing_partner/'+coordinator_id);
        document.location.href=url;
    }
}
</script>

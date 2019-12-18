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
<h4>Recruitment Partner </h4>
<small>
  <ul class="breadcrumb" style="padding: 0px;">
    <li><?php echo anchor("pramaan/dashboard","Dashboard");?></li>
    <li class="active">Recruitment Partner </li>
  </ul>
</small>
<hr/>
  <div class="row" style="margin-bottom: 5px;">
    <div class="col-sm-3 col-md-3">
        <a class="btn btn-success btn-sm" href="<?php echo base_url('pramaan/add_recruitment_partner/')?>"><i class="glyphicon glyphicon-plus"></i> Add Recruitment Partner</a>
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
            <th style="width:15%!important" nowrap>Partner Name </th>
            <th>Email </th>
            <th>phone</th>
            <th>Employers</th>
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
                <h3 class="modal-title">Employers Details</h3>
            </div>
            <div class="modal-body employers_details">
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
            "url": base_url+"pramaan/recruitment_partner_list",
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
           buttons: [/*{extend:'pdfHtml5',orientation: 'landscape',pageSize: 'A4',title: 'Recruitment Partners',
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
function tracked_employer(rec_partner_id)
{
  var track_url=base_url+'pramaan/employers_by_partner/'+rec_partner_id;
  $.ajax({
        url : track_url,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
            var employers_track_list_html='<div>-No Data found-</div>';
            if(data.status)
            {
                var partner=data.rec_partner;
                var slno=1;
                employers_track_list_html = '<div class="row">';
                employers_track_list_html += '<div class="col-sm-3 col-md-3">';
                employers_track_list_html += 'Partner Name:';
                employers_track_list_html += '</div>';
                employers_track_list_html += '<div class="col-sm-9 col-md-9">';
                employers_track_list_html += partner.name;
                employers_track_list_html += '</div>';
                employers_track_list_html += '<div class="col-sm-3 col-md-3">';
                employers_track_list_html += 'Partner Phone:';
                employers_track_list_html += '</div>';
                employers_track_list_html += '<div class="col-sm-9 col-md-9">';
                employers_track_list_html += partner.phone;
                employers_track_list_html += '</div>';
                employers_track_list_html += '<div class="col-sm-3 col-md-3">';
                employers_track_list_html += 'Partner Address:';
                employers_track_list_html += '</div>';
                employers_track_list_html += '<div class="col-sm-9 col-md-9">';
                employers_track_list_html += partner.address;
                employers_track_list_html += '</div>';
                employers_track_list_html += '</div></div>';
                employers_track_list_html += '<div class="row">';
                employers_track_list_html += '<div class="col-sm-12 col-md-12">';
                employers_track_list_html += '<table class="table">';
                employers_track_list_html += '<tr><th>Sl No</th><th>Employer Name</th><th>Sector</th><th>Address</th><th>Phone</th></tr>';
                
                $.each(data.employers_details,function(a,b)
                {
                  employers_track_list_html += '<tr><td>'+slno+'</td><td>'+b.name+'</td><td>'+b.sector+'</td><td>'+b.address+'</td><td>'+b.phone+'</td></tr>';
                  slno++;
                });
                
                employers_track_list_html += '</table>';
                employers_track_list_html += '</div></div>';
            }
            $('.employers_details').html(employers_track_list_html);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
    $('#modal_form_tracker').modal('show'); // show bootstrap modal when complete loaded
}

function partner_desk(rec_partner_id)
{
   if(rec_partner_id)
    {

        var url=(base_url+'partner/employers/'+rec_partner_id);
        document.location.href=url;
    }
}
</script>

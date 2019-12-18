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
    <a href="<?php echo base_url("pramaan/add_rs_vertical/$parent_id")?>"><button type="button" class="btn btn-success btn-min-width mr-1 mb-1"><i class="icon-android-add"></i> Add RS Vertical</button></a>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-10 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Placement Verticals
                </li>
            </ol>
        </div>
    </div>
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Placement Verticals </h4>
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

                            <table id="tblRsVer" class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Vertical Name</th>
                                    <th>Vertical Manager</th>
                                    <th>Sectors</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="tb">


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

<div id="divVertical" class="modal fade bs-example-modal-md" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md" role="document" >
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Sector List</h3>
            </div>
            <div class="modal-body">
                <table id="tblVertical" class="table table-striped " cellspacing="0"">
                    <thead>
                    <tr>
                        <th>SNo.</th>
                        <th>Sector Name</th>
                    </tr>
                    </thead>
                    <tbody id="tblVerticalBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <center><h4 class="modal-title"><b>Sectors</b></h4></center>
            </div>
            <div class="modal-body" id="sectors_id" style="overflow:scroll; height:400px;">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>

    </div>
</div>
</div><!-- //page inner -->

<!-- Inner div for modal -->
<div class="inner">
    <div class="modal fade" id="modal_form_tracker" role="dialog">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title">Sector Details</h3>
                </div>
                <div class="modal-body sector_details">
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


$(document).ready(function() {
    table = $('#tblRsVer').DataTable({
        "stateSave": true,
        "serverSide": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url + "pramaan/rs_verticals_list/"+"<?php echo $parent_id;?>",
            "type": "POST",
            error: function()
            {
                $("#tblRsVer tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
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
                /*{
                    extend:'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'A4',
                    title: 'RS Verticals',
                    customize: function (doc)
                    {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                'excelHtml5',
                'copyHtml5',
                'colvis'*/
            ],
        "order": [[ 1, "asc" ]]
    });

   

    /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
});



function SectorList(Id) {
    $("#divVertical").modal({ show: true });
    if (Id) {
        $.ajax({
            type: "POST",
            url: base_url + "pramaan/get_sector_for_vertical",
            data: {
                'id': Id
            },
            dataType: 'json',
            success: function (data) {
                var varTableBodyHtml = "";
                for(var i = 0; i < data.length; i++)
                {
                    varTableBodyHtml += "<tr>";
                    varTableBodyHtml += "<td>" + (i + 1) + "</td>";
                    varTableBodyHtml += "<td>" + data[i]['name'] + "</td>";
                    varTableBodyHtml += "</tr>";
                }

                $("#tblVerticalBody").empty().append(varTableBodyHtml);
                $("#divVertical").modal({ show: true });
            },
            error: function () {
                alert("Error Occurred");
            }
        });
    }
}

function EditVertical(Id)
{
    if (Id) {
        document.location.href = base_url + 'pramaan/edit_rs_vertical/' +"<?php echo $parent_id;?>"+"/"+ Id;
    }
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
            closeOnCancel: true
        },
        function(isConfirm) {
            if (isConfirm) {
                $.ajax({
                    type: "POST",
                    url: base_url + "pramaan/change_rs_vertical_active_status",
                    data: {
                        'id': id,
                        'active_status': active_status
                    },
                    dataType: 'json',
                    success: function (data) {
                        swal({
                                title: "",
                                text: "Account successfully " + strCompletedStatus + "!",
                                confirmButtonColor: ((active_status == 1) ? "#d9534f" : "#5cb85c"),
                                confirmButtonText: 'OK',
                                closeOnConfirm: false,
                                closeOnCancel: false
                            },
                            function(confirmed){
                                window.location.reload();
                            });
                    },
                    error: function () {
                        alert("Error Occurred");
                    }
                });

            }
            else
            {
                  window.location.href = base_url + "pramaan/sourcing_heads/";
            }
        }
    );
}

function get_sectors(vertical_id,active_status)
{
   /* alert(user_id);*/

   $.ajax({
        url : base_url+"pramaan/sector_list_by_vertical/"+vertical_id+"/"+active_status,
        type: "POST",
        dataType: "JSON",
        success: function(msg)
        {
            //alert(msg);
           /* alert(msg[0]['name']);*/
           /*alert(msg.length);*/
           var no_of_sectors=msg.length;
           var i=0;
           var x="";
           x=x+"<table class='table table-striped'>";
            x=x+"<tr>";
                x=x+"<th><b>"+"Sno"+"</b></th>";
                x=x+"<th><b>"+"Sector Name"+"</b></th>";
                
            x=x+"</tr>";

   if(no_of_sectors>0)
   {


           for(i=0;i<no_of_sectors;i++)
           {    
                /*row[i]=table.insertRow(i);
                cell1[i] = row[i].insertCell(0);
                cell2[i] = row[i].insertCell(1);
                cell3[i] = row[i].insertCell(2);
                cell4[i] = row[i].insertCell(3);
                cell5[i] = row[i].insertCell(4);
                
                 // Add some text to the new cells:
                cell1[i].innerHTML = msg[i]['sno'];
                cell2[i].innerHTML = msg[i]['name'];
                cell3[i].innerHTML = msg[i]['email'];
                cell4[i].innerHTML = msg[i]['phone'];
                cell5[i].innerHTML = msg[i]['region_id_list'];*/
              
              /* document.getElementById("regional_manager").innerHTML="<table id='regional_manager_list'><thead><tr><th>1</th></tr></thead><tbody></tbody></table>";*/
            x=x+"<tr>";
                x=x+"<td>"+(i+1)+"</td>";
                x=x+"<td>"+msg[i]['name']+"</td>";
            x=x+"</tr>";  
            
           }
           x=x+"</table>";
              document.getElementById("sectors_id").innerHTML=x;
            

  }

  else{
     x = x+"</table>";

    var y = "<center>No data found!</center>";
    
   
     document.getElementById("sectors_id").innerHTML=y;
  }
 

           



        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error');
        }
    }); 


}



 
  function show(id,current)
{
    
    
    
      if(current==1)
      {
         swal({
          title: "Do you want to Deactivate?",
          text: "You can activate it again!",
          type: "warning",
          showCancelButton: true,
          confirmButtonColor: "#DD6B55",
          confirmButtonText: "Yes, Dectivate!",
          cancelButtonText: "No!",
          closeOnConfirm: false,
          closeOnCancel: false
        },
        function(isConfirm){
          if (isConfirm) {
             var ar=[id];
        var url;
          url = base_url+"pramaan/change_vertical_status/"+id;
         $.ajax({
        url : url,
        data:{ar:ar},
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
         
            if (data.status == true) 
              {
               swal("Deactivated!", "", "success");
               window.location.href = base_url+'pramaan/rs_verticals/'+"<?php echo $parent_id;?>";
             
               
              }
            else
            { 
              //alert("ERROR!!");

              $.each(data.errors, function(key, val) 
              {
                $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
              });
             /* $("#sourcing_head_form").valid();*/
            } 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
            
          } else {

            swal("Cancelled", "", "error");
          }
        });
      }
     else if(current==2)
       { 
          swal({
              title: "Do you want to Activate?",
              
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#337ab7",
              confirmButtonText: "Yes, Activate!",
              cancelButtonText: "No!",
              closeOnConfirm: false,
              closeOnCancel: false
            },
            function(isConfirm){
              if (isConfirm) {
                         var ar=[id];
        var url;
         url = base_url+"pramaan/change_vertical_status/"+id;
         $.ajax({
        url : url,
        data:{ar:ar},
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
         
            if (data.status == true) 
              {
               swal("Activated!", "", "success");
                window.location.href = base_url+'pramaan/rs_verticals/'+"<?php echo $parent_id;?>";
             
               
              }
            else
            { 
              //alert("ERROR!!");

              $.each(data.errors, function(key, val) 
              {
                $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
              });
              /*$("#sourcing_head_form").valid();*/
            } 
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 
        }
    });
            
                swal("Activated!", "", "success");
              } else {

                swal("Cancelled", "", "error");
              }
            });
     }
  
  

}
</script>




                    

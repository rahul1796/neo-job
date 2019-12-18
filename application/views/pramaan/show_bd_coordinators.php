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
<div class="content-body" style="overflow-x: hidden !important;">
    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <?php if($parent_page)
                    echo '<li>'.anchor($parent_page,$parent_page_title).'</li>';
                ?>
                <li class="breadcrumb-item active">District Coordinators
                </li>
            </ol>
        </div>
    </div>

    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Recruitment District Coordinator</h4>
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

                            <table id="tblBdCoordinator" class="table table-striped table-bordered" style="width:100% !important;">
                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>District Coordinators name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Districts</th>
                                    <th>Regional Manager(BD)</th>
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
$(document).ready(function() {
    table = $('#tblBdCoordinator').DataTable({
        "stateSave": true,
        "serverSide": true,
        "paging": true,
        "scrollX": true,
        "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
        "pageLength": 10,
        "language": { "loadingRecords": "Loading..." },
        "ajax": {
            "url": base_url + "pramaan/show_bd_coordinator_list/"+"<?php echo $parent_id;?>",
            "type": "POST",
            error: function()
            {
                $("#tblBdCoordinator tbody").empty().append('<tr><td style="text-align: center;" colspan="9">No data found</td></tr>');
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
                    title: 'BD Coordinators',
                    customize: function (doc)
                    {
                        doc.content[1].table.widths = Array(doc.content[1].table.body[0].length + 1).join('*').split('');
                    }
                },
                'excelHtml5',
                'print',
                'colvis'*/
            ],
        "order": [[ 1, "asc" ]]
    });

    
    /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/

});

 $.ajax({
        url : base_url+"pramaan/bd_district_coordinator_list/"+"<?php echo $parent_id;?>",
        type: "POST",
        dataType: "JSON",
        success: function(msg)
        {
           /* alert(msg[0]['name']);*/
          /* alert(msg.length);*/
           var no_of_sourcing_head=msg.length;
           var table = document.getElementById("tb");
           var i=0;
           var row=[];
           var cell1=[];
           var cell2=[];
           var cell3=[];
           var cell4=[];
           var cell5=[];
           var cell6=[];
           var cell7=[];
      
           
           
           for(i=0;i<no_of_sourcing_head;i++)
           {    
                row[i]=table.insertRow(i);
                cell1[i] = row[i].insertCell(0);
                cell2[i] = row[i].insertCell(1);
                cell3[i] = row[i].insertCell(2);
                cell4[i] = row[i].insertCell(3);
                cell5[i] = row[i].insertCell(4);
                cell6[i] = row[i].insertCell(5);

             <?php  
  if($user_group_id==11)
{

    ?>   
                cell7[i] = row[i].insertCell(6);
      <?php
}
      ?>         
                
                 // Add some text to the new cells:
                cell1[i].innerHTML = msg[i]['sno'];
                cell2[i].innerHTML = msg[i]['name'];
                cell3[i].innerHTML = msg[i]['email'];
                cell4[i].innerHTML = msg[i]['phone'];
               
                
                cell5[i].innerHTML = msg[i]['district_name'];


                               <?php  
                    if($user_group_id==11)
                  {
                   ?>   
                     var flag_html;
 
                if(msg[i]['active_status'])
                   { 
                    
                   
 
                   flag_html =  '<button class="btn btn-primary btn-xs btn-success" data-title="Edit" onclick="show('+msg[i]['user_id']+',1);">Active</button>';

                  

                   

                   }  

                 else
                 {

                   
                  
                          flag_html =  '<button class="btn btn-primary btn-xs btn-danger" data-title="Edit" onclick="show('+msg[i]['user_id']+',2);">Inactive</button>';

                 

                 }
               
                 cell6[i].innerHTML =  flag_html;  
                
                /*cell5[i].innerHTML = '<button onclick='get_regional_manager("+msg[i]['user_id']+");' class="btn btn-primary btn-xs" data-title="Edit" ><span class="glyphicon glyphicon"></span></button>';*/

                cell7[i].innerHTML = '<a class="btn btn-primary btn-xs" href="'+base_url+'pramaan/edit_bd_district_coordinator/<?php echo $parent_id; ?>/'+msg[i]['user_id']+'" title="Edit Region" ><i class="glyphicon glyphicon-pencil"></i></a>';
<?php
}
else{
      ?> 
 cell6[i].innerHTML =  msg[i]['bd_regional_manager_name'];  
 <?php
}
 ?>
                
           }
           
            
            


           



        },
        error: function (jqXHR, textStatus, errorThrown)
        {
          //  alert('Error');
        }
    });
function get_sourcing_partner(user_id)
{
   /* alert(user_id);*/
   $.ajax({
        url : base_url+"pramaan/sourcing_partners_list_by_district/"+user_id,
        type: "POST",
        dataType: "JSON",
        success: function(msg)
        {
        
           /* alert(msg[0]['name']);*/
           /*alert(msg.length);*/
           var no_regional_managers_list=msg.length;
           if(no_regional_managers_list==0)
           {
             document.getElementById("regional_manager").innerHTML="<center><p>No Data Found!</p></center>";
           }
           else
           {
               var i=0;
           var x="";
           x=x+"<table class='table table-striped' >";
            x=x+"<tr>";
                x=x+"<td><b>"+"Sno"+"</b></td>";
                x=x+"<td><b>"+"Sourcing Partner Name"+"</b></td>";
              
                x=x+"<td><b>"+"Phone"+"</b></td>";
                
            x=x+"</tr>";  
           for(i=0;i<no_regional_managers_list;i++)
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
                x=x+"<td>"+msg[i]['sno']+"</td>";
                x=x+"<td>"+msg[i]['name']+"</td>";
               
                x=x+"<td>"+msg[i]['phone']+"</td>";
              
                
            x=x+"</tr>";  
                 
               




           }
           x=x+"</table>";
              document.getElementById("regional_manager").innerHTML=x;
            


           }

        
           



        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error');
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
        data: $('#form_sourcing_head').serialize(),
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

                    $('[name="'+ key +'"]', '#form_associates').closest('.form-group').find('.error_label').html(val).css( "background-color", "red" );
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

function sub_admin_desk(sourcing_head_id)
{
  if(sourcing_head_id)
    {
        var url=(base_url+'pramaan/sourcing_managers/'+sourcing_head_id);
        document.location.href=url;
    }
}

/*function show(id)
{
    
  
    var r = confirm("Are you sure you want to perform this action? (Activate/Deactivate)");
    if (r == true) {
        //change status here
        var ar=[id];
        var url;
         url = base_url+"pramaan/change_dis_cor_status";
         $.ajax({
        url : url,
        data:{ar:ar},
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
         
            if (data.status == true) 
              {
                window.location.href = base_url+'pramaan/district_coordinators/'+"<?=$parent_id ?>";
               
              }
            else
            { 
              alert("ERROR!!");

              $.each(data.errors, function(key, val) 
              {
                $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
              });
              $("#sourcing_head_form").valid();
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
        
    }

}
*/

function show(id,current)
{
      var ar=[id,'bd_district_coordinator'];
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
          closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm) {
        var url;
        url = base_url+"pramaan/change_status";
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
               window.location.href = base_url+'pramaan/bd_coordinators/'+"<?=$parent_id ?>";
               
              }
            else
            { 
              alert("ERROR!!");

              $.each(data.errors, function(key, val) 
              {
                $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
              });
              $("#sourcing_head_form").valid();
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
                      
        var url;
        url = base_url+"pramaan/change_status";
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
               window.location.href = base_url+'pramaan/bd_coordinators/'+"<?=$parent_id ?>";
               
              }
            else
            { 
              alert("ERROR!!");

              $.each(data.errors, function(key, val) 
              {
                $('[name="'+ key +'"]', form).closest('.form-group').find('.error_label').html(val);
              });
              $("#sourcing_head_form").valid();
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

<!-- Bootstrap modal -->
<div class="inner">
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">Sourcning Head Form</h3>
            </div>
                <div class="modal-body form">
                <form action="#" id="form_sourcing_head" class="form-horizontal">
                    <input type="hidden" value="" name="id"/>
                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-3">Sourcing Head Name<span class='validmark'>*</span></label>
                            <div class="col-md-9">
                                <input name="pname" placeholder="Sourcing Head Name" class="form-control" type="text">
                                <span class="error_label"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Phone<span class='validmark'>*</span></label>
                             <div class="col-md-9">
                                  <input name="phone" placeholder="Phone/mobile" class="form-control" type="text" maxlength="<?= PHONE_MAX?>">
                                  <span class="error_label"></span>
                              </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-3">Email<span class='validmark'>*</span></label>
                            <div class="col-md-9">
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



                    


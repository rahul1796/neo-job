
<script type="text/javascript">
$(document).ready(function() {
    $('#table').DataTable();
} );

</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#myTable').DataTable();
} );

</script>

<script type="text/javascript">
$(document).ready(function() {
    $('#regional_manager_list').DataTable();
} );

</script>
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
    <li><?php echo anchor("pramaan/dashboard","Dashboard");?></li>
  
    <li class="active"> Sourcing Partner Admins</li>
  </ul>
</small>
<hr/>
  <div class="row" style="margin-bottom: 5px;">
     <div class="col-sm-3 col-md-3">
        <a class="btn btn-success btn-sm" href="<?php echo base_url("partner/add_spoc/$parent_id")?>"><i class="glyphicon glyphicon-plus"></i> Add Sourcing Partner Admin</a>
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
  <table id="table" class="display" cellspacing="0" width="100%">
       <thead>
            <tr>
                <th>SNo</th>
                <th>Sourcing Partner Admin</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Status</th>
                
                <th>Action</th>
            </tr>
        </thead>
       <!--  <tfoot>
            <tr>
               <th>SNo</th>
                <th>Sourcing head name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Regional Managers</th>
                <th>Action</th>
            </tr>
        </tfoot> -->
        <tbody id="tb">
            
        </tbody>
    </table>

   <!--   <table id="regional_manager" class="display" cellspacing="0" width="100%">
       <thead>
            <tr>
                <th>SNo</th>
                <th>Regional Manager name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Regions</th>
                
            </tr>
        </thead>
        <tfoot>
            <tr>
               <th>SNo</th>
                <th>Sourcing head name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Regional Managers</th>
                <th>Action</th>
            </tr>
        </tfoot>
        <tbody id="tb_rm">
            
        </tbody>
    </table> -->
     <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <center><h4 class="modal-title"><b>Regional Managers</b></h4></center>
        </div>
        <div class="modal-body" id="regional_manager" style="overflow: scroll; height:400px;">
                
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
                <h3 class="modal-title">Sourcing Managers Details</h3>
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

 $.ajax({
        url : base_url+"partner/get_spoc_list/"+"<?php echo $parent_id;?>",
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
         
           
           
           for(i=0;i<no_of_sourcing_head;i++)
           {    
                row[i]=table.insertRow(i);
                cell1[i] = row[i].insertCell(0);
                cell2[i] = row[i].insertCell(1);
                cell3[i] = row[i].insertCell(2);
                cell4[i] = row[i].insertCell(3);
                cell5[i] = row[i].insertCell(4);
                cell6[i] = row[i].insertCell(5);
               
                
                 // Add some text to the new cells:
                cell1[i].innerHTML = msg[i]['sno'];
                cell2[i].innerHTML = msg[i]['name'];
                cell3[i].innerHTML = msg[i]['email'];
                cell4[i].innerHTML = msg[i]['phone'];

              
                 var flag_color;
                var flag_html;
 
                if(msg[i]['active_status'])
                   { 
                    
                    flag_color = 'green';
 
                   flag_html =  '<button style="width:100%" class="btn btn-primary btn-xs btn-success" data-title="Edit" onclick="show('+msg[i]['user_id']+');">Active</button>';

                  

                   

                   }  

                 else
                 {

                   
                   flag_color = 'red';
                          flag_html =  '<button  style="width:100%" class="btn btn-primary btn-xs btn-danger" data-title="Edit" onclick="show('+msg[i]['user_id']+');">Inactive</button>';

                 

                 }
               
                 cell5[i].innerHTML =  flag_html; 


                /*cell5[i].innerHTML = '<button onclick='get_regional_manager("+msg[i]['user_id']+");' class="btn btn-primary btn-xs" data-title="Edit" ><span class="glyphicon glyphicon"></span></button>';*/
/*                sourcing_admin_id*/
                cell6[i].innerHTML = '<a class="btn btn-primary btn-xs" href="'+base_url+'partner/edit_spoc/<?php echo $parent_id; ?>/'+msg[i]['user_id']+'" title="Edit Sourcing Partner Admin" ><i class="glyphicon glyphicon-pencil"></i></a>';


           }
           
            
            


           



        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error');
        }
    });



function sub_admin_desk(sourcing_head_id)
{
  if(sourcing_head_id)
    {
        var url=(base_url+'pramaan/sourcing_managers/'+sourcing_head_id);
        document.location.href=url;
    }
}


function show(id)
{
    
  
    var r = confirm("Are you sure you want to perform this action? (Activate/Deactivate)");
    if (r == true) {
        //change status here
        var ar=[id];
        var url;
         url = base_url+"partner/change_spoc_status";
         $.ajax({
        url : url,
        data:{ar:ar},
        type: "POST",
        dataType: "JSON",
        success: function(data)
        {
         
            if (data.status == true) 
              {
                window.location.href = base_url+'partner/spoc_list/'+"<?=$parent_id ?>";
               
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



                    

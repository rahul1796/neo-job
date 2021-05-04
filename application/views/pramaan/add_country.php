
<script type="text/javascript">
$(document).ready(function() {
    $('#table_country').DataTable();
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
    <?php if($parent_page)
        echo '<li>'.anchor($parent_page,$parent_page_title).'</li>';
    ?>
    <li class="active"> Country</li>
  </ul>
</small>
<hr/>
  <div class="row" style="margin-bottom: 5px;">
    <div class="col-sm-3 col-md-3">
        <a class="btn btn-success btn-sm" href="<?php echo base_url("pramaan/add_new_country")?>"><i class="glyphicon glyphicon-plus"></i> Add Country</a>
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
  <table id="table_country" class="display" cellspacing="0" width="100%">
       <thead>
            <tr>
                <th>SNo</th>
                <th>Country Name</th>
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
        <div class="modal-body" id="regional_manager">
                
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
        url : base_url+"pramaan/add_country_list",
        type: "POST",
        dataType: "JSON",
        success: function(msg)
        {
           /* alert(msg[0]['name']);*/
          /* alert(msg.length);*/
           /*alert(msg[1]);*/
          /* alert(msg.length);*/
           var no_of_sourcing_head=msg.length;
           var table_country = document.getElementById("tb");
           var i=0;
           var row=[];
           var cell1=[];
           var cell2=[];
           var cell3=[];
           var cell4=[];
           
           
          
           
           for(i=0;i<no_of_sourcing_head;i++)
           {    
                row[i]=table_country.insertRow(i);
                cell1[i] = row[i].insertCell(0);
                cell2[i] = row[i].insertCell(1);
                cell3[i] = row[i].insertCell(2);
                cell4[i] = row[i].insertCell(3);
                  
              
                
                 // Add some text to the new cells:country_id
                cell1[i].innerHTML = msg[i]['id'];
                cell2[i].innerHTML = msg[i]['name'];
                if(msg[i]['active_status']=='1')
                  cell3[i].innerHTML = 'Active';
                else
                  cell3[i].innerHTML = 'Inactive';

                
               
          
               

                cell4[i].innerHTML = "<button class='btn btn-primary btn-xs' data-title='Edit' ><span class='glyphicon glyphicon-pencil'></span></button>&nbsp;<button class='btn btn-primary btn-xs' data-title='Edit' onclick='change_country_status("+msg[i]['country_id']+","+msg[i]['active_status']+");'><span class='glyphicon glyphicon-flag'></span></button>";



           }
           
            
            


           



        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error');
        }
    });

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


function change_country_status(country_id,active_status)
{
 /* alert(country_id);*/
  var country_ar=[country_id,active_status];
  
  var url;
  url = base_url+"pramaan/change_status_country";
   $.ajax({
        url : url,
        type: "POST",
        data: {country_ar:country_ar},
        dataType:"JSON",
        success: function(msg)
        {
        
            window.location.href = base_url+'pramaan/add_country';
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert("ERROR!!!!!");
        }
    });
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



                    

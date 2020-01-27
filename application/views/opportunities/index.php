<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
<script>
  var statusOptions = JSON.parse('<?= json_encode($lead_status_options); ?>');
  console.log(statusOptions);
</script>
<style>
    .label{
        float: left;
        padding-right: 4px;
        padding-top: 2px;
        position: relative;
        text-align: right;
        vertical-align: middle;
    }
    .label:before{
        content:"*" ;
        color:red
    }

</style>
<div class="content-body" style="padding: 30px; margin-top: 10px;">
  <div class="row">
    <div class="col-md-12">
      <?php if(isset($_SESSION['status'])): ?>
      <div class="alert alert-primary" id="server-alert" role="alert">
          <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h5><?php echo $_SESSION['status']; ?></h5>
      </div>
      <br><br>
      <?php endif; ?>
    </div>

  </div>


    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;margin-top: -34px;">

      <div class="row">
        <div class="col-md-12">
          <h2>Available Opportunities</h2>
        </div>
        <br>
      </div>
        <div class="breadcrumb-wrapper col-xs-12" style="margin-left: -16px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Opportunities
                </li>
            </ol>
        </div>
    </div>
    <section>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Opportunities</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload" onclick="reload_table()"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form id="search" style="padding: 15px;">
                            <div class="row">
                              <div class="col-md-2 pl-1">
                                    <div class="form-group">
                                        <label for="search_by">Search By:</label>
                                        <select class="form-control" id="search_by" name="search_by" style="width: 250px;" onchange="searchby_onchange(this.value)">
                                        <option value="0"> -Select-</option>
                                        <option value="1">Company Name</option>
                                        <option value="2">Status</option>
                                        <option value="3">Opportunity Code</option>
                                        <option value="4">Contract Id</option>                                        
                                        <option value="5">Product</option>                                        
                                        <option value="6">Industry</option>
                                        <option value="7">Labournet Entity</option>
                                       
                                    </select>
                                        <input type="text" class="form-control hidden" id="searchbox" name="searchbox" value="" placeholder="Search here" style="width: 380px; margin-top: -33px; margin-left: 270px;">
                                        <div class="hidden" id="customer_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="customer_list" name="customer_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Company Name</option>
                                                <?php foreach($company_name_options as $option): ?>
                                                  <option value="<?php echo $option->company_name; ?>"><?php echo $option->company_name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>
                                        <select class="form-control hidden" name="status_id" id="status_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                            <option value="0">Select Status</option>
                                            <?php foreach($lead_status_options as $option): ?>
                                              <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                            <?php endforeach; ?>
                                          </select>

                                        <div class="hidden" id="spoc_name_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="opportunity_code" name="opportunity_code" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Opportunity Code</option>
                                                <?php foreach($opportunity_code_options as $option): ?>
                                                  <option value="<?php echo $option->opportunity_code; ?>"><?php echo $option->opportunity_code; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <div class="hidden" id="spoc_email_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="contract_id" name="contract_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Contract ID</option>
                                                <?php foreach($contract_id_options as $option): ?>
                                                  <option value="<?php echo $option->contract_id; ?>"><?php echo $option->contract_id; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>
                                           
                                             <select class="form-control hidden" name="buisness_vertical" id="buisness_vertical" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select Buisness Vertical</option>
                                                <?php foreach($business_vertical_options as $option): ?>
                                                  <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                              <select class="form-control hidden" name="industry" id="industry" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select Industry</option>
                                                <?php foreach($industries_list_options as $option): ?>
                                                  <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                              <select class="form-control hidden" name="ln_entity" id="ln_entity" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select Labournet Entity</option>
                                                <?php foreach($ln_entity_options as $option): ?>
                                                  <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                                <?php endforeach; ?>
                                              </select>

                                        <label id="lblsearchbox" style="color:red; display: none;margin-left: 12%;">* Please Enter Search Value</label>

                                       </div>
                                  </div>
                                <label class="hidden" id="lblSearchError" style="color:red;display:block ;margin-left: 120px;  float: left; margin-top: 63px;"></label>
                            </div>
                            <div class="text-center hidden" style="margin-bottom: 5px;  margin-left: 670px;  margin-top: -55px;" name="search_btn" id="search_btn">
                            <a class="btn btn-primary btn-md " onclick="btnSearch_OnClick()" style="color: white; cursor: pointer;"><i class="fa fa-search "></i> Search</a>
                                <Button type="button" onclick="window.location.reload();" class="btn btn-secondary btn-md "> Clear Search</Button>
                            </div>
                                </form>
                        </div>
                    </div>

                    <div class="card-body collapse in" style="font-size:0.90rem;">
                        <div class="card-block card-dashboard">
                            <table id="tblList" class="table table-striped table-bordered display responsive nowrap" style="margin-left: 0px!important; ">
                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Actions</th>
                                    <th>Company Name</th>
                                    <th>Status</th>
                                    <th>Oppurtunity Code</th>
                                    <th>Contract id</th>
                                    <th>Product</th>
                                    <th>Industry</th>
				                    <th>Labournet Entity</th>

                                </tr>
                                </thead>
                                <tbody id="tblBody">
                                </tbody>
                            </table>
                           <?php /*echo $this->pagination->create_links();*/?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- File export table -->
</div>


   </div>
 </div>
<div class="wrapper">

	<?php

			$this->load->view('common/footer');
	?>
	</div>
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
<script src="https://cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url().'adm-assets/vendors/datatables.min.js'?>"></script>
<script src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js" type="text/javascript"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
<script>
          $(document).ready(function(){
        $('#search_by').on('change', function(){
            $('#lblSearchError').html('');
        });
    });
    var varTable;
    function searchby_onchange(varSearchByValue)
    {
        if (varSearchByValue=='0')
        {
            LoadTableData();
            return;
        }
        //lert(varSearchByValue);
        $("#customer_list").selectedIndex = "0";
        $("#status_id").val('0'); 
        $("#opportunity_code").selectedIndex = "0";
        $("#contract_id").selectedIndex = "0";  
        $("#buisness_vertical").val('0');
        $("#industry").val('0');
        $("#ln_entity").val('0');
        $("#searchbox").val('');
    }

    function LoadTableData()
    {
        var varSearchTypeId = $("#search_by").val(),
            varSearchValue = $("#searchbox").val();

        switch(varSearchTypeId)
        {
             case "1":
                varSearchValue = $("#customer_list option:selected").text();
                break;
            case "2":
                varSearchValue = $("#status_id").val();
                break;

             case "3":
                varSearchValue = $("#opportunity_code option:selected").text();
                break;

            case "4":
            varSearchValue = $("#contract_id option:selected").text();
            break;

             case "5":
                varSearchValue = $("#buisness_vertical").val();
                break;

            case "6":
                varSearchValue = $("#industry").val();
                break;

            case "7":
                varSearchValue = $("#ln_entity").val();
                break;
        }

        if (varTable != undefined && varTable != null)
        {
            varTable.clear().destroy();
        }

        varTable = $("#tblList").DataTable({
            "serverSide": true,
            "paging": true,
            "scrollX": true,
            "language": { processing: '<div style="margin-left:-800px;margin-top:50px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
            "aLengthMenu": [[10, 25, 50, 100, 200, -1],[10, 25, 50, 100, 200, "All"]],
            "pageLength": 10,
            "searching": false,
            "ajax": {
                "url": base_url+"opportunitiescontroller/getOpporunityList/",
                "type": "POST",
                "data": function (d) {
                    d.search_type_id = varSearchTypeId;
                    d.search_value = varSearchValue;
                },
                "error": function() {
                    $("#tblList tbody").empty().append('<tr><td style="text-align: center;" colspan="14">No data found</td></tr>');
                }
            },
            "columnDefs":
                [
                    {
                        "targets": [0, 1 ],
                        "orderable": false
                    }
                ],
            "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
            buttons: []
        });
    }

    function btnSearch_OnClick()
    {
        $("#lblSearchError").hide();
        var varSearchTypeId = $("#search_by").val(),
            varSearchValue = $("#searchbox").val();

        switch(varSearchTypeId)
        {
            case "1":
                if ($("#customer_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select company!');
                    $("#lblSearchError").show();
                    return;
                }
                break;
           

              case "2":
                if ($("#status_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select company status!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "3":
                if ($("#opportunity_code option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select opportunity code!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "4":
                if ($("#contract_id option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select contract id!');
                    $("#lblSearchError").show();
                    return;
                }
                break;
              
                case "5":
                if ($("#buisness_vertical").val() == '0')
                {
                    $("#lblSearchError").text('* Please select buisness vertical!');
                    $("#lblSearchError").show();
                    return;
                }
                break;


                case "6":
                if ($("#industry").val() == '0')
                {
                    $("#lblSearchError").text('* Please select industry!');
                    $("#lblSearchError").show();
                    return;
                }
                break;
            

             case "7":
                if ($("#ln_entity").val() == '0')
                {
                    $("#lblSearchError").text('* Please select Labournet Entity!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            default:
                if (varSearchValue.trim() == '')
                {
                    switch(varSearchTypeId)
                    {
//                        case "1":
//                        $("#lblSearchError").text('* Please input Customer name!');
//                        break;
//                        case "4":
//                        $("#lblSearchError").text('* Please input Spoc Name!');
//                        break;
//                         case "5":
//                        $("#lblSearchError").text('* Please input Spoc Email!');
//                        break;
//                         case "6":
//                        $("#lblSearchError").text('* Please input Spoc Phone');
//                        break;
//                         case "7":
//                        $("#lblSearchError").text('* Please input Location!');
//                        break;
                    }

                    $("#lblSearchError").show();
                    return;
                }
                break;
        }

        LoadTableData();
    }

    $(document).ready(function() {
        $(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="icon-refresh"></i> Reload</button>').appendTo('div#table_length');
        LoadTableData();
    });



function reload_table()
{
    table.ajax.reload(null, false);
}

</script>
<script>
$(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#searchbox').addClass('hidden');
            }
            else {
                $('#searchbox').removeClass('hidden');
                $('#searchbox').focus();
            }
        });
    });
   
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#status_id').addClass('hidden');
            }
            else {
                $('#status_id').removeClass('hidden');
                $('#status_id').focus();
            }
        });
    });
   
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '7') {
                $('#industry').addClass('hidden');
            }
            else {
                $('#industry').removeClass('hidden');
                $('#industry').focus();
            }
        });
    });
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' ||  $('#search_by').val() == '6' || $('#search_by').val() == '7' ) {
                $('#buisness_vertical').addClass('hidden');
            }
            else {
                $('#buisness_vertical').removeClass('hidden');
                $('#buisness_vertical').focus();
            }
        });
    });

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' ) {
                $('#ln_entity').addClass('hidden');
            }
            else {
                $('#ln_entity').removeClass('hidden');
                $('#ln_entity').focus();
            }
        });
    });
     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0') {
                $('#search_btn').addClass('hidden');
            }
            else {
                $('#search_btn').removeClass('hidden');
                $('#search_btn').focus();
            }
        });
    });
     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0') {
                $('#lblSearchError').addClass('hidden');
            }
            else {
                $('#lblSearchError').removeClass('hidden');
                $('#lblSearchError').focus();
            }
        });
    });

     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#customer_list_container').addClass('hidden');
            }
            else {
                $('#customer_list_container').removeClass('hidden');
                $('#customer_list_container').focus();
            }
        });
    });

     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '4' || $('#search_by').val() == '5' ||  $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#spoc_name_list_container').addClass('hidden');
            }
            else {
                $('#spoc_name_list_container').removeClass('hidden');
                $('#spoc_name_list_container').focus();
            }
        });
    });


     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '5' ||  $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#spoc_email_list_container').addClass('hidden');
            }
            else {
                $('#spoc_email_list_container').removeClass('hidden');
                $('#spoc_email_list_container').focus();
            }
        });
    });

        
    $(document).ready(function() {
        $('.select2-neo').select2();
      });

      window.setTimeout(function() {
      $("#server-alert").fadeTo(500, 0).slideUp(500, function(){
          $(this).remove();
      });
  }, 4000);
</script>
 
<?php $this->load->view('opportunities/lead_history_modal'); ?>
<?php $this->load->view('opportunities/spoc_list_modal'); ?>
<?php $this->load->view('opportunities/lead_status_change_modal', ['lead_status_options'=>$lead_status_options]); ?>


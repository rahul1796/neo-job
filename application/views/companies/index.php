<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
<<link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/responsive.dataTables.min.css'?>">

<div class="content-body" style="padding: 30px; margin-top: 10px;">
  <div class="row">
    <div class="col-md-12">
    <?php $this->load->view('layouts/errors'); ?>
    </div>

  </div>

  <?php if (in_array($this->session->userdata('usr_authdet')['user_group_id'], lead_add_roles())): ?>
    <a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php echo base_url("companiescontroller/create")?>" style="float: right;"><i class="icon-android-add"></i>Add Company</a>
  <?php endif; ?>


    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;margin-top: -5px;">

      <div class="row">
        <div class="col-md-12">
          <h2>Available Companies</h2>
        </div>
        <br>
      </div>
        <div class="breadcrumb-wrapper col-xs-12" style="margin-left: -16px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Company
                </li>
            </ol>
        </div>
    </div>
    <section>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Companies</h4>
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
                                        <option value="2">Source</option>
                                        <option value="3">Spoc Name</option>
                                        <option value="4">Spoc Email</option>
                                        <option value="5">Spoc Phone</option>
                                        <option value="6">State</option>
                                        <option value="7">Industry</option>
                                        <option value="8">Functional Area</option>
                                    </select>
                                        <input type="text" class="form-control hidden" id="searchbox" name="searchbox" value="" placeholder="Search here" style="width: 380px; margin-top: -33px; margin-left: 270px;">
                                        <div class="hidden" id="customer_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="customer_list" name="customer_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Customer Name</option>
                                                <?php foreach($company_name_options as $option): ?>
                                                  <option value="<?php echo $option->company_name; ?>"><?php echo $option->company_name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>
                                        <select class="form-control hidden" name="source_id" id="source_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                            <option value="0">Select Source</option>
                                            <?php foreach($lead_source_options as $option): ?>
                                              <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                            <?php endforeach; ?>
                                          </select>
                                        
                                        <select class="form-control hidden" name="industry_id" id="industry_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                            <option value="0">Select Industry</option>
                                            <?php foreach($industries_list_options as $option): ?>
                                              <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                            <?php endforeach; ?>
                                          </select>
                                         <select class="form-control hidden" name="functional_area_id" id="functional_area_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                            <option value="0">Select Functional Area</option>
                                            <?php foreach($functional_area_list_options as $option): ?>
                                              <option value="<?php echo $option->id; ?>"><?php echo $option->name; ?></option>
                                            <?php endforeach; ?>
                                          </select>
                                             

                                        <div class="hidden" id="spoc_name_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="spoc_name_list" name="spoc_name_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Spoc Name</option>
                                                <?php foreach($spoc_name_list_options as $option): ?>
                                                  <option value="<?php echo $option->spoc_name; ?>"><?php echo $option->spoc_name; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <div class="hidden" id="spoc_email_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="spoc_email_list" name="spoc_email_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Spoc Email</option>
                                                <?php foreach($spoc_email_list_options as $option): ?>
                                                  <option value="<?php echo $option->spoc_email; ?>"><?php echo $option->spoc_email; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>

                                            <div class="hidden" id="spoc_phone_list_container" style="float: right; margin-top: -35px; margin-right: -505px;">
                                              <select class="form-control select2-neo" id="spoc_phone_list" name="spoc_phone_list" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="">Select Spoc Phone</option>
                                                <?php foreach($spoc_phone_list_options as $option): ?>
                                                  <option value="<?php echo $option->spoc_phone; ?>"><?php echo $option->spoc_phone; ?></option>
                                                <?php endforeach; ?>
                                              </select>
                                            </div>
                                             <select class="form-control hidden" name="state_id" id="state_id" style="margin-left: 270px; margin-top: -33px; width: 380px;">
                                                <option value="0">Select State</option>
                                                <?php foreach($state_options as $option): ?>
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
                                    <th>Opportunities</th>
                                    <th>Description</th>
                                    <th>Industry</th>
                                    <th>Functional Area</th>
				                    <th>SPOC Name</th>
                                    <th>SPOC Email</th>
                                    <th>SPOC Phone</th>
                                    <th>State</th>
                                    <th>District</th>
                                    <th>Source</th>
                                    <th>Remarks</th>
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
        // $("#customer_type_id").val('0');
        $("#source_id").val('0');        
        $("#industry_id").val('0');
        $("#functional_area_id").val('0');
         $("#state_id").val('0');
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
                varSearchValue = $("#source_id").val();
                break;

             case "3":
                varSearchValue = $("#spoc_name_list option:selected").text();
                break;

            case "4":
            varSearchValue = $("#spoc_email_list option:selected").text();
            break;

             case "5":
            varSearchValue = $("#spoc_phone_list option:selected").text();
            break;

             case "6":
                varSearchValue = $("#state_id").val();
                break;

            case "7":
                varSearchValue = $("#industry_id").val();
                break;

            case "8":
                varSearchValue = $("#functional_area_id").val();
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
            "aLengthMenu": [[10, 25, 50, 100, 200, -1], [10, 25, 50, 100, 200, "All"]],
            "pageLength": 10,
            "searching": false,
            "language": { "loadingRecords": "Loading..." },
            "ajax": {
                "url": base_url+"companiescontroller/getCompanyList/",
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
                if ($("#source_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select company source!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "3":
                if ($("#spoc_name_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select spoc name!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "4":
                if ($("#spoc_email_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select spoc email!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                 case "5":
                if ($("#spoc_phone_list option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select spoc phone!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

                case "6":
                if ($("#state_id option:selected").index() < 1)
                  {
                    $("#lblSearchError").text('* Please select state!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

            

            case "7":
                if ($("#industry_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select industry!');
                    $("#lblSearchError").show();
                    return;
                }
                break;

             case "8":
                if ($("#functional_area_id").val() == '0')
                {
                    $("#lblSearchError").text('* Please select functional area!');
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
                $('#source_id').addClass('hidden');
            }
            else {
                $('#source_id').removeClass('hidden');
                $('#source_id').focus();
            }
        });
    });
   
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '8') {
                $('#industry_id').addClass('hidden');
            }
            else {
                $('#industry_id').removeClass('hidden');
                $('#industry_id').focus();
            }
        });
    });
    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '6' || $('#search_by').val() == '7' ) {
                $('#functional_area_id').addClass('hidden');
            }
            else {
                $('#functional_area_id').removeClass('hidden');
                $('#functional_area_id').focus();
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

    $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' ||  $('#search_by').val() == '6' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#spoc_phone_list_container').addClass('hidden');
            }
            else {
                $('#spoc_phone_list_container').removeClass('hidden');
                $('#spoc_phone_list_container').focus();
            }
        });
    });

     $(document).ready(function () {
        $('#search_by').change(function () {
            if ($('#search_by').val() == '0' || $('#search_by').val() == '1' || $('#search_by').val() == '2' || $('#search_by').val() == '3' || $('#search_by').val() == '4' || $('#search_by').val() == '5' || $('#search_by').val() == '7' || $('#search_by').val() == '8') {
                $('#state_id').addClass('hidden');
            }
            else {
                $('#state_id').removeClass('hidden');
                $('#state_id').focus();
            }
        });
    });
    $(document).ready(function() {
        $('.select2-neo').select2();
      });

      function view_opportunity(company_id)
  {
    var opportunity_url=base_url+'companiescontroller/getOpportunityDetail/'+company_id;
    $.ajax({
          url : opportunity_url,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
              var opportunity_list_html='';
              if(data.status)
              {
                  var company=data.company_detail;
                  var slno=1;
                  opportunity_list_html += "<div  style='margin-bottom: 10px'>Company Name: <span style='font-weight: bold;'>"+company.company_name+"</span></div>";
                  
                  opportunity_list_html += '<div class="row">';
                  opportunity_list_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; height: 400px;">';
                  opportunity_list_html += '<table id="tblApplicationTrackerDetails" class="table table-striped table-bordered display responsive nowrap">';
                  opportunity_list_html += '<tr><th>Opportunity No.</th><th>Opportunity Code</th><th>Product Name</th><th>Contract ID</th><th>Created On</th><th>Spoc Name</th><th>Spoc Email</th><th>Spoc Phone</th></tr>';

                  $.each(data.opportunity_detail,function(a,b)
                  {
                    opportunity_list_html += '<tr><td>'+slno+'</td><td>'+b.opportunity_code+'</td><td>'+b.business_vertical+'</td><td>'+b.contract_id+'</td><td>'+b.created_at+'</td><td>'+b.spoc_name+'</td><td>'+b.spoc_email+'</td><td>'+b.spoc_phone+'</td></tr>';
                    slno++;
                  });

                  opportunity_list_html += '</table>';
                  opportunity_list_html += '</div></div>'; 
              }
              $('.candidate_job_status').html(opportunity_list_html);

              $("#tblApplicationTrackerDetails").DataTable();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });
      $('#modal_view_opportunity').modal('show'); // show bootstrap modal when complete loaded
  }
</script>
<div id="modal_view_opportunity" class="modal fade bs-example-modal-xl" role="dialog">
      <div class="modal-dialog modal-xl" role="document" >
          <div class="modal-content">
              <div class="modal-header" style="border-bottom:hidden;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title">Opportunity Details</h3>
              </div>
              <div class="modal-body candidate_job_status">
                  -No records found-
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              </div>
          </div>
      </div>
  </div>
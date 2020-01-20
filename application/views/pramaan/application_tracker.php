<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css">
<?php $user=$this->pramaan->_check_module_task_auth(true); ?>
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
.table td, .table th {
    padding: 0.75rem 0.75rem;
}


.nav {
	padding-left: 0;
	margin-bottom: 0;
	list-style: none
}
.nav>li {
	position: relative;
	display: block
}
.nav>li>a {
	position: relative;
	display: block;
	padding: 10px 15px
}
.nav>li>a:hover, .nav>li>a:focus {
	text-decoration: none;
	/*background-color: #eee*/
}
.nav>li.disabled>a {
	color: #777
}
.nav>li.disabled>a:hover, .nav>li.disabled>a:focus {
	color: #777;
	text-decoration: none;
	cursor: not-allowed;
	background-color: transparent
}
.nav .open>a, .nav .open>a:hover, .nav .open>a:focus {
	/*background-color: #eee;*/
	border-color: #428bca
}
.nav .nav-divider {
	height: 1px;
	margin: 9px 0;
	overflow: hidden;
	background-color: #e5e5e5
}
.nav>li>a>img {
	max-width: none
}
.nav-tabs {
	border-bottom: 1px solid #ddd
}
.nav-tabs>li {
	float: left;
	margin-bottom: -1px
}
.nav-tabs>li>a {
	margin-right: 2px;
	line-height: 1.42857143;
	border: 1px solid transparent;
	border-radius: 4px 4px 0 0
}
.nav-tabs>li>a:hover {
	border-color: #eee #eee #ddd
}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:hover, .nav-tabs>li.active>a:focus {
	color: #555;
	cursor: default;
	background-color: #fff;
	border: 1px solid #ddd;
	border-bottom-color: transparent
}
.nav-tabs.nav-justified {
	width: 100%;
	border-bottom: 0
}
.nav-tabs.nav-justified>li {
	float: none
}
.nav-tabs.nav-justified>li>a {
	margin-bottom: 5px;
	text-align: center
}
.nav-tabs.nav-justified>.dropdown .dropdown-menu {
	top: auto;
	left: auto
}

.nav-tabs { border-bottom: 2px solid #DDD; }
.nav-tabs > li.active > a, .nav-tabs > li.active > a:focus, .nav-tabs > li.active > a:hover { border-width: 0; }
.nav-tabs > li > a { border: none; color: #ffffff;background: #427280; }
.nav-tabs > li.active > a, .nav-tabs > li > a:hover { border: none;  color: #427280 !important; background: #fff; }
.nav-tabs > li > a::after { content: ""; background: #427280; height: 2px; position: absolute; width: 100%; left: 0px; bottom: -1px; transition: all 250ms ease 0s; transform: scale(0); }
.nav-tabs > li.active > a::after, .nav-tabs > li:hover > a::after { transform: scale(1); }
.tab-nav > li > a::after { background: #427280 none repeat scroll 0% 0%; color: #fff; }
.tab-pane { padding: 15px 0; }
.tab-content{padding:20px}
.nav-tabs > li  {width: 166px;; text-align:center;}

@media all and (max-width:724px){
.nav-tabs > li > a > span {display:none;}	
.nav-tabs > li > a {padding: 5px 5px;}
}

</style>
<?php
$options_sectors=array(''=>'-Select Sector-');
$options_orgTypes=array(''=>'-Select Org type-');
?>
<div class="content-body" style="overflow-x: hidden !important;">

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12">
        <div class="breadcrumb-wrapper col-xs-12">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Application Trackers
                </li>
            </ol>
        </div>
    </div>
    
    

    
    <section id="file-export">
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Application Trackers</h4>
                        <a class="heading-elements-toggle"><i class="icon-ellipsis font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="icon-m	inus4"></i></a></li>
                                <li><a data-action="reload"><i class="icon-reload"></i></a></li>
                                <li><a data-action="expand"><i class="icon-expand2"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-body collapse in">
                        <div class="card-block card-dashboard">
                                    <div class="container">
    <div class="row">
      <div class="col-md-12"> 
        <!-- Nav tabs -->
        <div class="card">
          <ul class="nav nav-tabs" role="tablist">
              <?php if (in_array($user['user_group_id'], application_tracker_qp_roles())): ?>
            <li role="presentation" class="active"><a href="#qp" aria-controls="QP" role="QP" data-toggle="tab"><i class="fa fa-wrench"></i>  <span>QP-Wise</span></a></li>
            <?php endif; ?>
            <?php if (in_array($user['user_group_id'], application_tracker_customer_roles())): ?>
            <li role="presentation"><a href="#customer" aria-controls="customer" role="customer" data-toggle="tab"><i class="fa fa-users"></i>  <span>Customer-Wise</span></a></li>
            <?php endif; ?>
            
             <?php if (in_array($user['user_group_id'], application_tracker_region_roles())): ?>
            <li role="presentation"><a href="#center" aria-controls="center" role="center" data-toggle="tab"><i class="fa fa-map-pin"></i>  <span>Center-Wise</span></a></li>
            <?php endif; ?>

            <?php if (in_array($user['user_group_id'], application_tracker_self_employed_roles())): ?>
            <li role="presentation"><a href="#selfemployed" aria-controls="selfemployed" role="selfemployed" data-toggle="tab"><i class="fa fa-user"></i>  <span>Self Employed</span></a></li>
            <?php endif; ?>


          </ul>

          <!-- Tab panes -->
          <div class="tab-content" style="font-size: 0.90rem;">
            <div role="tabpanel" class="tab-pane active" id="qp">
                <table id="table" class="table table-striped table-bordered">
                                  <thead>
                                  <tr>
                                      <th>SNo</th>
                                      <th style="width:40%!important" nowrap>Qualification Pack</th>
                                      <th nowrap>Sector </th>
                                      <th>Interested</th>
                                      <th>Profile<br>Submitted</th>
                                      <th>Pending<br>Customer<br>Feedback</th>
                                      <th>Profile<br>Accepted</th>
                                      <th>Profile<br>Rejected</th>
                                      <th>Interview<br>Scheduled</th>
                                      <th>Interview<br>Attended</th>
                                      <th>Interview<br>Not Attended</th>
                                      <th>Selected</th>
                                      <th>Rejected</th>
                                      <th>Offer In<br>Pipeline</th>
                                      <th>Offered</th>
                                      <th>Offer<br>Accepted</th>
                                      <th>Offer<br>Rejected</th>
                                      <th>Joined</th>
                                      <th>Not<br>Joined</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
            </div>
              <div role="tabpanel" class="tab-pane" id="customer">
                  <table id="table1" class="table table-striped table-bordered" style="width:100% !important;">
                                  <thead>
                                  <tr>
                                      <th>SlNo.</th>
                                      <th style="width:15%!important" nowrap>Customer Name</th>
                                      <th>Interested</th>
                                      <th>Profile<br>Submitted</th>
                                      <th>Pending<br>Customer<br>Feedback</th>
                                      <th>Profile<br>Accepted</th>
                                      <th>Profile<br>Rejected</th>
                                      <th>Interview<br>Scheduled</th>
                                      <th>Interview<br>Attended</th>
                                      <th>Interview<br>Not Attended</th>
                                      <th>Selected</th>
                                      <th>Rejected</th>
                                      <th>Offer In<br>Pipeline</th>
                                      <th>Offered</th>
                                      <th>Offer<br>Accepted</th>
                                      <th>Offer<br>Rejected</th>
                                      <th>Joined</th>
                                      <th>Not<br>Joined</th>
                                  </tr>
                                  </thead>
                                  <tbody>
                                  </tbody>
                              </table>
              </div>
<!--              <div role="tabpanel" class="tab-pane" id="region">                  
                   <table id="table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                              <thead>
                                  <tr>
                                      <th>SNo</th>
                                      <th>Region</th>                                    
                                  </tr>
                              </thead>                            
                              <tbody> 
                                  <tr>
                                   <td>1</td>
                                   <td>South Region</td>
                                  </tr>
                                  <tr>
                                   <td>2</td>
                                   <td>North Region</td>
                                  </tr>
                              </tbody>
                          </table>
              </div>-->
              
              <!-- State Wise Application Tracker -->
<!--              <div role="tabpanel" class="tab-pane" id="state">
                  <h5 style="margin-left: 30px; margin-bottom: -18px; margin-top: 12px;">Filter By:</h5>
                       <div class="col-sm-12" style="margin-bottom:30px;margin-top:30px;">

                                   <div class="col-sm-4">

                                          <label for="region">Region:</label>
                                          <select class="form-control" id="dropdown1">
                                       <option value="">All Region</option>
                                        <option value="Region New">Region New</option>
                                        <option value="Region 2">Region 2</option>
                                      </select>
                                  </div>
                                 <div class="col-sm-4">
                                      <label for="state">State:</label>
                                      <select class="form-control" id="dropdown2">
                                       <option value="">All States</option>
                                        <option value="Karnataka">Karnataka</option>
                                        <option value="Tamil Nadu">Tamil Nadu</option>
                                      </select>
                                      </div>                                     
                           </div>
                   <table id="table2" class="table table-striped table-bordered" cellspacing="0" width="100%">
                              <thead>
                                  <tr>
                                      <th>SNo</th>
                                      <th>State</th>                                    
                                  </tr>
                              </thead>                            
                              <tbody> 
                                  <tr>
                                   <td>1</td>
                                   <td>Tamil Nadu</td>
                                  </tr>
                                  <tr>
                                   <td>2</td>
                                   <td>Karnataka</td>
                                  </tr>
                              </tbody>
                          </table>
              </div>-->
               <!-- Center Wise Application Tracker -->
              <div role="tabpanel" class="tab-pane" id="center">
                   <table id="tableCenter" class="table table-striped table-bordered">
                              <thead>
                                  <tr>
                                      <th>SlNo.</th>
                                      <th style="width:40%!important" nowrap>Center Name</th>
                                      <th>Interested</th>
                                      <th>Profile<br>Submitted</th>
                                      <th>Pending<br>Customer<br>Feedback</th>
                                      <th>Profile<br>Accepted</th>
                                      <th>Profile<br>Rejected</th>
                                      <th>Interview<br>Scheduled</th>
                                      <th>Interview<br>Attended</th>
                                      <th>Interview<br>Not Attended</th>
                                      <th>Selected</th>
                                      <th>Rejected</th>
                                      <th>Offer In<br>Pipeline</th>
                                      <th>Offered</th>
                                      <th>Offer<br>Accepted</th>
                                      <th>Offer<br>Rejected</th>
                                      <th>Joined</th>
                                      <th>Not<br>Joined</th>                                
                                  </tr>
                              </thead>                            
                              <tbody> 
                                 
                              </tbody>
                          </table>
              </div>

              <!-- Self Employed Application Tracker -->
              <div role="tabpanel" class="tab-pane" id="selfemployed">
              <form id="form-filter" style="margin-top: -22px;">
              <div class="form-row col-md-12">
                    <!-- <div class="form-group col-md-3">
                    <label for="employment_start_date">From</label>
                    <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="employment_start_date" placeholder="Select From Date" name="employment_start_date" value="">
                    </div>
                    <div class="form-group col-md-3">
                    <label for="employment_end_date">To</label>
                    <input type="text" data-provide="datepicker" data-date-format="dd-M-yyyy" class="form-control" id="employment_end_date" placeholder="Select From Date" name="employment_end_date" value="">
                    </div> -->
                    <div class="form-group col-md-12">
                    <label for="center_name">Center Name</label>
                    <select class="form-control select2-neo" name="center_name" id="center_name">
                    <option value="0">Select Center Name</option>
                    <?php foreach($center_name as $option): ?>
                        <option value="<?php echo $option->center_name; ?>"><?php echo $option->center_name; ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                </div>
                <div class="form-row col-md-12">
                    <div class="form-group col-md-4">
                    <label for="batch_code">Batch Code</label>
                    <select class="form-control select2-neo" name="batch_code" id="batch_code">
                    <option value="0">Select Batch Code</option>
                    <?php foreach($batch_code as $option): ?>
                        <option value="<?php echo $option->batch_code; ?>"><?php echo $option->batch_code; ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="form-group col-md-4">
                    <label for="qualification_pack">QP</label>
                    <select class="form-control select2-neo" name="qualification_pack" id="qualification_pack">
                    <option value="0">Select QP</option>
                    <?php foreach($qualification_pack as $option): ?>
                        <option value="<?php echo $option->qualification_pack; ?>"><?php echo $option->qualification_pack; ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>
                    <div class="form-group col-md-4">
                    <label for="enrollment_no">Enrollment ID</label>
                    <select class="form-control select2-neo" name="enrollment_no" id="enrollment_no">
                    <option value="0">Select Enrollment Id</option>
                    <?php foreach($enrollment_no as $option): ?>
                        <option value="<?php echo $option->enrollment_no; ?>"><?php echo $option->enrollment_no; ?></option>
                    <?php endforeach; ?>
                    </select>
                    </div>                   
                </div>
                <div class="form-row col-md-12">
                    <div class="text-center" style="margin-left: 675px;" name="search_btn" id="search_btn">
                        <button type="button" id="btn-filter" class="btn btn-primary">Search</button>
                        <button type="button" id="btn-reset" class="btn btn-default">Reset</button>  
                        <a class="btn btn-success btn-md" href='<?php echo base_url('selfemployedcontroller/export_csv/');?>' style="color: white; cursor: pointer;"><i class="fa fa-download "></i> Download</a>
                    </div>
                </div>
                       
               </form>
                   <table id="tableSelfEmployed" class="table table-striped table-bordered">
                              <thead>
                                  <tr>
                                      <th>SNo.</th>
                                      <th>Region </th>
                                      <th>Batch Code</th>
                                      <th>Center Name</th>
                                      <th>Enrollment ID</th>
                                      <th>Batch Customer Name</th>
                                      <th>Candidate Name </th>
                                      <th>Qualification pack</th>
                                      <th>EMP Start Date</th>
                                      <th>Document Updated on</th>
                                      <th>Document download Link</th>
                                                                
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
  </div>

                          </div>
                      </div>
                  </div>
              </div>
          </div>
      </section>
      <!-- File export table -->

  </div>
  <div id="modal_form_tracker_qp" class="modal fade bs-example-modal-xl" role="dialog">
      <div class="modal-dialog modal-xl" role="document" >
          <div class="modal-content">
              <div class="modal-header" style="border-bottom:hidden;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title">Application Tracker (QP)</h3>
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
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/datatables.min.css'?>">
  <link rel="stylesheet" type="text/css" href="<?php echo base_url().'adm-assets/vendors/css/tables/datatable/dataTables.bootstrap4.min.css'?>">
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
              "language": { processing: '<div style="margin-left:-800px;margin-top:50px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
              "aLengthMenu": [[10, 25, 50, 100, 200, -1],[10, 25, 50, 100, 200, "All"]],
              "pageLength": 10,
              // "order": [], //Initial no order.

              // Load data for the table's content from an Ajax source
              "ajax": {
                  "url": base_url+"pramaan/application_tracker_list",
                  "type": "POST",
                  error: function()
                  {
                      $("#table tbody").empty().append('<tr><td align="center" colspan="17">No data found</td></tr>');
                  }
              },
              "columnDefs": [
                  {
                      "targets": [0], //last column
                      "orderable": false, //set not orderable
                  },
              ],
              "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
              "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
              buttons: []
          });

          /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
      });

  function reload_table()
  {
      table.ajax.reload(null,false); //reload datatable ajax 
  }
  function tracked_qp_candidates(qp_id,job_status)
  {
    var track_url=base_url+'pramaan/candidates_by_qp/'+qp_id+'/'+job_status;
    $.ajax({
          url : track_url,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
              var candidate_track_list_html = "<div  style='margin-bottom: 10px'>QP Name: <span style='font-weight: bold;'>"+data.qualification_pack_name+"</span></div>";
              candidate_track_list_html += "<div style='margin-bottom: 10px'>Status: <span style='font-weight: bold;'>"+data.candidate_job_status_name+"</span></div>";

              if(data.status)
              {
                  var slno=1;
                  candidate_track_list_html += '<div class="row">';
                  candidate_track_list_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; height: 400px;">';
                  candidate_track_list_html += '<table class="table table-striped">';
                  candidate_track_list_html += '<tr><th>SNo</th><th>Candidate Name</th><th>Mobile</th><th>Customer</th><th>Job Title</th><th>Job Description</th><th>Location</th></tr>';

                  $.each(data.candidate_detail,function(a,b)
                  {
                    candidate_track_list_html += '<tr><td>'+slno+'</td><td>'+b.candidate_name+'</td><td>'+b.mobile+'</td><td>'+ b.customer_name + '</td><td>'+b.job_title+'</td><td>'+b.job_desc+'</td><td>'+b.location_name+'</td></tr>';
                    slno++;
                  });

                  candidate_track_list_html += '</table>';
                  candidate_track_list_html += '</div></div>';
              }
              else
                  candidate_track_list_html='<div>-No data Found-</div>';
              $('.candidate_job_status').html(candidate_track_list_html);
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });
      $('#modal_form_tracker_qp').modal('show'); // show bootstrap modal when complete loaded
  }
  </script>

  <div id="modal_form_tracker_customer" class="modal fade bs-example-modal-xl" role="dialog">
      <div class="modal-dialog modal-xl" role="document" >
          <div class="modal-content">
              <div class="modal-header" style="border-bottom:hidden;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title">Application Tracker (Customer)</h3>
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

  <script type="text/javascript">
  var save_method; //for save method string
  var table;

  $(document).ready(function() {

      //datatables
      table = $('#table1').DataTable({

          "stateSave": true,
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "paging": true,
          "scrollX": true,
          "language": { processing: '<div style="margin-left:-800px;margin-top:50px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
          "aLengthMenu": [[10, 25, 50, 100, 200, -1],[10, 25, 50, 100, 200, "All"]],
          "pageLength": 10,
         // "order": [], //Initial no order.

          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": base_url+"partner/employers_tracker_list/",
              "type": "POST",
              error: function()
              {
                $("#table tbody").empty().append('<tr><td align="center" colspan="17">No data found</td></tr>');
              }
          },
          "columnDefs": [
                          { 
                              "targets": [0], //last column
                              "orderable": false, //set not orderable
                          },
                        ],
            "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
                    "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
             buttons: []
      }); 

       /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
  });
  function reload_table()
  {
      table.ajax.reload(null,false); //reload datatable ajax 
  }

  function tracked_candidates(customer_id,job_status_id)
  {
    var track_url=base_url+'partner/tracked_candidates_employerjob/'+customer_id+'/'+job_status_id;
    $.ajax({
          url : track_url,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
              var candidate_track_list_html='';
              if(data.status)
              {
                  var employer=data.employer_detail;
                  var slno=1;
                  candidate_track_list_html += "<div  style='margin-bottom: 10px'>Customer Name: <span style='font-weight: bold;'>"+employer.customer_name+"</span></div>";
                  candidate_track_list_html += "<div style='margin-bottom: 10px'>Status: <span style='font-weight: bold;'>"+data.candidate_job_status_name+"</span></div>";

                  candidate_track_list_html += '<div class="row">';
                  candidate_track_list_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; height: 400px;">';
                  candidate_track_list_html += '<table id="tblApplicationTrackerDetails" class="table table-striped table-bordered display responsive nowrap">';
                  candidate_track_list_html += '<tr><th>SNo</th><th>Candidate Name</th><th>Mobile</th><th>QP</th><th>Job Title</th><th>Job Description</th><th>Job Location</th></tr>';

                  $.each(data.candidate_detail,function(a,b)
                  {
                    candidate_track_list_html += '<tr><td>'+slno+'</td><td>'+b.candidate_name+'</td><td>'+b.mobile+'</td><td>'+b.qualification_pack_name+'</td><td>'+b.job_title+'</td><td>'+b.job_desc+'</td><td>'+b.location_name+'</td></tr>';
                    slno++;
                  });

                  candidate_track_list_html += '</table>';
                  candidate_track_list_html += '</div></div>'; 
              }
              $('.candidate_job_status').html(candidate_track_list_html);

              $("#tblApplicationTrackerDetails").DataTable();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });
      $('#modal_form_tracker_customer').modal('show'); // show bootstrap modal when complete loaded
  }
  </script>
  <script>
  $(document).ready(function() {
     var table =  $('#table2').DataTable();

                 $('#dropdown1').on('change', function () {
                      table.columns(1).search( this.value ).draw();
                  } );
                  $('#dropdown2').on('change', function () {
                      table.columns(2).search( this.value ).draw();
                  } );
                   $('#dropdown3').on('change', function () {
                      table.columns(3).search( this.value ).draw();
                  } );
  });
  </script>
  
  <div id="modal_form_tracker_center" class="modal fade bs-example-modal-xl" role="dialog">
      <div class="modal-dialog modal-xl" role="document" >
          <div class="modal-content">
              <div class="modal-header" style="border-bottom:hidden;">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                  <h3 class="modal-title">Application Tracker (Center)</h3>
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
  
  <script type="text/javascript">
  var save_method; //for save method string
  var table;

  $(document).ready(function() {

      //datatables
      table = $('#tableCenter').DataTable({

          "stateSave": true,
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "paging": true,
          "scrollX": true,
          "language": { processing: '<div style="margin-left:-800px;margin-top:50px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
          "aLengthMenu": [[10, 25, 50, 100, 200, -1],[10, 25, 50, 100, 200, "All"]],
          "pageLength": 10,
         // "order": [], //Initial no order.

          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": base_url+"partner/center_tracker_list/",
              "type": "POST",
              error: function()
              {
                $("#table tbody").empty().append('<tr><td align="center" colspan="17">No data found</td></tr>');
              }
          },
          "columnDefs": [
                          { 
                              "targets": [0], //last column
                              "orderable": false, //set not orderable
                          },
                        ],
            "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>>" +
                    "<'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
             buttons: []
      }); 

       /*$(' <button class="btn btn-sm btn-default" onclick="reload_table()"><i class="glyphicon glyphicon-refresh"></i> Reload</button>').appendTo('div#table_length');*/
  });
  function reload_table()
  {
      table.ajax.reload(null,false); //reload datatable ajax 
  }

  function tracked_center_candidates(center_name,job_status_id)
  {
    var track_url=base_url+'partner/tracked_candidates_center/'+center_name+'/'+job_status_id;
    $.ajax({
          url : track_url,
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
              var candidate_track_list_html='';
              if(data.status)
              {
                  var employer=data.center_detail;
                  var slno=1;
                  candidate_track_list_html += "<div  style='margin-bottom: 10px'>Center Name: <span style='font-weight: bold;'>"+center_name+"</span></div>";
                  
                //candidate_track_list_html += "<div style='margin-bottom: 10px'>Status: <span style='font-weight: bold;'>"+data.candidate_job_status_name+"</span></div>";

                  candidate_track_list_html += '<div class="row">';
                  candidate_track_list_html += '<div class="col-sm-12 col-md-12" style="overflow-x: auto; height: 400px;">';
                  candidate_track_list_html += '<table id="tblApplicationTrackerDetails" class="table table-striped table-bordered display responsive nowrap">';
                  candidate_track_list_html += '<tr><th>SNo</th><th>Candidate Name</th><th>Mobile</th><th>QP</th><th>Job Title</th></tr>';

                  $.each(data.job_detail,function(a,b)
                  {
                    candidate_track_list_html += '<tr><td>'+slno+'</td><td>'+b.candidate_name+'</td><td>'+b.mobile+'</td><td>'+b.qualification_pack_name+'</td><td>'+b.job_title+'</td></tr>';
                    slno++;
                  });

                  candidate_track_list_html += '</table>';
                  candidate_track_list_html += '</div></div>'; 
              }
              $('.candidate_job_status').html(candidate_track_list_html);

              $("#tblApplicationTrackerDetails").DataTable();
          },
          error: function (jqXHR, textStatus, errorThrown)
          {
              alert('Error get data from ajax');
          }
      });
      $('#modal_form_tracker_center').modal('show'); // show bootstrap modal when complete loaded
  }
 
    $(document).ready(function() {
        $('.select2-neo').select2({ width: '100%' });
        });
    $('#employment_start_date').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
    $('#employment_end_date').on('changeDate', function(ev){
        $(this).datepicker('hide');
    });
  </script>

<script type="text/javascript">
  var table;
  
 $(document).ready(function() {
  
     //datatables
     table = $('#tableSelfEmployed').DataTable({ 
        
         "processing": true, //Feature control the processing indicator.
         "serverSide": true, //Feature control DataTables' server-side processing mode.
         "order": [], //Initial no order.
         "scrollX": true,
         //"scrollY": "400px",
         "searching": false,
         "aLengthMenu": [[10, 25, 50, 100, 200, -1],[10, 25, 50, 100, 200, "All"]],
          "pageLength": 10,
         "language": { processing: '<div style="margin-left:-800px;margin-top:40px;font-size:15px;"><img src="<?php echo base_url('/assets/images/loading.gif');?>"></div> '},
         // Load data for the table's content from an Ajax source
         "ajax": {
             "url": "<?php echo site_url('selfemployedcontroller/selfemployedlist')?>",
             "type": "POST",
             "data": function ( data ) {
                 data.employment_start_date = $('#employment_start_date').val();
                 data.center_name = $('#center_name').val();
                 data.batch_code = $('#batch_code').val();
                 data.qualification_pack = $('#qualification_pack').val();
                 data.enrollment_no = $('#enrollment_no').val();
             }
         },
  
         //Set column definition initialisation properties.
         "columnDefs": [
         { 
             "targets": [ 0 ], //first column / numbering column
             "orderable": false, //set not orderable
         },
         ],
         "dom":  "<'row'<'col-md-4'l><'col-md-8 searchprint'Bfr>><'row'<'col-md-12't>><'row'<'col-md-4'i><'col-md-8'p>>",
         buttons: []

        
     });
     
    

     $('#btn-filter').click(function(){ //button filter event click
         table.ajax.reload();  //just reload table
     });
     $('#btn-reset').click(function(){ //button reset event click
         $('#form-filter')[0].reset();
         table.ajax.reload();  //just reload table
     });
  
 });
  
 </script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
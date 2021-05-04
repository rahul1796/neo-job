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

    <a class="btn btn-success btn-min-width mr-1 mb-1" href="<?php echo base_url("master/create")?>" style="float: right;"><i class="icon-android-add"></i>Add Location</a>

    <!-- File export table -->
    <div class=" breadcrumbs-top col-md-9 col-xs-12" style="margin-bottom: 10px;margin-top: -34px;">

      <div class="row">
        <div class="col-md-12">
          <h2>Available Locations</h2>
        </div>
        <br>
      </div>
        <div class="breadcrumb-wrapper col-xs-12" style="margin-left: -16px;">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><?php echo anchor("pramaan/dashboard","Dashboard");?></a>
                </li>
                <li class="breadcrumb-item active">Location
                </li>
            </ol>
        </div>
    </div>
    <section>
        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Available Location</h4>
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
                           <table id="tblList" class="table table-striped table-bordered display responsive nowrap">
                                <thead>
                                <tr>
                                    <th>SNo</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody id="tblBody">
                                  <?php $i = 1;?>
                                  <?php foreach($locations as $location): ?>
                                    <tr>
                                      <td><?= $i; ?></td>
                                      <td><?= $location->location_name ?></td>
                                      <td> <a href="<?= base_url('master/edit/').$location->location_id; ?>" class="btn btn-sm btn-danger"> <i class="fa fa-pencil"></i> </a> </td>
                                    </tr>
                                    <?php $i++; ?>
                                  <?php endforeach; ?>
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
<script type="text/javascript">
  $(document).ready(function() {
    window.setTimeout(function() {
        $("#server-alert").fadeTo(500, 0).slideUp(500, function(){
            $(this).remove();
        });
    }, 4000);
  });
</script>

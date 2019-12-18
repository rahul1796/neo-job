<section class="inner">
	<div class="clearfix">
        <h4>Roles</h4>
	</div>
      
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
            	<header class="panel-heading">
              		Roles
            	</header>
            	<div class="panel-body">
					<div class="row text-small">
		                <div class="col-sm-4 m-b-mini">
		                	<b>Total : </b><?php echo $total_record;?>
		                </div>
		                <div class="col-sm-4 m-b-mini">
		                </div>
		                <div class="col-sm-4 ">
		                	<a href="<?php echo base_url('admin/add_role')?>" class="pull-right btn btn-sm btn-white"><i class="fa fa-plus text"></i></a>
						</div>
              		</div>
            	</div>
            	<div class="table-responsive">
              		<table class="table table-striped b-t text-small"><!-- used static table in framework if any doubt please refere framework source code -->
                		<thead>
                  			<tr>
                  				<th>Sl</th>
                    			<th class="th-sortable" data-toggle="class">Roles</th>
                    			<th>Action</th>
                    		</tr>
                		</thead>
                		<tbody>
                			<?php 
                				if($roles_list)
                				{
                					foreach($roles_list as $i=>$r)
                					{
                					?>
                					<tr>
                						<td><?php echo ($i+1); ?></td>
                						<td><?php echo $r['role_name']; ?></td>
                						<td><a href="<?php echo base_url('admin/edit_role/'.$r['id'])?>"><i class="fa fa-pencil"></i></a></td>
                					</tr>
                					<?php 
                					}
                				}
                			?>
                  		</tbody>
              		</table>
            	</div>
            	<footer class="panel-footer">
              		<div class="row">
                		<div class="col-sm-5 text-right text-center-sm">                
                  			<ul class="pagination pagination-small m-t-none m-b-none">
			                    <?php echo $pagination;?>
                  			</ul>
                		</div>
              		</div>
            	</footer>
            </section>
		</div>
	</div>
</section>

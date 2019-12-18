<section class="main padder">
	<div class="clearfix">
        <h4>Users</h4>
	</div>
      
	<div class="row">
		<div class="col-lg-12">
			<section class="panel">
            	<header class="panel-heading">
              		Users
            	</header>
            	<div class="panel-body">
					<div class="row text-small">
		                <div class="col-sm-4 m-b-mini">
		                	<b>Total : </b><?php echo $total_record;?>
		                </div>
		                <div class="col-sm-4 m-b-mini">
		                </div>
		                <div class="col-sm-4 ">
		                	<a href="<?php echo site_url('admin/add_user'); ?>" class="pull-right btn btn-sm btn-white"><i class="fa fa-plus text"></i></a>
						</div>
              		</div>
            	</div>
            	<div class="table-responsive">
              		<table class="table table-striped b-t text-small"><!-- used static table in framework if any doubt please refere framework source code -->
                		<thead>
                  			<tr>
                  				<th>Sl</th>
                    			<th class="th-sortable" data-toggle="class">Name</th>
                    			<th>Email</th>
                    			<th>Mobile</th>
                    			<th>Role</th>
                    			<th>Blocked</th>
                    			<th>Action</th>
                    		</tr>
                		</thead>
                		<tbody>
                			<?php 
                				if($users_list)
                				{
                					foreach($users_list as $i=>$u)
                					{
                					?>
                					<tr>
                						<td><?php echo ($i+1); ?></td>
                						<td><?php echo $u['name']; ?></td>
                						<td><?php echo $u['email']; ?></td>
                						<td><?php echo $u['mobile']; ?></td>
                						<td><?php echo $u['role_name']; ?></td>
                						<td><?php echo $u['block']?'Yes':'No'; ?></td>
                						<td>
                							<a href="<?php echo site_url('admin/edit_user/'.$u['id']); ?>"><i class="fa fa-pencil"></i></a>&nbsp;|&nbsp;
                							<a onclick="return confirm("Are you sure?")" href="<?php echo site_url('admin/reset_user_pwd/'.$u['id']); ?>" class="reset_pwd" ><i class="fa fa-retweet"></i></a>
                						</td>
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

<script></script>
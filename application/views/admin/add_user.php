<section class="main padder">
	<div class="clearfix">
        <h4>Add user</h4>
	</div>
	 <div class="row">
	 	<div class="col-sm-6">
        	<section class="panel">
            	<div class="panel-body">
              		<form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo site_url('admin/p_add_user') ?>" >
              			<?php echo form_error('_check_login','<span class="error_inp">','</span>')?>      
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">User name</label>
                  			<div class="col-lg-8">
                    			<input type="text" name="name" placeholder="name" class="bg-focus form-control"   value="">
                    			<?php echo form_error('name','<span class="error_inp">','</span>')?>
                  			</div>
               			 </div>
               			 <div class="form-group">
                  			<label class="col-lg-3 control-label">Password</label>
                  			<div class="col-lg-8">
                  				<input type="password" name="password" placeholder="password" class="bg-focus form-control"   value="">
                  				<?php echo form_error('password','<span class="error_inp">','</span>')?>
                  			</div>
                		</div>
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">Email</label>
                  			<div class="col-lg-8">
                  				<input type="email" name="email" placeholder="email" class="bg-focus form-control"   value="">
                  				<?php echo form_error('email','<span class="error_inp">','</span>')?>
                  			</div>
                		</div>
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">Mobile</label>
                  			<div class="col-lg-8">
                  				<input type="text" name="mobile" placeholder="mobile" class="bg-focus form-control"   value="">
                  				<?php echo form_error('mobile','<span class="error_inp">','</span>')?>
                  			</div>
                		</div>
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">Role</label>
                  			<div class="col-lg-8">
                  				<select name="role" class="form-control">
                  					<option value="0">Choose</option>
                  				<?php
                  					if($roles)
                  					{
                  						foreach($roles as $r)
                  						{
                  							?>
                  							<option value="<?php echo $r['id']; ?>" ><?php echo $r['role_name'];?></option>	
                  							<?php 
                  						}
                  					} 
                  				?>
                  				</select>
                  				<?php echo form_error('role','<span class="error_inp">','</span>')?>
                    		</div>
                		</div>
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">Block</label>
                  			<div class="col-lg-8">
                    			<div class="checkbox">
                    				<label>
                        				<input name="block" type="checkbox" value="1">
                      				</label>
                    			</div>
                  			</div>
                		</div>
    					<div class="form-group">
                  			<label class="col-lg-3 control-label">Is active</label>
                  			<div class="col-lg-8">
                    			<div class="checkbox">
                    				<label>
                        				<input name="is_active" type="checkbox" value="1">
                      				</label>
                    			</div>
                  			</div>
                		</div>
                		<div class="form-group">
                  			<div class="col-lg-9 col-lg-offset-3">                      
                    			<button type="submit" class="btn btn-primary">Add</button>
                  		</div>
                	</div>
                </form>
            </div>
		</section>
      </div>
   </div>
</section>

<script>
</script>






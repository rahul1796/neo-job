<section class="main padder">
	<div class="clearfix">
        <h4>Add user</h4>
	</div>
	 <div class="row">
	 	<div class="col-sm-12">
        	<section class="panel">
            	<div class="panel-body">
              		<form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo site_url('admin/p_edit_user') ?>" >
              			<?php echo form_error('_check_login','<span class="error_inp">','</span>')?>      
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">User name</label>
                  			<div class="col-lg-8">
                    			<input type="text" name="name" placeholder="name" class="bg-focus form-control"   value="<?php echo $user_det[0]['name']; ?>">
                    			<input type="hidden" name="user_id"    value="<?php echo $user_det[0]['id']; ?>">
                    			<?php echo form_error('name','<span class="error_inp">','</span>')?>
                  			</div>
               			 </div>
               			<div class="form-group">
                  			<label class="col-lg-3 control-label">Email</label>
                  			<div class="col-lg-8">
                  				<input type="email" name="email" placeholder="email" class="bg-focus form-control"   value="<?php echo $user_det[0]['email']; ?>">
                  				<?php echo form_error('email','<span class="error_inp">','</span>')?>
                  			</div>
                		</div>
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">Mobile</label>
                  			<div class="col-lg-8">
                  				<input type="text" name="mobile" placeholder="mobile" class="bg-focus form-control"   value="<?php echo $user_det[0]['mobile']; ?>">
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
                  							$role_sel="";
                  							if($r['id']==$user_det[0]['role_id'])
                  								$role_sel="selected='selected'";
                  							?>
                  							<option value="<?php echo $r['id']; ?>" <?php echo $role_sel; ?>><?php echo $r['role_name'];?></option>	
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
                    				<?php 
                    					$blkchecked="";
                    					if($user_det[0]['block'])
                    					{
                    						$blkchecked="checked='checked'";
                    					}
                    				?>
                        				<input name="block" type="checkbox" value="1" <?php echo $blkchecked; ?>>
                      				</label>
                    			</div>
                  			</div>
                		</div>
    					<div class="form-group">
                  			<label class="col-lg-3 control-label">Is active</label>
                  			<div class="col-lg-8">
                    			<div class="checkbox">
                    				<label>
                    				<?php 
                    					$checked="";
                    					if($user_det[0]['is_active'])
                    					{
                    						$checked="checked='checked'";
                    					}
                    				?>
                        				<input name="is_active" type="checkbox" value="1" <?php echo $checked; ?>>
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







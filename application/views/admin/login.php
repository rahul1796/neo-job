<div class="main padder">
	<div class="row">
		<div class="col-lg-4 col-lg-offset-4 m-t-large">
			<section class="panel">
				<header class="panel-heading text-center">
              		Sign in
            	</header>
            	<form action="<?php echo site_url('admin/p_login') ?>" class="panel-body" method="post">
              		<?php echo form_error('_check_login','<span class="error_inp">','</span>')?>
              		<div class="block">
                		<label class="control-label">Name</label>
                		<input type="text"  class="form-control" id="name" placeholder="Name" name="name" value="<?php echo set_value('name')?>" >
                		<?php echo form_error('name','<span class="error_inp">','</span>')?>
                	</div>
              		<div class="block">
                		<label class="control-label">Password</label>
                		<input type="password"  class="form-control" id="password" placeholder="Password" name="pwd" value="<?php echo set_value('pwd')?>" >
                		<?php echo form_error('pwd','<span class="error_inp">','</span>')?>
              		</div>
              		<button type="submit" class="btn btn-info">Sign in</button>
             	</form>
			</section>
		</div>
	</div>
</div>

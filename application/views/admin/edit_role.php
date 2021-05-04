<style type="text/css">
ul 
{
  list-style-type: none;
}
</style>
<section class="inner">
	<div class="clearfix">
        <h4>Edit role</h4>
	</div>
	 <div class="row">
	 	<div class="col-sm-12">
        	<section class="panel">
            	<div class="panel-body">
              		<form class="form-horizontal" method="post" data-validate="parsley" action="<?php echo site_url('admin/p_edit_role') ?>" >
              			<?php echo form_error('_check_login','<span class="error_inp">','</span>')?>      
                		<div class="form-group">
                  			<label class="col-lg-3 control-label">Role</label>
                  				<div class="col-lg-8">
                    				<input type="text" name="role" placeholder="role" class="bg-focus form-control"   value="<?php echo $role_det[0]['role_name']; ?>">
                    				<input type="hidden" name="role_id"    value="<?php echo $role_det[0]['id']; ?>">
                    				<?php echo form_error('role','<span class="error_inp">','</span>')?>
                  				</div>
               			 </div>
               			 <div class="form-group">
                  			<label class="col-lg-3 control-label">Module & Task</label>
                  			<div class="col-lg-8">
                  				<?php echo form_error('task_ids','<span class="error_inp">','</span>')?>
                  				<div class="table-responsive" style="height:500px !important;overflow:auto">
              						<table class="table table-striped b-t text-small" ><!-- used static table in framework if any doubt please refere framework source code -->
                						<thead>
                  							<tr>
                  								<th class="th-sortable" data-toggle="class">Module</th>
                    							<th>Task - <input type="checkbox" name="sel_all" id="selectall"></th>
                    						</tr>
                						</thead>
                						<?php
                							if($module_task_list)
                							{
                								foreach($module_task_list as $mt)
                								{
                									/*$task=explode(",",$mt['task_names']);
                									$task_ids=explode(",",$mt['ids']);*/
                									
                									$task_id_link=explode(",",$mt['task_id_link']);
                									sort($task_id_link);
                									
                									?>
                									<tr>
                										<td><b><?php echo $mt['module_name']; ?></b></td>
                										<td>
                											<?php 
                												if($task_id_link)
                												{?>
                													<ul>
                												<?php 
                													foreach($task_id_link as $ti=>$td)
                													{
                														$task_det=explode(":",$td);
                														$id=$task_det[1];
                														$task=$task_det[0];
                														
                														?>
                														<li><b><input type="checkbox" name="task_ids[]" value="<?php echo $id; ?>" class="task_ids <?php echo $id; ?>"><?php echo $task; ?></b></li>
                														<?php 
                													}
                												?>
                													</ul>
                												<?php 
                												}
                											?>
                										</td>
                									</tr>
                									<?php 
                								}
                								
                							} 
                						?>
                						<tbody>
                						</tbody>
                					</table>
                				</div>
                			</div>
                		</div>
    					<div class="form-group">
                  			<label class="col-lg-3 control-label">Is active</label>
                  			<div class="col-lg-8">
                    			<div class="checkbox">
                    				<?php 
                    					$checked="";
                    					if($role_det[0]['is_active'])
                    					{
                    						$checked="checked='checked'";
                    					}
                    				?>
                    				<label>
                        				<input name="is_active" type="checkbox" value="1" <?php echo $checked ;?>>
                      				</label>
                    			</div>
                  			</div>
                		</div>
                		<div class="form-group">
                  			<div class="col-lg-9 col-lg-offset-3">                      
                    			<button type="submit" class="btn btn-primary">Save changes</button>
                  		</div>
                	</div>
                </form>
            </div>
		</section>
      </div>
   </div>
</section>

<script>

var access_ids="<?php echo $role_det[0]['access_id']; ?>";
                    				
$('#selectall').click(function(event) {  //on click 
    if(this.checked) { // check select status
        $('.task_ids').each(function() { //loop through each checkbox
            this.checked = true;  //select all checkboxes with class "checkbox1"               
        });
    }else{
        $('.task_ids').each(function() { //loop through each checkbox
            this.checked = false; //deselect all checkboxes with class "checkbox1"                       
        });         
    }
});

//select access list
$.each(access_ids.split(","),function(a,b){
	var acc_id_class="."+b;
	$(acc_id_class).attr("checked",true);
});
</script>






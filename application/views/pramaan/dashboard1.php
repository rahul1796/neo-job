<style type="text/css">
td
{
	text-align: center;
}
</style>
<div class="inner">
	<br>
	<p>
	<?php
	if($user_group_id==1)
	{
		redirect("partner/application_tracker");
	?>

	<?php
	}
	?>
	</p>
	<p>
	<?php
	if($user_group_id==4)
	{
	?>
	<table>
	<tr>
	<td>--</td>
	<th>Today</th><th>This week</th><th>This Month</th><th>This year</th><th>Till Date</th>
	</tr>
	<tr>
	<th>Total candidates<br> registered</th>
		<td><?php echo $statistics['n_candidates_todays']?></td>
		<td><?php echo $statistics['n_this_weeks']?></td>
		<td><?php echo $statistics['n_this_months']?></td>
		<td><?php echo $statistics['n_this_years']?></td>
		<td><?php echo $statistics['n_candidates']?></td>
	</tr>
	<tr>
	<th>Total candidates<br> placed</th>
		<td><?php echo $statistics['n_placed_today']?></td>
		<td><?php echo $statistics['n_placed_week']?></td>
		<td><?php echo $statistics['n_placed_month']?></td>
		<td><?php echo $statistics['n_placed_year']?></td>
		<td><?php echo $statistics['n_placed']?></td>
	</tr>
	<tr>
	<th>Number of open<br> positions</th>
		<td><?php echo $statistics['n_positions_open_today']?></td>
		<td><?php echo $statistics['n_positions_open_week']?></td>
		<td><?php echo $statistics['n_positions_open_month']?></td>
		<td><?php echo $statistics['n_positions_open_year']?></td>
		<td><?php echo $statistics['n_positions_open']?></td>
	</tr>
	<tr>
	<th>Number of positions <br>getting over this week</th>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
	</tr>
	<tr>
	</table>
	<?php
	}
	?>
	</p>

	<?php if($user_group_id==5)
	{
		redirect("partner/application_tracker");
	}
	?>

	<?php if( $user_group_id==8 || $user_group_id==11|| $user_group_id==12 || $user_group_id==13 ||$user_group_id==14)
	{
		redirect("pramaan/pramaan_jobs");
	}

	?>





	<?php 
	if($user_group_id==22)
	{
	?>
	<table>
	<tr>
	<td>--</td>
	<th>Today</th><th>This week</th><th>This Month</th><th>This year</th><th>Till Date</th>
	</tr>
	<tr>
	<th>Total candidates<br> registered</th>
		<td><?php echo $statistics['n_candidates_todays']?></td>
		<td><?php echo $statistics['n_this_weeks']?></td>
		<td><?php echo $statistics['n_this_months']?></td>
		<td><?php echo $statistics['n_this_years']?></td>
		<td><?php echo $statistics['n_candidates']?></td>
	</tr>
	<tr>
	<th>Total candidates<br> placed</th>
		<td><?php echo $statistics['n_placed_today']?></td>
		<td><?php echo $statistics['n_placed_week']?></td>
		<td><?php echo $statistics['n_placed_month']?></td>
		<td><?php echo $statistics['n_placed_year']?></td>
		<td><?php echo $statistics['n_placed']?></td>
	</tr>
	<tr>
	<th>Number of open<br> positions</th>
		<td><?php echo $statistics['n_positions_open_today']?></td>
		<td><?php echo $statistics['n_positions_open_week']?></td>
		<td><?php echo $statistics['n_positions_open_month']?></td>
		<td><?php echo $statistics['n_positions_open_year']?></td>
		<td><?php echo $statistics['n_positions_open']?></td>
	</tr>
	<tr>
	<th>Number of positions <br>getting over this week</th>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
		<td>-</td>
	</tr>
	<tr>
	</table>
	<?php 
	}
	?>

</div>
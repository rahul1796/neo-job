<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">

	<style>
		table {
			  font-family: arial, sans-serif;
			  border-collapse: collapse;
			  width: 100%;
			}

			td, th {
			  border: 1px solid #dddddd;
			  text-align: left;
			  padding: 8px;
			}

			tr:nth-child(even) {
			  background-color: #dddddd;
			}
			img{
				max-width: 50px; height: auto; display: block;
			}
	</style>
</head>
<body>

<div id="container">
	<h1>Opportunity Details</h1>
	
	<div id="body">
		<table class="table">
			 <tr>
			     <td>SlNo</td>
			 	 <td>Company Name</td>
				 <td>Opportunity Code</td>
				 <td>Product</td>
				 <td>Created On</td>
				 <td>SPOC Name</td>
				 <td>SPOC Email</td>
				 <td>SPOC Phone</td>
				
			 </tr>
			 <tbody>
				<?php $i=1?>
				<?php foreach ($opportunity_results as $key) {?>
					
				
				<tr>
				<td><?php echo $i++?></td>
				<td><?php echo $key["company_name"] ?></td>
				<td><?php echo $key["opportunity_code"] ?></td>
				<td><?php echo $key["business_vertical"] ?></td>
				<td><?php echo $key["created_at"] ?></td>
				<td><?php echo $key["spoc_name"] ?></td>
				<td><?php echo $key["spoc_email"] ?></td>
				<td><?php echo $key["spoc_phone"] ?></td>
			
			<?php }?>
			</tr>
			</tbody>
		</table>
	</div>

	
  </div>

</body>
</html>
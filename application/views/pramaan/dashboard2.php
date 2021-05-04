<style type="text/css">
.nav-pills>li.active>a, .nav-pills>li.active>a:hover, .nav-pills>li.active>a:focus 
{
color: #fff;
background-color: #428bca;
}

.margintop20 
{
    margin-top:20px;
}

.nav-pills>li>a {
border-radius: 0px;
}

a {
color: #000;
text-decoration: none;
}

a:hover {
color: #000;
text-decoration: none;
}

.nav-stacked>li+li {
margin-top: 0px;
margin-left: 0;
border-bottom:1px solid #dadada;
border-left:1px solid #dadada;
border-right:1px solid #dadada;
}

.active2 {
    border-right:4px solid #428bca;
}
</style>


<div class="inner container-fluid" style="margin-top:10px;">
		<div class="row" style="padding: 0px;">
			<div class="col-sm-3 col-md-3 column margintop20">
			<div><h3>Filter Results</h3></div>
				<div class="list-group panel">
					<a href="#quali_menu" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">Qualification</a>
					<div class="collapse" id="quali_menu">
						<a href="javascript:void(0)" class="list-group-item" data-toggle="collapse" data-parent="#SubMenu1">Non Metric(1234) <i class="fa fa-caret-down"></i></a>
						<a href="javascript:void(0)" class="list-group-item" data-parent="#SubMenu1">Metric(600)</a>
						<a href="javascript:void(0)" class="list-group-item" data-parent="#SubMenu1">Gradute(500)</a>
					</div>
					<a href="#experi_menu" class="list-group-item list-group-item-success" data-toggle="collapse" data-parent="#MainMenu">Work Experiance</a>
					<div class="collapse" id="experi_menu">
					  <a href="javascript:void(0)" class="list-group-item">Not Applicable(340)</a>
					  <a href="javascript:void(0)" class="list-group-item">Freshers(340)</a>
					  <a href="javascript:void(0)" class="list-group-item">0-2(Years)(469)</a>
					  <a href="javascript:void(0)" class="list-group-item">3-5(Years)(9494)</a>
					  <a href="javascript:void(0)" class="list-group-item">6& above(288)</a>
					</div>
				</div>
			</div>

			<div class="col-sm-9 col-md-9 column margintop20">
				<div><h3>Resource Organization: Cognizant</h3></div>
			  	<nav class="navbar navbar-light bg-faded">
				  <form class="form-inline float-xs-right" onsubmit="return false">
				    <a class="btn btn-warning" href="<?php echo site_url('partner/center')?>">Center</a>
				   <!--  <a class="btn btn-primary" href="<?php echo site_url('partner/view_center')?>">View Center</a> -->
				    <a class="btn btn-warning" href="<?php echo site_url('partner/associates')?>">Associates</a>
				   <!--  <a class="btn btn-primary" href="<?php echo site_url('partner/view_associate')?>">View Associate</a> -->
				  </form>
				</nav>

			</div>
		</div>
</div>

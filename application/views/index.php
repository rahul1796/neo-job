<!DOCTYPE html>
<html lang="en">
	<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">	
	<title>
		<?php 
			echo "NEO" . ($title ? ' - ' . ucwords($title) : '');
		?>
	</title>
		
	<script type="text/javascript">
		var site_url='<?php echo site_url();?>';
		var base_url='<?php echo base_url(); ?>';
		var images_fldr='<?php echo IMAGES_FOLDER; ?>';
	</script>

		<!-- loading the favicons -->

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<link rel="stylesheet" href="<?php echo base_url().'adm-assets/pageloader/css/main.css'?>">
		<script src="<?php echo base_url().'adm-assets/pageloader/js/vendor/modernizr-2.6.2.min.js'?>"></script>
		<link rel="shortcut icon" type="image/png" href="<?php echo base_url().'adm-assets/images/ico/favicon.png'?>">
        <link rel="apple-touch-icon" type="image/x-icon" href="<?php echo base_url().'assets/dist/img/ico/apple-touch-icon-57-precomposed.png;'?>">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="<?php echo base_url().'assets/dist/img/ico/apple-touch-icon-72-precomposed.png;'?>">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="<?php echo base_url().'assets/dist/img/ico/apple-touch-icon-114-precomposed.png;'?>">
        <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="<?php echo base_url().'assets/dist/img/ico/apple-touch-icon-144-precomposed.png;'?>">
	    <link href="<?php echo base_url().'adm-assets/css/bootstrap.min.css'?>" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url().'assets/font-awesome/css/font-awesome.min.css'?>" rel="stylesheet" type="text/css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-datepicker.min.css'?>">
		
        <style type="text/css">
        html
		{
			width: 100%;
            /*overflow-y: scroll;  */
        }
		.back-link a {
			color: #4ca340;
			text-decoration: none;
			border-bottom: 1px #4ca340 solid;
		}
		.back-link a:hover,
		.back-link a:focus {
			color: #408536;
			text-decoration: none;
			border-bottom: 1px #408536 solid;
		}
		h1 {
			height: 100%;
			/* The html and body elements cannot have any padding or margin. */
			margin: 0;
			font-size: 14px;
			font-family: 'Open Sans', sans-serif;
			font-size: 32px;
			margin-bottom: 3px;
		}
		.entry-header {
			text-align: left;
			margin: 0 auto 50px auto;
			width: 80%;
			max-width: 978px;
			position: relative;
			z-index: 10001;
		}
		#demo-content {
			padding-top: 100px;
		}
        </style>
        </head>
	<body data-open="click" data-menu="vertical-menu" data-col="2-columns" class="vertical-layout vertical-content-menu 2-columns  fixed-navbar">
	<!--<div id="loader-wrapper">
		<div id="loader"></div>

		<div class="loader-section section-left"></div>
		<div class="loader-section section-right"></div>

	</div>-->
	<div class="wrapper">

	<?php 
			$this->load->view('common/header');
			$this->load->view('common/body');
			$this->load->view('common/footer');
	?>
	</div>

	<!--<script>window.jQuery || document.write('<script src="<?php /*echo base_url().'adm-assets/pageloader/js/vendor/jquery-1.9.1.min.js'*/?>"><\/script>')</script>-->
	<script src="<?php echo base_url().'adm-assets/pageloader/js/main.js'?>"></script>
	<script type="text/javascript" src="<?php echo base_url().'assets/js/bootstrap-datepicker.min.js'?>"></script>
	<script src="<?php echo base_url().'adm-assets/vendors/js/extensions/zoom.min.js'?>" type="text/javascript"></script>
	
<script type="text/javascript">
$(function() 
{
	var pgurl = window.location.href.substr(window.location.href.lastIndexOf("/")+1);
	//alert(window.location.href);
	$(".main-menu-content ul li a").each(function()
	{ 
		var navurls=($(this).attr("href")).substr(window.location.href.lastIndexOf("/")+1);
		if(navurls == pgurl)
		{

			if(pgurl!='')
			{

				$('.main-menu-content ul.nav li').removeClass("active");
				$(this).parent().addClass("active");
			}
		}

	})
});

</script>
	<script>
		$(document).ready(function () {
			$('.dropdown-toggle').dropdown();
		});
	</script>
</body>
</html>
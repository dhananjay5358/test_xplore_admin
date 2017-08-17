<?php // echo site_url('welcome/attempt');?>
<html>
	<head>
		<title>Admin login page</title>
                <link href="<?php echo base_url('css/admin.css');?>" media="screen" rel="stylesheet" type="text/css"/>
	
	<!--css files of responsive admin panel starts here-->
		<link href="<?php echo base_url('css/bootstrap-responsive.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('css/bootstrap-cerulean.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('css/charisma-app.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('css/jquery-ui-1.8.21.custom.css');?>" rel="stylesheet">
		<link href='<?php echo base_url('css/fullcalendar.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/fullcalendar.print.css');?>' rel='stylesheet'  media='print'>
		<link href='<?php echo base_url('css/chosen.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/uniform.default.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/colorbox.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/jquery.cleditor.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/jquery.noty.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/noty_theme_default.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/elfinder.min.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/elfinder.theme.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/jquery.iphone.toggle.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/opa-icons.css');?>' rel='stylesheet'>
		<link href='<?php echo base_url('css/uploadify.css');?>' rel='stylesheet'>
	<!--css files of responsive admin panel ends here-->
	
	<!--js files of responsive admin panel starts here-->
	
	    <!-- jQuery -->
		<script src="<?php echo base_url('js/jquery-1.7.2.min.js');?>"></script>
		<!-- jQuery UI -->
		<script src="<?php echo base_url('js/jquery-ui-1.8.21.custom.min.js');?>"></script>
		<!-- transition / effect library -->
		<script src="<?php echo base_url('js/bootstrap-transition.js');?>"></script>
		<!-- alert enhancer library -->
		<script src="<?php echo base_url('js/bootstrap-alert.js');?>"></script>
		<!-- modal / dialog library -->
		<script src="<?php echo base_url('js/bootstrap-modal.js');?>"></script>
		<!-- custom dropdown library -->
		<script src="<?php echo base_url('js/bootstrap-dropdown.js');?>"></script>
		<!-- scrolspy library -->
		<script src="<?php echo base_url('js/bootstrap-scrollspy.js');?>"></script>
		<!-- library for creating tabs -->
		<script src="<?php echo base_url('js/bootstrap-tab.js');?>"></script>
		<!-- library for advanced tooltip -->
		<script src="<?php echo base_url('js/bootstrap-tooltip.js');?>"></script>
		<!-- popover effect library -->
		<script src="<?php echo base_url('js/bootstrap-popover.js');?>"></script>
		<!-- button enhancer library -->
		<script src="<?php echo base_url('js/bootstrap-button.js');?>"></script>
		<!-- accordion library (optional, not used in demo) -->
		<script src="<?php echo base_url('js/bootstrap-collapse.js');?>"></script>
		<!-- carousel slideshow library (optional, not used in demo) -->
		<script src="<?php echo base_url('js/bootstrap-carousel.js');?>"></script>
		<!-- autocomplete library -->
		<script src="<?php echo base_url('js/bootstrap-typeahead.js');?>"></script>
		<!-- tour library -->
		<script src="<?php echo base_url('js/bootstrap-tour.js');?>"></script>
		<!-- library for cookie management -->
		<script src="<?php echo base_url('js/jquery.cookie.js');?>"></script>
		<!-- calander plugin -->
		<script src='<?php echo base_url('js/fullcalendar.min.js');?>'></script>
		<!-- data table plugin -->
		<script src='<?php echo base_url('js/jquery.dataTables.min.js');?>'></script>
	
		<!-- chart libraries start -->
		<script src="<?php echo base_url('js/excanvas.js');?>"></script>
		<script src="<?php echo base_url('js/jquery.flot.min.js');?>"></script>
		<script src="<?php echo base_url('js/jquery.flot.pie.min.js');?>"></script>
		<script src="<?php echo base_url('js/jquery.flot.stack.js');?>"></script>
		<script src="<?php echo base_url('js/jquery.flot.resize.min.js');?>"></script>
		<!-- chart libraries end -->
	
		<!-- select or dropdown enhancer -->
		<script src="<?php echo base_url('js/jquery.chosen.min.js');?>"></script>
		<!-- checkbox, radio, and file input styler -->
		<script src="<?php echo base_url('js/jquery.uniform.min.js');?>"></script>
		<!-- plugin for gallery image view -->
		<script src="<?php echo base_url('js/jquery.colorbox.min.js');?>"></script>
		<!-- rich text editor library -->
		<script src="<?php echo base_url('js/jquery.cleditor.min.js');?>"></script>
		<!-- notification plugin -->
		<script src="<?php echo base_url('js/jquery.noty.js');?>"></script>
		<!-- file manager library -->
		<script src="<?php echo base_url('js/jquery.elfinder.min.js');?>"></script>
		<!-- star rating plugin -->
		<script src="<?php echo base_url('js/jquery.raty.min.js');?>"></script>
		<!-- for iOS style toggle switch -->
		<script src="<?php echo base_url('js/jquery.iphone.toggle.js');?>"></script>
		<!-- autogrowing textarea plugin -->
		<script src="<?php echo base_url('js/jquery.autogrow-textarea.js');?>"></script>
		<!-- multiple file upload plugin -->
		<script src="<?php echo base_url('js/jquery.uploadify-3.1.min.js');?>"></script>
		<!-- history.js for cross-browser state change on ajax -->
		<script src="<?php echo base_url('js/jquery.history.js');?>"></script>
		<!-- application script for Charisma demo -->
		<script src="<?php echo base_url('js/charisma.js');?>"></script>
	
	<!--js files of responsive admin panel ends here-->
	
	</head>
	<body>
           
		 <div class="container-fluid">
				<div class="row-fluid">
				
					<div class="row-fluid">
						<div class="span12 center login-header">
							<h2>Welcome to Travel App Admin Section</h2>
						</div><!--/span-->
					</div><!--/row-->
					
					<div class="row-fluid">
						<div class="well span5 center login-box">
							<div class="alert alert-info">
								Please login with your Username and Password.
							</div>
							<?php if(!empty($wrong)) { ?>
							<div>
								<span style="color: #ff0000"><?php echo $wrong; ?></span>
								<div class="clearfix"></div>
							</div>	
							<?php	}?>
							<?php if(!empty($log_out)) { ?>
							<div>
								<span style="color: #29A629"><?php echo $log_out; ?></span>
								<div class="clearfix"></div>
							</div>	
							<?php	}?>
							<form action="<?php echo site_url('admin/login/auth');?>" method="post">
								<fieldset>
									<div class="input-prepend" title="Username" data-rel="tooltip">
										<span class="add-on"><i class="icon-user"></i></span>
										<input autofocus type="text" name="username" id="username" class="input-large span10" type="text"/>
									</div>
									<div class="clearfix"></div>
		
									<div class="input-prepend" title="Password" data-rel="tooltip">
										<span class="add-on"><i class="icon-lock"></i></span>
										<input type="password" name="pass" id="pass" class="input-large span10" type="password" />
									</div>
									<div class="clearfix"></div>
		
									<div class="input-prepend">
									<label class="remember" for="remember"><input type="checkbox" id="remember" />Remember me</label>
									</div>
									<div class="clearfix"></div>
		
									<p class="center span5">
									<button type="submit" class="btn btn-primary">Login</button>
									</p>
								</fieldset>
							</form>
							
						</div><!--/span-->
					</div><!--/row-->
						</div><!--/fluid-row-->
				
			</div>  
		   
	</body>
</html>
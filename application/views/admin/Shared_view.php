<html>
	<head>
		<title>Xplore</title>
		<script type="text/javascript">
			function Open()
			{
				$TourId=document.getElementById("Tour_Id").value;		
				window.open("X-Plore://?T_id="+ $TourId);
			}
		</script>
    </head>
	<body>		
		<div class="container-fluid">		 
		<input type="hidden" id="Tour_Id" value="<?php echo $hello;?>"/>
		<input type="button" name="submit" onclick="Open()" />
	   	</div>	
	</body>
</html>

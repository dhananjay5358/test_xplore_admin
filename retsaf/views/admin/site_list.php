<?php //echo site_url();?> 
<html>
	<head>
		<title>Historical Sites</title>
		          
<script type="text/javascript">			
			
	function show_addSite_box()
	{					
			
		$.ajax({
				url: '<?php echo site_url('admin/gallery/get_gallerylist');?>',		
				data: null,
				success: function(response) {
					var gallery = eval('('+response+')');
						var gallery_select_box = '<select id="gallery_select_box"><option value="0" selected>Please Select Gallery</option>';
						
						if(gallery['0']!='no-data')
						{									
							for(var i=0;i<gallery.length;i++)
							{
								gallery_select_box = gallery_select_box + '<option value="'+gallery[i].gallery_id+'">'+gallery[i].gallery_name+'</option>';
							}																		
						}
						gallery_select_box = gallery_select_box + '</select>';
					
					$.ajax({
							url: '<?php echo site_url('admin/interests/get_interestlist');?>',		
							data: null,
							success: function(resp) {
								var data = eval('('+resp+')');
								var interest_select_box = '<select id="interest_select_box" ><option value="0" selected>Please Select Interest</option>';
								
								if(data['0']!='no-data')
								{									
									for(var i=0;i<data.length;i++)
									{
										interest_select_box = interest_select_box + '<option value="'+data[i].interest_id+'">'+data[i].title+'</option>';
									}																		
								}
								interest_select_box = interest_select_box + '</select>';
								
								var html='<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Create New Site</h2></div>'+
								'<div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal">'+
								'<div class="control-group"><label class="control-label" for="add_site_name">Historical Site Name * : </label>'+
								'<div class="controls"><input type="text" id="add_site_name" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label class="control-label" >For Free/Paid App * : </label>'+
								'<div class="controls"><input class="input-xlarge" type="radio" id="add_app_type_paid" name="add_app_type" value="1" checked>Paid app</input><input class="input-xlarge" type="radio" id="add_app_type_free" name="add_app_type" value="2">Free app</input></div></div>'+
								'<div class="control-group"><label class="control-label" for="interest_select_box">Select Interest * : </label>'+
								'<div class="controls">'+interest_select_box+'</div></div><div class="control-group"><label class="control-label" for="userfile">Feature Image * : </label>'+
								'<div class="controls"><input type="file" id="userfile" name="userfile" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label class="control-label" for="add_site_location">Street Address * : </label>'+
								'<div class="controls"><input type="text" id="add_site_location" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label for="add_site_city" class="control-label">City * : </label><div class="controls">'+
								'<input type="text" id="add_site_city" class="input-xlarge"></input></div></div><div class="control-group">'+
								'<label for="add_site_state" class="control-label">State * : </label><div class="controls"><input type="text" id="add_site_state" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label for="add_site_longitude" class="control-label">Longitude : </label><div class="controls"><input type="text" id="add_site_longitude" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label for="add_site_latitude" class="control-label">Latitude : </label><div class="controls"><input type="text" id="add_site_latitude" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label for="add_site_contact" class="control-label">Contact Phone : </label><div class="controls"><input type="text" id="add_site_contact" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label for="add_site_price" class="control-label">Price : </label><div class="controls"><input type="text" id="add_site_price" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label for="add_site_hours" class="control-label">Hours of Operation : </label><div class="controls"><input type="text" id="add_site_hours" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label for="add_site_web_site" class="control-label">Web Site : </label><div class="controls"><input type="text" id="add_site_web_site" class="input-xlarge"></input></div></div>'+
								'<div class="control-group"><label class="control-label" for="gallery_select_box">Select Image Gallery : </label>'+
								'<div class="controls">'+gallery_select_box+'</div></div><div class="control-group"><label class="control-label" for="add_site_desc">Historical Site Description * : </label>'+
								'<div class="controls"><textarea type="text" id="add_site_desc" class="input-xlarge"></textarea></div></div></form></div></div>';	
									
								var show_site_box=[{
											html: html,
											buttons:{"Save" : true , "Cancel" : false},
											submit: function(e,v,m,f){
												if(v==true)
												{									
													var site_name = (document.getElementById('add_site_name').value).replace(/^\s+|\s+$/g,'');
													var gallery_name = document.getElementById('gallery_select_box').value ? (document.getElementById('gallery_select_box').value) : '0';
													var site_desc = (document.getElementById('add_site_desc').value).replace(/^\s+|\s+$/g,'');
													var site_location = (document.getElementById('add_site_location').value).replace(/^\s+|\s+$/g,'');
													var site_city = (document.getElementById('add_site_city').value).replace(/^\s+|\s+$/g,'');
													var site_state = (document.getElementById('add_site_state').value).replace(/^\s+|\s+$/g,'');
													var longitude = document.getElementById('add_site_longitude').value ? (document.getElementById('add_site_longitude').value).replace(/^\s+|\s+$/g,'') : '';
													var latitude = document.getElementById('add_site_latitude').value ? (document.getElementById('add_site_latitude').value).replace(/^\s+|\s+$/g,'') : '';
													var site_contact = document.getElementById('add_site_contact').value ? (document.getElementById('add_site_contact').value).replace(/^\s+|\s+$/g,'') : '';
													var site_price = document.getElementById('add_site_price').value ? (document.getElementById('add_site_price').value).replace(/^\s+|\s+$/g,'') : '';
													var site_hours = document.getElementById('add_site_hours').value ? (document.getElementById('add_site_hours').value).replace(/^\s+|\s+$/g,'') : '';
													var site_web = document.getElementById('add_site_web_site').value ? (document.getElementById('add_site_web_site').value).replace(/^\s+|\s+$/g,'') :'';
													var interest = document.getElementById('interest_select_box').value;
													var userfile = (document.getElementById('userfile').value).replace(/^\s+|\s+$/g,'');
													var free_or_paid = $('input[name=add_app_type]:radio:checked').val();
													if(site_name=='')
													{
														alert("Historical Site Name cannot be empty.");
														document.getElementById('add_site_name').focus();
														e.preventDefault();
													}else if(interest=='0' || interest=='')
													{
														alert("Please Select Historical Interest.");
														document.getElementById('interest_select_box').focus();
														e.preventDefault();
													}else if(userfile=='')
													{
														alert("Please Select Feature Image for upload.");
														document.getElementById('userfile').focus();
														e.preventDefault();
													}else if(site_location=='')
													{
														alert("Street Address cannot be empty.");
														document.getElementById('add_site_location').focus();
														e.preventDefault();
													}else if(site_city=='')
													{
														alert("City cannot be empty.");
														document.getElementById('add_site_city').focus();
														e.preventDefault();
													}else if(site_state=='')
													{
														alert("State cannot be empty.");
														document.getElementById('add_site_state').focus();
														e.preventDefault();
													}/*else if(gallery_name=='0' || gallery_name=='')
													{
														//alert("Please Select Gallery.");
														//document.getElementById('gallery_select_box').focus();
														//e.preventDefault();
													}*/else if(site_desc=='')
													{
														alert("Historical Site Description cannot be empty.");
														document.getElementById('add_site_desc').focus();
														e.preventDefault();
													}else
													{
														add_site(site_name,free_or_paid,gallery_name,site_desc,site_location,site_city,site_state,longitude,latitude,interest,site_contact,site_price,site_hours,site_web);										
													}
												}
											
											}
										}];
										
								$.prompt(show_site_box);
								
							}
						});
				}
			});	
	}

	function add_site(site_name,free_or_paid,gallery_name,site_desc,site_location,site_city,site_state,site_longitude,site_latitude,interest,site_contact,site_price,site_hours,site_web)
	{
	
		$.ajaxFileUpload({
			url:"<?php echo site_url("admin/site/add_site"); ?>",
			secureuri:false,
			fileElementId:'userfile',
			dataType: 'JSON',
			type: 'POST',
			data:{
					'site_name' : site_name ,
					'free_or_paid' : free_or_paid,
					'gallery_name' : gallery_name ,
					'site_desc' : site_desc,
					'site_location' : site_location,
					'city' : site_city,
					'state' : site_state,
					'longitude' : site_longitude,
					'latitude' : site_latitude,
					'interest' : interest,
					'contact' : site_contact,
					'price' : site_price,
					'hours' : site_hours,
					'web_site' : site_web	  
				 },
			success: function (data, status)
					{
						if(data=='OK')
						{
							window.location.reload();
						}else if(data=='no-file')
						{
							alert("You are not selected feature image...");
						}else{
							alert("Some Error...");	
						}
					}

			});

	}
							
	function prompt_delete_site(id)
	{
		var show_delete_media_box=[{
							title:"Delete Historical Site",
							html: "<strong>are you really want to delete this Site ?</strong>",
							buttons:{"Delete" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									conformed_delete_site(id);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);
	
	}
	
	function conformed_delete_site(id)
	{
		$.ajax({
					url: '<?php echo site_url('admin/site/remove_site');?>',		
					type: 'POST',
					data: {
							'id': id
						  },
					success: function(resp) {
					window.location.reload();						
					}
				});			
	}

	function show_editSite_box(id)
	{
		$.ajax({
					url: '<?php echo site_url('admin/site/get_edit');?>',		
					type: 'POST',
					data: {									
							'id': id
						  },
					success: function(resp) {
						var data = eval('('+resp+')');
						var site = data['0'];
						var interests = data['1'];
						var galleries = data['2'];
	
						var interest_select_box = '<select id="interest_select_box"><option value="0">Please Select Interest</option>';
						if(interests!='no-data')
						{									
							for(var i=0;i<interests.length;i++)
							{
								if(site.interest_id==interests[i].interest_id)
								{
									interest_select_box = interest_select_box + '<option value="'+interests[i].interest_id+'" selected>'+interests[i].title+'</option>';
								}else
								{
									interest_select_box = interest_select_box + '<option value="'+interests[i].interest_id+'">'+interests[i].title+'</option>';
								}
							}																		
						}
						interest_select_box = interest_select_box + '</select>';
						
						var gallery_select_box = '<select id="gallery_select_box"><option value="0">Please Select Gallery</option>';
						if(galleries!='no-data')
						{									
							for(var i=0;i<galleries.length;i++)
							{
								if(site.gallery_id==galleries[i].gallery_id)
								{
									gallery_select_box = gallery_select_box + '<option value="'+galleries[i].gallery_id+'" selected>'+galleries[i].gallery_name+'</option>';
								}else
								{
									gallery_select_box = gallery_select_box + '<option value="'+galleries[i].gallery_id+'">'+galleries[i].gallery_name+'</option>';
								}
							}																		
						}
						gallery_select_box = gallery_select_box + '</select>';
						
						var html='<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Edit Site</h2></div>'+
						'<div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal">'+
						'<div class="control-group"><input type="hidden" id="edit_site_id" value="'+site.site_id+'"></input>'+
						'<label class="control-label" for="edit_site_name">Historical Site Name * : </label><div class="controls">'+
						'<input class="input-xlarge" type="text" id="edit_site_name" value="'+site.site_name+'"></input></div></div>'+
						'<div class="control-group"><label class="control-label" >For Free/Paid App * : </label>'+
						'<div class="controls"><input class="input-xlarge" type="radio" id="edit_app_type_paid" name="edit_app_type" value="1" '+((site.app_type == 1 || site.app_type == '1') ? 'checked' : '')+'>Paid app</input><input class="input-xlarge" type="radio" id="edit_app_type_free" name="edit_app_type" value="2" '+((site.app_type == 2 || site.app_type == '2') ? 'checked' : '')+'>Free app</input></div></div>'+
						'<div class="control-group"><label class="control-label" for="interest_select_box">Select Interest * : </label>'+
						'<div class="controls">'+interest_select_box+'</div></div><div class="control-group">'+
						'<input type="hidden" id="feature_image_id" value="'+site.feature_image_id+'"></input><label class="control-label" for="userfile">Current Feature Image * : </label><div class="controls"><img height="50px" width="50px" src="<?php echo base_url();?>'+site.path+'"/>'+
						'<input type="file" id="userfile" name="userfile" class="input-xlarge"></input></div></div>'+
						'<div class="control-group"><label class="control-label" for="edit_site_location">Street Address * : </label>'+
						'<div class="controls"><input class="input-xlarge" type="text" id="edit_site_location" value="'+site.location+'"></input></div></div>'+
						'<div class="control-group"><label class="control-label" for="edit_site_city">City * : </label><div class="controls">'+
						'<input type="text" id="edit_site_city" value="'+((site.city).substr(0, 1).toUpperCase() + (site.city).substr(1).toLowerCase())+'"></input></div></div>'+ 
						'<div class="control-group"><label class="control-label" for="edit_site_state">State * : </label><div class="controls">'+
						'<input class="input-xlarge" type="text" id="edit_site_state" value="'+((site.state).substr(0, 1).toUpperCase() + (site.state).substr(1).toLowerCase())+'"></input></div></div>'+
						'<div class="control-group"><label for="edit_site_longitude" class="control-label">Longitude : </label><div class="controls"><input type="text" id="edit_site_longitude" class="input-xlarge" value="'+site.longitude+'"></input></div></div>'+
								'<div class="control-group"><label for="edit_site_latitude" class="control-label">Latitude : </label><div class="controls"><input type="text" id="edit_site_latitude" class="input-xlarge" value="'+site.latitude +'"></input></div></div>'+
						'<div class="control-group"><label for="edit_site_contact" class="control-label">Contact Phone : </label><div class="controls"><input type="text" id="edit_site_contact" class="input-xlarge" value="'+site.contact+'"></input></div></div>'+
						'<div class="control-group"><label for="edit_site_price" class="control-label">Price : </label><div class="controls"><input type="text" id="edit_site_price" class="input-xlarge" value="'+site.price+'"></input></div></div>'+
						'<div class="control-group"><label for="edit_site_hours" class="control-label">Hours of Operation : </label><div class="controls"><input type="text" id="edit_site_hours" class="input-xlarge" value="'+site.hours+'"></input></div></div>'+
						'<div class="control-group"><label for="edit_site_web_site" class="control-label">Web Site : </label><div class="controls"><input type="text" id="edit_site_web_site" class="input-xlarge" value="'+site.web_site+'"></input></div></div>'+
						'<div class="control-group"><label class="control-label" for="gallery_select_box">Select Image Gallery : </label><div class="controls">'+gallery_select_box+'</div></div>'+
						'<div class="control-group"><label class="control-label" for="edit_site_desc">Historical Site Description * : </label>'+
						'<div class="controls"><textarea type="text" id="edit_site_desc" class="input-xlarge">'+site.description+'</textarea></div></div></form></div></div>';	
						
						var edit_site_box=[{
							html: html,
							buttons:{"Update" : true , "Cancel" : false},
							submit: function(e,v,m,f){
									if(v==true)
									{									
										var site_name = (document.getElementById('edit_site_name').value).replace(/^\s+|\s+$/g,'');
										var gallery_name = document.getElementById('gallery_select_box').value ? document.getElementById('gallery_select_box').value : '0';
										var site_desc = (document.getElementById('edit_site_desc').value).replace(/^\s+|\s+$/g,'');
										var site_location = (document.getElementById('edit_site_location').value).replace(/^\s+|\s+$/g,'');
										var site_city = (document.getElementById('edit_site_city').value).replace(/^\s+|\s+$/g,'');
										var site_state = (document.getElementById('edit_site_state').value).replace(/^\s+|\s+$/g,'');
										var site_contact = document.getElementById('edit_site_contact').value ? (document.getElementById('edit_site_contact').value).replace(/^\s+|\s+$/g,'') : '';
										var longitude = document.getElementById('edit_site_longitude').value ? (document.getElementById('edit_site_longitude').value).replace(/^\s+|\s+$/g,'') : '';
										var latitude  = document.getElementById('edit_site_latitude').value ? (document.getElementById('edit_site_latitude').value).replace(/^\s+|\s+$/g,'') : '';
										var site_price = document.getElementById('edit_site_price').value ? (document.getElementById('edit_site_price').value).replace(/^\s+|\s+$/g,'') : '';
										var site_hours = document.getElementById('edit_site_hours').value ? (document.getElementById('edit_site_hours').value).replace(/^\s+|\s+$/g,'') : '';
										var site_web = document.getElementById('edit_site_web_site').value ? (document.getElementById('edit_site_web_site').value).replace(/^\s+|\s+$/g,'') :'';
										var interest = document.getElementById('interest_select_box').value;
										var free_or_paid = $('input[name=edit_app_type]:radio:checked').val();
										if(site_name=='')
										{
											alert("Historical Site Name cannot be empty.");
											document.getElementById('edit_site_name').focus();
											e.preventDefault();
										}else if(interest=='0' || interest=='')
										{
											alert("Please Select Historical Interest.");
											document.getElementById('interest_select_box').focus();
											e.preventDefault();
										}else if(site_location=='')
										{
											alert("Street Address cannot be empty.");
											document.getElementById('edit_site_location').focus();
											e.preventDefault();
										}else if(site_city=='')
										{
											alert("City cannot be empty.");
											document.getElementById('edit_site_city').focus();
											e.preventDefault();
										}else if(site_state=='')
										{
											alert("State cannot be empty.");
											document.getElementById('edit_site_state').focus();
											e.preventDefault();
										}/*else if(gallery_name=='0' || gallery_name=='')
										{
											alert("Please Select Gallery.");
											document.getElementById('gallery_select_box').focus();
											e.preventDefault();
										}*/else if(site_desc=='') 
										{
											alert("Historical Site Description cannot be empty.");
											document.getElementById('edit_site_desc').focus();
											e.preventDefault();
										}else
										{
											edit_site(site_name,free_or_paid,gallery_name,site_desc,site_location,site_city,site_state,longitude,latitude,interest,site_contact,site_price,site_hours,site_web);										
										}
																				
									}
						
								}
							}];
								
						$.prompt(edit_site_box);
					}
			});				
	}

	function edit_site(site_name,free_or_paid,gallery_name,site_desc,site_location,site_city,site_state,longitude,latitude,interest,site_contact,site_price,site_hours,site_web)
	{
		var site_id = document.getElementById('edit_site_id').value;
		var image_id = document.getElementById('feature_image_id').value;
		
		$.ajaxFileUpload({
			url:"<?php echo site_url("admin/site/edit_site"); ?>",
			secureuri:false,
			fileElementId:'userfile',
			dataType: 'JSON',
			type: 'POST',
			data:{
				'site_name' : site_name ,
				'free_or_paid' : free_or_paid ,
				'gallery_name' : gallery_name ,
				'site_desc' : site_desc ,
				'site_location' : site_location ,
				'city' : site_city ,
				'state' : site_state ,
				'longitude' : longitude ,
				'latitude' : latitude ,
				'interest' : interest ,
				'site_id': site_id , 
				'image_id': image_id,
				'contact' : site_contact,
				'price' : site_price,
				'hours' : site_hours,
				'web_site' : site_web
				},
			success: function (data, status)
					{
						if(data=='OK')
						{
							window.location.reload();
						}else
						{
							alert("Some Error...");
						}
					}
			});
	}
					
</script>
	</head>
	<body>
		<?php echo $admin_header;?>
		
		<div class="container-fluid">
		  <div class="row-fluid">
		   
		   <?php include 'left-sidebar.php'; ?> 
		   
			<div id="content" class="span10">
			<!-- content starts -->
				<?php /*?><div>
					<ul class="breadcrumb">
						<li>
							<a href="#">Home</a> <span class="divider">/</span>
						</li>
						<li>
							<a href="#">Historical Sites</a>
						</li>
					</ul>
				</div><?php */?>
					
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Tour Sites</h2>
						<div class="box-icon">
							<!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
					<button class ="btn btn-info" style="float:right;" onClick="javascript:show_addSite_box(); return false;"><i class="icon-edit icon-white"></i>Add Site</button>
					<table class="table table-striped table-bordered bootstrap-datatable datatable">
						<thead>
							<tr>
								<th>Index</th><th>Sites</th><th>For Free/Paid app</th><th>Feature Image</th><th>Location</th><th>Action</th>
							</tr>
						</thead>
						<tbody id='siteList'>
					<?php if($sites['0'] != "no-data") 
							{
								$app_type = array( 1 => 'For Paid app', 2 => 'For Free app');
								$i = (isset($index) ? $index : 0);
								foreach($sites as $site)
								{ $i++;?>
									<tr id="site_<?php echo $site['site_id'];?>">
										<td><?php echo $i;?></td>
										<td><?php echo $site['site_name'];?></td>
										<td><?php echo (isset($app_type[$site['app_type']]) ? $app_type[$site['app_type']] : 'For Paid app');?></td>
										<?php if($site['feature_image_id'] == '0') {?>
										<td>No Image</td>
										<?php }else{?>
										<td><img id="feature_<?php echo $site['feature_image_id'];?>" src="<?php echo base_url($site['path']);?>"/></td>
										<?php }?>
										<td><?php echo $site['location'];?></td>
										<td>
											<a class="btn btn-info" id="edit_<?php echo $site['site_id'];?>" href="#" onClick="javascript:show_editSite_box(this.id); return false;"><i class="icon-edit icon-white"></i>Edit</a>																								
											<a class="btn btn-danger" id="delete_<?php echo $site['site_id'];?>" href="#" onClick="javascript:prompt_delete_site(this.id); return false;"><i class="icon-trash icon-white"></i>Delete</a>
										</td>								
									</tr>
					  <?php 	}
							} ?>
						</tbody>	
					</table>
				    </div>
				  </div>
				
		<ul id="pagination-digg"><?php if(isset($pages)) echo $pages;?></ul>	
	  </div>
    </div>
	
	<?php include 'admin-footer.php'; ?>
	
   </div>	
	</body>
</html>
<style type="text/css">
#siteList img{ width: 100px; height: 75px;}
</style>
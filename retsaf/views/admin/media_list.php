<html>
	<head>
		<title>Media Gallery List</title>
		<script type="text/javascript">
			
			function show_addMedia_box()
			{					
				$.ajax({
				url: '<?php echo site_url('admin/media/getInfoForSelectBox');?>',		
				data: null,
				success: function(response) {
					var select = eval('('+response+')');
					var types = select['0'];
					var interests = select['1'];
						var type_select_box = '<select id="type_select_box" ><option value="0" selected>Please Select Media Type</option>';
						
						if(types['0']!='no-data')
						{									
							for(var i=0;i<types.length;i++)
							{
								type_select_box = type_select_box + '<option value="'+types[i].mediaType_id+'">'+types[i].mediaType_title+'</option>';
							}																		
						}
						type_select_box = type_select_box + '</select>';
						
						var interest_select_box = '<select id="sites_select_box" ><option value="0" selected>Please Select Interest</option>';
						
						if(interests['0']!='no-data')
						{									
							for(var i=0;i<interests.length;i++)
							{
								interest_select_box = interest_select_box + '<option value="'+interests[i].interest_id+'">'+interests[i].title+'</option>';
							}																		
						}
						interest_select_box = interest_select_box + '</select>';
						
						var html='<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Create New Media</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><label class="control-label" for="add_media_name">Media Name * : </label><div class="controls"><input type="text" id="add_media_name" class="input-xlarge"></input></div></div>'+'<div class="control-group"><label for="type_select_box" class="control-label">Select Media Type * : </label><div class="controls">'+type_select_box+'</div></div>'+'<div class="control-group"><label class="control-label" for="sites_select_box">Select Interest * : </label><div class="controls">'+interest_select_box+'</div></div>'+'<div class="control-group"><label class="control-label" for="add_media_source_url">Media Source URL * : </label><div class="controls"><input type="text" id="add_media_source_url" class="input-xlarge"></input></div></div>'+'<div class="control-group"><label for="add_media_desc" class="control-label">Media Description * : </label><div class="controls"><textarea type="text" id="add_media_desc" class="input-xlarge"></textarea> </div></div>'+'<div class="control-group"><label for="userfile" class="control-label">Feature Image * : </label><div class="controls"><input type="file" id="userfile" name="userfile" class="input-file uniform_on"></input></div></div>'+'</form></div></div>';	
									
								var show_addMedia_box=[{
											html: html,
											buttons:{"Save" : true , "Cancel" : false},
											submit: function(e,v,m,f){
												if(v==true)
												{									
													var media_name = (document.getElementById('add_media_name').value).replace(/^\s+|\s+$/g,'');
													var media_type_id = document.getElementById('type_select_box').value;
													var site_id = document.getElementById('sites_select_box').value;
													var source_url = (document.getElementById('add_media_source_url').value).replace(/^\s+|\s+$/g,'');
													var media_desc = (document.getElementById('add_media_desc').value).replace(/^\s+|\s+$/g,'');
													var userfile = (document.getElementById('userfile').value).replace(/^\s+|\s+$/g,'');
													if(media_name=='')
													{
														alert("Media Name cannot be empty.");
														document.getElementById('add_media_name').focus();
														e.preventDefault();
													}else if(media_type_id=='0' || media_type_id=='')
													{
														alert("Please Select Media Type.");
														document.getElementById('type_select_box').focus();
														e.preventDefault();
													}else if(site_id=='0' || site_id=='')
													{
														alert("Please Select Interest.");
														document.getElementById('sites_select_box').focus();
														e.preventDefault();
													}else if(source_url=='')
													{
														alert("Media Source URL cannot be empty.");
														document.getElementById('add_media_source_url').focus();
														e.preventDefault();
													}else if(media_desc=='')
													{
														alert("Media Description cannot be empty.");
														document.getElementById('add_media_desc').focus();
														e.preventDefault();
													}else if(userfile=='')
													{
														alert("Please Select Feature Image for upload.");
														document.getElementById('userfile').focus();
														e.preventDefault();
													}else
													{
														add_media(media_name,media_type_id,site_id,source_url,media_desc);										
													}											
												}
											
											}
										}];
										
								$.prompt(show_addMedia_box);
				}
			});	
					

			}
			
			function add_media(media_name,media_type_id,site_id,source_url,media_desc)
			{
				
				$.ajaxFileUpload({
					url:"<?php echo site_url("admin/media/add_media"); ?>",
					secureuri:false,
					fileElementId:'userfile',
					dataType: 'JSON',
					type: 'POST',
					data:{
							'media_name' : media_name ,
							'media_type_id' : media_type_id ,
							'site_id' : site_id ,
							'source_url' : source_url ,	
							'media_desc' : media_desc	
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
			
			function show_edit_media_box(id)
			{
				$.ajax({
							url: '<?php echo site_url('admin/media/get_edit');?>',		
							type: 'POST',
							data: {									
									'id': id
								  },
							success: function(resp) {
								var data = eval('('+resp+')');
								
								var types = data['0'];
								var interests = data['1'];
								var media = data['2'];
									
							//type select box with selected value
								var type_select_box = '<select id="type_select_box"><option value="0">Please Select Media Type</option>';
								if(types['0']!='no-data')
								{									
									for(var i=0;i<types.length;i++)
									{
										if(media.mediaType_id==types[i].mediaType_id)
										{
											type_select_box = type_select_box + '<option value="'+types[i].mediaType_id+'" selected>'+types[i].mediaType_title+'</option>';
										}else
										{
											type_select_box = type_select_box + '<option value="'+types[i].mediaType_id+'">'+types[i].mediaType_title+'</option>';
										}
									}																		
								}
								type_select_box = type_select_box + '</select>';
							//site select box with selected value
								var interest_select_box = '<select id="site_select_box"><option value="0">Please Select Interest</option>';
								if(interests['0']!='no-data')
								{									
									for(var i=0;i<interests.length;i++)
									{
										if(media.interest_id==interests[i].interest_id)
										{
											interest_select_box = interest_select_box + '<option value="'+interests[i].interest_id+'" selected>'+interests[i].title+'</option>';
										}else
										{
											interest_select_box = interest_select_box + '<option value="'+interests[i].interest_id+'">'+interests[i].title+'</option>';
										}
									}																		
								}
								interest_select_box = interest_select_box + '</select>';
								var html='<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Edit Media</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data"  class="form-horizontal"><div class="control-group"><input type="hidden" id="edit_media_id" value="'+media.media_id+'"><label for="edit_media_name" class="control-label">Media Name * : </label><div class="controls"><input class="input-xlarge" type="text" id="edit_media_name" value="'+media.title+'"/></div></div>'+'<div class="control-group"><label class="control-label" for="type_select_box">Select Media Type * : </label><div class="controls">'+type_select_box+'</div></div>'+'<div class="control-group"><label class="control-label" for="site_select_box">Select Interest * : </label><div class="controls">'+interest_select_box+'</div></div>'+'<div class="control-group"><label class="control-label" for="edit_media_source_url">Media Source URL * : </label><div class="controls"><input class="input-xlarge" type="text" id="edit_media_source_url" value="'+media.url+'"/></div></div>'+'<div class="control-group"><label class="control-label" for="edit_media_desc">Media Description * : </label><div class="controls"><textarea type="text" id="edit_media_desc" class="input-xlarge">'+media.media_desc+'</textarea></div></div>'+'<div class="control-group"><input type="hidden" id="edit_feature_image_id" value="'+media.feature_image_id+'" /><label class="control-label" for="userfile">Current Feature Image * : </label><div class="controls"><img height="50px" width="50px" src="<?php echo base_url(); ?>'+media.path+'"/><input class="input-file uniform_on" type="file" id="userfile" name="userfile"></input></div></div>'+'</form></div></div>';	
											
								var edit_media_box=[{
									html: html,
									buttons:{"Update" : true , "Cancel" : false},
									submit: function(e,v,m,f){
											if(v==true)
											{									
												var media_name = (document.getElementById('edit_media_name').value).replace(/^\s+|\s+$/g,'');
												var media_type_id = document.getElementById('type_select_box').value;
												var site_id = document.getElementById('site_select_box').value;
												var source_url = (document.getElementById('edit_media_source_url').value).replace(/^\s+|\s+$/g,'');
												var media_desc = (document.getElementById('edit_media_desc').value).replace(/^\s+|\s+$/g,'');
										
												if(media_name=='')
												{
													alert("Media Name cannot be empty.");
													document.getElementById('edit_media_name').focus();
													e.preventDefault();
												}else if(media_type_id=='0' || media_type_id=='')
												{
													alert("Please Select Media Type.");
													document.getElementById('type_select_box').focus();
													e.preventDefault();
												}else if(site_id=='0' || site_id=='')
												{
													alert("Please Select Interest.");
													document.getElementById('site_select_box').focus();
													e.preventDefault();
												}else if(source_url=='')
												{
													alert("Media Source URL cannot be empty.");
													document.getElementById('edit_media_source_url').focus();
													e.preventDefault();
												}else if(media_desc=='')
												{
													alert("Media Description cannot be empty.");
													document.getElementById('edit_media_desc').focus();
													e.preventDefault();
												}else
												{
													edit_media(media_name,media_type_id,site_id,source_url,media_desc);										
												}										
											}
								
										}
									}];
										
								$.prompt(edit_media_box);
							}
					});				
			}
			
			function edit_media(media_name,media_type_id,site_id,source_url,media_desc)
			{
				
				var media_id = document.getElementById('edit_media_id').value;
				var image_id = document.getElementById('edit_feature_image_id').value;
				
				$.ajaxFileUpload({
					url:"<?php echo site_url("admin/media/edit_media"); ?>",
					secureuri:false,
					fileElementId:'userfile',
					dataType: 'JSON',
					type: 'POST',
					data:{
						'media_id' : media_id ,
						'media_name' : media_name ,
						'media_type_id' : media_type_id ,
						'source_url' : source_url ,	
						'media_desc' : media_desc ,	
						'site_id': site_id , 
						'image_id': image_id
						},
					success: function (data, status)
							{
								if(data=='OK')
								{
									window.location.reload();
								}else{
									alert("Some Error...");	
								}
							}

					});
			}
			
			function prompt_delete_media(id)
			{
				var show_delete_media_box=[{
							title:"Delete Media",
							html: "<strong>are you really want to delete this media ?</strong>",
							buttons:{"Delete" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									conformed_delete_media(id);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);		
			}
			
			function conformed_delete_media(id)
			{
				$.ajax({
							url: '<?php echo site_url('admin/media/remove_media');?>',		
							type: 'POST',
							data: {
									'id': id
								  },
							success: function(resp) {
							//alert(resp);
							window.location.reload();						
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
							<a href="#">Media Gallery</a>
						</li>
					</ul>
				</div><?php */?>
					
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Media Gallery</h2>
						<div class="box-icon">
							<!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<button class = "btn btn-info" style="float:right" onClick="javascript:show_addMedia_box(); return false;"><i class="icon-edit icon-white"></i>Add Media</button>
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
							<thead>
								<tr>
									<th>Index</th><th>Media</th><th>Feature Image</th><th>URL</th><th>Type</th><th>Action</th>
								</tr>
							</thead>
							<tbody id='mediaList'>
						<?php if($media['0'] != "no-data") 
								{
									$i = (isset($index) ? $index : 0);
									foreach($media as $med)
									{ $i++;?>
										<tr id="mediatr_<?php echo $med['media_id'];?>">
											<td><?php echo $i;?></td>
											<td><a href="<?php echo $med['url'];?>" target="_blank"> <?php echo $med['title'];?></a></td>
											<?php if($med['feature_image_id'] == '0') {?>
											<td>No Image</td>
											<?php }else{?>
											<td><img id="feature_<?php echo $med['feature_image_id'];?>" src="<?php echo base_url($med['path']);?>"/></td>
											<?php }?>
											<td><?php echo $med['url'];?></td>
											<td><?php echo $med['mediaType_title'];?></td>
											<td><a class="btn btn-info" id="edit_<?php echo $med['media_id'];?>" href="#" onClick="javascript:show_edit_media_box(this.id); return false;"><i class="icon-edit icon-white"></i>Edit</a>																								
												<a class="btn btn-danger" id="delete_<?php echo $med['media_id'];?>" href="#" onClick="javascript:prompt_delete_media(this.id); return false;"><i class="icon-trash icon-white"></i>Delete</a>
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
#mediaList img{ width: 100px; height: 75px;}
</style>
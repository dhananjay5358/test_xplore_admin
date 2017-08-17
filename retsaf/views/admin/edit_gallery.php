<html>
	<head>
		<title>Gallery : <?php echo $gallery['gallery_name']; ?></title>
		<script src="<?php echo base_url('js/jquery-1.9.1.js');?>"></script>
		<script src="<?php echo base_url('js/jquery-impromptu.js');?>"></script>
		<script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
       <!-- <script src="<?php echo base_url('js/jpicker-1.1.6.js');?>"></script>-->
		
		<link href="<?php echo base_url('css/jquery-impromptu.css');?>" media="screen" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('css/pagination-style.css');?>" media="screen" rel="stylesheet" type="text/css"/>
		<!--<link href="<?php echo base_url('css/jPicker-1.1.6.css');?>" media="screen" rel="stylesheet" type="text/css"/>-->
        <link href="<?php echo base_url('css/admin.css');?>" media="screen" rel="stylesheet" type="text/css"/>
                
                
                
 
        <script type="text/javascript">
			
			function change_gallery_name()
			{
					var id = document.getElementById('image_gallery_id').value;
					var name = (document.getElementById('image_gallery_name').value).replace(/^\s+|\s+$/g,'');
					if(name == '')
					{
						alert("Gallary Name cannot be empty.");
						document.getElementById('image_gallery_name').focus();
						return;
					}
					$.ajax({
							url: '<?php echo site_url('admin/gallery/update');?>',		
							type: 'POST',
							data: {
									'id' : id ,
									'name': name
								  },
							success: function(resp) {
							//alert(resp);
							window.location.reload();						
							}
						});
			}
			
			function show_addImage_box()
			{
				var gallery_name = document.getElementById('image_gallery_name').value;					
				
				var html='<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Add Image</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><label class="control-label" for="add_image_name">Image Name * : </label><div class="controls"><input type="text" id="add_image_name" class="input-xlarge"></input></div></div>'+'<div class="control-group"><label for="add_image_gallery_name" class="control-label">Under Gallery * : </label><div class="controls"><input type="text" class="input-xlarge" id="add_image_gallery_name" value="'+gallery_name+'" disabled/> </div></div>'+'<div class="control-group"><label class="control-label" for="userfile">Upload Image * : </label><div class="controls"><input class="input-file uniform_on" type="file" id="userfile" name="userfile"></input></div></div>'+'</form></div></div>';	
					
				var show_add_image_box=[{
							html: html,
							buttons:{"Save" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									var image_name = (document.getElementById('add_image_name').value).replace(/^\s+|\s+$/g,'');
									var userfile = (document.getElementById('userfile').value).replace(/^\s+|\s+$/g,'');
									if(image_name=='')
									{
										alert("Image Name cannot be empty.");
										document.getElementById('add_image_name').focus();
										e.preventDefault();
									}else if(userfile=='')
									{
										alert("Please Select Image for upload.");
										document.getElementById('userfile').focus();
										e.preventDefault();
									}else{
										add_image(image_name);										
									}										
								}
							
							}
						}];
						
				$.prompt(show_add_image_box);
			}
			
			function add_image(image_name)
			{
				
				var gallery_id = document.getElementById('image_gallery_id').value;
				
				$.ajaxFileUpload({
						url:"<?php echo site_url("admin/gallery/add_image"); ?>",
						secureuri:false,
						fileElementId:'userfile',
						dataType: 'JSON',
						type: 'POST',
						data:{
								'gallery_id' : gallery_id ,
								'image_name' : image_name 	
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
			
			function show_edit_imageBox(id)
			{
				var ids = id.split("_");
				var image_src = document.getElementById('imagetd_'+ids['1']).src;
				var image_name = document.getElementById('imageName_'+ids['1']).innerHTML;
				var gallery_name = document.getElementById('image_gallery_name').value;
				var html='<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Edit Image</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><input type="hidden" id="edit_image_id" value="'+ids['1']+'"><label for="edit_image_name" class="control-label">Image Name * : </label><div class="controls"><input type="text" id="edit_image_name" value="'+image_name+'"class="input-xlarge"></input></div></div>'+'<div class="control-group"><label for="edit_image_gallery_name" class="control-label">Under Gallery * : </label><div class="controls"><input type="text" class="input-xlarge" id="edit_image_gallery_name" value="'+gallery_name+'" disabled/></div></div>'+'<div class="control-group"><label for="userfile" class="control-label">Upload Image * : </label><div class="controls"><img height="50px" width="50px" src="'+image_src+'"/><input class="input-file uniform_on" type="file" id="userfile" name="userfile"></input></div></div>'+'</form></div></div>';	
					
				var show_edit_image_box=[{
							html: html,
							buttons:{"Save" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									var image_name = (document.getElementById('edit_image_name').value).replace(/^\s+|\s+$/g,'');
									var userfile = (document.getElementById('userfile').value).replace(/^\s+|\s+$/g,'');
									if(image_name=='')
									{
										alert("Image Name cannot be empty.");
										document.getElementById('edit_image_name').focus();
										e.preventDefault();
									}else{
										edit_image(image_name);											
									}										
								}
							
							}
						}];
						
				$.prompt(show_edit_image_box);
			}
			
			function edit_image(image_name)
			{
				var image_id = document.getElementById('edit_image_id').value;
				var gallery_id = document.getElementById('image_gallery_id').value;
				
				
				$.ajaxFileUpload({
						url:"<?php echo site_url("admin/gallery/edit_image"); ?>",
						secureuri:false,
						fileElementId:'userfile',
						dataType: 'JSON',
						type: 'POST',
						data:{
								'image_id' : image_id ,
								'gallery_id' : gallery_id ,	
								'image_name' : image_name 	
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
			
			function delete_image(id)
			{
				
				var show_delete_image_box=[{
							title:"Delete Image",
							html: "<strong>are you really want to delete this image ? </strong>",
							buttons:{"Delete" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									delete_image_conformed(id);									
								}
							
							}
						}];
						
				$.prompt(show_delete_image_box);
			}
			
			function delete_image_conformed(id)
			{
				$.ajax({
						url: '<?php echo site_url('admin/gallery/delete_image');?>',		
						type: 'POST',
						data: {
								'id' : id
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
							<a href="#">Edit Gallery</a>
						</li>
					</ul>
				</div><?php */?>
					
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Gallery</h2>
						<div class="box-icon">
							<!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<span style="float:right;">
							<button class = "btn btn-info" onClick="javascript:change_gallery_name(); return false;">Save</button>
							<button class = "btn btn-info" onClick="javascript:show_addImage_box(); return false;">Add Image</button>
						</span>
						<input type="hidden" id="image_gallery_id" value="<?php echo $gallery['gallery_id']; ?>"/>
						<div class="controls">
                           <label for="image_gallery_name">Gallary Name * : </label>
						   <input type="text" id="image_gallery_name" value="<?php echo $gallery['gallery_name']; ?>"/>
						</div>
						<?php if($images['0'] != "no-data") 
								{
									$i = (isset($index) ? $index : 0); ?>
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
							<thead>
								<tr>
									<th>Index</th><th>Name</th><th>Image</th><th>Action</th>
								</tr>
							</thead>
							<tbody id='imageList'>							
								<?	foreach($images as $image)
									{ $i++;?>
										<tr id="imagetr_<?php echo $image['image_id'];?>">
											<td><?php echo $i;?></td>
											<td id="imageName_<?php echo $image['image_id'];?>"><?php echo $image['name'];?></td>
											<td><img id="imagetd_<?php echo $image['image_id'];?>" src="<?php echo base_url($image['path']);?>"/></td>
											<td><a  class="btn btn-info"  id="edit_<?php echo $image['image_id'];?>" href="#" onClick="javascript:show_edit_imageBox(this.id);return false;"><i class="icon-edit icon-white"></i>Edit</a>																								
												<a  class="btn btn-danger" id="delete_<?php echo $image['image_id'];?>" href="#" onClick="javascript:delete_image(this.id); return false;"><i class="icon-trash icon-white"></i>Delete</a>
											</td>								
										</tr>
						  <?php 	}?>
								
							</tbody>	
						</table>
						<?php }else{ ?>
							<div style="color: #ff0000"><strong> No data available </strong> </div>
							<?php }?>	
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
#imageList img{ width: 100px; height: 75px;}
</style>
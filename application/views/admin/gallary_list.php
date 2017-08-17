<html>
	<head>
		<title>Image Gallery List</title>
		<script type="text/javascript">
			
			function show_addGallery_box()
			{
					
					var show_gallery_box=[{
								html: '<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Add Gallery</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><label class="control-label" for="add_gal">Gallery Name * : </label><div class="controls"><input type="text" id="add_gal" class="input-xlarge"></input></div></div></form></div></div>',
								buttons:{"Save" : true , "Cancel" : false},
								submit: function(e,v,m,f){
									var new_int = (document.getElementById('add_gal').value).replace(/^\s+|\s+$/g,'');
									if(v==true)
									{									
										if(new_int!='')
										{
										add_gallery(new_int);	
										}else{
										alert("Gallery Name cannot be empty.");
										document.getElementById('add_gal').focus();
										e.preventDefault();
										}										
									}
								
								}
							}];
										
				$.prompt(show_gallery_box);
			}
			
			function add_gallery(new_gallery)
			{
				$.ajax({
							url: '<?php echo site_url('admin/gallery/add_or_remove_gallery');?>',		
							type: 'POST',
							data: {
									'status' : 'add' ,
									'gallery': new_gallery
								  },
							success: function(resp) {
							//alert(resp);
							window.location.reload();						
							}
						});
			
			}
			
			
			function prompt_delete_gallery(id)
			{
				var show_delete_media_box=[{
							title:"Delete Image Gallery",
							html: "<strong>are you really want to delete this Gallery ?</strong>",
							buttons:{"Delete" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									conformed_delete_gallery(id);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);
			}
			
			function conformed_delete_gallery(id)
			{
				$.ajax({
							url: '<?php echo site_url('admin/gallery/add_or_remove_gallery');?>',		
							type: 'POST',
							data: {
									'status' : 'remove' ,
									'id': id
								  },
							success: function(resp) {
							//alert(resp);
							window.location.reload();						
							}
						});
			
			}
			
			function edit_gallery(id)
			{
				$.ajax({
							url: '<?php echo site_url('admin/interests/edit_interest');?>',		
							type: 'POST',
							data: {
									'interest' : interest ,
									'interest_id': thiss.id
								  },
							success: function(resp) {
							//alert(resp);
							//window.location.reload();	
								thiss.parentNode.previousSibling.innerHTML = interest;
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
							<a href="#">Image Gallery</a>
						</li>
					</ul>
				</div><?php */?>
					
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Image Gallery</h2>
						<div class="box-icon">
							<!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<button class = "btn btn-info" style="float:right;"onClick="javascript:show_addGallery_box(); return false;"><i class="icon-edit icon-white"></i>Add Gallery</button>
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
							<thead>
								<tr>
									<th>Index</th><th>Gallery</th><th>Action</th>
								</tr>
							</thead>
							<tbody id='galleryList'>
						<?php if($galleries['0'] != "no-data") 
								{
									$i = (isset($index) ? $index : 0);
									foreach($galleries as $gallery)
									{ $i++;?>
										<tr id="gallery_<?php echo $gallery['gallery_id'];?>">
											<td><?php echo $i;?></td>
											<td><?php echo $gallery['gallery_name'];?></td>
											<td><a class="btn btn-info" id="edit_<?php echo $gallery['gallery_id'];?>" href="<?php echo site_url('admin/gallery/edit_gallery/'.$gallery['gallery_id']);?>"><i class="icon-edit icon-white"></i>Edit</a>																								
											<a class="btn btn-danger" id="delete_<?php echo $gallery['gallery_id'];?>" href="#" onClick="javascript:prompt_delete_gallery(this.id); return false;">								<i class="icon-trash icon-white"></i>Delete</a>
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

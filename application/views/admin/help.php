<html>
	<head>
		<title>Welcome Help</title>
		
<script type="text/javascript">			
			
			
	function show_addMessage_box()
	{					
		var html = '<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Create New Help Message</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><label class="control-label" for="add_message">Message * : </label><div class="controls"><textarea class="input-xlarge" type="text" id="add_message"></textarea></div></div></form></div></div>';
		var add_message_box=[{
						html: html,
						buttons:{"Save" : true , "Cancel" : false},
						submit: function(e,v,m,f){
								if(v==true)
								{									
									var message = (document.getElementById('add_message').value).replace(/^\s+|\s+$/g,'');
									if(message!='')
									{
									add_message(message);
									}else{
									alert("Message cannot be empty.");
									document.getElementById('add_message').focus();
									e.preventDefault();
									}										
								}
					
							}
						}];
							
			$.prompt(add_message_box);
			
	}

	
	
	function add_message(message)
	{
		
		$.ajax({
					url: '<?php echo site_url('admin/help/add_or_remove_help');?>',		
					type: 'POST',
					data: {
							'status' : 'add' ,
							'instruction': message
						  },
					success: function(resp) {
					//alert(resp);
					window.location.reload();						
					}
				});
	
	}
	
	function delete_message(id)
	{
		
		var show_delete_message_box=[{
					title:"Delete Help Message",
					html: "<strong>are you really want to delete this Help Message ?</strong>",
					buttons:{"Delete" : true , "Cancel" : false},
					submit: function(e,v,m,f){
						if(v==true)
						{									
							delete_message_conformed(id);									
						}
					
					}
				}];
				
		$.prompt(show_delete_message_box);
	}
	
	function delete_message_conformed(id)
	{
		$.ajax({
				url: '<?php echo site_url('admin/help/add_or_remove_help');?>',		
				type: 'POST',
				data: {
						'status' : 'remove' ,
						'instruction_id': id
					  },
				success: function(resp) {
				//alert(resp);
				window.location.reload();						
				}
			});		
	}
	
	function show_editMessage_box(id)
	{
		var ids = id.split("_");
		var message = document.getElementById('messageName_'+ids['1']).innerHTML;
		
		var show_edit_message_box=[{
						html: '<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Edit Welcome Message</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><label class="control-label" for="edit_message">Message * : </label><div class="controls"><textarea type="text" id="edit_message" value="'+message+'">'+message+'</textarea></div></div></form></div></div>',
						buttons:{"Save" : true , "Cancel" : false},
						submit: function(e,v,m,f){							
							if(v==true)
							{									
								var message = (document.getElementById('edit_message').value).replace(/^\s+|\s+$/g,'');
									if(message!='')
									{
									edit_message(ids['1'],message);
									}else{
									alert("Message cannot be empty.");
									document.getElementById('edit_message').focus();
									e.preventDefault();
									}
												
							}
						
						}
					}];
								
		$.prompt(show_edit_message_box);				
	}
	
	function edit_message(id,message)
	{
	
		$.ajax({
					url: '<?php echo site_url('admin/help/edit_help');?>',		
					type: 'POST',
					data: {
							'instruction' : message ,
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
				<div>
					<ul class="breadcrumb">
						<li>
							<a href="#">Home</a> <span class="divider">/</span>
						</li>
						<li>
							<a href="#">Welcome Help</a>
						</li>
					</ul>
				</div>
					
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Help List</h2>
						<div class="box-icon">
							<!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">
						<button class="btn btn-info" style="float:right;" onClick="javascript:show_addMessage_box(); return false;"><i class="icon-edit icon-white"></i>Add Help Message</button>
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
							<thead>
								<tr>
									<th>Index</th><th>Message</th><th>Action</th>
								</tr>
							</thead>
							<tbody id='messageList'>
						<?php if($helps['0'] != "no-data") 
								{
									$i = (isset($index) ? $index : 0);
									foreach($helps as $help)
									{ $i++;?>
										<tr id="messagetr_<?php echo $help['instruction_id'];?>">
											<td><?php echo $i;?></td>
											<td id="messageName_<?php echo $help['instruction_id'];?>"><?php echo $help['instruction'];?></td>
											<td>
												<a class="btn btn-info"  id="edit_<?php echo $help['instruction_id'];?>" href="#" onClick="javascript:show_editMessage_box(this.id); return false;"><i class="icon-edit icon-white"></i>Edit</a>																								
												<a class="btn btn-danger"  id="delete_<?php echo $help['instruction_id'];?>" href="#" onClick="javascript:delete_message(this.id); return false;"><i class="icon-trash icon-white"></i>Delete</a>
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

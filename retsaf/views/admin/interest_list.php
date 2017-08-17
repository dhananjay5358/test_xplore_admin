<html>
	<head>
		<title>Historical Interest</title>
		<script type="text/javascript">
			
			function show_addInterest_box()
			{	
				var show_interest_box=[{
							html: '<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Create New Interest</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><label class="control-label" for="add_int">Interest Name * : </label><div class="controls"><input type="text" id="add_int" class="input-xlarge"></input></div><div class="controls capitalise"><input type="radio" name="capitalise" value="Default" checked>Default</input><input type="radio" name="capitalise" value="All">Capitalise All</input><input type="radio" name="capitalise" value="First">Capitalise First</input></div></div></form></div></div>',
							buttons:{"Save" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								var new_int = (document.getElementById('add_int').value).replace(/^\s+|\s+$/g,'');
								var capitalise = $("input[name=capitalise]:checked").val();
								if(v==true)
								{									
									if(new_int!='')
									{
									add_interest(new_int,capitalise);
									}else{
									alert("Interest Name cannot be empty.");
									document.getElementById('add_int').focus();
									e.preventDefault();
									}										
								}
							
							}
						}];
										
				$.prompt(show_interest_box);
			}
			
			function add_interest(new_interest,capitalise)
			{
				$.ajax({
							url: '<?php echo site_url('admin/interests/add_or_remove_interest');?>',		
							type: 'POST',
							data: {
									'status' : 'add' ,
									'interest': new_interest,
									'capitalise':capitalise 
								  },
							success: function(resp) {
							//alert(resp);
							window.location.reload();						
							}
						});
			
			}
			
			function prompt_delete_interest(id)
			{
				var show_delete_interest_box=[{
									title:"Delete Historical Interest",
									html: "<strong>are you really want to delete this Interest ?</strong>",
									buttons:{"Delete" : true , "Cancel" : false},
									submit: function(e,v,m,f){
										if(v==true)
										{									
											conformed_delete_interest(id);									
										}
									
									}
								}];
						$.prompt(show_delete_interest_box);
			
			}
			
			function conformed_delete_interest(id)
			{
				$.ajax({
							url: '<?php echo site_url('admin/interests/add_or_remove_interest');?>',		
							type: 'POST',
							data: {
									'status' : 'remove' ,
									'interest_id': id
								  },
							success: function(resp) {
							//alert(resp);
							window.location.reload();						
							}
						});
			
			}
			
			function show_editInterest_box(thiss)
			{
				
				$.ajax({
							url: '<?php echo site_url('admin/interests/getEdit_interest');?>',		
							type: 'POST',
							data: {
									'id': thiss.id
								  },
							success: function(resp) {
								var interest = resp;
								var show_interest_box=[{
												//title:"Edit Interest",
												//html: '<input type="text" id="edit_int" value="'+interest+'"></input>',
												html: '<div class="box span12"><div class="box-header well" data-original-title><h2><i class="icon-edit"></i> Edit Interest</h2></div><div class="box-content"><form method="POST" enctype="multipart/form-data" class="form-horizontal"><div class="control-group"><label class="control-label" for="edit_int">Interest Name * : </label><div class="controls"><input type="text" id="edit_int" class="input-xlarge" value="'+interest+'"></input></div></div></form></div></div>',
												buttons:{"Save" : true , "Cancel" : false},
												submit: function(e,v,m,f){
													var new_int = (document.getElementById('edit_int').value).replace(/^\s+|\s+$/g,'');
													if(v==true)
													{									
														if(new_int!='')
														{
															edit_interest(thiss,new_int);
														}else{
															alert("Interest Name cannot be empty.");
															document.getElementById('edit_int').focus();
															e.preventDefault();
														}										
													}
												
												}
											}];
														
								$.prompt(show_interest_box);						
							}
						});
				
				
								
			}
			
			function edit_interest(thiss,interest)
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
							window.location.reload();	
								//thiss.parentNode.previousSibling.innerHTML = interest;
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
								
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Historical Interest</h2>
						<div class="box-icon">
							<!--<a href="#" class="btn btn-setting btn-round"><i class="icon-cog"></i></a>-->
							<a href="#" class="btn btn-minimize btn-round"><i class="icon-chevron-up"></i></a>
							<a href="#" class="btn btn-close btn-round"><i class="icon-remove"></i></a>
						</div>
					</div>
					<div class="box-content">	
						<button class="btn btn-info" style="float:right;" onClick="javascript:show_addInterest_box(); return false;"><i class="icon-edit icon-white"></i>Add Historical Interest</button>
						<table class="table table-striped table-bordered bootstrap-datatable datatable">
							<thead>
								<tr>
									<th>Index</th><th>Interest</th><th>Action</th>
								</tr>
							</thead>
							<tbody id='interestList'>
						<?php if($interests['0'] != "no-data") 
								{
									$i = (isset($index) ? $index : 0);
									foreach($interests as $interest)
									{ $i++;?>
										<tr id="interest_<?php echo $interest['interest_id'];?>">
											<td><?php echo $i;?></td><td><?php echo $interest['title'];?></td>
											<td>
											  <a class="btn btn-info" id="edit_<?php echo $interest['interest_id'];?>" href="#" onClick="javascript:show_editInterest_box(this); return false;"><i class="icon-edit icon-white"></i> Edit</a>																								
											  <a class="btn btn-danger" id="delete_<?php echo $interest['interest_id'];?>" href="#" onClick="javascript:prompt_delete_interest(this.id); return false;"> <i class="icon-trash icon-white"></i>Delete</a>
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

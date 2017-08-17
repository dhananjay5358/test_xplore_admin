<html>
	<head>
		
		<style>
			.jqimessage input{
				width: 87%;
			}
			._button {
			    padding: 6px;
			    display: inline;
			    border-radius: 2px;
			    font-family: "Arial";
			    border: 0;
			    margin: 0 6px;
			    background: #2F6073;
			    font-size: 15px;
			    line-height: 15px;
			    color: white;
			    width: auto;
			    height: auto;
			    box-sizing: content-box;
			}
		</style>
		<title>Custom Places List</title>
		<script type="text/javascript">
			var data_for_select='';
			function prompt_delete_gallery(e)
			{
				var tr = $(e.target).closest("tr");
      		  	var data = this.dataItem(tr);

      		  	var show_delete_media_box=[{
							title:"Delete Custom places list",
							html: "<strong>are you really want to delete this Gallery ?</strong>",
							buttons:{"Delete" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									conformed_delete_gallery(data.experince_id);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);
			}
			
			function conformed_delete_gallery(id)
			{
				$.ajax({
							url: '<?php echo site_url('admin/places_/add_or_remove_custom_place');?>',		
							type: 'POST',
							data: {
									'status' : 'remove' ,
									'id': id
								  },
							success: function(resp) {
							window.location.reload();						
							}
						});
			
			}

			function prompt_accept_gallery(id)
			{
				var data = $(id).closest('tr');

				var html = "<br/><label>Place name</label><input type='text' value='"+data.find('.place_name').html()+"' id='place_name_'/><br/><label>Address</label><input type='text' value='"+data.find('.address').html()+"' id='address_'/><br/><label>Description</label><input type='text' value='"+data.find('.description').html()+"' id='description_'/><br/><label>Category</label><input type='text' value='"+data.find('.category').html()+"' id='category_'/></br><label>Longitude</label><input type='text' value='"+data.find('.longitude').html()+"' id='longitude_'/><br/><label>Latitude</label><input type='text' value='"+data.find('.latitude').html()+"' id='latitude_'/><br/><label>Website</label><input type='text' value='"+data.find('.web').html()+"' id='web_'/><br/><label>Phone</label><input type='text' value='"+data.find('.phone').html()+"' id='phone_'/><br/><label>Image</label><input type='text' value='"+data.find('.Image').html()+"'  id='Image_'/>";
				var show_delete_media_box=[{
							title:"Accept place",
							html: html,
							buttons:{"accept" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									accept_gallery(id);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);
			}
			
			function prompt_edit_gallery(e)	
			{
				var tr = $(e.target).closest("tr");
				  	var data = this.dataItem(tr);
				  	var id__=data.experince_id;
				  	      		  	
				$.ajax({
							url: '<?php echo site_url('admin/places_/getEdit_places');?>',		
							type: 'POST',
							data: {
									'id': id__
								  },
							success: function(resp) {
								var data = eval('('+resp+')');				
								var placename = data['0'];
								var exp = data['1'];
								

								
								var user = data['2'];
								var t_count;
								var exp_place_seperate=[];
								var select_box="";

								var userSelect="";

								var exp_place=[];
								var name_place;
								var desc_place;
								var ch_opt=[];
								var count_list=0;


								for(t_count=0;t_count<exp.length;t_count++)
								{
									userIDFB=exp[t_count].FbUserId;
									exp_place_seperate = exp[t_count].places.split(",");
								}
								

								for(var j=0;j<exp_place_seperate.length;j++)
					 			{

					 				exp_place = (exp_place_seperate[j]).split(":");

					 				try
					 				{
					 					name_place = window.atob(exp_place[1]);
					 					select_box = addSelectPlace(name_place, exp_place[0], select_box, placename);
					 				}
					 				catch(e)
					 				{
					 					console.log(e);
					 				}					 				
					 			}	


					 			function addSelectPlace(data,id,select_box_,placename_)
					 			{
					 				
					 				select_box_+='<div id="data_place_edit"><div><span>Select Places</span><br><span>';


									select_box_+='<select id="place_sel_box" name ="place_select_box[]" style="width:87%;">';

									select_box_+='<option value="" selected>Please Select places</option>';

									for(var k=0;k<placename_.length;k++)
									{
										try
										{
										var place_name__ = JSON.parse(""+placename_[k].Detail);
											if(id == placename_[k].PlaceIds)
											{	
												select_box_ += '<option value="'+placename_[k].PlaceIds+'" selected>'+place_name__['name']+'</option>';
											}
											else
											{

												select_box_ += '<option value="'+placename_[k].PlaceIds+'">'+place_name__['name']+'</option>';
											}

										}
										catch(e)
										{
										console.log(e);
										}

									}
									select_box_ += '</select>';

									select_box_+='</span></div><br/><div><span>Description</span><br/><span><textarea name="description[]" style="width:87%;">'+data+'</textarea></span></div></div><br/>';


									return select_box_;

					 			}

					 			
								data_for_select=select_box;
								
								var userSelect = '<div><span>User List</span><br><span><select id="user_select_box" name ="user_select_box[]" style="width:87%"><option value="" selected>Please Select user</option>';
								

								for(var a=0;a<user.length;a++)
								{
									if(userIDFB == user[a].FbUserId)
									{	
										userSelect = userSelect + '<option value="'+user[a].FbUserId+'"selected>'+user[a].FbUserId+'</option>';
									}
									else
									{

										userSelect = userSelect + '<option value="'+user[a].FbUserId+'">'+user[a].FbUserId+'</option>';
									}
								}																		
								
								userSelect = userSelect + '</select></span></div>';

								var link_ = "<?php echo site_url('admin/places_/edit_Place'); ?>";

								var link_cancel = "<?php echo site_url('admin/places_/experiance'); ?>";
								

								var html = '<br/><form method="POST" action="'+link_+'" enctype="multipart/form-data" class="form-horizontal"><input type="hidden" name="experince_id" value="'+exp[0].experince_id+'"><label>Tour name</label><input type="text" value="'+exp[0].tour_name+'" id="tour_name" name="tour_name"/><br/><br/><label>Tour Description</label><input type="text" value="'+exp[0].tour_description+'" id="tour_description" name="tour_description"/><br/><br/><label>Tags</label><input type="text" value="'+exp[0].tags+'" id="tags_detail" name="tags_detail"/><br/><br/><label>Public_private_group</label><input class="input-xlarge" type="radio" id="public_" name="group_detail" value="1" '+((exp[0].public_private_group == 1 || exp[0].public_private_group == 'Public') ? 'checked' : '')+' >Public</input><input class="input-xlarge" type="radio" id="private_" name="group_detail" value="2" '+((exp[0].public_private_group == 2 || exp[0].public_private_group == '2') ? 'checked' : '')+' >Private</input><input class="input-xlarge" type="radio" id="shared_" name="group_detail" value="3" '+((exp[0].public_private_group == 3 || exp[0].public_private_group == '3') ? 'checked' : '')+' >Shared</input><br/></br>';
									html = html + '<br>'+data_for_select+'<br/>'+userSelect+'<div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">submit</button><a href="'+link_cancel+'" name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</a></div></form>';
								
								var edit_exp_box=[{
											title:"Edit place",
											html: html,
											buttons:{},
											submit: function(e,v,m,f){
												if(v==true)
												{										
													// var tour_name_ = (document.getElementById('tour_name_info').value).replace(/^\s+|\s+$/g,'');
													// var tour_description_ = document.getElementById('tour_desc_info').value;
													// var tags_ = document.getElementById('tagsinfo').value;
													// var group_ = $('input[name=ppgroup_info]:radio:checked').val();

													// var places_ = (document.getElementById('places_info').value).replace(/^\s+|\s+$/g,'');
													// if(tour_name_=='')
													// {
													// 	alert("Tour name cannot be empty.");
													// 	document.getElementById('tour_name_info').focus();
													// 	e.preventDefault();
													// }else if(tour_description_=='')
													// {
													// 	alert("Tour description can not be empty.");
													// 	document.getElementById('tour_desc_info').focus();
													// 	e.preventDefault();
													// }else if(tags_=="")
													// {
													// 	alert("Tags can not be empty.");
													// 	document.getElementById('tagsinfo').focus();
													// 	e.preventDefault();
													// }else if(group_=='')
													// {
													// 	alert("Group cannot be empty.");
													// 	document.getElementById('ppgroup_info').focus();
													// 	e.preventDefault();
													// }else if(places_=='')
													// {
													// 	alert("Places cannot be empty.");
													// 	document.getElementById('places_info').focus();
													// 	e.preventDefault();
													// }else
													// {
													// 	edit_experience(tour_name_,tour_description_,tags_,group_,places_);										
													// }			
												}	
												
						
											}
										}];
									$.prompt(edit_exp_box);
								}
						});

				}			

			function accept_gallery(id)
			{
				var place_name = $('#place_name_').val();
				var address = $('#address_').val();
				var longitude = $('#longitude_').val();
				var latitude = $('#latitude_').val();
				var web = $('#web_').val();
				var phone = $('#phone_').val();
				var Image = $('#Image_').val();
				var description = $('#description_').val();
				var category = $('#category_').val();
				
				$.ajax({
						url: '<?php echo site_url('admin/places_/accept_Place');?>',		
						type: 'POST',
						data: {
								'id' : $(id).attr('id'),
								'place_name' : place_name ,
								'address': address,
								'longitude': longitude,
								'latitude': latitude,
								'web': web,
								'phone': phone,
								'Image': Image,
								'description' : description,
								'category' : category
							  },
						success: function(resp) {
							//thiss.parentNode.previousSibling.innerHTML = interest;
							//window.location.reload();
							console.log(resp);	
						}
				});
			}

			function edit_experience(tour_name_,tour_description_,tags_,group_,places_)
			{
				var experince_id = document.getElementById('exper_id').value;
				console.log(experince_id);
				console.log(tour_name_);
				//return;
				$.ajax({
						url: '<?php echo site_url('admin/places_/edit_Place');?>',		
						type: 'POST',
						data: {
								'experince_id' : experince_id,
								'tour_name' : tour_name_,
								'tour_desc': tour_description_,
								'tags': tags_,
								'ppgroup': group_,
								'places': places_
							  },
						success: function(resp) {
							//window.location.reload();
							console.log(resp);
						
						}
				});
			}

			// function edit_gallery(id)
			// {
			// 	var place_name = $('#place_name').val();
			// 	var address = $('#address').val();
			// 	var longitude = $('#longitude').val();
			// 	var latitude = $('#latitude').val();
			// 	var web = $('#web').val();
			// 	var phone = $('#phone').val();
			// 	var Image = $('#Image').val();
			// 	var description = $('#description').val();
			// 	var category = $('#category').val();
				
			// 	$.ajax({
			// 			url: '<?php //echo site_url('admin/places_/edit_Place');?>',		
			// 			type: 'POST',
			// 			data: {
			// 					'id' : $(id).attr('id'),
			// 					'place_name' : place_name ,
			// 					'address': address,
			// 					'longitude': longitude,
			// 					'latitude': latitude,
			// 					'web': web,
			// 					'phone': phone,
			// 					'Image': Image,
			// 					'description' : description,
			// 					'category' : category
			// 				  },
			// 			success: function(resp) {
			// 				//thiss.parentNode.previousSibling.innerHTML = interest;
			// 				window.location.reload();
			// 				//console.log(resp);	
			// 			}
			// 	});
			// }

			function Create_experiance()
			{
				$.ajax({
				url: '<?php echo site_url('admin/places_/get_placeslist');?>',		
				data: null,
				success: function(response) 
				{
					    var placename = eval('('+response+')');
					    console.log(placename);
                        var place_select_box = '<select id="place_select_box" style="width:87%;" name ="place_select_box[]"><option value="" selected>Please Select places</option>';
                                    
                        if(placename.places['0']!='no-data')
                        {                                                                       
                                for(var i=0;i<placename.places.length;i++)
                                {
                                        var Detail = placename.places[i].Detail;
                                        // console.log(placename.places[i].Detail);

                                        try{
                                        var det = JSON.parse(placename.places[i].Detail);
                                        place_select_box = place_select_box + '<option value="'+placename.places[i].PlaceIds+'">'+det.name+'</option>';
                                        }
                                        catch(e)
                                        {
                                        	console.log(e);
                                        }
                                }                                                                                                                                               
                        }

                        place_select_box = place_select_box + '</select>';
                        data_for_select = place_select_box;

						
						var user = '<div><span>User List</span><br><span><select id="user_select_box" name ="user_select_box[]" style="width:87%"><option value="" selected>Please Select user</option>';
						if(placename.user['0']!='no-data')
						{									
							for(var i=0;i<placename.user.length;i++)
							{
								user = user + '<option value="'+placename.user[i].FbUserId+'">'+placename.user[i].FbUserId+'</option>';
							}																		
						}
						user = user + '</select></span></div>';

						var link = "<?php echo site_url('admin/places_/add_places'); ?>";
						var html = "<br/><form method='POST' action='"+link+"' enctype='multipart/form-data' class='form-horizontal'><label>Tour name</label><input type='text' value='' id='tour_name' name='tour_name'/><br/><br/><label>Tour Description</label><input type='text' value='' id='tour_description' name='tour_description'/><br/><br/><label>Tags</label><input type='text' value='' id='tags_detail' name='tags_detail'/><br/><br/><label>Public_private_group</label><input class='input-xlarge' type='radio' id='public_' name='group_detail' value='1' checked>Public</input >&nbsp;&nbsp;<input class='input-xlarge' type='radio' id='private_' name='group_detail' value='2'>Private</input>&nbsp;&nbsp;<input class='input-xlarge' type='radio' id='shared_' name='group_detail' value='3' >Shared</input></br><br/><div id='data_place'><div><span>Select Places</span><br><span>"+place_select_box+"</span><span class='_button' id='add' onclick='add_place_dropdown();'>+</span></div><br/><div><span>Description</span><span><br><textarea style='width:87%;' name='description[]'></textarea></span></div></div><br/><div>"+user+"</div>";
						html = html + '<br/><br/><br/><div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">submit</button><button name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</button></div></form>';	
							var show_create_experience_box=[{
							title:"Create experience",
							html: html,
							buttons:{},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									// var tour_name = (document.getElementById('tour_name').value).replace(/^\s+|\s+$/g,'');
									// var tour_description = document.getElementById('tour_description').value;
									// var tags = document.getElementById('tags_detail').value;
									// // var group = (document.getElementById('group_detail').value).replace(/^\s+|\s+$/g,'');
									// var group = $('input[name=group_detail]:radio:checked').val();
									// // var places = (document.getElementById('places_detail').value).replace(/^\s+|\s+$/g,'');

									// var places = document.getElementById('place_select_box').value ? (document.getElementById('place_select_box').value) : '0';

									// if(tour_name=='')
									// {
									// 	alert("Tour name cannot be empty.");
									// 	document.getElementById('tour_name').focus();
									// 	e.preventDefault();
									// }else if(tour_description=='')
									// {
									// 	alert("Tour description can not be empty.");
									// 	document.getElementById('tour_description').focus();
									// 	e.preventDefault();
									// }else if(tags=="")
									// {
									// 	alert("Tags can not be empty.");
									// 	document.getElementById('tags_detail').focus();
									// 	e.preventDefault();
									// }else if(places=='')
									// {
									// 	alert("Places cannot be empty.");
									// 	document.getElementById('places_detail').focus();
									// 	e.preventDefault();
									// }else
									// {
									// 	add_places(tour_name,tour_description,tags,group,places);										
									// }			
								}
							
							}
						}];
						$.prompt(show_create_experience_box);
				}
				});	
			}

			function add_place_dropdown()
			{
				$('#data_place').append("<div class='remove_data'><br/><div><span>Select Places</span><br/><span>"+data_for_select+"</span><span class='_button' id='add' onclick='remove_place_dropdown(this);'>-</span><br/><br/><div><span>Description</span><br><span><textarea name='description[]' style='width:87%;'></textarea></span></div></div></div>");
			}

			function edit_place_dropdown()
			{
				$('#data_place_edit').append("<div class='remove_edit_data'><br/><div><span>Select Places</span><span>"+data_for_select+"</span><span class='_button' id='add' onclick='remove_edit_place_dropdown();'>-</span><br/><br/><div><span>Description</span><br><span><textarea name='description[]' style='width:87%;'></textarea></span></div></div></div>");
			}
			
			function remove_place_dropdown()
			{
				if (confirm("Please confirm to delete the field!"))
            	{
					$('.remove_data').remove();
				}
     
			}

			function remove_edit_place_dropdown()
			{
				if (confirm("Please confirm to delete the field!"))
            	{
					$('.remove_edit_data').remove();
				}
			}

			function add_places(tour_name,tour_description,tags,group,places)
			{
				
				$.ajaxFileUpload({
					url:"<?php echo site_url("admin/places_/add_places"); ?>",
					secureuri:false,
					fileElementId:'userfile',
					dataType: 'JSON',
					type: 'POST',
					data:{
							'tour_name' : tour_name ,
							'tour_description' : tour_description ,
							'tags' : tags ,
							'group' : group ,	
							'places' : places	
						 },
					success: function (resp)
							{
								// if(data=='OK')
								// {
								// 	window.location.reload();
								// }
								// else
								// {
								// 	alert("Some Error...");	
								// }
							}

					});
			
			}			
		</script>
	</head>
	<body>
		<?php echo $admin_header;?>
		<div class="container-fluid">
		  <div class="row-fluid">
		   
			<div id="content" class="span10" style="width:100%">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i> Experience</h2>
						<a class="btn btn-info" id="" style="margin-left:67%;" onClick="javascript:Create_experiance(); return false;"><i class="icon-edit icon-white"></i>Create experience</a>
						<a id="actionButton" style="float:right;" class="btn btn-info del_selected"><i class="icon-trash icon-white"></i>Delete selected item</a>                                        
					</div>
					<div class="box-content">
						
						<table class="table table-striped table-bordered bootstrap-datatable datatable" id="grid_data">
							
						</table>
						
						</div>
				  	</div>
				
		<ul id="pagination-digg"><?php if(isset($pages)) echo $pages;?></ul>	
	   </div>
    </div>
	
	<?php include 'admin-footer.php'; ?>
	
   </div>		
   </body>
   <script>

	$(document).ready(function() 
	   	{
        		var dataSource = new kendo.data.DataSource({
                
                transport: {
                    read:{
                        url: "<?php echo site_url('admin/places_/index_experience_kendo');?>",                            
                        dataType: "json",
                       
                    }
                },
                batch: true,
               	pageSize: 100,
                serverPaging: false,
                serverFiltering: true
 				//serverSorting: true
            });

        	$("#grid_data").kendoGrid({
	        	dataSource: dataSource,
	        	dataBound: function(e) {
   				$(".checkbox").bind("change", function(e) {
                            var grid = $("#grid_data").data("kendoGrid");
                            var row = $(e.target).closest("tr");
                            var data = grid.dataItem(row);
                           	});
   			  	},
	            height: 500,
	            mobile: true,
	            filterable: true,
		   		resizable: true,
	            sortable: true,
	            pageable: {
                    refresh: true,
                    pageSizes: ['all',5, 10, 20,50,100,150],
                    buttonCount: 5
                },
                editable:"inline",
	            columns:[
	            	{field:"experince_id",hidden:true},
       	            {field:"experince_id",title:"Select",width: "50px",filterable:false,template: "<input name='experince_id' class='checkbox' type='checkbox' data-bind='checked: experince_id' #= experince_id ? checked='' : '' #/>" },
       	            {field:"tour_name",title:"Tour name",filterable:false,width: "160px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                {field:"tour_description",title:"Tour description",filterable:false,width: "200px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                {field:"tags",title:"Tags",filterable:false,width: "150px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                //{field:"public_private_group",title:"Public private group",filterable:false,width: "140px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                {field:"places_exp",title:"Description",filterable:false,width: "160px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                { command: [
	                	{
	                        text: "",
	                        name: "Edit",
	                        click: prompt_edit_gallery,
	                        imageClass: "fa fa-pencil",
                        },
                        {
	                        text: "",
	                        name: "Delete",
	                        click: prompt_delete_gallery,
	                        imageClass: "fa fa-trash"
                        }], 
                        	title: "", width: "67px",headerAttributes: {style: "font-size: 14px"}
                    }
	                
	            ]
        	});

        	$("#actionButton").click(function(){
                    var idsToSend = [];
                    	
                    var grid = $("#grid_data").data("kendoGrid")
                    var ds = grid.dataSource.view();
                   
                    for (var i = 0; i < ds.length; i++) {
                        var row = grid.table.find("tr[data-uid='" + ds[i].uid + "']");
            						var checkbox = $(row).find(".checkbox");
                      
                      	if (checkbox.is(":checked")) {
                          idsToSend.push(ds[i].experince_id);
                        }
                    }
                    

                    if(idsToSend==""){
                    	alert("Please select a item!");
                    	return;
                    }

                    if(confirm("Please confirm to delete the file!"))
	                {
	                    var site="<?php echo site_url('admin/places_/deleteMultipleExperiences');?>";
	                    $.ajax({
	                            type: 'POST',
	                            url: site,
	                            data: 
	                            {
	                                values : idsToSend,
	                            },
	                            success: function(res) 
	                            {   
	                            	location.reload();
	                            },
	                            error: function(e) 
	                            {
	                                console.log(e);
	                            },
	                            complete: function(data) 
	                            {
	                                console.log(data);
	                            }
	                    });
	                }
                    
                    
                  });
                });
        
    
</script>
<style type="text/css">
            .k-grid tbody .k-button, .k-ie8 .k-grid tbody button.k-button {
    			 min-width: 33px; 
			}
		.jqi{
			    margin-left: 0px !important;
			}
		</style>
</html>

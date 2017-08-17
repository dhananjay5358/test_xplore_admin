<html>
	<head>
		<style>
			.jqimessage input{
				width: 87%;
			}
		</style>
		<title>Custom Places List</title>
		<script type="text/javascript">
			
			function prompt_delete_gallery(e)
			{
				var tr = $(e.target).closest("tr");
      		  	var data = this.dataItem(tr);
      		  	//alert(data.sr_no);return;
				var show_delete_media_box=[{
							title:"Delete Image Gallery",
							html: "<strong>are you really want to delete this Gallery ?</strong>",
							buttons:{"Delete" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									conformed_delete_gallery(data.sr_no);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);
			}
			
			function conformed_delete_gallery(id)
			{
			//	alert(id);
			//	return;
				$.ajax({
							url: '<?php echo site_url('admin/places_/add_or_remove_custom_place');?>',		
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

			function prompt_accept_gallery(e)
			{	
				var tr = $(e.target).closest("tr");
      		  	var data = this.dataItem(tr);
				var im = 'http://s3.amazonaws.com/retail-safari/'+data.image;
      		  	// console.log(data);
      		  	// return;

				//var data = $(id).closest('tr');

				var html = "<br/><label>Place name</label><input type='text' value='"+data.place_name+"' id='place_name_'/><br/><label>Address</label><input type='text' value='"+data.address+"' id='address_'/><br/><label>Description</label><textarea name='about' style='width:87%'  id='description_' >"+data.description+"</textarea><br/><label>Longitude</label><input type='text' value='"+data.longitude+"' id='longitude_'/><br/><label>Latitude</label><input type='text' value='"+data.latitude+"' id='latitude_'/><br/><label>Tag</label><input type='text' value='"+data.Category+"' id='tag'/><br/><label>Website</label><input type='text' value='"+data.web+"' id='web_'/><br/><label>Phone</label><input type='text' value='"+data.phone+"' id='phone_'/><br/><label>Image</label><input type='file' value='"+data.Image+"' name='Image_'  id='Image_'/><br/><br/><img src='"+im+"' style='width:150px;height:150px;' name='Image' id='Image_'/>";
				var show_delete_media_box=[{
							title:"Accept place",
							html: html,
							buttons:{"accept" : true , "Cancel" : false},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									accept_gallery(data.sr_no);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);
			}

			// function prompt_edit_gallery(e)
			// {
			
			// 	var tr = $(e.target).closest("tr");
   //    		  	var data = this.dataItem(tr);
			// 	var link = "<?php //echo site_url('admin/places_/edit_custom_Places'); ?>";
			// 	var im = 'http://s3.amazonaws.com/retail-safari/'+data.Image;
			// 	var html = '<form method="POST" id="contact" name="13" action="'+link+'" class="form-horizontal" enctype="multipart/form-data">';
			// 	 html = "<br/><label>Place name</label><input type='text' value='"+data.place_name+"' name='place_name' id='place_name'/><br/><br/><label>Address</label><input type='text' value='"+data.address+"' name='address' id='address'/><br/><br/><label>Description</label><input type='text' value='"+data.description+"' id='description' name='description'/><br/><br/><label>Category</label><input type='text' value='"+data.category+"' name='category' id='category'/><br/></br><label>Longitude</label><input type='text' value='"+data.longitude+"' name='longitude' id='longitude'/><br/><br/><label>Latitude</label><input type='text' value='"+data.latitude+"' name='latitude' id='latitude'/><br/><br/><label>Website</label><input type='text' value='"+data.web+"' name='web' id='web'/><br/><br/><label>Phone</label><input type='text' name='phone' value='"+data.phone+"' id='phone'/><br/><br/><label>Image</label><input type='file' value='"+data.Image+"' name='Image'  id='Image'/><br/><br/><img src='"+im+"' style='width:150px;height:150px;' name='Image' id='Image'/><br/>";
			// 	html = html + '<br/><br/><br/><div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">submit</button><button name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</button></div></form>';
				

			// 	var show_delete_media_box=[{
			// 				title:"Edit place",
			// 				html: html,
			// 				buttons:{},
			// 				submit: function(e,v,m,f){
			// 					if(v==true)
			// 					{									
			// 						//edit_gallery(id);									
			// 					}
							
			// 				}
			// 			}];
			// 	$.prompt(show_delete_media_box);
			// 	$( "#date_" ).datepicker({ dateFormat: 'yy-mm-dd' });


			// }


			function prompt_edit_gallery(e)
			{
				var tr = $(e.target).closest("tr");
      		  	var data = this.dataItem(tr);
				var link = "<?php echo site_url('admin/places_/edit_custom_Places'); ?>";
				var im = 'http://s3.amazonaws.com/retail-safari/'+data.image;
				var html = '<form method="POST" id="contact" name="13" action="'+link+'" class="form-horizontal" enctype="multipart/form-data">';
				html = html + "<input type='hidden' name='Id' value='"+data.sr_no+"'><input type='hidden' name='id_image' value='"+data.image+"'><br/><label>Place name</label><input type='text' value='"+data.place_name+"' name='place_name' id='place_name'/><br/><br/><label>Address</label><input type='text' value='"+data.address+"' name='address' id='address'/><br/><br/><label>Description</label><textarea name='description' style='width:87%'  id='description' >"+data.description+"</textarea><br/><br/><label>Longitude</label><input type='text' value='"+data.longitude+"' name='longitude' id='longitude'/><br/><br/><label>Latitude</label><input type='text' value='"+data.latitude+"' name='latitude' id='latitude'/><br/><br/><label>Tag</label><input type='text' value='"+data.Category+"' name='tag' id='tag'/></br></br><label>Website</label><input type='text' value='"+data.web+"' name='web' id='web'/><br/><br/><label>Phone</label><input type='text' name='phone' value='"+data.phone+"' id='phone'/><br/><br/><label>Image</label><input type='file' value='"+data.Image+"' name='Image_'  id='Image'/><br/><br/><img src='"+im+"' style='width:150px;height:150px;' name='Image' id='Image'/><br/>";
				html = html + '<br/><br/><br/><div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">submit</button><button name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</button></div></form>';

				 // var html = "<br/><label>Title</label><input type='text' value='"+data.Title+"' id='place_name'/><br/><label>Excerpt</label><textarea name='Excerpt_'  id='Excerpt_' >"+data.Excerpt+"</textarea><br/><label>Link</label><input type='text' value='"+data.Link+"' id='link'/><br/><label>Date</label><input type='text' value='"+data.Date+"' id='Date'/></br><label>Image</label><input type='file' name='Image_'/><br/><img src='https://s3.amazonaws.com/retail-safari/"+data.Image+"' style='width:150px;height:150px;'/></br>";
				

				var show_delete_media_box=[{
							title:"Edit place",
							html: html,
							buttons:{},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									//edit_gallery(id);									
								}
							
							}
						}];
				$.prompt(show_delete_media_box);
				$( "#date_" ).datepicker({ dateFormat: 'yy-mm-dd' });
			}


			function accept_gallery(id)
			{
			//	alert(id);return;
				var place_name = $('#place_name_').val();
				var address = $('#address_').val();
				var longitude = $('#longitude_').val();
				var latitude = $('#latitude_').val();
				var web = $('#web_').val();
				var phone = $('#phone_').val();
				var Image = $('#Image_').val();
				var description = $('#description_').val();
				var category = $('#tag').val();
//			alert(category);return;	
				$.ajax({
						url: '<?php echo site_url('admin/places_/accept_Place');?>',		
						type: 'POST',
						data: {
								'Id' :id,
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

			function edit_gallery(id)
			{
				var place_name = $('#place_name').val();
				var address = $('#address').val();
				var longitude = $('#longitude').val();
				var latitude = $('#latitude').val();
				var web = $('#web').val();
				var phone = $('#phone').val();
				var Image = $('#Image').val();
				var description = $('#description').val();
				var category = $('#category').val();
				
				$.ajax({
						url: '<?php echo site_url('admin/places_/edit_custom_Places');?>',		
						type: 'POST',
						data: {
								'Id' : id,
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
							window.location.reload();
							//console.log(resp);	
						}
				});
			}
			
						
		</script>
	</head>
	<body>
		<?php echo $admin_header;?>
		<div class="container-fluid">
		  <div class="row-fluid">
		   
		   
		   
			<div id="content" class="span10"  style="width:100%">
				<div class="box span12">
					<div class="box-header well" data-original-title>
						<h2><i class="icon-user"></i>Custom Places List</h2>
						<a id="actionButton" style="float:right;" class="btn btn-info del_selected"><i class="icon-trash icon-white"></i>Delete selected item</a>                                        
					</div>

					<div class="box-content">
						
						<table class="table table-striped table-bordered bootstrap-datatable datatable" id="custom_places">
							
						</table>
					</div>
				  	</div>
				
		<!--<ul id="pagination-digg"><?php //if(isset($pages)) echo $pages;?></ul>	-->
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
                        url: "<?php echo site_url('admin/places_/index_places_kendo');?>",                            
                        dataType: "json",
                       
                    }
                },
                batch: true,
               	pageSize: 5,
                serverPaging: false,
                //serverFiltering: true,
				schema:{
                    model:{
                    place_name:{type:"string"},
					addres:{type:"string"}
                    }
                }
               // serverSorting: true
            });

        	$("#custom_places").kendoGrid({
	        	dataSource: dataSource,
	        	dataBound: function(e) {
   				$(".checkbox").bind("change", function(e) {
                            var grid = $("#custom_places").data("kendoGrid");
                            var row = $(e.target).closest("tr");
                            var data = grid.dataItem(row);
                        });
   			  	},
	            height: 500,
		    	resizable: true,
			    sortable: true,
	            noRecords: true,
				messages: {
				    noRecords: "There is no data on current page"
				},
	            pageable: {
                    refresh: true,
                    pageSizes: ['all',5, 10, 20,50,100,150],
                    buttonCount: 5
                },
	            editable:"inline",
	               columns:[
                        {field:"sr_no",hidden:true},
                        {field:"sr_no",title:"Select",width: "65px",filterable:false,template: "<input name='sr_no' class='checkbox' type='checkbox' data-bind='checked: sr_no' #= sr_no ? checked='' : '' #/>" },
                        {field:"place_name",title:"Place Name",width: "120px",filterable: {
                        cell: {
                        operator: "contains",
                        template: function (args) {
                        args.element.css("width", "90%").addClass("k-textbox").keydown(function(e){
                        setTimeout(function(){
                            $(e.target).trigger("change");
                                });
                            });                   
                        },
                        showOperators: false
                        }
                    },attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                        {field:"address",title:"Address",filterable: {
                        cell: {
                        operator: "contains",
                        template: function (args) {
                        args.element.css("width", "90%").addClass("k-textbox").keydown(function(e){
                        setTimeout(function(){
                            $(e.target).trigger("change");
                                });
                            });                   
                        },
                        showOperators: false
                        }
                    },width: "120px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                        {field:"description",title:"Description",filterable:false,width: "120px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                        {field:"longitude",title:"Longitude",filterable:false,width: "100px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                        {field:"latitude",title:"Latitude",filterable:false,width: "100px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
			 {field:"Category",title:"Tag",filterable: {
                        cell: {
                        operator: "contains",
                        template: function (args) {
                        args.element.css("width", "90%").addClass("k-textbox").keydown(function(e){
                        setTimeout(function(){
                            $(e.target).trigger("change");
                                });
                            });                   
                        }, showOperators: false
                        }},
width: "100px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                        {field:"web",title:"Web",filterable:false,width: "100px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                        {field:"phone",title:"Phone",filterable:false,width: "100px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                       // {field:"Image",title:"Image",filterable:false,width: "120px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                       // {field:"category",title:"Category",filterable:false,width: "100px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
			{field:"image",title:"Profile Image",width: "120px",filterable:false, template: "#if(image!=null){# <img src='https://s3.amazonaws.com/retail-safari/" + "#=image#' > #}else{# No image #}#"},
                        //{field:"Image",title:"Profile Image",width: "120px",filterable:false, template: "#if(Image!=null){# <img src='https://s3.amazonaws.com/retail-safari/" + "#=Image#' > #}else{# No image #}#"}, 
						//{field:"Image",title:"Profile Image",width: "120px",filterable:false, template: "#if(Image!=null){# <img src='https://s3.amazonaws.com/retail-safari/" + "#=Image#' > #}else{# No image #}#"},  
                        { command: [{
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
                        },{
                        text: "",
                        name: "Accept",
                        click: prompt_accept_gallery,
                        imageClass: "fa fa-check",
                        }], 
                        title: "", width: "114px",headerAttributes: {style: "font-size: 14px"}
                    }
                        
                    ],
			filterable: {
                    mode: "row"
                }

        	});

			$("#actionButton").click(function(){
                    var idsToSend = [];
                    	
                    var grid = $("#custom_places").data("kendoGrid")
                    var ds = grid.dataSource.view();
                   	
                    for (var i = 0; i < ds.length; i++) {
                        var row = grid.table.find("tr[data-uid='" + ds[i].uid + "']");
            			var checkbox = $(row).find(".checkbox");
                      
                      	if (checkbox.is(":checked")) {
                          idsToSend.push(ds[i].sr_no);
                        }
                    }
                    


                    if(idsToSend==""){
                    	alert("Please select a file!");
                    	return;
                    }


                    if(confirm("Please confirm to delete the file!"))
	                {
	                    var site="<?php echo site_url('admin/places_/deleteMultipleCustomPlaces');?>";
	                    $.ajax({
	                            type: 'POST',
	                            url: site,
	                            data: {
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
    			 min-width: 10px; 
			}
		.jqi{
			    margin-left: 0px !important;
			}
			.k-filtercell{
                    width:135%;
                }
      </style>
</html>

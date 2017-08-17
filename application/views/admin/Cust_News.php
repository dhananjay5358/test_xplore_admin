<html>
	<head>
		<style>
			.jqimessage input{
				width: 87%;
			}

			#Excerpt_ {
				width:87%;
			}
		</style>
		<title>News</title>
		<script type="text/javascript">
			
			function prompt_delete_gallery(e)
			{
				var tr = $(e.target).closest("tr");
      		  	var data = this.dataItem(tr);

				var show_delete_media_box=[{
							title:"Delete Image Gallery",
							html: "<strong>are you really want to delete this news ?</strong>",
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
				$.ajax({
							url: '<?php echo site_url('admin/places_/add_or_remove_custom_news');?>',		
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

			function prompt_accept_gallery(id)
			{
				var data = $(id).closest('tr');
				var link = "<?php echo site_url('admin/places_/edit_news'); ?>";
				var html = '<form method="POST" id="contact" name="13" action="'+link+'" class="form-horizontal" enctype="multipart/form-data">';
				html = html + "<br/><label>Title</label><input type='text' value='"+data.find('.Title').html()+"' name='Title_'/><br/><label>Excerpt</label><textarea name='Excerpt_'  id='Excerpt_' >'"+data.find('.Excerpt').html()+"'</textarea><br/><label>Image</label><input type='file' name='Image_'/><br/><img src='"+data.find('.Image').attr('src')+"'/><br/><label>Link</label><input type='text' value='"+data.find('.Link').html()+"' name='Link_'/></br><label>Date</label><input type='text' value='"+data.find('.Date').html()+"' name='date_' id='date_'/>";
				html = html + '<br/><br/><br/><div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">submit</button><button name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</button></div></form>';

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
				var link = "<?php echo site_url('admin/places_/edit_news'); ?>";
				var im = 'http://s3.amazonaws.com/retail-safari/'+data.Image;
				var html = '<form method="POST" id="contact" name="13" action="'+link+'" class="form-horizontal" enctype="multipart/form-data">';
				html = html + "<br/><input type='hidden' name='id' value='"+data.sr_no+"'><input type='hidden' name='id_image' value='"+data.Image+"'><label>Title</label><input type='text' value='"+data.Title+"' name='Title_'/><br/><br/><label>Excerpt</label><textarea name='Excerpt_'  id='Excerpt_' >"+data.Excerpt+"</textarea><br/><br/><label>Image</label><input type='file' name='Image_'/><br/><br/><img src='"+im+"' style='width:150px;height:150px;'/><br/><br/><label>Link</label><input type='text' value='"+data.Link+"' name='Link_'/></br><br/><label>Date</label><input type='text' value='"+data.Date+"' name='date_' id='date_'/>";
				html = html + '<br/><br/><br/><div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">submit</button><button name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</button></div></form>';

				 // var html = "<br/><label>Title</label><input type='text' value='"+data.Title+"' id='place_name'/><br/><label>Excerpt</label><textarea name='Excerpt_'  id='Excerpt_' >"+data.Excerpt+"</textarea><br/><label>Link</label><input type='text' value='"+data.Link+"' id='link'/><br/><label>Date</label><input type='text' value='"+data.Date+"' id='Date'/></br><label>Image</label><input type='file' name='Image_'/><br/><img src='https://s3.amazonaws.com/retail-safari/"+data.Image+"' style='width:150px;height:150px;'/></br>";
				

				var show_delete_media_box=[{
							title:"Edit news",
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
						url: '<?php echo site_url('admin/places_/edit_Place');?>',		
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
							window.location.reload();
							//console.log(resp);	
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
						url: '<?php echo site_url('admin/places_/edit_Place');?>',		
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
							window.location.reload();
							//console.log(resp);	
						}
				});
			}

			function Create_News()
			{
				var link = "<?php echo site_url('admin/places_/save_news'); ?>";
				var html = '<form method="POST" id="contact" name="13" action="'+link+'" class="form-horizontal" enctype="multipart/form-data">';
				html = html + "<br/><label>Title</label><input type='text' value='' name='Title_'/><br/><label>Excerpt</label><textarea value='' name='Excerpt_'  id='Excerpt_' ></textarea><br/><label>Image</label><input type='file' name='Image_'/><br/><label>Link</label><input type='text' value='' name='Link_'/></br><label>Date</label><input type='text' value='' class='datepicker' name='date_' id='date_'/>";
				html = html + '<br/><br/><br/><div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">submit</button><button name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</button></div></form>';
				var show_delete_media_box=[{
							title:"Save news",
							html: html,
							buttons:{},
							submit: function(e,v,m,f){
								if(v==true)
								{									
									//save_news();									
								}
							}
						}];
				$.prompt(show_delete_media_box);
				$('.datepicker').datepicker({ dateFormat: 'yy-mm-dd' });

				//$( "#date_" ).datepicker({ dateFormat: 'yy-mm-dd' });
			}

			// $('#contact').submit(function(event)
			// {
			// 	// var Title_ = $("#Title_").val();
			// 	// var Excerpt_ = $("#Excerpt_").val();
			// 	// var Image_ = $("#Image_")[0].files[0];
			// 	// var Link_ = $("#Link_").val();
			// 	// var date_ = $("#date_").val();

				
			// 	var form = $('#contact').serialize();    
			// 	console.log(form);
			// 	return;           
   //      		var Form_Data = new FormData($(form)[1]);

			// 	var form_data = new FormData();                  // Creating object of FormData class
			// 	form_data.append("Title_", Title_);              // Appending parameter named file with properties of file_field to form_data
			// 	form_data.append("Excerpt_", Excerpt_);
			// 	form_data.append("Link_", Link_ );
			// 	form_data.append("date_", date_);
			// 	form_data.append("file", Image_);  
		
			// 	$.ajax({
			//             url: "<?php// echo site_url('admin/places_/save_news');?>",
  	// 					contentType: false,
			//             processData: false,
			//             data: Form_Data,                         // Setting the data attribute of ajax with file_data
			//             type: 'POST',
			// 			success: function(resp) {
			// 				console.log(resp);
			// 				//window.location.reload();
			// 			}
			//    });
			// });
			
		</script>
	</head>
	<body>
		<?php echo $admin_header;?>
		<div class="container-fluid">
		  	<div class="row-fluid">
		   	
			<div id="content" class="span10" style="width:100%">
				<div class="box span12">

					<div class="box-header well" data-original-title>
						
						<h2><i class="icon-user"></i>Custom News</h2>
						<a style="margin-left:69%;" class="btn btn-info" id="" onClick="Create_News()" ><i class="icon-edit icon-white"></i>Create news</a>
						<a id="actionButton" style="float:right;" class="btn btn-info del_selected"><i class="icon-trash icon-white"></i>Delete selected item</a>                                        
					</div>
					<div class="box-content">
						<table class="table table-striped table-bordered bootstrap-datatable datatable" id="news_kendo">
						
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
                        url: "<?php echo site_url('admin/places_/index_news_kendo');?>",                            
                        dataType: "json",
                       
                    }
                },
                batch: true,
               	pageSize: 100,
                serverPaging: false,
                serverFiltering: true
				//serverSorting: true
            });

        	$("#news_kendo").kendoGrid({
	        	dataSource: dataSource,
	        	dataBound: function(e) {
   				$(".checkbox").bind("change", function(e) {
                            var grid = $("#news_kendo").data("kendoGrid");
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
	            	{field:"sr_no",hidden:true},
	            	{field:"sr_no",title:"Select",width: "60px",filterable:false,template: "<input name='sr_no' class='checkbox' type='checkbox' data-bind='checked: sr_no' #= sr_no ? checked='' : '' #/>" },
	            	{field:"Image",title:"Image",width: "140px",filterable:false, template:"<img src='https://s3.amazonaws.com/retail-safari/" + "#=Image#' >",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                //{field:"Image",title:"Image",width: "140px",filterable:false, template:"<img src='https://s3.amazonaws.com/retail-safari/" + "#=Image#' >",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                {field:"Title",title:"Title",filterable:false,width: "160px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                {field:"Excerpt",title:"Excerpt",filterable:false,width: "200px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                {field:"Link",title:"Link",filterable:false,width: "150px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
	                {field:"Date",title:"Date",filterable:false,width: "140px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
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
                        }], 
                        title: "", width: "75px",headerAttributes: {style: "font-size: 14px"}
                    }
	                
	            ]
        	});

        	$("#actionButton").click(function(){
                    var idsToSend = [];
                    	
                    var grid = $("#news_kendo").data("kendoGrid")
                    var ds = grid.dataSource.view();
                    
                    for (var i = 0; i < ds.length; i++) {
                        var row = grid.table.find("tr[data-uid='" + ds[i].uid + "']");
            						var checkbox = $(row).find(".checkbox");
                      
                      	if (checkbox.is(":checked")) {
                          idsToSend.push(ds[i].sr_no);
                        }
                    }
                    
                    if(idsToSend==""){
                    	alert("Please select a item!");
                    	return;
                    }
                    
                    if(confirm("Please confirm to delete the file!"))
	                {
	                    var site="<?php echo site_url('admin/places_/deleteMultipleNews');?>";
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
    			 min-width: 33px; 
		}
		#ui-datepicker-div{
				margin-left: 0px!important;
				margin-top: 0px!important;
			}
		.jqi{
			    margin-left: 0px !important;
			}
</style>            
</html>

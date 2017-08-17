<html> 
    <head>
        <title>Xplore Add/Change Events</title>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
        <script src="<?php echo base_url('js/jquery-1.9.1.js');?>"></script>
	    <script src="<?php echo base_url('js/jquery-impromptu.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>

		<link href="<?php echo base_url('css/bootstrap.css');?>" media="screen" rel="stylesheet" type="text/css"/>
	    <link href="<?php echo base_url('css/jquery-impromptu.css');?>" media="screen" rel="stylesheet" type="text/css"/>
	    <link href="<?php echo base_url('css/pagination-style.css');?>" media="screen" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('css/admin.css');?>" media="screen" rel="stylesheet" type="text/css"/>


        <style type="text/css">
            ::selection{ background-color: #E13300; color: white; }
            ::moz-selection{ background-color: #E13300; color: white; }
            ::webkit-selection{ background-color: #E13300; color: white; }
            body {
                background-color: #fff;
                margin: 40px;
                font: 13px/20px normal Helvetica, Arial, sans-serif;
                color: #4F5155;
            }
            a {
                color: #003399;
                background-color: transparent;
                font-weight: normal;
            }
            h1 {
                color: #444;
                background-color: transparent;
                border-bottom: 1px solid #D0D0D0;
                font-size: 19px;
                font-weight: normal;
                margin: 0 0 14px 0;
                padding: 14px 15px 10px 15px;
            }
            code {
                font-family: Consolas, Monaco, Courier New, Courier, monospace;
                font-size: 12px;
                background-color: #f9f9f9;
                border: 1px solid #D0D0D0;
                color: #002166;
                display: block;
                margin: 14px 0 14px 0;
                padding: 12px 10px 12px 10px;
            }
            #body{
                margin: 0 15px 0 15px;
            }
            p.footer{
                text-align: right;
                font-size: 11px;
                border-top: 1px solid #D0D0D0;
                line-height: 32px;
                padding: 0 10px 0 10px;
                margin: 20px 0 0 0;
            }
            #container{
                margin: 10px;
                border: 1px solid #D0D0D0;
                -webkit-box-shadow: 0 0 8px #D0D0D0;
            }
            #body1{
                margin: 0 15px 0 15px;
            }
            #container1{
                margin: 10px;
                border: 1px solid #D0D0D0;
                -webkit-box-shadow: 0 0 8px #D0D0D0;
            }
            .tabs{
            border: 1px solid #D0D0D0;
            margin: 10px;
            -webkit-box-shadow: 0 0 8px #D0D0D0;
            font-size: 12px;
            }
            .tab2{
            border: 1px solid #D0D0D0;
            margin: 10px;
            -webkit-box-shadow: 0 0 8px #D0D0D0;
            font-size: 12px;
            }
        </style>
        
        <script type="text/javascript">
        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('starting_location'));
            google.maps.event.addListener(places, 'place_changed', function () {
                        var place = places.getPlace();
                        var address = place.formatted_address;
                        mylat= place.geometry.location.lat();
                        mylong=place.geometry.location.lng(); 
                        document.getElementById('lat').value=mylat;
        		        document.getElementById('lon').value=mylong;
                        document.getElementById('PlaceId').value= place.place_id;
 			Find(place.place_id);
        	}); 
        	     
		});
        
        function Find(PlaceId)
        	{
        	//alert(PlaceId);
        	$.ajax({
			url: "<?php echo site_url('site/Find_Events');?>",		
			type: 'POST',
			data: {
				 'placeId':PlaceId
			},
			success: function(resp) {
				//alert(JSON.stringify(resp));
				if(resp[0] != "no-data")
				{
				//alert(resp[0]);
				console.log('if');
				var abc=JSON.parse(resp);
				for(var i=0; i<abc.length; i++)
           				{
           				//alert(abc[0].Category);
           				//alert(abc[0].Detail);
           				//alert(abc[0].DateOfEstablishment);
           				//alert(typeof abc[0].Detail);
           				if(!(typeof abc[0].Detail === 'undefined'))
           				{
           				var myObj = JSON.parse(abc[0].Detail);
           				//alert(myObj.about);
           				document.getElementById('name').value=myObj.name;
         				document.getElementById('Tag').value=abc[0].Category;
         				
         				if(myObj.date != "")
         				document.getElementById('bday').value=myObj.date;
         				
         				if(myObj.startTime != "")
         				document.getElementById('s_time').value=myObj.startTime;
         				
         				if(myObj.endTime != "")
        				document.getElementById('usr_time').value=myObj.endTime;

                        if(myObj.Info_1 != "")
                        document.getElementById('usr_time').value=myObj.Info_1;

                        if(myObj.Info_2 != "")
                        document.getElementById('usr_time').value=myObj.Info_2;

                        if(myObj.Info_3 != "")
                        document.getElementById('usr_time').value=myObj.Info_3;
        				
        				document.getElementById('Description').value=myObj.about;
        				document.getElementById('Status').value="true";
        				}
           				}
				}
				else
				{
				console.log("else");
				document.getElementById('status').value="false";
				}
				//window.location.reload();						
				},
			error: function(resp) {
				console.log(resp);
				//window.location.reload();						
				}
				
		 	});
        	
        	}     
        
        function Update()
        {
         var name=document.getElementById('name').value;
         var address=document.getElementById('starting_location').value;
         var tag=document.getElementById('Tag').value;
         var edate=document.getElementById('bday').value;
         var stTime=document.getElementById('s_time').value;
         var edTime=document.getElementById('usr_time').value;
         var description=document.getElementById('Description').value;
         var lat=document.getElementById('lat').value;
         var lon=document.getElementById('lon').value;
         var placeId=document.getElementById('PlaceId').value;
         var status = document.getElementById('Status').value;
         var Info_1 = document.getElementById('Info_1').value;
         var Info_2 = document.getElementById('Info_2').value;
         var Info_3 = document.getElementById('Info_3').value;
         
         //alert(status);
		 var detail ={
        		"name":name,
        		"address":address,
        		"price" :"",
   				"discount" :"",
        		"date":edate,
        		"startTime":stTime,
        		"endTime":edTime,
   				"about":description,
    			"Reviews":"expert review",
    			"type":tag,
                "Info_1":Info_1,
                "Info_2":Info_2,
                "Info_3":Info_3
    	 };
    					
    	 var LatLng = {
    			"H":lat,
    			"L":lon
    		};

         $.ajax({
			url: '<?php echo site_url('site/SEvents');?>',		
			type: 'POST',
			data: {
				 'tag':tag,
				 'detail':JSON.stringify(detail),
				 'LatLng':JSON.stringify(LatLng),
				 'placeId':placeId,
				 'status' :status
				},
			success: function(resp) {
				if(resp == 1)
				{
				alert("Data stored on the server successfully !!");
				location.reload();
				    document.getElementById('name').value ="";
         			document.getElementById('starting_location').value ="";
         			document.getElementById('Tag').value ="";
         			document.getElementById('bday').value ="";
         			document.getElementById('s_time').value ="";
         			document.getElementById('usr_time').value ="";
         			document.getElementById('Description').value="";
         			document.getElementById('lat').value ="";
         			document.getElementById('lon').value ="";
         			document.getElementById('PlaceId').value ="";
         			document.getElementById('Status').value ="";
                    document.getElementById('Info_1').value ="";
                    document.getElementById('Info_2').value ="";
                    document.getElementById('Info_3').value ="";
				}
				else
				{
				console.log(resp);
				}
				//window.location.reload();						
				}
		 });
        }

        $(function() {
            $("#test").on("submit", function(event) {
                event.preventDefault();
                var fileToUpload = (document.getElementById('fileToUpload').value).replace(/^\s+|\s+$/g,'');
                $.ajaxFileUpload({
                    url: '<?php echo site_url('site/SEvents');?>',      
                    secureuri:false,
                    fileElementId:'fileToUpload',
                    dataType: 'JSON',
                    type: 'POST',
                    data:{ 
                        'formdata':$(this).serialize()},
                    success: function(resp) {
                        console.log(resp);
                        if(resp == 1)
                        {
                           alert("Place saved successfully.");
                        }
                        else
                        {
                            alert('Some error occoured.');
                            console.log(resp);
                        }          
                    }
                });
            });
        });
        </script>
    </head>
    <body>
        <div>
            <h1>Upload places data</h1> 
				<form id="test" name="test" class="form-panel col-sm-8 col-sm-8">
                        <div class="form-group">
                             <label for="Tag" class="col-sm-4 col-sm-4 control-label">
                                    <b>Name :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name"/>
                             </div>
                        </div>  
                        <div class="form-group">
                             <label for="Tag" class="col-sm-4 col-sm-4 control-label">
                                    <b>Tag :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Tag" id="Tag"/>
                             </div>
                        </div> 
                        <div class="form-group">
                             <label for="Tag" class="col-sm-4 col-sm-4 control-label">
                                    <b>Date :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="date" class="form-control" name="bday" id="bday"/>
                             </div>
                        </div>  
                        <div class="form-group">
                             <label for="s_time" class="col-sm-4 col-sm-4 control-label">
                                    <b>Start Time :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="time" class="form-control" name="s_time" id="s_time"/>
                             </div>
                        </div>  
                        <div class="form-group">
                             <label for="usr_time" class="col-sm-4 col-sm-4 control-label">
                                    <b>usr_time :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="time" class="form-control" name="usr_time" id="usr_time"/>
                             </div>
                        </div>  
                        <div class="form-group">
                             <label for="Description" class="col-sm-4 col-sm-4 control-label">
                                    <b>Description :</b>
                             </label>
                             <div class="col-sm-8">
                                    <textarea class="form-control" name="Description" id="Description"></textarea>
                             </div>
                        </div>  
                        <div class="form-group">
                             <label for="starting_location" class="col-sm-4 col-sm-4 control-label">
                                    <b>Address :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="starting_location" id="starting_location"/>
                             </div>
                        </div>
                        <div class="form-group">
                             <label for="fileToUpload" class="col-sm-4 col-sm-4 control-label">
                                    <b>Upload Image</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload"/>
                             </div>
                        </div>
                        <div class="form-group">
                             <label for="Info_1" class="col-sm-4 col-sm-4 control-label">
                                    <b>Info 1 :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Info_1" id="Info_1"/>
                             </div>
                        </div> 
                        <div class="form-group">
                             <label for="Info_2" class="col-sm-4 col-sm-4 control-label">
                                    <b>Info 2 :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Info_2" id="Info_2"/>
                             </div>
                        </div> 
                        <div class="form-group">
                             <label for="Info_3" class="col-sm-4 col-sm-4 control-label">
                                    <b>Info 3 :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Info_3" id="Info_3"/>
                             </div>
                        </div>
                        <div class="form-group">
                             <div class="col-sm-3">
                                    <button type="submit" class="btn btn-primary" >Submit</button>
                             </div>
                        </div>
                        
                        <input type="hidden" id="lon" name="lon">
                        <input type="hidden" id="lat" name="lat">
                        <input type="hidden" id="PlaceId" name="PlaceId">
                        <input type="hidden" id="Status" value="false" name="Status">
                </form>
    </body>
</html>
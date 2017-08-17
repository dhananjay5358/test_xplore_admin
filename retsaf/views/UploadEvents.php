<html> 
    <head>
        <title>Xplore Add/Change Events</title>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
        <script src="<?php echo base_url('js/jquery-1.9.1.js');?>"></script>
	    <script src="<?php echo base_url('js/jquery-impromptu.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('js/ajaxfileupload.js');?>"></script>
        <script type="text/javascript" src="<?php echo base_url('js/jquery-ui.js');?>"></script>
        
        <link href="<?php echo base_url('css/jquery-ui.css');?>" media="screen" rel="stylesheet" type="text/css"/>
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
        var query="";
        var ll="";
        var near_by="";
        var limit="";
        var redius="";
        var client_id="";
        var client_secret="";
         
        google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('starting_location'));
            google.maps.event.addListener(places, 'place_changed', function () {
                        var place = places.getPlace();
                        var address = place.formatted_address;
                        mylat= place.geometry.location.lat();
                        mylong=place.geometry.location.lng();
			document.getElementById("add").value=address; 
                        document.getElementById('G_lat').value=mylat;
        		        document.getElementById('G_lon').value=mylong;
                        document.getElementById('PlaceId').value= place.place_id;
                        document.getElementById("G_Lat_Long").value = mylat +" _ "+ mylong;
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
        
        // function Update()
   //      {
   //       var name=document.getElementById('name').value;
   //       var address=document.getElementById('starting_location').value;
   //       var tag=document.getElementById('Tag').value;
   //       var edate=document.getElementById('bday').value;
   //       var stTime=document.getElementById('s_time').value;
   //       var edTime=document.getElementById('usr_time').value;
   //       var description=document.getElementById('Description').value;
   //       var lat=document.getElementById('lat').value;
   //       var lon=document.getElementById('lon').value;
   //       var placeId=document.getElementById('PlaceId').value;
   //       var status = document.getElementById('Status').value;
   //       var Info_1 = document.getElementById('Info_1').value;
   //       var Info_2 = document.getElementById('Info_2').value;
   //       var Info_3 = document.getElementById('Info_3').value;
         
   //       //alert(status);
		 // var detail ={
   //      		"name":name,
   //      		"address":address,
   //      		"price" :"",
   // 				"discount" :"",
   //      		"date":edate,
   //      		"startTime":stTime,
   //      		"endTime":edTime,
   // 				"about":description,
   //  			"Reviews":"expert review",
   //  			"type":tag,
   //              "Info_1":Info_1,
   //              "Info_2":Info_2,
   //              "Info_3":Info_3
   //  	 };
    					
   //  	 var LatLng = {
   //  			"H":lat,
   //  			"L":lon
   //  		};

   //       $.ajax({
			// url: '<?php echo site_url('site/SEvents');?>',		
			// type: 'POST',
			// data: {
			// 	 'tag':tag,
			// 	 'detail':JSON.stringify(detail),
			// 	 'LatLng':JSON.stringify(LatLng),
			// 	 'placeId':placeId,
			// 	 'status' :status
			// 	},
			// success: function(resp) {
			// 	if(resp == 1)
			// 	{
			// 	alert("Data stored on the server successfully !!");
			// 	location.reload();
			// 	    document.getElementById('name').value ="";
   //       			document.getElementById('starting_location').value ="";
   //       			document.getElementById('Tag').value ="";
   //       			document.getElementById('bday').value ="";
   //       			document.getElementById('s_time').value ="";
   //       			document.getElementById('usr_time').value ="";
   //       			document.getElementById('Description').value="";
   //       			document.getElementById('lat').value ="";
   //       			document.getElementById('lon').value ="";
   //       			document.getElementById('PlaceId').value ="";
   //       			document.getElementById('Status').value ="";
   //                  document.getElementById('Info_1').value ="";
   //                  document.getElementById('Info_2').value ="";
   //                  document.getElementById('Info_3').value ="";
			// 	}
			// 	else
			// 	{
			// 	console.log(resp);
			// 	}
			// 	//window.location.reload();						
			// 	}
		 // });
   //      }

        // $(function() {
        //     $("#test").on("submit", function(event) {
        //         //event.preventDefault();
        //         return;
        //         var fileToUpload = (document.getElementById('fileToUpload').value).replace(/^\s+|\s+$/g,'');
        //         $.ajaxFileUpload({
        //             url: '<?php echo site_url('site/SEvents');?>',      
        //             secureuri:false,
        //             fileElementId:'fileToUpload',
        //             dataType: 'JSON',
        //             type: 'POST',
        //             data:{ 
        //                 'formdata':$(this).serialize()
        //             },
        //             success: function(resp) {
        //                 console.log(resp);
        //                 if(resp == 1)
        //                 {
        //                    alert("Place saved successfully.");
        //                    location.reload();       
        //                 }
        //                 else
        //                 {
        //                     alert('Some error occoured.');
        //                     console.log(resp);
        //                     location.reload();      
        //                 }          
        //             }
        //         });
        //     });
        // });
        
        
        $("document").ready(function()
        {
            $("#clear").on("click", function(event) {
            location.reload();
            });
            $( '#place_location' ).keyup(function() {
                if($(this).val() == '')
                {
                    return;
                }
                query=$(this).val();
                near_by=$("#Location").val();
                limit="10";
                redius="10,000";
                client_id="QHDBKQ2HEW2STOTBCXKTMHIVU25BKO1AIMLDYJK2KPI1YYBO";
                client_secret="JPWCA14UFZZNO2NRTKDWWPN1PX23SKMX4EY4ZGHEKSUFPMBX";
                var url="https://api.foursquare.com/v2/venues/suggestcompletion?";
                url=url+"near="+near_by;
                url=url+"&query="+query;
                url=url+"&limit="+limit;
                url=url+"&redius="+redius;
                url=url+"&client_id="+client_id;
                url=url+"&client_secret="+client_secret;
                //url=url+"&v="+newdate;
                var dateObj = new Date();

                var month="",date="";
                if(dateObj.getUTCMonth()+1 < 10)
                {
                    month="0"+dateObj.getUTCMonth()+1;
                }
                else
                {
                    month=dateObj.getUTCMonth()+1;
                }

                if(dateObj.getUTCDate() < 10)
                {
                    date=dateObj.getUTCDate();
                }
                else
                {
                    date=dateObj.getUTCDate();
                }

                newdate = dateObj.getUTCFullYear() + 1 + "" + month + "" + date;
                url=url+"&v="+newdate;
                //alert(url);
                $.get(url,function (data){
                                        console.log(data.response.minivenues);
                                        var res=data.response.minivenues;
                                        var availableTags = [];
                                        for(i=0;i<res.length;i++)
                                        {
                                            var id=""+res[i].id+"_"+res[i].location.lat+"_"+res[i].location.lng;
                                            var name='';
                                            if(!(typeof res[i].location.address == "undefined")){
                                                var name = res[i].name+" - "+res[i].location.address;
                                            }
                                            else
                                            {
                                                var name = res[i].name;
                                            }
                                            var arr={
                                                id : id,
                                                label : name
                                            };
                                            availableTags.push(arr);
                                        }
                                        //console.log(availableTags);
                                        $( "#place_location" ).autocomplete({
                                                                source: availableTags,
                                                                select: function (event, ui) {
                                                                    document.getElementById("F_Lat_Long").value="";
                                                                    var label = ui.item.label;
                                                                    var value = ui.item.id;
                                                                    var data = value.split("_");
                                                                    console.log(data);
                                                                    document.getElementById("F_lon").value = data[2];
                                                                    document.getElementById("F_lat").value = data[1];
                                                                    document.getElementById("VenueId").value = data[0];
                                                                    document.getElementById("F_Lat_Long").value = data[1] +" _ "+ data[2];
                                                                    //alert(data);
                                                                    fetch(data[0]);
                                                                }
                                        });
                });
            })            

            function fetch(value)
            {

                client_id="QHDBKQ2HEW2STOTBCXKTMHIVU25BKO1AIMLDYJK2KPI1YYBO";
                client_secret="JPWCA14UFZZNO2NRTKDWWPN1PX23SKMX4EY4ZGHEKSUFPMBX";
                var url="https://api.foursquare.com/v2/venues/"+value;
                url=url+"?client_id="+client_id;
                url=url+"&client_secret="+client_secret;

                //https://api.foursquare.com/v2/venues/
                //4fd489a4e4b02bbdc60a5b34
                //?client_id=QHDBKQ2HEW2STOTBCXKTMHIVU25BKO1AIMLDYJK2KPI1YYBO&client_secret=JPWCA14UFZZNO2NRTKDWWPN1PX23SKMX4EY4ZGHEKSUFPMBX&v=20160413
                //url=url+"&v="+newdate;
                var dateObj = new Date();

                var month="",date="";
                if((parseInt(dateObj.getUTCMonth())+1) < 10)
                {
                    month="0"+(parseInt(dateObj.getUTCMonth())+1);
                }
                else
                {
                    month=(dateObj.getUTCMonth()+1);
                }

                if((parseInt(dateObj.getUTCDate())) < 10)
                {
                    date=parseInt(dateObj.getUTCDate());
                }
                else
                {
                    date=parseInt(dateObj.getUTCDate());
                }

                newdate = dateObj.getUTCFullYear() + "" + month + "" + date;
                //alert(newdate);

                url=url+"&v="+newdate;

                $.get(url,function (data){
                                        console.log(data);
                                        var venue= data.response.venue;
                                        var phone ="";
                                        var time = "";
                                        var Url = "";

                                        if(!(typeof venue.contact.phone == "undefined"))
                                        {
                                            phone = venue.contact.phone;
                                        }

                                        if(!(typeof venue.hours.timeframes == "undefined"))
                                        {
                                            time = JSON.stringify(venue.hours.timeframes);
                                        }

                                        if(!(typeof venue.page.pageInfo.links == "undefined"))
                                        {
                                            if(!(typeof venue.page.pageInfo.links.items == "undefined"))
                                            {
                                                var Url=venue.page.pageInfo.links.items[0].url;
                                                // var link=venue.page.pageInfo.links;
                                                // for($i=0;$i<item;$i++)
                                                // {
                                                //     if(!(typeof link[i] == "undefined"))
                                                //     {
                                                //         Url=item[i].items[0].url;
                                                //         break;
                                                //     }
                                                // }
                                            }
                                        }
                                        //console.log(time);
                                        document.getElementById('s_time').value=time;
                                        //console.log(phone);
                                        document.getElementById('Phone').value=phone;
                                        //console.log(Url);
                                        document.getElementById('Website').value=Url;

                });
            }
            //navigator.geolocation.getCurrentPosition(success, fail , { enableHighAccuracy: true });
            //function success(position){
                //mylat = position.coords.latitude;
                //mylong = position.coords.longitude;
                //myLatLng = new google.maps.LatLng(mylat,mylong);
            //}
            //foresquare
            // https://api.foursquare.com/v2/venues/search?ll=40.7,-74&client_id=QHDBKQ2HEW2STOTBCXKTMHIVU25BKO1AIMLDYJK2KPI1YYBO&client_secret=JPWCA14UFZZNO2NRTKDWWPN1PX23SKMX4EY4ZGHEKSUFPMBX&v=20160407
            // https://api.foursquare.com/v2/venues/suggestcompletion?ll=44.3,37.2&near=Chicago,IL&query=court&limit=30&redius=150&client_id=QHDBKQ2HEW2STOTBCXKTMHIVU25BKO1AIMLDYJK2KPI1YYBO&client_secret=JPWCA14UFZZNO2NRTKDWWPN1PX23SKMX4EY4ZGHEKSUFPMBX&v=20160407
        });
        </script>
    </head>
    <body>
        <div>
            <h1>Upload places data ::</h1> 
				<form id="test" name="test" action="<?php echo site_url('site/SEvents'); ?>" method="POST" class="form-panel col-sm-10 col-sm-10" enctype="multipart/form-data">
                        <div class="form-group">
                             <label for="Location" class="col-sm-4 col-sm-4 control-label">
                                    <b>Location :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Location" id="Location" placeholder="Enter place or location name." required />
                             </div>
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group">
                             <label for="Tag" class="col-sm-4 col-sm-4 control-label">
                                    <b>Foursquare Suggestions :</b>
                             </label>
                             <div class="col-sm-4">
                                    <input type="text" class="form-control" name="place_location" id="place_location" placeholder="Enter a location." required />
                             </div>
                             <div class="col-sm-4">
                                    <input type="text" class="form-control" name="F_Lat_Long" id="F_Lat_Long" />
                             </div>
                        </div> 
                        <br/>
                        <br/>
                        <div class="form-group">
                             <label for="starting_location" class="col-sm-4 col-sm-4 control-label">
                                    <b>Google Places Address :</b>
                             </label>
                             <div class="col-sm-4">
                                    <input type="text" class="form-control" name="starting_location" id="starting_location" required />
                             </div>
                             <div class="col-sm-4">
                                    <input type="text" class="form-control" name="G_Lat_Long" id="G_Lat_Long" />
                             </div>
                        </div> 
                        <br/>
                        <br/>               
                        <div class="form-group">
                             <label for="name" class="col-sm-4 col-sm-4 control-label">
                                    <b>Name :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="name" id="name" required />
                             </div>
                        </div> 
                        <br/>
                        <br/>   
                        <div class="form-group">
                             <label for="Tag" class="col-sm-4 col-sm-4 control-label">
                                    <b>Tag :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Tag" id="Tag" required />
                             </div>
                        </div>
                        <br/>
                        <br/>                          
                        <div class="form-group">
                             <label for="Description" class="col-sm-4 col-sm-4 control-label">
                                    <b>Description :</b>
                             </label>
                             <div class="col-sm-8">
                                    <textarea class="form-control" name="Description" id="Description" required ></textarea>
                             </div>
                        </div> 
                        <div class="form-group" style="display:none;">
                             <label for="Tag" class="col-sm-4 col-sm-4 control-label">
                                    <b>Date :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="hidden" class="form-control" name="bday" id="bday" />
                             </div>
                        </div> 
                        <div class="form-group" style="display:none;">
                             <label for="s_time" class="col-sm-4 col-sm-4 control-label">
                                    <b>Start Time :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="hidden" class="form-control" name="s_time" id="s_time"/>
                             </div>
                        </div> 
                        <div class="form-group" style="display:none;">
                             <label for="usr_time" class="col-sm-4 col-sm-4 control-label">
                                    <b>usr_time :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="hidden" class="form-control" name="usr_time" id="usr_time"/>
                             </div>
                        </div> 
                        <br/> 
                        <br/>
                        <br/>                    
                        <div class="form-group">
                             <label for="fileToUpload" class="col-sm-4 col-sm-4 control-label">
                                    <b>Upload Image :<br/> (Optimal size of image is 600px X 600px)</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="file" class="form-control" name="fileToUpload" id="fileToUpload" />
                             </div>
                        </div>
                        <div class="form-group" style="display:none;">
                             <label for="Info_1" class="col-sm-4 col-sm-4 control-label">
                                    <b>Info 1 :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="hidden" class="form-control" name="Info_1" id="Info_1" />
                             </div>
                        </div> 
                        <div class="form-group" style="display:none;">
                             <label for="Info_2" class="col-sm-4 col-sm-4 control-label">
                                    <b>Info 2 :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="hidden" class="form-control" name="Info_2" id="Info_2" />
                             </div>
                        </div>
                        <div class="form-group" style="display:none;">
                             <label for="Info_3" class="col-sm-4 col-sm-4 control-label">
                                    <b>Info 3 :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="hidden" class="form-control" name="Info_3" id="Info_3" />
                             </div>
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group" >
                             <div class="col-sm-1">
                                    <input class="btn btn-primary" type="submit" value="Submit"/>
                             </div>
                              <div class="col-sm-1">
                                    
                             </div>
                              <div class="col-sm-1">
                                    <input type="button" class="btn btn-primary" value="Clear" id="clear" />
                             </div>
                        </div>
                        
                        <input type="hidden" id="G_lon" name="G_lon">
                        <input type="hidden" id="G_lat" name="G_lat">
                        <input type="hidden" id="F_lon" name="F_lon">
                        <input type="hidden" id="F_lat" name="F_lat">
                        <input type="hidden" id="VenueId" name="VenueId">
                        <input type="hidden" id="PlaceId" name="PlaceId">
                        <input type="hidden" id="Status" value="false" name="Status">
			<input type="hidden" id="add" value="" name="add">
                        <input type="hidden" id="Phone" name="Phone">
                        <input type="hidden" id="Website" name="Website">
                </form>

    </body>
</html>

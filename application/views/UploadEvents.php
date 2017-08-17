<?php 
    if($this->session->userdata('Upload_status')) 
    {
        if($this->session->userdata('Upload_status') == 'flase') 
        {
            echo '<script language="javascript">';
            //echo 'alert("Save Failed.")';
            echo '</script>';
            $this->session->unset_userdata('Upload_status');
        }
        else
        {
            echo '<script language="javascript">';
           //echo 'alert("Place Successfully Saved.")';
            echo '</script>';
            $this->session->unset_userdata('Upload_status');
        }
    }
?>

<html> 
    <head>
        <title>Production Xplore - Places</title>
        <script src="<?php echo base_url('js/jquery-1.9.1.js');?>"></script>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCgFaImUo6xWBC9qizp25fe9IWxDjFVHdM&libraries=places" />
	    <script src="<?php echo base_url('js/jquery-impromptu.js');?>"></script>
        <!-- <script type="text/javascript" src="<?php //echo base_url('js/ajaxfileupload.js');?>"></script> -->
        <script type="text/javascript" src="//cdn.ckeditor.com/4.4.0/standard/ckeditor.js"></script>
        <script type="text/javascript" src="<?php echo base_url('js/jquery-ui.js');?>"></script>
        
       
        <link href="<?php echo base_url('css/jquery-ui.css');?>" media="screen" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url('css/bootstrap.css');?>" media="screen" rel="stylesheet" type="text/css"/>
	    <link href="<?php echo base_url('css/jquery-impromptu.css');?>" media="screen" rel="stylesheet" type="text/css"/>
	    <link href="<?php echo base_url('css/pagination-style.css');?>" media="screen" rel="stylesheet" type="text/css"/>
        <link href="<?php echo base_url('css/admin.css');?>" media="screen" rel="stylesheet" type="text/css"/>

        <script type="text/javascript" src="<?php echo base_url('js/ckeditor.js');?>"></script>
        
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
                        console.log(place);
                        var address = place.formatted_address;
                        mylat= place.geometry.location.lat();
                        mylong=place.geometry.location.lng();
			            //document.getElementById("add").value=address; 
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
				    var abc=JSON.parse(resp);
                  
				    for(var i=0; i<abc.length; i++)
           		    {
           				if(!(typeof abc[0].Detail === 'undefined'))
           				{
                            document.getElementById('Status').value="true";
               				var obj = JSON.stringify(abc[0].Detail);
                            var myObj = JSON.parse(obj);
               				document.getElementById('name').value=myObj.name;
             				document.getElementById('Tag').value=abc[0].Category;
             				
                            if(abc[0].type == "Deals" || abc[0].type == "Events")
                            {
                                return;
                            }

                            if(abc[0].Foresquare_lat_lng != null)
                            {
                                var Foresquare_lat_lng_obj = JSON.parse(abc[0].Foresquare_lat_lng);
                                document.getElementById('F_Lat_Long').value = Foresquare_lat_lng_obj.H +" - "+ Foresquare_lat_lng_obj.L;
                                document.getElementById('F_lat').value = Foresquare_lat_lng_obj.H;
                                document.getElementById('F_lon').value = Foresquare_lat_lng_obj.L;
                            } 

                            if(abc[0].Venueid != null)
                            {
                                document.getElementById('VenueId').value = abc[0].Venueid;
                            }
                            
                            if(myObj.name != "")
                            document.getElementById('place_location').value=myObj.name;

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
            				
                            var base64Matcher = new RegExp("^(?:[A-Za-z0-9+/]{4})*(?:[A-Za-z0-9+/]{2}==|[A-Za-z0-9+/]{3}=|[A-Za-z0-9+/]{4})$");
                            if (base64Matcher.test(myObj.about)) {
                                document.getElementById('Description').value=atob(myObj.about);
                            } else {
                                document.getElementById('Description').value=myObj.about;
                            }
            				
            				document.getElementById('Status').value="true";

                            if(! (typeof myObj.Path == "undefined"))
                            {
                                var abc='';
                                var img=myObj.Path.split(",");
                                count=1;
                                for(var j=0;j<img.length;j++)
                                {
                                    if(img[j] != "" && img[j] != null)
                                    {
                                        abc=abc+'<span class="checkbox" > <span style="float:left;"><input type="checkbox" name="Path_image[]" value="'+img[j]+'" checked><a href="https://s3.amazonaws.com/retail-safari/'+img[j]+'" target="_blank">Image '+count+'</a></span><span class="checkbox" style="margin-left: 77px;"> <span><input type="radio" name="Profile" value="'+img[j]+'"></span></span></span>';
                                        count++;
                                    }
                                }
                                document.getElementById('Image_List').innerHTML=abc;
                            }

                            if(! (typeof myObj.profileimage == "undefined"))
                            {
                                if(myObj.profileimage != "" && myObj.profileimage != null)
                                {
                                    var abc='';
                                    var img=myObj.profileimage;
                                    abc=abc+'<span class="checkbox" > <span style="float:left;"><a href="https://s3.amazonaws.com/retail-safari/'+img+'" target="_blank">Image</a></span><span class="checkbox" style="margin-left: 77px;"> <span><input type="radio" name="Profile" value="'+img+'" checked></span></span></span>';
                                    document.getElementById('ProImage_List').innerHTML=abc;
                                }
                            }
        				}
           			}
				}
				else
				{
				document.getElementById('status').value="false";
				}					
				},
			error: function(resp) {
				console.log(resp);					
				}
				
		 	});
        	
        }   
        
        $("document").ready(function()
        {
            $("#clear").on("click", function(event) 
            {
                location.reload();
            });

            $('#place_location').keyup(function() {
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
                $.get(url,function (data)
                {
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
                    $( "#place_location" ).autocomplete({
                            source: availableTags,
                            select: function (event, ui) {
                                document.getElementById("F_Lat_Long").value="";
                                var label = ui.item.label;
                                var value = ui.item.id;
                                var data = value.split("_");
                                document.getElementById("F_lon").value = data[2];
                                document.getElementById("F_lat").value = data[1];
                                document.getElementById("VenueId").value = data[0];
                                document.getElementById("F_Lat_Long").value = data[1] +" _ "+ data[2];
                                //alert(data);
                                fetch(data[0]);
                                myLatLng = new google.maps.LatLng(F_lat,F_lon);
                                var geocoder = new google.maps.Geocoder();
                                 
                                geocoder.geocode({ 'latLng': myLatLng}, function (results, status)
                                                  {
                                                  
                                                  if (status == google.maps.GeocoderStatus.OK)
                                                  {
                                                  $('#add').val(results[0].formatted_address);
                                                  }
                                                  });

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

                $.get(url,function (data)
                {
                    var venue= data.response.venue;
                    console.log(venue);
                    var phone ="";
                    var time = "";
                    var Url = "";
                    var likes = "";

                    if(!(typeof venue.contact.phone == "undefined"))
                    {
                        phone = venue.contact.phone;
                    }

                    if(!(typeof venue.hours == "undefined"))
                    {
                        time = JSON.stringify(venue.hours.timeframes);
                    }

                    if(!(typeof venue.likes == "undefined"))
                    {
                        like = venue.likes.count;
                    }

                    if(!(typeof venue.page == "undefined"))
                    {
                        if(!(typeof venue.page.pageInfo == "undefined"))
                        {
                            if(!(typeof venue.page.pageInfo.links == "undefined"))
                            {
                             var Url=venue.page.pageInfo.links.items[0].url;
                            }
                        }
                    }

                    document.getElementById('s_time').value=time.replace(/"/g, "'");
                    document.getElementById('Phone').value=phone;
                    document.getElementById('Like').value=like;
                    document.getElementById('Website').value=Url;
                });
            }
        });
        </script>
    </head>
    <body>
        <div>
            <h1>Upload places data :<br/><a class="ajax-link" href="<?php echo site_url('site/viewPlaces');?>">HOME</a></h1>
				<form id="test" name="test" action="<?php echo site_url('site/SEvents'); ?>" method="POST" class="form-panel col-sm-10 col-sm-10" enctype="multipart/form-data">
                        <div class="form-group">
                             <label for="Location" class="col-sm-4 col-sm-4 control-label">
                                    <b>Location :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="Location" id="Location" placeholder="Enter place or location name." required value="Chicago"/>
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
                             <label for="PlaceIdentifier" class="col-sm-4 col-sm-4 control-label">
                                    <b>PlaceIdentifier :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="PlaceIdentifier" id="PlaceIdentifier" />
                             </div>
                        </div>
                        <br/>
                        <br/>   
                        <div class="form-group">
                             <label for="add" class="col-sm-4 col-sm-4 control-label">
                                    <b>Address :</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="text" class="form-control" name="add" id="add" required />
                             </div>
                        </div>
                        <br/>
                        <br/>
                        <div class="form-group">        
                            <label for="Phone" class="col-sm-4 col-sm-4 control-label">       
                                    <b>Phone :</b>        
                            </label>        
                            <div class="col-sm-8">      
                                    <input type="text" class="form-control" name="Phone" id="Phone"/>       
                            </div>      
                        </div>   
                        <br/>
                        <br/>
                        <div class="form-group">        
                            <label for="like" class="col-sm-4 col-sm-4 control-label">       
                                    <b>Like :</b>        
                            </label>        
                            <div class="col-sm-8">      
                                    <input type="text" class="form-control" name="Like" id="Like"/>       
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
                        <div class="col-sm-12 form-group">                  
                        
                             <label for="fileToUpload" class="col-sm-4 col-sm-4 control-label">
                                    <b>Upload Image :<br/> (Optimal size of image is 600px X 600px)</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="file"  name="fileToUpload[]" id="fileToUpload" multiple/>
                             </div>
                        
                        </div>
                        <br/> 
                        <br/>
                        <br/>   
                        <div class="col-sm-12 form-group">                         
                        
                             <label for="fileToUpload" class="col-sm-4 col-sm-4 control-label">
                                    <b>Upload Profile Image :<br/> (Optimal size of image is 600px X 600px)</b>
                             </label>
                             <div class="col-sm-8">
                                    <input type="file"  name="ProfileToUpload" id="ProfileToUpload" />
                             </div>
                        
                        </div>
                        <br/>
                        <br/>
                        <br/> 
                        <div class="form-group col-sm-12">
                            <label for="Image_List" class="col-sm-4 col-sm-4 control-label">
                                    <b>Uploaded Profile Image:</b>
                            </label>
                            <div class="col-sm-8" id="ProImage_List">
                                    
                            </div>
                        </div>
                        <br/> 
                        <br/>
                        <br/> 
                        <div class="form-group col-sm-12">
                            <label for="Image_List" class="col-sm-4 col-sm-4 control-label">
                                    <b>Uploaded Image List:<br/>(You can also select the radio button to set that image as profile)</b>
                            </label>
                            <div class="col-sm-8" id="Image_List">
                                    
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
                        
                        <input type="hidden" id="G_lon" name="G_lon" />
                        <input type="hidden" id="G_lat" name="G_lat" />
                        <input type="hidden" id="F_lon" name="F_lon" />
                        <input type="hidden" id="F_lat" name="F_lat" />
                        <!-- <input type="hidden" name="Path_image[]" value=""/> -->
                        <input type="hidden" id="VenueId" name="VenueId" />
                        <input type="hidden" id="PlaceId" name="PlaceId" />
                        <input type="hidden" id="Status" value="false" name="Status" />
                        <!-- <input type="hidden" id="Phone" name="Phone" /> -->
                        <input type="hidden" id="Website" name="Website" />
                        <input type="hidden" id="Discount" name="Discount" />
                        <input type="hidden" id="Price" name="Price" />
                        <input type="hidden" id="type" name="type" value="Place" />
                </form>
                <script type="text/javascript">
                    CKEDITOR.replace('Description');
                </script>
    </body>
</html>

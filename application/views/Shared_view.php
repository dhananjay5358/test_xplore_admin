<html>
	<head>
		<title>Xplore Add/Change Events</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link href="<?php echo base_url('css/mob/bootstrap.css');?>" rel="stylesheet">
		<link href="<?php echo base_url('css/mob/index.css');?>" rel="stylesheet"> 
		<script src="<?php echo base_url('js/mob/jquery-1.11.3.js');?>"></script>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD_39SzGyyLbs4qSatHaoesAhxAnLjDG-U&sensor=true&libraries=places" />
		<script src="<?php echo base_url('js/mob/mapwithmarker.js');?>"></script>
		<script src="<?php echo base_url('js/mob/jquery.mobile-1.4.5.min.js');?>"></script>
		<script src="<?php echo base_url('js/mob/bootstrap.min.js');?>"></script>
		
		<script type="text/javascript">
			function Open()
			{
				$TourId=document.getElementById("Tour_Id").value;		
				window.open("R_Safari://?T_id="+ $TourId);
			}

//show the tour on the map with all the places start here 
			
function help(Page)
{
    var page_ = {
        "Get_current" : "Get_current",
        "Map" : "Map",
        "Category" : "Category",
        "Listing" : "Listing",
        "Detail" : "Detail",
        "Tour" : "Tour",
        "save" : "save",
        "Map" : "Map",
        "Mytrips" : "Mytrips",
        "Settings" : "Settings"
    };
    alert(page_[Page]);
}			
$( document ).ready(function() {

 		var mylat  = 41.8818; //Chicago
               var mylong = -87.6633;  //Chicago
               var myLatLng = new google.maps.LatLng(mylat, mylong);
               var mapOptions = {
               zoom: 17,
               center: myLatLng,
               mapTypeId: google.maps.MapTypeId.ROADMAP
               }
               var map = new google.maps.Map(document.getElementById("Tour_map_canvas"), mapOptions);


    $TourId=document.getElementById("Tour_Id").value;
    Load_Trips($TourId,map);
});			
//Load saved trips in itinarary view starts here
function Load_Trips(srno,map)
{
    if(srno == "")
    {
        alert("Please Login First");
        return;
    }
    var url = 'https://wms-dev.com/xplore/index.php?/site/getDetailTour?';
    $.ajax({
           type: 'GET',
           url: url,
           contentType: "application/json",
           dataType: 'jsonp',
           jsonp: 'callback',
           data: {
           srno : srno
           },
           beforeSend : function(){
           //$("#visited_site_list").html('');
           //$("#loader_image").css("display","block");
           },
           crossDomain: true,
           
           success: function(res) {
           //alert("success" + JSON.stringify(res));
           
           if(res[0]!= "no-data")
           {
           document.getElementById('Itinerary').innerHTML='';
           var myObj = JSON.parse(res[0].Detail);		
           var str_array = myObj.Places_List_all.split(',');
           //return;
           var abca='';
            var Tour_description = myObj.Description;
           var Tour_name = res[0].TourName;
           
           document.getElementById('Itinerary').innerHTML = '<div class="col-sm-12 col-xs-12 enterMsg center Tname P_details Ita_n">'+Tour_name+'</div><div class="col-sm-12 col-xs-12 enterMsg center P_details Ita_n">'+Tour_description+'</div>';

           var service = new google.maps.places.PlacesService(map);
           for(var i=0; i<str_array.length; i++)
           {           
            service.getDetails({
                              placeId:str_array[i]
                              },
                              function(place, status) {
                              
                              if (status === google.maps.places.PlacesServiceStatus.OK)
                              {
                              var struct='';
                              if(!(typeof place.photos === "undefined"))
                              {
                              if(!(typeof place.photos[0] === "undefined"))
                              {
                              struct='<div class="col-sm-12 col-xs-12 paddingzero"><input type="hidden" id="Lat_'+place.place_id+'"  value="'+place.geometry.location.lat()+'"/><input type="hidden" id="Log_'+place.place_id+'" value="'+place.geometry.location.lng()+'"/><div class="col-sm-4 col-xs-4 paddingright"><img class="img-circle itineraryImg" src="'+place.photos[0].getUrl({'maxWidth': 500, 'maxHeight': 500})+'" /></div><div class="col-sm-8 col-xs-8 paddingzero"><ul class="yetToVisit"><li class="title hotelName" id="Place_name'+place.place_id+'"> '+place.name+' </li> <li class="title itinerarydes" id="Add_'+place.place_id+'"> '+place.vicinity+'</li><li class="title itinerarydes"></li></ul></div></div>';
                              }
                              }
                              else
                              {
                              struct='<div class="col-sm-12 col-xs-12 paddingzero"><input type="hidden" id="Lat_'+place.place_id+'"  value="'+place.geometry.location.lat()+'"/><input type="hidden" id="Log_'+place.place_id+'" value="'+place.geometry.location.lng()+'"/><div class="col-sm-4 col-xs-4 paddingright"><img class="img-circle itineraryImg" src="'+place.icon+'" /></div><div class="col-sm-8 col-xs-8 paddingzero"><ul class="yetToVisit"><li class="title hotelName" id="Place_name'+place.place_id+'"> '+place.name+' </li> <li class="title itinerarydes" id="Add_'+place.place_id+'"> '+place.vicinity+'</li><li class="title itinerarydes"> </li></ul></div></div>';
                              }
                              
                              
                              document.getElementById('Itinerary').innerHTML =  document.getElementById('Itinerary').innerHTML + '<div class="col-sm-12 col-xs-12 paddingtb borderbottom ui-state-default ALL_Places_Tour YetToVisitedNode" id="'+place.place_id+'" onclick="Detail(this,true)">' + struct + '</div>';
                              struct='';
                              
                              //code to add colore difference code starts here
                               col_Diff();                          
                              //code to add colore difference ends here
                              
                            }
                              else
                              {
                                alert(status);
                              }
                              });

           }
           $('#itinerary_Ui').show();
           $('#TourMapCanvas').hide();
           $('#ListDetail').hide();
           $('#Tour_Idq1').hide();
           $('.ui-loader').hide(); 
           }
           else
           {
           alert("No such data stored on the server.");
           return;
           }
           },
           error: function(e) {
           console.log(e.message);
           },
           complete: function(data) {
           //alert("complete");
           //console.log(data.message);
           }
           });
}
//load trips in itinerary view ends here	
function GoToItinerary()
{
$('#Tour_Idq1').hide();
$('.ui-loader').hide(); // hides
$('#itinerary_Ui').show();
$('#TourMapCanvas').hide();
$('#ListDetail').hide();
}


function Tour_Map_Canvas()
{
$('.ui-loader').hide(); 
$('#Tour_Idq1').hide();
$('#Tour_Idq').hide();
$('.ui-loader').hide(); // hides
$('#itinerary_Ui').hide();
$('#TourMapCanvas').show();
$('#ListDetail').hide();
var useragent = navigator.userAgent;
//alert(useragent);
var mapdiv = document.getElementById("Tour_map_canvas");
var mapitem = document.getElementById("Tour_map_item");

if ((useragent.indexOf('Android 3.') != -1) && (screen.width >= 800) && (screen.height >= 800)) {
    // galaxy tab
    mapdiv.style.height = '450px';
    //mapdiv.style.margin = '0.8em';
} else if ((useragent.indexOf('Android 2.') != -1 ) || (useragent.indexOf('Android 3.') != -1 )) {
    mapitem.style.maxWidth = '490px';
    mapdiv.style.height = '270px';
    //mapdiv.style.margin = '0.4em';
} else {
    mapdiv.style.height = '420px';
    //mapdiv.style.margin = '1em';
}
var mylat  = 41.8818; //Chicago
               var mylong = -87.6633;  //Chicago
               var myLatLng = new google.maps.LatLng(mylat, mylong);
var mapOptions = {
zoom: 15,
center: myLatLng,
mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("Tour_map_canvas"), mapOptions);

var infowindow = new google.maps.InfoWindow();
var no ='';
var name_p = [];
var lat_P = [];
var long_P = [];
var bounds = new google.maps.LatLngBounds();
$(".YetToVisitedNode").each(function() {
                            var ID_Place=$(this).attr('id');
                            var index = ID_Place.indexOf('_');
                            ID_Place= ID_Place.substr(index+1);
                            var name = document.getElementById('Place_name'+ID_Place).innerHTML;
                            var latitude_p = document.getElementById('Lat_'+ID_Place).value;
                            var Longitude_P = document.getElementById('Log_'+ID_Place).value;
                            name_p.push(name)
                            lat_P.push(latitude_p)
                            long_P.push(Longitude_P)
                            });

for(var j=0; j<name_p.length;j++)
{
    var position = new google.maps.LatLng(lat_P[j], long_P[j]);
    bounds.extend(position);
    marker = new google.maps.Marker({
                                    position: position,
                                    map: map
                                    });
    
    google.maps.event.addListener(marker, 'click', (function(marker, j) {
                                                    return function() {
                                                    infowindow.setContent((j+1)+' '+name_p[j]);
                                                    infowindow.open(map, marker);
                                                    }
                                                    })(marker, j));
}
map.fitBounds(bounds);
}


//Function to add colore difference code starts here
function col_Diff()
{
    var i=0;
    $(".YetToVisitedNode").each(function() {
		if(i%2 == 0 && !$(this).hasClass('evenItinerary'))
		{
    		if($(this).hasClass('oddItinerary'))
    		{
     			$(this).removeClass('oddItinerary');
    		}
    		$(this).addClass('evenItinerary');
		}
    	else if(i%2 != 0 && !$(this).hasClass('oddItinerary'))
    	{
    		if($(this).hasClass('evenItinerary'))
    		{
     			$(this).removeClass('evenItinerary');
    		}
    		$(this).addClass('oddItinerary');
    	}
		i++;
	});
}
//Function to add colore difference code starts here
		
		//function shows detil informtion about selected place also it contains structure of the details/profile sceen.
function Detail(tthis,flag){
    
   //alert($(tthis).parent().attr('id'));
    var Server_detail =[];
   var PlaceId_Detail='';
    if(flag == true)
   {
       PlaceId_Detail = tthis.id;
   }
   else if(flag == false)
   {
       var temp_val = $(tthis).parent().attr('id');
       var index_temp = temp_val.indexOf('_');
       PlaceId_Detail = temp_val.substr(index_temp+1);
   }
   else
   {
       PlaceId_Detail=tthis.id;
       Server_detail=flag;
       flag = true;
   }
    //alert(PlaceId_Detail);
    //return;
    var mylat  = 41.8818; //Chicago
               var mylong = -87.6633;  //Chicago
               var myLatLng = new google.maps.LatLng(mylat, mylong);
var mapOptions = {
zoom: 15,
center: myLatLng,
mapTypeId: google.maps.MapTypeId.ROADMAP
}

var map = new google.maps.Map(document.getElementById("Tour_map_canvas"), mapOptions);
    service = new google.maps.places.PlacesService(map);
    var text_img='';
    var diff;
    var no=-1;
    service.getDetails({
                       placeId:PlaceId_Detail
                       }, function(place, status) {
                       if (status === google.maps.places.PlacesServiceStatus.OK) {
                       
                       var d = new Date();
                       
                     
					  
                       var day='';
                       if(!(typeof place.opening_hours === "undefined"))
					   {
                       switch(d.getDay())
                       {
                       case 0:
                       day=place.opening_hours.weekday_text[6];
                       break;
                       case 1:
                       day=place.opening_hours.weekday_text[0];
                       break;
                       case 2:
                       day=place.opening_hours.weekday_text[1];
                       break;
                       case 3:
                       day=place.opening_hours.weekday_text[2];
                       break;
                       case 4:
                       day=place.opening_hours.weekday_text[3];
                       break;
                       case 5:
                       day=place.opening_hours.weekday_text[4];
                       break;
                       case 6:
                       day=place.opening_hours.weekday_text[5];
                       break;
                       }
                       
                       if(day.indexOf('Closed') == -1)
                       {
                       var index = day.indexOf(':');
                       var index_second;
                       var text = day.substr(index + 1);
                       
                       var count = (text.match(/,/g) || []).length;
     
						//alert(count);
						if(count>=1)
						{
                       index = text.indexOf(',');
                       index_second = text.lastIndexOf(',');
                       
                       day = text.substr(0,index);
                       text = text.substr(index+1);
                       
                       index = day.indexOf('–');
                       day=day.substr(0,index)
                       
                       index_second = text.indexOf('–');
                       text=text.substr(index_second+1);
                       
                       
                       day = ''+day+' To '+text;
                       }
                       else if(count == 0)
                       {
                       day = ''+text;
                       }
                       //day = ''+day+' To '+text;
                       
                       }
                       else
                       {
                       day = "Today closed.";
                       }
					   
					   }
					   
					   var length =  place.types.length;
					   var types = place.types[0];
					   
					   for (var l=1;l<length;l++)
					   {
					   if(place.types[l] != 'point_of_interest' && place.types[l] != 'establishment')
					   types = types +', '+ place.types[l];
					   }
                       detail_Id = place.place_id;
					   var Distance_Info = '';
					   
					   if(!(typeof document.getElementById("Distance"+place.place_id+"") === "undefined") && document.getElementById("Distance"+place.place_id+"") != null)
					   {
					   Distance_Info=document.getElementById("Distance"+place.place_id+"").innerHTML;
					   }
					   
					   var price='';
                       var abc='';
    					
                        abc=abc+ '<div class="ListdetailView">';
                         
						// Slider code start here					
						abc=abc+ '<div id="myCarousel" class="carousel slide" data-ride="carousel">';	
						abc=abc+'<div class="carousel-inner" role="listbox">';
						if(!(typeof place.photos === "undefined"))
						{
						if(!(typeof place.photos[1] === "undefined"))
						{
						 abc=abc+'<div class="item active">';					
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"><img class="desimage img-responsive" src="'+place.photos[1].getUrl({'maxWidth': 500, 'maxHeight': 500})+'" /></div>';
                         abc=abc+ '</div>';
						 }
						 if(!(typeof place.photos[2] === "undefined"))
						 {
						 abc=abc+'<div class="item">';
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"><img class="desimage img-responsive" src="'+place.photos[2].getUrl({'maxWidth': 500, 'maxHeight': 500})+'" /></div>';
                         abc=abc+ '</div>';
						 }
						 if(!(typeof place.photos[3] === "undefined"))
						 {
						 abc=abc+'<div class="item">';
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"><img class="desimage img-responsive" src="'+place.photos[3].getUrl({'maxWidth': 500, 'maxHeight': 500})+'" /></div>';
						 abc=abc+ '</div>';
						 }
						}
						else
						{
						 	abc=abc+'<div class="item active">';					
						    abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"><img class="desimage img-responsive" src="'+place.icon+'" /></div>';
                            abc=abc+ '</div>';
						}
						abc=abc+ '</div>';
					    
						// Left controls
						abc=abc+'<a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">';
						abc=abc+'<span class="fa fa-angle-left" aria-hidden="true"></span>';
						abc=abc+'<span class="sr-only">Previous</span>';
						abc=abc+'</a>';
						// right controls
						abc=abc+'<a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">';
						abc=abc+'<span class="fa fa-angle-right" aria-hidden="true"></span>';
						abc=abc+'<span class="sr-only">Next</span>';
						abc=abc+'</a>';
							
						abc=abc+ '</div>';
						// Slider code End here
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero">';
                         abc=abc+ '<div class="col-sm-5 col-xs-5 paddingzero detailplaceName"> '+place.name+'</div>';
						 if(!(typeof place.opening_hours === "undefined"))
						{
							abc=abc+ '<div class="col-sm-7 col-xs-7 paddingzero hourTime">';
							if(Server_detail.length > 0)
							{
								abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero restaurant">'+Server_detail[1]+'</div>';
							}
							else
							{
								abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero restaurant"></div>';
							}
							abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero openClose">Hours Today : '+day+'</div>';
							abc=abc+'</div>';
						}
						 abc=abc+ '</div>';
						 abc=abc+ '<div class="listImage ListdetailHeader col-sm-12 col-xs-12 paddingzero">';
                         abc=abc+ '<div class="container-fluid paddingzero HeaderDetails">';
               
                       
                       //abc=abc+ '<div class="title hotelName col-sm-6 col-xs-6 paddingzero">'+place.name+' </div>';
						 
						 
						if(!(typeof place.opening_hours === "undefined"))
						{
							//abc=abc+ '<div class="col-sm-6 col-xs-6 paddingzero hourTime"> Hours Today : '+day+'</div>';
						}
						abc=abc+ '</div>'; 
                        abc=abc+ '</div>';

							
					     abc=abc+ '<div class="site-desc paddingzero container-fluid">';
						 
						 //Code for tabs start here
						abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						abc=abc+ '<ul class="nav nav-pills">';
						abc=abc+ '<li class="active tableft"><a data-toggle="pill" href="#Summary">Summary</a></li>';
						abc=abc+ '<li><a data-toggle="pill" href="#Contact">Information</a></li>';
						abc=abc+ '</ul>';
						
						abc=abc+ '<div class="tab-content">';
						abc=abc+ '<div id="Summary" class="tab-pane fade in active">';
                       
                       if(Server_detail.length > 0)
                       {
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"> '+ Server_detail[0] +'</div>';
                       }
                       else
                       {
                         abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"> Sorry, place details are not available. </div>';
                       }
                       
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero"> <img src="<?php echo base_url('images/mob/busnessType.png');?>"/></div>';
						 abc=abc+ '<div class="col-sm-10 col-xs-10 paddingzero"> '+types+' </div>';
						 abc=abc+ '<div class="clear"></div>';
						 abc=abc+ '</div>';
						 
						abc=abc+ '</div>';
						abc=abc+ '<div id="Contact" class="tab-pane fade">';
						abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 if(!(typeof place.international_phone_number === "undefined"))
						 {
						 abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero"><a href="tel:'+place.international_phone_number+'"><img src="<?php echo base_url('images/mob/call.png');?>" /></a></div>';
                       	 abc=abc+ '<div class="col-sm-10 col-xs-10 paddingzero"><a href="tel:'+place.international_phone_number+'">'+place.international_phone_number+'</a></div>';
						 }
						 else
						 {
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"><a></a></div>';
						 }
						 
						 abc=abc+ '</div>';
                       
						 
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero"> <img src="<?php echo base_url('images/mob/address.png');?>" /></div>';
						 abc=abc+ '<div class="col-sm-10 col-xs-10 paddingzero"> '+place.vicinity+' </div>';
						 abc=abc+ '</div>';
                        abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 if(!(typeof place.website === "undefined"))
						 {
                        abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero "><img src="<?php echo base_url('images/mob/website.png');?>" /></div>';
                        abc=abc+ '<div class="col-sm-10 col-xs-10 paddingzero"><a href="'+place.website+'" target="_blank">'+place.website+'</a></div>';
						 }
						 else
						 {
                        abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"><a ></a> </div>';
						 }
						 abc=abc+ '</div>';
						 abc=abc+ '</div>';
						 
						
						abc=abc+ '</div>';
						abc=abc+ '</div>';
						//code for tabs end here
						 
						 abc=abc+ '</div>';
						 abc=abc+ '</div>';
                       document.getElementById("selected_site_Detail").innerHTML= abc;
            
                       $('#ListDetail').show();
           			   $('#TourMapCanvas').hide();
                       $('#itinerary_Ui').hide();
					   
					    if(flag == true)
   						{
       					 $("#DetailBackButton").attr("onclick","goToNearByPlace()");
   						}
   						else
   						{
       					 $("#DetailBackButton").attr("onclick","GoToItinerary()");
   						}
                       }
                       else
                       { 
                       alert(status);
                       }
                       });
}
//function shows detil informtion about selected place also it contains structure of the details/profile sceen end here.

		
		
		</script>
    	</head>
	<body>		
		<div class="container-fluid">		 
		<input type="hidden" id="Tour_Idq" value="<?php echo $hello;?>"/>
                <button id="Tour_Idq1" onclick="javascript:Open(); return false;" style="width:200px;">Submit</button>
	   	</div>	
	   
		<div data-role="page" id="itinerary_Ui">
    		<div data-role="header" class="homeHeader mapcanvasHeader navbar-fixed-top">
				<div class="col-sm-12 col-xs-12 paddingzero">
					<div class="col-sm-4 col-xs-4 paddingzero">
						<div class="backButton"></div>
					</div>
					<div class="col-sm-4 col-xs-4 paddingzero">
                		<div class="help center">
                    		<a href="#" onClick="help('Tour');"><img src="<?php echo base_url('images/mob/info.png');?>"></a>
                		</div>
            		</div>
					<div class="col-sm-4 col-xs-4 paddingzero">
						<input type="hidden" id="Tour_Id" value="<?php echo $hello;?>"/>
					</div>
					<div class="clear"></div>
				</div>	
    		</div>
    		
    		<div class="container-fluid paddingzero freezTopSpace" id="uio">
        		<div class="col-sm-12 col-xs-12 paddingzero" >
            		<div class="col-sm-12 col-xs-12 reset_tab reset_tab_" onclick="Tour_Map_Canvas()">Click to See Map</div>
        		</div>
				<div id="Itinerary" class="single_library_container"></div>
			</div>
    		<div data-role="footer container-fluid" class="footer">
				<div class="col-sm-12 col-xs-12 itinerarymainfooter paddingzero">
					<div class="col-sm-12 col-xs-12 paddingzero" >
            			<div class="col-sm-12 col-xs-12 reset_tab reset_tab_" onclick="Open()">Click To View In App</div>
        			</div>
				</div>
			</div>	
		</div>
		
		<div data-role="page" id="ListDetail">
    		<div data-role="header" class="detailsHeader navbar-fixed-top">
				<div class="col-sm-12 col-xs-12">
					<div class="backButton col-sm-3 col-xs-3 paddingzero">
						 <a href="#" onclick="GoToItinerary()"><img src="<?php echo base_url('images/mob/back.png');?>"><span class="back">Back</span></a>
					</div>
        			<div class="col-sm-6 col-xs-6 paddingzero center">
            			<div class="help">
            	    		<a href="#" onClick="help('Detail');"><img src="<?php echo base_url('images/mob/info.png');?>"></a>
           	 			</div>
        			</div>
					<div class="backButton col-sm-3 col-xs-3 paddingzero"></div>
				</div>	
    		</div>
    		<div class="listDetailsView">
        		<div id="selected_site_Detail"></div>
    		</div>
    		<div class="clear"></div>
		</div>
		
		
		
		<div data-role="page" id="TourMapCanvas">
    		<div data-role="header" class="homeHeader mapcanvasHeader">
        		<div class="backButton">
           			 <a href="#" onclick="GoToItinerary()"><img src="<?php echo base_url('images/mob/back.png');?>"><span class="back">Back</span></a>
        		</div>
        		<div class="nextButton"></div>
        		<div class="clear"></div>
    		</div>
    		<div class="content mapCanvasFull" id="Tour_map_item">
        		<div id="Tour_map_canvas"></div>
    		</div>
    		<div class="clear"></div>
		</div>
		
		
	</body>
</html>
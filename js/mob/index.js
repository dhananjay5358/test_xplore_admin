
//globel veriables
var map;
var infowindow;
var service;
var myLatLng;
var mylat ='';
var mylong ='';
var Places_Result = [];
var service_d;
var place_id_list=[];
var destination_list = [];
var types_prop = [];
var Category_prop=[];
var Itineraries_list = [];
var Itineraries_Ad = [];
var curent_location='';
var Tour_List = []; 
var bar= '';
var Destination_addr='';
var selected_places = [];
var site_url = 'http://wms-dev.com/xplore/index.php?/';
var FbUserId_Log='';
var detail_Id='';
var T_Flag="false"; //where T stands for trip.
var T_Profile = "";
var T_name = "";
var T_SrNo = "";
var T_des =  "";
var res_list_google = {};
var res_list_server= {};
var res_list_direction= {};
var Flag_For_Distance = "";
var category_In_Grid = "";
var arr=[];
var categories={};
var HasNextPage="false";
var pagination_={};
var interval=0;
var Save_id="0";
var process="";
//var tour_Places = [];
//globel variable

categories = {
    "Hotandnew" : "Hot and New",
    "fastcasual" : "Fast Casual",
    "ChicagoStaples" : "Chicago Staples",
    "Diner" : "Diner",
    "ChicagoPizza" : "Chicago Pizza",
    "UpscaleTraditional" : "Upscale Traditional",
    "chic" : "Chic",
    "cafe" : "Cafe",
    "rooftops" : "Rooftops",
    "mixology" : "Mixology",
    "wine" : "Wine",
    "whiskey" : "Whiskey",
    "distilleries" : "Distilleries",
    "Breweries" : "Breweries",
    "craftbeers" : "Craft Beers",
    "clubs" : "Clubs",
    "mingling" : "Mingling",
    "lounge" : "Lounge",
    "seasonal" : "Seasonal",
    "artsy" : "Artsy",
    "parks" : "Parks",
    "indie" : "Indie",
    "theatre" : "Theatre",
    "outdoors" : "Outdoors",
    "concerts" : "Concerts",
    "jazz" : "Jazz",
    "smallvenue" :"Small Venue"
};

function handleOpenURL(url) {
    // This function is triggered by Plugin
    setTimeout(function() {
               process="load_tour";
               var url_ = "" + url;
               var index=url_.indexOf('=');
               //alert(index+1);
               FbUserId_Log="_";
               url_ = url_.slice(index+1);
               //alert(url_);
               mylat  = 41.8818; //Chicago
               mylong = -87.6633;  //Chicago
               myLatLng = new google.maps.LatLng(mylat, mylong);
               var mapOptions = {
               zoom: 17,
               center: myLatLng,
               mapTypeId: google.maps.MapTypeId.ROADMAP
               }
               map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
               $('#homepage').hide();
               GoToItinerary();
               FbLogin(url_);
               }, 400);
}

var app = {
    initialize: function() {
        this.bindEvents();
    },
    bindEvents: function() {
        document.addEventListener('deviceready', this.onDeviceReady, false);
    },
    onDeviceReady: function() {
    app.receivedEvent('deviceready');
    },
    receivedEvent: function(id) {
                
    }
    
};

google.maps.event.addDomListener(window, 'load', function () {
            var places = new google.maps.places.Autocomplete(document.getElementById('starting_location'));
            google.maps.event.addListener(places, 'place_changed', function () {
                        var place = places.getPlace();
                        var address = place.formatted_address;
                        mylat= place.geometry.location.lat();
                        mylong=place.geometry.location.lng();
                                                               //google.maps.event.trigger(map, 'resize');                

			MapProp();

			$('#homepage').hide();
			$('#mapCanvas').show();
			if(mylat != '' && mylong !='')
			{
                myLatLng = new google.maps.LatLng(mylat, mylong);
                var mapOptions = {
                    zoom: 17,
                    center: myLatLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
                
                var marker = new google.maps.Marker({
                                                    position: myLatLng,
                                                    map: map
                                                    });   
                                                                               
			}
        });
        	var new_place = new google.maps.places.Autocomplete(document.getElementById('ADD_New'));
            google.maps.event.addListener(new_place, 'place_changed', function () {
                    var place = new_place.getPlace();
                    if (!place.geometry) {
      					return;
    				}
                     var address = place.formatted_address;
                     service = new google.maps.places.PlacesService(map);
    				 service.getDetails({
                        placeId:place.place_id
                       }, function(place, status) {
                       if (status === google.maps.places.PlacesServiceStatus.OK) {
                                        
                          var current = new google.maps.LatLng(mylat,mylong);
                          var destination = new google.maps.LatLng(place.geometry.location.lat(),place.geometry.location.lng());
                              service_d.getDistanceMatrix({
                                                            origins: [current], //LatLng Array
                                                            destinations: [destination], //LatLng Array
                                                            travelMode: google.maps.TravelMode.DRIVING,
                                                            unitSystem: google.maps.UnitSystem.IMPERIAL,
                                                            avoidHighways: false,
                                                            avoidTolls: false
                                                            }, callback_Dis);
                                        
                              function callback_Dis(response, status)
                              {
                                if (status === google.maps.DistanceMatrixStatus.OK)
                                {
                                        
                                        var results_ele = response.rows[0].elements;
                                        
                                        if(!(typeof results_ele[0] === "undefined"))
                                        {
                                                element = results_ele[0];
                                            if(!(typeof element.distance === "undefined"))
                                            {
                                                var distance = element.distance.text;
                                                add_itinerary(place,distance,'Itinerary');
                                            }
                                            else
                                            {
                                                var distance = "";
                                                add_itinerary(place,distance,'Itinerary');
                                            }
                                        }
                                        else
                                        {
                                            var distance="";
                                            add_itinerary(place,distance,'Itinerary');
                                        }

                                }
                                else
                                {
                                    var distance="";
                                    add_itinerary(place,distance,'Itinerary');
                                }
                              }
                       }
                       });
                    
        	});                     
});
// auto correct script start here

function add_itinerary(place,Dis,Sort)
{
    
    var addr=$("#ADD_New").val();
    
    var p_address=place.vicinity;
    var struct='';
    //alert(place.place_id);
    if(!(typeof place.photos === "undefined"))
    {
        if(!(typeof place.photos[0] === "undefined"))
        {
            struct='<div class="col-sm-12 col-xs-12 paddingzero"><input type="hidden" id="Lat_'+place.place_id+'"  value="'+place.geometry.location.lat()+'"/><input type="hidden" id="Log_'+place.place_id+'" value="'+place.geometry.location.lng()+'"/><div class="col-sm-4 col-xs-4 paddingright"><img class="img-circle itineraryImg" src="'+place.photos[0].getUrl({'maxWidth': 500, 'maxHeight': 500})+'" /></div><div class="col-sm-8 col-xs-8 paddingzero"><ul class="yetToVisit"><li class="title hotelName" id="Place_name'+place.place_id+'"> '+place.name+' </li> <li class="title itinerarydes" id="Add_'+place.place_id+'"> '+addr+'</li><li class="title itinerarydes">'+Dis+'</li></ul></div></div>';
        }
    }
    else
    {
        struct='<div class="col-sm-12 col-xs-12 paddingzero"><input type="hidden" id="Lat_'+place.place_id+'"  value="'+place.geometry.location.lat()+'"/><input type="hidden" id="Log_'+place.place_id+'" value="'+place.geometry.location.lng()+'"/><div class="col-sm-4 col-xs-4 paddingright"><img class="img-circle itineraryImg" src="'+place.icon+'" /></div><div class="col-sm-8 col-xs-8 paddingzero"><ul class="yetToVisit"><li class="title hotelName" id="Place_name'+place.place_id+'"> '+place.name+' </li> <li class="title itinerarydes" id="Add_'+place.place_id+'"> '+addr+'</li><li class="title itinerarydes">'+Dis+'</li></ul></div></div>';
    }
    
    var struct1='<div class="col-sm-12 col-xs-12 paddingtb borderbottom ui-state-default ALL_Places_Tour YetToVisitedNode" id="Itinerari_'+place.place_id+'"><div class="col-sm-9 col-xs-9 paddingzero" onclick="Detail(this,false)">' + struct + '</div> <div onClick="delete_it(this)" class="col-sm-1 col-xs-1 paddingzero Del" style="display:none"><img class="img-responsive" src="images/trash.png" /></div><div class="col-sm-2 col-xs-2 img- responsive paddingzero handle Drag" style="display:none"><span title="Re-arrange itinerary by dragging and dropping sites"></span></div><div class="markasvisited vis col-sm-3 col-xs-3 paddingleft" ><div class="markAsVisit">Mark visited</div><input onclick="visitsite(this)" class="visitedcheck" type="checkbox" name="visited"></div></div>';
    
    
   
    document.getElementById('Itinerary').innerHTML =  document.getElementById('Itinerary').innerHTML + struct1;
  
    
    Itineraries_list.push(place.place_id);
    Itineraries_Ad.push(addr);
    Tour_List.push('1');
    $("#ADD_New").val("");
   
    //function used to add colore difference code
    col_Diff();
    
    //code to hide edit mode if it is open.
    Editor();
    
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

						i=1;

						$(".visitedNode").each(function() {
	
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
						//Adding colore difference code ends here
                       }
//Function to add colore difference code starts here
                    
//mapProp is used to set property of the map on the screen
function MapProp(){
    
var useragent = navigator.userAgent;
				//alert(useragent);
				var mapdiv = document.getElementById("map_canvas");
				var mapitem = document.getElementById("map_item");
			
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
}
//mapProp is used to set property of the map on the screen

// show map function start here
  function showMap()
  {
  	MapProp();
  	$('#homepage').hide();
  	$('#mapCanvas').show();
  
  	navigator.geolocation.getCurrentPosition(success, fail , { enableHighAccuracy: true });
  	function success(position){
                mylat = position.coords.latitude;
                mylong = position.coords.longitude;
                myLatLng = new google.maps.LatLng(mylat,mylong);
                var mapOptions = {
                    zoom: 17,
                    center: myLatLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        
                
                var marker = new google.maps.Marker({
                                                    position: myLatLng,
                                                    map: map
                                                    });
                                                    
                                                    
            }
            
  	function fail(position){
                swal(" Current location is not found !!! Showing the default location.");
                mylat  = 41.8818; //Chicago
                mylong = -87.6633;  //Chicago
                myLatLng = new google.maps.LatLng(mylat, mylong);
                var mapOptions = {
                    zoom: 17,
                    center: myLatLng,
                    mapTypeId: google.maps.MapTypeId.ROADMAP
                }
                map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
                var marker = new google.maps.Marker({
                                                    position: myLatLng,
                                                    map: map
                                                    });
                                                
            }

  }
//show map function end here


// Back Button on google map page 
 function goToBack(){
     //location.reload();
     Itineraries_list=[];
     Itineraries_Ad=[];
     Tour_List=[];
     Category_prop=[];
     types_prop=[];
     res_list_google = {};
     res_list_server= {};
     res_list_direction= {};
     document.getElementById('Itinerary').innerHTML="";
     document.getElementById("MyTripList").innerHTML="";
     detail_Id = '';
     Save_id="0";
     process="";
  $('#homepage').show();
  $('#mapCanvas').hide();
  $('#nearByPlace').hide();
  $('#CatagoryPage').hide();
  $('#Detail').hide();
  $('#NotificationUi').hide();
  $("#starting_location").val("");
  $('#itinerary_Ui').hide();
  $('#ListDetail').hide();
  $('#Save_Ui').hide();
  $('#MyTrips').hide();
     $('#BuildSearchUi').hide();
 }
 
 function goToBackCat()
 {
     res_list_google = {};
     res_list_server= {};
     res_list_direction= {};
     Category_prop=[];
     types_prop=[];
     Save_id="0";
     process="";
  $('#NotificationUi').hide();
  $('#itinerary_Ui').hide();
  //$('#homepage').show();
  $('#mapCanvas').hide();
  $('#nearByPlace').hide();
  $('#CatagoryPage').show();
  $('#Detail').hide();
  $('#ListDetail').hide();
  $('#Save_Ui').hide();
  $('#MyTrips').hide();
     $('#BuildSearchUi').hide();
  document.getElementById('Itinerary').innerHTML = "";
  document.getElementById("MyTripList").innerHTML="";
  detail_Id = '';
  //$("#starting_location").val("");
 }

// Next Button on google map page 
 function goTonext()
 {
 // $('#nearByPlace').show();
  $('#CatagoryPage').show();
  $('#mapCanvas').hide();
 }
 function goTonextcat()
 {
  $('#nearByPlace').show();
  $('#CatagoryPage').hide();
 // $('#mapCanvas').hide();
 }


function Reset()
{
    $('.check').each(function() {
                        if ($(this).is(':checked'))
                        {
                            $(this).prop('checked',false);
                            if($(this).parent().parent().hasClass('active'))
                            {
                                $(this).parent().parent().removeClass('active');
                            }
                            else
                            {
                                $(this).parent().parent().parent().removeClass('active');
                            }
                        }
                     });
}

 //Function shows list of result that that has been requested by user. List contains different types of result depends upon the selected catagory this function also contains list structure.
function Places(){
    interval=0;
    HasNextPage="false";
 types_prop = [];
 Category_prop=[]
$('.check').each(function() {
    if ($(this).is(':checked')) {
        //$(this).prop('checked',false);
                     Category_prop.push(this.value);
        }
});
    if(Category_prop.indexOf("Hotandnew") != -1 || Category_prop.indexOf("fastcasual") != -1 || Category_prop.indexOf("ChicagoStaples") != -1 || Category_prop.indexOf("Diner") != -1 || Category_prop.indexOf("ChicagoPizza") != -1 || Category_prop.indexOf("UpscaleTraditional") != -1 || Category_prop.indexOf("chic") != -1 )
        {
        types_prop.push("restaurant");
        }
    if(Category_prop.indexOf("cafe") != -1)
    {
        types_prop.push("cafe");
    }
    if(Category_prop.indexOf("rooftops") != -1 || Category_prop.indexOf("mixology") != -1 || Category_prop.indexOf("wine") != -1 || Category_prop.indexOf("whiskey") != -1 || Category_prop.indexOf("distilleries") != -1 || Category_prop.indexOf("Breweries") != -1 || Category_prop.indexOf("craftbeers") != -1 || Category_prop.indexOf("clubs") != -1 || Category_prop.indexOf("mingling") != -1 || Category_prop.indexOf("lounge") != -1)
    {
        types_prop.push("bar");
        types_prop.push("night_club");
    }

if(types_prop.length == 0 && Category_prop.length ==0)
{
    swal("Please select any Catagory");
    document.getElementById("selected_site_list").innerHTML = "";
    goToBackCat();
    return;
}
	var radius_s = Math.round((document.getElementById('ex1').value) * 1609.34);
	var miles = parseFloat(document.getElementById('ex1').value);
	myLatLng = new google.maps.LatLng(mylat, mylong);
	console.log(types_prop);
    
	var request = {
    				location: myLatLng,
    				radius: radius_s,
    				types: types_prop
    			  };
    			  
	service = new google.maps.places.PlacesService(map);
	service_d = new google.maps.DistanceMatrixService();
    
    if(types_prop.length > 0)
    {
    console.log("nearby");
	service.nearbySearch(request, function callback(results, status, pagination) {
                         
 				if (status == google.maps.places.PlacesServiceStatus.OK)
    			{
                place_id_list=[];
                destination_list = [];
                res_list_google = results;
                         
                for (var i = 0; i < results.length; i++)
        		{
                    destination_list.push(new google.maps.LatLng(results[i].geometry.location.lat(), results[i].geometry.location.lng()));
                }
				
                pagination_ = pagination;
                var current = new google.maps.LatLng(mylat,mylong);
                         
    			service_d.getDistanceMatrix(
                                {
                                origins: [current], //LatLng Array
                                destinations: destination_list, //LatLng Array
                                travelMode: google.maps.TravelMode.DRIVING,
                                unitSystem: google.maps.UnitSystem.IMPERIAL,
                                avoidHighways: false,
                           	    avoidTolls: false
                                }, callback_Dis);

    			function callback_Dis(response, status) {
       				 if (status === google.maps.DistanceMatrixStatus.OK) {
                        curent_location = response.originAddresses;
                         console.log(response);
                        res_list_direction = response;
            	  		list(response,results,true);
    				}
    				else{
                        curent_location = response.origin;
    					list(response,results,false);
    				}
                }
    			} 
	});
    }
    else
    {
        list(res_list_direction,res_list_google,false);
    }
}
//places function ends here.

function list(response,results,flag)
{
    
    Flag_For_Distance=flag;
				
    var url = site_url+'site/getSelectedPlace?';
    if(Category_prop.length > 0)
    {
        console.log("Catagory" + Category_prop);
        $.ajax({
               type: 'GET',
               url: url,
               contentType: "application/json",
               dataType: 'jsonp',
               jsonp: 'callback',
               data: {
               Category : Category_prop.toString()
               },
               beforeSend : function(){
               //$("#loader_image").css("display","block");
               },
               crossDomain: true,
               success: function(res) {
               if(res['0']!= "no-data")
               {
               res_list_server=res;
               List_Load();
               }
               else
               {
               res_list_server=res;
               List_Load();
               }
               },
               error: function(e) {
               console.log(e.message);
               },
               complete: function(data) {
               
               }
               });
    }
    else
    {
        List_Load();
    }
}

function List_Load()
{
    var gridList='';
    var results_ele=[];
    console.log(typeof res_list_direction.rows);
    if(!(typeof res_list_direction.rows === "undefined"))
    {
        results_ele = res_list_direction.rows[0].elements;
    }
    var results = res_list_google;
    var servar_res = res_list_server;
    var flag=Flag_For_Distance;
    var miles = parseFloat(document.getElementById('ex1').value);
    var ser_flag="false";
    
    for (var i = 0; i < results.length; i++)
    {
        console.log("in result");
        for (var j = 0; j < servar_res.length; j++)
        {
            ser_flag="false";
            //alert(servar_res[j].PlaceIds)
            if (servar_res[j].PlaceIds == results[i].place_id) {
                //servar_res.splice(j, 1);
                ser_flag="true";
                break;
            }
        }
        if(ser_flag == "true")
        {
        ser_flag="false";
        continue;
        }
        var element 	='';
        var distance 	='';
        var duration 	='';
        
        if(!(typeof results_ele[i] === "undefined") && flag != false)
        {
            element = results_ele[i];
            if(!(typeof element.distance === "undefined"))
            {
                distance = element.distance.text;
                var D_str = distance.indexOf(' ');
                var str_d = distance.substr(0,D_str);
                var mil = parseFloat(str_d);
                
                if(mil > miles)
                {
                    continue;
                }
            }
            if(!(typeof element.duration === "undefined"))
            {
                duration = element.duration.text;
            }
        }
        
        var abc ='';
        
        gridList = gridList + '<div class="single_library_container col-sm-12 col-xs-12 '+results[i].place_id+'" id="List_'+ results[i].place_id +'"><input type="hidden" name="Place_Id" value="'+results[i].place_id+'"/><input type="hidden" id="Lat_'+results[i].place_id+'"  value="'+results[i].geometry.location.lat()+'"/><input type="hidden" id="Log_'+results[i].place_id+'" value="'+results[i].geometry.location.lng()+'"/>';
        
        gridList = gridList + '<div class="listDescription col-sm-11 col-xs-11 paddingzero" id="'+ results[i].place_id +'" onClick="javascript:Detail(this,true); return false;"><a id="selectedSiteListImage_'+results[i].id+'">';
		
        if(!(typeof results[i].photos === "undefined"))
        {
            gridList = gridList + '<div class="listImage col-sm-6 col-xs-6 paddingzero"><img class="firstimage" id="Place_Image'+results[i].place_id+'" src="'+results[i].photos[0].getUrl({'maxWidth': 180, 'maxHeight': 160})+'" /></div>';
        }
        else
        {
            gridList = gridList + '<div class="listImage col-sm-6 col-xs-6 paddingzero"><img class="firstimage" id="Place_Image'+results[i].place_id+'" src="'+results[i].icon+'" /></div>';
        }
        
        gridList = gridList + '<div class="listDescription col-sm-6 col-xs-6 paddingzero"><ul class="site-desc">';
        
        gridList = gridList + '<li class="title hotelName" id="Place_name'+results[i].place_id+'"> '+results[i].name+' - ';
        
        gridList = gridList + '<span id="Distance'+results[i].place_id+'">'+distance+'</span></li>';
        
        if(!(typeof results[i].rating === "undefined"))
        {
        gridList = gridList+ '<li class="title hotelRating"><div class="rating"></div><div class="mainStars"><span class="stars">'+results[i].rating+'</span></div><div class="reviews" id="Rev'+results[i].place_id+'">';
        }
        else
        {
            gridList = gridList + '<li class="title hotelRating"><div class="reviews" id="Rev'+results[i].place_id+'">';
        }
        
        if(!(typeof results[i].price_level === "undefined"))
        {
            for(var j=0;j<parseInt(results[i].price_level);j++)
            {
                abc = abc + '$';
            }
            gridList = gridList + '<div class="title hotelPrice"> </div>';
        }
        else
        {
            gridList = gridList + '<div class="title hotelPrice"> </div>';
        }
        
        gridList = gridList + '</div></li>';
        
        gridList = gridList + '<li class="title hotelAdd" id="Add_'+results[i].place_id+'"> '+results[i].vicinity+'</li>';
        
        if(!(typeof results[i].opening_hours === "undefined"))
        {
            if(results[i].opening_hours.open_now)
            {
                gridList = gridList + '<li class="title hotelstatus"></li>';
            }
            else
            {
                gridList = gridList + '<li class="title hotelstatus"></li>';
            }
        }
        
        gridList = gridList +	'</ul></div></a></div>';
        
        gridList = gridList + '<div class="listImage col-sm-1 col-xs-1 paddingzero" id="'+i+'" onClick="javascript:itineraries(this); return false;"><div class="readMore"><button type="button" class="btn btn-primary btn-xs" >+</button></div></div></div>';
    }
    
    var Tabs_='';
    Tabs_ = Tabs_ + '<div class="mainCatagory List_cat container-fluid paddingzero">';
    Tabs_ = Tabs_ + '<div class="panel-group" id="accordion1">';
    Tabs_ = Tabs_ + '<div class="panel panel-default">';
    Tabs_ = Tabs_ + '<div class="panel-heading eatPanel">';
    Tabs_ = Tabs_ + '<div class="col-sm-12 col-xs-12 paddingzero paddingCattb">';
    Tabs_ = Tabs_ + '<h4 class="panel-title">';
    Tabs_ = Tabs_ + '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion1" href="#Google_result">';
    Tabs_ = Tabs_ + '<div class="col-sm-3 col-xs-3 catpadingicon"> </div>';
    Tabs_ = Tabs_ + '<div class="col-sm-7 col-xs-7 paddingzero heading">';
    Tabs_ = Tabs_ + 'Click to see more';
    Tabs_ = Tabs_ + '</div>';
    Tabs_ = Tabs_ + '</a>';
    Tabs_ = Tabs_ + '</h4>';
    Tabs_ = Tabs_ + '</div>';
    Tabs_ = Tabs_ + '</div>';
    Tabs_ = Tabs_ +'<div id="Google_result" class="panel-collapse collapse">';
    Tabs_ = Tabs_ + '<div class="panel-body" id="GeneralTab">';
    var tabs_1='';
    tabs_1 = tabs_1 + '</div>';
    tabs_1 = tabs_1 + '</div>';
    tabs_1 = tabs_1 + '</div>';
    tabs_1 = tabs_1 + '</div>';
    tabs_1 = tabs_1 + '</div>';
    //alert(gridList);
    if(HasNextPage=="true")
    {
        if(interval<3)
        {
        document.getElementById("GeneralTab").innerHTML=document.getElementById("GeneralTab").innerHTML + gridList + '<div class="col-sm-12 col-xs-12 paddingzero clickToMore" onClick="javascript:Load_More_tab(); return false;">Click To See More</div>';
        }
        else
        {
         document.getElementById("GeneralTab").innerHTML=document.getElementById("GeneralTab").innerHTML + gridList;
        }
    }
    else
    {
    document.getElementById("selected_site_list").innerHTML= Tabs_ + gridList + '<div class="col-sm-12 col-xs-12 paddingzero clickToMore" onClick="javascript:Load_More_tab(); return false;">Click To See More</div>' + tabs_1;
    }
    console.log(servar_res);
    var place_id_array=[];
    var option="0";
    if(HasNextPage=="false")
    {
    if(Category_prop.length != 0)
    {
        var target=new Array(2);
        for (var j = 0; j < Category_prop.length; j++)
        {
            if(servar_res != "no-data")
            {
                for (var k=0; k < servar_res.length; k++)
                {
                    var test=servar_res[k].Category.split(',');
                    var index_= test.indexOf(Category_prop[j]);
                    if(index_ != -1)
                    {
                        target[0]=k;
                        target[1]=j;
                    }
                }
            }
        }
        
        arr = new Array(Category_prop.length);
        for (var j = 0; j < Category_prop.length; j++)
        {
            var abc=categories[Category_prop[j]];
            if(typeof abc == "undefined")
            {
                alert(Category_prop[j]);
            }
            
            var img_ = Category_prop[j].toLowerCase() + ".png";
           
            category_In_Grid = category_In_Grid + '<div class="mainCatagory List_cat container-fluid paddingzero">';
            category_In_Grid = category_In_Grid + '<div class="panel-group" id="accordion">';
            category_In_Grid = category_In_Grid + '<div class="panel panel-default">';
            category_In_Grid = category_In_Grid + '<div class="panel-heading eatPanel">';
            category_In_Grid = category_In_Grid + '<div class="col-sm-12 col-xs-12 paddingzero paddingCattb">';
            category_In_Grid = category_In_Grid + '<h4 class="panel-title">';
            category_In_Grid = category_In_Grid + '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#'+Category_prop[j]+'">';
            category_In_Grid = category_In_Grid + '<div class="col-sm-3 col-xs-3 catpadingicon"> <img src="images/'+img_+'" class="img-responsive" /></div>';
            category_In_Grid = category_In_Grid + '<div class="col-sm-7 col-xs-7 paddingzero heading">';
            category_In_Grid = category_In_Grid + abc;
            category_In_Grid = category_In_Grid + '</div>';
            category_In_Grid = category_In_Grid + '</a>';
            category_In_Grid = category_In_Grid + '</h4>';
            category_In_Grid = category_In_Grid + '</div>';
            category_In_Grid = category_In_Grid + '</div>';
            category_In_Grid = category_In_Grid +'<div id="'+Category_prop[j]+'" class="panel-collapse collapse">';
            category_In_Grid = category_In_Grid + '<div class="panel-body" >';
            
            category_In_Grid = category_In_Grid + '</div>';
            category_In_Grid = category_In_Grid + '</div>';
            category_In_Grid = category_In_Grid + '</div>';
            category_In_Grid = category_In_Grid + '</div>';
            category_In_Grid = category_In_Grid + '</div>';
            arr[j] = category_In_Grid;
            if(servar_res != "no-data")
            {
            for (var i=0; i < servar_res.length; i++)
            {
                console.log(typeof servar_res[i].LatLng);
                if(!(typeof servar_res[i].LatLng === "undefined"))
                {
                    var str_array = servar_res[i].Category.split(',');
                    var index = str_array.indexOf(Category_prop[j]);
                    if(index != -1)
                    {
                        index = place_id_array.indexOf(servar_res[i].PlaceIds);
                        if(index == -1)
                        {
                            var myObj = JSON.parse(servar_res[i].LatLng);
                            console.log(myObj);
                            if(j == target[1] && i==target[0])
                            {
                                //alert(Category_prop.length);
                                //alert("True section");
                                option = option+1;
                                Stored_On_server(servar_res[i].PlaceIds,myObj.H,myObj.L,j,true);
                            }
                            else
                            {
                                option = option+1;
                                //alert("False section");
                                //alert(target);
                                Stored_On_server(servar_res[i].PlaceIds,myObj.H,myObj.L,j,false);
                            }
                                place_id_array.push(servar_res[i].PlaceIds);
                        }
                    }
                }
            }
            }
            place_id_array=[];
            //alert(category_In_Grid);
            category_In_Grid ="";
        }
        if(Category_prop.length != 0 && option == "0")
        {
            for (var p=arr.length-1; p >=0; p--)
            {
                document.getElementById("selected_site_list").innerHTML= arr[p] +document.getElementById("selected_site_list").innerHTML;
            }
        }
    }
    else if(Category_prop.length != 0 && option == 0)
    {
        for (var p=arr.length-1; p >=0; p--)
        {
            document.getElementById("selected_site_list").innerHTML= arr[p] +document.getElementById("selected_site_list").innerHTML;
        }
    }
    }
}

function Load_More_tab()
{
    if(types_prop.length >0)
    {
        if (pagination_.hasNextPage)
        {
            $(".clickToMore").remove();
            if(interval<3){
                interval=interval+1;
                HasNextPage="true";
                pagination_.nextPage();
            }
            else
            {
                $(".clickToMore").remove();
            }
        }
        else
        {
        $(".clickToMore").remove();
        }
    }
}

function show_()
{
    var servar_res = res_list_server;
    //alert("show_");
    //setTimeout(function(){ alert("Hello"); }, 3000);
    var check=0;
    for (var i=0; i < servar_res.length; i++)
    {
        var parse=JSON.parse(servar_res[i].Detail);
        var test = arr.toString();
        var index=test.indexOf(parse.name);
        if(index !=-1)
        {
            check = check + 1;
        }
    }
    if(check >= servar_res.length)
    {
        for (var i=arr.length-1; i >= 0; i--)
        {
            document.getElementById("selected_site_list").innerHTML= arr[i] +document.getElementById("selected_site_list").innerHTML;
        }
    }
    else
    {
        setTimeout(function(){
                    for (var i=arr.length-1; i >= 0; i--)
                    {
                        document.getElementById("selected_site_list").innerHTML= arr[i]     +document.getElementById("selected_site_list").innerHTML;
                    }
                   }, 3000);
    }
    
    
}

function Stored_On_server(Id_Places,LAT,LON,j_id,End_flag)
{
    var current = new google.maps.LatLng(mylat,mylong);
    var destination = new google.maps.LatLng(LAT,LON);
    var miles = parseFloat(document.getElementById('ex1').value);
    
    service_d.getDistanceMatrix(
                                {
                                origins: [current], //LatLng Array
                                destinations: [destination], //LatLng Array
                                travelMode: google.maps.TravelMode.DRIVING,
                                unitSystem: google.maps.UnitSystem.IMPERIAL,
                                avoidHighways: false,
                                avoidTolls: false
                                }, callback_Dis);
    
    function callback_Dis(response, status)
    {
       
        if (status === google.maps.DistanceMatrixStatus.OK)
        {
            //alert(curent_location);
            var results_ele = response.rows[0].elements;
            
            if(!(typeof results_ele[0] === "undefined"))
            {
                element = results_ele[0];
                if(!(typeof element.distance === "undefined"))
                {
                    distance = element.distance.text;
                    var D_str = distance.indexOf(' ');
                    var str_d = distance.substr(0,D_str);
                    var mil = parseFloat(str_d);
                    if(mil <= miles)
                    {
                        Grid_Add(distance,Id_Places,j_id,End_flag)
                    }
                    else
                    {
                    	if(End_flag == true)
                       	{
                            //console.log(arr[i]);
                            show_();
                            //document.getElementById("selected_site_list").innerHTML= arr[i] +document.getElementById("selected_site_list").innerHTML;
                        }
                    }
                }
            }
        }
    	else
        {
            distance="";
            Grid_Add(distance,Id_Places,j_id,End_flag);
        }
    }
}

function Grid_Add(miles,Id_Places,j_id,End_flag)
    {
    var gridList='';
    service = new google.maps.places.PlacesService(map);
    service.getDetails({
                    placeId:Id_Places
                    }, function(place, status) {
                        if (status === google.maps.places.PlacesServiceStatus.OK) {
                            var abc ='';
                                //alert(JSON.stringify(place));
                            console.log(place.name + "  " + End_flag);
                            gridList = gridList + '<div class="single_library_container col-sm-12 col-xs-12 '+place.place_id+'" id="List_'+ place.place_id +'"><input type="hidden" name="Place_Id" value="'+place.place_id+'"/><input type="hidden" id="Lat_'+place.place_id+'"  value="'+place.geometry.location.lat()+'"/><input type="hidden" id="Log_'+place.place_id+'" value="'+place.geometry.location.lng()+'"/>';
                       
                            gridList = gridList + '<div class="listDescription col-sm-11 col-xs-11 paddingzero" id="'+ place.place_id +'" onClick="javascript:Detail_server(this,true); return false;"><a id="selectedSiteListImage_'+place.id+'">';
                       
                            if(!(typeof place.photos === "undefined"))
                            {
                            gridList = gridList + '<div class="listImage col-sm-6 col-xs-6 paddingzero"><img class="firstimage" id="Place_Image'+place.place_id+'" src="'+place.photos[0].getUrl({'maxWidth': 180, 'maxHeight': 160})+'" /></div>';
                            }
                            else
                            {
                            gridList = gridList + '<div class="listImage col-sm-6 col-xs-6 paddingzero"><img class="firstimage" id="Place_Image'+place.place_id+'" src="'+place.icon+'" /></div>';
                            }
                       
                            gridList = gridList + '<div class="listDescription col-sm-6 col-xs-6 paddingzero"><ul class="site-desc">';
                       
                            gridList = gridList + '<li class="title hotelName" id="Place_name'+place.place_id+'"> '+place.name+' - ';
                       
                            gridList = gridList + '<span id="Distance'+place.place_id+'">'+miles+'</span></li>';
                       
                            if(!(typeof place.rating === "undefined"))
                            {
                            gridList = gridList + '<li class="title hotelRating"><div class="rating"></div><div class="mainStars"><span class="stars"> '+place.rating+' </span></div><div class="reviews" id="Rev'+place.place_id+'">';
                            }
                            else
                            {
                            gridList = gridList + '<li class="title hotelRating"><div class="reviews" id="Rev'+place.place_id+'">';
                            }
                       
                            if(!(typeof place.price_level === "undefined"))
                            {
                                for(var j=0;j<parseInt(place.price_level);j++)
                                {
                                abc = abc + '$';
                                }
                                gridList = gridList + '<div class="title hotelPrice"> </div>';
                            }
                            else
                            {
                                gridList = gridList + '<div class="title hotelPrice"> </div>';
                            }
                       
                            gridList = gridList + '</div></li>';
                       
                            gridList = gridList + '<li class="title hotelAdd" id="Add_'+place.place_id+'"> '+place.vicinity+'</li>';
                       
                            gridList = gridList + '<li class="title hotelstatus"></li>';
                       
                            gridList = gridList +	'</ul></div></a></div>';
                       
                            gridList = gridList + '<div class="listImage col-sm-1 col-xs-1 paddingzero" onClick="javascript:itineraries(this); return false;"><div class="readMore"><button type="button" class="btn btn-primary btn-xs" >+</button></div></div></div>';
                       
                      //document.getElementById("selected_site_list").innerHTML= gridList + document.getElementById("selected_site_list").innerHTML;
                       var length_ = arr[j_id].length-30;
                       var xyz= arr[j_id].slice(0,length_);
                       var msn= arr[j_id].slice(length_);
                       arr[j_id] =xyz + gridList + msn;
                       //alert(End_flag);
                       if(End_flag == true)
                       {
                            //console.log(arr[i]);
                            show_();
                            //document.getElementById("selected_site_list").innerHTML= arr[i] +document.getElementById("selected_site_list").innerHTML;
                        }
                       //category_In_Grid = category_In_Grid + gridList;
                       //arr[j_id]=arr[j_id] + gridList;
                       //arr[j_id]=arr[j_id] + '</div>';
                }
                       /* Rating code start from here  */
                       $.fn.stars = function() {
                       return $(this).each(function() {
                                           // Get the value
                                           var val = parseFloat($(this).html());
                                           // Make sure that the value is in 0 - 5 range, multiply to get width
                                           var size = Math.max(0, (Math.min(5, val))) * 16;
                                           // Create stars holder
                                           var $span = $('<span />').width(size);
                                           // Replace the numerical value with stars
                                           $(this).html($span);
                                           });
                       }
                       $(function() {
                         $('span.stars').stars();
                         });
    });
    
}


function Detail_server(tthis,flag)
{
    var url = site_url+'site/getSelectedPlaceDetail?';
    PlaceId_Detail = tthis.id;
    $.ajax({
           type: 'GET',
           url: url,
           contentType: "application/json",
           dataType: 'jsonp',
           jsonp: 'callback',
           data: {
           selected_places : PlaceId_Detail
           },
           beforeSend : function(){
           //$("#visited_site_list").html('');
           //$("#loader_image").css("display","block");
           },
           crossDomain: true,
           
           success: function(res) {
           if(res['0']!= "no-data")
           {
           //alert(JSON.stringify(res));
           var parse=JSON.parse(res[0].Detail);
           var detail=[];
           
           detail.push(parse.about);
           detail.push(res[0].Category);
           
           Detail(tthis,detail);
           }
           else
           {
           res_list_server=res;
           List_Load();
           //swal("No data found on the server");
           }
           },
           error: function(e) {
           console.log(e.message);
           },
           complete: function(data) {
           //alert(data.message);
           //console.log(data.message);
           }
           });
}


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
								abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero restaurant">restaurant</div>';
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
                         abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"> 5 star hotels in Mumbai, The Leela offers world class luxury hotel near Mumbai airport offers tranquility in the chaotic city with palatial accommodation. </div>';
                       }
                       
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero"> <img src="images/busnessType.png" /></div>';
						 abc=abc+ '<div class="col-sm-10 col-xs-10 paddingzero"> '+types+' </div>';
						 abc=abc+ '<div class="clear"></div>';
						 abc=abc+ '</div>';
						 
						abc=abc+ '</div>';
						abc=abc+ '<div id="Contact" class="tab-pane fade">';
						abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 if(!(typeof place.international_phone_number === "undefined"))
						 {
						 abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero"><a href="tel:'+place.international_phone_number+'"><img src="images/call.png" /></a></div>';
                       abc=abc+ '<div class="col-sm-10 col-xs-10 paddingzero"><a href="tel:'+place.international_phone_number+'">'+place.international_phone_number+'</a></div>';
						 }
						 else
						 {
						  abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero"><a></a></div>';
						 }
						 
						 abc=abc+ '</div>';
                       
						 
						 abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero"> <img src="images/address.png" /></div>';
						 abc=abc+ '<div class="col-sm-10 col-xs-10 paddingzero"> '+place.vicinity+' </div>';
						 abc=abc+ '</div>';
                        abc=abc+ '<div class="col-sm-12 col-xs-12 paddingzero paddingtb">';
						 if(!(typeof place.website === "undefined"))
						 {
                        abc=abc+ '<div class="col-sm-2 col-xs-2 paddingzero "><img src="images/website.png" /></div>';
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
            
                       
                       document.getElementById("Tour_Listings").innerHTML = 'You have added ' + Tour_List.length + ' Listing <img src="images/eye.png">';
                       $('#ListDetail').show();
                       $('#nearByPlace').hide();
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
                       swal(status);
                       }
                       });
}
//function shows detil informtion about selected place also it contains structure of the details/profile sceen end here.

// Go back to Near by place from detail list view start here
function goToNearByPlace()
{
		document.getElementById("selected_site_Detail").innerHTML='';
        $('#ListDetail').hide();
        $('#itinerary_Ui').hide();
        $('#nearByPlace').show();
        $('#BuildSearchUi').hide();
    
}
// Go back to Near by place from detail list view end here

// Code for range slider start here
$(document).ready(function() {
    var slider = new Slider('#ex1',
                            {
                                formatter: function(value) {
                                    document.getElementById('dis_text').value=value;
                                    document.getElementById('dis1_text').value=value;
                                    $("#ex2").attr("value", value);
                                    return 'Miles: ' + value;
                                }
                            });
    var slider1 = new Slider('#ex2',
                            {
                                formatter: function(value) {
                                    document.getElementById('dis1_text').value=value;
                                    document.getElementById('dis_text').value=value;
                                    $("#ex1").attr("value", value);
                                    return 'Miles: ' + value;
                                }
                            });
				
});
// Code for range slider End here

//Itineraries starts here
function itineraries(thisobj)
{

// code to add and delete place from itinerary starts here 
if(!($('#List_'+thisobj.previousSibling.id).hasClass('addItinerary')))
{
  var ac=thisobj.previousSibling;
  var aaa="Add_"+thisobj.previousSibling.id;
  var p_address=document.getElementById(aaa).innerHTML;
    var name =document.getElementById('Place_name'+thisobj.previousSibling.id).innerHTML
    var index_=name.indexOf('-');
    name=name.substr(0,index_-1);
    
  var struct='<div class="col-sm-12 col-xs-12 paddingzero"><div class="col-sm-4 col-xs-4 paddingright"><img class="img-circle itineraryImg" src="'+document.getElementById('Place_Image'+thisobj.previousSibling.id).src+'" /></div><div class="col-sm-8 col-xs-8 paddingzero"><ul class="yetToVisit"><li class="title hotelName"> '+name+'</li> <li class="title itinerarydes"> '+document.getElementById('Add_'+thisobj.previousSibling.id).innerHTML+'</li><li class="title itinerarydes"> '+document.getElementById('Distance'+thisobj.previousSibling.id).innerHTML+'</li></ul></div></div>';

  

  document.getElementById('Itinerary').innerHTML =  document.getElementById('Itinerary').innerHTML + '<div class="col-sm-12 col-xs-12 paddingtb borderbottom ui-state-default ALL_Places_Tour YetToVisitedNode" id="Itinerari_'+thisobj.previousSibling.id+'"><div class="col-sm-9 col-xs-9 paddingzero" onclick="Detail(this,false)">' + struct + '</div> <div onClick="delete_it(this)" class="col-sm-1 col-xs-1 paddingzero Del" style="display:none"><img class="img-responsive" src="images/trash.png" /></div><div class="col-sm-2 col-xs-2 img- responsive paddingzero handle Drag" style="display:none"><span title="Re-arrange itinerary by dragging and dropping sites"></span></div><div class="markasvisited vis col-sm-3 col-xs-3 paddingleft" ><div class="markAsVisit">Mark visited</div><input onclick="visitsite(this)" class="visitedcheck" type="checkbox" name="visited"></div></div>';

  Itineraries_list.push(thisobj.previousSibling.id);
  Itineraries_Ad.push(p_address);
  Tour_List.push('1');
  //$('#List_'+thisobj.previousSibling.id).addClass('addItinerary');
    var abcn="."+thisobj.previousSibling.id;
    $(abcn).each(function() {
                 $(this).addClass('addItinerary');
                 });

}
else
{
  //$('#List_'+thisobj.previousSibling.id).removeClass('addItinerary');
    var abcn="."+thisobj.previousSibling.id;
    $(abcn).each(function() {
                 $(this).removeClass('addItinerary');
                 });
  $('#Itinerari_'+thisobj.previousSibling.id).remove();
  var index=Itineraries_list.indexOf(thisobj.previousSibling.id);
   if(index != -1)
         {
             Itineraries_list.splice(index, 1);
             Itineraries_Ad.splice(index, 1);
             Tour_List.splice(0,1);
         }
}
// code to add and delete place from itinerary ends here 
var i=1;

//code to add colore difference in the place tabs
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

i=1;

$(".visitedNode").each(function() {
	
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
//Adding colore difference code ends here

//code to hide edit mode if it is open.
 Editor();
//code end here
}
//itineraries ends here


//itinerary_Detail function will add/remove places to itinerary through details/Profile ui starts here
function itinerary_Detail()
{
if(detail_Id == '')
    return;
    
    var flag=true;
    if( $('#Itinerari_'+detail_Id).length )         // use this if you are using id to check
    {
        if(!($('#List_'+detail_Id).length))         // use this if you are using id to check
        {
            $('#Itinerari_'+detail_Id).remove();
            var index=Itineraries_list.indexOf(detail_Id);
            if(index != -1)
            {
                Itineraries_list.splice(index, 1);
                Itineraries_Ad.splice(index, 1);
                Tour_List.splice(0,1);
                document.getElementById("Tour_Listings").innerHTML = 'You have added ' + Tour_List.length + ' Listing <img src="images/eye.png">';
            }
            flag=false;
        }
    }
    
    if(flag == true)
    if(!($('#List_'+detail_Id).hasClass('addItinerary')))
    {
        
        var aaa="Add_"+detail_Id;
        var p_address=document.getElementById(aaa).innerHTML;
        var name =document.getElementById('Place_name'+detail_Id).innerHTML;
        var index_=name.indexOf('-');
        name=name.substr(0,index_-1);
        
        var struct='<div class="col-sm-12 col-xs-12 paddingzero"><div class="col-sm-4 col-xs-4 paddingright"><img class="img-circle itineraryImg" src="'+document.getElementById('Place_Image'+detail_Id).src+'" /></div><div class="col-sm-8 col-xs-8 paddingzero"><ul class="yetToVisit"><li class="title hotelName"> '+name+'</li> <li class="title itinerarydes"> '+document.getElementById('Add_'+detail_Id).innerHTML+'</li><li class="title itinerarydes"> '+document.getElementById('Distance'+detail_Id).innerHTML+'</li></ul></div></div>';
        
        
        
        document.getElementById('Itinerary').innerHTML =  document.getElementById('Itinerary').innerHTML + '<div class="col-sm-12 col-xs-12 paddingtb borderbottom ui-state-default ALL_Places_Tour YetToVisitedNode" id="Itinerari_'+detail_Id+'"><div class="col-sm-8 col-xs-8 paddingzero" onclick="Detail(this,false)">' + struct + '</div> <div onClick="delete_it(this)" class="col-sm-2 col-xs-2 paddingzero Del" style="display:none"><img class="img-responsive" src="images/trash.png" /></div><div class="col-sm-2 col-xs-2 img- responsive paddingzero handle Drag" style="display:none"><span title="Re-arrange itinerary by dragging and dropping sites"></span></div><div class="markasvisited vis col-sm-4 col-xs-4 paddingleft" ><div class="markAsVisit">Mark visited</div><input onclick="visitsite(this)" class="visitedcheck" type="checkbox" name="visited"></div></div>';
        
        Itineraries_list.push(detail_Id);
        Itineraries_Ad.push(p_address);
        Tour_List.push('1');
        document.getElementById("Tour_Listings").innerHTML = 'You have added ' + Tour_List.length + ' Listing <img src="images/eye.png">';
        //$('#List_'+detail_Id).addClass('addItinerary');
        var abcn="."+detail_Id;
        $(abcn).each(function() {
                                    $(this).addClass('addItinerary');
                                  // alert('hi');
                                    });
        
    }
    else
    {
       // $('#List_'+detail_Id).removeClass('addItinerary');
        var abcn="."+detail_Id;
        $(abcn).each(function() {
                                   $(this).removeClass('addItinerary');
                                   });

        $('#Itinerari_'+detail_Id).remove();
        var index=Itineraries_list.indexOf(detail_Id);
        if(index != -1)
        {
            Itineraries_list.splice(index, 1);
            Itineraries_Ad.splice(index, 1);
            Tour_List.splice(0,1);
            document.getElementById("Tour_Listings").innerHTML = 'You have added ' + Tour_List.length + ' Listing <img src="images/eye.png">';
        }
    }
    // code to add and delete place from itinerary ends here
    
    
    //code to add colore difference in the place tabs
    col_Diff();
    //Adding colore difference code ends here

}
//itinerary_Detail function will add/remove places to itinerary through details/Profile ui starts here

//code for editing itineraries from the Itinerary list
function Edit(thisObj)
{
//alert(document.getElementById('EDIT_a').innerHTML);
    if($('.vis').is(':visible'))
    {
     document.getElementById('EDIT_a').innerHTML = 'DONE';
     $(".vis").css("display", "none");
     $(".Drag").css("display", "block");
     $(".Del").css("display", "block");
    }
    else
    {
    Editor();
    }
}
//edit function ends here.

//added editor function to avoid redundancy(used in multiple places)
function Editor()
{
 	 document.getElementById('EDIT_a').innerHTML = '<img src="images/edit.png" style="padding-right: 10px;">EDIT';
     $(".vis").css("display", "block");
     $(".Drag").css("display", "none");
     $(".Del").css("display", "none");
}
//also this includes mainly in the edit function.

//code for deleting itineraries from the list
function delete_it(thes)
{
	 $(thes).parent().remove();
     var id=$(thes).parent().attr('id');
     var index = id.indexOf('_');
     if(index != -1)
     {
         id= id.substr(index+1);
         var abcn="."+id;
         //$('#List_'+id).removeClass('addItinerary');
         $(abcn).each(function() {
                      $(this).removeClass('addItinerary');
                      });

         index=Itineraries_list.indexOf(id);
         if(index != -1)
         {
             Itineraries_list.splice(index, 1);
             Itineraries_Ad.splice(index, 1);
         }
             Tour_List.splice(0,1);
         }
     }
//itinararies deletion function ends here

/* Drag itnorary Strat here */
$(document).bind('pageinit', function() {
		$( "#Itinerary" ).sortable({handle : '.handle'}).disableSelection();	
});
/* Drag itnorary End here */

//function adds Visited/unvisited class to the tag so that we can see the visited  places to visited tab and unvisited places in unvisited tab in itinerary (tours) ui
function visitsite(thisE)
{
	var id_list =$(thisE).parent().parent().attr('id');
	var index = id_list.indexOf('_');
	$(thisE).parent().parent().css("display","none");
	if ($(thisE).is(':checked')) {
		$(thisE).addClass( "Check_node" );
        $(thisE).parent().parent().addClass( "visitedNode" );
		$(thisE).parent().parent().removeClass( "YetToVisitedNode" );

		if(index != -1)
  	    {
   	      id_list= id_list.substr(index+1);
   	      index=Itineraries_list.indexOf(id_list);
   	      if(index != -1)
    	     {
        	     Itineraries_list.splice(index, 1);
       		     Itineraries_Ad.splice(index, 1);
       		 }
    	}
    } else {
    	$(thisE).removeClass( "Check_node" );
        $(thisE).parent().parent().removeClass( "visitedNode" );
		$(thisE).parent().parent().addClass( "YetToVisitedNode" );

		if(index != -1)
    	 {
	         id_list= id_list.substr(index+1);
	         var address_id = 'Add_'+id_list;
	         var PlaceAddress = document.getElementById(address_id).innerHTML;
             Itineraries_list.push(id_list);
             Itineraries_Ad.push(PlaceAddress);
    	 }
    }
}
//visited/unvisited function ends here


//function that toggels the visited and unvisited node starts here
function Visited_Tab_toggle(thisTb)
{
 if(thisTb.firstChild.value == "YetToVisit")
 {
 	$('.YetToVisitedNode').each(function() {
    	$(this).css("display","block");
 	});
 	$('.visitedNode').each(function() {
    	$(this).css("display","none");
 	});
 }
 else
 {
 	$('.YetToVisitedNode').each(function() {
    	$(this).css("display","none");
 	});
 	$('.visitedNode').each(function() {
    	$(this).css("display","block");
 	});
 	
 	$('input:checkbox.Check_node').each(function () {
       this.checked = true;
  	});
 }
}
//function that toggels the visited and unvisited node ends here


//function that enables the tinerary ui starts here
function GoToItinerary()
{
//alert(Tour_List.length);

 $( "#yet_to_visit_sites" ).trigger( "click" );   
 Editor();
 document.getElementById("selected_site_Detail").innerHTML='';
 document.getElementById("MyTripList").innerHTML="";
 detail_Id = '';

 $('#ListDetail').hide();
 $('#nearByPlace').hide();
 $('#itinerary_Ui').show();
    $('#Save_Ui').hide();
    $('#MyTrips').hide();
 $('#TourMapCanvas').hide();
    $('#BuildSearchUi').hide();
    
}
//function that enables the tinerary ui ends here

//function will take u to map for navigation
function Build(mode)
{
	if(Itineraries_list.length == 0)
	{
	swal("Please select a place first");
	return;
	}
	
	var CurrentLocationUser=document.getElementById('starting_location').value;
	
	//alert(CurrentLocationUser);
	
	$(".YetToVisitedNode").each(function() 
    {
    	var Firt_Address=$(this).attr('id');
    	var index = Firt_Address.indexOf('_');
    	Firt_Address= Firt_Address.substr(index+1);
   	 	index=Itineraries_list.indexOf(Firt_Address);
    	if(index !=-1)
    	{
    		Destination_addr=Itineraries_Ad[index];
    		alert(Destination_addr);
    		return false;
    	}
    });
    
    
    if(Destination_addr == '')
    {
    swal('Tour list not found');
    return;
    }
    //alert(des);
    //alert(CurrentLocationUser);
    if(CurrentLocationUser != "")
    {
    launchnavigator.navigate(
                             Destination_addr,
                             CurrentLocationUser,
                             function(){
                             //alert("Plugin success");
                             },
                             function(error){
                             swal("Plugin error: "+ error);
                             },
                             {
                             	preferGoogleMaps: true,
                             	transportMode: mode,
                             	urlScheme: "X-Plore://",
                             	backButtonText: "X-Plore",
                             	enableDebug: true
                             });
    }
    else
    {
        launchnavigator.navigate(
                                 Destination_addr,
                                 null,
                                 function(){
                                 //alert("Plugin success");
                                 },
                                 function(error){
                                 swal("Plugin error: "+ error);
                                 },
                                 {
                                 preferGoogleMaps: true,
                                 transportMode: mode,
                                 urlScheme: "X-Plore://",
                                 backButtonText: "X-Plore",
                                 enableDebug: true
                                 });
    }
}

//function use to invoke the transport mode droupdown (empty function please dont delete).
function trial()
{}
//function use to invoke the transport mode droupdown ends here

//
function Save_Ui_function()
{
$('#Save_Ui').show();
$('#itinerary_Ui').hide();
    detail_Id = '';

//Save_Tour(userid);
//Get_Tour();
//FbLogin();
}
//

//Facebook login start from here
function FbLogin(Get_Info)
{
    alert(Get_Info);
	
    facebookConnectPlugin.getLoginStatus(function (userData) {
                                           //alert(userData);
                                            if(userData.status == "connected")
                                            {
                                                FbUserId_Log=userData.authResponse.userID;
                                                if(Get_Info == "Save")
                                                {
                                                    Save_Ui_function();
                                                }
                                                else if(Get_Info == "MyTrips")
                                                {
                                                    Get_Tour();
                                                }
                                                else if(Get_Info == "Share")
                                                {
                                                    //Share_trip(Save_id);
                                                    if(Save_id != "0")
                                                    {
                                                        Share_trip(Save_id);
                                                    }
                                                    else
                                                    {
                                                        Save_Ui_function();
                                                        process="share";
                                                    }
                                                }
                                                else if(Get_Info == "Notification")
                                                {
                                                    Notification();
                                                }
                                                else
                                                {
                                                Load_Trips(Get_Info);
                                                }
                                            }
                                            else
                                            {
                                            facebookConnectPlugin.login(["public_profile"],
                                                                     function (userData_2) {
                                                                     
                                                                     if(userData_2.status == "connected")
                                                                     {
                                                                     //alert(userData.authResponse.userID)
                                                                     FbUserId_Log=userData_2.authResponse.userID;
                                                                        if(Get_Info == "Save")
                                                                        {
                                                                            Save_Ui_function();
                                                                        }
                                                                        else if(Get_Info == "MyTrips")
                                                                        {
                                                                            Get_Tour();
                                                                        }
                                                                        else if(Get_Info == "Share")
                                                                        {
                                                                            if(Save_id != "0")
                                                                            {
                                                                                Share_trip(Save_id);
                                                                            }
                                                                            else
                                                                            {
                                                                                Save_Ui_function();
                                                                                process="share";
                                                                            }
                                                                        }
                                                                        else if(Get_Info == "Notification")
                                                                        {
                                                                        Notification();
                                                                        }
                                                                        else
                                                                        {
                                                                            Load_Trips(Get_Info);
                                                                        }
                                                                     }
                                                                     else
                                                                     {
                                                                        alert("Login failed");
                                                                        return;
                                                                     }
                                                                    },
                                                                     function (error) {
                                                                     swal("" + error);
                                                                     });
                                            }
                                         },
                                         function (error) {
                                            alert("" + error);
                                         
                                         });
}
//Facebook login end here

// function will store the tour to the server starts here
//We have seperated this function from login to avoid the redundancy(unsed in multiple places)
function Save_Tour()
{
    
if(T_Flag == "false")
{
    alert("in save tour function");

    var Places_List_all=[];
    var profile = '';
    
    $(".ALL_Places_Tour").each(function()
                               {
                               var Places_List=$(this).attr('id');
                               var index = Places_List.indexOf('_');
                               Places_List_all.push(Places_List.substr(index+1));
                               });
    var ProfileTour="Public";
    var Tour_description = document.getElementById("desc_t").value;
    var Tour_name = document.getElementById("name_t").value;
    
    if($('#Public').is(':checked'))
    {
        ProfileTour="Public";
    }
    else
    {
        ProfileTour="Private";
    }
    
    if(Tour_name == "")
    {
        swal("Please enter tour name");
        return;
    }
    
    if(Tour_description == "")
    {
        swal("Please enter tour description.");
        return;
    }
    
    if(FbUserId_Log == "")
    {
        swal("Please Login First");
        return;
    }
    
    var detail = {
        "Places_List_all" : Places_List_all.toString(),
        "Description" : Tour_description,
    };
    
    var url = site_url+'site/add_save_tour?';
    
    $.ajax({
           type: 'GET',
           url: url,
           contentType: "application/json",
           dataType: 'jsonp',
           jsonp: 'callback',
           data: {
           Tour_name : Tour_name,
           detail : JSON.stringify(detail),
           userid : FbUserId_Log,
           ProfileTour : ProfileTour.toString()
           },
           beforeSend : function(){
           //$("#visited_site_list").html('');
           //$("#loader_image").css("display","block");
           },
           crossDomain: true,
           
           success: function(res) {
           var no=JSON.stringify(res);
           if(parseInt(no)>0)
           {
           swal("Stored successfully (Save tour function)");
           document.getElementById("desc_t").value="";
           document.getElementById("name_t").value="";
           Save_id=no;
           GoToItinerary();
           if(process=="share")
           {
            Share_trip(Save_id);
           }
           }
           else
           {
           swal("No data stored on the server");
           //document.getElementById("desc_t").value="";
           //document.getElementById("name_t").value="";
           }
           },
           error: function(e) {
           console.log(e.message);
           },
           complete: function(data) {
           //alert(data.message);
           //console.log(data.message);
           }
           });
    }
    else
    {
    Edit_Trip_Call();
    }
}
//function that stores the tour ends here

//Get_Tour function that retrives saved tour from the server starts here.
function Get_Tour()
{
    if(FbUserId_Log == "")
    {
        alert("Please Login First");
        return;
    }
    
    
    var url = site_url+'site/getAllTour?';
    
    var prefarance = "Names";
    
    $.ajax({
           type: 'GET',
           url: url,
           contentType: "application/json",
           dataType: 'jsonp',
           jsonp: 'callback',
           data: {
           Userid : FbUserId_Log,
           Prefarance : prefarance.toString()
           },
           beforeSend : function(){
           //$("#visited_site_list").html('');
           //$("#loader_image").css("display","block");
           },
           crossDomain: true,
           
           success: function(res) {
           
           if(res[0]!= "no-data")
           {
           document.getElementById("MyTripList").innerHTML='';
           var abc='';
           for(var i=0; i<res.length; i++)
           {
           var myObj = JSON.parse(res[i].Detail);
           var str_array = myObj.Places_List_all.split(',');
		   var description_='';
           if(myObj.Description.length >150)
           {
           description_=myObj.Description.substr(0,150) + "...";
           }
           else
           {
           description_=myObj.Description;
           }
           //alert(myObj.Description);
           //break;
           
           var struct='<div class="col-sm-11 col-xs-11 paddingzero" onclick="Load_Trips('+res[i].SrNo+')"><div class="col-sm-2 col-xs-2 paddingzero"><img class="img-responsive" src="images/tourlogo.png" /></div><div class="col-sm-9 col-xs-9 paddingzero paddingleft"><ul><li class="title Name" id="Name_'+res[i].SrNo+'"> '+res[i].TourName+'</li> <li class="title itinerarydes generalStatus" id="Profile_'+res[i].SrNo+'"> '+res[i].Profile+'</li><li class="title itinerarydes tripDesc" id="Description_'+res[i].SrNo+'"> '+description_+'</li></ul></div></div><div class="col-sm-1 col-xs-1 paddingzero"><div class="edit" onclick="Edit_Trip('+res[i].SrNo+')"><img class="img-responsive" src="images/edit.png" /></div><div class="edit" onclick="Delete_Trip('+res[i].SrNo+')"><img class="img-responsive" src="images/remove.png" /></div><div class="edit" onclick="Share_trip('+res[i].SrNo+')"><img class="img-responsive" src="images/sharesmall.png" /></div></div>';
           
           abc = abc + '<div class="col-sm-12 col-xs-12 paddingtb borderbottom" id="TourList_'+res[i].SrNo+'"><input type="hidden" id="PlaceLog_'+res[i].SrNo+'" value="'+myObj.Places_List_all+'"/>' + struct + '</div>';
           
           } 
           document.getElementById("MyTripList").innerHTML=abc;
            $('#MyTrips').show();
            //$('#Save_Ui').hide();
            $('#itinerary_Ui').hide();
            $( "#PopUpClose" ).trigger( "click" );
            swal("Retrived successfully");
           }
           else
           {
            swal("No data stored on the server form u");
            document.getElementById("MyTripList").innerHTML="";
            $('#MyTrips').hide();
            //$('#Save_Ui').hide();
            $('#itinerary_Ui').show();
            return;
           }
           },
           error: function(e) {
           console.log(e.message);
           },
           complete: function(data) {
           //alert(data.message);
           //console.log(data.message);
           }
           });

}
//Get_Tour function that retrives saved tour from the server ends here

//Load saved trips in itinarary view starts here
function Load_Trips(srno)
{
    console.log("load tour " + srno);
    if(srno == "")
    {
        alert("Please Login First");
        return;
    }
    Save_id=srno;
    var url = site_url+'site/getDetailTour?';
    alert("load trips " +srno);
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
           $(".addItinerary").each(function()
                                   {
                                      $(this).removeClass('addItinerary');
                                   });
           Itineraries_list=[];
           Itineraries_Ad=[];
           Tour_List=[];
           document.getElementById('Itinerary').innerHTML='';
           var myObj = JSON.parse(res[0].Detail);		
           var str_array = myObj.Places_List_all.split(',');
           //return;
           var abca='';
           
           service = new google.maps.places.PlacesService(map);
           for(var i=0; i<str_array.length; i++)
           {
           //alert(str_array[i]);
           
                      service.getDetails({
                              placeId:str_array[i]
                              },
                              function(place, status) {
                              
                              if (status === google.maps.places.PlacesServiceStatus.OK)
                              {
                              var struct='';
                                //alert("hi");
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
                              
                              
                              document.getElementById('Itinerary').innerHTML =  document.getElementById('Itinerary').innerHTML + '<div class="col-sm-12 col-xs-12 paddingtb borderbottom ui-state-default ALL_Places_Tour YetToVisitedNode" id="Itinerari_'+place.place_id+'"><div class="col-sm-8 col-xs-8 paddingzero" onclick="Detail(this,false)">' + struct + '</div> <div onClick="delete_it(this)" class="col-sm-2 col-xs-2 paddingzero Del" style="display:none"><img class="img-responsive" src="images/trash.png" /></div><div class="col-sm-2 col-xs-2 img- responsive paddingzero handle Drag" style="display:none"><span title="Re-arrange itinerary by dragging and dropping sites"></span></div><div class="markasvisited vis col-sm-4 col-xs-4 paddingleft" ><div class="markAsVisit">Mark visited</div><input onclick="visitsite(this)" class="visitedcheck" type="checkbox" name="visited"></div></div>';
                              struct='';
                              Itineraries_list.push(place.place_id);
                              Itineraries_Ad.push(place.vicinity);
                              Tour_List.push('1');
                              $("#ADD_New").val("");
                              
                            if($('#List_'+place.place_id).length)    // use this if you are using id to check
                            {
                             //$('#List_'+place.place_id).addClass('addItinerary');
                             var abcn="."+place.place_id;
                             //$('#List_'+id).removeClass('addItinerary');
                             $(abcn).each(function() {
                                        $(this).addClass('addItinerary');
                                        });
                            }
                              //code to add colore difference code starts here
                               col_Diff();                          
                              //code to add colore difference ends here
                              
                            }
                              else
                              {
                                swal(status);
                              }
                              });

           }
           $('#itinerary_Ui').show();
           $('#MyTrips').hide();
           $('#BuildSearchUi').hide();
           }
           else
           {
           swal("No data stored on the server form u");
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


//Share_trip function that share the trip over social networking site starts here
function Share_trip(Share_id)
{
    console.log("share tour");
    
    var link ="http://wms-dev.com/xplore/index.php/site/Shared?Tour_Id=";
    
    if(typeof Share_id !== 'undefined')
    {
        link =link + Share_id;
    }
    else
    {
        return;
    }
    
    link=encodeURI(link);
    //alert(link);
    facebookConnectPlugin.showDialog(
                                     {
                                     method: "feed",
                                     link: link,
                                     caption: 'Check This Out'
                                     },
                                     function (userData_2) {
                                     console.log(userData_2);
                                     },
                                     function (error) {
                                     swal("" + error);
                                     });
    //alert("share");
}
//Share_trip function that share the trip over social networking site ends here


//Edit_Trip function that edit the trip over server starts here
function Edit_Trip(srno)
{
    T_Profile = document.getElementById('Profile_'+srno).innerHTML;
    T_name = document.getElementById('Name_'+srno).innerHTML;
    T_des = document.getElementById('Description_'+srno).innerHTML;
    T_Pl_Log = document.getElementById('PlaceLog_'+srno).value;
    T_Flag = "true";
    T_SrNo = srno;
    Load_Trips(srno);
   console.log(T_Profile + "" + T_name + "" + T_des + "" + T_Pl_Log + "" +T_Flag);
    //swal("share tour");
}
//Edit_Trip function that edit the trip over server ends here

function Edit_Trip_Call()
{
    var ProfileTour= T_Profile;
    
    if($('#Public').is(':checked'))
    {
        ProfileTour="Public";
    }
    else
    {
        ProfileTour="Private";
    }

    
    console.log("in Edit tour function");
    if(FbUserId_Log == "")
    {
        alert("Please Login First");
        return;
    }

    
    var Tour_description = document.getElementById("desc_t").value;
    var Tour_name = document.getElementById("name_t").value;
    var Id = T_SrNo;
    
    var Places_List_all=[];
    var profile = '';
    
    $(".ALL_Places_Tour").each(function()
                               {
                               var Places_List=$(this).attr('id');
                               var index = Places_List.indexOf('_');
                               Places_List_all.push(Places_List.substr(index+1));
                               });
    
    if(Tour_name == "")
    {
        alert("Please enter tour name");
        return;
    }
    
    if(Tour_description == "")
    {
        alert("Please enter tour description.");
        return;
    }
    
    var detail = {
        "Places_List_all" : Places_List_all.toString(),
        "Description" : Tour_description,
    };
    
    var url = site_url+'site/EditTour?';
    
    $.ajax({
           type: 'GET',
           url: url,
           contentType: "application/json",
           dataType: 'jsonp',
           jsonp: 'callback',
           data: {
            srno : Id,
            Tour_name : Tour_name,
            detail : JSON.stringify(detail),
            userid : FbUserId_Log,
            ProfileTour : ProfileTour.toString()
           },
           beforeSend : function(){
           //$("#visited_site_list").html('');
           //$("#loader_image").css("display","block");
           },
           crossDomain: true,
           
           success: function(res) {
           //alert()
           if(JSON.stringify(res))
           {
           Save_id="0";
           process="";
           swal("Stored successfully (Edit trip call)");
           document.getElementById("desc_t").value="";
           document.getElementById("name_t").value="";
           //T_Flag = false;
           GoToItinerary();
           }
           else
           {
           swal("No data stored on the server");
           document.getElementById("desc_t").value="";
           document.getElementById("name_t").value="";
           }
           },
           error: function(e) {
           console.log(e.message);
           },
           complete: function(data) {
           //alert(data.message);
           //console.log(data.message);
           }
           });
    }

//Delete function that delete the trip over server starts here
function Delete_Trip(srno)
{
    //alert("load tour" + srno);
    if(FbUserId_Log == "")
    {
        alert("Please Login First");
        return;
    }
    
    var url = site_url+'site/DeleteTour?';
    
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
           if(res[0]!= "no-data Updated")
           {
            console.log(res);
            Get_Tour();
           }
           else
           {
            swal("No data stored on the server form u.");
            document.getElementById("MyTripList").innerHTML="";
            $('#MyTrips').hide();
            $('#itinerary_Ui').show();
            return;
           }
           },
           error: function(e) {
           console.log(e.message);
           },
           complete: function(data) {
           //alert(data.message);
           //console.log(data.message);
           }
           });

}
//Delete function that delete the trip over server ends here

function Tour_Map_Canvas()
{
console.log("Tour Map");
$('#itinerary_Ui').hide();
$('#TourMapCanvas').show();

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

var mapOptions = {
zoom: 15,
center: myLatLng,
mapTypeId: google.maps.MapTypeId.ROADMAP
}

map = new google.maps.Map(document.getElementById("Tour_map_canvas"), mapOptions);

var marker = new google.maps.Marker({
                                    position: myLatLng,
                                    map: map
                                    });

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
bounds.extend(myLatLng);
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
    swal(page_[Page]);
}

function Tour_Clear(Select)
{
    if(Select == "Build" && (Itineraries_list.length >0 || Itineraries_Ad.length >0))
    {
    if(!confirm("Do you want to clear a tour u have created?"))
    {
        GoToItinerary();
        return;
    }
    }
    Itineraries_list=[];
    Itineraries_Ad=[];
    Tour_List=[];
    T_Flag="false";
    document.getElementById('Itinerary').innerHTML='';
    $(".addItinerary").each(function()
                            {
                            $(this).removeClass('addItinerary');
                            });
    Save_id="0";
    process="";
    GoToItinerary();
}

function Notification()
{
    var stored="1095227707155925";
    facebookConnectPlugin.api("/950857141619867/posts",[],
                              function (result) {
                                console.log(JSON.stringify(result));
                                var abc='';
                              //alert(result.data.length);
                                for(var i=0;i<=result.data.length-1;i++)
                                {
                                    if(typeof result.data[i].message === "undefined")
                                    continue;
                                    //alert(result.data[i].message);
                                    //console.log(result.data[i].message);
                                    var struct='<div class="col-sm-2 col-xs-2"><img class="img-responsive" src="images/tourlogo.png"></div><div class="col-sm-10 col-xs-10"><ul><li class="title " > '+result.data[i].message+'</li><li class="title" > '+result.data[i].created_time+'</li></ul></div>';
                              
                                    abc = abc + '<div class="col-sm-12 col-xs-12 paddingtb borderbottom">' + struct + '</div>';
                                }
                                //alert(abc);
                                document.getElementById('Fb_Posts').innerHTML=abc;
                                $('#NotificationUi').show();
                                $('#homepage').hide();
                                $('#mapCanvas').hide();
                                $('#nearByPlace').hide();
                                $('#CatagoryPage').hide();
                                $('#Detail').hide();
                                $('#itinerary_Ui').hide();
                                $('#ListDetail').hide();
                                $('#Save_Ui').hide();
                                $('#MyTrips').hide();
                                $('#BuildSearchUi').hide();
                               },
                              function (error) {
                              alert("Failed: " + error);
                              });
}

function Build_SearchTours()
{
    $('#BuildSearchUi').show();
    $('#NotificationUi').hide();
    $('#homepage').hide();
    $('#mapCanvas').hide();
    $('#nearByPlace').hide();
    $('#CatagoryPage').hide();
    $('#Detail').hide();
    $('#itinerary_Ui').hide();
    $('#ListDetail').hide();
    $('#Save_Ui').hide();
    $('#MyTrips').hide();
    //$('#Build_ui').hide();
    //PublicTours();
}

function PublicTours()
{
    var url = site_url+'site/getPublicTour?';
    
    var prefarance = "Public";
    
    //$('#Build_ui').hide();
    $('#Search_ui').show();
    
    $.ajax({
           type: 'GET',
           url: url,
           contentType: "application/json",
           dataType: 'jsonp',
           jsonp: 'callback',
           data: {
           Prefarance : prefarance.toString()
           },
           beforeSend : function(){
           //$("#visited_site_list").html('');
           //$("#loader_image").css("display","block");
           },
           crossDomain: true,
           
           success: function(res) {
           
           if(res[0]!= "no-data")
           {
           document.getElementById("Search_ui_Data").innerHTML='';
           var abc='';
           for(var i=0; i<res.length; i++)
           {
           var myObj = JSON.parse(res[i].Detail);
           var str_array = myObj.Places_List_all.split(',');
           var description_='';
           if(myObj.Description.length >150)
           {
           description_=myObj.Description.substr(0,150) + "...";
           }
           else
           {
           description_=myObj.Description;
           }
           //alert(myObj.Description);
           //break
           
		   
		   var struct='<div class="col-sm-11 col-xs-11 paddingzero" onclick="Load_Trips('+res[i].SrNo+')"><div class="col-sm-2 col-xs-2 paddingzero"><img class="img-responsive" src="images/tourlogo.png" /></div><div class="col-sm-9 col-xs-9 paddingzero paddingleft"><ul><li class="title Name" id="Name_'+res[i].SrNo+'"> '+res[i].TourName+'</li> <li class="title itinerarydes generalStatus" id="Profile_'+res[i].SrNo+'"> '+res[i].Profile+'</li><li class="title itinerarydes tripDesc" id="Description_'+res[i].SrNo+'"> '+description_+'</li></ul></div></div><div class="col-sm-1 col-xs-1 paddingzero"><div class="edit" onclick="Share_trip('+res[i].SrNo+')"><img class="img-responsive" src="images/sharesmall.png" /></div></div>';
           
           abc = abc + '<div class="col-sm-12 col-xs-12 paddingtb borderbottom" id="TourList_'+res[i].SrNo+'"><input type="hidden" id="PlaceLog_'+res[i].SrNo+'" value="'+myObj.Places_List_all+'"/>' + struct + '</div>';
           
           }
           document.getElementById("Search_ui_Data").innerHTML=abc;
           //$('#MyTrips').show();
           //$('#Save_Ui').hide();
           //$('#itinerary_Ui').hide();
           $( "#PopUpClose" ).trigger( "click" );
           swal("Retrived successfully");
           }
           else
           {
           swal("No data stored on the server form u");
           document.getElementById("Search_ui_Data").innerHTML="";
           return;
           }
           },
           error: function(e) {
           console.log(e.message);
           },
           complete: function(data) {
           //alert(data.message);
           //console.log(data.message);
           }
           });
}

app.initialize();
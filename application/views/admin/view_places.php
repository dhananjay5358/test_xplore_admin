<html>
	<head>
		<?php echo $admin_header;?>
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyCgFaImUo6xWBC9qizp25fe9IWxDjFVHdM&libraries=places" /></script>
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

            $("document").ready(function()
            {
                $("#clear").on("click", function(event) 
                {
                    location.reload();
                });
            });
            
        </script>

    <script type="text/javascript">
            function prompt_delete_gallery(e)
            {
                var tr = $(e.target).closest("tr");
                var data = this.dataItem(tr);

                var show_delete_media_box=[{
                            title:"Delete Image Gallery",
                            html: "<strong>are you really want to delete this place ?</strong>",
                            buttons:{"Delete" : true , "Cancel" : false},
                            submit: function(e,v,m,f){
                                if(v==true)
                                {                                   
                                    conformed_delete_gallery(data.SrNumber);                                    
                                }
                            
                            }
                        }];
                $.prompt(show_delete_media_box);
            }
            
            function conformed_delete_gallery(id)
            {
                $.ajax({
                            url: '<?php echo site_url('site/remove_custom_places');?>',     
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

            function prompt_edit_gallery(e)
            {
                
              var tr = $(e.target).closest("tr");
              var data = this.dataItem(tr);
              console.log(data);
              var F_Lat_Long = data.Foresquare_lat_lng_H +" _ "+ data.Foresquare_lat_lng_L;

              var google_place_Lat_Long = data.latlng_H +" _ "+ data.latlng_L;
           
              var link = "<?php echo site_url('site/edit_Places'); ?>";

              var html = '<form method="POST" id="contact" name="13" action="'+link+'" class="form-horizontal" enctype="multipart/form-data">';
              var im = 'http://s3.amazonaws.com/retail-safari/'+data.Path;
              var cancel = "<?php echo site_url('site/viewPlaces'); ?>";
              console.log(data.isClosed);
              var img = 'http://s3.amazonaws.com/retail-safari/'+data.profileimage;
              html = html+"<br><input type='hidden' name='Id' value='"+data.SrNumber+"'><input type='hidden' name='id_image_path' value='"+data.Path+"'><input type='hidden' name='id_image' value='"+data.profileimage+"'><label>Place Name</label><input type='text' id='Location' value='Chicago' style='width:95%'/><br/></br><label>Foursquare Suggestions</label><input type='text' name='place_location' id='place_location' style='width:95%'/><br/><br/><label>Foursquare longitude latiitude</label><input type='text' value='"+F_Lat_Long+"' name='F_Lat_Long' id='F_Lat_Long' style='width:95%'/><br/><br/><label>Google place address</label><input type='text' name='starting_location' id='starting_location' style='width:95%'/><br/><br/><label>Google place longitude latiitude</label><input type='text' value='"+google_place_Lat_Long+"' style='width:95%' name='G_Lat_Long' id='G_Lat_Long'/><br/><br/><label>Name</label><input type='text' value='"+data.name+"' id='u_name' name='u_name' style='width:95%'/><br/><label>Address</label><input type='text' value='"+data.address+"' name='add' id='add' style='width:95%'/><br/><br/><label>Tag</label><input type='text' value='"+data.Category+"' name='tag_places' style='width:95%' id='tag_places'/><br/><label>Place Identifier</label><input type='text' value='"+data.PlaceIdentifier+"' name='PlaceIdentifier' style='width:95%' id='tag_places'/><br/><br/><label>Description</label><textarea name='about' contenteditable='true'  class='editor' id='about' style='width:95%' runat='server' rows='10'>"+data.about+"</textarea><br/><br/><label>Phone</label><input type='text' value='"+data.Phone+"' id='phone' style='width:95%' name='phone'/><label>Like</label><input type='text' value='"+data.Like+"' id='like' style='width:95%' name='Like'/><br/><br/><label>Business Id</label><input type='text' value='"+data.businessId+"' id='businessId' style='width:95%' name='businessId'/><br/><br/><label>Rating</label><input type='text' value='"+data.rating+"' id='rating' style='width:95%' name='rating'/><br/><br/><label>Upload Image(Optimal size of image is 600px X 600px)</label><input type='file' value='"+data.Path+"'  name='fileToUpload[]' id='fileToUpload' multiple/><br/><img src='"+im+"' style='width:150px;height:150px;'/><br/><br/><label>Upload profile Image(Optimal size of image is 600px X 600px)</label><input type='file' value='"+data.profileimage+"'  name='ProfileToUpload' id='ProfileToUpload'/><br/><img src='"+img+"' style='width:150px;height:150px;'/><input type='hidden' class='form-control' name='s_time' id='s_time'/><input type='hidden' class='form-control' name='bday' id='bday' /><input type='hidden' class='form-control' name='s_time' id='s_time'/><input type='hidden' class='form-control' name='usr_time' id='usr_time'/><input type='hidden' id='G_lon' name='G_lon' /><input type='hidden' id='G_lat' name='G_lat' /><input type='hidden' id='F_lon' name='F_lon' /><input type='hidden' id='F_lat' name='F_lat' /><input type='hidden' id='VenueId' name='VenueId' /><input type='hidden' id='PlaceId' name='PlaceId' /><input type='hidden' id='Status' value='false' name='Status' /><input type='hidden' id='Phone' name='Phone' /><input type='hidden' id='Website' name='Website' /><input type='hidden' id='Discount' name='Discount' /><input type='hidden' id='Price' name='Price' /><input type='hidden' id='type' name='type' value='Place' />";
                html = html + '<br/><br/><br/><div class="jqibuttons"><button name="jqi_0_buttonsubmit" id="jqi_0_buttonsubmit" value="true" class="jqidefaultbutton">Save</button><a href="'+cancel+'" name="jqi_0_buttonCancel" id="jqi_0_buttonCancel" value="false" class="jqidefaultbutton">Cancel</a></div></form>';
                
                var show_delete_media_box=[{
                            title:"Edit places",
                            html: html,
                            buttons:{},
                            submit: function(e,v,m,f){
                                if(v==true)
                                {                                   
                                    //edit_gallery(data.SrNumber);                                  
                                }
                            
                            }
                        }];
                $.prompt(show_delete_media_box);
                 $( 'textarea#about' ).ckeditor();
            }

          
            function edit_gallery(id)
            {
                var F_Lat_Long = $('#F_Lat_Long').val();
                var starting_location = $('#starting_location').val();

                var name = $('#u_name').val();
                var address = $('#add').val();
                var category = $('#Category').val();
                var about = $('#about').val();
                var Foresquare_Lattitude= $('#f_lattitude').val();
                var Foresquare_Longitude = $('#f_longitude').val();
                var phone = $('#phone').val();
                var latlng_H = $('#latlng_H').val();
                var latlng_L = $('#latlng_L').val();
                var Image = $('#fileToUpload').val();

                $.ajax({
                        url: '<?php echo site_url('site/edit_Places');?>',      
                        type: 'POST',
                        data: {
                                'Id' : id,
                                'F_Lat_Long':F_Lat_Long,
                                'starting_location':'starting_location',
                                'name' : name ,
                                'address': address,
                                'category': category,
                                'about': about,
                                'Foresquare_Lattitude': Foresquare_Lattitude,
                                'Foresquare_Longitude': Foresquare_Longitude,
                                'phone': phone,
                                'latlng_H' : latlng_H,
                                'latlng_L' : latlng_L,
                                'Image' : Image
                              },
                        success: function(resp) {
                            //thiss.parentNode.previousSibling.innerHTML = interest;
                            //window.location.reload();
                            //console.log(resp);    
                        }
                });
            }

            var query="";
            var ll="";
            var near_by="";
            var limit="";
            var redius="";
            var client_id="";
            var client_secret="";
            
            $(document).on('keyup', "#starting_location",function () {

            //google.maps.event.addDomListener(window, 'load', function () {
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
                    console.log('if');
                    var abc=JSON.parse(resp);
                    console.log(abc);
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
           

            

              $(document).on('keyup', "#place_location",function () {
                document.getElementById("F_Lat_Long").value = "";
                document.getElementById("phone").value = "";
                document.getElementById("like").value = "";

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
                    $("#place_location" ).autocomplete({
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
            });

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
                    var phone ="";
                    var time = "";
                    var Url = "";

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
                       like  = venue.likes.count;
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
                    document.getElementById('phone').value=phone;
                    document.getElementById('like').value=like;
                    document.getElementById('Website').value=Url;
                });
            }


        
        
    </script>
    </head>
    <body>
        
        
        <div class="container-fluid">
          <div class="row-fluid">
           
                <div id="content" class="span10" style="width:100%">
                <div class="box span12">
                    <div class="box-header well" data-original-title>
                        <h2><i class="icon-user"></i> View places</h2>
                        <a href="<?php echo site_url('site/SharedEvents');?>" class ="btn btn-info" style="margin-left:71%"><i class="icon-edit icon-white"></i>Add Places</a>      
                        <a id="actionButton" style="float:right;" class="btn btn-info del_selected"><i class="icon-trash icon-white"></i>Delete selected item</a>                                        
                    </div>
                    <div class="box-content">
                    
                    
                    <table class="table table-striped table-bordered bootstrap-datatable datatable" id="places_view">
                    
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
            $(".ob-icon-only", "#places_view").parent().css("width", 10);
            function filterAutoCompleteDataSource(e) {
              var gridFilter = e.sender.dataSource.filter();
              e.sender.element.find(".k-autocomplete input").data("kendoAutoComplete").dataSource.filter(gridFilter);
            }
            
            $(document).ready(function() 
            {
                var dataSource = new kendo.data.DataSource({
                transport: {
                    read:{
                        url: "<?php echo site_url('site/viewPlaces_kendo');?>",                            
                        dataType: "json",
                        }
                },
                batch: true,
                pageSize: 100,
                serverPaging: false,
                schema:{
                    model:{
                        name:{type:"string"}
                    }
                }
            });
            $("#places_view").kendoGrid({
                dataSource: dataSource,
                dataBound: function(e) {
                $(".checkbox").bind("change", function(e) {
                            var grid = $("#places_view").data("kendoGrid");
                            var row = $(e.target).closest("tr");
                            var data = grid.dataItem(row);
                            });
                },
            
            height:500,
                noRecords: true,
                messages: {
                    noRecords: "There is no data on current page"
                },
                sortable: true,
                resizable: true,
                pageable: {
                    refresh: true,
                    pageSizes: ['all',5, 10, 20,50,100,150],
                    buttonCount: 5
                },
                editable:"inline",
                columns:[
                {field:"SrNumber",hidden:true},
                {field:"about",hidden:true},
                {field:"SrNumber",title:"Select",width: "60px",filterable:false,template: "<input name='SrNumber' class='checkbox' type='checkbox' data-bind='checked: SrNumber' #= SrNumber ? checked='' : '' #/>" },
                {field:"name",title:"Place name",width: "140px",filterable: {
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
                    },width: "220px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                    // {field:"price",title:"Price",filterable:false,width: "140px"},
                    // {field:"date",title:"Date",filterable:false,width: "120px"},
                    // {field:"startTime",title:"Start-time",filterable:false,width: "120px"},
                    // {field:"endTime",title:"End-time",filterable:false,width: "120px"},
                    {field:"abt",title:"About", encoded: false ,filterable:false,width: "220px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                    //{field:"Reviews",title:"Reviews",filterable:false,width: "120px"},
                    {field:"Phone",title:"Phone",filterable:false,width: "120px",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},  
                    {field:"Category",title:"Tag",filterable: {
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
     //               {field:"",title:"Profile image",width: "140px",filterable:false, template: "<img src='https://s3.amazonaws.com/retail-safari/1485265401.sea-wallpaper-hd-6.jpg'>",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
       //             {field:"",title:"Image",width: "140px",filterable:false, template: "<img src='https://s3.amazonaws.com/retail-safari/1485265725.yellow-tulips-flowers-nature-related-wallpapers-72935.jpg' >",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                    //{field:"Website",title:"Website",filterable:false,width: "120px"},    
                    // {field:"",title:"Profile image",width: "140px",filterable:false, template: "<img src='http://127.0.0.1/xplor/images/16st1.jpg' >",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                 //    {field:"",title:"Image",width: "140px",filterable:false, template: "<img src='http://127.0.0.1/xplor/images/mahabaleshwar2.jpg' >",attributes: {style: "font-size: 14px"},headerAttributes: {style: "font-size: 14px"}},
                    //{field:"Path",title:"Image",width: "140px",filterable:false, template: "#if(Path!=null){# <img src='http://127.0.0.1/xplor/images/mahabaleshwar2.jpg' > #}else{# No image #}#"},

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
                        title: "", width: "85px",headerAttributes: {style: "font-size: 14px"}
                    }

                    
                    
                ],
                filterable: {
                    mode: "row"
                }
            });
        

        
     $("#actionButton").click(function(){
                    var idsToSend = [];
                        
                    var grid = $("#places_view").data("kendoGrid")
                    var ds = grid.dataSource.view();
                   
                    for (var i = 0; i < ds.length; i++) {
                        var row = grid.table.find("tr[data-uid='" + ds[i].uid + "']");
                                    var checkbox = $(row).find(".checkbox");
                      
                        if (checkbox.is(":checked")) {
                          idsToSend.push(ds[i].SrNumber);
                        }
                    }
                    
                    if(idsToSend==""){
                        alert("Please select a item!");
                        return;
                    }

                    if(confirm("Please confirm to delete the item!"))
                    {
                        var site="<?php echo site_url('admin/places_/deleteMultipleDeals');?>";
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
                 min-width: 13px; 
            }
        
        .jqi{
                margin-left: 0px !important;
            }           
        .k-filtercell{
                width:119%;
            
}   

      </style>
</html>

<style type="text/css">
#siteList img{ width: 100px; height: 75px;}
</style>


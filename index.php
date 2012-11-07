
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Google Maps JavaScript API v3 Example: Heatmap Layer</title>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=visualization"></script>
    <script src="http://code.jquery.com/jquery-1.8.2.min.js"></script>
    <script>
      // Adding 500 Data Points
      var map, pointarray, heatmap;
	  
	 
	  if (navigator.geolocation){navigator.geolocation.getCurrentPosition(initialize);}
	  else{x.innerHTML="Geolocation is not supported by this browser.";}
	  
      function initialize(position, lat, lng) {
	 
	 	if (lat){
			var pos = new google.maps.LatLng(position, lat);
			var mapOptions = {
					zoom: 13,
					center: new google.maps.LatLng(position,  lat),
					mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		}
		else {
	 		var pos = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
			var mapOptions = {
					zoom: 13,
					center: new google.maps.LatLng(position.coords.latitude,  position.coords.longitude),
					mapTypeId: google.maps.MapTypeId.ROADMAP
			};
		}
		
        map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
		
		getData();
		
		heatmap.setMap(map);
		
		var marker = new google.maps.Marker({
				position: pos,
				map: map,
				title: "User location"
		});
		google.maps.event.addListener(marker, 'click', function() {
				infowindow.open(map,marker);
		});
		
		google.maps.event.addListener(map, 'click', function( event ){
			$(".lat").val(event.latLng.lat());
			$(".lng").val(event.latLng.lng());
			$(".zoom").val(map.getZoom());
			
			map.setCenter(event.latLng);
			//$(".center_lat").val(c.lat());
			//$(".center_lng").val(c.lng());
	    });
		
      }

	  function getData() {
	  
	  		var b_data = [];
			
			//map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
			
	  		$.getJSON("data.php", 
			{
				format: "json"
			},
				function(data) {
					$.each(data,function(i,val){
						b_data.push( new google.maps.LatLng(val.latitude, val.longitude));
				});
			});
			
			
			pointArray = new google.maps.MVCArray(b_data);
			heatmap = new google.maps.visualization.HeatmapLayer({
				 data: pointArray
			});
			
	  }


      function changeGradient() {
        var gradient = [
          'rgba(0, 255, 255, 0)',
          'rgba(0, 255, 255, 1)',
          'rgba(0, 191, 255, 1)',
          'rgba(0, 127, 255, 1)',
          'rgba(0, 63, 255, 1)',
          'rgba(0, 0, 255, 1)',
          'rgba(0, 0, 223, 1)',
          'rgba(0, 0, 191, 1)',
          'rgba(0, 0, 159, 1)',
          'rgba(0, 0, 127, 1)',
          'rgba(63, 0, 91, 1)',
          'rgba(127, 0, 63, 1)',
          'rgba(191, 0, 31, 1)',
          'rgba(255, 0, 0, 1)'
        ]
        heatmap.setOptions({
          gradient: heatmap.get('gradient') ? null : gradient
        });
      }

      function changeRadius() {
        heatmap.setOptions({radius: heatmap.get('radius') ? null : 20});
      }

      function changeOpacity() {
        heatmap.setOptions({opacity: heatmap.get('opacity') ? null : 0.2});
      }
	  
	  
	  $(document).ready(function() {
		  $(".add").click(function() {
		  
		  	  $.ajax({type: "GET",
			  url: "data.php",
			  data: {
				"mode" : "add",
				"lng" : $(".lng").val(),
				"lat" : $(".lat").val()
			  },
			  success: function(msg){
			  }
			  });
			
			  //initialize($(".zoom").val(), $(".center_lat").val(), $(".center_lng").val());
			 
		  });
		  
		  
		  $(".address").click(function() {
		  
		  		var add_value = $(".addres_val").val();
				
				var mygc = new google.maps.Geocoder();
				mygc.geocode({'address' : add_value}, function(results, status){
						lat = results[0].geometry.location.lat();
						lng = results[0].geometry.location.lng();
						initialize(lat, lng);
				});
		  });
	  });


    </script>
  </head>

  <body onLoad="initialize()">
   <div style="width:960px; margin:0 auto;">
   
    	Address: <input type="text" size="100" name="address" class="addres_val" >
        <input type="submit" class="address" value="Search"> <br/>
    
    
    <input type="hidden" value="add" name="mode" />
    <input type="text" style="width:100px;" class="lat" name="lat"/>
    <input type="text" style="width:100px;" class="lng" name="lng"/>   
    <input type="text" style="width:100px;" class="zoom" name="zoom"/>
    <input type="hidden" style="width:100px;" class="center_lat" name="center_lat"/>
    <input type="hidden" style="width:100px;" class="center_lng" name="center_lng"/>
    <input type="submit" class="add" value="Add">

    <span class="success" style="display:none;"><font color="#006600">Added</font></span>
    
    
        <div id="map_canvas" style="height: 600px; width: 800px;"></div>
        <button onClick="toggleHeatmap()">Toggle Heatmap</button>
        <button onClick="changeGradient()">Change gradient</button>
        <button onClick="changeRadius()">Change radius</button>
        <button onClick="changeOpacity()">Change opacity</button>
     </div>
  </body>
</html>

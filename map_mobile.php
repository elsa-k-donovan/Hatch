

<!DOCTYPE html>
<html>
<head>

	<title>Mobile Map</title>

	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!--<link rel="shortcut icon" type="image/x-icon" href="docs/images/favicon.ico" />-->
  <script src = "jquery/jquery-3.4.1.js"></script>

  <script src='https://api.mapbox.com/mapbox.js/v3.2.1/mapbox.js'></script>
  <link href='https://api.mapbox.com/mapbox.js/v3.2.1/mapbox.css' rel='stylesheet' />
	<script src='https://api.tiles.mapbox.com/mapbox-gl-js/v1.5.0/mapbox-gl.js'></script>
	<link href='https://api.tiles.mapbox.com/mapbox-gl-js/v1.5.0/mapbox-gl.css' rel='stylesheet' />

	<link href='css/main.css' rel='stylesheet' />

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="css/L.Control.Locate.scss" />
	<script src="L.Control.Locate.js" charset="utf-8"></script>



  <script type="text/javascript">
  window.onload = function(){

		//Use delay to time how long the loader will be
		$(".loaderWrapper").delay(3000).fadeOut("slow");

    console.log("page is loaded");

    L.mapbox.accessToken = 'pk.eyJ1IjoiZWtkb25vdmFuIiwiYSI6ImNrMmM4cXlmZTF0cGYzbWxzNmlzdzl2ZGgifQ.RtgaQC8k5_P0bwjPEXXv8A';
		let sampleMap = L.mapbox.map('map').locate({setView: true, maxZoom: 14});


		function onLocationFound(e) {
	    // var radius = e.accuracy;
			console.log("User coordinates: " + e.latlng.lat + " " +e.latlng.lng);

			localStorage.setItem('currentMarkerLat', e.latlng.lat);
			localStorage.setItem('currentMarkerLong', e.latlng.lng);

		}

		sampleMap.on('locationfound', onLocationFound);


		var styleURL = 'mapbox://styles/ekdonovan/ck2caxx670bqd1cs3ssqssoa8';

		L.mapbox.styleLayer(styleURL).addTo(sampleMap);

    // Need to sign up with mapbox.com for an access token
    let myToken =  "pk.eyJ1IjoiZWtkb25vdmFuIiwiYSI6ImNrMmM4cXlmZTF0cGYzbWxzNmlzdzl2ZGgifQ.RtgaQC8k5_P0bwjPEXXv8A";

		//Declaring variables
		let markers = [];
		let editBool = false;

	$.get("getData.php", function(response)
	{
		console.log("get response: " + response);
		let parsedJSON = JSON.parse(response);
		console.log("parsed JSON" + parsedJSON);
		console.log("parsedJSON length: " + parsedJSON.length);
		//displayResponse(parsedJSON);

		for (let i = 0; i < parsedJSON.length; i++){
		let lat;
		let pieceID;
		let long;
		let name;
		let id;
		let desc;
		let image;

			$.each(parsedJSON[i], function (index, value) {

			if (index == "latitude"){
					lat = value;
					console.log("lat: " + lat);
			}
			else if(index == "pieceID"){
				pieceID = value;
				console.log("ID: " + pieceID);
			}
			else if (index == "longitude"){
					long = value;
					console.log("longitude: " + long);
			}
			else if (index == "name"){
				name = value;
				console.log("name: " + name);
			}
			else if (index == "description"){
				desc = value;
				console.log("description: " + desc);
			}
			else if (index == "image"){
				image = value;
				console.log("image path: " + image);
			}
			else {
				console.log("none");
			}
			})


			let newMarker = new L.Marker([lat, long], {icon: drawIcon}).addTo(sampleMap).on("click",function(e){markerOnClick(this,e,image,pieceID,lat,long,name,desc)});

			//push new Marker onto the array
			markers.push(newMarker);


		//	console.log(name + " at: "+lat+", "+long+ " has been added to map.");

	} //ParsedJSON.length for loop


	}); //GETDATA


	function markerOnClick(target,e,thisImage,thisID,thisLat,thisLong,thisName,thisDesc) {

	 localStorage.setItem('currentID', thisID);
 	 //localStorage.setItem('currentMarkerLat', thisLat);
 	 //localStorage.setItem('currentMarkerLong', thisLong);
 	 localStorage.setItem('currentName', thisName);
 	 localStorage.setItem('currentDesc', thisDesc);

 	editBool = true;
 	console.log("Marker " + thisName + " has been clicked.");

	//attach pop-up to marker
	if (editBool == true){
 			target.bindPopup("<b>"+thisName+"</b><img id='popImg' src='"+thisImage+"'/><br>"+thisDesc);
		}
	else {
		sampleMap.closePopup();
	}
 	toggleEdit();
 }; // ON CLICK FUNCTION



	//CUSTOM MARKERS
	var drawIcon = L.icon({
    iconUrl: 'draw_icon.png',
    shadowUrl: 'draw_shadow.png',

    iconSize:     [38, 95], // size of the icon
    shadowSize:   [50, 64], // size of the shadow
    iconAnchor:   [22, 94], // point of the icon which will correspond to marker's location
    shadowAnchor: [4, 62],  // the same for the shadow
    popupAnchor:  [-3, -76] // point from which the popup should open relative to the iconAnchor
});


/*Marker with custom variables
customHatchMarker = L.Marker.extend({
   options: {
		 	thisID: 'data',
      thisName: 'Custom data!',
      thisLat: 'More data!',
			thisLong: 'data',
			thisDesc: 'desc'
   }
});*/


let polyline;

function drawLine(){

		var latlngs = Array();

		for (var i = 0; i < markers.length; i++){
			//console.log(markers[i].getLatLng());
			latlngs.push(markers[i].getLatLng());
		}

		//console.log("created Latlng array");

		polyline = L.polyline(latlngs, {color: 'black', dashArray: '5,10'}).addTo(sampleMap);

		console.log("Drew polyline connecting markers.")

}

//	let blackBox;
	let rect1;


// set up an event listener ...
  sampleMap.on('click', onMapClick);

	let showPath = false;
	let switchToggle = document.getElementById('thisSwitch');

	switchToggle.addEventListener('click', onSwitchClick);


	/* WHY DOES THIS GET CALLED TWICE */
	function onSwitchClick(event) {
		console.log("clicked");
		showPath = !showPath;
		console.log("showPath:" + showPath);
		createPath();
	}


	function createPath(){
		if (showPath == false){
			//rect1.remove();
			console.log("createPath() function.");
			polyline.remove();
			boxExist = false;

			L.mapbox.styleLayer(styleURL).remove(sampleMap);
			console.log("removed layer");

			styleURL = 'mapbox://styles/ekdonovan/ck2caxx670bqd1cs3ssqssoa8';
			L.mapbox.styleLayer(styleURL).addTo(sampleMap);

			console.log("change style");
			//sampleMap._onResize();

		}
		else if (showPath == true) {
		//	drawBox();
			drawLine();

			L.mapbox.styleLayer(styleURL).remove(sampleMap);
			//L.mapbox.styleLayer.remove();
			console.log("removed layer");

			styleURL = 'mapbox://styles/ekdonovan/ck3c3x6ij2q0h1ckkdygeeupq';
			L.mapbox.styleLayer(styleURL).addTo(sampleMap);

			console.log("change style");
			//sampleMap._onResize();
		}
	}

//change EditBool if you click elsewhere on map
function onMapClick(event) {
			editBool = false;
 	    console.log("Closing edit bar.");
			toggleEdit();
		}


//	Hide/show Edit Bar
function toggleEdit(){
		if (editBool == false){
			document.getElementById("addEditBar").style.display = "none";
		//	map.removeLayer(L.rectangle);
		}
		else {
			document.getElementById("addEditBar").style.display = "flex";
		}
}

  }
  </script>
</head>

<body>

	<!--Loading GIF before page load -->
	<div class="loaderWrapper">
		<span class="loader">
			<img src = "image/hatch_roto2.gif" />
		</span>
	</div>

  <div id = "map">
</div>


<!-- borrowed from https://www.w3schools.com/howto/tryit.asp?filename=tryhow_css_switch-->
<label id="switch">
  <input type="checkbox" checked>
  <span id="thisSwitch" class="slider round"></span>
</label>
<!-- end of -->

<div id="barContainer">
	<div id = "addEditBar"><a href="editPins.php"><h1> Edit Pin </h1></a></div>
	<div id = "addPinBar"><a href="addPins.php"><h1> Add Pin </h1></a></div>
</div>

</body>


</head>
</html>

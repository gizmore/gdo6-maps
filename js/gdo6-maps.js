"use strict";

GDO = GDO || {};
GDO.Maps = {};
GDO.Maps.picker = null;
GDO.Maps.pickedPosition = function(id, inputid) {
	console.log('GDO.Maps.pickedPosition()', id, GDO.Maps.picker);
	id = id.substrTo('_picker');
	let p = GDO.Maps.picker.position;
	let json = ''+ p.lat() +', ' + p.lng() + '';
	document.getElementById(inputid).value = json;
};

GDO.Maps.openDialog = function(id) {
	console.log('Maps.openDialog()', id);
	GDO.openDialog(id);
	GDO.Maps.picker = null;
	var map = GDO.Maps.getMaps('#'+id+' .maps-canvas');
    google.maps.event.addListener(map, 'click', function(event) {                
        let clickedLocation = event.latLng;
        if (!GDO.Maps.picker){
            GDO.Maps.picker = new google.maps.Marker({
                position: clickedLocation,
                map: map,
                draggable: true
            });
        } else {
            GDO.Maps.picker.setPosition(clickedLocation);
        }
    });

	navigator.geolocation.getCurrentPosition(function (position) {
		let c = position.coords;
		let pos = new google.maps.LatLng(c.latitude, c.longitude);
		map.setCenter(pos);
	});
};

GDO.Maps.closeDialog = function(id, inputid) {
	console.log('Maps.closeDialog()', id);
	if (GDO.DIALOG_RESULT == 'ok') {
		if (GDO.Maps.picker) {
			GDO.Maps.pickedPosition(id, inputid);
		}
	}
};

GDO.Maps.getMaps = function(selector) {
	let canvas = document.querySelector(selector);
	let map = new google.maps.Map(canvas, {
		center: { lat: 53.066008, lng: 9.503834 },
		zoom: 8
	});
	return map;
};

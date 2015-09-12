var map;
var api_url_all = 'http://localhost/api/cars/all';
var api_url_nearest = 'http://localhost/api/cars?location=';
var nlayers = 0;

function init(){
    map = new ol.Map({
        target:'map',
        renderer:'canvas',
    	view: new ol.View({    		
    		center: ol.proj.transform([-0.1973237, 51.5412081], 'EPSG:4326', 'EPSG:3857'),
    		zoom:14
    	})
    });
    
    var newLayer = new ol.layer.Tile({
    source: new ol.source.OSM()});

	map.addLayer(newLayer);	
	
	map.on('singleclick', function(evt){
			
		coordsArr = ol.proj.transform([evt['coordinate'][0], evt['coordinate'][1]], 'EPSG:3857', 'EPSG:4326');			
		
		if (nlayers>0) {
			removeTopLayers();
		}
		
		var point = JSON.parse(JSON.stringify([{'longitude':coordsArr[0], 'latitude':coordsArr[1]}]));		
		drawPoints(point, '#dc3900');
		
		callWebservice(api_url_nearest + coordsArr[1]+','+coordsArr[0], '#00ca00');
		
     })
}


function removeTopLayers() {
	
    var layers = map.getLayers();
    
    for (i=0; i<=nlayers+1; i++) {
		layers.pop();
	}
}

function drawPoints(points, color) {

	for (i=0; i<points.length; i++) {
		markerLayer = new ol.layer.Vector({
			source: new ol.source.Vector({
				features: [
					new ol.Feature({
						geometry: new ol.geom.Circle(ol.proj.transform([points[i]['longitude'], points[i]['latitude']], 'EPSG:4326', 'EPSG:3857'), 50)
					})
				]
			}),
			projection: "EPSG:3857",
			style: new ol.style.Style({
				stroke: new ol.style.Stroke({
					color: '#000000',
					width: 1
				}),
				fill: new ol.style.Fill({
					color: color
				})
			}),
			visible: true   
		});

		map.addLayer(markerLayer);			
	}

	if (color=='#00ca00') {
		nlayers = points.length;
	}
	
}


function callWebservice(url, color) {

	$.ajax({
		type: 'GET',	
		contentType: "application/json; charset=utf-8",	
		datatype : 'json',
		url: url,				
		success: function(data, textStatus){ var obj = JSON.parse(JSON.stringify(data)); drawPoints(obj, color);},
		error:  function(jqXHR, textStatus, errorThrown){ }
		 
	});	
	
}

$(document).ready(function() {
	
	callWebservice(api_url_all, '#0000FF');

});

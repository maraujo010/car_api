var map;

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
   
}


function drawPoints(points) {

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
					color: '#0000FF'
				})
			}),
			visible: true   
		});

		map.addLayer(markerLayer);			
	}

}

function callWebservice(url) {

	$.ajax({
		type: 'GET',	
		contentType: "application/json; charset=utf-8",	
		datatype : 'json',
		url: url,				
		success: function(data, textStatus){ var obj = JSON.parse(JSON.stringify(data)); drawPoints(obj);},
		error:  function(jqXHR, textStatus, errorThrown){ }
		 
	});	
	
}

$(document).ready(function() {
	
	callWebservice('http://localhost/api/cars/all');

});

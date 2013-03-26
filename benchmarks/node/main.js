// Author: Jan Mochnak <janmochnak@icloud.com>
// (c) 2013

var geolib = require('geolib');
var util = require('util');

var polygons = require('./polygons');
var data = require('./data');

for(var c = 0; c < 5; c++) {

	var results = [];

	console.time('bench');

	for (var i = 0; i < polygons.length; i++) {
		var distance =0
			time = 0;

		for (var j = 0; j < data.length; j++) { // data
			var inPolygon = geolib.isPointInside({latitude: data[j][0], longitude: data[j][1]}, polygons[i][1]);
			if (inPolygon) {
				distance += data[j][2];
				time += data[j][3];
			}
		}
		results.push({polygon: polygons[i][0], distance: distance, time: time});
	}

	console.log(util.inspect(results));
	console.timeEnd('bench');
}
'use strict';
angular.module('gdo6').
run(function(GDOPositionSrvc, GDORequestSrvc){
	GDOPositionSrvc.withPosition().then(function(pos){
		console.log('gdo-record-location.js#withPosition', pos);
		var data = {position:sprintf('[%s,%s]', pos.lat, pos.lng)};
		GDORequestSrvc.sendGDO('Maps', 'Record', data, true);
	});
});

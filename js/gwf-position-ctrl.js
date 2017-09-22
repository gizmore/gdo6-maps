'use strict';
angular.module('gdo6').
controller('GDOPositionCtrl', function($scope, GDOPositionSrvc, GDOLocationPicker, GDOErrorSrvc) {

	$scope.data = {
		lat: null,
		lng: null,
	};
	
	$scope.init = function(config) {
		console.log('GDOPositionCtrl.init()', config);
		$scope.config = config;
		$scope.setLatLng(config.lat, config.lng);
		GDOPositionSrvc.start();
	};

	$scope.$on('gdo-position-changed', function($event, position) {
		console.log('GDOPositionCtrl.$on-gdo-position-changed()', position);
		if ($scope.config.defaultCurrent) {
			if ($scope.data.lat === null) {
				$scope.setLatLng(position.lat, position.lng);
			}
		}
	});
	
	$scope.setLatLng = function(lat, lng) {
		console.log('GDOPositionCtrl.setLatLng()', lat, lng);
		$scope.data.lat = lat;
		$scope.data.lng = lng;
		if (lat === null) {
			$scope.data.display = '---';
		} else {
			$scope.data.display = lat + '°;' + lng + '°;';
		}
		setTimeout($scope.$apply.bind($scope), 0);
	};
	
	
	//////////
	// Pick //
	//////////
	$scope.onPick = function() {
		console.log('GDOPositionCtrl.onPick()');
		
		var position = $scope.data.lat ? new google.maps.LatLng({lat:$scope.data.lat, lng:$scope.data.lng}) : null;
		GDOLocationPicker.open(position).then($scope.locationPicked, $scope.locationNotPicked);
	};
	
	$scope.locationPicked = function(latlng) {
		console.log('GDOPositionCtrl.locationPicked()');
		$scope.setLatLng(latlng.lat(), latlng.lng());
	};
	
	$scope.locationNotPicked = function(error) {
		console.log('GDOPositionCtrl.locationNotPicked()', error);
	};
	

});

'use strict';
angular.module('gdo6').
controller('GDOLocationBarCtrl', function($scope, GDOPositionSrvc, GDOLocationPicker, GDOErrorSrvc) {

	$scope.data = {
		position: GDOPositionSrvc.CURRENT,
		fix: false,
		fixLat: null,
		fixLng: null,
	};
	
	//////////
	// Pick //
	//////////
	$scope.onPick = function() {
		console.log('LocationBarCtrl.onPick()');
		GDOLocationPicker.open().then($scope.locationPicked, $scope.locationNotPicked);
	};
	
	$scope.locationPicked = function(latlng) {
		console.log('LocationBarCtrl.locationPicked()');
		GDOPositionSrvc.startPatching(latlng.lat(), latlng.lng());
		$scope.data.fix = true;
		$scope.data.fixLat = latlng.lat();
		$scope.data.fixLng = latlng.lng();
	};
	
	$scope.locationNotPicked = function(error) {
		console.log('LocationBarCtrl.locationNotPicked()', error);
	};
	
	$scope.toggleFixture = function() {
		console.log('LocationBarCtrl.toggleFixture()');
		if ($scope.data.fix) {
			if ($scope.data.fixLat) {
				GDOPositionSrvc.startPatching($scope.data.fixLat, $scope.data.fixLng);
			}
			else {
				$scope.data.fix = false;
			}
		}
		else {
			GDOPositionSrvc.stopPatching();
		}
	};
	
	$scope.$on('gdo-position-changed', function($event, position) {
		console.log('LocationBarCtrl.$on-gdo-position-changed()', position);
		$scope.data.position = position;
		setTimeout($scope.$apply.bind($scope), 1);
	});

	///////////
	// Probe //
	///////////
	$scope.onProbe = function() {
		console.log('LocationBarCtrl.onDetect()');
		GDOPositionSrvc.probe().then($scope.detected(), $scope.probeFailed);
	};
	
	$scope.detected = function(position) {
		console.log('LocationBarCtrl.detected()', position);
		setTimeout($scope.$apply.bind($scope), 1);
	};
			
	$scope.probeFailed = function(error) {
		console.log('LocationBarCtrl.probeFailed()', error);
		GDOErrorSrvc.showError(error.message, "Position Error");
	};

});

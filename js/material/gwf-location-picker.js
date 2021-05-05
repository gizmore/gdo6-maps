'use strict';
angular.module('gdo6').
service('GDOLocationPicker', function($q, $mdDialog, GDOMapUtil) {
	
	var LocationPicker = this;
	
	LocationPicker.open = function(initPosition, text) {

		var defer = $q.defer();
		
		function DialogController($scope, $mdDialog, initPosition, text) {
			
			console.log('DialogController()', initPosition);
			$scope.data = {
				marker: null,
				initPosition: initPosition,
				text: text,
				map: null,
			};
			$scope.pickPosition = function() {
				$mdDialog.hide();
				if ($scope.data.marker) {
					defer.resolve($scope.data.marker.getPosition());
				}
				else {
					defer.reject();
				}
			};
			$scope.closeDialog = function() {
				$mdDialog.hide();
				defer.reject();
			};
			$scope.clicked = function(event) {
				$scope.setMarker(event.latLng);
			};
			
			$scope.setMarker = function(latLng) {
				if (!$scope.data.marker) {
					$scope.data.marker = new google.maps.Marker({
						position: latLng,
						map: $scope.data.map,
						title: $scope.data.text||'Your Position',
						label: $scope.data.text||'Your Position',
						draggable: true,
					});
					$scope.data.map.setCenter(latLng);
				}
				$scope.data.marker.setPosition(latLng);
			};
			
			setTimeout(function() {
				if (!$scope.data.map) {
					$scope.data.map = GDOMapUtil.map('gdo-dialog-map');
					$scope.data.map.addListener('click', $scope.clicked);
					if ($scope.data.initPosition) {
						$scope.setMarker($scope.data.initPosition);
					}
				}
			}, 1);
		}
		
		$mdDialog.show({
			templateUrl: window.GDO_WEB_ROOT + 'GDO/Maps/js/material/tpl/gwf-location-picker.html',
			locals: {
				initPosition: initPosition,
				text: text,
			},
			controller: DialogController,
			onComplete: function() {
			}
		});
		
		return defer.promise;
	};

});

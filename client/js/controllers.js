'use strict';

angular.module('busTimeApp.controllers', [])
    .controller('MainCtrl', ['$scope', '$rootScope', '$window', '$location', function ($scope, $rootScope, $window, $location) {
        $scope.slide = '';
        $scope.lat = null;
        $scope.lng = null;

        $rootScope.back = function() {
          $scope.slide = 'slide-right';
          $window.history.back();
        }
        $rootScope.go = function(path){
          $scope.slide = 'slide-left';
          $location.url(path);
        }

   	$rootScope.setPosition = function (position) {
            $scope.lat = position.coords.latitude;
            $scope.lng = position.coords.longitude;
            //$scope.accuracy = position.coords.accuracy;
        }
 
        $rootScope.showError = function (error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    $rootScope.error = "User denied the request for Geolocation."
                    break;
                case error.POSITION_UNAVAILABLE:
                    $rootScope.error = "Location information is unavailable."
                    break;
                case error.TIMEOUT:
                    $rootScope.error = "The request to get user location timed out."
                    break;
                case error.UNKNOWN_ERROR:
                    $rootScope.error = "An unknown error occurred."
                    break;
            }
        }

 	 $rootScope.getLocation = function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition($rootScope.setPosition, $rootScope.showError);
            }
            else {
                $rootScope.error = "Geolocation is not supported by this browser.";
            }
		}
		
	 $rootScope.getLocation();
    }])
    .controller('MainMenuCtrl', ['$scope', 'MainMenu', function ($scope, MainMenu) {
        $scope.main = MainMenu.query();
    }])
    .controller('ServicesCtrl', ['$scope', 'Services', function ($scope, Services) {
	//console.log("lat:" + $scope.lat + "----long" + $scope.lng); 
	if($scope.lat != null && $scope.lng != null)
	{
		$scope.services = Services.query({lat:$scope.lat,lng:$scope.lng});
	}
	else
	{ 
        	$scope.services = Services.query();
	}
    }])	
    .controller('StopsCtrl', ['$scope', 'Stops', function ($scope, Stops) {
	if($scope.lat != null && $scope.lng != null)
	{
		$scope.stops = Stops.query({lat:$scope.lat,lng:$scope.lng});
	}
	else
	{
        	$scope.stops = Stops.query();
	}
    }])
    .controller('ServiceStopsCtrl', ['$scope', '$routeParams','ServiceStops', function ($scope,$routeParams,ServiceStops) {
	$scope.service = ServiceStops.query({_id:$routeParams.serviceId});
    }])
    .controller('StopServicesCtrl', ['$scope', '$routeParams','StopServices', function ($scope,$routeParams,StopServices) {
	$scope.stop = StopServices.query({_id:$routeParams.stopId});
    }]);

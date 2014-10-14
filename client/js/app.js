'use strict';

angular.module('busTimeApp', [
    'ngTouch',
    'ngRoute',
    'ngAnimate',
    'busTimeApp.controllers',
    'busTimeApp.restServices'
]).
config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/main', {templateUrl: 'partials/main.html', controller: 'MainMenuCtrl'});
	$routeProvider.when('/services', {templateUrl: 'partials/services.html', controller: 'ServicesCtrl'});
	$routeProvider.when('/services/:serviceId', {templateUrl: 'partials/services_stops.html', controller: 'ServiceStopsCtrl'});	
	$routeProvider.when('/stops', {templateUrl: 'partials/stops.html', controller: 'StopsCtrl'});	
	$routeProvider.when('/stops/:stopId', {templateUrl: 'partials/stop_services.html', controller: 'StopServicesCtrl'});		
    $routeProvider.otherwise({redirectTo: '/main'});
}]);

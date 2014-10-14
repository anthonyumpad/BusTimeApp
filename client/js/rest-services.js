'use strict';

(function () {

    var main = [
				{"id":"services","text":"Bus Service"},
				{"id":"stops","text":"Bus Stop"}
	       ];

	angular.module('busTimeApp.restServices', ['ngResource'])
        .factory('MainMenu', [
            function () {
                return {
                    query: function () {
                        return main;
                    },
                }

            }])
		.factory('Services', ['$resource',
			/* need to transform the REST object from PHP to array for easier use of filtering in Angular JS */
			function ($resource) {
			return $resource('https://ec2-54-69-212-116.us-west-2.compute.amazonaws.com/busTimeAppApi.php/services', {}, { query: {method:'GET',cache:true,isArray:true,
				transformResponse: function(data, headers){
					var services = [];
					var sArr = JSON.parse(data);
					angular.forEach(sArr,function(key,object)
					{
							var tmp = {"_id":object};
							services.push(tmp);
					});
					return services;
					}
				}
			});
		  }])
		.factory('Stops', ['$resource',
			/* need to transform the REST object from PHP to array for easier use of filtering in Angular JS */
			function ($resource) {
            return $resource('https://ec2-54-69-212-116.us-west-2.compute.amazonaws.com/busTimeAppApi.php/stops', {},{ query: {method:'GET',cache:true,isArray:true,
				transformResponse: function(data, headers){
					var stops = [];
					var sArr = JSON.parse(data);
					angular.forEach(sArr,function(key,object)
					{
							var dist = 0;
							if(sArr[object].distFromLocation)
							{
								dist = parseFloat(sArr[object].distFromLocation); 
							}
							var tmp = {"_id":object,"name":sArr[object].name,"distFromLocation":dist};
							stops.push(tmp);
					});
					return stops;
					}
				}
			});
          }])
		.factory('ServiceStops', ['$resource',
			/* need to transform the REST object from PHP to array for easier use of filtering in Angular JS */
			function ($resource) {
            return $resource('https://ec2-54-69-212-116.us-west-2.compute.amazonaws.com/busTimeAppApi.php/services', {},{ query: {method:'GET',cache:true,isArray:true,
                                transformResponse: function(data, headers){
                                        var serviceStops = [];
                                        var ssArr = JSON.parse(data);
					var tmp = [];
                                        angular.forEach(ssArr.stops,function(key,object)
                                        {
						tmp.push(ssArr.stops[object]);
                                        });
					var tmpObj = {"_id":ssArr._id,"stops":tmp};
					serviceStops.push(tmpObj);
                                        return serviceStops;
                                        }
                                }
                        });
          }])
		.factory('StopServices', ['$resource',
			/* need to transform the REST object from PHP to array for easier use of filtering in Angular JS */
			function ($resource) {
            return $resource('https://ec2-54-69-212-116.us-west-2.compute.amazonaws.com/busTimeAppApi.php/stopservices', {},{ query: {method:'GET',cache:true,isArray:true,
                                transformResponse: function(data, headers){
                                        var stopServices = [];
                                        var stopsSArr = JSON.parse(data);
					var tmp = [];
					angular.forEach(stopsSArr.services,function(value,object)
					{
						tmp.push(stopsSArr.services[object]);
					});
					var tmpObj = {"_id":stopsSArr._id,"name":stopsSArr.name,"services":tmp};
					stopServices.push(tmpObj);
                                        return stopServices;
                                        }
                                }
                        });
          }]);		  
}());

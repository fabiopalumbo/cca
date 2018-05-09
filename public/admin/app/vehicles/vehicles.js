angular.module('vehicles', [])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/vehicles',{
        templateUrl:'app/vehicles/vehicles-list.html',
        controller:'VehiclesListCtrl'
      })
      .when('/vehicles/:share',{
        templateUrl:'app/vehicles/vehicles-list.html',
        controller:'VehiclesListCtrl'
      });
  });
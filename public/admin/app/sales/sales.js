angular.module('sales', [])
  .config(function ($routeProvider) {
    $routeProvider.when('/sales',{
      templateUrl:'app/sales/sales-list.html',
      controller:'SalesListCtrl'
    });
  });
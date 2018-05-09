angular.module('stock', [])
  .config(function ($routeProvider) {
    $routeProvider.when('/stock',{
      templateUrl:'app/stock/stock-list.html',
      controller:'StockListCtrl'
    });
  });
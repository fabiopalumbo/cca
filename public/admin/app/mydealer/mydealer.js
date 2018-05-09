angular.module('mydealer', [])
  .config(function ($routeProvider) {
    $routeProvider.when('/mydealer',{
      templateUrl:'app/mydealer/mydealer-list.html',
      controller:'MydealerListCtrl'
    });
  });
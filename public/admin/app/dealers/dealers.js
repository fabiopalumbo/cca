angular.module('dealers', [])
  .config(function ($routeProvider) {
    $routeProvider.when('/dealers',{
      templateUrl:'app/dealers/dealers-list.html',
      controller:'DealersListCtrl'
    });
  });
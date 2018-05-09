angular.module('queries', [])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/queries',{
        templateUrl:'app/queries/queries-list.html',
        controller:'QueriesListCtrl'
      });
  });
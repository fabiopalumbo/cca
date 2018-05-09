angular.module('permissions', [])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/permissions',{
        templateUrl:'app/permissions/permissions-list.html',
        controller:'PermissionsListCtrl'
      });
  });
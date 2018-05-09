angular.module('profiles', [])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/profiles',{
        templateUrl:'app/profiles/profiles-list.html',
        controller:'ProfilesListCtrl'
      });
  });
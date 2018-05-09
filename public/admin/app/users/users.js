angular.module('users', [])
  .config(function ($routeProvider) {
    $routeProvider.when('/users',{
      templateUrl:'app/users/users-list.html',
      controller:'UsersListCtrl'
    });
  });
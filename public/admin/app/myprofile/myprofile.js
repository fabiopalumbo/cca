angular.module('myprofile', [])
  .config(function ($routeProvider) {
    $routeProvider.when('/myprofile',{
      templateUrl:'app/myprofile/myprofile-list.html',
      controller:'MyprofileListCtrl'
    });
  });
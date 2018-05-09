angular.module('contacts', [])
  .config(function ($routeProvider) {
    $routeProvider.when('/contacts',{
      templateUrl:'app/contacts/contacts-list.html',
      controller:'ContactsListCtrl'
    })
  });
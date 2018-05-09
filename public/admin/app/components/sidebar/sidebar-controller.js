
angular.module('sidebar',['globalFactory']).controller('SidebarCtrl', [
  '$scope','$rootScope','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    GlobalFactory.getUser().done(function(user){
      $scope.loggedUser = user;
    });

  }]);
angular.module('sales').controller('SalesListCtrl', [
  '$scope','$rootScope','pinesNotifications','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,pinesNotifications,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    var initSpinner = true;
    GlobalFactory.getUserAndDealer().done(function(obj) {
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;
      GlobalFactory.spinner(document.getElementById('sales-spinner'), 'spin');

    });
  }]);

angular.module('sales').controller('SalesEditCtrl', [
  '$scope','$rootScope','pinesNotifications','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance',
  function($scope,$rootScope,pinesNotifications,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance) {



  }]);

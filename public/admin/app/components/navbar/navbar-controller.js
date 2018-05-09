
angular.module('navbar',['globalFactory']).controller('NavbarCtrl', [
  '$scope','$rootScope','GlobalFactory','pinesNotifications', '$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,GlobalFactory,pinesNotifications,$location,$modal,$timeout,$route,$resource,$window) {

    $rootScope.selectDealer = function(){
      GlobalFactory.getUser().done(function(user){
        $scope.loggedUser = user;
        $resource('/api/dealer').query(function(dealers){
          if(dealers.length == 1){
            $rootScope.hasMultipleDealers = false;
            $rootScope.currentDealer = dealers[0];
          } else {
            $rootScope.hasMultipleDealers = true;
            $scope.openModal(dealers);
          }
        },function(error){
          if (error.status == 401) {
            pinesNotifications.notify({
              text: 'Acceso no autorizado.',
              type: 'error'
            });
            $location.path("/");
          } else {
            pinesNotifications.notify({
              text: 'Se ha producido un error. Intentlo nuevamente.',
              type: 'error'
            });
          }
        });
      });
    };

    $scope.openModal = function(dealers){
      if(dealers) $scope.dealers = dealers;
      $modal.open({
        templateUrl: '/admin/app/components/navbar/modal-select-dealer.html',
        controller: 'SelectDealerModalCtrl',
        scope: $scope,
        size: 'sm',
        backdrop: 'static',
        keyboard: false,
        resolve: {
        Dealers: function(){
            return _.map(dealers,function(i){return {id:i.id,name:i.name};});
          }
        }
      }).result.then(
        function (result) {
          // console.log(result);
          if(result){
            $rootScope.currentDealer = _.findWhere(dealers,{id:result.id});
            $route.reload();
          }
        }
      );
    };

  }]);

angular.module('navbar').controller('SelectDealerModalCtrl', [
  '$scope','$rootScope','Dealers','$modalInstance',
  function($scope,$rootScope,Dealers,$modalInstance) {

    if(Dealers) $scope.dealers = Dealers;
    $scope.modalInstance = $modalInstance;
    if($rootScope.currentDealer){
      $scope.dealerSelected = $rootScope.currentDealer;
    } else $scope.dealerSelected = {};
    $scope.setDealer = function(a){
      $scope.selectedDealer = a;
    };
    $scope.save = function(){
      $scope.modalInstance.close(_.findWhere(Dealers,{id:$scope.selectedDealer}));
    };


  }]);
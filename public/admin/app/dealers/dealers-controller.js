angular.module('dealers').controller('DealersListCtrl', [
  '$scope','$rootScope','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    GlobalFactory.getUserAndDealer().then(function(obj){
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;

      var dealers = $resource('/api/dealer');
      dealers.query(function(data){
        $scope.dealers = data;
      });

      $scope.openEditDealer = function(id) {
        $scope.selectedDealerId = id;
        $modal.open({
          templateUrl: '/admin/app/dealers/dealers-edit.html',
          controller: 'DealersEditCtrl',
          keybord: false,
          scope: $scope,
          size: 'lg',
          backdrop: 'static'
        }).result.then(
          function () {
            dealers.query(function(data){
              $scope.dealers = data;
            });
          }
        );
      };

      $scope.remove = function(id){
        var remove = $resource('/api/dealer/:id',{id:'@id'});
        remove.remove({id:id},function(data){
          GlobalFactory.notify('La concesionaria se eliminó correctamente.','alert-success');
          $route.reload();
        },function(error){
          if (error.status == 401) {
            GlobalFactory.notify('Acceso no autorizado.','alert-danger');
            $location.path("/");
          }else{
            GlobalFactory.notify('Se ha producido un error. Intentelo nuevamente.','alert-danger');
          }
        });
      };

    });

  }]);
angular.module('dealers').controller('DealersEditCtrl', [
  '$scope','$rootScope','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {

    $scope.object = {};
    $scope.validated = false;
    $scope.modalInstance = $modalInstance;
    $scope.filters = {};

    var dealerId;
    if(dealerId = $scope.$parent.selectedDealerId) {
      var dealers = $resource('/api/dealer/:id',{id:'@id'});
      dealers.get({id:dealerId},function(data){
        $scope.object = data;
        $scope.validated = !!data.validated_by;
      });
      //var data = $scope.$parent.dealers[dealerId-1];
    }
    var regions = $resource('/api/region');
    var regions_cities = $resource('/api/region-city');

    regions.query(function(data){
      $scope.regions = data;
      regions_cities.query(function(data){
        $scope.regions_cities = data;
      },function (error){GlobalFactory.throwAnError(error)});
    },function (error){GlobalFactory.throwAnError(error)});

    $scope.close = function(){
      $modalInstance.close();
    };

    $scope.save = function() {
      var dealer = $scope.object.id? $resource("/api/dealer/"+$scope.object.id) : $resource("/api/dealer");
//    if($scope.validated) $scope.object.validated_by = $rootScope.loggedUser.id;
      $scope.object.validated = $scope.validated;
      dealer.save({dealer:$scope.object},function(data){
        GlobalFactory.notify('La concesionaria se agregó correctamente.','alert-success');
        $modalInstance.close();
      },function (error) {
        if (error.status == 401) {
          GlobalFactory.notify('Acceso no autorizado.','alert-danger');
          $location.path("/");
        }else{
          GlobalFactory.notify('Se ha producido un error. Intentelo nuevamente.','alert-danger');
        }
      });
    }
  }]);
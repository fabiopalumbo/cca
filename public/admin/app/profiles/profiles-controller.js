angular.module('profiles').controller('ProfilesListCtrl', [
  '$scope','$rootScope','$routeParams','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,$routeParams,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {

    GlobalFactory.spinner(document.getElementById('profiles-spinner'),'spin');
    GlobalFactory.getUserAndDealer().then(function(obj){
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;

      $scope.getProfiles = function(){
        $scope.loading = true;
        GlobalFactory.spinner(document.getElementById('profiles-table-spinner'),'spin');
        $resource('/api/group').query(function(response){
          $scope.loading = false;
          GlobalFactory.spinner(document.getElementById('profiles-table-spinner'),'stop');
          $scope.profiles = response;
        }, function(error){
          $scope.loading = false;
          GlobalFactory.spinner(document.getElementById('profiles-table-spinner'),'stop');
          GlobalFactory.throwAnError(error);
        });
      };

      GlobalFactory.spinner(document.getElementById('profiles-spinner'),'stop');
      $scope.getProfiles();

      $scope.remove = function(id) {
        $resource('/api/group/'+id).delete(function (data) {
          GlobalFactory.notify('Perfil eliminado correctamente.','alert-success');
          $scope.getProfiles();
        }, function (error) {
          GlobalFactory.throwAnError(error);
        });
      };

      $scope.openEditProfile = function(id,ver) {
        $scope.selectedProfileId = id;
        if(ver) $scope.ver = true;
        else $scope.ver = false;
        $modal.open({
            templateUrl: '/admin/app/profiles/profiles-edit.html',
            controller: 'ProfilesEditCtrl',
            keyboard: false,
            scope: $scope,
            size: 'lg',
            backdrop: 'static'
          }).result.then(
            function (callback) {
              if(callback == 'edited'){
                GlobalFactory.notify('Perfl editado correctamente.','alert-success');
              } else if(callback == 'created'){
                GlobalFactory.notify('Perfil creado correctamente.','alert-success');
              }
              $scope.getProfiles();
            }
          );
      };
    },function(){
      GlobalFactory.spinner(document.getElementById('profiles-spinner'),'stop');
      GlobalFactory.throwAnError();
    });
  }]);

angular.module('profiles').controller('ProfilesEditCtrl', [
  '$scope','$rootScope','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {

    $scope.profileId = $scope.$parent.selectedProfileId ? $scope.$parent.selectedProfileId : null;
    $scope.modalInstance = $modalInstance;
    $scope.object = {};
    $scope.currentTab = 0;
    $scope.loading = true;
    GlobalFactory.spinner(document.getElementById('profiles-modal-spinner'),'spin');

    if($scope.profileId) {
      $resource('/api/group/:id',{id:'@id'}).get({id:$scope.profileId},function(data){
        GlobalFactory.spinner(document.getElementById('profiles-modal-spinner'),'stop');
        $scope.loading = false;
        $scope.object = data;
      });
    }else {
      GlobalFactory.spinner(document.getElementById('profiles-modal-spinner'),'stop');
      $scope.loading = false;
    }

    $scope.save = function() {
      var endpoint = '/api/group';
      var profileSave = $scope.profileId ? $resource(endpoint+'/'+$scope.profileId) : $resource(endpoint);
      profileSave.save($scope.object,function(data){
        if($scope.profileId){
          $scope.modalInstance.close('edited');
        } else{
          $scope.modalInstance.close('created');
        }
      },function (error) {
        GlobalFactory.throwAnError(error);
      });
    };

    $scope.close = function(){
      $modalInstance.close();
    };


  }]);

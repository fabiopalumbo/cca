angular.module('permissions').controller('PermissionsListCtrl', [
  '$scope','$rootScope','$routeParams','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,$routeParams,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {

    GlobalFactory.spinner(document.getElementById('permissions-spinner'),'spin');
    GlobalFactory.getUserAndDealer().then(function(obj){
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;
      $scope.filters = {
        name: ''
      };

      $scope.filter = function(){
        if($scope.filters.name == undefined) $scope.filters.name = '';
      };

      $scope.getPermissions = function(){
        $scope.profiles = [];
        $scope.permissions = [];
        $scope.loading = true;
        GlobalFactory.spinner(document.getElementById('permissions-table-spinner'),'spin');
        $resource('/api/group').query(function(result) {
          $scope.profiles = result;
          $resource('/api/permissions').query(function(response){
            $scope.loading = false;
            GlobalFactory.spinner(document.getElementById('permissions-table-spinner'),'stop');
            $scope.permissions = response;
            $scope.oldPermissions = [];
            $scope.permissions.forEach(function(permission){
              $scope.oldPermissions.push({
                id: permission.id,
                list: permission.list,
                read: permission.read,
                create: permission.create,
                update: permission.update,
                delete: permission.delete
              });
            });
            $scope.idOfPermissionsToSave = [];
          }, function(error){
            $scope.loading = false;
            GlobalFactory.spinner(document.getElementById('permissions-table-spinner'),'stop');
            GlobalFactory.throwAnError(error);
          });
        }, function(error){
          $scope.loading = false;
          GlobalFactory.spinner(document.getElementById('permissions-table-spinner'),'stop');
          GlobalFactory.throwAnError(error);
        });
      };

      $scope.updateStatus = function(permission){
        var oldPermission = _.findWhere($scope.oldPermissions,{id: permission.id});
        if(oldPermission.list == permission.list && oldPermission.read == permission.read && oldPermission.create == permission.create && oldPermission.update == permission.update && oldPermission.delete == permission.delete){
          var a = _.find($scope.idOfPermissionsToSave,function(num){ return num == permission.id;});
          if(a) $scope.idOfPermissionsToSave = _.reject($scope.idOfPermissionsToSave,function(num){ return num == permission.id;});
        } else{
          var b = _.find($scope.idOfPermissionsToSave,function(num){ return num == permission.id;});
          if(!b) $scope.idOfPermissionsToSave.push(permission.id);
        }
      };

      $scope.save = function(){
        var arrayToSave = [];
        $scope.idOfPermissionsToSave.forEach(function(id){
          var find = _.findWhere($scope.permissions,{id:id});
          if(find) arrayToSave.push(find);
        });
        $resource('/api/permissions').save({data: arrayToSave},function(){
          GlobalFactory.notify('Permisos guardados.','alert-success');
          $scope.getPermissions();
        },function(error){
          GlobalFactory.throwAnError(error);
          $scope.getPermissions();
        });
      };
      GlobalFactory.spinner(document.getElementById('permissions-spinner'),'stop');
      $scope.getPermissions();
    },function(){
      GlobalFactory.spinner(document.getElementById('profiles-spinner'),'stop');
      GlobalFactory.throwAnError();
    });
  }]);

angular.module('permissions').controller('PermissionsEditCtrl', [
  '$scope','$rootScope','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {



  }]);

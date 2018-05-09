angular.module('users').controller('UsersListCtrl', [
  '$scope','$rootScope','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    GlobalFactory.getUser().then(function(user) {
      $scope.loggedUser = user;
      $scope.loadImage = function(){
        $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';
      };

      $scope.$watch(function(){
        return $rootScope.loggedUser;
      },function(newVal,oldVal){
        if(newVal != oldVal){
          $scope.loggedUser = newVal;
          $scope.loadImage();
          if(!$scope.loggedUser.id){
            $window.location.href = '/ingresar';
          } else {
            console.log('logged');
          }
        }
      });

      var users = $resource('/api/user');
      users.query(function(data){
        $scope.users = data;
      });

      $scope.openEditUser = function(id) {
        $scope.selectedUserId = id;
        $modal.open({
          templateUrl: '/admin/app/users/users-edit.html',
          controller: 'UsersEditCtrl',
          scope: $scope,
          size: 'lg'
        }).result.then(
          function () {
            GlobalFactory.reload();
          }
        );
      };

      $scope.removeUser = function(id){
        var remove = $resource('/api/user/:id',{id:'@id'});
        remove.remove({id:id},function(data){
          alert("El usuario se elimino correctamente");
          $route.reload();
        },function(error){
          if(error.status == 401){
            alert("ACCESO NO AUTORIZADO");
            $location.path("/admin");
          }
        });
      };
    });

  }]);
angular.module('users').controller('UsersEditCtrl', [
  '$scope','$rootScope','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {
    $scope.object = {};
    $scope.modalInstance = $modalInstance;

    $scope.groups = [];
    $scope.dealers = [];


    var getGroups = function(){
      $resource('/api/group').query(function(groupData){
        for(var i  = groupData.length-1 ; i >= 0 ; i--){
          var selected = false;
          for(var j = $scope.object.groups.length-1 ; j >= 0 ; j--){
            if(parseInt(groupData[i].id) == parseInt($scope.object.groups[j])){
              selected = true;
              break;
            }
          }
          $scope.groups[i] = {
            id: groupData[i].id,
            name: groupData[i].name,
            selected: selected
          };
        }
      },function (error){
        if (error.status == 401) {
          alert("ACCESO NO AUTORIZADO");
          $location.path("/admin");
        }
      });
    };

    var getDealers = function(){
      $resource('/api/dealer').query(function(dealerData){
        for(var i  = dealerData.length-1 ; i >= 0 ; i--){
          var selected = false;
          for(var j = $scope.object.dealers.length-1 ; j >= 0 ; j--){
            if(parseInt(dealerData[i].id) == parseInt($scope.object.dealers[j])){
              selected = true;
              break;
            }
          }
          $scope.dealers[i] = {
            id: dealerData[i].id,
            name: dealerData[i].name,
            selected: selected
          };
        }
      },function (error){
        if (error.status == 401) {
          alert("ACCESO NO AUTORIZADO");
          $location.path("/admin");
        }
      });
    };

    var userId;
    if(userId = $scope.$parent.selectedUserId) {
      var users = $resource('/api/user/:id',{id:'@id'});
      users.get({id:userId},function(data){
        $scope.object = {
          id: data.id,
          first_name: data.first_name,
          last_name: data.last_name,
          email: data.email,
          phone: data.phone,
          groups: _.pluck(data.groups,'id'),
          dealers: _.pluck(data.dealers,'id')
        };

        getGroups();
        getDealers();
      });
    }else{
      $scope.object = {
        groups: [],
        dealers: []
      };

      getGroups();
      getDealers();
    }

    $scope.close = function(){
      $modalInstance.dismiss();
    };

    $scope.$watch(function(){
      return $rootScope.loggedUser;
    },function(newVal,oldVal){
      if(newVal != oldVal){
        $scope.loggedUser = newVal;
        $scope.loadImage();
      }
    });

    if(!$scope.loggedUser){
      console.log('not logged');
      //$location.path('/admin');
    }
    $scope.recoverPassword = function(){
      var recovery = $resource("user/password/recovery");
      recovery.post({email:$scope.usuario.email},function(){
        alert("Se ha enviado un mail a su casilla para recuperar la contraseña");
        $route.reload();
      },function(error){
        alert("Ha ocurrido un error inesperado.Intentelo de nuevo");
      });
    };

    Array.prototype.contains = function(obj) {
      var i = this.length;
      while (i--) {
        if (this[i] == obj) {
          return true;
        }
      }
      return false;
    };

    $scope.save = function() {
      $scope.object.groups = [];
      $scope.object.dealers = [];
      $scope.groups.forEach(function(group){
        if(group.selected){
          $scope.object.groups.push(group.id);
        }
      });
      $scope.dealers.forEach(function(dealer){
        if(dealer.selected){
          $scope.object.dealers.push(dealer.id);
        }
      });
     var user = $scope.object.id ? $resource("/api/user/"+$scope.object.id) : $resource("/api/user");
      user.save($scope.object,function(data){
        alert("El usuario se guardó correctamente");
        $modalInstance.close();
      },function (error) {
        if (error.status == 401) {
          alert("ACCESO NO AUTORIZADO");
          $location.path("/");
        }else{
          alert("Error al agregar usuario");
        }
      });
    }
  }]);

angularApp.controller('ModificarDatosUsuarioCtrl', [
    '$scope','$modalInstance','Usuario','$rootScope','Restangular','GlobalFactory',
    function($scope, $modalInstance,Usuario,$rootScope,Restangular,GlobalFactory) {
        $scope.modalInstance = $modalInstance;
        GlobalFactory.init();
        $scope.$watch(function(){
            return $rootScope.loggedUser;
        },function(newVal,oldVal){
            if(newVal != oldVal)
                $scope.loggedUser = newVal;
        });

        $scope.no_changes = true;
		$scope.modal = {
            name: Usuario.name,
            surname: Usuario.surname,
            email: Usuario.email,
            phone: Usuario.phone
        };
        $scope.checkStatus = function(){
            if(Usuario.name == $scope.modal.name && Usuario.surname == $scope.modal.surname && Usuario.phone == $scope.modal.phone)
            $scope.no_changes = true;
            else $scope.no_changes = false;
        };
        var baseUpdate = Restangular.all('user/'+ $scope.loggedUser.id + '/update');
        $scope.update = function(){
            baseUpdate.post($scope.modal).then(function(data){
            if(data == 'updated'){
                alert("Los datos se han actualizado correctamente");
                $modalInstance.close();
            }else alert("Error al actualizar. Intentelo de nuevo");

            });
        };
}]);

angularApp.controller('ModificarPasswordUsuarioCtrl', [
    '$scope','$modalInstance','$rootScope','Restangular','GlobalFactory',
    function($scope, $modalInstance,$rootScope,Restangular,GlobalFactory) {
        $scope.modalInstance = $modalInstance;
        GlobalFactory.init();
        $scope.$watch(function(){
            return $rootScope.loggedUser;
        },function(newVal,oldVal){
            if(newVal != oldVal)
                $scope.loggedUser = newVal;
        });

          $scope.oldPassword='';
          $scope.newPassword='';
          $scope.newPasswordRepeat='';

          $scope.changePassword = function(){
              $scope.psword = {
                  oldPassword: $scope.oldPassword,
                  newPassword: $scope.newPassword
              };
              if($scope.newPassword == $scope.newPasswordRepeat){
                  var updatePsw = Restangular.all('user/updatePass');
                  updatePsw.post($scope.psword).then(function(data){
                      if(data == 'updated'){
                          alert("La contraseña se ha modificado correctamente");
                          $modalInstance.close();

                      }else alert("Error. Intentelo de nuevo");
                  });
              }else alert("Las contraseñas no coinciden");
          };

 }]);

angularApp.controller('CerrarCuentaCtrl', [
    '$scope','$modalInstance','$rootScope','Restangular','GlobalFactory',
    function($scope, $modalInstance,$rootScope,Restangular,GlobalFactory) {

        $scope.passwordUser = '';
        $scope.modalInstance = $modalInstance;
        GlobalFactory.init();
        $scope.$watch(function(){
            return $rootScope.loggedUser;
        },function(newVal,oldVal){
            if(newVal != oldVal)
                $scope.loggedUser = newVal;
        });



        var closeUser = Restangular.all('user/delete');
        $scope.closeAccount = function(){
            $scope.user = {
                email:$scope.loggedUser.email,
                password: $scope.passwordUser
            };

            closeUser.post($scope.user).then(function (response) {
                if(response == 'yes'){
                    alert("La cuenta ha sido desactivada");
                    $modalInstance.close();
                }else{
                    alert("La contraseña ingresada es incorrecta");
                }

            });
        };


    }]);

angularApp.controller('ReactivarCuentaCtrl', [
    '$scope','$modalInstance','$rootScope','Restangular','GlobalFactory','Email',
    function($scope, $modalInstance,$rootScope,Restangular,GlobalFactory,Email) {
        $scope.modalInstance = $modalInstance;
        GlobalFactory.init();
        $scope.$watch(function(){
            return $rootScope.loggedUser;
        },function(newVal,oldVal){
            if(newVal != oldVal)
                $scope.loggedUser = newVal;
        });


        var activeUser = Restangular.all('user/active');
        $scope.activeAccount = function(){
            $scope.user = {
                email:Email,
                password: $scope.passwordUser
            };

            activeUser.post($scope.user).then(function (response) {
                if(response == 'active'){
                    alert("La cuenta ha sido re-activada");
                    $modalInstance.close();
                }else{
                    alert("La contraseña ingresada es incorrecta");
                }

            });
        };

    }]);

angularApp.controller('recoveryCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory', '$routeParams','$location',
    function($scope,$rootScope,Restangular,GlobalFactory,$routeParams,$location) {
        GlobalFactory.init();
        var token =  $routeParams.token;
        console.log(token);
        var Verify = Restangular.one('user/verify/'+token);
        Verify.get().then(function(data){
            if (data == 'error'){
             $location.path('/');
            }else{
                $scope.email = data;
            }
        });

        $scope.newPassword = '';
        $scope.newPasswordRepeat = '';
        $scope.recoveryPassword = function(){
            if ($scope.newPassword == $scope.newPasswordRepeat){
                $scope.psword = {
                    newPassword: $scope.newPassword,
                    newPasswordRepeat: $scope.newPasswordRepeat,
                    email : $scope.email
                };
                var changePsw = Restangular.all('user/updateRecovery');
                changePsw.post($scope.psword).then(function(data){
                    if(data != 'error'){
                        alert("La contraseña se ha modificado correctamente");
                        $location.path('/login');

                    }else alert("Error. Intentelo de nuevo");
                });
            }else{
                alert("Las contraseñas no coinciden");
            }


        };



    }]);

angularApp.controller('ProfileImageCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory', '$routeParams','$location','$modalInstance',
    function($scope,$rootScope,Restangular,GlobalFactory,$routeParams,$location,$modalInstance) {
       $scope.modalInstance = $modalInstance;
        GlobalFactory.init();
        $scope.$watch(function(){
            return $rootScope.loggedUser;
        },function(newVal,oldVal){
            if(newVal != oldVal)
                $scope.loggedUser = newVal;
        });
       $scope.obj = {};
       $scope.change = true;
       $scope.change = function(){
          if($scope.change){
               $scope.change = false;
           }else{
               $scope.change = true;
           }
       };

        $scope.url = 'api/user/'+$scope.loggedUser.id+'/setImage';

    }]);
/*
angularApp.config(['flowFactoryProvider', function (flowFactoryProvider) {
    flowFactoryProvider.defaults = {
        testChunks: false,
        permanentErrors: [404, 500, 501],
        maxChunkRetries: 1,
        chunkRetryInterval: 5000,
        simultaneousUploads: 4,
        singleFile: true
    };
    flowFactoryProvider.on('catchAll', function (event) {
        console.log('catchAll', arguments);
    });

}]);
*/
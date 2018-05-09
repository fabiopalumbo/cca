'use strict';

angularApp
    .controller('profileCtrl', function ($scope,  GlobalFactory, $location,$modal,$rootScope,Restangular,$route) {
        GlobalFactory.init();
        $scope.$watch(function(){
            return $rootScope.loggedUser;
        }, function(newVal,oldVal){
            if($rootScope.loggedUser != undefined){
                $scope.user = {
                    name: newVal.name,
                    surname: newVal.surname,
                    email: newVal.email,
                    phone: newVal.phone
                }
            }else{
                $location.path('/');
            }
        });
        $scope.openUpdateUserDialog = function() {
            $modal.open(
                {
                    templateUrl: 'admin-app/profile/view-modal-modificar-datos-usuario.html',
                    controller:'ModificarDatosUsuarioCtrl',
                    backdrop: 'static',
                    windowClass: 'small-dialog small-modal',
                    keyboard: true,
                    scope: $scope,
                    resolve:{

                        Usuario: function (){
                            return $scope.user;
                        }
                    }
                }
            ).result.then(
                function () {
                   GlobalFactory.reload();
                   location.reload(true);
                }
            );

        };


        $scope.openChangePasswordDialog = function() {
            $modal.open(
                {
                    templateUrl: 'admin-app/profile/view-modal-cambiar-password.html',
                    controller:'ModificarPasswordUsuarioCtrl',
                    backdrop: 'static',
                    windowClass: 'small-dialog small-modal',
                    keyboard: true,
                    scope: $scope

                }
            ).result.then(
                function () {

                    GlobalFactory.reload();

                }
            );

        };

        $scope.openCloseAccountDialog = function() {
            $modal.open(
                {
                    templateUrl: 'admin-app/profile/view-modal-cerrar-cuenta.html',
                    controller:'CerrarCuentaCtrl',
                    backdrop: 'static',
                    windowClass: 'small-dialog small-modal',
                    keyboard: true,
                    scope: $scope

                }
            ).result.then(
                function () {
                    var logout = Restangular.one('user/logout');
                    logout.get();
                    console.log('logout');
                    $location.path('/');
                    $rootScope.loggedUser=null;
                    GlobalFactory.reload();
                }
            );

        };

        $scope.openImageDialog = function() {
            $modal.open(
                {
                    templateUrl: 'admin-app/profile/view-modal-cambiar-imagen.html',
                    controller:'ProfileImageCtrl',
                    backdrop: 'static',
                    windowClass: 'large-modal',
                    keyboard: true,
                    scope: $scope

                }
            ).result.then(
                function () {
                    GlobalFactory.reload();
                    location.reload(true);
                }
            );

        };
    }

);

angularApp.controller('usuarioReadCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','$route','$timeout',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,$route,$timeout) {
        GlobalFactory.init();
        $scope.loadImage = function(){
            $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';

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
            $location.path('/admin');
        }

    var users = Restangular.one('user');
    users.get().then(function(data){
       $scope.usuarios = data;
    },function(error){
        if(error.status == 401){
            alert("ACCESO NO AUTORIZADO");
            $location.path("/admin");
            }
        });

        $scope.remove = function(id){
            var remove = Restangular.all('user/'+id+'/delete');
            remove.post().then(function(data){
                if(data == 'removed'){
                    alert("El usuario se elimino correctamente");
                    $route.reload();
                }
            },function(error){
                if(error.status == 401){
                    alert("ACCESO NO AUTORIZADO");
                    $location.path("/admin");
                }
                });
        };

}]);

angularApp.controller('usuarioEditCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','$routeParams','$route','$modal',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,$routeParams,$route,$modal) {
        GlobalFactory.init();
        $scope.loadImage = function(){
            $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';

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
            $location.path('/admin');
        }
        $scope.noChanges = true;
        var id =  $routeParams.id;
        var users = Restangular.one('user');
        users.get({userid:id}).then(function(data){
            $scope.usuario = data;
            $scope.object = {};
            $scope.object.name = $scope.usuario.name;
            $scope.object.surname = $scope.usuario.surname;
            $scope.object.email = $scope.usuario.email;
            $scope.object.phone = $scope.usuario.phone;

        },function(error){
            if(error.status == 401){
                alert("ACCESO NO AUTORIZADO");
                $location.path("/admin");
            }
            });

        $scope.checkStatus = function(){
           if($scope.usuario.name == $scope.object.name && $scope.usuario.surname == $scope.object.surname && $scope.object.phone == $scope.usuario.phone)
                $scope.noChanges = true;
            else $scope.noChanges = false;
        };

        $scope.update = function(){
            var update = Restangular.all('user/'+$scope.usuario.id+'/update');
            $scope.usuario = {
                'name' :  $scope.object.name,
                'surname' :  $scope.object.surname,
                'phone' :  $scope.object.phone
            };
            update.post($scope.usuario).then(function(){
                alert("Los datos se actualizaron correctamente");
                $route.reload();
            },function(error){
                if(error.status == 401){
                    alert("ACCESO NO AUTORIZADO");
                    $location.path("/admin");
                }
                });
        };

        var getGroups = Restangular.one('group');
        getGroups.get({userid:id}).then(function(data){
            $scope.grupos = data;
        },function(error){
            if(error.status == 401){
                alert("ACCESO NO AUTORIZADO");
                $location.path("/admin");
            }
        });


        var groups = Restangular.one('user/'+id+'/group');
        groups.get().then(function(data){
            $scope.gruposAll = data;
        },function(error){
            if (error.status == 401){
                alert("ACCESO NO AUTORIZADO");
                $location.path("/admin");
            }
        });

        $scope.asociarGrupoOpen = function() {
             groups.get().then(function(data){
                    $scope.gruposAll = data;
             },function(error){
                 if (error.status == 401){
                     alert("ACCESO NO AUTORIZADO");
                     $location.path("/admin");
                 }
             });
             $modal.open(
                    {
                        templateUrl: 'admin-app/views/usuario/modal-view-grupo.html',
                        controller: 'ModalGrupoReadCtrl',
                        backdrop: 'static',
                        windowClass: 'big-modal',
                        keyboard: true,
                        scope: $scope,
                        resolve: {
                            Grupo : function(){
                                return $scope.gruposAll;
                            }
                        }

                    }
                ).result.then(
                    function(result){
                        var UserGroup = Restangular.all('user/'+id+'/group/asociar');
                            UserGroup.post({group_id:result}).then(function() {
                              alert("Se han actualizado los grupos correctamente");
                              $route.reload();
                            },function(error){
                                if(error.status == 401){
                                    alert("ACCESO NO AUTORIZADO");
                                    $location.path("/admin");
                                }
                                });

                    }
                );
            };



        $scope.desasociarGrupo = function(grupo){
            var desasociar = Restangular.all('user/'+id+'/group/desasociar');
            desasociar.post({group_id:grupo.id}).then(function(data){
                if(data=='desasociado'){
                    alert("El usuario ya no pertenece al grupo");
                    $route.reload();
                }
            },function(error){
                if(error.status == 401){
                    alert("ACCESO NO AUTORIZADO");
                    $location.path("/admin");
                }
            });
        };

        $scope.recoveryPassword = function(){
           var recovery = Restangular.all("user/password/recovery");
            recovery.post({email:$scope.usuario.email}).then(function(){
                alert("Se ha enviado un mail a su casilla para recuperar la contraseña");
                $route.reload();

            },function(error){
                alert("Ha ocurrido un error inesperado.Intentelo de nuevo");

            });
        };

    }]);

angularApp.controller('ModalGrupoReadCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','Grupo','$modalInstance',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,Grupo,$modalInstance) {
        GlobalFactory.init();
        $scope.loadImage = function(){
            $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';

        };

        $scope.$watch(function(){
            return $rootScope.loggedUser;
        },function(newVal,oldVal){
            if(newVal != oldVal){
                $scope.loggedUser = newVal;
                $scope.loadImage();
            }
        });
        $scope.modalInstance = $modalInstance;
        $scope.groups = Grupo;
        if(!$scope.loggedUser){
            $location.path('/admin');
        }

        angular.forEach($scope.groups,function(grupo){
            grupo.selected = (grupo.selected == '1');
        });
        console.log($scope.grupos);

        $scope.select = function(group){
            $modalInstance.close(group);
        };

        $scope.saveGrupos = function(){
            var gruposSelecteds = [];
            angular.forEach($scope.groups,function(grupo){

                if(grupo.selected == true)
                    gruposSelecteds.push(grupo.id);
            });
            $scope.modalInstance.close(gruposSelecteds);
        };

    }]);

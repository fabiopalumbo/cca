angularApp.controller('grupoReadCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','$modal','$timeout',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,$modal,$timeout) {
       GlobalFactory.init();
        $scope.loadImage = function(){
            $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';
            var isAdmin = Restangular.one('user/' + $scope.loggedUser.id + '/admin');
            isAdmin.get().then(function(data){

            });
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

    var Groups = Restangular.one('group');
    Groups.get().then(function(data){
         $scope.grupos = data;

    },function(error){
        if(error.status == 401) {
            alert("ACCESO NO AUTORIZADO");
            $location.path("/admin");
            }
        });

        $scope.update = function(id){
            console.log(id);
            angular.forEach($scope.grupos,function(grupo){
                if (grupo.id == id){
                    $modal.open(
                        {
                            templateUrl: 'admin-app/views/grupos/view-edit-groups.html',
                            controller:'grupoEditCtrl',
                            backdrop: 'static',
                            windowClass: 'small-dialog big-modal',
                            keyboard: true,
                            scope: $scope,
                            resolve:{

                                Grupo: function (){
                                    return grupo;
                                }
                            }
                        }
                    ).result.then(
                        function () {
                            GlobalFactory.reload();
                            location.reload(true);
                        }
                    );
                }
            });

        };

        $scope.remove = function(id){
            $timeout(function(){
                var group = Restangular.all('group/'+ id +'/delete');
                if(confirm("¿Esta seguro?")==true){
                    group.post().then(function(result){
                        if(result == "deleted"){
                            alert("El grupo se ha eliminado correctamente");
                            location.reload(true);
                        }
                    },function(error){
                        if(error.status == 401){
                            alert("ACCESO NO AUTORIZADO");
                            $location.path("/admin");

                        }
                    });
                }
            });

        }

    }]);


angularApp.controller('grupoCreateCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','$modal',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,$modal) {
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

    $scope.object = {};
    var Groups = Restangular.one('user/groups');
    Groups.get().then(function(data){
    $scope.grupos = data;
    });

    $scope.object.name = '';
    $scope.notFound = false;
    $scope.object.description = '';
    $scope.create = function(){
        angular.forEach($scope.grupos,function(grupo){
            if(grupo.name == $scope.object.name ){
                alert("El grupo ya existe.");
                $scope.notFound = true;
            }

        });
        if(!$scope.notFound){
            var Grupo = {
                'name' : $scope.object.name,
                'description' : $scope.object.description
            };
            var create = Restangular.all("group/store");
            create.post(Grupo).then(function(result){
                if(result == 'grupo creado'){
                    alert("El grupo ha sido creado sactifactoriamente");
                    $location.path("/grupos");
                }else{
                    alert("Hubo un error al crear el grupo. Intentelo de nuevo");
                }
            },function(error){
                if(error.status == 401){
                    alert("ACCESO NO AUTORIZADO");
                    $location.path("/admin");
                }
            });
        }
    };

    }]);

angularApp.controller('grupoEditCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','Grupo','$modalInstance',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,Grupo,$modalInstance) {
        $scope.modalInstance = $modalInstance;
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

    $scope.object = {};
    $scope.noChanges= true;
    $scope.object.name = Grupo.name;
    $scope.object.description = Grupo.description;

        $scope.checkStatus = function(){
            if(Grupo.name == $scope.object.name && Grupo.description == $scope.object.description)
                $scope.noChanges = true;
            else $scope.noChanges = false;
        };

        $scope.update = function(){
           $scope.group ={
               'name' : $scope.object.name,
               'description' : $scope.object.description
           };
            var group = Restangular.all('group/'+Grupo.id+'/update');
            group.post($scope.group).then(function(result){
                if(result== 'updated'){
                    alert("El grupo se actualizo correctamente");
                    $modalInstance.close();
                }else{
                    alert("Hubo un error al actualizar el grupo. Intentelo de nuevo");
                }
            },function(error){
                if(error.status == 401){
                    alert("ACCESO NO AUTORIZADO");
                    $location.path("/admin");
                }
            });



        };
    }]);

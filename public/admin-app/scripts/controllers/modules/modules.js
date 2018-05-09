angularApp.controller('ModuloCtrl',[ '$scope','Restangular','$location','GlobalFactory','$rootScope','$route',function($scope,Restangular,$location,GlobalFactory,$rootScope,$route){
   GlobalFactory.init();
    if($rootScope.loggedUser== undefined){
        $location.path('/');
    }
    $scope.change = false;
    $scope.selected = "Todos";

    var grupos = Restangular.one('group');
    grupos.get().then(function(data){
        $scope.groups = data;
    },function(error){
       if(error.status == 401) {
           alert("ACCESO NO AUTORIZADO");
           $location.path("/admin");
       }
    });
    var modulos = Restangular.one('modulos/permisos');
    modulos.get().then(function(data){
        $scope.modulosObj = data;


        angular.forEach($scope.modulosObj,function(modulo){
            modulo.read = (modulo.read == '1');
            modulo.read_edit = (modulo.read_edit == '1');
            modulo.create = (modulo.create == '1');
            modulo.create_edit = (modulo.create_edit == '1');
            modulo.update = (modulo.update == '1');
            modulo.update_edit = (modulo.update_edit == '1');
            modulo.delete = (modulo.delete == '1');
            modulo.delete_edit = (modulo.delete_edit == '1');
            modulo.list = (modulo.list == '1');
            modulo.list_edit = (modulo.list_edit == '1');
            modulo.change = false;
        });
    });

   /* angular.forEach($scope.modulosObj,function(module){

    });
   */
    $scope.filterGroup=function(id){
        if (id==0){
            modulos.get().then(function(data){
                $scope.modulosObj = data;
                $scope.selected = "Todos";
            });
        } else{
            modulos.get({grupo_id : id}).then(function(data){
                $scope.modulosObj = data;
                $scope.selected  = data[0].nombre_grupo;

            });
        }
    };

    $scope.filterBy = function(filtro){
        console.log(filtro);
        var foundModule = false;
        var foundModuleVal = {};
        $scope.modulosObj.forEach(function(module){
            if(module.nombre_modulo == filtro){
                foundModule = true;
                foundModuleVal = module;
            }
        });
        if (foundModule != true){
           return false;
        } else{
           return true;
        }


    };



    $scope.changeStatusModule = function(module,permission){
        switch(permission){
            case 'read':
                module.read = !module.read;
                break;
            case 'create':
                module.create = !module.create;
                break;
            case 'update':
                module.update = !module.update;
                break;
            case 'delete':
                module.delete = !module.delete;
                break;
            case 'list':
                module.list = !module.list;
                break;
            default:
                break;
        }
        module.change = true;
    };

    $scope.updateModule = function(modulo){
        var updateModules = Restangular.all("modulos/permisos/update");
                updateModules.post(modulo).then(function(data){
                    if (data == 'update'){
                        alert("Los datos se actualizaron corectamente");
                        $route.reload();

                    }else alert("Error.Vuelva a intentarlo");
                },function(error){
                    if (error.status == 401){
                        alert("ACCESO NO AUTORIZADO");
                        $location.path("/admin");
                    }
                });




    };


}]);
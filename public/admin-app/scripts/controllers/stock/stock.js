angularApp.controller('stockReadCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','$modal','$timeout','$route',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,$modal,$timeout,$route) {
        GlobalFactory.init();
        $scope.loadImage = function(){
            $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';
            var isAdmin = Restangular.one('user/' + $scope.loggedUser.id + '/admin');
            isAdmin.get().then(function(data){

            });
        };

        $scope.remove = function(id) {
            var removeStock = Restangular.all('stock/' + id + '/delete');
            removeStock.post().then(function (data) {
                alert("El vehiculo se ha eliminado correctamente");
                $route.reload();

            }, function (error) {
                alert("Se ha producido un error,vuelva a intentarlo");
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

        var stock = Restangular.one('stock');
        stock.get().then(function(data){
            $scope.stock = data;
            console.log($scope.stock);
        });

        $scope.openCreateStock = function() {
            $modal.open(
                {
                    templateUrl: 'admin-app/views/stock/view-create-stock.html',
                    controller: 'stockCreateCtrl',
                    backdrop: 'static',
                    windowClass: 'big-modal',
                    keyboard: true,
                    scope: $scope
                }
            ).result.then(
                function () {
                    GlobalFactory.reload();
                }
            );

        };

    }]);

angularApp.controller('stockCreateCtrl', [
    '$scope','$rootScope','Restangular','GlobalFactory','$location','$modal','$timeout','$modalInstance',
    function($scope,$rootScope,Restangular,GlobalFactory,$location,$modal,$timeout,$modalInstance) {
        GlobalFactory.init();
        $scope.currentTab = 0;
        $scope.object = {};
        $scope.modalInstance = $modalInstance;
        $scope.object.combustible = "5";
        $scope.object.color = '';
        $scope.object.version = '';
        $scope.object.titular = '';
        $scope.object.kilometros = '';
        $scope.object.color = '';
        $scope.object.chasis = '';
        $scope.object.tipo = "1";



        $scope.loadImage = function(){
            $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';
            var isAdmin = Restangular.one('user/' + $scope.loggedUser.id + '/admin');
            isAdmin.get().then(function(data){
            });
        };

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
            $location.path('/admin');
        }

        var combustibles = Restangular.one('stock/combutibles');
        combustibles.get().then(function(data){
           $scope.combustibles = data;
            console.log($scope.combustibles);
        });

        var tipo = Restangular.one('stock/tipos');
        tipo.get().then(function(data){
            $scope.tipos = data;
        });



        var caracteristica = Restangular.one('caracteristicas');
        caracteristica.get().then(function(data){
            $scope.caracteristicas = data;
            $scope.caracteristicas.forEach(function(caracteristica){
                caracteristica.val = false;
            });

        },function (error){
            if (error.status == 401) {
                alert("ACCESO NO AUTORIZADO");
                $location.path("/admin");
                }
            });

        $scope.changeFeatures = function (index,value) {
            if(value == true){
                $scope.caracteristicas[index].val = true;
            }else{
                $scope.caracteristicas[index].val = false;
            }

        };

        $scope.save = function() {
            console.log($scope.caracteristicas);
            var obj = [];
            $scope.caracteristicas.forEach(function (caracteristica) {
                if (caracteristica.val == true) {
                    obj.push(caracteristica.id);
                }
            });

            var stock = Restangular.all("stock/create");
            stock.post({datos: $scope.object, caracteristicas: obj}).then(function(data){
                alert("El vehiculo se agrego al stock correctamente");
                $modalInstance.close();
            },function (error) {
                if(error.status == 400){
                    alert("El dominio del vehiculo ya fue agregado");
                }
                if (error.status == 401) {
                    alert("ACCESO NO AUTORIZADO");
                    $location.path("/admin");
                }else{
                    alert("Error al agregar vehiculo");
                }

            });
        }



    }]);

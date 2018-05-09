
angularApp.controller('RegisterCtrl',[ '$scope','$modalInstance','Restangular','$location','GlobalFactory','$modal','$modalStack',function($scope,$modalInstance,Restangular,$location,GlobalFactory,$modal,$modalStack){
  $scope.modalInstance = $modalInstance;
  $scope.checkRegister = false;
  $scope.close = function(){
    $modalInstance.close();
  };
    GlobalFactory.init();
    if($scope.loggedUser!=null){
        $location.path('/');
    }

    var baseRegister = Restangular.all('user/register');
    $scope.registroUser = function () {
        if($scope.checkRegister)
        {
            $scope.user = {
                name: $scope.userName,
                surname: $scope.userSurname,
                email: $scope.userEmail,
                phone: $scope.userPhone,
                password: $scope.userPassword

            };
              $scope.email = $scope.userEmail;
            baseRegister.post($scope.user).then(function(data){
                    if(data=='registrado'){
                        alert("Usted ha sido registrado correctamente. Sera logueado automaticamente.");
                        var Active = Restangular.all('user/active');
                        Active.post($scope.user);
                        var baseLogin = Restangular.all('user/login');
                        baseLogin.post($scope.user).then(function(data){
                            if(!data) alert("Los datos ingresados son incorrectos. Porfavor, ingrese datos válidos.");
                            else{
                                $modalInstance.close();

                            }
                        });
                    }
                    if(data=='en uso'){
                       alert("El email ingresado se encuentra en uso.Intente de nuevo");
                    }
                    if(data=='borrado'){
                        //var baseReactivar = Restangular.all('activate-email');
                        /*if (confirm("El email ingresado esta siendo usado por una cuenta desactivada. Desea reactivar su cuenta?") == true) {
                            baseReactivar.post($scope.USERemail).then(function(data){

                            });
                            alert("Un email sera enviado hacia su correo para reactivar su cuenta.");
                        }*/
                        if(confirm("El e-mail proporcionado ya forma parte de una cuenta desactivada. ¿Desea re-activarla?.")==true){
                            $modal.open(
                                {
                                    templateUrl: 'admin-app/profile/view-modal-reactivar-cuenta.html',
                                    controller:'ReactivarCuentaCtrl',
                                    backdrop: 'static',
                                    windowClass: 'small-dialog small-modal',
                                    keyboard: true,
                                    scope: $scope,
                                    resolve: {
                                        Email:function() {return $scope.email;}

                                    }

                                }
                            ).result.then(
                                function () {
                                    location.reload();
                                    //GlobalFactory.reload();
                                    //$location.path('/login');



                                }
                            );
                        }


                    }

                },function(error){
                    console.log(error);
                });
        }
        else alert("No has aceptado los términos y condiciones. Acépta los términos y condiciones si deseas continuar.");

    };
}]);

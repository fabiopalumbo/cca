angularApp.controller('LoginCtrl',[ '$scope','$modalInstance','Restangular','$location','GlobalFactory','$modal',function($scope,$modalInstance,Restangular,$location,GlobalFactory,$modal){
    $scope.modalInstance = $modalInstance;
    GlobalFactory.init();
    if($scope.loggedUser!=null){
        $location.path('/');
    }
    $scope.loginCheck = false;
    var baseLogin = Restangular.all('user/login');
    $scope.login = function(check){
        if(check)
        {
            $scope.user = {
                email: $scope.email,
                password : $scope.password,
                check: true
            };
        }
        else{
            $scope.user = {
                email: $scope.email,
                password: $scope.password,
                check: false
            };
        }
        baseLogin.post($scope.user).then(function(data){
            if(data==null) alert("Los datos ingresados son incorrectos o la cuenta esta desactivada. Por favor, ingrese datos válidos.");
            else{
                $modalInstance.close();
            }
        },function(error){
            console.log(error);
        });

    };
    $scope.input = 'password';
    $scope.change = function(){
        if($scope.loginCheck){
            $scope.input = 'text';
        }else{
            $scope.input = 'password';
        }
    };
    $scope.openPasswordModal = function() {
        $modal.open(
            {
                templateUrl: 'admin-app/views/login/view-modal-recuperar-contraseña.html',
                controller: 'passwordCtrl',
                backdrop: 'static',
                windowClass: 'normal-modal',
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

angularApp.controller('passwordCtrl', [
    '$scope','$modalInstance','$rootScope','Restangular','GlobalFactory',
    function($scope, $modalInstance,$rootScope,Restangular,GlobalFactory) {
        $scope.modalInstance = $modalInstance;
        GlobalFactory.init();

        $scope.sendPassword = function(){
            var email = Restangular.all("user/password/recovery");
            email.post({email : $scope.emailUser}).then(function(data){
                if (data == 'exist'){
                    alert("Se ha enviado un mail a su casilla de correo para proceder");
                    $modalInstance.close();

                }else{
                    alert("El mail ingresado no corresponde a ninguna cuenta");
                }

            });

        };

    }]);


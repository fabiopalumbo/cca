angularApp.controller('MainCtrl', ['$scope','Restangular','GlobalFactory','$modal','$rootScope','$location','$timeout',function ($scope, Restangular, GlobalFactory,$modal,$rootScope,$location,$timeout) {
    $scope.log = false ;
    //A reemplazar con el nombre de la pagina
    $scope.name = 'CCA FONSOFT';
    GlobalFactory.init();
    $scope.loadImage = function(){
        $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';

    };

    $scope.getUsuarioList = function(){
        var getPermissions = Restangular.one('user/permission');
        getPermissions.get().then(function(data){
            $scope.permission = data;
            angular.forEach($scope.permission,function(permiso){
                if(permiso.list == '1'){
                    if(permiso.id == 1) $scope.perfiles = true;
                    if(permiso.id == 2) $scope.permisos = true;
                    if(permiso.id == 3) $scope.usuarioMod = true;
                    if(permiso.id == 4) $scope.stock = true;
                    if(permiso.id == 4) $scope.contactos = true;
                }
            });
        });
    };

    $scope.$watch(function(){
        return $rootScope.loggedUser;
    },function(newVal,oldVal){
        if(newVal != oldVal){
            $scope.loggedUser = newVal;
            $scope.loadImage();
            $scope.getUsuarioList();


        }
    });

    //$scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : "src/images/perfil.png"
    //console.log($scope.loggedUser.id);
    var buttonToggled = false;
    $scope.toggleButton = function(){
        if(!buttonToggled){
            $("#menu").slideDown(100);
            $("#toggle").toggleClass("on",200);
            buttonToggled = true;
        } else{
            console.log("SUBE");
            $("#menu").stop().slideToggle("slow");
            $("#toggle").removeClass("on");
            buttonToggled = false;
        }


    };


    $(".dropdown-toggle").click(function(){
        $(".dropdown-perfil").slideToggle("slow");
    });

$scope.openLoginModal = function() {
        $modal.open(
            {
                templateUrl: 'admin-app/views/login/view-modal-login.html',
                controller: 'LoginCtrl',
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
    $scope.openRegisterModal = function() {
        $modal.open(
            {
                templateUrl: 'admin-app/views/register/register.html',
                controller: 'RegisterCtrl',
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

    var buttonToggled1 = false;
    $scope.toggleButton1 = function(){
        if(!buttonToggled1){
            $("#toggle1").toggleClass("on",200);
            $("#nav").removeClass("hidden-xs");
            //$("#nav").toggleClass("visible-xs",200);
            $("#nav").animate({width: '250px',
                height: '100%'

            },"slow");
            $(".container").animate({marginLeft: '310px'},"slow");
            $(".index-footer").animate({marginLeft:'220px'},"slow");
            buttonToggled1 = true;
        } else{
            console.log("SUBE");
            $("#toggle1").removeClass("on");
            //$("#nav").removeClass("visible-xs");

            $("#nav").stop().animate({width: '89px',
                height: '100%'

            },"slow");
            $(".container").stop().animate({marginLeft: '8%'},"slow");
            $(".index-footer").stop().animate({marginLeft:'0px'},"slow");
            $("#nav").toggleClass("hidden-xs",20);

            buttonToggled1 = false;
        }


    };

    if (screen.width>=750){
        $("#nav").css("width","89px");
    }


}]);

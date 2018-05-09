'use strict';

angularApp
    .factory('GlobalFactory', function( $rootScope, Restangular, $location, $route) {

        $rootScope.notifications = [{link: '#/dsada',description:'dadasdasd'},{link: '#',description:'12356126357'}];
        $rootScope.locationSide='home';

        //Hacer despues que se oculte o muestre contraseña segun checkbox

        var factory = {};
        factory.init = function () {
            if(!$rootScope.loggedUser){
                Restangular.one('user/check').get().then(function (user) {
                    $rootScope.loggedUser = user;
                },function(error){
                    $window.location = error.data;
                });
            }
        };

        factory.getUser = function() {
            return $rootScope.loggedUser;
        };

        factory.reload = function(){
            $route.reload();
        };


/*
        factory.init = function (delivery) {
            if($rootScope.activeUser){
                delivery();
            }else{
                Restangular.one('user').get().then(function (user) {
                    if(user){
                        $rootScope.activeUser = user;
                        delivery();
                    }else{
                        location.path('login');
                    }
                })
            }
            //$rootScope.notifications = [];
        };
 */

        return factory;

    });
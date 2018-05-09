'use strict';

var angularApp = angular.module('Admin',[
        'angular-growl',
        'ngRoute',
        'restangular',
        'ui.bootstrap'
    ])
    .config(function ($routeProvider,RestangularProvider,$locationProvider) {

        RestangularProvider.setBaseUrl('/api');

        $locationProvider.hashPrefix('!');

        $routeProvider
            .when('/',{
                templateUrl: 'admin-app/views/home.html',
                controller: 'HomeController'
            })

            .when('/profile', {
                templateUrl: 'admin-app/profile/profilev1.html',
                controller: 'profileCtrl'
            })
            .when('/modulos', {
                templateUrl: 'admin-app/views/modules/modulos.html',
                controller: 'ModuloCtrl'
            })
            .when('/grupos', {
                templateUrl: 'admin-app/views/grupos/view-read-groups.html',
                controller: 'grupoReadCtrl'
            })

            .when('/grupos/create', {
                templateUrl: 'admin-app/views/grupos/view-create-groups.html',
                controller: 'grupoCreateCtrl'

            })

            .when('/usuarios', {
                templateUrl: 'admin-app/views/usuario/view-crud-read.html',
                controller: 'usuarioReadCtrl'

            })
            .when('/usuario/edit/:id', {
                templateUrl: 'admin-app/views/usuario/view-crud-update.html',
                controller: 'usuarioEditCtrl'

            })

            .when('/recovery/:token', {
                templateUrl: 'admin-app/profile/view-modal-recovery-password.html',
                controller: 'recoveryCtrl'

            })

            .when('/stock', {
                templateUrl: 'admin-app/views/stock/view-read-stock.html',
                controller: 'stockReadCtrl'

            })

            .otherwise({
                redirectTo: '/'
            });


    });
/*

var angularApp = angular.module('prefab', [
    'ngRoute',
    'ngSanitize',
    'httpInterceptor',
    'resources',
    'services',
    'security',
    'ui',
    'ui.bootstrap',
    'ngAnimate',
    'angular-growl',
    'underscore',
    'restangular',
    'momentjs',
    'duScroll',
    'LocalStorageModule',
    'angular-google-analytics'
]).config(function($routeProvider){

    $routeProvider
        .when('/',{
            templateUrl: 'admin-app/views/home.html',
            controller: 'HomeController'
        })
        .otherwise({
            redirectTo: '/'
        });

});


*/

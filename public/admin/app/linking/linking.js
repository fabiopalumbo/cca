angular.module('linking', [])
    .config(function ($routeProvider) {
        $routeProvider.when('/linking',{
            templateUrl:'app/linking/linking.html',
            controller:'LinkingCtrl'
        });
    });
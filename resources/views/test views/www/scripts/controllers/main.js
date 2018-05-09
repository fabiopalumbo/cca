'use strict';

angular.module('newProject')
    .controller('MainCtrl',function ($scope, $location, $rootScope, $cookies, translationService, accountsService) {
        var First = true;
        $rootScope.redirect = function(url){
           $location.path(url);
        };
        translationService.getTranslation($scope, $cookies.lang || 'es');
        $scope.printLang = function(){
            if(First){
                translationService.getTranslation($scope, $cookies.lang || 'en');
                First = false;
            }
            else{
                translationService.getTranslation($scope, $cookies.lang || 'es');
                First = true;
            }
        };
        $scope.getAccounts = function(){
            accountsService.getUsers().then(function(){
                console.log($rootScope.accounts);
            });
        };
    });


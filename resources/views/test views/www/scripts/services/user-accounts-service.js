'use strict';

angular.module('newProject')
    .service('accountsService', function($http) {
        this.getUsers= function($scope,$rootScope) {
            var accountsFilePath = '/assets/json/accounts/users.json';
            /*
             $resource(languageFilePath).get(function (data) {
                $scope.translation = data;
             });
             */

            $http.get(accountsFilePath)
                .then(function(res){
                    $rootScope.accounts = res.data;
                });

        };
    });

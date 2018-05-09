'use strict';

angular.module('newProject')
    .controller('HomeCtrl', function ($scope, $location, $rootScope) {
        $scope.jesus = 'jesus';
        console.log($scope.jesus);
    });

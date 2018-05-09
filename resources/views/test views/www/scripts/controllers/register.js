'use strict';

angular.module('newProject')
    .controller('RegisterCtrl', function ($scope, $location, $rootScope) {
        $scope.user = {
            name: '',
            surname: '',
            email: '',
            phone: '',
            password: '',
            passwordRepeat: ''
        };
        $scope.inputType = 'password';
        $scope.hideShowPassword = function() {
            if ($scope.inputType == 'password')
                $scope.inputType = 'text';
            else
                $scope.inputType = 'password';
        };
        $scope.printValues = function(){
            console.log($scope.user);
        };
    });


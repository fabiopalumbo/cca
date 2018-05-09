'use strict';


  angularApp.controller('LoginCtrl2', function ($scope, $location, $rootScope) {
        $scope.user = {
            email: '',
            password: ''
        };
        $scope.inputType = 'password';
        $scope.hideShowPassword = function(){
            if($scope.inputType == 'password')
                $scope.inputType = 'text';
            else
                $scope.inputType = 'password';
        };
        $scope.printValues = function(){
            console.log($scope.user);
        };
    });


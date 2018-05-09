'use strict';

angularApp.controller('HomeController',[ '$scope', '$modal','GlobalFactory','$location','$rootScope','Restangular','$rootScope',function($scope, $modal,GlobalFactory,$location,$rootScope){
    GlobalFactory.init();
    $scope.loadImage = function(){
        $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';
    };
    if($location.path() == '/'){
        $scope.homeLocation = true;
    } else $scope.homeLocation = false;
    $scope.$watch(function(){
        return $rootScope.loggedUser;
    },function(newVal,oldVal){
        if(newVal != oldVal){
            $scope.loggedUser = newVal;
            $scope.loadImage();
        }
    });

}]);


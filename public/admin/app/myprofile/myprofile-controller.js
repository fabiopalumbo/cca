angular.module('myprofile').controller('MyprofileListCtrl', [
  '$scope','$rootScope','pinesNotifications','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,pinesNotifications,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    //var initSpinner = true;
    $scope.ngCrop = false;
    GlobalFactory.spinner(document.getElementById('myprofile-spinner'),'spin');
    GlobalFactory.getUserAndDealer().done(function(obj){
      GlobalFactory.spinner(document.getElementById('myprofile-spinner'),'stop');
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;
      $scope.myImage='';
      $scope.myCroppedImage='';

      var rand = function() {
        return Math.random().toString(36).substr(2); // remove `0.`
      };

      var token = function() {
        return rand() + rand(); // to make it longer
      };

      $scope.save = function(){
        GlobalFactory.spinner(document.getElementById('myprofile-crop-spinner'),'spin');
        $scope.obj = [{
            filename: token(),
            file: $scope.myImage
          },{
            filename: token(),
            file: $scope.myCroppedImage
          }];
        $resource('/api/user/image/upload').save($scope.obj,function(){
          $resource('/api/user/check').get(function (user) {
            if(!user.$resolved) {
              GlobalFactory.notify('Se perdió la sesión del Usuario','alert-danger');
              $rootScope.loggedUser = null;
              setTimeout($window.location.href = '/login',500);
            } else {
              $scope.cancel();
              GlobalFactory.notify('Nueva imágen subida correctamente','alert-success');
              setTimeout($window.location.reload(),500);
            }
          },function(error){
              $window.location = error.data;
          });
        },function(error){
          $scope.cancel();
          GlobalFactory.throwAnError(error);
        });
      };

      $scope.cancel = function(){
        $scope.myImage='';
        $scope.myCroppedImage='';
        $scope.ngCrop= false;
        $('#fileInput').wrap('<form>').closest('form').get(0).reset();
        $('#fileInput').unwrap();
      };

      var handleFileSelect=function(evt) {
        var file=evt.currentTarget.files[0];
        var reader = new FileReader();
        reader.onload = function (evt) {
          $scope.$apply(function($scope){
            $scope.ngCrop = true;
            $scope.filename = file.name;
            $scope.myImage=evt.target.result;
          });
        };
        reader.readAsDataURL(file);
      };
      angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);
    });
  }]);

angular.module('myprofile').controller('MyprofileEditCtrl', [
  '$scope','$rootScope','pinesNotifications','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,pinesNotifications,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {


  }]);
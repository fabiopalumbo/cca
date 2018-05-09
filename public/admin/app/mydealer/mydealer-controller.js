angular.module('mydealer').controller('MydealerListCtrl', [
  '$scope','$rootScope','pinesNotifications','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,pinesNotifications,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    //var initSpinner = true;
    GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'spin');
    GlobalFactory.getUserAndDealer().done(function(obj){
      GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'stop');
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;
      console.log($scope.currentDealer);
      $scope.myImage='';
      $scope.myCroppedImage='';

      var rand = function() {
        return Math.random().toString(36).substr(2); // remove `0.`
      };

      var token = function() {
        return rand() + rand(); // to make it longer
      };

      $scope.save = function(){
        GlobalFactory.spinner(document.getElementById('mydealer-crop-spinner'),'spin');
        $scope.obj = [{
          filename: token(),
          file: $scope.myImage
        },{
          filename: token().concat('_cropped'),
          file: $scope.myCroppedImage
        }];
        $resource('/api/dealer/'+$scope.currentDealer.id+'/image/upload').save($scope.obj,function(){
          $scope.cancel();
          GlobalFactory.notify('Nueva imágen subida correctamente','alert-success');
          setTimeout($window.location.reload(),500);
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
            $scope.myImage=evt.target.result;
          });
        };
        reader.readAsDataURL(file);
      };
      angular.element(document.querySelector('#fileInput')).on('change',handleFileSelect);
    });
  }]);

angular.module('mydealer').controller('MydealerEditCtrl', [
  '$scope','$rootScope','pinesNotifications','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,pinesNotifications,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {


  }]);
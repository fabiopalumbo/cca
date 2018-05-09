angular.module('queries').controller('QueriesListCtrl', [
  '$scope','$rootScope','$routeParams','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,$routeParams,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {

    GlobalFactory.spinner(document.getElementById('queries-spinner'),'spin');
    GlobalFactory.getUserAndDealer().then(function(obj){
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;
      $scope.getQueries = function(){
        $scope.loading = true;
        GlobalFactory.spinner(document.getElementById('queries-table-spinner'),'spin');
        $resource('/api/dealer/'+$scope.currentDealer.id+'/questions').query(function(response){
          $scope.loading = false;
          GlobalFactory.spinner(document.getElementById('queries-table-spinner'),'stop');
          $scope.queries = response;
        }, function(error){
          $scope.loading = false;
          GlobalFactory.spinner(document.getElementById('queries-table-spinner'),'stop');
          if(error.status == 400){
          GlobalFactory.notify(error.data,'alert-danger');
          GlobalFactory.notify('Inicie sesión y actualize las consultas.','alert-danger');
          var x = screen.width/2 - 700/2;
          var y = screen.height/2 - 450/2;
          var method = 'login';
          var state = 'null,'+method+','+$scope.currentDealer.id;
          var url = "http://"+$location.host()+"/api/mercadolibre/app-login?state="+state;
          window.open(url,"Mercadolibre-Login","width=700,height=450,scrollbars=NO,left="+x+',top='+y);
        }else{
          GlobalFactory.spinner(document.getElementById('queries-modal-spinner'),'stop');
          GlobalFactory.throwAnError(error);
        }
        });
      };

      GlobalFactory.spinner(document.getElementById('queries-spinner'),'stop');
      $scope.getQueries();

      $scope.answerQuery = function(id,ver) {
        console.log('id: '+id);
        $scope.querySelected = _.findWhere($scope.queries,{id:id});
        if(ver) $scope.ver = true;
        else $scope.ver = false;
        $modal.open({
            templateUrl: '/admin/app/queries/queries-edit.html',
            controller: 'QueriesEditCtrl',
            keyboard: false,
            scope: $scope,
            size: 'lg',
            backdrop: 'static'
          }).result.then(
            function (callback) {
              if(callback == 'edited'){
                GlobalFactory.notify('Perfl editado correctamente.','alert-success');
              } else if(callback == 'created'){
                GlobalFactory.notify('Perfil creado correctamente.','alert-success');
              }
              //$scope.getQueries();
            }
          );
      };
    },function(){
      GlobalFactory.spinner(document.getElementById('queries-spinner'),'stop');
      GlobalFactory.throwAnError();
    });
  }]);

angular.module('queries').controller('QueriesEditCtrl', [
  '$scope','$rootScope','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {
    //$scope.queryId = $scope.$parent.selectedQueryId ? $scope.$parent.selectedQueryId : null;
    $scope.modalInstance = $modalInstance;
    $scope.object = {};
    $scope.currentTab = 0;
    $scope.loading = true;
    GlobalFactory.spinner(document.getElementById('queries-modal-spinner'),'spin');

    $scope.getQueries = function(){
      $resource('/api/dealer/'+$scope.currentDealer.id+'/contact/mercadolibre/questions/'+$scope.querySelected.id+'/vehicle/' + $scope.querySelected.vehicle_id).query(function(data){
        GlobalFactory.spinner(document.getElementById('queries-modal-spinner'),'stop');
        $scope.loading = false;
        $scope.object = data[0];
      },function(error){
        GlobalFactory.spinner(document.getElementById('queries-modal-spinner'),'stop');
        GlobalFactory.throwAnError(error);
      });
    };

    $scope.getQueries();

    $scope.answer = function() {
      var endpoint = $resource('/api/dealer/'+$scope.currentDealer.id+'/contact/mercadolibre/question/'+$scope.object.id+'/answer/vehicle/'+$scope.object.vehicle_id);
      endpoint.save({answer:$scope.object.query.answer},function(data){
        $scope.object.question.answer = $scope.object.query.answer;
      },function (error) {
        GlobalFactory.throwAnError(error);
      });
    };

    $scope.close = function(){
      $modalInstance.close();
    };


  }]);

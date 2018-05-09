angular.module('linking').controller('LinkingCtrl', [
    '$scope','$rootScope','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
    function($scope,$rootScope,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
      GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'spin');
      GlobalFactory.getUserAndDealer().done(function(obj){
        $scope.loggedUser = obj.user;
        $scope.currentDealer = obj.dealer;
        $scope.loadImage = function(){
          $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';
        };
        var links = $resource('/api/dealer/:id/linking/:portal',{id:'@id'});
        links.get({id:$scope.currentDealer.id},function(data){
          $scope.links = data;
          GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'stop');
          // console.log(data);
          // data = {
          //   mercadolibre  : {username:false, portal:'mercadolibre'},
          //   deautos       : {username:false, portal:'deautos'     },
          //   autocosmos    : {username:false, portal:'autocosmos'  },
          //   olx           : {username:false, portal:'olx'         },
          //   demotores     : {username:false, portal:'demotores'   },
          //   facebook      : {username:true , portal:'facebook'    }
          // };
          // $scope.links = data;
        });
        $scope.nuevaVentana = function(){
            var x = screen.width/2 - 700/2;
            var y = screen.height/2 - 450/2;
            var state =','+$scope.currentDealer.id;
            var url = "http://"+$location.host()+"/api/mercadolibre/app-login?state="+state;
            window.open(url,"Mercadolibre-Login","width=700,height=450,scrollbars=NO,left="+x+',top='+y);
        };
        $scope.link = function(data){
          data.linking = false;
          var p = {
            id: $scope.currentDealer.id,
            data: {
              portal: data.portal,
              data: JSON.stringify(data.data)
            }
          };
          // console.log(p);
          GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'spin');
          links.save(p,function(data){
            $scope.links = data;
            GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'stop');
          })
        };
        $scope.unlink = function(data){
          var p = {
            id: $scope.currentDealer.id,
            portal: data.portal
          };
          GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'spin');
          links.delete(p,function(data){
            $scope.links = data;
            GlobalFactory.spinner(document.getElementById('mydealer-spinner'),'stop');
          });
        };
      });
    }]);
'use strict';

angular.module('globalFactory',[])
    .factory('GlobalFactory', function( $rootScope, $modal, $resource, $location, $route, $window, notify) {

        $rootScope.notifications = [{link: '#/dsada',description:'dadasdasd'},{link: '#',description:'12356126357'}];
        $rootScope.locationSide='home';

        //Hacer despues que se oculte o muestre contraseña segun checkbox

        var factory = {};
        factory.getUser = function () {
            var deferred = $.Deferred();

            if(!$rootScope.loggedUser){
                $resource('/api/user/check').get(function (user) {
                  if(!user.$resolved) {
                    $rootScope.loggedUser = null;
                    $window.location.href = '/login';
                  }
                  else {
                    $rootScope.loggedUser = user;
                    deferred.resolve(user);
                  }
                },function(error){
                    $window.location = error.data;
                });
            } else{
              deferred.resolve($rootScope.loggedUser);
            }

            return deferred;
        };

        factory.getUserAndDealer = function(){
          var deferred = $.Deferred();
          var obj = {
            user:'',
            dealer:{}
          };

          if(!$rootScope.loggedUser){
            $resource('/api/user/check').get(function (user) {
              if(!user.$resolved) {
                $rootScope.loggedUser = null;
                $window.location.href = '/login';
              }
              else {
                $rootScope.loggedUser = user;
                obj.user = user;
                factory.getDealer().done(function(dealer){
                  obj.dealer = dealer;
                  deferred.resolve(obj);
                });
              }
            },function(error){
                $window.location = error.data;
            });
          } else{
            obj.user = $rootScope.loggedUser;
            factory.getDealer().done(function (dealer) {
              obj.dealer = dealer;
              deferred.resolve(obj);
            });
          }

          return deferred;

        };

        factory.getDealer = function (){
          var deferred = $.Deferred();
          var dealers_url = $resource('/api/dealer');

          if(!$rootScope.currentDealer){
            dealers_url.query(function(dealers){
              if(dealers.length == 1){
                $rootScope.hasMultipleDealers = false;
                $rootScope.currentDealer = dealers[0];
                deferred.resolve(dealers[0]);
              } else {
                $rootScope.hasMultipleDealers = true;
                $modal.open({
                  templateUrl: '/admin/app/components/navbar/modal-select-dealer.html',
                  controller: 'SelectDealerModalCtrl',
                  size: 'sm',
                  backdrop: 'static',
                  keyboard: false,
                  resolve:{
                    Dealers: function(){
                      return dealers;
                    }
                  }
                }).result.then(
                  function (result) {
                    if(result){
                      $rootScope.currentDealer = result;
                      deferred.resolve(result);
                    }
                  }
                );
              }
            },function(error){
              if (error.status == 401) {
                factory.notify('Acceso no autorizado.','alert-danger');
                $location.path("/");
              } else {
                factory.notify('Se ha producido un error. Intentlo nuevamente.','alert-danger');
              }
            });
          } else {
            deferred.resolve($rootScope.currentDealer);
          }

          return deferred;
        };

        factory.notify = function(message,clase){
          notify({message: message, classes: clase, position: 'right', duration: 7000});
        };

        var spinner;
        var opts = {
          lines: 17 // The number of lines to draw
          , length: 3 // The length of each line
          , width: 2 // The line thickness
          , radius: 10 // The radius of the inner circle
          , scale: 2.25 // Scales overall size of the spinner
          , corners: 1 // Corner roundness (0..1)
          , color: '#242424' // #rgb or #rrggbb or array of colors
          , opacity: 0.15 // Opacity of the lines
          , rotate: 0 // The rotation offset
          , direction: 1 // 1: clockwise, -1: counterclockwise
          , speed: 1.2 // Rounds per second
          , trail: 50 // Afterglow percentage
          , fps: 20 // Frames per second when using setTimeout() as a fallback for CSS
          , zIndex: 1200 // The z-index (defaults to 2000000000)
          , className: 'spinner' // The CSS class to assign to the spinner
          , top: '50%' // Top position relative to parent
          , left: '50%' // Left position relative to parent
          , shadow: false // Whether to render a shadow
          , hwaccel: false // Whether to use hardware acceleration
          , position: 'relative' // Element positioning
        };

        factory.spinner = function(target,todo){
          if(todo == 'spin'){
            if(!spinner){
              spinner = new Spinner(opts);
              spinner.spin(target);
            } else if(spinner){
              spinner.spin(target);
            }

          } else if(todo == 'stop'){
            if(spinner){
              spinner.stop(target);
            }
          }
        };

        factory.throwAnError = function(error){
            if (error && error.status == 401) {
                factory.notify('Acceso no autotrizado.','alert-danger');
                $location.path('/admin');
            } else if(error && error.status == 500){
                console.log('error 500');
                factory.notify('Se ha producido un error. Intentelo nuevamente.','alert-danger');
            } else factory.notify('Se ha producido un error. Intentelo nuevamente.','alert-danger');
        };

        factory.reload = function(){
            $route.reload();
        };


/*
        factory.init = function (delivery) {
            if($rootScope.activeUser){
                delivery();
            }else{
                Restangular.one('user').get().then(function (user) {
                    if(user){
                        $rootScope.activeUser = user;
                        delivery();
                    }else{
                        location.path('login');
                    }
                })
            }
            //$rootScope.notifications = [];
        };
 */

        return factory;

    });
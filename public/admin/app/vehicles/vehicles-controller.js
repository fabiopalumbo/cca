angular.module('vehicles').controller('VehiclesListCtrl', [
  '$scope','$rootScope','$routeParams','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,$routeParams,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    if($routeParams.share){
      $scope.share = true;
      $scope.ver = true;
      GlobalFactory.spinner(document.getElementById('vehicles-share-spinner'),'spin');
    } else {
      $scope.share = false;
      $scope.ver = false;
      GlobalFactory.spinner(document.getElementById('vehicles-spinner'),'spin');
    }
    GlobalFactory.getUserAndDealer().then(function(obj){
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;

      var vehicles;
      if($scope.share) vehicles = $resource('/api/dealer/'+$scope.currentDealer.id+'/nuestros-autos/vehicles/share');
      else vehicles = $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles');
      var brands = $resource('/api/vehicle/brands');
      var colors = $resource('/api/vehicle/colors');
      var fuels = $resource('/api/vehicle/fuels');
      var models = $resource('/api/vehicle/models');
      var types = $resource('/api/vehicle/types');
      var features = $resource('/api/vehicle/features');
      var currencies = $resource('/api/vehicle/currencies');
      var listingTypes = $resource('/api/mercadolibre/listing-types');
      if($rootScope.verComo && $rootScope.verComo == 'tabla') $scope.verComo = 'tabla';
      else $scope.verComo = 'galeria';
      $scope.object = {};
      $scope.object.note = '';
      $scope.brandsFilter = {};
      $scope.modelsFilter = {};
      $scope.versionsFilter = {};
      $scope.showVersions = false;
      $scope.showTable = false;
      $scope.firstMl = true;
      $scope.firstFb = true;
      $scope.firstDa = true;
      $scope.items = {
        ML: false,
        NA: false,
        OLX : false,
        AC : false,
        AF : false,
        DA : false,
        FB : false
      };
      $scope.filters = {
        brand: '',
        fuel: '',
        model: '',
        page: '',
        tag: '',
        type: '',
        version:''
      };

      $scope.fillTable = function(result){
        if($scope.share) GlobalFactory.spinner(document.getElementById('vehicles-share-table-spinner'),'stop');
        else GlobalFactory.spinner(document.getElementById('vehicles-table-spinner'),'stop');
        $scope.showTable = true;
        $scope.vehicles = result.data;
        $scope.maxSize = 5;
        $scope.totalItems = result.total;
        $scope.itemsPerPage = result.per_page;
        $scope.currentPage = result.current_page;
      };

      $scope.getVehicles = function(page){
        $scope.showTable = false;
        if($scope.share) GlobalFactory.spinner(document.getElementById('vehicles-share-table-spinner'),'spin');
        else GlobalFactory.spinner(document.getElementById('vehicles-table-spinner'),'spin');
        if(page) $scope.filters.page = $scope.currentPage;
        vehicles.get($scope.filters, function(response){
          $scope.fillTable(response);
        }, function(error){
          if($scope.share) GlobalFactory.spinner(document.getElementById('vehicles-share-table-spinner'),'stop');
          else GlobalFactory.spinner(document.getElementById('vehicles-table-spinner'),'stop');
          GlobalFactory.throwAnError(error);
        });
      };

      brands.query(function(data){
        $scope.brands = data;
        colors.query(function(data){
          $scope.colors = data;
          fuels.query(function(data){
            $scope.fuels = data;
          listingTypes.query(function(data){
            $scope.listingTypes = data;
            models.query(function(data){
              $scope.models = data;
              types.query(function(data){
                $scope.types = data;
                currencies.query(function(data){
                  $scope.currencies = data;
                  $scope.getVehicles(false);
                  features.query(function(data){
                    $scope.features = data;
                    if($scope.share) GlobalFactory.spinner(document.getElementById('vehicles-share-spinner'),'stop');
                    else GlobalFactory.spinner(document.getElementById('vehicles-spinner'),'stop');
                 },function(error){GlobalFactory.throwAnError(error)});
                },function(error){GlobalFactory.throwAnError(error)});
               },function(error){GlobalFactory.throwAnError(error)});
              },function(error){GlobalFactory.throwAnError(error)});
             },function(error){GlobalFactory.throwAnError(error)});
            },function(error){GlobalFactory.throwAnError(error)});
          },function(error){GlobalFactory.throwAnError(error)});
        },function(error){GlobalFactory.throwAnError(error)});

      $scope.pageChanged = function() {
        $scope.getVehicles(true);
      };

      $scope.changeVerComo = function(verComo){
        $scope.verComo = verComo;
        $rootScope.verComo = verComo;
      };

      $scope.filter = function(tag){
        if(tag) $scope.filters.tag = '';
        if($scope.filters.fuel == undefined) $scope.filters.fuel = '';
        if($scope.filters.brand == undefined) $scope.filters.brand = '';
        if($scope.filters.model == undefined) $scope.filters.model = '';
        if($scope.filters.version == undefined) $scope.filters.version = '';
        if($scope.filters.page == undefined) $scope.filters.page = '';
        if($scope.filters.type == undefined) $scope.filters.type = '';
        if($scope.filters.type != undefined && $scope.filters.type != '') $scope.brandsFilter.type_vehicle_id = $scope.filters.type;
        else $scope.brandsFilter = {};
        $scope.getVehicles(false);
      };

      $scope.remove = function(id) {
        var removeVehicle = $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles/'+id);
        removeVehicle.delete(function (data) {
          GlobalFactory.notify('Vehículo eliminado correctamente.','alert-success');
          $scope.getVehicles(false);
        }, function (error) {
          GlobalFactory.throwAnError(error);
        });
      };

      $scope.openPublishVehicle = function(id) {
        $scope.showPublishTab = true;
        $scope.openEditVehicle(id);
      };
      $scope.openEditVehicle = function(id,ver) {
        $scope.selectedVehicleId = id;
        $scope.ver = !!ver;
        $modal.open({
            templateUrl: '/admin/app/vehicles/vehicles-edit.html',
            controller: 'VehiclesEditCtrl',
            keyboard: false,
            scope: $scope,
            size: 'lg',
            backdrop: 'static'
          }).result.then(
            function (callback) {
              if(callback == 'edited'){
                GlobalFactory.notify('Vehículo editado correctamente.','alert-success');
                var updatePortals = $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles/'+$scope.selectedVehicleId+'/update-portals');
                updatePortals.save(function(data){
                  if(data) GlobalFactory.notify(data,'alert-danger');
                },function(error){
                  //GlobalFactory.notify('No se pudo actualizar el vehiculo en alguno de los portales publicados','alert-danger');
                });
              } else if(callback == 'created'){
                GlobalFactory.notify('Vehículo creado correctamente.','alert-success');
              }
              $scope.getVehicles(false);
              $scope.showPublishTab = false;
            }
          );
      };
    },function(){
      GlobalFactory.spinner(document.getElementById('vehicles-spinner'),'stop');
      GlobalFactory.throwAnError();
    });
  }]);

angular.module('vehicles').controller('VehiclesEditCtrl', [
  '$scope','$rootScope','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {

    var vehicle = $resource('/api/dealer/'+$scope.currentDealer.id +'/vehicles/:id',{id:'@id'});
    $scope.vehicleId = $scope.$parent.selectedVehicleId ? $scope.$parent.selectedVehicleId : null;
    $scope.modalInstance = $modalInstance;
    $scope.filters = {};
    $scope.featuresEdit = angular.copy($scope.features);
    $scope.object = {};
    $scope.object.note = '';
    $scope.currentTab = 0;
    $scope.showModalBody = false;
    $scope.MlInState = false;
    $scope.hideModels = true;
    $scope.hideBrands = true;
    $scope.hideVersions = true;

    $scope.conditions = [
      {val: '0km',name:'0km'},
      {val: 'Usado',name:'Usado'},
      {val: 'Consignación',name:'Consignación'}
    ];

    $scope.transmissions = [
      {val: 'CVT',name:'CVT'},
      {val: 'Secuencial',name:'Secuencial'},
      {val: 'Manual',name:'Manual'},
      {val: 'Automática',name:'Automática'},
      {val: 'N/E Autos',name:'N/E Autos'}
    ];

    $scope.heatings = [
      {val: 'N/E',name:'N/E'},
      {val: 'Doble aire',name:'Doble aire'},
      {val: 'Calefacción',name:'Calefacción'},
      {val: 'Aire acondicionado',name:'Aire acondicionado'}
    ];

    $scope.directions = [
      {val:'Mecánica', name:'Mecánica'},
      {val:'Hidráulica', name:'Hidráulica'},
      {val:'Asistida', name:'Asistida'}
    ];

    $('[data-toggle=tooltip]').hover(function(){
      $(this).tooltip('show');
    }, function(){
      $(this).tooltip('hide');
    });

    window.fbAsyncInit = function() {
      FB.init({
        appId      : '1131927070172203',
        cookie     : true,
        version    : 'v2.2'
      });
    };
    
    (function(d, s, id){
      var js, fjs = d.getElementsByTagName(s)[0];
      if (d.getElementById(id)) return;
      js = d.createElement(s); js.id = id;
      js.src = "https://connect.facebook.net/es_LA/sdk.js";
      fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    $scope.logInWithFacebook = function() {
      FB.getLoginStatus(function (response) {
        if(response.status === 'connected'){
          if($scope.publishItems.facebook == true){
            $scope.items.FB = true;
            $scope.firstFb = true;
          }else{
            //GlobalFactory.notify('Ya se encuentra logueado.', 'alert-success');
            $scope.items.FB = false;
            $scope.firstFb = false;
            var accessToken = response.authResponse.accessToken;
            console.log(accessToken);
            $resource('/api/facebook/pages').query({token:accessToken},function(result) {
              setTimeout(function(){$scope.object.fbPage = $scope.userPages[0].page_id});
              $scope.userPages = result;
              console.log(result);
            });

          }
        }else {
          FB.login(function (response) {
            if (response.authResponse) {
              $resource('/api/facebook/login').query(function (result) {
                setTimeout(function(){$scope.object.fbPage = $scope.userPages[0].page_id});
                GlobalFactory.notify('Se ha logueado correctamente.', 'alert-success');
                $scope.userPages = result;
                console.log(result);
                if($scope.publishItems.facebook == true){
                  $scope.items.FB = true;
                  $scope.firstFb = true;
                }else{
                  $scope.items.FB = false;
                  $scope.firstFb = false;
                }
              });
            } else {
              GlobalFactory.notify('Usuario incorrecto o no autorizado.', 'alert-danger');
            }
          },{
            scope: 'manage_pages,publish_pages,publish_actions,public_profile',
            return_scopes: true
          });
        }
        return false;
      });
    };

    $scope.verifyState = function(){
      $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles/'+ $scope.vehicleId +'/mercadolibre/verify').get(function(result){
        if(result.item_id){
          $scope.MlInState = false;
          $scope.publishItems.mercadolibre = true;
        }else{
          $scope.MlInState = false;
          $scope.publishItems.mercadolibre = false;
          $scope.firstMl = true;
        }
      });
    };

    $scope.nuevaVentana = function(){
      var x = screen.width/2 - 700/2;
      var y = screen.height/2 - 450/2;
      var state = $scope.object.token+','+$scope.currentDealer.id;
      var url = "http://"+$location.host()+"/api/mercadolibre/app-login?state="+state;
      window.open(url,"Mercadolibre-Login","width=700,height=450,scrollbars=NO,left="+x+',top='+y);
    };

    $scope.publish = function(publishTo){
      if(publishTo == 'mercadolibre'){
        $scope.items.NA = false;
        $scope.items.FB = false;
        $scope.items.AC = false;
        $scope.items.DA = false;
        $scope.firstFb = true;
        $scope.firstDa = true;
        if($scope.publishItems.mercadolibre == true){
            $scope.items.ML = true;
            $scope.firstMl = true;
        }else{
            $scope.items.ML = false;
            $scope.firstMl = false;
        }
        if($scope.items.ML == false){
            $scope.MlInState = true;
        }else{
            $scope.MlInState = false;
        }

      // }else if(publishTo == 'nuestrosautos'){
      //   $scope.items.ML = false;
      //   $scope.items.FB = false;
      //   $scope.items.AC = false;
      //   $scope.items.DA = false;
      //   $scope.firstFb = true;
      //   $scope.firstDa = true;
      //   $scope.firstMl = true;
      //   $scope.publishItems.nuestrosAutos == true ? $scope.items.NA = true : $scope.items.NA = false;
      //   if(!$scope.publishItems.nuestrosAutos){
      //     var endpoint = '/api/dealer/'+$scope.currentDealer.id+'/vehicle/'+$scope.vehicleId+'/share';
      //     $resource(endpoint).save(function(data){
      //       GlobalFactory.notify('El vehiculo se compartio correctamente.','alert-success');
      //       $scope.publishItems.nuestrosAutos = true;
      //       console.log($scope.publishItems.nuestrosAutos);
      //     },function(error){
      //       GlobalFactory.notify('El vehiculo ya se encuentra compartido.','alert-danger');
      //     });
      //   }
      }else if(publishTo == 'facebook'){
        $scope.logInWithFacebook();
      }else if(publishTo == 'autocosmos'){
        $scope.items.ML = false;
        $scope.items.FB = false;
        $scope.items.DA = false;
        $scope.firstFb = true;
        $scope.firstDa = true;
        $scope.firstMl = true;
        $scope.items.NA = false;
        $scope.publishItems.autoCosmos == true ? $scope.items.AC = true : $scope.items.AC = false;
        if(!$scope.publishItems.autoCosmos){
          var publishNuestrosAutos = '/api/dealer/'+$scope.currentDealer.id+'/autocosmos/vehicle/'+$scope.vehicleId+'/publish';
          $resource(publishNuestrosAutos).save(function(data){
            if(data.data != 'OK'){
              $scope.portal = 'autocosmos';
              $scope.brandSelected = (data.brand == undefined) ? null :  data.brand ;
              // $scope.modelSelected = (data.model == undefined) ? null : data.model;
              // $scope.versionSelected = (data.version == undefined) ? $scope.object.version_id : data.version;
              $scope.brands = _.filter($scope.brands, function(brand){ return brand.autocosmos_id;});
              $scope.models =  _.filter($scope.models, function(model){ return model.autocosmos_id != null;});
              $modal.open({
                templateUrl: '/admin/app/vehicles/vehicles-conflicts.html',
                controller: 'VehiclesConflictCtrl',
                keyboard: false,
                scope: $scope,
                size: 'md',
                backdrop: 'static'
              }).result.then(
                  function (){
                    GlobalFactory.notify('Ya es posible publicarlo en Autocosmos.','alert-success');
                  }
              );

            }else{
              GlobalFactory.notify('El vehiculo se publico correctamente.','alert-success');
              $scope.publishItems.autoCosmos = true;
              $scope.publishItems.nuestrosAutos = true;
            }
          },function(error){
            $scope.publishItems.autoCosmos = false;
            GlobalFactory.notify(error.data,'alert-danger');
          });
        }
      }else if(publishTo == 'deautos'){
        $scope.items.ML = false;
        $scope.items.FB = false;
        $scope.firstFb = true;
        $scope.firstMl = true;
        $scope.items.AC = false;
        $scope.items.NA = false;
        $scope.publishItems.deAutos == true ? $scope.items.DA = true : $scope.items.DA = false;
        $scope.publishItems.deAutos == true ? $scope.firstDa = true: $scope.firstDa = false;
      }
    };

    $scope.doOnEdit = function(){
      $('[data-toggle=tooltip]').hover();
      if($scope.$parent.showPublishTab) setTimeout(function(){$('li[heading="Publicaciones"] a').click();});
      vehicle.get({id:$scope.vehicleId},function(data){
        $scope.object = data;
        $scope.object.brand = _.findWhere($scope.brands,{id:$scope.object.brand_id}).name;
        $scope.object.model = (function(l,id){for(var i=0,item;item=l[i++];)if(item.id==id)return item.name;})($scope.models,$scope.object.model_id);
        $scope.object.fuelName = (function(l,id){for(var i=0,item;item=l[i++];)if(item.id==id)return item.name;})($scope.fuels,$scope.object.type_vehicle_fuel_id);
        $scope.publishItems = data.publish;
        console.log($scope.publishItems);
        $resource('/api/vehicles/model/'+$scope.object.model_id+'/versions').query(function(result){
          if(result.length > 0){
            $scope.versions = result;
            $scope.object.version = (function(l,id){for(var i=0,item;item=l[i++];)if(item.id==id)return item.name;})($scope.versions,$scope.object.version_id);
            $scope.showVersions = true;
          }else $scope.showVersions = false;
        });

          data.features.forEach(function(dataFeature){
              $scope.featuresEdit.forEach(function(feature){
                  feature.children.forEach(function(child){
                      if(child.id == dataFeature.id){
                          child.val = true;
                      }
                  });
              });
          });

        $scope.showModalBody = true;
      });
    };

    if($scope.vehicleId) {
        $scope.doOnEdit();
    }else {
        setTimeout(function(){$scope.showModalBody = true;});
    }

    $scope.filterKMs = function(condition){
      if(condition == '0km') $scope.object.kilometers = '0';
    };

    $scope.filterModels = function(id){
      if(id){
        $scope.hideModels = false;
        $scope.filters.type_vehicle_brand_id = id;
      } else $scope.hideModels = true;
    };

    $scope.filterVersions = function(id){
      $resource('/api/vehicles/model/'+id+'/versions').query(function(result){
        if(result.length > 0){
          $scope.versions = result;
          $scope.showVersions = true;
        } else $scope.showVersions = false;
      });
    };

    $scope.filterBrands = function(id){
      if(id){
        $scope.hideBrands = false;
        $scope.filters.type_vehicle_id = id;
      } else $scope.hideBrands = true;
    };

    $scope.cleanVersions = function(){
      $scope.versions = '';
      $scope.showVersions = false;
      $scope.object.model_id = null;
    };

    $scope.getVehiclesImages = function(){
      $scope.vehicleImagesUrl = '/api/dealer/'+$scope.currentDealer.id+'/vehicle/'+$scope.vehicleId+'/image/upload';
      GlobalFactory.spinner(document.getElementById('vehicles-images-spinner'),'spin');
      $scope.spinning = true;
      if($scope.vehicleId){
        $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles/'+ $scope.vehicleId +'/images').get(function(result){
          $scope.vehiclesImages = result.images;
          if(result.vehicle_image_highlighted_id){
            $scope.highlighted_id = result.vehicle_image_highlighted_id;
          }
          GlobalFactory.spinner(document.getElementById('vehicles-images-spinner'),'stop');
          $scope.spinning = false;
        });
      }
    };

    $scope.setListingType = function(id){
      var listingType =  $resource('/api/dealer/'+$scope.currentDealer.id+'/mercadolibre/vehicle/'+$scope.vehicleId+'/listing-type');
      var publish =  $resource('/api/dealer/'+$scope.currentDealer.id+'/mercadolibre/vehicle/'+$scope.vehicleId+'/publish');
        listingType.save({listing_type:id},function(data){
        if(data.data != 'OK'){
          $scope.listing  = id;
          $scope.portal = 'mercadolibre';
          $scope.brandSelected = (data.brand == undefined) ? null :  data.brand ;
          // $scope.modelSelected = (data.model == undefined) ? null : data.model;
          // $scope.versionSelected = (data.version == undefined) ? $scope.object.version_id : data.version;
          $scope.brands = _.filter($scope.brands, function(brand){ return brand.mercadolibre_id;});
          $scope.models =  _.filter($scope.models, function(model){ return model.mercadolibre_id != null;});
          $scope.versions =  _.filter($scope.versions, function(version){ return version.mercadolibre_id != null;});
          $scope.firstMl = true;
          $modal.open({
            templateUrl: '/admin/app/vehicles/vehicles-conflicts.html',
            controller: 'VehiclesConflictCtrl',
            keyboard: false,
            scope: $scope,
            size: 'md',
            backdrop: 'static'
          }).result.then(
              function (){
                GlobalFactory.notify('Ya es posible publicarlo en Mercadolibre.','alert-success');
                  $scope.verifyState();
              }
          );
        }else{
            publish.save(function(data){
                GlobalFactory.notify('Ya está publicado en Mercadolibre.','alert-success');
                $scope.verifyState();
            },function(error){
                if(error.data == 'no hay cuenta vinculada' || error.data == 'not found'){
                    GlobalFactory.notify('Debe vincular la cuenta de Mercadolibre antes de continuar.','alert-danger');
                    $scope.nuevaVentana();
                }else if(error.data == 'refreshed'){
                    $scope.setListingType();
                }else if(error.data == 'validation_error'){
                    GlobalFactory.notify('La categoría de publicación seleccionada (oro/gratis/etc) no está disponible.','alert-danger');
                }else if(error.data == 'seller.unable_to_list'){
                    GlobalFactory.notify('Error con el usuario vinculado.','alert-danger');
                }else{
                    GlobalFactory.notify(error.data,'alert-danger');
                }
                $scope.verifyState();
            });
        }
      });
    };

    $scope.publishFB = function(){
      if(!$scope.object.fbPage) GlobalFactory.notify('Debe elegir una pagina de facebook para publicar.','alert-danger');
      else{
        GlobalFactory.spinner(document.getElementById('vehicles-publish-spinner'),'spin');
        $scope.userPages.forEach(function(userPage){
          if(userPage.page_id == $scope.object.fbPage){
            $scope.fbObject = {
              id : $scope.object.fbPage,
              note : $scope.object.pageNote,
              token: userPage.page_token
            };
          }
        });

        var publishFB =  $resource('/api/dealer/'+$scope.currentDealer.id+'/facebook/vehicle/'+$scope.vehicleId+'/share');
        publishFB.save({page:$scope.fbObject},function(data){
          GlobalFactory.notify('Publicacion compartida correctamente.','alert-success');
          $scope.publishItems.facebook = true;
          $scope.firstFb = true;
          $scope.publishItems.nuestrosAutos = true;
          GlobalFactory.spinner(document.getElementById('vehicles-publish-spinner'),'stop');
        },function(error){
          GlobalFactory.notify(error.data,'alert-danger');
          GlobalFactory.spinner(document.getElementById('vehicles-publish-spinner'),'stop');
        });
      }
    };
    $scope.publishDM = function(){
        $resource('/api/dealer/'+$scope.currentDealer.id+'/demotores/vehicle/'+$scope.vehicleId+'/publish').save(function(data){
            if(data.data != 'OK'){
                $scope.portal = 'demotores';
                 $scope.brandSelected = (data.brand == undefined) ? null :  data.brand ;
                // $scope.modelSelected = (data.model == undefined) ? null : data.model;
                // $scope.versionSelected = (data.version == undefined) ? null : data.version;
                $scope.brandShow = (data.brand_name == undefined) ? null :  data.brand_name ;
                $scope.modelShow = (data.model_name == undefined) ? null : data.model_name;
                $scope.versionShow = (data.version_name == undefined) ? null : data.version_name;
                $scope.brands = _.filter($scope.brands, function(brand){ return brand.demotores_id;});
                $scope.models =  _.filter($scope.models, function(model){ return model.demotores_id ;});
                $scope.versions =  _.filter($scope.versions, function(version){ return version.demotores_id != null;});
                $modal.open({
                    templateUrl: '/admin/app/vehicles/vehicles-conflicts.html',
                    controller: 'VehiclesConflictCtrl',
                    keyboard: false,
                    scope: $scope,
                    size: 'md',
                    backdrop: 'static'
                }).result.then(
                    function (){
                        GlobalFactory.notify('Ya es posible publicarlo en demotores.','alert-success');
                    }
                );
            }else{
                GlobalFactory.notify('El vehiculo se publico correctamente.','alert-success');
                $scope.items.DM = true;
                $scope.firstDM = true;
            }
        },function(error){
            GlobalFactory.notify(error.data,'alert-danger');
        });
    }

    $scope.publishDA = function(){
        $resource('/api/dealer/'+$scope.currentDealer.id+'/deautos/vehicle/'+$scope.vehicleId+'/login').save(function(data){
          if(data.data != 'OK'){
            $scope.portal = 'deautos';
            $scope.brandSelected = (data.brand == undefined) ? null :  data.brand ;
            // $scope.modelSelected = (data.model == undefined) ? null : data.model;
            // $scope.versionSelected = (data.version == undefined) ? null : data.version;
              $scope.brandShow = (data.brand == undefined) ? null :  data.brand ;
              $scope.modelShow = (data.model == undefined) ? null : data.model;
              $scope.versionShow = (data.version == undefined) ? null : data.version;
            $scope.brands = _.filter($scope.brands, function(brand){ return brand.deautos_id;});
            $scope.models =  _.filter($scope.models, function(model){ return model.deautos_id ;});
            $scope.versions =  _.filter($scope.versions, function(version){ return version.deautos_id != null;});
            $modal.open({
              templateUrl: '/admin/app/vehicles/vehicles-conflicts.html',
              controller: 'VehiclesConflictCtrl',
              keyboard: false,
              scope: $scope,
              size: 'md',
              backdrop: 'static'
            }).result.then(
                function (){
                  GlobalFactory.notify('Ya es posible publicarlo en deAutos.','alert-success');
                }
            );
          }else{
            GlobalFactory.notify('El vehiculo se publico correctamente.','alert-success');
            $scope.items.DA = true;
            $scope.firstDa = true;
          }
        },function(error){
          GlobalFactory.notify(error.data,'alert-danger');
        });
    };

    $scope.deleteDeautos = function(){
      var deleteDeautos = $resource('/api/dealer/'+$scope.currentDealer.id+'/deautos/vehicle/'+$scope.$parent.selectedVehicleId+'/unpublish');
      deleteDeautos.delete(function(data){
        GlobalFactory.notify('Publicacion eliminada correctamente.','alert-success');
        $scope.items.DA = false;
        $scope.publishItems.deAutos = false;
      });
    };

    $scope.cleanFlowAndReloadImages = function(flow){
      flow.cancel();
      $scope.getVehiclesImages();
    };

    $scope.highlightImage = function(id){
      if($scope.highlighted_id && $scope.highlighted_id != id){
        $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles/image/'+id+'/destacada').save({vehicle_id: $scope.vehicleId},function(){
          $scope.highlighted_id = id;
        },function(error){
          GlobalFactory.throwAnError(error);
        });
      } else if($scope.highlighted_id != id){
        $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles/image/'+id+'/destacada').save({vehicle_id: $scope.vehicleId},function(){
          $scope.highlighted_id = id;
        },function(error){
          GlobalFactory.throwAnError(error);
        });
      }
    };

    $scope.removeImage = function(id){
      $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicles/image/'+id).delete(function(){
        $scope.getVehiclesImages();
      });
    };

    $scope.deleteListingType = function(id){
      var deletelistingType = $resource('/api/dealer/'+$scope.currentDealer.id+'/mercadolibre/vehicle/'+$scope.$parent.selectedVehicleId+'/listing-type');
      deletelistingType.delete({item_id : id},function(data){
        $scope.object.item_id = null;
        GlobalFactory.notify('Publicacion eliminada correctamente.','alert-success');
        $scope.items.ML = false;
        $scope.publishItems.mercadolibre = false;
      },function(error){
        $scope.items.ML = false;
        GlobalFactory.notify(error.data,'alert-danger');
        if(error == 'Debe loguearse para eliminar la publicacion.'){
         $scope.nuevaVentana();
        }
      });
    };

    // $scope.deleteShare = function(id){
    //   var deleteShare = $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicle/'+$scope.vehicleId+'/share');
    //   deleteShare.delete(function(data){
    //     GlobalFactory.notify('Se dejó de compartir el vehiculo a la red de Nuestros Autos.','alert-success');
    //     $scope.items.NA = false;
    //     $scope.publishItems.nuestrosAutos = false;
    //   });
    // };

    $scope.deleteShareFacebook = function(){
      var deleteFacebookPost = $resource('/api/dealer/'+$scope.currentDealer.id+'/facebook/vehicle/'+$scope.$parent.selectedVehicleId+'/share');
      deleteFacebookPost.delete(function(data){
        GlobalFactory.notify('Publicacion eliminada correctamente.','alert-success');
        $scope.items.FB = false;
        $scope.publishItems.facebook = false;
      });
    };

    $scope.deleteAutocosmos = function(){
      var deleteAutocosmos = $resource('/api/dealer/'+$scope.currentDealer.id+'/autocosmos/vehicle/'+$scope.$parent.selectedVehicleId+'/unpublish');
      deleteAutocosmos.delete(function(data){
        GlobalFactory.notify('Publicacion eliminada correctamente.','alert-success');
        $scope.items.AC = false;
        $scope.publishItems.autoCosmos = false;
      });
    };

    $scope.save = function(something) {
      if(something) GlobalFactory.notify('Error al editar el vehículo. Revise los datos obligatorios.','alert-danger');
      else{
        var featuresArray = [];
        $scope.featuresEdit.forEach(function (feature) {
            if(feature.type_vehicle_id == $scope.object.type){
                feature.children.forEach(function(child){
                    if(child.val == true) {
                        featuresArray.push(child.id);
                    }
                });
            }
        });

        var endpoint = '/api/dealer/'+$scope.currentDealer.id+'/vehicles';
        var vehicleSave = $scope.vehicleId ? $resource(endpoint+'/'+$scope.vehicleId) : $resource(endpoint);
        vehicleSave.save({data: $scope.object, features: featuresArray},function(data){
          if($scope.vehicleId){
            var note = $scope.object.note ? $scope.object.note : '';
            var NoteSave = $scope.object.note_id ? $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicle/'+$scope.vehicleId+'/note/'+$scope.object.note_id) : $resource('/api/dealer/'+$scope.currentDealer.id+'/vehicle/'+$scope.vehicleId+'/note');
            NoteSave.save({note:note },function(data){
              $modalInstance.close('edited');
            });
          } else{
            GlobalFactory.notify('Vehículo creado correctamente. Ahora puedes cargar notas, imágenes, y características.','alert-success');
            $scope.vehicleId = data.id;
            $scope.doOnEdit();
          }
        },function (error) {
          if(error.status == 400){
            GlobalFactory.notify('El dominio del vehiculo ya fue agregado.','alert-danger');
          } else{
            GlobalFactory.throwAnError(error);
          }
        });
      }
    };

    $scope.close = function(){
      $modalInstance.close();
    };

    function validarEmail( email ) {
      expr = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return expr.test(email);
    }

  }]);

angular.module('vehicles').controller('VehiclesConflictCtrl', [
  '$scope','$rootScope','$routeParams','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window','$modalInstance',
  function($scope,$rootScope,$routeParams,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window,$modalInstance) {
    $scope.modalInstance = $modalInstance;
    $scope.guardarNuevo = function(something){
      var caracteristicas = {
        brand : $scope.brandSelected,
        model : $scope.modelSelected,
        version : $scope.versionSelected,
        listing : $scope.listing
      };
      console.log(caracteristicas);
      switch($scope.portal){
        case 'autocosmos' :
          if(something){
            GlobalFactory.notify('No ha completado todos los campos.','alert-danger');
          }else{
            $resource('/api/dealer/'+$scope.currentDealer.id+'/autocosmos/vehicle/'+$scope.vehicleId+'/update-vehicle').save(caracteristicas,function(){
              $scope.modalInstance.close();
            },function(error){
              GlobalFactory.throwAnError(error);
            });
          }
          break;
        case 'mercadolibre':
          if(something){
            GlobalFactory.notify('No ha completado todos los campos.','alert-danger');
          }else{
            $resource('/api/dealer/'+$scope.currentDealer.id+'/mercadolibre/vehicle/'+$scope.vehicleId+'/update-vehicle').save(caracteristicas,function(){
              $scope.modalInstance.close();
            },function(error){
              GlobalFactory.throwAnError(error);
            });
          }
          break;

        case 'deautos':
          if(something){
            GlobalFactory.notify('No ha completado todos los campos.','alert-danger');
          }else{
            $resource('/api/dealer/'+$scope.currentDealer.id+'/deautos/vehicle/'+$scope.vehicleId+'/update-vehicle').save(caracteristicas,function(){
              $scope.modalInstance.close();
            },function(error){
              GlobalFactory.throwAnError(error);
            });
          }
          break;

          case 'demotores':
              if(something){
                  GlobalFactory.notify('No ha completado todos los campos.','alert-danger');
              }else{
                  $resource('/api/dealer/'+$scope.currentDealer.id+'/demotores/vehicle/'+$scope.vehicleId+'/update-vehicle').save(caracteristicas,function(){
                      $scope.modalInstance.close();
                  },function(error){
                      GlobalFactory.throwAnError(error);
                  });
              }
              break;

      }


    }
  }]);

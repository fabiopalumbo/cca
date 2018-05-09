angular.module('contacts').controller('ContactsListCtrl', [
  '$scope','$rootScope','GlobalFactory','$location','$modal','$timeout','$route','$resource','$window',
  function($scope,$rootScope,GlobalFactory,$location,$modal,$timeout,$route,$resource,$window) {
    var initSpinner = true;
    GlobalFactory.getUserAndDealer().done(function(obj){
      $scope.loggedUser = obj.user;
      $scope.currentDealer = obj.dealer;
      GlobalFactory.spinner(document.getElementById('contacts-spinner'),'spin');
      $scope.wordfilter = '';
      $scope.filters = {
        tag: '',
        page: ''
      };

      $scope.showTable = true;

      $scope.fillTable = function(result){
        if(initSpinner){
          initSpinner = false;
          GlobalFactory.spinner(document.getElementById('contacts-spinner'),'stop');
        }
        else {
          GlobalFactory.spinner(document.getElementById('contacts-table-spinner'),'stop');
          $scope.showTable = true;
        }
        $scope.contacts = result.data;
        $scope.maxSize = 5;
        $scope.totalItems = result.total;
        $scope.itemsPerPage = result.per_page;
        $scope.currentPage = result.current_page;
      };

      var contacts = $resource('/api/dealer/'+$scope.currentDealer.id+'/contact/');
      $scope.getContacts = function(page){
        if(!initSpinner){
          $scope.showTable = false;
          GlobalFactory.spinner(document.getElementById('contacts-table-spinner'),'spin');
        }
        if(page){
          $scope.filters.page = $scope.currentPage;
        }
        contacts.get($scope.filters,function(response){
          console.log(response);
          $scope.fillTable(response);

        }, function(){
          if(initSpinner){
            initSpinner = false;
            GlobalFactory.spinner(document.getElementById('contacts-spinner'),'stop');
          }
        });
      };

      $scope.getContacts(false);

      $scope.pageChanged = function() {
        $scope.getContacts(true);
      };

      $scope.filter = function(wordfilter){
        $scope.activeLetter = '';
        $scope.filters = {
          tag: wordfilter,
          page: ''
        };
        $scope.getContacts(false);
      };

      $scope.loadImage = function(){
        $scope.profileUrl = $scope.loggedUser.image_url ? $scope.loggedUser.image_url : 'src/images/perfil.png';
      };

      $scope.alphabet = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];

      $scope.activeLetter = '';

      $scope.activateLetter = function(letter) {
        $scope.activeLetter = letter;
        $scope.filters = {
          tag:letter,
          page: ''
        };
        $scope.getContacts(false);
      };

      $scope.openEditContact = function(id) {
        $scope.selectedContactId = id;
        $modal.open({
          templateUrl: '/admin/app/contacts/contacts-edit.html',
          controller: 'ContactsEditCtrl',
          scope: $scope,
          size: 'lg',
          backdrop: 'static',
          keyboard: false
        }).result.then(
          function (callback) {
            if(callback == 'edited'){
              GlobalFactory.notify('Contacto editado correctamente.', 'alert-success');
            } else if(callback == 'created'){
              GlobalFactory.notify('Contacto creado correctamente.', 'alert-success');
            }
            $scope.getContacts(false);
          }
        );
      };

      $scope.remove = function(id){
        var remove = $resource('/api/dealer/'+$scope.currentDealer.id+'/contact/:id',{id:'@id'});
        remove.remove({id:id},function(response){
          if(response){
            GlobalFactory.notify('Contacto eliminado correctamente.','alert-success');
            $scope.getContacts(false);
          }
        },function(error){
          if(error.status == 401){
            GlobalFactory.notify('Acceso no autorizado.','alert-danger');
            $location.path("/admin");
          }
          if(error.status == 400){
            GlobalFactory.notify('Se ha producido un error. Intentelo nuevamente.','alert-danger');
            $scope.getContacts(false);
          }
        });

      };


    });

  }]);

angular.module('contacts').controller('ContactsEditCtrl', [
  '$scope','$rootScope','$resource','GlobalFactory','$location','$modal','$timeout','$modalInstance','$route',
  function($scope,$rootScope,$resource,GlobalFactory,$location,$modal,$timeout,$modalInstance,$route) {
    $scope.showModalBody = false;
    $scope.object = {};
    var edit = false;
    $scope.modalInstance = $modalInstance;
    var contactId;
    if(contactId = $scope.$parent.selectedContactId) {
      var contacts = $resource('/api/dealer/'+$scope.currentDealer.id +'/contact/:id',{id:'@id'});
      edit = true;
      contacts.get({id:contactId},function(data){
        console.log(data);
        $scope.object = data;
        $scope.showModalBody = true;
      });
    }else $scope.showModalBody = true;


    var group = $resource('/api/group');
    group.query(function(data){
      console.log(data);
      console.log($scope.object);
      $scope.groups = data;
      $scope.groups.forEach(function(group){
        group.val = false;
      });
    },function (error){
      if (error.status == 401) {
        GlobalFactory.notify('Acceso no autorizado.','alert-danger');
        $location.path("/admin");
      }
    });

    $scope.changeGroups = function (index,value) {
      $scope.groups[index].val = !!value;
    };

    $scope.close = function(){
      $modalInstance.dismiss();
    };

    $scope.$watch(function(){
      return $rootScope.loggedUser;
    },function(newVal,oldVal){
      if(newVal != oldVal){
        $scope.loggedUser = newVal;
        $scope.loadImage();
      }
    });

    $scope.save = function(invalid) {
      if(invalid) GlobalFactory.notify('Error. Revise los datos obligatorios.','alert-danger');
      else if(!$scope.object.email) GlobalFactory.notify('Error. Complete el email.','alert-danger');
      else if(!$scope.object.phone) GlobalFactory.notify('Error. Complete el teléfono.','alert-danger');
      else {
        if($scope.object.contact_type == 'empresa'){
          $scope.object.first_name = null;
          $scope.object.last_name = null;
          $scope.object.dni = null;
          $scope.object.birthday = null;
          $scope.object.genre = null;
        }else if($scope.object.contact_type == 'persona'){
          $scope.object.business_name = null;
          $scope.object.cuit = null;
        }
        var endpoint = '/api/dealer/'+$scope.currentDealer.id+'/contact';
        var contact = $scope.object.id ? $resource(endpoint+'/'+$scope.object.id) : $resource(endpoint);
        contact.save($scope.object,function(data){
          if(edit) $modalInstance.close('edited');
          else $modalInstance.close('created');

        },function (error) {
          if (error.status == 401) {
            GlobalFactory.notify('Acceso no autorizado.','alert-danger');
            $location.path("/");
          }else{
            console.log(error);
            if(error.data.email)
              GlobalFactory.notify('El email no es válido.','alert-danger');
            else if(error.data.phone)
              GlobalFactory.notify('El teléfono no es válido.','alert-danger');
            else
              GlobalFactory.notify('Se ha producido un error. Intentelo nuevamente.','alert-danger');
          }
        });
      }
    }

  }]);
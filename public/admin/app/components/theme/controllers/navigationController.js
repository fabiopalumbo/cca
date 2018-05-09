angular
  .module('theme.core.navigation_controller', ['adminApp','globalFactory','theme.core.services'])
  .controller('NavigationController', ['$scope', '$location', '$timeout','GlobalFactory','$rootScope', function($scope, $location, $timeout,GlobalFactory,$rootScope) {
    'use strict';

    $rootScope.redirectTo = function(location){
      $location.path(location);
    };
    GlobalFactory.getUser().done(function(user){

      $scope.loggedUser = user;

      $scope.menu = [{
        label: 'Modulos',
        separator:true
      }];
      $scope.setMenu = function(){
        console.log('setMenu');
        $scope.menu = [];
        if($scope.loggedUser.permission == undefined || $scope.loggedUser.permission.length == 0){
          // $scope.menu = [{
          //   label: 'Modulos',
          //   separator:true
          // }];
        }else{
          [{
            label: 'Administrar',
            separator:true
          },{
            label: 'Concesionarias',
            iconClasses: 'fa fa-bank',
            url: '#/dealers',
            name:'Concesionarias'
          },{
            label: 'Perfiles',
            iconClasses: 'fa fa-users',
            url: '#/profiles',
            name:'Perfiles'
          },{
            label: 'Permisos',
            iconClasses: 'fa fa-unlock-alt',
            url: '#/permissions',
            name:'Permisos'
          },{
            label: 'Usuarios',
            iconClasses: 'fa fa-user',
            url: '#/users',
            name:'Usuarios'
          },{
            label: 'Vehículos',
            separator:true
          },{
          //   label: 'En stock',
          //   iconClasses: 'fa fa-car',
          //   url: '#/vehicles',
          //   name:'Vehiculos'
          // },{
          //   label: 'Reservados',
          //   iconClasses: 'fa fa-car',
          //   url: '#/vehicles',
          //   name:'Vehiculos'
          // },{
          //   label: 'Vendidos',
          //   iconClasses: 'fa fa-car',
          //   url: '#/vehicles',
          //   name:'Vehiculos'
          // },{
            label: 'Vehículos',
            iconClasses: 'fa fa-car',
            url: '#/vehicles',
            name:'Vehiculos'
          },{
            label: 'Publicaciones',
            separator:true
          },{
            label: 'Consultas',
            iconClasses: 'fa fa-comments',
            url: '#/queries',
            name:'Consultas'
          },{
            label: 'Vinculaciones',
            iconClasses: 'fa fa-sitemap',
            url: '#/linking',
            name:'Vinculaciones'
          },{
            label: 'Red NuestrosAutos',
            iconClasses: 'fa fa-car',
            url: '#/vehicles/nuestros-autos',
            name:'Vehiculos'
          },{
            label: 'Contactos',
            separator:true
          },{
            label: 'Contactos',
            iconClasses: 'fa fa-envelope',
            url: '#/contacts',
            name:'Contactos'
          },{
            label: 'Ventas',
            separator:true
          },{
            label: 'Ventas',
            iconClasses: 'fa fa-tags',
            url: '#/sales',
            name:'Ventas'
          }
          ].forEach(function(item){
            if(item.separator || ($scope.loggedUser.permission[item.name] && $scope.loggedUser.permission[item.name].list == "1")){
              $scope.menu.push(item);
            }

          });
        }

      };


      $scope.$watch(function(){
        return $scope.loggedUser;
      },function(newVal){
          if($scope.loggedUser = newVal)
            $scope.setMenu();
      });




      /*{
          label: 'Explore',
          iconClasses: '',
          separator: true
        }, {

        }, {
          label: 'HTML Version',
          iconClasses: 'fa fa-code',
          url: '../../',
        }, {
          label: 'Layouts',
          iconClasses: 'fa fa-columns',
          html: '<span class="badge badge-info">2</span>',
          children: [{
            label: 'Grid Scaffolding',
            url: '#/layout-grid',
          }, {
            label: 'Boxed',
            url: '#/layout-boxed'
          }]
        }, {
          label: 'Base Styles',
          iconClasses: 'fa fa-flask',
          children: [{
            label: 'Typography',
            url: '#/ui-typography'
          }, {
            label: 'Buttons',
            url: '#/ui-buttons'
          }, {
            label: 'Font Icons',
            url: '#/ui-icons-fontawesome',
          }]
        }, {
          label: 'Bootstrap',
          iconClasses: 'fa fa-cogs',
          html: '<span class="label label-info">UI</span>',
          children: [{
            label: 'Modals',
            url: '#/ui-modals'
          }, {
            label: 'Progress Bars',
            url: '#/ui-progressbars'
          }, {
            label: 'Pagination',
            url: '#/ui-paginations'
          }, {
            label: 'Breadcrumbs',
            url: '#/ui-breadcrumbs'
          }, {
            label: 'Labels & Badges',
            url: '#/ui-labelsbadges',
          }, {
            label: 'Alerts',
            url: '#/ui-alerts',
          }, {
            label: 'Tabs',
            url: '#/ui-tabs',
          }, {
            label: 'Wells',
            url: '#/ui-wells'
          }, {
            label: 'Carousel',
            url: '#/ui-imagecarousel'
          }]
        }, {
          label: 'Plugins',
          iconClasses: '',
          separator: true
        }, {
          label: 'Components',
          iconClasses: 'fa fa-random',
          children: [{
            label: 'Tiles',
            url: '#/ui-tiles'
          }, {
            label: 'Bootbox',
            url: '#/components-bootbox'
          }, {
            label: 'Pines Notifications',
            url: '#/components-notifications'
          }, {
            label: 'Sliders & Ranges',
            url: '#/ui-sliders',
          }, {
            label: 'Pulsating Elements',
            url: '#/components-pulsate'
          }, {
            label: 'jQuery Knob',
            url: '#/components-knob'
          }]
        }, {
          label: 'Forms',
          iconClasses: 'fa fa-pencil',
          children: [{
            label: 'Form Layout',
            url: '#/form-layout',
          }, {
            label: 'Components',
            url: '#/form-components',
          }, {
            label: 'Pickers',
            url: '#/form-pickers'
          }, {
            label: 'Form Wizard',
            url: '#/form-wizard'
          }, {
            label: 'Validation',
            url: '#/form-validation',
          }, {
            label: 'Form Masks',
            url: '#/form-masks'
          }, {
            label: 'Advanced Uploaders',
            url: '#/form-fileupload',
          }, {
            label: 'WYSIWYG Editor',
            url: '#/form-wysiwyg',
          }, {
            label: 'Inline Editor',
            url: '#/form-xeditable',
          }]
        }, {
          label: 'Tables',
          iconClasses: 'fa fa-table',
          children: [{
            label: 'Tables',
            url: '#/tables-basic'
          }, {
            label: 'ngGrid',
            url: '#/tables-data',
          }, {
            label: 'Responsive Tables',
            url: '#/tables-responsive'
          }, {
            label: 'Editable Tables',
            url: '#/tables-editable',
          }]
        }, {
          label: 'Panels',
          iconClasses: 'fa fa-cog fa-spin',
          hideOnHorizontal: true,
          url: '#/ui-advancedpanels'
        }, {
          label: 'Analytics',
          iconClasses: 'fa fa-bar-chart-o',
          hideOnHorizontal: true,
          children: [{
            label: 'Flot',
            url: '#/charts-flot',
          }, {
            label: 'Chartist',
            url: '#/charts-chartist'
          }, {
            label: 'Morris.js',
            url: '#/charts-morrisjs'
          }, {
            label: 'Easy Pie Chart',
            url: '#/charts-easypiechart'
          }, {
            label: 'Sparklines',
            url: '#/charts-sparklines',
          }]
        }, {
          label: 'Maps',
          iconClasses: 'fa fa-map-marker',
          hideOnHorizontal: true,
          children: [{
            label: 'Google Maps',
            url: '#/maps-google'
          }, {
            label: 'Vector Maps',
            url: '#/maps-vector',
          }]
        }, {
          label: 'Pages',
          iconClasses: 'fa fa-files-o',
          hideOnHorizontal: true,
          children: [{
            label: 'Profile',
            url: '#/extras-profile'
          }, {
            label: 'Messages',
            url: '#/extras-messages'
          }, {
            label: 'Pricing Tables',
            url: '#/extras-pricingtable'
          }, {
            label: 'Timeline',
            url: '#/extras-timeline'
          }, {
            label: 'Invoice',
            url: '#/extras-invoice'
          }]
        }, {
          label: 'Extras',
          iconClasses: 'fa fa-briefcase',
          hideOnHorizontal: true,
          children: [{
            label: 'FAQ',
            url: '#/extras-faq',
          }, {
            label: 'Registration',
            url: '#/extras-registration'
          }, {
            label: 'Password Reset',
            url: '#/extras-forgotpassword'
          }, {
            label: 'Login',
            url: '#/extras-login'
          }, {
            label: '404 Page',
            url: '#/extras-404'
          }, {
            label: '500 Page',
            url: '#/extras-500'
          }]
        }, {
          label: 'Multiple Levels',
          iconClasses: 'fa fa-sitemap',
          hideOnHorizontal: true,
          children: [{
            label: 'Menu Item 1',
          }, {
            label: 'Menu Item 2',
            children: [{
              label: 'Deeper',
              children: [{
                label: 'Deeper Yet!'
              }]
            }]
          }]
        }, {
          label: 'Functional Apps',
          hideOnHorizontal: true,
          separator: true
        }, {
          label: 'Inbox',
          iconClasses: 'fa fa-inbox',
          url: '#/inbox',
          html: '<span class="badge badge-danger">3</span>'
        }, {
          label: 'Tasks',
          iconClasses: 'fa fa-tasks',
          url: '#/app-tasks',
          html: '<span class="badge badge-warning">1</span>'
        }, {
          label: 'Notes',
          iconClasses: 'fa fa-pencil-square-o',
          url: '#/app-notes',
        }, {
          label: 'To-do',
          iconClasses: 'fa fa-check',
          url: '#/app-todo',
        }];*/

        var setParent = function(children, parent) {
          angular.forEach(children, function(child) {
            child.parent = parent;
            if (child.children !== undefined) {
              setParent(child.children, child);
            }
          });
        };

        $scope.findItemByUrl = function(children, url) {
          for (var i = 0, length = children.length; i < length; i++) {
            if (children[i].url && children[i].url.replace('#', '') === url) {
              return children[i];
            }
            if (children[i].children !== undefined) {
              var item = $scope.findItemByUrl(children[i].children, url);
              if (item) {
                return item;
              }
            }
          }
        };

        setParent($scope.menu, null);

        $scope.openItems = []; $scope.selectedItems = []; $scope.selectedFromNavMenu = false;

        $scope.select = function(item) {
          // close open nodes
          if (item.open) {
            item.open = false;
            return;
          }
          for (var i = $scope.openItems.length - 1; i >= 0; i--) {
            $scope.openItems[i].open = false;
          }
          $scope.openItems = [];
          var parentRef = item;
          while (parentRef) {
            parentRef.open = true;
            $scope.openItems.push(parentRef);
            parentRef = parentRef.parent;
          }

          // handle leaf nodes
          if (!item.children || (item.children && item.children.length < 1)) {
            $scope.selectedFromNavMenu = true;
            for (var j = $scope.selectedItems.length - 1; j >= 0; j--) {
              $scope.selectedItems[j].selected = false;
            }
            $scope.selectedItems = [];
            parentRef = item;
            while (parentRef) {
              parentRef.selected = true;
              $scope.selectedItems.push(parentRef);
              parentRef = parentRef.parent;
            }
          }
        };

        $scope.highlightedItems = [];
        var highlight = function(item) {
          var parentRef = item;
          while (parentRef !== null) {
            if (parentRef.selected) {
              parentRef = null;
              continue;
            }
            parentRef.selected = true;
            $scope.highlightedItems.push(parentRef);
            parentRef = parentRef.parent;
          }
        };

        var highlightItems = function(children, query) {
          angular.forEach(children, function(child) {
            if (child.label.toLowerCase().indexOf(query) > -1) {
              highlight(child);
            }
            if (child.children !== undefined) {
              highlightItems(child.children, query);
            }
          });
        };

        // $scope.searchQuery = '';
        $scope.$watch('searchQuery', function(newVal, oldVal) {
          var currentPath = '#' + $location.path();
          if (newVal === '') {
            for (var i = $scope.highlightedItems.length - 1; i >= 0; i--) {
              if ($scope.selectedItems.indexOf($scope.highlightedItems[i]) < 0) {
                if ($scope.highlightedItems[i] && $scope.highlightedItems[i] !== currentPath) {
                  $scope.highlightedItems[i].selected = false;
                }
              }
            }
            $scope.highlightedItems = [];
          } else
          if (newVal !== oldVal) {
            for (var j = $scope.highlightedItems.length - 1; j >= 0; j--) {
              if ($scope.selectedItems.indexOf($scope.highlightedItems[j]) < 0) {
                $scope.highlightedItems[j].selected = false;
              }
            }
            $scope.highlightedItems = [];
            highlightItems($scope.menu, newVal.toLowerCase());
          }
        });

        $scope.$on('$routeChangeSuccess', function() {
          if ($scope.selectedFromNavMenu === false) {
            var item = $scope.findItemByUrl($scope.menu, $location.path());
            if (item) {
              $timeout(function() {
                $scope.select(item);
              });
            }
          }
          $scope.selectedFromNavMenu = false;
          $scope.searchQuery = '';
        });

    });

  }]);
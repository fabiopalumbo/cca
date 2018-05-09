angular
  .module('theme.core.notifications_controller', [])
  .controller('NotificationsController', ['$scope', '$filter', function($scope, $filter) {
    'use strict';
    $scope.notifications = [{
      text: 'Profile page has been updated',
      time: '8m',
      class: 'notification-info',
      iconClasses: 'fa-check',
      seen: true
    }, {
      text: 'Update pushed successfully',
      time: '24m',
      class: 'notification-success',
      iconClasses: 'fa-check',
      seen: false
    }, {
      text: 'New users requested to join',
      time: '16m',
      class: 'notification-primary',
      iconClasses: 'fa-check',
      seen: false
    }, {
      text: 'More Orders Pending',
      time: '2m',
      class: 'notification-danger',
      iconClasses: 'fa-check',
      seen: false
    }, {
      text: 'Initial Release 1.0',
      time: '4m',
      class: 'notification-primary',
      iconClasses: 'fa-check',
      seen: false
    }, ];

    $scope.setSeen = function(item, $event) {
      $event.preventDefault();
      $event.stopPropagation();
      item.seen = true;
    };

    $scope.setUnseen = function(item, $event) {
      $event.preventDefault();
      $event.stopPropagation();
      item.seen = false;
    };

    $scope.setSeenAll = function($event) {
      $event.preventDefault();
      $event.stopPropagation();
      angular.forEach($scope.notifications, function(item) {
        item.seen = true;
      });
    };

    $scope.unseenCount = $filter('filter')($scope.notifications, {
      seen: false
    }).length;

    $scope.$watch('notifications', function(notifications) {
      $scope.unseenCount = $filter('filter')(notifications, {
        seen: false
      }).length;
    }, true);
  }]);
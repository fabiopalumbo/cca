angular
  .module('theme.core.messages_controller', [])
  .controller('MessagesController', ['$scope', '$filter', function($scope, $filter) {
    'use strict';
    $scope.messages = [{
      name: 'Polly Paton',
      message: 'Uploaded all the...',
      time: '2s',
      thumb: 'assets/demo/avatar/avatar_01.png',
      read: false
    }, {
      name: 'Simon Corbett',
      message: 'I am signing...',
      time: '16m',
      thumb: 'assets/demo/avatar/avatar_02.png',
      read: false
    }, {
      name: 'Matt Tennant',
      message: 'Everything is work...',
      time: '2m',
      thumb: 'assets/demo/avatar/avatar_03.png',
      read: true
    }, {
      name: 'Alan Doyle',
      message: 'Please mail me the...',
      time: '6m',
      thumb: 'assets/demo/avatar/avatar_04.png',
      read: false
    }, {
      name: 'Polly Paton',
      message: 'Uploaded all the...',
      time: '12m',
      thumb: 'assets/demo/avatar/avatar_05.png',
      read: false
    }, {
      name: 'Simon Corbett',
      message: 'I am signing...',
      time: '2m',
      thumb: 'assets/demo/avatar/avatar_06.png',
      read: false
    }, {
      name: 'Matt Tennant',
      message: 'Everything is no...',
      time: '4h',
      thumb: 'assets/demo/avatar/avatar_07.png',
      read: true
    }, {
      name: 'Alan Doyle',
      message: 'Please mail me the...',
      time: '6h',
      thumb: 'assets/demo/avatar/avatar_08.png',
      read: false
    }, ];

    $scope.setRead = function(item, $event) {
      $event.preventDefault();
      $event.stopPropagation();
      item.read = true;
    };

    $scope.setUnread = function(item, $event) {
      $event.preventDefault();
      $event.stopPropagation();
      item.read = false;
    };

    $scope.setReadAll = function($event) {
      $event.preventDefault();
      $event.stopPropagation();
      angular.forEach($scope.messages, function(item) {
        item.read = true;
      });
    };

    $scope.unseenCount = $filter('filter')($scope.messages, {
      read: false
    }).length;

    $scope.$watch('messages', function(messages) {
      $scope.unseenCount = $filter('filter')(messages, {
        read: false
      }).length;
    }, true);
  }]);
angular
  .module('theme.demos.ui_components')
  .controller('TilesController', ['$scope', function($scope) {
    'use strict';
    $scope.epOptions = {
      barColor: "rgba(255, 255, 255, 0.6)",
      trackColor: 'rgba(255, 255, 255, 0.2)',
      scaleColor: 'rgba(255, 255, 255, 0.8)',
      scaleLength: 0,
      lineCap: 'square',
      lineWidth: 4,
      size: 80,
      onStep: function(from, to, percent) {
          $(this.el).find('.percent').text(Math.round(percent));
      }
    };
  }]);
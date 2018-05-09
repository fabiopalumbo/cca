angular.module('theme.demos.dashboard', [
    'angular-skycons',
    'angular-chartist',
    'theme.chart.canvas',
    'theme.demos.forms',
    'theme.demos.apps'
  ])
  .controller('DashboardController', ['$scope', '$theme', '$timeout', '$window', function($scope, $theme, $timeout, $window) {
    'use strict';

    $scope.drp_start = moment().subtract(1, 'days').format('MMMM D, YYYY');
    $scope.drp_end = moment().add(31, 'days').format('MMMM D, YYYY');
    $scope.drp_options = {
      ranges: {
        'Today': [moment(), moment()],
        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
        'This Month': [moment().startOf('month'), moment().endOf('month')],
        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
      },
      opens: 'left',
      startDate: moment().subtract(29, 'days'),
      endDate: moment()
    };

    $scope.visitorsChart = {};
    $scope.revenueChart ={};

    $scope.updateRevenueChart = function () {
      $timeout(function () {
        $scope.revenueChart.update();
      }, 10);
    };

    $scope.updateVisitorsChart = function () {
      $timeout(function () {
        $scope.visitorsChart.update();
      }, 10);
    };

    $scope.chartDefs = {};
    $scope.addChartDefinition = function (chartType, chartKey, chartData, chartOptions, chartEvents) {
      $scope.chartDefs[chartKey] = {
        chartType: chartType,
        chartData: chartData,
        chartOptions: chartOptions,
        chartEvents: chartEvents,
      };
    };

    $scope.addChartDefinition('Line', 'ct-chart-visitors', {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
      series: [
        [4,5,7,6,4,7,6,7,4,7,6,3],
        [3,4,4,5,3,5,3,6,3,4,5,2],
        [1,2,1,3,1,2,1,3,1,2,3,1]
      ]
    }, {
          height: 300,
          fullWidth: true,
          low: 0,
          high: 7,
          showArea: true,
          lineSmooth: Chartist.Interpolation.cardinal({
            tension: 0
          }),
          axisY: {
            onlyInteger: true,
            offset: 20,
            labelInterpolationFnc: function(value) {
            return '$' + value + 'K'
          },
      },
      plugins: [
        Chartist.plugins.tooltip({prefix: "$", suffix: "K"})
      ]
    }, {
      created: function (obj) {
        var chart = $(obj.svg._node).closest('[chartist]').data().$chartistController.chart;
        $scope.visitorsChart = chart;
      },
      draw: function(data) {
        if(data.type === 'point') {
          data.element.animate({
            y1: {
              begin: 100 * data.index,
              dur: 100,
              from: data.y + 100,
              to: data.y,
              easing: Chartist.Svg.Easing.easeOutQuint
            },
            y2: {
              begin: 100 * data.index,
              dur: 100,
              from: data.y + 100,
              to: data.y,
              easing: Chartist.Svg.Easing.easeOutQuint
            }
          });
        }

        if(data.type === 'line' || data.type === 'area') {
          data.element.animate({
            d: {
              begin: 100 * data.index,
              dur: 100,
              from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
              to: data.path.clone().stringify(),
              easing: Chartist.Svg.Easing.easeOutQuint
            }
          });
        }
      }
    });

    $scope.addChartDefinition('Bar', 'ct-chart-revenue', {
      labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        series: [
        [2,4,3,5,4,5,6,7,4,5,3,4],
        [3,6,2,6,2,7,4,5,2,6,5,6],
        [1,3,1,2,1,3,2,3,1,2,3,2]
        ]
    }, {
      height: 300,
      seriesBarDistance: 10,
      axisX: {
        offset: 20
      },
      axisY: {
        offset: 20,
        labelInterpolationFnc: function(value) {
          return '$' + value + 'K'
        },
        scaleMinSpace: 0
      },
      plugins: [
        Chartist.plugins.tooltip({prefix: "$", suffix: "K"})
      ]
    }, {
      created: function (obj) {
        var chart = $(obj.svg._node).closest('[chartist]').data().$chartistController.chart;
        $scope.revenueChart = chart;
      }
    });

  }]);
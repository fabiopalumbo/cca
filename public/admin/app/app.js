var adminApp = angular.module('adminApp', [
  'ngRoute',
  'ngSanitize',
  'theme',
  'vehicles',
  'users',
  'dealers',
  'contacts',
  'sales',
  'linking',
  'confirmButton',
  'mydealer',
  'myprofile',
  'navbar',
  'flow',
  'globalFactory',
  'cgNotify',
  'profiles',
  'permissions',
  'queries',
  'ngImgCrop'
]);

////TODO: move those messages to a separate module
//angular.module('adminApp').constant('I18N.MESSAGES', {
//  'errors.route.changeError':'Route change error',
//  'crud.user.save.success':"A user with id '{{id}}' was saved successfully.",
//  'crud.user.remove.success':"A user with id '{{id}}' was removed successfully.",
//  'crud.user.remove.error':"Something went wrong when removing user with id '{{id}}'.",
//  'crud.user.save.error':"Something went wrong when saving a user...",
//  'crud.project.save.success':"A project with id '{{id}}' was saved successfully.",
//  'crud.project.remove.success':"A project with id '{{id}}' was removed successfully.",
//  'crud.project.save.error':"Something went wrong when saving a project...",
//  'login.reason.notAuthorized':"You do not have the necessary access permissions.  Do you want to login as someone else?",
//  'login.reason.notAuthenticated':"You must be logged in to access this part of the application.",
//  'login.error.invalidCredentials': "Login failed.  Please check your credentials and try again.",
//  'login.error.serverError': "There was a problem with authenticating: {{exception}}."
//});

angular.module('adminApp').config(['$routeProvider', 'flowFactoryProvider', '$locationProvider', 'uiSelectConfig', function ($routeProvider, flowFactoryProvider, $locationProvider, uiSelectConfig) {
  $locationProvider.html5Mode({enable:true, requireBase:false});
  $routeProvider.otherwise({redirectTo:'/vehicles'});
  uiSelectConfig.theme = 'select2';
  flowFactoryProvider.on('catchAll', function(event) {
    console.log('catchAll', event);
  });
}]);

//angular.module('adminApp').run(['security', function(security) {
//  // Get the current user when the application starts
//  // (in case they are still logged in from a previous session)
//  security.requestCurrentUser();
//}]);


//angular.module('adminApp').controller('HeaderCtrl', ['$scope', '$location', '$route', 'security', 'breadcrumbs', 'notifications', 'httpRequestTracker',
//  function ($scope, $location, $route, security, breadcrumbs, notifications, httpRequestTracker) {
//    $scope.location = $location;
//    $scope.breadcrumbs = breadcrumbs;
//
//    $scope.isAuthenticated = security.isAuthenticated;
//    $scope.isAdmin = security.isAdmin;
//
//    $scope.home = function () {
//      if (security.isAuthenticated()) {
//        $location.path('/dashboard');
//      } else {
//        $location.path('/projectsinfo');
//      }
//    };
//
//    $scope.hasPendingRequests = function () {
//      return httpRequestTracker.hasPendingRequests();
//    };
//  }]);
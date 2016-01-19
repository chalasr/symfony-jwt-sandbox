;(function (demo) {

    function Authentication($rootScope, $http, authService, $httpBackend) {
      return {
        login: function (credentials) {
          return $http.post('/v1/login_check', credentials, { ignoreAuthModule: true })
        },
        logout: function (user) {
          delete $http.defaults.headers.common.Authorization;
          $rootScope.$broadcast('event:auth-logout-complete');
        }
      };
    };

    function MainCtrl($scope, $rootScope, $http, $timeout, authService, AuthenticationService) {
      $scope.credentials = {
          email: 'guest',
          password: 'guest'
      };
      $scope.access = {};

      $scope.submit = function(resource) {
        $scope.login();
        $timeout(function() {
          $scope.fetch(resource);
        }, 500);
      };

      $scope.fetch = function(resource) {
        var _format = resource == 'users' ? '' : '.json';
        $http.get('/v1/' + resource + _format)
          .then(function (response) {
            $rootScope.results = response;
            $scope.resource = resource;
            $scope.errorMessage = null;
          });
      }

      $scope.login = function() {
        var credentials = $scope.credentials;

        AuthenticationService.login($scope.credentials)
          .success(function (data, status, headers, config) {
            $http.defaults.headers.common.Authorization = 'Bearer ' + data.token;
            $scope.access.token = data.token;
          })
          .error(function (data, status, headers, config) {
            delete $http.defaults.headers.common.Authorization;
            $scope.access = {};
            $scope.errorMessage = 'Bad credentials';
          });
      }

    };

    demo
      .factory('AuthenticationService', Authentication)
      .controller('MainCtrl', MainCtrl)
    ;

})(angular.module('demoApp', ['http-auth-interceptor', 'ui.bootstrap'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
}));

;(function (demo) {

    function Authentication($rootScope, $http, authService, $httpBackend) {
      return {
        login: function (credentials) {
          return $http.post('/v1/login', credentials, { ignoreAuthModule: true })
        },
        logout: function (user) {
          delete $http.defaults.headers.common.Authorization;
          $rootScope.$broadcast('event:auth-logout-complete');
        }
      };
    }

    function MainCtrl($scope, $rootScope, $http, $timeout, authService, AuthenticationService) {
      $scope.access = {};
      $scope.credentials = {
          email: 'guest@rch.fr',
          password: 'guest'
      };

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
      };

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
      };

    }

    var ucFirst = function() {
      return function(input) {
        if (!angular.isString(input)) return input;
        return input.split(' ').map(function (ch) {
            return ch.charAt(0).toUpperCase() + ch.substring(1);
        }).join(' ');
      };
    }

    demo
      .factory('AuthenticationService', Authentication)
      .controller('MainCtrl', MainCtrl)
      .filter('ucfirst', ucFirst)
    ;


})(angular.module('demoApp', ['http-auth-interceptor', 'ui.bootstrap'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
}));

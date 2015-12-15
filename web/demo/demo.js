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

    function MainCtrl($scope, $rootScope, $http, authService, AuthenticationService) {
      $scope.credentials = {
        email: 'admin@sportroops.dev',
        password: ''
      };

      $scope.getUsers = function() {
        $http.get('/v1/users')
        .then(function (response) {
            $rootScope.results = response;
            $scope.errorMessage = null;
        });
      };

      $scope.submit = function (credentials) {
        AuthenticationService.login(credentials)
          .success(function (data, status, headers, config) {
            $http.defaults.headers.common.Authorization = 'Bearer ' + data.token;
            $scope.getUsers();
            authService.loginConfirmed(data, function (config) {
              config.headers.Authorization = 'Bearer ' + data.token;
              return config;
            });
          })
          .error(function (data, status, headers, config) {
            $scope.errorMessage = 'Bad credentials';
          });
      };

    };

    demo
      .factory('AuthenticationService', Authentication)
      .controller('MainCtrl', MainCtrl)
    ;

})(angular.module('demoApp', ['http-auth-interceptor', 'ui.bootstrap'], function($interpolateProvider) {
    $interpolateProvider.startSymbol('[%');
    $interpolateProvider.endSymbol('%]');
}));

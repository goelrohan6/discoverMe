'use strict';

/**
 * @ngdoc function
 * @name yapp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of yapp
 */
angular.module('yapp')
    .controller('LoginCtrl', function($scope, $rootScope,  $location, loginData) {
        $scope.login = {};
        $scope.signup = {};
        $scope.doLogin = function(customer) {
            loginData.post('login', {
                customer: customer
            }).then(function(results) {
                Data.toast(results);
                if (results.status == "success") {
                    $location.path('dashboard');
                }
            });
        };
        $scope.submit = function() {

            $location.path('/dashboard');

            return false;
        }

    });

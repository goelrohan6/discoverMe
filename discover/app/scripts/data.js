'use strict';
/**
 * @ngdoc function
 * @name yapp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of yapp
 */
angular.module('yapp')
    .factory("Data", ['$http', '$location',
        function($http, $q, $location) {

            var serviceBase = 'http://android.brinjal.me/merchant/v1/';

            var obj = {};

            obj.get = function(q) {
                return $http.get(serviceBase + q).then(function(results) {
                    return results.data;
                });
            };
            obj.post = function(q, object) {
                return $http.post(serviceBase + q, object).then(function(results) {
                    return results.data;
                });
            };
            obj.put = function(q, object) {
                return $http.put(serviceBase + q, object).then(function(results) {
                    return results.data;
                });
            };
            obj.delete = function(q) {
                return $http.delete(serviceBase + q).then(function(results) {
                    return results.data;
                });
            };
            return obj;
        }
    ])
    .factory("loginData", ['$http', '$location',
        function($http, $q, $location) {

            var serviceBase = 'http://android.brinjal.me/merchant/v2/';

            var obj = {};

            obj.get = function(q) {
                return $http.get(serviceBase + q).then(function(results) {
                    return results.data;
                });
            };
            obj.post = function(q, object) {
                return $http.post(serviceBase + q, object).then(function(results) {
                    return results.data;
                });
            };
            obj.put = function(q, object) {
                return $http.put(serviceBase + q, object).then(function(results) {
                    return results.data;
                });
            };
            obj.delete = function(q) {
                return $http.delete(serviceBase + q).then(function(results) {
                    return results.data;
                });
            };
            return obj;
        }
    ]);

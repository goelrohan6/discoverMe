'use strict';

/**
 * @ngdoc stores
 * @name yapp
 * @description
 * # yapp
 *
 * Main module of the application.
 */

angular
    .module('yapp', [
        'ui.router',
        'ngAnimate',
        'ui.bootstrap',
        'angular-location-picker'
    ])
    .config(function($httpProvider, $stateProvider, $urlRouterProvider) {
        $httpProvider.defaults.headers.common = {};
        $httpProvider.defaults.headers.post = {};
        $httpProvider.defaults.headers.put = {};
        $httpProvider.defaults.headers.patch = {};

        $urlRouterProvider.when('/dashboard', '/dashboard/stores');
        $urlRouterProvider.otherwise('/login');

        $stateProvider
            .state('base', {
                abstract: true,
                url: '',
                templateUrl: 'views/base.html'
            })
            .state('login', {
                url: '/login',
                parent: 'base',
                templateUrl: 'views/login.html',
                controller: 'LoginCtrl'
            })
            .state('dashboard', {
                url: '/dashboard',
                parent: 'base',
                templateUrl: 'views/dashboard.html',
                controller: 'DashboardCtrl'
            })
            .state('stores', {
                url: '/stores',
                parent: 'dashboard',
                templateUrl: 'views/dashboard/stores.html'
            })
            .state('coupons', {
                url: '/coupons',
                parent: 'dashboard',
                templateUrl: 'views/dashboard/coupons.html',
                controller: 'couponsCtrl'
            })
            .state('reports', {
                url: '/reports',
                parent: 'dashboard',
                templateUrl: 'views/dashboard/reports.html'
            });

    })
    .constant('_',
     window._
     )
    .run(function($rootScope, $location, loginData) {
        $rootScope.$on("$routeChangeStart", function(event, next, current) {
            $rootScope.authenticated = false;
            Data.get('session').then(function(results) {
                if (results.uid) {
                    $rootScope.authenticated = true;
                    $rootScope.uid = results.uid;
                    $rootScope.name = results.name;
                    $rootScope.email = results.email;
                } else {
                    var nextUrl = next.$$route.originalPath;
                    if (nextUrl == '/signup' || nextUrl == '/login') {

                    } else {
                        $location.path("/login");
                    }
                }
            });
        });
    });

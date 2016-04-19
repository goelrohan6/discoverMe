'use strict';
/**
 * @ngdoc function
 * @name yapp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of yapp
 */
angular.module('yapp')
    .controller('DashboardCtrl', function($scope, $state, $window, $uibModal, Data) {
        $scope.$state = $state;
        $scope.model = {
            name: 'Tabs'
        };

        $scope.store = {};
        Data.get('stores/1').then(function(data) {
            $scope.stores = data.data;
        });
        $scope.changeStoreStatus = function(store) {
            store.status = (store.status == "Active" ? "Inactive" : "Active");
            Data.put("stores/" + store.id, { status: store.status });
        };
        $scope.deleteStore = function(store) {
            if (confirm("Are you sure to remove the store")) {
                Data.delete("stores/" + store.id).then(function(result) {
                    $scope.stores = _.without($scope.stores, _.findWhere($scope.stores, { id: store.id }));
                });
            }
        };
        $scope.openDetails = function(p, size) {
            var modalInstance = $uibModal.open({
                templateUrl: 'views/dashboard/storeDetailsEdit.html',
                controller: 'storeDetailsEditCtrl',
                size: size,
                resolve: {
                    item: function() {
                        return p;
                    }
                }
            });
            modalInstance.result.then(function(selectedObject) {
                if (selectedObject.save == "insert") {
                    $scope.stores.push(selectedObject);
                    $scope.stores = $filter('orderBy')($scope.stores, 'id', 'reverse');
                } else if (selectedObject.save == "update") {
                    p.description = selectedObject.description;
                    p.price = selectedObject.price;
                    p.mrp = selectedObject.mrp;
                    p.stock = selectedObject.stock;
                    p.packing = selectedObject.packing;
                }
            });
        };
        $scope.openTimings = function(p, size) {
            var modalInstance = $uibModal.open({
                templateUrl: 'views/dashboard/storeTimingsEdit.html',
                controller: 'storeTimingsEditCtrl',
                size: size,
                resolve: {
                    item: function() {
                        return p;
                    }
                }
            });
            modalInstance.result.then(function(selectedObject) {
                if (selectedObject.save == "insert") {
                    $scope.stores.push(selectedObject);
                    $scope.stores = $filter('orderBy')($scope.stores, 'id', 'reverse');
                } else if (selectedObject.save == "update") {
                    p.description = selectedObject.description;
                    p.price = selectedObject.price;
                    p.mrp = selectedObject.mrp;
                    p.stock = selectedObject.stock;
                    p.packing = selectedObject.packing;
                }
            });
        };
        $scope.openLocation = function(p, size) {
            var modalInstance = $uibModal.open({
                templateUrl: 'views/dashboard/storeLocationEdit.html',
                controller: 'storeLocationEditCtrl',
                size: size,
                resolve: {
                    item: function() {
                        return p;
                    }
                }
            });
            modalInstance.result.then(function(selectedObject) {
                if (selectedObject.save == "insert") {
                    $scope.stores.push(selectedObject);
                    $scope.stores = $filter('orderBy')($scope.stores, 'id', 'reverse');
                } else if (selectedObject.save == "update") {
                    p.description = selectedObject.description;
                    p.price = selectedObject.price;
                    p.mrp = selectedObject.mrp;
                    p.stock = selectedObject.stock;
                    p.packing = selectedObject.packing;
                }
            });
        };
    })
    .controller('storeDetailsEditCtrl', function($scope, $uibModalInstance, item, Data) {


        $scope.store = angular.copy(item);
        $scope.cancel = function() {
            $uibModalInstance.dismiss('Close');
        };
        $scope.title = (item.id > 0) ? 'Edit store' : 'Add store';
        $scope.buttonText = (item.id > 0) ? 'Update store' : 'Add New store';
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.store);
        }
        $scope.savestore = function(store) {
            store.uid = $scope.uid;
            if (store.id > 0) {
                Data.put('stores/' + store.id, store).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(store);
                        x.save = 'update';
                        $uibModalInstance.close(x);
                    } else {
                        console.log(result);
                    }
                });
            } else {
                store.status = 'Active';
                Data.post('stores', store).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(store);
                        x.save = 'insert';
                        x.id = result.data;
                        $uibModalInstance.close(x);
                    } else {
                        console.log(result);
                    }
                });
            }
        };
    })
    .controller('storeTimingsEditCtrl', function($scope, $uibModalInstance, item, Data) {



        $scope.store = angular.copy(item);
        $scope.cancel = function() {
            $uibModalInstance.dismiss('Close');
        };
        $scope.title = (item.id > 0) ? 'Edit store' : 'Add store';
        $scope.buttonText = (item.id > 0) ? 'Update store' : 'Add New store';
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.store);
        }
        $scope.savestore = function(store) {
            store.uid = $scope.uid;
            if (store.id > 0) {
                Data.put('stores/' + store.id, store).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(store);
                        x.save = 'update';
                        $uibModalInstance.close(x);
                    } else {
                        console.log(result);
                    }
                });
            } else {
                store.status = 'Active';
                Data.post('stores', store).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(store);
                        x.save = 'insert';
                        x.id = result.data;
                        $uibModalInstance.close(x);
                    } else {
                        console.log(result);
                    }
                });
            }
        };
    })
    .controller('storeLocationEditCtrl', function($scope, $uibModalInstance, item, Data) {


        $scope.startTime = new Date();
        $scope.startTime.setHours(8);
        $scope.startTime.setMinutes(0);
        $scope.endTime = new Date();
        $scope.endTime.setHours(20);
        $scope.endTime.setMinutes(0);
        $scope.hstep = 1;
        $scope.mstep = 15;
        $scope.location = { latitude: 12.8139068, longitude: 77.6516683 };
        $scope.onLocationInitialize = function(location) {
            console.log("=====from controller=======");
        }
        $scope.onLocationChange = function(location) {
            console.log("=====from controller=======")
            $scope.location = location;
            console.log($scope.location);
        }
        $scope.onMapLoaded = function(map) {
            console.log("=====from controller=======")
            console.log(map);
        }

        $scope.store = angular.copy(item);
        $scope.cancel = function() {
            $uibModalInstance.dismiss('Close');
        };
        $scope.title = (item.id > 0) ? 'Edit store' : 'Add store';
        $scope.buttonText = (item.id > 0) ? 'Update store' : 'Add New store';
        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.store);
        }
        $scope.savestore = function(store) {
            store.uid = $scope.uid;
            if (store.id > 0) {
                Data.put('stores/' + store.id, store).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(store);
                        x.save = 'update';
                        $uibModalInstance.close(x);
                    } else {
                        console.log(result);
                    }
                });
            } else {
                store.status = 'Active';
                Data.post('stores', store).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(store);
                        x.save = 'insert';
                        x.id = result.data;
                        $uibModalInstance.close(x);
                    } else {
                        console.log(result);
                    }
                });
            }
        };
    });

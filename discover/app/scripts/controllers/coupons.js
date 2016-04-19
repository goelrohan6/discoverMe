angular.module('yapp')
    .controller('couponsCtrl', function($scope, $state, $window, $uibModal, Data, $filter) {
        $scope.coupon = {};
        Data.get('coupons/1').then(function(data) {
            $scope.coupons = data.data;
        });
        $scope.changeCouponStatus = function(coupon) {
            coupon.status = (coupon.status == "Active" ? "Inactive" : "Active");
            Data.put("coupons/" + coupon.id, { status: coupon.status });
        };
        $scope.deleteCoupon = function(coupon) {
            if (confirm("Are you sure to remove the coupon")) {
                Data.delete("coupons/" + coupon.id).then(function(result) {
                    $scope.coupons = _.without($scope.coupons, _.findWhere($scope.coupons, { id: coupon.id }));
                });
            }
        };
        $scope.open = function(p, size) {
            var modalInstance = $uibModal.open({
                templateUrl: 'views/dashboard/couponEdit.html',
                controller: 'couponEditCtrl',
                size: size,
                resolve: {
                    item: function() {
                        return p;
                    }
                }
            });
            modalInstance.result.then(function(selectedObject) {
                if (selectedObject.save == "insert") {
                    $scope.coupons.push(selectedObject);
                    $scope.coupons = $filter('orderBy')($scope.coupons, 'id', 'reverse');
                } else if (selectedObject.save == "update") {
                    p.description = selectedObject.description;
                    p.price = selectedObject.price;
                    p.mrp = selectedObject.mrp;
                    p.stock = selectedObject.stock;
                    p.packing = selectedObject.packing;
                }
            });
        };

        $scope.columns = [
            { text: "ID", predicate: "id", sortable: true, dataType: "number" },
            { text: "Title", predicate: "Title", sortable: true },
            { text: "Category", predicate: "category", sortable: true },
            { text: "description", predicate: "description",sortable: true},
            { text: "Status", predicate: "status", sortable: true },
            { text: "Action", predicate: "", sortable: false }
        ];

    })
    .controller('couponEditCtrl', function($scope, $uibModalInstance, item, Data) {

        $scope.coupon = angular.copy(item);

        $scope.cancel = function() {
            $uibModalInstance.dismiss('Close');
        };
        $scope.title = (item.id > 0) ? 'Edit coupon' : 'Add coupon';
        $scope.buttonText = (item.id > 0) ? 'Update coupon' : 'Add New coupon';

        var original = item;
        $scope.isClean = function() {
            return angular.equals(original, $scope.coupon);
        }
        $scope.savecoupon = function(coupon) {
            coupon.uid = $scope.uid;
            if (coupon.id > 0) {
                Data.put('coupons/' + coupon.id, coupon).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(coupon);
                        x.save = 'update';
                        $uibModalInstance.close(x);
                    } else {
                        console.log(result);
                    }
                });
            } else {
                coupon.status = 'Active';
                Data.post('coupons', coupon).then(function(result) {
                    if (result.status != 'error') {
                        var x = angular.copy(coupon);
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

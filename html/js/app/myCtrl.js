var app = angular.module("app", ["ngRoute"]);
app.config(function($routeProvider,$locationProvider) {
    $routeProvider
        .when("/acts", {
            templateUrl : "templates/actsGrid.html"
        })
        .when("/bills", {
            templateUrl : "templates/billsGrid.html"
        })
});
app.controller("ctrl", function($scope, $http, $timeout,$filter,$location) {
    $scope.$on('$viewContentLoaded', function(a,b,c) {
        $scope.updateRecords()}
    );

    $scope.orderBy = '';
    $scope.direction = '';
    $scope.currentTpl = '';

    var orderBy = $filter('orderBy');

    $scope.sortGrid = function(field) {
        $scope.orderBy = field;
        $scope.direction = ($scope.orderBy === field) ? !$scope.direction : false;
        $scope.records = orderBy($scope.records,field,$scope.direction);
    };


    $scope.updateRecords = function()
    {
        var params = {};
        var path = $location.path();
        var gridName = path.substring(1);
        $http.get(gridName,{params:params})
            .then(function(response) {
                $scope.records = response.data.records;
            });
    };


    var isEmpty = function(obj) {
        return Object.keys(obj).length === 0;
    };
});

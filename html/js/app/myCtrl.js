var app = angular.module("app", []);
app.controller("ctrl", function($scope, $http, $timeout,$filter) {

    $scope.orderBy = '';
    $scope.direction = '';

    var orderBy = $filter('orderBy');
    $scope.filterGrid = function($event) {
        $timeout(updateRecords,500);
    };

    $scope.sortGrid = function(field) {
        $scope.orderBy = field;
        $scope.direction = ($scope.orderBy === field) ? !$scope.direction : false;
        $scope.records = orderBy($scope.records,field,$scope.direction);
    };

    var updateRecords = function()
    {
        var params = {};
        var filters = serializeFilters();
        if(!isEmpty(filters)) params.filters = filters;

        $http.get("acts",{params:params})
            .then(function(response) {
                $scope.records = response.data.records;
            });
    };

    var serializeFilters = function()
    {
        var res = {};
        for (filter in $scope.filters)
        {
            if($scope.filters[filter].length > 0)
            {
                res[filter] = $scope.filters[filter];
            }
        }
        return res;
    };

    var isEmpty = function(obj) {
        return Object.keys(obj).length === 0;
    };

    updateRecords();
});
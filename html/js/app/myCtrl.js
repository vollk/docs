var app = angular.module("myApp", []);
app.controller("myCtrl", function($scope, $http) {
    $http.get("acts")
        .then(function(response) {
            $scope.records = response.data.records;
        });
});
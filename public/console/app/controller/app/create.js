'use strict'

var angular = require('angular')

module.exports = function ($scope, $http, $uibModalInstance, fusio) {
  $scope.app = {
    status: 1,
    name: '',
    url: '',
    scopes: []
  }

  $scope.states = [{
    key: 1,
    value: 'Active'
  }, {
    key: 2,
    value: 'Pending'
  }, {
    key: 3,
    value: 'Deactivated'
  }]

  $scope.scopes = [];
  $scope.toggle = true;

  $scope.create = function (app) {
    var data = angular.copy(app)

    // remove null values from scope
    if (angular.isArray(data.scopes)) {
      data.scopes = data.scopes.filter(function (val) {
        return !!val
      })
    }

    $http.post(fusio.baseUrl + 'backend/app', data)
      .then(function (response) {
        var data = response.data
        $scope.response = data
        if (data.success === true) {
          $uibModalInstance.close(data)
        }
      })
      .catch(function (response) {
        $scope.response = response.data
      })
  }

  $scope.close = function () {
    $uibModalInstance.dismiss('cancel')
  }

  $scope.closeResponse = function () {
    $scope.response = null
  }

  $scope.bulkSelect = function () {
    if ($scope.toggle) {
      var scopes = [];
      for (var i = 0; i < $scope.scopes.length; i++) {
        scopes.push($scope.scopes[i].name);
      }
      $scope.app.scopes = scopes;
    } else {
      $scope.app.scopes = [];
    }
    $scope.toggle = !$scope.toggle;
  }

  $scope.getUsers = function () {
    $http.get(fusio.baseUrl + 'backend/user?count=1024')
      .then(function (response) {
        $scope.users = response.data.entry
      })
  }

  $scope.getScopes = function () {
    $http.get(fusio.baseUrl + 'backend/scope?count=1024')
      .then(function (response) {
        $scope.scopes = response.data.entry
      })
  }

  $scope.getUsers()
  $scope.getScopes()
}

angular.module('ds1', ['angularjs-dropdown-multiselect'])


    .controller("adminDropdownMultiselect", function ($scope, $window, $log, $http) {

        $scope.myDropdownSettings = {
            styleActive: true,
            checkBoxes: true,
            showEnableSearchButton: true,
            smartButtonTextProvider(selectionArray) {
                if (selectionArray.length === 1) {
                    return selectionArray[0].label;
                } else {
                    return selectionArray.length + ' Vybráno';
                }
            }
        };
    });
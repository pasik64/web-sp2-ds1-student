angular.module('ds1', ['angularjs-dropdown-multiselect'])


    .controller("adminDropdownMultiselect", function ($scope, $window, $log, $http) {
        $scope.myDropdownSettings = {
            checkBoxes: true,
            showEnableSearchButton: true,
            scrollable: true,
            scrollableHeight: 200,
            smartButtonTextProvider(selectionArray) {
                if (selectionArray.length === 1) {
                    return selectionArray[0].label;
                } else {
                    return selectionArray.length + ' Vybráno';
                }
            }


        };
        $scope.projectText = {
            buttonDefaultText: "Vyberte roli",
            checkAll: "Vybrat vše",
            uncheckAll: "Odstranit výběr",
            enableSearch: "Vyhledat",
            disableSearch: "Ukončit vyhledávání"
        };
        var options = document.getElementById("selectBox").options;
        $scope.optionsList = new Array(options.length);
        for (var i=0; i<options.length; i++) {
            $scope.optionsList[i] = { id: options[i].getAttribute("value"), label: options[i].getAttribute("id")};
        }


        $scope.selectedOptions = new Array();
        for (var i=0; i < options.length; i++) {
            if (options[i].getAttribute("selected") == "true") {
                $scope.selectedOptions.push($scope.optionsList[i]);
            }
        }

        $scope.myEvent = {
            onItemSelect(item) {
                document.getElementById("selectBox").options.namedItem(item.label).setAttribute("selected", true);
            },
            onItemDeselect(item) {
                document.getElementById("selectBox").options.namedItem(item.label).removeAttribute("selected");
            },
            onSelectAll() {
                var options = document.getElementById("selectBox").options;
                for (var i=0; i<options.length; i++) {
                    options[i].setAttribute("selected", true);
                }
            },
            onDeselectAll() {
                var options = document.getElementById("selectBox").options;
                for (var i=0; i<options.length; i++) {
                    options[i].removeAttribute("selected");
                }
            }
        }

    });
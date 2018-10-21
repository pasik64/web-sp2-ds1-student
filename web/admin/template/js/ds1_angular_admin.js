angular.module('ds1', [])


// ADMIN - Sections Controller - správa sekcí
.controller("adminSectionsController", function ($scope, $window, $log, $http) {

    // az tady si mohu naplnit strom daty z window - TIMTO SE SPUSTI JS TREE
    $scope.jstreeContextMenu = $window.jstreeContextMenu;
    //console.log($scope.jstreeContextMenu);

    $scope.jstree_data = $window.jstree_data;

})

// Admin - Goods Controller - detail zbozi
.controller("adminGoodsDetailController", function ($scope, $window, $log, $http) {

    // INICIALIZACE
    $scope.selected_sections = [];

    // metody pro jsTree
    $scope.readyCB = function() {
        //console.log('ready event call back');
        //console.log($scope.jstree_data);
    };

    $scope.changedCB = function(e, data) {
        console.log('changed event call back');

        // vratit vybranee uzly
        $scope.selected_sections = data.instance.get_selected();
        if ($scope.selected_sections.length > 0) {
            $scope.$apply(); // jinak se to neprobije ven
        }


        $log.log($scope.selected_sections);
    };

    $scope.openNodeCB = function(e, data) {
        //console.log('open-node event call back');
    };
    // konec metody pro volani z jstree

    // pro testy
    /*
    $scope.jstree_data =  [
        { "id": "ajson1", "parent": "#", "text": "A", "state": { "opened": true }, "__uiNodeId": 1 },
        { "id": "ajson2", "parent": "#", "text": "Root node 2", "state": { "opened": true }, "__uiNodeId": 2 },
        { "id": "ajson3", "parent": "ajson2", "text": "Child 1", "state": { "opened": true }, "__uiNodeId": 3 },
        { "id": "ajson4", "parent": "ajson2", "text": "Child 2", "state": { "opened": true }, "__uiNodeId": 4 } ];
    */

    // po nacteni stranky
    angular.element(document).ready(function () {

        //console.log("scope jstree data:");
        //console.log($scope.jstree_data);

        // az tady si mohu naplnit strom daty z window - TIMTO SE SPUSTI JS TREE
        $scope.jstree_data = $window.jstree_data;


    });

})

// kopie http://jstree-directive.herokuapp.com/#/events
.directive('jsTree', ['$http', function($http) {

        var treeDir = {
            restrict: 'EA',
            fetchResource: function(url, cb) {
                return $http.get(url).then(function(data) {
                    if (cb) cb(data.data);
                });
            },

            managePlugins: function(s, e, a, config) {
                //console.log("my directive jsTree");

                if (a.treePlugins) {
                    config.plugins = a.treePlugins.split(',');
                    config.core = config.core || {};
                    config.core.check_callback = config.core.check_callback || true;

                    if (config.plugins.indexOf('state') >= 0) {
                        config.state = config.state || {};
                        config.state.key = a.treeStateKey;
                    }

                    if (config.plugins.indexOf('search') >= 0) {
                        var to = false;
                        if (e.next().attr('class') !== 'ng-tree-search') {
                            e.after('<input type="text" placeholder="Search Tree" class="ng-tree-search"/>')
                                .next()
                                .on('keyup', function(ev) {
                                    if (to) {
                                        clearTimeout(to);
                                    }
                                    to = setTimeout(function() {
                                        treeDir.tree.jstree(true).search(ev.target.value);
                                    }, 250);
                                });
                        }
                    }

                    if (config.plugins.indexOf('checkbox') >= 0) {
                        config.checkbox = config.checkbox || {};
                        config.checkbox.keep_selected_style = false;
                    }

                    if (config.plugins.indexOf('contextmenu') >= 0) {
                        if (a.treeContextmenu) {
                            config.contextmenu = s[a.treeContextmenu];

                            //console.log("custom context menu set");
                            //console.log(config.contextmenu);
                        }
                    }

                    if (config.plugins.indexOf('types') >= 0) {
                        if (a.treeTypes) {
                            config.types = s[a.treeTypes];
                            console.log(config);
                        }
                    }

                    if (config.plugins.indexOf('dnd') >= 0) {
                        if (a.treeDnd) {
                            config.dnd = s[a.treeDnd];
                            console.log(config);
                        }
                    }
                }
                return config;
            },
            manageEvents: function(s, e, a) {
                if (a.treeEvents) {
                    var evMap = a.treeEvents.split(';');
                    for (var i = 0; i < evMap.length; i++) {
                        if (evMap[i].length > 0) {
                            // plugins could have events with suffixes other than '.jstree'
                            var evt = evMap[i].split(':')[0];
                            if (evt.indexOf('.') < 0) {
                                evt = evt + '.jstree';
                            }
                            var cb = evMap[i].split(':')[1];
                            treeDir.tree.on(evt, s[cb]);
                        }
                    }
                }
            },
            link: function(s, e, a) { // scope, element, attribute \O/
                $(function() {
                    var config = {};

                    // users can define 'core'
                    config.core = {};
                    if (a.treeCore) {
                        config.core = $.extend(config.core, s[a.treeCore]);
                    }

                    // clean Case
                    a.treeData = a.treeData ? a.treeData.toLowerCase() : '';
                    a.treeSrc = a.treeSrc ? a.treeSrc.toLowerCase() : '';

                    if (a.treeData == 'html') {
                        treeDir.fetchResource(a.treeSrc, function(data) {
                            e.html(data);
                            treeDir.init(s, e, a, config);
                        });
                    } else if (a.treeData == 'json') {
                        treeDir.fetchResource(a.treeSrc, function(data) {
                            config.core.data = data;
                            treeDir.init(s, e, a, config);
                        });
                    } else if (a.treeData == 'scope') {
                        s.$watch(a.treeModel, function(n, o) {

                            console.log("watch model");
                            if (n) {
                                config.core.data = s[a.treeModel];
                                $(e).jstree('destroy');
                                treeDir.init(s, e, a, config);
                            }
                        }, true);
                        // Trigger it initally
                        // Fix issue #13
                        config.core.data = s[a.treeModel];
                        treeDir.init(s, e, a, config);
                    } else if (a.treeAjax) {
                        config.core.data = {
                            'url': a.treeAjax,
                            'data': function(node) {
                                return {
                                    'id': node.id != '#' ? node.id : 1
                                };
                            }
                        };
                        treeDir.init(s, e, a, config);
                    }
                });

            },
            init: function(s, e, a, config) {
                treeDir.managePlugins(s, e, a, config);
                this.tree = $(e).jstree(config);
                treeDir.manageEvents(s, e, a);
            }
        };

        return treeDir;

    }]);
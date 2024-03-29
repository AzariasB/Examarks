/* 
 * The MIT License
 *
 * Copyright 2017 azarias.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


(function () {
    'use strict';

    angular.module('examarks')
            .controller('Controller', ['post', 'modalForm', 'Notification', '$scope', '$compile', userListController]);

    function userListController(post, modalForm, Notification, $scope, $compile) {

        var self = this;

        //Attributes
        self.lastAssessmentId = null;
        self.dataPrototype = null;
        self.deleteModulePath = null;
        self.editModulePath = null;
        self.modules = [];
        self.queue = [];

        self.nwAssessments = {};

        //Funtions
        self.showNewModuleModal = showNewModuleModal;
        self.addAssessment = addAssessment;
        self.addAssessmentForm = addAssessmentForm;
        self.deleteModule = deleteModule;
        self.editModule = editModule;
        self.cancelRemoval = cancelRemoval;
        self.totalWeight = totalWeight;
        self.init = init;

        function totalWeight() {
            return Object.keys(self.nwAssessments).reduce(function (a, b) {
                return a + self.nwAssessments[b];
            }, 0);
        }


        function editModule(moduleId) {
            var path = self.editModulePath.replace('__id__', moduleId);
            post(path, function (response) {
                modalForm($scope, response.data.form, path, function (data) {
                    var mod = data.module;
                    if (!Array.isArray(mod.students)) {
                        var studs = mod.students;
                        mod.students = [];
                        Object.keys(studs).map(function (x) {
                            mod.students.push(x);
                        });
                    }

                    self.modules = self.modules.map(function (x) {
                        return x.id === data.module.id ? data.module : x;
                    });
                    if (data.success) {
                        Notification.success(data.message);
                    } else {
                        Notification.error(data.message);
                    }
                });
            });
        }

        function deleteModule(moduleId) {
            self.modules = self.modules.filter(function (x) {
                if (x.id === moduleId) {
                    self.queue.push(x);
                    return false;
                }
                return true;
            });
            Notification.info({
                'message': 'Deleting module ...<a href="#" ng-click="ctrl.cancelRemoval(' + moduleId + ')" >Cancel</a>',
                'templateUrl': '/notifTemplate',
                'scope': $scope,
                'onClose': function () {
                    confirmDeletion(moduleId);
                }
            });
        }

        function confirmDeletion(moduleId) {
            if (!self.queue.some(function (x) {
                return x.id === moduleId;
            }))
                return;

            var path = self.deleteModulePath.replace('__id__', moduleId);
            post(path, function (response) {
                if (response.data.success) {
                    Notification.success(response.data.message);
                } else {
                    Notification.error(response.data.message);
                }
            });

            self.queue = self.queue.filter(function (x) {
                return x.id !== moduleId;
            });
        }

        function cancelRemoval(moduleId) {
            self.queue = self.queue.filter(function (x) {
                if (x.id === moduleId) {
                    self.modules.push(x);
                    return false;
                }
                return true;
            });
        }

        function showNewModuleModal(requestUrl) {
            post(requestUrl, function (response) {
                modalForm($scope, response.data, requestUrl, actionUpdated);
            });
        }

        function actionUpdated(data) {
            if (data.success) {
                Notification.success("Successfully created");
                self.modules.push(data.module);
            } else {
                Notification.error(data.message);
            }
        }

        function addAssessment(lastId) {
            if (self.lastAssessmentId === null) {
                self.lastAssessmentId = lastId;
            }
            self.addAssessmentForm();

        }

        function addAssessmentForm() {
            var $nwForm = $(self.dataPrototype.replace(/__name__/g, self.lastAssessmentId));
            var aId = "assessment" + self.lastAssessmentId;

            self.nwAssessments[aId] = 0;
            $nwForm.find("input[type=number]").attr("ng-model", "ctrl.nwAssessments['" + aId + "']");

            var $span = $("<a class='pull-right' href='#'><span>&times;</span></a>");

            var $el = $("<div id='" + aId + "' class='col-xs-12 well'></div").append($span).append($compile($nwForm)($scope));

            $span.click(function () {
                $el.remove();
                delete self.nwAssessments[aId];
            });

            $("#modal-main").on("hide.bs.modal", function (e) {
                self.nwAssessments = {};
            });

            self.lastAssessmentId++;

            $("#assessementsList").append($el);
        }


        function init(deleteModulePath, editModulePath) {
            self.deleteModulePath = deleteModulePath;
            self.editModulePath = editModulePath;

            post(window.location.origin + window.location.pathname + '/json', function (response) {
                self.modules = response.data.modules;
            });
        }
    }

})();
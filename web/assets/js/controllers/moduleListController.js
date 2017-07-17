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
            .controller('Controller', ['post', 'modalForm', 'Notification', '$scope', userListController]);

    function userListController(post, modalForm, Notification, $scope) {

        var self = this;

        //Attributes
        self.lastAssessmentId = null;
        self.dataPrototype = null;
        self.deleteModulePath = null;
        self.modules = [];
        self.queue = [];

        self.nwAssessments = [];

        //Funtions
        self.showNewModuleModal = showNewModuleModal;
        self.addAssessment = addAssessment;
        self.removeAssessment = removeAssessment;
        self.addAssessmentForm = addAssessmentForm;
        self.deleteModule = deleteModule;
        self.moduleDeleted = moduleDeleted;
        self.cancelRemoval = cancelRemoval;
        self.init = init;

        function moduleDeleted(response) {
            Notification.success(response.message);
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

        function removeAssessment($el) {

        }

        function addAssessmentForm() {
            var nwForm = self.dataPrototype.replace(/__name__/g, self.lastAssessmentId);
            var aId = "assessment" + self.lastAssessmentId;
            var $span = $("<a class='pull-right' href='#'><span>&times;</span></a>");

            var $el = $("<div id='" + aId + "' class='col-xs-12 well'></div").append($span).append(nwForm);

            $span.click(function () {
                $el.remove();
            });
            self.lastAssessmentId++;

            $("#assessementsList").append($el);
        }


        function init(deleteModulePath) {
            self.deleteModulePath = deleteModulePath;

            post(window.location.origin + window.location.pathname + '/json', function (response) {
                self.modules = response.data.modules;
            });
        }
    }

})();
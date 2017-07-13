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
        self.modules = [];

        self.nwAssessments = [];

        //Funtions
        self.showNewModuleModal = showNewModuleModal;
        self.addAssessment = addAssessment;
        self.removeAssessment = removeAssessment;
        self.addAssessmentForm = addAssessmentForm;

        function showNewModuleModal(requestUrl) {
            post(requestUrl, function (response) {
                modalForm($scope, response.data, requestUrl, actionUpdated);
            });
        }

        function actionUpdated(data) {
            if (data.success) {
                Notification.success("Successfully created");
            } else {
                Notification.error(data.message);
            }
        }

        function addAssessment(lastId) {
            console.log("Add assessment", lastId);
            if (self.lastAssessmentId === null) {
                self.lastAssessmentId = lastId;
            }
            self.addAssessmentForm();

        }

        function removeAssessment($el) {

        }

        function addAssessmentForm() {
            var nwForm = self.dataPrototype.replace(/__name__/g, self.lastAssessmentId);
            self.lastAssessmentId++;
            var $el = $("<div class='col-xs-12'></div").append(nwForm);
            $("#assessementsList").append($el);
        }

    }

})();
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

        //Attributse
        self.users = [];
        self.queue = [];

        //Functions
        self.showNewStudentModal = showNewStudentModal;
        self.init = init;
        self.toPath = toPath;

        function showNewStudentModal(requestUrl) {
            post(requestUrl, function (response) {
                modalForm($scope, response.data, requestUrl, actionUpdated, Notification.error);
            });
        }

        function deleteUser(userId) {

        }

        function actionUpdated(data) {
            if (data.success) {
                self.users.push(data.student);
                Notification.success(data.message);
            } else {
                Notification.error(data.message);
            }
        }

        function init() {
            post(window.location.href + '/json', function (response) {
                self.users = response.data.users;
            });
        }

        function toPath(user) {

        }

        self.init();
    }

})();
//{{ user.isStudent ? path('student', {'studentId' : user.id }) : path('teacher', {'teacherId' : user.id}) }}
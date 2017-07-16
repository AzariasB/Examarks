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
            .controller('Controller', ['post', 'modalForm', 'Notification', '$scope', '$window', userListController]);

    function userListController(post, modalForm, Notification, $scope, $window) {

        var self = this;

        //Attributse
        self.users = [];
        self.queue = [];
        self.deleteUserPath;
        self.editUserPath;

        //Functions
        self.showNewStudentModal = showNewStudentModal;
        self.init = init;
        self.deleteUser = deleteUser;
        self.confirmRemoval = confirmRemoval;
        self.cancelRemoval = cancelRemoval;
        self.fastUserRemove = fastUserRemove;
        self.editUserClick = editUserClick;
        self.editedUser = editedUser;

        var cleanedUp = false;

        $window.onbeforeunload = function () {
            if (!cleanedUp) {
                self.queue.map(function (x) {
                    self.fastUserRemove(x.id);
                });
            }
            cleanedUp = true;
        };

        function editedUser(data) {
            self.users = self.users.map(function (x) {
                return x.id === data.student.id ? data.student : x;
            });
        }

        function editUserClick(userId) {
            var editPath = self.editUserPath.replace('__id__', userId);
            post(editPath, function (response) {
                modalForm($scope, response.data.form, editPath, self.editedUser);
            });
        }

        function fastUserRemove(userId) {
            post(self.deleteUserPath.replace('__id__', userId), function (response) {
                if (response.data.success) {
                    Notification.success(response.data.message);
                } else {
                    Notification.error(response.data.message);
                }
            }, null, null, false);
        }

        function showNewStudentModal(requestUrl) {
            post(requestUrl, function (response) {
                modalForm($scope, response.data, requestUrl, actionUpdated, Notification.error);
            });
        }

        function deleteUser(userId) {
            self.users = self.users.filter(function (x) {
                if (x.id === userId) {
                    self.queue.push(x);
                    return false;
                }
                return true;
            });
            Notification.info({
                'message': 'Deleting user ...<a href="#" ng-click="ctrl.cancelRemoval(' + userId + ')" >Cancel</a>',
                'templateUrl': '/notifTemplate',
                'scope': $scope,
                'onClose': function () {
                    self.confirmRemoval(userId);
                }
            });
        }

        function cancelRemoval(userId) {
            self.queue = self.queue.filter(function (x) {
                if (x.id === userId) {
                    self.users.push(x);
                    return false;
                }
                return true;
            });
        }

        function confirmRemoval(userId) {
            if (!self.queue.some(function (x) {
                return x.id === userId;
            })) {
                return;
            }

            self.queue.filter(function (x) {
                return x.id !== userId;
            });
            post(self.deleteUserPath.replace('__id__', userId), function (response) {
                if (response.data.success) {
                    Notification.success(response.data.message);
                } else {
                    Notification.error(response.data.message);
                }
            });
        }

        function actionUpdated(data) {
            if (data.success) {
                self.users.unshift(data.student);
                Notification.success(data.message);
            } else {
                Notification.error(data.message);
            }
        }

        function init(deleteSPath, editPath) {
            self.deleteUserPath = deleteSPath;
            self.editUserPath = editPath;
            post(window.location.origin + window.location.pathname + '/json', function (response) {
                self.users = response.data.users;
            });
        }
    }

})();
//{{ user.isStudent ? path('student', {'studentId' : user.id }) : path('teacher', {'teacherId' : user.id}) }}
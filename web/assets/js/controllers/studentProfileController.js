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

    angular.module('examarks')
            .controller('Controller', ['$scope', 'post', 'Notification', '$window', studentProfileController]);

    function studentProfileController($scope, post, Notification, $window) {
        var self = this;


        //Atributes
        self.studentId;
        self.moduleList;
        self.removeFromModulePath;

        self.modules = [];
        self.queue = [];

        //Methods
        self.init = init;
        self.removeModule = removeModule;
        self.cancelRemoval = cancelRemoval;
        self.confirmRemoval = confirmRemoval;
        
        $window.onbeforeunload = onBeforeUnload;
        
        function onBeforeUnload(){
            self.queue.forEach(function(x){
                self.confirmRemoval(x);
            });
        }

        function init(studentId, studentListPath, removestudentFromModulePath) {
            self.studentId = studentId;
            self.moduleList = studentListPath;
            self.removeFromModulePath = removestudentFromModulePath;

            post(studentListPath, function (response) {
                self.modules = response.data.modules;
            });

        }

        function removeModule(module) {
            self.queue.push(module);
            self.modules = self.modules.filter(function (x) {
                return x !== module;
            });
            Notification.info({
                message: 'Deleting module ...<a href="#" ng-click="ctrl.cancelRemoval(' + module.id + ')" >Cancel</a> ',
                onClose: function () {
                    self.confirmRemoval(module);
                },
                scope: $scope,
                templateUrl: '/notifTemplate'
            });
        }

        function confirmRemoval(module) {
            //If queue does not contain module, do not delete
            if (self.queue.indexOf(module) === -1) {
                return;
            }

            var path = self.removeFromModulePath.replace(/__id__/, module.id);
            post(path, function (data) {
                if (data.success) {
                    Notification.success({
                        message: 'Module was successfully removed'
                    });
                }
                self.queue = self.queue.filter(function (x) {
                    return x !== module;
                });
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

    }

})();
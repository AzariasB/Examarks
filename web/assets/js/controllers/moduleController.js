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
            .controller('Controller', ['post', 'modalForm', 'Notification', '$scope', '$ngConfirm', ModuleController]);

    function ModuleController(post, modalForm, Notification, $scope, ngConfirm) {

        var self = this;

        self.deleteModule = deleteModule;


        function deleteModule($event) {
            $event.preventDefault();

            ngConfirm({
                title: 'Delete this module ?',
                content: 'Do you really want to delete this module ?',
                scope: $scope,
                buttons: {
                    Yes: {
                        text: 'Yes',
                        'btnClass': 'btn-danger',
                        action: function () {
                            location.href = $event.target.href;
                        }
                    },
                    No: {
                        text: 'No',
                        'btnClass': 'btn-primary',
                        ation: function () {
                            return true;
                        }
                    }
                }
            });
        }
    }

})();
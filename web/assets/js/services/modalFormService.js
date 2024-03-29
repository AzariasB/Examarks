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
            .factory('modalForm', ['post', modalFormService]);

    function modalFormService(post) {
        $(document).ready(function () {
            if (!$("#modal-main")) {
                throw new "The modal must be in the page";
            }
        });

        function defaultFailure(response) {
            console.error(response.data);
        }

        function ajaxCallback($scope, submitCallback) {
            $scope.submitting = true;
            return function (response) {
                $scope.submitting = false;
                var data = response.data;
                if (data.success) {
                    $("#modal-main").modal('hide');
                    submitCallback && submitCallback(data);
                }
            };
        }

        function showModal($scope, data, url, submitCallback, failure) {
            failure = failure || defaultFailure;
            $scope.modalHtml = data;
            $scope.submit = function (fId) {
                var serialized = $('#' + fId).serialize();
                post(url, ajaxCallback($scope, submitCallback), serialized, failure);
            };
            $("#modal-main").modal();
            $("#modal-main").on("hide.bs.modal", function (e) {
                $scope.modalHtml = '';//reset html
            });
        }

        return showModal;
    }
})();
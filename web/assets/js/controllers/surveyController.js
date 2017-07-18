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
            .controller('Controller', ['$scope', 'post', 'Notification', SurveyController]);

    function SurveyController($scope, post, Notif) {
        var self = this;
        //Attributes
        self.progression = 0;
        self.questionIndex = 0;
        self.formPath = null;
        self.questionNumber = 0;
        self.currentValues = [];

        //Functions
        self.init = init;
        self.nextQuestion = nextQuestion;
        self.previousQuestion = previousQuestion;
        self.updateProgression = updateProgression;

        $scope.$watch('ctrl.questionIndex', self.updateProgression);

        function updateProgression() {
            self.progression = (self.questionIndex / self.questionNumber) * 100 | 0;
        }

        function nextQuestion() {
            self.questionIndex++;
        }

        function previousQuestion() {
            self.questionIndex--;
        }

        function init(formPath) {
            self.formPath = formPath;

            post(self.formPath, function (response) {
                $scope.form = response.data.form;
            });
        }

    }

})();

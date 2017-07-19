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


$(function () {
    var ctx = $("#canvas1");
    var ctx2 = $("#canvas2");

    var chart1Labels = [],
            chart2Labels = [],
            chart1Data = [],
            chart2Data = [];


    var chart1 = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chart1Labels,
            datasets: [{
                    label: 'Number of Assessments',
                    data: chart1Data,
                    backgroundColor: [
                        'rgba(63, 182, 24, 0.8)',
                        'rgba(39, 128, 227, 0.8)'
                    ],
                    borderColor: [
                        'rgba(63, 182, 24, 1)',
                        'rgba(39, 128, 227, 1)'
                    ],
                    borderWidth: 1
                }]
        },
        options: {
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
            }
        }
    });

    var chart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: chart2Labels,
            datasets: [{
                    label: 'Number of students',
                    data: chart2Data,
                    backgroundColor: [
                        'rgba(39, 128, 227, 0.8)',
                        'rgba(63, 182, 24, 0.8)',
                        'rgba(255, 0, 30, 0.8)'
                    ],
                    borderColor: [
                        'rgba(39, 128, 227, 1)',
                        'rgba(63, 182, 24, 1)',
                        'rgba(255, 0, 30, 1)'
                    ],
                    borderWidth: 1
                }]
        },
        options: {
            scales: {
                yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
            }
        }
    });

    $.post(location.href + '/json', function (data) {
        Object.keys(data.assessments).map(function (k) {
            chart1Labels.push(k);
            chart1Data.push(data.assessments[k]);
        });
        chart1.update();

        Object.keys(data.students).map(function (k) {
            chart2Labels.push(k);
            chart2Data.push(data.students[k]);
        });
        chart2.update();
    });



});
((document, $) => {
    'use strict';

    $(function () {
        let firstOfMonth = $('#firstOfMonth').val();
        let now = $('#now').val();

        // Storage date filter range
        let fromDate = '';
        let toDate = '';

        // Init date range
        let $dateRange = $('#dateRange').flatpickr({
            maxDate: 'today',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd-m-Y',
            mode: 'range',
            defaultDate: getDefaultDate(),
            onChange: function (selectedDates, dateStr, instance) {
                fromDate = formatDate(selectedDates[0]);

                if (selectedDates.length > 1) {
                    toDate = formatDate(selectedDates[1]);
                } else {
                    toDate = formatDate(new Date());
                }

                // Set date filter
                $('#from_date').val(fromDate);
                $('#to_date').val(toDate);
            }
        });

        let $workingTimeByTypeChartEl = $('#workingTimeByTypeChart');
        let $workingTimeByProjectChartEl = $('#workingTimeByProjectChart');

        // Format date to yyyy-mm-dd
        function formatDate(date) {
            let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();

            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;

            return [year, month, day].join('-');
        }

        // Get default date
        function getDefaultDate() {
            let oldFromDate = $('#oldFromDate').val();
            let oldToDate = $('#oldToDate').val();

            return [
                (oldFromDate || firstOfMonth),
                (oldToDate || now)
            ];
        }

        // Init Select 2 for date filter range
        $('#projectSelect').select2({
            placeholder: 'Select Project',
            allowClear: true
        }).val($('#oldProjectId').val())
            .trigger('change');

        // Draw Working Time By Type
        let workingTimeByType = JSON.parse(
            $('#workingTimeByType').val()
        );

        if (workingTimeByType && $workingTimeByTypeChartEl.length) {
            let data = {
                labels: workingTimeByType.labels,
                datasets: [{
                    data: workingTimeByType.data,
                    backgroundColor: [
                        '#15be4c',
                        '#0606b8',
                        '#eabc04',
                        '#f80346'
                    ],
                    hoverBackgroundColor: [
                        '#85f2ad',
                        '#9aceeb',
                        '#f0d04f',
                        '#f2ad85'
                    ]
                }]
            };

            let config = {
                type: 'doughnut',
                data: data,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    responsiveAnimationDuration: 500,
                    cutoutPercentage: 60,
                    legend: {
                        display: false
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Working Time By Working Type Chart'
                        }
                    }
                }
            }

            // Draw Chart
            new Chart($workingTimeByTypeChartEl, config);
        }

        // Draw Working Time By Project
        let workingTimeByProject = JSON.parse(
            $('#workingTimeByProject').val()
        );

        if (workingTimeByProject &&
            $workingTimeByProjectChartEl.length) {
            const data = {
                labels: workingTimeByProject.labels,
                datasets: [{
                    label: 'Working Times(hours)',
                    data: workingTimeByProject.data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                    ],
                    borderColor: [
                        'rgb(255, 99, 132)',
                    ],
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                // Include a dollar sign in the ticks
                                callback: function (value, index, ticks) {
                                    return value + 'h';
                                }
                            },
                            title: {
                                display: true,
                                text: 'Hours',
                                align: 'end'
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Project',
                                align: 'end'
                            }
                        }
                    },
                    responsive: true,
                    responsiveAnimationDuration: 500,
                    legend: {
                        display: true
                    },
                    plugins: {
                        title: {
                            display: true,
                            text: 'Working Time By Project Chart'
                        }
                    }
                },
            };

            // Draw Chart
            new Chart($workingTimeByProjectChartEl, config);
        }
    });
})(document, jQuery);

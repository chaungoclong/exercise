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

        let $workingTimeByPositionChartEl =
            $('#workingTimeByPositionChart');
        let $workingTimeByProjectChartEl =
            $('#workingTimeByProjectChart');

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

        // Init Project Users table
        $('#projectMembersTbl').DataTable();

        // Draw Working Time By Position
        let workingTimesByPosition = JSON.parse(
            $('#workingTimesByPosition').val()
        );

        if (workingTimesByPosition &&
            $workingTimeByPositionChartEl.length) {
            const data = {
                labels: workingTimesByPosition.labels,
                datasets: [{
                    label: 'Working Times(hours)',
                    data: workingTimesByPosition.data,
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
                                text: 'Positions',
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
                            text: 'Working Time By Position Chart'
                        }
                    }
                },
            };

            // Draw Chart
            new Chart($workingTimeByPositionChartEl, config);
        }
    });
})(document, jQuery);

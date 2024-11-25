@section('title', 'Calendar')

@include('modals.event-compliance-modal')


<x-main>
    <h2 class="fw-bold mb-5" style="font-size: 30px !important;">Calendar</h2>

    <div>
        <div class="card-lg calendar-card">
            <div id='calendar'></div>

        </div>
    </div>

</x-main>

<style>
    /* Style FullCalendar events as buttons */
    .fc-event {
        background-color: #EDF2FF; /* Button-like background */
        color: #4680FF !important; /* White text for contrast */
        border: 1px solid #4680FF; /* Remove default border */
        border-radius: 10px !important; /* Rounded corners */
        padding: 5px 10px; /* Padding for a button look */
        font-size: 14px; /* Adjust font size */
        cursor: pointer; /* Pointer cursor on hover */
        transition: background-color 0.3s ease; /* Smooth hover effect */
        text-align: center !important;
        
    }

    .fc-daygrid-event {
        border-radius: 10px !important;
    }

    /* Optional: For timeGrid events */
    .fc-timegrid-event {
        border-radius: 10px !important;
    }

    /* Hover effect for events */
    .fc-event:hover {
        background: none !important;
        background-color: none; /* Darker blue on hover */
    }

    /* Optional: Remove focus outline */
    .fc-event:focus {
        outline: none;
        box-shadow: none;
    }

    .fc-daygrid-event-dot {
        display: none; /* Hide the dot */
    }

    /* Adjust alignment of event text after removing the dot */
    .fc-event-title {
        margin-left: 0; /* Ensure the text aligns properly */
    }
</style>

<style>
    .calendar-card {
        padding: 25px !important;
    }

    .fc-toolbar-title {
        font-size: 16px !important; /* Larger font size */
        font-weight: bold; /* Bold text */
        text-transform: uppercase; /* Make the text uppercase */
    }

    .fc-prev-button {
        margin-right: 5px !important;
    }

    .fc .fc-button {
        
    }

    /* Styling for Previous and Next buttons */
    .fc-prev-button, .fc-next-button {
        background-color: #E4ECFF !important; /* Green background */
        color: #4680FF !important; /* White arrow color */
        border: none !important; /* Remove border */
        border-radius: 50% !important; /* Make it circular */
        width: 32px !important; /* Width of the button */
        height: 32px !important; /* Height of the button */
        display: flex !important; /* Center the content */
        justify-content: center !important;
        align-items: center !important;
        font-size: 14px !important; /* Arrow size */
        cursor: pointer !important; /* Pointer cursor */
        transition: background-color 0.3s ease !important; /* Smooth hover effect */
    }

    .fc-prev-button:focus, .fc-next-button:focus {
        outline: none !important; /* Remove the default focus outline */
        box-shadow: none !important; /* Optional: Remove any focus-related box-shadow */
    }

    /* Hover effect for Previous and Next buttons */
    .fc-prev-button:hover, .fc-next-button:hover {
        background-color: #4680FF !important;
        color: #FFFFFF !important;
    }

    .fc .fc-button {
        border: none !important; /* Remove border */
        font-size: 14px !important; /* Set font size */
        cursor: pointer !important; /* Pointer cursor */
        transition: background-color 0.3s ease !important; /* Smooth hover effect */
        padding: 10px 15px !important; /* Add padding */
        border-radius: 50px !important; /* Rounded corners */
    }

    .fc-today-button,
    .fc-dayGridMonth-button, 
    .fc-timeGridWeek-button, 
    .fc-timeGridDay-button, 
    .fc-listMonth-button {
        background-color: #E4ECFF !important;
        color: #4C83FF !important;
        border: none !important;
    }

    .fc-today-button,
    .fc-dayGridMonth-button:hover,
    .fc-timeGridWeek-button:hover, 
    .fc-timeGridDay-button:hover, 
    .fc-listMonth-button:hover {
        background-color: #4680FF !important;
        color: #FFFFFF !important;
        border: none !important;
    }

    .fc-button-active {
        background: #4680FF !important;
        color: #FFFFFF !important;
    }

    .fc-dayGridMonth-button:focus, 
    .fc-timeGridWeek-button:focus, 
    .fc-timeGridDay-button:focus, 
    .fc-listMonth-button:focus {
        outline: none !important;
        box-shadow: none !important;
    }

    .fc-dayGridMonth-button, 
    .fc-timeGridWeek-button, 
    .fc-timeGridDay-button {
        margin-right: 5px !important;
    }

    /* Hover effect for the Today button */
    .fc-today-button:hover {
        background-color: #4680FF !important; /* Darker orange on hover */
    }

    /* Remove focus outline on click or focus */
    .fc-today-button:focus {
        outline: none !important; /* Remove the default focus outline */
        box-shadow: none !important; /* Optional: Remove any focus-related box-shadow */
    }

  

    .fc-theme-standard th {
        /* padding: 25px !important; */
    }

    .fc-col-header-cell.fc-day-sun {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    /* Sunday */
    .fc-col-header-cell.fc-day-sun {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    /* Monday */
    .fc-col-header-cell.fc-day-mon {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    /* Tuesday */
    .fc-col-header-cell.fc-day-tue {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    /* Wednesday */
    .fc-col-header-cell.fc-day-wed {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    /* Thursday */
    .fc-col-header-cell.fc-day-thu {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    /* Friday */
    .fc-col-header-cell.fc-day-fri {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    /* Saturday */
    .fc-col-header-cell.fc-day-sat {
        border-left: none !important;
        border-right: none !important;
        padding: 8px 0px !important;
    }

    .fc-daygrid-day {
        font-size: 12px !important;

    }

    /* Date */
    .fc-daygrid-day:hover {
        background-color: #f0f8ff; /* Light blue background on hover */
        cursor: pointer; /* Change cursor to pointer */
        transition: background-color 0.3s ease; /* Smooth transition */
    }

    /* Optional: Adjust text styling on hover */
    .fc-daygrid-day:hover .fc-daygrid-day-number {
        color: #0056b3; /* Change date number color on hover */
        font-weight: bold; /* Make text bold */
    }


</style>

<script>

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize the Calendar
        var calendarEl = document.getElementById('calendar');


        var calendar = new FullCalendar.Calendar(calendarEl, {
            // plugins: ['dayGrid', 'timeGrid', 'list', 'interaction'],
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
            },
            buttonText: {
                today: 'Today',
                dayGridMonth: 'Month', // Customize 'Month' button
                timeGridWeek: 'Week',  // Customize 'Week' button
                timeGridDay: 'Day',    // Customize 'Day' button
                listMonth: 'List'       // Customize 'List' button
            },
            events: [
                @foreach ($projections as $monthYear => $items)
                    @foreach ($items as $item)
                        {
                            title: '{{ $item['compliance']['compliance_name'] }}'.replace(/&amp;/g, '&'),  // Event title
                            start: '{{ \Carbon\Carbon::parse($item['deadline'])->toIso8601String() }}',  // Event start date
                            end: '{{ \Carbon\Carbon::parse($item['deadline'])->addHour()->toIso8601String() }}',  // Optional: add an end time if needed
                            
                            compliance_name: '{{ $item['compliance']['compliance_name'] }}'.replace(/&amp;/g, '&'),
                            department: '{{ $item['compliance_department'] }}',
                            start_date: '{{ \Carbon\Carbon::parse($item['startWorkingOn'])->format('F j, Y') }}',
                            submit_date: '{{ \Carbon\Carbon::parse($item['submitOn'])->format('F j, Y') }}',
                            deadline: '{{ \Carbon\Carbon::parse($item['deadline'])->format('F j, Y') }}',
                            days_left: '{{ abs($item['days_left']) }}',
                        },
                    @endforeach
                @endforeach
            ],
            eventRender: function(event, element) {
                // Remove the date and time from the event display
                element.find('.fc-time').remove();  // Removes the time
                element.find('.fc-date').remove();  // Removes the date
            },
            eventClick: function(info) {
                let complianceName = info.event.extendedProps.compliance_name;
                let department = info.event.extendedProps.department;
                let startDate = info.event.extendedProps.start_date;
                let submitDate = info.event.extendedProps.submit_date;
                let deadline = info.event.extendedProps.deadline;
                let daysLeft = info.event.extendedProps.days_left;

                let today = new Date();
                today.setHours(0, 0, 0, 0);  // Set time to midnight for comparison

                // Convert the event deadline to a Date object for comparison
                let eventDeadline = new Date(deadline);

                // Determine if the deadline has passed and adjust days_left accordingly
                let displayDaysLeft;
                if (eventDeadline < today) {
                    // If the deadline is in the past, show the days_left as negative
                    displayDaysLeft = '-' + Math.abs(daysLeft);
                } else {
                    // Otherwise, show it as normal
                    displayDaysLeft = daysLeft;
                }

                $('#eComplianceNameTitle').text(complianceName);
                $('#eComplianceName').text(complianceName);
                $('#eDepartmentName').text(department);
                $('#eStartDate').text(startDate);
                $('#eSubmitOn').text(submitDate);
                $('#eDeadline').text(deadline);
                $('#eDaysLeft').text(displayDaysLeft);


                // Show the modal
                $('#eventComplianceModal').modal('show');

            }
        });

        calendar.render();
    });

</script>

<style>
    .fc .fc-event-time,
    .fc .fc-event-date {
        display: none;
    }
</style>
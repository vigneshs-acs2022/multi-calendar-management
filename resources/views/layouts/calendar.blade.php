@extends('layouts.app')
@push('scripts')
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/core/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid/main.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/@fullcalendar/timegrid/main.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
@endpush
@push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    slotMinTime: '8:00:00',
                    slotMaxTime: '19:00:00',
                    events:  [
                // Your events data should be here, for example:
                {
                    title: 'Event 1',
                    start: '2024-03-13T10:00:00',
                    end: '2024-03-13T12:00:00'
                },
                {
                    title: 'Event 2',
                    start: '2024-03-14T14:00:00',
                    end: '2024-03-14T16:00:00'
                }
                // Add more events as needed
            ]
                });
                calendar.render();
            });
        </script>
    @endpush

@section('content')
<div class = "row">
    <div class = col-4>
        <ul>
            <a class="btn" href="/oauth">Sync Google</a>
            <a class="btn" href="/gcalendar/create">Sync Google</a>
            <li></li>
            <li></li>
        </ul>
    </div>
    <div class = col-8>
        <iframe src="
        https://calendar.google.com/calendar/embed?src=38f60879b9bd054f8065625de34ad1d824f61748e1f1eee0e51d9099e538d5ff%40group.calendar.google.com&ctz=UTC"
            style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
        <iframe src="https://calendar.google.com/calendar/embed?src=ff6bb9e915ef115ea6c885aa4a550cfed42b03d8983c4f3334666256426cb908%40group.calendar.google.com&ctz=UTC"
            style="border: 0" width="800" height="600" frameborder="0" scrolling="no"></iframe>
    </div>
</div>
@endsection('contev')





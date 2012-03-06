(function () {
    $(document).ready(function () {
        var eventCalendarView = Object.create(neuf.eventCalendar).init();
    })
}())
neuf = {};

neuf.eventCalendar = {
    pageObject: $("#event-calendar"),

    init: function () {
        this.renderCalendar();
    },

    renderCalendar: function () {
        var params = {
            targetURL: "http://new.neuf.no/api/events/get_upcoming/",
            successCallback: this.parseCalendarData,
            errorCallback: function (jqXHR, textStatus, errorThrown) {
                console.log("Could not fetch calendar data!");
                console.log(jqXHR);
                console.log(textStatus);
                console.log(errorThrown);
            }
        };

        $.ajax({
            url: params.targetURL,
            type: "GET",
            dataType: "jsonp",
            success: function (data, textStatus, jqXHR) {
                params.successCallback(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                params.errorCallback(jqXHR, textStatus, errorThrown);
            }
        });
    },

    parseCalendarData: function (data) {
        var events =  [];

        for (var i = 0; i < data.events.length; i++) {
            events.push(neuf.eventCalendar.parseEvent(data.events[i]));
        }

        events.sort(function (a, b) {
            return Date.compare(a.startTime, b.startTime);
        });

        neuf.eventCalendar.createDOMElements(events);
    },

    parseEvent: function (rawEvent) {
        var event = {};

        event.title = rawEvent.title;
        event.author = rawEvent.author;
        event.content = rawEvent.content;
        event.startTime = new Date(parseInt(rawEvent.custom_fields._neuf_events_starttime[0]) * 1000);
        event.endTime = new Date(parseInt(rawEvent.custom_fields._neuf_events_endtime[0]) * 1000);
        event.venue = rawEvent.custom_fields._neuf_events_venue[0];

        return event;
    },

    createDOMElements: function (events) {
        var NUMBER_OF_WEEKS_TO_RENDER = 5;
        var monday = Date.today().setWeek(Date.today().getWeek());
        var anchor = neuf.eventCalendar.pageObject;

        for (var i = 0; i < NUMBER_OF_WEEKS_TO_RENDER; i++) {
            //week
            var weekDOMId = neuf.eventCalendar.dateAsWeekDOMId(monday);
            anchor.append('<div id="' + weekDOMId + '"></div>');
            //days
            for (var j = 0; j < 7; j++) {
                var day = monday.clone().addDays(j);
                $("#" + weekDOMId).append('<div id="' + neuf.eventCalendar.dateAsDOMId(day) + '"></div>')
            }
            monday.addWeeks(1);
        }
    },

    dateAsDOMId: function (date) {
        return "date-" + date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate();
    },

    dateAsWeekDOMId: function (date) {
        return "week-" + date.getFullYear() + "-" + date.getWeek();
    }
}
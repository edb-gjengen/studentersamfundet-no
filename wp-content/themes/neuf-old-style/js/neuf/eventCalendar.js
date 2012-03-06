(function () {
    $(document).ready(function () {
        var eventCalendarView = Object.create(neuf.eventCalendar).init();
    })
}())

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
        neuf.eventCalendar.fillCalendar(events);
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
            anchor.append('<div id="' + weekDOMId + '" class="week"></div>');
            //days
            for (var j = 0; j < 7; j++) {
                var day = monday.clone().addDays(j);
                var dayDOMId = neuf.eventCalendar.dayAsDOMId(day);
                $("#" + weekDOMId).append('<div id="' + dayDOMId + '" class="day"></div>')
                $("#" + dayDOMId).append("<h2>" + neuf.util.capitalize(day.toString("dddd d/M")) + "</h2>");

                if (day.getDay() === 0) {
                    $("#" + dayDOMId).addClass("alpha");
                }
                if (day.getDay() === 6) {
                    $("#" + dayDOMId).addClass("omega");
                }
            }
            monday.addWeeks(1);
        }


        
    },

    dayAsDOMId: function (date) {
        return "cal-day-" + date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate();
    },

    dateAsWeekDOMId: function (date) {
        return "cal-week-" + date.getFullYear() + "-" + date.getWeek();
    },

    fillCalendar: function (events) {
        //Style every week
        $(".week").addClass("program-6days");
        
        //FOr every day, style a bit
        $(".day").addClass("grid_2");

        for (var i = 0; i < events.length; i++) {
            //Empty background
            var event = events[i];
            var day = event.startTime;
            var dayDiv = $("#" + neuf.eventCalendar.dayAsDOMId(day));
        }
    }
}
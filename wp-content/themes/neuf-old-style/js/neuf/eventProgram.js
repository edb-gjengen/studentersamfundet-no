/*(function () {
    $(document).ready(function () {
        var eventCalendarView = Object.create(neuf.eventCalendar).init();
    })
}())*/

$(document).ready(function () {
    var programModel = {};

    function getEvents(days) {
        var params = {
            targetURL: "http://new.neuf.no/api/events/get_upcoming/",
            successCallback: parseProgramData,
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
                params.successCallback(data, days);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                params.errorCallback(jqXHR, textStatus, errorThrown);
            }
        });
    };

    function dateToId(date) {
        return date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate()
    };

    function weekToId(date) {
        return date.getFullYear() + "-" + date.getWeek();
    }

    function parseProgramData(data, days) {
        var events = [];

        for (var i = 0; i < data.events.length; i++) {
            events.push(new Event(data.events[i]));
        };

        _.each(events, function (event) {
            var day = days[dateToId(event.startTime)];
            if (day !== undefined) { //If the event date is past our program window we ignore it (or if it is a sunday)
                day.events.push(event);
            }
        });
    };

    var Event = function (rawEvent) {
        this.id = rawEvent.id;
        this.title = rawEvent.title;
        this.author = rawEvent.author;
        this.content = rawEvent.content;
        this.uri = rawEvent.uri;
        this.startTime = new Date(parseInt(rawEvent.custom_fields._neuf_events_starttime[0]) * 1000);
        this.time = this.startTime.toString("HH:mm");
        this.endTime = new Date(parseInt(rawEvent.custom_fields._neuf_events_endtime[0]) * 1000);
        this.venue = rawEvent.custom_fields._neuf_events_venue[0];
        this.thumbnailURI = rawEvent.attachments.length > 0 ? rawEvent.attachments[0].images["two-column-thumb"].url : undefined;
    };

    var Week = function(id, days) {
        this.id = id;
        this.days = days
    };

    var Day = function (id, dateAsHeader, events) {
        this.id = id;
        this.dateAsHeader = dateAsHeader;
        this.events = ko.observableArray(events)
    }

    //Fill view model with empty days that will be filled as events are fetched from the server
    var programModel = {};

    var days = {};
    //Next five weeks
    var nextWeeks = [];

    for (var i = 0; i < 5; i = i + 1) {
        var week = Date.today().add(i).weeks();

        //Make sure the week Date object is set to the monday of the current week
        if (!week.is().monday()) {
            week = week.previous().monday();
        }

        var weekDays = [];
        for (var j = 0; j < 6; j = j + 1) { //Ignore sundays
            var thisDay;
            if (Date.today().is().monday()) {
                thisDay = Date.today().add(i).weeks().add(j).days();
            }
            else {
                thisDay = Date.today().previous().monday().add(i).weeks().add(j).days()
            }
            var dayId = dateToId(thisDay);
            var day = new Day(dayId, neuf.util.capitalize(thisDay.toString("dddd d/M")), []);
            weekDays.push(day);
            days[dayId] = day;
        }

        nextWeeks.push(new Week(weekToId(week), weekDays));
    }

    programModel.weeks = nextWeeks;

    ko.applyBindings(programModel);
    getEvents(days);

    //TODO: wire up the filtering buttons
});
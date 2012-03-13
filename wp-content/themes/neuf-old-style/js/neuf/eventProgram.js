/*(function () {
    $(document).ready(function () {
        var eventCalendarView = Object.create(neuf.eventCalendar).init();
    })
}())*/

//Backbone app
$(document).ready(function () {

    window.Day = Backbone.Model.extend();

    window.DayCollection = Backbone.Collection.extend({
        model: Day
    });

    window.WeekView = Backbone.View.extend({
        tagName: "tr",

        render: function () {
            _.each(this.model.models, function (day) {
                $(this.el).append(new DayView({model:day}).render().el);
            }, this);

            return this;
        }
    });

    window.DayView = Backbone.View.extend({
        tagName: "td",

        initialize: function () {
            this.template = "{{content}}";
        },

        render: function () {
            $(this.el).html(Mustache.render(this.template, this.model.toJSON));
            return this;
        }
    });

    var week = new window.DayCollection;
    week.add([
        {content: "test"},
        {content: "lololo"}
    ]);

    var weekView = new window.WeekView({model: week});
    
    $("#event-program").html(weekView.render().el);
    console.log("test");
});


function nestCollection(model, attributeName, nestedCollection) {
    //setup nested references
    for (var i = 0; i < nestedCollection.length; i++) {
        model.attributes[attributeName][i] = nestedCollection.at(i).attributes;
    }
    //create empty arrays if none

    nestedCollection.bind('add', function (initiative) {
        if (!model.get(attributeName)) {
            model.attributes[attributeName] = [];
        }
        model.get(attributeName).push(initiative.attributes);
    });

    nestedCollection.bind('remove', function (initiative) {
        var updateObj = {};
        updateObj[attributeName] = _.without(model.get(attributeName), initiative.attributes);
        model.set(updateObj);
    });
    return nestedCollection;
}

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

        event.id = rawEvent.id;
        event.title = rawEvent.title;
        event.author = rawEvent.author;
        event.content = rawEvent.content;
        event.uri = rawEvent.uri;
        event.startTime = new Date(parseInt(rawEvent.custom_fields._neuf_events_starttime[0]) * 1000);
        event.endTime = new Date(parseInt(rawEvent.custom_fields._neuf_events_endtime[0]) * 1000);
        event.venue = rawEvent.custom_fields._neuf_events_venue[0];
        event.thumbnailURI = rawEvent.attachments.length > 0 ? rawEvent.attachments[0].images["two-column-thumb"].uri : undefined;

        return event;
    },

    createDOMElements: function (events) {
        var NUMBER_OF_WEEKS_TO_RENDER = 5;
        var monday = Date.today().setWeek(Date.today().getWeek());
        var anchor = neuf.eventCalendar.pageObject;

        for (var i = 0; i < NUMBER_OF_WEEKS_TO_RENDER; i++) {
            //week
            var weekDOMId = neuf.eventCalendar.dateAsWeekDOMId(monday);

            //weekday header row
            anchor.append('<tr id="heading-' + weekDOMId + '"></tr>');
            for (var j = 0; j < 7; j++) {
                var day = monday.clone().addDays(j);
                var dayDOMId = neuf.eventCalendar.dayAsDOMId(day);
                $("#heading-" + weekDOMId).append("<th><h2>" + neuf.util.capitalize(day.toString("dddd d/M")) + "</h2></th>");
            }

            //Content cells
            anchor.append('<tr id="' + weekDOMId + '"></tr>');
            for (var j = 0; j < 7; j++) {
                var day = monday.clone().addDays(j);
                var dayDOMId = neuf.eventCalendar.dayAsDOMId(day);
                //$("#" + weekDOMId).append('<td id="' + dayDOMId + '" class="day"><img src="/neuf/wp-content/themes/neuf-old-style/img/pig.png"></td>')
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
        for (var i = 0; i < events.length; i++) {
            var event = events[i];
            var day = event.startTime;
            var dayAsDOMId = neuf.eventCalendar.dayAsDOMId(day);
            var dayCell = $("#" + dayAsDOMId);
            var dayDiv = dayCell.append('<div id="event-"' + event.id + '"></div>')

            dayDiv.append('<img src="event.thumbnailURI">'); //TODO: what if undefined?
            var content = event.startTime.toString("H.mm") + " ";
            content = content + '<a title="Permanent lenke til ' + event.title + '" href="' + event.uri + '">' + event.title + '</a>';
            dayDiv.append("<p>" + content + "</p>");
        }
    }
}
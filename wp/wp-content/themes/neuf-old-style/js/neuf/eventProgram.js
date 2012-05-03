$(document).ready(function () {
    function getEvents(programModel) {
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
                params.successCallback(data, programModel);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                params.errorCallback(jqXHR, textStatus, errorThrown);
            }
        });
    }

    function dateToId(date) {
        return date.getFullYear() + "-" + date.getMonth() + "-" + date.getDate()
    }

    function weekToId(date) {
        return date.getFullYear() + "-" + date.getWeek();
    }

    function parseProgramData(data, programModel) {
        var events = programModel.events();
        for (var i = 0; i < data.events.length; i++) {
            events.push(new Event(data.events[i]));
        }

        _.each(events, function (event) {
            var day = programModel.days[dateToId(event.startTime)];
            if (day !== undefined) { //If the event date is past our program window we ignore it (or if it is a sunday)
                day.events.push(event);
            }
        });

        var eventTypes = [];
        _.each(events, function(event) {
            eventTypes.push(event.eventType);
        });

        _.each(_.uniq(eventTypes), function(eventTypeName) {
            var eventType = new EventType(eventTypeName);

            eventType.checked.subscribe(function (newValue) {
                if (newValue) {
                    programModel.checkedEvents.push(eventTypeName);
                }
                else {
                    programModel.checkedEvents.remove(eventTypeName);
                }

                programModel.eventTypeIsToggled(newValue, eventType);
            });
            programModel.eventTypes.push(eventType);
        });

        $('#load-spinner').hide();
        $('#content').fadeIn();
    }

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
        this.eventType = rawEvent.event;
    }

    var Week = function(id, days) {
        this.id = id;
        this.days = days;
    }

    var Day = function (id, dateAsHeader, events, programModel) {
        this.id = id;
        this.dateAsHeader = dateAsHeader;
        this.events = ko.observableArray(events);
        this.programModel = programModel;

        var that = this;
        this.filteredEvents = ko.computed(function () {
            var checkedEvents = that.programModel.checkedEvents();

            if (checkedEvents.length == 0) {
                return that.events();
            }

            return ko.utils.arrayFilter(that.events(), function (event) {
                return _.contains(checkedEvents, event.eventType);
            });
        }, this);
    }

    var EventType = function(name) {
        this.name = name;
        this.checked = ko.observable(false);
        this.icon = imagePath(name);
        this.id = "event_type_" + name;
    }

    function imagePath(eventTypeName) {
        var imageDir = "../wp/wp-content/themes/neuf-old-style/img/";
        var imageMap = {
            "default": imageDir+'tilesvisning.png',
            "debatt": imageDir+'ikon_debatt-50x50.png',
            "fest": imageDir+'ikon_fest-50x50.png',
            "film": imageDir+'ikon_film-50x50.png',
            "foredrag": imageDir+'ikon_foredrag-50x50.png',
            "konsert": imageDir+'ikon_konsert-50x50.png',
            "quiz": imageDir+'ikon_quiz-50x50.png',
            "teater": imageDir+'ikon_teater-50x50.png'
        }

        if (_.has(imageMap, eventTypeName.toLowerCase())) {
            return imageMap[eventTypeName.toLowerCase()];
        }

        return imageMap["default"];
    }

    //Fill view model with empty days that will be filled as events are fetched from the server
    var programModel = {};

    var days = {};
    //Next five weeks
    var nextWeeks = ko.observableArray();

    var eventTypes = ko.observableArray();

    programModel.events = ko.observableArray();
    programModel.checkedEvents = ko.observableArray();

    programModel.eventTypeIsToggled = function (newValue, eventType) {
        console.log(newValue);
        //This is ugly as shit
        /*
        var imgId = eventType.id  + "_img"
        var imgSelector = "#" + imgId;
        if ($(".category-chooser-item-img.checked").length === programModel.eventTypes().length) {
            console.log($(".category-chooser-item-img.checked"));
            console.log(programModel.eventTypes());
            $(".category-chooser-item-img").each(function () {
                if ($(this).attr("id") != imgId) {
                    $(this).removeClass("checked");
                    $(this).addClass("unchecked")
                }
            });
        } else {
            if (newValue) {
                console.log("yeahhh");
                console.log(imgSelector);
                console.log($(imgSelector));
                $(imgSelector).removeClass("unchecked");
                $(imgSelector).addClass("checked");
            } else {
                $(imgSelector).removeClass("checked");
                $(imgSelector).addClass("unchecked");
            }
        }
        */

    }

    for (var i = 0; i < 5; i = i + 1) {
        var week = Date.today().add(i).weeks();

        //Make sure the week Date object is set to the monday of the current week
        if (!week.is().monday()) {
            week = week.previous().monday();
        }

        var weekDays = ko.observableArray();
        for (var j = 0; j < 6; j = j + 1) { //Ignore sundays
            var thisDay;
            if (Date.today().is().monday()) {
                thisDay = Date.today().add(i).weeks().add(j).days();
            }
            else {
                thisDay = Date.today().previous().monday().add(i).weeks().add(j).days()
            }
            var dayId = dateToId(thisDay);
            var day = new Day(dayId, neuf.util.capitalize(thisDay.toString("dddd d/M")), [], programModel);
            weekDays.push(day);
            days[dayId] = day;
        }

        nextWeeks.push(new Week(weekToId(week), weekDays));
    }

    programModel.weeks = nextWeeks;
    programModel.eventTypes = eventTypes;
    programModel.days = days;

    ko.applyBindings(programModel);
    getEvents(programModel);
});

jQuery(document).ready(function($) {
  /* Only trigger for events */
  if ($("#post_type").val() !== "event") {
    return;
  }

  /* Datetimepicker */
  $(".datepicker").datetimepicker({
    currentText: "Nå",
    closeText: "Ok",
    hourText: "Time",
    dateFormat: "yy-mm-dd",
    timeFormat: "HH:mm",
    minuteText: "Minutt",
    timeText: "Tid",
    firstDay: 1,
    monthNames: [
      "Januar",
      "Februar",
      "Mars",
      "April",
      "Mai",
      "Juni",
      "Juli",
      "August",
      "September",
      "Oktober",
      "November",
      "Desember"
    ],
    dayNames: [
      "søndag",
      "mandag",
      "tirsdag",
      "onsdag",
      "torsdag",
      "fredag",
      "lørdag"
    ],
    dayNamesShort: ["søn", "man", "tir", "ons", "tor", "fre", "lør"],
    dayNamesMin: ["sø", "ma", "ti", "on", "to", "fr", "lø"]
  });

  $("select[name='_neuf_events_venue_id']").change(function(event) {
    if (event.target.value === "custom") {
      $("div#_neuf_events_venue_container").show();
    } else {
      $("div#_neuf_events_venue_container").hide();
    }
    //
  });

  /* Validation rules */

  /* Prior to WordPress 5 and Gutenberg we could validate the form
     and simply e.preventDefault. Now we have this monster. */

  var startTime = $(
    "#neuf_events_details input[name='_neuf_events_starttime']"
  ).first();
  var venueCustom = $(
    "#neuf_events_details input[name='_neuf_events_venue']"
  ).first();
  var venueId = $(
    "#neuf_events_details select[name='_neuf_events_venue_id']"
  ).first();

  $.each([startTime, venueCustom, venueId], function(i, obj) {
    obj.change(validateEvent);
  });

  function validDateTime(value) {
    try {
      $.datepicker.parseDateTime("yy-mm-dd", "HH:mm", value);
      return true;
    } catch (e) {
      return false;
    }
  }

  function complain(errors) {
    wp.data.dispatch("core/editor").lockPostSaving("neuf-venues-field-error");
    wp.data
      .dispatch("core/notices")
      .createNotice(
        "error",
        "Arrangementet mangler " + errors.join(" og ") + ".",
        {
          id: "neuf-venues-field-error",
          isDismissible: false
        }
      );
  }

  function stopComplaining() {
    wp.data.dispatch("core/editor").unlockPostSaving("neuf-venues-field-error");
    wp.data.dispatch("core/notices").removeNotice("neuf-venues-field-error");
  }

  function validateEvent() {
    var errors = [];

    if (!validDateTime(startTime.val())) {
      errors.push("starttidspunkt");
    }
    if ((venueId.val() === "custom" && !venueCustom.val()) || !venueId.val()) {
      errors.push("lokale");
    }

    if (errors.length) {
      complain(errors);
    } else {
      stopComplaining();
    }
  }

  validateEvent();
});

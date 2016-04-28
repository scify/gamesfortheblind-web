EasyBlog.module("composer/datetime", function($) {

var module = this;

EasyBlog.require()
.library(
    "moment",
    "datetimepicker"
)
.done(function(){

EasyBlog.Controller("Post.Datetime", {
    defaultOptions: {
        format: "Do MMM, YYYY HH:mm",
        originalValue: "",

        "{preview}": "[data-preview]",
        "{calendar}": "[data-calendar]",
        "{cancel}": "[data-cancel]",
        "{datetime}": "[data-datetime]"
    }
}, function(self, opts, base) {

    return {
        init: function() {
            self.calendar()._datetimepicker({
                component: "eb",
                format: opts.format
            });

            self.datetimepicker = self.calendar().data("DateTimePicker");

            // Get the original value from input
            opts.originalValue = self.datetime().val();

            if (!$.isEmpty(opts.originalValue)) {
                self.datetimepicker.setDate($.moment(opts.originalValue));
            }
        },

        "{calendar} dp.change": function(el, ev) {
            self.preview().text(ev.date.format(opts.format));

            // Set the datetime as SQL format
            self.datetime().val(ev.date.format("YYYY-MM-DD HH:mm:ss"));

            self.toggleCancelButton();
        },

        "{cancel} click": function() {
            var empty = $.isEmpty(opts.originalValue);

            if (empty || opts.originalValue == "0000-00-00 00:00:00") {
                self.preview().text(opts.emptyText);
                self.datetime().val("0000-00-00 00:00:00");
            } else {
                self.datetimepicker.setDate($.moment(opts.originalValue));
            }

            self.toggleCancelButton();
        },

        toggleCancelButton: function() {
            self.cancel()[self.datetime().val() == opts.originalValue ? "hide" : "show"]();
        }
    }
});

module.resolve();

});

});
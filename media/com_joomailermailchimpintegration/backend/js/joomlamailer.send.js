/**
 * Copyright (C) 2015  freakedout (www.freakedout.de)
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

!function($){
    $(document).ready(function(){

        joomlamailerJS.send = {
            init: function() {
                joomlamailerJS.send.segmentsTested = false;
                joomlamailerJS.send.creditCount = 0;
                joomlamailerJS.send.currentCredits = 0;

                $('#pickDeliveryTime').clockpick({
                    starthour : 0,
                    endhour : 23,
                    showminutes : true,
                    minutedivisions: 12,
                    military: true,
                    //event: 'mouseover',
                    layout: 'horizontal',
                    valuefield: 'deliveryTime'
                });
            },
            loadCampaign: function(cid) {
                joomlamailerJS.functions.preloader();
                window.location = 'index.php?option=com_joomailermailchimpintegration&view=send&campaign=' + cid
            },
            addCondition: function() {
                var x;
                var next = parseInt($('#conditionCount').val()) + 1;
                if (next > 10) {
                    return;
                }
                $.ajax({
                    url: joomlamailerJS.misc.adminUrl + 'index.php?option=com_joomailermailchimpintegration&controller=send&format=raw&task=addCondition',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        listId: $('#listId').val(),
                        conditionCount: $('#conditionCount').val()
                    },
                    beforeSend: function() {
                        for (x = 2; x < 11; x++) {
                            if ($('#segment' + x).html() == '') {
                                $('#segment' + x).css({
                                    'background': "url('" + joomlamailerJS.misc.baseUrl + "media/com_joomailermailchimpintegration/backend/images/loader_16.gif') no-repeat 10px 10px",
                                    'display': 'block',
                                    'height': '32px'
                                });
                                break;
                            }
                        }
                        //$('#segment' + next).html(joomlamailerJS.helpers.ajaxLoader).css('display', 'block');
                    },
                    success: function(response) {
                        $('#conditionCount').val(next);
                        if (next == 10) {
                            $('#addCondition').css('display', 'none');
                        }
                        $('#segment' + x).css({'background': '', 'height': ''}).html(response.html);
                        if (response.js) {
                            eval(response.js);
                            eval($('.calendar').attr('src', '../media/com_joomailermailchimpintegration/backend/images/calendar.png'));
                        }
                    }
                });
            },
            removeCondition: function(nr) {
                $('#segment' + nr).html('').css('display', 'none');

                var conditionsCount = parseInt($('#conditionCount').val()) - 1;
                $('#conditionCount').val(conditionsCount);
                if (conditionsCount < 10 ) {
                    $('#addCondition').css('display', '');
                }
            },
            addInterests: function(listId) {
                var staticOptions = 10;
                if (listId != '') {
                    $.ajax({
                        url: joomlamailerJS.misc.adminUrl + 'index.php?option=com_joomailermailchimpintegration&controller=send&format=raw&task=addInterests',
                        type: 'post',
                        dataType: 'json',
                        data: {listId: listId},
                        success: function(response) {
                            if (response.length > 0) {
                                for (x = 1; x <= 10; x++) {
                                    var element = $('#segmenttype' + x);
                                    if (element.html() != '') {
                                        var options = element.find('option');
                                        if (options.length > staticOptions) {
                                            for (i = options.length; i > staticOptions; i--) {
                                                options[i-1].remove();
                                            }
                                        }
                                        for (var i = 0; i < response.length; i++) {
                                            element.append($j('<option />').val(response[i].id).html(response[i].name));
                                        }
                                    }
                                }
                            }
                        }
                    });
                } else {
                    for (x = 1; x <= 10; x++) {
                        var element = $('#segmenttype' + x);
                        if (element.html() != '') {
                            var options = element.find('option');
                            if (options.length > staticOptions) {
                                for (i = options.length; i > staticOptions; i--) {
                                    options[i-1].remove();
                                }
                            }
                        }
                    }
                }
            },
            getSegmentFields: function(selector, num) {
                $.ajax({
                    url:  joomlamailerJS.misc.adminUrl + 'index.php?option=com_joomailermailchimpintegration&controller=send&format=raw&task=getSegmentFields',
                    type: 'post',
                    dataType: 'json',
                    data: {
                        listId: $('#listId').val(),
                        type: $('#segmenttype' + num).val(),
                        condition: $('#segmentTypeCondition_' + num).val(),
                        num: num,
                        conditionDetail: ($('#segmentTypeConditionDetail_' + num).length == 1
                            ? $('#segmentTypeConditionDetail_' + num).val() : '')
                    },
                    success: function(response) {
                        $(selector).html(response.html);
                        if (response.js) {
                            eval(response.js);
                            eval($j('.calendar').attr('src', '../media/com_joomailermailchimpintegration/backend/images/calendar.png'));
                        }
                    }
                });
            },
            testSegments: function() {
                if ($('#listId').val() == '') {
                    joomlamailerJS.sync.noListSelected();
                    return;
                }

                $('#ajax-spin').removeClass('hidden');
                joomlamailerJS.send.segmentsTested = true;

                var data = new Object();
                data['listId'] = $('#listId').val();
                data['match'] = $('#match').val();
                data['condCount'] = parseInt($('#conditionCount').val());

                data['conditionDetailValue'] = '';
                data['type'] = '';
                data['condition'] = '';
                data['conditionDetail'] = '';
                data['conditionDetailValue'] = '';

                for (i = 1; i <= data['condCount']; i++) {
                    var type = $('#segmenttype' + i).val();
                    data['type'] += type + '|*|';
                    data['condition'] += $('#segmentTypeCondition_' + i).val() + '|*|';
                    if (type == 'date') {
                        data['conditionDetail'] += $('#segmentTypeConditionDetail_' + i).val() + '|*|';
                        data['conditionDetailValue'] += $('#segmentTypeConditionDetailValue_' + i).val() + '|*|';
                    } else if (!isNaN(type)) {
                        data['conditionDetailValue'] += $('#segmentTypeConditionDetailValue_' + i).val().join(',');
                        data['conditionDetailValue'] = data['conditionDetailValue'] + '|*|';
                        data['conditionDetail'] += '#|*|';
                    } else {
                        data['conditionDetailValue'] += $('#segmentTypeConditionDetailValue_'+i).val() + '|*|';
                        data['conditionDetail'] += '#|*|';
                    }
                }

                $j.ajax({
                    url: joomlamailerJS.misc.adminUrl + 'index.php?option=com_joomailermailchimpintegration&controller=send&format=raw&task=testSegments',
                    type: 'post',
                    dataType: 'json',
                    data: data,
                    success: function(response) {
                        $('#ajax-spin').addClass('hidden');
                        if (response.error) {
                            $('#testResponse').html(response.msg);
                            $('#testResponse').css('display', 'block');
                        } else {
                            $('#testResponse').html(response.msg);
                            $('#credits').html(response.creditCount);
                            $('#testResponse').css('display', 'block');
                            joomlamailerJS.send.creditCount = response.creditCount;
                            joomlamailerJS.send.currentCredits = response.creditCount;
                        }
                    }
                });
            },
            validateEmail: function(email) {
                var pattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                if (email != '' && !pattern.test(email)) {
                    alert(joomlamailerJS.strings.errorInvalidEmails);
                    return false;
                }

                return true;
            },
            setCredits: function(val) {
                joomlamailerJS.send.currentCredits = list[val];
                $('#total').val(list[val]);
                if ($('#test').is(':checked') === false) {
                    $('#credits').html(list[val]);
                    joomlamailerJS.send.creditCount = list[val];
                } else {
                    joomlamailerJS.send.credits();
                }
            },
            credits: function() {
                var counter = 0;
                $('.testEmailField').each(function() {
                    if ($(this).val() != '') {
                        counter++;
                    }
                });
                $('#credits').html(counter);
            },
            getMerges: function() {
                if ($('#campaignType').is(':checked') === true && $('#listId').val() != '') {
                    $('#test').attr('checked', false);
                    $('#timewarp').attr('checked', false);
                    $('#schedule').attr('checked', false);
                    $('#useSegments').attr('checked', false);
                    $('#useTwitter').attr('checked', false);

                    $('#testmails').slideUp();

                    $('#testContent select, #testContent input[type=text]').attr('disabled', 'disabled');
                    $('#scheduleContent select, #scheduleContent input[type=text]').attr('disabled', 'disabled');
                    $('#segmentsContent select, #segmentsContent input[type=text]').attr('disabled', 'disabled');
                    $('#socialContent select, #socialContent input[type=text]').attr('disabled', 'disabled');

                    $.ajax({
                        url: joomlamailerJS.misc.adminUrl + 'index.php?option=com_joomailermailchimpintegration&controller=send&task=getMerges&format=raw',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            listId: $('#listId').val()
                        },
                        beforeSend: function() {
                            $('#merges').html('<img src="' + joomlamailerJS.misc.baseUrl + 'media/com_joomailermailchimpintegration/backend/images/loader_16.gif" />');
                            $('#auto-div').slideDown();
                        },
                        success: function(response) {
                            $('#merges').html(response.html);
                            if (response.js) {
                                eval(response.js);
                            }
                            joomlamailerJS.send.eventCheck();
                        }
                    });
                } else {
                    $('#auto-div').slideUp();
                    $('#merges').html('');
                    $('#testContent select, #testContent input[type=text]').removeAttr('disabled');
                    $('#scheduleContent select, #scheduleContent input[type=text]').removeAttr('disabled');
                    $('#segmentsContent select, #segmentsContent input[type=text]').removeAttr('disabled');
                    $('#socialContent select, #socialContent input[type=text]').removeAttr('disabled');
                }
            },
            eventCheck: function() {
                if ($('#new-auto-event').val() == 'signup') {
                    $('#mergefield').css('display', 'none');
                    if ($('#new-auto-offset-time').css('display') == 'none') {
                        joomlamailerJS.send.eventType(1);
                    }
                } else {
                    $('#mergefield').css('display', '');
                }
            },
            eventType: function(type) {
                if (type == 1) {
                    stylea = 'inline';
                    styleb = 'none';
                } else {
                    stylea = 'none';
                    styleb = 'inline';
                    $('#new-auto-event').val('date');
                }
                $('#timelbl1').css('display', stylea);
                $('#new-auto-offset-time').css('display', stylea);
                $('#new-auto-offset-units').css('display', stylea);
                $('#new-auto-offset-dir').css('display', stylea);
                $('#new-auto-event-switch-1').css('display', (type == 1) ? '' : 'none');
                $('#new-auto-event').css = ('display', styleb);
                $('#timelbl2').css('display', styleb);
                $('#new-auto-event-switch-2').css('display', (type == 1) ? 'none' : '');
                joomlamailerJS.send.eventCheck();
            },
            rating: function(elem, store) {
                var num = elem.parent().data('num');
                var value = elem.val();

                if (store) {
                    $('#segmentTypeConditionDetailValue_' + num).val(value);
                }
                for (var i = 1; i < 6; i++) {
                    if (i <= value) {
                        $('#segmentTypeConditionDiv_' + num + ' .rating_' + i).addClass('active');
                    } else {
                        $('#segmentTypeConditionDiv_' + num + ' .rating_' + i).removeClass('active');
                    }
                }
            },
            restoreRating: function(num) {
                var rating = $('#segmentTypeConditionDetailValue_' + num).val();
                for (var i = 1; i < 6; i++) {
                    if (i <= rating) {
                        $('#segmentTypeConditionDiv_' + num + ' .rating_' + i).addClass('active');
                    } else {
                        $('#segmentTypeConditionDiv_' + num + ' .rating_' + i).removeClass('active');
                    }
                }
            }
        }

        $(document).on('mouseleave', '.memberRating', function() {
            joomlamailerJS.send.restoreRating($(this).data('num'))
        });

        $(document).on('hover', '.memberRating li', function() {
            joomlamailerJS.send.rating($(this), false);
        });
        $(document).on('click', '.memberRating li', function() {
            joomlamailerJS.send.rating($(this), true);
        })

        $('#listId').change(function() {
            joomlamailerJS.send.addInterests($(this).val());
            joomlamailerJS.send.setCredits($(this).val());
            joomlamailerJS.send.getMerges();
        });

        $('#test').change(function() {
            joomlamailerJS.send.setCredits($('#listId').val());
            if ($('#test').is(':checked') === true) {
                $('#testmails').slideDown();
                $('#sendTestButton').removeClass('hidden');
                $('.sendNowButton').addClass('hidden');

            } else {
                $('#testmails').slideUp();
                $('#sendTestButton').addClass('hidden');
                $('.sendNowButton').removeClass('hidden');
            }
        });

        $('.testEmailField').change(function() {
            joomlamailerJS.send.validateEmail($(this).val());
        })
        .blur(function() {
            joomlamailerJS.send.credits();
        });

        $('#sendTestButton').click(function(e) {
            e.preventDefault();
            Joomla.submitbutton('send');
        });

        $('#timewarp').click(function() {
            if ($(this).is(':checked') === true) {
                if (joomlamailerJS.misc.customerPlan == 'free') {
                    alert(joomlamailerJS.strings.errorTimewarpOnlyForPayed);
                    $('#timewarp').attr('checked', false);
                } else {
                    $('#schedule').attr('checked', true);
                }
            }
        });

        $('#deliveryDate, #deliveryTime').change(function() {
            if ($(this).val() != '') {
                $('#schedule').attr('checked', true);
            }
        });

        $('#segmenttype1').change(function() {
            joomlamailerJS.send.getSegmentFields('#segmentTypeConditionDiv_1', 1);
        });
        $('#segmentTypeConditionDetail_1').change(function() {
            joomlamailerJS.send.getSegmentFields('#segmentTypeConditionDiv_1', 1);
        });

        $('#addCondition').click(function() {
            joomlamailerJS.send.addCondition();
        });

        $('#testSegments').click(function(e) {
            e.preventDefault();
            joomlamailerJS.send.testSegments();
        });

        $('#campaignType').change(function() {
            joomlamailerJS.send.getMerges();
        });

        $('#new-auto-event').change(function() {
            joomlamailerJS.send.eventCheck();
        });

        joomlamailerJS.send.init();

        Joomla.submitbutton = function(pressbutton) {
            if (pressbutton == 'syncHotness') {
                if ($('#listId').val() == '') {
                    joomlamailerJS.sync.noListSelected();
                    return;
                } else if (confirm(joomlamailerJS.strings.confirmSyncHotnessNow)) {
                    joomlamailerJS.sync.AjaxAddHotness(0);
                    return;
                }
            } else if (pressbutton == 'remove') {
                if (confirm(joomlamailerJS.strings.confirmDraftDelete)) {
                    Joomla.submitform(pressbutton);
                    return true;
                } else {
                    return false;
                }
            } else {
                if ($('#listId').val() == '') {
                    joomlamailerJS.sync.noListSelected();
                    return;
                } else if ($('#test').is(':checked') == true) {
                    var testEmails = [];
                    var invalidEmails = false;
                    $('.testEmailField').each(function() {
                        if ($(this).val()) {
                            if (joomlamailerJS.send.validateEmail($(this).val())) {
                                testEmails.push($(this).val());
                            } else {
                                invalidEmails = true;
                                return;
                            }
                        }
                    });
                    testEmails = testEmails.join();
                    if (invalidEmails == true) {
                        return;
                    } else if (testEmails == '') {
                        alert(joomlamailerJS.strings.errorEnterTestRecipients);
                        return;
                    } else {
                        joomlamailerJS.functions.preloader();
                        Joomla.submitform(pressbutton);
                    }

                    return;
                }

                if ($('#schedule').is(':checked') == true) {
                    var patternDate = /\d{4}-\d{2}-\d{2}/;
                    var patternTime = /\d{2}:\d{2}/;

                    if (!$('#deliveryDate').val().test(patternDate) || !$('#deliveryTime').val().test(patternTime)) {
                        alert(joomlamailerJS.strings.errorInvalidDate);
                        return;
                    }

                    var today = new Date();
                    var tomorrow = new Date();
                    tomorrow.setDate(today.getDate() + 1);
                    var deliveryDate = $('#deliveryDate').val();
                    deliveryDate = deliveryDate.replace(/-/g, '/');
                    var selectedDate = new Date(deliveryDate + ' ' + $('#deliveryTime').val() + ':00');

                    if (today > selectedDate) {
                        alert(joomlamailerJS.strings.errorInvalidDeliveryDateInThePast);
                        return;
                    } else if ($('#timewarp').is(':checked') == true) {
                        if (joomlamailerJS.misc.customerPlan == 'free') {
                            alert(joomlamailerJS.strings.errorTimewarpOnlyForPayed);
                            return;
                        } else if (tomorrow > selectedDate) {
                            alert(joomlamailerJS.strings.errorTimewarpNotScheduled24h);
                            return;
                        }
                    }
                } else if ($('#timewarp').is(':checked') == true) {
                    alert(joomlamailerJS.strings.errorTimewarpNotScheduled24h);
                    return;
                }

                if ($('#useSegments').is(':checked') == true && joomlamailerJS.send.segmentsTested == false) {
                    alert(joomlamailerJS.strings.errorPleaseTestSegments);
                    return;
                }

                if ($('#campaignType').is(':checked') == true) {
                    if ($('#useSegments').is(':checked') == true ||
                        $('#schedule').is(':checked') == true ||
                        $('#timewarp').is(':checked') == true ||
                        $('#useTwitter').is(':checked') == true) {

                        alert(joomlamailerJS.strings.errorAutoresponderSetup);
                        return;
                    } else if (isNaN(parseInt($('#new-auto-offset-time').val())) || parseInt($('#new-auto-offset-time').val()) <= 0) {
                       alert(joomlamailerJS.strings.errorAutoresponderDays);
                       return
                    } else {
                        joomlamailerJS.functions.preloader();
                        Joomla.submitform(pressbutton);
                        return;
                    }
                } else {
                    if (joomlamailerJS.send.creditCount == 0) {
                        alert(joomlamailerJS.strings.errorNoRecipients);
                        return;
                    } else if (confirm(joomlamailerJS.strings.confirmSend_1 + ' ' + joomlamailerJS.send.creditCount + ' ' + joomlamailerJS.strings.confirmSend_2)){
                        joomlamailerJS.functions.preloader();
                        Joomla.submitform(pressbutton);
                        return;
                    }
                }
            }

            return;
        }
    });
}(jQuery);


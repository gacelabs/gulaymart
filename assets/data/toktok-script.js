var th_count = $("#table-grid th").length;
var recipients = [];
var priceanddirections = [];
priceanddirections.promo_code = "";
var postDelivery = [];
var map;
var marker;
var marker1;
var sender_mark;
var recipient_mark;
var isExpress = false;
var isCod = false;
var promo_code_valid = false;
var promo_code_invalid_message = "";
var promo_checking = 0;
var some_error = "";
$(document).ready(function () {
    var price = 0;
    var clickChecker = false,
        firstload = true;
    var clickLocation = "",
        marker = "";
    var map, maptemp, roadTrip;
    var place = [],
        place2 = [];
    var clicker, clickLat, clickLng;
    $("#f_sender_mobile").mask("00000000000");
    $("#f_recepient_mobile").mask("00000000000");
    fillDataTable = function () {
        var data = { date_from: $("#card-filter #date_start").val(), date_to: $("#card-filter #date_end").val(), driver_id: $("#f_driver_id_op").val(), order_status: $("#f_order_status").val(), searchstring: $("#searchtext").val() };
        dataTable = $("#table-grid").DataTable({
            destroy: true,
            serverSide: true,
            order: [[0, "DESC"]],
            responsive: true,
            ajax: {
                url: base_url + "app/deliveries/deliveries_operator_list_table",
                type: "get",
                data: data,
                beforeSend: function (data) {
                    showCover("loading list...");
                },
                complete: function (data) {
                    hideCover();
                    var response = $.parseJSON(data.responseText);
                    total_prices = response.total_prices;
                    overall_prices = response.overall_prices;
                    if (response.data.length > 0) {
                        $(".total_prices").removeAttr("hidden");
                    } else {
                        $(".total_prices").attr("hidden", "hidden");
                    }
                    $("b.summary").remove();
                    $("#overall_price_placed_order").html(overall_prices.overall_price_placed_order);
                    $("#overall_price_scheduled_for_delivery").html(overall_prices.overall_price_scheduled_for_delivery);
                    $("#overall_price_on_the_way_sender").html(overall_prices.overall_price_on_the_way_sender);
                    $("#overall_price_item_picked_up").html(overall_prices.overall_price_item_picked_up);
                    $("#overall_price_on_the_way_recipient").html(overall_prices.overall_price_on_the_way_recipient);
                    $("#overall_price_item_delivered").html(overall_prices.overall_price_item_delivered);
                    $("#total_price_placed_order").append('<b class="summary">' + total_prices.total_price_placed_order + "</b>");
                    $("#total_price_scheduled_for_delivery").append('<b class="summary">' + total_prices.total_price_scheduled_for_delivery + "</b>");
                    $("#total_price_on_the_way_sender").append('<b class="summary">' + total_prices.total_price_on_the_way_sender + "</b>");
                    $("#total_price_item_picked_up").append('<b class="summary">' + total_prices.total_price_item_picked_up + "</b>");
                    $("#total_price_on_the_way_recipient").append('<b class="summary">' + total_prices.total_price_on_the_way_recipient + "</b>");
                    $("#total_price_item_delivered").append('<b class="summary">' + total_prices.total_price_item_delivered + "</b>");
                    if (firstload) {
                        getDrivers();
                        firstload = false;
                    }
                },
                error: function () {
                    hideCover();
                    $(".table-grid-error").html("");
                    $("#table-grid").append('<tbody class="table-grid-error text-center"><tr><th colspan="9">No data found in the server.</th></tr></tbody>');
                    $("#table-grid_processing").css("display", "none");
                    $("b.summary").remove();
                    $("#total_price_placed_order").append('<b class="summary">0.00</b>');
                    $("#total_price_scheduled_for_delivery").append('<b class="summary">0.00</b>');
                    $("#total_price_on_the_way_sender").append('<b class="summary">0.00</b>');
                    $("#total_price_item_picked_up").append('<b class="summary">0.00</b>');
                    $("#total_price_on_the_way_recipient").append('<b class="summary">0.00</b>');
                    $("#total_price_item_delivered").append('<b class="summary">0.00</b>');
                    $("#total_price_cancelled_order").append('<b class="summary">0.00</b>');
                    $("#total_price_deleted_order").append('<b class="summary">0.00</b>');
                    $("#total_price_expired_order").append('<b class="summary">0.00</b>');
                },
            },
            columnDefs: [
                { className: "text-right", targets: [4] },
                { orderable: false, targets: [th_count - 1, th_count - 2, th_count - 3, th_count - 4, th_count - 5] },
            ],
        });
    };
    loadDeliveryStatus();
    var parse = parse_query_string($("#table-grid"));
    if (parse) {
        fillDataTable();
    } else {
        fillup_daterange_subtract(1, "weeks");
        fillDataTable();
    }
    $.getScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyDhlw6pbriuwr_Mb6KYkVlBar7KD1KTrOs&libraries=places", () => {
        var input = $("#pac-input")[0],
            input2 = $("#pac-input2")[0];
        var geocoder = new google.maps.Geocoder();
        map = new google.maps.Map(document.getElementById("map"), { center: { lat: 14.599512, lng: 120.984222 }, zoom: 13 });
        roadTrip = new google.maps.Polyline();
        maptemp = map;
        var geocoder = new google.maps.Geocoder();
        var card = document.getElementById("pac-card");
        place = [];
        place2 = [];
        var lat1 = 0,
            lng1 = 0,
            lat2 = 0,
            lng2 = 0;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(card);
        var autocomplete = new google.maps.places.Autocomplete(input);
        var recepientLocation = new google.maps.places.Autocomplete(input2);
        autocomplete.bindTo("bounds", map);
        recepientLocation.bindTo("bounds", map);
        autocomplete.setComponentRestrictions({ country: ["ph"] });
        recepientLocation.setComponentRestrictions({ country: ["ph"] });
        recepientLocation.setFields(["address_components", "formatted_address", "geometry", "icon", "name"]);
        autocomplete.setFields(["address_components", "formatted_address", "geometry", "icon", "name"]);
        marker = new google.maps.Marker({ map: map, anchorPoint: new google.maps.Point(0, -29), icon: base_url + "webassets/img/tracking/pickup.svg" });
        marker1 = new google.maps.Marker({ map: map, anchorPoint: new google.maps.Point(0, -29), icon: base_url + "webassets/img/tracking/dropoff.svg" });
        sender_mark = new google.maps.Marker({ map: map, anchorPoint: new google.maps.Point(0, -29), icon: base_url + "webassets/img/tracking/pickup.svg" });
        recipient_mark = new google.maps.Marker({ map: map, anchorPoint: new google.maps.Point(0, -29), icon: base_url + "webassets/img/tracking/dropoff.svg" });
        $("#adddeliveryoperatormodal").on("shown.bs.modal", function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(showPosition);
            }
        });
        function showPosition(position) {
            const latlng = { lat: parseFloat(position.coords.latitude), lng: parseFloat(position.coords.longitude) };
            geocoder.geocode({ location: latlng }, function (results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $.each(results[0].address_components, function (key, val) {
                            if (val.types[0] == "locality") {
                                marker.setPosition(results[0].geometry.location);
                                clickChecker = false;
                                place = results[0];
                                $("#pac-input").val(place.formatted_address);
                                $("#f_sender_address").val(place.formatted_address);
                                google.maps.event.trigger(autocomplete, "place_changed");
                                clickLocation = "";
                            }
                        });
                    }
                }
            });
        }
        autocomplete.addListener("place_changed", function () {
            if (!clickChecker) {
                place = autocomplete.getPlace();
                marker.setPosition(place.geometry.location);
            } else {
                clickChecker = false;
            }
            marker.setVisible(true);
            if (!place.geometry) {
                sys_toast_warning_info("The location you entered is unserviceable.");
                return;
            }
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(15);
            }
            lat1 = place.geometry.location.lat();
            lng1 = place.geometry.location.lng();
            $("#f_sender_address").val($("#pac-input").val());
            $("#f_sender_address_lat").val(lat1);
            $("#f_sender_address_lng").val(lng1);
            if (place.length != 0 && place2.length != 0) {
                sender_mark.setVisible(false);
                recipient_mark.setVisible(false);
                priceanddirections.sender_lat = lat1;
                priceanddirections.sender_lon = lng1;
                priceanddirections.destinations = [];
                priceanddirections.destinations.push({ recipient_lat: lat2, recipient_lon: lng2 });
                roadTrip.setMap(null);
                getDeliveryPriceAndDirections(priceanddirections);
            }
        });
        recepientLocation.addListener("place_changed", function () {
            if (!clickChecker) {
                place2 = recepientLocation.getPlace();
                marker1.setPosition(place2.geometry.location);
            } else {
                clickChecker = false;
            }
            marker1.setVisible(true);
            if (!place2.geometry) {
                sys_toast_warning_info("The location you entered is unserviceable.");
                return;
            }
            if (place2.geometry.viewport) {
                map.fitBounds(place2.geometry.viewport);
            } else {
                map.setCenter(place2.geometry.location);
                map.setZoom(15);
            }
            lat2 = place2.geometry.location.lat();
            lng2 = place2.geometry.location.lng();
            $("#f_recepient_address").val($("#pac-input2").val());
            $("#f_recepient_address_lat").val(lat2);
            $("#f_recepient_address_lng").val(lng2);
            if (place.length != 0 && place2.length != 0) {
                sender_mark.setVisible(false);
                recipient_mark.setVisible(false);
                priceanddirections.sender_lat = lat1;
                priceanddirections.sender_lon = lng1;
                priceanddirections.destinations = [];
                priceanddirections.destinations.push({ recipient_lat: lat2, recipient_lon: lng2 });
                roadTrip.setMap(null);
                getDeliveryPriceAndDirections(priceanddirections);
            }
        });
        google.maps.event.addListener(map, "click", function (event) {
            var placeMarker = event.latLng;
            if (clicker) {
                if (clickLocation == "sender") {
                    marker.setPosition(placeMarker);
                } else {
                    marker1.setPosition(placeMarker);
                }
            } else {
                if (clickLocation == "sender") {
                    marker.setPosition(placeMarker);
                } else {
                    marker1.setPosition(placeMarker);
                }
            }
            clickLat = placeMarker.lat();
            clickLng = placeMarker.lng();
            geocoder.geocode({ latLng: placeMarker }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        if (clickLocation == "sender") {
                            clickChecker = true;
                            place = results[0];
                            $("#pac-input").val(results[0].formatted_address);
                            $("#f_sender_address").val(results[0].formatted_address);
                            google.maps.event.trigger(autocomplete, "place_changed");
                            clickLocation = "";
                        } else if (clickLocation == "recepient") {
                            clickChecker = true;
                            place2 = results[0];
                            $("#pac-input2").val(results[0].formatted_address);
                            $("#f_recepient_address").val(results[0].formatted_address);
                            google.maps.event.trigger(recepientLocation, "place_changed");
                            clickLocation = "";
                        }
                    } else {
                        sys_toast_warning_info("No results");
                    }
                } else {
                    sys_toast_warning_info(status);
                }
            });
        });
    });
    $("#date_start, #date_end, #f_sender_date, #f_recepient_date").datepicker({ dateFormat: "mm-dd-yy" });
    $("#pac-input").click(function (e) {
        e.preventDefault();
        $("#pac-input").val("");
        $("#f_distance").val("");
        $("#f_duration").val("");
        $("#f_price").val("");
        $("#f_sender_address").val("");
        $("#f_sender_address_lat").val("");
        $("#f_sender_address_lng").val("");
        clickLocation = "sender";
        roadTrip.setMap(null);
        marker.setVisible(false);
        sender_mark.setVisible(false);
        place = [];
        reset_toggles();
    });
    $("#pac-input2").click(function (e) {
        e.preventDefault();
        $("#pac-input2").val("");
        $("#f_distance").val("");
        $("#f_duration").val("");
        $("#f_price").val("");
        $("#f_recepient_address").val("");
        $("#f_recepient_address_lat").val("");
        $("#f_recepient_address_lng").val("");
        clickLocation = "recepient";
        roadTrip.setMap(null);
        marker1.setVisible(false);
        recipient_mark.setVisible(false);
        place2 = [];
        reset_toggles();
    });
    $("input#pac-input2").on("focus", function () {
        $("#adddeliveryoperatormodal").css("overflow-y", "hidden");
    });
    $("input#pac-input").on("focus", function () {
        $("#adddeliveryoperatormodal").css("overflow-y", "hidden");
    });
    $("input#pac-input").focusout(function () {
        $("#adddeliveryoperatormodal").css("overflow-y", "visible");
    });
    $("input#pac-input2").focusout(function () {
        $("#adddeliveryoperatormodal").css("overflow-y", "visible");
    });
    $("#search_btn, #reset_btn").click(function (e) {
        if (e.currentTarget.id == "reset_btn") {
            $("#f_order_status").val("").trigger("change");
            $("#f_driver_id_op").val("").trigger("change");
            fillup_daterange_subtract(1, "weeks");
        }
        fillDataTable();
    });
    $("#f_driver_id_op, #f_order_status").on("change", function () {
        fillDataTable();
    });
    $("#newDelivery_btn").click(function () {
        promo_code = 0;
        promo_code_valid = true;
        promo_code_invalid_message = "";
        getConsumerDriverId();
        place = [];
        place2 = [];
        $("#deliveryoperator_form")[0].reset();
        reset_toggles();
        $(".cargo_others").attr("hidden", "true");
        get_cargo_types();
        $("#adddeliveryoperatormodal").modal();
        $(".send_date").attr("hidden", "true");
        $(".rec_date").attr("hidden", "true");
        roadTrip.setMap(null);
        sender_mark.setVisible(false);
        recipient_mark.setVisible(false);
    });
    $('input[name="f_collectFrom"]').on("change", function () {
        if (isCod) {
            $("#f_collectFrom1").prop("checked", false);
            $("#f_collectFrom2").prop("checked", true);
            $("#f_collectFrom").val("R");
            $("#collectPFromModal").modal();
        }
    });
    $("input:checkbox[name='f_express_fee']").on("click", function () {
        $("span.order_price").remove();
        var box = $(this);
        if (box.is(":checked")) {
            $("input:checkbox[name='" + box.attr("name") + "']").prop("checked", false);
            box.prop("checked", true);
            isExpress = true;
        } else {
            box.prop("checked", false);
            isExpress = false;
        }
        roadTrip.setMap(null);
        if (place.length != 0 && place2.length != 0) {
            getDeliveryPriceAndDirections(priceanddirections);
        }
    });
    $("input:checkbox[name='f_is_cod']").on("click", function () {
        $("span.order_price").remove();
        var box = $(this);
        if (box.is(":checked")) {
            $("input:checkbox[name='" + box.attr("name") + "']").prop("checked", false);
            box.prop("checked", true);
            isCod = true;
            $("._cod").show("slow");
            $("#f_collectFrom1").prop("checked", false);
            $("#f_collectFrom2").prop("checked", true);
            $("#f_collectFrom").val("R");
        } else {
            box.prop("checked", false);
            isCod = false;
            $("#f_recepient_cod").val("");
            $("._cod").hide("slow");
        }
        roadTrip.setMap(null);
        if (place.length != 0 && place2.length != 0) {
            getDeliveryPriceAndDirections(priceanddirections);
        }
    });
    function reset_toggles() {
        $("#f_express_fee, #f_is_cod").attr("disabled", true);
        $("#f_recepient_cod").val("");
        $("._cod").hide("slow");
        $("input:checkbox[name='f_express_fee'], input:checkbox[name='f_is_cod']").prop("checked", false);
        isExpress = false;
        isCod = false;
    }
    $("#f_sender_date").on("change", function () {
        $("#f_recepient_date").val($("#f_sender_date").val());
    });
    $("#f_recepient_date").on("change", function () {
        $("#f_sender_date").val($("#f_recepient_date").val());
    });
    $("select#f_order_type_send").on("change", function () {
        $(".send_date").removeAttr("hidden");
        if ($("#f_order_type_rec").val() == 2) {
            $("#f_sender_date").val($("#f_recepient_date").val());
            $("#f_sender_date").addClass("disabled");
        }
        if (this.value != 2) {
            $(".send_date").attr("hidden", "hidden");
            $("#f_sender_date").val("");
            $("#f_recepient_date").removeClass("disabled");
            $("#f_sender_date").removeClass("disabled");
            $("#f_sender_datetime_from").val("");
            $("#f_sender_datetime_to").val("");
        }
    });
    $("select#f_order_type_rec").on("change", function () {
        $(".rec_date").removeAttr("hidden");
        if ($("#f_order_type_send").val() == 2) {
            $("#f_recepient_date").val($("#f_sender_date").val());
            $("#f_recepient_date").addClass("disabled");
        }
        if (this.value != 2) {
            $(".rec_date").attr("hidden", "hidden");
            $("#f_recepient_date").removeClass("disabled");
            $("#f_sender_date").removeClass("disabled");
            $("#f_recepient_date").val("");
            $("#f_recepient_datetime_from").val("");
            $("#f_recepient_datetime_to").val("");
        }
    });
    $("#deliveryoperator_form").submit(function (event) {
        event.preventDefault();
        var form = $(this);
        save_form(form);
    });
    $("#initiate_btn").click(function (e) {
        $("#deliveryoperator_form").submit();
    });
    $(document).delegate(".view", "click", function (e) {
        edit = true;
        showCover("Loading details...");
        $.ajax({
            type: "get",
            url: base_url + "app/deliveries/view_deliveries/" + e.currentTarget.id,
            data: "",
            success: function (data) {
                hideCover();
                var json_data = JSON.parse(data);
                sys_log(json_data.environment, json_data);
                $("#view_delivery_form")[0].reset();
                populate_view_form(json_data.message.message);
                $("#logs_space").html(json_data.logs_view);
                $("#viewdeliveriesmodal").modal();
            },
            error: function (error) {
                hideCover();
                sys_toast_error(error.responseText);
            },
        });
    });
    $("#f_recepient_cod").keypress(function (event) {
        return isNumber(event, this);
    });
    function isNumber(evt, element) {
        var charCode = evt.which ? evt.which : event.keyCode;
        if ((charCode != 46 || $(element).val().indexOf(".") != -1) && (charCode < 48 || charCode > 57)) return false;
        return true;
    }
    $("#f_promo_code").on("input", function () {
        promo_code = $("#f_promo_code").val();
        promo_checking = 0;
        promo_code_valid = true;
        promo_code_invalid_message = "";
        if (promo_code != "") {
            validate_promo_code(promo_code);
            $("select#f_order_type_send").val("1").trigger("change");
            $("select#f_order_type_rec").val("1").trigger("change");
            $("select#f_order_type_send, select#f_order_type_rec").addClass("disabled");
        } else {
            la1 = $("#f_sender_address_lat").val();
            lo1 = $("#f_sender_address_lng").val();
            la2 = $("#f_recepient_address_lat").val();
            lo2 = $("#f_recepient_address_lng").val();
            if (la1 != "" && la2 != "" && la2 != "" && lo2 != "") {
                getDeliveryPriceAndDirections(priceanddirections);
            }
            $("select#f_order_type_send").val("1").trigger("change");
            $("select#f_order_type_rec").val("1").trigger("change");
            $("select#f_order_type_send, select#f_order_type_rec").removeClass("disabled");
        }
    });
    function validate_promo_code(promo_code) {
        $.ajax({
            type: "post",
            url: base_url + "app/deliveries/validate_promo_code",
            data: { promo_code: promo_code },
            success: function (data) {
                var json_data = JSON.parse(data);
                if (json_data.success) {
                    la1 = $("#f_sender_address_lat").val();
                    lo1 = $("#f_sender_address_lng").val();
                    la2 = $("#f_recepient_address_lat").val();
                    lo2 = $("#f_recepient_address_lng").val();
                    promo_code = 0;
                    promo_code_valid = true;
                    promo_code_invalid_message = "";
                    if (la1 != "" && la2 != "" && la2 != "" && lo2 != "") {
                        priceanddirections.promo_code = promo_code;
                        getDeliveryPriceAndDirections(priceanddirections);
                    }
                } else {
                    promo_checking = 2;
                    promo_code_valid = false;
                    promo_code_invalid_message = json_data.message;
                    $("#f_distance").val("");
                    $("#f_duration").val("");
                    $("#f_price").val("");
                    reset_toggles();
                }
            },
        });
    }
    function getDeliveryPriceAndDirections(booking_data) {
        marker.setVisible(false);
        marker1.setVisible(false);
        var data = { f_sender_lat: booking_data.sender_lat, f_sender_lon: booking_data.sender_lon, f_promo_code: $("#f_promo_code").val(), destinations: booking_data.destinations, isExpress: isExpress, isCashOnDelivery: isCod };
        $.ajax({
            type: "get",
            url: base_url + "app/deliveries/getDeliveryPriceAndDirections/",
            data: data,
            success: function (data) {
                hideCover();
                var json_data = JSON.parse(data);
                if (json_data.success == true) {
                    $("#f_express_fee, #f_is_cod").removeAttr("disabled");
                    get_res = json_data.result;
                    some_error = "";
                    promo_code_invalid_message = "";
                    promo_code_valid = true;
                    promo_checking = 1;
                    json_data = get_res.data.getDeliveryPriceAndDirections;
                    direc = json_data.directions;
                    price = json_data.pricing.price;
                    duration = getDuration(json_data.pricing.duration);
                    distance = json_data.pricing.distance;
                    sender_lat = direc.legs[0].startLocation.latitude;
                    sender_lon = direc.legs[0].startLocation.longitude;
                    recipient_lat = direc.legs[0].endLocation.latitude;
                    recipient_lon = direc.legs[0].endLocation.longitude;
                    $("#f_price").val(price);
                    $("#f_distance").val(distance + " km");
                    $("#f_duration").val(duration);
                    $("#f_sender_address_lat").val(sender_lat);
                    $("#f_sender_address_lng").val(sender_lon);
                    $("#f_recepient_address_lat").val(recipient_lat);
                    $("#f_recepient_address_lng").val(recipient_lon);
                    postDelivery = { hash: json_data.hash };
                    var waypts = [];
                    waypts.push({ lat: sender_lat, lng: sender_lon });
                    $.each(direc.legs[0].polyline, function (key, val) {
                        waypts.push({ lat: val.latitude, lng: val.longitude });
                    });
                    waypts.push({ lat: recipient_lat, lng: recipient_lon });
                    roadTrip = new google.maps.Polyline({ path: waypts, strokeColor: "#FF0000", strokeOpacity: 1.0, strokeWeight: 2 });
                    roadTrip.setMap(map);
                    var senlatlng = new google.maps.LatLng(sender_lat, sender_lon);
                    var reclatlng = new google.maps.LatLng(recipient_lat, recipient_lon);
                    sender_mark.setPosition(senlatlng);
                    sender_mark.setVisible(true);
                    recipient_mark.setPosition(reclatlng);
                    recipient_mark.setVisible(true);
                    map.setZoom(15);
                    map.setCenter({ lat: sender_lat, lng: sender_lon });
                } else {
                    some_error = "";
                    if ("promo_code" in json_data) {
                        promo_checking = 2;
                        promo_code_valid = false;
                        promo_code_invalid_message = json_data.promo_code;
                    }
                    if ("some_error" in json_data) {
                        some_error = json_data.some_error;
                    }
                    price = 0;
                    $("#f_price").val(price);
                }
            },
            error: function (error) {
                hideCover();
                sys_toast_error(error.responseText);
            },
        });
    }
    function getConsumerDriverId() {
        $.ajax({
            type: "get",
            url: base_url + "app/deliveries/getConsumerDriverId/",
            data: "",
            success: function (data) {
                hideCover();
                var json_data = JSON.parse(data);
                var drivers = json_data.driverID;
                if (drivers != null) {
                    $("select#f_driver_id").find("option").remove().end().append('<option value ="" selected>Select Rider</option>').val("");
                    $.each(drivers, function (key, val) {
                        $("<option>").val(val.id).text(val.driver).appendTo("#f_driver_id");
                    });
                } else {
                    $("select#f_driver_id").find("option").remove().end().append('<option value ="" selected>No driver</option>').val("");
                }
            },
            error: function (error) {
                hideCover();
                sys_toast_error(error.responseText);
            },
        });
    }
    function save_form(form) {
        clearFormErrors();
        cargo_type = $("#f_cargo").val();
        cargo_others = $("#f_cargo_others").val();
        if (cargo_type != "others") {
            $("#f_cargo_others").val(cargo_type);
        }
        var pro_v = $("#f_promo_code").val();
        $("#f_promo_error").val("");
        $("#f_some_error").val("");
        if (!promo_code_valid && pro_v != "") {
            $("#f_promo_error").val("invalid");
        }
        if (some_error != "") {
            $("#f_some_error").val("invalid");
        }
        $("#f_post").val(JSON.stringify(postDelivery));
        postdata = new FormData(form[0]);
        showCover("Creating Delivery...");
        $.ajax({
            url: form.attr("action"),
            type: form.attr("method"),
            data: postdata,
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {
                json_data = JSON.parse(data);
                sys_log(json_data);
                if (json_data.success == true) {
                    $("#deliveryoperator_form")[0].reset();
                    $("#adddeliveryoperatormodal").modal("hide");
                    $("input[name=" + json_data.csrf_name + "]").val(json_data.csrf_hash);
                    roadTrip.setMap(null);
                    sys_toast_success(json_data.message);
                    fillDataTable();
                } else {
                    var pro_val = $("#f_promo_error").val();
                    if (pro_val == "invalid") {
                        if (!json_data.field_errors) {
                            json_data.field_errors = [];
                        }
                        json_data.field_errors.f_promo_code = promo_code_invalid_message;
                    }
                    if (json_data.field_errors) {
                        show_errors(json_data, form);
                    }
                    var some_val = $("#f_some_error").val();
                    if (some_val == "invalid") {
                        $("h1#operator_some_error").html(some_error);
                        $("#someErrorModal").modal();
                    }
                    sys_toast_warning_info(json_data.message.error);
                    $("input[name=" + json_data.csrf_name + "]").val(json_data.csrf_hash);
                }
                hideCover();
            },
            error: function (error) {
                hideCover();
                if (error.status == 403) {
                    sys_toast_warning_info("Security token has been expired. This page is being reloaded.");
                    setTimeout(function () {
                        showCover("Page reloading...");
                        window.location.href = window.location.href;
                    }, 1000);
                } else if (error.status == 404) {
                    sys_toast_error("Something went wrong. Please contact the system administrator.");
                }
            },
        });
    }
    function getDrivers() {
        $.ajax({
            type: "get",
            url: base_url + "app/deliveries/getConsumerDriverId/",
            data: "",
            success: function (data) {
                var json_data = JSON.parse(data);
                var drivers = json_data.driverID;
                sys_log(json_data.environment, json_data);
                $("select#f_driver_id_op").find("option").remove().end().append('<option value="" selected>Select Rider</option>').val("");
                $.each(drivers, function (key, val) {
                    $("<option>").val(val.id).text(val.driver).appendTo("#f_driver_id_op");
                });
            },
            error: function (error) {
                sys_toast_error(error.responseText);
            },
        });
    }
    function populate_view_form(data) {
        $("#f_id_op").text(data.delivery_id);
        $("#f_date").text(check_value(data.date_created));
        $("#f_distance_op").text(set_measurement_value(data.distance));
        $("#f_duration_op").text(check_value(data.duration));
        $("#f_promo_code_op").text(data.discount !== null && data.discount != 0 ? data.promo_code : " - ");
        $("span.is_discounted").remove();
        if (data.discount > 0) {
            var discount_value = Number(data.discount) + Number(data.price);
            $("span.discount").html('<span class="text-muted is_discounted">â‚±<del><b>' + discount_value.toFixed(2) + "</b></del><br></span>");
        }
        $("#f_price_op").text(set_amount_value(data.price));
        $("#f_notes").text(check_value(data.notes));
        $("#f_cash_on_delivery").text(check_value(data.cash_on_delivery));
        $("#f_d_type").html(check_value(data.isexpress));
        $("#f_collect_from").text(data.collect_payment_from == "S" ? "Sender" : "Recipient");
        $("#f_cargo_op").text(check_value(data.cargo));
        $("#f_driver_op").text(check_value(data.driver));
        $("#f_vehicle").text(check_value(data.vehicle));
        $("#f_sender_name_op").text(check_value(data.sender_name));
        $("#f_sender_address_op").text(check_value(data.sender_address));
        $("#f_sender_mobile_op").text(check_value(data.sender_mobile));
        $("#f_recipient_name").text(check_value(data.recipient_name));
        $("#f_recipient_address").text(check_value(data.recipient_address));
        $("#f_recipient_mobile").text(check_value(data.recipient_mobile));
        $("#f_consumer_rating").text(check_value(data.consumer_rating));
        $("#f_consumer_rating_text").text(check_value(data.consumer_rating_text));
        $("#f_driver_rating").text(check_value(data.driver_rating));
        $("#f_driver_rating_text").text(check_value(data.driver_rating_text));
        $("#f_status").text(check_value(data.status_value));
        $("b.br").append("<br class='brpg'>");
        $("#rider_avatar").attr("class", "avatar_image");
        $("#rider_avatar").attr("src", data.rider_avatar);
        $("#f_ordertype").text("As soon as possible");
        $(".orderType").attr("hidden", "hidden");
        if (data.order_type === "2") {
            $("#f_ordertype").text("Scheduled");
            $(".orderType").removeAttr("hidden");
            if (data.pick_up_date_from) {
                if (data.pick_up_date_from == data.pick_up_date_to) {
                    $("#f_pick_up").html(data.pick_up_date_from + " <b style='color: orange;'>From</b> " + data.pick_up_time_from + " <b>To</b> " + data.pick_up_time_to);
                } else {
                    $("#f_pick_up").html(data.pick_up_date_from + " " + data.pick_up_time_from + " <b style='color: orange;'>To</b> " + data.pick_up_date_to + " " + data.pick_up_time_to);
                }
            } else {
                $("#f_pick_up").text("As soon as possible");
            }
            if (data.deliver_date_from) {
                if (data.deliver_date_from == data.deliver_date_to) {
                    $("#f_deliver").html(data.deliver_date_from + " <b style='color: orange;'>From</b> " + data.deliver_time_from + " <b style='color: orange;'>To</b> " + data.deliver_time_to);
                } else {
                    $("#f_deliver").html(data.deliver_date_from + " " + data.deliver_time_from + " <b style='color: orange;'>To</b> " + data.deliver_date_to + " " + data.deliver_time_to);
                }
            } else {
                $("#f_deliver").text("As soon as possible");
            }
        }
        $("br.brpg").remove();
        $("#rider_avatar").attr("class", "hidden");
        $("#rider_avatar").attr("src", "");
        if (data.rider_avatar != null && data.rider_avatar != "" && data.driver !== null && data.driver !== "") {
            $("br.brpg").remove();
            $("b.br").append("<br class='brpg'>");
            $("#rider_avatar").attr("class", "avatar_image");
            $("#rider_avatar").attr("src", data.rider_avatar);
        }
        $("#item_status_content").addClass("hidden");
        if (data.image) {
            $("#item_status_content").removeClass("hidden");
            $("div.images").remove();
            $.each(data.image, function (arr, item) {
                $("div#item_status_content").append(
                    '<div class="col-md-6 images"> <div class="form-group input-group"> <div class="input-group-append"><div class="input-group-text btn-warning rounded mr-3 mt-2" style="height:30px;"><span class="fas fa-camera" style="color:white"></span></div><label style="color:black;width: 80%" ><b class="text-muted item_status_title">' +
                        item.status_description +
                        '</b><p class="item_status_body"><img src="' +
                        item.item_image +
                        '" width="50%" /></p></label></div></div></div>'
                );
            });
        }
    }
    async function loadDeliveryStatus() {
        var status;
        if (!status) {
            getJsonData("app/deliveries/fetch_operator_delivery_status").then(async function (data) {
                if (!data.status) {
                    sys_toast_warning(data.message);
                    return false;
                }
                status = data.data;
                status = status.map(function (item) {
                    return { id: item.index, text: item.value, value: item.index };
                });
                $("select[data-render=f_order_status]").select2({ placeholder: "All", data: status });
            });
        }
    }
    function get_cargo_types() {
        $.ajax({
            type: "get",
            url: base_url + "app/websiteBooking/getCargoTypes",
            data: null,
            success: function (data) {
                var json_data = JSON.parse(data);
                var cargo_types = json_data.message;
                $("select#f_cargo").find("option").remove().end();
                $.each(cargo_types, function (key, val) {
                    $("<option>").val(val.type).text(val.type).appendTo("#f_cargo");
                });
                $("<option>").val("others").text("Others").appendTo("#f_cargo");
            },
        });
    }
    $("select#f_cargo").on("change", function () {
        var cargo = $(this).val();
        if (cargo == "others") {
            $("#f_cargo_others").val("");
            $(".cargo_others").removeAttr("hidden");
        } else {
            $(".cargo_others").attr("hidden", "true");
        }
    });
    function getDuration(res_duration) {
        if (res_duration <= 60) {
            if (res_duration == 60) {
                res_duration = "1 hour";
            } else {
                res_duration = res_duration + " minutes";
            }
        } else {
            hours = parseInt(res_duration / 60);
            remainder = parseInt(res_duration % 60);
            duration = "";
            if (hours == 1) {
                duration = "1 hour ";
            } else {
                duration = hours + " hours ";
            }
            if (remainder == 1) {
                duration = duration + remainder + " minute";
            } else if (remainder > 1) {
                duration = duration + remainder + " minutes";
            }
            res_duration = duration;
        }
        return res_duration;
    }
});

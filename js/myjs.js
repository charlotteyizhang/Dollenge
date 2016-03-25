/**
 * Created by user on 2016/2/18.
 */
jQuery( document ).ready(function( $ ) {
    $('#loginBtn').on('show.bs.popover',function(){
        $('#registerBtn').popover('hide');
    })
    $('#loginBtn').popover({
        html: true,
        placement: "bottom",
        content: function () {
            return $(this).parent().find('.loginForm').html();
        }
    })
    $('#registerBtn').on('show.bs.popover',function(){
        $('#loginBtn').popover('hide');
    })
    $('#registerBtn').popover({
        html: true,
        placement: "bottom",
        content: function () {
            return $(this).parent().find('.registerForm').html();
        }
    })
    $('#userBtn').popover({
        html: true,
        placement: "bottom",
        content: function (){
            return $(this).parent().find('.userInfo').html();
        }
    })
    $('#close').click(function(){
        $('#userText').html("");
        $('#close').html("");
    })
    //$(".form_datetime").datetimepicker({
    //    format: "dd MM yyyy - hh:ii",
    //    autoclose: true,
    //    todayBtn: true,
    //    pickerPosition: "bottom-left"
    //})
    $(function() {

        $.noConflict();
        jQuery( document ).ready(function( $ ) {
            var src = "php/search.php";

            $( "#search" ).autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: src,
                        dataType: "json",
                        data: {
                            term : request.term,
                            table : $("#table").val()
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,//search after two characters
                select: function( event , ui ) {
                    var pageName = $("#table").val();
                    var path = pageName + ".php?" + pageName + "_id=" + ui.item.id;
                    window.location.replace(path);
//            alert( "You selected: " + ui.item.id );
                }
            });

        });

        $('#description').on('keyup', function(event) {
            var len = $(this).val().length;
            var left = 300-len;
            $("#countText").html(left + "characters left");
            $("#countText").css("color: red");
            if (len >= 500) {
                $(this).val($(this).val().substring(0, len-1));
            }
        });

    });
});
//Map js
    // This sample uses the Place Autocomplete widget to allow the user to search
    // for and select a place. The sample then displays an info window containing
    // the place ID and other information about the place that the user has
    // selected.

    // This example requires the Places library. Include the libraries=places
    // parameter when you first load the API. For example:
    // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">

function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -33.8688, lng: 151.2195},
        zoom: 13
    });

    var input = document.getElementById('location');

    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.bindTo('bounds', map);

    map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

    var infowindow = new google.maps.InfoWindow();
    var marker = new google.maps.Marker({
        map: map
    });
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });

    autocomplete.addListener('place_changed', function() {
        infowindow.close();
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        if (place.geometry.viewport) {
            map.fitBounds(place.geometry.viewport);
        } else {
            map.setCenter(place.geometry.location);
            map.setZoom(17);
        }

        // Set the position of the marker using the place ID and location.
        marker.setPlace({
            placeId: place.place_id,
            location: place.geometry.location
        });
        marker.setVisible(true);
        infowindow.setContent('<div style="color:#000;"><strong>' + place.name +  '<br>' +
            place.formatted_address);
        infowindow.open(map, marker);
        var location_id = document.getElementById('currentPage').value + "_location";
        //var location_name= location_id + "_name";
        document.getElementById(location_id).value = place.place_id;
        //document.getElementById(location_name).value = place.name;
    });
}

function setLocation(){

    var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: -33.866, lng: 151.196},
        zoom: 15
    });

    var infowindow = new google.maps.InfoWindow();
    var service = new google.maps.places.PlacesService(map);
    var place_Id = document.getElementById('location').value;
    service.getDetails({
        placeId: place_Id
    }, function(place, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
            var marker = new google.maps.Marker({
                map: map,
                position: place.geometry.location
            });
            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);
            }

            // Set the position of the marker using the place ID and location.
            marker.setPlace({
                placeId: place.place_id,
                location: place.geometry.location
            });
            marker.setVisible(true);
            infowindow.setContent('<div><strong>' + place.name +'<br>' +
                place.formatted_address);
            infowindow.open(map, marker);
        }
    });

}
function valiate(username){
    $.post('php/validate.php', {'user_name':username}, function(data) {
        $("#userText").show();
        $("#userText").html(data);
        $("#close").html("x");
    });
}


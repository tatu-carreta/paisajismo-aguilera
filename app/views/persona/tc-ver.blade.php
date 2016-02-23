@extends($project_name.'-master')

@section('head')
    @parent

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAn5w3k9P2ISxdcfYba-sPxrGrz3W9-Jqk&sensor=false"></script>
    <script type="text/javascript">
        var data = {{$data}};
        var directionsDisplay;
        var directionsService = new google.maps.DirectionsService();
        var map;
        var infowindow = new google.maps.InfoWindow({
            content: ""
        });
        var markers = [];
        
        function initialize() {
          directionsDisplay = new google.maps.DirectionsRenderer();
          var mapOptions = {
            center: new google.maps.LatLng(-34.92125, -57.95433329999997),
            zoom: 13,
          };
          map = new google.maps.Map(document.getElementById('map_canvas'), mapOptions);
          directionsDisplay.setMap(map);
          
          // Try HTML5 geolocation
            if(navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                var miPosicion = new google.maps.LatLng(position.coords.latitude,
                                                 position.coords.longitude);

                $(".long").val(miPosicion.lng());
                $(".lat").val(miPosicion.lat());
                var marker = new google.maps.Marker({
                    position: miPosicion,
                    map: map,
                    title: "Estoy Acá"
                });
                markers.push(marker);
                //$(".miPosicion").val(miPosicion);
                map.setCenter(miPosicion);
              }, function() {
                handleNoGeolocation(true);
              });
            } else {
              // Browser doesn't support Geolocation
              handleNoGeolocation(false);
            }
          
        var latLng = new google.maps.LatLng(data.latitud, data.longitud);
          // Creating a marker and putting it on the map
          var marker = new google.maps.Marker({
              position: latLng,
              map: map,
              title: data.title
          });
markers.push(marker);
          bindInfoWindow(marker, map, infowindow, data.descripcion);
          
          
        }

        function calcRoute() {
            deleteMarkers();
          //  alert($('.miPosicion').val());
          var start = new google.maps.LatLng($('.lat').val(), $(".long").val());
          var end = new google.maps.LatLng(data.latitud, data.longitud);
          var request = {
              origin:start,
              destination:end,
              travelMode: google.maps.TravelMode.DRIVING
          };
          directionsService.route(request, function(response, status) {
              //alert(status);
            if (status == google.maps.DirectionsStatus.OK) {
              directionsDisplay.setDirections(response);
            }
          });
        }
        
        function bindInfoWindow(marker, map, infowindow, strDescription) {
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(strDescription);
                infowindow.open(map, marker);
            });
        }
        
        // Sets the map on all markers in the array.
            function setAllMap(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }

            // Removes the markers from the map, but keeps them in the array.
            function clearMarkers() {
                setAllMap(null);
            }

            // Shows any markers currently in the array.
            function showMarkers() {
                setAllMap(map);
            }

            // Deletes all markers in the array by removing references to them.
            function deleteMarkers() {
                clearMarkers();
                markers = [];
            }

        google.maps.event.addDomListener(window, 'load', initialize);
        
        /*
        var data = {{$data}};
        var geocoder = new google.maps.Geocoder();
        var map;
        var infowindow = new google.maps.InfoWindow({
            content: ""
        });
        function initialize() {
            var mapOptions = {
                center: new google.maps.LatLng(-34.92125, -57.95433329999997),
                zoom: 13,
            };
            map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);

            var latLng = new google.maps.LatLng(data.latitud, data.longitud);
            // Creating a marker and putting it on the map
            var marker = new google.maps.Marker({
                position: latLng,
                map: map,
                title: data.title
            });

            bindInfoWindow(marker, map, infowindow, data.descripcion);
        }

        function bindInfoWindow(marker, map, infowindow, strDescription) {
            google.maps.event.addListener(marker, 'click', function() {
                infowindow.setContent(strDescription);
                infowindow.open(map, marker);
            });
        }

        google.maps.event.addDomListener(window, 'load', initialize);
        */
    </script>
@stop

@section('contenido')
    <section>
        <div class="container">
            <!--columna producto y descripcion -->
            <div class="col70">
                <div>
                    <h2>AN: {{ $persona -> apellido }} {{ $persona -> nombre }}</h2> 
                    <h2>Email: {{ $persona -> email }}</h2>
                    <h2>FN: {{ $persona -> fecha_nacimiento }}</h2>
                </div>
                <div class="clear"></div>
            </div>
            <!--columna info Mapa -->
            <div class="col30">
                <input type="hidden" class="long">
                <input type="hidden" class="lat">
                <div id="map_canvas" style="width:500px; height:500px"></div>
            </div>
            <div class="clear"></div>
            <button onclick="calcRoute();">Calcular</button>
        </div>
    </section>
@stop
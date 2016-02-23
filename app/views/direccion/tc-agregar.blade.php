@extends($project_name.'-master')

@section('head')
    @parent

    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAn5w3k9P2ISxdcfYba-sPxrGrz3W9-Jqk&sensor=false"></script>
    <script type="text/javascript">
        var map;
            var markers = [];
            var infowindow = new google.maps.InfoWindow({
                content: ""
            });
            function initialize() {
                var mapOptions = {
                    center: new google.maps.LatLng(-34.92125, -57.95433329999997),
                    zoom: 13,
                };
                map = new google.maps.Map(document.getElementById("map_canvas"),
                        mapOptions);

                google.maps.event.addListener(map, 'click', function(event) {
                    deleteMarkers();
                    var marker = new google.maps.Marker({
                        position: event.latLng,
                        map: map,
                        title: 'Dirección'
                    });

                    markers.push(marker);
                    
                    $("input[name='longitud']").val(event.latLng.lng());
                    $("input[name='latitud']").val(event.latLng.lat());
/*
                    $(".long").text(event.latLng.lng());
                    $(".lat").text(event.latLng.lat());
        */

                    //map.setCenter(event.latLng);

                    bindInfoWindow(marker, map, infowindow, 'Holi');


                   // alert('Point.X.Y: ' + event.latLng);
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

            function bindInfoWindow(marker, map, infowindow, strDescription) {
                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.setContent(strDescription);
                    infowindow.open(map, marker);
                });
            }

            google.maps.event.addDomListener(window, 'load', initialize);
        /*
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
            
            
        }
        function codificarGeo(address){
            var address;
            
            address += $("input[name='numero']").val();
            address += " Calle "+$("input[name='numero']").val();
            address += " "+$("input[name='ciudad']").val()+", ";
            address += $("input[name='provincia']").val()+", ";
            address += $("input[name='pais']").val();
            
            geocoder.geocode({'address': address}, function(results, status) {
                //alert(status);
                if (status == google.maps.GeocoderStatus.OK) {

                    var marker = new google.maps.Marker({
                        position: results[0].geometry.location,
                        map: map,
                        title: 'Dirección'
                    });
                    
                    $("input[name='longitud']").val(results[0].geometry.location.lng());
                    $("input[name='latitud']").val(results[0].geometry.location.lat());

                    bindInfoWindow(marker, map, infowindow, 'Holi');
                }
                else
                {
                    alert("some problem in geocode" + status);
                }
            });
/*
            var latLng = new google.maps.LatLng(data.latitud, data.longitud);
            // Creating a marker and putting it on the map
            var marker = new google.maps.Marker({
                 position: latLng,
                 map: map,
                 title: data.nombreMiembro
            });
            
            bindInfoWindow(marker, map, infowindow, data.description);
            
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
    <script src="{{URL::to('js/ckeditorLimitado.js')}}"></script>
    
    <section class="container">
        @if (Session::has('mensaje'))
            <div class="divAlerta error alert-success">{{ Session::get('mensaje') }}<i onclick="" class="cerrarDivAlerta fa fa-times fa-lg"></i></div>
        @endif
        {{ Form::open(array('url' => 'admin/direccion/agregar')) }}
            <h2 class="marginBottom2"><span>Carga y modificación de direccion</span></h2>
            <div id="error" class="error" style="display:none"><span></span></div>
            <div id="correcto" class="correcto ok" style="display:none"><span></span></div>

            <!-- Abre columna de descripción -->
            <div class="col70Admin datosProducto">
                <h3>Calle</h3>
                <input class="block anchoTotal marginBottom" type="text" name="calle" placeholder="Calle" required="true" maxlength="100">

                <h3>Numero</h3>
                <input class="block anchoTotal marginBottom fecha" type="text" name="numero" placeholder="Numero" required="true" maxlength="50">

                <h3>Piso</h3>
                <input class="block anchoTotal marginBottom" type="text" name="piso" placeholder="Piso" required="true" maxlength="50">

                <h3>Departamento</h3>
                <input class="block anchoTotal marginBottom" type="text" name="departamento" placeholder="Departamento" required="true" maxlength="200">

                <h3>Ciudad</h3>
                <input class="block anchoTotal marginBottom" type="text" name="ciudad_id" placeholder="Ciudad ID" required="true" maxlength="200">
            </div>
            <div id="map_canvas" style="width:500px; height:500px"></div>
            
            <a onclick="codificarGeo('667 Calle 49 La Plata, Buenos Aires, Argentina')" class="btnGris">Codificar</a>
            <div class="clear"></div>
            <!-- cierran columnas -->
            
            <div class="punteado">
                <input type="hidden" name="latitud">
                <input type="hidden" name="longitud">
                <input type="submit" value="Publicar" class="btn marginRight5">
                <a onclick="window.history.back();" class="btnGris">Cancelar</a>
            </div>
        {{Form::close()}}
    </section>
@stop

<!-- Indicar Sección a la que pertenece el producto -->
<div class="divModIndicarSeccion">
    <h3>Otras ubicaciones (opcional)</h3>
    <div class="modIndicarSeccion">
            @foreach($menues as $men)
            <div class="cadaSeccion">
                @if(count($men->children) == 0)
                    <div>
                        @foreach($men->secciones as $seccion)
                            <span><input id="menu{{$men->id}}" type="checkbox" name="secciones[]" value="{{$seccion->id}}" @if($seccion->id == $seccion_id) checked="true" disabled @endif>{{-- @if($seccion->titulo != ""){{$seccion->titulo}}@else Sección {{$seccion->id}} @endif --}}</span>
                        @endforeach
                    </div>
                    <div><label for="menu{{$men->id}}">{{$men->nombre}}</label></div>
                @endif
            </div>
            @endforeach
    </div>
</div>
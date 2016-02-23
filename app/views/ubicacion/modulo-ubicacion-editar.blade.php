@if($seccion_next != 'null')
<div class="col-md-6 divDatos">
    <!-- Indicar Sección a la que pertenece el producto -->
    <div class="divModIndicarSeccion">
        <h3>Ubicación</h3>
        <div class="modIndicarSeccion">
            @foreach($menues as $men)
            <div class="cadaSeccion">
                @if(count($men->children) == 0)
                    <div>
                        @foreach($men->secciones as $seccion)
                            <span><input id="menu{{$men->id}}" type="checkbox" name="secciones[]" value="{{$seccion->id}}" @if(in_array($seccion->id, $item->secciones->lists('id'))) checked="true" @endif @if($seccion->id == $seccion_next) disabled @endif>{{-- @if($seccion->titulo != ""){{$seccion->titulo}}@else Sección {{$seccion->id}} @endif --}}</span>
                        @endforeach
                    </div>
                    <div><label for="menu{{$men->id}}">{{$men->nombre}}</label></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
@else
    @foreach($item->secciones as $seccion)
        <input type="hidden" name="secciones[]" value="{{$seccion->id}}">
    @endforeach
@endif
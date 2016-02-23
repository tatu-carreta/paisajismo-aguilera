@extends($project_name.'-master')

@section('contenido')
<section class="container">
    <h3>Asignar Permisos a Usuarios</h3>

    {{ Form::open(array('url' => 'admin/permiso/asignar-roles')) }}
        <table>
            <tr>
                <th>Permisos</th>
                @foreach($perfiles as $perfil)
                    <th>{{ $perfil->name }}</th>
                @endforeach
            </tr>
            @foreach($permisos as $permiso)
            <tr>
                <td>{{$permiso->name}}</td>
                @foreach($perfiles as $perfil)
                    <td><input type="checkbox" name="perfiles[]" value="{{$perfil->id}}|{{$permiso->id}}" @if(in_array($permiso->id, $perfil->perms->lists('id'))) checked="true" @endif></td>
                @endforeach
                
            </tr>
            @endforeach
        </table>
    <input type="submit" value="Confirmar">
    {{Form::close()}}
</section>

@stop
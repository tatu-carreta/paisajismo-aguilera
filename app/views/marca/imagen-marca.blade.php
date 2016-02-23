<img class="lazy" src="@if(!is_null($marca->imagen())){{ URL::to($marca->imagen()->carpeta.$marca->imagen()->nombre) }}@else{{URL::to('images/sinImg.gif')}}@endif" alt="{{$marca->nombre}}">

@if (Session::has('mensaje'))
    <div class="divAlerta alert alert-warning @if(Session::has('ok')) ok @endif @if(Session::has('error')) error @endif">{{ Session::get('mensaje') }}<button type="button" class="close" data-dismiss="alert">&times;</button></div>
@endif
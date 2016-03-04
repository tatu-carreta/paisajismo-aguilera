<nav class="navbar navbar-default" role="navigation">
        <!-- El logotipo y el icono que despliega el menú se agrupan
             para mostrarlos mejor en los dispositivos móviles -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target=".navbar-ex1-collapse">
                <span class="sr-only">Desplegar navegación</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>

        <!-- Agrupar los enlaces de navegación, los formularios y cualquier
                otro elemento que se pueda ocultar al minimizar la barra -->
        <div class="collapse navbar-collapse navbar-ex1-collapse divMenu">
            <ul class="nav navbar-nav" id="menuPrincipal">
                @foreach($menus as $m)
                    @if($m->id != 9)
                    @if(count($m->children) > 0)
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">{{$m->lang()->nombre}} <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <?php $i = 1; ?>
                                @foreach($m->children as $c)
                                    @if(count($c->children) > 0)
                                        {{-- Si el hijo tiene mas hijos --}}
                                    @else
                                        <li>
                                            <a href="{{URL::to($prefijo.'/'.$c->lang()->url)}}">{{$c->lang()->nombre}}</a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                    @else
                        <li>
                            <a href="{{URL::to($prefijo.'/'.$m->lang()->url)}}" class="btn1">{{$m->lang()->nombre}}</a>
                    @endif
                    @endif
                    </li>
                @endforeach
            </ul>
        </div>
</nav>

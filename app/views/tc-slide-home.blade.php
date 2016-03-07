
<script src="{{URL::to('js/jquery.flexslider.js')}}"></script>
<script>
    // Can also be used with $(document).ready()
    $(window).load(function() {
      $('.flexslider').flexslider({
        animation: "slide",
        controlNav: false,
        directionNav: false
      });
    });
</script>

@if(Auth::check())
    @if (!is_null($slide_index) && !is_null($slide_index -> imagenes))
        <div class="row">
            <div class="col-md-12">
                <a class="btn pull-right iconoBtn-texto" href="{{URL::to('admin/slide/editar/'.$slide_index->id.'/home/I')}}"> <i class="fa fa-pencil fa-lg"></i>Editar Slide Home</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12">
                <a class="btn pull-right iconoBtn-texto" href="{{URL::to('admin/slide/agregar/1/I')}}"> <i class="fa fa-pencil fa-lg"></i>Agregar Slide Home</a>
            </div>
        </div>
    @endif
@endif
@if (!is_null($slide_index) && !is_null($slide_index -> imagenes))
    <div class="slideDinamicoHome" @if(Auth::check()) @if (!is_null($slide_index) && !is_null($slide_index -> imagenes)) id="Pr{{$slide_index->id.$slide_index->tipo}}" @endif @endif>
        <!-- Place somewhere in the <body> of your page -->
        <div class="flexslider">
            <ul class="slides">
                @if (!is_null($slide_index) && !is_null($slide_index -> imagenes))
                    @foreach($slide_index -> imagenes as $img)
                        <li>
                            <div class="imgSlideFlex">
                                <div class="flechaSlide"></div>
                                <img src="{{ URL::to($img->carpeta.$img->nombre) }}" />
                            </div>
                            <?php
                                /*
                            <div class="flex-caption">
                                <p>{{ $img->lang()->epigrafe }}</p>
                            </div>
                                 * 
                                 */
                            ?>
                            <div class="clearfix"></div>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

    </div>
@endif
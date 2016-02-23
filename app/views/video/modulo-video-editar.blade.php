<div class="row">
    @foreach($item->videos as $video)
        <div class="col-md-4 marginBottom1">
            <iframe class="video-tc" src="@if($video->tipo == 'youtube')https://www.youtube.com/embed/@else//player.vimeo.com/video/@endif{{ $video->url }}"></iframe>
            <a onclick="borrarVideoReload('{{ URL::to('admin/video/borrar') }}', '{{$video->id}}');" class="btn pull-right"><i class="fa fa-times fa-lg"></i>eliminar</a>
        </div>
    @endforeach
</div>
<div class="row">
    <div class="col-md-12">
        @if(count($item->videos) == 2)
            <div class="form-group marginBottom1">
                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
            </div>
        @elseif(count($item->videos) == 1)
            <div class="form-group marginBottom1">
                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
            </div>
            <div class="form-group marginBottom1">
                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
            </div>
        @elseif(count($item->videos) == 0)
            <div class="form-group marginBottom1">
                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
            </div>
            <div class="form-group marginBottom1">
                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
            </div>
            <div class="form-group marginBottom1">
                <input class="form-control" type="text" name="video[]" placeholder="URL de video">
            </div>
        @endif
    </div>
</div>
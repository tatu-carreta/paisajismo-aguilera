<script type="text/javascript">
    function ShowLoading() {
        $("#divLoading").show();
        return true;
        // These 2 lines cancel form submission, so only use if needed.
        //window.event.cancelBubble = true;
        //e.stopPropagation();
    }
</script>
<div id="divLoading">
    <img width="48" height="48" src="{{URL::to('images/loading.gif')}}">
    <p>{{ Lang::get('html.texto_loading') }}</p>
</div>
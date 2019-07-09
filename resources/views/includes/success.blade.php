@if(session("success"))
    <div class="alert alert-success">
        <span class="success-text">{{session("success")}}</span>
        <a href="javascript: void(0);" class="alert-close"><i class="{{$json->icons->close}}"></i></a>
    </div>
@endif
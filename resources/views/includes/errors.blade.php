@if(count($errors) > 0)
    @foreach($errors->all() as $error)
        <div class="alert alert-danger">
            <span class="error-text">{{$error}}</span>
            <a href="javascript: void(0);" class="alert-close"><i class="{{$json->icons->close}}"></i></a>
        </div>
    @endforeach
@endif

@if(session("error"))
    <div class="alert alert-danger">
        <span class="error-text">{{session("error")}}</span>
        <a href="javascript: void(0);" class="alert-close"><i class="{{$json->icons->close}}"></i></a>
    </div>
@endif
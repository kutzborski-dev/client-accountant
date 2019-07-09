@include("includes.header")

<main>
    <div class="filter">
    </div>

    <div class="container settings">
        <div class="col-md-12" id="main-right">
            <div class="row">
                <div class="box">
                    <div class="box-header mb-0">
                        <h4>{{$json->$hl->general->settings}} <i class="{{$json->icons->rangle}} h6 text-muted"></i> {{$page}}</h4>
                    </div>
        
                    <div class="box-content pl-0 container-fluid">
                        <div class="row pb-0">
                            <div class="col-md-3 bg-secondary pl-0 pr-0 pt-3">
                                <ul class="list-group bg-secondary">
                                    <li class="list-group-item @if(strtolower($page) === 'profile'){{'active'}}@endif"><a href="{{route('settings')}}">Profile</a></li>
                                    <li class="list-group-item @if(strtolower($page) === 'bills'){{'active'}}@endif"><a href="{{route('settings')}}/bills">Bills</a></li>
                                </ul>
                            </div>
        
                            <div class="col-md-9 pl-5 pr-4 pt-4 pb-3">
                                @yield("content")
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@include("includes.footer")
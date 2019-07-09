@extends("layouts.settings")

@section("content")
    <div class="row mb-0 pb-0">
        <form action="{{route('settings')}}" method="POST" class="col-md-12 validate" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input type="hidden" name="udid" value="{{$userData->id}}"/>

            <h5>Company data</h5>

            <div class="logo-fluid mt-4">
                <div class="col-md-2 float-left text-center">
                    <img src="{{$userData->logo}}" alt="Logo" class="logo"/>
                    <input type="file" name="logo" value="{{$userData->logo_raw}}" id="logo" style="display: none;"/>
                </div>

                <div class="col-md-2 float-left pl-0">
                    <div class="row mb-1 mt-0 pt-0 pb-0">
                        <a href="javascript: void(0);" class="btn btn-primary btn-sm bg-main hover-main" id="change-logo"><i class="{{$json->icons->edit}}"></i> {{$json->$hl->general->change}}</a>
                    </div>
                    
                    <div class="row mt-0 mb-0 pt-0 pb-0">
                        <a href="javascript: void(0);" class="btn btn-primary btn-sm bg-main @if($userData->logo_raw !== 'dummy.png'){{'hover-main'}}@else{{'disabled'}}@endif" id="remove-logo"@if($userData->logo_raw === 'dummy.png'){{"disabled"}}@endif><i class="{{$json->icons->trash}}"></i> {{$json->$hl->general->remove}}</a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="input-group mb-2 ap">
                    <div class="input-group-prepend border-right">
                        <span class="input-group-text border-right">Company</span>
                    </div>

                    <input type="text" name="company" class="form-control bg-white required" data-name="Company" value="{{$userData->company}}" readonly/>

                    <div class="input-group-append">
                        <input type="hidden" name="checked" class="required" value="checked"/>
                        <a class="input-group-text input-trigger bg-main hover-main btn btn-primary edit"><i class="{{$json->icons->edit}}"></i></a>
                    </div>
                </div>

                <div class="col-md-5 pl-0 pr-1">
                    <div class="input-group mb-2 ap">
                        <div class="input-group-prepend border-right">
                            <span class="input-group-text border-right">Title</span>
                        </div>
        
                        <input type="text" name="title" class="form-control bg-white required" data-name="Title" value="{{$userData->title}}" readonly/>

                        <div class="input-group-append">
                            <input type="hidden" name="checked" class="required" value="checked"/>
                            <a class="input-group-text input-trigger bg-main hover-main btn btn-primary edit"><i class="{{$json->icons->edit}}"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-7 pl-1 pr-0">
                    <div class="input-group mb-4 ap">
                        <div class="input-group-prepend border-right">
                            <span class="input-group-text border-right">Contact</span>
                        </div>
            
                        <input type="text" name="contact" class="form-control bg-white required" data-name="Contact" value="{{$userData->contact}}" readonly/>

                        <div class="input-group-append">
                            <input type="hidden" name="checked" class="required" value="checked"/>
                            <a class="input-group-text input-trigger bg-main hover-main btn btn-primary"><i class="{{$json->icons->edit}}"></i></a>
                        </div>
                    </div>
                </div>

                <h5 class="col-md-12 pl-0 pr-1">Address</h5>

                <div class="col-md-9 pl-0 pr-1">
                    <div class="input-group mb-2 mt-2 ap">
                        <div class="input-group-prepend border-right">
                            <span class="input-group-text border-right">Street</span>
                        </div>
                
                        <input type="text" name="street" class="form-control bg-white required" data-name="Street" value="{{$userData->street}}" readonly/>
        
                        <div class="input-group-append">
                            <input type="hidden" name="checked" class="required" value="checked"/>
                            <a class="input-group-text input-trigger bg-main hover-main btn btn-primary"><i class="{{$json->icons->edit}}"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 pl-1 pr-0">
                    <div class="input-group mb-2 mt-2 ap">
                        <div class="input-group-prepend border-right">
                            <span class="input-group-text border-right" title="{{$json->$hl->general->housenumber}}">No.</span>
                        </div>
                    
                        <input type="text" name="housenumber" class="form-control bg-white required" data-name="{{$json->$hl->general->housenumber}}" value="{{$userData->housenumber}}" readonly/>
            
                        <div class="input-group-append">
                            <input type="hidden" name="checked" class="required" value="checked"/>
                            <a class="input-group-text input-trigger bg-main hover-main btn btn-primary"><i class="{{$json->icons->edit}}"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-7 pl-0 pr-1">
                    <div class="input-group mb-2 ap">
                        <div class="input-group-prepend border-right">
                            <span class="input-group-text border-right">City</span>
                        </div>
                    
                        <input type="text" name="city" class="form-control bg-white required" data-name="City" value="{{$userData->city}}" readonly/>
            
                        <div class="input-group-append">
                            <input type="hidden" name="checked" class="required" value="checked"/>
                            <a class="input-group-text input-trigger bg-main hover-main btn btn-primary"><i class="{{$json->icons->edit}}"></i></a>
                        </div>
                    </div>
                </div>

                <div class="col-md-5 mb-4 pl-1 pr-0">
                    <div class="input-group ap">
                        <div class="input-group-prepend border-right">
                            <span class="input-group-text border-right">Postcode</span>
                        </div>
                        
                        <input type="text" name="postcode" class="form-control bg-white required" data-name="Postcode" value="{{$userData->postcode}}" readonly/>
                
                        <div class="input-group-append">
                            <a class="input-group-text input-trigger bg-main hover-main btn btn-primary"><i class="{{$json->icons->edit}}"></i></a>
                        </div>
                    </div>
                </div>

                <input type="submit" value="{{$json->$hl->general->save}}" class="btn btn-primary bg-main disabled" disabled/>
            </div>
        </form>
    </div>
@endsection
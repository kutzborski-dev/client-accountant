@extends("layouts.settings")

@section("content")
    <div class="row mb-0 pb-0">
        <form action="{{route('settings')}}/bills" method="POST" class="col-md-12 validate">
            @csrf
            @method('PATCH')
                <div class="col-md-9 pl-0 pr-1">
                        <div class="input-group mb-2 mt-2 ap">
                            <div class="input-group-prepend border-right">
                                <span class="input-group-text border-right">Monthly goal</span>
                            </div>
                    
                                <input type="number" name="monthly_goal" data-name="Monthly goal" class="form-control required bg-white" id="monthly-goal" data-name="Automatic monthly goal" value="{{$settings->monthly_goal}}"/>
            
                            <div class="input-group-append br-tr br-br">
                                <span class="input-group-text">{{$settings->currency}}</span>
                            </div>
                        </div>
                </div>

                <input type="submit" value="{{$json->$hl->general->save}}" class="btn btn-primary bg-main disabled" disabled/>
        </form>
    </div>
@endsection
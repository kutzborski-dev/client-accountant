@extends("layouts.app")

@section("content")
<div class="row">
    <div class="col-md-12" id="main-right">
      <div class="row">
        <div class="box">
          <div class="box-header">
            <h4>{{$json->$hl->general->customers}}</h4>

            <a href="#" class="header-btn icon"><i class="{{$json->icons->add}}"></i> {{$json->$hl->accounting->new_customer}}</a>
            <span href="#" class="header-btn search icon"><input type="search" class="searchbar" name="q" placeholder="{{$json->$hl->general->search}}..." autocomplete="off"><i class="{{$json->icons->search}}"></i></span>
          </div>

          <div class="box-content">
            <table class="table">
              <thead class="bg-light">
                <tr>
                  <th scope="col">{{$json->$hl->accounting->customer_id}} <i class="{{$json->icons->sort}} sort rechnungnr"></i></th>
                  <th scope="col">{{$json->$hl->accounting->contact}} <i class="{{$json->icons->sort}} sort kunde"></i></th>
                  <th scope="col">{{$json->$hl->accounting->customer}} <i class="{{$json->icons->sort}} sort eintragsdatum"></i></th>
                  <th scope="col">{{$json->$hl->accounting->entry_date}} <i class="{{$json->icons->sort}} sort eintragsdatum"></i></th>
                  <th scope="col" class="nocol"></th>
                </tr>
              </thead>
              <tbody>
                  @if(count($customers) > 0)
                    @foreach($customers as $customer)
                        <tr>
                            <th scope="row">{{$customer->id}}</th>
                            <td>{{$customer->contact}}</td>
                            <td><a href="{{route('customers.show', $customer->id)}}">{{$customer->company}}</a></td>
                            <td>{{$customer->entry_date}}</td>
                            <td><a href="{{route('customers.edit', $customer->id)}}" class="icon"><i class="{{$json->icons->edit}}"></i></a> <a href="{{route('customers.destroy', $customer->id)}}" class="icon"><i class="{{$json->icons->trash}}"></i></a></td>
                        </tr>
                    @endforeach
                  @else
                    <tr>
                      <th scope="row" colspan="5" style="text-align: center;">{{$json->$hl->accounting->no_customers}}</th>
                    </tr>
                  @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
@extends("layouts/app")

@section("content")
    <div class="row">
      <div class="col-md-5" id="main-left">
        <div class="row circles">
          <div class="col-sm">
          <div class="counter circle ldBar label-center" data-value="{{$customers->percentage}}" data-preset="circle" data-stroke="{{$customers->colour}}" data-text="<h4>{{$customers->count}}/25</h4>{{$json->$hl->general->customers}}" style="height: 100%; width: 100%; margin: auto;"></div>
          </div>

          <div class="col-sm">
          <div class="counter circle ldBar label-center" data-purpose="bills" data-value="{{$bills->percentage}}" data-preset="circle" data-stroke="{{$bills->colour}}" data-text="<h4>{{$bills->count}}/50</h4>{{$json->$hl->accounting->bills}}" style="height: 100%; width: 100%; margin: auto;"></div>
          </div>

          <div class="col-sm">
          <div class="counter circle ldBar label-center" data-purpose="offers" data-value="{{$customers->percentage}}" data-preset="circle" data-stroke="{{$offers->colour}}" data-text="<h4>{{$offers->count}}/50</h4>{{$json->$hl->accounting->offers}}" style="height: 100%; width: 100%; margin: auto;"></div>
          </div>
        </div>

        <div class="row">
          <div class="box box-text">
            <div class="box-header">
              <h4>{{$json->$hl->accounting->offers}}</h4>

              <a href="javascript: void(0);" class="dd-arrow header-btn" onclick="ddHide(this)"><i class="fas fa-caret-down"></i></a>
            </div>

            <div class="box-content">
              <div class="row border-bottom">
                <div class="col-2">
                  <i class="fas fa-file-invoice invoice-icon"></i>
                </div>

                <div class="col-10">
                  Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.

                  <br/>
                  <br/>

                <a href="#" class="btn {{$bills->colourClass}} btn-fluid"><span class="col-9">+10 {{$json->$hl->accounting->bills}}</span> <span class="col-3">@if($hl === 'en'){{$json->$hl->accounting->currency}}@endif{{"5.00"}}@if($hl === 'de'){{$json->$hl->accounting->currency}}@endif</span></a>
                </div>
              </div>

              <div class="row border-bottom">
                <div class="col-2">
                  <i class="fas fa-users customers-icon"></i>
                </div>

                <div class="col-10">
                  Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.

                  <br/>
                  <br/>

                  <a href="#" class="btn {{$customers->colourClass}} btn-fluid"><span class="col-9">+10 {{$json->$hl->general->customers}}</span> <span class="col-3">@if($hl === 'en'){{$json->$hl->accounting->currency}}@endif{{"5.00"}}@if($hl === 'de'){{$json->$hl->accounting->currency}}@endif</span></a>
                </div>
              </div>

              <div class="row border-bottom">
                <div class="col-2">
                  <i class="fas fa-file-alt invoice-icon"></i>
                </div>

                <div class="col-10">
                  Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore.

                  <br/>
                  <br/>

                <a href="#" class="btn {{$offers->colourClass}} btn-fluid"><span class="col-9">+10 {{$json->$hl->accounting->offers}}</span> <span class="col-3">@if($hl === 'en'){{$json->$hl->accounting->currency}}@endif{{"5.00"}}@if($hl === 'de'){{$json->$hl->accounting->currency}}@endif</span></a>
                </div>
              </div>

              <div class="row row-link">
                <div class="col-12 text-center">
                  <a href="#">{{$json->$hl->accounting->more_offers}}</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!--<div class="col-md-1"></div>-->

      <div class="col-md-7" id="main-right">
        <div class="row">
          <div class="box">
            <div class="box-header">
              <h4>{{$json->$hl->accounting->bills}}</h4>
            <a href="{{route('bills.index')}}" class="header-btn">{{$json->$hl->accounting->all_bills}}</a>
            </div>

            <div class="box-content">
              <table class="table">
                <thead class="bg-light">
                  <tr>
                    <th scope="col">{{$json->$hl->accounting->billing_id}}</th>
                    <th scope="col">{{$json->$hl->accounting->customer}}</th>
                    <th scope="col">{{$json->$hl->accounting->entry_date}}</th>
                    <th scope="col" class="nocol"></th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($bills) > 0)
                    @foreach($bills as $bill)
                        <tr>
                            <th scope="row">{{$bill->bill_id}}</th>
                            <td><a href="{{route('bills.show', $bill->id)}}">{{$bill->customer}}</a></td>
                            <td>{{$bill->entry_date}}</td>
                            <td><a href="{{route('bills.edit', $bill->id)}}" class="icon"><i class="far fa-edit"></i></a> <a href="{{route('bills.destroy', $bill->id)}}" class="icon"><i class="far fa-trash-alt"></i></a></td>
                        </tr>
                    @endforeach
                  @else
                    <tr>
                      <th scope="row" colspan="5" style="text-align: center;">{{$json->$hl->accounting->no_bills}}</th>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="box">
            <div class="box-header">
            <h4>{{$json->$hl->general->customers}}</h4>
              <a href="{{route('customers.index')}}" class="header-btn">{{$json->$hl->accounting->all_customers}}</a>
            </div>

            <div class="box-content">
              <table class="table">
                <thead class="bg-light">
                  <tr>
                    <th scope="col">{{$json->$hl->accounting->customer_id}}</th>
                    <th scope="col">{{$json->$hl->accounting->contact}}</th>
                    <th scope="col">{{$json->$hl->accounting->customer}}</th>
                    <th scope="col">{{$json->$hl->accounting->entry_date}}</th>
                    <th class="nocol"></th>
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
                            <td><a href="{{route('customers.edit', $customer->id)}}" class="icon"><i class="far fa-edit"></i></a> <a href="{{route('customers.destroy', $customer->id)}}" class="icon"><i class="far fa-trash-alt"></i></a></td>
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

        <div class="row">
          <div class="box">
            <div class="box-header">
            <h4>{{$json->$hl->accounting->offers}}</h4>
            <a href="{{route('offers.index')}}" class="header-btn">{{$json->$hl->accounting->all_offers}}</a>
            </div>

            <div class="box-content">
              <table class="table">
                <thead class="bg-light">
                  <tr>
                    <th scope="col">{{$json->$hl->accounting->offer_title}}</th>
                    <th scope="col">{{$json->$hl->accounting->customer}}</th>
                    <th scope="col">{{$json->$hl->accounting->entry_date}}</th>
                    <th scope="col" class="nocol"></th>
                  </tr>
                </thead>
                <tbody>
                    @if(count($offers) > 0)
                    @foreach($offers as $offer)
                    <tr>
                      <th scope="row">{{$offer->title}}</th>
                      <td><a href="{{route('customers.show', $offer->customer_id)}}">{{$offer->customer}}</a></td>
                      <td>{{$offer->entry_date}}</td>
                      <td><a href="#" class="icon"><i class="far fa-file-pdf"></i></a> <a href="{{route('offers.edit', $offer->id)}}" class="icon"><i class="far fa-edit"></i></a> <a href="{{route('offers.destroy', $offer->id)}}" class="icon"><i class="far fa-trash-alt"></i></a></td>
                    </tr>
                    @endforeach
                  @else
                  <tr>
                    <th scope="row" colspan="5" style="text-align: center;">{{$json->$hl->accounting->no_offers}}</th>
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
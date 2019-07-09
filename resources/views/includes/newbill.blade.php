<div class="col-md-6 add-bill-form">
    <div class="box mb-0">
      <div class="box-header">
        <h4>{{$json->$hl->accounting->new_bill}}</h4>
    
        <a href="javascript: void(0);" class="header-btn close-bill"><i class="{{$json->icons->close}}"></i> {{$json->$hl->general->close}}</a>
      </div>
    
      <div class="box-content form-group">
      <form action="{{route('bills.store')}}" class="validate" method="post">
        @csrf
        @include('includes.errors')
    
        <div class="input-group mb-2">
            <div class="input-group-prepend border-right">
            <div class="input-group-text">{{$json->$hl->accounting->billing_id}}</div>
            </div>
    
            <input type="number" name="bid" class="form-control bid required" data-name="Bill ID" value="{{$bills->highestId}}" placeholder="{{$json->$hl->accounting->eg_id}}"/>
        </div>
    
        <div class="input-group mb-2 ig-1">
          <div class="input-group input-group-header">
              <div class="input-group-header-content">
          <div class="input-group-prepend">
            <div class="input-group-text">{{$json->$hl->accounting->customer}}</div>
          </div>
    
          <span class="jssearch-selected"></span>
        </div>
    
          <a href="javascript: void(0);" class="jssearch-change float-right">{{$json->$hl->general->change}}</a>
          <a href="javascript: void(0);" class="jssearch-select float-right">{{$json->$hl->general->select}}</a>
    
          <div class="input-group-search float-right">
              <input type="text" class="searchbar jssearch" placeholder="{{$json->$hl->general->search}}..." name="q2"/> <i class="{{$json->icons->search}}"></i>
              </div>
        </div>
    
          <div class="input-group-content">
        <select multiple name="cid" class="form-control br-0 slist required" data-name="Customer" size="10"@if(count($bills->customers) < 1){{"disabled"}}@endif>
          @if(count($bills->customers) > 0)
            @foreach($bills->customers as $customer)
        <option value="{{$customer->id}}">{{$customer->company}}</option>
            @endforeach
          @else
        <option>{{$json->$hl->accounting->no_customers}}</option>
          @endif
        </select>
      </div>
      
      <div class="input-group-footer">
      <input type="button" class="form-control btn-secondary disabled selBtn" value="{{$json->$hl->general->select}}" disabled/>
      </div>
      </div>
    
      <table class="table product-table">
        <thead class="bg-light">
          <tr>
            <th scope="col">{{$json->$hl->accounting->product_id}}</th>
            <th scope="col">{{$json->$hl->accounting->product}}</th>
            <th scope="col">{{$json->$hl->accounting->price}}</th>
            <th scope="col">{{$json->$hl->accounting->vat}}</th>
            <th class="nocol table-btn"><a href="javascript: void(0);" class="add-product"><i class="{{$json->icons->add}}"></i> {{$json->$hl->accounting->add}}</a></th>
          </tr>
        </thead>
        <tbody class="table-body">
        </tbody>
        <tr>
          <th scope="col">Total:</th>
          <td></td>
          <th scope="col" class="price-total">@if($hl === 'en'){{$json->$hl->accounting->currency}}@endif<span>{{"0.00"}}</span>@if($hl === 'de'){{$json->$hl->accounting->currency}}@endif</th>
          <th scope="col" class="vat-total">@if($hl === 'en'){{$json->$hl->accounting->currency}}@endif<span>{{"0.00"}}</span>@if($hl === 'de'){{$json->$hl->accounting->currency}}@endif</th>
          <th scope="col" class="bill-total">@if($hl === 'en'){{$json->$hl->accounting->currency}}@endif<span>{{"0.00"}}</span>@if($hl === 'de'){{$json->$hl->accounting->currency}}@endif</td>
        </tr>
      </table>
    
        <input type="submit" class="form-control sbm btn-primary" value="{{$json->$hl->accounting->add}} {{$json->$hl->accounting->bill}}"/>
        </form>
      </div>
      </div>
    </div>
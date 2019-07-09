@extends("layouts.app")

@section("content")
@include('includes.newbill')
@include('includes.bill')

<div class="col-md-12" id="main-right">
  <div class="row">
    <div class="col-md-12">
        <div class="row circles">
          <div class="col-sm">
            <div class="counter circle ldBar label-center" data-value="{{$bills->percentage}}" data-preset="circle" data-stroke="{{$bills->colour}}" data-text="<h4>{{$bills->count}}/50</h4>{{$json->$hl->accounting->bills}}" style="height: 100%; width: 100%; margin: auto;"></div>
          </div>
      
          <div class="col-sm">
            <div class="counter circle ldBar label-center" data-value="{{$bills->monthly_percentage}}" data-preset="circle" data-stroke="{{$bills->monthly_colour}}" data-text="<h4>{{$bills->total_price}}</h4>Income" style="height: 100%; width: 100%; margin: auto;"></div>
          </div>
      
          <div class="col-sm">
            <div class="counter circle ldBar label-center" data-value="{{$bills->monthly_percentage}}" data-preset="circle" data-stroke="{{$bills->monthly_colour}}" data-text="<h4>{{$bills->vat}}</h4>VAT" style="height: 100%; width: 100%; margin: auto;"></div>
          </div>
      
          <div class="col-sm">
          <div class="counter circle ldBar label-center" data-value="{{$bills->monthly_percentage}}" data-preset="circle" data-stroke="{{$bills->monthly_colour}}" data-text="<h4>{{$bills->price}}</h4>Net income" style="height: 100%; width: 100%; margin: auto;"></div>
          </div>
      
          <div class="col-sm">
            <div class="counter circle ldBar label-center" data-value="{{$bills->monthly_percentage}}" data-preset="circle" data-stroke="{{$bills->monthly_colour}}" data-text="<h4>{{$bills->avg_price}}</h4>Net average" style="height: 100%; width: 100%; margin: auto;" title="Average net income per bill"></div>
          </div>
      
          @if($bills->monthly)
          <div class="col-sm">
            <div class="box box-sm box-fill">
              <div class="row" style="height: 37%">
                <h6>Monthly goal</h6>
                {{$bills->monthly_value}}
              </div>
      
              <div class="row pt-2" style="height: 43%">
                <h6>Income till goal</h6>
                {{$bills->monthly_togo}}
              </div>
      
              <div class="row pt-2" style="height: 20%">
                <small>Days left: {{$bills->monthly_days_left}}</small>
              </div>
            </div>
          </div>
          @endif
        </div>
      </div>
    </div>

  <div class="row">
    <div class="box">
      <div class="box-header">
        <h4>{{$json->$hl->accounting->bills}}</h4>

      <a href="javascript: void(0);" class="header-btn add-bill icon"><i class="{{$json->icons->add}}"></i> {{$json->$hl->accounting->new_bill}}</a>
        <span href="#" class="header-btn search icon"><input type="search" class="searchbar" name="q" placeholder="{{$json->$hl->general->search}}..." autocomplete="off"><i class="fas fa-search"></i></span>
      </div>

      <div class="box-content">
        @include('includes.success')

        <table class="table">
          <thead class="bg-light">
            <tr>
              <th class="sortable" scope="col" sort="bill_id">{{$json->$hl->accounting->billing_id}} <i class="{{$json->icons->sort}} sort"></i></th>
              <th scope="col" sort="customer">{{$json->$hl->accounting->customer}}</th>
              <th class="sortable" scope="col" sort="entry_date">{{$json->$hl->accounting->entry_date}} <i class="{{$json->icons->sort}}"></i></th>
              <th scope="col" class="nocol"></th>
            </tr>
          </thead>
          <tbody class="bills">
              @if(count($bills) > 0)
                @foreach($bills as $bill)
                <tr data-row="{{$bill->id}}">
                  <th scope="row">{{$bill->bill_id}}</th>
                  <td>{{$bill->customer}}</td>
                  <td>{{$bill->entry_date}}</td>
                  <td><a href="{{route('bill_pdf', $bill->id)}}" class="icon"><i id="bill-pdf-generator" class="pdf-generator {{$json->icons->pdf}}"></i></a> <a href="javascript: void(0);" class="icon edit-bill"><i class="{{$json->icons->edit}}"></i></a> <a href="#" class="icon"><i class="{{$json->icons->cancel}}"></i></a> <a href="{{route('bills.destroy', $bill->id)}}" class="icon"><i class="{{$json->icons->trash}}"></i></a></td>
                </tr>
                @endforeach
              @else
              <tr>
                <th scope="row" colspan="5" style="text-align: center;">{{$json->$hl->accounting->no_bills}}</th>
              </tr>
              @endif
          </tbody>
        </table>

        <form action="{{route('bills.index')}}" method="GET" class="no-validation nav-pagination text-center mt-4 mb-4">
          <input type="hidden" class="pagi-page" name="page" value="2"/>
          @if($bills->count > $defaultPagiLen && $bills->curPageCount == $defaultPagiLen)<input type="submit" class="btn btn-primary bg-main hover-main btn-m" value="Load more"/>@endif
        </form>
        
        <!--<nav aria-label="Page navigation">
          <ul class="pagination">
            {{-- $bills->links() --}}
          </ul>
        </nav>-->
      </div>
    </div>
  </div>
</div>
@endsection
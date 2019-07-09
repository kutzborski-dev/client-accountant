@extends("layouts.app")

@section("content")
<div class="col-md-12" id="main-right">
  <div class="row">
    <div class="box">
      <div class="box-header">
        <h4>{{$json->$hl->accounting->offers}}</h4>

      <a href="#" class="header-btn icon"><i class="far fa-file-alt"></i> {{$json->$hl->accounting->new_offer}}</a>
        <span href="#" class="header-btn search icon"><input type="search" class="searchbar" name="q" placeholder="{{$json->$hl->general->search}}..." autocomplete="off"><i class="fas fa-search"></i></span>
      </div>

      <div class="box-content">
        <table class="table">
          <thead class="bg-light">
            <tr>
              <th scope="col">{{$json->$hl->accounting->offer_title}} <i class="fas fa-sort sort rechnungnr" onclick="sortTable(this)"></i></th>
              <th scope="col">{{$json->$hl->accounting->customer}} <i class="fas fa-sort sort kunde"></i></th>
              <th scope="col">{{$json->$hl->accounting->entry_date}} <i class="fas fa-sort sort eintragsdatum"></i></th>
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
<html>
<head>
    <title>{{config('app.name')}}</title>
    <link href="{{URL::asset('resources/assets/css/style.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="{{URL::asset('resources/assets/js/jquery.js')}}"></script>
    
    <style type="text/css">
        body, html, main
        {
            background: #fff !important;
            font-size: 10pt;
        }

        h3
        {
            font-size: 16pt !important;
        }

        table
        {
            font-size: 10pt !important;
        }
    </style>
    </head>
    <body>
        <main>
            <div class="mt-5">
                <div class="col-md-12">
                    <div class="row mb-3">
                        <div class="col-md-12 logo">
                            <img src="{{$userData->logo}}"/>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>{{$bill->customer}}</strong>
                            <br/>
                            Gravesend, DA12 4TY
                            <br/>
                            Rochester Road 188
                        </div>

                        <div class="col-md-6 text-right">
                            <strong>Bill ID: </strong>{{$bill->bill_id}}
                            <br/>
                            <strong>Bill date: </strong>{{$bill->date}}
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <h3>Billing reminder</h3>
                        </div>
                    </div>

                    <div class="row @if($bill->pre_text != ''){{"mb-4"}}@endif">
                        <div class="col-md-12">
                            <p>{{$bill->pre_text}}</p>
                        </div>
                    </div>

                    <div class="row @if($bill->suf_text != ''){{"mb-4"}}@endif">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr scope="row">
                                        <th class="nocol"><strong>Product ID</strong></th>
                                        <th class="nocol"><strong>Product</strong></th>
                                        <th class="nocol"><strong>Price</strong></th>
                                        <th class="nocol"><strong>VAT</strong></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <th scope="col"><strong>{{$product->product_id}}</strong></th>
                                        <td>{{$product->product_name}}</td>
                                        <td>{{$product->product_price}}</td>
                                        <td>{{$product->product_vat}}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <th scope="col"><strong>{{$bill->totalPrice}}</strong></th>
                                        <th scope="col"><strong>{{$bill->totalVat}}</strong></th>
                                    </tr>
                                    <tr class="bt-2">
                                        <th scope="col"><strong>Total:</strong></th>
                                        <td></td>
                                        <th scope="col"><strong>{{$bill->total}}</strong></th>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <p>{{$bill->suf_text}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </body>
</html>
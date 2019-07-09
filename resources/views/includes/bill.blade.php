            <div class="mt-5 box pdf-box" id="bill-pdf">
                <div class="box-header">
                    <div class="row">
                        <div class="col-md-2 pdf-navigation">
                            <a href="javascript: void(0);" class="header-btn float-left prev-pdf"><i class="{{$json->icons->langle}}"></i></a>
                            <a href="javascript: void(0);" class="header-btn float-left next-pdf"><i class="{{$json->icons->rangle}}"></i></a>
                        </div>

                        <div class="col-md-8 text-center">
                            <i class="fas fa-spinner fa-pulse loading"></i> <h4 data-text="Bill PDF preview">Bill PDF preview</h4>
                        </div>

                        <div class="col-md-2">
                            <a href="javascript: void(0);" class="header-btn close-elem" onclick="closeElem('#bill-pdf')"><i class="{{$json->icons->close}}"></i> Close</a>
                        </div>
                    </div>
                </div>

                <div class="box-content col-md-12 mt-3">
                    <div class="row mb-0">
                        <div class="col-md-11 logo">
                            <img src="{{$userData->logo}}"/>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 float-left">
                            <h6 class="mb-1" id="bill-pdf-comp">Company</h6>
                            <br/>
                            <span id="bill-pdf-comp-addr">Gravesend, DA12 4TY</span>
                            <br/>
                            <span id="bill-pdf-comp-str">Rochester Road 188</span>
                        </div>

                        <div class="col-md-6 text-right float-left">
                            <h6 class="mb-0">Bill ID: </h6> <span id="bill-pdf-bid"></span>
                            <br/>
                            <h6>Bill date: </h6> <span id="bill-pdf-bdate"></span>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-md-12">
                            <h3>Billing reminder</h3>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-12">
                            <textarea class="form-control" id="bill-pdf-pre-text" placeholder="Billing text before products"></textarea>
                        </div>
                    </div>

                    <div class="row mb-0">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr scope="row">
                                        <th class="nocol">Product ID</th>
                                        <th class="nocol">Product</th>
                                        <th class="nocol">Price</th>
                                        <th class="nocol">VAT</th>
                                    </tr>
                                </thead>
                                <tbody id="bill-pdf-products">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <div class="col-md-12">
                            <textarea class="form-control" id="bill-pdf-suf-text" placeholder="Billing text after products"></textarea>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <button id="bill-pdf-download" class="pdf-download btn btn-primary bg-main hover-main"><i class="fas fa-spinner fa-pulse loading"></i> <span>Download PDF</span></button>
                        </div>
                    </div>
                </div>
            </div>
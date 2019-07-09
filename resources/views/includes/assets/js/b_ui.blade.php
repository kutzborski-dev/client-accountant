@if(Route::current()->getName() == 'bills.index')
<script type="text/javascript">
$(document).ready(function()
        {
            function initBodyClose(elem)
            {
                var click = false;
                var stop = false;

                $(elem).on('mouseover, mouseenter', function()
                {
                    if(click)
                    {
                        click = false;
                    }
                });

                $(elem).on('mouseleave', function()
                {
                    if(!click)
                    {
                        click = true;
                    }
                });

                $("body").on('click', function()
                {
                    if(click && !stop)
                    {
                        $(elem).hide();
                        $(".filter").hide();

                        $("body").css({"overflow-y" : "auto"});
                        stop = true;
                    }
                });

                $(".close-elem").on('click', function()
                {
                    stop = true;
                });
            }

            function getData(url, objects = null, callback = false)
            {
                var retData = null;

                $.ajax({
                    url: url,
                    dataType: 'JSON',
                    data: objects,
                    async: false,
                    success: function(data)
                    {
                        if(!callback)
                        {
                            retData = data;
                        }
                        else
                        {
                            retData = callback.apply({data});
                        }
                    }
                });

                return retData;
            }

            function downloadFile(url, filename)
            {
                var loader = this.loader;

                $.ajax({
                    type: "GET",
                    url: url,
                    xhrFields: {
                        responseType: "blob"
                    },
                    cache: false,
                    success: function(data)
                    {
                        if(window.navigator.msSaveBlob)
                        {
                            window.navigator.msSaveBlob(data, filename);
                            return false;
                        }

                        var link = document.createElement('a');
                        link.href = window.URL.createObjectURL(data);
                        link.download = filename;

                        document.body.appendChild(link);

                        link.click();

                        document.body.removeChild(link);

                        $(".loading").hide();
                        $(".pdf-box").hide();
                        $(".filter").hide();
                        $(".pdf-box h4").text($(".pdf-box h4").attr("data-text"));
                        $(".pdf-download").removeClass("disabled").removeAttr("disabled").children("span").text("Download PDF");
                        $(".pdf-box textarea").val("");

                        $("body").css({"overflow-y" : "auto"});
                    }
                });
            }

            var robjects = {};
            var sort_by = false;
            var sort_dir = false;
            var sorted = false;

            function initBills()
            {
                let bidInput = $(".add-bill-form .bid");
                let customerSelect = $(".add-bill-form .jssearch-select");
                let customerList = $(".add-bill-form .slist");
                var selBtn = $(".add-bill-form .selBtn");
                let productTable = $(".add-bill-form .product-table .table-body");
                var priceTotal = $(".add-bill-form .product-table .price-total span");
                var vatTotal = $(".add-bill-form .product-table .vat-total span");
                var billTotal = $(".add-bill-form .product-table .bill-total span");
                var tRow = $(".add-bill-form .product-table tbody:last-of-type");
                var vats = JSON.parse("{{$vats}}".replace(/&quot;/g, '"'));

                $(".pdf-generator#bill-pdf-generator").click(function(e)
                {
                    e.preventDefault();

                    $("body").css({"overflow-y" : "hidden"});

                    if(sort_dir === 'asc')
                    {
                        var prev_bill = $(this).parent().parent().parent().next();
                        var next_bill = $(this).parent().parent().parent().prev();
                    }
                    else
                    {
                        var prev_bill = $(this).parent().parent().parent().prev();
                        var next_bill = $(this).parent().parent().parent().next();
                    }

                    if(typeof prev_bill.attr("data-row") === "undefined")
                    {
                        $("#bill-pdf .next-pdf").addClass("disabled").attr("disabled", "true");
                    }
                    else
                    {
                        $("#bill-pdf .next-pdf").removeClass("disabled").removeAttr("disabled");
                    }

                    if(typeof next_bill.attr("data-row") === "undefined")
                    {
                        $("#bill-pdf .prev-pdf").addClass("disabled").attr("disabled", "true");
                    }
                    else
                    {
                        $("#bill-pdf .prev-pdf").removeClass("disabled").removeAttr("disabled");
                    }
                    
                    var bpdf_bid = $("#bill-pdf-bid");
                    var bpdf_bdate = $("#bill-pdf-bdate");
                    var bpdf_bcomp = $("#bill-pdf-comp");
                    var bpdf_baddr = $("#bill-pdf-comp-addr");
                    var bpdf_bstr = $("#bill-pdf-comp-str");
                    var bpdf_bproducts = $("#bill-pdf-products");
                    var bpdf_download = $("#bill-pdf-download");
                    var bpdf_ptext = $("#bill-pdf-pre-text");
                    var bpdf_stext = $("#bill-pdf-suf-text");

                    var url = $(this).parent().attr("href");
                    var bid = url.split("/");
                    bid = bid[bid.length - 1];
                    var bdate = null;
                    var bcompany = null;
                    var bproducts = null;
                    var products = null;

                    var route = "{{route('bills.show', 'x')}}".replace('x', bid);

                    var data = getData(route, {"type": "GET"});
                    bdate = data.entry_date.split(" ")[0];

                    bpdf_bid.text(data.bill_id);
                    bpdf_bdate.text(bdate);
                    bpdf_bcomp.text(data.customer);

                    if(data.products.length > 0)
                    {
                        for(product of data.products)
                        {
                            products += '<tr>\
                            <th scope="col">' + product.product_id + '</th>\
                            <td>' + product.product_name + '</td>\
                            <td>{{$json->$hl->accounting->currency}}' + product.product_price.toFixed(2) + '</td>\
                            <td>{{$json->$hl->accounting->currency}}' + (product.product_price * (product.vat_value / 100)).toFixed(2) + '</td>\
                            </tr>';
                        }

                        products += '<tr>\
                        <td></td>\
                        <td></td>\
                        <th scope="col">{{$json->$hl->accounting->currency}}' + data.totalPrice.toFixed(2) + '</th>\
                        <th scope="col">{{$json->$hl->accounting->currency}}' + data.totalVat.toFixed(2) + '</th>\
                        </tr>\
                        <tr class="bt-2">\
                        <th scope="col">Total:</th>\
                        <td></td>\
                        <th scope="col">{{$json->$hl->accounting->currency}}' + data.total.toFixed(2) + '</th>\
                        <td></td>\
                        </tr>';
                    }

                    bpdf_bproducts.html(products);

                    bpdf_download.click(function()
                    {
                        $(this).addClass("disabled");
                        $(this).attr("disabled", "true");
                        $("span", this).text("Generating PDF...");
                        $(".loading").css({"display" : "inline-block"});
                        $(".pdf-box h4").text("Generating PDF...");
                        var new_url = url + "/?pre=" + bpdf_ptext.val() + "&suf=" + bpdf_stext.val();
                        var fname = "bill_" + bid + "_" + bdate + ".pdf";
                        var download = downloadFile(new_url, fname);
                    });

                    $(".filter").show();
                    $("#bill-pdf").attr("data-current", bid).show();

                    initBodyClose('.pdf-box');
                });

                $(".edit-bill").click(function()
                {
                    var bid = $(this).parent().parent().attr("data-row");

                    var eGet = "{{route('bills.show', 'x')}}";
                    eGet = eGet.replace("x", bid);

                    $.get(eGet, {type: "GET"}, function(data)
                    {
                        $(".jssearch-change").click(function()
                        {
                            if(data.customer != "" && data.customer != null && data.customer_id != "" && data.customer_id != null)
                            {
                                selBtn.removeAttr("disabled");
                                selBtn.removeClass("disabled");
                                selBtn.removeClass("btn-secondary");
                                selBtn.addClass("btn-primary");
                            }
                        });

                        var eUrl = "{{route('bills.update', 'x')}}";
                        eUrl = eUrl.replace('x', bid);

                        $(".add-bill-form .input-group-content").hide();
                        $(".add-bill-form .input-group-footer").hide();
                        $(".add-bill-form .input-group-search").hide();
                        $(".add-bill-form .jssearch-change").show();
                        $(".add-bill-form .jssearch-select").hide();

                        $(".add-bill-form .input-group-header").css({"border-radius" : "0.25rem", "border-bottom" : "1px solid #ced4da"});

                        $(".add-bill-form form").attr("action", eUrl);
                        $(".add-bill-form form").prepend('{{method_field("PATCH")}}');
                        $(".add-bill-form .sbm").val("{{$json->$hl->accounting->edit}} {{$json->$hl->accounting->bill}}");
                        $(".add-bill-form .box-header h4").html("{{$json->$hl->accounting->edit}} {{$json->$hl->accounting->bill}}");

                        bidInput.val(bid);
                        var tPrice = (Number(priceTotal.html()) > 0 ? Number(priceTotal.html()) : 0.00);
                        var tVat = (Number(vatTotal.html()) > 0 ? Number(vatTotal.html()) : 0.00);

                        for(product of data.products)
                        {
                            var vSelect = '<select class="form-control vlist" name="vat[]" style="display: none;">';
                            vSelect += '<option value="0" data-value="0">None</option>';
                            vSelect += (product.vat_name != null ? '<option selected value="' + product.vat_id + '" data-value="' + product.vat_value + '">' + product.vat_name + ' (' + product.vat_value + '%)</option>' : '');
                            
                            for(vat of vats)
                            {
                                if(vat.id != product.vat_id)
                                {
                                    vSelect += '<option value="' + vat.id + '" data-value="' + vat.vat_value + '">' + vat.vat_name + ' (' + vat.vat_value + '%)</option>';
                                }
                            }

                            vSelect += '</select>';

                            var abody = '<tr>\
                            <th scope="row"><span>' + product.product_id + '</span><input class="form-control" type="hidden" name="pid[]" placeholder="{{$json->$hl->accounting->eg_id}}" value="' + product.product_id + '"/><input type="hidden" name="prid[]" value="' + product.id + '"/></th>\
                            <td><span>' + product.product_name + '</span><input class="form-control" type="hidden" name="product[]" value="' + product.product_name + '"/></td>\
                            <td class="price">'+ ("{{$hl}}" === "en" ? '<span class="currency">{{$json->$hl->accounting->currency}}</span>' : "") + '<span class="price-value">' + product.product_price.toFixed(2) + '</span>' + ("{{$hl}}" === "de" ? '<span class="currency">{{$json->$hl->accounting->currency}}</span>' : "") + '<input type="hidden" name="price[]" class="form-control" value="' + product.product_price.toFixed(2) + '"/></td>\
                            <td><span class="vat-text">' + (product.vat_name != null ? product.vat_name : "{{$json->$hl->general->none}}") + ' ' + (product.vat_value != null ? "(" + product.vat_value + "%) " : "") + ("{{$hl}}" === "en" && product.vat_name != null ? "{{$json->$hl->accounting->currency}}" : "") + (product.vat_name != null ? (product.product_price * (product.vat_value / 100)).toFixed(2) : "") + ("{{$hl}}" === "de" && product.vat_name != null ? "{{$json->$hl->accounting->currency}}" : "") + '</span>' + vSelect + '</td>\
                            <td class="actions"><a href="javascript: void(0);" class="edit-product"><i class="{{$json->icons->edit}}"></i></a> <a href="javascript: void(0);" class="ac-product" style="display: none;"><i class="{{$json->icons->checked}}"></i></a> <a href="javascript: void(0);" class="cancel-product"><i class="{{$json->icons->close}}"></i></a></td>\
                            </tr>';

                            tPrice += product.product_price;
                            tVat += product.product_price * (product.vat_value / 100);

                            productTable.append(abody);

                            $(".cancel-product", productTable).click(function()
                            {
                                $(this).parent().parent().remove();

                                var tPrice = (Number(priceTotal.html()) > 0 ? Number(priceTotal.html()) : tPrice);
                                var tVat = (Number(vatTotal.html()) > 0 ? Number(vatTotal.html()) : tVat);

                                tPrice -= product.product_price;
                                tVat -= product.product_price * (product.vat_value / 100);

                                priceTotal.html(tPrice.toFixed(2));
                                vatTotal.html(tVat.toFixed(2));
                                billTotal.html((tPrice + tVat).toFixed(2));
                            });

                            $(".edit-product", productTable).click(function()
                            {
                                tPrice = (Number(priceTotal.html()) > 0 ? Number(priceTotal.html()) : 0.00);
                                tVat = (Number(vatTotal.html()) > 0 ? Number(vatTotal.html()) : 0.00);

                                var eBtn = $(this);
                                eBtn.hide();
                                var ac_edit = false;
                                $(".ac-product", $(this).parent()).show();

                                var pRow = $(this).parent().parent();
                                var pid_input = $("th .form-control", pRow);
                                var pid = $("th span", pRow);
                                var product_input = $("input[name='product[]']", pRow);
                                var product = product_input.parent().children("span");
                                var price_input = $(".price .form-control", pRow);
                                var price_currency = $(".price .currency", pRow);
                                var price = $(".price .price-value", pRow);
                                var vat_select = $("select[name='vat[]']", pRow);
                                var vat = $(".vat-text", pRow);

                                tPrice -= Number(price_input.val());
                                tVat -= Number(price_input.val() * (vat_select.find(":selected").attr("data-value") / 100));

                                pid.hide();
                                pid_input.attr("type", "number");
                                product_input.attr("type", "text");
                                product.hide();
                                price_input.attr("type", "number");
                                price_currency.hide();
                                price.hide();
                                vat_select.show();
                                vat.hide();

                                $(".ac-product", productTable).click(function()
                                {
                                    if(ac_edit == false)
                                    {
                                        pid.html(pid_input.val()).show();
                                        pid_input.attr("type", "hidden");
                                        product_input.attr("type", "hidden");
                                        product.html(product_input.val()).show();
                                        price_input.attr("type", "hidden");
                                        price_currency.show();
                                        price.html(price_input.val()).show();
                                        vat_select.hide();
                                        vat.html(vat_select.find(":selected").text() + ("{{$hl}}" === "en" ? " {{$json->$hl->accounting->currency}}" : " ") + Number(price_input.val() * (vat_select.find(":selected").attr("data-value") / 100)).toFixed(2) + ("{{$hl}}" === "de" ? "{{$json->$hl->accounting->currency}}" : "")).show();
                                        eBtn.show();
                                        $(this).hide();

                                        tPrice += Number(price_input.val());
                                        tVat += Number(price_input.val() * (vat_select.find(":selected").attr("data-value") / 100));

                                        priceTotal.html(tPrice.toFixed(2));
                                        vatTotal.html(tVat.toFixed(2));
                                        billTotal.html((tPrice + tVat).toFixed(2));
                                        ac_edit = true;
                                    }
                                });
                            });
                        }

                        priceTotal.html(tPrice.toFixed(2))
                        vatTotal.html(tVat.toFixed(2));
                        billTotal.html((tPrice + tVat).toFixed(2));

                        $(".add-bill-form .slist").val(data.customer_id);
                        $(".add-bill-form .jssearch-selected").html(data.customer);

                        $(".filter").show();
                        $(".add-bill-form").show();

                        $(".close-bill").click(function()
                        {
                            $(".filter").hide();
                            $(".add-bill-form").hide();
                            $(".add-bill-form .bid").val("{{$bills->highestId}}");
                            priceTotal.html((0.00).toFixed(2));
                            vatTotal.html((0.00).toFixed(2));
                            billTotal.html((0.00).toFixed(2));

                            productTable.html("");
                        });
                    });
                });
            }

            initBills();

            var triggered = false;
            var pre_bills = $(".bills tr");

            function applyBills(bills, add = true, retHtml = false)
            {
                var bill_html;

                if(typeof bills === 'object')
                {
                    if(bills != "" && typeof bills != null)
                    {
                        for(bill of bills)
                        {
                            var cRoute = "{{route('customers.show', 'x')}}";
                            cRoute.replace('x', bill.customer_id);
                            
                            var bpdfRoute = "{{route('bill_pdf', 'x')}}";
                            bpdfRoute = bpdfRoute.replace("x", bill.id);

                            var bDel = "{{route('bills.destroy', 'x')}}";
                            bDel = bDel.replace("x", bill.id);

                            bill_html += '<tr data-row="' + bill.id + '">\
                            <th>' + bill.bill_id + '</th>\
                            <td><a href="' + cRoute + '">' + (typeof bill.customer !== "undefined" ? bill.customer : bill.company) + '</a></td>\
                            <td>' + bill.entry_date + '</td>\
                            <td><a href="' + bpdfRoute + '" class="icon"><i id="bill-pdf-generator" class="pdf-generator {{$json->icons->pdf}}"></i></a> <a href="javascript: void(0);" class="icon edit-bill"><i class="{{$json->icons->edit}}"></i></a> <a href="#" class="icon"><i class="{{$json->icons->cancel}}"></i></a> <a href="' + bDel + '" class="icon"><i class="{{$json->icons->trash}}"></i></a></td>\
                            </tr>';
                        }
                    }
                    else
                    {
                        bill_html = '<th scope="row" colspan="5" style="text-align: center;">{{$json->$hl->accounting->no_bills}}</th>';
                    }

                    if(!retHtml)
                    {
                        if(add)
                        {
                            $(".bills").html($(".bills").html() + bill_html);
                        }
                        else
                        {
                            $(".bills").html(bill_html);
                        }

                        initBills(); 
                    }
                    else
                    {
                        return bill_html;
                    }
                }
                else
                {
                    $(".bills").html(bills);
                }
            }

            $(".pdf-box .pdf-navigation a").click(function()
            {
                var current_pdf = $(".pdf-box").attr("data-current");
                var next_pdf;

                if($(this).attr("class").indexOf("prev-pdf") !== -1)
                {
                    next_pdf = false;
                }
                else if($(this).attr("class").indexOf("next-pdf") !== -1)
                {
                    next_pdf = true;
                }

                if($("tr[data-row='" + current_pdf + "']").length > 0)
                {
                    switch(next_pdf)
                    {
                        case false:
                            next_pdf = (!sort_dir || sort_dir === 'desc' ? $("tr[data-row='" + current_pdf + "']").next() : $("tr[data-row='" + current_pdf + "']").prev());
                        break;

                        case true:
                            next_pdf = (!sort_dir || sort_dir === 'desc' ? $("tr[data-row='" + current_pdf + "']").prev() : $("tr[data-row='" + current_pdf + "']").next());
                        break;
                    }

                    $(".pdf-generator", next_pdf).click();
                }
            });

            function initPagi(opts)
            {
                if(typeof opts !== 'object')
                {
                    return false;
                }

                var forms = $(".nav-pagination");

                for(form of forms)
                {
                    form.onsubmit = function(e)
                    {
                        e.preventDefault();

                        var fData = new FormData(form);

                        var nextPage = false;

                        for(var entry of fData.entries())
                        {
                            nextPage = entry[1];
                        }

                        var url = opts.url.replace("x", nextPage);

                        var objects = null;
                        var q = $(".search .searchbar").val();
                        var t = ("{{Route::current()->getName()}}".indexOf("bills") != -1 ? "Bill" : ("{{Route::current()->getName()}}".indexOf("customers") != -1 ? "Customer" : "Offer"));

                        if(q != "" && q != null)
                        {
                            objects = {"q": q, t: t, "page": nextPage};
                            url = "{{route('search')}}";

                            if(sort_by && sort_dir)
                            {
                                objects.sort = sort_by;
                                objects.sort_dir = sort_dir;
                            }
                        }
                        else
                        {
                            if(sort_by && sort_dir)
                            {
                                objects = {"sort": sort_by, "sort_dir": sort_dir};
                            }
                        }

                        var data = getData(url, objects);

                        var currentPage = data.current_page;
                        var totalCount = data.total;
                        var perPage = data.per_page;
                        var lastPage = data.last_page;
                        var npURL = data.next_page_url;

                        if(currentPage === lastPage && npURL === null)
                        {
                            $(form).hide();
                        }

                        if(data.data.length > 0)
                        {
                            $(".pagi-page", form).val(currentPage + 1);
                            robjects["page"] = nextPage;

                            applyBills(data.data);
                        }
                    }
                }
            }

            initPagi({
                "url": "{{route('bills.index', 'page=x')}}"
            });

            function sort()
            {
                var sort = $(this).attr("sort");

                if(sort_by != sort)
                {
                    sorted = false;

                    if(sort_by !== false)
                    {
                        var sElem = $(".sortable[sort='" + sort_by + "']");
                        sElem.removeClass("sorted");
                    }

                    sort_dir = "asc";
                    sort_by = (sort_by !== sort ? sort : sort_by);

                    if(!sorted)
                    {
                        $(this).addClass("sorted");
                        sorted = true;
                    }
                    else
                    {
                        $(this).removeClass("sorted");
                        sorted = false;
                    }
                }
                else
                {
                    if(!sorted)
                    {
                        $(this).addClass("sorted");
                        sorted = true;
                    }
                    else
                    {
                        $(this).removeClass("sorted");
                        sorted = false;
                    }

                    sort_dir = (sort_dir === "desc" ? "asc" : "desc");
                }

                var objects = {};
                var url = "";

                if(Object.size(robjects) > 0)
                {
                    for(key in robjects)
                    {
                        objects[key] = robjects[key];
                    }
                }

                url = (typeof robjects.q !== "undefined" ? "{{route('search')}}" : "{{route('bills.index')}}");

                objects.sort = sort_by;
                objects.sort_dir = sort_dir;

                var data = null;
                var dataHtml = "";

                if(typeof objects.page !== 'undefined' && objects.page > 1)
                {
                    var pages = objects.page;

                    for(var i = 1; i <= pages; i++)
                    {
                        objects.page = i;

                        data = getData(url, objects);
                        dataHtml += applyBills(data.data, false, true);
                    }
                }
                else
                {
                    data = getData(url, objects);
                }

                if(dataHtml == "" || dataHtml == null)
                {
                    if(typeof objects.page !== 'undefined' && objects.page > 1)
                    {
                        applyBills(data.data);
                    }
                    else
                    {
                        applyBills(data.data, false);
                    }
                }
                else
                {
                    applyBills(dataHtml);
                }
            }

            var sTriggered = false;

            $(".search .searchbar").keyup(function()
            {
                var q = $(this).val();
                robjects["page"] = 1;
                $(".no-validation.nav-pagination .pagi-page").val(2);

                if(q != "" && q != null)
                {
                    sTriggered = true;

                    var t = ("{{Route::current()->getName()}}".indexOf("bills") != -1 ? "Bill" : ("{{Route::current()->getName()}}".indexOf("customers") != -1 ? "Customer" : "Offer"));
                    var sUrl = "{{route('search')}}";
                    var s = false;
                    var pagiLen = "{{$defaultPagiLen}}";

                    if(sort_by && sort_dir)
                    {
                        s = sort_by;
                        sdir = sort_dir;
                    }
                    else
                    {
                        s = false;
                        sdir = false;
                    }

                    robjects["q"] = q;
                    robjects["t"] = t;

                    $.get(sUrl, {q: q, t: t, sort: s, sort_dir: sdir}, function(data)
                    {
                        var page = data.current_page;
                        var lastPage = data.last_page;

                        if(data.data.length < pagiLen && page >= lastPage)
                        {
                            $(".no-validation.nav-pagination").hide();
                        }
                        else
                        {
                            $(".no-validation.nav-pagination").show();
                        }
                        
                        applyBills(data.data, false);
                    });
                }
                else
                {
                    sTriggered = false;
                    $(".no-validation.nav-pagination").show();

                    if(robjects.hasOwnProperty("q") && robjects.hasOwnProperty("t"))
                    {
                        delete robjects["q"];
                        delete robjects["t"];
                    }

                    if(!sort_by && !sort_dir || sort_dir === "desc")
                    {
                        $(".bills").html(pre_bills);
                    }
                    else
                    {
                        var url = "{{route('bills.index', 'page=1')}}";

                        data = getData(url, {sort: sort_by, sort_dir: sort_dir});

                        applyBills(data.data, false);
                    }
                }
            });

            if(!sTriggered)
            {
                $(".sortable").click(sort);
            }
        });

        window.onload = function()
            {
                var loadings = document.getElementsByClassName("ldBar");

                for(var i = 0; i < loadings.length; i++)
                {
                    if(loadings[i].hasAttribute("data-text"))
                    {
                        var label = loadings[i].getElementsByClassName("ldBar-label");

                        label[0].innerHTML = loadings[i].getAttribute("data-text");

                        label[0].className += " no-after";
                    }
                }

                var jsSearch = document.querySelectorAll('.jssearch');

                jsSearch.forEach(function($this, i)
                {
                    var parentElem = $this.parentElement.parentElement;
                    var selLink = parentElem.querySelector('.jssearch-select');
                    var chgLink = parentElem.querySelector('.jssearch-change');
                    var selected = parentElem.querySelector('.jssearch-selected');
                    var selBtn = parentElem.parentElement.querySelector('.input-group-footer .selBtn');
                    var slist = parentElem.parentElement.querySelector('.input-group-content .slist');
                    var search = $this.parentElement;
                    var header = parentElem;
                    var content = parentElem.parentElement.querySelector('.input-group-content');
                    var footer = parentElem.parentElement.querySelector('.input-group-footer');

                    var sel = false;

                    slist.onchange = function()
                    {
                        if(sel == false)
                        {
                            selBtn.disabled = false;
                            selBtn.className = selBtn.className.replace('btn-secondary', 'btn-primary');
                        }

                        sel = true;
                    }

                    chgLink.onclick = function()
                    {
                        content.style.display = "block";
                        header.style.borderBottom = "none";
                        header.style.borderRadius = ".25rem .25rem 0 0";
                        footer.style.display = "block";

                        search.style.display = "block";
                        this.style.display = "none";

                        selected.innerHTML = "";
                    }

                    selLink.onclick = function()
                    {
                        this.style.display = "none";

                        content.style.display = "block";
                        footer.style.display = "block";
                        header.style.borderBottom = "none";
                        header.style.borderRadius = ".25rem .25rem 0 0";
                        search.style.display = "block";
                    }

                    selBtn.onclick = function()
                    {
                        content.style.display = "none";
                        header.style.borderBottom = "1px solid #ced4da";
                        header.style.borderRadius = ".25rem";
                        footer.style.display = "none";

                        chgLink.style.display = "block";

                        search.style.display = "none";

                        this.style.display = "block";

                        selected.innerHTML = slist.options[slist.selectedIndex].text;
                    }

                    $this.onkeyup = function()
                    {
                        var input = this.value.toLowerCase();
                        var opts = slist.querySelectorAll('option');

                        for(var j = 0; j < opts.length; j++)
                        {
                            if(input == null || input == "")
                            {
                                opts[j].style.display = "block";
                            }
                            else
                            {
                                opts[j].style.display = "none";
                                var optText = opts[j].text.toLowerCase();

                                if(optText.indexOf(input) != -1)
                                {
                                    opts[j].style.display = "block";
                                }
                            }
                        }
                    }
                });

                var pTable = document.querySelector(".product-table");
                var addProduct = document.querySelector(".add-product");
                var priceTotal = document.querySelector(".price-total span");
                var vatTotal = document.querySelector(".vat-total span");
                var billTotal = document.querySelector(".bill-total span");
                var pTotal = 0.00;
                var vTotal = 0.00;
                var vats = JSON.parse("{{$vats}}".replace(/&quot;/g, '"'));

                addProduct.onclick = function()
                {
                    pTotal = (Number(priceTotal.innerHTML) > 0 ? Number(priceTotal.innerHTML) : 0.00);
                    vTotal = (Number(vatTotal.innerHTML) > 0 ? Number(vatTotal.innerHTML) : 0.00);

                    var tbody = pTable.querySelector("tbody");

                    var tr = document.createElement("tr");
                    var pid_th = document.createElement("th");
                    var product_td = document.createElement("td");
                    var price_td = document.createElement("td");
                    var vat_td = document.createElement("td");
                    var action_td = document.createElement("td");
                    var added = false;

                    /* PID TH */

                    var pid_th_input = document.createElement("input");
                    pid_th_input.className = "form-control";
                    pid_th_input.setAttribute("type", "number");
                    pid_th_input.setAttribute("name", "pid[]");
                    pid_th_input.setAttribute("placeholder", "{{$json->$hl->accounting->eg_id}}");

                    /* PRID TH */

                    var prid_th_input = document.createElement("input");
                    prid_th_input.className = "form-control";
                    prid_th_input.setAttribute("type", "hidden");
                    prid_th_input.setAttribute("name", "prid[]");
                    prid_th_input.value = 0;

                    pid_th.appendChild(pid_th_input);
                    pid_th.appendChild(prid_th_input);

                    /* Product TD */

                    var product_td_input = document.createElement("input");
                    product_td_input.setAttribute("type", "text");
                    product_td_input.setAttribute("name", "product[]");
                    product_td_input.className = "form-control";

                    product_td.appendChild(product_td_input);

                    /* Price TD */
                    
                    var price_td_input = document.createElement("input");
                    price_td_input.setAttribute("type", "number");
                    price_td_input.setAttribute("name", "price[]");
                    price_td_input.className = "form-control";

                    price_td.appendChild(price_td_input);
                    price_td.className = 'price';

                    /* Vat TD */

                    var vat_td_text = document.createElement("span");
                    vat_td_text.className = "vat-text";

                    var vat_td_select = document.createElement("select");
                    vat_td_select.className = "form-control vlist";
                    vat_td_select.setAttribute("name", "vat[]");

                    var vat_td_select_opt = document.createElement("option");
                    vat_td_select_opt.setAttribute("value", "0");
                    vat_td_select_opt.setAttribute("data-value", "0");
                    vat_td_select_opt.innerHTML = "{{$json->$hl->general->none}}";

                    vat_td_select.appendChild(vat_td_select_opt);

                    for(vat of vats)
                    {
                        vat_td_select_opt = document.createElement("option");
                        vat_td_select_opt.setAttribute("value", vat.id);
                        vat_td_select_opt.setAttribute("data-value", vat.vat_value);
                        vat_td_select_opt.innerHTML = vat.vat_name + " (" + vat.vat_value + "%)";

                        vat_td_select.appendChild(vat_td_select_opt);
                    }

                    vat_td.appendChild(vat_td_text);
                    vat_td.appendChild(vat_td_select);

                    /* Action TD */

                    var action_td_add = document.createElement("a");
                    action_td_add.setAttribute("href", "javascript: void(0);");
                    action_td_add.className = "ac-product";

                    var action_td_add_icon = document.createElement("i");
                    action_td_add_icon.className = "{{$json->icons->checked}}";

                    action_td_add.appendChild(action_td_add_icon);

                    var action_td_cancel = document.createElement("a");
                    action_td_cancel.setAttribute("href", "javascript: void(0);");
                    action_td_cancel.className = "cancel-product";

                    var action_td_cancel_icon = document.createElement("i");
                    action_td_cancel_icon.className = "{{$json->icons->close}}";

                    action_td_cancel.appendChild(action_td_cancel_icon);
                    
                    action_td.appendChild(action_td_add);
                    action_td.appendChild(action_td_cancel);
                    action_td.className = 'actions';

                    /* Append to tr and tbody*/

                    tr.appendChild(pid_th);
                    tr.appendChild(product_td);
                    tr.appendChild(price_td);
                    tr.appendChild(vat_td);
                    tr.appendChild(action_td);

                    tbody.appendChild(tr);

                    /* Remove the product row */

                    action_td_cancel.onclick = function()
                    {
                        tr.parentElement.removeChild(tr);

                        var price_val = (price_td.querySelector('.form-control').value != "" && price_td.querySelector('.form-control').value != null && !isNaN(price_td.querySelector('.form-control').value) ? price_td.querySelector('.form-control').value : 0);
                        
                        pTotal -= price_val;
                        priceTotal.innerHTML = pTotal.toFixed(2);

                        if(vat_td.querySelector("select").value != 0)
                        {
                            var vat_val = Number(vat_td.querySelector("select").options[vat_td.querySelector("select").selectedIndex].getAttribute("data-value") / 100);

                            vTotal -= Number(price_val * vat_val);
                            vatTotal.innerHTML = vTotal.toFixed(2).replace(/\-/g, "");
                        }

                        billTotal.innerHTML = (pTotal + vTotal).toFixed(2).replace(/\-/g, "");
                    }

                    /* Apply the product row */

                    action_td_add.onclick = function()
                    {
                        /* Check if all inputs are valid (not empty) */

                        if(pid_th_input.value != "" && pid_th_input.value != null && product_td_input.value != "" && product_td_input.value != null && price_td_input.value != "" && price_td_input.value != null)
                        {
                            /* Check if the price field contains only numbers */

                            if(!isNaN(price_td_input.value))
                            {
                                /* PID TH */
                                
                                var pid_val = pid_th.querySelector('.form-control').value;

                                pid_th.querySelector('.form-control').setAttribute('type', 'hidden');
                                pid_th.setAttribute('scope', 'row');

                                /* Product TD */

                                var product_val = product_td.querySelector('.form-control').value;

                                product_td.querySelector('.form-control').setAttribute('type', 'hidden');

                                /* Price TD */

                                var price_val = Number(price_td.querySelector('.form-control').value);

                                price_td.querySelector('.form-control').setAttribute('type', 'hidden');

                                /* Vat TD */

                                vat_val = Number(vat_td.querySelector("select").options[vat_td.querySelector("select").selectedIndex].getAttribute("data-value") / 100);

                                /* Adding the values to the HTML */

                                if(added == false)
                                {
                                    pid_th.innerHTML = '<span>' + pid_val + '</span>' + pid_th.innerHTML;
                                    product_td.innerHTML = '<span>' + product_val + '</span>' + product_td.innerHTML;
                                    price_td.innerHTML = ('{{$hl}}' === 'en' ? '<span class="currency">{{$json->$hl->accounting->currency}}</span><span class="price-value">' : '<span class="price-value">') + price_val.toFixed(2) + ('{{$hl}}' === 'de' ? '</span><span class="currency">{{$json->$hl->accounting->currency}}</span>' : '</span>') + price_td.innerHTML;
                                    vat_td.querySelector(".vat-text").innerHTML = vat_td.querySelector("select").options[vat_td.querySelector("select").selectedIndex].text + ("{{$hl}}" === "en" ? " {{$json->$hl->accounting->currency}}" : " ") + Number(price_val * vat_val).toFixed(2) + ("{{$hl}}" === "de" ? "{{$json->$hl->accounting->currency}}" : "");
                                }
                                else
                                {
                                    pid_th.querySelector('span').innerHTML = pid_val;
                                    product_td.querySelector('span').innerHTML = product_val;
                                    price_td.querySelector('.price-value').innerHTML = price_val.toFixed(2);
                                    vat_td.querySelector('.vat-text').innerHTML = vat_td.querySelector("select").options[vat_td.querySelector("select").selectedIndex].text + ("{{$hl}}" === "en" && vat_td.querySelector("select").value != 0 ? " {{$json->$hl->accounting->currency}}" : " ") + Number(price_val * vat_val).toFixed(2) + ("{{$hl}}" === "de"  && vat_td.querySelector("select").value != 0 ? "{{$json->$hl->accounting->currency}}" : "");

                                    pid_th.querySelector('span').style.display = "block";
                                    product_td.querySelector('span').style.display = "block";
                                    price_td.querySelector('.currency').style.display = "inline";
                                    price_td.querySelector('.price-value').style.display = "inline";
                                    vat_td.querySelector('.vat-text').style.display = "block";
                                }

                                vat_td.querySelector('select').style.display = "none";

                                if(vat_td.querySelector("select").value != 0)
                                {
                                    vTotal += Number(price_val * vat_val);
                                }

                                vatTotal.innerHTML = vTotal.toFixed(2);

                                pTotal += Number(price_val);
                                priceTotal.innerHTML = pTotal.toFixed(2);

                                billTotal.innerHTML = (pTotal + vTotal).toFixed(2);

                                /* Hide checkmark and display edit button */

                                var edit_btn = document.createElement("a");
                                edit_btn.setAttribute("href", "javascript: void(0);");
                                edit_btn.className = "edit-product";

                                var edit_btn_icon = document.createElement("i");
                                edit_btn_icon.className = "{{$json->icons->edit}}";

                                edit_btn.appendChild(edit_btn_icon);

                                this.parentElement.insertBefore(edit_btn, this);

                                this.style.display = "none";

                                added = true;

                                edit_btn.onclick = function()
                                {
                                    this.style.display = "none";
                                    action_td_add.style.display = "inline";

                                    pid_th.querySelector('span').style.display = "none";
                                    product_td.querySelector('span').style.display = "none";
                                    price_td.querySelector('.price-value').style.display = "none";
                                    price_td.querySelector('.currency').style.display = "none";
                                    vat_td.querySelector('.vat-text').style.display = "none";
                                    vat_td.querySelector('select').style.display = "block";

                                    pid_th.querySelector('.form-control').setAttribute("type", "number");
                                    product_td.querySelector('.form-control').setAttribute("type", "text");
                                    price_td.querySelector('.form-control').setAttribute("type", "number");

                                    pTotal -= Number(price_val);

                                    vTotal -= Number(price_val * vat_val);
                                }
                            }
                            else
                            {
                                alert("The price field can only contain numbers!");
                            }
                        }
                        else
                        {
                            alert("Please fill out everything");
                        }
                    }
                }

                const filter = document.querySelector(".filter");

                const aBill = document.querySelector(".add-bill");
                const cBill = document.querySelector(".close-bill");
                const billForm = document.querySelector('.add-bill-form');

                aBill.onclick = function()
                {
                    filter.style.display = "block";
                    billForm.style.display = "block";

                    document.querySelector(".add-bill-form form").setAttribute("action", "{{route('bills.index')}}");
                    document.querySelector(".add-bill-form .sbm").value = "{{$json->$hl->accounting->add}} {{$json->$hl->accounting->bill}}";
                    document.querySelector(".add-bill-form .box-header h4").innerHTML = "{{$json->$hl->accounting->new_bill}}";
                
                    document.querySelector(".add-bill-form .input-group-content").style.display = "none";
                    document.querySelector(".add-bill-form .input-group-footer").style.display = "none";
                    document.querySelector(".add-bill-form .input-group-search").style.display = "none";
                    document.querySelector(".add-bill-form .jssearch-change").style.display = "block";
                    document.querySelector(".add-bill-form .jssearch-select").style.display = "none";

                    document.querySelector(".add-bill-form .input-group-header").style.borderRadius = "0.25rem";
                    document.querySelector(".add-bill-form .input-group-header").style.borderBottom = "1px solid #ced4da";

                    document.querySelector(".jssearch-change").style.display = "none";
                    document.querySelector(".jssearch-select").style.display = "block";

                    if(document.querySelector(".add-bill-form form input[value='PATCH']"))
                    {
                        document.querySelector(".add-bill-form form").removeChild(document.querySelector(".add-bill-form form input[value='PATCH']"));
                    }
                }

                cBill.onclick = function()
                {
                    filter.style.display = "none";
                    billForm.style.display = "none";
                    billForm.querySelector(".bid");
                    billForm.querySelector(".product-table .table-body").innerHTML = "";
                    billForm.querySelector(".product-table .price-total span").innerHTML = (0.00).toFixed(2);
                    billForm.querySelector(".product-table .vat-total span").innerHTML = (0.00).toFixed(2);
                    billForm.querySelector(".product-table .bill-total span").innerHTML = (0.00).toFixed(2);
                    billForm.querySelector(".slist").value = "";
                    billForm.querySelector(".jssearch-selected").innerHTML = "";
                    billForm.querySelector(".bid").value = "{{$bills->highestId}}";
                }

                const alert_closes = document.querySelectorAll(".alert-close");

                for(alert_close of alert_closes)
                {
                    alert_close.onclick = function()
                    {
                        const alert = this.parentElement;

                        this.parentElement.parentElement.removeChild(alert);
                    }
                }
            }
</script>
@endif
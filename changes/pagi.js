/*var page = 1;

                        function initPagi(data, sUrl, requests)
                        {
                            page = 1;

                            var pagination = $("ul.pagination[role='navigation']");
                            var page_items = $("li.page-item");

                            var prev = page_items[0];
                            var next = page_items[page_items.length - 1];

                            for(var i = 0; i < page_items.length; i++)
                            {
                                if(i != 0 && i != page_items.length -1)
                                {
                                    $(page_items[i]).remove();
                                }
                                else
                                {
                                    $("a", page_items[i]).attr("href", "javascript: void(0)");
                                }
                            }

                            if(data.current_page > 1)
                            {
                                $(prev).removeClass("disabled");
                            }
                            else
                            {
                                if($(prev).attr("class").indexOf("disabled") == -1)
                                {
                                    $(prev).addClass("disabled");
                                }
                            }

                            if(page < data.last_page)
                            {
                                $(next).removeClass("disabled");
                            }
                            else
                            {
                                if($(next).attr("class").indexOf("disabled") == -1)
                                {
                                    $(next).addClass("disabled");
                                }
                            }

                            for(var i = data.last_page; i > 0; i--)
                            {
                                $(prev).after('<li class="page-item' + (i === data.current_page ? " active" : "") + '">' + (i === data.current_page ? '<span class="page-link">' + i + '</span>' : '<a class="page-link" href="javascript: void(0);" data-value="' + i + '">' + i + '</a>') + '</li>');
                            }

                            function nextPage(npElem, aElem)
                            {
                                if($("a", npElem).attr("data-value"))
                                {
                                    if($("a", npElem).attr("data-value") <= data.last_page)
                                    {
                                        var nextPage = $("a", npElem).attr("data-value");

                                        page = nextPage;

                                        aElem.removeClass("active").html('<a class="page-link" href="javascript:void(0);" data-value="' + $(aElem).text() + '">' + $(aElem).text() + '</a>');
                                        npElem.addClass("active").html('<span class="page-link">' + $(npElem).text() + '</span>');

                                        if(sorted.length > 0)
                                        {
                                            var soUrl = sortUrl + "/?page=" + page;

                                            $.get(soUrl, sRequests, function(data)
                                            {
                                                applyBills(data.data);
                                            });
                                        }
                                        else
                                        {
                                            $.get(sUrl + "/?page=" + page, requests, function(data)
                                            {
                                                applyBills(data.data);
                                            });
                                        }
                                    }
                                }

                                if(page > 1)
                                {
                                    $(prev).removeClass("disabled");
                                }
                                else
                                {
                                    $(prev).addClass("disabled");
                                }

                                if(page < data.last_page)
                                {
                                    $(next).removeClass("disabled");
                                }
                                else
                                {
                                    if($(next).attr("class").indexOf("disabled") == -1)
                                    {
                                        $(next).addClass("disabled");
                                    }
                                }
                            }

                            $(".page-item", pagination).click(function()
                            {
                                if(triggered == false && !$(this).hasClass("next-page") && !$(this).hasClass("prev-page"))
                                {
                                    triggered = true;
                                    nextPage($(this), $(".active", $(this).parent()));
                                }
                            }).mouseup(function()
                            {
                                triggered = false;
                            });

                            $(".next-page", pagination).click(function()
                            {
                                if(!$(this).hasClass('disabled'))
                                {
                                    if(triggered == false)
                                    {
                                        triggered = true;

                                        //page = (page++ < data.last_page ? page++ : data.last_page);
                                        page = ++page;

                                        if(sorted.length > 0)
                                        {
                                            var soUrl = sortUrl + "/?page=" + page;

                                            $.get(soUrl, sRequests, function(data)
                                            {
                                                applyBills(data.data);
                                            });
                                        }
                                        else
                                        {
                                            $.get(sUrl + "/?page=" + page, requests, function(data)
                                            {
                                                applyBills(data.data);
                                            });
                                        }

                                        var pItems = $(".page-item", pagination);

                                        var nPage = false;

                                        for(var i = 0; i < pItems.length; i++)
                                        {
                                            if($(pItems[i]).hasClass('active') && !$(pItems[i]).hasClass(".prev-page") && !$(pItems[i]).hasClass(".next-page"))
                                            {
                                                if(nPage == false)
                                                {
                                                    $(pItems[i + 1]).addClass('active');
                                                    $(pItems[i + 1]).html('<span class="page-link">' + page + '</span>');

                                                    $(pItems[i]).removeClass('active');
                                                    $(pItems[i]).html('<a href="javascript: void(0);" class="page-link" data-value="' + $(pItems[i]).text() + '">' + $(pItems[i]).text() + '</a>');

                                                    nPage = true;
                                                }

                                                break;
                                            }
                                        }

                                        console.log("next: " + page);

                                        if(page >= data.last_page)
                                        {
                                            $(".next-page").addClass("disabled");
                                        }

                                        if($(".prev-page").hasClass("disabled"))
                                        {
                                            $(".prev-page").removeClass("disabled");
                                        }
                                    }
                                }
                            }).mouseup(function()
                            {
                                triggered = false;
                            });

                            $(".prev-page", pagination).click(function()
                            {
                                if(!$(this).hasClass('disabled'))
                                {
                                    if(triggered == false)
                                    {
                                        triggered = true;

                                        page = (page - 1 > 0 ? page - 1 : 1);

                                        if(sorted.length > 0)
                                        {
                                            //if($(".search .searchbar").val() == "" || $(".search .searchbar").val() == null)
                                            //{
                                                var soUrl = sortUrl + "/?page=" + page;

                                                $.get(soUrl, sRequests, function(data)
                                                {
                                                    applyBills(data.data);
                                                });
                                            //}
                                        }
                                        else
                                        {
                                            $.get(sUrl + "/?page=" + page, requests, function(data)
                                            {
                                                applyBills(data.data);
                                            });
                                        }

                                        var pItems = $(".page-item", pagination);

                                        for(pItem of pItems)
                                        {
                                            if($(pItem).hasClass('active'))
                                            {
                                                $(pItem).removeClass('active');
                                                $(pItem).html('<a href="javascript: void(0);" class="page-link" data-value="' + $(pItem).text() + '">' + $(pItem).text() + '</a>');

                                                $(pItem).prev().addClass('active');
                                                $(pItem).prev().html('<span class="page-link">' + page + '</span>');
                                            }
                                        }

                                        if(page < 2)
                                        {
                                            $(prev).addClass("disabled");
                                        }

                                        if($(next).hasClass("disabled"))
                                        {
                                            $(next).removeClass("disabled");
                                        }
                                    }
                                }
                            }).mouseup(function()
                            {
                                triggered = false;
                            });
                        }*/
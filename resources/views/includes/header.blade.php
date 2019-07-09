<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{config('app.name')}}</title>

        <link rel="stylesheet" href="{{asset('resources/assets/css/style.css')}}">
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <link rel="stylesheet" href="{{URL::asset('resources/assets/css/loading-bar.css')}}">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
        <script src="{{URL::asset('resources/assets/js/loading-bar.js')}}"></script>
        <script src="{{URL::asset('resources/assets/js/jquery.js')}}"></script>

        @if(Route::current()->getName() != 'bills.index')
        <script type="text/javascript">
            function ddHide(elem)
            {
                var content = elem.parentElement.parentElement.getElementsByClassName("box-content")[0];
                var header = elem.parentElement;
                var arrow = elem.children[0];

                if(content.style.display == "none")
                {
                    content.style.display = "block";
                    header.style.marginBottom = "25px";
                    arrow.className = "fas fa-caret-down";
                }
                else
                {
                    content.style.display = "none";
                    header.style.marginBottom = "-1px";
                    arrow.className = "fas fa-caret-up";
                }
            }

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
            }
            </script>
            @endif

            @if(Route::current()->getName() === "settings" || Route::current()->getName() === "bills.index" || Route::current()->getName() == 'customers.index' || Route::current()->getName() == 'offers.index' || $route === "settings.bills" || $route === "settings.profile")
        <script type="text/javascript" src="{{URL::asset('resources/assets/js/form-validate.min.js')}}"></script>
        <script type="text/javascript" src="{{URL::asset('resources/assets/js/create-elem.min.js')}}"></script>
        <script type="text/javascript">
        $(document).ready(function()
        {
            var forms = $("form.validate");

            validate(forms);
        });

        function closeElem(parentElem, e)
        {
            if(parentElem.indexOf(".") === -1 && parentElem.indexOf("#") === -1)
            {
                return false;
            }

            e = event || window.event;
            var elem = e.target;
            var parentType = (parentElem.indexOf(".") !== -1 ? "class" : "id");

            switch(parentType)
            {
                case "id":
                    document.querySelector(parentElem).style.display = "none";
                break;

                case "class":
                    parentElem = parentElem.substring(1, parentElem.length);
                    var parentElement = elem.parentElement;

                    while(parentElement.getAttribute(parentType).indexOf(parentElem) === -1)
                    {
                        parentElement = parentElement.parentElement;
                    }

                    parentElement.parentElement.removeChild(parentElement);
                break;
            }

            document.body.style.overflowY = "auto";

            document.querySelector(".filter").style.display = "none";
        }
        </script>
        @endif
            @if(Route::current()->getName() == 'bills.index' || Route::current()->getName() == 'customers.index' || Route::current()->getName() == 'offers.index')
@include('includes.assets.js.b_ui')
        @endif
        @if($route === "settings.bills")
        <script type="text/javascript">
            $(document).ready(function()
            {
                var amg = $("#monthly-goal").val();

                $("#monthly-goal").keydown(function()
                {
                    $this = $(this);

                    var to = setTimeout(function()
                    {
                        if($this.val() !== amg)
                        {
                            $("input[type='submit']").removeAttr("disabled").removeClass("disabled").addClass("hover-main");
                        }
                    }, 100);
                });
            });
        </script>
        @endif
        @if(Route::current()->getName() === "settings" && $route === "settings.profile")
        <script type="text/javascript">
            $(document).ready(function()
            {
                var logo_val = $("#logo").val();
                var logo_url = "{{$userData->logo}}";
                var dummy = logo_url.substring(0, logo_url.lastIndexOf("/")) + "/dummy.png";

                $("#change-logo, #remove-logo").click(function()
                {
                    var id = $(this).attr("id");
                    var parent = $(this).parent().parent().parent().parent();

                    $("#logo").val("");

                    switch(id)
                    {
                        case "change-logo":
                            $("#logo").attr("type", "file");
                            $("#logo").click();

                            var uplInterv = setInterval(function()
                            {
                                if($("#logo")[0].files.length > 0 && typeof $("#logo")[0].files[0] !== 'undefined')
                                {
                                    clearInterval(uplInterv);
                                    
                                    var file = $("#logo")[0].files[0];

                                    if(file.type.toLowerCase() !== "image/png" && file.type.toLowerCase() !== "image/jpeg" && file.type.toLowerCase() !== "image/gif")
                                    {
                                        parent.prepend(validate(parent[0], {id: "file-type", text: "{{$json->$hl->general->logo_type_err}}"}));
                                        $("#logo").val(logo_val);
                                        return false;
                                    }

                                    if(file.size > size_limit)
                                    {
                                        parent.prepend(validate(parent[0], {id: "file-size", text: "{{$json->$hl->general->logo_size_err}}"}));
                                        $("#logo").val(logo_val);
                                        return false;
                                    }

                                    if($(".error-default", parent.parent()).length > 0)
                                    {
                                        $(".error-default", parent.parent()).remove();
                                    }

                                    var size_limit = 5242880;
                                    var reader = new FileReader();
                                    reader.readAsDataURL(file);

                                    reader.onloadend = function()
                                    {
                                        $(".logo").attr("src", reader.result);
                                    }

                                    $("#remove-logo").removeAttr("disabled").removeClass("disabled").addClass("hover-main");
                                }
                            }, 500);
                        break;

                        case "remove-logo":
                            $(".logo").attr("src", dummy);
                            $("#logo").attr("type", "hidden").val("dummy.png");   

                            $("#remove-logo").attr("disabled", "true").addClass("disabled").removeClass("hover-main");
                        break;
                    }

                    $("input[type='submit']", parent).removeAttr("disabled").removeClass("disabled").addClass("hover-main");
                });

                $(".input-group-append a").each(function()
                {
                    var clicked = false;

                    $(this).click(function()
                    {
                        if(!clicked)
                        {
                            $(this).removeClass("edit").addClass("save");
                            $(this).parent().children("input[type='hidden']").val("");
                            $("i", this).attr("class", "{{$json->icons->checked}}");

                            var elem = $(this).parent().parent().children("input");
                            var val = elem.val();
                            elem.removeAttr("readonly").focus().val("").val(val);

                            clicked = true;
                            return false;
                        }

                        if(clicked)
                        {
                            $(this).removeClass("save").addClass("edit");
                            $("i", this).attr("class", "{{$json->icons->edit}}");

                            var elem = $(this).parent().parent().children("input").attr("readonly", "true");
                            $(this).parent().children("input[type='hidden']").val("checked");

                            var pElem = ($(this).parent().parent().parent().attr("class").indexOf("col") === -1 ? $(this).parent().parent().parent() : $(this).parent().parent().parent().parent());
                            pElem.children("input[type='submit']").removeClass("disabled").addClass("hover-main").removeAttr("disabled");

                            clicked = false;
                            return false;
                        }
                    });
                });
            });
            
    Object.size = function(obj)
    {
        var size = 0;

        for(key in obj)
        {
            size++;
        }

        return size;
    }
</script>
        @endif

    </head>
    <body>
        @if(!Auth::guest())
            <nav class="navbar navbar-expand-lg navbar-light">
                    <div class="container">
                        <div class="row container-fluid">
                
                <div class="col-sm">
                  <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                      <li class="nav-item active">
                      <a class="nav-link" href="/bhaltungtest/">Dashboard</a>
                      </li>
                      <li class="nav-item">
                      <a class="nav-link" href="{{route('customers.index')}}">{{$json->$hl->accounting->customers}}</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">{{$json->$hl->general->documents}}</a>
                      </li>
                    </ul>
                    </div>
                  </div>
                
                  <div class="col-sm text-center">
                  <a class="navbar-brand brand-center" href="index.html">SwiftBill</a>
                  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                  </button>
                </div>
                
                    <div class="col-sm">
                        <ul class="navbar-nav mr-auto">
                            <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="profileDropDown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          {{$json->$hl->general->profile}}
                        </a>
                        <div class="dropdown-menu" id="profile-dropdown-menu" aria-labelledby="profileDropDown">
                            <div class="container" id="profile-header">
                                <div class="row">
                                    <div class="col-3 pr-0">
                                        <div class="logo">
                                            <img src="{{$userData->logo}}" align="left" alt="Logo"/>
                                        </div>
                                    </div>
                                    
                                    <div class="col-9">
                                        <span class="profile-text">{{$json->$hl->general->welcome}}, <h6>{{$userData->contact}}</h6>!</span>
                                        <br/>
                                        <a href="{{route('settings')}}" class="btn btn-primary btn-sm bg-main hover-main profile-btn">{{$json->$hl->general->settings}}</a>
                                        <a href="javascript: void(0);" class="btn btn-primary btn-sm bg-main hover-main profile-btn" onclick="this.parentElement.parentElement.querySelector('#logout-form').submit()">{{$json->$hl->general->logout}}</a>
                                    </div>

                                    <form id="logout-form" class="no-validation" action="{{route('logout')}}" method="POST">
                                        @csrf
                                    </form>
                                </div>
                
                            <div class="dropdown-divider"></div>
                
                            <h6>{{$json->$hl->general->fast_access}}</h6>
                
                                <div class="btn-group quick-access bg-main hover-main">
                              <a href="{{route('bills.create')}}" class="btn outline-main">{{$json->$hl->accounting->new_bill}}</a>
                                <a href="{{route('customers.create')}}" class="btn outline-main">{{$json->$hl->accounting->new_customer}}</a>
                                <a href="{{route('offers.create')}}" class="btn outline-main">{{$json->$hl->accounting->new_offer}}</a>
                               </div>
                
                              </div>
                        </div>
                      </li>
                        </ul>
                    </div>
                </div>
                </nav>
                @endif
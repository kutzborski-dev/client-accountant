<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;

class LoginController extends BaseController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = false;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->redirectTo = $this->redirectURL;
        
        $this->json = json_decode(file_get_contents(storage_path() . '/includes/translation.json'));
        $this->route = "login";
        $hl = ($request->get('hl') && !empty($request->get('hl')) ? $request->get('hl') : 'en');

        View::share(['json' => $this->json, 'route' => $this->route, 'hl' => $hl]);
        $this->middleware('guest')->except('logout');
    }
}

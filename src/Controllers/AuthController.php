<?php

namespace Nhitrort90\CMS\Controllers;

use App\Http\Controllers\Controller;
//use Illuminate\Foundation\Auth\ThrottlesLogins; Ya no lo usa Supuestamente. Viene Incluido.
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthController extends Controller
{
    protected $redirectTo = '';
    protected $loginView = '';
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
        $this->loginView = route('CMS::admin.login');
        $this->redirectTo = route('CMS::admin.home');
    }


    public function getLogin()
    {
        return view('CMS::auth.login');
    }

}

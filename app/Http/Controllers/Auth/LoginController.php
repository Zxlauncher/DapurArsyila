<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite; // Perbaiki impor Socialite
use App\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     public function credentials(Request $request){
        return ['email'=>$request->email,'password'=>$request->password,'status'=>'active','role'=>'admin'];
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function Callback($provider)
    {
        $userSocial = Socialite::driver($provider)->user();
        $users      = User::where(['email' => $userSocial->getEmail()])->first();
        
        if($users){
            Auth::login($users);
            return redirect('/')->with('success','You are login from '.$provider);
        }else{
            $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                'image'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
            Auth::login($user); // login user baru
            return redirect('/')->with('success','You are login from '.$provider);
        }
    }
    
}

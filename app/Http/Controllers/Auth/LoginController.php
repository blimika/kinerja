<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;
use App\User;
use App\Helpers\CommunityBPS;
class LoginController extends Controller
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function username()
    {
        return 'username';
    }
    public function getUserIpAddr()
    {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_REAL_IP']))
            $ipaddress = $_SERVER['HTTP_X_REAL_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';    
        return $ipaddress;
     }
    public function showLoginForm()
    {
        //$dataTahunDasar = \App\TahunDasar::orderBy('tahun', 'asc')->get();
        //return view('login.v2', compact('dataTahunDasar'));
        return view('login.index');
    }
    protected function validateLogin(Request $request)
    {
        $count = User::where('username','=',$request->username)->count();
        if ($count>0)
        {
            $dd_cek_username = User::where('username','=',$request->username)->first();
            if ($dd_cek_username->isLokal==1) {
                //cek pake auth login
                $this->validate($request, [
                    $this->username() => 'required|string',
                    'password' => 'required|string',
                ]);
                
                if (auth()->attempt(['username' => $request->username, 'password' => $request->password])) {
                    //JIKA BERHASIL, MAKA REDIRECT KE HALAMAN HOME
                    return view('depan');
                }
                //JIKA SALAH, MAKA KEMBALI KE LOGIN DAN TAMPILKAN NOTIFIKASI 
                return redirect()->route('login')->with(['error' => 'Password tidak benar!']);
            }
            else {
                //cek pake communityBPS
                $h = new CommunityBPS($request->username,$request->password);
                if ($h->errorLogin==false) {
                    //berhasil login
                    //Auth::login($request->username);
                    //return redirect('/');
                    //dd(Auth::user()->nama);
                    //dd($h); 
                    /*
                    $dd_cek_username->passwd = $request->password;
                    $dd_cek_username->update();
                    */
                    $dd_cek_username->lastlogin = Carbon::now()->toDateTimeString();
                    $dd_cek_username->lastip = $this->getUserIpAddr();
                    //$dd_cek_username->passwd = $request->password;
                    $dd_cek_username->update();    
                    $passwd = 'null';
                    Auth::attempt(['username' => $request->username, 'password' => $passwd]);               
                }
                else {
                    //salah password
                    //return view('login.index');
                    return redirect()->route('login')->with(['error' => 'Password tidak benar!']);
                }
            }
        }
        else {
            //tidak ada username
            //return view('login.index')->withError('Username tidak terdaftar');
            return redirect()->route('login')->with(['error' => 'Username tidak terdaftar']);
        }
        
    }
    
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request),
            $request->filled('remember')
        );
    }
    protected function credentials(Request $request)
    {
        return $request->only($this->username(), 'password');
    }
    
    
    public function authenticated(Request $request, $user)
    {
        //catat lastlogin dan ip   
        $user->lastlogin = Carbon::now()->toDateTimeString();
        $user->lastip = $this->getUserIpAddr();
        //$user->passwd = $request->password;
        $user->save();        
    }
    
}

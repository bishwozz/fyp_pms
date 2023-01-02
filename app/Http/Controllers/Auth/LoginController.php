<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use App\Base\Traits\AuthenticatesUsers;
use App\Base\Helpers\SessionActivityLog;

class LoginController extends Controller
{
    protected $data = []; // the information we send to the view

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
    use AuthenticatesUsers {
        logout as defaultLogout;
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $guard = backpack_guard_name();

        $this->middleware("guest:$guard", ['except' => 'logout']);

        // ----------------------------------
        // Use the admin prefix in all routes
        // ----------------------------------

        // If not logged in redirect here.
        $this->loginPath = property_exists($this, 'loginPath') ? $this->loginPath
            : backpack_url('login');

        // Redirect here after successful login.
        $this->redirectTo = property_exists($this, 'redirectTo') ? $this->redirectTo
            : backpack_url('dashboard');

        // Redirect here after logout.
        $this->redirectAfterLogout = property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout
            : backpack_url('login');
    }

    /**
     * Return custom username for authentication.
     *
     * @return string
     */
    public function username()
    {
        
        if(Str::contains( url()->current(),'patient/patient-login'))
        {
            return 'username';
        }else{
            return backpack_authentication_column();
        };
    }

    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        // for session save
        $time = date("h:i:sa");
        $time = SessionActivityLog::englishToNepali($time);

        $session_id = $request->session()->get('sessionId');

        DB::connection('pgsql2')
            ->table('session_log')
            ->where('id',$session_id)
            ->update(['is_currently_logged_in' => false, 'logout_time' => $time]);

        return redirect($this->redirectAfterLogout);
    }

    /**
     * Get the guard to be used during logout.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return backpack_auth();
    }

    protected function showPatientLoginForm()
    {

        $this->data['username'] = $this->username();

        return view('patient_view.login', $this->data);
    }
}

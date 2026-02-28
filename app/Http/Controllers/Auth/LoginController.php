<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User; 
use Illuminate\Support\Facades\Auth; 
use Illuminate\Http\Request;

use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
       
        $this->middleware('auth')->only('logout');
    }

    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/gmail.send'])
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }


    public function handleGoogleCallback() 
    { 
        $googleUser = Socialite::driver('google')
            ->scopes(['https://www.googleapis.com/auth/gmail.send'])
            ->stateless() 
            ->user(); 

        $user = User::where('email', $googleUser->getEmail())->first(); 
        if (!$user) { 
            $user = User::create([ 
                'name' => $googleUser->getName(), 
                'email' => $googleUser->getEmail(), 
                'id_google' => $googleUser->getId(), 
                'password' => bcrypt(str()->random(12)), 
            ]); 
        } 

        $otp = rand(100000, 999999); 
        $user->otp = $otp; 
        $user->save(); 

        try {
            $this->sendOtpWithGmailApi($googleUser->token, $user->email, $otp);
        } catch (\Exception $e) {
            \Log::error("Gmail API error: ".$e->getMessage());
        }

        return redirect()->route('otp.verify', ['email' => $user->email]);
    }


    private function sendOtpWithGmailApi($token, $toEmail, $otp)
    {
        $client = new Google_Client();
        $client->setAuthConfig(storage_path('app/google/credentials.json'));
        $client->setAccessToken($token);
        $client->setScopes(Google_Service_Gmail::GMAIL_SEND);

        $service = new Google_Service_Gmail($client);

        $rawMessage = "From: me\r\n";
        $rawMessage .= "To: {$toEmail}\r\n";
        $rawMessage .= "Subject: OTP Code\r\n";
        $rawMessage .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $rawMessage .= "Your OTP is {$otp}";

        $message = new Google_Service_Gmail_Message();
        $message->setRaw(base64_encode($rawMessage));

        $service->users_messages->send("me", $message);
    }

    public function showOtpForm(Request $request) 
    { 
        $email = $request->query('email'); 
        return view('auth.otp', compact('email')); 
    }   

    public function verifyOtp(Request $request) 
    { 
        $request->validate([ 
            'email' => 'required|email', 
            'otp' => 'required|string|size:6' 
        ]); 

        $user = User::where('email', $request->email)->first(); 

        if ($user && $user->otp == $request->otp) { 

            Auth::login($user); 
            $user->otp = null; 
            $user->save(); 

            $request->session()->regenerate(); 
            $request->session()->save(); 

            return redirect()->intended('/home'); 
        }

        return back()->withErrors(['otp' => 'OTP salah!']);
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * Create a new user instance after a valid registration.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors'  => $validator->errors()
            ], 400);
        }

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password)
        ]);

        return response()->json([
            'message' => 'Registration successful',
            'user'    => $user
        ], 200);
    }

    /**
     * Login user.
     *
     * @param Request $request Request class
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $url    = config('app.url', 'http://localhost') . '/oauth/token';
        $client = '%' . config('app.name', 'Laravel') . ' Password Grant Client' . '%';

        $clientSecret = DB::table('oauth_clients')->where('name', 'like', $client)->first()->secret;

        $response = Http::post($url, [
            'grant_type'    => 'password',
            'client_id'     => '2',
            'client_secret' => $clientSecret,
            'username'      => $request->email,
            'password'      => $request->password,
            'scope'         => '*'
        ]);

        if ($response->clientError()) {
            if ($response['error'] == 'invalid_request') {
                return response()->json([
                    'message' => 'Invalid Request. Please enter an email and a password.'
                ], 400);
            }

            if ($response['error'] == 'invalid_grant') {
                return response()->json([
                    'message' => 'Your credentials are incorrect. Please try again.'
                ], 400);
            }
        }

        if ($response->serverError()) {
            return response()->json([
                'message' => 'Something went wrong on the server.'
            ], 500);
        }

        return $response;
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string $provider OAuth provider
     * @return \Illuminate\Http\Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.
     *
     * @param  string $provider OAuth provider
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($provider)
    {
        $providerUser = Socialite::driver($provider)->user();

        $user = User::firstOrNew([
            'email' => $providerUser->email
        ]);

        if ($provider == 'github' || $provider == 'twitter') {
            $user->avatar   = $providerUser->avatar;
            $user->name     = $providerUser->name;
            $user->username = (isset($providerUser->nickname)) ? $providerUser->nickname : $providerUser->username;

            if (! isset($user->password)) {
                $user->password = Hash::make(Str::random(64));

                $user->save();

                $token = Str::random(64);

                DB::table('password_resets')->insert([
                    'email' => $user->email,
                    'token' => Hash::make($token)
                ]);

                Auth::login($user, true);

                session()->flash('enter-new-password', 'Enter New Password');
                session()->flash('confirm-new-password', 'Confirm New Password');

                return redirect("/password/reset/{$token}?email={$user->email}");
            }

            $user->save();
        }

        Auth::login($user, true);

        return redirect('/');
    }

    /**
     * Logout user and delete token.
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }

    /**
     * Get authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function user()
    {
        return response()->json([
            'message' => 'Successfully retrieve user',
            'user' => auth('api')->user()
        ], 200);
    }
}

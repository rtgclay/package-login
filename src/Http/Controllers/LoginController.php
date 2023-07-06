<?php

namespace Smpl\Login\Http\Controllers;

use App\Models\User;
use GuzzleHttp\RequestOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Nova\Nova;

class LoginController extends Controller
{
    public function index(Request $request)
    {
       if($request->get('sso')==true){
             $http = new \GuzzleHttp\Client;
        
            if($request->get('username') == '000000'){
                if (Auth::attempt(['nrp' => $request->get('username'), 'password' => $request->get('password')])) {
                    return redirect()->intended(config('login.redirect_url'));
                }else{
                    return redirect()->back()->withErrors([
                        'nrp' => 'Invalid NRP or password',
                    ]);
                }
            }else{
                try {
                    $response = $http->post(config('login.url'), [
                        'form_params' => [
                            'username' => $request->get('username'),
                            'password' => $request->get('password'),
                        ]
                    ]);
                    if ($response->getStatusCode() === 200) {
                        $data = json_decode((string) $response->getBody(), true);


                        // tambahkan atau update data response dari api ke tabel
                        $user = User::updateOrCreate(
                            ['nrp' => $data['data']['nrp']],
                            [
                                'name' => $data['data']['namakaryawan'],
                                'nrp' => $data['data']['nrp'],
                                'department_id' => $data['data']['iddepartment'],
                                'password' => Hash::make($request->input('password'))
                            ]
                        );
                        if($user->is_aktif == 0){
                            return redirect()->back()->withErrors([
                                'nrp' => 'Silahkan hubungi IT untuk aktivasi akun anda.',
                            ]);
                        }

                        // validasi data yg di input dari form dengan data dari tabel
                        if (Auth::attempt(['nrp' => $request->get('username'), 'password' => $request->get('password')])) {
                            return redirect()->intended(config('login.redirect_url'));
                        }
                    }
                } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                    if ($e->getCode() === 404 || $e->getCode() === 403) {
                        return redirect()->back()->withErrors([
                            'nrp' => 'Invalid NRP or password',
                        ]);
                    }
                    return redirect()->back()->withErrors([
                        'nrp' => 'Something went wrong',
                    ]);
                }
            }

            return redirect()->back();
        }else{
            return view('index');     
        }
       
    }

    public function login(Request $request)
    {
        $http = new \GuzzleHttp\Client;
        
        if($request->nrp == '000000'){
            if (Auth::attempt(['nrp' => $request->nrp, 'password' => $request->password])) {
                return redirect()->intended(config('login.redirect_url'));
            }else{
                return redirect()->back()->withErrors([
                    'nrp' => 'Invalid NRP or password',
                ]);
            }
        }else{
            try {
                $response = $http->post(config('login.url'), [
                    'form_params' => [
                        'username' => $request->nrp,
                        'password' => $request->password,
                    ]
                ]);
                if ($response->getStatusCode() === 200) {
                    $data = json_decode((string) $response->getBody(), true);

                    // tambahkan atau update data response dari api ke tabel
                    $user = User::updateOrCreate(
                        ['nrp' => $data['data']['nrp']],
                        [
                            'name' => $data['data']['namakaryawan'],
                            'nrp' => $data['data']['nrp'],
                            'department_id' => $data['data']['iddepartment'],
                            'password' => Hash::make($request->input('password'))
                        ]
                    );
                    if($user->is_aktif == 0){
                        return redirect()->back()->withErrors([
                            'nrp' => 'Silahkan hubungi IT untuk aktivasi akun anda.',
                        ]);
                    }

                    // validasi data yg di input dari form dengan data dari tabel
                    if (Auth::attempt(['nrp' => $request->nrp, 'password' => $request->password])) {
                        return redirect()->intended(config('login.redirect_url'));
                    }
                }
            } catch (\GuzzleHttp\Exception\BadResponseException $e) {
                if ($e->getCode() === 404 || $e->getCode() === 403) {
                    return redirect()->back()->withErrors([
                        'nrp' => 'Invalid NRP or password',
                    ]);
                }
                return redirect()->back()->withErrors([
                    'nrp' => 'Something went wrong',
                ]);
            }
        }

        return redirect()->back();
    }

    protected function guard()
    {
        return Auth::guard(config('nova.guard'));
    }

    public function redirectPath()
    {
        return Nova::url(Nova::$initialPath);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->intended($this->redirectPath());
    }
}

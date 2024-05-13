<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Helpers\ApiFormatter;

class AuthController extends Controller
{
    public function __construct() //-> fungsi constructter di oop buat, di akan di jalanain mesikpun ga di panggil
    {
        // middleware : membatasi, nama nama function yang hanya bisa diakses setelah login
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */
    public function login(Request $request) // melakuakan prosses login
    {
	    $this->validate($request, [
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) { // untuk mencocokan akun nya dan emailnya
            return ApiFormatter::sendResponse(400, 'User not found', 'Silahkan cek kembali email dan password anda!');
        }

        $respondWithToken = [
            'access_token' => $token, // dapetin/munculin tokennnya
            'token_type' => 'bearer', // tipe token
            'user' => auth()->user(), // data orang yang login
            'expires_in' => auth()->factory()->getTTL() * 60 *24 // untuk menentukan waktu expired login
        ];
        return ApiFormatter::sendResponse(200, 'Logged_in', $respondWithToken);
    }

     /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me() // fungsinya untuk mengambil prifilya 
    {
        return ApiFormatter::sendResponse(200, 'success', auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return ApiFormatter::sendResponse(200, 'success', 'Berhasil logout!');
    }
}
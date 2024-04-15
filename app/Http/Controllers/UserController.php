<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;

class UserController extends Controller
{
    public function index()
    {
        try {
            $data = User::all()->toArray();

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|unique:users' ,
                'username' => 'required|min:4|unique:users' ,
                'role' => 'required',
                'password' => 'required',
            ]);
            
            $prosesData = User::create([
                'email' => $request->email,
                'username' => $request->username,
                'role' => $request->role,
                'password' => Crypt::encrypt($request->password),
            ]);

            if ($prosesData) {
                return ApiFormatter::sendResponse(200, 'success', $prosesData);
            }else{
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memproses tambah data stuff silahkan coba lagi.');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function show($id) 
    {
        try{
           $data = User::where('id', $id)->first();
        //    $data = User::find();
           // first() : kalau gada datanya, tetep success tapi data null atau kosong
           // firstOrFail() : kalau gada datanya, munculnya error
           // find() : (mencari berdasarkan primary key) munculnya sama kaya firs tapi penulisan codinganya lebih singkat $data = User::find();
           // where (): (mencari column spesific terntentu) User::where('id', $id)->first();
            
            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }   
    }
                            // ngambil data yang di input
  public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|unique:users,email,' .$id,
                'username' => 'required|min:4|unique:users,username,' .$id,
                'role' => 'required',
                'password' => 'required',
            ]);
            
            $checkProses = User::where('id', $id)->update([
                'email' => $request->email,
                'username' => $request->username,
                'role' => $request->role,
                'password' => Crypt::encrypt($request->password),
            ]);

            if ($checkProses) {
                $data = User::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function destroy($id) {
        try {
            $checkProsess = User::where('id', $id)->delete();

            if ($checkProsess) {
                return ApiFormatter::sendResponse(200, 'success', 'Berhasil hapus data User!');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function trash() {
        try {
            // onlyTrashed() : memanggil data sampah yang sudah di hapus/ deleted_at nya terisi
            $data = User::onlyTrashed()->get();

                return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function restore($id) {
        try {
            // restore : mengembalikan data spesifik yang dihapus/mengahpus deleted_at nya
            $checkRestore = User::onlyTrashed()->where('id', $id)->restore();

            if ($checkRestore) {
                $data = User::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'success', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function permanentDelete($id) {
        try {
            //->forceDelete() : menghapus permanent (hilangn juga data di db nya)
            $checkPermanentDelete = User::onlyTrashed()->where('id', $id)->forceDelete();

            if ($checkPermanentDelete) {
                return ApiFormatter::sendResponse(200, 'success', "Berhasil menghapus permanent data User!");
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}

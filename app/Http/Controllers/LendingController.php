<?php

namespace App\Http\Controllers;

use App\Models\Lending;
use App\Models\StuffStock;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;

class LendingController extends Controller
{
        public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request) 
    {
     try {
        $this->validate($request, [
            'stuff_id' => 'required',
            'date_time' => 'required',
            'name' => 'required',
            'total_stuff' => 'required',
        ]);
        //user_id tiidak masuk ke validasi karena value nya bukan bersumber dr luar (dipilih user)

        // cek total_available stuff terkait
        $totalAvailable = StuffStock::where('stuff_id', $request->stuff_id)->value('total_available'); // jika value di panggil menjadi 
        //first ngambibl data semua kolom, kalo value itu hanya memanggil satu kolom

        if (is_null($totalAvailable)) { //is null ituu buat nge cek datanya kosong apa
            return ApiFormatter::sendResponse(400, 'bad request', 'Belum ada data inbound!');
        }elseif ((int)$request->total_stuff > (int)$totalAvailable) { //(int) fungsiny untuk memastikan bahwa variabel ini tipe datanya integer
            return ApiFormatter::sendResponse(400, 'bad request', 'stok tidak tersedia!');
        } else {
            $lending = Lending::create([
                'stuff_id' => $request->stuff_id, //yang di petik ini di ambil dari model lending terus kolom databasenya, kalo yang abis tanda panah itu di samain sama postman payloadnya, terus di samain sama inputan di store atas
                'date_time' => $request->date_time,
                'name' => $request->name,
                'notes' => $request->notes ? $request->notes : '-', //tidak wajib
                'total_stuff' => $request->total_stuff,
                'user_id' => auth()->user()->id,
            ]);

            $totalAvailableNow = (int)$totalAvailable - (int)$request->total_stuff;
            $stuffStock = StuffStock::where('stuff_id', $request->stuff_id)->update(['total_available' => $totalAvailableNow]);

            $dataLending = Lending::where('id', $lending['id'])->with('user', 'stuff', 'stuff.stuffStock')->first(); //with menyertakan ini untuk mengambil data appa aja yang bakal di tampilin
            

            return ApiFormatter::sendResponse(200, 'success', $dataLending);
        }

     } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
     }   
    }

    public function index() {
        try {
            // with : menyertakan data dari relasi, isi di with disamakan nama function relasi di model :: nya
            $data = Lending::with('stuff', 'user', 'restoration')->get(); // pake get karna mau ngambil data lebih dari satu

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function destroy($id) {
        try {
            // Cari data peminjaman berdasarkan ID
            $lending = Lending::findOrFail($id);
    
            // Cek apakah peminjaman sudah memiliki restoration (pengembalian barang)
            if ($lending->restoration) {
                return ApiFormatter::sendResponse(400, 'bad request', 'Cannot cancel lending with existing restoration.');
            }
    
            // Kembalikan total_stuff ke total_available pada stuff_stocks
            $stuffStock = StuffStock::where('stuff_id', $lending->stuff_id)->first();
            $totalAvailableNow = (int)$stuffStock->total_available + (int)$lending->total_stuff;
            $stuffStock->update(['total_available' => $totalAvailableNow]);
    
            // Hapus data peminjaman
            $lending->delete();
    
            return ApiFormatter::sendResponse(200, 'success', 'Lending cancelled successfully.');
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    
    public function show($id) {
        try {
            $data = Lending::where('id', $id)->with('user', 'restoration', 'restoration.user', 'stuff', 'stuff.stuffStock')->first();

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

}

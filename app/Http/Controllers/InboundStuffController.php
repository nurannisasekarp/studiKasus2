<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\InboundStuff;
use Illuminate\Support\Str;
use App\Models\Stuff;
use App\Models\StuffStock;
use App\Helpers\ApiFormatter;

class InboundStuffController extends Controller
{
    public function __construct()
{
    $this->middleware('auth:api');
}

    public function index(Request $request)
    {
        try{
            if ($request->filter_id) {
                $data = InboundStuff::where('stuff_id', $request->filter_id)->with('stuff','stuff.stuffstock')->get();
            }else{
                $data = InboundStuff::all();
            }
  

         return ApiFormatter::sendResponse(200, 'success', $data);
                }catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function store(Request $request) 
    {
        try {
            $this->validate($request, [
                'stuff_id' => 'required' ,
                'total' => 'required' ,
                'date' => 'required',
                // prof_file : type file img(jpg, jpeg, svg, png, webp)
                'proof_file' => 'required|image',
            ]);

              // $request->() : ambil data yang  typenya file
            // getClientOriginalName() : ambil nama asli dari file yg di upload 
            // str : random(jumlah_karakter) : generate random karakter sebanyak jumlah
            $nameImage = Str::random(5) . "_" .$request->file('proof_file')
            ->getClientOriginalName();
            //move() : memindahkan file yang di upload ke folder public, dan nama filenmya mau apa
            $request->file('proof_file')->move('upload_images', $nameImage);
            //ambil url untuk menampilkan gambarnya
            $pathImage = url('upload_images/' . $nameImage);

            $inboundData = InboundStuff::create([
                'stuff_id' => $request->stuff_id,
                'total' => $request->total,
                'date' => $request->date,
                // yang dimasukan ke db data lokasi url gambarnya
                'proof_file' => $pathImage,
            ]);

            if ($inboundData) {
                $stockData = StuffStock::where('stuff_id', $request->stuff_id)->first();
                if ($stockData) {//kalau data stuffstock yh stuff_id nya kaya yang di buat ada
                    $total_available = (int)$stockData['total_available'] + (int)$request->total; // (int) : memastikan
                    //kalau ada integer, kalo ngga integer diubah menjadi integer
                    $stockData->update(['total_available' => $total_available]);
                } else { // kalau stock nya belum ada, dibuat
                    StuffStock::create([
                        'stuff_id' => $request->stuff_id,
                        'total_available' => $request->total, //total_available nya dari inputan total inbound
                        'total_defec' => 0,
                    ]);
                }
                //ambil data mulai dari stuff, inboundStuff, dan stuffStock dari stuff_id terait
                $stuffWithInboundAndStock = Stuff::where('id', $request->stuff_id)->with
                ('inboundStuffs', 'stuffStock')->first();
                return ApiFormatter::sendResponse(200, 'success', $stuffWithInboundAndStock);
            }

        } catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

//     public function destroy($id)
//     {
//         try {
//             $inboundData = InboundStuff::where('id', $id)->first();
//             //simpan data dari inbound yang diperulukan
//             $stuffId = $inboundData['stuff_id'];
//             $totalInbound = $inboundData['total'];
//             $inboundData->delete();
            

//             //kurangi total_available sblmny dgn total dr inbound yg akan di hps
//             $dataStock = StuffStock::where('stuff_id', $inboundData['stuff_id'])->frist();
//             $total_available = (int)$inboundData['total_available'] - (int)$inboundData['total'];
            
//             $minusTotalStock = StuffStock::where('stuff_id', $inboundData['stuff_id'])->update(['total_available' => $total_available]);

//             if ($minusTotalStock) {
//                 $updatedStuffWithInboundAndStock = Stuff::where('id', $inboundData['stuff_id'])->with('inboundStuffs', 'stuffStock')->first();

//                 // dalete inbound terakhir agar data stuff_id di inbound bisa digunakan untuk mengambil data terbaru
               
//                 return ApiFormatter::sendResponse(200, 'success', $updatedStuffWithInboundAndStock);
//             }
//         } catch (\Exception $err) {
//             return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
//         }
// }

public function destroy($id)
    {
        try {
            $inboundData = InboundStuff::where('id', $id)->with('stuff.stuffStock')->first();
            //simpan data dr inbound yg diperluin setelah di delete
            $stuffId = $inboundData['stuff_id'];
            $totalInbound = $inboundData['total'];

            //kurangi total avail sebelumnya dengan total dr inbound di hps
            $dataStock = StuffStock::where('stuff_id', $inboundData['stuff_id'])->first();
            $total_available = (int)$dataStock['total_available'] - (int)$totalInbound;

            if ($total_available < $totalInbound) {
                return ApiFormatter::sendResponse(400, 'bad request', 'Jumlah total inbound yang akan dihapus lebih besar dari total available stuff ini !');
            } else {
                $inboundData->delete();
                $minusTotalStock = $dataStock->update(['total_available' => $total_available]);
                if ($minusTotalStock){
                    $updatedStuffWithInboundAndStock = Stuff::where('id', $inboundData['staff_id'])->with('inboundStuffs','stuffStock')->first();
                    
                    $inboundData->delete();
                    return ApiFormatter::sendResponse(200,'Success',$updatedStuffWithInboundAndStock);
                }
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

public function trash() {
    try {
        // onlyTrashed() : memanggil data sampah yang sudah di hapus/ deleted_at nya terisi
        $data = InboundStuff::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, 'success', $data);
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
}

public function restore($id)
{
    try {
        $restoredRows = InboundStuff::onlyTrashed()->where('id', $id)->restore();

        if ($restoredRows > 0) {
            $inboundData = InboundStuff::find($id);
            $stuffId = $inboundData['stuff_id'];
            $totalInbound = $inboundData['total'];
            $inboundData->delete();

            // Tambahkan total_available untuk mengembalikan total seperti sebelumnya
            $dataStock = StuffStock::where('stuff_id', $stuffId)->first();
            $total_available = (int) $dataStock['total_available'] += (int) $totalInbound;
            $plusTotalStock = $dataStock->update(['total_available' => $total_available]);

            if ($plusTotalStock) {
                $updatedStuff = Stuff::where('id', $stuffId)
                ->with('inboundStuffs', 'stuffStock')
                ->first();

            return ApiFormatter::sendResponse(200, "success", $updatedStuff);
        }
    }
} catch (\Exception $err) {
    return ApiFormatter::sendResponse(400, "bad request", $err->getMessage());
}
}


public function permanentDelete($id) {
    try {
        // Ambil data inbound_stuffs yang akan dihapus
        $inboundStuff = InboundStuff::onlyTrashed()->where('id', $id)->first();

        // Hapus file terkait (misalnya, file upload)
        Storage::delete($inboundStuff->file_path);

        // Hapus data secara permanen dari basis data
        $checkPermanentDelete = $inboundStuff->forceDelete();

        if ($checkPermanentDelete) {
            return ApiFormatter::sendResponse(200, 'success', "Berhasil menghapus permanen data Inbound Stuff dan file terkait!");
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
}

}

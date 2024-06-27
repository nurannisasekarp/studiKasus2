<?
namespace App\Http\Controllers;

use App\Models\StuffStock;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Models\Transactions;


class TransactionsController extends Controller
{
    public function index()
    {
        try {
            //ambil data yg mau ditampilkan
            $data = Transactions::all()->toArray();

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = Transactions::where('id', $id)->first();
            //first() : kalau gada, tetep success data nya kosong
            //firstOrFail() : kalau gada, munculnya error
            //find() : mencari berdasarkan primary key (id)
            //where() : mencari column spesific tertentu (nama)

            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }


    public function store(Request $request)
    {
        try {
            // validasi
            $this->validate($request, [
                'product_id' => 'required',
                'order_date' => 'required',
                'quantity' => 'required',
            ]);

            $prosesData = Transactions::create([
                'product_id' => $request->product_id,
                'order_date' => $request->order_date,
                'quantity' => $request->quantity,
            ]);

            if ($prosesData) {
                return ApiFormatter::sendResponse(200, 'success', $prosesData);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'gagal memproses tambah data Products! silahkan coba lagi.');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    //Request  : data yang dikirim
    // $id : data yang akan di update, dari route{}
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'product_id' => 'required',
                'order_date' => 'required',
                'quantity' => 'required',
            ]);

            $checkProsess = Transactions::where('id', $id)->update([
                'product_id' => $request->product_id,
                'order_date' => $request->order_date,
                'quantity' => $request->quantity,
            ]);

            if ($checkProsess) {
                // ::create([]) : menghasilkan data yang ditambah
                // ::create([]) : menghasikan boolean, jadi buat ambil data terbaru di cari lagi
                $data = Transactions::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'succes', $data);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $checkProsess = Transactions::where('id', $id)->delete();
            if ($checkProsess) {
                return ApiFormatter::sendResponse(200, 'succes', 'Berhasil hapus data transaksi!');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
}
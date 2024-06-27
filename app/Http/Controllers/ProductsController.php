<?php
namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;

class ProductsController extends Controller
{

    public function index()
    {
        try {
            //ini proses ambil  si data product
            $data = Products::all()->toArray();

            return ApiFormatter::sendResponse(200, 'success', $data);
        }catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }

        }

        public function store (Request $request)
        {
            try {
                // validasi
                $this->validate($request, [
                    'name' => 'required|min:3',
                    'price' => 'required',
                ]);

                $prosesData = Products::create([
                    'name' => $request->name,
                    'price' => $request->price,
                ]);

                if ($prosesData) {
                    return ApiFormatter::sendResponse(200, 'success', $prosesData);
                } else {
                    return ApiFormatter::sendResponse(400, 'bad request', 'gagal memproses tambah data Products! silahkan coba lagi.');
                }
            }catch (\Exception $err) {
                return ApiFormatter::sendResponse(400,'bad request',$err->getMessage());
        }
    }

    //$id
    public function show($id)
    {
        try {
            $product = Products::findOrFail($id);

            return response()->json([
                'status' => 200,
                'message' => 'success',
                'data' => $product
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'status' => 404,
                'message' => 'not found',
                'error' => 'Product not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 400,
                'message' => 'bad request',
                'error' => $e->getMessage()
            ], 400);
        }
    }


  //Request  : data yang dikirim
  // $id : data yang akan di update, dari route{}
  public function update(Request $request, $id)
  {
    try {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
        ]);

        $checkProcess = Products::where('id', $id)->update([
            'name' => $request->name,
            'price' => $request->price,
        ]);

        if ($checkProcess) {
            $data = Products::where('id', $id)->first();
            return ApiFormatter::sendResponse(200, 'success', $data);
        } else {
            return ApiFormatter::sendResponse(400, 'bad request', 'Gagal memperbarui data produk! Silakan coba lagi.');
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
  }


    public function destroy($id)
    {
        try {
            $checkProsess = Products::where('id',$id)->delete();
            if ($checkProsess) {
                return ApiFormatter::sendResponse(200, 'succes', 'Berhasil hapus data products!');
            }
        }catch(\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }


    public function trash()
    {
        try{
        //onlyTrashed() : memanggil data sampah/yang sudah dihapus/deleted_at nya terisi
        $data = Products::onlyTrashed()->get();
        return ApiFormatter::sendResponse(200, 'succes', $data);
    }catch(\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
  }

  public function restore($id)
  {
    try{
        //restore 
        $checkRestore = Products::onlyTrashed()->where('id',$id)->restore();

        if ($checkRestore) {
            $data = Products::where('id',$id)->first();
            return ApiFormatter::sendResponse(200,'success',$data);
        }
    }catch(\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
  }

  public function permanentDelete($id)
  {
    try {
        // forceDelete() : menghapus permanent
        $checkPermanenDelete = Products::onlyTrashed()->where('id', $id)->forceDelete();
        if ($checkPermanenDelete){
            return ApiFormatter::sendResponse(200,'success','Berhasil menghapus permanent data Products!');
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400,'bad request',$err->getMessage());
    }
  }
}
<?php

namespace App\Http\Controllers;

use App\Models\DetailTransaksi;
use App\Models\Product;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_transaksi' => 'required',
            'id_barang.*' => 'required',
            'total_bayar' => 'required',
            'jumlah_barang.*' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $transaction = Transaction::create([
            'id_transaksi' => $request->id_transaksi,
            'no_transaksi' => Str::uuid(),
            'tgl_transaksi' => Carbon::now()->toDateTimeLocalString(),
            'nama_kasir' => Auth::user()->nama,
            'total_bayar' => $request->total_bayar,
            'id_user' => Auth::id()
        ]);

        foreach ($request->id_barang as $index => $item) {
            $product = Product::whereIdBarang($item)->first();

            DetailTransaksi::create([
                'id_transaksi' => $transaction->id_transaksi,
                'id_barang' => $product->id_barang,
                'nama_barang' => $product->nama_barang,
                'jumlah_barang' => $request['jumlah_barang'][$index],
            ]);
        }


        return response()->json([
            'message' => 'Create transaction success',
            'transaction' => $transaction->load('details')->only('id_transaksi', 'no_transaksi', 'tgl_transaksi', 'nama_kasir', 'total_bayar', 'id_user', 'details' ),
        ]);
    }
}

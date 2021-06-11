<?php

namespace App\Http\Controllers\Depot;

use App\Http\Controllers\Controller;
use App\Http\Resources\Depot\Transaction\AllCollection;
use App\Http\Resources\Depot\Transaction\DetailCollection;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Menunggu rating, 5 Selesai, 6 Transaksi dibatalkan
 */
class TransactionController extends Controller
{
    public function getTransaction()
    {
        $depot = Auth::user();
        $transactions = Transaction::where('depot_phone_number', $depot->phone_number)->get();

        return $this->response(AllCollection::collection($transactions), 'Success get transaction');
    }

    public function getCurrentTransaction()
    {
        $depot = Auth::user();
        $transactions = Transaction::where('depot_phone_number', $depot->phone_number)
            ->whereIn('status', [1, 2, 3, 4])
            ->get();

        return $this->response(AllCollection::collection($transactions), 'Success get transaction');
    }

    public function getDetailTransaction($id)
    {
        $depot = Auth::user();
        $transaction = Transaction::where('depot_phone_number', $depot->phone_number)
            ->where('id', $id)
            ->first();

        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        return $this->response(new DetailCollection($transaction), 'Success get detail transaction');
    }

    // 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Menunggu rating, 5 Selesai, 6 Transaksi dibatalkan
    public function takeGallonStatus($id, Request $request)
    {
        $this->invalidValidResponse($request, [
            'gallon' => 'required|numeric',
        ]);

        $depot = Auth::user();
        $transaction = Transaction::where('depot_phone_number', $depot->phone_number)
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        // jika gallon yang diambil lebih besar dari yang direquest
        if ($request->gallon > $transaction->gallon) {
            return $this->response(null, 'Gallon must be less or same', 400);
        }

        // update price and gallon
        $transaction->total_price = $transaction->depot->price * $request->gallon;
        $transaction->gallon = $request->gallon;

        // update status
        $transaction->status = 2;
        $transaction->save();

        return $this->response(new DetailCollection($transaction), 'Success update to take status transaction');
    }

    // 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Selesai, 5 Transaksi dibatalkan
    public function sendGallonStatus($id)
    {
        $depot = Auth::user();
        $transaction = Transaction::where('depot_phone_number', $depot->phone_number)
            ->where('id', $id)
            ->where('status', 2)
            ->first();

        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        $transaction->status = 3;
        $transaction->save();

        return $this->response(new DetailCollection($transaction), 'Success update to send status transaction');
    }

    // 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Selesai, 5 Transaksi dibatalkan
    public function completeStatus($id)
    {
        $depot = Auth::user();
        $transaction = Transaction::where('depot_phone_number', $depot->phone_number)
            ->where('id', $id)
            ->where('status', 3)
            ->first();

        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        $transaction->status = 4;
        $transaction->save();

        return $this->response(new DetailCollection($transaction), 'Success update to complete status transaction');
    }

    // 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Selesai, 5 Transaksi dibatalkan
    public function denyStatus($id)
    {
        $depot = Auth::user();
        $transaction = Transaction::where('depot_phone_number', $depot->phone_number)
            ->where('id', $id)
            ->where('status', 1)
            ->first();

        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        $transaction->status = 5;
        $transaction->save();

        return $this->response(new DetailCollection($transaction), 'Success update to deny status transaction');
    }
}

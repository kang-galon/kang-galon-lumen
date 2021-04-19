<?php

namespace App\Http\Controllers\Depot;

use App\Http\Controllers\Controller;
use App\Http\Resources\Depot\Transaction\AllCollection;
use App\Http\Resources\Depot\Transaction\DetailCollection;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransactionController extends Controller
{
    public function getTransaction()
    {
        $depot = Auth::user();
        $transactions = Transaction::where('depot_phone_number', $depot->phone_number)->get();

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

    public function updateStatusTransaction($id, Request $request)
    {
        // 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Selesai, 5 Transaksi dibatalkan
        $this->invalidValidResponse($request, [
            'status' => ['required', Rule::in([2, 3, 4, 5])],
        ]);

        $depot = Auth::user();
        $transaction = Transaction::where('depot_phone_number', $depot->phone_number)
            ->where('id', $id);

        if ($request->status == 2) {
            $transaction = $transaction->where('status', 1);
        } else if ($request->status == 3) {
            $transaction = $transaction->where('status', 2);
        } else if ($request->status == 4) {
            $transaction = $transaction->where('status', 3);
        } else if ($request->status == 5) {
            $transaction = $transaction->where('status', 1);
        }

        $transaction = $transaction->first();

        if ($transaction == null) {
            if ($transaction == null) {
                return $this->response(null, 'Transaction not found', 404);
            }
        }

        $transaction->status = (int)$request->status;
        $transaction->save();

        return $this->response(new DetailCollection($transaction), 'Success update status transaction');
    }
}

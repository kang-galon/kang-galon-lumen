<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Depot;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function addTransaction(Request $request)
    {
        $this->invalidValidResponse($request, [
            'depot_phone_number' => 'required|numeric|starts_with:+628',
            'client_location' => 'required',
            'gallon' => 'required|numeric',
        ]);

        $depot = Depot::where('phone_number', $request->depot_phone_number)->first();
        if ($depot == null) {
            return $this->response(null, 'Depot not found', 404);
        }

        $client = Auth::user();
        $transaction = Transaction::create([
            'client_phone_number' => $client->phone_number,
            'depot_phone_number' => $request->depot_phone_number,
            'client_location' => $request->client_location,
            'total_price' => $depot->price * $request->gallon,
            'gallon' => $request->gallon,
        ]);

        $transactionArray = json_decode($transaction, true);
        $transactionArray['total_price_description'] = 'Rp. ' . number_format($transaction->total_price);
        return $this->response($transactionArray, 'Success create transaction', 201);
    }

    public function getTransaction()
    {
        $client = Auth::user();
        $transactions = Transaction::where('client_phone_number', $client->phone_number)->get();
        foreach ($transactions as $transaction) {
            // 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Selesai
            if ($transaction['status'] == 1) {
                $transaction['status_description'] = 'Menunggu persetujuan';
            } else if ($transaction['status'] == 2) {
                $transaction['status_description'] = 'Mengambil galon';
            } else if ($transaction['status'] == 3) {
                $transaction['status_description'] = 'Mengantar galon';
            } else if ($transaction['status'] == 4) {
                $transaction['status_description'] = 'Transaksi selesai';
            }

            $transaction['total_price_description'] = 'Rp. ' . number_format($transaction->total_price);
        }

        return $this->response($transactions, 'Success get transaction');
    }

    public function getDetailTransaction($id)
    {
        $client = Auth::user();
        $transaction = Transaction::where('client_phone_number', $client->phone_number)
            ->where('id', $id)
            ->first();

        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        $transaction['total_price_description'] = 'Rp. ' . number_format($transaction->total_price);
        return $this->response($transaction, 'Success get detail transaction');
    }
}

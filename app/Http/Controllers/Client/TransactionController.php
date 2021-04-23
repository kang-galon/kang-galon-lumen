<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Transaction\AllCollection;
use App\Http\Resources\Client\Transaction\DetailCollection;
use App\Models\Depot;
use App\Models\Transaction;
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

        // check if transaction with status 1, 2, 3 doesn't exist
        $transactions = Transaction::where('client_phone_number', $client->phone_number)
            ->whereIn('status', [1, 2, 3])
            ->get();
        if ($transactions->count() > 0) {
            return $this->response(null, 'Failed create transaction', 400);
        }

        $transaction = Transaction::create([
            'client_phone_number' => $client->phone_number,
            'depot_phone_number' => $request->depot_phone_number,
            'client_location' => $request->client_location,
            'total_price' => $depot->price * $request->gallon,
            'gallon' => $request->gallon,
        ]);

        $transaction = Transaction::find($transaction->id);
        return $this->response(new AllCollection($transaction), 'Success create transaction', 201);
    }

    public function getTransaction()
    {
        $client = Auth::user();
        $transactions = Transaction::where('client_phone_number', $client->phone_number)->get();

        return $this->response(AllCollection::collection($transactions), 'Success get transaction');
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

        return $this->response(new DetailCollection($transaction), 'Success get detail transaction');
    }

    public function getCurrentTransaction()
    {
        $client = Auth::user();
        $transaction = Transaction::where('client_phone_number', $client->phone_number)
            ->whereIn('status', [1, 2, 3])
            ->first();


        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        return $this->response(new DetailCollection($transaction), 'Success get detail transaction');
    }
}

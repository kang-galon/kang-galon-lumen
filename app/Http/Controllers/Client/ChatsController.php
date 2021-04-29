<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Chats\TransactionCollection;
use App\Models\Chats;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatsController extends Controller
{
    public function getMessage()
    {
        // get message base on current transaction (status 1, 2, 3)
        $client = Auth::user();
        $transaction = Transaction::where('client_phone_number', $client->phone_number)
            ->whereIn('status', [1, 2, 3])
            ->first();

        return $this->response(new TransactionCollection($transaction), 'Success get detail transaction');
    }

    public function sendMessage(Request $request)
    {
        $this->invalidValidResponse($request, [
            'to' => 'required|numeric',
            'transaction_id' => 'required',
            'message' => 'required',
        ]);

        // check transaction id
        $transaction = Transaction::find($request->transaction_id);
        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        $client = Auth::user();

        Chats::create([
            'to' => $request->to,
            'sender' => $client->phone_number,
            'transaction_id' => $request->transaction_id,
            'message' => $request->message,
        ]);

        return $this->response(null, 'Success send message');
    }
}

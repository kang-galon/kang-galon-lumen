<?php

namespace App\Http\Controllers\Depot;

use App\Helper\FirebaseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Depot\Chats\ChatsCollection;
use App\Models\Chats;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * 1 Menunggu persetujuan, 2 Mengambil galon, 3 Mengantar galon, 4 Menunggu rating, 5 Selesai, 6 Transaksi dibatalkan
 */
class ChatsController extends Controller
{
    public function getMessage($transactionId)
    {
        // get message base on current transaction (status 1, 2, 3)
        $depot = Auth::user();
        $transaction = Transaction::where('id', $transactionId)
            ->where('depot_phone_number', $depot->phone_number)
            ->whereIn('status', [1, 2, 3])
            ->first();

        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        return $this->response(new ChatsCollection($transaction), 'Success get detail transaction');
    }

    public function sendMessage(Request $request)
    {
        $this->invalidValidResponse($request, [
            'transaction_id' => 'required',
            'message' => 'required',
        ]);

        // check transaction id
        $transaction = Transaction::where('id', $request->transaction_id)
            ->whereIn('status', [1, 2, 3])
            ->first();
        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        $depot = Auth::user();
        Chats::create([
            'to' => $transaction->client_phone_number,
            'sender' => $depot->phone_number,
            'transaction_id' => $request->transaction_id,
            'message' => $request->message,
        ]);

        // send notification to client
        $client = User::where('phone_number', $transaction->client_phone_number)
            ->first();
        FirebaseHelper::sendNotification($client->device_id, 'Ada pesan baru', 'Pesan dari depot ' . $depot->name);

        return $this->response(null, 'Success send message');
    }
}

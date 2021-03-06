<?php

namespace App\Http\Controllers\Client;

use App\Helper\FirebaseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Chats\ChatsCollection;
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
    public function getMessage()
    {
        // get message base on current transaction (status 1, 2, 3)
        $client = Auth::user();
        $transaction = Transaction::where('client_phone_number', $client->phone_number)
            ->whereIn('status', [1, 2, 3])
            ->first();

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

        $client = Auth::user();
        Chats::create([
            'to' => $transaction->depot_phone_number,
            'sender' => $client->phone_number,
            'transaction_id' => $request->transaction_id,
            'message' => $request->message,
        ]);

        // send notification to depot
        $depot = User::where('phone_number', $transaction->depot_phone_number)
            ->first();
        FirebaseHelper::sendNotification($depot->device_id, 'Ada pesan baru', 'Pesan dari pelanggan ' . $client->name);

        return $this->response(null, 'Success send message');
    }
}

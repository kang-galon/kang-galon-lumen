<?php

namespace App\Http\Controllers\Client;

use App\Helper\FirebaseHelper;
use App\Http\Controllers\Controller;
use App\Http\Resources\Client\Transaction\AllCollection;
use App\Http\Resources\Client\Transaction\DetailCollection;
use App\Models\Depot;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;

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

        // check if transaction with status 1, 2, 3, 4  doesn't exist
        $transactions = Transaction::where('client_phone_number', $client->phone_number)
            ->whereIn('status', [1, 2, 3, 4])
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

        // send notification to client
        FirebaseHelper::sendNotification($client->device_id, 'Berhasil checkout', 'Silahkan menunggu galon anda dijemput');

        // send notification to depot
        FirebaseHelper::sendNotification($depot->user->device_id, 'Ada pesanan', 'Ada pesanan baru sebanyak ' . $request->gallon . ' galon');

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
            ->whereIn('status', [1, 2, 3, 4])
            ->first();


        if ($transaction == null) {
            return $this->response(null, 'Transaction not found', 404);
        }

        return $this->response(new DetailCollection($transaction), 'Success get detail transaction');
    }

    public function testNotification()
    {
        $deviceId = 'dL1jVOshRVyT4HqilgFIP6:APA91bHZnjXyqrJIQ_EOW9uu0lxt1zoXDuTlaWfbOHfY7cZ6_zDfTjX59qGdUnZP0iYTQAYfmgkXpUssETjKoEFCch9znqI_gNrHN1jlMW1FyAe6AvmIJTngjrbv4rqU6SOk8FQGL8DL';
        $message = CloudMessage::withTarget('token', $deviceId)
            ->withNotification([
                'title' => 'test title heyaaa',
                'data' => 'test data heyaaa'
            ]);
        // ->withData(['title' => 'test data heyaaa']);

        Firebase::messaging()->send($message);
    }
}

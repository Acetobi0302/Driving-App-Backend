<?php

namespace App\Http\Controllers;


use App\Models\Log;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LogController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();

        $logs = Log::with('users:id,name')->where('booking_id', $validatedData['booking_id'])->get();
        return response()->json(['success' => true, 'data' => $logs]);
    }

    public function store($booking)
    {
        $user = auth()->user();
        $log = new Log;
        $log->booking_id = $booking->id;
        $log->change_date = Carbon::now('europe/berlin');
        $log->date = $booking->date;
        $log->start = $booking->start;
        $log->end = $booking->end;
        $log->car_id = $booking->car_id;
        $log->amount = $booking->amount;
        $log->type = 'create';
        $log->user_id =  $user->id;
        $log->save();
    }

    public function update($oldBooking, $booking, $isDelete)
    {
        $user = auth()->user();
        $changed = false;
        $log = new Log;
        $log->booking_id = $booking->id;
        $log->change_date = Carbon::now('europe/berlin');
        $ostart = Carbon::parse($oldBooking['start'])->format('Hi');
        $bstart = Carbon::parse($booking['start'])->format('Hi');

        if ($ostart != $bstart) {
            $log->start = $booking->start;
            $log->end = $booking->end;
            $changed = true;
        }
        if ($oldBooking['car_id'] != $booking['car_id']) {
            $log->car_id = $booking->car_id;
            $changed = true;
        }
        if ($oldBooking['amount'] != $booking['amount']) {
            $log->amount = $booking->amount;
            $changed = true;
        }
        if ($oldBooking['date'] != $booking['date']) {
            $log->date = $booking->date;
            $changed = true;
        }
        if ($changed || $isDelete) {
            $log->type = $isDelete ? 'delete' : 'update';
            $log->user_id =  $user->id;
            $log->save();
        }
    }
}

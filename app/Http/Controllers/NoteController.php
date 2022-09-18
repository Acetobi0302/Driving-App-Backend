<?php

namespace App\Http\Controllers;


use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NoteController extends Controller
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

        $notes = Note::with('users:id,name')->where('booking_id', $validatedData['booking_id'])->get();
        return response()->json(['success' => true, 'data' => $notes]);
    }

    public function store($booking)
    {
        $user = auth()->user();
        if ($booking['note'] == "" || $booking['note'] == null) {
            return false;
        }
        $note = new Note;
        $note->booking_id = $booking->id;
        $note->note = $booking->note;
        $note->user_id =  $user->id;
        $note->save();
    }

    public function delete(Request $request, $id)
    {
        $s = Note::find($id);
        if ($s) {
            $s->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => "note not found with id: $id."], 404);
    }
}

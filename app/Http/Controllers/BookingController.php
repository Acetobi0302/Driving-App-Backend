<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookingCollection;
use App\Http\Resources\StudentBookingCollection;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    protected $noteService;
    protected $logService;

    public function __construct(NoteController $noteService, LogController $logService)
    {
        $this->noteService = $noteService;
        $this->logService = $logService;
    }


    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date|before:end_date',
            'end_date' => 'required|date|after:start_date',
            'driver_id' => 'max:10',
            'student_id' => 'max:10',
            'car_id' => 'max:10',
            'deleted' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        // ['franchise','car','student','driver','course']
        $bookings = Booking::with(['driver:id,name', 'student:id,first_name,last_name', 'course', 'franchise:id,name', 'car:id,number_plate'])->whereBetween('date', [$validatedData['start_date'], $validatedData['end_date']]);

        if ($request->has('driver_id') && $validatedData['driver_id'] != null) {
            $bookings =  $bookings->where('driver_id', $validatedData['driver_id']);
        }
        if ($request->has('student_id') && $validatedData['student_id'] != null) {
            $bookings =  $bookings->where('student_id', $validatedData['student_id']);
        }
        if ($request->has('car_id') && $validatedData['car_id'] != null) {
            $bookings =  $bookings->where('car_id', $validatedData['car_id']);
        }
        if ($validatedData['deleted'] === "true") {
            $bookings =  $bookings->onlyTrashed()->get();
        } else {
            $bookings =  $bookings->get();
        }
        return new BookingCollection($bookings);
        // return response()->json(['success' => true, 'data' => $bookings]);
    }

    public function studentBookings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();
        // ['franchise','car','student','driver','course']
        $bookings = Booking::with(['driver:id,name', 'notecount', 'course', 'franchise:id,name'])->where('student_id', $validatedData['student_id'])->orderBy('start')->get();
        return new StudentBookingCollection($bookings);
        // return response()->json(['success' => true, 'data' => $bookings]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:course_art,id',
            'note' => 'max:255',
            'car_id' => 'required',
            'date' => 'required|date',
            'start' => 'required|date|before:end',
            'end' => 'required|date|after:start',
            'paid' => 'required|boolean',
            'amount' => 'max:10',
            'franchise_id' => 'required|exists:franchise,id',
            'user_id' => 'max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();

        if ($validatedData['amount'] != null) {
            $validatedData['paid_at'] = Carbon::now('europe/berlin');
            $validatedData['user_id'] = $user->id;
        }

        $slotAvailable = $this->slotAvailable($validatedData['franchise_id'], $validatedData['driver_id'], $validatedData['date'], $validatedData['start'], $validatedData['end'], 0);
        if (!$slotAvailable) {
            return response()->json(['success' => false, 'message' => 'Diese Zeitraum bereits von einem bestimmten Fahrer gebucht wurde. Wählen Sie bitte ein anderes Zeitraum oder einen anderen Fahrer aus.'], 422);
        }

        $carAvailable = $this->carAvailable($validatedData['franchise_id'], $validatedData['car_id'], $validatedData['date'], $validatedData['start'], $validatedData['end'], 0);
        if (!$carAvailable) {
            return response()->json(['success' => false, 'message' => 'Das Auto ist bereits für diese bestimmtes Zeitraum bei anderen Fahrer zugeteilt.'], 422);
        }

        $booking = Booking::Create($validatedData);
        $this->noteService->store($booking);
        $this->logService->store($booking);
        return response()->json(['success' => true, 'data' => $booking]);
    }

    public function storeBreak(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:users,id',
            'private' => 'required|max:1',
            'date' => 'required|date',
            'start' => 'required|date|before:end',
            'end' => 'required|date|after:start',
            'franchise_id' => 'required|exists:franchise,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();

        $slotAvailable = $this->slotAvailable($validatedData['franchise_id'], $validatedData['driver_id'], $validatedData['date'], $validatedData['start'], $validatedData['end'], 0);
        if (!$slotAvailable) {
            return response()->json(['success' => false, 'message' => 'Diese Zeitraum bereits von einem bestimmten Fahrer gebucht wurde. Wählen Sie bitte ein anderes Zeitraum oder einen anderen Fahrer aus.'], 422);
        }

        $booking = Booking::Create($validatedData);
        $this->noteService->store($booking);
        $this->logService->store($booking);
        return response()->json(['success' => true, 'data' => $booking]);
    }

    public function updateBreak(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:users,id',
            'private' => 'required|max:1',
            'date' => 'required|date',
            'start' => 'required|date|before:end',
            'end' => 'required|date|after:start',
            'franchise_id' => 'required|exists:franchise,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();

        $slotAvailable = $this->slotAvailable($validatedData['franchise_id'], $validatedData['driver_id'], $validatedData['date'], $validatedData['start'], $validatedData['end'], $id);
        if (!$slotAvailable) {
            return response()->json(['success' => false, 'message' => 'Diese Zeitraum bereits von einem bestimmten Fahrer gebucht wurde. Wählen Sie bitte ein anderes Zeitraum oder einen anderen Fahrer aus.'], 422);
        }

        $oldbooking = Booking::find($id);

        $booking = Booking::find($id);
        if ($booking) {
            $booking->update($validatedData);
            $this->noteService->store($booking);
            $fdh = $this->logService->update($oldbooking, $booking, false);
            return response()->json(['success' => true, 'data' => $fdh]);
        }
        return response()->json(['success' => false, 'message' => "Booking not found with id $id."], 404);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required|exists:users,id',
            'student_id' => 'required|exists:students,id',
            'course_id' => 'required|exists:course_art,id',
            'note' => 'max:255',
            'car_id' => 'required',
            'date' => 'required|date',
            'start' => 'required|date|before:end',
            'end' => 'required|date|after:start',
            'paid' => 'required|boolean',
            'amount' => 'max:10',
            'franchise_id' => 'required|exists:franchise,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();

        $slotAvailable = $this->slotAvailable($validatedData['franchise_id'], $validatedData['driver_id'], $validatedData['date'], $validatedData['start'], $validatedData['end'], $id);
        if (!$slotAvailable) {
            return response()->json(['success' => false, 'message' => 'Diese Zeitraum bereits von einem bestimmten Fahrer gebucht wurde. Wählen Sie bitte ein anderes Zeitraum oder einen anderen Fahrer aus.'], 422);
        }

        $carAvailable = $this->carAvailable($validatedData['franchise_id'], $validatedData['car_id'], $validatedData['date'], $validatedData['start'], $validatedData['end'], $id);
        if (!$carAvailable) {
            return response()->json(['success' => false, 'message' => 'Das Auto ist bereits für diese bestimmtes Zeitraum bei anderen Fahrer zugeteilt.'], 422);
        }
        $oldbooking = Booking::find($id);
        if ($validatedData['amount'] != null && $validatedData['amount'] != $oldbooking->amount) {
            $validatedData['paid_at'] = Carbon::now('europe/berlin');
            $validatedData['user_id'] = $user->id;
        }
        $booking = Booking::find($id);
        if ($booking) {
            $booking->update($validatedData);
            $this->noteService->store($booking);
            $fdh = $this->logService->update($oldbooking, $booking, false);
            return response()->json(['success' => true, 'data' => $fdh]);
        }
        return response()->json(['success' => false, 'message' => "Booking not found with id $id."], 404);
    }

    public function accounts(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'required|exists:users,id',
            'role' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->messages()->first()], 422);
        }

        $validatedData = $validator->safe()->all();

        $user = auth()->user();
        if ($user->role != 'admin') {
            $validatedData['user_id'] = $user->id;
            $validatedData['role'] = $user->role;
        }
        if ($validatedData['role'] == 'driver') {
            $bookings = Booking::with(['driver:id,name', 'student:id,first_name,last_name', 'course', 'franchise:id,name', 'car:id,number_plate'])->where('driver_id', $validatedData['user_id'])->where('private', 0)->whereBetween('date', [$validatedData['start_date'], $validatedData['end_date']])->get();
        } else {
            $bookings = Booking::with(['driver:id,name', 'student:id,first_name,last_name', 'course', 'franchise:id,name', 'car:id,number_plate'])->where('user_id', $validatedData['user_id'])->whereBetween('paid_at', [$validatedData['start_date'] . " 00:00:00", $validatedData['end_date'] . " 23:59:59"])->get();
        }
        return new BookingCollection($bookings);
    }

    public function delete(Request $request, $id)
    {
        $cm = Booking::find($id);
        if ($cm) {
            $this->logService->update($cm, $cm, true);
            $cm->delete();
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => "Booking not found with id $id."], 404);
    }

    private function slotAvailable($frenchiseId, $driver_id, $date, $start, $end, $id)
    {
        $slots = Booking::where('franchise_id', $frenchiseId)->where('driver_id', $driver_id)->where('date', $date)->where(function ($query) use ($start, $end, $id) {
            $query->whereBetween('start', [$start, $end]);
            $query->orWhereBetween('end', [$start, $end]);
            $query->orWhere('start', '<', $start);
            $query->Where('end', '>', $start);
        })->where('id', '!=', $id)->count();
        return !($slots > 0);
    }

    private function carAvailable($frenchiseId, $car_id, $date, $start, $end, $id)
    {
        $slots = Booking::where('franchise_id', $frenchiseId)->where('car_id', $car_id)->where('date', $date)->where(function ($query) use ($start, $end, $id) {
            $query->whereBetween('start', [$start, $end]);
            $query->orWhereBetween('end', [$start, $end]);
            $query->orWhere('start', '<=', $start);
            $query->Where('end', '>=', $start);
        })->where('id', '!=', $id)->count();
        return !($slots > 0);
    }
}

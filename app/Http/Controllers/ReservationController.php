<?php

namespace App\Http\Controllers;

use App\Http\Resources\seatsResource;
use App\Models\Reservation;
use App\Models\Trip;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    private $user_id;

    public function __construct(Request $request)
    {
        $user = User::where('api_token', $request->user_token)->first();
        $this->user_id = $user->id;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $start_station = DB::table('trip_stations')->where('trip_id', $request->trip_id)->where('station_id', $request->start_station)->value('stop_number');
            $end_station = DB::table('trip_stations')->where('trip_id', $request->trip_id)->where('station_id', $request->end_station)->value('stop_number');
            $busy_seat = Reservation::where('seat_id', $request->seat_id)
                ->where(function ($query) use ($start_station, $end_station) {
                    $query->where(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.start_station_id', '<=', $start_station)->where('reservations.end_station_id', '>=', $end_station);
                    })->orWhere(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.start_station_id', '>', $start_station)->where('reservations.start_station_id', '<', $end_station);
                    })->orWhere(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.end_station_id', '>', $start_station)->where('reservations.end_station_id', '<', $end_station);
                    });
                })
                ->select('reservations.*')->first();
            if (isset($busy_seat)) {
                return json_encode(['message' => 'Seat not Available']);
            }
            Reservation::create([
                'trip_id' => $request->trip_id,
                'seat_id' => $request->seat_id,
                'start_station_id' => $start_station,
                'end_station_id' => $end_station,
                'user_id' => $this->user_id,
            ]);
            return response()->json(['message' => 'Seat reserved successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

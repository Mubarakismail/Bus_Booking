<?php

namespace App\Http\Controllers;

use App\Http\Resources\seatsResource;
use App\Models\Reservation;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SeatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $start_station = DB::table('trip_stations')->where('trip_id', $request->trip_id)->where('station_id', $request->start_station)->value('stop_number');
        $end_station = DB::table('trip_stations')->where('trip_id', $request->trip_id)->where('station_id', $request->end_station)->value('stop_number');
        $busy_seats = Reservation::where('reservations.trip_id', $request->trip_id)
            ->where(function ($query) use ($start_station, $end_station) {
                $query->where(function ($query1) use ($start_station, $end_station) {
                    $query1->where('reservations.start_station_id', '<=', $start_station)->where('reservations.end_station_id', '>=', $end_station);
                })->orWhere(function ($query1) use ($start_station, $end_station) {
                    $query1->where('reservations.start_station_id', '>', $start_station)->where('reservations.start_station_id', '<', $end_station);
                })->orWhere(function ($query1) use ($start_station, $end_station) {
                    $query1->where('reservations.end_station_id', '>', $start_station)->where('reservations.end_station_id', '<', $end_station);
                });
            })->select('reservations.seat_id as id')
            ->get();

        $available_seats_reservation = Reservation::where('reservations.trip_id', $request->trip_id)
            ->where(function ($query) use ($start_station, $end_station) {
                $query->Where(function ($query1) use ($start_station, $end_station) {
                    $query1->where('reservations.start_station_id', '>', $start_station)->where('reservations.start_station_id', '>=', $end_station);
                })->orWhere(function ($query1) use ($start_station, $end_station) {
                    $query1->where('reservations.start_station_id', '>', $end_station)->where('reservations.end_station_id', '>', $start_station);
                });
            })
            ->select('reservations.seat_id as id')
            ->get();

        $all_seats = Seat::join('buses', 'seats.bus_id', 'buses.id')
            ->join('trip_buses', 'trip_buses.bus_id', 'buses.id')
            ->join('trips', 'trip_buses.trip_id', 'trips.id')
            ->where('trips.id', $request->trip_id)
            ->groupBy('seats.id')
            ->select('seats.id')
            ->get();


        $tst1 = $available_seats_reservation->diff($busy_seats);
        $tst2 = $all_seats->diff($busy_seats);
        $tst2 = $tst2->merge($tst1);

        $data = [
            'start_station' => $request->start_station,
            'end_station' => $request->end_station,
            'trip_id' => $request->trip_id,
            'seats' => $tst2->all(),
        ];

        return new seatsResource($data);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

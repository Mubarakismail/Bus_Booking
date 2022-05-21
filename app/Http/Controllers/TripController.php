<?php

namespace App\Http\Controllers;

use App\Http\Resources\TripsResource;
use App\Models\Reservation;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $busy_seats = Trip::where('trips.departure_time', '>=', '2022-05-17 00:00:00')
            ->join('reservations', 'reservations.trip_id', 'trips.id')
            ->where(function ($query) use ($request) {
                $query->where(function ($query1) use ($request) {
                    $query1->where('reservations.start_station_id', '<=', $request->start_station)->where('reservations.end_station_id', '>=', $request->end_station);
                })->orWhere(function ($query1) use ($request) {
                    $query1->where('reservations.start_station_id', '>=', $request->start_station)->where('reservations.end_station_id', '<', $request->end_station);
                })->orWhere(function ($query1) use ($request) {
                    $query1->where('reservations.start_station_id', '>=', $request->start_station)->where('reservations.end_station_id', '>', $request->end_station);
                })->orWhere(function ($query1) use ($request) {
                    $query1->where('reservations.end_station_id', '<=', $request->start_station)->where('reservations.end_station_id', '>', $request->end_station);
                });
            })
            ->join('trip_stations', 'trip_stations.trip_id', 'trips.id')
            ->join('stations', 'trip_stations.station_id', 'stations.id')
            ->select('trips.*')->get();


        $available_seats_reservation = Trip::where('trips.departure_time', '>=', '2022-05-20 00:00:00')
            ->join('reservations', 'reservations.trip_id', 'trips.id')
            ->where(function ($query) use ($request) {
                $query->Where(function ($query1) use ($request) {
                    $query1->where('reservations.start_station_id', '>', $request->start_station)->where('reservations.start_station_id', '>=', $request->end_station);
                })->orWhere(function ($query1) use ($request) {
                    $query1->where('reservations.start_station_id', '>', $request->end_station)->where('reservations.end_station_id', '>', $request->start_station);
                });
            })
            ->join('trip_stations', 'trip_stations.trip_id', 'trips.id')
            ->join('stations', 'trip_stations.station_id', 'stations.id')
            ->select('trips.*')->get();

        $all_seats = Trip::where('trips.departure_time', '>=', '2022-05-20 00:00:00')
            ->join('trip_buses', 'trip_buses.trip_id', 'trips.id')
            ->join('buses', 'trip_buses.bus_id', 'buses.id')
            ->join('seats', 'buses.id', 'seats.bus_id')
            ->select('trips.*')
            ->groupBy('trips.id')
            ->groupBy('trips.departure_time')
            ->groupBy('trips.arrival_time')
            ->groupBy('trips.type')
            ->groupBy('trips.created_at')
            ->groupBy('trips.updated_at')
            ->get();


        $tst1 = $available_seats_reservation->diff($all_seats);
        $tst2 = $all_seats->diff($busy_seats);
        $tst2 = $tst2->merge($tst1);

        $data = [
            'start_station_id' => intVal($request->start_station),
            'end_station_id' => intVal($request->end_station),
            'trips' => $tst2->all(),
        ];

        return new TripsResource($data);
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
     * @param  \App\Models\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function edit(Trip $trip)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {
        //
    }
}

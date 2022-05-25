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
        $all_seats = [];
        $trips = $this->getTripsHaveStations($request);
        $busy_seats = collect();
        foreach ($trips as $trip) {
            $busy_seats->push(Trip::where('trips.departure_time', '>=', $request->time)
                ->join('reservations', 'reservations.trip_id', 'trips.id')
                ->where(function ($query) use ($trip) {
                    $start_station = $trip['start_stop_number'];
                    $end_station = $trip['end_stop_number'];
                    $query->where(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.start_station_id', '<=', $start_station)->where('reservations.end_station_id', '>=', $end_station);
                    })->orWhere(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.start_station_id', '>', $start_station)->where('reservations.start_station_id', '<', $end_station);
                    })->orWhere(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.end_station_id', '>', $start_station)->where('reservations.end_station_id', '<', $end_station);
                    });
                })
                ->join('trip_stations', 'trip_stations.trip_id', 'trips.id')
                ->join('stations', 'trip_stations.station_id', 'stations.id')->groupBy('trips.id')->pluck('trips.id')->flatten()->toArray());
            $all_seats[] = $trip['id'];
        }
        $busy_seats = $busy_seats->flatten()->toArray();

        $available_seats_reservation = collect();
        foreach ($trips as $trip) {
            $available_seats_reservation->push(Trip::where('trips.departure_time', '>=', $request->time)
                ->join('reservations', 'reservations.trip_id', 'trips.id')
                ->where(function ($query) use ($trip) {
                    $start_station = $trip['start_stop_number'];
                    $end_station = $trip['end_stop_number'];
                    $query->Where(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.start_station_id', '>', $start_station)->where('reservations.start_station_id', '>=', $end_station);
                    })->orWhere(function ($query1) use ($start_station, $end_station) {
                        $query1->where('reservations.start_station_id', '>', $end_station)->where('reservations.end_station_id', '>', $start_station);
                    });
                })
                ->join('trip_stations', 'trip_stations.trip_id', 'trips.id')
                ->join('stations', 'trip_stations.station_id', 'stations.id')
                ->groupBy('trips.id')->pluck('trips.id')->flatten()->toArray());
        }
        $available_seats_reservation = $available_seats_reservation->flatten()->toArray();


        $tst2 = array_diff($all_seats, $busy_seats);
        $tst2 = array_merge($tst2, $available_seats_reservation);


        $data = [
            'start_station_id' => intVal($request->start_station),
            'end_station_id' => intVal($request->end_station),
            'trips' => Trip::whereIn('id', $tst2)->get(),
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

    public function getTripsHaveStartStation($request)
    {
        return Trip::join('trip_stations', 'trips.id', 'trip_stations.trip_id')->where('station_id', $request->start_station)
            ->groupBy('trips.id')
            ->groupBy('trips.departure_time')
            ->groupBy('trips.arrival_time')
            ->groupBy('trips.type')
            ->groupBy('trips.created_at')
            ->groupBy('trips.updated_at')
            ->groupBy('trip_stations.stop_number')
            ->select(['trips.*', 'trip_stations.stop_number as start_stop_number'])
            ->get();
    }
    public function getTripsHaveEndStation($request)
    {
        return Trip::join('trip_stations', 'trips.id', 'trip_stations.trip_id')->where('station_id', $request->end_station)
            ->groupBy('trips.id')
            ->groupBy('trips.departure_time')
            ->groupBy('trips.arrival_time')
            ->groupBy('trips.type')
            ->groupBy('trips.created_at')
            ->groupBy('trips.updated_at')
            ->groupBy('trip_stations.stop_number')
            ->select(['trips.*', 'trip_stations.stop_number as end_stop_number'])
            ->get();
    }

    public function getTripsHaveStations($request)
    {
        $trip_start_stations = $this->getTripsHaveStartStation($request);
        $trip_end_stations = $this->getTripsHaveEndStation($request);
        $trips_intersection = $trip_start_stations->whereIn('id', $trip_end_stations->pluck('id')->toArray());
        $trips = collect([]);
        // to merge stops_number 
        foreach ($trips_intersection as $trip) {
            $tripCollection = collect($trip);
            $tripCollection = $tripCollection->merge(
                $trip_end_stations->where('id', $trip->id)->first()->only('end_stop_number')
            );
            if ($tripCollection['end_stop_number'] > $tripCollection['start_stop_number']) {
                $trips->push($tripCollection);
            }
        }
        return $trips->toArray();
    }
}

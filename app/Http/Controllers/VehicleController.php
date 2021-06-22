<?php

namespace App\Http\Controllers;

use App\Models\Sparepart;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, [
                'type'         => 'required',
                'merk'         => 'required',
                'name'         => 'required',
                'year_release' => 'required',
                'year_bought'  => 'required',
                'stock'        => 'required',
                'condition'    => 'required'
            ]);

            $createVehicle = Vehicle::create([
                'type'         => $request->type,
                'merk'         => $request->merk,
                'name'         => $request->name,
                'year_release' => $request->year_release,
                'year_bought'  => $request->year_bought,
                'stock'        => $request->stock,
                'condition'    => $request->condition
            ]);

            if ($createVehicle) {
                if ($request->sparepart) {
                    foreach ($request->sparepart as $key => $value) {
                        $datasparepart = array(
                            'type_sparepart' => $value['type_sparepart'],
                            'name_sparepart' => $value['name_sparepart'],
                            'condition_sparepart' => $value['condition_sparepart'],
                            'vehicles_id' => $createVehicle->id
                        );
                        Sparepart::create($datasparepart);

                        $stockSparepart = Warehouse::where('name_sparepart', '=', $value['name_sparepart'])->get();
                        foreach ($stockSparepart as $key => $val) {
                            Warehouse::where('name_sparepart', '=', $value['name_sparepart'])->update([
                                'stock_sparepart' => $val->stock_sparepart - 1,
                            ]);
                        }
                    }
                }

                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => "Vehicle Created Succes"
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'status'    => 404,
                    'message'   => "Vehicle Created Failed"
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Vehicle $vehicle)
    {
        return response()->json([
            'status'    => 200,
            'message'   => $vehicle->get()
        ]);
    }

     /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function detail($id)
    {
        $vehicle = Vehicle::where('id', '=', $id)->first();
        $sparepart = Sparepart::where('vehicles_id', '=', $vehicle->id)->get();
        $response = [
            'vehicles'  => $vehicle,
            'sparepart' => $sparepart
        ];

        return response()->json([
            'status'    => 200,
            'message'   => $response
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $this->validate($request, [
                'type'         => 'required',
                'merk'         => 'required',
                'name'         => 'required',
                'year_release' => 'required',
                'year_bought'  => 'required',
                'stock'        => 'required',
                'condition'    => 'required'
            ]);

            $vehicle = Vehicle::where('id', '=', $id)->first();
            // dd($vehicle); die;

            $updateVehicle = [
                'type'         => $request->type,
                'merk'         => $request->merk,
                'name'         => $request->name,
                'year_release' => $request->year_release,
                'year_bought'  => $request->year_bought,
                'stock'        => $request->stock,
                'condition'    => $request->condition
            ];

            $vehicle->update($updateVehicle);

            if ($updateVehicle) {
                $sparepart = Sparepart::where('vehicles_id', $vehicle->id);
                // dd($sparepart); die;
                $sparepart->delete();

                if ($request->sparepart) {
                    foreach ($request->sparepart as $key => $value) {
                        $datasparepart = array(
                            'type_sparepart' => $value['type_sparepart'],
                            'name_sparepart' => $value['name_sparepart'],
                            'condition_sparepart' => $value['condition_sparepart'],
                            'vehicles_id' => $vehicle->id
                        );
                        Sparepart::create($datasparepart);

                        $stockSparepart = Warehouse::where('name_sparepart', '=', $value['name_sparepart'])->get();
                        foreach ($stockSparepart as $key => $val) {
                            Warehouse::where('name_sparepart', '=', $value['name_sparepart'])->update([
                                'stock_sparepart' => $val->stock_sparepart - 1,
                            ]);
                        }
                    }
                }

                DB::commit();

                return response()->json([
                    'status'    => 200,
                    'message'   => "Vehicle Updated Succes"
                ]);
            } else {
                DB::rollback();
                return response()->json([
                    'status'    => 404,
                    'message'   => "Vehicle Updated Failed"
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicle = Vehicle::find($id);
        if (isset($vehicle->id)) {
            $sparepart = Sparepart::where('vehicles_id', $vehicle->id);
            $sparepart->delete();
            $vehicle->delete();

            return response()->json([
                'status'    => 200,
                'message'   => "Vehicle Deleted Succes"
            ]);
        } else {
            return response()->json([
                'status'    => 404,
                'message'   => "Vehicle Deleted Failed"
            ]);
        }
    }
}

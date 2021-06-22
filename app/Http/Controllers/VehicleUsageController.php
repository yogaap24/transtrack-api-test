<?php

namespace App\Http\Controllers;

use App\Models\VehicleUsage;
use Illuminate\Http\Request;

class VehicleUsageController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'vehicles_id'  => 'required',
         ]);

         VehicleUsage::create([
             'vehicles_id'   => $request->vehicles_id,
             'used_on_day'   => $request->used_on_day,
             'used_on_month' => $request->used_on_month,
             'user_on_year'  => $request->user_on_year,
         ]);

         return response()->json([
             'status'    => 200,
             'message'   => "Vehicle Usage Created Succes"
         ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\VehicleUsage  $vehicleUsage
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleUsage $vehicleUsage)
    {
        return response()->json([
            'status'    => 200,
            'message'   => $vehicleUsage->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\VehicleUsage  $vehicleUsage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'vehicles_id'  => 'required',
         ]);

         VehicleUsage::where('id', $id)->update([
             'vehicles_id'   => $request->vehicles_id,
             'used_on_day'   => $request->used_on_day,
             'used_on_month' => $request->used_on_month,
             'user_on_year'  => $request->user_on_year,
         ]);

         return response()->json([
             'status'    => 200,
             'message'   => "Vehicle Usage Update Succes"
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\VehicleUsage  $vehicleUsage
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $vehicleUsage = VehicleUsage::find($id);
        $vehicleUsage->delete();

        return response()->json([
            'status'    => 200,
            'message'   => "Vehicle Usage Deleted Succes"
        ]);
    }
}

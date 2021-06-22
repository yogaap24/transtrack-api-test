<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
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
           'name_sparepart'  => 'required',
           'stock_sparepart' => 'required',
        ]);

        Warehouse::create([
            'name_sparepart'  => $request->name_sparepart,
            'stock_sparepart' => $request->stock_sparepart,
        ]);

        return response()->json([
            'status'    => 200,
            'message'   => "Warehouse Created Succes"
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function show(Warehouse $warehouse)
    {
        return response()->json([
            'status'    => 200,
            'message'   => $warehouse->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name_sparepart'  => 'required',
            'stock_sparepart' => 'required',
         ]);

         Warehouse::where('id', $id)->update([
             'name_sparepart'  => $request->name_sparepart,
             'stock_sparepart' => $request->stock_sparepart,
         ]);

         return response()->json([
             'status'    => 200,
             'message'   => "Warehouse Updated Succes"
         ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $warehouse = Warehouse::find($id);
        $warehouse->delete();

        return response()->json([
            'status'    => 200,
            'message'   => "Warehouse Deleted Succes"
        ]);
    }
}

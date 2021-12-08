<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        // $min_produktivitas = User::min('id');
        // $user = User::inRandomOrder()->where('role', 'driver')->where('is_active', '1')->where('is_ready', '1')->first();
        // $order = Order::with('driver')->get();

        $driver_id = request('driver_id');

        if ($driver_id) {
            $order = Order::with('user')->where('driver_id', $driver_id)->where('is_done', '0')->first();
        }

        return response()->json([
            'message' => 'Program fetched',
            'status'    => 200,
            // 'foods' => $user,
            'order' => $order,
        ]);
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
        $driver = User::inRandomOrder()->where('role', 'driver')->where('is_active', '1')->where('is_ready', '1')->first();

        die($driver);

        $order = Order::create([
            'user_id'       => $request->user_id,
            'driver_id'     => $driver->id,
            'type'          => $request->type,

            'lat_jemput'         => $request->lat_jemput,
            'lng_jemput'         => $request->lng_jemput,
            'desc_jemput'         => $request->desc_jemput,

            'lat_tujuan'         => $request->lat_tujuan,
            'lng_tujuan'         => $request->lng_tujuan,
            'desc_tujuan'         => $request->desc_tujuan,

            'jarak'         => $request->jarak,
            'harga'         => $request->harga,

            'is_done'           => false

        ]);

        // return responserSuccess('Order', 200, $order);
        return response()->json([
            'message' => 'Successfully',
            'status'    => 200,
            // 'foods' => $user,
            'order' => $order,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }

    function get_order(Request $request, $id)
    {
        $order = Order::find($id);
        $order->is_get = $request->is_get;
        $order->save();

        return response()->json([
            'Message' => 'Order successfully deleted',
            'status'    => 200,
            'order'      => $order
        ]);
    }
}

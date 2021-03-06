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

        $order = 'Kosong';
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

        $driver_id = 0;
        if ($driver) {
            $driver_id = $driver->id;

            $user = User::find($driver_id);
            $user->is_ready = 0;
            $user->save();
        }
        // die($driver_id);


        $order = Order::create([
            'user_id'       => $request->user_id,
            'driver_id'     => $driver_id,
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
    public function show($id)
    {
        //
        $order = Order::with('driver')->first();
        return response()->json([
            'message' => 'Program fetched',
            'status'    => 200,
            // 'foods' => $user,
            'order' => $order,
        ]);
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

    //sudah di push = buatmi di androidnya
    public function cekOrder()
    {
        // die('masuk');
        $user_id = request('user_id');
        $order = Order::where('user_id', $user_id)->where('is_done', '0')->first();

        if ($order) {
            $available = true;
        } else {
            $available = false;
        }
        return response()->json([
            'Message' => 'Order available',
            'status'    => 200,
            'available' => $available,
            'order'      => $order
        ]);
    }

    public function changeDriver($id)
    {
        $driver = User::inRandomOrder()->where('role', 'driver')->where('is_active', '1')->where('is_ready', '1')->first();
        $order = Order::find($id);

        if ($order) {
            $order->driver_id = $driver->id;
            $order->save();
            return response()->json([
                'Message' => 'Driver successfully change',
                'status'    => 200,
                'order'      => $order
            ]);
        } else {

            return response()->json([
                'Message' => 'Driver failed change',
                'status'    => 500
            ]);
        }
    }
}

// http://api-aco.scrollupstudio.com/api/
// http://localhost:8000/api/
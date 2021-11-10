<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\FoodResource;
use App\Models\Food;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\New_;
use Symfony\Component\Console\Input\Input;

class FoodApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $user_id = request('user_id');
        $search = request('search');
        if ($user_id) {
            if ($search) {
                $data = Food::latest()
                    ->where('user_id', $user_id)
                    ->where('name', 'LIKE', "%$search%")
                    ->get();
            } else {
                $data = Food::latest()->where('user_id', $user_id)->get();
            }
        } else {
            if ($search) {
                $data = Food::latest()
                    ->where('name', 'LIKE', "%$search%")
                    ->get();
            } else {
                $data = Food::latest()->get();
            }
        }

        return response()->json([
            'message' => 'Program fetched',
            'status'    => 200,
            'foods' => FoodResource::collection($data),
        ]);
    }

    function getByUser($user_id)
    {
        $foods = DB::table('food')->where('user_id', $user_id)->get();
        return response()->json([
            'message' => 'Program fetched',
            'status'    => 200,
            'foods' => FoodResource::collection($foods),
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
        // $validator = Validator::make($request->all(), [

        // ]);

        // return response()->json($request);
        // die;

        $food = Food::create([
            'user_id'       => $request->user_id,
            'name'          => $request->name,
            // 'gambar'        => $request->gambar,
            'harga'         => $request->harga,
            'desc'          => $request->desc,
            'is_ready'      => $request->is_ready,
            'kategori'      => $request->kategori,
        ]);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $file_name = time() . "-" . $gambar->getClientOriginalName();

            $storage = 'uploads/foods/';
            $gambar->move($storage, $file_name);
            $food['gambar'] = $storage . $file_name;
        } else {
            $food['gambar'] = '';
        }

        return response()->json([
            'Message' => 'Food successfully created',
            'status'    => 200,
            'foods' => new FoodResource($food)
        ]);
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
        $food = Food::find($id);
        if (is_null($food)) {
            return response()->json('Data not found', 404);
        }
        return response()->json([new FoodResource($food)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        //
        // die($request);

        // $data = [
        //     // 'user_id'       => $food->user_id,
        //     'name'          => $request->name,
        //     // 'gambar'        => $request->gambar,
        //     'harga'         => $request->harga,
        //     'desc'          => $request->desc,
        //     'is_ready'      => $request->is_ready,
        //     'kategori'      => $request->kategori,
        // ];

        $food->user_id = $request->user_id;
        $food->name = $request->name;
        $food->desc = $request->desc;
        $food->harga = $request->harga;
        $food->is_ready = $request->is_ready;
        $food->kategori = $request->kategori;

        // return response()->json($request);
        // die();

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $file_name = time() . "-" . $gambar->getClientOriginalName();

            $storage = 'uploads/foods/';
            $gambar->move($storage, $file_name);
            $food['gambar'] = $storage . $file_name;
        } else {
            $food['gambar'] = $food->gambar;
        }

        $food->save();

        return response()->json([
            'Message'   => 'Food successfully updated',
            'status'    => 200,
            'food'      => new FoodResource($food)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        //
        if ($food->gambar != '') {
            unlink($food->gambar);
        }
        $food->delete();
        return response()->json([
            'Message' => 'Food successfully deleted',
            'status'    => 200
        ]);
    }

    function is_ready(Request $request, $id)
    {

        $food = Food::find($id);

        $food->is_ready = $request->is_ready;
        $food->save();

        return response()->json([
            'Message' => 'Food successfully deleted',
            'status'    => 200,
            'food'      => $food
        ]);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Models\Program;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProgramResource;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    //

    function index()
    {
        $data = Program::latest()->get();
        return response()->json([
            ProgramResource::collection($data), 'Program fetched'
        ]);
    }

    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string|max:225',
            'desc'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $program = Program::create([
            'name'  => $request->name,
            'desc'  => $request->desc
        ]);

        return response()->json([
            'Program successfully', new ProgramResource($program)
        ]);
    }

    function show($id)
    {
        $program = Program::find($id);
        if (is_null($program)) {
            return response()->json('Data not found', 404);
        }
        return response()->json([new ProgramResource($program)]);
    }

    function update(Request $request, Program $program)
    {
        $validator = Validator::make($request->all(), [
            'name'      => 'required|string|max:255',
            'desc'      => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $program->name = $request->name;
        $program->desc = $request->desc;
        $program->save();

        return response()->json(['Program updated successfully.', new ProgramResource($program)]);
    }

    function destroy(Program $program)
    {
        $program->delete();
        return response()->json('Program deleted successfully');
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);
        if ($validator->fails()) {
            return sendCustomResponse($validator->messages()->first(),  'error', 500);
        }
        $uploadFolder = 'users';
        $image = $request->file('image');
        $image_uploaded_path = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = array(
            "image_name" => basename($image_uploaded_path),
            "image_url" => Storage::disk('public')->url($image_uploaded_path),
            "mime" => $image->getClientMimeType()
        );
        return sendCustomResponse('File Uploaded Successfully', 'success',   200, $uploadedImageResponse);
    }
}

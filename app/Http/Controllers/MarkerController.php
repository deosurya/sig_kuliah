<?php
namespace App\Http\Controllers;

use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    public function index()
    {
        $markers = Marker::all();
        return response()->json($markers);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude'   => 'required|numeric',
            'longitude'  => 'required|numeric',
            'keterangan' => 'nullable|string',
        ]);

        $marker = Marker::create($validated);

        return response()->json([
            'message' => 'Marker created successfully',
            'marker'  => $marker,
        ]);
    }

    public function destroy(Marker $marker)
    {
        $marker->delete();

        return response()->json([
            'message' => 'Marker deleted successfully',
        ]);
    }
}

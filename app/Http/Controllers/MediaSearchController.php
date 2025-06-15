<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\Parameter;
use App\Models\Document;
use Illuminate\Support\Facades\Log;

class MediaSearchController extends Controller
{
    public function filters()
    {
        try {
            $areas = Area::select('id', 'name')->get()->toArray() ?? [];
            $years = Document::select('year')->distinct()->orderBy('year', 'desc')->pluck('year')->toArray() ?? [];
            
            return response()->json([
                'areas' => $areas,
                'years' => $years,
            ]);
        } catch (\Exception $e) {
            \Log::error('Filters error: ' . $e->getMessage());
            return response()->json([
                'areas' => [],
                'years' => []
            ]);
        }
    }

    public function parameters(Request $request)
    {
        try {
            $parameters = Parameter::when($request->area_id, function($q) use ($request) {
                    $q->where('area_id', $request->area_id);
                })
                ->select('id', 'name')
                ->get()
                ->toArray();

            return response()->json([
                'parameters' => $parameters,
            ]);
        } catch (\Exception $e) {
            \Log::error('Parameters error: ' . $e->getMessage());
            return response()->json([
                'parameters' => []
            ]);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Document::query()->with(['parameter', 'area']);
    
            // Get the input values properly from the request
            $areaId = $request->input('area');
            $parameterId = $request->input('parameter');
            $year = $request->input('year');
            $searchQuery = $request->input('query');
    
            if ($areaId) {
                $query->where('area_id', $areaId);
            }
            if ($parameterId) {
                $query->where('parameter_id', $parameterId);
            }
            if ($year) {
                $query->where('year', $year);
            }
            if ($searchQuery) {
                $query->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchQuery) . '%']);
            }
    
            $records = $query->limit(30)->get()->map(function ($doc) {
                $extension = strtolower(pathinfo($doc->path, PATHINFO_EXTENSION));
                return [
                    'id' => $doc->id,
                    'name' => $doc->title,
                    'relative_path' => $doc->path,
                    'is_pdf' => $extension === 'pdf',
                    'is_image' => in_array($extension, ['jpg', 'jpeg', 'png', 'gif']),
                    'year' => $doc->year,
                    'parameter_name' => optional($doc->parameter)->name,
                    'area_name' => optional($doc->area)->name
                ];
            });
    
            return response()->json([
                'success' => true,
                'records' => $records
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Search failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
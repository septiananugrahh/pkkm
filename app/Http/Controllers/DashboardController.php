<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Node;
use App\Models\Year;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $defaultYear = Year::where('year', now()->year)->first();
        $year = $request->input('year');

        // Jika tidak ada tahun yang dipilih dari request, gunakan ID dari tahun saat ini
        if (!$year) {
            $year = $defaultYear ? $defaultYear->id : null;
        }

        $nodes = Node::with(['children.children.children'])
            ->where('year_id', $year)
            ->get();

        $totalNodes = $nodes->count();

        $totalFiles = File::whereHas('node', function ($q) use ($year) {
            $q->where('year_id', $year);
        })->count();

        $totalYears = Year::count();

        // Hitung persentase selesai dari leaf
        $leafNodes = Node::where('year_id', $year)
            ->whereDoesntHave('children')
            ->withCount('files')
            ->get();

        $completed = $leafNodes->where('is_completed', true)->count();
        $completionPercentage = $leafNodes->count() > 0
            ? round(($completed / $leafNodes->count()) * 100, 2)
            : 0;

        return inertia('Dashboard', [
            'totalNodes' => $totalNodes,
            'totalFiles' => $totalFiles,
            'totalYears' => $totalYears,
            'years' => Year::all(),
            'year' => $year,
            'completionPercentage' => $completionPercentage,
        ]);
    }
}

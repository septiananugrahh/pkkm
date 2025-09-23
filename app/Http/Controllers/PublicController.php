<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\Year;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function index(Request $request)
    {
        $defaultYear = Year::where('year', now()->year)->first();
        $year = $request->input('year');

        // Jika tidak ada tahun yang dipilih dari request, gunakan ID dari tahun saat ini
        if (!$year) {
            $year = $defaultYear ? $defaultYear->id : null;
        }

        $nodes = Node::with([
            'files',
            'year', // Tambahkan eager loading untuk relasi 'year' di sini
            'children' => function ($query) {
                // ... kode children recursive Anda di sini
                $query->orderBy('order', 'asc')->with([
                    'files',
                    'children' => function ($query) {
                        $query->orderBy('order', 'asc')->with([
                            'files',
                            'children' => function ($query) {
                                $query->orderBy('order', 'asc')->with([
                                    'files',
                                    'children' => function ($query) {
                                        $query->orderBy('order', 'asc')->with('files');
                                    }
                                ]);
                            }
                        ]);
                    }
                ]);
            }
        ])
            ->where('year_id', $year)
            ->whereNull('parent_id')
            ->orderBy('order', 'asc')
            ->get();

        $years = Year::orderBy('year', 'asc')->get();

        return Inertia::render('Public/Index', [
            'nodes' => $nodes,
            'years' => $years,
            'year' => (int) $year,
        ]);
    }
}

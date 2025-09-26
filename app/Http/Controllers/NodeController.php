<?php

namespace App\Http\Controllers;

use App\Models\Node;
use App\Models\Year;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class NodeController extends Controller
{
    // Tampilkan daftar node (filter per tahun)
    // app/Http/Controllers/NodeController.php

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

        // Pastikan Anda juga mengirimkan data tahun yang relevan ke view
        $years = Year::all();


        return inertia('Nodes/Index', [
            'nodes' => $nodes,
            'years' => $years,
            'year' => $year,
        ]);
    }

    public function toggleComplete(Node $node)
    {
        $node->is_completed = !$node->is_completed;
        $node->save();

        // Kalau pakai Inertia bisa return response redirect atau json
        return back()->with('message', 'Node status berhasil diupdate.');
    }






    public function create(Request $request)
    {
        $parentId = $request->get('parent_id');
        $parent = $parentId ? Node::find($parentId) : null;
        $years = Year::all();

        return inertia('Nodes/Create', [
            'parent' => $parent,
            'years' => $years
        ]);
    }


    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:nodes,id',
            'year_id' => 'required|exists:years,id'
        ]);

        Node::create($data);

        return redirect()->route('nodes.index')->with('success', 'Node berhasil ditambahkan');
    }




    // Update node
    public function update(Request $request, Node $node)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'order' => 'nullable|integer',
        ]);

        $node->update($data);

        return back()->with('success', 'Node berhasil diperbarui.');
    }

    // Hapus node
    public function destroy(Node $node)
    {
        DB::transaction(function () use ($node) {
            $this->deleteNodeAndChildren($node);
        });

        return redirect()->back()->with('success', 'Node dan semua sub-node berhasil dihapus.');
    }

    /**
     * Delete a node and all of its children recursively.
     *
     * @param Node $node
     * @return void
     */
    private function deleteNodeAndChildren(Node $node)
    {
        // First, delete the children recursively
        foreach ($node->children as $child) {
            $this->deleteNodeAndChildren($child);
        }

        // Then, delete the files associated with the current node
        foreach ($node->files as $file) {
            Storage::disk('public')->delete($file->file_path);
            $file->delete(); // This deletes the record from the database
        }

        // Finally, delete the current node itself
        $node->delete();
    }
}

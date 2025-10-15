<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\Node;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function availableFiles()
    {
        $files = File::select('id', 'file_name', 'file_path')
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('files')
                    ->groupBy('file_name');
            })
            ->orderBy('file_name')
            ->get();

        return response()->json($files);
    }



    public function attach(Request $request, Node $node)
    {
        $request->validate([
            'file_id' => 'required|exists:files,id',
        ]);

        $file = File::findOrFail($request->file_id);

        // buat salinan entry dengan node_id baru
        File::create([
            'node_id'   => $node->id,
            'file_name' => $file->file_name,
            'file_path' => $file->file_path, // path tetap sama
            'file_type' => $file->file_type,
        ]);

        return back()->with('success', 'File berhasil ditautkan ke node.');
    }


    public function store(Request $request, $nodeId)
    {
        $request->validate([
            'files.*' => 'required|mimes:jpg,jpeg,png,pdf,docx,ppt,pptx|max:51200'
        ], [
            'files.*.max' => 'File terlalu besar. Maksimal ukuran file adalah 50MB.',
            'files.*.mimes' => 'Tipe file tidak didukung. Harap upload file dengan ekstensi jpg, jpeg, png, pdf, doc, docx, ppt, atau pptx.'
        ]);

        $uploadedCount = 0;
        $uploadedFiles = [];

        foreach ($request->file('files') as $file) {
            $path = $file->store('uploads/files', 'public');

            $fileRecord = File::create([
                'node_id'   => $nodeId,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
            ]);

            $uploadedFiles[] = $fileRecord;
            $uploadedCount++;
        }

        return back()->with('success', "$uploadedCount file(s) berhasil diunggah");
    }


    public function destroy(File $file)
    {
        DB::transaction(function () use ($file) {
            // Cek apakah file_path ini digunakan lebih dari sekali di tabel
            $duplicateCount = File::where('file_path', $file->file_path)
                ->where('id', '!=', $file->id)
                ->count();

            // Jika hanya ada satu (yaitu file ini sendiri), baru hapus fisik
            if ($duplicateCount === 0) {
                Storage::disk('public')->delete($file->file_path);
            }

            // Hapus entri file dari database
            $file->delete();
        });

        return back()->with('success', 'File berhasil dihapus.');
    }
}

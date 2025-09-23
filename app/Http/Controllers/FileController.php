<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\File;
use App\Models\Node;
use Illuminate\Http\Request;

class FileController extends Controller
{
    public function store(Request $request, $nodeId)
    {
        $request->validate([
            'files.*' => 'required|mimes:jpg,jpeg,png,pdf,doc,docx|max:20480'
        ], [
            'files.*.max' => 'File terlalu besar. Maksimal ukuran file adalah 20MB.',
            'files.*.mimes' => 'Tipe file tidak didukung. Harap upload file dengan ekstensi jpg, jpeg, png, pdf, doc, atau docx.'
        ]);


        foreach ($request->file('files') as $file) {
            $path = $file->store('uploads/files', 'public');

            File::create([
                'node_id'   => $nodeId,
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
            ]);
        }

        return back()->with('success', 'Files uploaded successfully');
    }


    public function destroy(File $file)
    {
        // Gunakan DB transaction untuk memastikan data konsisten
        // Jika penghapusan gagal, tidak ada yang terhapus
        DB::transaction(function () use ($file) {
            // Hapus file fisik dari storage
            Storage::disk('public')->delete($file->file_path);

            // Hapus record file dari database
            $file->delete();
        });

        // Redirect kembali dengan pesan sukses atau respons Inertia
        return back()->with('success', 'File berhasil dihapus.');
    }
}

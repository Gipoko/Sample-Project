<?php
namespace App\Http\Controllers;
use App\Models\Document;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class DocumentController extends Controller
{
    public function index()
    {
       return DB::table('documents')
        ->join('users', 'documents.uploaded_by', '=', 'users.id')
        ->select(
            'documents.id',
            'documents.title',
            'documents.file_path',
            'documents.uploaded_by',
            'documents.created_at',
            'documents.updated_at',
            'users.name as uploader_name'
        )
        ->latest('documents.id')
        ->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'file' => 'required|file',
        ]);

        $path = $request->file('file')->store('documents', 'public');

        $doc = Document::create([
            'title' => $request->title,
            'file_path' => $path,
            'uploaded_by' => auth()->id() ?? 0,
        ]);

        return response()->json($doc);
    }

    public function update(Request $request, $id)
    {
        $doc = Document::findOrFail($id);

        $doc->title = $request->title;

        if ($request->hasFile('file')) {
            $doc->file_path = $request->file('file')->store('documents', 'public');
        }

        $doc->save();

        return response()->json($doc);
    }

    public function destroy($id)
    {
        Document::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}

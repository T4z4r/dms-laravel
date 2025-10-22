<?php
namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Signature;
use Illuminate\Http\Request;

class SignatureController extends Controller
{
    public function show(File $file)
    {
        $this->authorize('sign', $file);
        return view('files.sign', compact('file'));
    }

    public function store(Request $request, File $file)
    {
        $this->authorize('sign', $file);

        $request->validate([
            'signature_type'=>'required|in:drawn,typed,uploaded',
            'signature_image'=>'required|string', // data URL or base64 or path
            'notes'=>'nullable|string'
        ]);

        $sig = Signature::create([
            'file_id'=>$file->id,
            'user_id'=>auth()->id(),
            'signature_type'=>$request->signature_type,
            'signature_image'=>$request->signature_image,
            'notes'=>$request->notes,
        ]);

        $file->update([
            'is_signed'=>true,
            'signed_by'=>auth()->id(),
            'signed_at'=>now()
        ]);

        return redirect()->route('files.index')->with('success','Document signed successfully');
    }

    public function view(File $file)
    {
        $this->authorize('viewSignature',$file);
        $signature = $file->signature;
        if(!$signature) return back()->with('error','No signature found');
        return view('files.signature_view', compact('file','signature'));
    }
}

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
            'signature_type' => 'required|in:drawn,typed,uploaded',
            'signature_image' => 'required',
            'notes' => 'nullable|string'
        ]);

        $signatureImage = '';

        if ($request->signature_type === 'drawn') {
            // Assume it's base64 data URL
            $signatureImage = $request->signature_image;
        } elseif ($request->signature_type === 'typed') {
            $signatureImage = $request->signature_image;
        } elseif ($request->signature_type === 'uploaded') {
            // Handle file upload
            if ($request->hasFile('signature_image')) {
                $uploadedFile = $request->file('signature_image');
                $path = $uploadedFile->store('signatures', 'public');
                $signatureImage = '/storage/' . $path;
            } else {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Signature image is required for uploaded type.'], 422);
                }
                return back()->withErrors(['signature_image' => 'Signature image is required for uploaded type.']);
            }
        }

        $sig = Signature::create([
            'file_id' => $file->id,
            'user_id' => auth()->id(),
            'signature_type' => $request->signature_type,
            'signature_image' => $signatureImage,
            'notes' => $request->notes,
        ]);

        $file->update([
            'is_signed' => true,
            'signed_by' => auth()->id(),
            'signed_at' => now()
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Document signed successfully']);
        }

        return redirect()->route('files.index')->with('success', 'Document signed successfully');
    }

    public function view(File $file)
    {
        $this->authorize('viewSignature',$file);
        $signature = $file->signature;
        if(!$signature) return back()->with('error','No signature found');
        return view('files.signature_view', compact('file','signature'));
    }
}

<?php

namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentStoreRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Interfaces\DocumentRepositoryInterface;
use Illuminate\Support\Facades\Storage;


use Aws\Common\Exception\MultipartUploadException;
use Aws\S3\MultipartUploader;
use Aws\S3\S3Client;




use App\Events\DocumentUploaded;
use Mail;

class DocumentController extends Controller
{
    private $documentRepository;
    public function __construct(DocumentRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    // public function documentStore(DocumentStoreRequest $request)
    public function documentStore(Request $request)
    {
        $data = $this->validate($request, [
            'document' => 'required|mimes:pdf,txt,word,xml,csv|max:10240',
        ]);
        $size = $request->file('document')->getSize();
        $dir = date('Y').'/'.date('m').'/'.date('d');
        $docPath = $request->file('document')->store($dir, 's3');
        $full_url = Storage::url($docPath);
        // $full_url = $s3->getObjectUrl( env('AWS_BUCKET'), $docPath );
        // echo "<pre>";
        // var_dump(auth()->user()->id);
        // var_dump($size);
        // var_dump($docPath);
        // var_dump($full_url);
        // var_dump($data);
        // [auth()->user()->id,$size,$docPath,$full_url];
        // die;
        // $this->documentRepository = 
        // $data = Document::create([
        //     'image' => $docPath,
        // ]);
        
        // event(new DocumentUploaded($docPath,$userId,$size));

        

        // $file = $request->file('image');
        //     $imageName=time().$file->getClientOriginalName();
        //     $filePath = 'images/' . $imageName;
        //     Storage::disk('s3')->put($filePath, file_get_contents($file));
        
        return response()->json(['success'=>true, 'message'=>'Doc getting uploaded', 'data'=>[auth()->user()->id,$size,$docPath,$full_url]],Response::HTTP_CREATED);
    }

    public function documentStoreInter(DocumentStoreRequest $request) {
        $validatedData = $request->validated();
        $validatedData['document'] = $request->file('document')->store('document','public');
        $data = Document::create($validatedData);
        return response()->json(['success'=>true, 'message'=>'Doc getting uploaded'],Response::HTTP_CREATED);
    }
}

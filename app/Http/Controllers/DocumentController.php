<?php

namespace App\Http\Controllers;
use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Requests\DocumentStoreRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Repositories\Interfaces\DocumentRepositoryInterface;
use App\Events\DocumentUpload;

use Illuminate\Support\Facades\Event;


class DocumentController extends Controller
{
    private $documentRepository;
    public function __construct(DocumentRepositoryInterface $documentRepository)
    {
        $this->documentRepository = $documentRepository;
    }

    public function documentStore(Request $request)
    {
        $data = $this->validate($request, [
            'document' => 'required|mimes:pdf,txt,word,xml,csv|max:10240',
        ]);
        $userId =  auth()->user()->id;
        $fileName = $userId. '_' . time() .'_' . $request->file('document')->getClientOriginalName();
        $fileSize = $request->file('document')->getSize();
        $dir = date('Y').'/'.date('m').'/'.date('d');
        $localFilePath = $request->file('document')->storePubliclyAs('docs/temp', $fileName, 'public');

        
        $s3FilePath = $dir.'/'.$fileName;
        Event::dispatch(new DocumentUpload($userId, $localFilePath, $s3FilePath, $fileName, $fileSize));

        return response()->json(['success'=>true, 'message'=>'Doc getting uploaded'],Response::HTTP_CREATED);
    }

}

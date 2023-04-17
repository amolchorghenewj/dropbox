<?php

namespace App\Repositories;

use App\Repositories\Interfaces\DocumentRepositoryInterface;
use App\Models\Document;

class DocumentRepository implements DocumentRepositoryInterface
{
    public function documentsByUserId($userId){
        return Document::find($userId)->latest()->paginate(10);
    }

    public function storeDocument($data){
        return Document::create($data);
    }

    public function updateDocument($data, $id){
        $document = Document::where('id', $id)->first();
        $document->name = $data['name'];
        $document->slug = $data['slug'];
        $document->save();
    } 
    public function destroyDocument($id){
        $document = Document::find($id);
        $document->delete();
    }
}
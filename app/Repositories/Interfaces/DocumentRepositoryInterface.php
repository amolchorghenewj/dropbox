<?php
namespace App\Repositories\Interfaces;

Interface DocumentRepositoryInterface{
    
    public function documentsByUserId($userId);
    public function storeDocument($data);
    public function updateDocument($data, $id); 
    public function destroyDocument($id);
}
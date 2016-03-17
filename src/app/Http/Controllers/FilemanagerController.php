<?php

namespace SaeedVaziry\LaravelFilemanager\App\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use File;
use Validator;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;

class FilemanagerController extends Controller
{

    public function getIndex(Request $req)
    {
        $files = array();
        $f = File::allFiles(config('filemanager.uploadDir'));
        if($req->has('type'))
        {
            if($req->input('type') == "image")
            {
                foreach ($f as $file)
                {
                    if(in_array(File::extension($file),config('filemanager.imageTypes')))
                        array_push($files,array("file"=>$file,'fileName'=>$file->getFilename(),"lastModified"=>File::lastModified($file),"type"=>File::extension($file)));
                }
            }
        }
        else
        {
            foreach ($f as $file)
            {
                array_push($files,array("file"=>$file,'fileName'=>$file->getFilename(),"lastModified"=>File::lastModified($file),"type"=>File::extension($file)));
            }
        }
        usort($files,array($this,"sortFunction"));
        return view('vendor.filemanager.index')
            ->with('files',$this->customPaginate($files,config('filemanager.filesPerPage')))
            ->with('imageTypes',config('filemanager.imageTypes'));
    }
    private function sortFunction($a, $b) {
       if ($a["lastModified"] == $b["lastModified"]) return 0;
       return ($a["lastModified"] > $b["lastModified"]) ? -1 : 1;
    }
    public function postUpload(Request $req)
    {
        $rules = array(
            'file' => 'required|max:'.config('filemanager.fileMaxSize'),
        );
        $this->validate($req,$rules);

        $file = $req->file('file');
        $fileName = $file->getClientOriginalName();
        $req->file('file')->move(config('filemanager.uploadDir'),$fileName);

        if($req->has('type'))
        {
            if($req->input('type') == "image")
            {
                if($req->has('select'))
                {
                    return redirect(config('filemanager.basicRoute').'?type=image&select=image')
                        ->with('alert','success')
                        ->with('message',trans('filemanager::filemanager.uploaded'));
                }
                return redirect(config('filemanager.basicRoute').'?type=image')
                    ->with('alert','success')
                    ->with('message',trans('filemanager::filemanager.uploaded'));
            }
        }
        return redirect(config('filemanager.basicRoute'))
            ->with('alert','success')
            ->with('message',trans('filemanager::filemanager.uploaded'));
    }
    public function getDelete(Request $req)
    {
        File::delete(config('filemanager.uploadDir').'/'.$req->input('name'));
        return redirect(config('filemanager.basicRoute'))
            ->with('alert','success')
            ->with('message',trans('filemanager::filemanager.deleted'));
    }
    public function customPaginate($items,$perPage)
    {
        $pageStart = \Request::get('page', 1);
        // Start displaying items from this number;
        $offSet = ($pageStart * $perPage) - $perPage;
        // Get only the items you need using array_slice
        $itemsForCurrentPage = array_slice($items, $offSet, $perPage, true);
        return new LengthAwarePaginator($itemsForCurrentPage, count($items), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
    }
}

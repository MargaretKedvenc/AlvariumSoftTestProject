<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\SaveImportData;
use App\Jobs\SavePromImages;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;

class ImportController extends Controller
{
    public function upload()
    {
        return view('admin/upload-import');
    }
    public function saveFile(Request $request)
    {
        // if ($_FILES['file']['type'] != 'text/csv'){
        //     throw new FileNotFoundException("не правильный формат");
        // }
        $path = storage_path('app/public/import/'. $_FILES['file']['name']);
        file_put_contents($path, file_get_contents($_FILES['file']['tmp_name']));
        if (is_file($path)){
            $storagePath = 'import/'.$_FILES['file']['name'];
                return response()->json([
                    'success' => true,
                    'message' => 'файл сохранен',
                    'path' => $storagePath
                ], 200);
            }
        return response()->json([
            'error' => 'server_error',
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    public function import(Request $request)
    {
        $url = $request->url;
        ini_set('max_execution_time', 900);
        SaveImportData::dispatch($url);
        SavePromImages::dispatch($url);
//        Storage::delete('public/'.$url);
//        Log::info(__CLASS__.' import complete '.date("Y-m-d H-i-s"));
        return response()->json([
            'success' => true,
            'message' => 'импор выполнен'
        ], 200);
    }
}

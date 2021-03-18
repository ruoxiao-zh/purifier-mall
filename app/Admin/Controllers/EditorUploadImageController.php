<?php

namespace App\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Encore\Admin\Controllers\AdminController;

class EditorUploadImageController extends AdminController
{
    protected $allowed_ext = ["png", "jpg", "gif", 'jpeg'];

    public function upload(Request $request)
    {
        $folder_name = "uploads/editor/" . date("Ym", time()) . '/' . date("d", time()) . '/';

        $upload_path = public_path() . '/' . $folder_name;
        $urls = [];

        foreach ($request->file() as $file) {
            $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

            if ( !in_array($extension, $this->allowed_ext)) {
                return false;
            }

            $filename = $file->getClientOriginalName();

            $file->move($upload_path, $file->getClientOriginalName());

            $urls[] = env('APP_URL') . '/' . $folder_name . $filename;
        }

        return [
            "errno" => 0,
            "data"  => $urls,
        ];
    }
}

<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;
use Intervention\Image\ImageManagerStatic as Image;



class UserController extends Controller
{
    /**
     * @return \Illuminate\Support\Collection
     */

    /**
     * @return \Illuminate\Support\Collection
     */
    public function fileImport(Request $request)
    {
        $path = $request->file('file')->getRealPath();
        $array = \Excel::toArray('', $path, null, \Maatwebsite\Excel\Excel::TSV)[0];
        unset($array[0]);
        $image = [];
        foreach ($array as $key => $value) {
            $image[$key] = $value['1'];
        }
        $i = 0;
        foreach ($image as $value) {

            if ($i == 2) {
                break;
            }
            $url = $value;
            $start = date("Y-m-d H:s:i", strtotime($request->start));
            $image_name = \Carbon\Carbon::now()->timestamp;
            $img = public_path('images') . '\\' . $image_name . '.png';
            file_put_contents($img, file_get_contents($value));
            $thumbnail_name = 'thumb' . $image_name;
            $thumb = Image::make(public_path('images/' . $image_name . '.png'))->resize(256, 256)->save(public_path('thumbnail/' . $thumbnail_name . '.png'));
            // $thumb = public_path('thumbnail') . '\\' . $thumb . '.png';
            // $thumb = Image::make("test.png")->resize(256, 256)->insert('public/thumbnail/watermark.png');
            $email = request('email');
            $user = new User();
            $user->email = $email;
            $user->photo = $image_name;
            $user->thumbnail = $thumbnail_name;
            $user->save();
            $i++;
        }
        return redirect()->route('mail', $email);
    }
}

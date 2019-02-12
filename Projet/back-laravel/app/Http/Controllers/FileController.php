<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App;
use App\File;
use App\City;
use App\Imagetype;
use App\Http\Requests\StoreFile;
use App\Http\Requests\UpdateFile;

class FileController extends Controller
{
    private $image_ext = ['jpg', 'jpeg', 'png', 'gif'];
    private $audio_ext = ['mp3', 'ogg', 'mpga'];
    private $video_ext = ['mp4', 'mpeg'];

    /**
     * @param $city_id
     * @param $type
     * @param null $file_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($city_id, $type)
    {
        $city = City::findOrFail($city_id);

        $records_per_page = ($type == 'video') ? 6 : 15;

        $files = File::where('type', $type)
            ->where('city_id', $city_id)
            ->orderBy('id', 'desc')->paginate($records_per_page);

        return view('file.index', compact('files', 'city'));
    }

    /**
     * @param $city_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create($city_id)
    {
        $city = City::find($city_id);
        $imagetypes = Imagetype::all();
        return view('file.create', compact('city', 'imagetypes'));
    }

    /**
     * @param $city_id
     * @param StoreFile $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store($city_id, StoreFile $request)
    {
        $validated = $request->validated();
        if (!isset($validated['imagetype'])) {
            $validated['imagetype'] = null;
        }
        $file = $validated['file'];
        $filename = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $type = $this->getType($ext);

        $path = Storage::disk('public')->putFile('files/' . $type, $file, 'public');
        File::create([
            'filename' => $filename,
            'path' => $path,
            'type' => $type,
            'extension' => $ext,
            'alt' => $validated['alt'],
            'city_id' => $city_id,
            'imagetype_id' => $validated['imagetype']
        ]);
        return redirect()->route('fileIndex', [$city_id, $type])->with('success', 'Le fichier a bien été créé.');
    }

    /**
     * @param $file_id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($file_id)
    {
        $file = File::findOrFail($file_id);
        $imagetypes = Imagetype::all();
        return view('file.edit', compact('file', 'imagetypes'));
    }

    /**
     * @param $file_id
     * @param UpdateFile $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($file_id, UpdateFile $request)
    {
        $validated = $request->validated();
        if (!isset($validated['imagetype'])) {
            $validated['imagetype'] = null;
        }
        $file = File::findOrFail($file_id);
        $file->update([
            'alt' => $validated['alt'],
            'imagetype_id' => $validated['imagetype']
        ]);
        return redirect()->route('fileIndex', [$file->city->id, $file->type])->with('success', 'Le fichier a bien été modifié.');
    }

    /**
     * @param $file_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($file_id)
    {
        $file = File::find($file_id);
        $city_id = $file->city->id;
        $type = $file->type;
        Storage::disk('public')->delete($file->path);
        $file->delete();
        return redirect()->route('fileIndex', [$city_id, $type])->with('success', 'Le fichier a bien été supprimé.');
    }


    /**
     * Get type by extension
     * @param  string $ext Specific extension
     * @return string      Type
     */
    private function getType($ext)
    {
        if (in_array($ext, $this->image_ext)) {
            return 'image';
        }

        if (in_array($ext, $this->audio_ext)) {
            return 'audio';
        }

        if (in_array($ext, $this->video_ext)) {
            return 'video';
        }
    }
}

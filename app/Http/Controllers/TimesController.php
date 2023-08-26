<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\Time;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class TimesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clube = Time::all();
        return view('clubes.index');

        //return view('clubes.index')->with('clube', $clube);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clubes.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->hasFile("escudo")) {
            $file = $request->file("escudo");
            $imageName = time() . '_' . $file->getClientOriginalName();

            $file->move(\public_path("escudos/"), $imageName);

            $clube = new Time([
                "nome" => $request->nome,
                "escudo" => $imageName,
                "apelido" => $request->apelido,
                "fundacao" => $request->data_fundacao
            ]);
            $clube->save();
            dd($clube->save());
        }

        return redirect("/");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Time  $clube
     * @return \Illuminate\Http\Response
     */
    public function show(Time $clube)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Time  $clube
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->id;
        $clube = Time::findOrFail($id);
        return view('edit')->with('clube', $clube);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Time  $clube
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $clube = Time::findOrFail($id);
        if ($request->hasFile("escudo")) {
            if (File::exists("escudos/" . $clube->escudo)) {
                File::delete("escudos/" . $clube->escudo);
            }
            $file = $request->file("escudos");
            $clube->escudo = time() . "_" . $file->getClientOriginalName();
            $file->move(\public_path("/escudos"), $clube->escudo);
            $request['escudos'] = $clube->escudo;
        }

        $clube->update([
            "nome" => $request->nome,
            "escudo" => $clube->escudo,
        ]);

        if ($request->hasFile("images")) {
            $files = $request->file("images");
            foreach ($files as $file) {
                $imageName = time() . '_' . $file->getClientOriginalName();
                $request["time_id"] = $id;
                $request["image"] = $imageName;
                $file->move(\public_path("images"), $imageName);
                //Image::create($request->all());
            }
        }

        return redirect("/");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Time  $clube
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        $clube = Time::findOrFail($id);

        if (File::exists("escudos/" . $clube->escudo)) {
            File::delete("escudos/" . $clube->escudo);
        }

        $clube->delete();
        return back();
    }



    public function deleteescudos($id)
    {
        $escudo = Time::findOrFail($id)->escudo;
        if (File::exists("escudos/" . $escudo)) {
            File::delete("escudos/" . $escudo);
        }
        return back();
    }
    public function delete(Request $request)
    {
        $id = $request->id;
        Time::find($id)->delete();
        return redirect("/");
    }

    public function readCsv()
    {
        $filePath = base_path('public/dados/clubes.csv');

        $file = fopen($filePath, 'r');

        $firstline = true;
        while (($data = fgetcsv($file, 2000, ";")) !== FALSE) {
            if (!$firstline) {
                $data = array_map("utf8_encode", $data);
                $escudo = 'escudo_ ' . $data[0] . ' _ ' . time() . '.png';
                Storage::disk('public')->put($escudo, file_get_contents($data[3]));

                rename(base_path('public/' . $escudo), base_path('public/escudos/' . $escudo));

                $clube = new Time([
                    "nome" => $data[0],
                    "escudo" => $escudo,
                    "apelido" => $data[1],
                    "fundacao" => $data[2]
                ]);
                $clube->save();
            }

            $firstline = false;
        }
        fclose($file);

        return redirect("/");
    }
}

<?php

namespace App\Http\Controllers;

use App\Revista;
use Illuminate\Http\Request;

class RevistaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $revistas = Revista::orderBy('codigo')->paginate(10);

        return view('revista.index', [
            'revistas' => $revistas
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Revista  $revista
     * @return \Illuminate\Http\Response
     */
    public function show(Revista $revista)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Revista  $revista
     * @return \Illuminate\Http\Response
     */
    public function edit(Revista $revista)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Revista  $revista
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Revista $revista)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Revista  $revista
     * @return \Illuminate\Http\Response
     */
    public function destroy(Revista $revista)
    {
        //
    }
}
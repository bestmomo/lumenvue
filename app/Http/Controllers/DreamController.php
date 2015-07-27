<?php

namespace App\Http\Controllers;

use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\Request;
use App\Repositories\DreamRepository;
use Auth;

class DreamController extends Controller {

    /**
     * Repository instance.
     *
     */
    protected $dreamRepository;

    /**
     * Validation rules.
     *
     */
    protected $rules = [
        'content' => 'required|max:2000',
    ];

    /**
     * Create a new DreamController controller instance.
     *
     * @param  App\Repositories\DreamRepository $dreamRepository
     * @return void
     */
    public function __construct(DreamRepository $dreamRepository)
    {
        $this->dreamRepository = $dreamRepository;

        $this->middleware('auth', ['only' => ['store', 'update', 'destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return $this->dreamRepository->getDreamsWithUserPaginate(4);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->rules);

        $this->dreamRepository->store($request->all(), Auth::id());

        return $this->dreamRepository->getDreamsWithUserPaginate(4);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->rules);

        if ($this->dreamRepository->update($request->all(), $id)) 
        {
            return ['result' => 'success'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($this->dreamRepository->destroy($id)) 
        {
            return ['result' => 'success'];
        }
    }

}

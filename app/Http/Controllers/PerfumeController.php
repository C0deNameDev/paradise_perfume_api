<?php

namespace App\Http\Controllers;

use App\Http\Resources\CharacteristicResource;
use App\Http\Resources\PerfumePageResource;
use App\Http\Resources\PerfumeResource;
use App\Models\Perfume;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class PerfumeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(
        private Perfume $perfume,
        private User $user,
        private ImageKitProvider $imageKitProvider = new ImageKitProvider()
    ) {
    }

        public function paginate()
        {
            $perfumes = $this->perfume::paginate(5, ['*'], 'page');
            $pers = [];
            foreach ($perfumes->items() as $perfume) {
                array_push($pers, new PerfumeResource($perfume));
            }

            return new PerfumePageResource(['current_page' => $perfumes->currentPage(), 'data' => $pers]);
        }

    public function index()
    {
        try {
            if (! request()->has('page')) {
                $perfumes = $this->perfume::all();

                return $this->sendResponse('perfumes retrieved', PerfumeResource::collection($perfumes));
            } else {
                // $page = request()->query('page');
                $page_size = request()->query('size');
                // dd($page_size);
                $perfumes = $this->perfume::paginate($page_size, ['*'], 'page');
                // dd($perfumes->lastPage());
                $pers = [];
                foreach ($perfumes->items() as $perfume) {
                    array_push($pers, new PerfumeResource($perfume));
                }

                return $this->sendResponse('perfumes retrieved', new PerfumePageResource(['last_page' => $perfumes->lastPage(), 'current_page' => $perfumes->currentPage(), 'data' => $pers]));
            }

        } catch (Exception $e) {
            $this->sendError('an error has occured', '', 500);
        }
    }

    public function get_by_id($perfume_id)
    {
        try {
            $perfume = Perfume::find($perfume_id);
            if (! $perfume) {
                return $this->sendError('Perfume not found', '', 404);
            }

            return $this->sendResponse('perfume found', ['perfume' => new PerfumeResource($perfume), 'characteristics' => $perfume->characteristics()->get()->pluck('name')]);
        } catch (Exception $e) {
            $this->sendError('an error has occured', '', 500);
        }

    }

    public function get_perfume_picture($perfume_id)
    {
        try {

            $perfume = $this->perfume::find($perfume_id);
            if (! $perfume) {
                return $this->sendError('perfume not found', '', 404);
            }
            if (! $perfume->picture || $perfume->picture === 'default') {

                return $this->sendResponse('image fetched', $this->perfume->default_picture);
            }
            $perfume_picture = $this->imageKitProvider->get_perfume_picture($perfume->picture);
            if ($perfume_picture) {
                return $this->sendResponse('image fetched', $perfume_picture);
            }

            return $this->sendError('could not fetch image', '', 500);
        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function get_details($perfume_id)
    {
        $perfume = $this->perfume::find($perfume_id);
        if (! $perfume) {
            return $this->sendError('perfume not found', '', 404);
        }

        return $this->sendResponse('characteristics retrieved', CharacteristicResource::collection($perfume->characteristics));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfume $perfume)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perfume $perfume)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Perfume $perfume)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Perfume $perfume)
    {
        //
    }
}

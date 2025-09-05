<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\CantonRequest;
use App\Models\Canton;
use App\Models\Provincia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;
use Illuminate\Http\JsonResponse;

class CantonesController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['canton.view']);

        return view('backend.pages.cantones.index', [
            'cantones' => Canton::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['canton.create']);

        return view('backend.pages.cantones.create', [
            'roles' => Role::all(),
            'provincias' => Provincia::all()->pluck('nombre','id'),
        ]);
    }

    public function store(CantonRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['canton.create']);

        $canton = new Canton();
        $canton->nombre = $request->nombre;
        $canton->provincia_id = $request->provincia_id;
        $canton->save();

        session()->flash('success', __('Canton ha sido creada satisfactoriamente.'));
        return redirect()->route('admin.cantones.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['canton.edit']);

        $canton = Canton::findOrFail($id);
        return view('backend.pages.cantones.edit', [
            'canton' => $canton,
            'provincias' => Provincia::all()->pluck('nombre','id'),
            'roles' => Role::all(),
        ]);
    }

    public function update(CantonRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['canton.edit']);

        $canton = Canton::findOrFail($id);
        $canton->nombre = $request->nombre;
        $canton->provincia_id = $request->provincia_id;
        $canton->save();

        session()->flash('success', 'Canton ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['canton.delete']);

        $canton = Canton::findOrFail($id);
        $canton->delete();
        session()->flash('success', 'Canton ha sido borrado satisfactoriamente.');
        return back();
    }

    public function getCantonByProvincia(Request $request): JsonResponse
    {
        $data['cantones'] = Canton::where("provincia_id", $request->provincia_id)
                                ->get(["nombre", "id"]);
  
        return response()->json($data);
    }
}
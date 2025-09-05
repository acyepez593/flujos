<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\ParroquiaRequest;
use App\Models\Parroquia;
use App\Models\Provincia;
use App\Models\Canton;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ParroquiasController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['parroquia.view']);

        return view('backend.pages.parroquias.index', [
            'parroquias' => Parroquia::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['parroquia.create']);

        return view('backend.pages.parroquias.create', [
            'roles' => Role::all(),
            'provincias' => Provincia::all()->pluck('nombre','id'),
            'cantones' => Canton::all()->pluck('nombre','id'),
        ]);
    }

    public function store(ParroquiaRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['parroquia.create']);

        $parroquia = new Parroquia();
        $parroquia->nombre = $request->nombre;
        $parroquia->provincia_id = $request->provincia_id;
        $parroquia->canton_id = $request->canton_id;
        $parroquia->save();

        session()->flash('success', __('Parroquia ha sido creada satisfactoriamente.'));
        return redirect()->route('admin.parroquias.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['parroquia.edit']);

        $parroquia = Parroquia::findOrFail($id);
        return view('backend.pages.parroquias.edit', [
            'parroquia' => $parroquia,
            'provincias' => Provincia::all()->pluck('nombre','id'),
            'cantones' => Canton::all()->pluck('nombre','id'),
            'roles' => Role::all(),
        ]);
    }

    public function update(ParroquiaRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['parroquia.edit']);

        $parroquia = Parroquia::findOrFail($id);
        $parroquia->nombre = $request->nombre;
        $parroquia->provincia_id = $request->provincia_id;
        $parroquia->canton_id = $request->canton_id;
        $parroquia->save();

        session()->flash('success', 'Parroquia ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['parroquia.delete']);

        $parroquia = Parroquia::findOrFail($id);
        $parroquia->delete();
        session()->flash('success', 'Parroquia ha sido borrado satisfactoriamente.');
        return back();
    }

    public function getParroquiaByCanton(Request $request): JsonResponse
    {
        $data['parroquias'] = Parroquia::where("canton_id", $request->canton_id)
                                ->get(["nombre", "id"]);
  
        return response()->json($data);
    }
}
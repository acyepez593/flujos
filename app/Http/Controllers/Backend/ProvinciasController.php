<?php
    
declare(strict_types=1);

namespace App\Http\Controllers\Backend;
    
use App\Http\Controllers\Controller;
use App\Http\Requests\ProvinciaRequest;
use App\Models\Provincia;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Spatie\Permission\Models\Role;

class ProvinciasController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['provincia.view']);

        return view('backend.pages.provincias.index', [
            'provincias' => Provincia::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['provincia.create']);

        return view('backend.pages.provincias.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(ProvinciaRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['provincia.create']);

        $provincia = new Provincia();
        $provincia->nombre = $request->nombre;
        $provincia->save();

        session()->flash('success', __('Provincia ha sido creada satisfactoriamente.'));
        return redirect()->route('admin.provincias.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['provincia.edit']);

        $provincia = Provincia::findOrFail($id);
        return view('backend.pages.provincias.edit', [
            'provincia' => $provincia,
            'roles' => Role::all(),
        ]);
    }

    public function update(ProvinciaRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['provincia.edit']);

        $provincia = Provincia::findOrFail($id);
        $provincia->nombre = $request->nombre;
        $provincia->save();

        session()->flash('success', 'Provincia ha sido actualizado satisfactoriamente.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['provincia.delete']);

        $provincia = Provincia::findOrFail($id);
        $provincia->delete();
        session()->flash('success', 'Provincia ha sido borrado satisfactoriamente.');
        return back();
    }

    public function getProvincias(){
        $provincias = Provincia::all()->pluck('nombre','id');
        return json_encode($provincias);
    }

}
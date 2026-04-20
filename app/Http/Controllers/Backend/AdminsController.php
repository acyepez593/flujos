<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminRequest;
use App\Models\Admin;
use App\Models\Catalogo;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminsController extends Controller
{
    public function index(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['admin.view']);

        $cargos = Catalogo::where('tipo_catalogo_id', 30)->get(['id','tipo_catalogo_id','nombre']);
        $cargosTemp = [];
        foreach($cargos as $cargo){
            $cargosTemp[$cargo->id] = $cargo->nombre;
        }

        $abreviacionesTitulo = Catalogo::where('tipo_catalogo_id', 31)->get(['id','tipo_catalogo_id','nombre']);
        $abreviacionesTituloTemp = [];
        foreach($abreviacionesTitulo as $abreviacionTitulo){
            $abreviacionesTituloTemp[$abreviacionTitulo->id] = $abreviacionTitulo->nombre;
        }

        $agencias = Catalogo::where('tipo_catalogo_id', 3)->get(['id','tipo_catalogo_id','nombre']);
        $agenciasTemp = [];
        foreach($agencias as $agencia){
            $agenciasTemp[$agencia->id] = $agencia->nombre;
        }

        return view('backend.pages.admins.index', [
            'admins' => Admin::all(),
        ]);
    }

    public function create(): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['admin.create']);

        return view('backend.pages.admins.create', [
            'roles' => Role::all(),
        ]);
    }

    public function store(AdminRequest $request): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['admin.create']);

        $admin = new Admin();
        $admin->name = $request->name;
        $admin->username = $request->username;
        $admin->email = $request->email;
        $admin->cargo_id = $request->cargo_id;
        $admin->abreviacion_titulo_id = $request->abreviacion_titulo_id;
        $admin->agencia_id = $request->agencia_id;
        $admin->password = Hash::make($request->password);
        $admin->save();

        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', __('El usuario ha sido creado!.'));
        return redirect()->route('admin.admins.index');
    }

    public function edit(int $id): Renderable
    {
        $this->checkAuthorization(auth()->user(), ['admin.edit']);

        $admin = Admin::findOrFail($id);
        return view('backend.pages.admins.edit', [
            'admin' => $admin,
            'roles' => Role::all(),
        ]);
    }

    public function update(AdminRequest $request, int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['admin.edit']);

        $admin = Admin::findOrFail($id);
        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->cargo_id = $request->cargo_id;
        $admin->abreviacion_titulo_id = $request->abreviacion_titulo_id;
        $admin->agencia_id = $request->agencia_id;
        $admin->username = $request->username;
        if ($request->password) {
            $admin->password = Hash::make($request->password);
        }
        $admin->save();

        $admin->roles()->detach();
        if ($request->roles) {
            $admin->assignRole($request->roles);
        }

        session()->flash('success', 'El usuario ha sido actualizado!.');
        return back();
    }

    public function destroy(int $id): RedirectResponse
    {
        $this->checkAuthorization(auth()->user(), ['admin.delete']);

        $admin = Admin::findOrFail($id);
        $admin->delete();
        session()->flash('success', 'El usuario ha sido borrado!');
        return back();
    }
}

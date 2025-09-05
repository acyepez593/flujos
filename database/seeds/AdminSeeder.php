<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Admin::where('username', 'superadmin')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "SUPER ADMIN";
            $admin->email    = "augusto.yepez@sppat.gob.ec";
            $admin->username = "superadmin";
            $admin->initials = "SA";
            $admin->password = Hash::make('admin123456*');
            $admin->save();
        }

        $admin = Admin::where('username', 'gabriela.paez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "GABRIELA PÁEZ";
            $admin->email    = "gabriela.paez@sppat.gob.ec";
            $admin->username = "gabriela.paez";
            $admin->initials = "GP";
            $admin->password = Hash::make('gabriela.paez123');
            $admin->save();
        }

        $admin = Admin::where('username', 'alexis.vazquez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "ALEXIS VÁSQUEZ";
            $admin->email    = "alexis.vasquez@sppat.gob.ec";
            $admin->username = "alexis.vasquez";
            $admin->initials = "AV";
            $admin->password = Hash::make('alexis.vasquez123');
            $admin->save();
        }

        $admin = Admin::where('username', 'dominique.haro')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "DOMINIQUE HARO";
            $admin->email    = "dominique.haro@sppat.gob.ec";
            $admin->username = "dominique.haro";
            $admin->initials = "DH";
            $admin->password = Hash::make('dominique.haro123');
            $admin->save();
        }

        $admin = Admin::where('username', 'jimmy.hernandez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "JUMMY HERNÁNDEZ";
            $admin->email    = "jimmy.hernandez@sppat.gob.ec";
            $admin->username = "jimmy.hernandez";
            $admin->initials = "JH";
            $admin->password = Hash::make('jimmy.hernandez123');
            $admin->save();
        }

        $admin = Admin::where('username', 'rigo.jimenez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "RIGO JIMÉNEZ";
            $admin->email    = "rigo.jimenez@sppat.gob.ec";
            $admin->username = "rigo.jimenez";
            $admin->initials = "RJ";
            $admin->password = Hash::make('rigo.jimenez123');
            $admin->save();
        }

        $admin = Admin::where('username', 'dario.santillan')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "DARÍO SANTILLAN";
            $admin->email    = "dario.santillan@sppat.gob.ec";
            $admin->username = "dario.santillan";
            $admin->initials = "DS";
            $admin->password = Hash::make('dario.santillan123');
            $admin->save();
        }
    }
}

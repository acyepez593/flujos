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
            //$admin->initials = "SA";
            $admin->password = Hash::make('admin123456*');
            $admin->save();
        }

        $admin = Admin::where('username', 'silvia.cisneros')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "SILVIA CISNEROS";
            $admin->email    = "silvia.cisneros@sppat.gob.ec";
            $admin->username = "silvia.cisneros";
            //$admin->initials = "SC";
            $admin->password = Hash::make('Silvia.cisneros123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'maria.almeida')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "MARIA GABRIELA ALMEIDA";
            $admin->email    = "maria.almeida@sppat.gob.ec";
            $admin->username = "maria.almeida";
            //$admin->initials = "GA";
            $admin->password = Hash::make('Maria.almeida123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'dominique.haro')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "JONNATAN JAMI";
            $admin->email    = "jonnatan.jami@sppat.gob.ec";
            $admin->username = "jonnatan.jami";
            //$admin->initials = "JJ";
            $admin->password = Hash::make('Jonnatan.jami123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'andrea.proaño')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "ANDREA PROAÑO";
            $admin->email    = "andrea.proaño@sppat.gob.ec";
            $admin->username = "andrea.proaño";
            //$admin->initials = "AP";
            $admin->password = Hash::make('Andrea.proaño123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'juan.vasconez')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "JUAN ANDRES VASCONEZ";
            $admin->email    = "juan.vasconez@sppat.gob.ec";
            $admin->username = "juan.vasconez";
            //$admin->initials = "JV";
            $admin->password = Hash::make('Juan.vasconez123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'peter.koehn')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "PETER KOEHN";
            $admin->email    = "peter.koehn@sppat.gob.ec";
            $admin->username = "peter.koehn";
            //$admin->initials = "PK";
            $admin->password = Hash::make('Peter.koehn123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'victor.murillo')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "VICTOR MURILLO";
            $admin->email    = "victor.murillo@sppat.gob.ec";
            $admin->username = "victor.murillo";
            //$admin->initials = "VM";
            $admin->password = Hash::make('Victor.murillo123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'mayra.tapia')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "MAYRA TAPIA";
            $admin->email    = "mayra.tapia@sppat.gob.ec";
            $admin->username = "mayra.tapia";
            //$admin->initials = "MT";
            $admin->password = Hash::make('Mayra.tapia123*');
            $admin->save();
        }

        $admin = Admin::where('username', 'alexandra.ortega')->first();

        if (is_null($admin)) {
            $admin           = new Admin();
            $admin->name     = "ALEXANDRA ORTEGA";
            $admin->email    = "alexandra.ortega@sppat.gob.ec";
            $admin->username = "alexandra.ortega";
            //$admin->initials = "VM";
            $admin->password = Hash::make('Alexandra.ortega123*');
            $admin->save();
        }
    }
}

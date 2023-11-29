<?php

namespace Database\Seeders;

use App\Models\Assistance_request;
use App\Models\Client;
use App\Models\Technician;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Workshop;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PruebaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taller = User::create([
            'name' => 'Teo Montalvo',
            'email' => 'taller1@gmail.com',
            'password' => bcrypt('12345678'),
            'type' => 'taller'
        ]);

        $tallerTecn = Workshop::create([
            'description' => 'Somos un taller de Primer nivel',
            'location' => 'Los lotes entre 8vo y 9no anillo',
            'contact_info' => '30236526',
            'user_id' => $taller->id
        ]);

        $tecnico1 = Technician::create([
            'name' => 'Ing Milton Soto',
            'phone' => '84585325',
            'workshop_id' => $tallerTecn->id
        ]);

        $tecnico2 = Technician::create([
            'name' => 'Ing Mario Mendez',
            'phone' => '10235894',
            'workshop_id' => $tallerTecn->id
        ]);

        $cliente = User::create([
            'name' => 'Juan Roca',
            'email' => 'cliente1@gmail.com',
            'password' => bcrypt('12345678'),
            'type' => 'cliente'
        ]);

        $cliVehicle = Client::create([
            'phone' => '78415875',
            'user_id' => $cliente->id
        ]);

        $vehiculo1= Vehicle::create([
            'brand' => 'Toyota',
            'model' => 'Corolla',
            'year' => 2011,
            'licence_plate' => '123ABC',
            'client_id' => $cliVehicle->id
        ]);

        $vehiculo2 = Vehicle::create([
            'brand' => 'Mazda',
            'model' => 'CX5',
            'year' => 2022,
            'licence_plate' => '456SDF',
            'client_id' => $cliVehicle->id
        ]);


        Assistance_request::create([
            'client_id' => $cliVehicle->id,
            'workshop_id' => $tallerTecn->id,
            'vehicle_id' => $vehiculo1->id,
            'technician_id' => $tecnico1->id,
            'problem_description' => 'Se frego el carrito',
            'latitud' => -17.851482,
            'longitud' => -63.166290,
            //'photos' => '',
            //'voice_note' => '',
            'status' => 'pendiente'
        ]);

        Assistance_request::create([
            'client_id' => $cliVehicle->id,
            'workshop_id' => $tallerTecn->id,
            'vehicle_id' => $vehiculo2->id,
            'technician_id' => $tecnico2->id,
            'problem_description' => 'No da el radiador',
            'latitud' => -17.851482,
            'longitud' => -63.166290,
            //'photos' => '',
            //'voice_note' => '',
            'status' => 'pendiente'
        ]);
    }
}

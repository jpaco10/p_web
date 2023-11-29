<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Assistance_request;
use App\Models\Client;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssistanceRequestController extends Controller
{
    public function getVehicles($client_id)
    {
        try {
            $cliente = Client::where('user_id', $client_id)->first();
            $vehicles = Vehicle::where('client_id', $cliente->id)->get();

            if (!$cliente) {
                return response()->json([], 200);
            }

            if (!$vehicles) {
                return response()->json([], 200);
            }

            return response()->json($vehicles, 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al procesar la solicitud de asistencia', 'error' => $th->getMessage()], 500);
        }
    }

    public function getAssistanceRequests($client_id)
    {
        try {
            $cliente = Client::where('user_id', $client_id)->first();

            if (!$cliente) {
                return response()->json([], 200);
            }

            $respuesta = Assistance_request::leftJoin('clients', 'clients.id', 'assistance_requests.client_id')
                ->leftJoin('users as CLI', 'CLI.id', 'clients.user_id')
                ->leftJoin('workshops', 'workshops.id', 'assistance_requests.workshop_id')
                ->leftJoin('users as TA', 'TA.id', 'workshops.user_id')
                ->leftJoin('technicians', 'technicians.id', 'assistance_requests.technician_id')
                ->leftJoin('vehicles', 'vehicles.id', 'assistance_requests.vehicle_id')

                ->select(
                    'assistance_requests.id as assistance_request_id',
                    'assistance_requests.problem_description as problem_description',
                    'assistance_requests.latitud as latitud',
                    'assistance_requests.longitud as longitud',
                    'assistance_requests.photos as photos',
                    'assistance_requests.voice_note as voice_note',
                    'assistance_requests.status as status',
                    'assistance_requests.created_at as assistance_request_date',

                    // CLIENTE
                    'assistance_requests.client_id as client_id',
                    'clients.phone as client_phone',
                    'clients.user_id as client_user_id',
                    'CLI.name as client_user_name',
                    'CLI.email as client_user_email',

                    // TALLER
                    'assistance_requests.workshop_id as workshop_id',
                    'workshops.description as workshop_description',
                    'workshops.location as workshop_location',
                    'workshops.contact_info as workshop_contact_info',
                    'TA.id as workshop_user_id',
                    'TA.name as workshop_user_name',
                    'TA.email as workshop_user_email',

                    // TECNICO
                    'assistance_requests.technician_id as technician_id',
                    'technicians.name as technicians',
                    'technicians.phone as technician_phone',

                    // VEHICULO
                    'assistance_requests.vehicle_id as vehicle_id',
                    'vehicles.brand as vehicle_brand',
                    'vehicles.model as vehicle_model',
                    'vehicles.year as vehicle_year',
                    'vehicles.licence_plate as vehicle_licence_plate',
                )
                ->where('assistance_requests.client_id', $cliente->id)
                ->get();

                dd($respuesta);
            return response()->json($respuesta, 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al procesar la solicitud de asistencia', 'error' => $th->getMessage()], 500);
        }
    }

    public function clientRequestAssistance(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'user_id' => 'required|exists:users,id',
                // 'workshop_id' => 'required',
                'vehicle_id' => 'required|exists:vehicles,id',
                //'problem_description' => 'required',
                'latitud' => 'required',
                'longitud' => 'required',
                'photos' => 'required|image',
                'voice_note' => 'required',
                // 'status' => 'required',
            ]);

            $cliente = Client::where('user_id', $request->user_id)->first();

            $timestamp = time();

            $nombreImagen = $timestamp . '_' . $validatedData['photos']->getClientOriginalName();
            $nombreAudio = $timestamp . '_' . $validatedData['voice_note']->getClientOriginalName();

            $rutaImagen = $validatedData['photos']->storeAs('public/imagenes/assistance', $nombreImagen);
            $rutaAudio = $validatedData['voice_note']->storeAs('public/audios/assistance', $nombreAudio);

            $urlImagen = Storage::url($rutaImagen);
            $urlAudio = Storage::url($rutaAudio);

            $assistanceData = [
                'client_id' => $cliente->id,
                'workshop_id' => 1,
                'vehicle_id' => $request->vehicle_id,
                'technician_id' => 1,
                'problem_description' => "hola",
                'latitud' => $request->latitud,
                'longitud' => $request->longitud,
                'photos' => $urlImagen,
                'voice_note' => $urlAudio,
                'status' => 'pendiente',
            ];

            $assistance = Assistance_request::create($assistanceData);

            return response()->json(['message' => 'Asistencia creada correctamente'], 201);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Error al procesar la solicitud de asistencia', 'error' => $th->getMessage()], 500);
        }
    }
}

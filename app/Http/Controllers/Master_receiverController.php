<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\TypeEquipment;
use App\Models\RepairRequest;
use App\Models\Appliance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class Master_receiverController extends Controller
{
    public function index()
    {
        return view('master_receiver.index');
    }

    public function zakaz()
    {
        $cart = Session::get('cart', []);
        $types = TypeEquipment::all();
        return view('master_receiver.zakaz', compact('types'));
    }

    public function order(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string'],
            'phone' => ['required', 'digits:11', 'numeric'],
            'email' => ['required', 'email'],
            'adres' => ['required_if:delivery,1', 'nullable', 'string'],
            'comment' => ['nullable', 'max:5000'],
            'consent' => 'accepted',
            'delivery' => ['nullable', 'in:0,1'],
            'type' => ['exists:type_equipment,id'],
            'brand' => ['required', 'string'],
            'model' => ['required', 'string'],
            'master_time' => ['nullable', 'required_if:delivery,1', ],
        ]);


        // Преобразуем 'master_time' в нужный формат (Y-m-d H:i)
        if ($validatedData['master_time']) {
            $validatedData['master_time'] = Carbon::createFromFormat('Y-m-d\TH:i', $validatedData['master_time'])->format('Y-m-d H:i');
        }
        // Получаем id статуса "Зарегистрирована"
        $idStatus = DB::table('status')->where('status', 'Зарегистрирована')->value('id');

        $clientData = $this->splitFullName($validatedData['name']);

        $client = Client::updateOrCreate(
            ['phone' => $validatedData['phone']],
            $clientData + ['email' => $validatedData['email']]
        );

        DB::beginTransaction();
        try {
            $appliance = Appliance::create([
                'id_client' => $client->id,
                'brand' => $validatedData['brand'],
                'model' => $validatedData['model'],
                'id_type_equipment' => $validatedData['type'],
            ]);

            RepairRequest::create([
                'id_client' => $client->id,
                'id_appliance' => $appliance->id,
                'issue_description' => $validatedData['comment'],
                'preferred_visit_time' => $validatedData['master_time'],
                'id_status' => $idStatus,
            ]);

            DB::commit();
            return redirect()->route('cart.view')->with('success', 'Заявка успешно оформлена!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Ошибка при оформлении заявки: ' . $e->getMessage()])->withInput();
        }
    }

    private function splitFullName($fullName)
    {
        $parts = explode(' ', $fullName, 3);
        return [
            'surname' => $parts[0] ?? '',
            'name' => $parts[1] ?? '',
            'patronymic' => $parts[2] ?? '',
        ];
    }
}

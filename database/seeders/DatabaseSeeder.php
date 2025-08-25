<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Center;
use App\Models\Device;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\TransferInvoice;
use App\Models\TransferInvoiceItem;
use App\Models\Unit;
use App\Models\User;
use App\Models\Vital;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Centers
        $centers = Center::factory()->count(5)->create();
        $devices = Device::factory()->count(10)->create();

        Unit::factory()->count(5)->create();

        // Helper functions to get random IDsw
        $randCenter = fn() => $centers->random()->id;
        $randDevice = fn() => $devices->random()->id;

        // 2. Create Users
        $admin = User::factory()->create([
            'name' => 'Abdurahman',
            'email' => 'admin@gmail.com',
            'center_id' => $randCenter(),
        ]);

        $users = User::factory(20)
            ->create()
            ->each(function ($user) use ($randCenter) {
                $user->center_id = $randCenter();
                $user->save();
            });

        // 3. Create Patients
        $patients = Patient::factory(50)
            ->create()
            ->each(function ($patient) use ($randCenter, $randDevice) {
                $patient->center_id = $randCenter();
                $patient->device_id = $randDevice();
                $patient->save();
            });

        // 4. Create Products & Suppliers
        $products = Product::factory(30)->create();
        $suppliers = Supplier::factory(10)->create();

        // Helper functions for random IDs after creation
        $randPatient = fn() => $patients->random()->id;
        $randDoctor = fn() => User::where('doctor', true)->get()->random()->id;
        $randProduct = fn() => $products->random()->id;
        $randSupplier = fn() => $suppliers->random()->id;

        // 5. Create Appointments
        Appointment::factory(100)
            ->create()
            ->each(function ($appointment) use ($randCenter, $randPatient, $randDoctor, $randDevice) {
                $appointment->center_id = $randCenter();
                $appointment->patient_id = $randPatient();
                $appointment->doctor_id = $randDoctor();
                $appointment->device_id = $randDevice();
                $appointment->save();
            });

        // 6. Create Orders + OrderItems
        Order::factory(80)
            ->create()
            ->each(function (Order $order) use ($randCenter, $randPatient, $randProduct) {
                $order->center_id = $randCenter();
                $order->patient_id = $randPatient();
                $order->save();

                OrderItem::factory(rand(1, 5))
                    ->create()
                    ->each(function ($item) use ($order, $randProduct) {
                        $item->order_id = $order->id;
                        $item->product_id = $randProduct();
                        $item->save();
                    });
            });

        // 7. Create Invoices + InvoiceItems
        Invoice::factory(40)
            ->create()
            ->each(function (Invoice $inv) use ($randCenter, $randSupplier, $randProduct) {
                $inv->center_id = $randCenter();
                $inv->supplier_id = $randSupplier();
                $inv->save();

                InvoiceItem::factory(rand(1, 4))
                    ->create()
                    ->each(function ($item) use ($inv, $randProduct) {
                        $item->invoice_id = $inv->id;
                        $item->product_id = $randProduct();
                        $item->save();
                    });
            });

        // 8. Create TransferInvoices + TransferInvoiceItems
        TransferInvoice::factory(20)
            ->create()
            ->each(function (TransferInvoice $ti) use ($randCenter, $randProduct) {
                $ti->from_center_id = $randCenter();
                $ti->to_center_id = $randCenter();
                $ti->save();

                TransferInvoiceItem::factory(rand(1, 3))
                    ->create()
                    ->each(function ($item) use ($ti, $randProduct) {
                        $item->transfer_invoice_id = $ti->id;
                        $item->product_id = $randProduct();
                        $item->save();
                    });
            });

        // 9. Create Vitals for random Patients
        $patients->random(30)->each(function (Patient $patient) {
            Vital::factory(30)
                ->create(['patient_id' => $patient->id]);
        });
    }
}

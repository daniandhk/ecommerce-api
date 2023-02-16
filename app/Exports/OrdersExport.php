<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OrdersExport implements FromView, Responsable, ShouldAutoSize
{
    use Exportable;
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $orders = Order::with('product', 'user')->latest()->get();
        return view('exports.orders', [
            'orders' => $orders,
        ]);
    }
}

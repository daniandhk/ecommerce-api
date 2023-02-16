<table>
    <thead>
        <tr>
            <td>Email</td>
            <td>Barang</td>
            <td>Harga</td>
            <td>Jumlah</td>
            <td>Total</td>
            <td>Alamat</td>
            <td>Status</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>{{ $order->user->email }}</td>
                <td>{{ $order->product->name }}</td>
                <td>Rp. {{ number_format($order->product->price, 0, ',', '.') }} / pcs</td>
                <td>{{ $order->quantity }} pcs</td>
                <td>Rp. {{ number_format($order->product->price * $order->quantity, 0, ',', '.') }}</td>
                <td>{{ $order->address }}</td>
                <td>{{ $order->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

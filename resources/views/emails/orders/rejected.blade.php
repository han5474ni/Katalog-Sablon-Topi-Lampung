@component('mail::message')
# Pesanan Anda Tidak Dapat Diproses

Halo {{ $order->user->name }},

Mohon maaf, pesanan Anda dengan nomor **{{ $order->order_number }}** tidak dapat diproses karena:

{{ $rejectionReason }}

Detail Pesanan:
- Nomor Pesanan: {{ $order->order_number }}
- Total: Rp {{ number_format($order->total_amount, 0, ',', '.') }}
- Tanggal Pesanan: {{ $order->created_at->format('d M Y H:i') }}

Anda dapat menghubungi kami untuk informasi lebih lanjut atau membuat pesanan baru melalui website kami.

@component('mail::button', ['url' => url('/')])
Kembali ke Website
@endcomponent

Terima kasih atas pengertian Anda.

Salam,<br>
{{ config('app.name') }}
@endcomponent
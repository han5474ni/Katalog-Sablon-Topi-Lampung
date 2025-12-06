<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CustomDesignOrder;
use App\Models\CustomerAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppController extends Controller
{
    public function send(Request $request, string $type, int $id)
    {
        $adminWa = config('services.whatsapp.admin_phone')
            ?: env('ADMIN_WHATSAPP')
            ?: '6289508585888'; // default fallback

        // Build message text server-side for consistent formatting
        $text = '';
        if ($type === 'custom') {
            /** @var CustomDesignOrder|null $order */
            $order = CustomDesignOrder::with(['variant', 'product', 'uploads'])->find($id);
            if (!$order) {
                abort(404);
            }

            $orderNumber = $order->order_number ?? $order->id;
            $unitPrice = (float)($order->product_price ?? 0);
            $qty = (int)($order->quantity ?? 1);
            $variantText = '';
            if ($order->variant) {
                $vt = [];
                if ($order->variant->color) $vt[] = $order->variant->color;
                if ($order->variant->size) $vt[] = $order->variant->size;
                if (!empty($vt)) $variantText = ' • ' . implode(' • ', $vt);
            }

            $shippingService = $this->labelShippingService($order->shipping_service ?? null);
            $address = null;
            if (!empty($order->customer_address_id)) {
                $address = CustomerAddress::find($order->customer_address_id);
            }
            $recipient = $address?->recipient_name;
            $recipientPhone = $address?->phone;
            $addressLine = $address ? $address->formatted_address : null;

            $waLines = [];
            $waLines[] = "Halo Admin,";
            $waLines[] = "Saya ingin membayar pesanan berikut:";
            $waLines[] = "";
            $waLines[] = "🧾 Pesanan #{$orderNumber}";
            $waLines[] = "Jenis: Custom Design";
            $waLines[] = "";
            $waLines[] = "Daftar Produk:";
            $lineProduct = sprintf("- %s%s x%d — Rp %s", $order->product_name, $variantText, $qty, number_format($unitPrice ?: ((float)$order->total_price / max($qty,1)), 0, ',', '.'));
            $waLines[] = $lineProduct;
            $waLines[] = "";
            $waLines[] = "Total Pembayaran: Rp " . number_format((float)$order->total_price, 0, ',', '.');
            if ($shippingService || $addressLine) {
                $waLines[] = "";
                $waLines[] = "Pengiriman: " . ($shippingService ?: '-');
                if ($recipient) $waLines[] = "Penerima: {$recipient}" . ($recipientPhone ? " ({$recipientPhone})" : '');
                if ($addressLine) $waLines[] = "Alamat: {$addressLine}";
            }
            $waLines[] = "";
            $waLines[] = "Mohon info langkah pembayaran. Terima kasih.";
            $text = implode("\n", $waLines);
        } else {
            /** @var Order|null $order */
            $order = Order::find($id);
            if (!$order) {
                abort(404);
            }

            $orderNumber = $order->order_number ?? $order->id;
            $address = $order->address; // lazy-loaded relation
            $recipient = $address?->recipient_name;
            $recipientPhone = $address?->phone;
            $addressLine = $address ? $address->formatted_address : null;
            $shippingService = $this->labelShippingService($order->shipping_service ?? null);

            $items = is_array($order->items) ? $order->items : [];
            $waLines = [];
            $waLines[] = "Halo Admin,";
            $waLines[] = "Saya ingin membayar pesanan berikut:";
            $waLines[] = "";
            $waLines[] = "🧾 Pesanan #{$orderNumber}";
            $waLines[] = "Jenis: Reguler";
            $waLines[] = "";
            $waLines[] = "Daftar Produk:";
            $subtotalCalc = 0;
            foreach ($items as $idx => $it) {
                $name = $it['name'] ?? 'Produk';
                $color = !empty($it['color']) ? (' • ' . $it['color']) : '';
                $size = !empty($it['size']) ? (' • ' . $it['size']) : '';
                $qty = isset($it['quantity']) ? (int)$it['quantity'] : 0;
                $price = isset($it['price']) ? (float)$it['price'] : 0;
                $lineTotal = $price * $qty;
                $subtotalCalc += $lineTotal;
                $waLines[] = sprintf("%d) %s%s%s x%d — Rp %s", $idx + 1, $name, $color, $size, $qty, number_format($lineTotal, 0, ',', '.'));
                $waLines[] = sprintf("   Harga satuan: Rp %s", number_format($price, 0, ',', '.'));
            }
            $waLines[] = "";
            $waLines[] = "Subtotal: Rp " . number_format($subtotalCalc, 0, ',', '.');
            $waLines[] = "Total Pembayaran: Rp " . number_format((float)$order->total, 0, ',', '.');
            if ($shippingService || $addressLine) {
                $waLines[] = "";
                $waLines[] = "Pengiriman: " . ($shippingService ?: '-');
                if ($recipient) $waLines[] = "Penerima: {$recipient}" . ($recipientPhone ? " ({$recipientPhone})" : '');
                if ($addressLine) $waLines[] = "Alamat: {$addressLine}";
            }
            $waLines[] = "";
            $waLines[] = "Mohon info langkah pembayaran. Terima kasih.";
            $text = implode("\n", $waLines);
        }

        // Choose appropriate endpoint based on user agent
        $ua = strtolower($request->header('User-Agent', ''));
        $isMobile = preg_match('/android|iphone|ipad|mobile/', $ua) === 1;
        $encodedText = urlencode($text);

        $redirectUrl = $isMobile
            ? ('https://api.whatsapp.com/send?phone=' . $adminWa . '&text=' . $encodedText . '&app_absent=0')
            : ('https://web.whatsapp.com/send?phone=' . $adminWa . '&text=' . $encodedText . '&app_absent=0');

        Log::info('WhatsApp redirect initiated', [
            'type' => $type,
            'order_id' => $id,
            'admin_phone' => $adminWa,
            'is_mobile' => $isMobile,
            'text_length' => strlen($text),
        ]);

        return redirect()->away($redirectUrl);
    }

    private function labelShippingService(?string $service): ?string
    {
        $s = strtolower((string)$service);
        return match ($s) {
            'jne' => 'JNE',
            'jnt', 'j&t' => 'J&T',
            'sicepat' => 'SiCepat',
            'pos' => 'POS Indonesia',
            'pickup' => 'Ambil di Toko',
            default => ($service ?: null),
        };
    }
}
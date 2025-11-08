@props(['product', 'showRibbon' => true])

<div class="product-card"
     data-product-id="{{ $product->id }}"
     data-product-slug="{{ $product->slug }}"
     data-product-name="{{ $product->name }}"
     data-product-price="{{ $product->formatted_price }}"
     data-product-image="{{ $product->image ? (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://') ? $product->image : asset('storage/' . $product->image)) : '' }}"
     data-variant-images="{{ json_encode($product->variant_images ?? []) }}">
    
    <!-- Product Image Container -->
    <div class="product-image-container" 
         data-product-id="{{ $product->id }}">
        
        @php
            // Priority: variant_images[0] > product->image > placeholder
            $displayImage = null;
            if (!empty($product->variant_images) && is_array($product->variant_images) && count($product->variant_images) > 0) {
                $displayImage = $product->variant_images[0];
            } elseif ($product->image) {
                // Check if image is already a full URL (http/https)
                if (str_starts_with($product->image, 'http://') || str_starts_with($product->image, 'https://')) {
                    $displayImage = $product->image;
                } else {
                    $displayImage = asset('storage/' . $product->image);
                }
            }
        @endphp
        
        @if($displayImage)
            <img class="product-image" 
                 src="{{ $displayImage }}" 
                 alt="{{ $product->name }}"
                 loading="lazy"
                 onload="console.log('✅ Image loaded:', this.src);"
                 onerror="console.error('❌ Image failed:', this.src); if(!this.dataset.errorHandled) { this.dataset.errorHandled='1'; this.src='https://via.placeholder.com/400x400/e0e0e0/666666?text=No+Image'; }">
        @else
            <div class="no-image-placeholder">
                <i class="fas fa-image"></i>
            </div>
        @endif
        
        <!-- CUSTOM Ribbon (Hidden by default) -->
        @if($showRibbon && !empty($product->custom_design_allowed) && $product->custom_design_allowed)
            <div class="product-ribbon" aria-hidden="true">
                CUSTOM
            </div>
        @endif
        
    </div>
    
    <!-- Product Info -->
    <div class="product-info">
        <h3 class="product-title">
            {{ $product->name }}
        </h3>
        <p class="product-price">
            Rp {{ $product->formatted_price }}
        </p>
        
        <!-- Action Buttons (Both White with Black Icons) -->
        <div class="product-actions" role="group" aria-label="Aksi produk">
            <button class="action-btn action-chat" type="button" aria-label="Chat tentang produk">
                <i class="fas fa-comments" aria-hidden="true"></i>
            </button>
            <button class="action-btn action-cart" type="button" aria-label="Tambahkan ke keranjang" data-product-id="{{ $product->id }}">
                <i class="fas fa-shopping-cart" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</div>

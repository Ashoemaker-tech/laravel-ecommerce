<div class="space-y-6">
    @if ($initialVariation)

    <livewire:product-dropdown :variations="$initialVariation" />
        
    @endif

    @if ($skuVariant)
    <div class="space-y-6">
        <div class="text-lg font-semibold">
            {{ $skuVariant->formattedPrice() }}
        </div>
        <x-primary-button wire:click.prevent="addToCart">Add to cart</x-primary-button>
    </div>
        
    @endif

</div>

<div>
    <div class="space-y-4">
        <img src="{{ $selectedImageUrl }}" alt="">
        
        <div class="grid grid-cols-6 gap-2">
            @foreach ($product->getMedia() as $media)
                
            <button wire:click="selectImage('{{ $media->getUrl() }}')">
                <img src="{{ $media->getUrl('thumbnail') }}" alt="">
            </button>
            @endforeach
        </div>
    </div>
</div>

@extends('layouts.admin')

@section('title', isset($car) ? 'Edit Vehicle - Admin' : 'Create Vehicle - Admin')
@section('topTitle', isset($car) ? 'Edit Vehicle' : 'Create Vehicle')

@section('content')
<div class="wrap">
    <div class="whitePanel" style="max-width: 900px; margin: 0 auto;">
        <div class="panelHead">
            <div>
                <h5>{{ isset($car) ? 'Edit Vehicle' : 'Create New Vehicle' }}</h5>
                <small>{{ isset($car) ? 'Update vehicle information' : 'Add a new Tesla vehicle to inventory' }}</small>
            </div>
            <a href="{{ route('admin.inventory') }}" style="padding: 8px 16px; border-radius: 8px; background: #6b7280; color: white; font-size: 12px; font-weight: 900; text-decoration: none;">
                ← Back
            </a>
        </div>

        @if($errors->any())
            <div style="margin: 12px 14px; padding: 12px; background: #fee2e2; border: 1px solid #ef4444; border-radius: 8px;">
                <ul style="margin: 0; padding-left: 20px; color: #dc2626; font-size: 12px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ isset($car) ? route('admin.inventory.update', $car) : route('admin.inventory.store') }}" enctype="multipart/form-data" style="padding: 20px;">
            @csrf
            @if(isset($car))
                @method('PUT')
            @endif

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Vehicle Name *</label>
                <input type="text" name="name" value="{{ old('name', $car->name ?? '') }}" required
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="e.g., Tesla Model 3" />
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Model *</label>
                    <input type="text" name="model" value="{{ old('model', $car->model ?? '') }}" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., Model 3" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Year *</label>
                    <input type="number" name="year" value="{{ old('year', $car->year ?? '') }}" min="1900" max="{{ date('Y') + 1 }}" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="{{ date('Y') }}" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Variant</label>
                    <input type="text" name="variant" value="{{ old('variant', $car->variant ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., Long Range" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Price *</label>
                    <input type="number" name="price" value="{{ old('price', $car->price ?? '') }}" step="0.01" min="0" required
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0.00" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Range (miles)</label>
                    <input type="number" name="range_miles" value="{{ old('range_miles', $car->range_miles ?? '') }}" min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Top Speed (mph)</label>
                    <input type="number" name="top_speed_mph" value="{{ old('top_speed_mph', $car->top_speed_mph ?? '') }}" min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">0-60 (seconds)</label>
                    <input type="number" name="zero_to_sixty" value="{{ old('zero_to_sixty', $car->zero_to_sixty ?? '') }}" step="0.1" min="0"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="0.0" />
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Drivetrain</label>
                    <input type="text" name="drivetrain" value="{{ old('drivetrain', $car->drivetrain ?? '') }}"
                        style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                        placeholder="e.g., AWD" />
                </div>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Display Order</label>
                <input type="number" name="display_order" value="{{ old('display_order', $car->display_order ?? '') }}" min="0"
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="0" />
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Car Images (Multiple images allowed)</label>
                <input type="file" name="images[]" multiple accept="image/jpeg,image/jpg,image/png,image/webp"
                    style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    id="car-images-input" />
                <p style="margin-top: 6px; font-size: 11px; color: #9ca3af;">
                    Upload multiple images. Images will be stored in public/cars/ directory. Max 5MB per image.
                </p>
            </div>

            @if(isset($car) && !empty($car->getAllImages()))
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Current Images</label>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px;">
                        @foreach($car->getAllImages() as $index => $imagePath)
                            <div style="position: relative; border: 1px solid rgba(0,0,0,.1); border-radius: 8px; overflow: hidden;">
                                <img src="{{ asset($imagePath) }}" alt="{{ $car->name }} Image {{ $index + 1 }}" 
                                    style="width: 100%; height: 120px; object-fit: cover; display: block;" />
                                <label style="position: absolute; top: 4px; right: 4px; cursor: pointer; background: rgba(239,68,68,0.9); color: white; padding: 4px 8px; border-radius: 4px; font-size: 10px; font-weight: 900;">
                                    <input type="checkbox" name="delete_images[]" value="{{ $imagePath }}" style="display: none;" onchange="this.parentElement.parentElement.style.opacity = this.checked ? '0.5' : '1';" />
                                    Delete
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 11px; font-weight: 900; color: #6b7280; margin-bottom: 6px;">Image URL (Legacy - Optional)</label>
                <input type="url" name="image_url" value="{{ old('image_url', $car->image_url ?? '') }}"
                    style="width: 100%; height: 40px; padding: 0 12px; border-radius: 8px; border: 1px solid rgba(0,0,0,.1); font-size: 13px;"
                    placeholder="https://example.com/image.jpg" />
                <p style="margin-top: 6px; font-size: 11px; color: #9ca3af;">
                    Optional: External image URL (for backward compatibility)
                </p>
            </div>

            <div style="margin-bottom: 16px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: #111827;">
                    <input type="checkbox" name="is_available" value="1" {{ old('is_available', $car->is_available ?? true) ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer;" />
                    Available for purchase
                </label>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; font-weight: 700; color: #111827;">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $car->is_featured ?? false) ? 'checked' : '' }}
                        style="width: 18px; height: 18px; cursor: pointer;" />
                    Show on Homepage
                </label>
                <p style="margin-top: 6px; font-size: 11px; color: #9ca3af;">
                    Featured cars will appear on the homepage. Only available cars can be featured.
                </p>
            </div>

            <div style="display: flex; gap: 12px;">
                <button type="submit" style="flex: 1; height: 44px; border-radius: 8px; background: #111827; color: white; font-size: 13px; font-weight: 900; cursor: pointer; border: none;">
                    {{ isset($car) ? 'Update Vehicle' : 'Create Vehicle' }}
                </button>
                <a href="{{ route('admin.inventory') }}" style="flex: 1; height: 44px; border-radius: 8px; background: #ef4444; color: white; font-size: 13px; font-weight: 900; text-decoration: none; display: flex; align-items: center; justify-content: center;">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<style>
@media (max-width: 768px) {
    .wrap > div {
        margin: 0 !important;
    }
    form > div[style*="grid"] {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endsection

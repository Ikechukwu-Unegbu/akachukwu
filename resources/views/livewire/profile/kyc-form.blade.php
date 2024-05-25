<div class="profile_form border-bottom mb-7 border-3 ">
    <div>
        <h3>{{ !$check_bvn_exists ? 'Update Your Static Account' : 'BVN Linked Succssfully' }}</h3>
    </div>
    <form wire:submit.prevent="submit">
        <div class="mb-3">
            @if (!$check_bvn_exists)<label for="bvn" class="form-label">BVN (Bank Verification Number)</label>@endif
            <input type="text" class="form-control" wire:model="bvn" name="bvn" id="bvn" {{ $check_bvn_exists ? 'readonly' : '' }}>
            @error('bvn')<div id="bvn_error" class="form-text text-danger">{{$message}}</div>@enderror
        </div>
        @if (!$check_bvn_exists)
        <div class="mb-3">
            <button class="btn btn-dark">Save</button>
        </div>
        @endif
    </form>
</div>
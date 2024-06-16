<x-ashop-ashop:user-account>
    <x-slot:title>
        <h4 class="my-auto">
            <i class="fa-regular fa-address-book me-2"></i> Address Book
        </h4>
        <a href="{{ route('shop.user.addresses.create') }}" class="btn btn-sm btn-primary px-3">
            <i class="fa-solid fa-plus"></i> New
        </a>
    </x-slot:title>
    <div id="user_addresses">
        @foreach ($addresses as $address)
            <div class="card mb-3 {{ $address->default ? 'border-info border-2' : '' }}">
                <div class="card-body">
                    <p class="fw-bold mb-0">
                        @if ($address->default)
                            <span class="badge bg-primary">Default</span>
                        @endif
                        @if ($address->billing_addr)
                            <span class="badge bg-info">Billing</span>
                        @endif

                        <span>{{ $address->name }},</span>
                        <span>{{ $address->mobile }}</span>
                    </p>
                    <p class="mb-0 small">
                        <span>{{ $address->address_line_1 }},</span>
                        <span>{{ $address->address_line_2 }}</span>
                        <span>{{ $address->landmark }},</span>
                    </p>
                    <p class="mb-0 small">
                        <span>{{ $address->city }},</span>
                        <span>{{ $address->state }}</span>,
                        <span>{{ $address->pincode }}</span>
                    </p>
                </div>
                <div class="card-footer d-flex justify-content-between">
                    <span>
                        <a href="{{ route('shop.user.addresses.edit', [$address]) }}"
                            class="btn btn-sm btn-success px-3">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </a>
                        <form action="{{ route('shop.user.addresses.destroy', [$address]) }}" method="POST"
                            class="d-inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger px-3">
                                <i class="fa-solid fa-trash"></i> Delete
                            </button>
                        </form>
                    </span>
                    @if (!$address->default)
                        <a href="{{ route('shop.user.addresses.make-default', [$address]) }}"
                            class="btn btn-sm btn-info px-3">
                            <i class="fa-solid fa-house"></i> Make Default
                        </a>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</x-ashop-ashop:user-account>

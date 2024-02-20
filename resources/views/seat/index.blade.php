@php
    use App\Enums\SeatStatus;
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Seats
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if(session('success'))
                        <div class="mb-6 font-medium text-sm text-green-600 dark:text-green-400" role="alert">
                            <span class="font-medium">
                                Reservation successful! <br />
                                {{ session('success') }}
                            </span>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        @method('POST')

                        <div class="grid grid-cols-12 gap-4">
                            @foreach($seats as $seat)
                                <label
                                    for="seat[{{ $seat->id }}]"
                                    class="
                                        block
                                        bg-gray-200
                                        dark:bg-gray-700
                                        p-4
                                        text-center
                                        rounded-md
                                        border
                                        @switch($seat->status)
                                            @case(SeatStatus::RESERVED->value)
                                                border-yellow-300
                                                @break
                                            @case(SeatStatus::TAKEN->value)
                                                border-red-400
                                                @break
                                            @default
                                                border-green-400
                                        @endswitch
                                    "
                                >
                                    <input
                                        type="checkbox"
                                        name="seat[]"
                                        id="seat[{{ $seat->id }}]"
                                        value="{{ $seat->id }}"
                                        class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                                        @if($seat->status !== SeatStatus::FREE->value) disabled @endif
                                    />
                                    <br />

                                    <span
                                        class="
                                            text-xs
                                            capitalize
                                            @switch($seat->status)
                                                @case(SeatStatus::RESERVED->value)
                                                    text-yellow-800 dark:text-yellow-300
                                                    @break
                                                @case(SeatStatus::TAKEN->value)
                                                    text-red-800 dark:text-red-400
                                                    @break
                                                @default
                                                    text-green-800 dark:text-green-400
                                            @endswitch
                                        "
                                    >
                                        @switch($seat->status)
                                            @case(SeatStatus::RESERVED->value)
                                                {{ SeatStatus::RESERVED->value }}
                                                @break
                                            @case(SeatStatus::TAKEN->value)
                                                {{ SeatStatus::TAKEN->value }}
                                                @break
                                            @default
                                                {{ SeatStatus::FREE->value }}
                                        @endswitch
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        <x-input-error :messages="$errors->get('seat')" class="mt-2" />

                        <div class="mt-8">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                Reserve seat(s)
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

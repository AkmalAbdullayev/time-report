@props([
    "colspan" => null
])

<th {{ $attributes->merge(['colspan' => $colspan]) }}>
    {{ $slot }}
</th>

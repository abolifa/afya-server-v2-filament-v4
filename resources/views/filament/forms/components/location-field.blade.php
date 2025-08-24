@php
    $mapId     = 'map-' . $getId();
    $latField  = $getLatitudeField();
    $lngField  = $getLongitudeField();
    $addrField = $getAddressField();
    $strField  = $getStreetField();
    $ctyField  = $getCityField();
@endphp

<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @once
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @endonce

    <div class="w-full">
        <div id="{{ $mapId }}" class="w-full rounded-md h-full" style="height:350px;background:#fee2e2; z-index: 0"
             wire:ignore></div>
    </div>

    <script>
        (function () {
            const MAP_ID = @json($mapId);
            const LAT_PATH = @json("data.$latField");
            const LNG_PATH = @json("data.$lngField");
            const ADR_PATH = @json("data.$addrField");
            const STR_PATH = @json("data.$strField");
            const CTY_PATH = @json("data.$ctyField");

            const DEFAULT_LAT = 32.8872, DEFAULT_LNG = 13.1913;

            function getWireFor(el) {
                const root = el.closest('[wire\\:id]');
                if (!root) return null;
                const id = root.getAttribute('wire:id');
                return window.Livewire?.find ? window.Livewire.find(id) : null;
            }

            function currentLatLng(el) {
                try {
                    const w = getWireFor(el);
                    const lat = Number(w?.get(LAT_PATH)), lng = Number(w?.get(LNG_PATH));
                    return {lat: isFinite(lat) ? lat : DEFAULT_LAT, lng: isFinite(lng) ? lng : DEFAULT_LNG};
                } catch {
                    return {lat: DEFAULT_LAT, lng: DEFAULT_LNG};
                }
            }

            async function reverseGeocode(el, lat, lng) {
                try {
                    const url = new URL('https://nominatim.openstreetmap.org/reverse');
                    url.searchParams.set('format', 'jsonv2');
                    url.searchParams.set('lat', lat);
                    url.searchParams.set('lon', lng);
                    url.searchParams.set('accept-language', 'ar');

                    const res = await fetch(url.toString(), {headers: {'User-Agent': 'Afya-Filament-Map/1.0'}});
                    if (!res.ok) return;
                    const data = await res.json();
                    const addr = data?.address ?? {};
                    const display = data?.display_name || '';

                    // Build street & city fallbacks
                    const houseNo = addr.house_number;
                    const road = addr.road || addr.residential || addr.pedestrian || addr.path || addr.suburb;
                    const street = [houseNo, road].filter(Boolean).join(' ') || road || '';

                    const city =
                        addr.city ||
                        addr.town ||
                        addr.village ||
                        addr.municipality ||
                        addr.county ||
                        addr.state ||
                        addr.region || '';

                    const w = getWireFor(el);
                    if (!w) return;
                    if (display) w.set(ADR_PATH, display);
                    if (street) w.set(STR_PATH, street);
                    if (city) w.set(CTY_PATH, city);
                } catch {
                }
            }

            function initLeaflet() {
                const el = document.getElementById(MAP_ID);
                if (!el) return;
                if (el.offsetWidth === 0 || el.offsetHeight === 0) return setTimeout(initLeaflet, 100);
                if (el.dataset.inited === '1') return;
                if (typeof L === 'undefined') return setTimeout(initLeaflet, 100);

                const {lat, lng} = currentLatLng(el);
                const map = L.map(el).setView([lat, lng], 7);
                el.dataset.inited = '1';

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {attribution: '&copy; OpenStreetMap contributors'}).addTo(map);
                const marker = L.marker([lat, lng], {draggable: true}).addTo(map);

                function setWire(lat, lng, doReverse = false) {
                    const w = getWireFor(el);
                    if (!w) return;
                    w.set(LAT_PATH, Number(lat.toFixed(6)));
                    w.set(LNG_PATH, Number(lng.toFixed(6)));
                    if (doReverse) reverseGeocode(el, lat, lng);
                }

                marker.on('dragend', e => {
                    const p = e.target.getLatLng();
                    setWire(p.lat, p.lng, true);
                });
                map.on('click', e => {
                    marker.setLatLng(e.latlng);
                    setWire(e.latlng.lat, e.latlng.lng, true);
                });

                setTimeout(() => map.invalidateSize(), 50);
                window.addEventListener('resize', () => map.invalidateSize());
                window.addEventListener('reflow-map', () => map.invalidateSize());
                setTimeout(() => {
                    el.style.background = 'transparent';
                }, 200);
            }

            document.addEventListener('DOMContentLoaded', initLeaflet);
            window.addEventListener('livewire:navigated', initLeaflet);
            setTimeout(initLeaflet, 400);
        })();
    </script>
</x-dynamic-component>

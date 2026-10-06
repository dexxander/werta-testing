@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://unpkg.com/maplibre-gl@5.12.0/dist/maplibre-gl.css">

    @include('partials.navbar')

    @php($hasLocations = count($locations) > 0)

    <main class="guidance-center-main">
        <section class="guidance-center-hero" aria-labelledby="guidance-center-title">
            <div class="container">
                <p class="pill-label">{{ __('In-person support') }}</p>
                <h1 id="guidance-center-title">{{ __('Guidance Center') }}</h1>
                <p>{{ __('Find our configured guidance location and the information you need before visiting.') }}</p>
            </div>
        </section>

        <section class="guidance-center-section" aria-labelledby="location-heading">
            <div class="container">
                <div class="guidance-center-grid guidance-center-grid--map-only">
                    <div class="guidance-map-card">
                        <h2 id="location-heading" class="visually-hidden">{{ __('Guidance Center location map') }}</h2>
                        <div
                            id="guidance-center-map"
                            class="guidance-map"
                            role="application"
                            aria-label="{{ $hasLocations ? __('Map showing :count configured Guidance Center locations', ['count' => count($locations)]) : __('Guidance Center map with no locations configured') }}"
                        ></div>
                        <p id="guidance-map-status" class="guidance-map-status" role="status" aria-live="polite"></p>
                        <noscript>
                            <p class="guidance-map-status">{{ __('JavaScript is required to display the map.') }}</p>
                        </noscript>
                    </div>

                    @if($hasLocations)
                        <aside
                            id="guidance-location-details"
                            class="guidance-location-card"
                            aria-label="{{ __('Location details') }}"
                            tabindex="-1"
                            hidden
                        >
                            <h2 id="guidance-location-name"></h2>
                            <p class="guidance-address">
                                <i class="bi bi-signpost-2" aria-hidden="true"></i>
                                <span id="guidance-location-address"></span>
                            </p>
                            <div id="guidance-location-extra" class="guidance-details" hidden>
                                <i class="bi bi-info-circle" aria-hidden="true"></i>
                                <p id="guidance-location-additional-details"></p>
                            </div>
                        </aside>
                    @endif
                </div>
            </div>
        </section>
    </main>

    @include('partials.footer')

    <style>
        .guidance-center-main { min-height: 65vh; }

        .guidance-center-hero {
            padding: 64px 0 52px;
            text-align: center;
            background: var(--cream-light);
            border-bottom: 1px solid rgba(123, 107, 53, 0.18);
        }

        .guidance-center-hero h1 {
            margin: 0 0 0.85rem;
            font-family: 'IM Fell English', serif;
            font-size: clamp(2.5rem, 6vw, 4rem);
            line-height: 1.1;
            color: var(--dark);
        }

        .guidance-center-hero p:last-child {
            max-width: 650px;
            margin: 0 auto;
            color: var(--muted);
            font-size: var(--text-md);
            line-height: 1.7;
        }

        .guidance-center-section { padding: var(--section-py) 0; }

        .guidance-center-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 2rem;
            align-items: stretch;
        }

        .guidance-center-grid--map-only { grid-template-columns: minmax(0, 1fr); }

        .guidance-map-card,
        .guidance-location-card {
            overflow: hidden;
            border-radius: var(--radius-xl);
            background: var(--cream-light);
            box-shadow: var(--card-ring), var(--shadow-md);
        }

        .guidance-map { width: 100%; min-height: 470px; }

        .guidance-map-marker {
            display: flex;
            align-items: flex-start;
            gap: 4px;
            padding: 0;
            border: 0;
            background: transparent;
            text-align: left;
            cursor: pointer;
        }

        .guidance-map-marker:focus-visible {
            outline: 3px solid var(--gold);
            outline-offset: 4px;
            border-radius: var(--radius-sm);
        }

        .guidance-map-marker__pin {
            display: block;
            width: 46px;
            height: auto;
            flex: 0 0 46px;
            filter: drop-shadow(0 2px 4px rgba(44, 36, 22, 0.35));
        }

        .guidance-map-marker__label {
            max-width: 180px;
            margin-top: 1px;
            padding: 3px 6px;
            border-radius: 4px;
            background: rgba(255, 255, 255, 0.92);
            box-shadow: 0 1px 4px rgba(44, 36, 22, 0.28);
            color: #242424;
            font-family: 'Lato', sans-serif;
            font-size: 13px;
            font-weight: 700;
            line-height: 1.2;
            white-space: normal;
        }

        .guidance-map-status {
            display: none;
            margin: 0;
            padding: 0.9rem 1.2rem;
            color: var(--primary-dark);
            background: rgba(196, 168, 64, 0.12);
            font-size: var(--text-base);
        }

        .guidance-map-status:not(:empty) { display: block; }

        .guidance-location-card {
            padding: 2.25rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .guidance-location-card[hidden] { display: none; }

        .guidance-location-card:focus { outline: none; }

        .guidance-location-card h2 {
            margin: 0 0 1.25rem;
            font-family: 'IM Fell English', serif;
            font-size: var(--text-3xl);
            line-height: 1.2;
        }

        .guidance-address,
        .guidance-details {
            display: flex;
            gap: 0.75rem;
            align-items: flex-start;
            color: var(--muted);
            line-height: 1.7;
        }

        .guidance-address i,
        .guidance-details > i { margin-top: 0.2rem; color: var(--primary); }
        .guidance-details { padding-top: 1.25rem; border-top: 1px solid rgba(123, 107, 53, 0.18); }
        .guidance-details p { margin: 0; white-space: pre-line; }
        .guidance-details[hidden] { display: none; }

        @media (max-width: 767.98px) {
            .guidance-center-hero { padding: 48px 0 40px; }
            .guidance-center-section { padding: 48px 0; }
            .guidance-center-grid { grid-template-columns: 1fr; gap: 1.25rem; }
            .guidance-map { min-height: 360px; }
            .guidance-location-card { padding: 1.5rem; }
        }
    </style>

    <script src="https://unpkg.com/maplibre-gl@5.12.0/dist/maplibre-gl.js"></script>
    <script>
        (() => {
            const locations = @json($locations);
            const styleUrl = @json($map['styleUrl']);
            const pinImageUrl = @json(asset('images/Werta_pin.png'));
            const status = document.getElementById('guidance-map-status');
            const detailsPanel = document.getElementById('guidance-location-details');
            const detailsName = document.getElementById('guidance-location-name');
            const detailsAddress = document.getElementById('guidance-location-address');
            const detailsExtra = document.getElementById('guidance-location-extra');
            const detailsAdditional = document.getElementById('guidance-location-additional-details');
            const markerElements = [];
            let activeLocationIndex = null;
            const hasCoordinates = (location) => Number.isFinite(location.latitude)
                && Number.isFinite(location.longitude)
                && location.latitude >= -90 && location.latitude <= 90
                && location.longitude >= -180 && location.longitude <= 180;
            const firstLocated = locations.find(hasCoordinates);
            const center = firstLocated
                ? [firstLocated.longitude, firstLocated.latitude]
                : [@json($map['defaultLongitude']), @json($map['defaultLatitude'])];

            try {
                const guidanceMap = new maplibregl.Map({
                    container: 'guidance-center-map',
                    style: styleUrl,
                    center,
                    zoom: firstLocated ? 11 : 5,
                    cooperativeGestures: true,
                    attributionControl: true,
                });

                if ('geolocation' in navigator) {
                    navigator.geolocation.getCurrentPosition(
                        ({ coords }) => {
                            guidanceMap.easeTo({
                                center: [coords.longitude, coords.latitude],
                                zoom: 13,
                                duration: 900,
                            });
                        },
                        () => {
                            // Keep the configured/default center when access is denied or unavailable.
                        },
                        {
                            enableHighAccuracy: true,
                            timeout: 8000,
                            maximumAge: 300000,
                        }
                    );
                }

                guidanceMap.addControl(new maplibregl.NavigationControl({
                    showCompass: false,
                    visualizePitch: false,
                }), 'top-right');

                guidanceMap.on('error', () => {
                    status.textContent = WertaI18n.t('The map could not be loaded. Please try again later.');
                });

                if (!locations.length) return;

                const addLocationMarker = (location, index, coordinates) => {
                    const markerElement = document.createElement('button');
                    markerElement.type = 'button';
                    markerElement.className = 'guidance-map-marker';
                    markerElement.setAttribute('aria-label', `Show details for ${location.name || 'Guidance Center'}`);
                    markerElement.setAttribute('title', location.name || location.address);
                    markerElements[index] = markerElement;

                    if (detailsPanel) {
                        markerElement.setAttribute('aria-controls', 'guidance-location-details');
                        markerElement.setAttribute('aria-expanded', 'false');
                        markerElement.addEventListener('click', () => {
                            const willOpen = detailsPanel.hidden || activeLocationIndex !== index;

                            markerElements.forEach((element) => {
                                element?.setAttribute('aria-expanded', 'false');
                            });

                            if (!willOpen) {
                                detailsPanel.hidden = true;
                                activeLocationIndex = null;
                                markerElement.focus({ preventScroll: true });
                                return;
                            }

                            detailsName.textContent = location.name || WertaI18n.t('Guidance Center');
                            detailsAddress.textContent = location.address;
                            detailsAdditional.textContent = location.additionalDetails;
                            detailsExtra.hidden = !location.additionalDetails;
                            detailsPanel.hidden = false;
                            activeLocationIndex = index;
                            markerElement.setAttribute('aria-expanded', 'true');
                            detailsPanel.focus({ preventScroll: true });
                            detailsPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        });
                    }

                    const pinElement = document.createElement('img');
                    pinElement.className = 'guidance-map-marker__pin';
                    pinElement.src = pinImageUrl;
                    pinElement.alt = '';
                    pinElement.setAttribute('aria-hidden', 'true');
                    markerElement.appendChild(pinElement);

                    if (location.name) {
                        const labelElement = document.createElement('span');
                        labelElement.className = 'guidance-map-marker__label';
                        labelElement.textContent = location.name;
                        markerElement.appendChild(labelElement);
                    }

                    new maplibregl.Marker({
                        element: markerElement,
                        anchor: 'bottom-left',
                        offset: [-23, 0],
                    })
                        .setLngLat([coordinates.longitude, coordinates.latitude])
                        .addTo(guidanceMap);
                };

                let failedLocations = 0;
                const pendingLocations = locations.map(async (location, index) => {
                    if (hasCoordinates(location)) {
                        addLocationMarker(location, index, location);
                        return;
                    }

                    try {
                        const response = await fetch(location.coordinateUrl, {
                            headers: { Accept: 'application/json' },
                        });
                        if (!response.ok) throw new Error('Location lookup failed.');
                        addLocationMarker(location, index, await response.json());
                    } catch (error) {
                        failedLocations += 1;
                    }
                });

                Promise.all(pendingLocations).then(() => {
                    status.textContent = failedLocations
                        ? WertaI18n.t(':count configured locations could not be located on the map.', { count: failedLocations })
                        : '';
                });
            } catch (error) {
                status.textContent = WertaI18n.t('The map could not be loaded. Please try again later.');
            }
        })();
    </script>
@endsection

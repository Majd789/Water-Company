<style>
    .stat-box2 {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        width: 100% !important;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .stat-box1 {
        background: #ffffff;
        padding: 15px;
        border-radius: 10px;
        text-align: center;
        margin: 0 10px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .stats-container {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
    }
</style>

@extends('layouts.app')

@section('content')
    <div class="recent-orders text-center">
        <h2 class="mb-4">خريطة تصنيف متقدم والبحث حسب المحطة</h2>

        <div class="stats-container">
            <div class="stat-box1" style="width: 200px;">
                <h5>🚰 إجمالي الآبار</h5>
                <p id="totalWells" class="fs-4 fw-bold mb-0 text-primary">0</p>
            </div>
            <div class="stat-box1" style="width: 200px;">
                <h5>🏭 إجمالي المحطات</h5>
                <p id="totalStations" class="fs-4 fw-bold mb-0 text-success">0</p>
            </div>
        </div>

        <div class="stat-box2 d-flex justify-content-center mt-4">
            <div class="input-group shadow-sm" style="max-width: 400px; border-radius: 50px; overflow: hidden;">
                <span class="input-group-text bg-white border-end-0 rounded-start-pill" style="border: none;">
                    <i class="bi bi-search text-secondary"></i>
                </span>
                <input type="text" id="searchBox" class="form-control border-start-0 rounded-end-pill"
                    placeholder="ابحث باسم المحطة تقريباً..." style="border: none; box-shadow: none;">
            </div>
        </div>
    </div>

    <div id="map" style="width: 100%; height: 80vh;" class="mt-4"></div>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster/dist/MarkerCluster.Default.css" />

    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>

    <script>
        const map = L.map('map');
        map.fitBounds([
            [32.0, 35.5],
            [37.5, 42.0]
        ]);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const categories = [{
                name: 'محطات',
                keywords: ['محطة', 'محظة', 'مطة']
            },
            {
                name: 'آبار',
                keywords: ['بئر', 'بار']
            },
            {
                name: 'مناهل',
                keywords: ['منهل']
            },
            {
                name: 'خزانات',
                keywords: ['خزان', 'خزانات']
            },
            {
                name: 'شبكة',
                keywords: ['شبكة']
            },
            {
                name: 'مطار',
                keywords: ['مطار']
            },
            {
                name: 'جامعة',
                keywords: ['جامعة']
            }
        ];
        const townCategory = 'مدن/بلدات';

        const layers = {};
        categories.forEach(cat => layers[cat.name] = L.markerClusterGroup().addTo(map));
        layers[townCategory] = L.markerClusterGroup().addTo(map);

        const overlays = {};
        Object.keys(layers).forEach(key => overlays[key] = layers[key]);
        L.control.layers(null, overlays, {
            collapsed: false
        }).addTo(map);

        const allMarkers = [];
        let bounds = L.latLngBounds();
        let wellCount = 0;
        let stationCount = 0;

        fetch('{{ asset('kml/stations.kml') }}')
            .then(res => res.ok ? res.text() : Promise.reject(res.status))
            .then(kmlText => {
                const parser = new DOMParser();
                const kmlDoc = parser.parseFromString(kmlText, 'application/xml');
                const placemarks = Array.from(kmlDoc.getElementsByTagName('Placemark'));

                placemarks.forEach(pm => {
                    const title = pm.getElementsByTagName('name')[0]?.textContent.trim() || '';
                    const extData = {};
                    Array.from(pm.getElementsByTagName('Data')).forEach(d => {
                        extData[d.getAttribute('name')] = d.getElementsByTagName('value')[0]
                            ?.textContent.trim() || '';
                    });
                    const townName = extData.town || extData.بلدة || '';
                    const lowerTitle = title.toLowerCase();
                    let catName = townCategory;

                    categories.forEach(cat => {
                        cat.keywords.forEach(kw => {
                            if (lowerTitle.includes(kw)) catName = cat.name;
                        });
                    });

                    const point = pm.getElementsByTagName('Point')[0];
                    if (!point) return;
                    const [lng, lat] = point.getElementsByTagName('coordinates')[0].textContent.trim().split(
                        ',').map(parseFloat);
                    if (isNaN(lat) || isNaN(lng)) return;

                    const marker = L.marker([lat, lng]);
                    marker.options.category = catName;
                    marker.options.title = title;
                    marker.options.town = townName;

                    marker.bindPopup(`
                        <div class="card shadow-sm p-2" style="min-width: 220px;">
                            <div class="fw-bold fs-6 mb-1">${title}</div>
                            <div class="text-muted mb-1">${townName}</div>
                            <div class="small text-secondary">📍 ${lat.toFixed(6)}, ${lng.toFixed(6)}</div>
                            <button class="btn btn-sm btn-outline-primary mt-2 w-100" onclick="copyCoords('${lat.toFixed(6)}','${lng.toFixed(6)}')">
                                📋 نسخ الإحداثيات
                            </button>
                        </div>
                    `);

                    if (catName === 'محطات') stationCount++;
                    if (catName === 'آبار') wellCount++;

                    allMarkers.push(marker);
                    bounds.extend([lat, lng]);
                });

                allMarkers.forEach(m => layers[m.options.category].addLayer(m));
                if (bounds.isValid()) map.fitBounds(bounds);

                document.getElementById('totalStations').innerText = stationCount;
                document.getElementById('totalWells').innerText = wellCount;

                document.getElementById('searchBox').addEventListener('input', e => {
                    const q = e.target.value.trim().toLowerCase();

                    if (!q) {
                        // إذا لم يكن هناك نص في البحث، اعرض كل العلامات كما هي
                        Object.values(layers).forEach(lg => lg.clearLayers());
                        allMarkers.forEach(m => layers[m.options.category].addLayer(m));
                        return;
                    }

                    // إذا كان هناك نص بحث، قم بالتصفية
                    Object.values(layers).forEach(lg => lg.clearLayers());
                    allMarkers.forEach(m => {
                        if (m.options.title.toLowerCase().includes(q)) {
                            layers[m.options.category].addLayer(m);
                        }
                    });
                });

            })
            .catch(err => console.error('Error loading KML:', err));

        function copyCoords(lat, lng) {
            navigator.clipboard.writeText(`Latitude: ${lat}, Longitude: ${lng}`)
                .then(() => alert('✅ تم نسخ الإحداثيات'))
                .catch(() => alert('⚠️ فشل نسخ الإحداثيات'));
        }
    </script>
@endsection

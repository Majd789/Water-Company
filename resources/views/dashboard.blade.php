{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout> --}}

@extends('layouts.app')

@section('content')
    <div class="map-dashboard-section">
        <h2 class="section-title" style="text-align: center">📊 خريطة محطات وآبار المياه في ادلب</h2>

        <div class="toggle-map-data mb-3">
            <div class="toggle-switch">
                <input type="checkbox" id="showStationsToggle" class="toggle-checkbox" checked>
                <label for="showStationsToggle" class="toggle-label">
                    <span class="toggle-inner"></span>
                    <span class="toggle-switch-handle"></span>
                </label>
                <span class="toggle-text">عرض المحطات</span>
            </div>

            <div class="toggle-switch">
                <input type="checkbox" id="showWellsToggle" class="toggle-checkbox" checked>
                <label for="showWellsToggle" class="toggle-label">
                    <span class="toggle-inner"></span>
                    <span class="toggle-switch-handle"></span>
                </label>
                <span class="toggle-text">عرض الآبار</span>
            </div>
        </div>

        {{-- Add this CSS within your @push('styles') section --}}
        <style>
            .toggle-map-data {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
                /* Adjusted gap slightly for smaller size */
                margin-bottom: 20px;
                align-items: center;
                padding: 10px;
                background-color: #f8f9fa;
                border-radius: 8px;
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
            }

            /* Styles for the custom toggle switch */
            .toggle-switch {
                position: relative;
                display: flex;
                align-items: center;
                cursor: pointer;
                user-select: none;
                -webkit-tap-highlight-color: transparent;
            }

            .toggle-checkbox {
                display: none;
            }

            .toggle-label {
                position: relative;
                display: block;
                width: 30px;
                /* Half of 60px */
                height: 17px;
                /* Half of 34px */
                background-color: #ccc;
                border-radius: 17px;
                /* Half of 34px */
                transition: background-color 0.4s ease;
                margin-right: 8px;
                /* Adjusted margin */
                flex-shrink: 0;
                box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.1);
            }

            /* Change background when checked (on state) */
            .toggle-checkbox:checked+.toggle-label {
                background-color: #007bff;
            }

            /* Inner circle (handle) of the switch */
            .toggle-switch-handle {
                position: absolute;
                top: 2px;
                /* Adjusted top to fit smaller height */
                right: 2px;
                /* Adjusted right to fit smaller width */
                width: 13px;
                /* Half of 26px */
                height: 13px;
                /* Half of 26px */
                background-color: white;
                border-radius: 50%;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
                /* Adjusted shadow */
                transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            }

            /* Move the handle to the left when checked for RTL */
            .toggle-checkbox:checked+.toggle-label .toggle-switch-handle {
                transform: translateX(-13px);
                /* Half of -26px */
            }

            .toggle-text {
                font-weight: bold;
                color: #363949;
                white-space: nowrap;
                text-align: right;
                font-size: 0.9rem;
                /* Slightly smaller text for better fit */
            }
        </style>
        <div class="filter-container">
            <select id="governorateFilter" class="app-filter-select">
                <option value="">🔍 المحافظة</option>
                @foreach ($governorates as $governorate)
                    <option value="{{ $governorate->id }}" data-lat="{{ $governorate->latitude }}"
                        data-lng="{{ $governorate->longitude }}" @if ($governorate->id == 1) selected @endif>
                        {{ $governorate->name }}
                    </option>
                @endforeach
            </select>

            <select id="unitFilter" class="app-filter-select">
                <option value="">🔍 الوحدة</option>
                @foreach ($units as $unit)
                    <option value="{{ $unit->id }}">{{ $unit->unit_name }}</option>
                @endforeach
            </select>

            <select id="statusFilter" class="app-filter-select">
                <option value="">🔍 حالة المحطة</option>
                <option value="عاملة">✅ عاملة</option>
                <option value="متوقفة">⛔ متوقفة</option>
                <option value="خارج الخدمة">❌ خارج الخدمة</option>
            </select>

            {{-- New Well Status Filter --}}
            <select id="wellStatusFilter" class="app-filter-select">
                <option value="">🔍 حالة البئر</option>
                <option value="يعمل">✅ عامل</option>
                <option value="متوقف">⛔ متوقف</option>

            </select>

            <select id="operatorFilter" class="app-filter-select">
                <option value="">🔍 الجهة المشغلة</option>
                <option value="تشغيل تشاركي">🤝 تشغيل تشاركي</option>
                <option value="المؤسسة العامة لمياه الشرب">💧 المؤسسة العامة لمياه الشرب</option>
            </select>

            <select id="energyFilter" class="app-filter-select">
                <option value="">🔍 مصدر الطاقة</option>
                <option value="كهرباء">⚡️ كهرباء</option>
                <option value="مولدة">⚙️ مولدة</option>
                <option value="طاقة شمسية">☀️ طاقة شمسية</option>
                <option value="كهرباء ومولدة">⚡️⚙️ كهرباء ومولدة</option>
                <option value="كهرباء وطاقة شمسية">⚡️☀️ كهرباء وطاقة شمسية</option>
                <option value="طاقة شمسية ومولدة">☀️⚙️ طاقة شمسية ومولدة</option>
                <option value="كهرباء ومولدة وطاقة شمسية">⚡️⚙️☀️ كهرباء ومولدة وطاقة شمسية</option>
            </select>


        </div>



        <div style="margin-bottom: 10px" class="stats-container">
            <div class="stat-box1">
                <h4>📌 إجمالي المحطات</h4>
                <p id="totalStations" class="stat-value">0</p>
            </div>
            <div class="stat-box1">
                <h4>✅ المحطات العاملة</h4>
                <p id="workingStations" class="stat-value">0</p>
            </div>
            <div class="stat-box1">
                <h4>⛔ المحطات المتوقفة</h4>
                <p id="stoppedStations" class="stat-value">0</p>
            </div>
            {{-- New KPI for Wells --}}
            <div class="stat-box1">
                <h4>💧 إجمالي الآبار</h4>
                <p id="totalWells" class="stat-value">0</p>
            </div>
            <div class="stat-box1">
                <h4>✅ الآبار العاملة</h4>
                <p id="workingWells" class="stat-value">0</p>
            </div>
            <div class="stat-box1">
                <h4>⛔ الآبار المتوقفة</h4>
                <p id="stoppedWells" class="stat-value">0</p>
            </div>
        </div>

        <div id="map" class="map-container" style="z-index: 1"></div>
    </div>



    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster/dist/leaflet.markercluster.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const map = L.map('map').setView([35.0, 38.5], 7);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);

            const stationMarkers = L.markerClusterGroup();
            const wellMarkers = L.markerClusterGroup();

            const allStations = @json($stations);
            const allWells = @json($wells);

            // Custom icons for stations and wells
            const stationIcon = L.icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });

            const wellIcon = L.icon({
                iconUrl: 'https://cdn.rawgit.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
                shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/0.7.7/images/marker-shadow.png',
                iconSize: [25, 41],
                iconAnchor: [12, 41],
                popupAnchor: [1, -34],
                shadowSize: [41, 41]
            });


            function updateMapMarkers(filteredStations, filteredWells) {
                stationMarkers.clearLayers();
                wellMarkers.clearLayers();

                const showStations = document.getElementById('showStationsToggle').checked;
                const showWells = document.getElementById('showWellsToggle').checked;

                let validMarkersExist = false;
                const bounds = new L.LatLngBounds();

                if (showStations) {
                    filteredStations.forEach(station => {
                        if (station.latitude && station.longitude) {
                            const popupContent = `
                                    <div class="station-popup">
                                        <b>${station.station_name || 'اسم المحطة غير معروف'}</b><br>
                                        <p>المحافظة: ${station.town?.unit?.governorate?.name || 'غير معروف'}</p>
                                        <p>الوحدة: ${station.town?.unit?.unit_name || 'غير معروف'}</p>
                                        <p>الحالة التشغيلية: <span class="${getOperationalStatusClass(station.operational_status)}">${station.operational_status || 'غير معروف'}</span></p>
                                        <p>مصدر الطاقة: ${station.energy_source || 'غير معروف'}</p>
                                        <a href="/stations/${station.id}" class="view-station-btn">عرض تفاصيل المحطة</a>
                                    </div>
                                `;
                            const marker = L.marker([station.latitude, station.longitude], {
                                    icon: stationIcon
                                })
                                .bindPopup(popupContent);
                            stationMarkers.addLayer(marker);
                            bounds.extend([station.latitude, station.longitude]);
                            validMarkersExist = true;
                        }
                    });
                }

                if (showWells) {
                    filteredWells.forEach(well => {
                        // Wells typically get coordinates from their station, or directly if 'well_location' is parsed
                        // For this example, we assume well.station.latitude and well.station.longitude
                        // If well_location is a string like "LAT,LNG", you'd parse it here
                        const lat = well.station?.latitude;
                        const lng = well.station?.longitude;

                        if (lat && lng) {
                            const popupContent = `
                                    <div class="well-popup">
                                        <b>${well.well_name || 'اسم البئر غير معروف'}</b><br>
                                        <p>المحطة: ${well.station?.station_name || 'غير معروف'}</p>
                                        <p>المحافظة: ${well.station?.town?.unit?.governorate?.name || 'غير معروف'}</p>
                                        <p>الوحدة: ${well.station?.town?.unit?.unit_name || 'غير معروف'}</p>
                                        <p>حالة البئر: <span class="${getWellStatusClass(well.well_status)}">${well.well_status || 'غير معروف'}</span></p>
                                        <p>نوع البئر: ${well.well_type || 'غير معروف'}</p>
                                        <p>مصدر الطاقة: ${well.energy_source || 'غير معروف'}</p>
                                        <a href="/wells/${well.id}" class="view-well-btn">عرض تفاصيل البئر</a>
                                    </div>
                                `;
                            const marker = L.marker([lat, lng], {
                                    icon: wellIcon
                                })
                                .bindPopup(popupContent);
                            wellMarkers.addLayer(marker);
                            bounds.extend([lat, lng]);
                            validMarkersExist = true;
                        }
                    });
                }

                map.addLayer(stationMarkers);
                map.addLayer(wellMarkers);


                if (validMarkersExist) {
                    if (Object.keys(bounds).length > 0 && bounds
                        .isValid()) { // Check if bounds has actually been extended
                        map.fitBounds(bounds, {
                            padding: [50, 50]
                        });
                    }
                } else {
                    map.setView([35.0, 38.5], 7); // Default view if no markers
                }
            }


            function updateStatsCards(filteredStations, filteredWells) {
                document.getElementById("totalStations").textContent = filteredStations.length;
                document.getElementById("workingStations").textContent = filteredStations.filter(s => s
                    .operational_status === "عاملة").length;
                document.getElementById("stoppedStations").textContent = filteredStations.filter(s => s
                    .operational_status === "متوقفة").length;

                document.getElementById("totalWells").textContent = filteredWells.length;
                document.getElementById("workingWells").textContent = filteredWells.filter(w => w.well_status ===
                    "يعمل").length;
                document.getElementById("stoppedWells").textContent = filteredWells.filter(w => w.well_status ===
                    "متوقف").length;
            }

            function getOperationalStatusClass(status) {
                switch (status) {
                    case 'عاملة':
                        return 'status-working';
                    case 'متوقفة':
                        return 'status-stopped';
                    case 'خارج الخدمة':
                        return 'status-out-of-service';
                    default:
                        return '';
                }
            }

            // New function for well status classes
            function getWellStatusClass(status) {
                switch (status) {
                    case 'يعمل':
                        return 'status-working'; // Using existing green for "عامل"
                    case 'متوقف':
                        return 'status-stopped'; // Using existing red for "متوقف"

                    default:
                        return '';
                }
            }


            function applyFilters() {
                const unitId = document.getElementById("unitFilter").value;
                const status = document.getElementById("statusFilter").value; // Station status
                const wellStatus = document.getElementById("wellStatusFilter").value; // Well status
                const operator = document.getElementById("operatorFilter").value;
                const energy = document.getElementById("energyFilter").value;
                const governorateId = document.getElementById("governorateFilter").value;

                const filteredStations = allStations.filter(station => {
                    const matchesUnit = unitId === "" || (station.town?.unit?.id == unitId);
                    const matchesStatus = status === "" || station.operational_status === status;
                    const matchesOperator = operator === "" || station.operator_entity === operator;
                    const matchesEnergy = energy === "" || (station.energy_source && station.energy_source
                        .includes(energy));
                    const matchesGovernorate = governorateId === "" || (station.town?.unit?.governorate
                        ?.id == governorateId);
                    return matchesUnit && matchesStatus && matchesOperator && matchesEnergy &&
                        matchesGovernorate;
                });

                const filteredWells = allWells.filter(well => {
                    const matchesUnit = unitId === "" || (well.station?.town?.unit?.id == unitId);
                    const matchesWellStatus = wellStatus === "" || well.well_status === wellStatus;
                    // Operators and energy source might apply to wells as well, check your data structure
                    const matchesOperator = operator === "" || (well.operator_entity ? well
                        .operator_entity === operator : well.station?.operator_entity === operator);
                    const matchesEnergy = energy === "" || (well.energy_source && well.energy_source
                        .includes(energy));
                    const matchesGovernorate = governorateId === "" || (well.station?.town?.unit
                        ?.governorate?.id == governorateId);
                    return matchesUnit && matchesWellStatus && matchesOperator && matchesEnergy &&
                        matchesGovernorate;
                });


                updateMapMarkers(filteredStations, filteredWells);
                updateStatsCards(filteredStations, filteredWells);
            }

            // Initial load
            applyFilters(); // Call applyFilters to render initial state correctly

            document.querySelectorAll(".app-filter-select").forEach(filterElement => {
                filterElement.addEventListener("change", applyFilters);
            });

            // Toggle visibility of stations/wells
            document.getElementById("showStationsToggle").addEventListener("change", applyFilters);
            document.getElementById("showWellsToggle").addEventListener("change", applyFilters);

            document.getElementById("resetFilters").addEventListener("click", function() {
                document.querySelectorAll(".app-filter-select").forEach(filterElement => filterElement
                    .value = "");
                document.getElementById("showStationsToggle").checked = true;
                document.getElementById("showWellsToggle").checked = true;
                applyFilters();
                map.setView([35.0, 38.5], 7);
            });

            document.getElementById("governorateFilter").addEventListener("change", function() {
                const selectedOption = this.selectedOptions[0];
                const lat = selectedOption.getAttribute("data-lat");
                const lng = selectedOption.getAttribute("data-lng");
                const governorateValue = this.value;

                if (lat && lng) {
                    map.setView([lat, lng], 9);
                } else if (governorateValue === "1") { // Idlib default coordinates
                    map.setView([35.84, 36.64], 9);
                } else {
                    map.setView([35.0, 38.5], 7);
                }
                applyFilters();
            });
        });
    </script>
@endsection

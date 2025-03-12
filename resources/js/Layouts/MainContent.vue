<template>
    <div
        class="px-8 py-6 bg-white/50 dark:bg-black/70 rounded-lg shadow-lg min-h-[40dvw] text-white"
    >
        <div class="mb-2 flex items-center justify-end">
            <PrimaryButton @click="initMap">Refresh</PrimaryButton>
        </div>
        <div id="map" class="w-full px-8 py-10"></div>
    </div>
</template>

<script setup>
import { onMounted } from "vue";
import { useGeolocation } from "@vueuse/core";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { coords, locatedAt, error, resume, pause } = useGeolocation();

let map;
let marker;

var osm = L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "© OpenStreetMap",
});

var osmHOT = L.tileLayer(
    "https://{s}.tile.openstreetmap.fr/hot/{z}/{x}/{y}.png",
    {
        maxZoom: 19,
        attribution:
            "© OpenStreetMap contributors, Tiles style by Humanitarian OpenStreetMap Team hosted by OpenStreetMap France",
    }
);

var Esri_WorldImagery = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    {
        attribution:
            "Tiles &copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community",
    }
);

var baseMaps = {
    OpenStreetMap: osm,
    "OpenStreetMap HOT": osmHOT,
    "Esri World Imagery": Esri_WorldImagery,
};

const defaultCoords = {
    latitude: -8.678046878394552,
    longitude: 115.19820570945741,
};

const initMap = () => {
    if (map) {
        map.remove();
    }

    map = L.map("map", {
        center: [defaultCoords.latitude, defaultCoords.longitude],
        zoom: 18,
    });

    marker = L.marker([defaultCoords.latitude, defaultCoords.longitude])
        .addTo(map)
        .bindPopup("Kamu di sini")
        .openPopup();

    if (
        coords.value.latitude != Infinity &&
        coords.value.longitude != Infinity
    ) {
        map.setView([coords.value.latitude, coords.value.longitude], 18);

        if (marker) {
            map.removeLayer(marker);
        }

        marker = L.marker([coords.value.latitude, coords.value.longitude])
            .addTo(map)
            .bindPopup("Kamu di sini")
            .openPopup();
    }

    osm.addTo(map);

    L.control.layers(baseMaps).addTo(map);

    map.on("click", onClickMap);
};

const onClickMap = (e) => {
    if (marker) {
        map.removeLayer(marker);
    }

    marker = L.marker([e.latlng.lat, e.latlng.lng])
        .addTo(map)
        .bindPopup(
            "Kamu menekan koordinat: " + e.latlng.lat + ", " + e.latlng.lng
        )
        .openPopup();
};

onMounted(() => {
    initMap();
});
</script>

<style>
#map {
    height: 700px;
}
</style>

<template>
    <div
        class="px-8 py-6 bg-white/50 dark:bg-black/70 rounded-lg shadow-lg min-h-[40dvw] text-white"
    >
        <div class="mb-2 flex items-center justify-end gap-2">
            <PrimaryButton @click="logDrawnItemsCoords"
                >Log Coordinates</PrimaryButton
            >
            <PrimaryButton @click="initMap">Reset</PrimaryButton>
        </div>
        <div id="map" class="w-full px-8 py-10"></div>
    </div>
</template>

<script setup>
import { onMounted, watch } from "vue";
import { useGeolocation } from "@vueuse/core";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { coords } = useGeolocation();

let map;
let drawnItems = new L.FeatureGroup();

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
    latitude: -8.79639915819047,
    longitude: 115.17645835876466,
};

const initMap = () => {
    if (map) {
        map.remove();
    }

    map = L.map("map", {
        center: [defaultCoords.latitude, defaultCoords.longitude],
        zoom: 18,
    });

    if (
        coords.value.latitude != Infinity &&
        coords.value.longitude != Infinity
    ) {
        map.setView([coords.value.latitude, coords.value.longitude], 18);
    }

    osm.addTo(map);

    L.control.layers(baseMaps).addTo(map);

    // Add drawing plugin
    drawnItems = new L.FeatureGroup();
    map.addLayer(drawnItems);

    var drawControl = new L.Control.Draw({
        edit: {
            featureGroup: drawnItems,
        },
        draw: {
            polyline: true,
            polygon: true,
            rectangle: true,
            circle: true,
            marker: true,
        },
    });

    map.addControl(drawControl);

    map.on(L.Draw.Event.CREATED, function (event) {
        var layer = event.layer;
        drawnItems.addLayer(layer);
        if (event.layerType === "marker") {
            var popupContent = `Koordinat: ${layer.getLatLng().lat}, ${
                layer.getLatLng().lng
            }`;
            layer.bindPopup(popupContent).openPopup();
        }
    });
};

const logDrawnItemsCoords = () => {
    drawnItems.eachLayer((layer) => {
        if (layer instanceof L.Marker) {
            console.log("Marker:", layer.getLatLng());
        } else if (layer instanceof L.Polyline) {
            console.log("Polyline:", layer.getLatLngs());
        } else if (layer instanceof L.Polygon) {
            console.log("Polygon:", layer.getLatLngs());
        } else if (layer instanceof L.Rectangle) {
            console.log("Rectangle:", layer.getLatLngs());
        } else if (layer instanceof L.Circle) {
            console.log(
                "Circle:",
                layer.getLatLng(),
                "Radius:",
                layer.getRadius()
            );
        } else if (layer instanceof L.CircleMarker) {
            console.log("CircleMarker:", layer.getLatLng());
        }
    });
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
scriptscript

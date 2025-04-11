<template>
    <Head title="Tambah Marker" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-white">
                Tambah Marker
            </h2>
            <p class="text-gray-500">
                (Klik pada peta untuk menambahkan marker)
            </p>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div
                    class="overflow-hidden bg-gray-700 shadow-sm sm:rounded-lg p-6"
                >
                    <div
                        class="mb-2 flex items-center justify-end gap-2 w-full"
                    >
                        <Button
                            size="small"
                            severity="contrast"
                            @click="initMap"
                            icon="pi pi-refresh"
                            label="Refresh"
                        />
                    </div>
                    <div id="map" class="w-full px-8 py-10 mx-auto"></div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>

    <Dialog
        v-model:visible="showDialog"
        modal
        header="Tambah Marker"
        :style="{ width: '30rem' }"
    >
        <div class="w-full mt-4">
            <FloatLabel variant="on" class="w-full">
                <Textarea
                    id="keterangan"
                    v-model="input.keterangan"
                    autocomplete="off"
                    class="w-full"
                    autoResize
                    rows="5"
                    cols="30"
                />
                <label for="keterangan">Keterangan</label>
            </FloatLabel>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <Button
                label="Batal"
                icon="pi pi-times"
                class="p-button-text"
                @click="showDialog = false"
            />
            <Button
                label="Simpan"
                icon="pi pi-check"
                class="p-button-text"
                @click="saveMarker()"
            />
        </div>
    </Dialog>

    <Dialog
        v-model:visible="ondelete.visible"
        modal
        header="Hapus Marker"
        :style="{ width: '30rem' }"
    >
        <div class="w-full">
            <p>Apakah Anda yakin ingin menghapus marker ini?</p>
            <p class="text-sm text-gray-500">
                {{ ondelete.keterangan }}
            </p>
        </div>
        <div class="flex justify-end gap-2 mt-4">
            <Button
                label="Batal"
                icon="pi pi-times"
                class="p-button-text"
                @click="ondelete.visible = false"
            />
            <Button
                label="Hapus"
                icon="pi pi-check"
                class="p-button-text"
                @click="deleteMarker(ondelete.id)"
            />
        </div>
    </Dialog>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { onMounted, watch } from "vue";
import { useGeolocation } from "@vueuse/core";
import { ref } from "vue";
import { useToast } from "primevue/usetoast";

const toast = useToast();

const showDialog = ref(false);
const input = ref({
    latitude: null,
    longitude: null,
    keterangan: "",
});

const openDialog = () => {
    showDialog.value = true;
    input.value.keterangan = "";
};

const saveMarker = async () => {
    try {
        const response = await axios.post("/api/markers", {
            latitude: input.value.latitude,
            longitude: input.value.longitude,
            keterangan: input.value.keterangan,
        });

        toast.add({
            severity: "success",
            summary: "Berhasil",
            detail: "Marker berhasil ditambahkan",
            life: 3000,
        });

        input.value = {
            latitude: null,
            longitude: null,
            keterangan: "",
        };

        // Refresh the map to show the new marker
        initMap();

        showDialog.value = false;
    } catch (error) {
        console.error("Error saving marker:", error);
    }
};

const { coords } = useGeolocation();

let map;

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

    getMarkers();

    let currentMarker = null;

    map.on("click", (e) => {
        input.value.latitude = e.latlng.lat;
        input.value.longitude = e.latlng.lng;

        // Remove the previous marker if it exists
        if (currentMarker) {
            map.removeLayer(currentMarker);
        }

        // Add new marker to the map
        currentMarker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(map);

        openDialog();
    });

    watch(showDialog, (newValue) => {
        if (!newValue && currentMarker) {
            // Remove the marker when the dialog is closed
            map.removeLayer(currentMarker);
            currentMarker = null;
        }
    });

    // Add click event to existing markers
    map.on("layeradd", (event) => {
        if (event.layer instanceof L.Marker) {
            event.layer.on("click", () => {
                const { lat, lng } = event.layer.getLatLng();
                ondelete.value.latitude = lat;
                ondelete.value.longitude = lng;
                ondelete.value.keterangan =
                    event.layer.getPopup()?.getContent() || "";
                ondelete.value.id = event.layer.options.id;
                ondelete.value.visible = true;
            });
        }
    });
};

const getMarkers = async () => {
    try {
        const response = await axios.get("/api/markers");
        const markers = response.data;

        markers.forEach((marker) => {
            L.marker([marker.latitude, marker.longitude], { id: marker.id })
                .addTo(map)
                .bindPopup(marker.keterangan);
        });
    } catch (error) {
        console.error("Error fetching markers:", error);
    }
};

const ondelete = ref({
    latitude: null,
    longitude: null,
    keterangan: "",
    id: null,
    visible: false,
});

const deleteMarker = async (id) => {
    try {
        await axios.delete(`/api/markers/${id}`);
        toast.add({
            severity: "success",
            summary: "Berhasil",
            detail: "Marker berhasil dihapus",
            life: 3000,
        });

        ondelete.value = {
            latitude: null,
            longitude: null,
            keterangan: "",
            id: null,
            visible: false,
        };

        initMap();
    } catch (error) {
        console.error("Error deleting marker:", error);
        toast.add({
            severity: "error",
            summary: "Gagal",
            detail: "Gagal menghapus marker",
            life: 3000,
        });
    }
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

import { ref, computed, onMounted, unref, withCtx, createVNode, createTextVNode, createBlock, createCommentVNode, openBlock, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderAttr, ssrRenderList, ssrIncludeBooleanAttr, ssrLooseContain, ssrLooseEqual } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth, B as BackIcon, L as LocationIcon } from "./AppLayout-WpxQpyk2.js";
import { _ as _sfc_main$2 } from "./Breadcrumb-ZUUo-p_f.js";
import { _ as _sfc_main$3 } from "./Modal-B9Hg0zK1.js";
import { LMap, LTileLayer, LMarker } from "@vue-leaflet/vue-leaflet";
/* empty css                 */
import { u as useForm, h as head_default, l as link_default } from "../ssr.js";
import L from "leaflet";
import "./FlashMessage-CsoanNwB.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
import "@inertiajs/core";
import "flowbite";
import "@vue/server-renderer";
import "lodash-es";
import "flowbite-vue";
import "primevue/config";
import "@primevue/themes/aura";
import "primevue/toastservice";
import "@primevue/themes";
import "vue3-apexcharts";
import "@fullcalendar/vue3";
import "vue-multiselect";
const tileLayerUrl = "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png";
const attribution = '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors';
const _sfc_main = /* @__PURE__ */ Object.assign({
  layout: _sfc_main$1
}, {
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    boardingHouse: Object,
    clusters: Array,
    owners: Array
  },
  setup(__props) {
    delete L.Icon.Default.prototype._getIconUrl;
    L.Icon.Default.mergeOptions({
      iconRetinaUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon-2x.png",
      iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-icon.png",
      shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png"
    });
    const props = __props;
    useAuth();
    const breadcrumbs = [
      { label: "Properti" },
      { label: "Kos", href: route("boarding-houses.index") },
      { label: props.boardingHouse.name, href: route("boarding-houses.show", props.boardingHouse.id) },
      { label: "Edit" }
    ];
    const form = useForm({
      owner_id: props.boardingHouse.owner_id || "",
      cluster_id: props.boardingHouse.cluster_id || "",
      thumbnail: null,
      name: props.boardingHouse.name || "",
      description: props.boardingHouse.description || "",
      address: props.boardingHouse.address || "",
      phone: props.boardingHouse.phone || "",
      latitude: props.boardingHouse.latitude || "",
      longitude: props.boardingHouse.longitude || "",
      status: props.boardingHouse.status || "active"
    });
    const thumbnailPreview = ref(null);
    const currentThumbnail = computed(() => {
      if (thumbnailPreview.value) {
        return thumbnailPreview.value;
      }
      if (props.boardingHouse.thumbnail) {
        return `/storage/${props.boardingHouse.thumbnail}`;
      }
      return null;
    });
    const isMapModalOpen = ref(false);
    const map = ref(null);
    const zoom = ref(13);
    const mapCenter = ref([-6.2088, 106.8456]);
    const markerPosition = ref(null);
    const selectedLocation = ref(null);
    onMounted(() => {
      if (form.latitude && form.longitude) {
        const lat = parseFloat(form.latitude);
        const lng = parseFloat(form.longitude);
        mapCenter.value = [lat, lng];
      } else if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
          (position) => {
            mapCenter.value = [position.coords.latitude, position.coords.longitude];
          },
          () => {
          }
        );
      }
    });
    function closeMapModal() {
      isMapModalOpen.value = false;
    }
    function onMapClick(event) {
      const { lat, lng } = event.latlng;
      markerPosition.value = [lat, lng];
      selectedLocation.value = { lat, lng };
    }
    function updateMarkerPosition(event) {
      const { lat, lng } = event.target.getLatLng();
      markerPosition.value = [lat, lng];
      selectedLocation.value = { lat, lng };
    }
    function confirmLocation() {
      if (selectedLocation.value) {
        form.latitude = selectedLocation.value.lat.toString();
        form.longitude = selectedLocation.value.lng.toString();
        closeMapModal();
      }
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), {
        title: `Edit ${__props.boardingHouse.name}`
      }, null, _parent));
      _push(`<div class="flex flex-col gap-3 px-3 h-full"><div class="flex justify-between items-center h-10">`);
      _push(ssrRenderComponent(_sfc_main$2, { items: breadcrumbs }, null, _parent));
      _push(ssrRenderComponent(unref(link_default), {
        href: _ctx.route("boarding-houses.show", __props.boardingHouse.id),
        class: "flex gap-2 items-center px-3 py-2 text-gray-700 dark:text-gray-300 rounded border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(ssrRenderComponent(BackIcon, { class: "w-4 h-4" }, null, _parent2, _scopeId));
            _push2(`<span class="hidden text-sm md:block"${_scopeId}>Kembali</span>`);
          } else {
            return [
              createVNode(BackIcon, { class: "w-4 h-4" }),
              createVNode("span", { class: "hidden text-sm md:block" }, "Kembali")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div><div class="h-[90%] grid-cols-12 gap-4 md:gap-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"><div class="flex flex-col gap-2 px-8 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-600"><div class="font-bold text-gray-700 md:text-xl dark:text-gray-300"> Edit Kos: ${ssrInterpolate(__props.boardingHouse.name)}</div></div><div class="overflow-auto px-8 pb-8" data-simplebar><form class="max-w-4xl mx-auto py-6 space-y-6"><div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700"> Informasi Dasar </h3><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div class="space-y-2"><label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Nama Kos <span class="text-red-500">*</span></label><input id="name" class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500" type="text"${ssrRenderAttr("value", unref(form).name)} required placeholder="Masukkan nama kos">`);
      if (unref(form).errors.name) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.name)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="space-y-2"><label for="owner_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Pemilik <span class="text-red-500">*</span></label><select id="owner_id" class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"><option selected hidden value=""> Pilih pemilik </option><!--[-->`);
      ssrRenderList(props.owners, (owner) => {
        _push(`<option${ssrRenderAttr("value", owner.id)}${ssrIncludeBooleanAttr(Array.isArray(unref(form).owner_id) ? ssrLooseContain(unref(form).owner_id, owner.id) : ssrLooseEqual(unref(form).owner_id, owner.id)) ? " selected" : ""}>${ssrInterpolate(owner.name)}</option>`);
      });
      _push(`<!--]--></select>`);
      if (unref(form).errors.owner_id) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.owner_id)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="space-y-2 md:col-span-2"><label for="cluster_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Cluster </label><select id="cluster_id" class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"><option value=""${ssrIncludeBooleanAttr(Array.isArray(unref(form).cluster_id) ? ssrLooseContain(unref(form).cluster_id, "") : ssrLooseEqual(unref(form).cluster_id, "")) ? " selected" : ""}>Tidak ada cluster</option><!--[-->`);
      ssrRenderList(props.clusters, (cluster) => {
        _push(`<option${ssrRenderAttr("value", cluster.id)}${ssrIncludeBooleanAttr(Array.isArray(unref(form).cluster_id) ? ssrLooseContain(unref(form).cluster_id, cluster.id) : ssrLooseEqual(unref(form).cluster_id, cluster.id)) ? " selected" : ""}>${ssrInterpolate(cluster.name)}</option>`);
      });
      _push(`<!--]--></select>`);
      if (unref(form).errors.cluster_id) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.cluster_id)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></div><div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700"> Media </h3><div class="space-y-2"><label for="thumbnail" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Thumbnail </label>`);
      if (currentThumbnail.value) {
        _push(`<div class="mb-4"><div class="relative inline-block"><img${ssrRenderAttr("src", currentThumbnail.value)} alt="Current thumbnail" class="w-full max-w-xs h-48 object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600 shadow-sm">`);
        if (thumbnailPreview.value || unref(form).thumbnail) {
          _push(`<button type="button" class="absolute top-2 right-2 px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-md"> Hapus </button>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div>`);
      } else {
        _push(`<!---->`);
      }
      if (thumbnailPreview.value && !currentThumbnail.value) {
        _push(`<div class="mb-4"><div class="relative inline-block"><img${ssrRenderAttr("src", thumbnailPreview.value)} alt="Preview thumbnail" class="w-full max-w-xs h-48 object-cover rounded-lg border-2 border-gray-300 dark:border-gray-600 shadow-sm"><button type="button" class="absolute top-2 right-2 px-3 py-1.5 text-xs font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-colors shadow-md"> Hapus </button></div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<div class="relative"><input id="thumbnail" type="file" accept="image/*" class="block w-full text-sm text-gray-600 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/30 dark:file:text-primary-300 cursor-pointer bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"></div>`);
      if (unref(form).errors.thumbnail) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.thumbnail)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700"> Kontak &amp; Lokasi </h3><div class="space-y-5"><div class="space-y-2"><label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Alamat <span class="text-red-500">*</span></label><input id="address" class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500" type="text"${ssrRenderAttr("value", unref(form).address)} required placeholder="Masukkan alamat lengkap">`);
      if (unref(form).errors.address) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.address)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="grid grid-cols-1 md:grid-cols-2 gap-5"><div class="space-y-2"><label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Telepon </label><input id="phone" class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500" type="text"${ssrRenderAttr("value", unref(form).phone)} placeholder="Masukkan nomor telepon">`);
      if (unref(form).errors.phone) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.phone)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="space-y-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Status <span class="text-red-500">*</span></label><select id="status" class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"><option value="active"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "active") : ssrLooseEqual(unref(form).status, "active")) ? " selected" : ""}>Aktif</option><option value="inactive"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "inactive") : ssrLooseEqual(unref(form).status, "inactive")) ? " selected" : ""}>Tidak Aktif</option><option value="maintenance"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "maintenance") : ssrLooseEqual(unref(form).status, "maintenance")) ? " selected" : ""}>Maintenance</option></select>`);
      if (unref(form).errors.status) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.status)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="space-y-3 pt-2 border-t border-gray-200 dark:border-gray-700"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Lokasi di Peta </label><div class="flex items-start gap-3"><button type="button" class="flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition-colors shadow-sm hover:shadow-md">`);
      _push(ssrRenderComponent(LocationIcon, { class: "w-4 h-4" }, null, _parent));
      _push(` Pilih Lokasi di Peta </button>`);
      if (unref(form).latitude && unref(form).longitude) {
        _push(`<div class="flex items-center gap-2 px-3 py-2 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg"><svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg><span class="text-xs font-medium text-green-700 dark:text-green-400">Lokasi telah dipilih</span></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="grid grid-cols-2 gap-3"><div class="space-y-2"><label for="latitude" class="block text-xs font-medium text-gray-600 dark:text-gray-400"> Latitude </label><input id="latitude" class="w-full px-3 py-2 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500" type="number" step="any"${ssrRenderAttr("value", unref(form).latitude)} placeholder="Latitude" readonly>`);
      if (unref(form).errors.latitude) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.latitude)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="space-y-2"><label for="longitude" class="block text-xs font-medium text-gray-600 dark:text-gray-400"> Longitude </label><input id="longitude" class="w-full px-3 py-2 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500" type="number" step="any"${ssrRenderAttr("value", unref(form).longitude)} placeholder="Longitude" readonly>`);
      if (unref(form).errors.longitude) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.longitude)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></div></div></div><div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl p-6 border border-gray-200 dark:border-gray-700"><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-700"> Deskripsi </h3><div class="space-y-2"><label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300"> Deskripsi Kos </label><textarea id="description" class="w-full px-4 py-2.5 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors placeholder:text-gray-400 dark:placeholder:text-gray-500 resize-none" rows="4" placeholder="Masukkan deskripsi lengkap tentang kos...">${ssrInterpolate(unref(form).description)}</textarea>`);
      if (unref(form).errors.description) {
        _push(`<div class="text-xs text-red-600 dark:text-red-400">${ssrInterpolate(unref(form).errors.description)}</div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="flex flex-col sm:flex-row gap-3 justify-end pt-4 border-t border-gray-200 dark:border-gray-700">`);
      _push(ssrRenderComponent(unref(link_default), {
        href: _ctx.route("boarding-houses.show", __props.boardingHouse.id),
        class: "px-6 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-center"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Batal `);
          } else {
            return [
              createTextVNode(" Batal ")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<button type="submit"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""} class="px-6 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors shadow-sm hover:shadow-md">`);
      if (unref(form).processing) {
        _push(`<span class="flex items-center gap-2"><svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menyimpan... </span>`);
      } else {
        _push(`<span>Simpan Perubahan</span>`);
      }
      _push(`</button></div></form></div>`);
      _push(ssrRenderComponent(_sfc_main$3, {
        show: isMapModalOpen.value,
        title: "Pilih Lokasi di Peta",
        maxWidth: "4xl",
        onClose: closeMapModal,
        onConfirm: confirmLocation,
        confirmText: "Gunakan Lokasi Ini"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-4"${_scopeId}><div class="h-[500px] w-full rounded-lg overflow-hidden border-2 border-gray-300 dark:border-gray-600 shadow-inner"${_scopeId}>`);
            if (isMapModalOpen.value) {
              _push2(ssrRenderComponent(unref(LMap), {
                ref_key: "map",
                ref: map,
                zoom: zoom.value,
                center: mapCenter.value,
                onClick: onMapClick,
                style: { "height": "100%", "width": "100%" }
              }, {
                default: withCtx((_2, _push3, _parent3, _scopeId2) => {
                  if (_push3) {
                    _push3(ssrRenderComponent(unref(LTileLayer), {
                      url: tileLayerUrl,
                      attribution
                    }, null, _parent3, _scopeId2));
                    if (markerPosition.value) {
                      _push3(ssrRenderComponent(unref(LMarker), {
                        "lat-lng": markerPosition.value,
                        draggable: true,
                        "onUpdate:latLng": updateMarkerPosition
                      }, null, _parent3, _scopeId2));
                    } else {
                      _push3(`<!---->`);
                    }
                  } else {
                    return [
                      createVNode(unref(LTileLayer), {
                        url: tileLayerUrl,
                        attribution
                      }),
                      markerPosition.value ? (openBlock(), createBlock(unref(LMarker), {
                        key: 0,
                        "lat-lng": markerPosition.value,
                        draggable: true,
                        "onUpdate:latLng": updateMarkerPosition
                      }, null, 8, ["lat-lng"])) : createCommentVNode("", true)
                    ];
                  }
                }),
                _: 1
              }, _parent2, _scopeId));
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
            if (selectedLocation.value) {
              _push2(`<div class="p-4 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg"${_scopeId}><p class="text-sm font-medium text-primary-900 dark:text-primary-100 mb-2"${_scopeId}> Lokasi yang dipilih: </p><div class="grid grid-cols-2 gap-4 text-sm"${_scopeId}><div${_scopeId}><span class="text-primary-700 dark:text-primary-300 font-medium"${_scopeId}>Latitude:</span><span class="ml-2 text-primary-900 dark:text-primary-100"${_scopeId}>${ssrInterpolate(selectedLocation.value.lat.toFixed(6))}</span></div><div${_scopeId}><span class="text-primary-700 dark:text-primary-300 font-medium"${_scopeId}>Longitude:</span><span class="ml-2 text-primary-900 dark:text-primary-100"${_scopeId}>${ssrInterpolate(selectedLocation.value.lng.toFixed(6))}</span></div></div></div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "space-y-4" }, [
                createVNode("div", { class: "h-[500px] w-full rounded-lg overflow-hidden border-2 border-gray-300 dark:border-gray-600 shadow-inner" }, [
                  isMapModalOpen.value ? (openBlock(), createBlock(unref(LMap), {
                    key: 0,
                    ref_key: "map",
                    ref: map,
                    zoom: zoom.value,
                    center: mapCenter.value,
                    onClick: onMapClick,
                    style: { "height": "100%", "width": "100%" }
                  }, {
                    default: withCtx(() => [
                      createVNode(unref(LTileLayer), {
                        url: tileLayerUrl,
                        attribution
                      }),
                      markerPosition.value ? (openBlock(), createBlock(unref(LMarker), {
                        key: 0,
                        "lat-lng": markerPosition.value,
                        draggable: true,
                        "onUpdate:latLng": updateMarkerPosition
                      }, null, 8, ["lat-lng"])) : createCommentVNode("", true)
                    ]),
                    _: 1
                  }, 8, ["zoom", "center"])) : createCommentVNode("", true)
                ]),
                selectedLocation.value ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "p-4 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg"
                }, [
                  createVNode("p", { class: "text-sm font-medium text-primary-900 dark:text-primary-100 mb-2" }, " Lokasi yang dipilih: "),
                  createVNode("div", { class: "grid grid-cols-2 gap-4 text-sm" }, [
                    createVNode("div", null, [
                      createVNode("span", { class: "text-primary-700 dark:text-primary-300 font-medium" }, "Latitude:"),
                      createVNode("span", { class: "ml-2 text-primary-900 dark:text-primary-100" }, toDisplayString(selectedLocation.value.lat.toFixed(6)), 1)
                    ]),
                    createVNode("div", null, [
                      createVNode("span", { class: "text-primary-700 dark:text-primary-300 font-medium" }, "Longitude:"),
                      createVNode("span", { class: "ml-2 text-primary-900 dark:text-primary-100" }, toDisplayString(selectedLocation.value.lng.toFixed(6)), 1)
                    ])
                  ])
                ])) : createCommentVNode("", true)
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`</div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/BoardingHouses/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

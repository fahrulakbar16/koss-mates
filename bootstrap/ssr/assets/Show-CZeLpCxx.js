import { ref, watch, unref, withCtx, createVNode, withDirectives, createBlock, createCommentVNode, createTextVNode, vModelText, openBlock, toDisplayString, vModelSelect, Fragment, renderList, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderClass, ssrInterpolate, ssrIncludeBooleanAttr, ssrLooseContain, ssrLooseEqual, ssrRenderList } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth, B as BackIcon, H as HomeIcon, E as EditIcon, T as TrashIcon, P as PlusSquareIcon, M as MoneyIcon, a as BedIcon } from "./AppLayout-WpxQpyk2.js";
import { S as SearchIcon, _ as _sfc_main$5 } from "./ConfirmModal-DbAIyvQK.js";
import { _ as _sfc_main$2 } from "./Breadcrumb-ZUUo-p_f.js";
import { _ as _sfc_main$4 } from "./Modal-B9Hg0zK1.js";
import { _ as _sfc_main$3 } from "./Pagination-DHA0kVzb.js";
import { u as useForm, h as head_default, l as link_default } from "../ssr.js";
import { router } from "@inertiajs/core";
import "./FlashMessage-CsoanNwB.js";
import "./_plugin-vue_export-helper-1tPrXgE0.js";
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
const _sfc_main = /* @__PURE__ */ Object.assign({
  layout: _sfc_main$1
}, {
  __name: "Show",
  __ssrInlineRender: true,
  props: {
    boardingHouse: Object,
    rooms: Object,
    search: String,
    status: String
  },
  setup(__props) {
    const props = __props;
    const { can } = useAuth();
    const breadcrumbs = [
      { label: "Properti" },
      { label: "Cluster", path: route("clusters.index") },
      { label: props.boardingHouse?.name || "Detail" }
    ];
    const search = ref(props.search || "");
    const statusFilter = ref(props.status || "");
    let timeout = null;
    watch(search, () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        fetchRooms();
      }, 400);
    });
    watch(statusFilter, () => {
      fetchRooms();
    });
    function fetchRooms() {
      router.get(
        route("boarding-houses.show", props.boardingHouse.id),
        {
          search: search.value,
          status: statusFilter.value
        },
        {
          preserveScroll: true,
          preserveState: true,
          replace: true
        }
      );
    }
    function formatCurrency(value) {
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
      }).format(value);
    }
    const isModalOpen = ref(false);
    const isEditMode = ref(false);
    const form = useForm({
      id: null,
      name: "",
      number: "",
      description: "",
      capacity: 1,
      status: "available",
      facilities: null
    });
    function closeModal() {
      isModalOpen.value = false;
      selectedItem.value = null;
      form.reset();
      form.clearErrors();
    }
    function saveRoom() {
      if (isEditMode.value) {
        if (!can("rooms.edit")) {
          return;
        }
        form.put(route("boarding-houses.rooms.update", [props.boardingHouse.id, form.id]), {
          onSuccess: closeModal
        });
      } else {
        if (!can("rooms.create")) {
          return;
        }
        form.post(route("boarding-houses.rooms.store", props.boardingHouse.id), {
          onSuccess: closeModal
        });
      }
    }
    const selectedItem = ref(null);
    const isConfirmModalOpen = ref(false);
    const closeConfirmModal = () => {
      selectedItem.value = null;
      isConfirmModalOpen.value = false;
    };
    const destroyData = () => {
      if (!can("rooms.delete")) {
        return;
      }
      router.delete(route("boarding-houses.rooms.destroy", [props.boardingHouse.id, selectedItem.value.id]), {
        onSuccess: () => {
          closeConfirmModal();
        },
        preserveScroll: true
      });
    };
    ref(false);
    ref(false);
    useForm({
      id: null,
      owner_id: "",
      thumbnail: null,
      name: "",
      description: "",
      address: "",
      phone: "",
      latitude: "",
      longitude: "",
      status: "active"
    });
    const isConfirmBoardingHouseModalOpen = ref(false);
    const closeConfirmBoardingHouseModal = () => {
      isConfirmBoardingHouseModalOpen.value = false;
    };
    const destroyBoardingHouse = () => {
      if (!can("boarding_houses.delete")) {
        return;
      }
      router.delete(route("boarding-houses.destroy", props.boardingHouse.id), {
        onSuccess: () => {
          router.visit(route("clusters.index"));
        }
      });
    };
    const isPriceModalOpen = ref(false);
    const selectedRoomForPrice = ref(null);
    const priceForm = useForm({
      prices: []
    });
    function closePriceModal() {
      isPriceModalOpen.value = false;
      selectedRoomForPrice.value = null;
      priceForm.prices = [];
      priceForm.clearErrors();
    }
    function addPriceItem() {
      const existingDurations = priceForm.prices.map((p) => p.duration);
      let newDuration = 1;
      while (existingDurations.includes(newDuration)) {
        newDuration++;
      }
      priceForm.prices.push({
        id: null,
        duration: newDuration,
        price: 0
      });
      priceForm.prices.sort((a, b) => a.duration - b.duration);
    }
    function removePriceItem(index) {
      priceForm.prices.splice(index, 1);
    }
    function savePrices() {
      if (!can("rooms.edit") || !selectedRoomForPrice.value) {
        return;
      }
      const validPrices = priceForm.prices.filter((p) => p.duration > 0 && p.price > 0);
      if (validPrices.length === 0) {
        alert("Minimal harus ada satu harga yang diisi");
        return;
      }
      const durations = validPrices.map((p) => p.duration);
      const uniqueDurations = [...new Set(durations)];
      if (durations.length !== uniqueDurations.length) {
        alert("Durasi tidak boleh duplikat");
        return;
      }
      const data = {
        prices: validPrices.map((p) => ({
          id: p.id,
          duration: p.duration,
          price: p.price
        }))
      };
      router.post(
        route("boarding-houses.rooms.prices.store", [props.boardingHouse.id, selectedRoomForPrice.value.id]),
        data,
        {
          onSuccess: () => {
            closePriceModal();
            router.reload({ only: ["rooms"] });
          },
          onError: (errors) => {
            console.error("Error saving prices:", errors);
          }
        }
      );
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), {
        title: `Detail ${__props.boardingHouse.name}`
      }, null, _parent));
      _push(`<div class="flex flex-col gap-3 px-3 h-full"><div class="flex justify-between items-center h-10">`);
      _push(ssrRenderComponent(_sfc_main$2, { items: breadcrumbs }, null, _parent));
      _push(ssrRenderComponent(unref(link_default), {
        href: _ctx.route("clusters.index"),
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
      _push(`</div><div class="h-[90%] grid-cols-12 gap-4 md:gap-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"><div class="px-8 py-6 border-b border-gray-200 dark:border-gray-600"><div class="flex flex-col md:flex-row gap-6"><div class="flex-shrink-0"><div class="relative w-full md:w-64 h-48 rounded-xl overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">`);
      if (__props.boardingHouse.thumbnail) {
        _push(`<img${ssrRenderAttr("src", `/storage/${__props.boardingHouse.thumbnail}`)}${ssrRenderAttr("alt", __props.boardingHouse.name)} class="object-cover w-full h-full">`);
      } else {
        _push(`<div class="flex items-center justify-center w-full h-full text-gray-400 dark:text-gray-500">`);
        _push(ssrRenderComponent(HomeIcon, { class: "w-20 h-20" }, null, _parent));
        _push(`</div>`);
      }
      _push(`<span class="${ssrRenderClass([
        "absolute top-3 right-3 px-3 py-1 text-xs font-semibold rounded-full shadow-sm",
        __props.boardingHouse.status === "active" ? "bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300" : __props.boardingHouse.status === "maintenance" ? "bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300" : "bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300"
      ])}">${ssrInterpolate(__props.boardingHouse.status === "active" ? "Aktif" : __props.boardingHouse.status === "maintenance" ? "Maintenance" : "Tidak Aktif")}</span></div></div><div class="flex-1"><div class="flex items-start justify-between mb-4"><div><h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">${ssrInterpolate(__props.boardingHouse.name)}</h1>`);
      if (__props.boardingHouse.cluster) {
        _push(`<p class="text-sm text-gray-500 dark:text-gray-400 mb-1"> Cluster: ${ssrInterpolate(__props.boardingHouse.cluster.name)}</p>`);
      } else {
        _push(`<!---->`);
      }
      if (__props.boardingHouse.owner) {
        _push(`<p class="text-sm text-gray-500 dark:text-gray-400"> Pemilik: ${ssrInterpolate(__props.boardingHouse.owner.name)}</p>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="flex gap-2">`);
      if (unref(can)("boarding_houses.edit")) {
        _push(`<button class="p-2 text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors" title="Edit Boarding House">`);
        _push(ssrRenderComponent(EditIcon, { class: "w-5 h-5" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      if (unref(can)("boarding_houses.delete")) {
        _push(`<button class="p-2 text-primary-500 hover:text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors" title="Hapus Boarding House">`);
        _push(ssrRenderComponent(TrashIcon, { class: "w-5 h-5" }, null, _parent));
        _push(`</button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="grid grid-cols-1 md:grid-cols-2 gap-4"><div><p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Alamat</p><p class="text-sm text-gray-900 dark:text-white">${ssrInterpolate(__props.boardingHouse.address || "-")}</p></div><div><p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Telepon</p><p class="text-sm text-gray-900 dark:text-white">${ssrInterpolate(__props.boardingHouse.phone || "-")}</p></div>`);
      if (__props.boardingHouse.latitude && __props.boardingHouse.longitude) {
        _push(`<div><p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Koordinat</p><p class="text-sm text-gray-900 dark:text-white">${ssrInterpolate(__props.boardingHouse.latitude)}, ${ssrInterpolate(__props.boardingHouse.longitude)}</p></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      if (__props.boardingHouse.description) {
        _push(`<div class="mt-4"><p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Deskripsi</p><p class="text-sm text-gray-900 dark:text-white">${ssrInterpolate(__props.boardingHouse.description)}</p></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></div><div class="flex flex-col h-full"><div class="flex flex-col gap-2 px-8 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-600"><div class="font-bold text-gray-700 md:text-xl dark:text-gray-300"> Daftar Kamar </div><div class="flex gap-3 items-center"><select class="px-3 h-10 text-sm text-gray-800 bg-transparent rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 focus:border-blue-300 focus:outline-hidden focus:ring-2 focus:ring-blue-500/20"><option value=""${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "") : ssrLooseEqual(statusFilter.value, "")) ? " selected" : ""}>Semua Status</option><option value="available"${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "available") : ssrLooseEqual(statusFilter.value, "available")) ? " selected" : ""}>Tersedia</option><option value="occupied"${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "occupied") : ssrLooseEqual(statusFilter.value, "occupied")) ? " selected" : ""}>Terisi</option><option value="maintenance"${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "maintenance") : ssrLooseEqual(statusFilter.value, "maintenance")) ? " selected" : ""}>Maintenance</option></select><div class="relative py-2"><div class="absolute left-4 top-1/2 -translate-y-1/2">`);
      _push(ssrRenderComponent(SearchIcon, { class: "text-gray-400" }, null, _parent));
      _push(`</div><input${ssrRenderAttr("value", search.value)} type="text" placeholder="Cari kamar" class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-400 dark:focus:border-blue-800 xl:w-[200px]"></div>`);
      if (unref(can)("rooms.create")) {
        _push(`<button type="button" class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary">`);
        _push(ssrRenderComponent(PlusSquareIcon, null, null, _parent));
        _push(`<span class="hidden text-sm md:block">Tambah Kamar</span></button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><div class="overflow-auto px-8 pb-8" data-simplebar>`);
      if (__props.rooms.data && __props.rooms.data.length > 0) {
        _push(`<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 py-6"><!--[-->`);
        ssrRenderList(__props.rooms.data, (room) => {
          _push(`<div class="p-4 rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 hover:shadow-lg transition-all duration-200"><div class="flex items-start justify-between mb-3"><div class="flex-1"><h3 class="font-semibold text-gray-900 dark:text-white mb-1">${ssrInterpolate(room.name)}</h3>`);
          if (room.number) {
            _push(`<p class="text-xs text-gray-500 dark:text-gray-400"> No. ${ssrInterpolate(room.number)}</p>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div><span class="${ssrRenderClass([
            "px-2 py-1 text-xs font-medium rounded-full",
            room.status === "available" ? "bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300" : room.status === "occupied" ? "bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300" : "bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300"
          ])}">${ssrInterpolate(room.status === "available" ? "Tersedia" : room.status === "occupied" ? "Terisi" : "Maintenance")}</span></div><div class="space-y-2 mb-3"><div class="flex items-center justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Harga:</span><span class="font-semibold text-gray-900 dark:text-white">`);
          if (room.min_price) {
            _push(`<span>${ssrInterpolate(formatCurrency(room.min_price))}</span>`);
          } else {
            _push(`<span class="text-gray-400 italic">Belum diatur</span>`);
          }
          _push(`</span></div>`);
          if (room.prices && room.prices.length > 0) {
            _push(`<div class="text-xs text-gray-500 dark:text-gray-400"><!--[-->`);
            ssrRenderList(room.prices.slice(0, 2), (price, idx) => {
              _push(`<span>${ssrInterpolate(price.duration)} bln: ${ssrInterpolate(formatCurrency(price.price))}`);
              if (idx < room.prices.slice(0, 2).length - 1) {
                _push(`<span>, </span>`);
              } else {
                _push(`<!---->`);
              }
              _push(`</span>`);
            });
            _push(`<!--]-->`);
            if (room.prices.length > 2) {
              _push(`<span>...</span>`);
            } else {
              _push(`<!---->`);
            }
            _push(`</div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<div class="flex items-center justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Kapasitas:</span><span class="font-semibold text-gray-900 dark:text-white">${ssrInterpolate(room.capacity)} Orang</span></div>`);
          if (room.transactions_count !== void 0) {
            _push(`<div class="flex items-center justify-between text-sm"><span class="text-gray-500 dark:text-gray-400">Transaksi:</span><span class="font-semibold text-gray-900 dark:text-white">${ssrInterpolate(room.transactions_count)}</span></div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
          if (room.description) {
            _push(`<p class="text-xs text-gray-600 dark:text-gray-300 mb-3 line-clamp-2">${ssrInterpolate(room.description)}</p>`);
          } else {
            _push(`<!---->`);
          }
          if (unref(can)("rooms.edit") || unref(can)("rooms.delete") || unref(can)("rooms.view")) {
            _push(`<div class="flex gap-2 justify-end pt-3 border-t border-gray-200 dark:border-gray-600">`);
            if (unref(can)("rooms.edit")) {
              _push(`<button class="flex items-center gap-1 px-2 py-1 text-xs font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded transition-colors" title="Atur Harga">`);
              _push(ssrRenderComponent(MoneyIcon, { class: "w-4 h-4" }, null, _parent));
              _push(`<span>Harga</span></button>`);
            } else {
              _push(`<!---->`);
            }
            if (unref(can)("rooms.edit")) {
              _push(`<button class="text-yellow-500 hover:text-yellow-600" title="Edit">`);
              _push(ssrRenderComponent(EditIcon, { class: "w-5 h-5" }, null, _parent));
              _push(`</button>`);
            } else {
              _push(`<!---->`);
            }
            if (unref(can)("rooms.delete")) {
              _push(`<button class="text-primary-500 hover:text-primary-600" title="Hapus">`);
              _push(ssrRenderComponent(TrashIcon, { class: "w-5 h-5" }, null, _parent));
              _push(`</button>`);
            } else {
              _push(`<!---->`);
            }
            _push(`</div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<div class="py-12 text-center">`);
        _push(ssrRenderComponent(BedIcon, { class: "w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" }, null, _parent));
        _push(`<p class="text-gray-500 dark:text-gray-400"> Tidak ada kamar ditemukan </p></div>`);
      }
      _push(`</div>`);
      if (__props.rooms.data && __props.rooms.data.length > 0) {
        _push(ssrRenderComponent(_sfc_main$3, { pagination: __props.rooms }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      _push(ssrRenderComponent(_sfc_main$4, {
        show: isModalOpen.value,
        title: isEditMode.value ? `Edit ${selectedItem.value?.name}` : "Tambah Kamar Baru",
        confirmText: "Simpan",
        maxWidth: "lg",
        onClose: closeModal,
        onConfirm: saveRoom
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-3"${_scopeId}><div class="space-y-1 text-sm"${_scopeId}><label for="room_name" class="text-gray-900 dark:text-white"${_scopeId}>Nama Kamar <span class="text-primary-500"${_scopeId}>*</span></label><input id="room_name" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).name)} required placeholder="Masukkan nama kamar"${_scopeId}>`);
            if (unref(form).errors.name) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="room_number" class="text-gray-900 dark:text-white"${_scopeId}>Nomor Kamar</label><input id="room_number" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).number)} placeholder="Masukkan nomor kamar"${_scopeId}>`);
            if (unref(form).errors.number) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.number)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="room_capacity" class="text-gray-900 dark:text-white"${_scopeId}>Kapasitas</label><input id="room_capacity" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="number" min="1"${ssrRenderAttr("value", unref(form).capacity)} placeholder="1"${_scopeId}>`);
            if (unref(form).errors.capacity) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.capacity)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="room_status" class="text-gray-900 dark:text-white"${_scopeId}>Status <span class="text-primary-500"${_scopeId}>*</span></label><select id="room_status" class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"${_scopeId}><option value="available"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "available") : ssrLooseEqual(unref(form).status, "available")) ? " selected" : ""}${_scopeId}>Tersedia</option><option value="occupied"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "occupied") : ssrLooseEqual(unref(form).status, "occupied")) ? " selected" : ""}${_scopeId}>Terisi</option><option value="maintenance"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "maintenance") : ssrLooseEqual(unref(form).status, "maintenance")) ? " selected" : ""}${_scopeId}>Maintenance</option></select>`);
            if (unref(form).errors.status) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.status)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="room_description" class="text-gray-900 dark:text-white"${_scopeId}>Deskripsi</label><textarea id="room_description" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" rows="3" placeholder="Masukkan deskripsi kamar"${_scopeId}>${ssrInterpolate(unref(form).description)}</textarea>`);
            if (unref(form).errors.description) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.description)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "space-y-3" }, [
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "room_name",
                    class: "text-gray-900 dark:text-white"
                  }, [
                    createTextVNode("Nama Kamar "),
                    createVNode("span", { class: "text-primary-500" }, "*")
                  ]),
                  withDirectives(createVNode("input", {
                    id: "room_name",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    required: "",
                    placeholder: "Masukkan nama kamar"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).name]
                  ]),
                  unref(form).errors.name ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.name), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "room_number",
                    class: "text-gray-900 dark:text-white"
                  }, "Nomor Kamar"),
                  withDirectives(createVNode("input", {
                    id: "room_number",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).number = $event,
                    placeholder: "Masukkan nomor kamar"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).number]
                  ]),
                  unref(form).errors.number ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.number), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "room_capacity",
                    class: "text-gray-900 dark:text-white"
                  }, "Kapasitas"),
                  withDirectives(createVNode("input", {
                    id: "room_capacity",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "number",
                    min: "1",
                    "onUpdate:modelValue": ($event) => unref(form).capacity = $event,
                    placeholder: "1"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [
                      vModelText,
                      unref(form).capacity,
                      void 0,
                      { number: true }
                    ]
                  ]),
                  unref(form).errors.capacity ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.capacity), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "room_status",
                    class: "text-gray-900 dark:text-white"
                  }, [
                    createTextVNode("Status "),
                    createVNode("span", { class: "text-primary-500" }, "*")
                  ]),
                  withDirectives(createVNode("select", {
                    id: "room_status",
                    "onUpdate:modelValue": ($event) => unref(form).status = $event,
                    class: "block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  }, [
                    createVNode("option", { value: "available" }, "Tersedia"),
                    createVNode("option", { value: "occupied" }, "Terisi"),
                    createVNode("option", { value: "maintenance" }, "Maintenance")
                  ], 8, ["onUpdate:modelValue"]), [
                    [vModelSelect, unref(form).status]
                  ]),
                  unref(form).errors.status ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.status), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "room_description",
                    class: "text-gray-900 dark:text-white"
                  }, "Deskripsi"),
                  withDirectives(createVNode("textarea", {
                    id: "room_description",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    "onUpdate:modelValue": ($event) => unref(form).description = $event,
                    rows: "3",
                    placeholder: "Masukkan deskripsi kamar"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).description]
                  ]),
                  unref(form).errors.description ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.description), 1)) : createCommentVNode("", true)
                ])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$5, {
        show: isConfirmModalOpen.value,
        question: `Yakin ingin menghapus`,
        selected: `${selectedItem.value?.name}`,
        title: "Hapus Kamar",
        confirmText: "Ya, Hapus!",
        maxWidth: "md",
        onClose: closeConfirmModal,
        onConfirm: destroyData
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$5, {
        show: isConfirmBoardingHouseModalOpen.value,
        question: `Yakin ingin menghapus`,
        selected: `${__props.boardingHouse?.name}`,
        title: "Hapus Boarding House",
        confirmText: "Ya, Hapus!",
        maxWidth: "md",
        onClose: closeConfirmBoardingHouseModal,
        onConfirm: destroyBoardingHouse
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$4, {
        show: isPriceModalOpen.value,
        title: `Atur Harga - ${selectedRoomForPrice.value?.name || ""}`,
        maxWidth: "2xl",
        onClose: closePriceModal,
        onConfirm: savePrices,
        confirmText: "Simpan Harga"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-4"${_scopeId}><p class="text-sm text-gray-600 dark:text-gray-400"${_scopeId}> Atur harga kamar berdasarkan durasi sewa (dalam bulan) </p><div class="space-y-3"${_scopeId}><!--[-->`);
            ssrRenderList(unref(priceForm).prices, (priceItem, index) => {
              _push2(`<div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"${_scopeId}><div class="flex-1 grid grid-cols-2 gap-3"${_scopeId}><div${_scopeId}><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"${_scopeId}> Durasi (Bulan) <span class="text-primary-500"${_scopeId}>*</span></label><input type="number" min="1" step="1"${ssrRenderAttr("value", priceItem.duration)} class="w-full px-3 py-2 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="Durasi" required${_scopeId}></div><div${_scopeId}><label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"${_scopeId}> Harga (Rp) <span class="text-primary-500"${_scopeId}>*</span></label><input type="number" step="0.01" min="0"${ssrRenderAttr("value", priceItem.price)} class="w-full px-3 py-2 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors" placeholder="Masukkan harga" required${_scopeId}></div></div><button type="button" class="p-2 mt-6 text-primary-600 hover:text-primary-700 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors" title="Hapus"${_scopeId}>`);
              _push2(ssrRenderComponent(TrashIcon, { class: "w-4 h-4" }, null, _parent2, _scopeId));
              _push2(`</button></div>`);
            });
            _push2(`<!--]--></div><button type="button" class="w-full px-4 py-2 text-sm font-medium text-primary-600 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors"${_scopeId}> + Tambah Durasi </button>`);
            if (unref(priceForm).errors.prices) {
              _push2(`<div class="text-sm text-primary-600 dark:text-primary-400"${_scopeId}>${ssrInterpolate(unref(priceForm).errors.prices)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "space-y-4" }, [
                createVNode("p", { class: "text-sm text-gray-600 dark:text-gray-400" }, " Atur harga kamar berdasarkan durasi sewa (dalam bulan) "),
                createVNode("div", { class: "space-y-3" }, [
                  (openBlock(true), createBlock(Fragment, null, renderList(unref(priceForm).prices, (priceItem, index) => {
                    return openBlock(), createBlock("div", {
                      key: index,
                      class: "flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700"
                    }, [
                      createVNode("div", { class: "flex-1 grid grid-cols-2 gap-3" }, [
                        createVNode("div", null, [
                          createVNode("label", { class: "block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" }, [
                            createTextVNode(" Durasi (Bulan) "),
                            createVNode("span", { class: "text-primary-500" }, "*")
                          ]),
                          withDirectives(createVNode("input", {
                            type: "number",
                            min: "1",
                            step: "1",
                            "onUpdate:modelValue": ($event) => priceItem.duration = $event,
                            class: "w-full px-3 py-2 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors",
                            placeholder: "Durasi",
                            required: ""
                          }, null, 8, ["onUpdate:modelValue"]), [
                            [
                              vModelText,
                              priceItem.duration,
                              void 0,
                              { number: true }
                            ]
                          ])
                        ]),
                        createVNode("div", null, [
                          createVNode("label", { class: "block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" }, [
                            createTextVNode(" Harga (Rp) "),
                            createVNode("span", { class: "text-primary-500" }, "*")
                          ]),
                          withDirectives(createVNode("input", {
                            type: "number",
                            step: "0.01",
                            min: "0",
                            "onUpdate:modelValue": ($event) => priceItem.price = $event,
                            class: "w-full px-3 py-2 text-sm text-gray-900 dark:text-white bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors",
                            placeholder: "Masukkan harga",
                            required: ""
                          }, null, 8, ["onUpdate:modelValue"]), [
                            [
                              vModelText,
                              priceItem.price,
                              void 0,
                              { number: true }
                            ]
                          ])
                        ])
                      ]),
                      createVNode("button", {
                        type: "button",
                        onClick: ($event) => removePriceItem(index),
                        class: "p-2 mt-6 text-primary-600 hover:text-primary-700 hover:bg-primary-50 dark:hover:bg-primary-900/20 rounded-lg transition-colors",
                        title: "Hapus"
                      }, [
                        createVNode(TrashIcon, { class: "w-4 h-4" })
                      ], 8, ["onClick"])
                    ]);
                  }), 128))
                ]),
                createVNode("button", {
                  type: "button",
                  onClick: addPriceItem,
                  class: "w-full px-4 py-2 text-sm font-medium text-primary-600 bg-primary-50 dark:bg-primary-900/20 border border-primary-200 dark:border-primary-800 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors"
                }, " + Tambah Durasi "),
                unref(priceForm).errors.prices ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "text-sm text-primary-600 dark:text-primary-400"
                }, toDisplayString(unref(priceForm).errors.prices), 1)) : createCommentVNode("", true)
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/BoardingHouses/Show.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

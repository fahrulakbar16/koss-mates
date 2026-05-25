import { ref, watch, unref, withCtx, createVNode, withDirectives, createBlock, createCommentVNode, createTextVNode, vModelText, openBlock, toDisplayString, Fragment, renderList, vModelSelect, useSSRContext } from "vue";
import { ssrRenderComponent, ssrIncludeBooleanAttr, ssrLooseContain, ssrLooseEqual, ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrRenderClass } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth, P as PlusSquareIcon, H as HomeIcon, E as EditIcon, T as TrashIcon } from "./AppLayout-WpxQpyk2.js";
import { S as SearchIcon, _ as _sfc_main$4 } from "./ConfirmModal-DbAIyvQK.js";
import { _ as _sfc_main$2 } from "./Breadcrumb-ZUUo-p_f.js";
import { _ as _sfc_main$3 } from "./Modal-B9Hg0zK1.js";
import { _ as _sfc_main$5 } from "./Pagination-DHA0kVzb.js";
import { u as useForm, h as head_default } from "../ssr.js";
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
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    boardingHouses: Object,
    clusters: Array,
    owners: Array,
    search: String,
    cluster_id: String,
    status: String
  },
  setup(__props) {
    const props = __props;
    const { can } = useAuth();
    const breadcrumbs = [{ label: "Properti" }, { label: "Boarding House" }];
    const search = ref(props.search || "");
    const clusterFilter = ref(props.cluster_id || "");
    const statusFilter = ref(props.status || "");
    let timeout = null;
    watch(search, () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        fetchBoardingHouses();
      }, 400);
    });
    watch(clusterFilter, () => {
      fetchBoardingHouses();
    });
    watch(statusFilter, () => {
      fetchBoardingHouses();
    });
    function fetchBoardingHouses() {
      router.get(
        route("boarding-houses.index"),
        {
          search: search.value,
          cluster_id: clusterFilter.value,
          status: statusFilter.value
        },
        {
          preserveScroll: true,
          preserveState: true,
          replace: true
        }
      );
    }
    const isModalOpen = ref(false);
    const isEditMode = ref(false);
    const form = useForm({
      id: null,
      owner_id: "",
      cluster_id: "",
      thumbnail: null,
      name: "",
      description: "",
      address: "",
      phone: "",
      latitude: "",
      longitude: "",
      status: "active"
    });
    function handleThumbnailChange(event) {
      form.thumbnail = event.target.files[0];
    }
    function closeModal() {
      isModalOpen.value = false;
      selectedItem.value = null;
      form.reset();
      form.clearErrors();
    }
    function saveBoardingHouse() {
      if (isEditMode.value) {
        if (!can("boarding_houses.edit")) {
          return;
        }
        form.put(route("boarding-houses.update", form.id), {
          onSuccess: closeModal,
          forceFormData: true
        });
      } else {
        if (!can("boarding_houses.create")) {
          return;
        }
        form.post(route("boarding-houses.store"), {
          onSuccess: closeModal,
          forceFormData: true
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
      if (!can("boarding_houses.delete")) {
        return;
      }
      router.delete(route("boarding-houses.destroy", selectedItem.value.id), {
        onSuccess: () => {
          closeConfirmModal();
        },
        preserveScroll: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Daftar Boarding House" }, null, _parent));
      _push(`<div class="flex flex-col gap-3 px-3 h-full"><div class="flex justify-between items-center h-10">`);
      _push(ssrRenderComponent(_sfc_main$2, { items: breadcrumbs }, null, _parent));
      if (unref(can)("boarding_houses.create")) {
        _push(`<button type="button" class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary">`);
        _push(ssrRenderComponent(PlusSquareIcon, null, null, _parent));
        _push(`<span class="hidden text-sm md:block">Tambah Boarding House</span></button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="h-[90%] grid-cols-12 gap-4 md:gap-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"><div class="flex flex-col gap-2 px-8 py-1 sm:flex-row sm:items-center sm:justify-between"><div class="font-bold text-gray-700 md:text-xl dark:text-gray-300"> Daftar Boarding House </div><div class="flex gap-3 items-center"><select class="px-3 h-10 text-sm text-gray-800 bg-transparent rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 focus:border-blue-300 focus:outline-hidden focus:ring-2 focus:ring-blue-500/20"><option value=""${ssrIncludeBooleanAttr(Array.isArray(clusterFilter.value) ? ssrLooseContain(clusterFilter.value, "") : ssrLooseEqual(clusterFilter.value, "")) ? " selected" : ""}>Semua Cluster</option><!--[-->`);
      ssrRenderList(props.clusters, (cluster) => {
        _push(`<option${ssrRenderAttr("value", cluster.id)}${ssrIncludeBooleanAttr(Array.isArray(clusterFilter.value) ? ssrLooseContain(clusterFilter.value, cluster.id) : ssrLooseEqual(clusterFilter.value, cluster.id)) ? " selected" : ""}>${ssrInterpolate(cluster.name)}</option>`);
      });
      _push(`<!--]--></select><select class="px-3 h-10 text-sm text-gray-800 bg-transparent rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 focus:border-blue-300 focus:outline-hidden focus:ring-2 focus:ring-blue-500/20"><option value=""${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "") : ssrLooseEqual(statusFilter.value, "")) ? " selected" : ""}>Semua Status</option><option value="active"${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "active") : ssrLooseEqual(statusFilter.value, "active")) ? " selected" : ""}>Aktif</option><option value="inactive"${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "inactive") : ssrLooseEqual(statusFilter.value, "inactive")) ? " selected" : ""}>Tidak Aktif</option><option value="maintenance"${ssrIncludeBooleanAttr(Array.isArray(statusFilter.value) ? ssrLooseContain(statusFilter.value, "maintenance") : ssrLooseEqual(statusFilter.value, "maintenance")) ? " selected" : ""}>Maintenance</option></select><div class="relative py-2"><div class="absolute left-4 top-1/2 -translate-y-1/2">`);
      _push(ssrRenderComponent(SearchIcon, { class: "text-gray-400" }, null, _parent));
      _push(`</div><input${ssrRenderAttr("value", search.value)} type="text" placeholder="Cari boarding house" class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-400 dark:focus:border-blue-800 xl:w-[200px]"></div></div></div><div class="overflow-auto px-8 pb-8" data-simplebar>`);
      if (__props.boardingHouses.data && __props.boardingHouses.data.length > 0) {
        _push(`<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"><!--[-->`);
        ssrRenderList(__props.boardingHouses.data, (boardingHouse) => {
          _push(`<div class="rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-800 overflow-hidden hover:shadow-lg transition-shadow"><div class="relative h-48 bg-gray-200 dark:bg-gray-600">`);
          if (boardingHouse.thumbnail) {
            _push(`<img${ssrRenderAttr("src", `/storage/${boardingHouse.thumbnail}`)}${ssrRenderAttr("alt", boardingHouse.name)} class="object-cover w-full h-full">`);
          } else {
            _push(`<div class="flex items-center justify-center w-full h-full text-gray-400">`);
            _push(ssrRenderComponent(HomeIcon, { class: "w-16 h-16" }, null, _parent));
            _push(`</div>`);
          }
          _push(`<span class="${ssrRenderClass([
            "absolute top-2 right-2 px-2 py-1 text-xs font-medium rounded",
            boardingHouse.status === "active" ? "bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300" : boardingHouse.status === "maintenance" ? "bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300" : "bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300"
          ])}">${ssrInterpolate(boardingHouse.status === "active" ? "Aktif" : boardingHouse.status === "maintenance" ? "Maintenance" : "Tidak Aktif")}</span></div><div class="p-4"><h3 class="font-semibold text-gray-900 dark:text-white mb-1">${ssrInterpolate(boardingHouse.name)}</h3>`);
          if (boardingHouse.cluster) {
            _push(`<p class="text-xs text-gray-500 dark:text-gray-400 mb-2">${ssrInterpolate(boardingHouse.cluster.name)}</p>`);
          } else {
            _push(`<!---->`);
          }
          if (boardingHouse.address) {
            _push(`<p class="text-xs text-gray-600 dark:text-gray-300 mb-2 line-clamp-2">${ssrInterpolate(boardingHouse.address)}</p>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<p class="text-xs text-gray-500 dark:text-gray-400 mb-3">${ssrInterpolate(boardingHouse.rooms_count)} Kamar </p>`);
          if (unref(can)("boarding_houses.edit") || unref(can)("boarding_houses.delete")) {
            _push(`<div class="flex gap-2 justify-end pt-2 border-t border-gray-200 dark:border-gray-600">`);
            if (unref(can)("boarding_houses.edit")) {
              _push(`<button class="text-yellow-500 hover:text-yellow-600" title="Edit">`);
              _push(ssrRenderComponent(EditIcon, null, null, _parent));
              _push(`</button>`);
            } else {
              _push(`<!---->`);
            }
            if (unref(can)("boarding_houses.delete")) {
              _push(`<button class="text-red-500 hover:text-red-600" title="Hapus">`);
              _push(ssrRenderComponent(TrashIcon, null, null, _parent));
              _push(`</button>`);
            } else {
              _push(`<!---->`);
            }
            _push(`</div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div></div>`);
        });
        _push(`<!--]--></div>`);
      } else {
        _push(`<div class="py-12 text-center"><p class="text-gray-500 dark:text-gray-400"> Tidak ada boarding house ditemukan </p></div>`);
      }
      _push(`</div>`);
      _push(ssrRenderComponent(_sfc_main$3, {
        show: isModalOpen.value,
        title: isEditMode.value ? `Edit ${selectedItem.value?.name}` : "Tambah Boarding House Baru",
        confirmText: "Simpan",
        maxWidth: "lg",
        onClose: closeModal,
        onConfirm: saveBoardingHouse
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-3"${_scopeId}><div class="space-y-1 text-sm"${_scopeId}><label for="name" class="text-gray-900 dark:text-white"${_scopeId}>Nama Boarding House <span class="text-red-500"${_scopeId}>*</span></label><input id="name" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).name)} required placeholder="Masukkan nama boarding house"${_scopeId}>`);
            if (unref(form).errors.name) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="owner_id" class="text-gray-900 dark:text-white"${_scopeId}>Pemilik <span class="text-red-500"${_scopeId}>*</span></label><select id="owner_id" class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"${_scopeId}><option selected hidden value=""${_scopeId}> Pilih pemilik </option><!--[-->`);
            ssrRenderList(props.owners, (owner) => {
              _push2(`<option${ssrRenderAttr("value", owner.id)}${ssrIncludeBooleanAttr(Array.isArray(unref(form).owner_id) ? ssrLooseContain(unref(form).owner_id, owner.id) : ssrLooseEqual(unref(form).owner_id, owner.id)) ? " selected" : ""}${_scopeId}>${ssrInterpolate(owner.name)}</option>`);
            });
            _push2(`<!--]--></select>`);
            if (unref(form).errors.owner_id) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.owner_id)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="cluster_id" class="text-gray-900 dark:text-white"${_scopeId}>Cluster</label><select id="cluster_id" class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"${_scopeId}><option value=""${ssrIncludeBooleanAttr(Array.isArray(unref(form).cluster_id) ? ssrLooseContain(unref(form).cluster_id, "") : ssrLooseEqual(unref(form).cluster_id, "")) ? " selected" : ""}${_scopeId}>Tidak ada cluster</option><!--[-->`);
            ssrRenderList(props.clusters, (cluster) => {
              _push2(`<option${ssrRenderAttr("value", cluster.id)}${ssrIncludeBooleanAttr(Array.isArray(unref(form).cluster_id) ? ssrLooseContain(unref(form).cluster_id, cluster.id) : ssrLooseEqual(unref(form).cluster_id, cluster.id)) ? " selected" : ""}${_scopeId}>${ssrInterpolate(cluster.name)}</option>`);
            });
            _push2(`<!--]--></select>`);
            if (unref(form).errors.cluster_id) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.cluster_id)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="thumbnail" class="text-gray-900 dark:text-white"${_scopeId}>Thumbnail</label><input id="thumbnail" type="file" accept="image/*" class="block w-full text-sm text-gray-600 border border-gray-400 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"${_scopeId}>`);
            if (unref(form).errors.thumbnail) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.thumbnail)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="address" class="text-gray-900 dark:text-white"${_scopeId}>Alamat <span class="text-red-500"${_scopeId}>*</span></label><input id="address" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).address)} required placeholder="Masukkan alamat"${_scopeId}>`);
            if (unref(form).errors.address) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.address)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="phone" class="text-gray-900 dark:text-white"${_scopeId}>Telepon</label><input id="phone" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).phone)} placeholder="Masukkan nomor telepon"${_scopeId}>`);
            if (unref(form).errors.phone) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.phone)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="grid grid-cols-2 gap-3"${_scopeId}><div class="space-y-1 text-sm"${_scopeId}><label for="latitude" class="text-gray-900 dark:text-white"${_scopeId}>Latitude</label><input id="latitude" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="number" step="any"${ssrRenderAttr("value", unref(form).latitude)} placeholder="Latitude"${_scopeId}>`);
            if (unref(form).errors.latitude) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.latitude)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="longitude" class="text-gray-900 dark:text-white"${_scopeId}>Longitude</label><input id="longitude" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="number" step="any"${ssrRenderAttr("value", unref(form).longitude)} placeholder="Longitude"${_scopeId}>`);
            if (unref(form).errors.longitude) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.longitude)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div></div><div class="space-y-1 text-sm"${_scopeId}><label for="description" class="text-gray-900 dark:text-white"${_scopeId}>Deskripsi</label><textarea id="description" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" rows="3" placeholder="Masukkan deskripsi"${_scopeId}>${ssrInterpolate(unref(form).description)}</textarea>`);
            if (unref(form).errors.description) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.description)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="status" class="text-gray-900 dark:text-white"${_scopeId}>Status <span class="text-red-500"${_scopeId}>*</span></label><select id="status" class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"${_scopeId}><option value="active"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "active") : ssrLooseEqual(unref(form).status, "active")) ? " selected" : ""}${_scopeId}>Aktif</option><option value="inactive"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "inactive") : ssrLooseEqual(unref(form).status, "inactive")) ? " selected" : ""}${_scopeId}>Tidak Aktif</option><option value="maintenance"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "maintenance") : ssrLooseEqual(unref(form).status, "maintenance")) ? " selected" : ""}${_scopeId}>Maintenance</option></select>`);
            if (unref(form).errors.status) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.status)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div></div>`);
          } else {
            return [
              createVNode("div", { class: "space-y-3" }, [
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "name",
                    class: "text-gray-900 dark:text-white"
                  }, [
                    createTextVNode("Nama Boarding House "),
                    createVNode("span", { class: "text-red-500" }, "*")
                  ]),
                  withDirectives(createVNode("input", {
                    id: "name",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    required: "",
                    placeholder: "Masukkan nama boarding house"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).name]
                  ]),
                  unref(form).errors.name ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.name), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "owner_id",
                    class: "text-gray-900 dark:text-white"
                  }, [
                    createTextVNode("Pemilik "),
                    createVNode("span", { class: "text-red-500" }, "*")
                  ]),
                  withDirectives(createVNode("select", {
                    id: "owner_id",
                    "onUpdate:modelValue": ($event) => unref(form).owner_id = $event,
                    class: "block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  }, [
                    createVNode("option", {
                      selected: "",
                      hidden: "",
                      value: ""
                    }, " Pilih pemilik "),
                    (openBlock(true), createBlock(Fragment, null, renderList(props.owners, (owner) => {
                      return openBlock(), createBlock("option", {
                        key: owner.id,
                        value: owner.id
                      }, toDisplayString(owner.name), 9, ["value"]);
                    }), 128))
                  ], 8, ["onUpdate:modelValue"]), [
                    [vModelSelect, unref(form).owner_id]
                  ]),
                  unref(form).errors.owner_id ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.owner_id), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "cluster_id",
                    class: "text-gray-900 dark:text-white"
                  }, "Cluster"),
                  withDirectives(createVNode("select", {
                    id: "cluster_id",
                    "onUpdate:modelValue": ($event) => unref(form).cluster_id = $event,
                    class: "block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  }, [
                    createVNode("option", { value: "" }, "Tidak ada cluster"),
                    (openBlock(true), createBlock(Fragment, null, renderList(props.clusters, (cluster) => {
                      return openBlock(), createBlock("option", {
                        key: cluster.id,
                        value: cluster.id
                      }, toDisplayString(cluster.name), 9, ["value"]);
                    }), 128))
                  ], 8, ["onUpdate:modelValue"]), [
                    [vModelSelect, unref(form).cluster_id]
                  ]),
                  unref(form).errors.cluster_id ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.cluster_id), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "thumbnail",
                    class: "text-gray-900 dark:text-white"
                  }, "Thumbnail"),
                  createVNode("input", {
                    id: "thumbnail",
                    type: "file",
                    accept: "image/*",
                    onChange: handleThumbnailChange,
                    class: "block w-full text-sm text-gray-600 border border-gray-400 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                  }, null, 32),
                  unref(form).errors.thumbnail ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.thumbnail), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "address",
                    class: "text-gray-900 dark:text-white"
                  }, [
                    createTextVNode("Alamat "),
                    createVNode("span", { class: "text-red-500" }, "*")
                  ]),
                  withDirectives(createVNode("input", {
                    id: "address",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).address = $event,
                    required: "",
                    placeholder: "Masukkan alamat"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).address]
                  ]),
                  unref(form).errors.address ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.address), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "phone",
                    class: "text-gray-900 dark:text-white"
                  }, "Telepon"),
                  withDirectives(createVNode("input", {
                    id: "phone",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).phone = $event,
                    placeholder: "Masukkan nomor telepon"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).phone]
                  ]),
                  unref(form).errors.phone ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.phone), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "grid grid-cols-2 gap-3" }, [
                  createVNode("div", { class: "space-y-1 text-sm" }, [
                    createVNode("label", {
                      for: "latitude",
                      class: "text-gray-900 dark:text-white"
                    }, "Latitude"),
                    withDirectives(createVNode("input", {
                      id: "latitude",
                      class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                      type: "number",
                      step: "any",
                      "onUpdate:modelValue": ($event) => unref(form).latitude = $event,
                      placeholder: "Latitude"
                    }, null, 8, ["onUpdate:modelValue"]), [
                      [vModelText, unref(form).latitude]
                    ]),
                    unref(form).errors.latitude ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "text-sm text-red-500"
                    }, toDisplayString(unref(form).errors.latitude), 1)) : createCommentVNode("", true)
                  ]),
                  createVNode("div", { class: "space-y-1 text-sm" }, [
                    createVNode("label", {
                      for: "longitude",
                      class: "text-gray-900 dark:text-white"
                    }, "Longitude"),
                    withDirectives(createVNode("input", {
                      id: "longitude",
                      class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                      type: "number",
                      step: "any",
                      "onUpdate:modelValue": ($event) => unref(form).longitude = $event,
                      placeholder: "Longitude"
                    }, null, 8, ["onUpdate:modelValue"]), [
                      [vModelText, unref(form).longitude]
                    ]),
                    unref(form).errors.longitude ? (openBlock(), createBlock("div", {
                      key: 0,
                      class: "text-sm text-red-500"
                    }, toDisplayString(unref(form).errors.longitude), 1)) : createCommentVNode("", true)
                  ])
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "description",
                    class: "text-gray-900 dark:text-white"
                  }, "Deskripsi"),
                  withDirectives(createVNode("textarea", {
                    id: "description",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    "onUpdate:modelValue": ($event) => unref(form).description = $event,
                    rows: "3",
                    placeholder: "Masukkan deskripsi"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).description]
                  ]),
                  unref(form).errors.description ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.description), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "status",
                    class: "text-gray-900 dark:text-white"
                  }, [
                    createTextVNode("Status "),
                    createVNode("span", { class: "text-red-500" }, "*")
                  ]),
                  withDirectives(createVNode("select", {
                    id: "status",
                    "onUpdate:modelValue": ($event) => unref(form).status = $event,
                    class: "block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  }, [
                    createVNode("option", { value: "active" }, "Aktif"),
                    createVNode("option", { value: "inactive" }, "Tidak Aktif"),
                    createVNode("option", { value: "maintenance" }, "Maintenance")
                  ], 8, ["onUpdate:modelValue"]), [
                    [vModelSelect, unref(form).status]
                  ]),
                  unref(form).errors.status ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.status), 1)) : createCommentVNode("", true)
                ])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$4, {
        show: isConfirmModalOpen.value,
        question: `Yakin ingin menghapus`,
        selected: `${selectedItem.value?.name}`,
        title: "Hapus Boarding House",
        confirmText: "Ya, Hapus!",
        maxWidth: "md",
        onClose: closeConfirmModal,
        onConfirm: destroyData
      }, null, _parent));
      _push(`</div>`);
      if (__props.boardingHouses.data && __props.boardingHouses.data.length > 0) {
        _push(ssrRenderComponent(_sfc_main$5, { pagination: __props.boardingHouses }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/BoardingHouses/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

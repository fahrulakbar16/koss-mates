import { ref, computed, watch, unref, withCtx, createVNode, withDirectives, createBlock, createCommentVNode, createTextVNode, vModelText, openBlock, toDisplayString, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderList, ssrInterpolate, ssrRenderClass } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth, P as PlusSquareIcon, O as OfficeIcon, H as HomeIcon, E as EditIcon, T as TrashIcon, b as PageIcon } from "./AppLayout-WpxQpyk2.js";
import { S as SearchIcon, _ as _sfc_main$4 } from "./ConfirmModal-DbAIyvQK.js";
import { _ as _sfc_main$2 } from "./Breadcrumb-ZUUo-p_f.js";
import { _ as _sfc_main$3 } from "./Modal-B9Hg0zK1.js";
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
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    clusters: Object,
    owners: Array,
    search: String
  },
  setup(__props) {
    const props = __props;
    const { can } = useAuth();
    const breadcrumbs = [{ label: "Properti" }, { label: "Cluster" }];
    const search = ref(props.search || "");
    const allClusters = ref(props.clusters?.data || []);
    const nextCursor = ref(props.clusters?.next_cursor || null);
    const hasNextPage = computed(() => !!nextCursor.value);
    const isLoadingMore = ref(false);
    ref(null);
    const isInitialLoad = ref(true);
    watch(() => props.clusters, (newClusters) => {
      if (newClusters?.data) {
        const currentSearch = props.search || "";
        const wasSearching = search.value && search.value !== currentSearch;
        if (isInitialLoad.value || wasSearching || allClusters.value.length === 0) {
          allClusters.value = [...newClusters.data];
          isInitialLoad.value = false;
        } else {
          allClusters.value = [...allClusters.value, ...newClusters.data];
        }
        nextCursor.value = newClusters.next_cursor || null;
        isLoadingMore.value = false;
      }
    }, { immediate: true });
    let timeout = null;
    watch(search, (newSearch, oldSearch) => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        allClusters.value = [];
        nextCursor.value = null;
        isLoadingMore.value = false;
        isInitialLoad.value = true;
        router.get(
          route("clusters.index"),
          { search: search.value },
          {
            preserveScroll: false,
            preserveState: false,
            replace: true
          }
        );
      }, 400);
    });
    const isModalOpen = ref(false);
    const isEditMode = ref(false);
    const form = useForm({
      id: null,
      name: "",
      address: "",
      description: ""
    });
    function closeModal() {
      isModalOpen.value = false;
      selectedItem.value = null;
      form.reset();
      form.clearErrors();
    }
    function saveCluster() {
      if (isEditMode.value) {
        if (!can("clusters.edit")) {
          return;
        }
        form.put(route("clusters.update", form.id), {
          onSuccess: () => {
            closeModal();
            allClusters.value = [];
            nextCursor.value = null;
            isInitialLoad.value = true;
            router.reload({ only: ["clusters"] });
          }
        });
      } else {
        if (!can("clusters.create")) {
          return;
        }
        form.post(route("clusters.store"), {
          onSuccess: () => {
            closeModal();
            allClusters.value = [];
            nextCursor.value = null;
            isInitialLoad.value = true;
            router.reload({ only: ["clusters"] });
          }
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
      if (!can("clusters.delete")) {
        return;
      }
      router.delete(route("clusters.destroy", selectedItem.value.id), {
        onSuccess: () => {
          closeConfirmModal();
          allClusters.value = [];
          nextCursor.value = null;
          isInitialLoad.value = true;
          router.reload({ only: ["clusters"] });
        },
        preserveScroll: true
      });
    };
    const isConfirmBoardingHouseModalOpen = ref(false);
    const selectedCluster = ref(null);
    const selectedBoardingHouse = ref(null);
    const closeConfirmBoardingHouseModal = () => {
      selectedCluster.value = null;
      selectedBoardingHouse.value = null;
      isConfirmBoardingHouseModalOpen.value = false;
    };
    const destroyBoardingHouse = () => {
      if (!can("boarding_houses.delete")) {
        return;
      }
      router.delete(route("clusters.boarding-houses.destroy", [selectedCluster.value.id, selectedBoardingHouse.value.id]), {
        onSuccess: () => {
          closeConfirmBoardingHouseModal();
          allClusters.value = [];
          nextCursor.value = null;
          isInitialLoad.value = true;
          router.reload({ only: ["clusters"] });
        },
        preserveScroll: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Daftar Cluster" }, null, _parent));
      _push(`<div class="flex flex-col gap-3 px-3 h-full"><div class="flex justify-between items-center h-10">`);
      _push(ssrRenderComponent(_sfc_main$2, { items: breadcrumbs }, null, _parent));
      if (unref(can)("clusters.create")) {
        _push(`<button type="button" class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary">`);
        _push(ssrRenderComponent(PlusSquareIcon, null, null, _parent));
        _push(`<span class="hidden text-sm md:block">Tambah Cluster</span></button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="h-[90%] grid-cols-12 gap-4 md:gap-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"><div class="flex flex-col gap-2 px-8 py-4 sm:flex-row sm:items-center sm:justify-between border-b border-gray-200 dark:border-gray-600"><div class="font-bold text-gray-700 md:text-xl dark:text-gray-300"> Daftar Cluster </div><div class="flex gap-3 items-center"><div class="relative py-2"><div class="absolute left-4 top-1/2 -translate-y-1/2">`);
      _push(ssrRenderComponent(SearchIcon, { class: "text-gray-400" }, null, _parent));
      _push(`</div><input${ssrRenderAttr("value", search.value)} type="text" placeholder="Cari cluster" class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-400 dark:focus:border-blue-800 xl:w-[200px]"></div></div></div><div class="overflow-auto px-8 pb-8" data-simplebar>`);
      if (allClusters.value.length > 0) {
        _push(`<div class="space-y-6 py-6"><!--[-->`);
        ssrRenderList(allClusters.value, (cluster) => {
          _push(`<div class="p-6 rounded-xl border border-gray-200 bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 dark:border-gray-700 shadow-sm hover:shadow-md transition-all duration-200"><div class="flex justify-between items-start mb-6"><div class="flex-1"><div class="flex items-center gap-3 mb-2"><div class="p-2 rounded-lg bg-primary-100 dark:bg-primary-900/30">`);
          _push(ssrRenderComponent(OfficeIcon, { class: "w-6 h-6 text-primary-600 dark:text-primary-400" }, null, _parent));
          _push(`</div><div><h3 class="text-xl font-bold text-gray-900 dark:text-white">${ssrInterpolate(cluster.name)}</h3>`);
          if (cluster.address) {
            _push(`<p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1"><span>${ssrInterpolate(cluster.address)}</span></p>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div></div>`);
          if (cluster.description) {
            _push(`<p class="text-sm text-gray-600 dark:text-gray-300 mt-2 line-clamp-2">${ssrInterpolate(cluster.description)}</p>`);
          } else {
            _push(`<!---->`);
          }
          _push(`<div class="flex items-center gap-4 mt-3"><span class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">`);
          _push(ssrRenderComponent(HomeIcon, { class: "w-4 h-4" }, null, _parent));
          _push(` ${ssrInterpolate(cluster.boarding_houses_count)} Kos </span></div></div><div class="flex gap-2 ml-4">`);
          if (unref(can)("boarding_houses.create")) {
            _push(`<button class="flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-white bg-primary-500 rounded-lg hover:bg-primary-600 transition-colors" title="Tambah Kos">`);
            _push(ssrRenderComponent(PlusSquareIcon, { class: "w-4 h-4" }, null, _parent));
            _push(` Tambah </button>`);
          } else {
            _push(`<!---->`);
          }
          if (unref(can)("clusters.edit")) {
            _push(`<button class="p-2 text-yellow-500 hover:text-yellow-600 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 rounded-lg transition-colors" title="Edit Cluster">`);
            _push(ssrRenderComponent(EditIcon, { class: "w-5 h-5" }, null, _parent));
            _push(`</button>`);
          } else {
            _push(`<!---->`);
          }
          if (unref(can)("clusters.delete")) {
            _push(`<button class="p-2 text-red-500 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors" title="Hapus Cluster">`);
            _push(ssrRenderComponent(TrashIcon, { class: "w-5 h-5" }, null, _parent));
            _push(`</button>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div></div>`);
          if (cluster.boarding_houses && cluster.boarding_houses.length > 0) {
            _push(`<div class="mt-6"><div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4"><!--[-->`);
            ssrRenderList(cluster.boarding_houses, (boardingHouse) => {
              _push(`<div class="group relative rounded-xl border border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800 overflow-hidden hover:shadow-lg transition-all duration-200 hover:-translate-y-1"><div class="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800">`);
              if (boardingHouse.thumbnail) {
                _push(`<img${ssrRenderAttr("src", `/storage/${boardingHouse.thumbnail}`)}${ssrRenderAttr("alt", boardingHouse.name)} class="object-cover w-full h-full">`);
              } else {
                _push(`<div class="flex items-center justify-center w-full h-full text-gray-400 dark:text-gray-500">`);
                _push(ssrRenderComponent(HomeIcon, { class: "w-16 h-16" }, null, _parent));
                _push(`</div>`);
              }
              _push(`<span class="${ssrRenderClass([
                "absolute top-3 right-3 px-2.5 py-1 text-xs font-semibold rounded-full shadow-sm",
                boardingHouse.status === "active" ? "bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300" : boardingHouse.status === "maintenance" ? "bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300" : "bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300"
              ])}">${ssrInterpolate(boardingHouse.status === "active" ? "Aktif" : boardingHouse.status === "maintenance" ? "Maintenance" : "Tidak Aktif")}</span>`);
              if (unref(can)("boarding_houses.view") || unref(can)("boarding_houses.edit") || unref(can)("boarding_houses.delete")) {
                _push(`<div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2">`);
                if (unref(can)("boarding_houses.view")) {
                  _push(ssrRenderComponent(unref(link_default), {
                    href: _ctx.route("boarding-houses.show", boardingHouse.id),
                    class: "p-2 text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition-colors",
                    title: "Detail"
                  }, {
                    default: withCtx((_, _push2, _parent2, _scopeId) => {
                      if (_push2) {
                        _push2(ssrRenderComponent(PageIcon, { class: "w-5 h-5" }, null, _parent2, _scopeId));
                      } else {
                        return [
                          createVNode(PageIcon, { class: "w-5 h-5" })
                        ];
                      }
                    }),
                    _: 2
                  }, _parent));
                } else {
                  _push(`<!---->`);
                }
                if (unref(can)("boarding_houses.edit")) {
                  _push(`<button class="p-2 text-white bg-yellow-500 rounded-lg hover:bg-yellow-600 transition-colors" title="Edit">`);
                  _push(ssrRenderComponent(EditIcon, { class: "w-5 h-5" }, null, _parent));
                  _push(`</button>`);
                } else {
                  _push(`<!---->`);
                }
                if (unref(can)("boarding_houses.delete")) {
                  _push(`<button class="p-2 text-white bg-red-500 rounded-lg hover:bg-red-600 transition-colors" title="Hapus">`);
                  _push(ssrRenderComponent(TrashIcon, { class: "w-5 h-5" }, null, _parent));
                  _push(`</button>`);
                } else {
                  _push(`<!---->`);
                }
                _push(`</div>`);
              } else {
                _push(`<!---->`);
              }
              _push(`</div><div class="p-4"><h4 class="font-semibold text-gray-900 dark:text-white mb-1 line-clamp-1">${ssrInterpolate(boardingHouse.name)}</h4>`);
              if (boardingHouse.owner) {
                _push(`<p class="text-xs text-gray-500 dark:text-gray-400 mb-2"> Pemilik: ${ssrInterpolate(boardingHouse.owner.name)}</p>`);
              } else {
                _push(`<!---->`);
              }
              if (boardingHouse.address) {
                _push(`<p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-2">${ssrInterpolate(boardingHouse.address)}</p>`);
              } else {
                _push(`<!---->`);
              }
              if (boardingHouse.rooms_count !== void 0) {
                _push(`<div class="mt-2 text-xs text-gray-500 dark:text-gray-400">${ssrInterpolate(boardingHouse.rooms_count)} Kamar </div>`);
              } else {
                _push(`<!---->`);
              }
              _push(`</div></div>`);
            });
            _push(`<!--]--></div></div>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</div>`);
        });
        _push(`<!--]--></div>`);
      } else if (allClusters.value.length === 0 && !isLoadingMore.value) {
        _push(`<div class="py-12 text-center">`);
        _push(ssrRenderComponent(OfficeIcon, { class: "w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4" }, null, _parent));
        _push(`<p class="text-gray-500 dark:text-gray-400"> Tidak ada cluster ditemukan </p></div>`);
      } else {
        _push(`<!---->`);
      }
      if (isLoadingMore.value) {
        _push(`<div class="py-8 text-center"><div class="inline-flex items-center gap-2 text-gray-500 dark:text-gray-400"><svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>Memuat data...</span></div></div>`);
      } else {
        _push(`<!---->`);
      }
      if (hasNextPage.value && !isLoadingMore.value) {
        _push(`<div class="py-6 text-center"><button class="px-6 py-2 text-sm font-medium text-white bg-primary-500 rounded-lg hover:bg-primary-600 transition-colors"> Muat Lebih Banyak </button></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
      _push(ssrRenderComponent(_sfc_main$3, {
        show: isModalOpen.value,
        title: isEditMode.value ? `Edit ${selectedItem.value?.name}` : "Tambah Cluster Baru",
        confirmText: "Simpan",
        maxWidth: "lg",
        onClose: closeModal,
        onConfirm: saveCluster
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-3"${_scopeId}><div class="space-y-1 text-sm"${_scopeId}><label for="name" class="text-gray-900 dark:text-white"${_scopeId}>Nama Cluster <span class="text-red-500"${_scopeId}>*</span></label><input id="name" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).name)} required placeholder="Masukkan nama cluster"${_scopeId}>`);
            if (unref(form).errors.name) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="address" class="text-gray-900 dark:text-white"${_scopeId}>Alamat</label><input id="address" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).address)} placeholder="Masukkan alamat cluster"${_scopeId}>`);
            if (unref(form).errors.address) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.address)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="description" class="text-gray-900 dark:text-white"${_scopeId}>Deskripsi</label><textarea id="description" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" rows="3" placeholder="Masukkan deskripsi cluster"${_scopeId}>${ssrInterpolate(unref(form).description)}</textarea>`);
            if (unref(form).errors.description) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.description)}</div>`);
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
                    createTextVNode("Nama Cluster "),
                    createVNode("span", { class: "text-red-500" }, "*")
                  ]),
                  withDirectives(createVNode("input", {
                    id: "name",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    required: "",
                    placeholder: "Masukkan nama cluster"
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
                    for: "address",
                    class: "text-gray-900 dark:text-white"
                  }, "Alamat"),
                  withDirectives(createVNode("input", {
                    id: "address",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).address = $event,
                    placeholder: "Masukkan alamat cluster"
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
                    for: "description",
                    class: "text-gray-900 dark:text-white"
                  }, "Deskripsi"),
                  withDirectives(createVNode("textarea", {
                    id: "description",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    "onUpdate:modelValue": ($event) => unref(form).description = $event,
                    rows: "3",
                    placeholder: "Masukkan deskripsi cluster"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).description]
                  ]),
                  unref(form).errors.description ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-red-500"
                  }, toDisplayString(unref(form).errors.description), 1)) : createCommentVNode("", true)
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
        title: "Hapus Cluster",
        confirmText: "Ya, Hapus!",
        maxWidth: "md",
        onClose: closeConfirmModal,
        onConfirm: destroyData
      }, null, _parent));
      _push(ssrRenderComponent(_sfc_main$4, {
        show: isConfirmBoardingHouseModalOpen.value,
        question: `Yakin ingin menghapus`,
        selected: `${selectedBoardingHouse.value?.name}`,
        title: "Hapus Kos",
        confirmText: "Ya, Hapus!",
        maxWidth: "md",
        onClose: closeConfirmBoardingHouseModal,
        onConfirm: destroyBoardingHouse
      }, null, _parent));
      _push(`</div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Clusters/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

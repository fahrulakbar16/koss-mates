import { ref, watch, unref, withCtx, createVNode, createBlock, toDisplayString, openBlock, withDirectives, createCommentVNode, Fragment, renderList, vShow, createTextVNode, vModelText, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderList, ssrInterpolate, ssrRenderClass, ssrRenderStyle } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth, P as PlusSquareIcon, G as GearIcon, E as EditIcon, T as TrashIcon } from "./AppLayout-WpxQpyk2.js";
import { S as SearchIcon, _ as _sfc_main$4 } from "./ConfirmModal-DbAIyvQK.js";
import { U as UpIcon, D as DownIcon } from "./DownIcon-CPTLChOV.js";
import { _ as _sfc_main$2 } from "./Breadcrumb-ZUUo-p_f.js";
import { _ as _sfc_main$3 } from "./Modal-B9Hg0zK1.js";
import { _ as _sfc_main$5 } from "./Pagination-DHA0kVzb.js";
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
    roles: Object,
    search: String,
    sortBy: String,
    sortDirection: String
  },
  setup(__props) {
    const { can } = useAuth();
    const breadcrumbs = [{ label: "Konfigurasi" }, { label: "Jabatan" }];
    const props = __props;
    function fetchRoles({
      sortBy = props.sortBy,
      sortDirection = props.sortDirection
    } = {}) {
      router.get(
        route("roles.index"),
        {
          search: search.value,
          sortBy,
          sortDirection
        },
        {
          preserveScroll: true,
          preserveState: true,
          replace: true
        }
      );
    }
    const search = ref(props.search || "");
    let timeout = null;
    watch(search, () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        fetchRoles();
      }, 400);
    });
    const isModalOpen = ref(false);
    const isEditMode = ref(false);
    const form = useForm({
      id: null,
      name: "",
      leave_quota_per_year: 0,
      loan_quota: 0
    });
    function formatCurrency(val) {
      try {
        const num = Number(val || 0);
        return new Intl.NumberFormat("id-ID", { style: "currency", currency: "IDR", maximumFractionDigits: 0 }).format(num);
      } catch {
        return `Rp ${Number(val || 0).toLocaleString("id-ID")}`;
      }
    }
    function closeModal() {
      isModalOpen.value = false;
      form.reset();
    }
    function saveRole() {
      if (isEditMode.value) {
        if (!can("roles.edit")) {
          return;
        }
        form.put(route("roles.update", form.id), {
          onSuccess: closeModal
        });
      } else {
        if (!can("roles.create")) {
          return;
        }
        form.post(route("roles.store"), {
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
      if (!can("roles.delete")) {
        return;
      }
      router.delete(route("roles.destroy", selectedItem.value.id), {
        onSuccess: () => {
          closeConfirmModal();
        },
        preserveScroll: true
      });
    };
    const isStatsModalOpen = ref(false);
    const statsLoading = ref(false);
    const statsRole = ref(null);
    const statsUsers = ref([]);
    const statsPermissions = ref([]);
    const activeStatsTab = ref("users");
    function closeStatsModal() {
      isStatsModalOpen.value = false;
      statsUsers.value = [];
      statsPermissions.value = [];
    }
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Daftar Jabatan" }, null, _parent));
      _push(`<div class="flex flex-col h-full gap-3 px-3 overflow-hidden"><div class="flex items-center justify-between h-10">`);
      _push(ssrRenderComponent(_sfc_main$2, { items: breadcrumbs }, null, _parent));
      if (unref(can)("roles.create")) {
        _push(`<button class="inline-flex items-center gap-2 text-white rounded-md border hover:border-gray-300 btn-primary px-3 py-2 text-sm font-medium hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">`);
        _push(ssrRenderComponent(PlusSquareIcon, null, null, _parent));
        _push(`<span class="hidden text-sm md:block">Tambah Jabatan Baru</span></button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="flex flex-col overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"><div class="flex flex-col h-16 gap-2 px-8 sm:flex-row sm:items-center sm:justify-between"><div class="font-bold text-gray-700 md:text-xl dark:text-gray-300"> Daftar Jabatan </div><div class="flex items-center gap-3"><div class="relative py-2"><div class="absolute -translate-y-1/2 left-4 top-1/2">`);
      _push(ssrRenderComponent(SearchIcon, { class: "text-gray-400" }, null, _parent));
      _push(`</div><input${ssrRenderAttr("value", search.value)} type="text" placeholder="Cari Jabatan" class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-400 dark:focus:border-blue-800 xl:w-[220px]"></div></div></div><div class="overflow-auto" data-simplebar><table class="min-w-full text-sm"><thead><tr><th class="py-2.5 bg-gray-100 border-gray-200 border-y dark:border-gray-600 dark:bg-gray-800"><div class="flex items-center justify-center px-3"><p class="flex flex-col items-center font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> No. </p></div></th><th class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"><div class="flex items-center justify-center gap-2 px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Nama Jabatan </p><div class="flex flex-col items-center">`);
      _push(ssrRenderComponent(UpIcon, {
        class: [
          "-mb-1",
          __props.sortBy === "name" && __props.sortDirection === "asc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(ssrRenderComponent(DownIcon, {
        class: [
          "-mt-1",
          __props.sortBy === "name" && __props.sortDirection === "desc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(`</div></div></th><th class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"><div class="flex items-center justify-center px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Jatah Cuti </p></div></th><th class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"><div class="flex items-center justify-center px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Jatah Piutang </p></div></th><th class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"><div class="flex items-center justify-center gap-2 px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Total Pengguna </p><div class="flex flex-col items-center">`);
      _push(ssrRenderComponent(UpIcon, {
        class: [
          "-mb-1",
          __props.sortBy === "total" && __props.sortDirection === "asc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(ssrRenderComponent(DownIcon, {
        class: [
          "-mt-1",
          __props.sortBy === "total" && __props.sortDirection === "desc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(`</div></div></th><th class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"><div class="flex items-center justify-center gap-2 px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Jumlah Hak Akses </p><div class="flex flex-col items-center">`);
      _push(ssrRenderComponent(UpIcon, {
        class: [
          "-mb-1",
          __props.sortBy === "access" && __props.sortDirection === "asc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(ssrRenderComponent(DownIcon, {
        class: [
          "-mt-1",
          __props.sortBy === "access" && __props.sortDirection === "desc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(`</div></div></th>`);
      if (unref(can)("roles.edit") || unref(can)("roles.delete")) {
        _push(`<th class="bg-gray-100 border-gray-200 border-y dark:border-gray-600 dark:bg-gray-800"><div class="flex items-center justify-center"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Aksi </p></div></th>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</tr></thead><tbody>`);
      if (__props.roles.data && __props.roles.data.length > 0) {
        _push(`<!--[-->`);
        ssrRenderList(__props.roles.data, (role, index) => {
          _push(`<tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"><td class="py-2.5 border-gray-200 border-y dark:border-gray-600"><div class="flex items-center justify-center whitespace-nowrap"><p class="px-3 text-gray-500 dark:text-gray-400">${ssrInterpolate((__props.roles.current_page - 1) * __props.roles.per_page + index + 1)}. </p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex items-center justify-center whitespace-nowrap"><p class="${ssrRenderClass([{ "cursor-pointer hover:text-primary": unref(can)("roles.view") }, "font-medium text-gray-800 dark:text-white/90"])}">${ssrInterpolate(role.name)}</p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex items-center justify-center whitespace-nowrap"><p class="font-medium text-gray-800 dark:text-white/90">${ssrInterpolate(role.leave_quota_per_year ?? 0)} Hari </p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex items-center justify-center whitespace-nowrap"><p class="font-medium text-gray-800 dark:text-white/90">${ssrInterpolate(formatCurrency(role.loan_quota || 0))}</p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex items-center justify-center px-3 whitespace-nowrap"><p class="${ssrRenderClass([unref(can)("roles.view") ? "text-primary hover:underline" : "text-gray-600", "cursor-pointer dark:text-blue-400"])}">${ssrInterpolate(role.total)} pengguna </p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex items-center justify-center px-3 whitespace-nowrap"><p class="${ssrRenderClass([unref(can)("roles.view") ? "text-primary hover:underline" : "text-gray-600", "cursor-pointer dark:text-blue-400"])}">${ssrInterpolate(role.access)} akses </p></div></td>`);
          if (unref(can)("roles.edit") || unref(can)("roles.delete")) {
            _push(`<td class="py-2.5 border-gray-200 border-y dark:border-gray-600"><div class="flex justify-center gap-3 px-4 whitespace-nowrap sm:px-0">`);
            if (unref(can)("roles.edit")) {
              _push(ssrRenderComponent(unref(link_default), {
                href: _ctx.route("roles.edit", role.id),
                class: "text-primary hover:text-primary",
                title: "Kelola Hak Akses"
              }, {
                default: withCtx((_, _push2, _parent2, _scopeId) => {
                  if (_push2) {
                    _push2(ssrRenderComponent(GearIcon, null, null, _parent2, _scopeId));
                  } else {
                    return [
                      createVNode(GearIcon)
                    ];
                  }
                }),
                _: 2
              }, _parent));
            } else {
              _push(`<!---->`);
            }
            if (unref(can)("roles.edit")) {
              _push(`<button class="text-yellow-500 hover:text-yellow-600" title="Edit">`);
              _push(ssrRenderComponent(EditIcon, null, null, _parent));
              _push(`</button>`);
            } else {
              _push(`<!---->`);
            }
            if (unref(can)("roles.delete")) {
              _push(`<button class="text-red-500 hover:text-red-600" title="Hapus">`);
              _push(ssrRenderComponent(TrashIcon, null, null, _parent));
              _push(`</button>`);
            } else {
              _push(`<!---->`);
            }
            _push(`</div></td>`);
          } else {
            _push(`<!---->`);
          }
          _push(`</tr>`);
        });
        _push(`<!--]-->`);
      } else {
        _push(`<tr><td${ssrRenderAttr("colspan", unref(can)("roles.edit") || unref(can)("roles.delete") ? 7 : 6)} class="py-6 font-medium text-center text-gray-500 dark:text-gray-400"> Tidak ada data ditemukan </td></tr>`);
      }
      _push(`</tbody></table>`);
      _push(ssrRenderComponent(_sfc_main$3, {
        show: isStatsModalOpen.value,
        title: `Rincian ${statsRole.value?.name || ""}`,
        confirmText: "Tutup",
        maxWidth: "3xl",
        onClose: closeStatsModal,
        onConfirm: closeStatsModal
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-4"${_scopeId}><div class="flex gap-2 border-b"${_scopeId}><button class="${ssrRenderClass([activeStatsTab.value === "users" ? "text-primary border-b-2 border-primary" : "text-gray-500", "px-3 py-2 text-sm font-medium"])}"${_scopeId}> Daftar Pengguna (${ssrInterpolate(statsUsers.value.length)}) </button><button class="${ssrRenderClass([activeStatsTab.value === "permissions" ? "text-primary border-b-2 border-primary" : "text-gray-500", "px-3 py-2 text-sm font-medium"])}"${_scopeId}> Daftar Hak Akses (${ssrInterpolate(statsPermissions.value.length)}) </button></div>`);
            if (statsLoading.value) {
              _push2(`<div class="py-8 text-center text-gray-500"${_scopeId}> Memuat... </div>`);
            } else {
              _push2(`<div${_scopeId}><div style="${ssrRenderStyle(activeStatsTab.value === "users" ? null : { display: "none" })}"${_scopeId}><table class="min-w-full text-sm"${_scopeId}><thead${_scopeId}><tr class="bg-gray-50"${_scopeId}><th class="p-2 text-left"${_scopeId}>Nama</th><th class="p-2 text-left"${_scopeId}>Username</th><th class="p-2 text-left"${_scopeId}>Email</th></tr></thead><tbody${_scopeId}>`);
              if (statsUsers.value.length === 0) {
                _push2(`<tr${_scopeId}><td colspan="3" class="p-4 text-center text-gray-500"${_scopeId}>Tidak ada pengguna</td></tr>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<!--[-->`);
              ssrRenderList(statsUsers.value, (u) => {
                _push2(`<tr class="border-t"${_scopeId}><td class="p-2"${_scopeId}>${ssrInterpolate(u.name)}</td><td class="p-2"${_scopeId}>${ssrInterpolate(u.username || "-")}</td><td class="p-2"${_scopeId}>${ssrInterpolate(u.email || "-")}</td></tr>`);
              });
              _push2(`<!--]--></tbody></table></div><div style="${ssrRenderStyle(activeStatsTab.value === "permissions" ? null : { display: "none" })}"${_scopeId}><table class="min-w-full text-sm"${_scopeId}><thead${_scopeId}><tr class="bg-gray-50"${_scopeId}><th class="p-2 text-left"${_scopeId}>Display</th><th class="p-2 text-left"${_scopeId}>Group</th></tr></thead><tbody${_scopeId}>`);
              if (statsPermissions.value.length === 0) {
                _push2(`<tr${_scopeId}><td colspan="2" class="p-4 text-center text-gray-500"${_scopeId}>Tidak ada hak akses</td></tr>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<!--[-->`);
              ssrRenderList(statsPermissions.value, (p) => {
                _push2(`<tr class="border-t"${_scopeId}><td class="p-2"${_scopeId}>${ssrInterpolate(p.name)}</td><td class="p-2"${_scopeId}>${ssrInterpolate(p.group_name || "-")}</td></tr>`);
              });
              _push2(`<!--]--></tbody></table></div></div>`);
            }
            _push2(`</div>`);
          } else {
            return [
              createVNode("div", { class: "space-y-4" }, [
                createVNode("div", { class: "flex gap-2 border-b" }, [
                  createVNode("button", {
                    class: ["px-3 py-2 text-sm font-medium", activeStatsTab.value === "users" ? "text-primary border-b-2 border-primary" : "text-gray-500"],
                    onClick: ($event) => activeStatsTab.value = "users"
                  }, " Daftar Pengguna (" + toDisplayString(statsUsers.value.length) + ") ", 11, ["onClick"]),
                  createVNode("button", {
                    class: ["px-3 py-2 text-sm font-medium", activeStatsTab.value === "permissions" ? "text-primary border-b-2 border-primary" : "text-gray-500"],
                    onClick: ($event) => activeStatsTab.value = "permissions"
                  }, " Daftar Hak Akses (" + toDisplayString(statsPermissions.value.length) + ") ", 11, ["onClick"])
                ]),
                statsLoading.value ? (openBlock(), createBlock("div", {
                  key: 0,
                  class: "py-8 text-center text-gray-500"
                }, " Memuat... ")) : (openBlock(), createBlock("div", { key: 1 }, [
                  withDirectives(createVNode("div", null, [
                    createVNode("table", { class: "min-w-full text-sm" }, [
                      createVNode("thead", null, [
                        createVNode("tr", { class: "bg-gray-50" }, [
                          createVNode("th", { class: "p-2 text-left" }, "Nama"),
                          createVNode("th", { class: "p-2 text-left" }, "Username"),
                          createVNode("th", { class: "p-2 text-left" }, "Email")
                        ])
                      ]),
                      createVNode("tbody", null, [
                        statsUsers.value.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                          createVNode("td", {
                            colspan: "3",
                            class: "p-4 text-center text-gray-500"
                          }, "Tidak ada pengguna")
                        ])) : createCommentVNode("", true),
                        (openBlock(true), createBlock(Fragment, null, renderList(statsUsers.value, (u) => {
                          return openBlock(), createBlock("tr", {
                            key: u.id,
                            class: "border-t"
                          }, [
                            createVNode("td", { class: "p-2" }, toDisplayString(u.name), 1),
                            createVNode("td", { class: "p-2" }, toDisplayString(u.username || "-"), 1),
                            createVNode("td", { class: "p-2" }, toDisplayString(u.email || "-"), 1)
                          ]);
                        }), 128))
                      ])
                    ])
                  ], 512), [
                    [vShow, activeStatsTab.value === "users"]
                  ]),
                  withDirectives(createVNode("div", null, [
                    createVNode("table", { class: "min-w-full text-sm" }, [
                      createVNode("thead", null, [
                        createVNode("tr", { class: "bg-gray-50" }, [
                          createVNode("th", { class: "p-2 text-left" }, "Display"),
                          createVNode("th", { class: "p-2 text-left" }, "Group")
                        ])
                      ]),
                      createVNode("tbody", null, [
                        statsPermissions.value.length === 0 ? (openBlock(), createBlock("tr", { key: 0 }, [
                          createVNode("td", {
                            colspan: "2",
                            class: "p-4 text-center text-gray-500"
                          }, "Tidak ada hak akses")
                        ])) : createCommentVNode("", true),
                        (openBlock(true), createBlock(Fragment, null, renderList(statsPermissions.value, (p) => {
                          return openBlock(), createBlock("tr", {
                            key: p.id,
                            class: "border-t"
                          }, [
                            createVNode("td", { class: "p-2" }, toDisplayString(p.name), 1),
                            createVNode("td", { class: "p-2" }, toDisplayString(p.group_name || "-"), 1)
                          ]);
                        }), 128))
                      ])
                    ])
                  ], 512), [
                    [vShow, activeStatsTab.value === "permissions"]
                  ])
                ]))
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(ssrRenderComponent(_sfc_main$3, {
        show: isModalOpen.value,
        title: isEditMode.value ? `Edit ${selectedItem.value?.name}` : "Tambah Jabatan",
        confirmText: "Simpan",
        maxWidth: "lg",
        onClose: closeModal,
        onConfirm: saveRole
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-3"${_scopeId}><div class="space-y-1 text-sm"${_scopeId}><label for="name" class="font-semibold text-gray-900 dark:text-white"${_scopeId}>Nama <span class="text-red-500"${_scopeId}>*</span></label><input id="name" class="w-full text-sm font-medium text-gray-600 placeholder-gray-500 border-gray-400 rounded-lg placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).name)} required placeholder="Masukkan nama jabatan"${_scopeId}>`);
            if (unref(form).errors.name) {
              _push2(`<div class="text-sm text-red-500"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="leave_quota_per_year" class="font-semibold text-gray-900 dark:text-white"${_scopeId}>Jatah Cuti (hari/tahun)</label><input id="leave_quota_per_year" class="w-full text-sm font-medium text-gray-600 placeholder-gray-500 border-gray-400 rounded-lg placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="number" min="0" step="1"${ssrRenderAttr("value", unref(form).leave_quota_per_year)} placeholder="Contoh: 12"${_scopeId}></div><div class="space-y-1 text-sm"${_scopeId}><label for="loan_quota" class="font-semibold text-gray-900 dark:text-white"${_scopeId}>Jatah Piutang (Rp)</label><input id="loan_quota" class="w-full text-sm font-medium text-gray-600 placeholder-gray-500 border-gray-400 rounded-lg placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="number" min="0" step="1000"${ssrRenderAttr("value", unref(form).loan_quota)} placeholder="Contoh: 3000000"${_scopeId}></div></div>`);
          } else {
            return [
              createVNode("div", { class: "space-y-3" }, [
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "name",
                    class: "font-semibold text-gray-900 dark:text-white"
                  }, [
                    createTextVNode("Nama "),
                    createVNode("span", { class: "text-red-500" }, "*")
                  ]),
                  withDirectives(createVNode("input", {
                    id: "name",
                    class: "w-full text-sm font-medium text-gray-600 placeholder-gray-500 border-gray-400 rounded-lg placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    required: "",
                    placeholder: "Masukkan nama jabatan"
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
                    for: "leave_quota_per_year",
                    class: "font-semibold text-gray-900 dark:text-white"
                  }, "Jatah Cuti (hari/tahun)"),
                  withDirectives(createVNode("input", {
                    id: "leave_quota_per_year",
                    class: "w-full text-sm font-medium text-gray-600 placeholder-gray-500 border-gray-400 rounded-lg placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "number",
                    min: "0",
                    step: "1",
                    "onUpdate:modelValue": ($event) => unref(form).leave_quota_per_year = $event,
                    placeholder: "Contoh: 12"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [
                      vModelText,
                      unref(form).leave_quota_per_year,
                      void 0,
                      { number: true }
                    ]
                  ])
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "loan_quota",
                    class: "font-semibold text-gray-900 dark:text-white"
                  }, "Jatah Piutang (Rp)"),
                  withDirectives(createVNode("input", {
                    id: "loan_quota",
                    class: "w-full text-sm font-medium text-gray-600 placeholder-gray-500 border-gray-400 rounded-lg placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "number",
                    min: "0",
                    step: "1000",
                    "onUpdate:modelValue": ($event) => unref(form).loan_quota = $event,
                    placeholder: "Contoh: 3000000"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [
                      vModelText,
                      unref(form).loan_quota,
                      void 0,
                      { number: true }
                    ]
                  ])
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
        title: "Hapus Jabatan",
        confirmText: "Ya, Hapus!",
        maxWidth: "md",
        onClose: closeConfirmModal,
        onConfirm: destroyData
      }, null, _parent));
      _push(`</div>`);
      if (__props.roles.data && __props.roles.data.length > 0) {
        _push(ssrRenderComponent(_sfc_main$5, { pagination: __props.roles }, null, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Roles/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

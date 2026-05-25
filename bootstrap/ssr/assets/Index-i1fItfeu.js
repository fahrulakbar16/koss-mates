import { ref, watch, unref, withCtx, createVNode, withDirectives, createBlock, createCommentVNode, vModelText, openBlock, toDisplayString, Fragment, renderList, vModelSelect, useSSRContext } from "vue";
import { ssrRenderComponent, ssrIncludeBooleanAttr, ssrLooseContain, ssrLooseEqual, ssrRenderList, ssrRenderAttr, ssrInterpolate, ssrRenderClass } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth, P as PlusSquareIcon, E as EditIcon, T as TrashIcon } from "./AppLayout-WpxQpyk2.js";
import { S as SearchIcon, _ as _sfc_main$4 } from "./ConfirmModal-DbAIyvQK.js";
import { U as UpIcon, D as DownIcon } from "./DownIcon-CPTLChOV.js";
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
    users: Object,
    roles: Object,
    search: String,
    role: String,
    status: String,
    statusCounts: Object,
    sortBy: String,
    sortDirection: String
  },
  setup(__props) {
    const props = __props;
    const { can } = useAuth();
    const breadcrumbs = [{ label: "Menu Utama" }, { label: "Pengguna" }];
    const statusFilter = ref(props.status || "all");
    const formatTime = (date) => {
      if (!date) return "-";
      return new Intl.DateTimeFormat("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric",
        hour: "2-digit",
        minute: "2-digit"
      }).format(new Date(date));
    };
    const isOnline = (lastSeen) => {
      if (!lastSeen) return false;
      const now = /* @__PURE__ */ new Date();
      const seen = new Date(lastSeen);
      const diffMinutes = (now - seen) / (1e3 * 60);
      return diffMinutes < 2;
    };
    const getStatusCount = (status) => {
      if (!props.statusCounts) return 0;
      return props.statusCounts[status] || 0;
    };
    function fetchUsers({
      sortBy = props.sortBy,
      sortDirection = props.sortDirection
    } = {}) {
      router.get(
        route("users.index"),
        {
          search: search.value,
          role: roleFilter.value,
          status: statusFilter.value,
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
    const roleFilter = ref(props.role || "");
    let timeout = null;
    watch(search, () => {
      clearTimeout(timeout);
      timeout = setTimeout(() => {
        fetchUsers();
      }, 400);
    });
    watch(roleFilter, () => {
      fetchUsers();
    });
    watch(statusFilter, () => {
      const currentUrl = new URL(window.location.href);
      currentUrl.searchParams.delete("page");
      router.get(
        route("users.index"),
        {
          search: search.value,
          role: roleFilter.value,
          status: statusFilter.value,
          sortBy: props.sortBy,
          sortDirection: props.sortDirection
        },
        {
          preserveScroll: true,
          preserveState: true,
          replace: true
        }
      );
    });
    const isModalOpen = ref(false);
    const isEditMode = ref(false);
    const form = useForm({
      id: null,
      name: "",
      username: "",
      email: "",
      role: "",
      status: "active"
    });
    function closeModal() {
      isModalOpen.value = false;
      selectedItem.value = null;
      form.reset();
      form.clearErrors();
    }
    function saveUser() {
      if (isEditMode.value) {
        if (!can("users.edit")) {
          return;
        }
        form.put(route("users.update", form.id), {
          onSuccess: closeModal
        });
      } else {
        if (!can("users.create")) {
          return;
        }
        form.post(route("users.store"), {
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
      if (!can("users.delete")) {
        return;
      }
      router.delete(route("users.destroy", selectedItem.value.id), {
        onSuccess: () => {
          closeConfirmModal();
        },
        preserveScroll: true
      });
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Daftar Pengguna" }, null, _parent));
      _push(`<div class="flex flex-col gap-3 px-3 h-full"><div class="flex justify-between items-center h-10">`);
      _push(ssrRenderComponent(_sfc_main$2, { items: breadcrumbs }, null, _parent));
      if (unref(can)("users.create")) {
        _push(`<button type="button" class="flex gap-2 items-center px-3 py-2 text-white rounded btn-primary">`);
        _push(ssrRenderComponent(PlusSquareIcon, null, null, _parent));
        _push(`<span class="hidden text-sm md:block">Tambah Pengguna</span></button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div><div class="h-[90%] grid-cols-12 gap-4 md:gap-6 overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"><div class="flex flex-col gap-2 px-8 py-1 sm:flex-row sm:items-center sm:justify-between"><div class="font-bold text-gray-700 md:text-xl dark:text-gray-300"> Daftar Pengguna </div><div class="flex gap-3 items-center"><select class="px-3 h-10 text-sm text-gray-800 bg-transparent rounded-lg border border-gray-200 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 focus:border-blue-300 focus:outline-hidden focus:ring-2 focus:ring-blue-500/20"><option value=""${ssrIncludeBooleanAttr(Array.isArray(roleFilter.value) ? ssrLooseContain(roleFilter.value, "") : ssrLooseEqual(roleFilter.value, "")) ? " selected" : ""}>Semua Peran</option><!--[-->`);
      ssrRenderList(props.roles, (role) => {
        _push(`<option${ssrRenderAttr("value", role.name)}${ssrIncludeBooleanAttr(Array.isArray(roleFilter.value) ? ssrLooseContain(roleFilter.value, role.name) : ssrLooseEqual(roleFilter.value, role.name)) ? " selected" : ""}>${ssrInterpolate(role.name)}</option>`);
      });
      _push(`<!--]--></select><div class="relative py-2"><div class="absolute left-4 top-1/2 -translate-y-1/2">`);
      _push(ssrRenderComponent(SearchIcon, { class: "text-gray-400" }, null, _parent));
      _push(`</div><input${ssrRenderAttr("value", search.value)} type="text" placeholder="Cari pengguna" class="h-10 w-full rounded-lg border border-gray-200 bg-transparent py-2.5 pl-12 pr-4 text-sm text-gray-800 placeholder:text-gray-400 focus:border-blue-300 focus:outline-hidden focus:ring-3 focus:ring-blue-500/10 dark:border-gray-700 dark:bg-gray-800 dark:text-white/90 dark:placeholder:text-gray-400 dark:focus:border-blue-800 xl:w-[200px]"></div></div></div><div class="px-8 border-b border-gray-200 dark:border-gray-600"><nav class="flex gap-4 -mb-px"><button class="${ssrRenderClass([
        "px-4 py-2 text-sm font-medium border-b-2 transition-colors",
        statusFilter.value === "all" ? "border-primary text-primary" : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
      ])}"> Semua `);
      if (statusFilter.value === "all") {
        _push(`<span class="px-2 py-0.5 ml-2 text-xs text-primary bg-primary/10 rounded-full">${ssrInterpolate(getStatusCount("all"))}</span>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</button><button class="${ssrRenderClass([
        "px-4 py-2 text-sm font-medium border-b-2 transition-colors",
        statusFilter.value === "active" ? "border-green-500 text-green-600 dark:text-green-400" : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
      ])}"> Aktif `);
      if (statusFilter.value === "active") {
        _push(`<span class="px-2 py-0.5 ml-2 text-xs text-green-600 bg-green-100 rounded-full dark:bg-green-900 dark:text-green-300">${ssrInterpolate(getStatusCount("active"))}</span>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</button><button class="${ssrRenderClass([
        "px-4 py-2 text-sm font-medium border-b-2 transition-colors",
        statusFilter.value === "pending" ? "border-yellow-500 text-yellow-600 dark:text-yellow-400" : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
      ])}"> Menunggu Verifikasi `);
      if (statusFilter.value === "pending") {
        _push(`<span class="px-2 py-0.5 ml-2 text-xs text-yellow-600 bg-yellow-100 rounded-full dark:bg-yellow-900 dark:text-yellow-300">${ssrInterpolate(getStatusCount("pending"))}</span>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</button><button class="${ssrRenderClass([
        "px-4 py-2 text-sm font-medium border-b-2 transition-colors",
        statusFilter.value === "inactive" ? "border-primary-500 text-primary-600 dark:text-primary-400" : "border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300"
      ])}"> Tidak Aktif `);
      if (statusFilter.value === "inactive") {
        _push(`<span class="px-2 py-0.5 ml-2 text-xs text-primary-600 bg-primary-100 rounded-full dark:bg-primary-900 dark:text-primary-300">${ssrInterpolate(getStatusCount("inactive"))}</span>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</button></nav></div><div class="overflow-auto" data-simplebar><table class="min-w-full text-sm"><thead><tr><th class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"><div class="flex justify-center items-center"><p class="flex flex-col items-center font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> No. </p></div></th><th class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"><div class="flex gap-2 justify-center items-center px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Nama &amp; Email Pengguna </p><div class="flex flex-col items-center">`);
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
      _push(`</div></div></th><th class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"><div class="flex gap-2 justify-center items-center px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Username </p><div class="flex flex-col items-center">`);
      _push(ssrRenderComponent(UpIcon, {
        class: [
          "-mb-1",
          __props.sortBy === "username" && __props.sortDirection === "asc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(ssrRenderComponent(DownIcon, {
        class: [
          "-mt-1",
          __props.sortBy === "username" && __props.sortDirection === "desc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(`</div></div></th><th class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"><div class="flex gap-2 justify-center items-center px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Peran &amp; Akses </p><div class="flex flex-col items-center">`);
      _push(ssrRenderComponent(UpIcon, {
        class: [
          "-mb-1",
          __props.sortBy === "role" && __props.sortDirection === "asc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(ssrRenderComponent(DownIcon, {
        class: [
          "-mt-1",
          __props.sortBy === "role" && __props.sortDirection === "desc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(`</div></div></th><th class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"><div class="flex justify-center items-center"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Status </p></div></th><th class="py-3 bg-gray-100 border border-gray-200 cursor-pointer dark:border-gray-600 dark:bg-gray-800"><div class="flex gap-2 justify-center items-center px-3"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Terakhir Dilihat </p><div class="flex flex-col items-center">`);
      _push(ssrRenderComponent(UpIcon, {
        class: [
          "-mb-1",
          __props.sortBy === "updated_at" && __props.sortDirection === "asc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(ssrRenderComponent(DownIcon, {
        class: [
          "-mt-1",
          __props.sortBy === "updated_at" && __props.sortDirection === "desc" ? "text-gray-900 dark:text-gray-200" : "text-gray-400 dark:text-gray-500"
        ]
      }, null, _parent));
      _push(`</div></div></th>`);
      if (unref(can)("users.edit") || unref(can)("users.delete")) {
        _push(`<th class="py-3 bg-gray-100 border border-gray-200 dark:border-gray-600 dark:bg-gray-800"><div class="flex justify-center items-center"><p class="font-medium text-gray-500 whitespace-nowrap dark:text-gray-400"> Aksi </p></div></th>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</tr></thead><tbody>`);
      if (__props.users.data && __props.users.data.length > 0) {
        _push(`<!--[-->`);
        ssrRenderList(__props.users.data, (user, index) => {
          _push(`<tr class="cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-800"><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex justify-center items-center whitespace-nowrap"><p class="px-3 text-gray-500 dark:text-gray-400">${ssrInterpolate((__props.users.current_page - 1) * __props.users.per_page + index + 1)}. </p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex gap-3 items-center whitespace-nowrap ps-5"><img class="object-cover w-10 h-10 rounded-full"${ssrRenderAttr(
            "src",
            user.profile_photo_path ? `/storage/${user.profile_photo_path}` : `https://ui-avatars.com/api/?name=${encodeURIComponent(
              user.name
            )}&background=3b82f6&color=fff`
          )} alt="User profile" loading="lazy"><div class="flex flex-col leading-tight"><p class="font-medium text-gray-800 dark:text-white/90">${ssrInterpolate(user.name)}</p><span class="text-gray-500 dark:text-gray-400">${ssrInterpolate(user.email)}</span></div></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex justify-center items-center px-3 whitespace-nowrap"><p class="text-gray-500 dark:text-gray-400">${ssrInterpolate(user.username)}</p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex justify-center items-center px-3 whitespace-nowrap"><p class="text-gray-500 dark:text-gray-400">${ssrInterpolate(user.roles[0]?.name || "-")}</p></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex justify-center items-center px-3 whitespace-nowrap"><span class="${ssrRenderClass([
            "px-3 py-1 rounded-full text-xs font-medium",
            user.status === "active" ? "bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300" : user.status === "pending" ? "bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:text-yellow-300" : "bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300"
          ])}">${ssrInterpolate(user.status === "active" ? "Aktif" : user.status === "pending" ? "Menunggu Verifikasi" : "Tidak Aktif")}</span></div></td><td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex justify-center items-center px-3 text-gray-500 whitespace-nowrap dark:text-gray-400">`);
          if (user.last_seen && isOnline(user.last_seen)) {
            _push(`<span class="px-3 py-1 text-teal-500 bg-teal-200 rounded-full dark:bg-teal-400 dark:text-teal-100"> Online </span>`);
          } else if (user.last_seen) {
            _push(`<span>${ssrInterpolate(formatTime(user.last_seen))}</span>`);
          } else {
            _push(`<span class="text-gray-400"> Tidak pernah terlihat </span>`);
          }
          _push(`</div></td>`);
          if (unref(can)("users.edit") || unref(can)("users.delete")) {
            _push(`<td class="py-2.5 border border-gray-200 dark:border-gray-600"><div class="flex gap-3 justify-center px-4 whitespace-nowrap sm:px-0">`);
            if (unref(can)("users.edit")) {
              _push(`<button class="text-yellow-500 hover:text-yellow-600" title="Edit">`);
              _push(ssrRenderComponent(EditIcon, null, null, _parent));
              _push(`</button>`);
            } else {
              _push(`<!---->`);
            }
            if (unref(can)("users.delete")) {
              _push(`<button class="text-primary-500 hover:text-primary-600" title="Hapus">`);
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
        _push(`<tr><td${ssrRenderAttr(
          "colspan",
          unref(can)("users.edit") || unref(can)("users.delete") ? 7 : 6
        )} class="py-6 font-medium text-center text-gray-500 dark:text-gray-400 border border-gray-200 dark:border-gray-600"> Tidak ada pengguna ditemukan </td></tr>`);
      }
      _push(`</tbody></table>`);
      _push(ssrRenderComponent(_sfc_main$3, {
        show: isModalOpen.value,
        title: isEditMode.value ? `Edit ${selectedItem.value?.name}` : "Tambah Pengguna Baru",
        confirmText: "Simpan",
        maxWidth: "lg",
        onClose: closeModal,
        onConfirm: saveUser
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="space-y-3"${_scopeId}><div class="space-y-1 text-sm"${_scopeId}><label for="name" class="text-gray-900 dark:text-white"${_scopeId}>Nama Lengkap</label><input id="name" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).name)} required placeholder="Masukkan nama lengkap pengguna"${_scopeId}>`);
            if (unref(form).errors.name) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.name)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="username" class="text-gray-900 dark:text-white"${_scopeId}>Nama Pengguna</label><input id="username" class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="text"${ssrRenderAttr("value", unref(form).username)} required placeholder="Masukkan username"${_scopeId}>`);
            if (unref(form).errors.username) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.username)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="email" class="text-gray-900 dark:text-white"${_scopeId}>Email</label><input id="email"${ssrRenderAttr("value", unref(form).email)} class="w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500" type="email" placeholder="Masukkan email pengguna"${_scopeId}>`);
            if (unref(form).errors.email) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.email)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="role" class="text-gray-900 dark:text-white"${_scopeId}>Peran</label><select id="role" class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"${_scopeId}><option selected hidden value=""${_scopeId}> Pilih peran pengguna </option><!--[-->`);
            ssrRenderList(props.roles, (role) => {
              _push2(`<option${ssrRenderAttr("value", role.name)}${ssrIncludeBooleanAttr(Array.isArray(unref(form).role) ? ssrLooseContain(unref(form).role, role.name) : ssrLooseEqual(unref(form).role, role.name)) ? " selected" : ""}${_scopeId}>${ssrInterpolate(role.name)}</option>`);
            });
            _push2(`<!--]--></select>`);
            if (unref(form).errors.role) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.role)}</div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div><div class="space-y-1 text-sm"${_scopeId}><label for="status" class="text-gray-900 dark:text-white"${_scopeId}>Status</label><select id="status" class="block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"${_scopeId}><option value="active"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "active") : ssrLooseEqual(unref(form).status, "active")) ? " selected" : ""}${_scopeId}>Aktif</option><option value="pending"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "pending") : ssrLooseEqual(unref(form).status, "pending")) ? " selected" : ""}${_scopeId}> Menunggu Verifikasi </option><option value="inactive"${ssrIncludeBooleanAttr(Array.isArray(unref(form).status) ? ssrLooseContain(unref(form).status, "inactive") : ssrLooseEqual(unref(form).status, "inactive")) ? " selected" : ""}${_scopeId}>Tidak Aktif</option></select>`);
            if (unref(form).errors.status) {
              _push2(`<div class="text-sm text-primary-500"${_scopeId}>${ssrInterpolate(unref(form).errors.status)}</div>`);
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
                  }, "Nama Lengkap"),
                  withDirectives(createVNode("input", {
                    id: "name",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).name = $event,
                    required: "",
                    placeholder: "Masukkan nama lengkap pengguna"
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
                    for: "username",
                    class: "text-gray-900 dark:text-white"
                  }, "Nama Pengguna"),
                  withDirectives(createVNode("input", {
                    id: "username",
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "text",
                    "onUpdate:modelValue": ($event) => unref(form).username = $event,
                    required: "",
                    placeholder: "Masukkan username"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).username]
                  ]),
                  unref(form).errors.username ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.username), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "email",
                    class: "text-gray-900 dark:text-white"
                  }, "Email"),
                  withDirectives(createVNode("input", {
                    id: "email",
                    "onUpdate:modelValue": ($event) => unref(form).email = $event,
                    class: "w-full text-sm font-medium placeholder-gray-500 text-gray-600 rounded-lg border-gray-400 placeholder:font-normal dark:border-white dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500",
                    type: "email",
                    placeholder: "Masukkan email pengguna"
                  }, null, 8, ["onUpdate:modelValue"]), [
                    [vModelText, unref(form).email]
                  ]),
                  unref(form).errors.email ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.email), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "role",
                    class: "text-gray-900 dark:text-white"
                  }, "Peran"),
                  withDirectives(createVNode("select", {
                    id: "role",
                    "onUpdate:modelValue": ($event) => unref(form).role = $event,
                    class: "block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  }, [
                    createVNode("option", {
                      selected: "",
                      hidden: "",
                      value: ""
                    }, " Pilih peran pengguna "),
                    (openBlock(true), createBlock(Fragment, null, renderList(props.roles, (role) => {
                      return openBlock(), createBlock("option", {
                        key: role.name,
                        value: role.name
                      }, toDisplayString(role.name), 9, ["value"]);
                    }), 128))
                  ], 8, ["onUpdate:modelValue"]), [
                    [vModelSelect, unref(form).role]
                  ]),
                  unref(form).errors.role ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
                  }, toDisplayString(unref(form).errors.role), 1)) : createCommentVNode("", true)
                ]),
                createVNode("div", { class: "space-y-1 text-sm" }, [
                  createVNode("label", {
                    for: "status",
                    class: "text-gray-900 dark:text-white"
                  }, "Status"),
                  withDirectives(createVNode("select", {
                    id: "status",
                    "onUpdate:modelValue": ($event) => unref(form).status = $event,
                    class: "block p-2.5 w-full text-sm text-gray-600 rounded-lg border-gray-400 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                  }, [
                    createVNode("option", { value: "active" }, "Aktif"),
                    createVNode("option", { value: "pending" }, " Menunggu Verifikasi "),
                    createVNode("option", { value: "inactive" }, "Tidak Aktif")
                  ], 8, ["onUpdate:modelValue"]), [
                    [vModelSelect, unref(form).status]
                  ]),
                  unref(form).errors.status ? (openBlock(), createBlock("div", {
                    key: 0,
                    class: "text-sm text-primary-500"
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
        title: "Hapus Pengguna",
        confirmText: "Ya, Hapus!",
        maxWidth: "md",
        onClose: closeConfirmModal,
        onConfirm: destroyData
      }, null, _parent));
      _push(`</div>`);
      if (__props.users.data && __props.users.data.length > 0) {
        _push(ssrRenderComponent(_sfc_main$5, { pagination: __props.users }, null, _parent));
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
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Users/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

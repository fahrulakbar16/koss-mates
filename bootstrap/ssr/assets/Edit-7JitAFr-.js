import { ref, reactive, onMounted, unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderList, ssrIncludeBooleanAttr, ssrLooseContain } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth } from "./AppLayout-WpxQpyk2.js";
import { _ as _sfc_main$2 } from "./Breadcrumb-ZUUo-p_f.js";
import { u as useForm, h as head_default } from "../ssr.js";
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
const _sfc_main = /* @__PURE__ */ Object.assign({
  layout: _sfc_main$1
}, {
  __name: "Edit",
  __ssrInlineRender: true,
  props: {
    role: Object,
    permissions: Object
  },
  setup(__props) {
    const { can } = useAuth();
    const props = __props;
    const breadcrumbs = [
      { label: "Konfigurasi" },
      { label: "Jabatan", href: route("roles.index") },
      { label: props.role.name }
    ];
    const rolePermissions = ref(props.role.permissions.map((p) => p.id));
    const hasPermission = (id) => rolePermissions.value.includes(id);
    useForm({
      // permission_id: null,
      permission_ids: [],
      checked: false
    });
    const isGroupChecked = (groupPermissions) => {
      return groupPermissions.every((p) => hasPermission(p.id));
    };
    const checkedPermissions = reactive({});
    onMounted(() => {
      Object.values(props.permissions).forEach((group) => {
        group.forEach((permission) => {
          checkedPermissions[permission.id] = hasPermission(permission.id);
        });
      });
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Edit Akses Pengguna" }, null, _parent));
      _push(`<div class="flex flex-col h-full gap-3 px-3 overflow-hidden"><div class="flex items-center justify-between h-10">`);
      _push(ssrRenderComponent(_sfc_main$2, { items: breadcrumbs }, null, _parent));
      _push(`</div><div class="flex flex-col overflow-hidden rounded border border-gray-200 bg-white dark:border-gray-600 dark:bg-white/[0.03]"><div class="flex items-center justify-between gap-2 px-8 py-5 font-semibold text-gray-700 border-b md:text-xl dark:text-gray-300"> Kelola Hak Akses <span class="text-sky-500">${ssrInterpolate(__props.role.name)}</span></div><div class="overflow-auto" data-simplebar><div class="grid grid-cols-1 gap-6 p-8 md:grid-cols-4"><!--[-->`);
      ssrRenderList(props.permissions, (groupPermissions, groupName) => {
        _push(`<div class="p-4 space-y-2 border rounded-lg"><h5 class="flex items-center justify-between text-lg font-bold dark:text-gray-100">${ssrInterpolate(groupName)} <label class="cursor-pointer"><input type="checkbox"${ssrIncludeBooleanAttr(isGroupChecked(groupPermissions)) ? " checked" : ""}${ssrIncludeBooleanAttr(!unref(can)("roles.edit")) ? " disabled" : ""} class="sr-only peer"><div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-sky-300 dark:peer-focus:ring-sky-500 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[&#39;&#39;] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-sky-500 dark:peer-checked:bg-sky-600"></div></label></h5><div class="grid gap-"><!--[-->`);
        ssrRenderList(groupPermissions, (permission) => {
          _push(`<div class="flex items-center gap-3 p-2"><label class="cursor-pointer"><input type="checkbox"${ssrIncludeBooleanAttr(
            Array.isArray(
              checkedPermissions[permission.id]
            ) ? ssrLooseContain(
              checkedPermissions[permission.id],
              null
            ) : checkedPermissions[permission.id]
          ) ? " checked" : ""}${ssrIncludeBooleanAttr(hasPermission(permission.id)) ? " checked" : ""}${ssrIncludeBooleanAttr(!unref(can)("roles.edit")) ? " disabled" : ""} class="sr-only peer"><div class="relative w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-sky-300 dark:peer-focus:ring-sky-500 dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[&#39;&#39;] after:absolute after:top-0.5 after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-sky-500 dark:peer-checked:bg-sky-600"></div></label><span class="dark:text-gray-200">${ssrInterpolate(permission.display_name)}</span></div>`);
        });
        _push(`<!--]--></div></div>`);
      });
      _push(`<!--]--></div></div></div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Roles/Edit.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

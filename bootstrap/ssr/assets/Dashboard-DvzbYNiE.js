import { unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderStyle, ssrInterpolate } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./AppLayout-WpxQpyk2.js";
import { h as head_default } from "../ssr.js";
import { Users, UserCheck, Shield, UserCog } from "lucide-vue-next";
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
  __name: "Dashboard",
  __ssrInlineRender: true,
  props: {
    metrics: {
      type: Object,
      default: () => ({
        total_users: 0,
        active_users: 0,
        total_roles: 0,
        users_with_roles: 0
      })
    }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Dashboard" }, null, _parent));
      _push(`<div class="p-6 space-y-6"><div><h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Dashboard</h1><p class="text-sm text-gray-500 dark:text-gray-400 mt-1"> Pusat Terpadu untuk Manajemen dan Kustomisasi </p></div><div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6"><div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"><div class="p-3 rounded-xl" style="${ssrRenderStyle({ "background-color": "#1b84ff1a" })}">`);
      _push(ssrRenderComponent(unref(Users), { class: "w-6 h-6 text-[#1B84FF]" }, null, _parent));
      _push(`</div><div><h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${ssrInterpolate(__props.metrics.total_users)}</h2><p class="text-sm text-gray-500 dark:text-gray-400">Total Pengguna</p></div></div><div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"><div class="p-3 rounded-xl" style="${ssrRenderStyle({ "background-color": "#10b9811a" })}">`);
      _push(ssrRenderComponent(unref(UserCheck), { class: "w-6 h-6 text-[#10B981]" }, null, _parent));
      _push(`</div><div><h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${ssrInterpolate(__props.metrics.active_users)}</h2><p class="text-sm text-gray-500 dark:text-gray-400">Pengguna Aktif</p></div></div><div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"><div class="p-3 rounded-xl" style="${ssrRenderStyle({ "background-color": "#f59e0b1a" })}">`);
      _push(ssrRenderComponent(unref(Shield), { class: "w-6 h-6 text-[#F59E0B]" }, null, _parent));
      _push(`</div><div><h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${ssrInterpolate(__props.metrics.total_roles)}</h2><p class="text-sm text-gray-500 dark:text-gray-400">Total Jabatan</p></div></div><div class="bg-white rounded-2xl shadow p-6 flex items-center gap-4"><div class="p-3 rounded-xl" style="${ssrRenderStyle({ "background-color": "#8b5cf61a" })}">`);
      _push(ssrRenderComponent(unref(UserCog), { class: "w-6 h-6 text-[#8B5CF6]" }, null, _parent));
      _push(`</div><div><h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${ssrInterpolate(__props.metrics.users_with_roles)}</h2><p class="text-sm text-gray-500 dark:text-gray-400">Pengguna dengan Jabatan</p></div></div></div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Dashboard.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

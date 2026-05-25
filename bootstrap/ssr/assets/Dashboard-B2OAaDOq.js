import { unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrInterpolate, ssrRenderStyle, ssrRenderList } from "vue/server-renderer";
import { _ as _sfc_main$1, u as useAuth } from "./AppLayout-WpxQpyk2.js";
import { h as head_default } from "../ssr.js";
import { Home, FileText, CheckCircle, Clock, CreditCard, MessageSquare, User, Bell } from "lucide-vue-next";
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
    stats: {
      type: Object,
      default: () => ({
        total_sewa: 0,
        sewa_aktif: 0,
        pembayaran_pending: 0
      })
    },
    activities: {
      type: Array,
      default: () => []
    }
  },
  setup(__props) {
    const { user } = useAuth();
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Dashboard Penyewa" }, null, _parent));
      _push(`<div class="p-6 space-y-6"><div><h1 class="text-2xl font-semibold text-gray-900 dark:text-white"> Dashboard Penyewa </h1><p class="text-sm text-gray-500 dark:text-gray-400 mt-1"> Selamat datang, kelola informasi sewa Anda di sini </p></div><div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-2xl shadow-lg p-6 text-white"><div class="flex items-center justify-between"><div><h2 class="text-xl font-semibold mb-2"> Halo, ${ssrInterpolate(unref(user)?.name || "Penyewa")}! </h2><p class="text-blue-100"> Kelola informasi sewa dan transaksi Anda dengan mudah </p></div><div class="p-4 bg-white/20 rounded-xl">`);
      _push(ssrRenderComponent(unref(Home), { class: "w-8 h-8" }, null, _parent));
      _push(`</div></div></div><div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6"><div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-center gap-4"><div class="p-3 rounded-xl" style="${ssrRenderStyle({ "background-color": "#1b84ff1a" })}">`);
      _push(ssrRenderComponent(unref(FileText), { class: "w-6 h-6 text-[#1B84FF]" }, null, _parent));
      _push(`</div><div><h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${ssrInterpolate(__props.stats?.total_sewa || 0)}</h2><p class="text-sm text-gray-500 dark:text-gray-400"> Total Sewa </p></div></div><div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-center gap-4"><div class="p-3 rounded-xl" style="${ssrRenderStyle({ "background-color": "#10b9811a" })}">`);
      _push(ssrRenderComponent(unref(CheckCircle), { class: "w-6 h-6 text-[#10B981]" }, null, _parent));
      _push(`</div><div><h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${ssrInterpolate(__props.stats?.sewa_aktif || 0)}</h2><p class="text-sm text-gray-500 dark:text-gray-400"> Sewa Aktif </p></div></div><div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6 flex items-center gap-4"><div class="p-3 rounded-xl" style="${ssrRenderStyle({ "background-color": "#f59e0b1a" })}">`);
      _push(ssrRenderComponent(unref(Clock), { class: "w-6 h-6 text-[#F59E0B]" }, null, _parent));
      _push(`</div><div><h2 class="text-2xl font-semibold text-gray-900 dark:text-white">${ssrInterpolate(__props.stats?.pembayaran_pending || 0)}</h2><p class="text-sm text-gray-500 dark:text-gray-400"> Pembayaran Pending </p></div></div></div><div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6"><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"> Akses Cepat </h3><div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4"><button class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-blue-500 dark:hover:border-blue-500 transition-colors">`);
      _push(ssrRenderComponent(unref(FileText), { class: "w-6 h-6 text-gray-600 dark:text-gray-400 mb-2" }, null, _parent));
      _push(`<span class="text-sm font-medium text-gray-700 dark:text-gray-300"> Data Sewa </span></button><button class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-blue-500 dark:hover:border-blue-500 transition-colors">`);
      _push(ssrRenderComponent(unref(CreditCard), { class: "w-6 h-6 text-gray-600 dark:text-gray-400 mb-2" }, null, _parent));
      _push(`<span class="text-sm font-medium text-gray-700 dark:text-gray-300"> Pembayaran </span></button><button class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-blue-500 dark:hover:border-blue-500 transition-colors">`);
      _push(ssrRenderComponent(unref(MessageSquare), { class: "w-6 h-6 text-gray-600 dark:text-gray-400 mb-2" }, null, _parent));
      _push(`<span class="text-sm font-medium text-gray-700 dark:text-gray-300"> Pesan </span></button><button class="flex flex-col items-center justify-center p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:border-blue-500 dark:hover:border-blue-500 transition-colors">`);
      _push(ssrRenderComponent(unref(User), { class: "w-6 h-6 text-gray-600 dark:text-gray-400 mb-2" }, null, _parent));
      _push(`<span class="text-sm font-medium text-gray-700 dark:text-gray-300"> Profil </span></button></div></div><div class="bg-white dark:bg-gray-800 rounded-2xl shadow p-6"><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4"> Aktivitas Terbaru </h3><div class="space-y-4">`);
      if (!__props.activities || __props.activities.length === 0) {
        _push(`<div class="text-center py-8 text-gray-500 dark:text-gray-400"><p>Belum ada aktivitas terbaru</p></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`<!--[-->`);
      ssrRenderList(__props.activities, (activity, index) => {
        _push(`<div class="flex items-center gap-4 p-4 border border-gray-200 dark:border-gray-700 rounded-lg"><div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">`);
        _push(ssrRenderComponent(unref(Bell), { class: "w-5 h-5 text-blue-600 dark:text-blue-400" }, null, _parent));
        _push(`</div><div class="flex-1"><p class="text-sm font-medium text-gray-900 dark:text-white">${ssrInterpolate(activity.title)}</p><p class="text-xs text-gray-500 dark:text-gray-400">${ssrInterpolate(activity.date)}</p></div></div>`);
      });
      _push(`<!--]--></div></div></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Penyewa/Dashboard.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

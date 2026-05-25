import { ref, onMounted, watch, unref, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrInterpolate, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { _ as _sfc_main$1 } from "./AppLayout-WpxQpyk2.js";
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
  __name: "Index",
  __ssrInlineRender: true,
  props: {
    settings: Object
  },
  setup(__props) {
    const props = __props;
    const formData = ref({
      logo_main: null,
      logo_favicon: null,
      site_name: "",
      site_description: "",
      contact_email: "",
      contact_phone: "",
      contact_address: ""
    });
    ref({});
    const form = useForm({
      settings: []
    });
    const loadSettings = () => {
      if (props.settings) {
        Object.keys(props.settings).forEach((group) => {
          props.settings[group].forEach((setting) => {
            if (setting.key === "logo_main") {
              formData.value.logo_main = setting.value;
            } else if (setting.key === "logo_favicon") {
              formData.value.logo_favicon = setting.value;
            } else if (setting.key === "site_name") {
              formData.value.site_name = setting.value || "";
            } else if (setting.key === "site_description") {
              formData.value.site_description = setting.value || "";
            } else if (setting.key === "contact_email") {
              formData.value.contact_email = setting.value || "";
            } else if (setting.key === "contact_phone") {
              formData.value.contact_phone = setting.value || "";
            } else if (setting.key === "contact_address") {
              formData.value.contact_address = setting.value || "";
            }
          });
        });
      }
    };
    onMounted(() => {
      loadSettings();
    });
    watch(() => props.settings, () => {
      loadSettings();
    }, { deep: true });
    const getImageUrl = (path) => {
      if (!path) return null;
      if (path.startsWith("data:image")) return path;
      if (path.startsWith("http")) return path;
      if (path.startsWith("settings/")) return `/storage/${path}`;
      if (path.startsWith("/storage/")) return path;
      return `/storage/${path}`;
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: "Pengaturan Website" }, null, _parent));
      _push(`<div class="p-6 space-y-6"><div><h1 class="text-2xl font-bold">Pengaturan Website</h1><p class="text-gray-500"> Kelola logo dan pengaturan website </p></div><form class="space-y-6"><div class="bg-white rounded-lg shadow p-6"><h2 class="text-lg font-semibold mb-4">Logo Website</h2><div class="space-y-4"><div><label class="block text-sm font-medium text-gray-700 mb-2"> Logo Utama </label><div class="flex items-center gap-4">`);
      if (formData.value.logo_main) {
        _push(`<div class="flex-shrink-0"><img${ssrRenderAttr("src", getImageUrl(formData.value.logo_main))} alt="Logo Utama" class="w-32 h-32 object-contain border border-gray-200 rounded-lg"></div>`);
      } else {
        _push(`<div class="flex-shrink-0 w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center"><span class="text-gray-400 text-sm">No Logo</span></div>`);
      }
      _push(`<div class="flex-1"><input type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"><p class="mt-1 text-xs text-gray-500"> Format: JPG, PNG. Maksimal 2MB </p></div></div></div><div><label class="block text-sm font-medium text-gray-700 mb-2"> Favicon </label><div class="flex items-center gap-4">`);
      if (formData.value.logo_favicon) {
        _push(`<div class="flex-shrink-0"><img${ssrRenderAttr("src", getImageUrl(formData.value.logo_favicon))} alt="Favicon" class="w-16 h-16 object-contain border border-gray-200 rounded-lg"></div>`);
      } else {
        _push(`<div class="flex-shrink-0 w-16 h-16 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center"><span class="text-gray-400 text-xs">No Icon</span></div>`);
      }
      _push(`<div class="flex-1"><input type="file" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"><p class="mt-1 text-xs text-gray-500"> Format: ICO, PNG. Ukuran disarankan 32x32 atau 16x16 </p></div></div></div></div></div><div class="bg-white rounded-lg shadow p-6"><h2 class="text-lg font-semibold mb-4">Pengaturan Umum</h2><div class="space-y-4"><div><label class="block text-sm font-medium text-gray-700 mb-2"> Nama Website </label><input${ssrRenderAttr("value", formData.value.site_name)} type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Nama Website"></div><div><label class="block text-sm font-medium text-gray-700 mb-2"> Deskripsi Website </label><textarea rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Deskripsi Website">${ssrInterpolate(formData.value.site_description)}</textarea></div><div><label class="block text-sm font-medium text-gray-700 mb-2"> Email Kontak </label><input${ssrRenderAttr("value", formData.value.contact_email)} type="email" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="email@example.com"></div><div><label class="block text-sm font-medium text-gray-700 mb-2"> Nomor Telepon </label><input${ssrRenderAttr("value", formData.value.contact_phone)} type="text" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="+62 123 456 7890"></div><div><label class="block text-sm font-medium text-gray-700 mb-2"> Alamat </label><textarea rows="2" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Alamat Lengkap">${ssrInterpolate(formData.value.contact_address)}</textarea></div></div></div><div class="flex justify-end gap-3"><button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"> Reset </button><button type="submit"${ssrIncludeBooleanAttr(unref(form).processing) ? " disabled" : ""} class="px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-md disabled:opacity-50 transition-colors">${ssrInterpolate(unref(form).processing ? "Menyimpan..." : "Simpan Pengaturan")}</button></div></form></div><!--]-->`);
    };
  }
});
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Settings/Index.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

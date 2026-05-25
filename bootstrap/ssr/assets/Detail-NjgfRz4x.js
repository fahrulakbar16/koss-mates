import { computed, ref, onBeforeUnmount, mergeProps, unref, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrInterpolate, ssrRenderAttr, ssrRenderClass, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { a as usePage, u as useForm } from "../ssr.js";
import "sweetalert2";
import "@vue/server-renderer";
import "@inertiajs/core";
import "lodash-es";
import "flowbite-vue";
import "primevue/config";
import "@primevue/themes/aura";
import "primevue/toastservice";
import "@primevue/themes";
import "vue3-apexcharts";
import "@fullcalendar/vue3";
import "vue-multiselect";
const _sfc_main = {
  __name: "Detail",
  __ssrInlineRender: true,
  props: {
    user: { type: Object, default: null }
  },
  setup(__props) {
    const props = __props;
    const page = usePage();
    const user = computed(() => props.user ?? page.props.auth?.user ?? {});
    const isEditing = ref(false);
    const photoPreview = ref(user.value?.profile_photo_url || null);
    const profileForm = useForm({
      name: user.value?.name || "",
      email: user.value?.email || "",
      photo: null
      // File
    });
    onBeforeUnmount(() => {
    });
    const passwordForm = useForm({
      current_password: "",
      password: "",
      password_confirmation: ""
    });
    const submitting = ref(false);
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "overflow-hidden bg-white shadow sm:rounded-lg" }, _attrs))}><div class="flex items-center justify-between px-6 py-4 border-b"><h3 class="text-lg font-semibold text-gray-800">${ssrInterpolate(isEditing.value ? "Ubah Data Profile" : "Profil")}</h3><div class="flex items-center gap-2">`);
      if (!isEditing.value) {
        _push(`<button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white rounded-md btn-primary"> Ubah Profile </button>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div>`);
      if (!isEditing.value) {
        _push(`<div class="grid grid-cols-1 lg:grid-cols-[320px,1fr]"><div class="flex flex-col items-center justify-center gap-4 p-8 border-b lg:border-b-0 lg:border-r"><div class="flex items-center justify-center w-56 h-56 overflow-hidden rounded-full bg-primary-50 border border-primary-200">`);
        if (user.value?.profile_photo_url) {
          _push(`<img${ssrRenderAttr("src", user.value.profile_photo_url)} alt="Profile" class="object-cover w-full h-full">`);
        } else {
          _push(`<span class="text-6xl font-semibold select-none text-primary-600">${ssrInterpolate((user.value?.name || user.value?.username || "?").toString().charAt(0).toUpperCase())}</span>`);
        }
        _push(`</div><p class="text-sm text-gray-500">Foto profil saat ini</p></div><div class="p-8"><dl class="grid grid-cols-1 gap-y-5 gap-x-12 sm:grid-cols-2"><div><dt class="text-sm text-gray-500">Name</dt><dd class="mt-1 text-base font-semibold text-gray-900">${ssrInterpolate(user.value?.name || "-")}</dd></div><div><dt class="text-sm text-gray-500">Email Address</dt><dd class="mt-1 text-base font-semibold text-gray-900">${ssrInterpolate(user.value?.email || "-")}</dd></div><div><dt class="text-sm text-gray-500">Username</dt><dd class="mt-1 font-medium">${ssrInterpolate(user.value?.username || "-")}</dd></div><div><dt class="text-sm text-gray-500">Role</dt><dd class="mt-1 font-medium">${ssrInterpolate(user.value?.role || user.value?.roles?.[0]?.name || "-")}</dd></div><div><dt class="text-sm text-gray-500">Status</dt><dd class="mt-1 font-medium"><span class="${ssrRenderClass([
          "inline-flex px-2 py-1 text-xs font-semibold rounded-full",
          user.value?.status === "active" ? "bg-green-100 text-green-800" : "bg-gray-100 text-gray-800"
        ])}">${ssrInterpolate(user.value?.status || "-")}</span></dd></div></dl></div></div>`);
      } else {
        _push(`<form class="grid grid-cols-1 lg:grid-cols-[320px,1fr]"><div class="flex flex-col items-center justify-center gap-4 p-8 border-b lg:border-b-0 lg:border-r"><div class="flex items-center justify-center w-56 h-56 overflow-hidden rounded-full bg-primary-50 border border-primary-200">`);
        if (photoPreview.value) {
          _push(`<img${ssrRenderAttr("src", photoPreview.value)} alt="Profile" class="object-cover w-full h-full">`);
        } else {
          _push(`<span class="text-6xl font-semibold select-none text-primary-600">${ssrInterpolate((user.value?.name || user.value?.username || "?").toString().charAt(0).toUpperCase())}</span>`);
        }
        _push(`</div><p class="text-sm text-gray-500">Pratinjau foto profil</p></div><div class="p-8"><div class="grid grid-cols-1 gap-4"><label class="space-y-1"><span class="text-sm text-gray-700">Name</span><input${ssrRenderAttr("value", unref(profileForm).name)} type="text" autocomplete="name" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">`);
        if (unref(profileForm).errors.name) {
          _push(`<span class="text-sm text-primary-600">${ssrInterpolate(unref(profileForm).errors.name)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</label><label class="space-y-1"><span class="text-sm text-gray-700">Email Address</span><input${ssrRenderAttr("value", unref(profileForm).email)} type="email" autocomplete="email" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">`);
        if (unref(profileForm).errors.email) {
          _push(`<span class="text-sm text-primary-600">${ssrInterpolate(unref(profileForm).errors.email)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</label><label class="space-y-1"><span class="text-sm text-gray-700">Old Password</span><input${ssrRenderAttr("value", unref(passwordForm).current_password)} type="password" autocomplete="current-password" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">`);
        if (unref(passwordForm).errors.current_password) {
          _push(`<span class="text-sm text-primary-600">${ssrInterpolate(unref(passwordForm).errors.current_password)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</label><label class="space-y-1"><span class="text-sm text-gray-700">New Password</span><input${ssrRenderAttr("value", unref(passwordForm).password)} type="password" autocomplete="new-password" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">`);
        if (unref(passwordForm).errors.password) {
          _push(`<span class="text-sm text-primary-600">${ssrInterpolate(unref(passwordForm).errors.password)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</label><label class="space-y-1"><span class="text-sm text-gray-700">Confirm Password</span><input${ssrRenderAttr("value", unref(passwordForm).password_confirmation)} type="password" autocomplete="new-password" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">`);
        if (unref(passwordForm).errors.password_confirmation) {
          _push(`<span class="text-sm text-primary-600">${ssrInterpolate(unref(passwordForm).errors.password_confirmation)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</label><div class="space-y-1"><span class="text-sm text-gray-700">Change Profile Photo</span><div class="flex items-center gap-3"><label class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md cursor-pointer hover:bg-indigo-700"> Choose File <input type="file" accept="image/*" class="hidden"></label><span class="text-sm text-gray-600 truncate">${ssrInterpolate(unref(profileForm).photo ? unref(profileForm).photo.name : "No file chosen")}</span></div>`);
        if (unref(profileForm).errors.photo) {
          _push(`<span class="text-sm text-primary-600">${ssrInterpolate(unref(profileForm).errors.photo)}</span>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div><div class="flex items-center gap-2 pt-2"><button type="submit"${ssrIncludeBooleanAttr(submitting.value) ? " disabled" : ""} class="inline-flex items-center px-4 py-2 text-sm font-medium text-white rounded-md btn-primary disabled:opacity-50">${ssrInterpolate(submitting.value ? "Updating..." : "Update Profile")}</button><button type="button" class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border rounded-md hover:bg-gray-50"> Batal </button></div></div></div></form>`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Profile/Detail.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

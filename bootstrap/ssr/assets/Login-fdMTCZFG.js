import { mergeProps, useSSRContext, ref, computed, withCtx, unref, createVNode, createBlock, openBlock, createCommentVNode, withModifiers, toDisplayString, withDirectives, vModelText, vModelDynamic } from "vue";
import { ssrRenderAttrs, ssrRenderComponent, ssrRenderSlot, ssrRenderStyle, ssrRenderClass, ssrInterpolate, ssrRenderAttr, ssrRenderDynamicModel, ssrIncludeBooleanAttr } from "vue/server-renderer";
import { _ as _sfc_main$2 } from "./FlashMessage-CsoanNwB.js";
import { _ as _sfc_main$3 } from "./InputError-DV-e6PP2.js";
import { a as usePage, u as useForm } from "../ssr.js";
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
const _sfc_main$1 = {
  __name: "FullScreenLayout",
  __ssrInlineRender: true,
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "min-h-screen" }, _attrs))}><main>`);
      _push(ssrRenderComponent(_sfc_main$2, null, null, _parent));
      ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
      _push(`</main></div>`);
    };
  }
};
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/layout/FullScreenLayout.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const _sfc_main = {
  __name: "Login",
  __ssrInlineRender: true,
  props: {
    googleLoginUrl: String
  },
  setup(__props) {
    const page = usePage();
    const activeTab = ref("login");
    const flashError = computed(() => page.props.flash?.error || null);
    const loginForm = useForm({
      user: "",
      password: "",
      remember: false
    });
    const registerForm = useForm({
      name: "",
      email: "",
      password: ""
    });
    const showPassword = ref(false);
    const showRegisterPassword = ref(false);
    const togglePasswordVisibility = () => {
      showPassword.value = !showPassword.value;
    };
    const toggleRegisterPasswordVisibility = () => {
      showRegisterPassword.value = !showRegisterPassword.value;
    };
    const getErrorMessage = (error) => {
      if (!error) return null;
      if (typeof error === "string") return error;
      if (Array.isArray(error)) return error[0] || null;
      if (typeof error === "object") {
        const values = Object.values(error);
        return values.length > 0 ? Array.isArray(values[0]) ? values[0][0] : values[0] : null;
      }
      return String(error);
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(ssrRenderComponent(_sfc_main$1, _attrs, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="flex relative w-full h-screen overflow-hidden"${_scopeId}><div class="hidden relative lg:flex lg:w-1/2 bg-cover bg-center bg-no-repeat" style="${ssrRenderStyle({ backgroundImage: "url(https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&q=80&w=2000)" })}"${_scopeId}><div class="absolute inset-0 bg-black/40"${_scopeId}></div><div class="relative z-10 flex flex-col justify-between p-8 h-full text-white"${_scopeId}><div class="flex gap-2 items-center"${_scopeId}><div class="flex justify-center items-center w-10 h-10 bg-white rounded-lg"${_scopeId}><svg class="w-6 h-6 text-primary-600" fill="currentColor" viewBox="0 0 20 20"${_scopeId}><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"${_scopeId}></path></svg></div><span class="text-xl font-bold"${_scopeId}>Tharahub</span></div><div class="flex flex-col gap-6 max-w-md"${_scopeId}><h1 class="text-4xl font-bold leading-tight"${_scopeId}> Pengelolaan Kos Modern di Ujung Jari Anda. </h1><p class="text-lg leading-relaxed text-white/90"${_scopeId}> Bergabunglah dengan ribuan penyewa, pengelola, dan pemilik kos untuk pengalaman sewa menyewa yang lebih transparan dan mudah. </p><div class="flex gap-3"${_scopeId}><div class="flex gap-2 items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg border border-primary-500/30"${_scopeId}><svg class="w-5 h-5 text-primary-400" fill="currentColor" viewBox="0 0 20 20"${_scopeId}><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"${_scopeId}></path></svg><span class="text-sm font-medium"${_scopeId}>Terpercaya</span></div><div class="flex gap-2 items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg border border-primary-500/30"${_scopeId}><svg class="w-5 h-5 text-primary-400" fill="currentColor" viewBox="0 0 20 20"${_scopeId}><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"${_scopeId}></path></svg><span class="text-sm font-medium"${_scopeId}>Aman</span></div></div></div></div></div><div class="flex flex-1 items-center justify-center bg-gray-100 dark:bg-gray-900 lg:w-1/2"${_scopeId}><div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg dark:bg-gray-800"${_scopeId}><div class="relative flex gap-1 p-1 mb-6 bg-gray-100 rounded-lg dark:bg-gray-700"${_scopeId}><button class="${ssrRenderClass([activeTab.value === "login" ? "bg-white text-gray-900 shadow-sm dark:bg-gray-600 dark:text-white" : "text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300", "relative flex-1 px-4 py-2.5 text-base font-medium transition-all duration-200 rounded-md"])}"${_scopeId}> Masuk </button><button class="${ssrRenderClass([activeTab.value === "register" ? "bg-white text-gray-900 shadow-sm dark:bg-gray-600 dark:text-white" : "text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300", "relative flex-1 px-4 py-2.5 text-base font-medium transition-all duration-200 rounded-md"])}"${_scopeId}> Registrasi </button></div>`);
            if (activeTab.value === "login") {
              _push2(`<div${_scopeId}><div class="mb-6"${_scopeId}><h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white"${_scopeId}> Selamat Datang Kembali </h2><p class="text-sm text-gray-600 dark:text-gray-400"${_scopeId}> Silakan masukkan detail akun Anda untuk melanjutkan. </p></div><form${_scopeId}><div class="space-y-5"${_scopeId}>`);
              if (flashError.value || unref(loginForm).errors.user || unref(loginForm).errors.password || unref(loginForm).hasErrors) {
                _push2(`<div class="p-3 text-sm text-primary-600 bg-primary-50 rounded-lg border border-primary-200 dark:bg-primary-900/20 dark:text-primary-400 dark:border-primary-800"${_scopeId}>`);
                if (flashError.value) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(flashError.value)}</p>`);
                } else if (getErrorMessage(unref(loginForm).errors.user)) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(getErrorMessage(unref(loginForm).errors.user))}</p>`);
                } else if (getErrorMessage(unref(loginForm).errors.password)) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(getErrorMessage(unref(loginForm).errors.password))}</p>`);
                } else if (Object.keys(unref(loginForm).errors).length > 0) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(getErrorMessage(Object.values(unref(loginForm).errors)[0]))}</p>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(`</div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<div${_scopeId}><label for="email" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"${_scopeId}> Email </label><div class="relative"${_scopeId}><div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"${_scopeId}><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"${_scopeId}></path></svg></div><input type="text" id="email"${ssrRenderAttr("value", unref(loginForm).user)} placeholder="email@contoh.com" class="${ssrRenderClass([
                "pl-10 pr-4 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                unref(loginForm).errors.user ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
              ])}"${_scopeId}></div>`);
              _push2(ssrRenderComponent(_sfc_main$3, {
                message: getErrorMessage(unref(loginForm).errors.user),
                class: "mt-1"
              }, null, _parent2, _scopeId));
              _push2(`</div><div${_scopeId}><div class="flex justify-between items-center mb-2"${_scopeId}><label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300"${_scopeId}> Password </label><a href="/forgot-password" class="text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors"${_scopeId}> Lupa Password? </a></div><div class="relative"${_scopeId}><div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"${_scopeId}><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"${_scopeId}></path></svg></div><input${ssrRenderDynamicModel(showPassword.value ? "text" : "password", unref(loginForm).password, null)}${ssrRenderAttr("type", showPassword.value ? "text" : "password")} id="password" placeholder="Enter your password" class="${ssrRenderClass([
                "pl-10 pr-11 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                unref(loginForm).errors.password ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
              ])}"${_scopeId}><button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"${_scopeId}>`);
              if (!showPassword.value) {
                _push2(`<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"${_scopeId}></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"${_scopeId}></path></svg>`);
              } else {
                _push2(`<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"${_scopeId}></path></svg>`);
              }
              _push2(`</button></div>`);
              _push2(ssrRenderComponent(_sfc_main$3, {
                message: getErrorMessage(unref(loginForm).errors.password),
                class: "mt-1"
              }, null, _parent2, _scopeId));
              _push2(`</div><div${_scopeId}><button type="submit"${ssrIncludeBooleanAttr(unref(loginForm).processing) ? " disabled" : ""} class="px-4 py-3 w-full text-sm font-semibold text-white bg-primary-600 rounded-lg shadow-sm transition-all hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"${_scopeId}>`);
              if (unref(loginForm).processing) {
                _push2(`<span${_scopeId}>Masuk...</span>`);
              } else {
                _push2(`<span${_scopeId}>Masuk Sekarang</span>`);
              }
              _push2(`</button></div><div class="relative my-6"${_scopeId}><div class="absolute inset-0 flex items-center"${_scopeId}><div class="w-full border-t border-gray-300 dark:border-gray-600"${_scopeId}></div></div><div class="relative flex justify-center text-xs"${_scopeId}><span class="px-2 bg-white text-gray-500 dark:bg-gray-800 dark:text-gray-400"${_scopeId}> Atau lanjutkan dengan </span></div></div><div${_scopeId}><a${ssrRenderAttr("href", __props.googleLoginUrl || "#")} class="flex gap-3 justify-center items-center px-4 py-3 w-full text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition-all hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"${_scopeId}><svg class="w-5 h-5" viewBox="0 0 24 24"${_scopeId}><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"${_scopeId}></path><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"${_scopeId}></path><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"${_scopeId}></path><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"${_scopeId}></path></svg><span${_scopeId}>Google</span></a></div></div></form></div>`);
            } else {
              _push2(`<!---->`);
            }
            if (activeTab.value === "register") {
              _push2(`<div${_scopeId}><div class="mb-6"${_scopeId}><h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white"${_scopeId}> Buat Akun Baru </h2><p class="text-sm text-gray-600 dark:text-gray-400"${_scopeId}> Daftar sekarang untuk mulai mencari kos impian Anda. </p></div><form${_scopeId}><div class="space-y-5"${_scopeId}>`);
              if (unref(registerForm).errors.name || unref(registerForm).errors.email || unref(registerForm).errors.password || unref(registerForm).hasErrors) {
                _push2(`<div class="p-3 text-sm text-primary-600 bg-primary-50 rounded-lg border border-primary-200 dark:bg-primary-900/20 dark:text-primary-400 dark:border-primary-800"${_scopeId}>`);
                if (getErrorMessage(unref(registerForm).errors.name)) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(getErrorMessage(unref(registerForm).errors.name))}</p>`);
                } else if (getErrorMessage(unref(registerForm).errors.email)) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(getErrorMessage(unref(registerForm).errors.email))}</p>`);
                } else if (getErrorMessage(unref(registerForm).errors.password)) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(getErrorMessage(unref(registerForm).errors.password))}</p>`);
                } else if (Object.keys(unref(registerForm).errors).length > 0) {
                  _push2(`<p${_scopeId}>${ssrInterpolate(getErrorMessage(Object.values(unref(registerForm).errors)[0]))}</p>`);
                } else {
                  _push2(`<!---->`);
                }
                _push2(`</div>`);
              } else {
                _push2(`<!---->`);
              }
              _push2(`<div${_scopeId}><label for="name" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"${_scopeId}> Nama Lengkap </label><div class="relative"${_scopeId}><div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"${_scopeId}><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"${_scopeId}></path></svg></div><input type="text" id="name"${ssrRenderAttr("value", unref(registerForm).name)} placeholder="Nama Anda" class="${ssrRenderClass([
                "pl-10 pr-4 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                unref(registerForm).errors.name ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
              ])}"${_scopeId}></div>`);
              _push2(ssrRenderComponent(_sfc_main$3, {
                message: getErrorMessage(unref(registerForm).errors.name),
                class: "mt-1"
              }, null, _parent2, _scopeId));
              _push2(`</div><div${_scopeId}><label for="register-email" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"${_scopeId}> Email </label><div class="relative"${_scopeId}><div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"${_scopeId}><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"${_scopeId}></path></svg></div><input type="email" id="register-email"${ssrRenderAttr("value", unref(registerForm).email)} placeholder="email@contoh.com" class="${ssrRenderClass([
                "pl-10 pr-4 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                unref(registerForm).errors.email ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
              ])}"${_scopeId}></div>`);
              _push2(ssrRenderComponent(_sfc_main$3, {
                message: getErrorMessage(unref(registerForm).errors.email),
                class: "mt-1"
              }, null, _parent2, _scopeId));
              _push2(`</div><div${_scopeId}><label for="register-password" class="block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"${_scopeId}> Password </label><div class="relative"${_scopeId}><div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"${_scopeId}><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"${_scopeId}></path></svg></div><input${ssrRenderDynamicModel(showRegisterPassword.value ? "text" : "password", unref(registerForm).password, null)}${ssrRenderAttr("type", showRegisterPassword.value ? "text" : "password")} id="register-password" placeholder="Enter your password" class="${ssrRenderClass([
                "pl-10 pr-11 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                unref(registerForm).errors.password ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
              ])}"${_scopeId}><button type="button" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"${_scopeId}>`);
              if (!showRegisterPassword.value) {
                _push2(`<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"${_scopeId}></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"${_scopeId}></path></svg>`);
              } else {
                _push2(`<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"${_scopeId}></path></svg>`);
              }
              _push2(`</button></div>`);
              _push2(ssrRenderComponent(_sfc_main$3, {
                message: getErrorMessage(unref(registerForm).errors.password),
                class: "mt-1"
              }, null, _parent2, _scopeId));
              _push2(`</div><div${_scopeId}><button type="submit"${ssrIncludeBooleanAttr(unref(registerForm).processing) ? " disabled" : ""} class="px-4 py-3 w-full text-sm font-semibold text-white bg-primary-600 rounded-lg shadow-sm transition-all hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"${_scopeId}>`);
              if (unref(registerForm).processing) {
                _push2(`<span${_scopeId}>Mendaftar...</span>`);
              } else {
                _push2(`<span${_scopeId}>Daftar Sekarang</span>`);
              }
              _push2(`</button></div><div class="relative my-6"${_scopeId}><div class="absolute inset-0 flex items-center"${_scopeId}><div class="w-full border-t border-gray-300 dark:border-gray-600"${_scopeId}></div></div><div class="relative flex justify-center text-xs"${_scopeId}><span class="px-2 bg-white text-gray-500 dark:bg-gray-800 dark:text-gray-400"${_scopeId}> Atau lanjutkan dengan </span></div></div><div${_scopeId}><a${ssrRenderAttr("href", __props.googleLoginUrl || "#")} class="flex gap-3 justify-center items-center px-4 py-3 w-full text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition-all hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"${_scopeId}><svg class="w-5 h-5" viewBox="0 0 24 24"${_scopeId}><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"${_scopeId}></path><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"${_scopeId}></path><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"${_scopeId}></path><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"${_scopeId}></path></svg><span${_scopeId}>Google</span></a></div></div></form></div>`);
            } else {
              _push2(`<!---->`);
            }
            _push2(`</div></div></div>`);
          } else {
            return [
              createVNode("div", { class: "flex relative w-full h-screen overflow-hidden" }, [
                createVNode("div", {
                  class: "hidden relative lg:flex lg:w-1/2 bg-cover bg-center bg-no-repeat",
                  style: { backgroundImage: "url(https://images.unsplash.com/photo-1518780664697-55e3ad937233?auto=format&fit=crop&q=80&w=2000)" }
                }, [
                  createVNode("div", { class: "absolute inset-0 bg-black/40" }),
                  createVNode("div", { class: "relative z-10 flex flex-col justify-between p-8 h-full text-white" }, [
                    createVNode("div", { class: "flex gap-2 items-center" }, [
                      createVNode("div", { class: "flex justify-center items-center w-10 h-10 bg-white rounded-lg" }, [
                        (openBlock(), createBlock("svg", {
                          class: "w-6 h-6 text-primary-600",
                          fill: "currentColor",
                          viewBox: "0 0 20 20"
                        }, [
                          createVNode("path", { d: "M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" })
                        ]))
                      ]),
                      createVNode("span", { class: "text-xl font-bold" }, "Tharahub")
                    ]),
                    createVNode("div", { class: "flex flex-col gap-6 max-w-md" }, [
                      createVNode("h1", { class: "text-4xl font-bold leading-tight" }, " Pengelolaan Kos Modern di Ujung Jari Anda. "),
                      createVNode("p", { class: "text-lg leading-relaxed text-white/90" }, " Bergabunglah dengan ribuan penyewa, pengelola, dan pemilik kos untuk pengalaman sewa menyewa yang lebih transparan dan mudah. "),
                      createVNode("div", { class: "flex gap-3" }, [
                        createVNode("div", { class: "flex gap-2 items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg border border-primary-500/30" }, [
                          (openBlock(), createBlock("svg", {
                            class: "w-5 h-5 text-primary-400",
                            fill: "currentColor",
                            viewBox: "0 0 20 20"
                          }, [
                            createVNode("path", {
                              "fill-rule": "evenodd",
                              d: "M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z",
                              "clip-rule": "evenodd"
                            })
                          ])),
                          createVNode("span", { class: "text-sm font-medium" }, "Terpercaya")
                        ]),
                        createVNode("div", { class: "flex gap-2 items-center px-4 py-2 bg-white/10 backdrop-blur-sm rounded-lg border border-primary-500/30" }, [
                          (openBlock(), createBlock("svg", {
                            class: "w-5 h-5 text-primary-400",
                            fill: "currentColor",
                            viewBox: "0 0 20 20"
                          }, [
                            createVNode("path", {
                              "fill-rule": "evenodd",
                              d: "M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z",
                              "clip-rule": "evenodd"
                            })
                          ])),
                          createVNode("span", { class: "text-sm font-medium" }, "Aman")
                        ])
                      ])
                    ])
                  ])
                ]),
                createVNode("div", { class: "flex flex-1 items-center justify-center bg-gray-100 dark:bg-gray-900 lg:w-1/2" }, [
                  createVNode("div", { class: "w-full max-w-md p-8 bg-white rounded-lg shadow-lg dark:bg-gray-800" }, [
                    createVNode("div", { class: "relative flex gap-1 p-1 mb-6 bg-gray-100 rounded-lg dark:bg-gray-700" }, [
                      createVNode("button", {
                        onClick: ($event) => activeTab.value = "login",
                        class: ["relative flex-1 px-4 py-2.5 text-base font-medium transition-all duration-200 rounded-md", activeTab.value === "login" ? "bg-white text-gray-900 shadow-sm dark:bg-gray-600 dark:text-white" : "text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"]
                      }, " Masuk ", 10, ["onClick"]),
                      createVNode("button", {
                        onClick: ($event) => activeTab.value = "register",
                        class: ["relative flex-1 px-4 py-2.5 text-base font-medium transition-all duration-200 rounded-md", activeTab.value === "register" ? "bg-white text-gray-900 shadow-sm dark:bg-gray-600 dark:text-white" : "text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-300"]
                      }, " Registrasi ", 10, ["onClick"])
                    ]),
                    activeTab.value === "login" ? (openBlock(), createBlock("div", { key: 0 }, [
                      createVNode("div", { class: "mb-6" }, [
                        createVNode("h2", { class: "mb-2 text-2xl font-bold text-gray-900 dark:text-white" }, " Selamat Datang Kembali "),
                        createVNode("p", { class: "text-sm text-gray-600 dark:text-gray-400" }, " Silakan masukkan detail akun Anda untuk melanjutkan. ")
                      ]),
                      createVNode("form", {
                        onSubmit: withModifiers(($event) => unref(loginForm).post("/login", {
                          onError: () => {
                          }
                        }), ["prevent"])
                      }, [
                        createVNode("div", { class: "space-y-5" }, [
                          flashError.value || unref(loginForm).errors.user || unref(loginForm).errors.password || unref(loginForm).hasErrors ? (openBlock(), createBlock("div", {
                            key: 0,
                            class: "p-3 text-sm text-primary-600 bg-primary-50 rounded-lg border border-primary-200 dark:bg-primary-900/20 dark:text-primary-400 dark:border-primary-800"
                          }, [
                            flashError.value ? (openBlock(), createBlock("p", { key: 0 }, toDisplayString(flashError.value), 1)) : getErrorMessage(unref(loginForm).errors.user) ? (openBlock(), createBlock("p", { key: 1 }, toDisplayString(getErrorMessage(unref(loginForm).errors.user)), 1)) : getErrorMessage(unref(loginForm).errors.password) ? (openBlock(), createBlock("p", { key: 2 }, toDisplayString(getErrorMessage(unref(loginForm).errors.password)), 1)) : Object.keys(unref(loginForm).errors).length > 0 ? (openBlock(), createBlock("p", { key: 3 }, toDisplayString(getErrorMessage(Object.values(unref(loginForm).errors)[0])), 1)) : createCommentVNode("", true)
                          ])) : createCommentVNode("", true),
                          createVNode("div", null, [
                            createVNode("label", {
                              for: "email",
                              class: "block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                            }, " Email "),
                            createVNode("div", { class: "relative" }, [
                              createVNode("div", { class: "absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" }, [
                                (openBlock(), createBlock("svg", {
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                  })
                                ]))
                              ]),
                              withDirectives(createVNode("input", {
                                type: "text",
                                id: "email",
                                "onUpdate:modelValue": ($event) => unref(loginForm).user = $event,
                                placeholder: "email@contoh.com",
                                class: [
                                  "pl-10 pr-4 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                                  unref(loginForm).errors.user ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
                                ]
                              }, null, 10, ["onUpdate:modelValue"]), [
                                [vModelText, unref(loginForm).user]
                              ])
                            ]),
                            createVNode(_sfc_main$3, {
                              message: getErrorMessage(unref(loginForm).errors.user),
                              class: "mt-1"
                            }, null, 8, ["message"])
                          ]),
                          createVNode("div", null, [
                            createVNode("div", { class: "flex justify-between items-center mb-2" }, [
                              createVNode("label", {
                                for: "password",
                                class: "block text-sm font-semibold text-gray-700 dark:text-gray-300"
                              }, " Password "),
                              createVNode("a", {
                                href: "/forgot-password",
                                class: "text-xs font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 transition-colors"
                              }, " Lupa Password? ")
                            ]),
                            createVNode("div", { class: "relative" }, [
                              createVNode("div", { class: "absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" }, [
                                (openBlock(), createBlock("svg", {
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                  })
                                ]))
                              ]),
                              withDirectives(createVNode("input", {
                                "onUpdate:modelValue": ($event) => unref(loginForm).password = $event,
                                type: showPassword.value ? "text" : "password",
                                id: "password",
                                placeholder: "Enter your password",
                                class: [
                                  "pl-10 pr-11 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                                  unref(loginForm).errors.password ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
                                ]
                              }, null, 10, ["onUpdate:modelValue", "type"]), [
                                [vModelDynamic, unref(loginForm).password]
                              ]),
                              createVNode("button", {
                                type: "button",
                                onClick: togglePasswordVisibility,
                                class: "absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                              }, [
                                !showPassword.value ? (openBlock(), createBlock("svg", {
                                  key: 0,
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                  }),
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                  })
                                ])) : (openBlock(), createBlock("svg", {
                                  key: 1,
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                  })
                                ]))
                              ])
                            ]),
                            createVNode(_sfc_main$3, {
                              message: getErrorMessage(unref(loginForm).errors.password),
                              class: "mt-1"
                            }, null, 8, ["message"])
                          ]),
                          createVNode("div", null, [
                            createVNode("button", {
                              type: "submit",
                              disabled: unref(loginForm).processing,
                              class: "px-4 py-3 w-full text-sm font-semibold text-white bg-primary-600 rounded-lg shadow-sm transition-all hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            }, [
                              unref(loginForm).processing ? (openBlock(), createBlock("span", { key: 0 }, "Masuk...")) : (openBlock(), createBlock("span", { key: 1 }, "Masuk Sekarang"))
                            ], 8, ["disabled"])
                          ]),
                          createVNode("div", { class: "relative my-6" }, [
                            createVNode("div", { class: "absolute inset-0 flex items-center" }, [
                              createVNode("div", { class: "w-full border-t border-gray-300 dark:border-gray-600" })
                            ]),
                            createVNode("div", { class: "relative flex justify-center text-xs" }, [
                              createVNode("span", { class: "px-2 bg-white text-gray-500 dark:bg-gray-800 dark:text-gray-400" }, " Atau lanjutkan dengan ")
                            ])
                          ]),
                          createVNode("div", null, [
                            createVNode("a", {
                              href: __props.googleLoginUrl || "#",
                              class: "flex gap-3 justify-center items-center px-4 py-3 w-full text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition-all hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
                            }, [
                              (openBlock(), createBlock("svg", {
                                class: "w-5 h-5",
                                viewBox: "0 0 24 24"
                              }, [
                                createVNode("path", {
                                  fill: "#4285F4",
                                  d: "M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                }),
                                createVNode("path", {
                                  fill: "#34A853",
                                  d: "M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                }),
                                createVNode("path", {
                                  fill: "#FBBC05",
                                  d: "M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                }),
                                createVNode("path", {
                                  fill: "#EA4335",
                                  d: "M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                })
                              ])),
                              createVNode("span", null, "Google")
                            ], 8, ["href"])
                          ])
                        ])
                      ], 40, ["onSubmit"])
                    ])) : createCommentVNode("", true),
                    activeTab.value === "register" ? (openBlock(), createBlock("div", { key: 1 }, [
                      createVNode("div", { class: "mb-6" }, [
                        createVNode("h2", { class: "mb-2 text-2xl font-bold text-gray-900 dark:text-white" }, " Buat Akun Baru "),
                        createVNode("p", { class: "text-sm text-gray-600 dark:text-gray-400" }, " Daftar sekarang untuk mulai mencari kos impian Anda. ")
                      ]),
                      createVNode("form", {
                        onSubmit: withModifiers(($event) => unref(registerForm).post("/register", {
                          onError: () => {
                          }
                        }), ["prevent"])
                      }, [
                        createVNode("div", { class: "space-y-5" }, [
                          unref(registerForm).errors.name || unref(registerForm).errors.email || unref(registerForm).errors.password || unref(registerForm).hasErrors ? (openBlock(), createBlock("div", {
                            key: 0,
                            class: "p-3 text-sm text-primary-600 bg-primary-50 rounded-lg border border-primary-200 dark:bg-primary-900/20 dark:text-primary-400 dark:border-primary-800"
                          }, [
                            getErrorMessage(unref(registerForm).errors.name) ? (openBlock(), createBlock("p", { key: 0 }, toDisplayString(getErrorMessage(unref(registerForm).errors.name)), 1)) : getErrorMessage(unref(registerForm).errors.email) ? (openBlock(), createBlock("p", { key: 1 }, toDisplayString(getErrorMessage(unref(registerForm).errors.email)), 1)) : getErrorMessage(unref(registerForm).errors.password) ? (openBlock(), createBlock("p", { key: 2 }, toDisplayString(getErrorMessage(unref(registerForm).errors.password)), 1)) : Object.keys(unref(registerForm).errors).length > 0 ? (openBlock(), createBlock("p", { key: 3 }, toDisplayString(getErrorMessage(Object.values(unref(registerForm).errors)[0])), 1)) : createCommentVNode("", true)
                          ])) : createCommentVNode("", true),
                          createVNode("div", null, [
                            createVNode("label", {
                              for: "name",
                              class: "block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                            }, " Nama Lengkap "),
                            createVNode("div", { class: "relative" }, [
                              createVNode("div", { class: "absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" }, [
                                (openBlock(), createBlock("svg", {
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                  })
                                ]))
                              ]),
                              withDirectives(createVNode("input", {
                                type: "text",
                                id: "name",
                                "onUpdate:modelValue": ($event) => unref(registerForm).name = $event,
                                placeholder: "Nama Anda",
                                class: [
                                  "pl-10 pr-4 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                                  unref(registerForm).errors.name ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
                                ]
                              }, null, 10, ["onUpdate:modelValue"]), [
                                [vModelText, unref(registerForm).name]
                              ])
                            ]),
                            createVNode(_sfc_main$3, {
                              message: getErrorMessage(unref(registerForm).errors.name),
                              class: "mt-1"
                            }, null, 8, ["message"])
                          ]),
                          createVNode("div", null, [
                            createVNode("label", {
                              for: "register-email",
                              class: "block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                            }, " Email "),
                            createVNode("div", { class: "relative" }, [
                              createVNode("div", { class: "absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" }, [
                                (openBlock(), createBlock("svg", {
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                  })
                                ]))
                              ]),
                              withDirectives(createVNode("input", {
                                type: "email",
                                id: "register-email",
                                "onUpdate:modelValue": ($event) => unref(registerForm).email = $event,
                                placeholder: "email@contoh.com",
                                class: [
                                  "pl-10 pr-4 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                                  unref(registerForm).errors.email ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
                                ]
                              }, null, 10, ["onUpdate:modelValue"]), [
                                [vModelText, unref(registerForm).email]
                              ])
                            ]),
                            createVNode(_sfc_main$3, {
                              message: getErrorMessage(unref(registerForm).errors.email),
                              class: "mt-1"
                            }, null, 8, ["message"])
                          ]),
                          createVNode("div", null, [
                            createVNode("label", {
                              for: "register-password",
                              class: "block mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300"
                            }, " Password "),
                            createVNode("div", { class: "relative" }, [
                              createVNode("div", { class: "absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" }, [
                                (openBlock(), createBlock("svg", {
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                  })
                                ]))
                              ]),
                              withDirectives(createVNode("input", {
                                "onUpdate:modelValue": ($event) => unref(registerForm).password = $event,
                                type: showRegisterPassword.value ? "text" : "password",
                                id: "register-password",
                                placeholder: "Enter your password",
                                class: [
                                  "pl-10 pr-11 py-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border dark:bg-gray-700 placeholder:text-gray-400 focus:outline-none focus:ring-2 dark:text-white dark:placeholder:text-gray-500 transition-colors",
                                  unref(registerForm).errors.password ? "border-primary-500 focus:border-primary-500 focus:ring-primary-500/20" : "border-gray-300 dark:border-gray-600 focus:border-primary-500 focus:ring-primary-500/20 dark:focus:border-primary-500"
                                ]
                              }, null, 10, ["onUpdate:modelValue", "type"]), [
                                [vModelDynamic, unref(registerForm).password]
                              ]),
                              createVNode("button", {
                                type: "button",
                                onClick: toggleRegisterPasswordVisibility,
                                class: "absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                              }, [
                                !showRegisterPassword.value ? (openBlock(), createBlock("svg", {
                                  key: 0,
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                  }),
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                  })
                                ])) : (openBlock(), createBlock("svg", {
                                  key: 1,
                                  class: "w-5 h-5",
                                  fill: "none",
                                  stroke: "currentColor",
                                  viewBox: "0 0 24 24"
                                }, [
                                  createVNode("path", {
                                    "stroke-linecap": "round",
                                    "stroke-linejoin": "round",
                                    "stroke-width": "2",
                                    d: "M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"
                                  })
                                ]))
                              ])
                            ]),
                            createVNode(_sfc_main$3, {
                              message: getErrorMessage(unref(registerForm).errors.password),
                              class: "mt-1"
                            }, null, 8, ["message"])
                          ]),
                          createVNode("div", null, [
                            createVNode("button", {
                              type: "submit",
                              disabled: unref(registerForm).processing,
                              class: "px-4 py-3 w-full text-sm font-semibold text-white bg-primary-600 rounded-lg shadow-sm transition-all hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                            }, [
                              unref(registerForm).processing ? (openBlock(), createBlock("span", { key: 0 }, "Mendaftar...")) : (openBlock(), createBlock("span", { key: 1 }, "Daftar Sekarang"))
                            ], 8, ["disabled"])
                          ]),
                          createVNode("div", { class: "relative my-6" }, [
                            createVNode("div", { class: "absolute inset-0 flex items-center" }, [
                              createVNode("div", { class: "w-full border-t border-gray-300 dark:border-gray-600" })
                            ]),
                            createVNode("div", { class: "relative flex justify-center text-xs" }, [
                              createVNode("span", { class: "px-2 bg-white text-gray-500 dark:bg-gray-800 dark:text-gray-400" }, " Atau lanjutkan dengan ")
                            ])
                          ]),
                          createVNode("div", null, [
                            createVNode("a", {
                              href: __props.googleLoginUrl || "#",
                              class: "flex gap-3 justify-center items-center px-4 py-3 w-full text-sm font-medium text-gray-700 bg-white rounded-lg border border-gray-300 transition-all hover:bg-gray-50 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600"
                            }, [
                              (openBlock(), createBlock("svg", {
                                class: "w-5 h-5",
                                viewBox: "0 0 24 24"
                              }, [
                                createVNode("path", {
                                  fill: "#4285F4",
                                  d: "M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                }),
                                createVNode("path", {
                                  fill: "#34A853",
                                  d: "M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                }),
                                createVNode("path", {
                                  fill: "#FBBC05",
                                  d: "M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                }),
                                createVNode("path", {
                                  fill: "#EA4335",
                                  d: "M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                })
                              ])),
                              createVNode("span", null, "Google")
                            ], 8, ["href"])
                          ])
                        ])
                      ], 40, ["onSubmit"])
                    ])) : createCommentVNode("", true)
                  ])
                ])
              ])
            ];
          }
        }),
        _: 1
      }, _parent));
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Admin/Auth/Login.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};

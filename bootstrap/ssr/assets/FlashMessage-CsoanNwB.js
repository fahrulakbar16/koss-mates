import { ref, watch, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrInterpolate } from "vue/server-renderer";
import { a as usePage } from "../ssr.js";
const _sfc_main = {
  __name: "FlashMessage",
  __ssrInlineRender: true,
  setup(__props) {
    const successMessage = ref("");
    const errorMessage = ref("");
    const page = usePage();
    watch(
      () => page.props.flash.success,
      (message) => {
        if (message) {
          successMessage.value = message;
          setTimeout(() => successMessage.value = "", 5e3);
        }
      },
      { immediate: true }
    );
    watch(
      () => page.props.flash.error,
      (message) => {
        if (message) {
          errorMessage.value = message;
          setTimeout(() => errorMessage.value = "", 5e3);
        }
      },
      { immediate: true }
    );
    window.flash = {
      success: (msg) => {
        successMessage.value = msg;
        setTimeout(() => successMessage.value = "", 5e3);
      },
      error: (msg) => {
        errorMessage.value = msg;
        setTimeout(() => errorMessage.value = "", 5e3);
      }
    };
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "space-y-2" }, _attrs))}>`);
      if (successMessage.value) {
        _push(`<div class="fixed end-1 top-20 mx-7 my-1.5 z-50 inline-flex items-center gap-x-3 rounded-lg border border-success-500 text-success-500 bg-white p-2 shadow"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-success-50"><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"></path></svg></div><div class="max-w-48 text-sm font-semibold">${ssrInterpolate(successMessage.value)}</div><button class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-gray-400 hover:bg-gray-200"> ✕ </button></div>`);
      } else {
        _push(`<!---->`);
      }
      if (errorMessage.value) {
        _push(`<div class="fixed end-1 top-20 mx-7 my-1.5 z-50 inline-flex items-center gap-x-3 rounded-lg border border-error-500 text-error-500 bg-white p-2 shadow"><div class="flex h-8 w-8 items-center justify-center rounded-lg bg-error-50"><svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 11.793a1 1 0 1 1-1.414 1.414L10 11.414l-2.293 2.293a1 1 0 0 1-1.414-1.414L8.586 10 6.293 7.707a1 1 0 0 1 1.414-1.414L10 8.586l2.293-2.293a1 1 0 0 1 1.414 1.414L11.414 10l2.293 2.293Z"></path></svg></div><div class="max-w-48 text-sm font-semibold">${ssrInterpolate(errorMessage.value)}</div><button class="flex h-7 w-7 items-center justify-center rounded-lg bg-white text-gray-400 hover:bg-gray-200"> ✕ </button></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/layout/FlashMessage.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};

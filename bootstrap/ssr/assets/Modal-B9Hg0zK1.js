import { ref, watch, onMounted, onUnmounted, computed, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderStyle, ssrRenderClass, ssrInterpolate, ssrRenderSlot } from "vue/server-renderer";
const _sfc_main = {
  __name: "Modal",
  __ssrInlineRender: true,
  props: {
    show: { type: Boolean, default: false },
    maxWidth: { type: String, default: "md" },
    closeable: { type: Boolean, default: true },
    title: { type: String, default: "" },
    closeText: { type: String, default: "Batalkan" },
    confirmText: { type: String, default: "" }
  },
  emits: ["close", "confirm"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const dialog = ref();
    const showSlot = ref(props.show);
    watch(
      () => props.show,
      (val) => {
        if (val) {
          document.body.style.overflow = "hidden";
          showSlot.value = true;
          dialog.value?.showModal();
        } else {
          document.body.style.overflow = null;
          setTimeout(() => {
            dialog.value?.close();
            showSlot.value = false;
          }, 200);
        }
      }
    );
    const close = () => {
      if (props.closeable) emit("close");
    };
    const closeOnEscape = (e) => {
      if (e.key === "Escape" && props.show) {
        e.preventDefault();
        close();
      }
    };
    onMounted(() => document.addEventListener("keydown", closeOnEscape));
    onUnmounted(() => {
      document.removeEventListener("keydown", closeOnEscape);
      document.body.style.overflow = null;
    });
    const maxWidthClass = computed(() => {
      return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl"
      }[props.maxWidth];
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<dialog${ssrRenderAttrs(mergeProps({
        class: "z-50 min-h-full min-w-full overflow-y-auto bg-transparent backdrop:bg-transparent",
        ref_key: "dialog",
        ref: dialog
      }, _attrs))}>`);
      if (showSlot.value) {
        _push(`<div class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto px-4"><div class="fixed inset-0 bg-gray-500 dark:bg-gray-950 opacity-75" style="${ssrRenderStyle(__props.show ? null : { display: "none" })}"></div><div class="${ssrRenderClass([maxWidthClass.value, "mb-6 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all w-full mx-auto"])}" style="${ssrRenderStyle(__props.show ? null : { display: "none" })}"><div class="px-8 py-4 border-b flex justify-between items-center"><h3 class="text-lg font-medium text-primary-500">${ssrInterpolate(__props.title)}</h3><button class="text-primary-500 hover:text-primary-700 py-1 px-2.5 rounded-lg bg-white dark:bg-gray-800 border border-gray-400"> ✕ </button></div><div class="px-8 py-4 max-h-[400px] overflow-y-auto" data-simplebar>`);
        ssrRenderSlot(_ctx.$slots, "default", {}, null, _push, _parent);
        _push(`</div><div class="px-8 py-5 flex justify-end gap-4"><button type="button" class="px-4 py-2 w-full bg-gray-200 font-semibold text-gray-600 rounded-lg hover:bg-gray-300">${ssrInterpolate(__props.closeText)}</button>`);
        if (__props.confirmText) {
          _push(`<button type="button" class="px-4 py-2 bg-primary-500 w-full text-white rounded-lg hover:bg-primary-600">${ssrInterpolate(__props.confirmText)}</button>`);
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</dialog>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/common/Modal.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};

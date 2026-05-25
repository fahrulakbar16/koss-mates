import { mergeProps, useSSRContext, ref, watch, onMounted, onUnmounted, computed } from "vue";
import { ssrRenderAttrs, ssrRenderStyle, ssrRenderClass, ssrInterpolate } from "vue/server-renderer";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-1tPrXgE0.js";
const _sfc_main$1 = {};
function _sfc_ssrRender(_ctx, _push, _parent, _attrs) {
  _push(`<svg${ssrRenderAttrs(mergeProps({
    width: "22",
    height: "22",
    viewBox: "0 0 20 20",
    fill: "currentColor",
    xmlns: "http://www.w3.org/2000/svg"
  }, _attrs))}><path fill-rule="evenodd" clip-rule="evenodd" d="M3.04175 9.37363C3.04175 5.87693 5.87711 3.04199 9.37508 3.04199C12.8731 3.04199 15.7084 5.87693 15.7084 9.37363C15.7084 12.8703 12.8731 15.7053 9.37508 15.7053C5.87711 15.7053 3.04175 12.8703 3.04175 9.37363ZM9.37508 1.54199C5.04902 1.54199 1.54175 5.04817 1.54175 9.37363C1.54175 13.6991 5.04902 17.2053 9.37508 17.2053C11.2674 17.2053 13.003 16.5344 14.357 15.4176L17.177 18.238C17.4699 18.5309 17.9448 18.5309 18.2377 18.238C18.5306 17.9451 18.5306 17.4703 18.2377 17.1774L15.418 14.3573C16.5365 13.0033 17.2084 11.2669 17.2084 9.37363C17.2084 5.04817 13.7011 1.54199 9.37508 1.54199Z" fill="currentColor"></path></svg>`);
}
const _sfc_setup$1 = _sfc_main$1.setup;
_sfc_main$1.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/icons/SearchIcon.vue");
  return _sfc_setup$1 ? _sfc_setup$1(props, ctx) : void 0;
};
const SearchIcon = /* @__PURE__ */ _export_sfc(_sfc_main$1, [["ssrRender", _sfc_ssrRender]]);
const _sfc_main = {
  __name: "ConfirmModal",
  __ssrInlineRender: true,
  props: {
    show: {
      type: Boolean,
      default: false
    },
    maxWidth: {
      type: String,
      default: "md"
    },
    closeable: {
      type: Boolean,
      default: true
    },
    title: { type: String, default: "" },
    question: { type: String, default: "Anda yakin ingin melanjutkan?" },
    selected: { type: String, default: "" },
    confirmText: { type: String, default: "OK" }
  },
  emits: ["close", "confirm"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const emit = __emit;
    const dialog = ref();
    const showSlot = ref(props.show);
    watch(
      () => props.show,
      () => {
        if (props.show) {
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
      if (props.closeable) {
        emit("close");
      }
    };
    const closeOnEscape = (e) => {
      if (e.key === "Escape") {
        e.preventDefault();
        if (props.show) {
          close();
        }
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
      }, _attrs))}><div class="fixed inset-0 flex items-center justify-center bg-black/50 z-50" scroll-region><div class="fixed inset-0 transform transition-all" style="${ssrRenderStyle(__props.show ? null : { display: "none" })}"><div class="absolute inset-0 bg-gray-500 dark:bg-gray-950 opacity-75"></div></div><div class="${ssrRenderClass([maxWidthClass.value, "bg-white dark:bg-gray-800 rounded-2xl w-full p-8 relative shadow-lg"])}" style="${ssrRenderStyle(__props.show ? null : { display: "none" })}"><div class="flex justify-between items-center"><svg width="32" height="32" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path opacity="0.3" d="M23.0236 10.2725L21.3909 8.61547C21.277 8.50387 21.1871 8.37027 21.1265 8.22278C21.0659 8.0753 21.036 7.91702 21.0385 7.75759V5.40722C21.037 5.08522 20.9719 4.7667 20.8469 4.46995C20.722 4.1732 20.5397 3.90407 20.3105 3.67803C20.0813 3.45198 19.8097 3.27347 19.5113 3.15276C19.2129 3.03204 18.8937 2.97149 18.5718 2.97459H16.2227C16.0633 2.9771 15.9051 2.94714 15.7577 2.88653C15.6103 2.82592 15.4767 2.73593 15.3652 2.62203L13.7208 0.965024C13.2627 0.507176 12.6416 0.25 11.9941 0.25C11.3466 0.25 10.7256 0.507176 10.2675 0.965024L8.61128 2.59853C8.49974 2.71242 8.3662 2.80241 8.21879 2.86302C8.07137 2.92363 7.91318 2.95359 7.75382 2.95109H5.40463C5.0828 2.95262 4.76444 3.01776 4.46783 3.14276C4.17124 3.26776 3.90224 3.45016 3.67631 3.67948C3.45038 3.9088 3.27196 4.18052 3.1513 4.47904C3.03064 4.77756 2.97012 5.09698 2.97322 5.41897V7.76934C2.97573 7.92877 2.94578 8.08705 2.8852 8.23453C2.82462 8.38202 2.73468 8.51562 2.62084 8.62722L0.964666 10.2725C0.507047 10.7308 0.25 11.3522 0.25 12C0.25 12.6478 0.507047 13.2692 0.964666 13.7275L2.59735 15.3845C2.71119 15.4961 2.80113 15.6297 2.86171 15.7772C2.92229 15.9247 2.95224 16.083 2.94973 16.2424V18.5928C2.95126 18.9148 3.01637 19.2333 3.14131 19.5301C3.26625 19.8268 3.44855 20.0959 3.67776 20.322C3.90697 20.548 4.17855 20.7265 4.47692 20.8472C4.77529 20.968 5.09456 21.0285 5.41638 21.0254H7.76557C7.92492 21.0229 8.08312 21.0529 8.23053 21.1135C8.37794 21.1741 8.51148 21.2641 8.62302 21.378L10.2792 23.035C10.7373 23.4928 11.3583 23.75 12.0059 23.75C12.6534 23.75 13.2744 23.4928 13.7325 23.035L15.3769 21.4015C15.4885 21.2876 15.622 21.1976 15.7694 21.137C15.9168 21.0764 16.075 21.0464 16.2344 21.0489H18.5836C19.2316 21.0489 19.853 20.7914 20.3112 20.333C20.7693 19.8746 21.0267 19.2528 21.0267 18.6045V16.2542C21.0242 16.0947 21.0542 15.9365 21.1148 15.789C21.1753 15.6415 21.2653 15.5079 21.3791 15.3963L23.0353 13.7393C23.2629 13.5113 23.4432 13.2406 23.5658 12.9427C23.6885 12.6448 23.7511 12.3256 23.75 12.0034C23.7489 11.6812 23.6842 11.3625 23.5595 11.0654C23.4348 10.7683 23.2527 10.4989 23.0236 10.2725Z" fill="#F4A42B"></path><g transform="translate(10, 5)"><path d="M1.99316 10.3125C2.82117 10.3125 3.49295 10.9841 3.49316 11.8125C3.49316 12.6411 2.82131 13.3135 1.99316 13.3135C1.16523 13.3132 0.494141 12.6409 0.494141 11.8125C0.494359 10.9843 1.16537 10.3127 1.99316 10.3125ZM1.99316 0.6875C2.31131 0.6875 2.61683 0.813984 2.8418 1.03906C3.06668 1.26413 3.19336 1.56945 3.19336 1.8877V7.48047C3.19336 7.79869 3.06665 8.10404 2.8418 8.3291C2.61683 8.55418 2.31131 8.68066 1.99316 8.68066C1.67514 8.68062 1.37042 8.55405 1.14551 8.3291C0.920542 8.10402 0.793945 7.79878 0.793945 7.48047V1.8877C0.793945 1.56939 0.920542 1.26414 1.14551 1.03906C1.37042 0.814142 1.67516 0.687541 1.99316 0.6875Z" fill="#F4A42B"></path></g></svg><button class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"> ✕ </button></div><div class="my-7"><div class="text-lg font-bold text-gray-700 dark:text-gray-300"> Konfirmasi ${ssrInterpolate(__props.title)}</div><div class="text-gray-700 dark:text-gray-300">${ssrInterpolate(__props.question)} <span class="text-primary-500 font-semibold">${ssrInterpolate(__props.selected)}</span> ? </div></div><button class="bg-orange-400 hover:bg-orange-500 w-full flex items-center justify-center gap-2 rounded-lg py-2.5 text-white font-semibold transition">${ssrInterpolate(__props.confirmText)}</button></div></div></dialog>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/common/ConfirmModal.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  SearchIcon as S,
  _sfc_main as _
};

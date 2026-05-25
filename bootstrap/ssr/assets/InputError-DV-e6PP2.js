import { ssrRenderAttrs, ssrInterpolate } from "vue/server-renderer";
import { useSSRContext } from "vue";
const _sfc_main = {
  __name: "InputError",
  __ssrInlineRender: true,
  props: {
    message: [String, Array]
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      if (__props.message) {
        _push(`<div${ssrRenderAttrs(_attrs)}><p class="text-sm text-red-600 dark:text-red-400">${ssrInterpolate(Array.isArray(__props.message) ? __props.message[0] : __props.message)}</p></div>`);
      } else {
        _push(`<!---->`);
      }
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/InputError.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};

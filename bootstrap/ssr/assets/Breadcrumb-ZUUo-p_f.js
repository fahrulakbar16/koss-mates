import { mergeProps, unref, withCtx, createTextVNode, toDisplayString, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderList, ssrRenderComponent, ssrInterpolate, ssrRenderClass } from "vue/server-renderer";
import { l as link_default } from "../ssr.js";
const _sfc_main = {
  __name: "Breadcrumb",
  __ssrInlineRender: true,
  props: {
    items: {
      type: Array,
      default: () => []
    }
  },
  setup(__props) {
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<nav${ssrRenderAttrs(mergeProps({ class: "flex px-2 text-gray-500 dark:text-gray-300 text-sm sm:text-base space-x-1.5 font-semibold" }, _attrs))}><!--[-->`);
      ssrRenderList(__props.items, (item, index) => {
        _push(`<!--[-->`);
        if (index > 0) {
          _push(`<span>/</span>`);
        } else {
          _push(`<!---->`);
        }
        if (item.href) {
          _push(ssrRenderComponent(unref(link_default), {
            href: item.href,
            class: "hover:text-primary-500 hover:underline"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(`${ssrInterpolate(item.label)}`);
              } else {
                return [
                  createTextVNode(toDisplayString(item.label), 1)
                ];
              }
            }),
            _: 2
          }, _parent));
        } else {
          _push(`<span class="${ssrRenderClass([index === __props.items.length - 1 ? "text-primary-500" : ""])}">${ssrInterpolate(item.label)}</span>`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></nav>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/common/Breadcrumb.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};

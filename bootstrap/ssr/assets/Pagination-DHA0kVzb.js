import { computed, mergeProps, useSSRContext } from "vue";
import { ssrRenderAttrs, ssrRenderAttr, ssrIncludeBooleanAttr, ssrRenderList, ssrInterpolate, ssrRenderClass } from "vue/server-renderer";
import "../ssr.js";
const _sfc_main = {
  __name: "Pagination",
  __ssrInlineRender: true,
  props: {
    pagination: {
      type: Object,
      required: true
    },
    perPageOptions: {
      type: Array,
      default: () => [10, 15, 25, 50, 100]
    },
    clientSide: {
      type: Boolean,
      default: false
    }
  },
  emits: ["page-changed", "per-page-changed"],
  setup(__props, { emit: __emit }) {
    const props = __props;
    const currentPerPage = computed(() => {
      return props.pagination.per_page || 10;
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<div${ssrRenderAttrs(mergeProps({ class: "flex justify-between items-center px-5 py-3" }, _attrs))}><div class="flex flex-1 justify-between sm:hidden"><button${ssrIncludeBooleanAttr(!__props.pagination.prev_page_url) ? " disabled" : ""} class="inline-flex relative items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 hover:bg-gray-50 disabled:opacity-50"> Sebelumnya </button><button${ssrIncludeBooleanAttr(!__props.pagination.next_page_url) ? " disabled" : ""} class="inline-flex relative items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 hover:bg-gray-50 disabled:opacity-50"> Berikutnya </button></div><div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between"><div class="flex gap-4 items-center"><div class="flex gap-2 items-center"><label for="per-page" class="text-sm text-gray-700 whitespace-nowrap">Tampilkan</label><select id="per-page"${ssrRenderAttr("value", currentPerPage.value)} class="px-3 py-1.5 text-sm bg-white rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"><!--[-->`);
      ssrRenderList(__props.perPageOptions, (option) => {
        _push(`<option${ssrRenderAttr("value", option)}>${ssrInterpolate(option)}</option>`);
      });
      _push(`<!--]--></select><span class="text-sm text-gray-700 whitespace-nowrap">per halaman</span></div></div><div><nav class="inline-flex isolate -space-x-px rounded-md" aria-label="Pagination"><!--[-->`);
      ssrRenderList(__props.pagination.links, (link, i) => {
        _push(`<!--[-->`);
        if (!link.url) {
          _push(`<span class="inline-flex relative items-center px-4 py-2 text-sm font-semibold text-gray-400">${link.label ?? ""}</span>`);
        } else {
          _push(`<button class="${ssrRenderClass([
            "relative inline-flex items-center rounded px-4 py-2 text-sm font-semibold focus:z-20",
            link.active ? "bg-primary-500 text-white" : "text-gray-700 border border-gray-300 hover:bg-gray-50"
          ])}">${link.label ?? ""}</button>`);
        }
        _push(`<!--]-->`);
      });
      _push(`<!--]--></nav></div></div></div>`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Components/common/Pagination.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as _
};

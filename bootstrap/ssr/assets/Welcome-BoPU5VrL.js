import { ref, onMounted, onUnmounted, computed, unref, withCtx, createVNode, resolveDynamicComponent, createBlock, openBlock, createTextVNode, useSSRContext } from "vue";
import { ssrRenderComponent, ssrRenderAttr, ssrRenderVNode, ssrRenderClass, ssrInterpolate, ssrRenderAttrs, ssrRenderList, ssrRenderStyle } from "vue/server-renderer";
import { h as head_default, l as link_default } from "../ssr.js";
import { _ as _export_sfc } from "./_plugin-vue_export-helper-1tPrXgE0.js";
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
const seoTitle = "Tharahub - Temukan Kos Ideal Anda dengan Mudah";
const seoDescription = "Platform modern untuk mencari, mengelola, dan menyewa kos di seluruh Indonesia. Temukan kos impian Anda dengan fitur lengkap, foto detail, lokasi strategis, dan harga transparan. Terpercaya & Aman.";
const seoKeywords = "kos, kost, boarding house, sewa kos, cari kos, kos murah, kos dekat kampus, kos strategis, platform kos, kos indonesia, sewa kamar, kos terpercaya";
const _sfc_main = {
  __name: "Welcome",
  __ssrInlineRender: true,
  props: {
    canLogin: {
      type: Boolean
    },
    canRegister: {
      type: Boolean
    },
    laravelVersion: {
      type: String,
      required: true
    },
    phpVersion: {
      type: String,
      required: true
    },
    featuredBoardingHouse: {
      type: Object,
      default: null
    },
    recommendedBoardingHouses: {
      type: Array,
      default: () => []
    }
  },
  setup(__props) {
    const props = __props;
    const searchQuery = ref("");
    const activeFilter = ref("semua");
    const isScrolled = ref(false);
    const mobileMenuOpen = ref(false);
    let scrollObserver = null;
    onMounted(() => {
      const handleScroll = () => {
        isScrolled.value = window.scrollY > 20;
      };
      window.addEventListener("scroll", handleScroll);
      setupScrollAnimations();
      return () => {
        window.removeEventListener("scroll", handleScroll);
        if (scrollObserver) {
          scrollObserver.disconnect();
        }
      };
    });
    onUnmounted(() => {
      if (scrollObserver) {
        scrollObserver.disconnect();
      }
    });
    const setupScrollAnimations = () => {
      const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -100px 0px"
      };
      scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            entry.target.classList.add("animate-on-scroll");
            scrollObserver.unobserve(entry.target);
          }
        });
      }, observerOptions);
      setTimeout(() => {
        const elements = document.querySelectorAll(".scroll-reveal");
        elements.forEach((el) => {
          if (el && !el.classList.contains("animate-on-scroll")) {
            scrollObserver.observe(el);
          }
        });
      }, 200);
    };
    const closeMobileMenu = () => {
      mobileMenuOpen.value = false;
    };
    const filteredBoardingHouses = computed(() => {
      let filtered = [...props.recommendedBoardingHouses];
      if (activeFilter.value === "termurah") {
        filtered.sort((a, b) => (a.min_price || 0) - (b.min_price || 0));
      } else if (activeFilter.value === "terpopuler") {
        filtered.sort((a, b) => (b.rooms_count || 0) - (a.rooms_count || 0));
      }
      return filtered;
    });
    const formatPrice = (price) => {
      if (!price || price === 0) return "Harga belum tersedia";
      return new Intl.NumberFormat("id-ID", {
        style: "currency",
        currency: "IDR",
        minimumFractionDigits: 0
      }).format(price);
    };
    const getImageUrl = (boardingHouse) => {
      return boardingHouse.first_image || boardingHouse.thumbnail || "/images/placeholder.png";
    };
    const siteUrl = computed(() => {
      if (typeof window !== "undefined") {
        return window.location.origin;
      }
      return "https://Tharahub.com";
    });
    const seoImage = computed(() => {
      if (props.featuredBoardingHouse?.hero_image) {
        return props.featuredBoardingHouse.hero_image;
      }
      return `${siteUrl.value}/images/placeholder.png`;
    });
    const websiteStructuredData = computed(() => {
      return JSON.stringify({
        "@context": "https://schema.org",
        "@type": "WebSite",
        name: "Tharahub",
        url: siteUrl.value,
        description: seoDescription,
        potentialAction: {
          "@type": "SearchAction",
          target: {
            "@type": "EntryPoint",
            urlTemplate: `${siteUrl.value}/search?q={search_term_string}`
          },
          "query-input": "required name=search_term_string"
        }
      });
    });
    const organizationStructuredData = computed(() => {
      return JSON.stringify({
        "@context": "https://schema.org",
        "@type": "Organization",
        name: "Tharahub",
        url: siteUrl.value,
        logo: `${siteUrl.value}/images/logo/Tharahub-logo.png`,
        description: seoDescription,
        sameAs: [
          "https://www.facebook.com/Tharahub",
          "https://www.instagram.com/Tharahub",
          "https://twitter.com/Tharahub"
        ],
        contactPoint: {
          "@type": "ContactPoint",
          contactType: "Customer Service",
          availableLanguage: "Indonesian"
        }
      });
    });
    return (_ctx, _push, _parent, _attrs) => {
      _push(`<!--[-->`);
      _push(ssrRenderComponent(unref(head_default), { title: seoTitle }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<meta name="title"${ssrRenderAttr("content", seoTitle)} data-v-b4fbfa7e${_scopeId}><meta name="description"${ssrRenderAttr("content", seoDescription)} data-v-b4fbfa7e${_scopeId}><meta name="keywords"${ssrRenderAttr("content", seoKeywords)} data-v-b4fbfa7e${_scopeId}><meta name="author" content="Tharahub" data-v-b4fbfa7e${_scopeId}><meta name="robots" content="index, follow" data-v-b4fbfa7e${_scopeId}><meta name="language" content="Indonesian" data-v-b4fbfa7e${_scopeId}><meta name="revisit-after" content="7 days" data-v-b4fbfa7e${_scopeId}><link rel="canonical"${ssrRenderAttr("href", siteUrl.value)} data-v-b4fbfa7e${_scopeId}><meta property="og:type" content="website" data-v-b4fbfa7e${_scopeId}><meta property="og:url"${ssrRenderAttr("content", siteUrl.value)} data-v-b4fbfa7e${_scopeId}><meta property="og:title"${ssrRenderAttr("content", seoTitle)} data-v-b4fbfa7e${_scopeId}><meta property="og:description"${ssrRenderAttr("content", seoDescription)} data-v-b4fbfa7e${_scopeId}><meta property="og:image"${ssrRenderAttr("content", seoImage.value)} data-v-b4fbfa7e${_scopeId}><meta property="og:image:width" content="1200" data-v-b4fbfa7e${_scopeId}><meta property="og:image:height" content="630" data-v-b4fbfa7e${_scopeId}><meta property="og:image:alt" content="Tharahub - Platform Pencarian Kos Terpercaya" data-v-b4fbfa7e${_scopeId}><meta property="og:site_name" content="Tharahub" data-v-b4fbfa7e${_scopeId}><meta property="og:locale" content="id_ID" data-v-b4fbfa7e${_scopeId}><meta name="twitter:card" content="summary_large_image" data-v-b4fbfa7e${_scopeId}><meta name="twitter:url"${ssrRenderAttr("content", siteUrl.value)} data-v-b4fbfa7e${_scopeId}><meta name="twitter:title"${ssrRenderAttr("content", seoTitle)} data-v-b4fbfa7e${_scopeId}><meta name="twitter:description"${ssrRenderAttr("content", seoDescription)} data-v-b4fbfa7e${_scopeId}><meta name="twitter:image"${ssrRenderAttr("content", seoImage.value)} data-v-b4fbfa7e${_scopeId}><meta name="twitter:image:alt" content="Tharahub - Platform Pencarian Kos Terpercaya" data-v-b4fbfa7e${_scopeId}><meta name="theme-color" content="#fa5252" data-v-b4fbfa7e${_scopeId}><meta name="apple-mobile-web-app-capable" content="yes" data-v-b4fbfa7e${_scopeId}><meta name="apple-mobile-web-app-status-bar-style" content="black-translucent" data-v-b4fbfa7e${_scopeId}><meta name="format-detection" content="telephone=no" data-v-b4fbfa7e${_scopeId}>`);
            ssrRenderVNode(_push2, createVNode(resolveDynamicComponent("script"), { type: "application/ld+json" }, null), _parent2, _scopeId);
            ssrRenderVNode(_push2, createVNode(resolveDynamicComponent("script"), { type: "application/ld+json" }, null), _parent2, _scopeId);
          } else {
            return [
              createVNode("meta", {
                name: "title",
                content: seoTitle
              }),
              createVNode("meta", {
                name: "description",
                content: seoDescription
              }),
              createVNode("meta", {
                name: "keywords",
                content: seoKeywords
              }),
              createVNode("meta", {
                name: "author",
                content: "Tharahub"
              }),
              createVNode("meta", {
                name: "robots",
                content: "index, follow"
              }),
              createVNode("meta", {
                name: "language",
                content: "Indonesian"
              }),
              createVNode("meta", {
                name: "revisit-after",
                content: "7 days"
              }),
              createVNode("link", {
                rel: "canonical",
                href: siteUrl.value
              }, null, 8, ["href"]),
              createVNode("meta", {
                property: "og:type",
                content: "website"
              }),
              createVNode("meta", {
                property: "og:url",
                content: siteUrl.value
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:title",
                content: seoTitle
              }),
              createVNode("meta", {
                property: "og:description",
                content: seoDescription
              }),
              createVNode("meta", {
                property: "og:image",
                content: seoImage.value
              }, null, 8, ["content"]),
              createVNode("meta", {
                property: "og:image:width",
                content: "1200"
              }),
              createVNode("meta", {
                property: "og:image:height",
                content: "630"
              }),
              createVNode("meta", {
                property: "og:image:alt",
                content: "Tharahub - Platform Pencarian Kos Terpercaya"
              }),
              createVNode("meta", {
                property: "og:site_name",
                content: "Tharahub"
              }),
              createVNode("meta", {
                property: "og:locale",
                content: "id_ID"
              }),
              createVNode("meta", {
                name: "twitter:card",
                content: "summary_large_image"
              }),
              createVNode("meta", {
                name: "twitter:url",
                content: siteUrl.value
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:title",
                content: seoTitle
              }),
              createVNode("meta", {
                name: "twitter:description",
                content: seoDescription
              }),
              createVNode("meta", {
                name: "twitter:image",
                content: seoImage.value
              }, null, 8, ["content"]),
              createVNode("meta", {
                name: "twitter:image:alt",
                content: "Tharahub - Platform Pencarian Kos Terpercaya"
              }),
              createVNode("meta", {
                name: "theme-color",
                content: "#fa5252"
              }),
              createVNode("meta", {
                name: "apple-mobile-web-app-capable",
                content: "yes"
              }),
              createVNode("meta", {
                name: "apple-mobile-web-app-status-bar-style",
                content: "black-translucent"
              }),
              createVNode("meta", {
                name: "format-detection",
                content: "telephone=no"
              }),
              (openBlock(), createBlock(resolveDynamicComponent("script"), {
                type: "application/ld+json",
                innerHTML: websiteStructuredData.value
              }, null, 8, ["innerHTML"])),
              (openBlock(), createBlock(resolveDynamicComponent("script"), {
                type: "application/ld+json",
                innerHTML: organizationStructuredData.value
              }, null, 8, ["innerHTML"]))
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<div class="min-h-screen bg-gray-50 dark:bg-gray-900 scroll-smooth" data-v-b4fbfa7e><nav class="${ssrRenderClass([
        "sticky top-0 z-50 border-b transition-all duration-300",
        isScrolled.value ? "bg-white/95 dark:bg-gray-900/95 backdrop-blur-md shadow-md" : "bg-white dark:bg-gray-900 border-gray-200 dark:border-gray-800"
      ])}" data-v-b4fbfa7e><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="flex justify-between items-center h-16" data-v-b4fbfa7e>`);
      _push(ssrRenderComponent(unref(link_default), {
        href: "/",
        class: "flex items-center gap-2 animate-fade-in hover:opacity-80 transition-opacity"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(`<div class="w-8 h-8 bg-primary-600 rounded flex items-center justify-center transform transition-transform duration-300 hover:scale-110 hover:rotate-12" data-v-b4fbfa7e${_scopeId}><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e${_scopeId}><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" data-v-b4fbfa7e${_scopeId}></path></svg></div><h1 class="text-xl font-bold text-gray-900 dark:text-white" data-v-b4fbfa7e${_scopeId}>Tharahub</h1>`);
          } else {
            return [
              createVNode("div", { class: "w-8 h-8 bg-primary-600 rounded flex items-center justify-center transform transition-transform duration-300 hover:scale-110 hover:rotate-12" }, [
                (openBlock(), createBlock("svg", {
                  class: "w-5 h-5 text-white",
                  fill: "none",
                  stroke: "currentColor",
                  viewBox: "0 0 24 24"
                }, [
                  createVNode("path", {
                    "stroke-linecap": "round",
                    "stroke-linejoin": "round",
                    "stroke-width": "2",
                    d: "M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                  })
                ]))
              ]),
              createVNode("h1", { class: "text-xl font-bold text-gray-900 dark:text-white" }, "Tharahub")
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<div class="hidden md:flex items-center gap-8" data-v-b4fbfa7e>`);
      _push(ssrRenderComponent(unref(link_default), {
        href: "/",
        class: "text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 relative group py-2"
      }, {
        default: withCtx((_, _push2, _parent2, _scopeId) => {
          if (_push2) {
            _push2(` Home <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-300 group-hover:w-full" data-v-b4fbfa7e${_scopeId}></span>`);
          } else {
            return [
              createTextVNode(" Home "),
              createVNode("span", { class: "absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-300 group-hover:w-full" })
            ];
          }
        }),
        _: 1
      }, _parent));
      _push(`<a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 relative group py-2" data-v-b4fbfa7e> Fitur <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-300 group-hover:w-full" data-v-b4fbfa7e></span></a><a href="#how-it-works" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 relative group py-2" data-v-b4fbfa7e> Cara Kerja <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-300 group-hover:w-full" data-v-b4fbfa7e></span></a><a href="#faq" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-all duration-300 relative group py-2" data-v-b4fbfa7e> FAQ <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-600 transition-all duration-300 group-hover:w-full" data-v-b4fbfa7e></span></a></div><div class="hidden md:flex items-center gap-3" data-v-b4fbfa7e>`);
      if (__props.canLogin && !_ctx.$page.props.auth?.user) {
        _push(ssrRenderComponent(unref(link_default), {
          href: _ctx.route("login"),
          class: "px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Masuk `);
            } else {
              return [
                createTextVNode(" Masuk ")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      if (__props.canRegister && !_ctx.$page.props.auth?.user) {
        _push(ssrRenderComponent(unref(link_default), {
          href: _ctx.route("register"),
          class: "px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Daftar `);
            } else {
              return [
                createTextVNode(" Daftar ")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      if (_ctx.$page.props.auth?.user) {
        _push(ssrRenderComponent(unref(link_default), {
          href: _ctx.route("dashboard"),
          class: "px-4 py-2 text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Dashboard `);
            } else {
              return [
                createTextVNode(" Dashboard ")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div><button class="md:hidden p-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" aria-label="Toggle menu" data-v-b4fbfa7e>`);
      if (!mobileMenuOpen.value) {
        _push(`<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" data-v-b4fbfa7e></path></svg>`);
      } else {
        _push(`<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" data-v-b4fbfa7e></path></svg>`);
      }
      _push(`</button></div>`);
      if (mobileMenuOpen.value) {
        _push(`<div class="md:hidden py-4 border-t border-gray-200 dark:border-gray-700" data-v-b4fbfa7e><div class="flex flex-col space-y-4" data-v-b4fbfa7e>`);
        _push(ssrRenderComponent(unref(link_default), {
          href: "/",
          onClick: closeMobileMenu,
          class: "px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Home `);
            } else {
              return [
                createTextVNode(" Home ")
              ];
            }
          }),
          _: 1
        }, _parent));
        _push(`<a href="#features" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" data-v-b4fbfa7e> Fitur </a><a href="#how-it-works" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" data-v-b4fbfa7e> Cara Kerja </a><a href="#faq" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 hover:bg-gray-50 dark:hover:bg-gray-800 rounded-lg transition-colors" data-v-b4fbfa7e> FAQ </a><div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2" data-v-b4fbfa7e>`);
        if (__props.canLogin && !_ctx.$page.props.auth?.user) {
          _push(ssrRenderComponent(unref(link_default), {
            href: _ctx.route("login"),
            onClick: closeMobileMenu,
            class: "block px-4 py-2 text-center text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` Masuk `);
              } else {
                return [
                  createTextVNode(" Masuk ")
                ];
              }
            }),
            _: 1
          }, _parent));
        } else {
          _push(`<!---->`);
        }
        if (__props.canRegister && !_ctx.$page.props.auth?.user) {
          _push(ssrRenderComponent(unref(link_default), {
            href: _ctx.route("register"),
            onClick: closeMobileMenu,
            class: "block px-4 py-2 text-center text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` Daftar `);
              } else {
                return [
                  createTextVNode(" Daftar ")
                ];
              }
            }),
            _: 1
          }, _parent));
        } else {
          _push(`<!---->`);
        }
        if (_ctx.$page.props.auth?.user) {
          _push(ssrRenderComponent(unref(link_default), {
            href: _ctx.route("dashboard"),
            onClick: closeMobileMenu,
            class: "block px-4 py-2 text-center text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 rounded-lg transition-colors"
          }, {
            default: withCtx((_, _push2, _parent2, _scopeId) => {
              if (_push2) {
                _push2(` Dashboard `);
              } else {
                return [
                  createTextVNode(" Dashboard ")
                ];
              }
            }),
            _: 1
          }, _parent));
        } else {
          _push(`<!---->`);
        }
        _push(`</div></div></div>`);
      } else {
        _push(`<!---->`);
      }
      _push(`</div></nav><section class="relative bg-white dark:bg-gray-900 py-12 overflow-hidden" data-v-b4fbfa7e><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="grid lg:grid-cols-2 gap-8 items-center" data-v-b4fbfa7e><div class="animate-slide-in-left" data-v-b4fbfa7e><h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-gray-900 dark:text-white mb-4" data-v-b4fbfa7e> Temukan Kos Ideal Anda dengan <span class="text-primary-600 inline-block animate-pulse-slow" data-v-b4fbfa7e>Mudah</span></h1><p class="text-lg text-gray-600 dark:text-gray-400 mb-8 animate-fade-in-delay" data-v-b4fbfa7e> Platform modern untuk mencari, mengelola, dan menyewa kos di seluruh Indonesia dengan aman dan terpercaya. </p><div class="flex gap-2 animate-fade-in-delay-2" data-v-b4fbfa7e><div class="flex-1 relative group" data-v-b4fbfa7e><input${ssrRenderAttr("value", searchQuery.value)} type="text" placeholder="Cari area, kota, atau nama kos" class="w-full px-4 py-3 pl-12 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:text-white transition-all duration-300 group-hover:border-primary-400" data-v-b4fbfa7e><svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 transition-colors duration-300 group-focus-within:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" data-v-b4fbfa7e></path></svg></div><button class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg transition-all duration-300 transform hover:scale-105 hover:shadow-lg active:scale-95 animate-pulse-slow" data-v-b4fbfa7e> Cari </button></div></div><div class="relative animate-slide-in-right" data-v-b4fbfa7e>`);
      if (__props.featuredBoardingHouse) {
        _push(`<div class="relative rounded-2xl overflow-hidden shadow-2xl group" data-v-b4fbfa7e><img${ssrRenderAttr("src", __props.featuredBoardingHouse.hero_image)}${ssrRenderAttr("alt", __props.featuredBoardingHouse.name)} class="w-full h-[400px] object-cover transition-transform duration-700 group-hover:scale-110" data-v-b4fbfa7e><div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black/80 to-transparent p-6 transform transition-transform duration-300 group-hover:translate-y-0" data-v-b4fbfa7e><h3 class="text-white text-xl font-bold mb-1 transform transition-all duration-300 group-hover:translate-x-2" data-v-b4fbfa7e>${ssrInterpolate(__props.featuredBoardingHouse.name)}</h3><p class="text-white/90 transform transition-all duration-300 group-hover:translate-x-2" data-v-b4fbfa7e>${ssrInterpolate(__props.featuredBoardingHouse.cluster || __props.featuredBoardingHouse.address)}</p></div></div>`);
      } else {
        _push(`<div class="relative rounded-2xl overflow-hidden shadow-2xl bg-gray-200 dark:bg-gray-800 h-[400px] flex items-center justify-center" data-v-b4fbfa7e><p class="text-gray-500 dark:text-gray-400" data-v-b4fbfa7e>Gambar kos tidak tersedia</p></div>`);
      }
      _push(`</div></div></div></section><section class="py-12 bg-gray-50 dark:bg-gray-800" data-v-b4fbfa7e><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8" data-v-b4fbfa7e><h2 class="text-3xl font-bold text-gray-900 dark:text-white scroll-reveal" data-v-b4fbfa7e>Rekomendasi Kos Untukmu</h2><div class="flex gap-2 flex-wrap scroll-reveal" data-v-b4fbfa7e><button class="${ssrRenderClass([
        "px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform",
        activeFilter.value === "semua" ? "bg-primary-600 text-white scale-105 shadow-lg" : "bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:scale-105 active:scale-95"
      ])}" data-v-b4fbfa7e> Semua </button><button class="${ssrRenderClass([
        "px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform",
        activeFilter.value === "terdekat" ? "bg-primary-600 text-white scale-105 shadow-lg" : "bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:scale-105 active:scale-95"
      ])}" data-v-b4fbfa7e> Terdekat </button><button class="${ssrRenderClass([
        "px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform",
        activeFilter.value === "termurah" ? "bg-primary-600 text-white scale-105 shadow-lg" : "bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:scale-105 active:scale-95"
      ])}" data-v-b4fbfa7e> Termurah </button><button class="${ssrRenderClass([
        "px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 transform",
        activeFilter.value === "terpopuler" ? "bg-primary-600 text-white scale-105 shadow-lg" : "bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 hover:scale-105 active:scale-95"
      ])}" data-v-b4fbfa7e> Terpopuler </button></div></div>`);
      if (filteredBoardingHouses.value.length > 0) {
        _push(`<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6" data-v-b4fbfa7e><div${ssrRenderAttrs({
          name: "kos-list",
          class: "contents"
        })} data-v-b4fbfa7e>`);
        ssrRenderList(filteredBoardingHouses.value, (kos, index) => {
          _push(`<div style="${ssrRenderStyle({ "--delay": index * 0.1 + "s" })}" class="bg-white dark:bg-gray-900 rounded-xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 cursor-pointer transform hover:-translate-y-2 animate-fade-in-up" data-v-b4fbfa7e><div class="relative h-48 bg-gray-200 dark:bg-gray-800 overflow-hidden" data-v-b4fbfa7e><img${ssrRenderAttr("src", getImageUrl(kos))}${ssrRenderAttr("alt", kos.name)} class="w-full h-full object-cover transition-transform duration-500 hover:scale-110" data-v-b4fbfa7e><div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 hover:opacity-100 transition-opacity duration-300" data-v-b4fbfa7e></div></div><div class="p-4" data-v-b4fbfa7e><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2 line-clamp-1" data-v-b4fbfa7e>${ssrInterpolate(kos.name)}</h3><div class="flex items-center gap-1 text-gray-600 dark:text-gray-400 mb-3" data-v-b4fbfa7e><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" data-v-b4fbfa7e></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" data-v-b4fbfa7e></path></svg><span class="text-sm" data-v-b4fbfa7e>${ssrInterpolate(kos.cluster || kos.address)}</span></div><div class="text-primary-600 font-bold text-lg" data-v-b4fbfa7e>${ssrInterpolate(formatPrice(kos.min_price))}<span class="text-sm font-normal text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> /bulan</span></div></div></div>`);
        });
        _push(`</div></div>`);
      } else {
        _push(`<div class="text-center py-12" data-v-b4fbfa7e><p class="text-gray-500 dark:text-gray-400" data-v-b4fbfa7e>Tidak ada kos yang tersedia saat ini.</p></div>`);
      }
      _push(`</div></section><section class="py-16 bg-primary-600 text-white" data-v-b4fbfa7e><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center" data-v-b4fbfa7e><div class="scroll-reveal" style="${ssrRenderStyle({ "--delay": "0s" })}" data-v-b4fbfa7e><div class="text-4xl md:text-5xl font-bold mb-2" data-v-b4fbfa7e>${ssrInterpolate(__props.recommendedBoardingHouses.length)}+</div><div class="text-primary-100 text-sm md:text-base" data-v-b4fbfa7e>Kos Tersedia</div></div><div class="scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.1s" })}" data-v-b4fbfa7e><div class="text-4xl md:text-5xl font-bold mb-2" data-v-b4fbfa7e>50+</div><div class="text-primary-100 text-sm md:text-base" data-v-b4fbfa7e>Lokasi Strategis</div></div><div class="scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.2s" })}" data-v-b4fbfa7e><div class="text-4xl md:text-5xl font-bold mb-2" data-v-b4fbfa7e>2,500+</div><div class="text-primary-100 text-sm md:text-base" data-v-b4fbfa7e>Penyewa Puas</div></div><div class="scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.3s" })}" data-v-b4fbfa7e><div class="text-4xl md:text-5xl font-bold mb-2" data-v-b4fbfa7e>98%</div><div class="text-primary-100 text-sm md:text-base" data-v-b4fbfa7e>Kepuasan Pengguna</div></div></div></div></section><section id="features" class="py-16 bg-white dark:bg-gray-900 scroll-mt-16" data-v-b4fbfa7e><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="text-center mb-12 scroll-reveal" data-v-b4fbfa7e><h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4" data-v-b4fbfa7e> Mengapa Pilih <span class="text-primary-600" data-v-b4fbfa7e>Tharahub?</span></h2><p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" data-v-b4fbfa7e> Platform terpercaya yang memudahkan Anda menemukan kos impian dengan fitur lengkap dan terpercaya </p></div><div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8" data-v-b4fbfa7e><div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0s" })}" data-v-b4fbfa7e><div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4" data-v-b4fbfa7e><svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" data-v-b4fbfa7e></path></svg></div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Pencarian Cepat &amp; Mudah</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Cari kos berdasarkan lokasi, harga, dan fasilitas dengan filter yang lengkap. Temukan kos impian Anda dalam hitungan menit. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.1s" })}" data-v-b4fbfa7e><div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4" data-v-b4fbfa7e><svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" data-v-b4fbfa7e></path></svg></div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Foto &amp; Detail Lengkap</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Lihat foto kamar, fasilitas, dan informasi detail lengkap sebelum memutuskan. Semua informasi tersedia secara transparan. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.2s" })}" data-v-b4fbfa7e><div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4" data-v-b4fbfa7e><svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" data-v-b4fbfa7e></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" data-v-b4fbfa7e></path></svg></div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Lokasi Strategis</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Temukan kos di lokasi strategis dekat kampus, kantor, atau pusat kota. Peta interaktif membantu Anda menemukan lokasi terbaik. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.3s" })}" data-v-b4fbfa7e><div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4" data-v-b4fbfa7e><svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" data-v-b4fbfa7e></path></svg></div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Harga Transparan</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Lihat harga sewa yang jelas dan transparan tanpa biaya tersembunyi. Bandingkan harga dengan mudah untuk mendapatkan yang terbaik. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.4s" })}" data-v-b4fbfa7e><div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4" data-v-b4fbfa7e><svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" data-v-b4fbfa7e></path></svg></div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Terpercaya &amp; Aman</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Semua kos telah diverifikasi dan terpercaya. Data pribadi Anda aman dengan sistem keamanan berlapis dan enkripsi. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-xl p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.5s" })}" data-v-b4fbfa7e><div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mb-4" data-v-b4fbfa7e><svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" data-v-b4fbfa7e></path></svg></div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Dukungan 24/7</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Tim support siap membantu Anda kapan saja. Dapatkan bantuan konsultasi gratis untuk menemukan kos yang sesuai kebutuhan. </p></div></div></div></section><section id="how-it-works" class="py-16 bg-gray-50 dark:bg-gray-800 scroll-mt-16" data-v-b4fbfa7e><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="text-center mb-12 scroll-reveal" data-v-b4fbfa7e><h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4" data-v-b4fbfa7e> Cara Kerja <span class="text-primary-600" data-v-b4fbfa7e>Tharahub</span></h2><p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto" data-v-b4fbfa7e> Temukan kos impian Anda hanya dalam 3 langkah mudah </p></div><div class="grid md:grid-cols-3 gap-8" data-v-b4fbfa7e><div class="text-center scroll-reveal" style="${ssrRenderStyle({ "--delay": "0s" })}" data-v-b4fbfa7e><div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold" data-v-b4fbfa7e> 1 </div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Cari Kos</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Gunakan search bar atau filter untuk menemukan kos sesuai lokasi, harga, dan fasilitas yang Anda inginkan. </p></div><div class="text-center scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.2s" })}" data-v-b4fbfa7e><div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold" data-v-b4fbfa7e> 2 </div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Lihat Detail</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Lihat foto, fasilitas, lokasi, dan informasi lengkap kos. Bandingkan beberapa pilihan untuk mendapatkan yang terbaik. </p></div><div class="text-center scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.4s" })}" data-v-b4fbfa7e><div class="w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center mx-auto mb-4 text-white text-2xl font-bold" data-v-b4fbfa7e> 3 </div><h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e>Hubungi Pemilik</h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Hubungi pemilik kos langsung melalui kontak yang tersedia. Lakukan kunjungan dan booking kos impian Anda. </p></div></div></div></section><section id="faq" class="py-16 bg-white dark:bg-gray-900 scroll-mt-16" data-v-b4fbfa7e><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="text-center mb-12 scroll-reveal" data-v-b4fbfa7e><h2 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4" data-v-b4fbfa7e> Pertanyaan yang Sering Diajukan </h2><p class="text-lg text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Temukan jawaban untuk pertanyaan umum tentang Tharahub </p></div><div class="space-y-4" data-v-b4fbfa7e><div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0s" })}" data-v-b4fbfa7e><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e> Apakah menggunakan Tharahub gratis? </h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Ya, menggunakan Tharahub untuk mencari dan melihat informasi kos adalah gratis. Tidak ada biaya tersembunyi untuk pengguna. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.1s" })}" data-v-b4fbfa7e><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e> Bagaimana cara memastikan kos yang ditampilkan terpercaya? </h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Semua kos di Tharahub telah melalui proses verifikasi. Kami memastikan informasi yang ditampilkan akurat dan terpercaya. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.2s" })}" data-v-b4fbfa7e><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e> Apakah saya bisa melihat lokasi kos di peta? </h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Ya, setiap kos memiliki informasi lokasi yang lengkap. Anda dapat melihat lokasi kos di peta interaktif untuk memastikan lokasinya strategis. </p></div><div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 scroll-reveal" style="${ssrRenderStyle({ "--delay": "0.3s" })}" data-v-b4fbfa7e><h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2" data-v-b4fbfa7e> Bagaimana cara menghubungi pemilik kos? </h3><p class="text-gray-600 dark:text-gray-400" data-v-b4fbfa7e> Setelah menemukan kos yang sesuai, Anda dapat menghubungi pemilik kos langsung melalui nomor telepon atau kontak yang tersedia di halaman detail kos. </p></div></div></div></section><section class="py-16 bg-gradient-to-r from-primary-600 to-primary-700 text-white" data-v-b4fbfa7e><div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center" data-v-b4fbfa7e><div class="scroll-reveal" data-v-b4fbfa7e><h2 class="text-3xl md:text-4xl font-bold mb-4" data-v-b4fbfa7e> Siap Mencari Kos Impian? </h2><p class="text-xl text-primary-100 mb-8" data-v-b4fbfa7e> Bergabunglah dengan ribuan penyewa yang sudah menemukan kos terbaik melalui Tharahub. Mulai pencarian Anda sekarang! </p></div><div class="flex flex-col sm:flex-row gap-4 justify-center" data-v-b4fbfa7e>`);
      if (__props.canRegister) {
        _push(ssrRenderComponent(unref(link_default), {
          href: _ctx.route("register"),
          class: "px-8 py-4 text-lg font-semibold bg-white text-primary-600 rounded-xl transition-all duration-300 transform hover:scale-105 hover:shadow-xl"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Daftar Sekarang `);
            } else {
              return [
                createTextVNode(" Daftar Sekarang ")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      if (__props.canLogin) {
        _push(ssrRenderComponent(unref(link_default), {
          href: _ctx.route("login"),
          class: "px-8 py-4 text-lg font-semibold bg-white/10 hover:bg-white/20 text-white rounded-xl transition-all duration-300 border-2 border-white/30"
        }, {
          default: withCtx((_, _push2, _parent2, _scopeId) => {
            if (_push2) {
              _push2(` Masuk ke Akun `);
            } else {
              return [
                createTextVNode(" Masuk ke Akun ")
              ];
            }
          }),
          _: 1
        }, _parent));
      } else {
        _push(`<!---->`);
      }
      _push(`</div></div></section><footer class="bg-gray-900 text-gray-400 py-12" data-v-b4fbfa7e><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8" data-v-b4fbfa7e><div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8" data-v-b4fbfa7e><div data-v-b4fbfa7e><div class="flex items-center gap-2 mb-4" data-v-b4fbfa7e><div class="w-8 h-8 bg-primary-600 rounded flex items-center justify-center" data-v-b4fbfa7e><svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" data-v-b4fbfa7e><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" data-v-b4fbfa7e></path></svg></div><h3 class="text-xl font-bold text-white" data-v-b4fbfa7e>Tharahub</h3></div><p class="text-sm mb-4" data-v-b4fbfa7e> Platform terpercaya untuk menemukan kos terbaik. Temukan tempat tinggal impian Anda dengan mudah dan cepat. </p></div><div data-v-b4fbfa7e><h4 class="text-white font-semibold mb-4" data-v-b4fbfa7e>Temukan Kos</h4><ul class="space-y-2 text-sm" data-v-b4fbfa7e><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Cari Kos</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Kos Populer</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Lokasi Strategis</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Harga Terjangkau</a></li></ul></div><div data-v-b4fbfa7e><h4 class="text-white font-semibold mb-4" data-v-b4fbfa7e>Bantuan</h4><ul class="space-y-2 text-sm" data-v-b4fbfa7e><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Cara Mencari Kos</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>FAQ</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Kontak Kami</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Panduan Pengguna</a></li></ul></div><div data-v-b4fbfa7e><h4 class="text-white font-semibold mb-4" data-v-b4fbfa7e>Informasi</h4><ul class="space-y-2 text-sm" data-v-b4fbfa7e><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Tentang Kami</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Kebijakan Privasi</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Syarat &amp; Ketentuan</a></li><li data-v-b4fbfa7e><a href="#" class="hover:text-primary-400 transition-colors" data-v-b4fbfa7e>Karir</a></li></ul></div></div><div class="border-t border-gray-800 pt-8 text-center text-sm" data-v-b4fbfa7e><p data-v-b4fbfa7e>© ${ssrInterpolate((/* @__PURE__ */ new Date()).getFullYear())} Tharahub. All rights reserved.</p><p class="mt-2 text-xs" data-v-b4fbfa7e>Laravel v${ssrInterpolate(__props.laravelVersion)} (PHP v${ssrInterpolate(__props.phpVersion)})</p></div></div></footer></div><!--]-->`);
    };
  }
};
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("resources/js/Pages/Welcome.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
const Welcome = /* @__PURE__ */ _export_sfc(_sfc_main, [["__scopeId", "data-v-b4fbfa7e"]]);
export {
  Welcome as default
};

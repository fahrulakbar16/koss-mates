import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

/**
 * Composable untuk mengakses warna dari website settings
 */
export function useColors() {
    const page = usePage();
    const settings = computed(() => page.props.settings || {});

    const colors = computed(() => ({
        primary: settings.value.color_primary || "#3B82F6",
        secondary: settings.value.color_secondary || "#8B5CF6",
        accent: settings.value.color_accent || "#10B981",
        success: settings.value.color_success || "#10B981",
        warning: settings.value.color_warning || "#F59E0B",
        error: settings.value.color_error || "#EF4444",
        info: settings.value.color_info || "#3B82F6",
    }));

    /**
     * Convert hex color to RGB
     */
    const hexToRgb = (hex) => {
        const result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
        return result
            ? {
                  r: parseInt(result[1], 16),
                  g: parseInt(result[2], 16),
                  b: parseInt(result[3], 16),
              }
            : null;
    };

    /**
     * Get RGB color string
     */
    const getRgb = (colorKey) => {
        const hex = colors.value[colorKey];
        const rgb = hexToRgb(hex);
        return rgb ? `${rgb.r}, ${rgb.g}, ${rgb.b}` : "59, 130, 246";
    };

    /**
     * Get color with opacity
     */
    const withOpacity = (colorKey, opacity = 1) => {
        const rgb = getRgb(colorKey);
        return `rgba(${rgb}, ${opacity})`;
    };

    return {
        colors,
        getRgb,
        withOpacity,
    };
}


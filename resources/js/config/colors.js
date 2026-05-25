/**
 * Color Configuration
 * Centralized color system for the application
 *
 * This file provides a consistent color palette that can be used throughout the application.
 * Colors can be customized via settings and will be applied dynamically.
 */

export const colorPalette = {
    primary: {
        50: '#ecf3ff',
        100: '#dde9ff',
        200: '#c2d6ff',
        300: '#9cb9ff',
        400: '#7592ff',
        500: '#465fff',
        600: '#3641f5',
        700: '#2a31d8',
        800: '#252dae',
        900: '#262e89',
        DEFAULT: '#465fff',
    },
    secondary: {
        50: '#f4ebff',
        100: '#e9d5ff',
        200: '#d8b4fe',
        300: '#c084fc',
        400: '#a855f7',
        500: '#8B5CF6',
        600: '#7c3aed',
        700: '#6d28d9',
        800: '#5b21b6',
        900: '#4c1d95',
        DEFAULT: '#8B5CF6',
    },
    accent: {
        50: '#ecfdf3',
        100: '#d1fadf',
        200: '#a6f4c5',
        300: '#6ce9a6',
        400: '#32d583',
        500: '#10B981',
        600: '#039855',
        700: '#027a48',
        800: '#05603a',
        900: '#054f31',
        DEFAULT: '#10B981',
    },
    success: {
        50: '#ecfdf3',
        100: '#d1fadf',
        200: '#a6f4c5',
        300: '#6ce9a6',
        400: '#32d583',
        500: '#12b76a',
        600: '#039855',
        700: '#027a48',
        800: '#05603a',
        900: '#054f31',
        DEFAULT: '#12b76a',
    },
    warning: {
        50: '#fffaeb',
        100: '#fef0c7',
        200: '#fedf89',
        300: '#fec84b',
        400: '#fdb022',
        500: '#f79009',
        600: '#dc6803',
        700: '#b54708',
        800: '#93370d',
        900: '#7a2e0e',
        DEFAULT: '#f79009',
    },
    error: {
        50: '#fef3f2',
        100: '#fee4e2',
        200: '#fecdca',
        300: '#fda29b',
        400: '#f97066',
        500: '#f04438',
        600: '#d92d20',
        700: '#b42318',
        800: '#912018',
        900: '#7a271a',
        DEFAULT: '#f04438',
    },
    info: {
        50: '#f0f9ff',
        100: '#e0f2fe',
        200: '#b9e6fe',
        300: '#7cd4fd',
        400: '#36bffa',
        500: '#0ba5ec',
        600: '#0086c9',
        700: '#026aa2',
        800: '#065986',
        900: '#0b4a6f',
        DEFAULT: '#0ba5ec',
    },
};

/**
 * Get Tailwind color class for a color and shade
 * @param {string} colorName - Color name (primary, secondary, etc.)
 * @param {number|string} shade - Shade number (50, 100, 500, etc.) or 'DEFAULT'
 * @returns {string} Tailwind class name
 */
export function getColorClass(colorName, shade = 'DEFAULT') {
    if (shade === 'DEFAULT') {
        return `${colorName}-500`;
    }
    return `${colorName}-${shade}`;
}

/**
 * Common color mappings for replacing hardcoded colors
 */
export const colorMappings = {
    // Replace indigo with primary
    'indigo-50': 'primary-50',
    'indigo-100': 'primary-100',
    'indigo-200': 'primary-200',
    'indigo-300': 'primary-300',
    'indigo-400': 'primary-400',
    'indigo-500': 'primary-500',
    'indigo-600': 'primary-600',
    'indigo-700': 'primary-700',
    'indigo-800': 'primary-800',
    'indigo-900': 'primary-900',

    // Replace blue with primary (for consistency)
    'blue-50': 'primary-50',
    'blue-100': 'primary-100',
    'blue-200': 'primary-200',
    'blue-300': 'primary-300',
    'blue-400': 'primary-400',
    'blue-500': 'primary-500',
    'blue-600': 'primary-600',
    'blue-700': 'primary-700',
    'blue-800': 'primary-800',
    'blue-900': 'primary-900',
};

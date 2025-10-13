/**
 * Config
 * -------------------------------------------------------------------------------------
 * ! IMPORTANT: Make sure you clear the browser local storage In order to see the config changes in the template.
 * ! To clear local storage: (https://www.leadshook.com/help/how-to-clear-local-storage-in-google-chrome-browser/).
 */

'use strict';

// JS global variables
let config = {
  colors: {
    primary: '#e22c2a', // Vibrant red from the logo's stripe
    secondary: '#f8d424', // Golden yellow from the logo's sphere
    success: '#71dd37', // Kept original
    info: '#03c3ec', // Kept original
    warning: '#f8d424', // Golden yellow from the logo's sphere
    danger: '#e22c2a', // Vibrant red from the logo's stripe
    dark: '#000000', // Black from the logo's text
    black: '#000', // Black
    white: '#fff', // White from the logo
    cardColor: '#fff', // White from the logo
    bodyBg: '#f5f5f5', // Light gray from the logo's background
    bodyColor: '#566a7f', // Kept original
    headingColor: '#233446', // Kept original
    textMuted: '#a1acb8', // Kept original
    borderColor: '#eceef1' // Kept original
  },
  colors_label: {
    primary: '#e22c2a1a', // Low opacity red
    secondary: '#f8d4241a', // Low opacity yellow
    success: '#71dd371a', // Kept original
    info: '#03c3ec1a', // Kept original
    warning: '#f8d4241a', // Low opacity yellow
    danger: '#e22c2a1a', // Low opacity red
    dark: '#0000001a' // Low opacity black
  },
  colors_dark: {
    cardColor: '#2b2c40', // Kept original
    bodyBg: '#232333', // Kept original
    bodyColor: '#e0e0e0', // Light gray for text in dark mode
    headingColor: '#ffffff', // White for headings in dark mode
    textMuted: '#b0b0b0', // Slightly darker gray for muted text
    borderColor: '#444564' // Kept original
  },
  enableMenuLocalStorage: true // Enable menu state with local storage support
};

let assetsPath = document.documentElement.getAttribute('data-assets-path'),
  templateName = document.documentElement.getAttribute('data-template'),
  rtlSupport = false; // set true for rtl support (rtl + ltr), false for ltr only.

/**
 * TemplateCustomizer
 * ! You must use(include) template-customizer.js to use TemplateCustomizer settings
 * -----------------------------------------------------------------------------------------------
 */

// To use more themes, just push it to THEMES object.

/* TemplateCustomizer.THEMES.push({
  name: 'theme-raspberry',
  title: 'Raspberry'
}); */

// To add more languages, just push it to LANGUAGES object.
/*
TemplateCustomizer.LANGUAGES.fr = { ... };
*/

/**
 * TemplateCustomizer settings
 * -------------------------------------------------------------------------------------
 * cssPath: Core CSS file path
 * themesPath: Theme CSS file path
 * displayCustomizer: true(Show customizer), false(Hide customizer)
 * lang: To set default language, Add more langues and set default. Fallback language is 'en'
 * controls: [ 'rtl','style','layoutType','showDropdownOnHover','layoutNavbarFixed','layoutFooterFixed','themes'] | Show/Hide customizer controls
 * defaultTheme: 0(Default), 1(Semi Dark), 2(Bordered)
 * defaultStyle: 'light', 'dark' (Mode)
 * defaultTextDir: 'ltr', 'rtl' (rtlSupport must be true for rtl mode)
 * defaultLayoutType: 'static', 'fixed'
 * defaultMenuCollapsed: true, false
 * defaultNavbarFixed: true, false
 * defaultFooterFixed: true, false
 * defaultShowDropdownOnHover : true, false (for horizontal layout only)
 */

if (typeof TemplateCustomizer !== 'undefined') {
  window.templateCustomizer = new TemplateCustomizer({
    cssPath: assetsPath + 'vendor/css' + (rtlSupport ? '/rtl' : '') + '/',
    themesPath: assetsPath + 'vendor/css' + (rtlSupport ? '/rtl' : '') + '/',
    displayCustomizer: true,
    defaultTheme: 1,
    defaultStyle: 'dark',
    defaultLayoutType: 'fixed',
    defaultMenuCollapsed: false,
    defaultNavbarFixed: true,
    defaultFooterFixed: false,
    defaultShowDropdownOnHover: true,
    controls: [
      'style',
      'layoutType',
      'showDropdownOnHover',
      'layoutNavbarFixed',
      'layoutFooterFixed',
      'themes'
    ],
  });
}

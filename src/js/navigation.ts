/**
 * Navigation - entry point.
 *
 * Initializes header scroll behavior, desktop dropdowns,
 * and mobile drawer menu. Loaded on every page via
 * wp_enqueue_script_module().
 */

import { initScrollBehavior } from './navigation/scroll-behavior'
import { initMobileMenu } from './navigation/mobile-menu'
import { initDropdowns } from './navigation/dropdowns'

initScrollBehavior()
initMobileMenu()
initDropdowns()

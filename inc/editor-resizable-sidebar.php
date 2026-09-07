<?php
/**
 * Resizable Editor Sidebar
 *
 * Adds a drag handle to the Gutenberg sidebar so editors can resize it.
 * Width preference is saved per-device in localStorage.
 *
 * Drop-in portable: copy this file into any theme's inc/ directory and
 * add one require_once line to functions.php. No build step, no npm,
 * no external assets — everything is inlined.
 *
 * @package WebOk
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'webok_editor_resizable_sidebar' ) ) {

	function webok_editor_resizable_sidebar() {

		$css = <<<'CSS'
.interface-interface-skeleton__sidebar {
	position: relative;
}
.interface-interface-skeleton__sidebar .interface-complementary-area,
.interface-interface-skeleton__sidebar .interface-complementary-area__fill {
	width: 100% !important;
}
.webok-resize-handle {
	position: absolute;
	top: 0;
	left: -3px;
	width: 6px;
	height: 100%;
	cursor: col-resize;
	z-index: 100000;
}
.webok-resize-handle:hover,
.webok-resize-handle.is-dragging {
	background: rgba(0, 0, 0, 0.08);
}
CSS;

		$js = <<<'JS'
(function () {
	const STORAGE_KEY = 'webok_editor_sidebar_width';
	const OLD_KEY     = 'toast_rs_personal_sidebar_width';
	const MIN_WIDTH   = 280;
	const SIDEBAR_SEL = '.interface-interface-skeleton__sidebar';
	const HANDLE_CLS  = 'webok-resize-handle';

	/* One-time migration from the Toast plugin. */
	if (!localStorage.getItem(STORAGE_KEY) && localStorage.getItem(OLD_KEY)) {
		localStorage.setItem(STORAGE_KEY, localStorage.getItem(OLD_KEY));
		localStorage.removeItem(OLD_KEY);
	}

	function applyHandle(sidebar) {
		if (sidebar.querySelector('.' + HANDLE_CLS)) return;

		/* Restore saved width. */
		const saved = localStorage.getItem(STORAGE_KEY);
		if (saved) sidebar.style.width = saved + 'px';

		/* Create drag handle. */
		const handle = document.createElement('div');
		handle.className = HANDLE_CLS;
		sidebar.prepend(handle);

		let startX, startW;

		handle.addEventListener('pointerdown', function (e) {
			e.preventDefault();
			handle.setPointerCapture(e.pointerId);
			handle.classList.add('is-dragging');
			startX = e.clientX;
			startW = sidebar.getBoundingClientRect().width;
		});

		handle.addEventListener('pointermove', function (e) {
			if (!handle.hasPointerCapture(e.pointerId)) return;
			const maxW = window.innerWidth * 0.5;
			const w = Math.min(maxW, Math.max(MIN_WIDTH, startW - (e.clientX - startX)));
			sidebar.style.width = w + 'px';
		});

		handle.addEventListener('pointerup', function (e) {
			if (!handle.hasPointerCapture(e.pointerId)) return;
			handle.releasePointerCapture(e.pointerId);
			handle.classList.remove('is-dragging');
			localStorage.setItem(STORAGE_KEY, Math.round(sidebar.getBoundingClientRect().width));
		});
	}

	/* Observe the DOM for the sidebar element (Gutenberg mounts it lazily). */
	const observer = new MutationObserver(function () {
		const sidebar = document.querySelector(SIDEBAR_SEL);
		if (sidebar) applyHandle(sidebar);
	});

	observer.observe(document.body, { childList: true, subtree: true });

	/* Also check immediately in case the sidebar already exists. */
	const sidebar = document.querySelector(SIDEBAR_SEL);
	if (sidebar) applyHandle(sidebar);
})();
JS;

		wp_register_style( 'webok-resizable-sidebar', false );
		wp_enqueue_style( 'webok-resizable-sidebar' );
		wp_add_inline_style( 'webok-resizable-sidebar', $css );

		wp_register_script( 'webok-resizable-sidebar', false, [], false, true );
		wp_enqueue_script( 'webok-resizable-sidebar' );
		wp_add_inline_script( 'webok-resizable-sidebar', $js );
	}

	add_action( 'enqueue_block_editor_assets', 'webok_editor_resizable_sidebar' );
}

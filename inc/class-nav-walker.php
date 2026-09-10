<?php
/**
 * Custom Walker for dropdown navigation.
 *
 * Simplified two-depth walker supporting desktop and mobile rendering.
 * Desktop: horizontal nav with absolute-positioned dropdown for parent items.
 * Mobile: stacked list with collapsible submenus.
 *
 * Items with the CSS class "cta-button" (set in Appearance > Menus)
 * receive the "ble-header__cta" class for CTA styling.
 *
 * @package WebOk\BrannenLakeEstates
 */

namespace WebOk\BrannenLakeEstates;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BLE_Nav_Walker extends \Walker_Nav_Menu {

	/**
	 * @var string 'desktop' or 'mobile'
	 */
	private string $menu_type;

	/**
	 * @var int|null Tracks the current parent item ID.
	 */
	private ?int $current_parent_id = null;

	/**
	 * Inline chevron SVG used for dropdown toggles.
	 */
	private const CHEVRON_SVG = '<svg width="12" height="12" viewBox="0 0 12 12" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

	public function __construct( string $menu_type = 'desktop' ) {
		$this->menu_type = $menu_type;
	}

	/**
	 * Opens the sub-menu wrapper.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ): void {
		if ( $depth !== 0 ) {
			return;
		}

		$id = $this->current_parent_id;

		if ( $this->menu_type === 'desktop' ) {
			$menu_id = 'ble-submenu-' . $id;
			$output .= '<ul class="ble-header__dropdown" id="' . \esc_attr( $menu_id ) . '" data-ble-submenu hidden>';
		} else {
			$menu_id = 'ble-mobile-submenu-' . $id;
			$output .= '<ul class="ble-header__mobile-dropdown" id="' . \esc_attr( $menu_id ) . '" data-ble-mobile-submenu hidden>';
		}
	}

	/**
	 * Closes the sub-menu wrapper.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ): void {
		if ( $depth === 0 ) {
			$output .= '</ul>';
		}
	}

	/**
	 * Renders a single menu item.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ): void {
		$classes      = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[]    = 'menu-item-' . $item->ID;
		$has_children = \in_array( 'menu-item-has-children', $classes, true );
		$is_cta       = \in_array( 'cta-button', $classes, true );

		if ( $has_children ) {
			$this->current_parent_id = $item->ID;
		}

		if ( $this->menu_type === 'desktop' ) {
			$this->render_desktop( $output, $item, $depth, $classes, $has_children, $is_cta );
		} else {
			$this->render_mobile( $output, $item, $depth, $classes, $has_children, $is_cta );
		}
	}

	/**
	 * Closes a menu item.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ): void {
		$output .= '</li>';
	}

	/**
	 * Desktop item rendering.
	 */
	private function render_desktop( string &$output, object $item, int $depth, array $classes, bool $has_children, bool $is_cta ): void {
		if ( $depth === 0 ) {
			if ( $is_cta ) {
				$classes[] = 'ble-header__cta';
			}

			$class_str = \implode( ' ', \array_filter( $classes ) );
			$output   .= '<li class="' . \esc_attr( $class_str ) . '">';

			$link_class = $is_cta ? ' class="ble-header__cta-link"' : '';
			$output    .= '<a href="' . \esc_url( $item->url ) . '"' . $link_class . '>';
			$output    .= \esc_html( $item->title );
			$output    .= '</a>';

			if ( $has_children ) {
				$menu_id = 'ble-submenu-' . $item->ID;
				$output .= '<button'
					. ' aria-expanded="false"'
					. ' aria-controls="' . \esc_attr( $menu_id ) . '"'
					. ' data-ble-submenu-toggle'
					. ' aria-label="' . \esc_attr( 'Open ' . $item->title . ' submenu' ) . '"'
					. '>' . self::CHEVRON_SVG . '</button>';
			}
		} elseif ( $depth === 1 ) {
			$class_str = \implode( ' ', \array_filter( $classes ) );
			$output   .= '<li class="' . \esc_attr( $class_str ) . '">';
			$output   .= '<a href="' . \esc_url( $item->url ) . '">';
			$output   .= \esc_html( $item->title );
			$output   .= '</a>';
		}
	}

	/**
	 * Mobile item rendering.
	 */
	private function render_mobile( string &$output, object $item, int $depth, array $classes, bool $has_children, bool $is_cta ): void {
		if ( $depth === 0 ) {
			if ( $is_cta ) {
				$classes[] = 'ble-header__cta';
			}

			$class_str = \implode( ' ', \array_filter( $classes ) );
			$output   .= '<li class="' . \esc_attr( $class_str ) . '">';

			$link_class = $is_cta ? ' class="ble-header__cta-link"' : '';
			$output    .= '<a href="' . \esc_url( $item->url ) . '"' . $link_class . '>';
			$output    .= \esc_html( $item->title );
			$output    .= '</a>';

			if ( $has_children ) {
				$menu_id = 'ble-mobile-submenu-' . $item->ID;
				$output .= '<button'
					. ' aria-expanded="false"'
					. ' aria-controls="' . \esc_attr( $menu_id ) . '"'
					. ' data-ble-mobile-submenu-toggle'
					. ' aria-label="' . \esc_attr( 'Open ' . $item->title . ' submenu' ) . '"'
					. '>' . self::CHEVRON_SVG . '</button>';
			}
		} elseif ( $depth === 1 ) {
			$class_str = \implode( ' ', \array_filter( $classes ) );
			$output   .= '<li class="' . \esc_attr( $class_str ) . '">';
			$output   .= '<a href="' . \esc_url( $item->url ) . '">';
			$output   .= \esc_html( $item->title );
			$output   .= '</a>';
		}
	}
}

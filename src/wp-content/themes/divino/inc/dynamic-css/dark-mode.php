<?php
/**
 * Dark palette - Dynamic CSS
 *
 * @package divino
 * @since 3.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

add_filter( 'divino_dynamic_theme_css', 'divino_dark_palette_css', 11 );

/**
 * Generate dynamic CSS for Dark palette.
 *
 * @param string $dynamic_css divino Dynamic CSS.
 * @param bool   $force       Whether to forcefully bypass palette check and return the CSS. Since 4.10.0.
 *
 * @return string Generated dynamic CSS for Dark palette.
 * @since 4.9.0
 */
function divino_dark_palette_css( $dynamic_css, $force = false ) {
	/**
	 * Filter to conditionally apply dark palette CSS.
	 *
	 * @param bool $apply_css Whether to apply dark palette CSS.
	 * @return bool
	 * @since 4.11.0
	 */
	if ( ! apply_filters( 'ast_dark_palette_css', true ) ) {
		return $dynamic_css;
	}

	if ( divino_Global_Palette::is_dark_palette() || $force ) {

		$dark_palette_common_dynamic_css = array(
			'.divino-dark-mode-enable .blockUI.blockOverlay' => array(
				'background-color' => 'var( --ast-global-color-primary, --ast-global-color-4 ) !important',
			),
			'.ast-header-social-wrap svg' => array(
				'fill'   => 'var(--ast-global-color-2)',
				'stroke' => 'var(--ast-global-color-2)',
			),
			' .divino-dark-mode-enable .main-header-menu .sub-menu' => array(
				'background-color' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
			),
			'.divino-dark-mode-enable .ast-header-search .ast-search-menu-icon .search-form' => array(
				'border-color' => 'var(--ast-border-color) !important',
			),
			':root'                       => array(
				'border-color' => 'var(--ast-border-color) !important',
			),
			' .divino-dark-mode-enable label, .divino-dark-mode-enable legend' => array(
				'color' => 'var(--ast-global-color-2)',
			),
			' .divino-dark-mode-enable input[type="text"]:focus, .divino-dark-mode-enable input[type="number"]:focus, .divino-dark-mode-enable input[type="email"]:focus, .divino-dark-mode-enable input[type="url"]:focus, .divino-dark-mode-enable input[type="password"]:focus, .divino-dark-mode-enable input[type="search"]:focus, .divino-dark-mode-enable input[type=reset]:focus, .divino-dark-mode-enable input[type="tel"]:focus, .divino-dark-mode-enable input[type="date"]:focus, .divino-dark-mode-enable select:focus, .divino-dark-mode-enable textarea:focus, .divino-dark-mode-enable .select2-container--default .select2-selection--single .select2-selection__rendered' => array(
				'color' => 'var(--ast-global-color-2)',
			),
			' .divino-dark-mode-enable .wp-block-search.wp-block-search__button-inside .wp-block-search__inside-wrapper' => array(
				'border'  => '1px solid var(--ast-border-color)',
				'outline' => 'none',
			),
		);

		if ( class_exists( 'WooCommerce' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(

					' .divino-dark-mode-enable .woocommerce-js label, .divino-dark-mode-enable .woocommerce-js legend' => array(
						'color' => 'var(--ast-global-color-3)',
					),
					' .divino-dark-mode-enable .woocommerce-js div.product .woocommerce-tabs ul.tabs li a' => array(
						'color' => 'var(--ast-global-color-3)',
					),
					'.divino-dark-mode-enable .woocommerce-error, .divino-dark-mode-enable .woocommerce-info, .divino-dark-mode-enable .woocommerce-message' => array(
						'background-color' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'color'            => 'var(--ast-global-color-3)',
					),
				),
			);
		}

		if ( defined( 'WPFORMS_VERSION' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.divino-dark-mode-enable .wpforms-field-container .wpforms-field-label, .divino-dark-mode-enable .wpforms-field-sublabel' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable .wpcf7 input[type=file]' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'border-color' => 'var(--ast-border-color)',
					),
					':root body' => array(
						'--wpforms-field-background-color' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--wpforms-label-sublabel-color'   => 'var(--ast-global-color-2)',
						'--wpforms-label-color'            => 'var(--ast-global-color-2)',
						'--wpforms-field-text-color'       => 'var(--ast-global-color-2)',
						'--wpforms-field-border-color'     => 'var(--ast-border-color)',
					),
				),
			);
		}

		if ( defined( 'CFVSW_VER' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.divino-dark-mode-enable .cfvsw-swatches-option.cfvsw-label-option.cfvsw-selected-swatch, .divino-dark-mode-enable .cfvsw-swatches-option:hover' => array(
						'background' => 'var(--ast-global-color-6 )',
					),
					'.divino-dark-mode-enable .cfvsw-swatches-option' => array(
						'background' => 'var(--ast-global-color-5 )',
					),
				),
			);
		}

		if ( class_exists( 'GFForms' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.divino-dark-mode-enable .gform-body legend, .divino-dark-mode-enable .gform-body label, .divino-dark-mode-enable .gform-theme--framework .field_sublabel_above .gform-field-label--type-sub' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable legend, .divino-dark-mode-enable label' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					' .divino-dark-mode-enable .gform_page_fields .gform-grid-col input[type=text], .divino-dark-mode-enable .gform_page_fields .gform-grid-col input[type=email], .divino-dark-mode-enable .gform_page_fields .gform-grid-col input[type=password], .divino-dark-mode-enable .gfield .ginput_container input[type=text], .divino-dark-mode-enable .gform-theme--foundation .gfield textarea, .divino-dark-mode-enable .gform-theme--foundation .gfield select, .divino-dark-mode-enable .gform-theme--foundation .gfield input.large' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'border-color' => 'var(--ast-border-color)',
						'color'        => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable .gform_page_fields .gform-grid-col input[type=text]:focus, .divino-dark-mode-enable .gform_page_fields .gform-grid-col input[type=email]:focus, .divino-dark-mode-enable .gform_page_fields .gform-grid-col input[type=password]:focus, .divino-dark-mode-enable .gfield .ginput_container input[type=text]:focus, .divino-dark-mode-enable .gform-theme--foundation .gfield textarea:focus, .divino-dark-mode-enable .gform-theme--foundation .gfield select:focus, .divino-dark-mode-enable .gform-theme--foundation .gfield input.large:focus ' => array(
						'outline-width' => 'inherit',
					),
					' .divino-dark-mode-enable .gfield_radio .gchoice, .divino-dark-mode-enable .gform-theme--framework .gfield--type-image_choice.gfield--image-choice-appearance-card .gchoice:hover' => array(
						'--gf-ctrl-bg-color'       => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--gf-ctrl-bg-color-hover' => 'var( --ast-global-color-primary, --ast-global-color-4 )',
						'--gf-ctrl-bg-color-focus' => 'var( --ast-global-color-primary, --ast-global-color-4 )',
					),
					' .divino-dark-mode-enable .gform-theme--framework input[type]:where(:not(.gform-text-input-reset):not([type=hidden])):where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => array(
						'--gf-local-bg-color'     => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--gf-local-border-color' => 'var(--ast-border-color)',

					),

					' .divino-dark-mode-enable .gform-theme--framework input[type]:where(:not(.gform-text-input-reset):not([type=hidden])):where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):hover' => array(
						'--gf-local-bg-color'      => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--gf-ctrl-bg-color-focus' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),

					' .divino-dark-mode-enable .gform-theme--framework input[type]:where(:not(.gform-text-input-reset):not([type=hidden])):where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)):focus' => array(
						'--gf-ctrl-bg-color-focus' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),

					' .divino-dark-mode-enable .gform-theme--framework .gfield--type-image_choice.gfield--image-choice-appearance-card .gchoice .gform-field-label' => array(
						'--gf-local-color' => 'var(--ast-global-color-2)',
					),

				),
			);
		}

		if ( defined( 'WPCF7_VERSION' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.divino-dark-mode-enable legend, .divino-dark-mode-enable label' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable .wpcf7 input[type=file]' => array(
						'background'   => 'var( --ast-global-color-primary, --ast-global-color-4 )',
						'color'        => 'var(--ast-global-color-2)',
						'border-color' => 'var(--ast-border-color)',
					),
				),
			);
		}

		if ( function_exists( 'buddypress' ) && is_buddypress() ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.divino-dark-mode-enable .buddypress-wrap .bp-feedback' => array(
						'background' => 'transparent',
					),
				),
			);
		}

		if ( class_exists( 'bbpress' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.divino-dark-mode-enable #bbpress-forums li.bbp-header, .divino-dark-mode-enable #bbpress-forums li.bbp-footer, .divino-dark-mode-enable #bbpress-forums div.odd, .divino-dark-mode-enable #bbpress-forums ul.odd, .divino-dark-mode-enable #bbpress-forums div.bbp-forum-header, .divino-dark-mode-enable #bbpress-forums div.bbp-topic-header, .divino-dark-mode-enable #bbpress-forums div.bbp-reply-header, label, legend' => array(
						'background' => 'transparent',
						'color'      => 'var(--ast-global-color-2)',
					),

					'.divino-dark-mode-enable #bbpress-forums div.even, .divino-dark-mode-enable #bbpress-forums ul.even' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'color'      => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable #bbpress-forums fieldset.bbp-form' => array(
						'border-color' => 'var(--ast-border-color)',
					),
					'.divino-dark-mode-enable #bbpress-forums .bbp-template-notice' => array(
						'background-color' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'color'            => 'var(--ast-global-color-2)',
					),
				),
			);
		}

		if ( defined( 'SRFM_VER' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'body #srfm-single-page-container' => array(
						'--srfm-bg-color' => 'var( --ast-global-color-primary, --ast-global-color-4 )',
					),
				),
			);
		}

		if ( defined( 'FLUENTFORM' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					' :root body ' => array(
						'--fluentform-border-color' => 'var(--ast-border-color)',
					),
					' .divino-dark-mode-enable .frm-fluent-form .choices__inner, .divino-dark-mode-enable .frm-fluent-form .choices__list--dropdown .choices__item--selectable, .divino-dark-mode-enable .frm-fluent-form .choices__inner, .divino-dark-mode-enable .fluentform .ff-checkable-grids tbody>tr:nth-child(2n-1)>td, .divino-dark-mode-enable .fluentform .ff-checkable-grids thead>tr>th, .divino-dark-mode-enable .ff_net_table tbody tr td' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'border-color' => 'var(--ast-border-color)',
					),
					'.divino-dark-mode-enable .frm-fluent-form .choices__list--dropdown .choices__item--selectable.is-highlighted' => array(
						'background' => 'var( --ast-global-color-alternate-background, --ast-global-color-6 )',
					),
					' .divino-dark-mode-enable .ff-default .ff-el-form-control:focus' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'color'      => 'var(--ast-global-color-2)',
					),
				),
			);
		}

		if ( class_exists( 'SFWD_LMS' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'#learndash_lesson_topics_list ul>li>span.topic_item' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),
					'.learndash #learndash_lesson_topics_list ul>li>span.topic_item:hover' => array(
						'background' => 'var( --ast-global-color-alternate-background, --ast-global-color-6 )',
					),

					'body .learndash_course_content #lessons_list>div:nth-of-type(odd)' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),
					'body .learndash_course_content #lessons_list>div:nth-of-type(even)' => array(
						'background' => 'var( --ast-global-color-subtle-background, --ast-global-color-7 )',
						'color'      => 'var(--ast-global-color-2)',
					),

					'.learndash .learndash_course_content .lessons_list .notcompleted:before' => array(
						'color' => 'var(--ast-global-color-1)',
					),

					'body #quiz_list>div:nth-of-type(odd)' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),

					'.divino-dark-mode-enable #learndash_lessons, .divino-dark-mode-enable #learndash_quizzes, .divino-dark-mode-enable #learndash_profile, .divino-dark-mode-enable #learndash_lesson_topics_list > div' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),

					'.divino-dark-mode-enable #learndash_profile' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),

					'.divino-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item, .divino-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-name, .divino-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-list-item-expanded:before, .divino-dark-mode-enable .learndash-wrapper .ld-table-list a.ld-table-list-item-preview, .divino-dark-mode-enable .learndash-wrapper .ld-breadcrumbs, .divino-dark-mode-enable .learndash-wrapper .ld-table-list a.ld-table-list-item-preview, .divino-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-items, .divino-dark-mode-enable .learndash-wrapper .ld-table-list.ld-no-pagination, .divino-dark-mode-enable .learndash-wrapper .wpProQuiz_content .wpProQuiz_response, .divino-dark-mode-enable .learndash-wrapper .wpProQuiz_graded_points, .divino-dark-mode-enable .learndash-wrapper .wpProQuiz_points, .divino-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-list-item .ld-item-list-item-expanded .ld-progress, .divino-dark-mode-enable  .learndash-wrapper .ld-table-list .ld-table-list-footer, .divino-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-item .ld-table-list-title a, .divino-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-item-preview a, .divino-dark-mode-enable .wpProQuiz_modal_window, .divino-dark-mode-enable #wpProQuiz_user_content table.wp-list-table tbody tr.categoryTr th, .divino-dark-mode-enable #wpProQuiz_user_content table.wp-list-table tfoot tr th, .divino-dark-mode-enable#wpProQuiz_user_content .wpProQuiz_response ' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'border-color' => 'var(--ast-border-color)',
						'color'        => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable .learndash-wrapper .ld-breadcrumbs, .divino-dark-mode-enable .learndash-wrapper .ld-lesson-status, .divino-dark-mode-enable .learndash-wrapper .ld-topic-status, .divino-dark-mode-enable .learndash-wrapper .ld-course-status.ld-course-status-enrolled ' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),
					' .divino-dark-mode-enable .ld-propanel-widget-filtering .toggle-section' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'border-color' => 'var(--ast-border-color)',
					),
					' .divino-dark-mode-enable .select2-container--ld_propanel .select2-selection--multiple' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'border-color' => 'var(--ast-border-color)',
					),
					' .divino-dark-mode-enable .ld-propanel-widget-filtering .section-toggle.active,  .divino-dark-mode-enable .ld-propanel-widget-reporting table' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),
					' .divino-dark-mode-enable .ld-propanel-widget-reporting table tr th, .divino-dark-mode-enable .ld-propanel-widget-progress-chart div.propanel-admin-row div.col-1-2 div.title, .divino-dark-mode-enable .ld-propanel-widget-progress-chart div.propanel-admin-row div.col-1-2:last-child div.title, .divino-dark-mode-enable .learndash-wrapper .ld-table-list .ld-table-list-footer' => array(
						'background' => 'var( --ast-global-color-alternate-background, --ast-global-color-6 )',
					),

					' .divino-dark-mode-enable .ld-propanel-widget-overview .propanel-stat .stat-label a' => array(
						'color' => 'var(--ast-global-color-2)',
					),

					' .divino-dark-mode-enable .ld-propanel-widget-filtering .filter-selection.filter-section-date>input, .divino-dark-mode-enable .ld-propanel-widget-reporting table tbody' => array(
						'border-color' => 'var(--ast-border-color)',
					),

					' .divino-dark-mode-enable .flatpickr-calendar, .divino-dark-mode-enable .flatpickr-day, .divino-dark-mode-enable .flatpickr-weekday, .divino-dark-mode-enable .flatpickr-current-month .flatpickr-monthDropdown-months' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'color'      => 'var(--ast-global-color-2)',
					),
					' .divino-dark-mode-enable .learndash-wrapper .ld-item-list .ld-item-search .ld-item-search-wrapper' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),

				),
			);
		}

		if ( class_exists( 'LifterLMS' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					' .divino-dark-mode-enable .llms-instructor-info .llms-instructors .llms-author, .divino-dark-mode-enable .llms-access-plan .llms-access-plan-content, .divino-dark-mode-enable .llms-access-plan .llms-access-plan-footer,  .divino-dark-mode-enable  .llms-lesson-preview section, .divino-dark-mode-enable .single-lesson.ast-separate-container .llms-lesson-preview .llms-lesson-link:hover, .divino-dark-mode-enable .llms-student-dashboard .orders-table, .divino-dark-mode-enable .llms-table tbody tr:nth-child(odd) td, .divino-dark-mode-enable .llms-table tbody tr:nth-child(odd) th, .divino-dark-mode-enable  .llms-table tfoot tr, .divino-dark-mode-enable .llms-sd-notification-center .llms-notification-list-item .llms-notification:hover, .divino-dark-mode-enable .llms-sd-notification-center, .divino-dark-mode-enable .redeem-voucher .form-row input[type=text]' => array(
						'background'   => 'var( --ast-global-color-primary, --ast-global-color-4 )',
						'color'        => 'var(--ast-global-color-2)',
						'border-color' => 'var(--ast-border-color)',
					),
					' .divino-dark-mode-enable body .llms-form-field input:focus, .llms-form-field input:focus-visible' => array(
						'outline' => 'inherit',
					),
					' .divino-dark-mode-enable body .llms-syllabus-wrapper .llms-lesson-preview .llms-lesson-link, .divino-dark-mode-enable .llms-lesson-preview section:hover, .divino-dark-mode-enable .llms-lesson-preview section, .divino-dark-mode-enable .llms-lesson-preview, .divino-dark-mode-enable .llms-syllabus-wrapper .llms-section-title + .llms-lesson-preview, .divino-dark-mode-enable .llms-access-plan-content .llms-access-plan-pricing, .divino-dark-mode-enable .single-lesson .llms-course-navigation .llms-lesson-preview .llms-lesson-link, .divino-dark-mode-enable .llms-student-dashboard .orders-table tbody tr:nth-child(odd) td, .divino-dark-mode-enable .llms-student-dashboard .orders-table tbody tr:nth-child(odd) th, .divino-dark-mode-enable .llms-notification, .divino-dark-mode-enable .llms-notification .llms-notification-title' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'color'        => 'var(--ast-global-color-2)',
						'border-color' => 'var(--ast-border-color)',
					),
					' .divino-dark-mode-enable label, .divino-dark-mode-enable  legend, .divino-dark-mode-enable .select2-container--default .select2-selection--single .select2-selection__rendered, .divino-dark-mode-enable .lifterlms .llms-checkout-wrapper .llms-notice, .divino-dark-mode-enable .llms-access-plan-description' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					' .divino-dark-mode-enable .select2-container .select2-selection--single, .divino-dark-mode-enable .select2-dropdown, .divino-dark-mode-enable select, .divino-dark-mode-enable .lifterlms .llms-checkout-wrapper .llms-checkout-col.llms-col-2' => array(
						'border-color' => 'var(--ast-border-color)',
					),
					// ' .divino-dark-mode-enable .lesson-template-default .wp-block-group.has-background' => array(
					// 'color' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					// ),
					' .divino-dark-mode-enable .wpforms-container input[type=range] ' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 ) !important',
					),

					' .divino-dark-mode-enable .ast-lifterlms-container .llms-loop .llms-loop-item, .divino-dark-mode-enable .ast-lifterlms-container .llms-loop .llms-loop-item .llms-loop-item-content ' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),

					' .divino-dark-mode-enable .ast-lifterlms-container .llms-loop .llms-loop-item .llms-loop-item-content, .divino-dark-mode-enable .llms-loop-item-content .llms-loop-title, .divino-dark-mode-enable .llms-loop-item-content .llms-meta, .divino-dark-mode-enable .llms-loop-item-content .llms-author, .divino-dark-mode-enable .llms-loop-item-content .llms-featured-pricing ' => array(
						'background'   => 'var( --ast-global-color-primary, --ast-global-color-4 )',
						'border-color' => 'var(--ast-border-color)',
						'color'        => 'var(--ast-global-color-2)',
					),

					' .divino-dark-mode-enable .ast-container .llms-loop-item-content .llms-loop-title:hover, .divino-dark-mode-enable .ast-lifterlms-container .llms-loop-item-content .llms-loop-title:hover, .divino-dark-mode-enable .llms-student-dashboard .llms-loop-item-content .llms-loop-title:hover' => array(
						'color' => 'var(--ast-global-color-1)',
					),

					' .divino-dark-mode-enable .gform-theme--framework .gform-field-label:where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *))' => array(
						'--gf-ctrl-label-color-primary' => 'var(--ast-global-color-2)',
					),

					' .divino-dark-mode-enable .gform-theme--framework .gfield_description:where(:not(.gform-theme__disable):not(.gform-theme__disable *):not(.gform-theme__disable-framework):not(.gform-theme__disable-framework *)), .divino-dark-mode-enable .gform-theme--framework .gfield--type-product .ginput_product_price' => array(
						'--gf-ctrl-desc-color' => 'var(--ast-global-color-2)',
					),

					' .divino-dark-mode-enable .gform-theme--framework .gfield--type-product .ginput_product_price' => array(
						'--gf-field-prod-price-color' => 'var(--ast-global-color-2)',
					),

				),
			);
		}

		// Surecart comaptibility css
		if ( defined( 'SURECART_PLUGIN_FILE' ) ) {
			$dark_palette_common_dynamic_css = array_merge(
				$dark_palette_common_dynamic_css,
				array(
					'.divino-dark-mode-enable .sc-pill-option__wrapper .sc-pill-option__button' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'body .sc-input-group'               => array(
						'--sc-input-background-color-focus' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'color' => 'var(--ast-global-color-2)',
					),
					'body .sc-input-group.sc-quantity-selector .sc-quantity-selector__control' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable .sc-drawer' => array(
						'background-color' => 'var( --ast-global-color-secondary, --ast-global-color-5 ) !important',
					),
					'.divino-dark-mode-enable .sc-pill-option__wrapper .sc-pill-option__button:hover' => array(
						'color' => 'var(--ast-global-color-1)',
					),
					'.divino-dark-mode-enable .wp-block-surecart-column' => array(
						'background' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),
					'.divino-dark-mode-enable .sc-input-group, .divino-dark-mode-enable .sc-input-group:hover, .divino-dark-mode-enable .sc-input-group:focus-within' => array(
						'background'   => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'border-color' => 'var(--ast-border-color)',
					),
					'.divino-dark-mode-enable .sc-input-group:focus-within' => array(
						'color' => 'white',
					),
					'.divino-dark-mode-enable .wp-block-surecart-slide-out-cart' => array(
						'border-color' => 'var(--ast-border-color)',
					),
					'.divino-dark-mode-enable .sc-product-line-item__title, .sc-product-line-item__description, .sc-product-line-item__price, .sc-product-line-item__description, .sc-coupon-form, .wp-block-surecart-slide-out-cart-header__title, .sc-product-line-item__price-description, .sc-product-line-item__price-variant, .sc-product-line-item__price-amount' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable .sc-product-line-item__price-variant, .sc-product-line-item__trial, .divino-dark-mode-enable .wp-block-surecart-product-list-price' => array(
						'color' => 'var(--ast-global-color-2)',
					),
					'.divino-dark-mode-enable .wp-block-surecart-cart-icon__icon svg' => array(
						'fill' => 'none',
					),
					'.divino-dark-mode-enable  svg'       => array(
						'--sc-alert-background-color' => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'fill'                        => 'var(--ast-global-color-2)',
					),
					':root body '                        => array(
						'--sc-input-label-color'           => 'var(--ast-global-color-3) ',
						'--sc-card-background-color'       => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-background-color-focus' => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-background-color'      => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-select-background-color'     => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-border-color'          => 'var(--ast-border-color) ',
						'--sc-select-border-color'         => 'var(--ast-border-color) ',
						'--sc-select-border-color-focus'   => 'var(--ast-border-color) ',
						'--sc-input-border-color-focus'    => 'var(--ast-border-color) ',
						'--sc-input-control-background-color' => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-control-color'         => 'var(--ast-global-color-2) ',
						'--sc-input-color-focus'           => 'var(--ast-global-color-2) ',
						'--sc-card-border-color'           => 'var(--ast-border-color) ',
						'--sc-input-color'                 => 'var(--ast-global-color-2) ',
						'--sc-panel-background-color'      => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-menu-item-color'             => 'var(--ast-global-color-2) ',
						'--sc-input-background-color-disabled' => 'var( --ast-global-color-alternate-background, --ast-global-color-6 ) ',
						'--sc-input-border-color-disabled' => 'var(--ast-border-color) ',
						'--sc-input-color-disabled'        => 'var(--ast-global-color-2)',
						'--sc-choice-background-color'     => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--gf-color-in-ctrl'               => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--sc-input-background-color-hover' => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-color-gray-50'               => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--sc-color-white'                 => 'var( --ast-global-color-alternate-background, --ast-global-color-6 )',
						'--sc-color-gray-600'              => 'var(--ast-global-color-2)',
						'--sc-color-gray-800'              => 'var(--ast-global-color-2)',
						'--sc-color-gray-900'              => 'var(--ast-global-color-2)',
						'--sc-color-gray-100'              => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
					),
					'.wp-block-surecart-column.has-background' => array(
						'--sc-input-label-color'           => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--sc-card-background-color'       => 'var(--ast-global-color-2)',
						'--sc-input-background-color'      => 'var(--ast-global-color-2)',
						'--sc-select-background-color'     => 'var(--ast-global-color-2)',
						'--sc-input-background-color-focus' => 'var(--ast-global-color-2) ',
						'--sc-input-color'                 => 'var( --ast-global-color-secondary, --ast-global-color-5 )',
						'--sc-input-color-focus'           => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-panel-background-color'      => 'var(--ast-global-color-2)',
						'--sc-menu-item-color'             => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-control-background-color' => 'var(--ast-global-color-2) ',
						'--sc-input-control-color'         => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-background-color-hover' => 'var(--ast-global-color-2) ',
						'--sc-input-border-color'          => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-border-color-focus'    => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
						'--sc-input-border-color-disabled' => 'var( --ast-global-color-secondary, --ast-global-color-5 ) ',
					),
				),
			);
		}

		$dynamic_css .= divino_parse_css( $dark_palette_common_dynamic_css );
	}

	return $dynamic_css;
}

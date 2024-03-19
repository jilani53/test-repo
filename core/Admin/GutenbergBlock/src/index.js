import icons from "./shortcode/blockIcon";
import DynamicShortcodeInput from "./shortcode/dynamicShortcode";
import { escapeAttribute, escapeHTML } from "@wordpress/escape-html";
import { InspectorControls } from '@wordpress/block-editor';

const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;
const { PanelBody, PanelRow } = wp.components;
const { Fragment } = wp.element;
const ServerSideRender = wp.serverSideRender;
const el = wp.element.createElement;

/**
 * Register: Smart Brand Gutenberg Block.
 */
registerBlockType("sp-smart-brand/shortcode", {
  title: escapeHTML(__("Smart Brand", "smart-brands-for-woocommerce")),
  description: escapeHTML(__(
    "Use Smart Brand to insert a brands group in your page",
    "smart-brands-for-woocommerce"
  )),
  icon: icons.brandIcon,
  category: "common",
  supports: {
    html: true,
  },
  edit: (props) => {
    const { attributes, setAttributes } = props;
    var shortCodeList = sp_smart_brand_gb_block.shortCodeList;

    let scriptLoad = (shortcodeId) => {
      let spBrandBlockLoaded = false;
      let spBrandBlockLoadedInterval = setInterval(function () {
        let uniqId = jQuery("#smart-brand-main-area-" + shortcodeId).parents().attr('id');
        // console.log( uniqId );
        if (document.getElementById(uniqId)) {
          jQuery.getScript(sp_smart_brand_gb_block.loadPublic);
          spBrandBlockLoaded = true;
          uniqId = '';
        }
        if (spBrandBlockLoaded) {
          clearInterval(spBrandBlockLoadedInterval);
        }
        if (0 == shortcodeId) {
          clearInterval(spBrandBlockLoadedInterval);
        }
      }, 10);
    }

    let updateShortcode = (updateShortcode) => {
      setAttributes({ shortcode: escapeAttribute(updateShortcode.target.value) });
    }

    let shortcodeUpdate = (e) => {
      updateShortcode(e);
      let shortcodeId = escapeAttribute(e.target.value);
      scriptLoad(shortcodeId);
    }

    document.addEventListener('readystatechange', event => {
      if (event.target.readyState === "complete") {
        let shortcodeId = escapeAttribute(attributes.shortcode);
        scriptLoad(shortcodeId);
      }
    });
	if (jQuery('.sp-smart-brand-wrapper:not(.sp-smart-brand-wrapper-loaded)').length > 0) {
		let shortcodeId = escapeAttribute(attributes.shortcode);
		scriptLoad(shortcodeId);
	}

    if (attributes.preview) {
      return (
        el('div', { className: 'sp_smart_brand_shortcode_block_preview_image' },
          el('img', { src: escapeAttribute(sp_smart_brand_gb_block.url + "/core/Admin/GutenbergBlock/assets/wp-brand-block-preview.png") })
        )
      )
    }

    if (shortCodeList.length === 0) {
      return (
        <Fragment>
          {
            el('div', { className: 'components-placeholder components-placeholder is-large' },
              el('div', { className: 'components-placeholder__label' },
                el('img', { className: 'block-editor-block-icon', src: escapeAttribute(sp_smart_brand_gb_block.url + "admin/GutenbergBlock/assets/wp-brand-icon.svg") }),
                escapeHTML(__("Smart Brand", "smart-brands-for-woocommerce"))
              ),
              el('div', { className: 'components-placeholder__instructions' },
                escapeHTML(__("No shortcode found. ", "smart-brands-for-woocommerce")),
                el('a', { href: escapeAttribute(sp_smart_brand_gb_block.link) },
                  escapeHTML(__("Create a shortcode now!", "smart-brands-for-woocommerce"))
                )
              )
            )
          }
        </Fragment>
      );
    }

    if (!attributes.shortcode || attributes.shortcode == 0) {
      return (
        <Fragment>
          <InspectorControls>
            <PanelBody title="Smart Brand">
              <PanelRow>
                <DynamicShortcodeInput
                  attributes={attributes}
                  shortCodeList={shortCodeList}
                  shortcodeUpdate={shortcodeUpdate}
                />
              </PanelRow>
            </PanelBody>
          </InspectorControls>
          {
            el('div', { className: 'components-placeholder components-placeholder is-large' },
              el('div', { className: 'components-placeholder__label' },
                el('img', { className: 'block-editor-block-icon', src: escapeAttribute(sp_smart_brand_gb_block.url + "/core/Admin/GutenbergBlock/assets/sbn.svg") }),
                escapeHTML(__("Smart Brand", "smart-brands-for-woocommerce"))
              ),
              el('div', { className: 'components-placeholder__instructions' }, escapeHTML(__("Select a brand group", "smart-brands-for-woocommerce"))),
              <DynamicShortcodeInput
                attributes={attributes}
                shortCodeList={shortCodeList}
                shortcodeUpdate={shortcodeUpdate}
              />
            )
          }
        </Fragment>
      );
    }

    return (
      <Fragment>
        <InspectorControls>
          <PanelBody title="Smart Brand">
            <PanelRow>
              <DynamicShortcodeInput
                attributes={attributes}
                shortCodeList={shortCodeList}
                shortcodeUpdate={shortcodeUpdate}
              />
            </PanelRow>
          </PanelBody>
        </InspectorControls>
        <ServerSideRender block="sp-smart-brand/shortcode" attributes={attributes} />
      </Fragment>
    );
  },
  save() {
    // Rendering in PHP
    return null;
  },
});

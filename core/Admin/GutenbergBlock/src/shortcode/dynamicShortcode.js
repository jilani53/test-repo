/**
 * Shortcode select component.
 */
 import { escapeAttribute, escapeHTML } from "@wordpress/escape-html";
 const { __ } = wp.i18n;
 const { Fragment } = wp.element;
 const el = wp.element.createElement;
 
 const DynamicShortcodeInput = ( { attributes : { shortcode }, shortCodeList, shortcodeUpdate } ) => (
     <Fragment>
        {el('div', {className: 'sp-smart-brand-gutenberg-shortcode editor-styles-wrapper'},
            el('select', {className: 'sp-smart-brand-shortcode-selector', onChange: e => shortcodeUpdate(e), value: escapeAttribute( shortcode ) },
                el('option', {value: escapeAttribute('0')}, escapeHTML( __( '-- Select a brand group --', 'smart-brands-for-woocommerce' ))),
                shortCodeList.map( shortcode => {
                    var title = (shortcode.title.length > 35) ? shortcode.title.substring(0,30) + '.... #(' + shortcode.id + ')' : shortcode.title + ' #(' + shortcode.id + ')';
                    return el('option', {value: escapeAttribute( shortcode.id.toString() ), key: escapeAttribute( shortcode.id.toString() )}, escapeHTML( title ) )
                })
            )
        )}
     </Fragment>
 );
 
 export default DynamicShortcodeInput;
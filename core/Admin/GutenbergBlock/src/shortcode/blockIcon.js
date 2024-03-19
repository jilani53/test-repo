import { escapeAttribute } from "@wordpress/escape-html";
const el = wp.element.createElement;
const icons = {};
icons.brandIcon = el('img', {src: escapeAttribute( sp_smart_brand_gb_block.url + '/core/Admin/GutenbergBlock/assets/sbn.svg' )});
export default icons;
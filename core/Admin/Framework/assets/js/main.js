/**
 *
 * -----------------------------------------------------------
 *
 * Codestar Framework
 * A Simple and Lightweight WordPress Option Framework
 *
 * -----------------------------------------------------------
 *
 */
; (function ($, window, document, undefined) {
  'use strict';

  //
  // Constants
  //
  var SPF_SMART_BRANDS = SPF_SMART_BRANDS || {};

  SPF_SMART_BRANDS.funcs = {};

  SPF_SMART_BRANDS.vars = {
    onloaded: false,
    $body: $('body'),
    $window: $(window),
    $document: $(document),
    $form_warning: null,
    is_confirm: false,
    form_modified: false,
    code_themes: [],
    is_rtl: $('body').hasClass('rtl'),
  };

  //
  // Helper Functions
  //
  SPF_SMART_BRANDS.helper = {

    //
    // Generate UID
    //
    uid: function (prefix) {
      return (prefix || '') + Math.random().toString(36).substr(2, 9);
    },

    // Quote regular expression characters
    //
    preg_quote: function (str) {
      return (str + '').replace(/(\[|\])/g, "\\$1");
    },

    //
    // Reneme input names
    //
    name_nested_replace: function ($selector, field_id) {

      var checks = [];
      var regex = new RegExp(SPF_SMART_BRANDS.helper.preg_quote(field_id + '[\\d+]'), 'g');

      $selector.find(':radio').each(function () {
        if (this.checked || this.orginal_checked) {
          this.orginal_checked = true;
        }
      });

      $selector.each(function (index) {
        $(this).find(':input').each(function () {
          this.name = this.name.replace(regex, field_id + '[' + index + ']');
          if (this.orginal_checked) {
            this.checked = true;
          }
        });
      });

    },

    //
    // Debounce
    //
    debounce: function (callback, threshold, immediate) {
      var timeout;
      return function () {
        var context = this, args = arguments;
        var later = function () {
          timeout = null;
          if (!immediate) {
            callback.apply(context, args);
          }
        };
        var callNow = (immediate && !timeout);
        clearTimeout(timeout);
        timeout = setTimeout(later, threshold);
        if (callNow) {
          callback.apply(context, args);
        }
      };
    },

  };

  //
  // Custom clone for textarea and select clone() bug
  //
  $.fn.csf_clone = function () {

    var base = $.fn.clone.apply(this, arguments),
      clone = this.find('select').add(this.filter('select')),
      cloned = base.find('select').add(base.filter('select'));

    for (var i = 0; i < clone.length; ++i) {
      for (var j = 0; j < clone[i].options.length; ++j) {

        if (clone[i].options[j].selected === true) {
          cloned[i].options[j].selected = true;
        }

      }
    }

    this.find(':radio').each(function () {
      this.orginal_checked = this.checked;
    });

    return base;

  };

  //
  // Expand All Options
  //
  $.fn.csf_expand_all = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        e.preventDefault();
        $('.csf-wrapper').toggleClass('csf-show-all');
        $('.csf-section').csf_reload_script();
        $(this).find('.fa').toggleClass('fa-indent').toggleClass('fa-outdent');

      });
    });
  };

  //
  // Options Navigation
  //
  $.fn.csf_nav_options = function () {
    return this.each(function () {

      var $nav = $(this),
        $window = $(window),
        $wpwrap = $('#wpwrap'),
        $links = $nav.find('a'),
        $last;

      $window.on('hashchange csf.hashchange', function () {

        var hash = window.location.hash.replace('#tab=', '');
        var slug = hash ? hash : $links.first().attr('href').replace('#tab=', '');
        var $link = $('[data-tab-id="' + slug + '"]');

        if ($link.length) {

          $link.closest('.csf-tab-item').addClass('csf-tab-expanded').siblings().removeClass('csf-tab-expanded');

          if ($link.next().is('ul')) {

            $link = $link.next().find('li').first().find('a');
            slug = $link.data('tab-id');

          }

          $links.removeClass('csf-active');
          $link.addClass('csf-active');

          if ($last) {
            $last.addClass('hidden');
          }

          var $section = $('[data-section-id="' + slug + '"]');

          $section.removeClass('hidden');
          $section.csf_reload_script();

          $('.csf-section-id').val($section.index() + 1);

          $last = $section;

          if ($wpwrap.hasClass('wp-responsive-open')) {
            $('html, body').animate({ scrollTop: ($section.offset().top - 50) }, 200);
            $wpwrap.removeClass('wp-responsive-open');
          }

        }

      }).trigger('csf.hashchange');

    });
  };

  //
  // Metabox Tabs
  //
  $.fn.csf_nav_metabox = function () {
    return this.each(function () {

      var $nav = $(this),
        $links = $nav.find('a'),
        $sections = $nav.parent().find('.csf-section'),
        $last;

      $links.each(function (index) {

        $(this).on('click', function (e) {

          e.preventDefault();

          var $link = $(this);

          $links.removeClass('csf-active');
          $link.addClass('csf-active');

          if ($last !== undefined) {
            $last.addClass('hidden');
          }

          var $section = $sections.eq(index);

          $section.removeClass('hidden');
          $section.csf_reload_script();

          $last = $section;

        });

      });

      $links.first().trigger('click');

    });
  };

  //
  // Metabox Page Templates Listener
  //
  $.fn.csf_page_templates = function () {
    if (this.length) {

      $(document).on('change', '.editor-page-attributes__template select, #page_template', function () {

        var maybe_value = $(this).val() || 'default';

        $('.csf-page-templates').removeClass('csf-metabox-show').addClass('csf-metabox-hide');
        $('.csf-page-' + maybe_value.toLowerCase().replace(/[^a-zA-Z0-9]+/g, '-')).removeClass('csf-metabox-hide').addClass('csf-metabox-show');

      });

    }
  };

  //
  // Metabox Post Formats Listener
  //
  $.fn.csf_post_formats = function () {
    if (this.length) {

      $(document).on('change', '.editor-post-format select, #formatdiv input[name="post_format"]', function () {

        var maybe_value = $(this).val() || 'default';

        // Fallback for classic editor version
        maybe_value = (maybe_value === '0') ? 'default' : maybe_value;

        $('.csf-post-formats').removeClass('csf-metabox-show').addClass('csf-metabox-hide');
        $('.csf-post-format-' + maybe_value).removeClass('csf-metabox-hide').addClass('csf-metabox-show');

      });

    }
  };

  //
  // Search
  //
  $.fn.csf_search = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input');

      $input.on('change keyup', function () {

        var value = $(this).val(),
          $wrapper = $('.csf-wrapper'),
          $section = $wrapper.find('.csf-section'),
          $fields = $section.find('> .csf-field:not(.csf-depend-on)'),
          $titles = $fields.find('> .csf-title, .csf-search-tags');

        if (value.length > 3) {

          $fields.addClass('csf-metabox-hide');
          $wrapper.addClass('csf-search-all');

          $titles.each(function () {

            var $title = $(this);

            if ($title.text().match(new RegExp('.*?' + value + '.*?', 'i'))) {

              var $field = $title.closest('.csf-field');

              $field.removeClass('csf-metabox-hide');
              $field.parent().csf_reload_script();

            }

          });

        } else {

          $fields.removeClass('csf-metabox-hide');
          $wrapper.removeClass('csf-search-all');

        }

      });

    });
  };

  //
  // Sticky Header
  //
  $.fn.csf_sticky = function () {
    return this.each(function () {

      var $this = $(this),
        $window = $(window),
        $inner = $this.find('.csf-header-inner'),
        padding = parseInt($inner.css('padding-left')) + parseInt($inner.css('padding-right')),
        offset = 32,
        scrollTop = 0,
        lastTop = 0,
        ticking = false,
        stickyUpdate = function () {

          var offsetTop = $this.offset().top,
            stickyTop = Math.max(offset, offsetTop - scrollTop),
            winWidth = $window.innerWidth();

          if (stickyTop <= offset && winWidth > 782) {
            $inner.css({ width: $this.outerWidth() - padding });
            $this.css({ height: $this.outerHeight() }).addClass('csf-sticky');
          } else {
            $inner.removeAttr('style');
            $this.removeAttr('style').removeClass('csf-sticky');
          }

        },
        requestTick = function () {

          if (!ticking) {
            requestAnimationFrame(function () {
              stickyUpdate();
              ticking = false;
            });
          }

          ticking = true;

        },
        onSticky = function () {

          scrollTop = $window.scrollTop();
          requestTick();

        };

      $window.on('scroll resize', onSticky);

      onSticky();

    });
  };

  //
  // Dependency System
  //
  $.fn.csf_dependency = function () {
    return this.each(function () {

      var $this = $(this),
        $fields = $this.children('[data-controller]');

      if ($fields.length) {

        var normal_ruleset = $.csf_deps.createRuleset(),
          global_ruleset = $.csf_deps.createRuleset(),
          normal_depends = [],
          global_depends = [];

        $fields.each(function () {

          var $field = $(this),
            controllers = $field.data('controller').split('|'),
            conditions = $field.data('condition').split('|'),
            values = $field.data('value').toString().split('|'),
            is_global = $field.data('depend-global') ? true : false,
            ruleset = (is_global) ? global_ruleset : normal_ruleset;

          $.each(controllers, function (index, depend_id) {

            var value = values[index] || '',
              condition = conditions[index] || conditions[0];

            ruleset = ruleset.createRule('[data-depend-id="' + depend_id + '"]', condition, value);

            ruleset.include($field);

            if (is_global) {
              global_depends.push(depend_id);
            } else {
              normal_depends.push(depend_id);
            }

          });

        });

        if (normal_depends.length) {
          $.csf_deps.enable($this, normal_ruleset, normal_depends);
        }

        if (global_depends.length) {
          $.csf_deps.enable(SPF_SMART_BRANDS.vars.$body, global_ruleset, global_depends);
        }

      }

    });
  };

  //
  // Field: background
  //
  $.fn.csf_field_background = function () {
    return this.each(function () {
      $(this).find('.csf--background-image').csf_reload_script();
    });
  };

  //
  // Field: code_editor
  //
  $.fn.csf_field_code_editor = function () {
    return this.each(function () {

      if (typeof CodeMirror !== 'function') { return; }

      var $this = $(this),
        $textarea = $this.find('textarea'),
        $inited = $this.find('.CodeMirror'),
        data_editor = $textarea.data('editor');

      if ($inited.length) {
        $inited.remove();
      }

      var interval = setInterval(function () {
        if ($this.is(':visible')) {

          var code_editor = CodeMirror.fromTextArea($textarea[0], data_editor);

          // load code-mirror theme css.
          if (data_editor.theme !== 'default' && SPF_SMART_BRANDS.vars.code_themes.indexOf(data_editor.theme) === -1) {

            var $cssLink = $('<link>');

            $('#csf-codemirror-css').after($cssLink);

            $cssLink.attr({
              rel: 'stylesheet',
              id: 'csf-codemirror-' + data_editor.theme + '-css',
              href: data_editor.cdnURL + '/theme/' + data_editor.theme + '.min.css',
              type: 'text/css',
              media: 'all'
            });

            SPF_SMART_BRANDS.vars.code_themes.push(data_editor.theme);

          }

          CodeMirror.modeURL = data_editor.cdnURL + '/mode/%N/%N.min.js';
          CodeMirror.autoLoadMode(code_editor, data_editor.mode);

          code_editor.on('change', function (editor, event) {
            $textarea.val(code_editor.getValue()).trigger('change');
          });

          clearInterval(interval);

        }
      });

    });
  };

  //
  // Field: spinner
  //
  $.fn.csf_field_spinner = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('input'),
        $inited = $this.find('.ui-button'),
        data = $input.data();

      if ($inited.length) {
        $inited.remove();
      }

      $input.spinner({
        min: data.min || 0,
        max: data.max || 100,
        step: data.step || 1,
        create: function (event, ui) {
          if (data.unit) {
            $input.after('<span class="ui-button csf--unit">' + data.unit + '</span>');
          }
        },
        spin: function (event, ui) {
          $input.val(ui.value).trigger('change');
        }
      });

    });
  };

  //
  // Field: switcher
  //
  $.fn.csf_field_switcher = function () {
    return this.each(function () {

      var $switcher = $(this).find('.csf--switcher');

      $switcher.on('click', function () {

        var value = 0;
        var $input = $switcher.find('input');

        if ($switcher.hasClass('csf--active')) {
          $switcher.removeClass('csf--active');
        } else {
          value = 1;
          $switcher.addClass('csf--active');
        }

        $input.val(value).trigger('change');
      });
    });
  };

  //
  // Field: media
  //
  $.fn.csf_field_media = function () {
    return this.each(function () {

      var $this = $(this),
        $upload_button = $this.find('.csf--button'),
        $remove_button = $this.find('.csf--remove'),
        $library = $upload_button.data('library') && $upload_button.data('library').split(',') || '',
        $auto_attributes = ($this.hasClass('csf-assign-field-background')) ? $this.closest('.csf-field-background').find('.csf--auto-attributes') : false,
        wp_media_frame;

      $upload_button.on('click', function (e) {

        e.preventDefault();

        if (typeof window.wp === 'undefined' || !window.wp.media || !window.wp.media.gallery) {
          return;
        }

        if (wp_media_frame) {
          wp_media_frame.open();
          return;
        }

        wp_media_frame = window.wp.media({
          library: {
            type: $library
          }
        });

        wp_media_frame.on('select', function () {

          var thumbnail;
          var attributes = wp_media_frame.state().get('selection').first().attributes;
          var preview_size = $upload_button.data('preview-size') || 'thumbnail';

          if ($library.length && $library.indexOf(attributes.subtype) === -1 && $library.indexOf(attributes.type) === -1) {
            return;
          }

          $this.find('.csf--id').val(attributes.id);
          $this.find('.csf--width').val(attributes.width);
          $this.find('.csf--height').val(attributes.height);
          $this.find('.csf--alt').val(attributes.alt);
          $this.find('.csf--title').val(attributes.title);
          $this.find('.csf--description').val(attributes.description);

          if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.thumbnail !== 'undefined' && preview_size === 'thumbnail') {
            thumbnail = attributes.sizes.thumbnail.url;
          } else if (typeof attributes.sizes !== 'undefined' && typeof attributes.sizes.full !== 'undefined') {
            thumbnail = attributes.sizes.full.url;
          } else if (attributes.type === 'image') {
            thumbnail = attributes.url;
          } else {
            thumbnail = attributes.icon;
          }

          console.log(attributes);

          if ($auto_attributes) {
            $auto_attributes.removeClass('csf--attributes-hidden');
          }

          $remove_button.removeClass('hidden');

          $this.find('.csf--preview').removeClass('hidden');
          $this.find('.csf--src').attr('src', thumbnail);
          $this.find('.csf--thumbnail').val(thumbnail);
          $this.find('.csf--url').val(attributes.url).trigger('change');

        });

        wp_media_frame.open();

      });

      $remove_button.on('click', function (e) {

        e.preventDefault();

        if ($auto_attributes) {
          $auto_attributes.addClass('csf--attributes-hidden');
        }

        $remove_button.addClass('hidden');
        $this.find('input').val('');
        $this.find('.csf--preview').addClass('hidden');
        $this.find('.csf--url').trigger('change');

      });
    });
  };

  //
  // Field: slider
  //
  $.fn.csf_field_slider = function () {
    return this.each(function () {
      var $this = $(this),
        $input = $this.find('input'),
        $slider = $this.find('.csf-slider-ui'),
        data = $input.data(),
        value = $input.val() || 0;

      if ($slider.hasClass('ui-slider')) {
        $slider.empty();
      }

      $slider.slider({
        range: 'min',
        value: value,
        min: data.min || 0,
        max: data.max || 100,
        step: data.step || 1,
        slide: function (e, o) {
          $input.val(o.value).trigger('change');
        }
      });

      $input.on('keyup', function () {
        $slider.slider('value', $input.val());
      });
    });
  };

  //
  // Field: sortable
  //
  $.fn.csf_field_sortable = function () {
    return this.each(function () {
      var $sortable = $(this).find('.csf-sortable');
      $sortable.sortable({
        axis: 'y',
        helper: 'original',
        cursor: 'move',
        placeholder: 'widget-placeholder',
        update: function (event, ui) {
          $sortable.csf_customizer_refresh();
        }
      });

      $sortable.find('.csf-sortable-content').csf_reload_script();
    });
  };

  //
  // Confirm
  //
  $.fn.csf_confirm = function () {
    return this.each(function () {
      $(this).on('click', function (e) {

        var confirm_text = $(this).data('confirm') || window.csf_vars.i18n.confirm;
        var confirm_answer = confirm(confirm_text);

        if (confirm_answer) {
          SPF_SMART_BRANDS.vars.is_confirm = true;
          SPF_SMART_BRANDS.vars.form_modified = false;
        } else {
          e.preventDefault();
          return false;
        }

      });
    });
  };

  $.fn.serializeObject = function () {

    var obj = {};

    $.each(this.serializeArray(), function (i, o) {
      var n = o.name,
        v = o.value;

      obj[n] = obj[n] === undefined ? v
        : $.isArray(obj[n]) ? obj[n].concat(v)
          : [obj[n], v];
    });

    return obj;

  };

  //
  // Options Save
  //
  $.fn.csf_save = function () {
    return this.each(function () {

      var $this = $(this),
        $buttons = $('.csf-save'),
        $panel = $('.csf-options'),
        flooding = false,
        timeout;

      $this.on('click', function (e) {

        if (!flooding) {

          var $text = $this.data('save'),
            $value = $this.val();

          $buttons.attr('value', $text);

          if ($this.hasClass('csf-save-ajax')) {

            e.preventDefault();

            $panel.addClass('csf-saving');
            $buttons.prop('disabled', true);

            window.wp.ajax.post('csf_' + $panel.data('unique') + '_ajax_save', {
              data: $('#csf-form').serializeJSONSPF_SMART_BRANDS()
            })
              .done(function (response) {

                // clear errors
                $('.csf-error').remove();

                if (Object.keys(response.errors).length) {

                  var error_icon = '<i class="csf-label-error csf-error">!</i>';

                  $.each(response.errors, function (key, error_message) {

                    var $field = $('[data-depend-id="' + key + '"]'),
                      $link = $('a[href="#tab=' + $field.closest('.csf-section').data('section-id') + '"]'),
                      $tab = $link.closest('.csf-tab-item');

                    $field.closest('.csf-fieldset').append('<p class="csf-error csf-error-text">' + error_message + '</p>');

                    if (!$link.find('.csf-error').length) {
                      $link.append(error_icon);
                    }

                    if (!$tab.find('.csf-arrow .csf-error').length) {
                      $tab.find('.csf-arrow').append(error_icon);
                    }

                  });

                }

                $panel.removeClass('csf-saving');
                $buttons.prop('disabled', false).attr('value', $value);
                flooding = false;

                SPF_SMART_BRANDS.vars.form_modified = false;
                SPF_SMART_BRANDS.vars.$form_warning.hide();

                clearTimeout(timeout);

                var $result_success = $('.csf-form-success');
                $result_success.empty().append(response.notice).fadeIn('fast', function () {
                  timeout = setTimeout(function () {
                    $result_success.fadeOut('fast');
                  }, 1000);
                });

              })
              .fail(function (response) {
                alert(response.error);
              });

          } else {

            SPF_SMART_BRANDS.vars.form_modified = false;

          }

        }

        flooding = true;

      });

    });
  };

  //
  // Option Framework
  //
  $.fn.csf_options = function () {
    return this.each(function () {

      var $this = $(this),
        $content = $this.find('.csf-content'),
        $form_success = $this.find('.csf-form-success'),
        $form_warning = $this.find('.csf-form-warning'),
        $save_button = $this.find('.csf-header .csf-save');

      SPF_SMART_BRANDS.vars.$form_warning = $form_warning;

      // Shows a message white leaving theme options without saving
      if ($form_warning.length) {

        window.onbeforeunload = function () {
          return (SPF_SMART_BRANDS.vars.form_modified) ? true : undefined;
        };

        $content.on('change keypress', ':input', function () {
          if (!SPF_SMART_BRANDS.vars.form_modified) {
            $form_success.hide();
            $form_warning.fadeIn('fast');
            SPF_SMART_BRANDS.vars.form_modified = true;
          }
        });

      }

      if ($form_success.hasClass('csf-form-show')) {
        setTimeout(function () {
          $form_success.fadeOut('fast');
        }, 1000);
      }

      $(document).keydown(function (event) {
        if ((event.ctrlKey || event.metaKey) && event.which === 83) {
          $save_button.trigger('click');
          event.preventDefault();
          return false;
        }
      });

    });
  };

  //
  // Taxonomy Framework
  //
  $.fn.csf_taxonomy = function () {
    return this.each(function () {

      var $this = $(this),
        $form = $this.parents('form');

      if ($form.attr('id') === 'addtag') {

        var $submit = $form.find('#submit'),
          $cloned = $this.find('.csf-field').csf_clone();

        $submit.on('click', function () {

          if (!$form.find('.form-required').hasClass('form-invalid')) {

            $this.data('inited', false);

            $this.empty();

            $this.html($cloned);

            $cloned = $cloned.csf_clone();

            $this.csf_reload_script();

          }

        });

      }

    });
  };

  //
  // Shortcode Framework
  //
  $.fn.csf_shortcode = function () {

    var base = this;

    base.shortcode_parse = function (serialize, key) {

      var shortcode = '';

      $.each(serialize, function (shortcode_key, shortcode_values) {

        key = (key) ? key : shortcode_key;

        shortcode += '[' + key;

        $.each(shortcode_values, function (shortcode_tag, shortcode_value) {

          if (shortcode_tag === 'content') {

            shortcode += ']';
            shortcode += shortcode_value;
            shortcode += '[/' + key + '';

          } else {

            shortcode += base.shortcode_tags(shortcode_tag, shortcode_value);

          }

        });

        shortcode += ']';

      });

      return shortcode;

    };

    base.shortcode_tags = function (shortcode_tag, shortcode_value) {

      var shortcode = '';

      if (shortcode_value !== '') {

        if (typeof shortcode_value === 'object' && !$.isArray(shortcode_value)) {

          $.each(shortcode_value, function (sub_shortcode_tag, sub_shortcode_value) {

            // sanitize spesific key/value
            switch (sub_shortcode_tag) {

              case 'background-image':
                sub_shortcode_value = (sub_shortcode_value.url) ? sub_shortcode_value.url : '';
                break;

            }

            if (sub_shortcode_value !== '') {
              shortcode += ' ' + sub_shortcode_tag.replace('-', '_') + '="' + sub_shortcode_value.toString() + '"';
            }

          });

        } else {

          shortcode += ' ' + shortcode_tag.replace('-', '_') + '="' + shortcode_value.toString() + '"';

        }

      }

      return shortcode;

    };

    base.insertAtChars = function (_this, currentValue) {

      var obj = (typeof _this[0].name !== 'undefined') ? _this[0] : _this;

      if (obj.value.length && typeof obj.selectionStart !== 'undefined') {
        obj.focus();
        return obj.value.substring(0, obj.selectionStart) + currentValue + obj.value.substring(obj.selectionEnd, obj.value.length);
      } else {
        obj.focus();
        return currentValue;
      }

    };

    base.send_to_editor = function (html, editor_id) {

      var tinymce_editor;

      if (typeof tinymce !== 'undefined') {
        tinymce_editor = tinymce.get(editor_id);
      }

      if (tinymce_editor && !tinymce_editor.isHidden()) {
        tinymce_editor.execCommand('mceInsertContent', false, html);
      } else {
        var $editor = $('#' + editor_id);
        $editor.val(base.insertAtChars($editor, html)).trigger('change');
      }

    };

    return this.each(function () {

      var $modal = $(this),
        $load = $modal.find('.csf-modal-load'),
        $content = $modal.find('.csf-modal-content'),
        $insert = $modal.find('.csf-modal-insert'),
        $loading = $modal.find('.csf-modal-loading'),
        $select = $modal.find('select'),
        modal_id = $modal.data('modal-id'),
        nonce = $modal.data('nonce'),
        editor_id,
        target_id,
        gutenberg_id,
        sc_key,
        sc_name,
        sc_view,
        sc_group,
        $cloned,
        $button;

      $(document).on('click', '.csf-shortcode-button[data-modal-id="' + modal_id + '"]', function (e) {

        e.preventDefault();

        $button = $(this);
        editor_id = $button.data('editor-id') || false;
        target_id = $button.data('target-id') || false;
        gutenberg_id = $button.data('gutenberg-id') || false;

        $modal.removeClass('hidden');

        // single usage trigger first shortcode
        if ($modal.hasClass('csf-shortcode-single') && sc_name === undefined) {
          $select.trigger('change');
        }

      });

      $select.on('change', function () {

        var $option = $(this);
        var $selected = $option.find(':selected');

        sc_key = $option.val();
        sc_name = $selected.data('shortcode');
        sc_view = $selected.data('view') || 'normal';
        sc_group = $selected.data('group') || sc_name;

        $load.empty();

        if (sc_key) {

          $loading.show();

          window.wp.ajax.post('csf-get-shortcode-' + modal_id, {
            shortcode_key: sc_key,
            nonce: nonce
          })
            .done(function (response) {

              $loading.hide();

              var $appended = $(response.content).appendTo($load);

              $insert.parent().removeClass('hidden');

              $cloned = $appended.find('.csf--repeat-shortcode').csf_clone();

              $appended.csf_reload_script();
              $appended.find('.csf-fields').csf_reload_script();

            });

        } else {

          $insert.parent().addClass('hidden');

        }

      });

      $insert.on('click', function (e) {

        e.preventDefault();

        if ($insert.prop('disabled') || $insert.attr('disabled')) { return; }

        var shortcode = '';
        var serialize = $modal.find('.csf-field:not(.csf-depend-on)').find(':input:not(.ignore)').serializeObjectSPF_SMART_BRANDS();

        switch (sc_view) {

          case 'contents':
            var contentsObj = (sc_name) ? serialize[sc_name] : serialize;
            $.each(contentsObj, function (sc_key, sc_value) {
              var sc_tag = (sc_name) ? sc_name : sc_key;
              shortcode += '[' + sc_tag + ']' + sc_value + '[/' + sc_tag + ']';
            });
            break;

          case 'group':

            shortcode += '[' + sc_name;
            $.each(serialize[sc_name], function (sc_key, sc_value) {
              shortcode += base.shortcode_tags(sc_key, sc_value);
            });
            shortcode += ']';
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            shortcode += '[/' + sc_name + ']';

            break;

          case 'repeater':
            shortcode += base.shortcode_parse(serialize[sc_group], sc_group);
            break;

          default:
            shortcode += base.shortcode_parse(serialize);
            break;

        }

        shortcode = (shortcode === '') ? '[' + sc_name + ']' : shortcode;

        if (gutenberg_id) {

          var content = window.csf_gutenberg_props.attributes.hasOwnProperty('shortcode') ? window.csf_gutenberg_props.attributes.shortcode : '';
          window.csf_gutenberg_props.setAttributes({ shortcode: content + shortcode });

        } else if (editor_id) {

          base.send_to_editor(shortcode, editor_id);

        } else {

          var $textarea = (target_id) ? $(target_id) : $button.parent().find('textarea');
          $textarea.val(base.insertAtChars($textarea, shortcode)).trigger('change');

        }

        $modal.addClass('hidden');

      });

      $modal.on('click', '.csf--repeat-button', function (e) {

        e.preventDefault();

        var $repeatable = $modal.find('.csf--repeatable');
        var $new_clone = $cloned.csf_clone();
        var $remove_btn = $new_clone.find('.csf-repeat-remove');

        var $appended = $new_clone.appendTo($repeatable);

        $new_clone.find('.csf-fields').csf_reload_script();

        SPF_SMART_BRANDS.helper.name_nested_replace($modal.find('.csf--repeat-shortcode'), sc_group);

        $remove_btn.on('click', function () {

          $new_clone.remove();

          SPF_SMART_BRANDS.helper.name_nested_replace($modal.find('.csf--repeat-shortcode'), sc_group);

        });

      });

      $modal.on('click', '.csf-modal-close, .csf-modal-overlay', function () {
        $modal.addClass('hidden');
      });

    });
  };

  //
  // WP Color Picker
  //
  if (typeof Color === 'function') {

    Color.prototype.toString = function () {

      if (this._alpha < 1) {
        return this.toCSS('rgba', this._alpha).replace(/\s+/g, '');
      }

      var hex = parseInt(this._color, 10).toString(16);

      if (this.error) { return ''; }

      if (hex.length < 6) {
        for (var i = 6 - hex.length - 1; i >= 0; i--) {
          hex = '0' + hex;
        }
      }

      return '#' + hex;

    };

  }

  SPF_SMART_BRANDS.funcs.parse_color = function (color) {

    var value = color.replace(/\s+/g, ''),
      trans = (value.indexOf('rgba') !== -1) ? parseFloat(value.replace(/^.*,(.+)\)/, '$1') * 100) : 100,
      rgba = (trans < 100) ? true : false;

    return { value: value, transparent: trans, rgba: rgba };

  };

  $.fn.csf_color = function () {
    return this.each(function () {

      var $input = $(this),
        picker_color = SPF_SMART_BRANDS.funcs.parse_color($input.val()),
        palette_color = window.csf_vars.color_palette.length ? window.csf_vars.color_palette : true,
        $container;

      // Destroy and Reinit
      if ($input.hasClass('wp-color-picker')) {
        $input.closest('.wp-picker-container').after($input).remove();
      }

      $input.wpColorPicker({
        palettes: palette_color,
        change: function (event, ui) {

          var ui_color_value = ui.color.toString();

          $container.removeClass('csf--transparent-active');
          $container.find('.csf--transparent-offset').css('background-color', ui_color_value);
          $input.val(ui_color_value).trigger('change');

        },
        create: function () {

          $container = $input.closest('.wp-picker-container');

          var a8cIris = $input.data('a8cIris'),
            $transparent_wrap = $('<div class="csf--transparent-wrap">' +
              '<div class="csf--transparent-slider"></div>' +
              '<div class="csf--transparent-offset"></div>' +
              '<div class="csf--transparent-text"></div>' +
              '<div class="csf--transparent-button">transparent <i class="fas fa-toggle-off"></i></div>' +
              '</div>').appendTo($container.find('.wp-picker-holder')),
            $transparent_slider = $transparent_wrap.find('.csf--transparent-slider'),
            $transparent_text = $transparent_wrap.find('.csf--transparent-text'),
            $transparent_offset = $transparent_wrap.find('.csf--transparent-offset'),
            $transparent_button = $transparent_wrap.find('.csf--transparent-button');

          if ($input.val() === 'transparent') {
            $container.addClass('csf--transparent-active');
          }

          $transparent_button.on('click', function () {
            if ($input.val() !== 'transparent') {
              $input.val('transparent').trigger('change').removeClass('iris-error');
              $container.addClass('csf--transparent-active');
            } else {
              $input.val(a8cIris._color.toString()).trigger('change');
              $container.removeClass('csf--transparent-active');
            }
          });

          $transparent_slider.slider({
            value: picker_color.transparent,
            step: 1,
            min: 0,
            max: 100,
            slide: function (event, ui) {

              var slide_value = parseFloat(ui.value / 100);
              a8cIris._color._alpha = slide_value;
              $input.wpColorPicker('color', a8cIris._color.toString());
              $transparent_text.text((slide_value === 1 || slide_value === 0 ? '' : slide_value));

            },
            create: function () {

              var slide_value = parseFloat(picker_color.transparent / 100),
                text_value = slide_value < 1 ? slide_value : '';

              $transparent_text.text(text_value);
              $transparent_offset.css('background-color', picker_color.value);

              $container.on('click', '.wp-picker-clear', function () {

                a8cIris._color._alpha = 1;
                $transparent_text.text('');
                $transparent_slider.slider('option', 'value', 100);
                $container.removeClass('csf--transparent-active');
                $input.trigger('change');

              });

              $container.on('click', '.wp-picker-default', function () {

                var default_color = SPF_SMART_BRANDS.funcs.parse_color($input.data('default-color')),
                  default_value = parseFloat(default_color.transparent / 100),
                  default_text = default_value < 1 ? default_value : '';

                a8cIris._color._alpha = default_value;
                $transparent_text.text(default_text);
                $transparent_slider.slider('option', 'value', default_color.transparent);

                if (default_color.value === 'transparent') {
                  $input.removeClass('iris-error');
                  $container.addClass('csf--transparent-active');
                }

              });

            }
          });
        }
      });

    });
  };

  //
  // ChosenJS
  //
  $.fn.csf_chosen = function () {
    return this.each(function () {

      var $this = $(this),
        $inited = $this.parent().find('.chosen-container'),
        is_sortable = $this.hasClass('csf-chosen-sortable') || false,
        is_ajax = $this.hasClass('csf-chosen-ajax') || false,
        is_multiple = $this.attr('multiple') || false,
        set_width = is_multiple ? '100%' : 'auto',
        set_options = $.extend({
          allow_single_deselect: true,
          disable_search_threshold: 10,
          width: set_width,
          no_results_text: window.csf_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

      if ($inited.length) {
        $inited.remove();
      }

      // Chosen ajax
      if (is_ajax) {

        var set_ajax_options = $.extend({
          data: {
            type: 'post',
            nonce: '',
          },
          allow_single_deselect: true,
          disable_search_threshold: -1,
          width: '100%',
          min_length: 3,
          type_delay: 500,
          typing_text: window.csf_vars.i18n.typing_text,
          searching_text: window.csf_vars.i18n.searching_text,
          no_results_text: window.csf_vars.i18n.no_results_text,
        }, $this.data('chosen-settings'));

        $this.SPF_SMART_BRANDSAjaxChosen(set_ajax_options);

      } else {

        $this.chosen(set_options);

      }

      // Chosen keep options order
      if (is_multiple) {

        var $hidden_select = $this.parent().find('.csf-hide-select');
        var $hidden_value = $hidden_select.val() || [];

        $this.on('change', function (obj, result) {

          if (result && result.selected) {
            $hidden_select.append('<option value="' + result.selected + '" selected="selected">' + result.selected + '</option>');
          } else if (result && result.deselected) {
            $hidden_select.find('option[value="' + result.deselected + '"]').remove();
          }

          // Force customize refresh
          if (window.wp.customize !== undefined && $hidden_select.children().length === 0 && $hidden_select.data('customize-setting-link')) {
            window.wp.customize.control($hidden_select.data('customize-setting-link')).setting.set('');
          }

          $hidden_select.trigger('change');

        });

        // Chosen order abstract
        $this.SPF_SMART_BRANDSChosenOrder($hidden_value, true);

      }

      // Chosen sortable
      if (is_sortable) {

        var $chosen_container = $this.parent().find('.chosen-container');
        var $chosen_choices = $chosen_container.find('.chosen-choices');

        $chosen_choices.bind('mousedown', function (event) {
          if ($(event.target).is('span')) {
            event.stopPropagation();
          }
        });

        $chosen_choices.sortable({
          items: 'li:not(.search-field)',
          helper: 'orginal',
          cursor: 'move',
          placeholder: 'search-choice-placeholder',
          start: function (e, ui) {
            ui.placeholder.width(ui.item.innerWidth());
            ui.placeholder.height(ui.item.innerHeight());
          },
          update: function (e, ui) {

            var select_options = '';
            var chosen_object = $this.data('chosen');
            var $prev_select = $this.parent().find('.csf-hide-select');

            $chosen_choices.find('.search-choice-close').each(function () {
              var option_array_index = $(this).data('option-array-index');
              $.each(chosen_object.results_data, function (index, data) {
                if (data.array_index === option_array_index) {
                  select_options += '<option value="' + data.value + '" selected>' + data.value + '</option>';
                }
              });
            });

            $prev_select.children().remove();
            $prev_select.append(select_options);
            $prev_select.trigger('change');

          }
        });

      }

    });
  };

  //
  // Helper Checkbox Checker
  //
  $.fn.csf_checkbox = function () {
    return this.each(function () {

      var $this = $(this),
        $input = $this.find('.csf--input'),
        $checkbox = $this.find('.csf--checkbox');

      $checkbox.on('click', function () {
        $input.val(Number($checkbox.prop('checked'))).trigger('change');
      });

    });
  };

  //
  // Siblings
  //
  $.fn.csf_siblings = function () {
    return this.each(function () {

      var $this = $(this),
        $siblings = $this.find('.csf--sibling'),
        multiple = $this.data('multiple') || false;

      $siblings.on('click', function () {

        var $sibling = $(this);

        if (multiple) {

          if ($sibling.hasClass('csf--active')) {
            $sibling.removeClass('csf--active');
            $sibling.find('input').prop('checked', false).trigger('change');
          } else {
            $sibling.addClass('csf--active');
            $sibling.find('input').prop('checked', true).trigger('change');
          }

        } else {

          $this.find('input').prop('checked', false);
          $sibling.find('input').prop('checked', true).trigger('change');
          $sibling.addClass('csf--active').siblings().removeClass('csf--active');

        }

      });

    });
  };

  //
  // Help Tooltip
  //
  $.fn.csf_help = function () {
    return this.each(function () {

      var $this = $(this),
        $tooltip,
        offset_left,
        $class;

      $this.on({
        mouseenter: function () {
          // this class add with the support tooltip.
          if ($this.find('.sp_brand-support').length > 0) {
            $class = 'support-tooltip';
          }

          var help_text = $this.find('.csf-help-text').html();
          if ($('.sp_brand-tooltip').length > 0) {
            $tooltip = $('.sp_brand-tooltip').html(help_text);
          } else {
            $tooltip = $('<div class="sp_brand-tooltip ' + $class + '"></div>').html(help_text).appendTo('body');
          }

          offset_left = SPF_SMART_BRANDS.vars.is_rtl
            ? $this.offset().left + 36
            : $this.offset().left + 36

          $tooltip.css({
            top: $this.offset().top - (($tooltip.outerHeight() / 2) - 14),
            left: offset_left,
            textAlign: 'left',
          });
        },
        mouseleave: function () {
          if (!$tooltip.is(':hover')) {
            $tooltip.remove();
          }
        }
      });

      // Event delegation to handle tooltip removal when the cursor leaves the tooltip itself.
      $('body').on('mouseleave', '.sp_brand-tooltip', function () {
        if ($tooltip !== undefined) {
          $tooltip.remove();
        }
      });
    });
  };

  //
  // Customize Refresh
  //
  $.fn.csf_customizer_refresh = function () {
    return this.each(function () {

      var $this = $(this),
        $complex = $this.closest('.csf-customize-complex');

      if ($complex.length) {

        var unique_id = $complex.data('unique-id');

        if (unique_id === undefined) {
          return;
        }

        var $input = $complex.find(':input'),
          option_id = $complex.data('option-id'),
          obj = $input.serializeObjectSPF_SMART_BRANDS(),
          data = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '',
          control = window.wp.customize.control(unique_id + '[' + option_id + ']');

        // clear the value to force refresh.
        control.setting._value = null;

        control.setting.set(data);

      } else {

        $this.find(':input').first().trigger('change');

      }

      $(document).trigger('csf-customizer-refresh', $this);

    });
  };

  //
  // Customize Listen Form Elements
  //
  $.fn.csf_customizer_listen = function (options) {

    var settings = $.extend({
      closest: false,
    }, options);

    return this.each(function () {

      if (window.wp.customize === undefined) { return; }

      var $this = (settings.closest) ? $(this).closest('.csf-customize-complex') : $(this),
        $input = $this.find(':input'),
        unique_id = $this.data('unique-id'),
        option_id = $this.data('option-id');

      if (unique_id === undefined) {
        return;
      }

      $input.on('change keyup', function () {

        var obj = $this.find(':input').serializeObjectSPF_SMART_BRANDS();
        var val = (!$.isEmptyObject(obj) && obj[unique_id] && obj[unique_id][option_id]) ? obj[unique_id][option_id] : '';

        window.wp.customize.control(unique_id + '[' + option_id + ']').setting.set(val);

      });

    });
  };

  //
  // Customizer Listener for Reload JS
  //
  $(document).on('expanded', '.control-section', function () {

    var $this = $(this);

    if ($this.hasClass('open') && !$this.data('inited')) {

      var $fields = $this.find('.csf-customize-field');
      var $complex = $this.find('.csf-customize-complex');

      if ($fields.length) {
        $this.csf_dependency();
        $fields.csf_reload_script({ dependency: false });
        $complex.csf_customizer_listen();
      }

      $this.data('inited', true);

    }

  });

  //
  // Window on resize
  //
  SPF_SMART_BRANDS.vars.$window.on('resize csf.resize', SPF_SMART_BRANDS.helper.debounce(function (event) {

    var window_width = navigator.userAgent.indexOf('AppleWebKit/') > -1 ? SPF_SMART_BRANDS.vars.$window.width() : window.innerWidth;

    if (window_width <= 782 && !SPF_SMART_BRANDS.vars.onloaded) {
      $('.csf-section').csf_reload_script();
      SPF_SMART_BRANDS.vars.onloaded = true;
    }

  }, 200)).trigger('csf.resize');

  //
  // Widgets Framework
  //
  $.fn.csf_widgets = function () {
    return this.each(function () {

      $(document).on('widget-added widget-updated', function (event, $widget) {

        var $fields = $widget.find('.csf-fields');

        if ($fields.length) {
          $fields.csf_reload_script();
        }

      });

      $(document).on('click', '.widget-top', function (event) {

        var $fields = $(this).parent().find('.csf-fields');

        if ($fields.length) {
          $fields.csf_reload_script();
        }

      });

      $('.widgets-sortables, .control-section-sidebar').on('sortstop', function (event, ui) {
        ui.item.find('.csf-fields').csf_reload_script_retry();
      });

    });
  };

  //
  // Nav Menu Options Framework
  //
  $.fn.csf_nav_menu = function () {
    return this.each(function () {

      var $navmenu = $(this);
      $navmenu.on('click', 'a.item-edit', function () {
        $(this).closest('li.menu-item').find('.csf-fields').csf_reload_script();
      });

      $navmenu.on('sortstop', function (event, ui) {
        ui.item.find('.csf-fields').csf_reload_script_retry();
      });
    });
  };

  //
  // Retry Plugins
  //
  $.fn.csf_reload_script_retry = function () {
    return this.each(function () {

      var $this = $(this);
    });
  };

  //
  // Reload Plugins
  //
  $.fn.csf_reload_script = function (options) {

    var settings = $.extend({
      dependency: true,
    }, options);

    return this.each(function () {

      var $this = $(this);

      // Avoid for conflicts
      if (!$this.data('inited')) {

        // Field plugins
        $this.children('.csf-field-background').csf_field_background();
        $this.children('.csf-field-code_editor').csf_field_code_editor();
        $this.children('.csf-field-spinner').csf_field_spinner();
        $this.children('.csf-field-switcher').csf_field_switcher();
        $this.children('.csf-field-media').csf_field_media();
        $this.children('.csf-field-slider').csf_field_slider();
        $this.children('.csf-field-sortable').csf_field_sortable();
        // Field colors
        $this.children('.csf-field-border').find('.csf-color').csf_color();
        $this.children('.csf-field-color').find('.csf-color').csf_color();
        $this.children('.csf-field-color_group').find('.csf-color').csf_color();
        // Field chosenjs
        $this.children('.csf-field-select').find('.csf-chosen').csf_chosen();

        // Field Checkbox
        $this.children('.csf-field-checkbox').find('.csf-checkbox').csf_checkbox();

        // Field Siblings
        $this.children('.csf-field-button_set').find('.csf-siblings').csf_siblings();
        $this.children('.csf-field-layout_preset').find('.csf-siblings').csf_siblings();

        // Help Tooptip
        $this.children('.csf-field').find('.csf-help').csf_help();

        if (settings.dependency) {
          $this.csf_dependency();
        }

        $this.data('inited', true);

        $(document).trigger('csf-reload-script', $this);
      }
    });
  };

  //
  // Document ready and run scripts
  //
  $(document).ready(function () {

    $('.csf-save').csf_save();
    $('.csf-options').csf_options();
    $('.csf-sticky-header').csf_sticky();
    $('.csf-nav-options').csf_nav_options();
    $('.csf-nav-metabox').csf_nav_metabox();
    $('.csf-taxonomy').csf_taxonomy();
    $('.csf-page-templates').csf_page_templates();
    $('.csf-post-formats').csf_post_formats();
    $('.csf-shortcode').csf_shortcode();
    $('.csf-search').csf_search();
    $('.csf-confirm').csf_confirm();
    $('.csf-expand-all').csf_expand_all();
    $('.csf-onload').csf_reload_script();
    $('#widgets-editor').csf_widgets();
    $('#widgets-right').csf_widgets();
    $('#menu-to-edit').csf_nav_menu();

  });

  // Live Preview script for Logo Carousel.
  var preview_box = $('#sp-brand-preview-box');
  var preview_display = $('#sp-brand_live_preview').hide();
  $(document).on('click', '#sp-brand-show-preview:contains(Hide)', function (e) {
    e.preventDefault();
    var _this = $(this);
    _this.html('<i class="fa fa-eye" aria-hidden="true"></i> Show Preview');
    preview_box.html('');
    preview_display.hide();
  });

  $(document).on('click', '#sp-brand-show-preview:not(:contains(Hide))', function (e) {
    e.preventDefault();
    var previewJS = window.csf_vars.previewJS;
    var _data = $('form#post').serialize();
    var _this = $(this);
    var data = {
      action: 'smart_brands_preview_meta_box',
      data: _data,
      ajax_nonce: $('#csf_metabox_noncesp_smart_brand_metaboxes').val()
    };
    $.ajax({
      type: "POST",
      url: ajaxurl,
      data: data,
      error: function (response) {
        console.log(response)
      },
      success: function (response) {
        preview_display.show();
        preview_box.html(response);
        $.getScript(previewJS, function () {
          _this.html('<i class="fa fa-eye-slash" aria-hidden="true"></i> Hide Preview');
          $(document).on('keyup change', '.post-type-smart_brand_sc', function (e) {
            e.preventDefault();
            _this.html('<i class="fa fa-refresh" aria-hidden="true"></i> Update Preview');
          });
          $("html, body").animate({ scrollTop: preview_display.offset().top - 50 }, "slow");
        });
      }
    })
  });

  /* Custom JavaScript. */
  $(document).on('keyup change', '#csf-form', function (e) {
    e.preventDefault();
    var $button = $(this).find('.csf-save.csf-save-ajax');
    $button.css({ "background-color": "#00C263", "pointer-events": "initial" }).val('Save Settings');
    $button.attr('value', 'Save Settings').attr('disabled', false);
  });
  $(".csf-save").click(function (e) {
    e.preventDefault();
    $(this).css({ "background-color": "#C5C5C6", "pointer-events": "none" }).val('Changes Saved');
  })

})(jQuery, window, document);

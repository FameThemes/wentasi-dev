
( function( api ) {

	api.controlConstructor['typography'] = api.Control.extend( {
		ready: function() {
			var control = this;

            // console.log( control );

            control.selectFontOptions( window.typographyWebfonts );
            jQuery( '.typography-font-family select', control.container).html( window.fontFamiliesOptions );
            control.setupDefaultFields();

			control.container.on( 'change', 'select.font-family',
				function() {
                    var v = jQuery( this ).val();
                    control.setFontVariants( v );
				}
			);

            control.container.on( 'change', 'select, input',
                function() {
                    var v = jQuery( this ).val();
                    control.setValues( v );
                }
            );

		},

        getFontId: function( fontName ){
            var font_id = fontName.toLowerCase();
            return font_id.replace(/ /g, '-');
        },

        getStyle: function( fontWeight, fontStyle ){
            var style = '';
            style += fontWeight;
            if ( fontWeight !== '' ){
                style += fontStyle;
            } else {

                if ( fontStyle === 'normal' ) {
                    style = 'regular';
                } else if ( fontStyle === 'regular' ) {
                    style = fontStyle;
                } else {
                    style += fontStyle;
                }

            }

            return style;
        },

        setupDefaultFields: function(){

            var control = this, v, v_unit;
            if ( control.params.value.length <= 0 ) {
                return ;
            }
            var values = JSON.parse( control.params.value );
            //console.log( values );

            values = jQuery.extend( true, {
                    'font-family'     : '',
                    'font-color'      : '',
                    'font-style'      : '',
                    'font-weight'     : '',
                    'font-size'       : '',
                    'line-height'     : '',
                    'letter-spacing'  : '',
                    'text-transform'  : '',
                    'text-decoration' : '',
                }, values );


            var font_id = control.getFontId( values['font-family'] ),
                style = control.getStyle( values['font-weight'], values['font-style'] );

            control.setFontVariants( font_id );

            if ( control.params.fields.font_family ) {
                jQuery('.font-family', control.container).find('option[value="' + font_id+ '"]').attr('selected', 'selected');
            }

            if ( control.params.fields.font_family && control.params.fields.font_style ) {
                jQuery('.font-style', control.container).find('option[value="' + ( style ) + '"]').attr('selected', 'selected');
            }

            if ( control.params.fields.font_size ) {
                // font size
                v = parseFloat( values['font-size'] );
                v_unit = values['font-size'].replace(/([0-9]+)/i, '');
                jQuery('.font-size', control.container).val( v );
                jQuery('.font-size-unit', control.container).find('option[value="' + v_unit + '"]').attr('selected', 'selected');
            }

            // Line height
            if ( control.params.fields.line_height ) {
                v = parseFloat(values['line-height']);
                v_unit = values['line-height'].replace(/([0-9]+)/i, '');
                jQuery('.line-height', control.container).val(v);
                jQuery('.line-height-unit', control.container).find('option[value="' + v_unit + '"]').attr('selected', 'selected');
            }

            // Letter spacing
            if ( control.params.fields.letter_spacing ) {
                v = parseFloat(values['letter-spacing']);
                v_unit = values['letter-spacing'].replace(/([0-9]+)/i, '');
                jQuery('.letter-spacing', control.container).val(v);
                jQuery('.letter-spacing', control.container).find('option[value="' + v_unit + '"]').attr('selected', 'selected');
            }

            // text decoration
            if ( control.params.fields.text_decoration ) {
                jQuery('.text-decoration', control.container).find('option[value="' + ( values['text-decoration'] ) + '"]').attr('selected', 'selected');
            }

            // text transform
            if ( control.params.fields.text_transform ) {
                jQuery('.text-transform', control.container).find('option[value="' + ( values['text-transform'] ) + '"]').attr('selected', 'selected');
            }

            // text Color
            if ( control.params.fields.font_color ) {
                jQuery('.text-color', control.container).val(values.color);

                jQuery('.text-color', control.container).wpColorPicker({
                    change: function (event, ui) {
                        control.setValues(v);
                    },
                });
            }



        },


        setValues: function( ){
            var control = this;

            var css = {}, font = {};

            if ( control.params.fields.font_size ) {
                css['font-size'] =  jQuery( '.font-size', control.container ).val() || '';
                if ( css['font-size'] !== '' ) {
                    css['font-size'] += 'px';
                }
            }

            if ( control.params.fields.line_height ) {
                css['line-height'] = jQuery('.line-height', control.container).val() || '';
                if (css['line-height'] !== '') {
                    css['line-height'] += 'px';
                }
            }

            if ( control.params.fields.letter_spacing ) {
                css['letter-spacing'] = jQuery('.letter-spacing', control.container).val() || '';
                if (css['letter-spacing'] !== '') {
                    css['letter-spacing'] += 'px';
                }
            }
            if ( control.params.fields.text_decoration ) {
                css['text-decoration'] = jQuery('.text-decoration', control.container).val() || '';
            }
            if ( control.params.fields.text_transform ) {
                css['text-transform'] = jQuery('.text-transform', control.container).val() || '';
            }
            if ( control.params.fields.font_color ) {
                css['color'] = jQuery('.text-color', control.container).val() || '';
            }

            if ( control.params.fields.font_family && control.params.fields.font_style ) {
                var _style = jQuery('select.font-style', control.container).val() || '';
                var style;
                var weight = parseInt(_style);
                if (isNaN(weight)) {
                    weight = '';
                    if (_style !== 'regular') {
                        style = _style;
                    } else {
                        style = 'normal';
                    }
                } else {
                    style = _style.slice(weight.toString().length);
                }

                if (style === '') {
                    style = 'normal';
                }

                css['font-style'] = style;
                css['font-weight'] = weight;
            }

            if ( control.params.fields.font_family ) {
                var font_id = jQuery('.font-family', control.container).val();
                var font_url = '';
                if (typeof font_id !== 'undefined') {
                    if (typeof window.typographyWebfonts[font_id] !== 'undefined') {
                        font = window.typographyWebfonts[font_id];
                        css['font-family'] = font.name;
                        font_url = font.url;
                    }
                }
            }

             var data = {
                 font_id : font_id,
                 //font : font,
                 style : _style,
                 css_selector : control.params.css_selector,
                 css : css,
                 font_url : font_url,
             };

            control.settings['default'].set( JSON.stringify( css ) );
            control.preview( data );

        },

        preview: function( settings ){
            var frame = jQuery("#customize-preview iframe").contents();
            //console.log( settings );
            if ( settings.font_url ) {
                var id = 'google-font-' + settings.font_id;
                if ( jQuery( '#'+id ).length > 0 ){
                    jQuery( '#'+id).remove();
                }
                jQuery( 'head', frame ).append('<link id="'+id+'" rel="stylesheet" href="' + settings.font_url + '" type="text/css" />');
            }

            jQuery( settings.css_selector, frame).removeAttr( 'style' );
            jQuery( settings.css_selector, frame ).css( settings.css );

        },

        setFontVariants: function( font_id ){
            // font_weights
            var control = this, output = '';

            //console.log( font_id );
            output = '<option value="">' + control.params.labels.option_default + '</option>';

            if ( typeof window.typographyWebfonts[ font_id ] !== 'undefined' && font_id !== '' ) {

                _.each( window.typographyWebfonts[ font_id ]['font_weights'], function (value, id) {
                    output += '<option value="' + value + '">' + control.getWeightLabel(value) + '</option>'
                });

                if (window.typographyWebfonts[font_id]['font_weights'].length <= 1) {
                    output += '<option value="italic">' + control.getWeightLabel('italic') + '</option>'
                    output += '<option value="700">' + control.getWeightLabel('700') + '</option>'
                    output += '<option value="700italic">' + control.getWeightLabel('700italic') + '</option>'
                }
            }

            jQuery('.typography-font-style select', control.container ).html( output  );

        },

        getWeightLabel: function( weight ){
            if(  typeof window.fontStyleLabels[ weight ] !== "undefined" ){
                return window.fontStyleLabels[ weight ];
            } else {
                return weight;
            }
        },

        selectFontOptions: function( fonts ){
            var control = this;

            if ( typeof window.fontFamiliesOptions === "undefined" ) {
                var fontOptions = {};

                _.each( fonts, function (font, id) {

                    var html = '<option value="' + id + '">' + font.name + '</option>';

                    if (typeof ( font.font_type ) === "undefined" || font.font_type === '') {
                        font.font_type = 'default';
                    }

                    if (typeof fontOptions[font.font_type] === "undefined") {
                        fontOptions[font.font_type] = {};
                    }
                    fontOptions[font.font_type][id] = html;

                });

                var optionsSelect = '';

                _.each(fontOptions, function (v, id ) {
                    if (typeof v !== 'string') {

                        if ( id === 'google_font' ) {
                            optionsSelect += ' <optgroup class="lv-1" label="' + id + '"></optgroup>';
                        } else {
                            optionsSelect += ' <optgroup class="lv-1" label="' + id + '">';
                        }

                        _.each(v, function (v2, id2) {
                            if (typeof v2 !== 'string') {
                                optionsSelect += ' <optgroup class="lv-2" label="' + id2 + '">';
                                _.each(v2, function (v3, id3) {
                                    if (typeof v3 === 'string') {
                                        optionsSelect += v3;
                                    }
                                });
                                optionsSelect += '</optgroup>';

                            } else {
                                optionsSelect += v2;
                            }

                        });

                        if ( id === 'google_font' ) {

                        } else {
                            optionsSelect += '</optgroup>';
                        }


                    } else {
                        optionsSelect += v;
                    }
                });
                window.fontFamiliesOptions = '<option value="">' + control.params.labels.option_default +'</option>'+optionsSelect;
            }
        }

	} );


} )( wp.customize );
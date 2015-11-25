
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

            control.container.on( 'click', '.typography-header', function( event ){
                event.preventDefault();
                jQuery( '.typography-settings', control.container ).slideToggle( );
            } );

            control.container.on( 'click', '.typography-close', function( event ){
                event.preventDefault();
                jQuery( '.typography-settings', control.container ).slideUp( );
            } );

		},

        setupDefaultFields: function(){
            var control = this, v, v_unit;
            if ( control.params.value.length <= 0 ) {
                return ;
            }
            var values = JSON.parse( control.params.value );
           // if ( typeof ( values ).length ) {}
            //console.log( values );
            control.setFontVariants( values.font_id );

            jQuery( '.font-family', control.container ).find( 'option[value="'+values.font_id+'"]').attr( 'selected', 'selected' );
            jQuery( '.font-style', control.container ).find( 'option[value="'+ ( values.css['font-weight']+values.css['font-style'] ) +'"]').attr( 'selected', 'selected' );

            // font size
            v = parseFloat( values.css['font-size'] );
            v_unit = values.css['font-size'].replace(/([0-9]+)/i, '');
            jQuery( '.font-size', control.container ).val( v );
            jQuery( '.font-size-unit', control.container ).find( 'option[value="'+ v_unit +'"]').attr( 'selected', 'selected' );

            // Line height
            v = parseFloat( values.css['line-height'] );
            v_unit = values.css['line-height'].replace(/([0-9]+)/i, '');
            jQuery( '.line-height', control.container ).val( v );
            jQuery( '.line-height-unit', control.container ).find( 'option[value="'+ v_unit +'"]').attr( 'selected', 'selected' );

            // Letter spacing
            v = parseFloat( values.css['letter-spacing'] );
            v_unit = values.css['letter-spacing'].replace(/([0-9]+)/i, '');
            jQuery( '.letter-spacing', control.container ).val( v );
            jQuery( '.letter-spacing', control.container ).find( 'option[value="'+ v_unit +'"]').attr( 'selected', 'selected' );

            // text decoration
            jQuery( '.text-decoration', control.container ).find( 'option[value="'+ ( values.css['text-decoration'] ) +'"]').attr( 'selected', 'selected' );

            // text transform
            jQuery( '.text-transform', control.container ).find( 'option[value="'+ ( values.css['text-transform'] ) +'"]').attr( 'selected', 'selected' );

            // text Color
            jQuery( '.text-color', control.container ).val( values.css.color );


            jQuery('.text-color', control.container ).wpColorPicker( {
                change: function( event, ui ){
                    control.setValues( v );
                },
            });

        },


        setValues: function( ){
            var control = this;

            var css = {}, font = {};

            css['font-size']        =  jQuery( '.font-size', control.container ).val() || '';
            if ( css['font-size'] !== '' ) {
                css['font-size'] += jQuery( '.font-size-unit', control.container ).val() || 'px';
            }
            css['line-height']      =  jQuery( '.line-height', control.container ).val() || '';

            if ( css['line-height'] !== '' ) {
                css['line-height'] += jQuery( '.line-height-unit', control.container ).val() || 'px';
            }

            css['letter-spacing']   =  jQuery( '.letter-spacing', control.container ).val() || '';

            if ( css['letter-spacing'] !== '' ) {
                css['letter-spacing'] += jQuery( '.letter-spacing-unit', control.container ).val() || 'px';
            }

            css['text-decoration']  =  jQuery( '.text-decoration', control.container ).val();
            css['text-transform']   =  jQuery( '.text-transform', control.container ).val();
            css['color']            =  jQuery( '.text-color', control.container ).val();

            var _style =  jQuery( 'select.font-style', control.container ).val() || '';
            var style;
            var weight = parseInt( _style );
            if ( isNaN( weight ) ) {
                weight = '';
                if ( _style !== 'regular' ) {
                    style = _style ;
                }else {
                    style = 'normal' ;
                }
            } else {
                style = _style.slice( weight.toString().length );
            }

            if ( style === '' ){
                style = 'normal' ;
            }

            css['font-style']  = style;
            css['font-weight'] = weight;

            var font_id      =  jQuery( '.font-family', control.container ).val();
            var font_url     =  '';
            if ( typeof font_id !== 'undefined' ) {
                if ( typeof window.typographyWebfonts[ font_id ] !== 'undefined'  ) {
                    font = window.typographyWebfonts[ font_id ];
                    css['font-family'] =  font.name;
                    font_url = font.url;
                }
            }

            // encodeURIComponent
             var data = {
                 font_id : font_id,
                 font : font,
                 style : _style,
                 css_selector : control.params.css_selector,
                 css : css,
                 font_url : font_url,
             };

            //console.log( data );
            control.settings['default'].set( JSON.stringify( data ) );
            control.preview( data );

        },

        preview: function( settings ){
            var frame = jQuery("#customize-preview iframe").contents();
            //console.log( control.frameBody );
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

            output = '<option value="">' + control.params.labels.option_default + '</option>';

            if ( font_id !== '' ) {

                _.each(window.typographyWebfonts[font_id]['font_weights'], function (value, id) {
                    output += '<option value="' + value + '">' + control.getWeightLabel(value) + '</option>'
                });

                if (window.typographyWebfonts[font_id]['font_weights'].length <= 1) {
                    output += '<option value="italic">' + control.getWeightLabel('italic') + '</option>'
                    output += '<option value="700">' + control.getWeightLabel('700') + '</option>'
                    output += '<option value="700italic">' + control.getWeightLabel('700italic') + '</option>'
                }
            }

            jQuery('.typography-font-style select', control.control ).html( output  );

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
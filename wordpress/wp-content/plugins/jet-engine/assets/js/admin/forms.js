var GridLayout = VueGridLayout.GridLayout;
var GridItem = VueGridLayout.GridItem;
var JEBookingFormBuilder = new Vue({
	el: '#form_builder',
	components: {
		GridLayout,
		GridItem,
	},
	data: {
		layout: JSON.parse( JSON.stringify( JetEngineFormSettings.form_data ) ),
		result: JSON.parse( JSON.stringify( JetEngineFormSettings.form_data ) ),
		index: 1,
		showEditor: false,
		currentItem: {},
		currentIndex: false,
		fieldTypes: JetEngineFormSettings.field_types,
		inputTypes:JetEngineFormSettings.input_types,
	},
	mounted: function () {
		this.index = this.layout.length;
	},
	computed: {
		resultJSON: function() {
			return JSON.stringify( this.result );
		},
	},
	methods: {
		inArray: function( needle, haystack ) {
			return -1 < haystack.indexOf( needle );
		},
		addRepeaterItem: function( items, item ) {
			items.push( item );
		},
		itemInstance: function( item ) {

			var instance = JetEngineFormSettings.labels.field;

			if ( item.settings.is_message ) {
				instance = JetEngineFormSettings.labels.message;
			}

			if ( item.settings.is_submit ) {
				instance = JetEngineFormSettings.labels.submit;
			}

			return instance;

		},
		currentWidth: function( width ) {
			switch( width ) {

				case 2:
					return '1/6';

				case 3:
					return '1/4';

				case 4:
					return '1/3';

				case 6:
					return '1/2';

				case 8:
					return '2/3';

				case 9:
					return '3/4';

				case 10:
					return '5/6';

				case 12:
					return 'Fullwidth';

				default:
					return width + '/12';
			}
		},
		editField: function( item, index ) {

			this.applyFieldChanges();

			this.currentItem  = item;
			this.currentIndex = index;
			this.showEditor   = true;

		},
		applyFieldChanges: function() {

			if ( false === this.currentIndex ) {
				return;
			}

			this.result.splice( this.currentIndex, 1, this.currentItem );

			this.currentItem  = {};
			this.currentIndex = false;
			this.showEditor   = false;

		},
		cancelFieldChanges: function() {

			this.currentItem  = {};
			this.currentIndex = false;
			this.showEditor   = false;

		},
		deleteRepeterItem: function( index, items ) {
			items.splice( index, 1 );
		},
		addField: function( isSubmit, isMessage ) {
			var maxY            = 0,
				currY           = 0,
				newItem         = {},
				defaultSettings = JSON.parse( JSON.stringify( JetEngineFormSettings.default_settings ) );

			defaultSettings.is_message = isMessage;
			defaultSettings.is_submit  = isSubmit;

			if ( isSubmit ) {
				defaultSettings.type      = 'submit';
				defaultSettings.name      = 'submit';
				defaultSettings.label     = 'Submit';
				defaultSettings.className = '';
			}

			for ( var i = 0; i < this.result.length; i++ ) {
				currY = this.result[ i ].y;
				if ( currY > maxY ) {
					maxY = currY;
				}
			}

			maxY++;

			newItem = {
				"x": 0,
				"y": maxY,
				"w": 12,
				"h": 1,
				"i": String(this.index),
				"settings": defaultSettings,
			};

			this.index++;

			this.layout.push( newItem );
			this.result.push( newItem );

		},
		updateLayout: function( newLayout ) {
			this.result.splice( 0, this.result.length );
			for ( var i = 0; i <= newLayout.length - 1; i++ ) {
				this.result.push( newLayout[ i ] );
			}
		},
		removeField: function( item, index ) {

			if ( ! confirm( JetEngineFormSettings.confirm_message ) ) {
				return;
			}

			this.layout.splice( index, 1 );
			this.reindexLayout();

			for ( var i = 0; i < this.result.length; i++ ) {
				if ( this.result[ i ].i == item.i ) {
					this.result.splice( i, 1 );
					return;
				}
			}

		},
		reindexLayout : function () {
			for ( var i = 0; i < this.layout.length; i++ ) {
				this.layout[i]['i'] = String( i );
			}
		}
	}
});

var JEBookingFormNotifications = new Vue({
	el: '#notifications_builder',
	data: {
		items: JSON.parse( JSON.stringify( JetEngineFormSettings.notifications_data ) ),
		index: 1,
		showEditor: false,
		currentItem: {},
		currentIndex: false,
		availableTypes: JetEngineFormSettings.notification_types,
		postTypes: JetEngineFormSettings.post_types,
		postStatuses: JetEngineFormSettings.post_statuses,
		userFields: JetEngineFormSettings.user_fields,
	},
	mounted: function() {

		var self = this;

		self.items.forEach( function( item, index ) {

			if ( item.fields_map && undefined !== item.fields_map.length) {
				item.fields_map = {};
				self.items.splice( index, 1, item );
			}

		} );

	},
	computed: {
		resultJSON: function() {
			return JSON.stringify( this.items );
		},
		availableFields: function() {

			var fields = [];

			if ( JEBookingFormBuilder.layout ) {
				JEBookingFormBuilder.layout.forEach( function( item ) {
					if ( 'submit' !== item.settings.type ) {
						fields.push( item.settings.name );
					}
				});
			}

			this.items.forEach( function( item ) {
				if ( 'register_user' === item.type && item.add_user_id ) {
					fields.push( 'user_id' );
				}
			});

			return fields;

		},
	},
	methods: {
		addField: function() {

			this.items.push( {
				'type': 'email',
				'mail_to': 'admin',
				'hook_name': 'send',
				'custom_email': '',
				'from_field': '',
				'post_type': '',
				'fields_map': {},
				'email': {},
			} );

		},
		editItem: function( item, index ) {

			this.applyItemChanges();

			this.currentItem  = item;
			this.currentIndex = index;
			this.showEditor   = true;

		},
		applyItemChanges: function() {

			if ( false === this.currentIndex ) {
				return;
			}

			this.items.splice( this.currentIndex, 1, this.currentItem );

			this.currentItem  = false;
			this.currentIndex = false;
			this.showEditor   = false;

		},
		cancelItemChanges: function() {

			this.currentItem  = false;
			this.currentIndex = false;
			this.showEditor   = false;

		},
		removeItem: function( item, index ) {

			if ( ! confirm( JetEngineFormSettings.confirm_message ) ) {
				return;
			}

			if( index === this.currentIndex && this.showEditor ){
				this.showEditor   = false;
			}

			this.items.splice( index, 1 );

		}
	}
});

function JEBookingFormSetMessages() {
	var $messages = jQuery('#messages-settings .messages-list'),
		messages_data = JetEngineFormSettings.messages;

	if( $messages.length ){
		jQuery.each( messages_data, function( message, value ) {
			$messages.find( 'input[name="_messages['+ message + ']"]' )[0].value = value;
		});
	}
}

jQuery( document ).ready( JEBookingFormSetMessages );
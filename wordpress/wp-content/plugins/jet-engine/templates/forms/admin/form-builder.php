<div id="form_builder">
	<grid-layout class="jet-form-canvas" v-if="!showEditor"
		:layout="layout"
		:col-num="12"
		:row-height="48"
		:margin="[5, 5]"
		:is-draggable="true"
		:is-resizable="true"
		:vertical-compact="true"
		:use-css-transforms="true"
		:style="{ margin: '0 -5px' }"
		@layout-updated="updateLayout"
	>
		<grid-item class="jet-form-canvas__field"
			v-for="( item, index ) in layout"
			:key="item.i"
			:x="item.x"
			:y="item.y"
			:w="item.w"
			:h="item.h"
			:i="item.i"
			:max-h="1"
		>
			<div class="jet-form-canvas__field-content">
				<div class="jet-form-canvas__field-start">
					<div class="jet-form-canvas__field-remove" @click="removeField( item, index )"></div>
					<div class="jet-form-canvas__field-label">
						<span class="jet-form-canvas__field-name">
							<span v-html="itemInstance( item )"></span>:&nbsp;
							<span v-if="'submit' === item.settings.type">{{ item.settings.label }}</span>
							<span v-else>{{ item.settings.name }}</span>
						</span>
						<span class="jet-form-canvas__field-type">Type: {{ item.settings.type }}</span>
					</div>
				</div>
				<div class="jet-form-canvas__field-end">
					<span>{{ currentWidth( item.w ) }}</span>
					<div class="jet-form-canvas__field-edit" @click="editField( item, index )">
						<span class="dashicons dashicons-edit"></span>
					</div>
				</div>
			</div>
		</grid-item>
	</grid-layout>
	<div class="jet-form-canvas__actions" v-if="!showEditor">
		<button type="button" class="jet-form-canvas__add" @click="addField( false, false )"><?php
			_e( 'Add Field', 'jet-engine' );
		?></button>
		<button type="button" class="jet-form-canvas__add add-default" @click="addField( true, false )"><?php
			_e( 'Add Submit Button', 'jet-engine' );
		?></button>
	</div>
	<div class="jet-form-canvas__result">
		<textarea name="_form_data">{{ resultJSON }}</textarea>
	</div>
	<div class="jet-form-editor" v-if="showEditor">
		<div class="jet-form-editor__header">
			<span v-html="itemInstance( currentItem )"></span>: {{ currentItem.settings.name }}
		</div>
		<div class="jet-form-editor__content"
			v-if="true === currentItem.settings.is_submit"
		>
			<div class="jet-form-editor__row">
				<div class="jet-form-editor__row-label"><?php _e( 'Label:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.label">
				</div>
			</div>
			<div class="jet-form-editor__row">
				<div class="jet-form-editor__row-label"><?php _e( 'Custom CSS Class:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.class_name">
				</div>
			</div>
		</div>
		<div class="jet-form-editor__content"
			v-if="false === currentItem.settings.is_message && false === currentItem.settings.is_submit"
		>
			<div class="jet-form-editor__row">
				<div class="jet-form-editor__row-label"><?php _e( 'Type:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<select type="text" v-model="currentItem.settings.type">
						<option v-for="( typeLabel, typeIndex in fieldTypes" :value="typeIndex">{{ typeLabel }}</option>
					</select>
				</div>
			</div>
			<div class="jet-form-editor__row" v-if="'text' === currentItem.settings.type">
				<div class="jet-form-editor__row-label"><?php _e( 'Field Type:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<select type="text" v-model="currentItem.settings.field_type">
						<option v-for="( typeLabel, typeName ) in inputTypes" :value="typeName">{{ typeLabel }}</option>
					</select>
				</div>
			</div>
			<div class="jet-form-editor__row">
				<div class="jet-form-editor__row-label"><?php _e( 'Name:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.name">
				</div>
			</div>
			<div class="jet-form-editor__row">
				<div class="jet-form-editor__row-label"><?php _e( 'Label:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.label">
				</div>
			</div>
			<div class="jet-form-editor__row">
				<div class="jet-form-editor__row-label"><?php _e( 'Description:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.desc">
				</div>
			</div>
			<div class="jet-form-editor__row" v-if="'calculated' !== currentItem.settings.type">
				<div class="jet-form-editor__row-label"><?php _e( 'Required:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="checkbox" value="required" v-model="currentItem.settings.required">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'hidden' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Field Value:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<select type="text" v-model="currentItem.settings.hidden_value">
						<option value="post_id"><?php _e( 'Current Post ID', 'jet-engine' ); ?></option>
						<option value="post_title"><?php _e( 'Current Post Title', 'jet-engine' ); ?></option>
						<option value="post_meta"><?php _e( 'Current Post Meta', 'jet-engine' ); ?></option>
					</select>
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'hidden' === currentItem.settings.type && 'post_meta' === currentItem.settings.hidden_value"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Meta field to get value from:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.hidden_value_field">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="inArray( currentItem.settings.type, [ 'select', 'checkboxes', 'radio' ] )"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Fill Options From:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<select type="text" v-model="currentItem.settings.field_options_from">
						<option value="manual_input"><?php _e( 'Manual Input', 'jet-engine' ); ?></option>
						<option value="meta_field"><?php _e( 'Meta Field', 'jet-engine' ); ?></option>
					</select>
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="inArray( currentItem.settings.type, [ 'select', 'checkboxes', 'radio' ] ) && 'meta_field' === currentItem.settings.field_options_from"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Meta field to get value from:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.field_options_key">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="inArray( currentItem.settings.type, [ 'select', 'checkboxes', 'radio' ] ) && 'manual_input' === currentItem.settings.field_options_from"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Options List:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<div class="jet-form-repeater">
						<div class="jet-form-repeater__items">
							<div class="jet-form-repeater__item"
								v-for="( option, index ) in currentItem.settings.field_options"
							>
								<div class="jet-form-repeater__item-input">
									<div class="jet-form-repeater__item-input-label"><?php
										_e( 'Value:', 'jet-engine' );
									?></div>
									<input type="text" v-model="currentItem.settings.field_options[ index ].value">
								</div>
								<div class="jet-form-repeater__item-input">
									<div class="jet-form-repeater__item-input-label"><?php
										_e( 'Label:', 'jet-engine' );
									?></div>
									<div class="jet-form-repeater__item-input-control">
										<input type="text" v-model="currentItem.settings.field_options[ index ].label">
									</div>
								</div>
								<div class="jet-form-repeater__item-delete">
									<span class="dashicons dashicons-dismiss"
										@click="deleteRepeterItem( index, currentItem.settings.field_options )"
									></span>
								</div>
							</div>
						</div>
						<button type="button" class="button"
							@click="addRepeaterItem( currentItem.settings.field_options, { value: '', label: '' } )"
						><?php
							_e( 'Add Option', 'jet-engine' );
						?></button>
					</div>
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'calculated' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label">
					<?php _e( 'Calculation Formula:', 'jet-engine' ); ?>
					<div class="jet-form-editor__row-notice">
						<?php _e( 'Set math formula to calculate field value.', 'jet-engine' ); ?><br>
						<?php _e( 'For example:', 'jet-engine' ); ?><br><br>
						%FIELD::quantity%*%META::price%<br><br>
						<?php _e( 'Where:', 'jet-engine' ); ?><br>
						- <?php _e( '%FIELD::quantity% - macros for form field value. "quantity" - is a field name to get value from', 'jet-engine' ); ?><br>
						- <?php _e( '%META::price% - macros for current post meta value. "quantity" - is a meta key to get value from', 'jet-engine' ); ?>
					</div>
				</div>
				<div class="jet-form-editor__row-control">
					<textarea v-model="currentItem.settings.calc_formula"></textarea>
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'calculated' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Decimal Places Number:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="number" min="1" max="20" value="2" v-model="currentItem.settings.precision">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'calculated' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Calculated Value Prefix:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.calc_prefix">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'calculated' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Calculated Value Suffix:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.calc_suffix">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'text' === currentItem.settings.type || 'select' === currentItem.settings.type || 'textarea' === currentItem.settings.type || 'number' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Placeholder:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.placeholder">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'hidden' !== currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Default:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="text" v-model="currentItem.settings.default">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'number' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Min Value:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="number" v-model="currentItem.settings.min">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'number' === currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Max Value:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<input type="number" v-model="currentItem.settings.max">
				</div>
			</div>
			<div class="jet-form-editor__row"
				v-if="'hidden' !== currentItem.settings.type"
			>
				<div class="jet-form-editor__row-label"><?php _e( 'Field Visibility:', 'jet-engine' ); ?></div>
				<div class="jet-form-editor__row-control">
					<select type="text" v-model="currentItem.settings.visibility">
						<option value="all"><?php _e( 'For all', 'jet-engine' ); ?></option>
						<option value="logged_id"><?php _e( 'Only for logged in users', 'jet-engine' ); ?></option>
						<option value="not_logged_in"><?php _e( 'Only for NOT-logged in users', 'jet-engine' ); ?></option>
					</select>
				</div>
			</div>
		</div>
		<div class="jet-form-editor__actions">
			<button type="button" class="button button-primary button-large" @click="applyFieldChanges"><?php
				_e( 'Apply Changes', 'jet-engine' );
			?></button>
			<button type="button" class="button button-default button-large" @click="cancelFieldChanges"><?php
				_e( 'Cancel', 'jet-engine' );
			?></button>
		</div>
	</div>
</div>
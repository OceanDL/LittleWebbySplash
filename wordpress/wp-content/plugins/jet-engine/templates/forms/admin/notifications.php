<div id="notifications_builder">
	<div class="jet-form-list">
		<div class="jet-form-list__item" v-for="( item, index ) in items">
			<div class="jet-form-canvas__field-content">
				<div class="jet-form-canvas__field-start">
					<div class="jet-form-canvas__field-remove" @click="removeItem( item, index )"></div>
					<div class="jet-form-canvas__field-label">
						<span class="jet-form-canvas__field-name">
							<span v-html="availableTypes[ item.type ]"></span>
						</span>
					</div>
				</div>
				<div class="jet-form-canvas__field-end">
					<div class="jet-form-canvas__field-edit" @click="editItem( item, index )">
						<span class="dashicons dashicons-edit"></span>
					</div>
				</div>
			</div>
			<div class="jet-form-editor" v-if="showEditor && index === currentIndex">
				<div class="jet-form-editor__content">
					<div class="jet-form-editor__row">
						<div class="jet-form-editor__row-label"><?php _e( 'Type:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<select type="text" v-model="currentItem.type">
								<option v-for="( typeLabel, typeValue ) in availableTypes" :value="typeValue">
									{{ typeLabel }}
								</option>
							</select>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'hook' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Hook Name:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="text" v-model="currentItem.hook_name">
							<div class="jet-form-editor__row-note">
								jet-engine-booking/{{ currentItem.hook_name }}
							</div>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Mail to:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<select type="text" v-model="currentItem.mail_to">
								<option value="admin"><?php _e( 'Admin email', 'jet-engine' ); ?></option>
								<option value="form"><?php _e( 'Email from submitted form field', 'jet-engine' ); ?></option>
								<option value="custom"><?php _e( 'Custom email', 'jet-engine' ); ?></option>
							</select>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type && 'custom' === currentItem.mail_to">
						<div class="jet-form-editor__row-label"><?php _e( 'Email Address:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="text" v-model="currentItem.custom_email">
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type && 'form' === currentItem.mail_to">
						<div class="jet-form-editor__row-label"><?php _e( 'From Field:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<select type="text" v-model="currentItem.from_field">
								<option v-for="field in availableFields" :value="field" >{{ field }}</option>
							</select>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Reply to:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<select type="text" v-model="currentItem.reply_to">
								<option value=""><?php _e( 'Not selected', 'jet-engine' ); ?></option>
								<option value="form"><?php _e( 'Email from submitted form field', 'jet-engine' ); ?></option>
								<option value="custom"><?php _e( 'Custom email', 'jet-engine' ); ?></option>
							</select>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type && 'custom' === currentItem.reply_to">
						<div class="jet-form-editor__row-label"><?php _e( 'Reply to Email Address:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="text" v-model="currentItem.reply_to_email">
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type && 'form' === currentItem.reply_to">
						<div class="jet-form-editor__row-label"><?php _e( 'Reply To Email From Field:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<select type="text" v-model="currentItem.reply_from_field">
								<option v-for="field in availableFields" :value="field" >{{ field }}</option>
							</select>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'insert_post' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Post Type:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<select type="text" v-model="currentItem.post_type">
								<option v-for="( typeLabel, typeValue ) in postTypes" :value="typeValue" >
									{{ typeLabel }}
								</option>
							</select>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'insert_post' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Post Status:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<select type="text" v-model="currentItem.post_status">
								<option v-for="( statusLabel, statusValue ) in postStatuses" :value="statusValue" >
									{{ statusLabel }}
								</option>
							</select>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'insert_post' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Fields Map:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<div class="jet-form-editor__row-notice"><?php
								_e( 'Set meta fields names to save apropriate form fields into', 'jet-engine' );
							?></div>
							<div class="jet-form-editor__row-fields">
								<div class="jet-form-editor__row-map" v-for="field in availableFields">
									<span>{{ field }}</span>
									<input type="text" v-model="currentItem.fields_map[ field ]">
								</div>
							</div>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'register_user' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Fields Map:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<div class="jet-form-editor__row-notice"><?php
								_e( 'Set form fields names to to get user data from', 'jet-engine' );
							?></div>
							<div class="jet-form-editor__row-error" v-if="! currentItem.fields_map.login || ! currentItem.fields_map.email || ! currentItem.fields_map.password"><?php
								_e( 'User Login, Email and Password fields can\'t be empty', 'jet-engine' );
							?></div>
							<div class="jet-form-editor__row-fields">
								<div class="jet-form-editor__row-map" v-for="( uFieldLabel, uField ) in userFields">
									<span>{{ uFieldLabel }}</span>
									<select v-model="currentItem.fields_map[ uField ]">
										<option value="">--</option>
										<option v-for="field in availableFields" :value="field">{{ field }}</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'register_user' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Log In User after Register:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="checkbox" value="yes" v-model="currentItem.log_in">
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'register_user' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Add User ID to form data:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="checkbox" value="yes" v-model="currentItem.add_user_id">
							<div class="jet-form-editor__row-control-desc">
								<?php _e( 'Registered user ID will be added to from data. If form is filled by logged in user - current user ID will be added to form data.', 'jet-engine' ); ?>
							</div>
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Subject:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="text" v-model="currentItem.email.subject">
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'From Name:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="text" v-model="currentItem.email.from_name">
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php
							_e( 'From Email Address:', 'jet-engine' );
						?></div>
						<div class="jet-form-editor__row-control">
							<input type="text" v-model="currentItem.email.from_address">
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'webhook' === currentItem.type">
						<div class="jet-form-editor__row-label"><?php _e( 'Webhook URL:', 'jet-engine' ); ?></div>
						<div class="jet-form-editor__row-control">
							<input type="text" v-model="currentItem.webhook_url">
						</div>
					</div>
					<div class="jet-form-editor__row" v-if="'email' === currentItem.type">
						<div class="jet-form-editor__row-label">
							<?php _e( 'Content:', 'jet-engine' ); ?>
							<div class="jet-form-editor__row-notice">
								<?php _e( 'Available macros:', 'jet-engine' ); ?>
								<div v-for="field in availableFields">
									- <i>%{{ field }}%</i>
								</div>
							</div>
						</div>
						<div class="jet-form-editor__row-control">
							<textarea v-model="currentItem.email.content"></textarea>
						</div>
					</div>
				</div>
				<div class="jet-form-editor__actions">
					<button type="button" class="button button-primary button-large" @click="applyItemChanges"><?php
						_e( 'Apply Changes', 'jet-engine' );
					?></button>
					<button type="button" class="button button-default button-large" @click="cancelItemChanges"><?php
						_e( 'Cancel', 'jet-engine' );
					?></button>
				</div>
			</div>
		</div>
	</div>
	<div class="jet-form-canvas__actions">
		<button type="button" class="jet-form-canvas__add" @click="addField()"><?php
			_e( 'Add Notification', 'jet-engine' );
		?></button>
	</div>
	<div class="jet-form-canvas__result">
		<textarea name="_notifications_data">{{ resultJSON }}</textarea>
	</div>
</div>
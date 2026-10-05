/**
 * Edit form of study program pages and the front page.
 *
 * @package NajdiSvujSen
 */

( function ( $ ) {
	'use strict';

	const settings = window.najdisvujsenForm || { i18n: {} };
	const i18n = settings.i18n;
	const form = document.getElementById( 'post' );
	const root = document.querySelector( '.nsj-form' );

	if ( ! form || ! root ) {
		return;
	}

	const postId = root.dataset.post;
	const storageKey = 'nsj-tab-' + postId;
	let dirty = false;
	let submitting = false;
	let counter = Date.now() % 100000;

	/* Rich text editors ------------------------------------------------ */

	function editorSettings( lite ) {
		const buttons = lite
			? 'bold,italic,link,unlink,bullist,undo,redo'
			: 'formatselect,bold,italic,link,unlink,bullist,numlist,nsjhighlight,undo,redo';

		return {
			tinymce: {
				toolbar1: buttons,
				toolbar2: '',
				plugins: 'lists,link,paste,wordpress,wplink,wpautoresize',
				block_formats: i18n.paragraph + '=p;' + i18n.subheading + '=h3',
				paste_as_text: true,
				wpautop: true,
				menubar: false,
				statusbar: false,
				resize: true,
				min_height: lite ? 90 : 160,
				autoresize_min_height: lite ? 90 : 160,
				wp_autoresize_on: true,
				content_style: 'body{font:15px/1.6 -apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;margin:10px 12px}blockquote{margin:12px 0;padding:10px 14px;background:#eef8fd;border-left:4px solid #3ab0e1;border-radius:6px}h3{font-size:17px;margin:14px 0 6px}',
				setup( editor ) {
					editor.addButton( 'nsjhighlight', {
						text: i18n.highlight,
						tooltip: i18n.highlight,
						onclick() {
							editor.execCommand( 'mceBlockQuote' );
						},
						onPostRender() {
							const button = this;
							editor.on( 'NodeChange', ( event ) => {
								button.active( Boolean( editor.dom.getParent( event.element, 'blockquote' ) ) );
							} );
						},
					} );
					editor.on( 'change keyup', () => {
						editor.save();
						dirty = true;
					} );
				},
			},
			quicktags: false,
			mediaButtons: false,
		};
	}

	function isVisible( element ) {
		return Boolean( element.offsetParent ) && ! element.closest( 'template' );
	}

	function initEditors( scope ) {
		if ( ! window.wp || ! wp.editor ) {
			return;
		}

		scope.querySelectorAll( 'textarea.nsj-rich:not(.is-ready)' ).forEach( ( textarea ) => {
			if ( ! isVisible( textarea ) ) {
				return;
			}

			textarea.classList.add( 'is-ready' );
			wp.editor.initialize( textarea.id, editorSettings( '1' === textarea.dataset.lite ) );
		} );
	}

	function removeEditors( scope ) {
		scope.querySelectorAll( 'textarea.nsj-rich.is-ready' ).forEach( ( textarea ) => {
			const editor = window.tinymce && tinymce.get( textarea.id );

			if ( editor ) {
				editor.save();
			}

			wp.editor.remove( textarea.id );
			textarea.classList.remove( 'is-ready' );
			textarea.style.removeProperty( 'display' );
			textarea.removeAttribute( 'aria-hidden' );
		} );
	}

	function saveEditors() {
		if ( window.tinymce ) {
			tinymce.triggerSave();
		}
	}

	/* Tabs ------------------------------------------------------------- */

	const tabs = Array.from( root.querySelectorAll( '.nsj-tab' ) );
	const panels = Array.from( root.querySelectorAll( '.nsj-panel' ) );

	function showTab( name, focus ) {
		const tab = tabs.find( ( item ) => item.dataset.tab === name ) || tabs[ 0 ];

		tabs.forEach( ( item ) => {
			item.setAttribute( 'aria-current', String( item === tab ) );
		} );
		panels.forEach( ( panel ) => {
			panel.hidden = panel.dataset.panel !== tab.dataset.tab;
		} );

		try {
			window.sessionStorage.setItem( storageKey, tab.dataset.tab );
		} catch ( error ) {}

		const panel = panels.find( ( item ) => ! item.hidden );

		initEditors( panel );

		if ( focus ) {
			panel.querySelector( '.nsj-panel__title' ).focus( { preventScroll: true } );
		}
	}

	tabs.forEach( ( tab ) => {
		tab.addEventListener( 'click', () => showTab( tab.dataset.tab ) );
	} );

	root.querySelectorAll( '.nsj-panel__title' ).forEach( ( title ) => title.setAttribute( 'tabindex', '-1' ) );

	let initial = tabs[ 0 ].dataset.tab;

	try {
		initial = window.sessionStorage.getItem( storageKey ) || initial;
	} catch ( error ) {}

	showTab( initial );

	/* Repeaters -------------------------------------------------------- */

	function itemIndex( item ) {
		const input = item.querySelector( '[name]' );
		const repeater = item.closest( '.nsj-repeater' );
		const prefix = repeater.dataset.name + '[';

		if ( ! input || ! input.name.startsWith( prefix ) ) {
			return null;
		}

		return input.name.slice( prefix.length ).split( ']' )[ 0 ];
	}

	function renameItem( html, repeater, from, to ) {
		const name = repeater.dataset.name;
		const id = repeater.id;

		return html
			.split( name + '[' + from + ']' ).join( name + '[' + to + ']' )
			.split( id + '-' + from + '-' ).join( id + '-' + to + '-' );
	}

	function updateRepeater( repeater ) {
		const max = parseInt( repeater.dataset.max, 10 ) || 0;
		const items = repeater.querySelectorAll( ':scope > .nsj-repeater__items > .nsj-item' );
		const add = repeater.querySelector( ':scope > .nsj-repeater__add' );

		add.disabled = Boolean( max ) && items.length >= max;
		add.title = add.disabled ? i18n.maxReached : '';

		items.forEach( ( item, index ) => {
			item.querySelector( '.nsj-item__up' ).disabled = 0 === index;
			item.querySelector( '.nsj-item__down' ).disabled = index === items.length - 1;
		} );
	}

	function updateItemHead( item ) {
		const titleField = item.dataset.titleField;
		const badgeField = item.dataset.badgeField;
		const title = item.querySelector( '.nsj-item__title' );

		if ( titleField ) {
			const input = item.querySelector( '[name$="[' + titleField + ']"]' );
			let value = input ? input.value.trim() : '';

			if ( value && input.type === 'date' ) {
				const parts = value.split( '-' );
				value = parseInt( parts[ 2 ], 10 ) + '. ' + parseInt( parts[ 1 ], 10 ) + '. ' + parts[ 0 ];
			}

			title.textContent = value || item.dataset.itemLabel;
		}

		if ( badgeField ) {
			const checked = item.querySelector( '[name$="[' + badgeField + ']"]:checked' );
			const badge = item.querySelector( '.nsj-item__badge' );

			badge.textContent = checked ? checked.parentElement.textContent.trim() : '';
			badge.hidden = ! checked;
		}

		const thumb = item.querySelector( '.nsj-item__thumb' );
		const image = item.querySelector( '.nsj-item__body .nsj-image .nsj-image__preview img' );

		if ( thumb ) {
			thumb.innerHTML = image ? '<img src="' + image.src + '" alt="">' : '';
		}
	}

	function setCollapsed( item, collapsed ) {
		item.classList.toggle( 'is-collapsed', collapsed );
		item.querySelector( '.nsj-item__toggle' ).setAttribute( 'aria-expanded', String( ! collapsed ) );

		if ( ! collapsed ) {
			initEditors( item );
		}
	}

	function syncAttributes( scope ) {
		saveEditors();
		scope.querySelectorAll( 'input, textarea, select' ).forEach( ( field ) => {
			if ( field.tagName === 'TEXTAREA' ) {
				field.textContent = field.value;
			} else if ( field.tagName === 'SELECT' ) {
				Array.from( field.options ).forEach( ( option ) => option.toggleAttribute( 'selected', option.selected ) );
			} else if ( field.type === 'checkbox' || field.type === 'radio' ) {
				field.toggleAttribute( 'checked', field.checked );
			} else {
				field.setAttribute( 'value', field.value );
			}
		} );
	}

	function addItem( repeater, html, after ) {
		const list = repeater.querySelector( ':scope > .nsj-repeater__items' );
		const holder = document.createElement( 'div' );

		holder.innerHTML = html.trim();

		const item = holder.firstElementChild;

		if ( after ) {
			after.after( item );
		} else {
			list.appendChild( item );
		}

		setupScope( item );
		setCollapsed( item, false );
		updateItemHead( item );
		updateRepeater( repeater );
		dirty = true;

		return item;
	}

	function moveItem( item, direction ) {
		const sibling = direction < 0 ? item.previousElementSibling : item.nextElementSibling;

		if ( ! sibling ) {
			return;
		}

		removeEditors( item );
		removeEditors( sibling );

		if ( direction < 0 ) {
			sibling.before( item );
		} else {
			sibling.after( item );
		}

		initEditors( item );
		initEditors( sibling );
		updateRepeater( item.closest( '.nsj-repeater' ) );
		item.querySelector( direction < 0 ? '.nsj-item__up' : '.nsj-item__down' ).focus();
		dirty = true;
	}

	root.addEventListener( 'click', ( event ) => {
		const button = event.target.closest( 'button' );

		if ( ! button || ! root.contains( button ) ) {
			return;
		}

		const item = button.closest( '.nsj-item' );

		if ( button.classList.contains( 'nsj-repeater__add' ) ) {
			const repeater = button.closest( '.nsj-repeater' );
			const template = repeater.querySelector( ':scope > .nsj-repeater__template' );
			const added = addItem( repeater, renameItem( template.innerHTML, repeater, '__i__', 'n' + ( ++counter ) ) );
			const first = added.querySelector( '.nsj-item__body input:not([type=hidden]), .nsj-item__body textarea' );

			added.scrollIntoView( { block: 'nearest', behavior: 'smooth' } );

			if ( first ) {
				first.focus( { preventScroll: true } );
			}
		} else if ( button.classList.contains( 'nsj-item__toggle' ) ) {
			setCollapsed( item, ! item.classList.contains( 'is-collapsed' ) );
		} else if ( button.classList.contains( 'nsj-item__up' ) ) {
			moveItem( item, -1 );
		} else if ( button.classList.contains( 'nsj-item__down' ) ) {
			moveItem( item, 1 );
		} else if ( button.classList.contains( 'nsj-item__copy' ) ) {
			const repeater = item.closest( '.nsj-repeater' );

			removeEditors( item );
			syncAttributes( item );

			const html = renameItem( item.outerHTML, repeater, itemIndex( item ), 'n' + ( ++counter ) );

			initEditors( item );
			addItem( repeater, html, item );
		} else if ( button.classList.contains( 'nsj-item__remove' ) ) {
			if ( ! window.confirm( i18n.confirmRemove ) ) {
				return;
			}

			const repeater = item.closest( '.nsj-repeater' );

			removeEditors( item );
			item.remove();
			updateRepeater( repeater );
			dirty = true;
		} else if ( button.classList.contains( 'nsj-list__add' ) ) {
			const list = button.closest( '.nsj-list' );
			const max = parseInt( list.dataset.max, 10 ) || 0;
			const items = list.querySelector( '.nsj-list__items' );

			if ( max && items.children.length >= max ) {
				window.alert( i18n.maxReached );
				return;
			}

			items.insertAdjacentHTML( 'beforeend', list.querySelector( '.nsj-list__template' ).innerHTML );
			items.lastElementChild.querySelector( 'input, textarea' ).focus();
			dirty = true;
		} else if ( button.classList.contains( 'nsj-list__remove' ) ) {
			button.closest( '.nsj-list__item' ).remove();
			dirty = true;
		} else if ( button.classList.contains( 'nsj-image__choose' ) ) {
			chooseImage( button.closest( '.nsj-image' ) );
		} else if ( button.classList.contains( 'nsj-image__remove' ) ) {
			const picker = button.closest( '.nsj-image' );

			picker.querySelector( 'input' ).value = picker.dataset.empty;
			picker.querySelector( '.nsj-image__preview' ).innerHTML = '';
			picker.classList.remove( 'has-image' );

			if ( item ) {
				updateItemHead( item );
			}

			dirty = true;
		} else if ( button.classList.contains( 'nsj-gallery__add' ) ) {
			chooseImages( button.closest( '.nsj-gallery' ) );
		} else if ( button.classList.contains( 'nsj-gallery__remove' ) ) {
			button.closest( '.nsj-gallery__item' ).remove();
			dirty = true;
		}
	} );

	root.addEventListener( 'input', ( event ) => {
		const item = event.target.closest( '.nsj-item' );

		dirty = true;

		if ( item ) {
			updateItemHead( item );
		}

		if ( event.target.dataset.max ) {
			updateCounter( event.target );
		}
	} );

	root.addEventListener( 'change', ( event ) => {
		const item = event.target.closest( '.nsj-item' );

		dirty = true;

		if ( item ) {
			updateItemHead( item );
		}
	} );

	/* Images ----------------------------------------------------------- */

	function imageUrl( attachment ) {
		const sizes = attachment.sizes || {};

		return ( sizes.medium || sizes.thumbnail || sizes.full || attachment ).url;
	}

	function chooseImage( picker ) {
		const frame = wp.media( {
			title: i18n.chooseImage,
			button: { text: i18n.useImage },
			library: { type: 'image' },
			multiple: false,
		} );

		frame.on( 'select', () => {
			const attachment = frame.state().get( 'selection' ).first().toJSON();
			const item = picker.closest( '.nsj-item' );

			picker.querySelector( 'input' ).value = attachment.id;
			picker.querySelector( '.nsj-image__preview' ).innerHTML = '<img class="nsj-image__img" src="' + imageUrl( attachment ) + '" alt="">';
			picker.classList.add( 'has-image' );

			if ( item ) {
				updateItemHead( item );
			}

			dirty = true;
		} );

		frame.open();
	}

	function chooseImages( gallery ) {
		const max = parseInt( gallery.dataset.max, 10 ) || 0;
		const list = gallery.querySelector( '.nsj-gallery__list' );
		const frame = wp.media( {
			title: i18n.chooseImages,
			button: { text: i18n.useImages },
			library: { type: 'image' },
			multiple: 'add',
		} );

		frame.on( 'select', () => {
			frame.state().get( 'selection' ).toJSON().forEach( ( attachment ) => {
				if ( max && list.children.length >= max ) {
					return;
				}

				const li = document.createElement( 'li' );
				const sizes = attachment.sizes || {};

				li.className = 'nsj-gallery__item';
				li.innerHTML = '<img src="' + ( sizes.thumbnail || sizes.full || attachment ).url + '" alt="">'
					+ '<input type="hidden" name="' + gallery.dataset.name + '" value="' + attachment.id + '">'
					+ '<button type="button" class="nsj-gallery__remove" aria-label="' + i18n.remove + '"><span class="dashicons dashicons-no-alt" aria-hidden="true"></span></button>';
				list.appendChild( li );
			} );

			if ( max && frame.state().get( 'selection' ).length > max ) {
				window.alert( i18n.maxReached );
			}

			dirty = true;
		} );

		frame.open();
	}

	/* Counters and sorting --------------------------------------------- */

	function updateCounter( input ) {
		const max = parseInt( input.dataset.max, 10 );

		if ( ! max ) {
			return;
		}

		let counterElement = input.nextElementSibling;

		if ( ! counterElement || ! counterElement.classList.contains( 'nsj-counter' ) ) {
			counterElement = document.createElement( 'span' );
			counterElement.className = 'nsj-counter';
			counterElement.setAttribute( 'aria-live', 'polite' );
			input.after( counterElement );
		}

		const length = input.value.length;

		counterElement.textContent = length + ' / ' + max + ' ' + i18n.chars + ( length > max ? ' – ' + i18n.tooLong : '' );
		counterElement.classList.toggle( 'is-over', length > max );
	}

	function setupScope( scope ) {
		scope.querySelectorAll( '[data-max]' ).forEach( ( input ) => {
			if ( input.matches( 'input, textarea' ) ) {
				updateCounter( input );
			}
		} );

		$( scope ).find( '.nsj-repeater__items' ).addBack( '.nsj-repeater__items' ).sortable( {
			handle: '> .nsj-item__head > .nsj-handle',
			items: '> .nsj-item',
			axis: 'y',
			placeholder: 'nsj-item nsj-item--placeholder',
			forcePlaceholderSize: true,
			start( event, ui ) {
				removeEditors( ui.item[ 0 ] );
			},
			stop( event, ui ) {
				initEditors( ui.item[ 0 ] );
				updateRepeater( ui.item[ 0 ].closest( '.nsj-repeater' ) );
				dirty = true;
			},
		} );

		$( scope ).find( '.nsj-list__items' ).sortable( { handle: '.nsj-handle', axis: 'y', stop: () => ( dirty = true ) } );
		$( scope ).find( '.nsj-gallery__list' ).sortable( { stop: () => ( dirty = true ) } );
	}

	setupScope( root );
	root.querySelectorAll( '.nsj-repeater' ).forEach( updateRepeater );

	/* Validation ------------------------------------------------------- */

	function isEmpty( field ) {
		const choices = field.querySelectorAll( 'input[type=radio], input[type=checkbox]' );

		if ( choices.length ) {
			return ! Array.from( choices ).some( ( choice ) => choice.checked );
		}

		const input = field.querySelector( 'input:not([type=hidden]), textarea, select' ) || field.querySelector( 'input[type=hidden]' );

		return ! input || '' === input.value.trim() || '-1' === input.value;
	}

	function validate() {
		const errors = [];

		saveEditors();
		root.querySelectorAll( '.nsj-panel [data-required]' ).forEach( ( field ) => {
			field.classList.remove( 'has-error' );

			if ( field.closest( 'template' ) || ! isEmpty( field ) ) {
				return;
			}

			field.classList.add( 'has-error' );
			errors.push( field );
		} );

		root.querySelectorAll( '[data-max]' ).forEach( ( input ) => {
			const max = parseInt( input.dataset.max, 10 );
			const field = input.closest( '.nsj-field' );

			if ( max && input.matches( 'input, textarea' ) && input.value.length > max && ! errors.includes( field ) ) {
				field.classList.add( 'has-error' );
				errors.push( field );
			}
		} );

		return errors;
	}

	function describe( field ) {
		const panel = field.closest( '.nsj-panel' );
		const tab = tabs.find( ( item ) => item.dataset.tab === panel.dataset.panel );
		const item = field.closest( '.nsj-item' );
		const parts = [ tab.querySelector( '.nsj-tab__label' ).textContent ];

		if ( item ) {
			parts.push( item.querySelector( '.nsj-item__title' ).textContent );
		}

		parts.push( field.dataset.label );

		return parts.join( ' › ' );
	}

	function reveal( field ) {
		const panel = field.closest( '.nsj-panel' );
		const item = field.closest( '.nsj-item' );

		showTab( panel.dataset.panel );

		if ( item ) {
			setCollapsed( item, false );
		}

		field.scrollIntoView( { block: 'center' } );

		const input = field.querySelector( 'input:not([type=hidden]), textarea, select, button' );

		if ( input ) {
			input.focus( { preventScroll: true } );
		}
	}

	function showErrors( errors ) {
		const box = root.querySelector( '.nsj-errors' );

		box.innerHTML = '';

		if ( ! errors.length ) {
			box.hidden = true;
			return;
		}

		const intro = document.createElement( 'p' );
		const list = document.createElement( 'ul' );

		intro.innerHTML = '<strong></strong>';
		intro.firstChild.textContent = i18n.required;

		errors.forEach( ( field ) => {
			const li = document.createElement( 'li' );
			const button = document.createElement( 'button' );

			button.type = 'button';
			button.className = 'button-link';
			button.textContent = describe( field );
			button.addEventListener( 'click', () => reveal( field ) );
			li.appendChild( button );
			list.appendChild( li );
		} );

		box.append( intro, list );
		box.hidden = false;
		box.scrollIntoView( { block: 'start', behavior: 'smooth' } );
	}

	form.addEventListener(
		'submit',
		( event ) => {
			const submitter = event.submitter;

			saveEditors();

			if ( submitter && ( submitter.id === 'post-preview' || submitter.classList.contains( 'submitdelete' ) ) ) {
				return;
			}

			const errors = validate();

			if ( errors.length ) {
				event.preventDefault();
				event.stopImmediatePropagation();
				showErrors( errors );
				reveal( errors[ 0 ] );
				$( '#publishing-action .spinner' ).removeClass( 'is-active' );
				$( '#publish, #save-post' ).removeClass( 'disabled' );
				return;
			}

			showErrors( [] );
			submitting = true;
		},
		true
	);

	window.addEventListener( 'beforeunload', ( event ) => {
		if ( dirty && ! submitting ) {
			event.preventDefault();
			event.returnValue = i18n.unsaved;
		}
	} );

	/* Preview of a published page ------------------------------------- */

	document.addEventListener(
		'click',
		( event ) => {
			const button = event.target.closest( '#post-preview' );

			if ( ! button || ! root.dataset.permalink ) {
				return;
			}

			event.preventDefault();
			event.stopImmediatePropagation();
			saveEditors();

			const target = window.open( '', 'wp-preview-' + postId );
			const data = new FormData( form );

			data.set( 'action', 'najdisvujsen_preview' );
			data.set( 'nonce', settings.previewNonce );

			fetch( settings.ajaxUrl, { method: 'POST', body: data, credentials: 'same-origin' } )
				.then( ( response ) => response.json() )
				.then( ( result ) => {
					if ( ! result.success ) {
						throw new Error( 'preview' );
					}

					target.location = result.data.url;
				} )
				.catch( () => {
					target.close();
					window.alert( i18n.previewError );
				} );
		},
		true
	);
}( jQuery ) );

/**
 * Front-end behaviour of the Najdi svůj sen theme.
 *
 * @package NajdiSvujSen
 * @since 0.3.0
 */

( function () {
	'use strict';

	const l10n = window.najdisvujsenL10n || {};
	const reducedMotion = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	const root = document.documentElement;

	const format = ( text, value ) => String( text || '' ).replace( /%[sd]/, value );

	const normalize = ( text ) => String( text ).toLowerCase().normalize( 'NFD' ).replace( /[̀-ͯ]/g, '' ).trim();

	/* Header */

	function initHeader() {
		const header = document.querySelector( '[data-header]' );

		if ( ! header ) {
			return;
		}

		const setHeight = () => root.style.setProperty( '--header-height', header.offsetHeight + 'px' );
		setHeight();
		new ResizeObserver( setHeight ).observe( header );

		const burger = header.querySelector( '[data-burger]' );
		const nav = header.querySelector( '.site-nav' );
		const desktop = window.matchMedia( '(min-width: 1100px)' );

		const setOpen = ( open ) => {
			header.classList.toggle( 'is-open', open );
			document.body.style.overflow = open ? 'hidden' : '';

			if ( burger ) {
				burger.setAttribute( 'aria-expanded', String( open ) );
				burger.setAttribute( 'aria-label', open ? l10n.closeMenu : l10n.openMenu );
			}
		};

		if ( burger && nav ) {
			burger.addEventListener( 'click', () => setOpen( ! header.classList.contains( 'is-open' ) ) );
			nav.addEventListener( 'click', ( event ) => {
				if ( event.target.closest( 'a' ) ) {
					setOpen( false );
				}
			} );
			document.addEventListener( 'keydown', ( event ) => {
				if ( 'Escape' === event.key && header.classList.contains( 'is-open' ) ) {
					setOpen( false );
					burger.focus();
				}
			} );
			desktop.addEventListener( 'change', () => setOpen( false ) );
		}

		const links = nav ? Array.from( nav.querySelectorAll( 'a[href^="#"]' ) ) : [];
		const targets = links.map( ( link ) => document.getElementById( decodeURIComponent( link.hash.slice( 1 ) ) ) ).filter( Boolean );

		if ( ! targets.length ) {
			return;
		}

		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					if ( ! entry.isIntersecting ) {
						return;
					}

					links.forEach( ( link ) => {
						const active = link.hash === '#' + entry.target.id;
						link.classList.toggle( 'is-active', active );

						if ( active ) {
							link.setAttribute( 'aria-current', 'true' );
						} else {
							link.removeAttribute( 'aria-current' );
						}
					} );
				} );
			},
			{ rootMargin: '-40% 0px -55% 0px' }
		);

		targets.forEach( ( target ) => observer.observe( target ) );
	}

	/* Marquees run only while visible */

	function initMarquees() {
		const marquees = document.querySelectorAll( '.marquee' );

		if ( ! marquees.length ) {
			return;
		}

		const observer = new IntersectionObserver( ( entries ) => {
			entries.forEach( ( entry ) => entry.target.classList.toggle( 'is-paused', ! entry.isIntersecting ) );
		} );

		marquees.forEach( ( marquee ) => observer.observe( marquee ) );
	}

	/* Tabs */

	function selectTab( tabs, selected ) {
		tabs.forEach( ( tab ) => tab.setAttribute( 'aria-selected', String( tab === selected ) ) );
	}

	function onArrowKeys( list, tabs ) {
		list.addEventListener( 'keydown', ( event ) => {
			const index = tabs.indexOf( document.activeElement );

			if ( -1 === index || ( 'ArrowRight' !== event.key && 'ArrowLeft' !== event.key ) ) {
				return;
			}

			event.preventDefault();
			const next = tabs[ ( index + ( 'ArrowRight' === event.key ? 1 : -1 ) + tabs.length ) % tabs.length ];
			next.focus();
			next.click();
		} );
	}

	function initFilterTabs() {
		document.querySelectorAll( '[data-filter-tabs]' ).forEach( ( list ) => {
			const section = list.closest( '.section' );
			const tabs = Array.from( list.querySelectorAll( '[role="tab"]' ) );
			const items = section ? section.querySelectorAll( '[data-filter-items] [data-level]' ) : [];

			const apply = ( tab ) => {
				selectTab( tabs, tab );
				items.forEach( ( item ) => {
					item.hidden = item.dataset.level !== tab.dataset.value;
				} );
			};

			tabs.forEach( ( tab ) => tab.addEventListener( 'click', () => apply( tab ) ) );
			onArrowKeys( list, tabs );
			apply( tabs[ 0 ] );
		} );
	}

	function initPanelTabs() {
		document.querySelectorAll( '[data-tabs]' ).forEach( ( widget ) => {
			const list = widget.querySelector( '[role="tablist"]' );
			const tabs = Array.from( widget.querySelectorAll( '[role="tab"]' ) );

			tabs.forEach( ( tab ) => {
				tab.addEventListener( 'click', () => {
					selectTab( tabs, tab );
					widget.querySelectorAll( '[role="tabpanel"]' ).forEach( ( panel ) => {
						panel.classList.toggle( 'is-active', panel.id === tab.getAttribute( 'aria-controls' ) );
					} );
				} );
			} );

			if ( list ) {
				onArrowKeys( list, tabs );
			}
		} );
	}

	/* Program explorer */

	function initExplorer() {
		const explorer = document.querySelector( '[data-explorer]' );

		if ( ! explorer ) {
			return;
		}

		const mapNode = document.getElementById( 'career-map' );
		const map = mapNode ? JSON.parse( mapNode.textContent ) : {};
		const levels = Array.from( explorer.querySelectorAll( '[data-level]' ) );
		const levelTabs = Array.from( explorer.querySelectorAll( '[data-level-tab]' ) );
		const search = explorer.querySelector( '[data-explorer-search]' );
		const count = explorer.querySelector( '[data-explorer-count]' );
		const pickerBubbles = Array.from( explorer.querySelectorAll( '.picker [data-career]' ) );
		const clear = explorer.querySelector( '[data-picker-clear]' );
		const result = explorer.querySelector( '[data-picker-result]' );
		const dice = explorer.querySelector( '[data-dice]' );
		const diceLabel = explorer.querySelector( '[data-dice-label]' );
		const state = { level: levels.length ? levels[ 0 ].dataset.level : '', career: '', query: '' };

		levels.forEach( ( level ) => level.querySelectorAll( '.program-link' ).forEach( ( link ) => {
			link.dataset.search = normalize( link.textContent );
		} ) );

		const render = () => {
			const slugs = state.career ? map[ state.career.toLowerCase() ] || [] : null;
			const query = normalize( state.query );
			const filtering = Boolean( query || slugs );
			let hits = 0;

			levels.forEach( ( level ) => {
				level.hidden = level.dataset.level !== state.level;

				level.querySelectorAll( '.program-link' ).forEach( ( link ) => {
					const match = ( ! query || link.dataset.search.includes( query ) ) && ( ! slugs || slugs.includes( link.dataset.slug ) );
					link.classList.toggle( 'is-hit', filtering && match );
					link.classList.toggle( 'is-dim', filtering && ! match );

					if ( filtering && match && ! level.hidden ) {
						hits++;
					}
				} );

				if ( filtering ) {
					level.querySelectorAll( 'details' ).forEach( ( details ) => {
						details.open = true;
					} );
				}
			} );

			pickerBubbles.forEach( ( bubble ) => bubble.setAttribute( 'aria-pressed', String( bubble.dataset.career === state.career ) ) );

			if ( clear ) {
				clear.hidden = ! state.career;
			}

			if ( count ) {
				count.hidden = ! filtering;
				count.textContent = hits ? format( l10n.found, hits ) : l10n.notFound;
			}
		};

		const showResult = ( career ) => {
			if ( ! result ) {
				return;
			}

			result.hidden = ! career;

			if ( career ) {
				const name = document.createElement( 'strong' );
				name.textContent = career.toLowerCase();
				const parts = String( l10n.landed ).split( '%s' );
				result.replaceChildren( parts[ 0 ], name, parts[ 1 ] || '' );
			}
		};

		const setCareer = ( career, announce ) => {
			state.career = career;
			showResult( announce ? career : '' );
			render();
		};

		levelTabs.forEach( ( tab ) => {
			tab.addEventListener( 'click', () => {
				selectTab( levelTabs, tab );
				state.level = tab.dataset.levelTab;
				render();
			} );
		} );

		if ( levelTabs.length ) {
			onArrowKeys( levelTabs[ 0 ].parentElement, levelTabs );
		}

		if ( search ) {
			search.addEventListener( 'input', () => {
				state.query = search.value;
				render();
			} );
		}

		pickerBubbles.forEach( ( bubble ) => {
			bubble.addEventListener( 'click', () => {
				if ( dice && dice.classList.contains( 'is-spinning' ) ) {
					return;
				}

				setCareer( state.career === bubble.dataset.career ? '' : bubble.dataset.career, false );
			} );
		} );

		if ( clear ) {
			clear.addEventListener( 'click', () => setCareer( '', false ) );
		}

		document.querySelectorAll( '.hero [data-career]' ).forEach( ( bubble ) => {
			bubble.addEventListener( 'click', () => {
				setCareer( bubble.dataset.career, true );
				explorer.closest( '.section' ).scrollIntoView( { behavior: reducedMotion ? 'auto' : 'smooth' } );
			} );
		} );

		if ( dice && pickerBubbles.length ) {
			dice.addEventListener( 'click', () => {
				if ( dice.classList.contains( 'is-spinning' ) ) {
					return;
				}

				const total = pickerBubbles.length;
				const start = Math.floor( Math.random() * total );
				const target = Math.floor( Math.random() * total );
				const steps = reducedMotion ? 0 : total + ( ( target - start + total ) % total );
				let step = 0;

				setCareer( '', false );
				dice.classList.add( 'is-spinning' );
				dice.disabled = true;

				if ( diceLabel ) {
					diceLabel.textContent = l10n.spinning;
				}

				const finish = () => {
					pickerBubbles.forEach( ( bubble ) => bubble.classList.remove( 'is-rolling' ) );
					dice.classList.remove( 'is-spinning' );
					dice.disabled = false;

					if ( diceLabel ) {
						diceLabel.textContent = l10n.pickForMe;
					}

					const landed = pickerBubbles[ target ];
					landed.classList.add( 'is-landed' );
					landed.addEventListener( 'animationend', () => landed.classList.remove( 'is-landed' ), { once: true } );
					setCareer( landed.dataset.career, true );
				};

				const tick = () => {
					if ( step >= steps ) {
						window.setTimeout( finish, reducedMotion ? 0 : 420 );
						return;
					}

					step++;
					pickerBubbles.forEach( ( bubble, index ) => bubble.classList.toggle( 'is-rolling', index === ( start + step ) % total ) );
					window.setTimeout( tick, 40 + Math.pow( step / steps, 3 ) * 280 );
				};

				tick();
			} );
		}

		render();
	}

	/* Count-up numbers */

	function initCounters() {
		const counters = document.querySelectorAll( '[data-count]' );

		if ( ! counters.length || reducedMotion ) {
			return;
		}

		const formatter = new Intl.NumberFormat( root.lang || 'cs-CZ' );

		const run = ( node ) => {
			const target = parseInt( node.dataset.count, 10 );
			const suffix = node.dataset.suffix || '';
			const started = performance.now();

			const frame = ( now ) => {
				const progress = Math.min( 1, ( now - started ) / 1800 );
				const value = Math.round( target * ( 1 - Math.pow( 1 - progress, 3 ) ) );
				node.textContent = formatter.format( value ) + ( 1 === progress ? suffix : '' );

				if ( progress < 1 ) {
					window.requestAnimationFrame( frame );
				}
			};

			window.requestAnimationFrame( frame );
		};

		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					if ( entry.isIntersecting ) {
						observer.unobserve( entry.target );
						run( entry.target );
					}
				} );
			},
			{ threshold: 0.4 }
		);

		counters.forEach( ( counter ) => observer.observe( counter ) );
	}

	/* Video */

	function initVideo() {
		const video = document.querySelector( '[data-video]' );

		if ( ! video ) {
			return;
		}

		const id = video.dataset.video;
		const play = video.querySelector( '[data-video-play]' );
		const controls = video.querySelector( '.video__controls' );
		const toggle = video.querySelector( '[data-video-toggle]' );
		const sound = video.querySelector( '[data-video-sound]' );
		const soundLabel = video.querySelector( '[data-video-sound-label]' );
		const saveData = navigator.connection && navigator.connection.saveData;
		let frame = null;
		let userPaused = false;
		let visible = false;

		const command = ( func ) => {
			if ( frame && frame.contentWindow ) {
				frame.contentWindow.postMessage( JSON.stringify( { event: 'command', func, args: [] } ), 'https://www.youtube-nocookie.com' );
			}
		};

		const load = ( muted ) => {
			if ( frame ) {
				return;
			}

			const params = new URLSearchParams( {
				autoplay: '1',
				mute: muted ? '1' : '0',
				loop: '1',
				playlist: id,
				controls: '0',
				playsinline: '1',
				rel: '0',
				modestbranding: '1',
				iv_load_policy: '3',
				disablekb: '1',
				enablejsapi: '1',
				origin: window.location.origin,
			} );

			frame = document.createElement( 'iframe' );
			frame.src = 'https://www.youtube-nocookie.com/embed/' + encodeURIComponent( id ) + '?' + params.toString();
			frame.title = video.getAttribute( 'aria-label' ) || '';
			frame.allow = 'autoplay; encrypted-media; picture-in-picture; fullscreen';
			frame.tabIndex = -1;
			video.appendChild( frame );
			video.classList.add( 'is-loaded' );
			video.classList.toggle( 'has-sound', ! muted );

			if ( controls ) {
				controls.hidden = false;
			}

			if ( soundLabel ) {
				soundLabel.textContent = muted ? l10n.soundOn : l10n.soundOff;
			}
		};

		if ( play ) {
			play.addEventListener( 'click', ( event ) => {
				event.preventDefault();
				load( false );
			} );
		}

		if ( toggle ) {
			toggle.addEventListener( 'click', () => {
				userPaused = ! video.classList.contains( 'is-paused' );
				command( userPaused ? 'pauseVideo' : 'playVideo' );
				video.classList.toggle( 'is-paused', userPaused );
				toggle.setAttribute( 'aria-label', userPaused ? l10n.playVideo : l10n.pauseVideo );
			} );
		}

		if ( sound ) {
			sound.addEventListener( 'click', () => {
				const unmute = ! video.classList.contains( 'has-sound' );
				command( unmute ? 'unMute' : 'mute' );
				video.classList.toggle( 'has-sound', unmute );

				if ( soundLabel ) {
					soundLabel.textContent = unmute ? l10n.soundOff : l10n.soundOn;
				}
			} );
		}

		new IntersectionObserver(
			( [ entry ] ) => {
				visible = entry.isIntersecting;

				if ( visible && ! frame && ! reducedMotion && ! saveData ) {
					load( true );
				} else if ( frame && ! userPaused ) {
					command( visible ? 'playVideo' : 'pauseVideo' );
				}
			},
			{ rootMargin: '200px 0px' }
		).observe( video );
	}

	/* Lightbox */

	function initLightbox() {
		const photos = Array.from( document.querySelectorAll( 'a[data-lightbox]' ) );

		if ( ! photos.length || ! window.HTMLDialogElement ) {
			return;
		}

		const icon = ( name ) => '<svg class="icon" aria-hidden="true" focusable="false"><use href="#icon-' + name + '"></use></svg>';
		const dialog = document.createElement( 'dialog' );
		dialog.className = 'lightbox';
		dialog.innerHTML =
			'<figure class="lightbox__figure"><img class="lightbox__img" alt=""><figcaption class="lightbox__caption"><span class="lightbox__label"></span><span class="lightbox__count"></span></figcaption></figure>' +
			'<button type="button" class="lightbox__button lightbox__close">' + icon( 'x' ) + '</button>' +
			'<button type="button" class="lightbox__button lightbox__prev">' + icon( 'arrow-left' ) + '</button>' +
			'<button type="button" class="lightbox__button lightbox__next">' + icon( 'arrow-right' ) + '</button>';
		document.body.appendChild( dialog );

		const image = dialog.querySelector( '.lightbox__img' );
		const label = dialog.querySelector( '.lightbox__label' );
		const counter = dialog.querySelector( '.lightbox__count' );
		const prev = dialog.querySelector( '.lightbox__prev' );
		const next = dialog.querySelector( '.lightbox__next' );
		dialog.querySelector( '.lightbox__close' ).setAttribute( 'aria-label', l10n.close );
		prev.setAttribute( 'aria-label', l10n.previous );
		next.setAttribute( 'aria-label', l10n.next );

		let gallery = [];
		let index = 0;
		let opener = null;

		const show = ( position ) => {
			index = ( position + gallery.length ) % gallery.length;
			const photo = gallery[ index ];
			const caption = photo.querySelector( '.photo__label' );
			image.src = photo.href;
			image.alt = caption ? caption.textContent : '';
			label.textContent = caption ? caption.textContent : '';
			counter.textContent = gallery.length > 1 ? ( index + 1 ) + ' / ' + gallery.length : '';
			prev.hidden = next.hidden = gallery.length < 2;
		};

		photos.forEach( ( photo ) => {
			photo.addEventListener( 'click', ( event ) => {
				event.preventDefault();
				opener = photo;
				gallery = photos.filter( ( item ) => item.dataset.lightbox === photo.dataset.lightbox );
				show( gallery.indexOf( photo ) );
				dialog.showModal();
				document.body.style.overflow = 'hidden';
			} );
		} );

		prev.addEventListener( 'click', () => show( index - 1 ) );
		next.addEventListener( 'click', () => show( index + 1 ) );
		dialog.querySelector( '.lightbox__close' ).addEventListener( 'click', () => dialog.close() );

		dialog.addEventListener( 'click', ( event ) => {
			if ( event.target === dialog ) {
				dialog.close();
			}
		} );

		dialog.addEventListener( 'keydown', ( event ) => {
			if ( 'ArrowRight' === event.key ) {
				show( index + 1 );
			} else if ( 'ArrowLeft' === event.key ) {
				show( index - 1 );
			}
		} );

		dialog.addEventListener( 'close', () => {
			document.body.style.overflow = '';
			image.removeAttribute( 'src' );

			if ( opener ) {
				opener.focus();
			}
		} );
	}

	initHeader();
	initMarquees();
	initFilterTabs();
	initPanelTabs();
	initExplorer();
	initCounters();
	initVideo();
	initLightbox();
}() );

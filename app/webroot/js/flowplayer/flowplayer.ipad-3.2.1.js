/**
 * ipad.js 3.2.1. The Flowplayer API
 *
 * Copyright 2010 Flowplayer Oy
 * By Thomas Dubois <thomas@flowplayer.org>
 *
 * This file is part of Flowplayer.
 *
 * Flowplayer is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Flowplayer is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Flowplayer.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Date: 2010-08-25 17:40:08 +0000 (Wed, 25 Aug 2010)
 * Revision: 4219
 */


//$f.addPlugin("ipad", function(options) {
$f.addPlugin("ipad", function(options) {
	var STATE_UNLOADED = -1;
	var STATE_LOADED    = 0;
	var STATE_UNSTARTED = 1;
	var STATE_BUFFERING = 2;
	var STATE_PLAYING   = 3;
	var STATE_PAUSED    = 4;
	var STATE_ENDED     = 5;

	var self = this;

	var currentVolume = 1;
	var onStartFired = false;
	var stopping = false;
	var playAfterSeek = false;

	var activeIndex = 0;
	var activePlaylist = [];
	var clipDefaults = {
		accelerated: 	false,		// unused
		autoBuffering: 	false,
		autoPlay: 		true,
		baseUrl: 		null,
		bufferLength: 	3,			// unused
		connectionProvider: null,	// unused
		cuepointMultiplier: 1000,	// not yet implemented
		cuepoints: [],				// not yet implemented
		controls: {},				// unused
		duration: 0,				// not yet implemented
		extension: '',
		fadeInSpeed: 1000,			// not yet implemented
		fadeOutSpeed: 1000,			// not yet implemented
		image: false,				// unused
		linkUrl: null,				// not yet implemented
		linkWindow: '_self',		// not yet implemented
		live: false,				// unused
		metaData: {},
		originalUrl: null,
		position: 0,				// unused
		playlist: [],				// unused
		provider: 'http',
		scaling: 'scale',			// not yet implemented
		seekableOnBegin: false,		// unused
		start: 0,					// not yet implemented
		url: null,
		urlResolvers: []			// unused
	};

	var currentState = STATE_UNLOADED;
	var previousState= STATE_UNLOADED;

	var isiDevice = /iPad|iPhone|iPod/i.test(navigator.userAgent);

	var video = null;

	function extend(to, from, includeFuncs) {
		if (from) {
			for (key in from) {
				if (key) {
					if ( from[key] && typeof from[key] == "function" && ! includeFuncs )
						continue;
					if ( from[key] && typeof from[key] == "object" && from[key].length == undefined) {
						var cp = {};
						extend(cp, from[key]);
						to[key] = cp;
					} else {
						to[key] = from[key];
					}
				}
			}
		}
	}

	var opts = {
		simulateiDevice: false,
		controlsSizeRatio: 1.5,
		controls: true,
		debug: false
	};

	extend(opts, options);

	// some util funcs
	function log() {
		if ( opts.debug ) {
			if ( isiDevice ) {
				var str = [].splice.call(arguments,0).join(', ');
				console.log.apply(console, [str]);
			} else {
				console.log.apply(console, arguments);
			}
		}

	}

	function stateDescription(state) {
		switch(state) {
			case -1: return "UNLOADED";
			case  0: return "LOADED";
			case  1: return "UNSTARTED";
			case  2: return "BUFFERING";
			case  3: return "PLAYING";
			case  4: return "PAUSED";
			case  5: return "ENDED";
		}
		return "UNKOWN";
	}

	function actionAllowed(eventName) {
		var ret = $f.fireEvent(self.id(), "onBefore"+eventName, activeIndex);
		return ret !== false;
	}

	function stopEvent(e) {
		e.stopPropagation();
		e.preventDefault();
		return false;
	}

	function setState(state, force) {
		if ( currentState == STATE_UNLOADED && ! force )
			return;

		previousState = currentState;
		currentState = state;

		log(stateDescription(state));
	}

	function resetState() {
		video.fp_stop();

		onStartFired = false;
		stopping 	 = false;
		playAfterSeek= false;
		// call twice so previous state is unstarted too
		setState(STATE_UNSTARTED);
		setState(STATE_UNSTARTED);
	}

	function replay() {
		resetState();
		playAfterSeek = true;
		video.fp_seek(0);
	}

	function scaleVideo(clip) {

	}

	// internal func, maps flowplayer's API
	function addAPI() {


		function fixClip(clip) {
			var extendedClip = {};
			extend(extendedClip, clipDefaults);
			extend(extendedClip, self.getCommonClip());
			extend(extendedClip, clip);

			if ( extendedClip.ipadUrl )
				url = extendedClip.ipadUrl;
			else if ( extendedClip.url )
				url = extendedClip.url;

			if ( url && url.indexOf('://') == -1 && extendedClip.baseUrl )
				url = extendedClip.baseUrl + '/' + url;

			extendedClip.originalUrl = extendedClip.url;
			extendedClip.completeUrl = url;
			extendedClip.extension = extendedClip.completeUrl.substr(extendedClip.completeUrl.lastIndexOf('.'));
			extendedClip.type = 'video';

			// remove this
			delete extendedClip.index;

			log("fixed clip", extendedClip);

			return extendedClip;
		}

		video.fp_play = function(clip, inStream, /* private one, handy for playlists */ forcePlay) {
			var url = null;
			var autoBuffering 	 = true;
			var autoPlay 		 = true;

			log("Calling play() " + clip, clip);

			if ( inStream ) {
				log("ERROR: inStream clips not yet supported");
				return;
			}

			// we got a param :
			// array, index, clip obj, url
			if ( clip !== undefined ) {

				// simply change the index
				if ( typeof clip == "number" ) {
					if ( activeIndex >= activePlaylist.length )
						return;

					activeIndex = clip;
					clip = activePlaylist[activeIndex];
				} else {
					// String
					if ( typeof clip == "string" ) {
						clip = {
							url: clip
						};
					}

					// replace playlist
					video.fp_setPlaylist(clip.length !== undefined ? clip : [clip]);
				}

				clip = activePlaylist[activeIndex];
				url = clip.completeUrl;

				if ( clip.autoBuffering !== undefined && clip.autoBuffering === false )
					autoBuffering = false;

				if ( clip.autoPlay === undefined || clip.autoPlay === true || forcePlay === true ) {
					autoBuffering = true;
					autoPlay = true;
				} else {
					autoPlay = false;
				}
			} else {
				log("clip was not given, simply calling video.play, if not already buffering");

				// clip was not given, simply calling play
				if ( currentState != STATE_BUFFERING )
					video.play();

				return;
			}

			log("about to play "+ url, autoBuffering, autoPlay);

			// we have a new clip to play
			resetState();

			if ( url ) {
				log("Changing SRC attribute"+ url);
				video.setAttribute('src', url);
			}


			//return;

			// autoBuffering is true or we just called play
			if ( autoBuffering ) {
				if ( ! actionAllowed('Begin') )
					return false;

				$f.fireEvent(self.id(), 'onBegin', activeIndex);

				log("calling video.load()");
				video.load();
			}

			// auto
			if ( autoPlay ) {
				log("calling video.play()");
				video.play();
			}
		}

		video.fp_pause = function() {
			log("pause called");

			if ( ! actionAllowed('Pause') )
				return false;

			video.pause();
		};

		video.fp_resume = function() {
			log("resume called");

			if ( ! actionAllowed('Resume') )
				return false;

			video.play();
		};

		video.fp_stop = function() {
			log("stop called");

			if ( ! actionAllowed('Stop') )
				return false;

			stopping = true;
			video.pause();
			try {
				video.currentTime = 0;
			} catch(ignored) {}
		};

		video.fp_seek = function(position) {
			log("seek called "+ position);

			if ( ! actionAllowed('Seek') )
				return false;

			var seconds = 0;
			var position = position + "";
			if ( position.charAt(position.length-1) == '%' ) {
				var percentage = parseInt(position.substr(0, position.length-1)) / 100;
				var duration = video.duration;

				seconds = duration * percentage;
			} else {
				seconds = position;
			}

			try {
				video.currentTime = seconds;
			} catch(e) {
				log("Wrong seek time");
			}
		};

		video.fp_getTime = function() {
		//  log("getTime called");
			return video.currentTime;
		};

		video.fp_mute = function() {
			log("mute called");

			if ( ! actionAllowed('Mute') )
				return false;

			currentVolume = video.volume;
			video.volume = 0;
		};

		video.fp_unmute = function() {
			if ( ! actionAllowed('Unmute') )
				return false;

			video.volume = currentVolume;
		};

		video.fp_getVolume = function() {
			return video.volume * 100;
		};

		video.fp_setVolume = function(volume) {
			if ( ! actionAllowed('Volume') )
				return false;

			video.volume = volume / 100;
		};

		video.fp_toggle = function() {
			log('toggle called');
			if ( self.getState() == STATE_ENDED ) {
				replay();
				return;
			}

			if ( video.paused )
				video.fp_play();
			else
				video.fp_pause();
		};

		video.fp_isPaused = function() {
			return video.paused;
		};

		video.fp_isPlaying = function() {
			return ! video.paused;
		};

		video.fp_getPlugin = function(name) {
			if ( name == 'canvas' || name == 'controls' ) {
				var config = self.getConfig();
				//log("looking for config for "+ name, config);

				return config['plugins'] && config['plugins'][name] ? config['plugins'][name] : null;
			}
			log("ERROR: no support for "+ name +" plugin on iDevices");
			return null;
		};
		/*
		video.fp_css = function(name, css) {
			if ( self.plugins[name] && self.plugins[name]._api &&
				 self.plugins[name]['_api'] && self.plugins[name]['_api']['css'] &&
				 self.plugins[name]['_api']['css'] instanceof Function )
				return self.plugins[name]['_api']['css']();

			return self;
		}*/

		video.fp_close = function() {
			setState(STATE_UNLOADED);

			video.parentNode.removeChild(video);
			video = null;
		};

		video.fp_getStatus = function() {
			var bufferStart = 0;
			var bufferEnd   = 0;

			try {
				bufferStart = video.buffered.start();
				bufferEnd   = video.buffered.end();
			} catch(ignored) {}

			return {
				bufferStart: bufferStart,
				bufferEnd:  bufferEnd,
				state: currentState,
				time: video.fp_getTime(),
				muted: video.muted,
				volume: video.fp_getVolume()
			};
		};

		video.fp_getState = function() {
			return currentState;
		};

		video.fp_startBuffering = function() {
			if ( currentState == STATE_UNSTARTED )
				video.load();
		};

		video.fp_setPlaylist = function(playlist) {
			log("Setting playlist");
			activeIndex = 0;
			for ( var i = 0; i < playlist.length; i++ )
				playlist[i] = fixClip(playlist[i]);

			activePlaylist = playlist;

			// keep flowplayer.js in sync
			$f.fireEvent(self.id(), 'onPlaylistReplace', playlist);
		};

		video.fp_addClip = function(clip, index) {
			clip = fixClip(clip);
			activePlaylist.splice(index, 0, clip);

			// keep flowplayer.js in sync
			$f.fireEvent(self.id(), 'onClipAdd', clip, index);
		};

		video.fp_updateClip = function(clip, index) {
			extend(activePlaylist[index], clip);
			return activePlaylist[index];
		};

		video.fp_getVersion = function() {
			return '3.2.3';
		}

		video.fp_isFullscreen = function() {
			return false; //video.webkitDisplayingFullscreen;
		}

		video.fp_toggleFullscreen = function() {
			if ( video.fp_isFullscreen() )
				video.webkitExitFullscreen();
			else
				video.webkitEnterFullscreen();
		}

		// install all other core API with dummy function
		// core API methods
		$f.each(("toggleFullscreen,stopBuffering,reset,playFeed,setKeyboardShortcutsEnabled,isKeyboardShortcutsEnabled,addCuepoints,css,animate,showPlugin,hidePlugin,togglePlugin,fadeTo,invoke,loadPlugin").split(","),
			function() {
				var name = this;

				video["fp_"+name] = function() {
				log("ERROR: unsupported API on iDevices "+ name);
					return false;
				};
			}
		);
	}

	// Internal func, maps Flowplayer's events
	function addListeners() {


		// Volume*,Mute*,Unmute*,PlaylistReplace,ClipAdd,Error"
		// Begin*,Start,Pause*,Resume*,Seek*,Stop*,Finish*,LastSecond,Update,BufferStop

		/* CLIP EVENTS MAPPING */
		/*
		var onBegin = function(e) {
			// we are not getting that one on the device ?
			fireOnBeginIfNeeded(e);
		};
		video.addEventListener('loadstart', onBegin, false);


		*/

		var events = [	'abort',
						'canplay',
						'canplaythrough',
						'durationchange',
						'emptied',
						'ended',
						'error',
						'loadeddata',
						'loadedmetadata',
						'loadstart',
						'pause',
						'play',
						'playing',
						'progress',
						'ratechange',
						'seeked',
						'seeking',
						'stalled',
						'suspend',
						'timeupdate',
						'volumechange',
						'waiting'];
		var eventsLogger = function(e) {
			log("Got event "+ e.type, e);
		}

		for ( var i = 0; i < events.length; i++ )
			video.addEventListener(events[i], eventsLogger);



		var onBufferEmpty = function(e) {
			log("got onBufferEmpty event "+e.type)
			setState(STATE_BUFFERING);
			$f.fireEvent(self.id(), 'onBufferEmpty', activeIndex);
		};
		video.addEventListener('emptied', onBufferEmpty, false);
		video.addEventListener('waiting', onBufferEmpty, false);

		var onBufferFull = function(e) {
			if ( previousState == STATE_UNSTARTED || previousState == STATE_BUFFERING )	{
				// wait for play event, nothing to do

			} else {
				log("Restoring old state "+ stateDescription(previousState));
				setState(previousState);
			}
			$f.fireEvent(self.id(), 'onBufferFull', activeIndex);
		};
		video.addEventListener('canplay', onBufferFull, false);
		video.addEventListener('canplaythrough', onBufferFull, false);

		var onMetaData = function(e) {
			// update clip
			video.fp_updateClip({duration: video.duration, metaData: {duration: video.duration}}, activeIndex);
			activePlaylist[activeIndex].duration = video.duration;

			$f.fireEvent(self.id(), 'onMetaData', activeIndex, activePlaylist[activeIndex]);
		};
		video.addEventListener('loadedmetadata', onMetaData, false);
		video.addEventListener('durationchange', onMetaData, false);

		var onStart = function(e) {
			if ( currentState == STATE_PAUSED ) {
				if ( ! actionAllowed('Resume') ) {
					// user initiated resume
					log("Resume disallowed, pausing");
					video.fp_pause();
					return stopEvent(e);
				}

				$f.fireEvent(self.id(), 'onResume', activeIndex);
			}

			setState(STATE_PLAYING);

			if ( ! onStartFired ) {
				onStartFired = true;
				$f.fireEvent(self.id(), 'onStart', activeIndex);
			}
		};
		video.addEventListener('playing', onStart, false);

		var onFinish = function(e) {
			if ( ! actionAllowed('Finish') ) {
				if ( activePlaylist.length == 1 ) {
					//In the case of a single clip, the player will start from the beginning of the clip.
					log("Active playlist only has one clip, onBeforeFinish returned false. Replaying");
					replay();
				} else if ( activeIndex != (activePlaylist.length -1) ) {
					// In the case of an ordinary clip in a playlist, the "Play again" button will appear.
					// oops, we don't have any play again button yet :)
					// simply go to the beginning of the video
					log("Not the last clip in the playlist, but onBeforeFinish returned false. Returning to the beginning of current clip");
					video.fp_seek(0);
				} else {
					//In the case of the final clip in a playlist, the player will start from the beginning of the playlist.
					log("Last clip in playlist, but onBeforeFinish returned false, start again from the beginning");
					video.fp_play(0);
				}

				return stopEvent(e);
			}	// action was canceled

			setState(STATE_ENDED);
			$f.fireEvent(self.id(), 'onFinish', activeIndex);

			if ( activePlaylist.length > 1 && activeIndex < (activePlaylist.length - 1) ) {
				// not the last clip in the playlist
				log("Not last clip in the playlist, moving to next one");
				video.fp_play(++activeIndex, false, true);
			}

		};
		video.addEventListener('ended', onFinish, false);

		var onError = function(e) {
			setState(STATE_LOADED, true);
			$f.fireEvent(self.id(), 'onError', activeIndex, 201);
			if ( opts.onFail && opts.onFail instanceof Function )
				opts.onFail.apply(self, []);
		};
		video.addEventListener('error', onError, false);

		var onPause = function(e) {
			log("got pause event from player" + self.id());
			if ( stopping )
				return;

			if ( currentState == STATE_BUFFERING && previousState == STATE_UNSTARTED ) {
				log("forcing play");
				setTimeout(function() { video.play(); }, 0);
				return;// stopEvent(e);
			}

			if ( ! actionAllowed('Pause') ) {
				// user initiated pause
				video.fp_resume();
				return stopEvent(e);
			}

			setState(STATE_PAUSED);
			$f.fireEvent(self.id(), 'onPause', activeIndex);
		}
		video.addEventListener('pause', onPause, false);

		var onSeek = function(e) {
			$f.fireEvent(self.id(), 'onBeforeSeek', activeIndex);
		};
		video.addEventListener('seeking', onSeek, false);

		var onSeekDone = function(e) {
			if ( stopping ) {
				stopping = false;
				$f.fireEvent(self.id(), 'onStop', activeIndex);
			}
			else
				$f.fireEvent(self.id(), 'onSeek', activeIndex);


			log("seek done, currentState", stateDescription(currentState));

			if ( playAfterSeek ) {
				playAfterSeek = false;
				video.fp_play();
			} else if ( currentState != STATE_PLAYING )
				video.fp_pause();
		};
		video.addEventListener('seeked', onSeekDone, false);





		/* PLAYER EVENTS MAPPING */

		var onVolumeChange = function(e) {
			// add onBeforeQwe here
			$f.fireEvent(self.id(), 'onVolume', video.fp_getVolume());
		};
		video.addEventListener('volumechange', onVolumeChange, false);
	}

	// this is called only on iDevices
	function onPlayerLoaded() {
		video.fp_play(0);
		//installControlbar();
	}


	function installControlbar() {
		// if we're on an iDevice, try to load the js controlbar if needed
		/*
		if ( self['controls'] == undefined )
			return;	// js controlbar not loaded

		var controlsConf = {};
		if ( self.getConfig() && self.getConfig()['plugins'] && self.getConfig()['plugins']['controls'] )
			controlsConf = self.getConfig()['plugins']['controls'];

		var controlsRoot = document.createElement('div');

		// dynamically load js, css file according to swf url ?

		// something more smart here

		controlsRoot.style.position = "absolute";
		controlsRoot.style.bottom = 0;
		self.getParent().children[0].appendChild(controlsRoot);

		self.controls(controlsRoot, {heightRatio: opts.controlsSizeRatio  }, controlsConf);
		*/
	}




	// Here we are getting serious. If we're on an iDevice, we don't care about Flash embed.
	// replace it by ours so we can install a video html5 tag instead when FP's init will be called.
	if ( isiDevice || opts.simulateiDevice ) {

		if ( ! window.flashembed.__replaced ) {

			var realFlashembed = window.flashembed;
			window.flashembed = function(root, opts, conf) {
				// DON'T, I mean, DON'T use self here as we are in a global func

				if (typeof root == 'string') {
					root = document.getElementById(root.replace("#", ""));
				}

				// not found
				if (!root) { return; }

				var style = window.getComputedStyle(root, null);
				var width = parseInt(style.width);
				var height= parseInt(style.height);

				// clearing root
				while(root.firstChild)
					root.removeChild(root.firstChild);

				var container = document.createElement('div');
				var api = document.createElement('video');
				container.appendChild(api);
				root.appendChild(container);

				//var hasBuiltinControls = conf.config['plugins'] == undefined || (conf.config['plugins'] && conf.config['plugins']['controls'] && conf.config['plugins']['controls'] != null
				//						&& self['controls'] == undefined);	// we make a careful use of "self", as we're looking in the prototype

				// styling  container
				container.style.height = height+'px';
				container.style.width  = width+'px';
				container.style.display= 'block';
				container.style.position = 'relative';
				container.style.background = '-webkit-gradient(linear, left top, left bottom, from(rgba(0, 0, 0, 0.5)), to(rgba(0, 0, 0, 0.7)))';
				container.style.cursor = 'default';
				container.style.webkitUserDrag = 'none';

				// styling video tag
				api.style.height = '100%';
				api.style.width  = '100%';
				api.style.display= 'block';
				api.id = opts.id;
				api.name = opts.id;
				api.style.cursor = 'pointer';
				api.style.webkitUserDrag = 'none';

				api.type="video/mp4";
			//	if ( hasBuiltinControls )
			//		api.controls="controls";

				api.playerConfig = conf.config;

				// tell the player we are ready and go back to player's closure
				$f.fireEvent(conf.config.playerId, 'onLoad', 'player');

				//api.fp_play(conf.config.playlist);
			};

			flashembed.getVersion = realFlashembed.getVersion;
			flashembed.asString = realFlashembed.asString;
			flashembed.isSupported = function() {return true;}
			flashembed.__replaced = true;
		}


		// hack so we get the onload event before everybody and we can set the api
		var __fireEvent = self._fireEvent;
		// only on iDevice, of course

		self._fireEvent = function(a) {
			if ( a[0] == 'onLoad' && a[1] == 'player' ) {
				video = self.getParent().querySelector('video');

				if ( opts.controls )
					video.controls="controls";

				addAPI();
				addListeners();

				setState(STATE_LOADED, true);

				// set up first clip
				video.fp_setPlaylist(video.playerConfig.playlist);

				// we are loaded
				onPlayerLoaded();

				__fireEvent.apply(self, [a]);
			}


			var shouldFireEvent = currentState != STATE_UNLOADED;
			if ( currentState == STATE_UNLOADED && typeof a == 'string' )
				shouldFireEvent = true;

			if ( shouldFireEvent )
				return __fireEvent.apply(self, [a]);
		}

		// please, don't ask me why, but if you call video.clientHeight while the video is buffering
		// it will be stuck buffering
		self._swfHeight = function() {
			return parseInt(video.style.height);
		}

		self.hasiPadSupport = function() {
			return true;
		}
	} // end of iDevice test


	// some chaining
	return self;
});

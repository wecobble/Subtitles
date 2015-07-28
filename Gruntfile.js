/**
 * Plugin: Subtitles
 *
 * @see http://gruntjs.com/sample-gruntfile
 */
module.exports = function(grunt) {
	/**
	 * Output elapsed time for grunt tasks.
	 * @see https://www.npmjs.com/package/time-grunt
	 */
	require( 'time-grunt' )(grunt);

	/**
	 * Load tasks.
	 * @see https://www.npmjs.com/package/matchdep
	 */
	require( 'matchdep' ).filterDev(['grunt-*']).forEach( grunt.loadNpmTasks );

	// config
	grunt.initConfig( {
		// read in project settings
		pkg: grunt.file.readJSON( 'package.json' ),

		/**
		 * JSHint
		 * @see https://github.com/gruntjs/grunt-contrib-jshint
		 */
		jshint: {
			dev: {
				options: {
					curly: true
				},
				src: [
					'*.js',
					'**/*.js',
					'!node_modules/**'
				]
			}
		},

		/**
		 * Grunt Search
		 */
		search: {
			inlineStyles : {
				files: {
					src: ['*.php','**/*.php']
				},
				options: {
					searchString: /style\s?=\s?["']*/g,
					logFormat: 'console'
				}
			},
			short_tags: {
				files: {
					src: ['*.php','**/*.php']
				},
				options: {
					searchString: /(<\?[^p])|(<\?$)/,
					logFormat: 'console'
				}
			}
		},

		/**
		 * Theme and Plugin internationalization
		 * @see https://github.com/blazersix/grunt-wp-i18n/
		 */
		makepot: {
			target: {
				options: {
					domainPath: 'languages/',    // Where to save the POT file.
					mainFile: '<%= pkg.name %>.php',    // Main project file.
					potFilename: '<%= pkg.name %>.pot',    // Name of the POT file.
					potHeaders: {
						poedit: true,                 // Includes common Poedit headers.
						'x-poedit-keywordslist': true // Include a list of all possible gettext functions.
								},
					type: 'wp-plugin',    // Type of project (wp-plugin or wp-theme).
					updateTimestamp: true,    // Whether the POT-Creation-Date should be updated without other changes.
					updatePoFiles: true, // Whether to update PO files in the same directory as the POT file.
					processPot: function( pot, options ) {
						pot.headers['report-msgid-bugs-to'] = 'https://wordpress.org/plugins/subtitles/';
						pot.headers['last-translator'] = 'Philip Arthur Moore (http://philiparthurmoore.com/)';
						pot.headers['language-team'] = 'Philip Arthur Moore <philip@pressbuild.com>';
						pot.headers['language'] = 'en_US';
						var translation, // Exclude meta data from pot.
							excluded_meta = [
								'Plugin Name of the plugin/theme',
								'Plugin URI of the plugin/theme',
								'Author of the plugin/theme',
								'Author URI of the plugin/theme'
								];
									for ( translation in pot.translations[''] ) {
										if ( 'undefined' !== typeof pot.translations[''][ translation ].comments.extracted ) {
											if ( excluded_meta.indexOf( pot.translations[''][ translation ].comments.extracted ) >= 0 ) {
												console.log( 'Excluded meta: ' + pot.translations[''][ translation ].comments.extracted );
													delete pot.translations[''][ translation ];
												}
											}
										}
						return pot;
					}
				}
			}
		},

		dirs: {
			lang: 'languages',
		},

		potomo: {
			dist: {
				options: {
					poDel: false
				},
				files: [{
				 expand: true,
				 cwd: '<%= dirs.lang %>',
				src: ['*.po'],
				dest: '<%= dirs.lang %>',
				 ext: '.mo',
				nonull: true
			}]
		}
	},

		/**
		 * Theme Release via git-archive
		 * @see https://www.npmjs.com/package/git-archive
		 */
		'git-archive': {
			archive: {
				options: {
					'output': 'releases/<%= pkg.name %>-<%= pkg.version %>.zip',
					'tree-ish': 'master',
					'worktree-attributes': true,
					'extra': 9
				}
			}
		}
	} );

	// register tasks
	grunt.registerTask( 'dev', ['jshint:dev','search'] ); // default development tasks
	grunt.registerTask( 'build', ['makepot','potomo'] ); // default build tasks
	grunt.registerTask( 'release', ['git-archive'] ); // tasks to run when we're ready for a new release
	// default task
	grunt.registerTask( 'default', ['build'] );
};
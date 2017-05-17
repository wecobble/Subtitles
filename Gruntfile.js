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
	require( 'matchdep' ).filterDev( ['grunt-*'] ).forEach( grunt.loadNpmTasks );

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
					domainPath: '/languages',
					potFilename: '<%= pkg.name %>-en_US.pot',
					type: 'wp-plugin'
				}
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
	grunt.registerTask( 'build', ['makepot'] ); // default build tasks
	grunt.registerTask( 'release', ['git-archive'] ); // tasks to run when we're ready for a new release
	// default task
	grunt.registerTask( 'default', ['build'] );
};

module.exports = function (grunt) {
    var configBridge = grunt.file.readJSON('./bower_components/bootstrap/grunt/configBridge.json', {encoding: 'utf8'});

    grunt.initConfig({
        pkg: grunt.file.readJSON('bower_components/bootstrap/package.json'),
        path: 'bower_components/bootstrap',
        banner: '/*!\n' +
        ' * Bootstrap v<%= pkg.version %> (<%= pkg.homepage %>)\n' +
        ' * Copyright 2011-<%= grunt.template.today("yyyy") %> <%= pkg.author %>\n' +
        ' * Licensed under <%= pkg.license.type %> (<%= pkg.license.url %>)\n' +
        ' */\n',

        clean: {
            css: ['app/assets/css/*.css', 'app/assets/css/*.map', '!app/assets/css/*.less'],
            js: ['app/assets/js', 'public/js']
        },

        less: {
            compileCore: {
                options: {
                    strictMath: true,
                    sourceMap: true,
                    outputSourceFiles: true,
                    sourceMapURL: 'bootstrap.css.map',
                    sourceMapFilename: 'app/assets/css/bootstrap.css.map',
                    paths: '<%= path %>/less'
                },
                src: 'app/assets/css/custombootstrap.less',
                dest: 'app/assets/css/bootstrap.css'
            }
        },

        autoprefixer: {
            options: {
                browsers: configBridge.config.autoprefixerBrowsers
            },
            core: {
                options: {
                    map: true
                },
                src: 'app/assets/css/bootstrap.css'
            }
        },

        cssmin: {
            options: {
                compatibility: 'ie8',
                keepSpecialComments: '*',
                noAdvanced: true
            },
            minifyCore: {
                src: 'app/assets/css/bootstrap.css',
                dest: 'public/css/bootstrap.min.css'
            }
        },

        usebanner: {
            options: {
                position: 'top',
                banner: '<%= banner %>'
            },
            files: {
                src: 'public/css/*.css'
            }
        },

        concat: {
            options: {
                separator: ';'
            },
            release: {
                src: [
                    'bower_components/jquery/dist/jquery.js',
                    'bower_components/bootstrap/dist/js/bootstrap.js'
                ],
                dest: 'app/assets/js/webcfinder.js'
            }
        },

        uglify: {
            all: {
                files: {
                    'public/js/webcfinder.min.js': ['app/assets/js/webcfinder.js']
                }
            }
        },

        watch: {
            files: ['app/assets/css/custombootstrap.less'],
            tasks: ['dist-css']
        }
    });

    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-less');

    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');

    grunt.loadNpmTasks('grunt-contrib-watch');

    // CSS distribution task
    grunt.registerTask('dist-css', ['clean:css', 'less:compileCore', 'autoprefixer', 'usebanner', 'cssmin']);

    // JS distribution task
    grunt.registerTask('dist-js', ['clean:js', 'concat:release', 'uglify:all'])
};

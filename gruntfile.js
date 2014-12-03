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
            css: ['app/assets/css/*.css', 'app/assets/css/*.map', '!app/assets/css/*.less']
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
                dest: 'app/assets/css/bootstrap.min.css'
            }
        },

        usebanner: {
            options: {
                position: 'top',
                banner: '<%= banner %>'
            },
            files: {
                src: 'app/assets/css/*.css'
            }
        }
    });

    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-banner');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-less');

    // CSS distribution task.
    grunt.registerTask('dist-css', ['clean:css', 'less:compileCore', 'autoprefixer', 'usebanner', 'cssmin']);
};

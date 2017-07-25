# WP Qualitycontrol

With complex themes it is often impossible to try every single combination
of settings and fields provided in post-types. 

WP Qualitycontrol creates many fuzzed posts with minimal configuration. After
generating semi-realistic content the created posts are tested to see if they
produce a valid webpage.

# Setup

## 1. Define WP Qualitycontrol as a dependency

`composer require niels-de-blaauw/wp-qualitycontrol`

The tool will load automatically.

## 2. Run the generate command

Inside WordPress install, use the generate command:

`wp qualitycontrol generate --prompt`

# Notes

- The generate command will clean generated posts before and after running 
the command, unless you tell it not to.
- If the testing sequence is enabled and fails, cleaning the generated posts
is skipped so you can debug with the generated content that failed.
- You can manually clean generated posts with `wp qualitycontrol clean`.
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


# Customisation

It is possible to overwrite and extend the field processing with custom
classes. This method can be used to add non-standard ACF field types, or
modify output generation when the default does not fit the project.

## Filters
There are three filters to hijack the field object.

### 1. Field name

```
add_filter('ndb/qualitycontrol/field_name=post_option_name', function($returnValue, $field, $post_type){
	return new CustomObject($field, $post_type);
}, 10, 3);
```

### 2. Field key

```
add_filter('ndb/qualitycontrol/field_key=field_597c8fd614621', function($returnValue, $field, $post_type){
  return new CustomObject($field, $post_type);
}, 10, 3);
```

### 3. Field type

```
add_filter('ndb/qualitycontrol/field_type=color_picker', function($returnValue, $field, $post_type){
	return new CustomObject($field, $post_type);
}, 10, 3);
```

## Return value

Returning `false` skips to the next filter. Otherwise you must return
an object implementing `\NDB\QualityControl\FieldTypes\iFieldType`.

# Notes

- The generate command will clean generated posts before and after running
the command, unless you tell it not to.
- If the testing sequence is enabled and fails, cleaning the generated posts
is skipped so you can debug with the generated content that failed.
- You can manually clean generated posts with `wp qualitycontrol clean`.

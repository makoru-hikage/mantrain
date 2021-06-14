# mantrain v1.0.0

A novel implementation of a middleware for associative arrays with the extensive use of method chains.

## Usage Summary
```php
<?php

...
$input = $request->getParsedBody();
// $input = [
//     "username" => "cmoran",
//     "post_title" => "Sample Title",
//     "post_content" => "Long long sample content."
// ];

$mantrain = new \SampleLib\Validator($input, $rules);

$mantrain = $mantrain
  ->run('\SampleLib\Authorization', [$authAdapter])
  ->run('\SampleLib\PostCreation', [$repository])
  ->run();
 
$responseBody = $mantrain->getData();
$statusCode = $mantrain->getCode();
  

// run() will need a class's name and its additional constructor arguments if applicable

// All the supplied classes are descendants of Module class.

```
The method chain goes in like this:
- 1.0. `$mantrain`'s first class is initiated, it is a child of `\DeltaX\Mantrain\Module`
- 2.0. `$mantrain` runs its own `process()` method then initiates `\SampleLib\Authorization`, also a `Module`
- 3.0. `$mantrain` is now running `\SampleLib\Authorization`'s `process()` then initiates `\SampleLib\PostCreation` which is also a `Module`
- 4.0. `$mantrain` is now running `\SampleLib\PostCreation`'s `process()` 
- 5.0. You may now extract the final `data` and `code`

Note: Should a running `Module` have a non-zero `$code`, it'll just return itself over and over again.

The example chain portrays a situation where an HTTP REQUEST is received and the body is parsed. The input is then validated, then authorized, then used to insert a database record.

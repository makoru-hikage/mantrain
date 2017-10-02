# mantrain

A novel implementation of a middleware for associative arrays with the extensive use of method chains.

## Usage Summary
```
<?php

...

$input = [
    "username" => "cmoran",
    "post_title" => "Sample Title",
    "post_content" => "Long long sample content."
];
$mantrain = new HandlerInitiator($input);

$responseBody = $mantrain
  ->setHandler('\SampleLib\Validator')
  ->setHandlerArguments($rules)
  ->run()
  ->setHandler('\SampleLib\Authorization')
  ->setHandlerArguments($authAdapter)
  ->set('setContext', $context)
  ->set('setAuthorizedRoles', $roles)
  ->run()
  ->setHandler('\SampleLib\PostCreation')
  ->setHandlerArguments($repository)
  ->run();
  
// setHandler() prepares the Handler class to be instantiated
// setHandlerArguments() prepares the arguments for the instantiation
// set() is for accessing the instantiated Handler's properties
// run() processes the input data the passed through the Handler

```

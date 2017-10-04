# mantrain

A novel implementation of a middleware for associative arrays with the extensive use of method chains.

## Usage Summary
```
<?php

...
$input = $request->getParsedBody();
// $input = [
//     "username" => "cmoran",
//     "post_title" => "Sample Title",
//     "post_content" => "Long long sample content."
// ];

$mantrain = new HandlerInitiator($input);

$mantrain = $mantrain
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
 
$responseBody = $mantrain->getData();
$statusCode = $mantrain->getCode();
  
// setHandler() prepares the Handler class to be instantiated
// setHandlerArguments() prepares the arguments for the instantiation
// set() is for accessing the instantiated Handler's properties
// run() processes the input data the passed through the Handler

// All the supplied classes are descendants of Handler class.
// Every run() method returns a new HandlerInitiator object;

```
The method chain goes in like this:
- 1.0. `$mantrain` prepares handler class
- 2.0. `$mantrain` prepares handler arguments
- 3.0. `$mantrain` does the following
    - 3.1. `$mantrain` initiates the class and supply the set arguments 
    - 3.2. `$mantrain` sets the input for the handler
    - 3.3. The handler's methods are accessed to set its properties
    - 3.4. The handler is run. It's `$outputData` and `$code` are set within the overriden `process()` method and passed onto the next `HandlerInitiator`
- 4.0. Should this `HandlerInitiator` be given a non-zero `$code`, it'll call all the methods, but all of them will just return `$this` and do nothing else. Otherwise, the rest will continue working well as long as `HandlerInitiator`'s 2nd argument is `0`.
- 5.0. When you call `getData()`, the `$data` that was supplied with a non-zero `$code` will be returned despite the long chain.

The example chain portrays a situation where an HTTP REQUEST is received and the bodys is parsed. The input is then validated, then authorzied, then used to insert a database record.

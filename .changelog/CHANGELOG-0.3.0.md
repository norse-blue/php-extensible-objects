# Release 0.3.0

Consistency changes, stricter code usage enforcing and refactoring to split responsibilities.

## Added

- On the dev side of things we now include [PHP Insights](https://github.com/nunomaduro/phpinsights) to get some
valuable insights about the code.

## Changed

- Although discouraged by some people, we like the Exception suffix for custom exception classes. A missing `Exception`
suffix in the class name for class `NorseBlue\ExtensibleObjects\Exceptions\ClassNotExtensionMethod` was added to be
consistent with the naming conventions.
- We really like type hints were they are helpful, so que added a `declare(strict_types=1);` on all of our files. This
could potentially be a BC change, so please test your code thoroughly.
- We declared some classes and methods as final now, as they are not supposed to be modified or extended. Some of the
reasoning behind these changes is fantastically explained by [Brent](https://twitter.com/brendt_gd) in his blog post
titled [SOLID, interfaces and final - Rant With Brent 01](https://stitcher.io/blog/solid-interfaces-and-final-rant-with-brent).
If you were relying on extending or overloading these methods or classes your code could break. (Commit #8ee6a5)
- The core trait has been given some love. We split the responsibility of verifying and loading an extension method to
its own class `NorseBlue\ExtensibleObjects\ExtensionMethodLoader`, now the
`NorseBlue\ExtensionObjects\Tratis\HandlesExtensionMethods` is much leaner.

## Fixed

- With the changes made in a recent release to also include the parent extension methods in child classes, we forgot to
also update the contract (which could potentially break havoc, though minimized if using the included trait, if the
needed methods were not available in your class). We are sometimes forgetful, but now it is corrected: the
`NorseBlue\ExtensibleObjects\Contracts\Extensible` now has the proper methods declared. (Commit #4eae29)

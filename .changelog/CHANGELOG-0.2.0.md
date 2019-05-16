# Release 0.2.0

Make child objects inherit parent's extension methods.

## Changed

- `NorseBlue\ExtensibleObjects\Traits\HandlesExtensionMethod` trait now takes into account if the object is extending
a class that implements the `NorseBlue\ExtensibleObjects\Contracts\Extensible` contract and has registered extension
methods and makes them available to the child class ([#1](https://github.com/norse-blue/php-extensible-objects/pull/1)).
- Improved the messages of the exception being thrown, they now include the extension that could not be registered.
- Improved the [README](../README.md) file.

---

Previous: [Release 0.1.0](CHANGELOG-0.1.0.md)

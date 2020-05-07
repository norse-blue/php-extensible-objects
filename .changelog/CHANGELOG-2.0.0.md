# Release 2.0.0

We've taken out some responsibilities of this package and placed them into its own package to lighten it up.

## Changed :slot_machine:

- The `Creatable` way of instancing an object takes again precedence over the direct constructor usage. This shouldn't be a problem because underneath the `create` method handles the constructor call correctly for us.

## Removed :fire:

- The `Creatable` contract was moved to its own package [norse-blue/creatable-objects](https://github.com/norse-blue/php-creatable-objects). :boom: This is a BC change for code depending on this package.
- The `ClassConstructorAccessibleResolver` class is no longer needed.

---
Previous: [Release 1.0.0](CHANGELOG-1.0.0.md)

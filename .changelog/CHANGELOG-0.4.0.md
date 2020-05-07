# Release 0.4.0

New features and small improvements.

## Added

- The extension methods can now be guarded (using the new `protected static $guarded_extension` property)
 to prevent re-registering or unregistering them once they are registered. The declaration of this new
 property is optional in your classes, but if defined you can enlist your guarded extension methods and
 the trait will take this into account when registering/unregistering them. (Commit #37adf0)

## Changed

- The `registerExtensionMethod` function allows now to be given an array of names to register the
 callable to all of them (kind of like aliases). The extension methods are treated as a normal
  extension method registry, so _aliases_ are not dependant on each other. (Commit #33f410)
- The `unregisterExtensionMethod` now also accepts an array of names to unregister multiple
 extension methods in batch. (Commit #33f410)

---
Previous: [Release 0.3.0](CHANGELOG-0.3.0.md)

# Release 0.4.1

Small improvements

## Added

- A `Creatable` interface to handle extensible objects with private/protected constructors. Now the extensions can be registered to them.

## Changed

- The `static::$extensions` declaration in each of the extensible classes is not needed anymore.  
  **Note:** You are still encouraged to add the static property to each of your child classes, as this allows them to be handled in their own static property instead of a shared one.

---
Previous: [Release 0.4.0](CHANGELOG-0.4.0.md)

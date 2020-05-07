# Release 1.0.0

First stable release.

## Added :sparkles:

- Static extension methods are now allowed and auto discoverable. (a0885c8a313ab9ca23856cc335d93bb625457dc9|150abedc4a67f444a21af71989b0ecb2723ad0cb)

## Changed :slot_machine:

- The `$guard` param of the `registerExtensionMethod` from the `Extensible` contract can now be bypassed. (61b3bff3d11bfefd2286ecc1724200f73f86aaae)
- The constructor of the extension method now takes precedence over the `Creatable` contract instantiation. (c91e78821330e563dedbbe40f4ef9c23a2b389d0)
- Some methods are now marked as final. (f28462732a382b8461283d0dfb685ab816e23123) 
- The number of defined constructor params is now resolved to use the meta-data as needed. (e38dc5abcb041f021111eaadecc0bd0d079eadc5)
- The `ExtensibleObject` class is now marked as `abstract`). (165c74ad3c86e9e4bd2af27ea705c83b2799c8a5)

---
Previous: [Release 0.5.1](CHANGELOG-0.5.1.md)

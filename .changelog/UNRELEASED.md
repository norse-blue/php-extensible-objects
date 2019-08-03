# Unreleased

## Added :sparkles:

- Static extension methods are now allowed and auto discoverable. (a0885c8a313ab9ca23856cc335d93bb625457dc9|150abedc4a67f444a21af71989b0ecb2723ad0cb)


## Changed :slot_machine:

- The `$guard` param of the `registerExtensionMethod` from the `Extensible` contract can now be bypassed. (61b3bff3d11bfefd2286ecc1724200f73f86aaae)
- The constructor of the extension method now takes precedence over the `Creatable` contract instantiation. (c91e78821330e563dedbbe40f4ef9c23a2b389d0)
- Some methods are now marked as final. (f28462732a382b8461283d0dfb685ab816e23123) 
- The number of defined constructor params is now resolved so that those number of params are used when creating the extension method. 

## Deprecated :dart:



## Removed :fire:



## Fixed :bug:



## Security :lock:



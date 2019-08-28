---
layout: default
title: Exceptions
nav_order: 4
permalink: /exceptions
---

# Exceptions
{: .no_toc }

## Table of contents
{: .no_toc .text-delta }

1. TOC
{:toc}

---

The following is a list of the different exceptions that are used within this package.

## ClassNotExtensionMethodException

Exception thrown when trying to register an extension method that does not implement the ExtensionMethod contract.

## ExtensionGuardedException

Exception thrown when trying to unregister or replace a guarded extension method.

## ExtensionNotCallableException

Exception thrown when trying to register an extension method that is not callable.

## ExtensionNotFoundException

Exception thrown when trying to get a nonexistent extension from the extension collection.

## MethodDefinedInClassException

Exception thrown when trying to register an extension method with the same name as a defined class method.

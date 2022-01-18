# Upgrade Guide

## Version 1 to 2
***

Version 2 adjusts classes to be compatible with version 3 of
[Tatter\Handlers](https://github.com/tattersoftware/codeigniter4-handlers). If you have
created any custom thumbnail handlers be sure to read the
[Upgrade Guide](https://github.com/tattersoftware/codeigniter4-handlers/blob/develop/UPGRADING.md)
for that library as well.

* Thumbnail handlers have been dubbed "Thumbnailers" to differentiate them from the library and service; adjust any class extensions appropriately
* The interface method has been renamed `process()` to differentiate it from the service method
* The service and interface method now both return the string path to the new file; failed attempts should throw some version of `RuntimeException`
